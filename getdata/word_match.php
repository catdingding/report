<?php
error_reporting(E_ALL);
ini_set('memory_limit', '1024M');

require "connect_mysql.php";

function sort_accounts($a, $b)
{
    if ($a[1] == $b[1]) {
        return 0;
    }
    return ($a[1] < $b[1]) ? 1 : -1;
}

//加權
$plus="/縣|市|鄉|鎮|區|里|國|町|郡|洲|州/";

$sql    = "SELECT gov,start_date,word FROM report WHERE word!='' AND plan_name LIKE '%年度行政人員%' ORDER BY start_date DESC limit 0,100";
$result = $db->query($sql);
while ($row = $result->fetch()) {
    $condition = '';
    $word      = explode(',', $row['word']);
    foreach ($word as $key) {
        $condition .= "OR name LIKE '%$key%' ";
    }

    $start_date = $row['start_date'];

    $i    = 0;
    $list = [];
    $max=0;

    foreach ($word as $key) {
        if (preg_match($plus, $key)) {
            $max += 3;
        } else {
            $max += 1;
        }
    }

    $sql     = "SELECT ai_id,name FROM accounts WHERE DATEDIFF('$start_date',date)<=365 AND DATEDIFF('$start_date',date)>=0 AND name NOT LIKE '%機票%' AND name NOT LIKE '%車票%' AND (1=2 $condition)";
    $result2 = $db->query($sql);
    while ($row2 = $result2->fetch()) {
        $list[$i] = [$row2['name'], 0];
        foreach ($word as $key) {
            if (preg_match("/$key/", $row2[1])) {
                if (preg_match($plus, $key)) {
                    $list[$i][1] += 3;
                } else {
                    $list[$i][1] += 1;
                }
            }
        }
        $i++;
    }
    usort($list, 'sort_accounts');
    if ($list[0][1]<$max*0.5) {
        echo $row['word'] . '=>' . '找不到' . '<br>';
    }else{
        echo $row['word'] . '=>' . $list[0][0] . '<br>';
    }
}
