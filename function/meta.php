<?php
$metalist=array(
	"fb:app_id" => $cfg['facebook']['app_id'],
	"og:url" => "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],
	"og:type" => "website",
	"og:title" => $cfg['website']['name'],
	"og:description" => "Help you pick it!",
	"og:image" => "http://helpicky.twbbs.org/res/image/icon/icon-200.png",
);

function setmeta($key, $value){
	global $metalist;
	$metalist[$key] = $value;
}

function showmeta(){
	global $metalist;
	foreach ($metalist as $key => $value) {
		echo "<meta property=\"".$key."\" content=\"".$value."\">\n";
	}
}
?>