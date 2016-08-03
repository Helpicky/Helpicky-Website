<!DOCTYPE html>
<?php
require("../function/common.php");
require("../function/formula.php");
if($login === false)header("Location: ../login/");
$date = $_GET["date"] ?? date("Y-m-d");
$show = $_GET["show"] ?? "1,2,3,4";
?>
<html lang="zh-Hant-TW">
<head>
<?php
require("../res/template/comhead.php");
showmeta();
?>
<title>日記-<?php echo $cfg['website']['name']; ?></title>
<link href="../res/css/diary.css" rel="stylesheet">
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
	$(function () {
		$('[data-toggle="tooltip"]').tooltip()
	})
</script>
</head>
<body Marginwidth="-1" Marginheight="-1" Topmargin="0" Leftmargin="0">
<?php
require("../res/template/header.php");
?>
<div class="container-fluid">
<div class="row">
	<div class="col-xs-12 col-sm-offset-1 col-sm-10 col-md-offset-2 col-md-8 col-lg-offset-3 col-lg-6">
		<?php
		$week = array("日", "一", "二", "三", "四", "五", "六");
		?>
		<h2>
			<div class="row">
				<div class="col-xs-12 col-sm-2">日記</div>
				<div class="col-sm-10 hidden-xs"><input type="date" name="date" value="<?php echo $date; ?>" max="<?php echo date("Y-m-d"); ?>" style="background-color: #efede9;" onchange="location='?date='+this.value+'&show=<?php echo $show; ?>'"> 星期<?php echo $week[date("w", strtotime($date))]; ?></div>
				<div class="col-xs-12 visible-xs-block"><small><input type="date" name="date" value="<?php echo $date; ?>" max="<?php echo date("Y-m-d"); ?>" style="background-color: #efede9;" onchange="location='?date='+this.value+'&show=<?php echo $show; ?>'"> 星期<?php echo $week[date("w", strtotime($date))]; ?></small></div>
			</div>
		</h2>
		<ul class="pager">
			<li><a href="?date=<?php echo date("Y-m-d", strtotime($date)-86400); ?>&show=<?php echo $show; ?>">←  前一天</a></li>
			<?php
			if ($date < date("Y-m-d")) {
			?>
			<li><a href="?date=<?php echo date("Y-m-d", strtotime($date)+86400); ?>&show=<?php echo $show; ?>">後一天  →</a></li>
			<li><a href="?date=<?php echo date("Y-m-d"); ?>&show=<?php echo $show; ?>">今天  →→</a></li>
			<?php
			}
			?>
		</ul>
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
		$nutritionlist = getnutritionlist();
		foreach ($nutritionlist as $nutrition) {
			$nutritionsum[$nutrition["id"]] = 0;
		}
		for ($meal=1; $meal <= 4; $meal++) { 
		?>
		<div class="row">
			<div class="col-xs-2 col-md-1" style="z-index: 3;"><img src="../res/image/diary/meal<?php echo $meal; ?>.png" width="50px" onclick="change_stats(<?php echo $meal; ?>)" class="position: inherit; "></div>
			<div class="col-xs-10 col-md-11" style="z-index: 2;">
				<div id="meal<?php echo $meal; ?>tool" style="display: none; padding-top: 0px; padding-bottom: 0px; padding-left: 30px; position: inherit; margin-top: 12px; margin-left: -20px; margin-bottom: 0px;" class="jumbotron">
					<button type="button" class="btn btn-info" data-toggle="tooltip" data-placement="bottom" title="掃描條碼以加入日記" style="color: #000; background-color: rgba(0, 0, 0, 0); border-color: rgba(0, 0, 0, 0);" onclick="alert('掃描功能尚未完成唷~')">
						<span class="glyphicon glyphicon-barcode"></span>
					</button>
					<button type="button" class="btn btn-default" data-toggle="tooltip" data-placement="bottom" title="搜尋產品以加入日記" style="color: #000; background-color: rgba(0, 0, 0, 0); border-color: rgba(0, 0, 0, 0);" onclick="location='../search/?date=<?php echo $date; ?>&meal=<?php echo $meal; ?>'">
						<span class="glyphicon glyphicon-search"></span>
					</button>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12" style="margin-top: 20px; z-index: 1;">
				<div id="meal<?php echo $meal; ?>food" style="display: none; text-align: center; padding-top: 10px; padding-bottom: 10px; position: inherit; margin-top: -35px; margin-left: 25px; margin-right: 10px;" class="jumbotron">
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
						$food = getfood($temp["fid"]);
						foreach ($nutritionlist as $nutrition) {
							$nutritionsum[$nutrition["id"]] += $food[$nutrition["id"]];
						}
						?>
						<a href="../info/?fid=<?php echo $food["fid"]; ?>" style="font-size:20px;"><?php echo $food["name"]; ?></a>
						<a href="del.php?hash=<?php echo $temp["hash"];?>&date=<?php echo $date; ?>&meal=<?php echo $temp["meal"]; ?>" style="font-size:20px;"><span class="glyphicon glyphicon-remove"></span></a><br>
						<?php
					}
				} else {
					echo '目前沒有紀錄，<a href="../search/?date='.$date.'&meal='.$meal.'">立即加入</a>';
				}
				?>
				</div>
			</div>
		</div>
		<?php
		}
		?>
		<div class="row">
			<div class="col-xs-12 text-center">
				<big>今日已攝取</big>
				<div id="chart_div"></div>
			</div>
		</div>
	</div>
</div>
</div>
<?php
require("../res/template/footer.php");
?>
<script type="text/javascript">
	<?php
	foreach (explode(",", $show) as $temp) {
		echo "change_stats(".$temp.");\n";
	}
	$usergoal = array();
	foreach (Nutrition($login["AE"]) as $key => $value) {
		if ($value["recommend"] != 0) {
			$usergoal[$key] = $value["recommend"];
		} else {
			$usergoal[$key] = $value["max"];
		}
	}
	?>

	google.charts.load('current', {'packages':['corechart']});
	google.charts.setOnLoadCallback(drawVisualization);

	function drawVisualization() {
		var data = google.visualization.arrayToDataTable([
			['營養', '攝取', '1/3', '2/3', '建議']
			<?php
			foreach ($nutritionlist as $nutrition) {
				echo ",['".$nutrition["name"]."', ".(100*$nutritionsum[$nutrition["id"]]/$usergoal[$nutrition["id"]]).", 33.3, 66.7, 100]";
			}
			?>
		]);

		var options = {
			// title : 'Monthly Coffee Production by Country',
			orientation: 'vertical',
			vAxis: {title: '營養'},
			hAxis: {title: '比例（%）'},
			seriesType: 'line',
			series: {0: {type: 'bars'}},
			backgroundColor : { fill:'transparent' },
			colors: ['#DBB6FF','#FE99BC','#FE99BC','#FF0000']
		};

		var chart = new google.visualization.ComboChart(document.getElementById('chart_div'));
		chart.draw(data, options);
	}
</script>
</body>
</html>