<?php 
error_reporting(E_ALL ^ E_NOTICE);
require "connect_mysql.php";
$search=$_GET["search"];
$type=$_GET["type"];

$i=0;
$data=[];

if ($search!="") {
	$data[0]=["id"=>"$search","text"=>"$search"];
	$i++;
}

switch ($type) {
	case 'area':
		$sql="SELECT name FROM area WHERE name LIKE '%$search%' ORDER BY number DESC limit 0,5";
		break;
	case 'office':
		$sql="SELECT office FROM report WHERE office LIKE '%$search%' GROUP BY office ORDER BY count(*) DESC limit 0,5";
		break;
	case 'member_name':
		$sql="SELECT name FROM member_name WHERE name LIKE '%$search%' ORDER BY number DESC limit 0,5";
		break;
	case 'topic_cat':
		$sql="SELECT topic_cat FROM report WHERE topic_cat LIKE '%$search%' GROUP BY topic_cat ORDER BY count(*) DESC limit 0,5";
		break;
	case 'adm_cat':
		$sql="SELECT adm_cat FROM report WHERE adm_cat LIKE '%$search%' GROUP BY adm_cat ORDER BY count(*) DESC limit 0,5";
		break;
	case 'gov':
		$sql="SELECT gov FROM report WHERE gov LIKE '%$search%' GROUP BY gov ORDER BY count(*) DESC limit 0,5";
		break;
}

$result=$db->query($sql);
while ($row=$result->fetch()) {
	if ($row[0]==$search) {
		continue;
	}
	$data[$i]=["id"=>"$row[0]","text"=>"$row[0]"];
	$i++;
}
$json = [
    "data"    => $data
];
echo json_encode($json, JSON_UNESCAPED_UNICODE);



?>