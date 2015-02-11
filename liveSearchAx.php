<?php
include "lib/class.php";
$q=$_GET["q"];
$file=$_GET["file"];
$j=new Collection($file);
//$j->output1();
$res=$j->find($q);
if(count($res)===0) {echo " ";exit;}
foreach($res as $key => $value){
	echo "<a href='".$value."'>".$key."</a><br />";
}
?>
