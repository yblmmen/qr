<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
include_once($board_skin_path."/skin.function.php");
$Search_box = new Frm_search(); // 검색폼 관련 Class

// 원본 주소
$original_url = $_SERVER['REQUEST_URI'];

// 변환된 주소 템플릿
$base_redirect_url = "http://158.180.78.143/theme/Bootstrap5/skin/board/syndgate_tel/car_edit.php?bo_table=network&pg=1&wr_id=";

// 원본 주소에서 wr_id 값 추출
preg_match('/wr_id=(\d+)/', $original_url, $matches);
$wr_id = isset($matches[1]) ? $matches[1] : '';

// 변환된 주소 생성
$redirect_url = $base_redirect_url . $wr_id;

// 리다이렉션
header("Location: $redirect_url");
exit;
?>

<table id="<?php echo $grid_list;?>"></table>
<div id="<?php echo $grid_pager;?>"></div>
