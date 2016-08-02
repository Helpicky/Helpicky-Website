<!DOCTYPE html>
<?php
require("../function/common.php");
require("../function/checkallergen.php");
if($login === false)header("Location: ../login/");
$date = $_GET["date"] ?? null;
$meal = $_GET["meal"] ?? null;
$fidlist = $_GET["fid"] ?? "";
$fidlist = explode(",", $fidlist);

$allergenlist = array();
foreach ($fidlist as $fid) {
	$food = getfood($fid);
	$allergenlist = array_merge($allergenlist, checkallergen($login["allergen"], $food["allergen"]));
}
$allergenlist = array_unique($allergenlist);

if ($fidlist[0] == "") {
	addmsgbox("danger", "食物ID遺失！請返回上一頁操作");
} else if (count($allergenlist) && !isset($_GET["allergencheck"])) {
	addmsgbox("danger", '<span class="glyphicon glyphicon-alert" aria-hidden="true"></span>
過敏原警告！此產品包含以下的過敏原：'.implode("、", $allergenlist)."。你確定要加入嗎？");
} else if ($date != null && $meal != null && 1 <= $meal && $meal <= 4) {
	foreach ($fidlist as $fid) {
		var_dump($fid);
		var_dump(getrandommd5());
		$query = new query;
		$query->table = "diary";
		$query->value = array(
			array("uid", $login["uid"]),
			array("date", $date),
			array("meal", $meal),
			array("fid", $fid),
			array("hash", getrandommd5())
		);
		INSERT($query);
		$query = new query;
		$query->table = "food";
		$query->value = array(
			array("diarycnt", ($food["diarycnt"]+1))
		);
		$query->where = array(
			array("fid", $fid)
		);
		UPDATE($query);
	}
	header("Location: ./?date=".$_GET["date"]."&show=".$meal);
}
?>
<html lang="zh-Hant-TW">
<head>
<?php
require("../res/template/comhead.php");
showmeta();
?>
<title>新增日記-<?php echo $cfg['website']['name']; ?></title>
</head>
<body Marginwidth="-1" Marginheight="-1" Topmargin="0" Leftmargin="0">
<?php
require("../res/template/header.php");
?>
<div class="row">
	<div class="col-xs-12 col-sm-offset-1 col-sm-10 col-md-offset-1 col-md-10">
		<h2>新增日記</h2>
		<form method="get" id="form">
			<input type="hidden" name="fid" value="<?php echo $_GET["fid"] ?? ""; ?>">
			<input type="hidden" name="meal" id="meal" value="<?php echo $meal ?? ""; ?>">
			<input type="hidden" name="allergencheck">
			<div class="input-group">
				<span class="input-group-addon">日期</span>
				<input class="form-control" name="date" type="date" value="<?php echo ($_GET["date"] ? $_GET["date"] : date("Y-m-d")); ?>" required>
			</div>
			<br>
			<img src="../res/image/diary/meal1.png" width="50px" onclick="meal.value=1; form.submit();">　
			<img src="../res/image/diary/meal2.png" width="50px" onclick="meal.value=2; form.submit();">　
			<img src="../res/image/diary/meal3.png" width="50px" onclick="meal.value=3; form.submit();">　
			<img src="../res/image/diary/meal4.png" width="50px" onclick="meal.value=4; form.submit();">
		</form>
	</div>
</div>
</body>
</html>