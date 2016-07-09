<?php
error_reporting(E_ALL ^ E_NOTICE);
require "connect_mysql.php";
require "condition.php";

$page = $_GET['page'];
$i    = 0;
$data = [];
$start=($page-1)*25;

$sql    = "SELECT id,report_name,member_name,start_date,end_date,office,area FROM report WHERE 1=1 $condition ORDER BY start_date DESC limit $start,25";
$result = $db->prepare("$sql");
bind();
$result->execute();
while ($row=$result->fetch()) {
    $data[$i] = ["id" => "$row[0]", "report_name" => "$row[1]", "member_name" => "$row[2]", "date" => "$row[3]至<br>$row[4]", "office" => "$row[5]", "area" => "$row[6]"];
    $i++;
}

$sql    = "SELECT count(*) FROM report WHERE 1=1 $condition ";
$result = $db->prepare("$sql");
bind();
$result->execute();
while ($row=$result->fetch()) {
    $count = $row[0];
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
