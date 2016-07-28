<?php

error_reporting(E_ALL ^ E_NOTICE);

require "connect_mysql.php";
require "condition.php";

function sort_rank($a, $b)
{
    if ($a['number'] == $b['number']) {
        return 0;
    }

    return ($a['number'] < $b['number']) ? 1 : -1;
}

$kind = $_GET['kind'];
$page = $_GET['page'];

$data = [];
$i    = 0;
$count  = 0;

$start = ($page - 1) * 25;

if (in_array($kind, ['year', 'month', 'day', 'office', 'topic_cat', 'gov'])) {
    if ($kind == 'year') {
        $sql = "SELECT YEAR(start_date),count(*) FROM report WHERE 1=1 $condition GROUP BY YEAR(start_date) ORDER BY YEAR(start_date) limit $start,25";
    	$sql2    = "SELECT DISTINCT YEAR(start_date) FROM report WHERE 1=1 $condition ";
    } elseif ($kind == 'month') {
        $sql = "SELECT MONTH(start_date),count(*) FROM report WHERE 1=1 $condition GROUP BY MONTH(start_date) ORDER BY MONTH(start_date) limit $start,25";
    	$sql2    = "SELECT DISTINCT MONTH(start_date) FROM report WHERE 1=1 $condition ";
    } elseif ($kind == 'day') {
        $sql = "SELECT DATEDIFF(end_date,start_date)+1,count(*) FROM report WHERE DATEDIFF(end_date,start_date)>=0 $condition GROUP BY DATEDIFF(end_date,start_date) ORDER BY DATEDIFF(end_date,start_date) limit $start,25";
    	$sql2    = "SELECT DISTINCT DATEDIFF(end_date,start_date) FROM report WHERE 1=1 $condition ";
    } else {
        $sql = "SELECT $kind,count(*) FROM report WHERE 1=1 $condition AND $kind!='' GROUP BY $kind ORDER BY count(*) DESC,$kind limit $start,25";
    	$sql2    = "SELECT DISTINCT $kind FROM report WHERE 1=1 $condition ";
    }

    $result = $db->prepare("$sql");
    bind();
    $result->execute();
    while ($row=$result->fetch()) {
        $data[$i] = ["name" => "$row[0]", "number" => "$row[1]"];
        $i++;
    }

	$result = $db->prepare("$sql2");
    bind();
    $result->execute();
	while ($row =$result->fetch()) {
	    $count++;
	}

} elseif (in_array($kind, ['area'])) {
    $list   = [];
    $sql    = "SELECT DISTINCT $kind FROM report WHERE 1=1 $condition ";
    $result = $db->prepare("$sql");
    bind();
    $result->execute();
    while ($row = $result->fetch()) {
        $list = array_merge($list, explode(",", $row[0]));
    }

    $list = array_unique($list);

    $sql    = "SELECT count(*) FROM report WHERE 1=1 $condition AND FIND_IN_SET(:key,$kind)";
    $result=$db->prepare("$sql");
    foreach ($list as $key) {
        if ($key == '') {
            continue;
        }
        bind();
        $result->bindParam(":key", $key);
        $result->execute();
        while ($row = $result->fetch()) {
            $data[$i] = ["name" => "$key", "number" => "$row[0]"];
        }
        $i++;
    }
    $count=1;
    //json輸出
    usort($data, 'sort_rank');
}else{
	exit('kind錯誤');
}


$max_page = ceil($count / 25);

$json = [
    "data"    => $data,
    "summary" => [
        "page"     => "$page",
        "max_page" => "$max_page",
        "count"    => "$count",
    ],
];

echo json_encode($json, JSON_UNESCAPED_UNICODE);
