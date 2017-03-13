<?php
error_reporting(E_ALL);

require "simple_html_dom.php";
require "connect_mysql.php";

//$grab_type = 'all';
$grab_type='new';

$column = ["gov", "id", "plan_name", "report_name", "main_file", "other_file", "report_date", "report_page", "office", "member_name", "member_office", "member_unit", "member_job", "member_level", "member_num", "start_date", "end_date", "area", "visit", "type", "keyword", "note", "topic_cat", "adm_cat", "summary"];

//取得當前要查詢的城市和頁碼
$sql    = "SELECT info.*,gov.* FROM info INNER JOIN gov ON info.cat=gov.number WHERE info.type='local' ";
$result = $db->query($sql);
while ($row = $result->fetch()) {
    $gov         = $row["name"];
    $city_number = $row["number"];
    $link        = $row["link"];
    if ($grab_type == 'all') {
        $page_num = $row["page_num"];
        $page_num++;
    } else if ($grab_type == 'new') {
        $page_num = 1;
        page_update();
    }
}

//從列表抓取連結
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $link . "/OpenFront/report/report_result.jsp");
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_REFERER, $link . "/OpenFront/report/report_result.jsp");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array("pageNo" => $page_num, "searchAdv" => "查詢", "reportRadio" => "1", "sortField" => "startDate", "qSysId" => "C")));
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2490.86 Safari/537.36");
$curl_scraped_page = curl_exec($ch);

$html = new simple_html_dom();
$html->load($curl_scraped_page, true, false);

//取得該城市總頁數
$maxpage = explode("/", $html->find(".HL", 1)->plaintext)[1];
$maxpage = intval($maxpage);

//prepare
$sql    = "INSERT IGNORE INTO report (gov,id, plan_name, report_name, main_file, other_file, report_date, report_page, office, member_name, member_office, member_unit, member_job, member_level, member_num, start_date, end_date, area, visit, type, keyword, note, topic_cat, adm_cat, summary) VALUES (:gov,:id, :plan_name, :report_name, :main_file, :other_file, :report_date, :report_page, :office, :member_name, :member_office, :member_unit, :member_job, :member_level, :member_num, :start_date, :end_date, :area, :visit, :type, :keyword, :note, :topic_cat, :adm_cat, :summary)";
$result = $db->prepare($sql);

//遍歷id
$list = $html->find(".report_list", 0)->find("tr");
$i    = 0;
foreach ($list as $tr) {
    //跳過第一個tr(標頭)
    if ($i == 0) {
        $i++;
        continue;
    }
    $id = $tr->find("td", 2)->plaintext;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $link . "/OpenFront/report/report_detail.jsp?sysId=" . $id);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2490.86 Safari/537.36");
    $curl_scraped_page = curl_exec($ch);
    //取得頁面網址
    $html = new simple_html_dom();
    $html->load($curl_scraped_page, true, false);

    $main = $html->find(".report_DATAtable", 0);
    //還沒好的不抓取
    if ($main->children(19) == null) {
        continue;
    }
    //各項資料
    $plan_name = $main->children(2)->find("td", 0)->plaintext;

    $report_name = $main->children(3)->find("td", 0)->plaintext;

    $main_file = [];
    foreach ($main->children(4)->find("td", 0)->find("a") as $key) {
        $main_file[count($main_file)] = $link . "/OpenFront/report/" . $key->href;
    }
    $main_file = implode(",", $main_file);

    $other_file = [];
    foreach ($main->children(5)->find("td", 0)->find("a") as $key) {
        $other_file[count($other_file)] = $key->href;
    }
    $other_file = implode(",", $other_file);

    $report_date = intval(str_replace(["民國", "年", "月", "日"], "", $main->children(14)->find("td", 0)->plaintext)) + 19110000;

    $report_page = $main->children(18)->find("td", 0)->plaintext;

    $office = $main->children(6)->find("td", 0)->plaintext;
    //人員資料那區
    $memberdata    = $main->children(8)->find("td", 0)->find("tr");
    $a             = 0;
    $member_name   = [];
    $member_office = [];
    $member_unit   = [];
    $member_job    = [];
    $member_level  = [];
    foreach ($memberdata as $memberdata) {
        if ($a = 0) {
            $a++;
            continue;
        }
        array_push($member_name, preg_replace("/<(.*)>/U", "", $memberdata->find("td", 0)));
        array_push($member_office, preg_replace("/<(.*)>/U", "", $memberdata->find("td", 1)));
        array_push($member_unit, preg_replace("/<(.*)>/U", "", $memberdata->find("td", 2)));
        array_push($member_job, preg_replace("/<(.*)>/U", "", $memberdata->find("td", 3)));
        array_push($member_level, preg_replace("/<(.*)>/U", "", $memberdata->find("td", 4)));
    }
    //array_push會多一個[0]，要去掉
    unset($member_name[0], $member_office[0], $member_unit[0], $member_job[0], $member_level[0]);
    $member_num  = count($member_name);
    $member_name = implode(",", $member_name);

    $member_office = implode(",", $member_office);

    $member_unit = implode(",", $member_unit);

    $member_job = implode(",", $member_job);

    $member_level = implode(",", $member_level);

    $date       = explode("&nbsp;至&nbsp;", $main->children(12)->find("td", 0)->plaintext);
    $start_date = intval(str_replace(" ", 0, str_replace(["民國", "年", "月", "日"], "", $date[0]))) + 19110000;
    $end_date   = intval(str_replace(" ", 0, str_replace(["民國", "年", "月", "日"], "", $date[1]))) + 19110000;

    $area = $main->children(9)->find("td", 0)->plaintext;
    $area = str_replace("&nbsp;", "", $area);
    $area = str_replace(["，", "、", ";"], ",", $area);
    $area = preg_replace('/\s/', "", $area);
    $area = explode(",", $area);
    $area = array_diff($area, [""]);
    $area = implode(",", $area);

    $visit = $main->children(10)->find("td", 0)->plaintext;
    $visit = str_replace("&nbsp;", "", $visit);
    $visit = str_replace(["，", "、", ";"], ",", $visit);

    $type = $main->children(11)->find("td", 0)->plaintext;

    $keyword = $main->children(17)->find("td", 0)->plaintext;
    $keyword = str_replace("&nbsp;", "", $keyword);
    $keyword = str_replace(["，", "、", ";"], ",", $keyword);

    $note = $main->children(13)->find("td", 0)->plaintext;

    $topic_cat = $main->children(15)->find("td", 0)->plaintext;

    $adm_cat = $main->children(16)->find("td", 0)->plaintext;

    $summary = $main->children(19)->find("td", 0)->plaintext;

    foreach ($column as $key) {
        $$key = str_replace(["&nbsp;", " "], "", $$key);
        $result->bindValue(":$key", $$key);
    }
    //更新報告資料
    $result->execute();
}

if ($grab_type == 'all') {
    page_update();
}

function page_update()
{
    global $grab_type,$page_num,$maxpage,$city_number,$db;
    if ($grab_type == 'all') {
        if ($page_num >= $maxpage) {
            $page_num = 1;
            $city_number++;
        }
    } elseif ($grab_type == 'new') {
        $city_number++;
    }

    //所有城市的跑完後，回第一個城市繼續跑
    $result = $db->query("SELECT count(*) FROM gov");
    $row    = $result->fetch();
    if ($city_number >= $row[0]) {
        $city_number = 0;
    }

    $db->exec("UPDATE info SET page_num='$page_num',maxpage='$maxpage',cat='$city_number' WHERE type='local' ");
}