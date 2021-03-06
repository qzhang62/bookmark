<?php
define('DIR','data/');
define('DELIMITER','::::');
class Item{
	var $name;
	var $url;
	function set($name,$url)
	{
		$this->name=$name;
		$this->url=$url;
	}
	function getName()
	{
		return $this->name;
	}
	function getURL()
	{
		return $this->url;
	}
}
class Collection{
	var $items=array();
	var $size;
	var $curPtr;
	var $filename;
	var $fullPath;
	function __construct($filename){
		$this->size=0;
		$this->curPtr=null;
		$this->filename=$filename;
		$this->fullPath=DIR.$this->filename;
		if(file_exists(DIR.$filename)){
			$this->loadFromFile($filename);	
		}
	}
	function addBack($arr){
		$this->items[]=$arr;
		$this->size+=1;	
	}
	function getSize()
	{
		return $this->size;	
	}
	function loadFromFile($filename)
	{
		$fh = @fopen(DIR.$filename, "r") or die("can't open file");
		$this->filename=$filename;
		while (($buffer = fgets($fh, 4096)) !== false) {
        	//echo "abc";
			$fields=explode("::::",$buffer);
			$this->addBack($fields);
		}
		//echo "Loaded file";
		fclose($fh);
	}
	function find($keyword)
	{
		//echo "$keyword";
		if($keyword==='' || $keyword===null) return;
		$res=array();
		reset($this->items);
		foreach( $this->items as $item)
		{
			if(stripos($item[1],$keyword)!==FALSE){
				$res[$item[1]]=$item[2];	
			}	
		}
		return $res;
	}
	function output()
	{
		var_dump($this->items);	
	}
	function niceOutput($all=0){
		// $all=0	only print line with field[0]=F
		// $all=1	only print line with field[0]=T
		// $all=2	print all line
		$handle = @fopen(DIR.$this->filename, "r");
		echo "<ul>";
		$i=1;
		while (($buffer = fgets($handle, 4096)) !== false) {
			$fields = explode("::::", $buffer);
			//echo $fields[0]."<br>";
			if ($fields[0] == "F" and ($all===0 or $all===2)) {
				echo "<li class='col-lg-3 col-md-3'><a href='$fields[2]' target='_blank' class='readinglist'>$fields[1]</a>   <span class='delete'><a href='index.php?delete_id=$i'  class='btn btn-default btn-xs btn-inline'><span class='glyphicon glyphicon-remove showTip' id='removeItem' data-toggle='tooltip' title='remove'></span></a><a href='#' class='btn btn-xs btn-default btn-inline' data-toggle='modal' data-target='#renameModal' data-id='$i' data-title='$fields[1]'><span class='glyphicon glyphicon-registration-mark showTip' id='renameItem' data-toggle='tooltip' title='rename'></a></span></li>";
			}
			if ($fields[0] == "T" and ($all===1 or $all===2)) {
				echo "<li class='col-lg-3 col-md-3'><a href='$fields[2]' target='_blank' class='readinglist'>$fields[1]</a>   <span class='delete'><a href='index.php?delete_id=$i' class='btn btn-default btn-xs btn-inline'><span class='glyphicon glyphicon-remove showTip'  id='removeItem' data-toggle='tooltip' title='remove'></span></a><a href='index.php?rename_id=$i' class='btn btn-xs btn-default btn-inline'><span class='glyphicon glyphicon-registration-mark showTip' id='renameItem' data-toggle='tooltip' title='rename'></span></span></a></span></li>";
			}
			$i++;
		}
		echo "</ul>";
		if (!feof($handle)) {
			echo "Error: unexpected fgets() fail\n";
		}
		fclose($handle);	
		}
	function saveToTxt(){	// save current collection to a txt file
		$text=file_get_contents(DIR.$this->filename);
		$filename=$this->filename+".xml";
		header("Content-type: application/text");
		header("Content-Disposition: attachment; filename=\"$filename\"");
		die($text);
		header("location:index.php");
	}
	function saveToXML(){	// save current collection to a xml file
		$str='<collections>';
		$str.='<collection>';
		foreach($this->items as $item){
			$str.="<item>";
			$str.="<show>$item[0]</show>";
			$str.="<description>".htmlspecialchars($item[1])."</description>";
			$str.="<url>".htmlspecialchars($item[2])."</url>";
			$str.="</item>";
		}
		$str.='</collection>';
		$str.='</collections>';
		$xml = new SimpleXMLElement($str);
		$filename=$this->filename.".xml";
		header('Content-Type: text/xml');
		header('Content-Disposition: attachment; filename="'.$filename.'"');
		header('Content-Transfer-Encoding: binary');
		die($xml->asXML());
		header("location:index.php");
	}
	function hideItem($id){
		$fullPath=DIR.$this->filename;
		$fh = @fopen($fullPath, "r+");
		$line_id=$id;
		$file_content='';
		$i=1;
		while(!feof($fh)) {
			if($i==$line_id){
				$tmp=fgets($fh);
				$tmp[0]='T';
				$file_content.=$tmp;
			}
			else{
				$file_content.=fgets($fh);
			}
			$i++;
		}
		file_put_contents($fullPath,$file_content);
		fclose($fh);	
	}
	function renameItem($id,$name="no name"){
		$fullPath=DIR.$this->filename;
		$fh = @fopen($fullPath, "r+");
		$line_id=$id;
		$file_content='';
		$i=1;
		while(!feof($fh)) {
			if($i==$line_id){
				$tmp=fgets($fh);
				$pieces=explode(DELIMITER,$tmp);
				$pieces[1]=$name;
				$tmp=implode(DELIMITER,$pieces);
				$file_content.=$tmp;
			}
			else{
				$file_content.=fgets($fh);
			}
			$i++;
		}
		file_put_contents($fullPath,$file_content);
		fclose($fh);
	}
	function addItem($url,$description)
	{
		$fh = fopen($this->fullPath, 'a') or die("can't open file");
		$line="F".DELIMITER."$description".DELIMITER."$url";
		//echo $line;
		if(flock($fh,LOCK_EX)){
		fwrite($fh, "$line".PHP_EOL);
		flock($fh,LOCK_UN);
		}
		fclose($fh);	
	}
}
?>
