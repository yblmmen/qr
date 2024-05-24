<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

define("FC_DIR", "fullcalendar-5.11.3");
define("FC_PLUGIN_URL", G5_PLUGIN_URL."/".FC_DIR);
define("FC_PLUGIN_PATH", G5_PLUGIN_PATH."/".FC_DIR);

// 기본보기설정. 작성자명노출, 주차표시, 언어설정, 화면버튼종류, 일정목록보기종류
list($fc_default_view, $fc_display_name, $fc_weeks_number, $fc_lang, $fc_display_types, $fc_list, $popup, $lunar) = explode("|", $board['bo_10']);

// 필드 속성 변경 (wr_1, wr_2  데이터타입이 datetime 이면 아래의 코드는 주석으로 처리하여도 됩니다.)
$sql = "SHOW COLUMNS FROM `{$write_table}` LIKE 'wr_1'";
$res = sql_fetch($sql);
if($res['Type'] != 'datetime') {
    $sql = "ALTER TABLE `{$write_table}`
                CHANGE `wr_1` `wr_1` DATETIME NOT NULL DEFAULT '1000-01-01 00:00:00' COMMENT '시작일시',
                CHANGE `wr_2` `wr_2` DATETIME NOT NULL DEFAULT '1000-01-01 00:00:00' COMMENT '종료일시'";
    sql_query($sql);
}

/* 옵션으로 사용될 배열 선언 */
$default_view_array = array(
    "dayGridMonth" => "월간",
    "dayGridWeek" => "주간",
    "dayGridDay" => "일간"
);

$display_types_array = array(
    "dayGridMonth" => "월간",
    "dayGridWeek" => "주간",
    "timeGridWeek" => "주간(시간)",
    "dayGridDay" => "일간",
    "timeGridDay" => "일간(시간)",
    "listDay" => "목록(일)",
    "listWeek" => "목록(주)",
    "listMonth" => "목록(월)",
    "listYear" => "목록(년)"
);

// 음력사용여부
$display_lunar_arr = array(
    "0" => "음력사용 안함",
    "1" => "음력사용 함"
);

$display_name_array = array(
    0 => "숨기기",
    1 => "표시"
);

$week_number_array = array(
    0 => "숨기기",
    1 => "표시"
);

$lang_array = array(
    "ko" => "한국어",
    "en" => "영어",
    "zh-cn" => "중국어(간체)",
    "zh-tw" => "중국어(번체)",
    "ja" => "일어",
    "vi" => "베트남어",
    "id" => "인도네시아어"
);

$list_array = array(
    "person" => "자신이 등록한 자료만 보기",
    "all" => "모든 자료 보기"
);

// 배경색 및 글자색 (삭제금지)
$arr_color = array(

    "#ffffff",
    "#3788D8",
    "#0000FF",
    "#cccccc",
    "#FF0000",
    "#dd6666",
    "#80CBDB",
    "#FFD300",
    "#00B500",
    "#1CA261",
    "#24ACF2",
    "#C8B8B9",
    "#9AA2AE",
    "#555555",
    "#888888",
    "#000000"
);

$popupArr = array(
    "popup" => "팝업 사용",
    "none" => "팝업 사용 안함"
);

$repeatArr = array(
    "0" => "없음",
    "10" => "매주 반복",
    "20" => "격주 반복",
    "80" => "매월 반복",
    "90" => "매년 반복",
    "1" => "N일마다 반복",
    "30" => "N주마다 반복",
    "99" => "N개월마다 반복"
);