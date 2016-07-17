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
?>