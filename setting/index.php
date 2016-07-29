<!DOCTYPE html>
<?php
require("../function/common.php");
if($login === false)header("Location: ../login/");
else if (isset($_POST["submit"])) {
	$query = new query;
	$query->table = "user";
	$query->value = array(
		array("nickname", $_POST["nickname"]),
		array("height", $_POST["height"]),
		array("weight", $_POST["weight"]),
		array("age", $_POST["age"]),
		array("BEE", $_POST["BEE"]),
		array("activity_factor", $_POST["activity_factor"]),
		array("stress_factor", $_POST["stress_factor"]),
		array("EE", $_POST["EE"]),
		array("EE_diff", $_POST["EE_diff"]),
		array("allergen", $_POST["allergen"])
	);
	if (isset($_POST["gender"])) {
		$query->value[] = array("gender", $_POST["gender"]);
	}
	if ($_POST["password"] !== "") {
		$query->value[] = array("password", password_hash($_POST["password"], PASSWORD_DEFAULT));
	}
	$query->where = array(
		array("uid", $login["uid"])
	);
	UPDATE($query);
	$login = checklogin();
	addmsgbox("success", "已更新");
} else if (isset($_POST["auto"])) {
	require("../function/formula.php");
	switch ($_POST["auto"]) {
		case 'BEE':
			$value = BEE($_POST["gender"], $_POST["weight"], $_POST["height"], $_POST["age"]);
			$query = new query;
			$query->table = "user";
			$query->value = array(
				array("gender", $_POST["gender"]),
				array("height", $_POST["height"]),
				array("weight", $_POST["weight"]),
				array("age", $_POST["age"]),
				array("BEE", $value)
			);
			$query->where = array(
				array("uid", $login["uid"])
			);
			UPDATE($query);
			$login = checklogin();
			addmsgbox("success", "已自動設定 基本能量消耗（BEE） 為 ".$login["BEE"]);
			break;
		case 'EE':
			$value = EE($_POST["BEE"], $_POST["activity_factor"], $_POST["stress_factor"]);
			$query = new query;
			$query->table = "user";
			$query->value = array(
				array("BEE", $_POST["BEE"]),
				array("activity_factor", $_POST["activity_factor"]),
				array("stress_factor", $_POST["stress_factor"]),
				array("EE", $value)
			);
			$query->where = array(
				array("uid", $login["uid"])
			);
			UPDATE($query);
			$login = checklogin();
			addmsgbox("success", "已自動設定 熱量消耗（Energy Expenditure） 為 ".$login["EE"]);
			break;
		default:
			addmsgbox("danger", "自動設定發生錯誤");
			break;
	}
}
?>
<html lang="zh-Hant-TW">
<head>
<?php
require("../res/template/comhead.php");
showmeta();
?>
<title>設定-<?php echo $cfg['website']['name']; ?></title>
</head>
<body Marginwidth="-1" Marginheight="-1" Topmargin="0" Leftmargin="0">
<?php
require("../res/template/header.php");
?>
<div class="row">
	<div class="col-xs-12 col-sm-offset-1 col-sm-10 col-md-offset-1 col-md-10">
		<h2>設定</h2>
		<form method="post">
		<table width="0" border="0" cellspacing="10" cellpadding="0" class="table">
		<tr>
			<th>參數</th>
			<th>數值</th>
			<th>自動設定</th>
		</tr>
		<tr>
			<td>暱稱</td>
			<td><input type="text" name="nickname" value="<?php echo $login['nickname']; ?>"></td>
			<td></td>
		</tr>
		<tr>
			<td>性別</td>
			<td>
				<input type="radio" name="gender" value="1" <?php echo ($login["gender"]==1?"checked":""); ?>>男
				<input type="radio" name="gender" value="2" <?php echo ($login["gender"]==2?"checked":""); ?>>女
			</td>
			<td></td>
		</tr>
		<tr>
			<td>身高</td>
			<td><input type="number" step="0.1" min="0" max="999.9" name="height" value="<?php echo $login['height']; ?>"></td>
			<td></td>
		</tr>
		<tr>
			<td>體重</td>
			<td><input type="number" step="0.1" min="0" max="999.9" name="weight" value="<?php echo $login['weight']; ?>"></td>
			<td></td>
		</tr>
		<tr>
			<td>年齡</td>
			<td><input type="number" min="0" max="999" name="age" value="<?php echo $login['age']; ?>"></td>
			<td></td>
		</tr>
		<tr>
			<td>基本能量消耗（BEE）</td>
			<td><input type="number" step="0.1" min="0" max="9999" name="BEE" value="<?php echo $login['BEE']; ?>"></td>
			<td>
				<button type="submit" class="btn btn-primary" name="auto" value="BEE">根據性別、身高、體重、年齡</button>
			</td>
		</tr>
		<tr>
			<td>活動因素（Activity Factor）</td>
			<td><input type="number" step="0.01" min="0" max="9.9" name="activity_factor" value="<?php echo $login['activity_factor']; ?>" id="tAF"></td>
			<td>
				<select onchange="if (this.value!='') tAF.value=this.value">
					<option value="">請選擇</option>
					<option value="1.0">臥床 1.0</option>
					<option value="1.2">輕度運動 1.2</option>
					<option value="1.4">中度運動 1.4</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>壓力因素（Stress Factor）</td>
			<td><input type="number" step="0.01" min="0" max="9.9" name="stress_factor" value="<?php echo $login['stress_factor']; ?>" id="tSF"></td>
			<td>
				<select onchange="if (this.value!='') tSF.value=this.value">
					<option value="">請選擇</option>
					<option value="1.0">正常壓力 1.0</option>
					<option value="1.4">生長 1.4</option>
					<option value="1.1">懷孕 1.1</option>
					<option value="1.4">哺乳 1.4</option>
					<option value="1.13">發燒1℃ 1.13</option>
					<option value="1.15">腹膜炎 1.05-1.25</option>
					<option value="1.2">小手術或癌症 1.2</option>
					<option value="1.3">癌症惡病質 1.2-1.4</option>
					<option value="1.3">骨折、骨骼創傷 1.3</option>
					<option value="1.6">敗血 1.4-1.8</option>
					<option value="1.7">燒傷(30%) 1.7</option>
					<option value="2.0">燒傷(50%) 2.0</option>
					<option value="2.2">燒傷(70%) 2.2</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>實際能量消耗（Energy Expenditure）</td>
			<td><input type="number" min="0" max="9999" name="EE" value="<?php echo $login['EE']; ?>"></td>
			<td>
				<button type="submit" class="btn btn-primary" name="auto" value="EE">根據基本能量消耗、活動因素、壓力因素</button>
			</td>
		</tr>
		<tr>
			<td>增減攝取熱量</td>
			<td><input type="number" min="-999" max="999" name="EE_diff" value="<?php echo $login['EE_diff']; ?>"></td>
			<td></td>
		</tr>
		<tr>
			<td>過敏原</td>
			<td><input type="text" min="-9999" max="9999" name="allergen" value="<?php echo $login['allergen']; ?>"></td>
			<td></td>
		</tr>
		</table>
		<button type="submit" class="btn btn-success" name="submit">
			<span class="glyphicon glyphicon-pencil"></span>
			修改
		</button>
		</form>
<?php
	include("../res/template/footer.php");
?>
</body>
</html>