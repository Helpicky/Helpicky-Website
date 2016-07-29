<?php
function checklogin(){
	require("../function/facebook-php-sdk-v4/src/Facebook/autoload.php");
	require("../config/config.php");
	if (!isset($_COOKIE[$cfg['cookie']['name']])) return false;
	$query = new query;
	$query->table = "session";
	$query->where = array(
		array("cookie", $_COOKIE[$cfg['cookie']['name']])
	);
	$row = fetchone(SELECT($query));
	if($row !== null){
		$query = new query;
		$query->table="user";
		$query->where = array(
			array("uid", $row["uid"]),
		);
		$temp = fetchone(SELECT($query));
		$temp["allergen"] = explode(",", $temp["allergen"]);
		return $temp;
	}
	return false;
}
?>