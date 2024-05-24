<?php
include_once('./_common.php');
include_once($board_skin_path.'/config.php');

//--------------------------------------------------------------------------------------------------
// This script reads event data from a JSON file and outputs those events which are within the range
// supplied by the "start" and "end" GET parameters.
//
// An optional "timezone" GET parameter will force all ISO8601 date stings to a given timezone.
//
// Requires PHP 5.2.0 or higher.
//--------------------------------------------------------------------------------------------------

// Require our Event class and datetime utilities
require FC_PLUGIN_PATH.'/examples/php/utils.php';

// 정기휴일 체크
$sca        = $_POST['sca'];
$stx        = $_POST['stx'];
$start      = $_POST['start'];
$end        = $_POST['end'];
$timezone   = $_POST['timezone'];
$bo_table   = $_POST['bo_table'];

// Short-circuit if the client did not give us a date range.
if (!$start || !$end) {
	die("Please provide a date range.");
}

// Parse the start/end parameters.
// These are assumed to be ISO8601 strings with no time nor timezone, like "2013-12-29".
// Since no timezone will be present, they will parsed as UTC.

$range_start = parseDateTime($start);
$range_end = parseDateTime($end);

// Parse the timezone parameter if it is present.
$timezone = null;
if (isset($timezone)) {
	$timezone = new DateTimeZone($timezone);
}

// Read and parse our events JSON file into an array of event data arrays. ( yyyy-mm-dd )
//$frdate = $start;
//$todate = $end;

$sql_where = "";
if ($sca || $stx || $stx === '0') { // 검색이면
    $sql_search = get_sql_search($sca, $sfl, $stx, $sop);
    if ($sql_search) {
        $sql_where = " AND ". $sql_search;
    }
}

if($fc_list == "person") {
    $sql_where .= " AND `mb_id` = '{$member['mb_id']}' ";
}

$startDay = new DateTime($start);
$endDay = new DateTime($end);
$diffDay  = date_diff($startDay, $endDay);
$startDay = $startDay->format('Y-m-d');
$endDay = $endDay->format('Y-m-d');

// 일정 가져오기
$sql = "SELECT
            `wr_id`,
            `wr_option`,
            `wr_name`,
            `wr_subject`,
            `mb_id`,
            `wr_link1`,
            `wr_1`,
            `wr_2`,
            `wr_3`,
            `wr_4`,
            `wr_5`,
            `wr_6`,
            `wr_7`,
            `wr_8`
        FROM
            `{$write_table}`
        WHERE
            `wr_1` <= '{$end}' AND
            ( CASE WHEN `wr_6` > 0 THEN `wr_7` ELSE `wr_2` END ) >= '{$start}'
            {$sql_where} ";
$result = sql_query($sql, TRUE);

$list = array();
while($row=sql_fetch_array($result)) {
    $tempArr = array();
    $starttime = strtotime($startDay);
    $dailyStartDate = date("Y-m-d", strtotime($row['wr_1']));
    $dailyStartTime = date("H:i:s", strtotime($row['wr_1']));
    $dailyEndDate = date("Y-m-d H:i:s", strtotime($row['wr_2']));
    $dailyEndTime = date("H:i:s", strtotime($row['wr_2']));

    // 일정 반복 종료일
    $expireDate = ($row['wr_7']) ? date("Y-m-d", strtotime($row['wr_7'])) : $endDay;

    // 일정시작일과 일정종료일간의 간격
    $subDiff = date_diff(new DateTime($row['wr_1']), new DateTime($row['wr_2']));

    // 일정시작일의 "요일"
    $fst_weeks = date("N", strtotime($row['wr_1']));

    // 날짜반복 - 시작날짜
    $start_day2 = ($dailyStartDate < $startDay) ? $startDay : $dailyStartDate;

    // 날짜반복 - 종료날짜
    $end_day2 = ($expireDate > $endDay) ? $endDay : $expireDate;

    // 반복일정이 아니면 반복일정 계산하지 않는다.
    if(!$row['wr_6'] || $row['wr_6']==0) {
        $list[] = $row;
        continue;
    }

    // 반복일정이면 반복유형에 따라 계산 실행
    while($start_day2 <= $end_day2) {

        $rst = false;
        $calendarDay = strtotime($start_day2);
		$weeks = date("N", $calendarDay); // 달력의 요일

		switch($row['wr_6']) {
			case "1" : // N일마다 반복
				// 달력날짜에서 일정시작일을 뺀후 특정일로 나누어서 나머지가 0이 되는 날.
				$diffDay = intval(($calendarDay - strtotime($row['wr_1'])) / 86400);
				if($diffDay%$row['wr_8']==0) $rst = true;
				break;
			case "10" : // 매주 반복 (요일)
				if($weeks==$fst_weeks) $rst = true;
				break;
			case "20" : // 격주 반복 (요일)
				$calWeekCnt = date('W', $calendarDay); // 달력날짜의 주차
				$weekCnt = date('W', strtotime($row['wr_1'])); // 일정시작일의 주차
				if(($calWeekCnt-$weekCnt)%2==0 && $weeks==$fst_weeks) $rst = true;
				break;
            case "30" : // N주마다반복
                $calWeekCnt = date('W', $calendarDay); // 달력날짜의 주차
                $weekCnt = date('W', strtotime($row['wr_1'])); // 일정시작일의 주차
                if(($calWeekCnt-$weekCnt) % $row['wr_8']==0 && $weeks==$fst_weeks) $rst = true;
                break;
			case "80" : // 매월 특정일 반복 (매월 00일)
                $d1 = date("d", $calendarDay); // 달력의 날짜
                $d2 = date("d", strtotime($row['wr_1'])); // 일정의 날짜
				if($d1 == $d2) $rst = true;
				break;
			case "90" : // 매년 반복
				$d1 = date("md", $calendarDay); // 달력의 월일
                $d2 = date("md", strtotime($row['wr_1'])); // 일정의 월일
				if($d1 == $d2) $rst = true;
                break;
            case "99" : // N개월마다 반복
				// $d1 = date("md", $calendarDay); // 달력의 월일
                // $d2 = date("md", strtotime($row['wr_1'])); // 일정의 월일
                $datetime1 = new DateTime($row['wr_1']);
                $datetime2 = new DateTime($start_day2);
                $mDay1 = $datetime1->format("d");
                $mDay2 = $datetime2->format("d");
                $interval = $datetime2->diff($datetime1);
                $m = (($interval->format('%y') * 12) + $interval->format('%m'));
				if($m % $row['wr_8']==0 && $mDay1 == $mDay2) $rst = true;
                break;
		}

        // 반복일정에 해당되면.
        if($rst) {
            $row['start'] =  $row['wr_1']." ".$dailyStartTime;
            $row['end'] =  $row['wr_2']." ".$dailyEndTime;
            $row['wr_1'] = $start_day2." ".$dailyStartTime; // 일정시작일
            $row['wr_2'] = date("Y-m-d", strtotime("+{$subDiff->days} day", strtotime($start_day2)))." ".$dailyEndTime; // 일정 종료일
            $list[] = $row;
        }

        // 반복시마다 1일씩 추가
        $start_day2 = date('Y-m-d', strtotime($start_day2 . ' +1 day'));
    }
}


// 2차 가공 시작
$input_arrays = array();
for($i=0; $i < count($list); $i++) {

    // 비밀글 : 관리자 또는 자신의 일정일때만 보여준다.
    if (strstr($list[$i]['wr_option'], "secret")) {
        if ($list[$i]['mb_id'] !== $member['mb_id'] && !$is_admin) {
            continue;
        }
    }

    $rows = array();

    // $rows['resourceId'] = 'a';
    // $rows['startStr'] = "--";
    // $rows['progress'] = '';

    $rows['title'] = ($fc_display_name) ? '['.$list[$i]['wr_name'].'] '.$list[$i]['wr_subject'] : $list[$i]['wr_subject']; // 타이틀
    $rows['textColor'] = $list[$i]['wr_3']; // 글자색상
    $rows['color'] = $list[$i]['wr_4']; // 배경색
    $rows['repeat'] = ($list[$i]['wr_6'] > 0) ? true : false; // 반복일정여부 (반복일정이면 true)
    $rows['id'] = $list[$i]['wr_id']; // 일정고유번호
    $rows['url'] = ($list[$i]['wr_link1'] && !$is_admin && $list[$i]['mb_id'] != $member['mb_id']) ? $list[$i]['wr_link1'] : "";
    $rows['write_id']  = ($member['mb_id'] == $list[$i]['mb_id'] || $is_admin) ? $list[$i]['mb_id'] : ""; // 작성자 ID
    $rows['member_id'] = $member['mb_id'];
    $rows['allDay'] = ($list[$i]['wr_5']==1) ? true : false;
    // 종일이면 종료날짜에 1일을 추가해야 달력에 정상적으로 막대가 표시된다.
    $rows['end'] = ($list[$i]['wr_5']==1) ? date("Y-m-d", strtotime("+1 day", strtotime($list[$i]['wr_2']))) : str_replace(" ", "T", $list[$i]['wr_2']);
    $rows['start']  = str_replace(" ", "T", $list[$i]['wr_1']); // T 추가

    $input_arrays[] = $rows;
}

// Accumulate an output array of event data arrays.
$output_arrays = array();
foreach ($input_arrays as $array) {

	// Convert the input array into a useful Event object
	$event = new Event($array, $timezone);

	// If the event is in-bounds, add it to the output
	if ($event->isWithinDayRange($range_start, $range_end)) {
		$output_arrays[] = $event->toArray();
    }
}

$response = new stdClass();
$response->rows = $output_arrays;
$response->post = $_POST;

// Send JSON to the client.
// echo json_encode($output_arrays);
echo json_encode($response);
//echo json_encode($input_arrays);