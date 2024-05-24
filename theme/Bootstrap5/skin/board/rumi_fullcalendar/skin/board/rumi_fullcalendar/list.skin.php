<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
unset($list);
include_once($board_skin_path.'/config.php');

$rumi_uid = time();
set_session('rumi_fullcalendar_ss', $rumi_uid);

if (empty($board['bo_10'])) {
    $bo_10 = 'dayGridMonth|1|0|ko|dayGridMonth,dayGridWeek,dayGridDay|all|popup';
    sql_query(" UPDATE `{$g5['board_table']}` SET bo_10 = 'dayGridMonth|1|0|ko|dayGridMonth,dayGridWeek,dayGridDay' WHERE bo_table = '{$bo_table}' ", FALSE);
    list($fc_default_view, $fc_display_name, $fc_weeks_number, $fc_lang, $fc_display_types, $fc_list, $popup) = explode("|", $bo_10);
}

$fc_lang = $fc_lang ? $fc_lang : 'ko';
$defaultview = ($fc_default_view) ? $fc_default_view : 'dayGridMonth';
$weekNumbers = ($fc_weeks_number) ? true : false;

if($is_category) {
    $category = explode("|", $board['bo_category_list']); // 카테고리
}
$category = json_encode($category, JSON_UNESCAPED_UNICODE);

// 권한에 따라 버튼 생성
$admin_btn = "";
if($is_admin) {
    $admin_btn = "<button type='button' class='fc-button fc-button-primary' id='btn-settings'><i class='fa fa-gear' aria-hidden='true'></i></button>";
    $admin_btn .= "<button type='button' class='fc-button fc-button-primary' id='btn-adminset'>A</button>";
}
$bbs_write_btn = ($write_href) ? "<button type='button' class='fc-button fc-button-primary' id='btn-write'><i class='fa fa-pencil' aria-hidden='true'></i></button>" : "";
$btns = "<script>";
$btns .= ($write_href) ? "cfg.bbs_write_url = \"{$write_href}\";\n" : "cfg.bbs_write_url = \"\";\n";
$btns .= ($bbs_write_btn) ? "cfg.bbs_write_btn = \"{$bbs_write_btn}\";\n" : "";
$btns .= ($admin_btn) ? "cfg.bbs_admin_btn = \"{$admin_btn}\";\n" : "";
$btns .= ($admin_href) ? "cfg.bbs_admin_url = \"".htmlspecialchars_decode($admin_href)."\";\n" : "";
$btns .= "</script>";

add_stylesheet('<link rel="stylesheet" href="'.G5_PLUGIN_URL.'/rumiPopup/rumiPopup.css">', 0); // 팝업창 CSS
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/css/style.css">', 0);
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/css/fullcalendar.css">', 0);
add_stylesheet('<link rel="stylesheet" href="'.FC_PLUGIN_URL.'/lib/main.css" />', 0);
add_javascript('
<script>
var cfg = {
    plugin_url: "'.FC_PLUGIN_URL.'",
    fc_lang: "'.$fc_lang.'",
    fc_display_types: "'.$fc_display_types.'",
    g5_time_ymd: "'.G5_TIME_YMD.'",
    defaultview: "'.$defaultview.'",
    weekNumbers: "'.$weekNumbers.'",
    board_skin_url: "'.$board_skin_url.'",
    sca: "'.urlencode($sca).'",
    is_category: "'.$is_category.'",
    category: '.$category.',
    rumi_uid: "'.$rumi_uid.'",
    lunar: "'.(($lunar > 0) ? true : false).'",
    startStr: "",
    writeType: "'.$popup.'"
}</script>', 0);
add_javascript($btns, 1);
add_javascript('<script src="'.G5_PLUGIN_URL.'/rumiPopup/jquery.rumiPopup.js"></script>', 2); // 팝업창
add_javascript('<script src="'.$board_skin_url.'/js/skin.function.js"></script>', 3);
add_javascript('<script src="'.$board_skin_url.'/js/fullcalendar.js"></script>', 4);
add_javascript('<script src="'.FC_PLUGIN_URL.'/lib/main.js"></script>', 5);
add_javascript('<script src="'.FC_PLUGIN_URL.'/lib/locales/'.$fc_lang.'.js"></script>', 6); // 언어파일
?>

<div id="bo_list">
    <!-- 게시판 카테고리 시작 { -->
    <?php if ($is_category) { ?>
    <nav id="bo_cate">
        <h2><?php echo $board['bo_subject'] ?> 카테고리</h2>
        <ul id="bo_cate_ul"></ul>
    </nav>
    <?php } ?>

    <!-- } 게시판 카테고리 끝 -->
    <input type="hidden" id="sca" class="sca" value="" />
    <div id="calendar"></div>

</div>