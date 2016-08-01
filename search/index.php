<!DOCTYPE html>
<?php
require("../function/common.php");
require("../function/checkallergen.php");
if($cfg['system']['require_login'] && $login === false)header("Location: ../login/");
$search = $_GET["name"] ?? "";
$date = $_GET["date"] ?? "";
$meal = $_GET["meal"] ?? "";
?>
<html lang="zh-Hant-TW">
<head>
<?php
require("../res/template/comhead.php");
showmeta();
?>
<title>搜尋-<?php echo $cfg['website']['name']; ?></title>
</head>
<body Marginwidth="-1" Marginheight="-1" Topmargin="0" Leftmargin="0">
<?php
require("../res/template/header.php");
?>
<div class="row">
	<div class="col-xs-12 col-sm-offset-2 col-sm-8 col-lg-offset-2 col-lg-8">
		<h2>搜尋</h2>
		<div class="row">
			<div class="col-xs-12">
				<form method="get">
					<input type="hidden" name="date" value="<?php echo $date; ?>">
					<input type="hidden" name="meal" value="<?php echo $meal; ?>">
					<div class="input-group">
						<input class="form-control" name="name" type="text" value="<?php echo $search; ?>" placeholder="搜尋" maxlength="20" autofocus>
						<span class="input-group-btn">
							<button type="submit" class="btn btn-info">
								<span class="glyphicon glyphicon-search"></span>
							</button>
						</span>
					</div>
				</form>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12">
				熱門關鍵字：
				<?php
				$query = new query;
				$query->table = "keyword";
				$query->where = array(
					array("count", $cfg['search']['show']['threshold'], ">=")
				);
				$query->order = array("count", "DESC");
				$query->limit = $cfg['search']['show']['number'];
				$row = SELECT($query);
				foreach ($row as $index => $temp) {
					echo ($index?"、":"");
					?><a href="?name=<?php echo $temp["keyword"]; ?>&date=<?php echo $date; ?>&meal=<?php echo $meal; ?>"><?php echo $temp["keyword"]; ?></a>
					<?php echo "(".$temp["count"].")";
				}
				?>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<form method="post" action="../function/diary.php">
				<input type="hidden" name="action" value="add">
				<input type="hidden" name="return" value="diary">
				<input type="hidden" name="date" value="<?php echo $date; ?>">
				<input type="hidden" name="meal" value="<?php echo $meal; ?>">
				<ul class="list-group" id="contact-list">
				<?php
				if ($search != "") {
					$lasttime = @file_get_contents("../log/search/CTR/".$login["uid"]."-".$search.".log");
					if ($lasttime == "" || time() - $lasttime > $cfg['search']['CTR']['cooldown']) {
						$query = new query;
						$query->table = "keyword";
						$query->where = array("keyword", $search);
						$row = fetchone(SELECT($query));
						if ($row == null) {
							$query = new query;
							$query->table = "keyword";
							$query->value = array("keyword", $search);
							INSERT($query);
						} else {
							$query = new query;
							$query->table = "keyword";
							$query->value = array("count", ($row["count"]+1));
							$query->where = array("keyword", $search);
							UPDATE($query);
						}
						$t=file_put_contents("../log/search/CTR/".$login["uid"]."-".$search.".log", time());
					}
					$query = new query;
					$query->table = "food";
					$query->where = array(
						array("name", str_replace("+", "[+]", @$search), "REGEXP"),
						array("hide", "0")
					);
					$query->order = array("CTR", "DESC");
					$foodlist = SELECT($query);
					if (count($foodlist) > 0) {
						foreach($foodlist as $food){
						?>
							<li class="list-group-item" style="height: 120px">
								<div class="row">
									<div class="col-xs-6 col-sm-6">
										<a href="../info/?fid=<?php echo $food["fid"]; ?>&date=<?php echo $date; ?>&meal=<?php echo $meal; ?>">
											<span><?php echo $food["name"]; ?></span><br>
											<span><?php echo $food["calories"]; ?>大卡</span><br>
											<?php
											$allergenlist = checkallergen($login["allergen"], $food["allergen"]);
											if (count($allergenlist)) {
												?><span style="color: red;"><span class="glyphicon glyphicon-alert" aria-hidden="true"></span>過敏原警告</span><?php
											}
											?>
										</a>
									</div>
									<div class="col-xs-4 col-sm-4">
										<span>平均<?php echo $food["rating"]; ?>分</span><br>
										<span>點擊<?php echo $food["CTR"]; ?>次</span>
									</div>

									<div class="col-xs-2 col-sm-2">
										<a href="../diary/add.php?date=<?php echo $date; ?>&meal=<?php echo $meal; ?>&fid=<?php echo $food["fid"]; ?>" class="btn btn-success btn-circle" role="button">
											<span class="glyphicon glyphicon-plus"></span>
										</a>
									</div>
								</div>
							</li>
						<?php
						}
					} else {
					?>
					<li>查無搜尋結果</li>
					<?php
					}
				}
				?>
				</ul>
				</form>
			</div>
		</div>
<?php
	include("../res/template/footer.php");
?>
</body>
</html>