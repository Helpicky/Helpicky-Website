<?php
date_default_timezone_set("Asia/Taipei");
require_once("../config/config.php");
require_once("../function/SQL-function/sql.php");
require_once("../function/checkpermission.php");
require_once("../function/msgbox.php");
require_once("../function/meta.php");
require_once("../function/getdata.php");
$login = checklogin();
function url(){
	$url = $_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
	return substr($url, 0, strrpos($url,"/")+1);
}
function het($text){
	return htmlentities($text,ENT_QUOTES);
}
function getrandommd5(){
	return md5(uniqid(rand(), true));
}
function addlearn($id){
	global $login;
	if (!in_array($id, $login["learn"])) {
		$login["learn"][] = $id;
		$query = new query;
		$query->table = "user";
		$query->value = array(
			array("learn", implode(",", $login["learn"]))
		);
		$query->where = array(
			array("uid", $login["uid"])
		);
		UPDATE($query);
	}
}
$inlearn=false;
if (isset($_COOKIE[$cfg['cookie']['name']."_learn"])) {
	$inlearn=$_COOKIE[$cfg['cookie']['name']."_learn"];
}
?>