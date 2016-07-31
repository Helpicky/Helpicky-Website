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
$diary = array();
for ($i=1-$cfg['achievement']['show_days']; $i <= 0; $i++) { 
	$diary[date("Y-m-d", time()+86400*$i)] = array();
	foreach ($nutritionlist as $temp) {
		$diary[date("Y-m-d", time()+86400*$i)][$temp[0]] = 0;
	}
}
$query = new query;
$query->table = "diary";
$query->where = array(
	array("uid", $login["uid"]),
	array("date", date("Y-m-d", time()-86400*($cfg['achievement']['show_days']-1)), ">=")
);
$row = SELECT($query);
foreach ($row as $temp) {
	$query2 = new query;
	$query2->table = "food";
	$query2->where = array(
		array("fid", $temp["fid"])
	);
	$row2 = fetchone(SELECT($query2));
	foreach ($nutritionlist as $temp2) {
		$diary[$temp["date"]][$temp2[0]] += $row2[$temp2[0]];
	}
}
$nutrition = Nutrition($login["AE"]);
$nutrition["calories"] = $login["AE"];
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
	foreach ($nutritionlist as $temp) {
	?>
	var data = new google.visualization.DataTable();
	data.addColumn('string', '日期');
	data.addColumn('number', '食用');
	data.addColumn('number', '所需');
	data.addRows([
		<?php
		foreach ($diary as $key => $value) {
			echo "['".date("d", strtotime($key))."', ".$value[$temp[0]].", ".$nutrition[$temp[0]]."],";
		}
		?>
	]);
	var options = {
		hAxis: {
			title: '日期'
		},
		vAxis: {
			title: '<?php echo $temp[1]; ?>'
		},
		colors: ['#0011FF', '#FF0011']
	};
	new google.visualization.LineChart(document.getElementById('<?php echo $temp[0]; ?>_chart_div')).draw(data, options);
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
		foreach ($nutritionlist as $temp) {
			echo '<div id="'.$temp[0].'_chart_div"></div>';
		}
		?>
<?php
	include("../res/template/footer.php");
?>
</body>
</html>