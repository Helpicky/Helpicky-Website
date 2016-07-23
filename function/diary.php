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
		$query = new query;
		$query->table = "food";
		$query->where = array(
			array("fid", $_POST["fid"])
		);
		$row = fetchone(INSERT($query));
		$query = new query;
		$query->table = "food";
		$query->value = array(
			array("diarycnt", ($row["diarycnt"]+1))
		);
		$query->where = array(
			array("fid", $_POST["fid"])
		);
		UPDATE($query);
		header("Location: ../".$_POST["return"]."/?show=".$_POST["meal"]);
		break;
	
	default:
		exit("Something went wrong!");
		break;
}
?>