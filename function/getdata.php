<?php
require_once("../function/SQL-function/sql.php");

function getfood($fid){
	$query = new query;
	$query->table = "food";
	$query->where = array(
		array("fid", $fid)
	);
	$food = fetchone(SELECT($query));
	if ($food["servings"] != 0) {
		$food["calories"] *= $food["servings"];
		$food["protein"] *= $food["servings"];
		$food["fats"] *= $food["servings"];
		$food["saturated_fats"] *= $food["servings"];
		$food["trans_fats"] *= $food["servings"];
		$food["carbohydrates"] *= $food["servings"];
		$food["sodium"] *= $food["servings"];
		$food["sugar"] *= $food["servings"];
	}
	return $food;
}

function getrating($fid){
	$query = new query;
	$query->table = "rating";
	$query->column = array("COUNT(*) AS `count`", "rating");
	$query->where = array(
		array("fid", $fid)
	);
	$query->group = array("rating");
	$row = SELECT($query);
	$rating = array(1=>0, 2=>0, 3=>0, 4=>0, 5=>0, "avg"=>0, "count"=>0, "maxcount"=>0);
	foreach ($row as $temp) {
		$rating[$temp["rating"]] = $temp["count"];
		$rating["avg"] += $temp["rating"] * $temp["count"];
		$rating["count"] += $temp["count"];
		$rating["maxcount"] = max($rating["maxcount"], $temp["count"]);
	}
	if ($rating["count"] != 0) {
		$rating["avg"] /= $rating["count"];
	} else {
		$rating["count"] = 0;
	}
	$query = new query;
	$query->table = "food";
	$query->value = array(
		array("rating", $rating["avg"])
	);
	$query->where = array(
		array("fid", $fid)
	);
	UPDATE($query);
	$rating["maxcount"] /= 100;
	return $rating;
}
?>