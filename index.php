<?php
// Reading list
// Author: Qiang Zhang
// Aug 10,2014
// Description: By reading txt file, get a reading list(web link) 
include "class.php";
if(!isset($_COOKIE['filename'])){
	setcookie("filename","default.txt",time()+840000);
}
$filename=$_COOKIE['filename'];

$default=new Collection($filename);
$keywordfile="keyword.txt";

// reading txt file, print it out on the page
if (isset($_POST['name']))
{
	//echo "success";
	$description=$_POST['name'];
	$url=$_POST['url'];
	$default->addItem($url,$description);
	header('location:index.php');
}

// Dealing with delete line by making the first character in the line "T" (meaning read)
if (isset($_GET['delete_id'])){
	$default->hideItem($_GET['delete_id']);
	header('location:index.php');
}
// dealing with keyword.txt file, writing
if (isset($_POST['keyword']))
{
	$textareaContent=$_POST['keyword'];
	file_put_contents($keywordfile, $textareaContent);
	header('location:index.php');
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Q's reading list</title>
<link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
		<link href="css/bootstrap-responsive.css" rel="stylesheet">
        <script src="http://code.jquery.com/jquery-1.11.0.min.js"></script></script>
        <script src="js/bootstrap.min.js"></script>
		<script src="js/respond.js"></script>
</head>

<body onload="javascript:document.forms['myForm']['url'].focus()">
<div class="header">
<form action="index.php" method="post" onsubmit="return validateForm()" name="myForm">
Description: <input type="text" name="name" id="name" onkeypress="goToURL(event)">
URL: <input type="text" name="url" id="url" placeholder="Press F7 to get focus" onkeypress="goToName(event)">
<input type="submit" class="btn btn-primary">
<select id="fileSelect" name="fileSelect">
<?php
$dir = DIR;
$files1 = scandir($dir);
foreach($files1 as $file){
	if($file==='.' || $file==='..') continue;
	if($file===$_COOKIE['filename']){
		echo "<option value='$file' selected>$file</option>";
		}else{
	echo "<option value='$file'>$file</option>";}	
}
?>
</select>

</form>
      <a href="#" id="new" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#smallModal">New</a>
 
<div class="modal fade" id="smallModal" tabindex="-1" role="dialog" aria-labelledby="smallModal" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Add New List</h4>
      </div>
      <form action="addList.php">
      <div class="modal-body">
       <label>Filename:</label> <input type="text" name="filename" />
      </div>
      <div class="modal-footer">
      <input type="submit" />
        <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>-->
      </div>
      </form>
    </div>
  </div>
</div>


<div class="wrapper" id="wrapper">
<?php 
	$default->niceOutput();
?>
</div>
<div class="rightpanel">
<?php
	$keywordContent=file_get_contents($keywordfile);
?>
<form action="index.php" method="post" name="keywordForm">

<textarea class="keyword" id="keyword" name="keyword">
<?php echo $keywordContent; ?>
</textarea>
<input type="submit" class="btn btn-primary">
</form>
<script type="text/javascript">
function validateForm() {
    var x = document.forms["myForm"]["name"];
    if (x.value == null || x.value == "") {
		return false;
    }
	var y = document.forms["myForm"]["url"];
    if (y.value == null || y.value == "" || urlValid(y.value)== false ) {
		return false;
    }
}
function getClipboard() {
	//var x = document.forms["myForm"]["url"];
	//x.value=window.clipboardData.getData('Text');
	var y = document.forms["myForm"]["url"];
	y.focus();
	//document.write(x);
	//document.write(window.clipboardData.getData('Text'));
}
function goToName(e){
  //alert("bcd");
  //alert(event.keyCode);
  var keycode = (window.event) ? window.event.keyCode : e.keyCode;
  //alert("message");
  if(keycode == 13){
	//var x=document.forms["myForm"]["name"];
	var x=document.getElementById("name");
		   x.focus();
		  setTimeout(function() { document.getElementById('name').focus(); }, 10);
  };
};
function goToURL(e){
	//alert(e.keyCode);
  var keycode = (window.event) ? window.event.keyCode : e.keyCode;
    //alert("message");
  if(keycode == 13){
	 
	//var x=document.forms["myForm"]["url"];
	var x=document.getElementById("url");
		   x.focus();
    setTimeout(function() { document.getElementById('url').focus(); }, 10)
  };
};
function urlValid(s) {    
      var regexp = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;
      return regexp.test(s);    
 }
var fun=function(e){
	  var keycode = (window.event) ? window.event.keyCode : e.keyCode;
	  var evt=window.event ? window.event : e;
	  
    //alert("message");
  if(keycode == 119){
	 evt.preventDefault();
	//var x=document.forms["myForm"]["url"];
	var x=document.getElementById("keyword");
    setTimeout(function() { x.focus(); }, 10);
  }else if(keycode == 118) {
	  evt.preventDefault();
	  var x=document.getElementById("url");
    setTimeout(function() { x.focus(); }, 10);
	  }
	};
document.onkeydown=fun;

function showResult(str) {
  if (window.XMLHttpRequest) {
    // code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
  } else {  // code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange=function() {
    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
		
		
	if(xmlhttp.responseText){
      document.getElementById("wrapper").innerHTML=xmlhttp.responseText;
}
	  
	  
	  
    }
  }
  xmlhttp.open("GET","getContent.php?q="+this.value,true);
  xmlhttp.send();
}
document.getElementById('fileSelect').onchange=showResult;
</script>

</div>
</body>
</html>
