<?php
include "class.php";
$q=$_GET["q"];
$j=new Collection("default.txt");
//$j->output1();
$res=$j->find($q);
if(count($res)===0) {echo " ";exit;}
foreach($res as $key => $value){
	echo "<a href='".$value."'>".$key."</a><br />";
}
?>
