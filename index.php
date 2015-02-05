<?php
// Reading list
// Author: Qiang Zhang
// Aug 10,2014
// Description: By reading txt file, get a reading list(web link) 
include "lib\\class.php";
include "lib\\config.php";
include "lib\\helper.php";
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
// Dealing with rename line
if (isset($_GET['rename_id'])){
  $default->renameItem($_GET['rename_id']);
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
<link href="css/bootstrap.min.css" rel="stylesheet">    <link href="css/style.css" rel="stylesheet">
        <script src="http://code.jquery.com/jquery-1.11.0.min.js"></script></script>
        <script src="js/bootstrap.min.js"></script>
		<script src="js/respond.js"></script>
</head>

<body onload="javascript:document.forms['myForm']['url'].focus()">
<div class="container">
<div class="row">
  <div class="col-lg-8">
    <form action="index.php" method="post" onsubmit="return validateForm()" name="myForm">
    Description: <input type="text" name="name" id="name" onkeypress="goToURL(event)">
    URL: <input type="text" name="url" id="url" placeholder="Press F7 to get focus" onkeypress="goToName(event)">
    <input type="submit" class="btn btn-primary btn-xs">
      <input type="checkbox" id="showHidden">Show Hidden
  </div>
  <div class="col-lg-4">
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
          <a href="#" id="new" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#smallModal">New</a>

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
    <div><a href="liveSearch.php" class="btn btn-default btn-xs">Search</a></div>
  </div>
</div> <!-- end of row 1 -->
<div class="row">
  <div class="col-lg-8 col-md-8" id="wrapper">
  <?php
      $default->niceOutput();
  ?>
  </div>
  <div class="col-lg-3 col-md-3 col-lg-offset-1 col-md-offset-1">
  <?php
      $keywordContent=file_get_contents($keywordfile);
  ?>
    <div class="panel panel-primary"></div>
      <form action="index.php" method="post" name="keywordForm">
      <div>
        <textarea class="keyword" id="keyword" name="keyword">
      <?php echo $keywordContent; ?>
      </textarea>
      </div>
      <input type="submit" class="btn btn-primary btn-xs btn-block">
      </form>
    </div>
  <script type="text/javascript" src="js/custom.js"></script>
  </div>
</div> <!-- end of row 2 -->
</div>
</body>
</html>
