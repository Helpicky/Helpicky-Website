<!DOCTYPE html>
<?php
require("../function/common.php");
if($cfg['system']['require_login'] && $login === false)header("Location: ../login/");
?>
<html lang="zh-Hant-TW">
<head>
<?php
require("../res/template/comhead.php");
showmeta();
?>
<title>熱門商品-<?php echo $cfg['website']['name']; ?></title>
</head>
<body Marginwidth="-1" Marginheight="-1" Topmargin="0" Leftmargin="0">
<?php
require("../res/template/header.php");
?>
<div class="row">
	<div class="col-xs-12 col-sm-offset-1 col-sm-10 col-md-offset-1 col-md-10">
		<h2>熱門商品</h2>
		<div class="row">
			<div class="col-xs-12 col-sm-6 col-lg-3">
				<h3>評分最高</h3>
				<?php
				$query = new query;
				$query->table = "food";
				$query->where = array(
					array("hide", "0")
				);
				$query->order = array("rating", "DESC");
				$query->limit = "10";
				$row = SELECT($query);
				foreach ($row as $index => $temp) {
					echo ($index?"<br>":"");
					?><a href="../info/?fid=<?php echo $temp["fid"]; ?>"><?php echo $temp["name"]; ?></a>
					<?php echo "(".$temp["rating"].")";
				}
				?>
			</div>
			<div class="col-xs-12 col-sm-6 col-lg-3">
				<h3>點擊最多</h3>
				<?php
				$query = new query;
				$query->table = "food";
				$query->where = array(
					array("hide", "0")
				);
				$query->order = array("CTR", "DESC");
				$query->limit = "10";
				$row = SELECT($query);
				foreach ($row as $index => $temp) {
					echo ($index?"<br>":"");
					?><a href="../info/?fid=<?php echo $temp["fid"]; ?>"><?php echo $temp["name"]; ?></a>
					<?php echo "(".$temp["CTR"].")";
				}
				?>
			</div>
			<div class="col-xs-12 col-sm-6 col-lg-3">
				<h3>食用最多</h3>
				<?php
				$query = new query;
				$query->table = "food";
				$query->where = array(
					array("hide", "0")
				);
				$query->order = array("diarycnt", "DESC");
				$query->limit = "10";
				$row = SELECT($query);
				foreach ($row as $index => $temp) {
					echo ($index?"<br>":"");
					?><a href="../info/?fid=<?php echo $temp["fid"]; ?>"><?php echo $temp["name"]; ?></a>
					<?php echo "(".$temp["diarycnt"].")";
				}
				?>
			</div>
			<div class="col-xs-12 col-sm-6 col-lg-3">
				<h3>Helpicky指數最高</h3>
				<?php
				$query = new query;
				$query->table = "food";
				$query->column = array("*", "(`diarycnt`*".$cfg['hot']['index']['diarycnt']."+`rating`*".$cfg['hot']['index']['rating']."+`CTR`*".$cfg['hot']['index']['CTR'].") AS `index`");
				$query->where = array(
					array("hide", "0")
				);
				$query->order = array("index", "DESC");
				$query->limit = "10";
				$row = SELECT($query);
				foreach ($row as $index => $temp) {
					echo ($index?"<br>":"");
					?><a href="../info/?fid=<?php echo $temp["fid"]; ?>"><?php echo $temp["name"]; ?></a>
					<?php echo "(".$temp["index"].")";
				}
				?>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
			</div>
		</div>
	</div>
</div>
<?php
	include("../res/template/footer.php");
?>
</body>
</html>