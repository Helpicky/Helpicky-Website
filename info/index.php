<!DOCTYPE html>
<?php
require("../function/common.php");
require("../function/checkallergen.php");
if($cfg['system']['require_login'] && $login === false)header("Location: ../login/");
$fid = $_GET["fid"];
$query = new query;
$query->table = "rating";
$query->where = array(
	array("uid", $login["uid"]),
	array("fid", $fid)
);
$rating = fetchone(SELECT($query))["rating"] ?? "0";
if (isset($_POST["rating"])) {
	if ($_POST["rating"] == 0) {
		$query = new query;
		$query->table = "rating";
		$query->where = array(
			array("uid", $login["uid"]),
			array("fid", $fid)
		);
		DELETE($query);
		$rating = 0;
		addmsgbox("success", "已取消評分");
	} else if($rating == 0) {
		$query = new query;
		$query->table = "rating";
		$query->value = array(
			array("uid", $login["uid"]),
			array("fid", $fid),
			array("rating", $_POST["rating"])
		);
		INSERT($query);
		$rating = $_POST["rating"];
		addmsgbox("success", "感謝你的評分");
	} else {
		$query = new query;
		$query->table = "rating";
		$query->value = array(
			array("rating", $_POST["rating"])
		);
		$query->where = array(
			array("uid", $login["uid"]),
			array("fid", $fid)
		);
		UPDATE($query);
		$rating = $_POST["rating"];
		addmsgbox("success", "已修改評分");
	}
}
$query = new query;
$query->table = "food";
$query->where = array(
	array("fid", $fid)
);
$food = fetchone(SELECT($query));
$allergenlist = checkallergen($login["allergen"], $food["allergen"]);
if (count($allergenlist)) {
	addmsgbox("danger", '<span class="glyphicon glyphicon-alert" aria-hidden="true"></span>
過敏原警告！此產品包含以下的過敏原：'.implode("、", $allergenlist));
}
?>
<html lang="zh-Hant-TW">
<head>
<?php
require("../res/template/comhead.php");
setmeta("og:description", $food["name"]);
showmeta();
?>
<title>產品資訊-<?php echo $cfg['website']['name']; ?></title>
<script src="../res/js/bootstrap-rating-input.js"></script>
</head>
<body Marginwidth="-1" Marginheight="-1" Topmargin="0" Leftmargin="0">
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.7&appId=1740035992902253";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<?php
require("../res/template/header.php");
?>
<div class="row">
	<div class="col-xs-12 col-md-offset-1 col-md-10">
		<h2>
			<?php echo $food["name"]; ?>
			<a href="../diary/add.php?date=<?php echo $_GET["date"]??""; ?>&meal=<?php echo $_GET["meal"]??""; ?>&fid=<?php echo $fid; ?>" class="btn btn btn-success btn-circle" role="button">
				<span class="glyphicon glyphicon-plus"></span>
			</a>
		</h2>
		<button type="button" class="btn btn-primary btn-md" data-toggle="modal" data-target="#myModal">
			檢視產品資訊
		</button>
		<div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="myModalLabel">
							產品資訊　<small>來源：全家食在購安心　連結：<a href="http://foodsafety.family.com.tw/index.php/resume/food?product_id=<?php echo $food["familyid"]; ?>" target="_blank">http://foodsafety.family.com.tw/index.php/resume/food?product_id=<?php echo $food["familyid"]; ?></a></small>
						</h4>
					</div>
					<div class="modal-body">
						<iframe src="http://foodsafety.family.com.tw/index.php/resume/food?product_id=<?php echo $food["familyid"]; ?>" style="width: 100%; height: 400px;"></iframe>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">關閉</button>
					</div>
				</div>
			</div>
		</div>
		<br>
		<?php
		$lasttime = @file_get_contents("../log/info/CTR/".$login["uid"]."-".$fid.".log");
		if ($lasttime == "" || time() - $lasttime > $cfg['info']['CTR']['cooldown']) {
			$query = new query;
			$query->table = "food";
			$query->where = array("fid", $fid);
			$row = fetchone(SELECT($query));
			$query = new query;
			$query->table = "food";
			$query->value = array("CTR", ($row["CTR"]+1));
			$query->where = array("fid", $fid);
			UPDATE($query);
			$t=file_put_contents("../log/info/CTR/".$login["uid"]."-".$fid.".log", time());
		}
		$query = new query;
		$query->table = "diary";
		$query->column = "COUNT(*) AS `count`";
		$query->where = array(
			array("fid", $food["fid"])
		);
		$count = fetchone(SELECT($query))["count"];
		$ratingall = getrating($food["fid"]);
		?>
		<div class="row">
			<div class="col-xs-12 col-sm-5 col-md-5 col-lg-4 text-center">
				<span style="font-size: 50px"><?php echo number_format($ratingall["avg"], 1); ?></span><br>
				<span class="glyphicon glyphicon-user"></span> 總評分次數：<?php echo $ratingall["count"]; ?><br>
				<span class="glyphicon glyphicon-eye-open"></span> 總點擊次數：<?php echo $food["CTR"]; ?><br>
				<span class="glyphicon glyphicon-cutlery"></span> 總食用次數：<?php echo $count; ?><br>
				<form method="post" id="ratingf">
					<span style="font-size: 25px">我的評分 <input type="number" name="rating" class="rating" id="ratingi" data-clearable="" value="<?php echo $rating; ?>"></span>
					<script>
						$(function(){
							$('#ratingi').on('change', function(){
								ratingf.submit();
							});
						});
					</script>
				</form>
			</div>
			<?php
			?>
			<div class="col-xs-12 col-sm-7 col-md-7 col-lg-8 text-left">
				<div class="row">
					<div class="col-xs-2 col-lg-1 text-right"><span class="glyphicon glyphicon-star"></span>5</div>
					<div class="col-xs-10 col-lg-11">
						<div class="progress"><div class="progress-bar progress-bar-primary" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($ratingall[5]/$ratingall["maxcount"]);?>%"></div></div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-2 col-lg-1 text-right"><span class="glyphicon glyphicon-star"></span>4</div>
					<div class="col-xs-10 col-lg-11">
						<div class="progress"><div class="progress-bar progress-bar-info" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($ratingall[4]/$ratingall["maxcount"]);?>%"></div></div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-2 col-lg-1 text-right"><span class="glyphicon glyphicon-star"></span>3</div>
					<div class="col-xs-10 col-lg-11">
						<div class="progress"><div class="progress-bar progress-bar-success" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($ratingall[3]/$ratingall["maxcount"]);?>%"></div></div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-2 col-lg-1 text-right"><span class="glyphicon glyphicon-star"></span>2</div>
					<div class="col-xs-10 col-lg-11">
						<div class="progress"><div class="progress-bar progress-bar-warning" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($ratingall[2]/$ratingall["maxcount"]);?>%"></div></div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-2 col-lg-1 text-right"><span class="glyphicon glyphicon-star"></span>1</div>
					<div class="col-xs-10 col-lg-11">
						<div class="progress"><div class="progress-bar progress-bar-danger" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo ($ratingall[1]/$ratingall["maxcount"]);?>%"></div></div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12">
				<div class="fb-comments" data-href="http://helpicky.twbbs.org/info/?fid=<?php echo $food["fid"]; ?>" data-width="100%" data-numposts="5"></div>
			</div>
		</div>
	</div>
</div>
<?php
require("../res/template/footer.php");
?>
</body>
</html>
