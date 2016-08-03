<!DOCTYPE html>
<?php
require("../function/common.php");
if ($login !== false) {
	addmsgbox("info", "你已經登入了", false);
	?><script>setTimeout(function(){location="../home/";}, 1000)</script><?php
} else if (isset($_GET["fblogin"])) {
	require("../function/facebook-php-sdk-v4/src/Facebook/autoload.php");
	$fb = new Facebook\Facebook([
		'app_id' => $cfg['facebook']['app_id'],
		'app_secret' => $cfg['facebook']['app_secret'],
		'default_graph_version' => 'v2.7'
	]);
	$helper = $fb->getJavaScriptHelper();
	try {
		$accessToken = $helper->getAccessToken();
		if (! isset($accessToken)) {
			addmsgbox("danger", "Facebook登入失敗");
		} else {
			$response = $fb->get('/me',$accessToken->getValue())->getDecodedBody();
			$query = new query;
			$query->table = "user";
			$query->where = array(
				array("fbid", $response["id"])
			);
			$login = fetchone(SELECT($query));
			if($login !== null){
				addmsgbox("success", "登入成功");
				?><script>setTimeout(function(){location="../home/";}, 1000)</script><?php
			} else {
				$query = new query;
				$query->table = "user";
				$query->value = array(
					array("fbid", $response["id"]),
					array("nickname", $response["name"])
				);
				INSERT($query);
				$query = new query;
				$query->table="user";
				$query->where = array(
					array("fbid", $response["id"])
				);
				$login = fetchone(SELECT($query));
				addmsgbox("success", "自動新建帳戶");
				?><script>setTimeout(function(){location="../setting/";}, 1000)</script><?php
			}
			$cookie = getrandommd5();
			$query = new query;
			$query->table = "session";
			$query->value = array(
				array("uid", $login["uid"]),
				array("cookie", $cookie)
			);
			INSERT($query);
			setcookie($cfg['cookie']['name'], $cookie, time()+86400*7, "/");
		}
	} catch(Facebook\Exceptions\FacebookResponseException $e) {
		addmsgbox("error", "Facebook登入失敗");
	} catch(Facebook\Exceptions\FacebookSDKException $e) {
		addmsgbox("error", "Facebook登入失敗");
	}
}
?>
<html lang="zh-Hant-TW">
<head>
<?php
require("../res/template/comhead.php");
showmeta();
?>
<title>登入-<?php echo $cfg['website']['name']; ?></title>
<script type="text/javascript">
  function statusChangeCallback(response) {
  	console.log(response);
    if (response.status === 'connected') {
      return true;
    } else if (response.status === 'not_authorized') {
      return false;
    } else {
      return false;
    }
  }
  function checkLoginState() {
    FB.getLoginStatus(function(response) {
      if(statusChangeCallback(response))document.location = '?fblogin';
    });
  }
  window.fbAsyncInit = function() {
  FB.init({
    appId      : '<?php echo $cfg['facebook']['app_id']; ?>',
    cookie     : true,
    xfbml      : true,
    version    : 'v2.7'
  });
  FB.getLoginStatus(function(response) {
    statusChangeCallback(response);
  });
  };
  (function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/zh_TW/sdk.js#xfbml=1&version=v2.7&appId=1740035992902253";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));
</script>
</head>
<body Marginwidth="-1" Marginheight="-1" Topmargin="0" Leftmargin="0">
<?php
	require("../res/template/header.php");
?>
<div class="row">
	<div class="col-xs-12 col-sm-offset-3 col-sm-6 col-md-offset-4 col-md-4">
		<h2>登入</h2>
		<div class="fb-login-button" data-max-rows="1" data-size="xlarge" data-show-faces="false" data-auto-logout-link="false" onlogin="checkLoginState();"></div>
	</div>
</div>
<?php
require("../res/template/footer.php");
?>
</body>
</html>
