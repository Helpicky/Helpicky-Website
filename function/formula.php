<?php
function BEE($gender, $weight, $height, $age){
	if ($weight == 0 || $height == 0 || $age == 0) {
		return 0;
	}
	if ($gender == 1) {
		return 66 + 13.7 * $weight + 5 * $height - 6.8 * $age;
	} else if ($gender == 2) {
		return 655 + 9.6 * $weight + 1.7 * $height - 4.7 * $age;
	} else {
		return 0;
	}
}

function EE($BEE, $activity_factor, $stress_factor){
	if ($BEE == 0 || $activity_factor == 0 || $stress_factor == 0) {
		return 0;
	}
	return $BEE * $activity_factor * $stress_factor;
}

function Nutrition($EE){
	$data = array(
		'calories' => array("min" => $EE * 0.9, "recommend" => $EE, "max" => $EE * 1.1),
		'protein' => array("min" => $EE * 0.10 / 4, "recommend" => $EE * 0.12 / 4, "max" => $EE * 0.15 / 4),
		'carbohydrates' => array("min" => $EE * 0.50 / 4, "recommend" => $EE * 0.58 / 4, "max" => $EE * 0.65 / 4),
		'fats' => array("min" => $EE * 0.25 / 9, "recommend" => $EE * 0.30 / 9, "max" => $EE * 0.35 / 9),
		'saturated_fats' => array("min" => 0, "recommend" => 0, "max" => $EE * 0.35 / 9),
		'trans_fats' => array("min" => 0, "recommend" => 0, "max" => $EE * 0.01 / 9),
		'sugar' => array("min" => 0, "recommend" => 0, "max" => $EE * 0.1 / 4),
		'sodium' => array("min" => 0, "recommend" => 0, "max" => 2000),
	);
	if ($EE >= 2700) {
		$data['milk'] = array("min" => 480, "recommend" => 0, "max" => 0);
	} else {
		$data['milk'] = array("min" => 360, "recommend" => 0, "max" => 0);
	}
	return $data;
}

function getMealNutrition($need, $eat, $get){
	$distribute = array(
		1 => array("protein"=>0.33, "fats"=>0.24, "carbohydrates"=>0.32),
		2 => array("protein"=>0.34, "fats"=>0.38, "carbohydrates"=>0.34),
		3 => array("protein"=>0.33, "fats"=>0.38, "carbohydrates"=>0.34)
	);
	$res = array(
		"carbohydrates" => $need["carbohydrates"] * $distribute[$get]["carbohydrates"] - $eat[$get]["carbohydrates"],
		"fats" => $need["fats"] * $distribute[$get]["fats"] - $eat[$get]["fats"],
		"protein" => $need["protein"] * $distribute[$get]["protein"] - $eat[$get]["protein"]
	);
	return $res;
}
?>