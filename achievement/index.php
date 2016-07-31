<!DOCTYPE html>
<?php
require("../function/common.php");
require("../function/formula.php");
if($login === false)header("Location: ../login/");
$nutritionlist = array(
	array("calories", "熱量"),
	array("protein", "蛋白質"),
	array("carbohydrates", "碳水化合物"),
	array("fats", "脂肪"),
	array("saturated_fats", "飽和脂肪"),
	array("trans_fats", "反式脂肪"),
	array("sugar", "糖"),
	array("sodium", "鈉")
);
$nutritionsum = array();
for ($i=1-$cfg['achievement']['show_days']; $i <= 0; $i++) { 
	$nutritionsum[date("Y-m-d", time()+86400*$i)] = array();
	foreach ($nutritionlist as $nutrition) {
		$nutritionsum[date("Y-m-d", time()+86400*$i)][$nutrition[0]] = 0;
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
		$nutritionsum[$diary["date"]][$nutrition[0]] += $food[$nutrition[0]];
	}
}
$usergoal = Nutrition($login["AE"]);
$usergoal["calories"] = $login["AE"];
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
	data.addColumn('number', '所需');
	data.addRows([
		<?php
		foreach ($nutritionsum as $date => $nutritionday) {
			echo "['".date("d", strtotime($date))."', ".$nutritionday[$nutrition[0]].", ".$usergoal[$nutrition[0]]."],";
		}
		?>
	]);
	var options = {
		hAxis: {
			title: '日期'
		},
		vAxis: {
			title: '<?php echo $nutrition[1]; ?>'
		},
		colors: ['#0011FF', '#FF0011']
	};
	new google.visualization.LineChart(document.getElementById('<?php echo $nutrition[0]; ?>_chart_div')).draw(data, options);
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
<div class="row">
	<div class="col-xs-12 col-sm-offset-1 col-sm-10 col-md-offset-1 col-md-10">
		<h2>成就</h2>
		<?php
		foreach ($nutritionlist as $nutrition) {
			echo '<div id="'.$nutrition[0].'_chart_div"></div>';
		}
		?>
<?php
	include("../res/template/footer.php");
?>
</body>
</html>