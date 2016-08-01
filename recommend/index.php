<!DOCTYPE html>
<?php
require("../function/common.php");
require("../function/formula.php");
if($login === false)header("Location: ../login/");
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
	<div class="col-xs-12 col-sm-offset-1 col-sm-10 col-md-offset-1 col-md-10">
		<h2>飲食推薦</h2>
		<div class="row">
			<div class="col-xs-12">
				你要推薦一套...？<br>
				<form method="post" id="form">
					<input type="hidden" name="meal" id="meal">
					<img src="../res/image/diary/meal1.png" width="50px" onclick="meal.value=1; form.submit();">　
					<img src="../res/image/diary/meal2.png" width="50px" onclick="meal.value=2; form.submit();">　
					<img src="../res/image/diary/meal3.png" width="50px" onclick="meal.value=3; form.submit();">　
					<!-- <img src="../res/image/diary/meal4.png" width="50px" onclick="meal.value=4; form.submit();"> -->
				</form>
				<?php
				if (isset($_POST["meal"])) {
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
					$mealnutrition = getMealNutrition($need, $sum, $_POST["meal"]);
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
					switch ($_POST["meal"]) {
						case '1':
							$query = new query;
							$query->table = "food";
							$query->where = array(
								array("catid", "3", null, "OR"), //蒸箱
								array("catid", "5", null, "OR"), //關東煮
								array("catid", "6", null, "OR"), //飯糰、壽司、手卷
								array("catid", "8", null, "OR"), //三明治/漢堡
								array("catid", "12", null, "OR"), //麵包
								array("catid", "11") //蛋糕甜點
							);
							$mainlist = SELECT($query);
							$query = new query;
							$query->table = "food";
							$query->where = array(
								array("catid", "1", null, "OR"), 
								array("catid", "16", null, "OR"),
								array("catid", "17")
							);
							$drinklist = SELECT($query);
							echo "現有 ".count($mainlist)." 項主餐與 ".count($drinklist)." 項飲料進行搭配<br>";
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
							function cmp($a, $b) {
							    if ($a["score"] == $b["score"]) {
							        return 0;
							    }
							    return ($a["score"] < $b["score"]) ? 1 : -1;
							}
							usort($grouplist, 'cmp');
							foreach ($grouplist as $count => $temp) {
								if ($count>=200) break;
								echo getfood($temp["main"])["name"]."+".getfood($temp["drink"])["name"]." 碳水化合物:".$temp["carbohydrates"]." 脂肪:".$temp["carbohydrates"]." 蛋白質:".$temp["protein"]." ".$temp["score"]."分<br>";
							}
							break;
						default:
							break;
					}
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