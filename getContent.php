<?php
	require_once('lib\\class.php');
	 $filename=$_GET['q'];
	 setcookie('filename',$filename,time()+84000);
	 $j=new Collection($filename);
	 $j->niceOutput();
?>