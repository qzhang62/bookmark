<html>
<head>
<script>
function showResult(str) {
  if (str.length==0) { 
    document.getElementById("livesearch").innerHTML="";
    document.getElementById("livesearch").style.border="0px";
    return;
  }
  if (window.XMLHttpRequest) {
    // code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
  } else {  // code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange=function() {
    if (xmlhttp.readyState==4 && xmlhttp.status==200) {
		
		
	if(xmlhttp.responseText){
      document.getElementById("livesearch").innerHTML=xmlhttp.responseText;
      document.getElementById("livesearch").style.border="1px solid #A5ACB2";
	  document.getElementById("livesearch").setAttribute("style","display:block;width:200px");
	  document.getElementById("livesearch").style.width='200px';}
	  
	  
	  
    }
  }
  xmlhttp.open("GET","liveSearchAx.php?q="+str,true);
  xmlhttp.send();
}
function hello(e){
	if(e.keyCode===13){
		e.preventDefault();	
	}
}

</script>
</head>
<body>

<form>
<input type="text" size="30" id="searchBox" onKeyup="showResult(this.value)">
<div id="livesearch"></div>
</form>
</body>
<script type="text/javascript">
//document.getElementById('searchBox').onkeydown=hello;
document.getElementById('searchBox').addEventListener('keydown',function(e){if(e.keyCode===13){
		e.preventDefault();	
	}});
</script>
</html>