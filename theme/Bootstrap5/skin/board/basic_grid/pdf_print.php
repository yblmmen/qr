<?php
include_once('./_common.php');
include_once(G5_PATH.'/lib/common.lib.php');
set_time_limit(0);
ini_set('memory_limit', '640M');

require_once($board_skin_path.'/tcpdf/config/tcpdf_config_grid.php'); // 인쇄용지 및 여백 설정파일
require_once($board_skin_path.'/tcpdf/tcpdf.php');
//require_once($board_skin_path.'/tcpdf/examples/tcpdf_include.php');

//$pdf = new TCPDF('P-가로인쇄, L-세로인쇄', PDF_UNIT, 'A4', true, 'UTF-8', false);
$pdf = new TCPDF('L', PDF_UNIT, 'A4', true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('http://www.yourdomain.co.kr');
$pdf->SetTitle('http://www.yourdomain.co.kr');
$pdf->SetSubject('http://www.yourdomain.co.kr');
$pdf->SetKeywords('http://www.yourdomain.co.kr');


// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.'038', PDF_HEADER_STRING);
$name1 = "사이트이름  -  http://www.yourdomain.co.kr";
$name2 = "노예같은 집사 협회 - 오늘도 나는 감자 캔다";

$pdf->SetFont('nanumbarungothicyethangul', '', 6);
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $name1, $name2);

// set header and footer fonts
//$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setHeaderFont(Array('nanumbarungothicyethangul', '', '8'));
//$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->setFooterFont(Array('nanumbarungothicyethangul', '', '8'));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/kor.php')) {
	require_once(dirname(__FILE__).'/lang/kor.php');
	$pdf->setLanguageArray($l);
}

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
			$con_search .= "제품명 : {$stx}/";
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
	$con_search .= "분류 : {$cn}/";
}

// 등록일 구간 검색
if($fd && $td) {
	$sql_search .= " and wr_datetime between '".$fd." 00:00:00' and '".$td." 23:59:59' ";
	$con_search .= "등록일 : {$fd}~{$td}/";
}

// 창고위치 : 여분필드1 검색 (다중 체크박스 - 다수 선택시 2차원 배열로 넘어옴)
if($w1) {

	foreach($w1 as $k=>$v) {
		$w1_v .= ",'".$v."'";
	}
	$w1 = substr($w1_v,1); // 첫번째 콤마(,) 제거
	$sql_search .= " and wr_1 in (".$w1.") ";
	$con_search .= "창고위치 : {$w1}/";
}

// 여분필드2 검색
if($w2) {
	$sql_search .= " and wr_2 = '".$w2."' ";
	$con_search .= "크기 : {$w2}/";
}

// 여분필드3 검색
if($w3) {
	$sql_search .= " and wr_3 = '".$w3."' ";
	$con_search .= "관리팀 : {$w3}/";
}

// 여분필드4 검색
if($w4) {
	$sql_search .= " and wr_4 = '".$w4."' ";
	$con_search .= "색상 : {$w4}/";
}

// 여분필드5 검색
if($w5) {
	$sql_search .= " and wr_5 = '".$w5."' ";
	$con_search .= "재질 : {$w5}/";
}

// 여분필드6 검색
if($w6) {
	$sql_search .= " and wr_6 = '".$w6."' ";
	$con_search .= "A/S : {$w6}/";
}

// 여분필드7 검색
if($w7) {
	$sql_search .= " and wr_7 = '".$w7."' ";
	$con_search .= "배송 : {$w7}/";
}

// 여분필드8 검색
if($w8) {
	$sql_search .= " and wr_8 = '".$w8."' ";
	$con_search .= "용량 : {$w8}/";
}

// 여분필드9 검색
if($w9) {
	$sql_search .= " and wr_9 = '".$w9."' ";
	$con_search .= "상품코드 : {$w9}/";
}

// 여분필드10 검색 (입고일 구간 검색)
if($fd10 && $td10) {
	$sql_search .= " and wr_10 between '".$fd10."' and '".$td10."' ";
	$con_search .= "입고일 : {$fd10}~{$td10}/";
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

// 검색조건
$con_search = substr( $con_search , 0, strlen($con_search )-1); // 마지막 한글자 삭제
// DB 쿼리 끝.


$pdf->AddPage();
$pdf->SetFillColor(238,238,238); // 배경 음영색 (회색)

$pdf->SetFont('nanumbarungothicyethangul', '', 7); // 글꼴 설정.
$pdf->MultiCell(20, 6, '회사명', 'LTB', 'C', 1, 0, '', '', true, 0, false, true, 6, 'M');
$pdf->MultiCell(35, 6, '노예같은 집사 협회', 'LTB', 'C', 0, 0, '', '', true, 0, false, true, 6, 'M');
$pdf->MultiCell(20, 6, '사업자번호', 'LTB', 'C', 1, 0, '', '', true, 0, false, true, 6, 'M');
$pdf->MultiCell(35, 6, '123-45-6789', 'LTB', 'C', 0, 0, '', '', true, 0, false, true, 6, 'M');
$pdf->MultiCell(20, 6, '전화', 'LTB', 'C', 1, 0, '', '', true, 0, false, true, 6, 'M');
$pdf->MultiCell(35, 6, '031-9999-9999', 'LTB', 'C', 0, 0, '', '', true, 0, false, true, 6, 'M');
$pdf->MultiCell(20, 6, '팩스', 'LTB', 'C', 1, 0, '', '', true, 0, false, true, 6, 'M');
$pdf->MultiCell(34, 6, '031-9999-9998', 'LTBR', 'C', 0, 0, '', '', true, 0, false, true, 6, 'M');
$pdf->MultiCell(20, 6, '출력시간', 'LTB', 'C', 1, 0, '', '', true, 0, false, true, 6, 'M');
$pdf->MultiCell(34, 6, G5_TIME_YMDHIS, 'LTBR', 'C', 0, 1, '', '', true, 0, false, true, 6, 'M');

$pdf->MultiCell(20, 6, '문서제목', 'LTB', 'C', 1, 0, '', '', true, 0, false, true, 6, 'M');
$pdf->MultiCell(35, 6, '문서 제목 설정', 'LBR', 'C', 0, 0, '', '', true, 0, false, true, 6, 'M');
$pdf->MultiCell(20, 6, '주요검색조건', 'LTB', 'C', 1, 0, '', '', true, 0, false, true, 6, 'M');
$pdf->MultiCell(144, 6, $con_search, 'LTB', 'L', 0, 0, '', '', true, 0, false, true, 6, 'M');
$pdf->MultiCell(20, 6, '검색결과', 'LTB', 'C', 1, 0, '', '', true, 0, false, true, 6, 'M');
$pdf->MultiCell(34, 6, '총 '.$total_count.'건', 'LTBR', 'C', 0, 1, '', '', true, 0, false, true, 6, 'M');

$pdf->Ln(2);


$pdf->SetFont('nanumbarungothicyethangul', '', 7);
$pdf->setCellPaddings( $left = '0.7', $top = '1', $right = '0.7', $bottom = '1');
$tbl = <<<EOD
<style>
table { width:100%; border-spacing:0; border-collapse:collapse; }
th { border:0px solid #000000; }
td { border:0px solid #000000; vertical-align:middle; }

.td_01 { width:4%; }
.td_02 { width:8%; }
.td_03 { width:17%; }
.td_04 { width:8%; }
.td_05 { width:11%; }
.td_06 { width:7%; }
.td_07 { width:6%;}
.td_08 { width:6%; }
.td_09 { width:4%; }
.td_10 { width:6%; }
.td_11 { width:6%; }
.td_12 { width:6%; }
.td_13 { width:6%; }
.td_14 { width:*; }

</style>
<table>
	<thead>
		<tr style="text-align:center;line-height:22px;background-color:#eeeeee;">
			<th class="td_01">번호</th>
			<th class="td_02">분류</th>
			<th class="td_03">제품명(모델명)</th>
			<th class="td_04">상품코드</th>
			<th class="td_05">등록일시</th>
			<th class="td_06">창고입고일</th>
			<th class="td_07">관리팀</th>
			<th class="td_08">창고위치</th>
			<th class="td_09">크기</th>
			<th class="td_10">색상</th>
			<th class="td_11">재질</th>
			<th class="td_12">A/S</th>
			<th class="td_13">배송여부</th>
			<th class="td_14">용량</th>
		</tr>
	</thead>
	<tbody>
EOD;
$num=0;
for($i=0; $row=sql_fetch_array($result); $i++) {

	$num = $i+1; // 번호

	$WR_1 = $WR1[$row[wr_1]];
	$WR_2 = $WR2[$row[wr_2]];
	$WR_3 = $WR3[$row[wr_3]];
	$WR_4 = $WR4[$row[wr_4]];
	$WR_5 = $WR5[$row[wr_5]];
	$WR_6 = $WR6[$row[wr_6]];
	$WR_7 = $WR7[$row[wr_7]];
	$WR_8 = $WR8[$row[wr_8]];


	$tbl .= <<<EOD
		<tr align="center" nobr="true">
			<td class="td_01">$num</td>
			<td class="td_02">$row[ca_name]</td>
			<td class="td_03" align="left">$row[wr_subject]</td>
			<td class="td_04">$row[wr_9]</td>
			<td class="td_05">$row[wr_datetime]</td>

			<td class="td_06">$row[wr_10]</td>
			<td class="td_07">$WR_3</td>
			<td class="td_08">$WR_1</td>
			<td class="td_09">$WR_2</td>
			<td class="td_10">$WR_4</td>

			<td class="td_11">$WR_5</td>
			<td class="td_12">$WR_6</td>
			<td class="td_13">$WR_7</td>
			<td class="td_14">$WR_8</td>

		</tr>
EOD;
}
$tbl .= <<<EOD
	</tbody>
	<tfoot>
		<tr style="line-height:18px;background-color:#eeeeee;">
			<th colspan="14" align="left" class="td_14"> 추가내용 : 본 문서는 [ 대외비 ] 입니다.</th>
		</tr>
	</tfoot>
</table>
EOD;

$pdf->writeHTML($tbl, true, false, false, false, '');
//$tbl = '메모리 사용량 : '.number_format(memory_get_usage()).'Byte ('.(memory_get_usage() / 1000).'KB)';
//$pdf->writeHTML($tbl, true, false, false, false, '');
$pdf->Output('grid_list.pdf', 'I'); // PDF로 저장시 기본 파일명

//============================================================+
// END OF FILE
//============================================================+
