<?
include_once("./_common.php");

// 초기화면 파일 경로 지정 : 이 코드는 가능한 삭제하지 마십시오.
if ($config['cf_include_index']) {
    if (!@include_once($config['cf_include_index'])) {
        die('기본환경 설정에서 초기화면 파일 경로가 잘못 설정되어 있습니다.');
    }
    return; // 이 코드의 아래는 실행을 하지 않습니다.
}

if(!$is_member) {
	alert("로그인후 이용해 주세요!");
}

if($mode!="excel") {
	alert("잘못된 접근입니다.");
}

//print_r($_GET);

// DB 쿼리 시작
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

// 여분필드1 검색 (다중 체크박스 - 다수 선택시 2차원 배열로 넘어옴)
if($w1) {

	foreach($w1 as $k=>$v) {
		$w1_v .= ",'".$v."'";
	}
	$w1 = substr($w1_v,1); // 첫번째 콤마(,) 제거
	$sql_search .= " and wr_1 in (".$w1.") ";
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

$sql = " select
			*
          $sql_common
          $sql_search
          $sql_order";
$result = sql_query($sql);

//echo $sql;
// DB 쿼리 끝.

$ti = str_replace("-","",G5_TIME_YMDHIS);
$ti = str_replace(":","",$ti);
$ti = str_replace(" ","_",$ti);
header( "Content-type: application/vnd.ms-excel" );
header( "Content-Disposition: attachment; filename={$ti}_grid_list.xls" );
?>
<meta http-equiv="Content-Type" content="application/vnd.ms-excel;charset=UTF-8">
		<table border="1">
			<thead>
				<tr>
					<th>번호</th>
					<th>분류명</th>
					<th>제품명(모델명)</th>
					<th>상품코드</th>
					<th>등록일시</th>

					<th>창고입고일</th>
					<th>관리팀</th>
					<th>창고위치</th>
					<th>크기</th>
					<th>색상</th>

					<th>재질</th>
					<th>A/S가능여부</th>
					<th>배송가능여부</th>
					<th>용량</th>
				</tr>
			</thead>
			<tbody>
			<?php
			for ($i=0; $row=sql_fetch_array($result); $i++)  {
			?>
				<tr>
					<td><?php echo number_format($i+1);?></td>
					<td><?php echo $row['ca_name']; ?></td>
					<td><?php echo $row['wr_subject']; ?></td>
					<td><?php echo $row['wr_9']; ?></td>
					<td><?php echo $row['wr_datetime']; ?></td>

					<td><?php echo $row['wr_10']; ?></td>
					<td><?php echo $WR3[$row['wr_3']]; ?></td>
					<td><?php echo $WR1[$row['wr_1']]; ?></td>
					<td><?php echo $WR2[$row['wr_2']]; ?></td>
					<td><?php echo $WR4[$row['wr_4']]; ?></td>

					<td><?php echo $WR5[$row['wr_5']]; ?></td>
					<td><?php echo $WR6[$row['wr_6']]; ?></td>
					<td><?php echo $WR7[$row['wr_7']]; ?></td>
					<td><?php echo $WR8[$row['wr_8']]; ?></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
<!--
style="mso-number-format:'\@'" => 엑셀로 변환시 전화번호나 숫자중에서 맨앞에 '0'이 포함될경우 없어지는 현상을 없애줌.
-->