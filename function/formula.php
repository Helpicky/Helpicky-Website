<?php
function BEE($gender, $weight, $height, $age){
	if ($gender == 1) {
		return 66 + 13.7 * $weight + 5 * $height - 6.8 * $age;
	} else if ($gender == 2) {
		return 655 + 9.6 * $weight + 1.7 * $height - 4.7 * $age;
	} else {
		exit("function BEE gerder Error");
	}
}

function EE($BEE, $activity_factor, $stress_factor){
	return $BEE * $activity_factor * $stress_factor;
}

function Distribution($EE){
	$data = array(
		'protein' => $EE * 0.15,
		'carbohydrates' => $EE * 0.60,
		'fats' => $EE * 0.25,
		'saturated_fats' => $EE * 0.1,
		'trans_fats' => $EE * 0.01,
		'sugar' => $EE * 0.1,
		'sodium' => 2000
	);
	if ($EE >= 2700) {
		$data['milk'] = 480;
	} else {
		$data['milk'] = 360;
	}
	return $data;
}
?>