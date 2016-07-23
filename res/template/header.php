<div id="wrap" sytle="min-height: 100%;">
	<header class="navbar navbar-static-top bs-docs-nav" id="top" role="banner">
		<div class="container">
			<div class="navbar-header">
				<button class="navbar-toggle collapsed" type="button" data-toggle="collapse" data-target=".bs-navbar-collapse">
					<span class="sr-only">巡覽切換</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a href="../home/" class="navbar-brand">
					<div style="float: left;">
						<img src="../res/image/icon.png" alt="Helpicky" height="30px">
					</div>
					<div style="float: left;">
						&nbsp;&nbsp;<span style="font-weight: bold; font-size: px; font-family: '標楷體';">Helpicky</span><br>
					</div>
				</a>
			</div>
			<nav class="collapse navbar-collapse bs-navbar-collapse" role="navigation">
				<ul class="nav navbar-nav">
					<li>
						<a href="../home/">首頁</a>
					</li>
					<?php
					if ($login !== false) {
					?>
					<li>
						<a href="../diary/">日記</a>
					</li>
					<li>
						<a href="../search/">搜尋</a>
					</li>
					<li>
						<a href="../recommend/">推薦</a>
					</li>
					<?php
					}
					?>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<?php
					if ($login === false) {
					?>
					<li>
						<a href="../login/">登入</a>
					</li>
					<?php
					} else {
					?>
					<li>
						<a href="../setting/">設定</a>
					</li>
					<li>
						<a href="../logout/"><?php echo $login["nickname"]; ?> 登出</a>
					</li>
					<?php
					}
					?>
				</ul>
			</nav>
		</div>
	</header>
<?php
	showmsgbox();
?>
	<div class="container-fluid">