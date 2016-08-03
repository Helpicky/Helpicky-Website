<!DOCTYPE html>
<?php
require("../function/common.php");
require("../function/formula.php");
if($login === false)header("Location: ../login/");
$nutritionlist = getnutritionlist();
$nutritionsum = array();
for ($i=1-$cfg['achievement']['show_days']; $i <= 0; $i++) { 
	$nutritionsum[date("Y-m-d", time()+86400*$i)] = array();
	foreach ($nutritionlist as $nutrition) {
		$nutritionsum[date("Y-m-d", time()+86400*$i)][$nutrition["id"]] = 0;
		$nutritionsum[date("Y-m-d", time()+86400*$i)]["record"] = false;
	}
}
$query = new query;
$query->table = "diary";
$query->where = array(
	array("uid", $login["uid"]),
	array("date", date("Y-m-d", time()-86400*($cfg['achievement']['show_days']-1)), ">=")
);
$diarylist = SELECT($query);
foreach ($diarylist as $diary) {
	$food = getfood($diary["fid"]);
	foreach ($nutritionlist as $nutrition) {
		$nutritionsum[$diary["date"]][$nutrition["id"]] += $food[$nutrition["id"]];
		$nutritionsum[$diary["date"]]["record"] = true;
	}
}
$usergoal = Nutrition($login["AE"]);
$usergoal["calories"] = array("min" => $login["AE"] * 0.9, "recommend" => $login["AE"], "max" => $login["AE"] * 1.1);
?>
<html lang="zh-Hant-TW">
<head>
<meta charset="UTF-8">
<?php
require("../res/template/comhead.php");
showmeta();
?>
<title>成就-<?php echo $cfg['website']['name']; ?></title>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
google.charts.load('current', {packages: ['corechart', 'line']});
google.charts.setOnLoadCallback(drawLineColors);
function drawLineColors() {
	<?php 
	foreach ($nutritionlist as $nutrition) {
	?>
	var data = new google.visualization.DataTable();
	data.addColumn('string', '日期');
	data.addColumn('number', '食用');
	<?php
	if ($usergoal[$nutrition["id"]]["max"] != 0) {
		echo "data.addColumn('number', '建議上限');";
	}
	if ($usergoal[$nutrition["id"]]["min"] != 0) {
		echo "data.addColumn('number', '建議下限');";
	}
	?>
	data.addRows([
		<?php
		foreach ($nutritionsum as $date => $nutritionday) {
			echo "['".date("d", strtotime($date))."',";
			if ($nutritionday["record"]) {
				echo $nutritionday[$nutrition["id"]];
			}
			if ($usergoal[$nutrition["id"]]["max"] != 0) {
				echo ",".$usergoal[$nutrition["id"]]["max"];
			}
			if ($usergoal[$nutrition["id"]]["min"] != 0) {
				echo ",".$usergoal[$nutrition["id"]]["min"];
			}
			echo "],";
		}
		?>
	]);
	var options = {
		hAxis: {
			title: '日期'
		},
		vAxis: {
			title: '<?php echo $nutrition["name"]; ?>（<?php echo $nutrition["unit"]; ?>）',
			titleTextStyle: {italic: false}
		},
		colors: ['#0000FF'
		<?php
		if ($usergoal[$nutrition["id"]]["max"] != 0) {
			echo ",'#FF0000'";
		}
		if ($usergoal[$nutrition["id"]]["min"] != 0) {
			echo ",'#FE99BC'";
		}
		?>
		]
	};
	new google.visualization.LineChart(document.getElementById('<?php echo $nutrition["id"]; ?>_chart_div')).draw(data, options);
	<?php
	}
	?>
}
</script>
</head>
<body Marginwidth="-1" Marginheight="-1" Topmargin="0" Leftmargin="0">
<?php
require("../res/template/header.php");
?>
<div class="container-fluid">
<div class="row">
	<div class="col-xs-12 col-sm-offset-1 col-sm-10 col-md-offset-1 col-md-10">
		<h2>成就</h2>
		<?php
		foreach ($nutritionlist as $nutrition) {
			echo '<div id="'.$nutrition["id"].'_chart_div"></div>';
		}
		?>
	</div>
</div>
</div>
<?php
require("../res/template/footer.php");
?>
</body>
</html>