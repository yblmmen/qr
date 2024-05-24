<?php
include_once('./_common.php');
include_once(G5_PATH.'/lib/common.lib.php');
set_time_limit(0);
ini_set('memory_limit', '640M');

require_once('./tcpdf_include.php');

//$pdf = new TCPDF('P-가로인쇄, L-세로인쇄', PDF_UNIT, 'A4', true, 'UTF-8', false);
$pdf = new TCPDF('L', PDF_UNIT, 'A4', true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('http://www.carway.co.kr');
$pdf->SetTitle('http://www.carway.co.kr');
$pdf->SetSubject('http://www.carway.co.kr');
$pdf->SetKeywords('http://www.carway.co.kr');


// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.'038', PDF_HEADER_STRING);
$name1 = "CARWAY  -  http://www.carway.co.kr";
$name2 = "효율적이고 사용이 간편한 자동차관리프로그램 카웨이로 시작하세요!";

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
$con_search = "";
$sql_common = " from cm_cash_receipt";
$sql_search = " where mb_id = '$member[mb_id]' ";

// 검색실행 - 거래기간 선택
if($fd & $td) {
	$sql_search .= " and cr_date between '{$fd} 00:00:00' and '{$td} 23:59:59'";
	$con_search .= "거래기간 : {$fd} ~ {$td}";
}

// 검색실행 - 취소일자
if($cfd & $ctd) {
	$sql_search .= " and cr_cancel_date between '{$cfd}' and '{$ctd}'";
	$con_search .= "취소일자 : {$cfd} ~ {$ctd}";
}

// 검색실행 - 거래유형
if($ct & $ct) {
	$sql_search .= " and cr_type = '$ct'";
	$con_search .= "거래유형 : {$ct}";
}

// 검색실행 - 발급구분
if($cs & $cs) {
	$sql_search .= " and cr_section = '$cs'";
	$con_search .= "발급구분 : {$cs}";
}

// 검색실행 - 팀명선택
if($wt & $wt) {
	$sql_search .= " and work_team = '$wt'";
	$con_search .= "팀명칭 : {$wt}";
}


if ($stx) {
	$stxx = explode(" ", $stx);
    $sql_search .= " and ( ";
    switch ($sfl) {
        case "cc" :
            $sql_search .= " (cr_approval = '$stx') ";
            break;
        case "cn" :
            $sql_search .= " (cr_number = '$stx') ";
            break;
        case "wt" :
            $sql_search .= " (work_team = '$stx') ";
            break;
		default :
            $sql_search .= " ($sfl like '%$stx%') ";
            break;
    }
    $sql_search .= " ) ";
}
$sql_order = " order by cr_date desc ";

$sql = "select
			*
		$sql_common
		$sql_search
		$sql_order ";
$result = sql_query($sql, true);
$total_count = sql_num_rows($result);
//echo $sql;
$con_search = substr( $con_search , 0, strlen($con_search )-1); // 마지막 한글자 삭제

// 출력할 업체의 정보 불러오기
$sql = "select
			*
		from
			cm_office
		where
			mb_id = '".$member['mb_id']."' ";
$off = sql_fetch($sql);

// DB 쿼리 끝.
$pdf->AddPage();
$pdf->SetFillColor(238,238,238); // 배경 음영색 (회색)

$pdf->SetFont('nanumbarungothicyethangul', '', 7); // 글꼴 설정.
$pdf->MultiCell(20, 6, '회사명', 'LTB', 'C', 1, 0, '', '', true, 0, false, true, 6, 'M');
$pdf->MultiCell(35, 6, $off[office_name], 'LTB', 'C', 0, 0, '', '', true, 0, false, true, 6, 'M');
$pdf->MultiCell(20, 6, '사업자번호', 'LTB', 'C', 1, 0, '', '', true, 0, false, true, 6, 'M');
$pdf->MultiCell(35, 6, $off[office_license], 'LTB', 'C', 0, 0, '', '', true, 0, false, true, 6, 'M');
$pdf->MultiCell(20, 6, '전화', 'LTB', 'C', 1, 0, '', '', true, 0, false, true, 6, 'M');
$pdf->MultiCell(35, 6, $off[office_tel], 'LTB', 'C', 0, 0, '', '', true, 0, false, true, 6, 'M');
$pdf->MultiCell(20, 6, '팩스', 'LTB', 'C', 1, 0, '', '', true, 0, false, true, 6, 'M');
$pdf->MultiCell(34, 6, $off[office_fax], 'LTBR', 'C', 0, 0, '', '', true, 0, false, true, 6, 'M');
$pdf->MultiCell(20, 6, '출력시간', 'LTB', 'C', 1, 0, '', '', true, 0, false, true, 6, 'M');
$pdf->MultiCell(34, 6, G5_TIME_YMDHIS, 'LTBR', 'C', 0, 1, '', '', true, 0, false, true, 6, 'M');

$pdf->MultiCell(20, 6, '문서제목', 'LTB', 'C', 1, 0, '', '', true, 0, false, true, 6, 'M');
$pdf->MultiCell(35, 6, '현금영수증 발행 현황', 'LBR', 'L', 0, 0, '', '', true, 0, false, true, 6, 'M');
$pdf->MultiCell(20, 6, '주요검색조건', 'LTB', 'C', 1, 0, '', '', true, 0, false, true, 6, 'M');
$pdf->MultiCell(144, 6, $con_search, 'LTB', 'L', 0, 0, '', '', true, 0, false, true, 6, 'M');
$pdf->MultiCell(20, 6, '검색결과', 'LTB', 'C', 1, 0, '', '', true, 0, false, true, 6, 'M');
$pdf->MultiCell(34, 6, '총 '.$total_count.'건', 'LTBR', 'C', 0, 1, '', '', true, 0, false, true, 6, 'M');

$pdf->Ln(2);


$pdf->SetFont('nanumbarungothicyethangul', '', 7);
$pdf->setCellPaddings( $left = '0.7', $top = '1', $right = '0', $bottom = '1');
$tbl = <<<EOD
<style>
table { width:100%; border-spacing:0; border-collapse:collapse; }
th { border:0px solid #000000; }
td { border:0px solid #000000; vertical-align:middle; }

.td_01 { width:3%; }
.td_02 { width:10.5%; }
.td_03 { width:6%; }
.td_04 { width:6%; }
.td_05 { width:6%; }

.td_06 { width:6%; }
.td_07 { width:5%; }
.td_08 { width:8%; }
.td_09 { width:12%; }
.td_10 { width:7%; }

.td_11 { width:6%; }
.td_12 { width:7%; }
.td_13 { width:*; text-align:left; }
</style>
<table>
	<thead>
		<tr style="text-align:center;background-color:#eeeeee;">
			<th class="td_01">번호</th>
			<th class="td_02">거래일자</th>
			<th class="td_03">거래유형</th>
			<th class="td_04">승인번호</th>
			<th class="td_05">거래금액</th>

			<th class="td_06">부가세</th>
			<th class="td_07">발급구분</th>
			<th class="td_08">발급번호</th>
			<th class="td_09">취소사유</th>
			<th class="td_10">원거래취소일자</th>

			<th class="td_11">팀명칭</th>
			<th class="td_12">차량번호</th>
			<th class="td_13">메모</th>
		</tr>
	</thead>
	<tbody>
EOD;
$num=0;
for($i=0; $row=sql_fetch_array($result); $i++) {

	$num = $i+1; // 번호

	$cr_money = number_format($row['cr_money']);
	$cr_vat = number_format($row['cr_vat']);

	$cr_money_hap += $row['cr_money'];
	$cr_vat_hap += $row['cr_vat'];

	$tbl .= <<<EOD
		<tr align="center" nobr="true">
			<td class="td_01">$num</td>
			<td class="td_02">$row[cr_date]</td>
			<td class="td_03">$row[cr_type]</td>
			<td class="td_04">$row[cr_approval]</td>
			<td class="td_05" align="right">$cr_money</td>

			<td class="td_06" align="right">$cr_vat</td>
			<td class="td_07">$row[cr_section]</td>
			<td class="td_08">$row[cr_number]</td>
			<td class="td_09">$row[cr_cancel_memo]</td>
			<td class="td_10">$row[cr_cancel_date]</td>

			<td class="td_11">$row[work_team]</td>
			<td class="td_12">$row[car_no]</td>
			<td class="td_13">$row[memo]</td>
		</tr>
EOD;
}

$cr_money_hap = number_format($cr_money_hap); // 연금(개인+회사)
$cr_vat_hap = number_format($cr_vat_hap); // 고용(개인+회사)

$tbl .= <<<EOD
	</tbody>
	<tfoot>
		<tr style="line-height:12px;background-color:#eeeeee;">
			<th colspan="4" align="right">합계</th>
			<th align="right">$cr_money_hap</th>
			<th align="right">$cr_vat_hap</th>
			<th class="td_13" colspan="7"></th>
		</tr>
	</tfoot>
</table>
EOD;

$pdf->writeHTML($tbl, true, false, false, false, '');
//$tbl = '메모리 사용량 : '.number_format(memory_get_usage()).'Byte ('.(memory_get_usage() / 1000).'KB)';
//$pdf->writeHTML($tbl, true, false, false, false, '');
$pdf->Output('bank_account_lsit3.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
