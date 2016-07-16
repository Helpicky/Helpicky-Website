<?php
date_default_timezone_set("Asia/Taipei");
function url(){
	$url = $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
	return substr($url,0,strrpos($url,"/")+1);
}
function het($text){
	return htmlentities($text,ENT_QUOTES);
}
function getrandommd5(){
	return md5(uniqid(rand(),true));
}
?>