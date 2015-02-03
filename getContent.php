<?php
	require_once('lib\\class.php');
	$showHidden=0;
	if(isset($_GET['showHidden']) && $_GET['showHidden']==="true"){
		$showHidden=2;
	}
	 $filename=$_GET['q'];
	 setcookie('filename',$filename,time()+84000);
	 $j=new Collection($filename);
	 $j->niceOutput($showHidden);
?>