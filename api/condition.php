<?php

$condition_list = ['gov', 'id', 'plan_name', 'report_name', 'report_date', 'report_page', 'office', 'member_name', 'member_office', 'member_unit', 'member_job', 'member_level', 'member_num', 'start_date', 'end_date', 'area', 'visit', 'type', 'keyword', 'note', 'topic_cat', 'adm_cat', 'summary', 'year', 'search'];
foreach ($condition_list as $key) {
    $$key = $_GET[$key];
}

function bind()
{
    global $condition_list,$result,$search;
    foreach ($condition_list as $key) {
        global $$key;
        if ($key==='search' || !$$key){
            continue;
        }
        if($key === 'year') {
            $result->bindValue(":year_start", "$year-0-0");
            $result->bindValue(":year_end", "$year-12-31");
        }elseif($search==='similar' && !in_array($key,['start_date','end_date','report_page','member_num'])) {
            $result->bindValue(":$key", "%".$$key."%");
        }else{
            $result->bindValue(":$key", $$key);
        }
    }
}

$condition = "";

foreach ($condition_list as $key) {
    if ($$key=='') {
        continue;
    }
    if ($key==='search'){
    } elseif($key === 'year') {
        $condition .= " AND start_date>=:year_start AND start_date<=:year_end ";
    } elseif ($key === 'start_date') {
        $condition .= " AND start_date>=:start_date ";
    } elseif ($key === 'end_date') {
        $condition .= " AND end_date<=:end_date ";
    } elseif ($search === 'similar') {
        if (in_array($key, ['report_page', 'member_num'])) {
            $condition .= " AND $key=:$key ";
        } else {
            $condition .= " AND $key LIKE :$key ";
        }
    } else{
        if (in_array($key, ['member_name', 'member_office', 'member_unit', 'member_job', 'member_level', 'area', 'visit', 'keyword'])) {
            $condition .= " AND FIND_IN_SET(:$key,$key) ";
        } else {
            $condition .= " AND $key=:$key ";
        }
    }
}
