<!DOCTYPE html>
<?php
require("../function/common.php");
require("../function/formula.php");
if($login === false)header("Location: ../login/");
$meal = $_POST["meal"] ?? null;
?>
<html lang="zh-Hant-TW">
<head>
<?php
require("../res/template/comhead.php");
showmeta();
?>
<title>飲食推薦-<?php echo $cfg['website']['name']; ?></title>
</head>
<body Marginwidth="-1" Marginheight="-1" Topmargin="0" Leftmargin="0">
<?php
require("../res/template/header.php");
?>
<div class="row">
	<div class="col-xs-12 col-sm-offset-2 col-sm-8 col-lg-offset-2 col-lg-8">
		<h2>飲食推薦</h2>
		<div class="row">
			<div class="col-xs-12">
				你要挑選一套...？<br>
				<form method="post" id="form">
					<input type="hidden" name="meal" id="meal">
					<img src="../res/image/diary/meal1.png" width="50px" onclick="meal.value=1; form.submit();">　
					<img src="../res/image/diary/meal2.png" width="50px" onclick="meal.value=2; form.submit();">　
					<img src="../res/image/diary/meal3.png" width="50px" onclick="meal.value=3; form.submit();">　
					<!-- <img src="../res/image/diary/meal4.png" width="50px" onclick="meal.value=4; form.submit();"> -->
				</form>
				<?php
				if ($meal) {
					for ($i=1; $i <= 4; $i++) { 
						$sum[$i] = array(
							"carbohydrates" => 0,
							"fats" => 0,
							"protein" => 0
						);
					}
					$query = new query;
					$query->table = "diary";
					$query->where = array(
						array("uid", $login["uid"]),
						array("date", date("Y-m-d"))
					);
					$row = SELECT($query);
					foreach ($row as $diary) {
						$food = getfood($diary["fid"]);
						$sum[$diary["meal"]]["carbohydrates"] += $food["carbohydrates"];
						$sum[$diary["meal"]]["fats"] += $food["fats"];
						$sum[$diary["meal"]]["protein"] += $food["protein"];
					}
					$need=array();
					foreach (Nutrition($login["AE"]) as $key => $value) {
						$need[$key] = $value["recommend"];
					}
					$mealnutrition = getMealNutrition($need, $sum, $meal);
					echo "你現在需要：碳水化合物".(int)$mealnutrition["carbohydrates"]."克 脂肪".(int)$mealnutrition["fats"]."克 蛋白質".(int)$mealnutrition["protein"]."克<br>";
					function diffpercent($nutrition, $need){
						$temp=abs(($nutrition-$need)/$need);
						if ($temp<0.05) return 100;
						if ($temp<0.10) return 95;
						if ($temp<0.15) return 90;
						if ($temp<0.20) return 85;
						if ($temp<0.25) return 80;
						if ($temp<0.30) return 60;
						return 0;
					}
					function cmp($a, $b) {
					    if ($a["score"] == $b["score"]) {
					        return 0;
					    }
					    return ($a["score"] < $b["score"]) ? 1 : -1;
					}
					switch ($meal) {
						case '1':
							$query = new query;
							$query->table = "food";
							$query->where = array(
								array("catid", "3", null, "OR"),
								array("catid", "5", null, "OR"),
								array("catid", "6", null, "OR"),
								array("catid", "8", null, "OR"),
								array("catid", "12", null, "OR"),
								array("catid", "11")
							);
							$mainlist = SELECT($query);
							$query = new query;
							$query->table = "food";
							$query->where = array(
								// array("catid", "1", null, "OR"), 
								array("catid", "16", null, "OR"),
								array("catid", "17")
							);
							$drinklist = SELECT($query);
							break;
						case '2':
						case '3':
							$query = new query;
							$query->table = "food";
							$query->where = array(
								array("catid", "5", null, "OR"),
								array("catid", "6", null, "OR"),
								array("catid", "7", null, "OR"),
								array("catid", "8", null, "OR"),
								array("catid", "12", null, "OR")
							);
							$mainlist = SELECT($query);
							$query = new query;
							$query->table = "food";
							$query->where = array(
								// array("catid", "1", null, "OR"), 
								array("catid", "16", null, "OR"),
								array("catid", "17")
							);
							$drinklist = SELECT($query);
							break;
						default:
							break;
					}
					// echo "現有 ".count($mainlist)." 項主餐與 ".count($drinklist)." 項飲料進行搭配<br>";
					$grouplist=array();
					foreach ($mainlist as $main) {
						foreach ($drinklist as $drink) {
								$temp=array(
									"carbohydrates" => $main["carbohydrates"]+$drink["carbohydrates"],
									"fats" => $main["fats"]+$drink["fats"],
									"protein" => $main["protein"]+$drink["protein"]
								);
								$temp["main"]=$main["fid"];
								$temp["drink"]=$drink["fid"];
								$temp["score"]=
									diffpercent($temp["carbohydrates"], $mealnutrition["carbohydrates"])+
									diffpercent($temp["fats"], $mealnutrition["fats"])+
									diffpercent($temp["protein"], $mealnutrition["protein"]);
								$grouplist[]=$temp;
						}
					}
					usort($grouplist, 'cmp');
					// echo count($grouplist)."項結果<br>";
					?>
					<ul class="list-group" id="contact-list">
					<?php
					foreach ($grouplist as $count => $temp) {
						if ($count>=200) break;
						?>
							<li class="list-group-item">
								<div class="row">
									<div class="col-xs-6 col-sm-6">
										<a href="../info/?fid=<?php echo $temp["main"]; ?>" target="_blank"><?php echo getfood($temp["main"])["name"]; ?></a><br>
										<a href="../info/?fid=<?php echo $temp["drink"]; ?>" target="_blank"><?php echo getfood($temp["drink"])["name"]; ?></a>
									</div>
									<div class="col-xs-6 col-sm-4">
										碳水化合物:<?php echo $temp["carbohydrates"]; ?>公克<br>
										脂肪:<?php echo $temp["fats"]; ?>公克<br>
										蛋白質:<?php echo $temp["protein"]; ?>公克<br>
										<!--<?php echo $temp["score"]; ?>分-->
									</div>
									<div class="col-xs-12 col-sm-2">
										<a href="../diary/add.php?meal=<?php echo $meal; ?>&fid=<?php echo $temp["main"]; ?>,<?php echo $temp["drink"]; ?>" class="btn btn-success btn-circle" role="button">
											<span class="glyphicon glyphicon-plus"></span>
										</a>
									</div>
								</div>
							</li>
						<?php
					}
					?>
					</ul>
					<?php
				}
				?>
			</div>
		</div>
	</div>
</div>
<?php
	include("../res/template/footer.php");
?>
</body>
</html>