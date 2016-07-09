<?php
error_reporting(E_ALL);

require "simple_html_dom.php";
require "connect_mysql.php";

$column = ["gov", "id", "plan_name", "report_name", "main_file", "other_file", "report_date", "report_page", "office", "member_name", "member_office", "member_unit", "member_job", "member_level", "member_num", "start_date", "end_date", "area", "visit", "type", "keyword", "note", "topic_cat", "adm_cat", "summary"];
$gov    = '中央政府';

//獲取cookie
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://report.nat.gov.tw/ReportFront/rpt_search_group.jspx");
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, "cookie.txt");
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array("action" => "search", "sort" => "4", "searchType" => "quick")));
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2490.86 Safari/537.36");
curl_exec($ch);
/*
//查詢過的頁碼
$sql = "SELECT * FROM info WHERE type='center'";
$result = $db->query($sql);
while ($row = $result->fetch()) {
$page_num = $row[0];
}
 */

//$page_num++;
$page_num = 1;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://report.nat.gov.tw/ReportFront/rpt_search.jspx?orgType=1");
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_REFERER, "http://report.nat.gov.tw/ReportFront/rpt_search.jspx");
curl_setopt($ch, CURLOPT_COOKIEFILE, "cookie.txt");
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array("action" => "refresh", "sort" => "4", "page_size" => "40", "page" => $page_num)));
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2490.86 Safari/537.36");
$curl_scraped_page = curl_exec($ch);
$html              = new simple_html_dom();
$html->load($curl_scraped_page, true, false);
//總頁數
$maxpage = explode("/", $html->find(".page", 0)->find("span", 0)->find("em", 1)->plaintext)[1];
$maxpage = intval($maxpage);

//prepare
$sql    = "INSERT IGNORE INTO report (gov,id, plan_name, report_name, main_file, other_file, report_date, report_page, office, member_name, member_office, member_unit, member_job, member_level, member_num, start_date, end_date, area, visit, type, keyword, note, topic_cat, adm_cat, summary) VALUES (:gov,:id, :plan_name, :report_name, :main_file, :other_file, :report_date, :report_page, :office, :member_name, :member_office, :member_unit, :member_job, :member_level, :member_num, :start_date, :end_date, :area, :visit, :type, :keyword, :note, :topic_cat, :adm_cat, :summary)";
$result = $db->prepare($sql);

//遍歷id
$list = $html->find(".altrow");
foreach ($list as $tr) {
    $id = str_replace("report_detail.jspx?sysId=", "", $tr->find("td", 2)->find("a", 0)->href);

    $html = file_get_html("http://report.nat.gov.tw/ReportFront/report_detail.jspx?sysId=" . $id);
    if (preg_match("/sysid/", $html->find("p", 0)->plaintext)) {
        continue;
    }
    $main = $html->find(".cp .body")[0];

    $plan_name = $main->find("table", 0)->find("tr", 1)->find("td", 0)->plaintext;

    $report_name = $main->find("table", 0)->find("tr", 2)->find("td", 0)->plaintext;

    $main_file = [];
    foreach ($main->find("table", 0)->find("tr", 3)->find("td", 0)->find("a") as $key) {
        $main_file[count($main_file)] = "http://report.nat.gov.tw/ReportFront/" . $key->href;
    }
    $main_file = implode(",", $main_file);

    $other_file = [];
    foreach ($main->find("table", 0)->find("tr", 4)->find("td", 0)->find("a") as $key) {
        $other_file[count($other_file)] = $key->href;
    }
    $other_file = implode(",", $other_file);

    $report_date = intval(str_replace("/", "", $main->find("table", 0)->find("tr", 5)->find("td", 0)->plaintext)) + 19110000;

    $report_page = $main->find("table", 0)->find("tr", 6)->find("td", 0)->plaintext;

    $office = preg_replace("/http(.*)/", "", $main->find("table", 1)->find("tr", 0)->find("td", 0)->plaintext);

    $memberdata    = $main->find("table", 1)->find("tr", 1)->find("td", 0)->find("tr");
    $i             = 0;
    $member_name   = [];
    $member_office = [];
    $member_unit   = [];
    $member_job    = [];
    $member_level  = [];
    foreach ($memberdata as $memberdata) {
        if ($i = 0) {
            $i++;
            continue;
        }
        array_push($member_name, preg_replace("/<(.*)>/U", "", $memberdata->find("td", 0)));
        array_push($member_office, preg_replace("/<(.*)>/U", "", $memberdata->find("td", 1)));
        array_push($member_unit, preg_replace("/<(.*)>/U", "", $memberdata->find("td", 2)));
        array_push($member_job, preg_replace("/<(.*)>/U", "", $memberdata->find("td", 3)));
        array_push($member_level, preg_replace("/<(.*)>/U", "", $memberdata->find("td", 4)));
    }
    unset($member_name[0], $member_office[0], $member_unit[0], $member_job[0], $member_level[0]);
    $member_num  = count($member_name);
    $member_name = implode(",", $member_name);

    $member_office = implode(",", $member_office);

    $member_unit = implode(",", $member_unit);

    $member_job = implode(",", $member_job);

    $member_level = implode(",", $member_level);

    $date       = explode(" 至 ", $main->find("table", 1)->children(2)->find("td", 0)->plaintext);
    $start_date = intval(str_replace("/", "", $date[0])) + 19110000;
    $end_date   = intval(str_replace("/", "", $date[1])) + 19110000;

    $area = $main->find("table", 3)->find("tr", 0)->find("td", 0)->plaintext;
    $area = str_replace(["，", "、", ";"], ",", $area);
    $area = str_replace(" ", "", $area);
    $area = explode(",", $area);
    $area = array_diff($area, [""]);
    $area = implode(",", $area);

    $visit = $main->find("table", 3)->find("tr", 1)->find("td", 0)->plaintext;
    $visit = str_replace(["，", "、", ";"], ",", $visit);

    $type = $main->find("table", 3)->find("tr", 2)->find("td", 0)->plaintext;

    $keyword = $main->find("table", 3)->find("tr", 3)->find("td", 0)->plaintext;
    $keyword = str_replace(["，", "、", ";"], ",", $keyword);

    $note = $main->find("table", 3)->find("tr", 4)->find("td", 0)->plaintext;

    $topic_cat = $main->find("table", 4)->find("tr", 0)->find("td", 0)->plaintext;

    $adm_cat = $main->find("table", 4)->find("tr", 1)->find("td", 0)->plaintext;

    $summary = $main->find("p", 0)->plaintext;

    foreach ($column as $key) {
        $$key = str_replace(["&nbsp;", " "], "", $$key);
        $result->bindValue(":$key", $$key);
    }
    //更新報告資料
    $result->execute();
}
/*
//都查過就從頭開始
if ($page_num >= $maxpage) {
$page_num = 0;
}
 */

//更新已查詢頁數
$sql = "UPDATE info SET page_num='$page_num',maxpage='$maxpage' WHERE type='center' ";
$db->exec($sql);
