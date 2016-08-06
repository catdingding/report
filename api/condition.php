<?php

$condition_list = ['gov', 'id', 'plan_name', 'report_name', 'report_date', 'report_page', 'office', 'member_name', 'member_office', 'member_unit', 'member_job', 'member_level', 'member_num', 'start_date', 'end_date', 'area', 'visit', 'type', 'keyword', 'note', 'topic_cat', 'adm_cat', 'summary', 'year', 'search'];
foreach ($condition_list as $value) {
    $$value = $_GET[$value];
}

function bind()
{
    global $condition_list,$result,$search;
    foreach ($condition_list as $value) {
        global $$value;
        if ($value==='search' || !$$value){
            continue;
        }
        if($value === 'year') {
            $result->bindValue(":year_start", "$year-0-0");
            $result->bindValue(":year_end", "$year-12-31");
        }elseif($search==='similar' && !in_array($value,['start_date','end_date','report_page','member_num'])) {
            $result->bindValue(":$value", "%".$$value."%");
        }else{
            $result->bindValue(":$value", $$value);
        }
    }
}

$condition = "";

foreach ($condition_list as $value) {
    if ($$value=='') {
        continue;
    }
    if ($value==='search'){
    } elseif($value === 'year') {
        $condition .= " AND start_date>=:year_start AND start_date<=:year_end ";
    } elseif ($value === 'start_date') {
        $condition .= " AND start_date>=:start_date ";
    } elseif ($value === 'end_date') {
        $condition .= " AND end_date<=:end_date ";
    } elseif ($search === 'similar') {
        if (in_array($value, ['report_page', 'member_num'])) {
            $condition .= " AND $value=:$value ";
        } else {
            $condition .= " AND $value LIKE :$value ";
        }
    } else{
        if (in_array($value, ['member_name', 'member_office', 'member_unit', 'member_job', 'member_level', 'area', 'visit', 'keyword'])) {
            $condition .= " AND FIND_IN_SET(:$value,$value) ";
        } else {
            $condition .= " AND $value=:$value ";
        }
    }
}
