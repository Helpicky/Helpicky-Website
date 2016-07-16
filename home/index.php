<!DOCTYPE html>
<?php
require("../function/common.php");
?>
<html lang="zh-Hant-TW">
<head>
<?php
include_once("../res/template/comhead.php");
?>
<title>首頁-<?php echo $cfg['website']['name']; ?></title>
</head>
<body topmargin="0" leftmargin="0" bottommargin="0">
<?php
require("../res/template/header.php");
?>
<div class="row">
	<div class="col-md-offset-1 col-md-10">
		<h2>Helpicky</h2>
	</div>
</div>
<?php
	include("../res/template/footer.php");
?>
</body>
</html>