<!DOCTYPE html>
<?php
require("../function/common.php");
?>
<html lang="zh-Hant-TW">
<head>
<?php
include_once("../res/template/comhead.php");
showmeta();
?>
<title><?php echo $cfg['website']['name']; ?></title>
</head>
<body topmargin="0" leftmargin="0" bottommargin="0">
<?php
require("../res/template/header.php");
?>
<div class="container-fluid">
<div class="row">
	<div class="col-md-offset-1 col-md-10" echo="keep">
		<h2 class="text-center">Helpicky help you pick it</h2>
		<img src="../res/image/icon/icon2-1024.png" class="col-xs-12"><br>
	</div>
</div>
</div>
<?php
	include("../res/template/footer.php");
?>
</body>
</html>