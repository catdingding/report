<?php 
error_reporting(E_ALL);
ini_set('memory_limit', '1024M');
require "connect_mysql.php";

require "jieba/vendor/multi-array/MultiArray.php";
require "jieba/vendor/multi-array/Factory/MultiArrayFactory.php";
require "jieba/class/Jieba.php";
require "jieba/class/Finalseg.php";
use Fukuball\Jieba\Jieba;
use Fukuball\Jieba\Finalseg;
Jieba::init(array('mode'=>'default','dict'=>'big'));
Finalseg::init();

$stopword=['之','與','及','和','報告書','報告','考察報告','的'];


$sql="SELECT id,plan_name FROM report WHERE word='' limit 0,500";
$result=$db->query($sql);
while ($row=$result->fetch()) {
	$word = Jieba::cutForSearch($row['plan_name']);
	for ($i=0; $i <count($word) ; $i++) { 
		if (in_array($word[$i],$stopword)) {
			unset($word[$i]);
		}
	}
	$word=implode(',', $word);
	$id=$row['id'];
	
	$sql="UPDATE report SET word='$word' WHERE id='$id' ";
	$db->exec($sql);
}

?>