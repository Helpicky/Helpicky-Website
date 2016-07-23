<!DOCTYPE html>
<?php
require("../function/common.php");
if($login === false)header("Location: ../login/");
$date = $_GET["date"] ?? date("Y-m-d");
$show = $_GET["show"] ?? "0";
?>
<html lang="zh-Hant-TW">
<head>
<meta charset="UTF-8">
<?php
require("../res/template/comhead.php");
?>
<title>日記-<?php echo $cfg['website']['name']; ?></title>
<style type="text/css">
#meal1tool {
	background-color: #e7543f;
}
#meal1food {
	background-color: #ffa88e;
}
#meal2tool {
	background-color: #ffaf3c;
}
#meal2food {
	background-color: #ffd297;
}
#meal3tool {
	background-color: #8bff1a;
}
#meal3food {
	background-color: #cbff98;
}
#meal4tool {
	background-color: #5eddfe;
}
#meal4food {
	background-color: #bbf1ff;
}
</style>
</head>
<body Marginwidth="-1" Marginheight="-1" Topmargin="0" Leftmargin="0">
<?php
require("../res/template/header.php");
?>
<div class="row">
	<div class="col-xs-12 col-sm-offset-1 col-sm-10 col-md-offset-1 col-md-10">
		<h2>日記</h2>
		<script type="text/javascript">
			function change_stats(id) {
				if (id <= 0 || id > 4) return ;
				if (document.all["meal"+id+"tool"].style.display=="none") {
					document.all["meal"+id+"tool"].style.display="";
					document.all["meal"+id+"food"].style.display="";
				} else {
					document.all["meal"+id+"tool"].style.display="none";
					document.all["meal"+id+"food"].style.display="none";
				}
			}
		</script>
		<?php
		for ($meal=1; $meal <= 4; $meal++) { 
		?>
		<div class="row">
			<div class="col-xs-3 col-md-1"><img src="../res/image/diary/meal<?php echo $meal; ?>.jpg" width="50px" onclick="change_stats(<?php echo $meal; ?>)"></div>
			<div class="col-xs-9 col-md-11">
				<div id="meal<?php echo $meal; ?>tool" style="display: none; padding-top: 0px; padding-bottom: 0px;" class="jumbotron">
					<button type="button" class="btn btn-info" style="color: #000; background-color: rgba(0, 0, 0, 0); border-color: rgba(0, 0, 0, 0);" onclick="alert('此功能尚未完成唷~')">
						<span class="glyphicon glyphicon-barcode"></span>
					</button>
					<a href="../search/?date=<?php echo date("Y-m-d"); ?>&meal=<?php echo $meal; ?>" class="btn" role="button" style="color: #000; background-color: rgba(0, 0, 0, 0); border-color: rgba(0, 0, 0, 0);">
						<span class="glyphicon glyphicon-search"></span>
					</a>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div id="meal<?php echo $meal; ?>food" style="display: none; text-align: center; padding-top: 10px; padding-bottom: 10px;" class="jumbotron">
				<?php
				$query = new query;
				$query->table = "diary";
				$query->where = array(
					array("uid", $login["uid"]),
					array("date", $date),
					array("meal", $meal)
				);
				$row = SELECT($query);
				if (count($row) != 0) {
					foreach ($row as $temp) {
						$query2 = new query;
						$query2->table = "food";
						$query2->where = array(
							array("fid", $temp["fid"])
						);
						$row2 = fetchone(SELECT($query2));
						?>
						<a href="../info/?fid=<?php echo $row2["fid"]; ?>"><?php echo $row2["name"]; ?></a><br>
						<?php
					}
				} else {
					echo "目前沒有加入";
				}
				?>
				</div>
			</div>
		</div>
		<?php
		}
		?>
<?php
	include("../res/template/footer.php");
?>
<script type="text/javascript">
	change_stats(<?php echo $show; ?>);
</script>
</body>
</html>