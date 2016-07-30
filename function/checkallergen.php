<?php
$query = new query;
$query->table = "allergen";
$row = SELECT($query);
$allergenname=array();
foreach ($row as $temp) {
	$allergenname[$temp["id"]] = $temp["name"];
}
function checkallergen($user, $food){
	global $allergenname;
	$user = explode(",", $user);
	$food = explode(",", $food);
	$res = array_intersect($user, $food);
	foreach ($res as $key => $value) {
		$res[$key] = $allergenname[$value];
	}
	return $res;
}
?>