<!DOCTYPE html>
<?php
require("../function/common.php");
if($login === false)header("Location: ../login/");
if (isset($_GET["hash"])) {
	$query = new query;
	$query->table = "diary";
	$query->where = array(
		array("hash", $_GET["hash"])
	);
	$row = fetchone(SELECT($query));
	$query = new query;
	$query->table = "diary";
	$query->where = array(
		array("hash", $_GET["hash"])
	);
	DELETE($query);
	$query = new query;
	$query->table = "food";
	$query->where = array(
		array("fid", $row["fid"])
	);
	$row2 = fetchone(SELECT($query));
	$query = new query;
	$query->table = "food";
	$query->value = array(
		array("diarycnt", ($row2["diarycnt"]-1))
	);
	$query->where = array(
		array("fid", $row["fid"])
	);
	UPDATE($query);
	header("Location: ./?date=".$_GET["date"]."&show=".$_GET["meal"]);
} else {
	addmsgbox("danger", "有些東西出錯了！請返回上一頁操作");
}
?>
<html lang="zh-Hant-TW">
<head>
<?php
require("../res/template/comhead.php");
showmeta();
?>
<title>刪除日記-<?php echo $cfg['website']['name']; ?></title>
</head>
<body Marginwidth="-1" Marginheight="-1" Topmargin="0" Leftmargin="0">
<?php
require("../res/template/header.php");
?>
<div class="row">
</div>
</body>
</html>