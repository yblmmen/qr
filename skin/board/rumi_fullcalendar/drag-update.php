<?php
include_once('./_common.php');

// 게시판 글쓰기 레벨보다 낮다면 변경 불가.
if($member['mb_level'] < $board['bo_write_level']) {
    die();
}

include_once($board_skin_path.'/config.php');

$wr_id = $_POST['wr_id'];
$start = $_POST['start'];
$end = $_POST['end'];
$allDay = ($_POST['allDay']=="true") ? true : false;

// 필수값 누락시 업데이트 중지 - 아무런 메세지 표시하지 않음
if (!isset($start) || !isset($end) || !isset($wr_id) || !$is_member) {
	die("");
}

$start = new DateTime($start);
$end = new DateTime($end);
if($allDay==true) {
    $wr_1 = $start->format("Y-m-d H:i:00");
    $wr_2 = date("Y-m-d H:i:s", strtotime($end->format("Y-m-d"))-1); // 종일이면 1초 빼서 "0000-00-00 23:59:59" 로 맞춘다.
} else {
    $wr_1 = $start->format("Y-m-d H:i:00");
    $wr_2 = $end->format("Y-m-d H:i:00");
}

// 반복일정은 드래그 또는 리사이즈로 변경할 수 없도록 한다.
$res = sql_fetch("SELECT `wr_6` FROM `{$write_table}` WHERE `wr_id` = '{$wr_id}'", FALSE);
if($res['wr_6']>0) {
    die("반복일정은 변경할 수 없습니다.");
}

// 자신이 등록한 자료만 수정 가능 하게. ( 회원만 수정 가능 )
$sql = "UPDATE
            `{$write_table}`
        SET
            `wr_1` = '{$wr_1}',
            `wr_2` = '{$wr_2}'
        WHERE
            `wr_id` = '{$wr_id}' AND `mb_id` = '{$member['mb_id']}' ";
sql_query($sql, TRUE);

$response = new stdClass();
$response->editDate['startDate'] = $wr_1;
$response->editDate['endDate'] = $wr_2;

echo json_encode($response);