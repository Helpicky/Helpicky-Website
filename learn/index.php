<!DOCTYPE html>
<?php
require("../function/common.php");
if($login === false)header("Location: ../login/");
if (isset($_POST["start"])) {
	setcookie($cfg['cookie']['name']."_learn", "1", time()+86400*7, "/");
	addmsgbox("success", "已開始新手教學模式，請根據提示訊息完成所有任務，欲停止請回到本頁面下方點選按鈕");
	addmsgbox("info", "第一步，前往設定頁面完成設定");
} else if (isset($_POST["stop"])) {
	setcookie($cfg['cookie']['name']."_learn", "", time(), "/");
	addmsgbox("success", "已結束新手教學模式");
} else if (isset($_POST["restart"])) {
	$query = new query;
	$query->table = "user";
	$query->value = array(
		array("learn", "")
	);
	$query->where = array(
		array("uid", $login["uid"])
	);
	UPDATE($query);
	$login = checklogin();
	addmsgbox("success", "已重設所有任務");
}
?>
<html lang="zh-Hant-TW">
<head>
<?php
require("../res/template/comhead.php");
showmeta();
?>
<title>新手教學-<?php echo $cfg['website']['name']; ?></title>
</head>
<body Marginwidth="-1" Marginheight="-1" Topmargin="0" Leftmargin="0">
<?php
require("../res/template/header.php");
?>
<div class="container-fluid">
<div class="row">
	<div class="col-xs-12 col-sm-offset-1 col-sm-10 col-md-offset-2 col-md-8">
		<h2>新手教學</h2>
		<table width="0" border="0" cellspacing="10" cellpadding="0" class="table">
		<tr>
			<td>完成設定</td>
			<td><?php echo (in_array(1, $login["learn"])?"完成":"未完成"); ?></td>
		</tr>
		<tr>
			<td>搜尋一項產品</td>
			<td><?php echo (in_array(2, $login["learn"])?"完成":"未完成"); ?></td>
		</tr>
		<tr>
			<td>檢視詳細資料</td>
			<td><?php echo (in_array(3, $login["learn"])?"完成":"未完成"); ?></td>
		</tr>
		<tr>
			<td>評分一項產品</td>
			<td><?php echo (in_array(4, $login["learn"])?"完成":"未完成"); ?></td>
		</tr>
		<tr>
			<td>加一項產品到日記</td>
			<td><?php echo (in_array(5, $login["learn"])?"完成":"未完成"); ?></td>
		</tr>
		<tr>
			<td>刪除一項產品日記</td>
			<td><?php echo (in_array(6, $login["learn"])?"完成":"未完成"); ?></td>
		</tr>
		<tr>
			<td>使用一次推薦</td>
			<td><?php echo (in_array(7, $login["learn"])?"完成":"未完成"); ?></td>
		</tr>
		<tr>
			<td>查看成就</td>
			<td><?php echo (in_array(8, $login["learn"])?"完成":"未完成"); ?></td>
		</tr>
		</table>
		<form method="post">
			<button type="submit" class="btn btn-success" name="start">
				<span class="glyphicon glyphicon-floppy-disk"></span>
				開始
			</button>
			<button type="submit" class="btn btn-success" name="stop">
				<span class="glyphicon glyphicon-floppy-disk"></span>
				結束
			</button>
			<button type="submit" class="btn btn-success" name="restart" onclick="if (!confirm('你確定嗎？所有任務都將未完成')) {return false;}">
				<span class="glyphicon glyphicon-floppy-disk"></span>
				重新開始
			</button>
		</form>
	</div>
</div>
</div>
<?php
require("../res/template/footer.php");
?>
</body>
</html>