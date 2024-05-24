<?php
//if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
include_once("./_common.php");
include_once($board_skin_path."/_arr.php"); // 여분필드 배열 담긴 파일



$sql_common = " from g5_write_".$bo_table;

// 원글만 검색 (게시판 특성상 댓글은 검색에서 제외)
$sql_search = " where wr_is_comment = 0";

// 기타검색
if ($stx) {
    $sql_search .= " and ( ";
    switch ($sfl) {
        case "wr_subject" :
            $sql_search .= " ($sfl like '%$stx%') ";
            break;
		default :
            $sql_search .= " ($sfl like '%$stx%') ";
            break;
    }
    $sql_search .= " ) ";
}

//분류검색
if($cn) {
	$sql_search .= " and ca_name = '".$cn."' ";
}

// 등록일 구간 검색
if($fd && $td) {
	$sql_search .= " and wr_datetime between '".$fd." 00:00:00' and '".$td." 23:59:59' ";
}

// 여분필드1 검색
if($w1) {
	$w1 = str_replace(",","','", $w1);
	$sql_search .= " and wr_1 in ('".$w1."') ";
}

// 여분필드2 검색
if($w2) {
	$sql_search .= " and wr_2 = '".$w2."' ";
}

// 여분필드3 검색
if($w3) {
	$sql_search .= " and wr_3 = '".$w3."' ";
}

// 여분필드4 검색
if($w4) {
	$sql_search .= " and wr_4 = '".$w4."' ";
}

// 여분필드5 검색
if($w5) {
	$sql_search .= " and wr_5 = '".$w5."' ";
}

// 여분필드6 검색
if($w6) {
	$sql_search .= " and wr_6 = '".$w6."' ";
}

// 여분필드7 검색
if($w7) {
	$sql_search .= " and wr_7 = '".$w7."' ";
}

// 여분필드8 검색
if($w8) {
	$sql_search .= " and wr_8 = '".$w8."' ";
}

// 여분필드9 검색
if($w9) {
	$sql_search .= " and wr_9 = '".$w9."' ";
}

// 여분필드10 검색 (입고일 구간 검색)
if($fd10 && $td10) {
	$sql_search .= " and wr_10 between '".$fd10."' and '".$td10."' ";
}

$sidx = ($sidx)? $sidx:"wr_num, wr_reply";
$sord = ($sord)? $sord:"asc";
$sql_order = " order by {$sidx} {$sord} ";

$sql = "select
			count(*) as cnt
       $sql_common
       $sql_search
       $sql_order ";
$row = sql_fetch($sql);
$total_count = $row[cnt];

//한페이지에 보여질 리스트 숫자..
$rows = 30;
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if (!$page) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select
			*
          $sql_common
          $sql_search
          $sql_order
          limit $from_record, $rows ";
$result = sql_query($sql);
//print_r($sql);
// DB 처리 끝

// unset으로 고정값 제거
$RST = $_POST;
unset($RST['_search']);
unset($RST['bo_table']);
unset($RST['nd']);
unset($RST['rows']);
unset($RST['page']);
unset($RST['sidx']);
unset($RST['sord']);
unset($RST['sfl']);
$RST= array_filter($RST); // 배열의 빈값을 모두 제거
// 검색을 실행한 경우 resetn 값을 돌려준다. (엑셀저장버튼을 보이게 하거나 숨긴다.)
if(count($RST)>0) {
	$responce->resetn = 'Y';
}

$responce->page = $page; // 현재페이지
$responce->total = $total_page; // 총페이지
$responce->records = $total_count; // 총 자료수
$responce->para = array_filter($_POST);

$i=0;
if($page!=1){
	$num=$total_count-($rows*($page-1));
} else {
	$num=$total_count;
}

//print_r($list);
while($rs=sql_fetch_array($result)) {

	$comment_cnt = ($rs['wr_comment']>0)? "&nbsp;(<span style='color:red;'>".$rs['wr_comment']."</span>)":"";

	$responce->rows[$i]['num'] = $num;
	$responce->rows[$i]['wr_id'] = $rs['wr_id'];
	$responce->rows[$i]['ca_name'] = $rs['ca_name'];
	$responce->rows[$i]['wr_subject'] = $rs['wr_subject'].$comment_cnt;
	$responce->rows[$i]['wr_name'] = $rs['wr_name'];
	$responce->rows[$i]['wr_datetime'] = $rs['wr_datetime'];
	$responce->rows[$i]['wr_1'] = $WR1[$rs['wr_1']];
	$responce->rows[$i]['wr_2'] = $WR2[$rs['wr_2']];
	$responce->rows[$i]['wr_3'] = $WR3[$rs['wr_3']];
	$responce->rows[$i]['wr_4'] = $WR4[$rs['wr_4']];
	$responce->rows[$i]['wr_5'] = $WR5[$rs['wr_5']];
	$responce->rows[$i]['wr_6'] = $WR6[$rs['wr_6']];
	$responce->rows[$i]['wr_7'] = $WR7[$rs['wr_7']];
	$responce->rows[$i]['wr_8'] = $WR8[$rs['wr_8']];
	$responce->rows[$i]['wr_9'] = $rs['wr_9'];
	$responce->rows[$i]['wr_10'] = $rs['wr_10'];

	$num--;
	$i++;
}

echo json_encode($responce);

?>