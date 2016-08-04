<!DOCTYPE html>
<?php
require("../function/common.php");
?>
<html lang="zh-Hant-TW">
<head>
<?php
require("../res/template/comhead.php");
showmeta();
?>
<title><?php echo $cfg['website']['name']; ?></title>
<link href="css/stylish-portfolio.css" rel="stylesheet">
<link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700,300italic,400italic,700italic" rel="stylesheet" type="text/css">
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
	  if(statusChangeCallback(response))document.location = '../login/?fblogin';
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
<body topmargin="0" leftmargin="0" bottommargin="0">
<?php
require("../res/template/header.php");
?>
<!-- <a id="menu-toggle" href="#" class="btn btn-dark btn-lg toggle"><i class="fa fa-bars"></i></a>
<nav id="sidebar-wrapper">
	<ul class="sidebar-nav">
		<a id="menu-close" href="#" class="btn btn-light btn-lg pull-right toggle"><i class="fa fa-times"></i></a>
		<li class="sidebar-brand">
			<a href="#top" onclick=$("#menu-close").click();>Start Bootstrap</a>
		</li>
		<li>
			<a href="#top" onclick=$("#menu-close").click();>Home</a>
		</li>
		<li>
			<a href="#about" onclick=$("#menu-close").click();>About</a>
		</li>
		<li>
			<a href="#services" onclick=$("#menu-close").click();>Services</a>
		</li>
		<li>
			<a href="#portfolio" onclick=$("#menu-close").click();>Portfolio</a>
		</li>
		<li>
			<a href="#contact" onclick=$("#menu-close").click();>Contact</a>
		</li>
	</ul>
</nav> -->

<!-- Header -->
<header id="top" class="header">
	<div class="row text-center text-vertical-center hidden-xs">
		<h1>健康的挑食</h1>
		<h3>幫您選擇健康的飲食生活</h3>
		<br>
		<?php
		if ($login === false) {
		?>
		<div class="row text-center">
			<div class="col-xs-12"><div class="fb-login-button" data-max-rows="1" data-size="xlarge" data-show-faces="false" data-auto-logout-link="false" onlogin="checkLoginState();"></div></div>
		</div>
		<?php
		}
		?>
	</div>
	<div class="row text-center visible-xs-block">
		<h1>健康的挑食</h1>
		<h3>幫您選擇健康的飲食生活</h3>
		<br>
		<?php
		if ($login === false) {
		?>
		<div class="row text-center">
			<div class="col-xs-12"><div class="fb-login-button" data-max-rows="1" data-size="xlarge" data-show-faces="false" data-auto-logout-link="false" onlogin="checkLoginState();"></div></div>
		</div>
		<?php
		}
		?>
	</div>
	<!-- <div class="row">
		<a href="#about" class="btn btn-dark btn-lg">Find Out More</a>
	</div> -->
</header>

<!-- About -->
<section id="about" class="about">
	<div class="container">
		<div class="row">
			<div class="col-lg-12 text-center">
				<h2>Helpicky help you pick it!</h2>
				<img src="../res/image/home/nav5.png">
				<h4><strong>登入、設定與搜尋</strong></h4>
				<a href="https://youtu.be/3MDUJ9kKmGo" class="btn btn-light">看影片介紹</a>
				<!-- <p class="lead">想要使用根據您飲食日記做出的強大飲食推薦嗎？登入後請先依<a target="_blank" href="https://youtu.be/3MDUJ9kKmGo">登入、設定與搜尋教學影片</a>進行操作</p>
			</div> -->
		</div>
		<!-- /.row -->
	</div>
	<!-- /.container -->
</section>

<!-- Services -->
<!-- The circle icons use Font Awesome's stacked icon classes. For more information, visit http://fontawesome.io/examples/ -->
<section id="services" class="services bg-primary">
	<div class="container">
		<div class="row text-center">
			<div class="col-lg-10 col-lg-offset-1">
				<h2>我們的其他有趣功能</h2>
				<hr class="small">
				<div class="row">
					<div class="col-md-3 col-sm-6">
						<div class="service-item">
							<!-- <span class="fa-stack fa-4x">
							<i class="fa fa-circle fa-stack-2x"></i>
							<i class="fa fa-cloud fa-stack-1x text-primary"></i>
						</span> -->
							<img src="../res/image/home/nav1.png">
							<h4>
								<strong>日記</strong>
							</h4>
							<p>紀錄您飲食狀況的隨身工具</p>
							<a href="#diary" class="btn btn-light">看影片介紹</a>
						</div>
					</div>
					<div class="col-md-3 col-sm-6">
						<div class="service-item">
							<!-- <span class="fa-stack fa-4x">
							<i class="fa fa-circle fa-stack-2x"></i>
							<i class="fa fa-compass fa-stack-1x text-primary"></i>
						</span> -->
							<img src="../res/image/home/nav2.png">
							<h4>
								<strong>成就</strong>
							</h4>
							<p>想知道BMI最近降了多少，各種營養量的攝取是否達到標準，成就精美而詳細的曲線為您度量</p>
							<a href="#achieve" class="btn btn-light">看影片介紹</a>
						</div>
					</div>
					<div class="col-md-3 col-sm-6">
						<div class="service-item">
							<!-- <span class="fa-stack fa-4x">
							<i class="fa fa-circle fa-stack-2x"></i>
							<i class="fa fa-flask fa-stack-1x text-primary"></i>
						</span> -->
							<img src="../res/image/home/nav4.png">
							<h4>
								<strong>飲食推薦</strong>
							</h4>
							<p>我們的最核心功能<br>根據您的飲食日記，推薦出最有益您健康的多種飲食組合</p>
							<a href="#recommend" class="btn btn-light">看影片介紹</a>
						</div>
					</div>
					<div class="col-md-3 col-sm-6">
						<div class="service-item">
							<!-- <span class="fa-stack fa-4x">
							<i class="fa fa-circle fa-stack-2x"></i>
							<i class="fa fa-shield fa-stack-1x text-primary"></i>
						</span> -->
							<img src="../res/image/home/nav3.png">
							<h4>
								<strong>熱門商品</strong>
							</h4>
							<p>不只要健康，還想知道那些食物已經炙手可熱，熱門商品全部滿足您</p>
							<a href="#hot" class="btn btn-light">看影片介紹</a>
						</div>
					</div>
				</div>
				<!-- /.row (nested) -->
			</div>
			<!-- /.col-lg-10 -->
		</div>
		<!-- /.row -->
	</div>
	<!-- /.container -->
</section>
<!-- Callout -->
<aside class="callout">
	<div class="text-vertical-center">
		<h3>趕快把Helpicky加入書籤吧</h3>
	</div>
</aside>
<!-- Portfolio -->
<section id="portfolio" class="portfolio">
	<div class="container">
		<div class="row">
			<div class="col-lg-10 col-lg-offset-1 text-center">
				<hr class="small">
				<div class="row">
					<div class="col-md-6">
						<div class="portfolio-item">
							<div class="embed-responsive embed-responsive-16by9">
								<iframe id="diary" class="embed-responsive-item" src="https://www.youtube.com/embed/woZOdupkbvE" allowfullscreen></iframe>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="portfolio-item">
							<div class="embed-responsive embed-responsive-16by9">
								<iframe id="achieve" class="embed-responsive-item" src="https://www.youtube.com/embed/_1g4gpOlnwE" allowfullscreen></iframe>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="portfolio-item">
							<div class="embed-responsive embed-responsive-16by9">
								<iframe id="recommend" class="embed-responsive-item" src="https://www.youtube.com/embed/OpDdgR1K-uA" allowfullscreen></iframe>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="portfolio-item">
							<div class="embed-responsive embed-responsive-16by9">
								<iframe id="hot" class="embed-responsive-item" src="https://www.youtube.com/embed/-VaUNLdj5pM" allowfullscreen></iframe>
							</div>
						</div>
					</div>
				</div>
				<!-- /.row (nested) -->
				<!-- <a href="#" class="btn btn-dark">View More Items</a> -->
			</div>
			<!-- /.col-lg-10 -->
		</div>
		<!-- /.row -->
	</div>
	<!-- /.container -->
</section>
<!-- Call to Action -->
<aside class="call-to-action bg-primary">
	<div class="container">
		<div class="row">
			<div class="col-lg-12 text-center">
				<h3>想要追蹤Helpicky為您跨出的步伐嗎？馬上加入Helpicky粉絲團。</h3>
				<a href="https://www.facebook.com/Helpicky" class="btn btn-lg btn-light" target="_blank">粉絲專頁</a>
				<a href="https://www.facebook.com/Helpicky" class="btn btn-lg btn-dark" target="_blank">小精靈的最新貼文</a>
			</div>
		</div>
	</div>
</aside>
<!-- Footer -->
<footer>
	<div class="container">
		<div class="row">
			<div class="col-lg-10 col-lg-offset-1 text-center">
				<h4><strong>聯絡Helpicky</strong>
				</h4>
				
				<ul class="list-unstyled">
					<li><i class="fa fa-envelope-o fa-fw"></i> <a href="mailto:helpicky@gmail.com">helpicky@gmail.com</a>
					</li>
				</ul>
				<br>
				<ul class="list-inline">
					<li>
						<a href="https://www.facebook.com/Helpicky" target="_blank"><i class="fa fa-facebook fa-fw fa-3x"></i></a>
					</li>
					<li>
						<a href="https://plus.google.com/114861070416184531354" target="_blank"><i class="fa fa-google fa-fw fa-3x"></i></a>
					</li>
					<li>
						<a href="https://www.youtube.com/channel/UC9MMSbyMC5NAgRkFjM1C6ow" target="_blank"><i class="fa fa-youtube fa-fw fa-3x"></i></a>
					</li>
				</ul>
				<hr class="small">
				<p class="text-muted">Copyright &copy; Helpciky 2016</p>
			</div>
		</div>
	</div>
	<!-- <a id="to-top" href="#top" class="btn btn-dark btn-lg"><i class="fa fa-chevron-up fa-fw fa-1x"></i></a> -->
</footer>

<!-- jQuery -->
<script src="js/jquery.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="js/bootstrap.min.js"></script>

<!-- Custom Theme JavaScript -->
<script>
// Closes the sidebar menu
$("#menu-close").click(function(e) {
	e.preventDefault();
	$("#sidebar-wrapper").toggleClass("active");
});
// Opens the sidebar menu
$("#menu-toggle").click(function(e) {
	e.preventDefault();
	$("#sidebar-wrapper").toggleClass("active");
});
// Scrolls to the selected menu item on the page
$(function() {
	$('a[href*=#]:not([href=#],[data-toggle],[data-target],[data-slide])').click(function() {
		if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') || location.hostname == this.hostname) {
			var target = $(this.hash);
			target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
			if (target.length) {
				$('html,body').animate({
					scrollTop: target.offset().top
				}, 1000);
				return false;
			}
		}
	});
});
//#to-top button appears after scrolling
var fixed = false;
$(document).scroll(function() {
	if ($(this).scrollTop() > 250) {
		if (!fixed) {
			fixed = true;
			// $('#to-top').css({position:'fixed', display:'block'});
			$('#to-top').show("slow", function() {
				$('#to-top').css({
					position: 'fixed',
					display: 'block'
				});
			});
		}
	} else {
		if (fixed) {
			fixed = false;
			$('#to-top').hide("slow", function() {
				$('#to-top').css({
					display: 'none'
				});
			});
		}
	}
});
// Disable Google Maps scrolling
// See http://stackoverflow.com/a/25904582/1607849
// Disable scroll zooming and bind back the click event
var onMapMouseleaveHandler = function(event) {
	var that = $(this);
	that.on('click', onMapClickHandler);
	that.off('mouseleave', onMapMouseleaveHandler);
	that.find('iframe').css("pointer-events", "none");
}
var onMapClickHandler = function(event) {
		var that = $(this);
		// Disable the click handler until the user leaves the map area
		that.off('click', onMapClickHandler);
		// Enable scrolling zoom
		that.find('iframe').css("pointer-events", "auto");
		// Handle the mouse leave event
		that.on('mouseleave', onMapMouseleaveHandler);
	}
	// Enable map zooming with mouse scroll when the user clicks the map
$('.map').on('click', onMapClickHandler);
</script>
<?php
require("../res/template/footer.php");
?>
</body>
</html>
