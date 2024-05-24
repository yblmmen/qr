<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if(G5_COMMUNITY_USE === false) {
    include_once(G5_THEME_SHOP_PATH.'/index.php');
    return;
}

include_once(G5_THEME_PATH.'/head.php');

if(is_file(G5_PATH.'/main.php'))
{
	include G5_PATH.'/main.php';
}else{
?>



<?php
}

include_once(G5_THEME_PATH.'/tail.php');