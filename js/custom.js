/**
 * Created by Qiang on 1/25/2015.
 */
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
