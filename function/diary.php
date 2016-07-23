<?php
require("../function/common.php");
if($login === false)header("Location: ../login/");
switch ($_POST["action"]) {
	case 'add':
		$query = new query;
		$query->table = "diary";
		$query->value = array(
			array("uid", $login["uid"]),
			array("date", $_POST["date"]),
			array("meal", $_POST["meal"]),
			array("fid", $_POST["fid"]),
			array("hash", getrandommd5())
		);
		INSERT($query);
		header("Location: ../".$_POST["return"]."/?show=".$_POST["meal"]);
		break;
	
	default:
		exit("Something went wrong!");
		break;
}
?>