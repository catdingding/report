<?php
error_reporting(E_ALL);
require "connect_mysql.php";
require "simple_html_dom.php";
$start_time=time();

$result = $db->query("SELECT link FROM accounts WHERE name='' limit 0,100 ");
while ($row = $result->fetch()) {
    $link = $row[0];

    //curl設定
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://web.pcc.gov.tw' . $link);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2490.86 Safari/537.36");
    $curl_scraped_page = curl_exec($ch);

    $result2=$db->prepare("REPLACE INTO accounts(id,name,link,office,area,price,date,post_date) VALUES(:id,:name,:link,:office,:area,:price,:date,:post_date)");

//phpsimpledom
    $html = new simple_html_dom();
    $html->load($curl_scraped_page, true, false);

    foreach ($html->find('.award_table_tr_1') as $key) {
        if ($key->find('th', 0)->plaintext === '機關名稱') {
            $office = $key->find('td', 0)->plaintext;
        }
    }
    foreach ($html->find('.award_table_tr_2') as $key) {
        if ($key->find('th', 0)->plaintext === '標案案號') {
            $id = $key->find('td', 0)->plaintext;
        }
        if ($key->find('th', 0)->plaintext === '標案名稱') {
            $name = $key->find('td', 0)->plaintext;
        }
        if ($key->find('th', 0)->plaintext === '履約地點') {
            $area = $key->find('td', 0)->plaintext;
        }
    }

    foreach ($html->find('.award_table_tr_6') as $key) {
        if ($key->find('th', 0)->plaintext === '決標日期') {
            $date = $key->find('td', 0)->plaintext;
            $date = preg_replace("/[\s\/]/", '', $date) + 19110000;
        }
        if ($key->find('th', 0)->plaintext === '決標公告日期') {
            $post_date = $key->find('td', 0)->plaintext;
            $post_date = preg_replace("/[\s\/]/", '', $post_date) + 19110000;
        }
        if ($key->find('th', 0)->plaintext === '總決標金額') {
            $price = $key->find('td', 0)->plaintext;
            $price = str_replace(['元', ','], '', $price);
        }
    }

    foreach (['link','office','id','name','area','date','post_date','price'] as $key) {
    	$$key=preg_replace("/\s/",'',$$key);
    	$result2->bindValue(":$key", $$key);
    }
    $result2->execute();
    
    if (time()-$start_time>50) {
        exit(1);
    }
}
