<!DOCTYPE html>
<?php
require("../function/common.php");
if($login === false)header("Location: ../login/");
$diary = array();
for ($i=1-$cfg['achievement']['show_days']; $i <= 0; $i++) { 
	$diary[date("Y-m-d", time()+86400*$i)] = 0;
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
	$diary[$temp["date"]] += $row2["calories"];
}
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
  var data = new google.visualization.DataTable();
  data.addColumn('string', '日期');
  data.addColumn('number', '熱量');

  data.addRows([
  	<?php
  	foreach ($diary as $key => $value) {
  		echo "['".date("m/d", strtotime($key))."', ".$value."],";
  	}
  	?>
  ]);

  var options = {
    hAxis: {
      title: '日期'
    },
    vAxis: {
      title: '熱量'
    },
    colors: ['#a52714']
  };

  var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
  chart.draw(data, options);
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
		<div id="chart_div"></div>
<?php
	include("../res/template/footer.php");
?>
</body>
</html>