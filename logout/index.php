<!DOCTYPE html>
<?php
require("../function/common.php");
if (isset($_COOKIE[$cfg['cookie']['name']])) {
	$query = new query;
	$query->table = "session";
	$query->where = array(
		array("cookie", $_COOKIE[$cfg['cookie']['name']])
	);
	DELETE($query);
}
setcookie($cfg['cookie']['name'], "", time(), "/");
$login = false;
?>
<html lang="zh-Hant-TW">
<head>
<?php
require("../res/template/comhead.php");
showmeta();
?>
<title>登出-<?php echo $cfg['website']['name']; ?></title>
</head>
<body Marginwidth="-1" Marginheight="-1" Topmargin="0" Leftmargin="0">
<?php
addmsgbox("success", "已登出", false);
require("../res/template/header.php");
require("../res/template/footer.php");
?>
<script>setTimeout(function(){location="../home/";}, 5000)</script>
</body>
</html>