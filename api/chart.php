<?php
require "connect_mysql.php";
error_reporting(E_ALL ^ E_NOTICE);
//地區排序函數
function sort_td($a, $b)
{
    if ($a[2] == $b[2]) {
        return 0;
    }
    return ($a[2] < $b[2]) ? 1 : -1;
}

$kind     = $_GET['kind'];
$result;

require("condition.php");

//生成圖表判定
switch ($kind) {
    case 'area':
        area();
        break;
    case 'day':
        day();
        break;
    case 'office':
        office();
        break;
    case 'topic_cat':
        topic_cat();
        break;
    case 'year':
        year();
        break;
    case 'month':
        month();
        break;
    case 'member_name':
        member_name();
        break;
    case 'gov':
        gov();
        break;
}

function area()
{
    global $condition,$db,$result;
    $print     = [];
    $area   = [];
    $sql    = "SELECT DISTINCT area FROM report WHERE 1=1 $condition ";
    $result = $db->prepare("$sql");
    bind();
    $result->execute();
    while ($row=$result->fetch()) {
        $area = array_merge($area, explode(",", $row[0]));
    }

    $area = array_unique($area);

    $i = 0;
    $sql    = "SELECT count(*),area.en_name FROM report INNER JOIN area ON area.name=:key WHERE 1=1 $condition AND FIND_IN_SET(:key,area)";
    $result = $db->prepare("$sql");
    bind();
    $result->bindParam(":key", $key);
    foreach ($area as $key) {
        if ($key == '') {
            continue;
        }
        $print[$i] = [];
        //數量
        $result->execute();
        while ($row=$result->fetch()) {
            $print[$i][0] = $row[1];
            $print[$i][1] = $key;
            $print[$i][2] = $row[0];
        }
        $i++;
    }

    //json輸出
    usort($print, 'sort_td');

    echo '{';
    echo '"map":{';
    echo '"cols": [';
    echo '{"label":"國家","type":"string"},
          {"label":"考察次數","type":"number"}';
    echo '],';

    echo '"rows": [';
    for ($i = 0; $i < count($print); $i++) {
        echo '{"c":[{"v":"' . $print[$i][0] . '","f":"' . $print[$i][1] . '"},{"v":' . $print[$i][2] . '}]}';
        if ($i < count($print) - 1) {
            echo ',';
        }
    }
    echo ']';
    echo '},';

    $core = array_slice($print, 0, 50);

    echo '"core":{';
    echo '"cols": [';
    echo '{"label":"國家","type":"string"},
          {"label":"考察次數","type":"number"}';
    echo '],';

    echo '"rows": [';
    for ($i = 0; $i < count($core); $i++) {
        echo '{"c":[{"v":"' . $core[$i][1] . '"},{"v":' . $core[$i][2] . '}]}';
        if ($i < count($core) - 1) {
            echo ',';
        }
    }
    echo ']';
    echo '}';
    echo '}';
}

function day()
{
    $i    = 0;
    $rows = [];
    global $condition,$db,$result;

    $sql    = "SELECT DATEDIFF(end_date,start_date)+1,count(*) FROM report WHERE DATEDIFF(end_date,start_date)>=0 AND DATEDIFF(end_date,start_date)<=49 $condition GROUP BY DATEDIFF(end_date,start_date)  ORDER BY DATEDIFF(end_date,start_date)";
    $result = $db->prepare("$sql");
    bind();
    $result->execute();
    while ($row=$result->fetch()) {
        $rows[$i] = ["c" => [["v" => "$row[0]", "f" => "$row[0]天"], ["v" => "$row[1]"]]];
        $i++;
    }

    $json = [
        "cols" => [
            [
                "label" => "天數",
                "type"  => "number",
            ],
            [
                "label" => "考察次數",
                "type"  => "number",
            ],
        ],
        "rows" => $rows,
    ];

    echo json_encode($json, JSON_UNESCAPED_UNICODE);
}

function office()
{
    $i    = 0;
    $rows = [];
    global $condition,$db,$result;

    $sql    = "SELECT office,count(*) FROM report WHERE 1=1 $condition GROUP BY office ORDER BY count(*) DESC limit 0,50";
    $result = $db->prepare("$sql");
    bind();
    $result->execute();
    while ($row=$result->fetch()) {
        $rows[$i] = ["c" => [["v" => "$row[0]"], ["v" => "$row[1]"]]];
        $i++;
    }

    $json = [
        "cols" => [
            [
                "label" => "單位",
                "type"  => "string",
            ],
            [
                "label" => "考察次數",
                "type"  => "number",
            ],
        ],
        "rows" => $rows,
    ];

    echo json_encode($json, JSON_UNESCAPED_UNICODE);

}

function topic_cat()
{
    $i    = 0;
    $rows = [];
    global $condition,$db,$result;

    $sql    = "SELECT topic_cat,count(*) FROM report WHERE 1=1 $condition GROUP BY topic_cat ORDER BY count(*) DESC ";
    $result = $db->prepare("$sql");
    bind();
    $result->execute();
    while ($row=$result->fetch()) {
        $rows[$i] = ["c" => [["v" => "$row[0]"], ["v" => "$row[1]"]]];
        $i++;
    }

    $json = [
        "cols" => [
            [
                "label" => "主題",
                "type"  => "string",
            ],
            [
                "label" => "考察次數",
                "type"  => "number",
            ],
        ],
        "rows" => $rows,
    ];

    echo json_encode($json, JSON_UNESCAPED_UNICODE);
}

function year()
{
    $i    = 0;
    $rows = [];
    global $condition,$db,$result;

    $sql    = "SELECT year(start_date),count(*) FROM report WHERE 1=1 $condition GROUP BY year(start_date) ORDER BY year(start_date)";
    $result = $db->prepare("$sql");
    bind();
    $result->execute();
    while ($row=$result->fetch()) {
        $rows[$i] = ["c" => [["v" => "$row[0]", "f" => "$row[0]年"], ["v" => "$row[1]"]]];
        $i++;
    }

    $json = [
        "cols" => [
            [
                "label" => "年份",
                "type"  => "string",
            ],
            [
                "label" => "考察次數",
                "type"  => "number",
            ],
        ],
        "rows" => $rows,
    ];

    echo json_encode($json, JSON_UNESCAPED_UNICODE);

}

function month()
{
    $i    = 0;
    $rows = [];
    global $condition,$db,$result;

    $sql    = "SELECT month(start_date),count(*) FROM report WHERE 1=1 $condition GROUP BY month(start_date) ORDER BY month(start_date)";
    $result = $db->prepare("$sql");
    bind();
    $result->execute();
    while ($row=$result->fetch()) {
        $rows[$i] = ["c" => [["v" => "$row[0]", "f" => "$row[0]月"], ["v" => "$row[1]"]]];
        $i++;
    }

    $json = [
        "cols" => [
            [
                "label" => "月份",
                "type"  => "string",
            ],
            [
                "label" => "考察次數",
                "type"  => "number",
            ],
        ],
        "rows" => $rows,
    ];

    echo json_encode($json, JSON_UNESCAPED_UNICODE);
}

function gov()
{
    $i    = 0;
    $rows = [];
    global $condition,$db,$result;

    $sql    = "SELECT gov,count(*) FROM report WHERE 1=1 $condition GROUP BY gov ORDER BY count(*) DESC ";
    $result = $db->prepare("$sql");
    bind();
    $result->execute();
    while ($row=$result->fetch()) {
        $rows[$i] = ["c" => [["v" => "$row[0]"], ["v" => "$row[1]"]]];
        $i++;
    }

    $json = [
        "cols" => [
            [
                "label" => "政府",
                "type"  => "string",
            ],
            [
                "label" => "考察次數",
                "type"  => "number",
            ],
        ],
        "rows" => $rows,
    ];

    echo json_encode($json, JSON_UNESCAPED_UNICODE);
}
