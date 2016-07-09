<?php
error_reporting(E_ALL);
require "connect_mysql.php";
require "simple_html_dom.php";

//查詢頁碼控制
$result = $db->query("SELECT page_num,maxpage FROM info WHERE type='accounts'");
while ($row = $result->fetch()) {
    $page      = $row['page_num'];
    $star_page = $row['page_num'];
    $max_page  = $row['maxpage'];
}

//date控制
$year                   = date('Y') - 1911;
date('m') <= 6 ? $month = ['/1/1', '/6/30'] : $month = ['/7/1', '/12/31'];
$date                   = $year . $month[0] . '-' . $year . $month[1];

while ($page < $max_page && $page < ($star_page + 5)) {
    $page++;
    //curl設定
    $ch    = curl_init();
    $query = http_build_query(array('timeRangeTemp' => $date, "querySentence" => '支援及輔助運輸服務', 'sym' => 'on', 'sortCol' => 'AWARD_NOTICE_DATE', 'itemPerPage' => 1000, "d-7095067-p" => $page, "root" => 'tps', "timeRange" => $date, 'tenderStatusType' => '決標'));
    curl_setopt($ch, CURLOPT_URL, "http://web.pcc.gov.tw/prkms/prms-searchBulletionClient.do?" . $query);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2490.86 Safari/537.36");
    $curl_scraped_page = curl_exec($ch);

//phpsimpledom
    $html = new simple_html_dom();
    $html->load($curl_scraped_page, true, false);

//獲取總資料數，算出總頁數
    $max_page = ceil(str_replace(",", "", $html->find('.pagebanner b', 0)->innertext) / 1000);

//獲取查詢到的標案，並插入資料表
    $row    = $html->find('#searchResult', 0)->find('tbody', 0)->find('tr');
    $i      = 0;
    $VALUES = [];
    foreach ($row as $key) {
        if (!preg_match('/無法決標/', $key->find('td', 5))) {
            $link       = $key->find('td', 3)->find('a', 0)->href;
            $link       = str_replace(' ', '%20', $link);
            $VALUES[$i] = "('$link')";
            $i++;
        }
    }
    $VALUES = implode(",", $VALUES);
    $db->exec("INSERT IGNORE INTO accounts(link) VALUES $VALUES ");
}

//查完就從頭來
if ($page >= $max_page) {
    $page = 0;
}

//更新查詢頁碼資料
$db->exec("UPDATE info SET page_num='$page',maxpage='$max_page' WHERE type='accounts'");
