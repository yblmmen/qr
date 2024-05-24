<?php
include_once('./_common.php');
include_once(G5_PATH.'/lib/common.lib.php');
set_time_limit(0);
ini_set('memory_limit', '640M');

require_once('./tcpdf_include.php');

$pdf = new TCPDF('P', PDF_UNIT, 'A4', true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->setPrintHeader(false);
$pdf->setFooterFont(Array('nanumbarungothicyethangul', '', '8'));
$pdf->SetMargins(PDF_MARGIN_LEFT, 22, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

if (@file_exists(dirname(__FILE__).'/lang/kor.php')) {
	require_once(dirname(__FILE__).'/lang/kor.php');
	$pdf->setLanguageArray($l);
}

function subject_list($uid) {

	global $member;

	// 작업명 정리.
	$sql = "select
				work_name
			from
				g4_work_list
			where
				work_uid = '{$uid}' and office_id = '".$member[mb_id]."' ";
	$ro = sql_query($sql);
	//echo $sql;
	$subject='';
	while($data = sql_fetch_array($ro)) {
		$subject .= ", ".$data['work_name'];
	}
	$subject = substr($subject,1);

	return $subject;
}

function insure_list($uid) {

	global $member;

	// 보험건 정리.
	$sql = "select
				ins_name,
				ins_section,
				ins_money
			from
				g4_insure
			where
				work_uid = '{$uid}' and office_id = '".$member[mb_id]."' ";
	$ro = sql_query($sql);
	//echo $sql;
	unset($insure_name);
	while($data = sql_fetch_array($ro)) {
		$insure_name .= ", ".$data['ins_name']." ".$data['ins_section'];
		if($data['ins_section']=="보험" && $data['ins_money'] > 0) {
			$insure_money = " [면책금 : ".number_format($data['ins_money'])."원]";
		}
	}
	$insure_name = "<br/>[".substr($insure_name,1)."]".$insure_money;

	return $insure_name;
}


// DB 쿼리 시작
$table = "g4_work";
$sql_common = " from $table";
$sql_search = " where wr_is_comment = '0' and mb_id = '$member[mb_id]' ";

// 검색실행 - 작업일자
if($fd && $td) {
	$sql_search .= " and wr_1 between '$fd' and '$td'";
}

// 검색실행 - 작업구분
if($ca) {
	$sql_search .= " and ca_name = '$ca' ";
}


// 검색실행 - 거래처
if($cs) {
	$sql_search .= " and wr_3 = '$cs' ";
}

//검색실행 - 기타조건 (차량번호, 차량명칭, 고객아이디, 고객명)
if ($stx) {
	$stxx = explode(" ", $stx);
    $sql_search .= " and ( ";
    switch ($sfl) {
        case "1" : // 차량번호
            $sql_search .= " (wr_subject like '%$stx%') ";
            break;
		case "2" : // 차량명칭
            $sql_search .= " (wr_link1 like '%$stx%') ";
            break;
		case "3" : // 고객이름
            $sql_search .= " (wr_link2 like '%$stx%') ";
            break;
		case "4" : // 고객전화
            $sql_search .= " (wr_link2 like '%$stx%') ";
            break;
		case "5" : // 고객휴대폰
            $sql_search .= " (wr_link2 like '%$stx%') ";
            break;
		default :
            $sql_search .= " ($sfl like '%$stx%') ";
            break;
    }
    $sql_search .= " ) ";
}

$sql_order = " order by wr_datetime desc ";

$sql = " select
			*
		  $sql_common
          $sql_search
          $sql_order ";
$result = sql_query($sql, true);
// DB 쿼리 끝.

$sql = "select
			sum(wr_15) as total_amount
		$sql_common
        $sql_search";
$res = sql_fetch($sql);



$pdf->AddPage();
$pdf->SetFillColor(238,238,238); // 배경 음영색 (회색)

$pdf->SetFont('nanumbarungothicyethangul', '', 18); // 글꼴 설정.
$pdf->MultiCell(40, 8, '거 래 명 세 표', 'B', 'C', 0, 1, 86, '', true, 0, false, true, 8, 'T');

$pdf->Ln(8);

$pdf->SetFont('nanumbarungothicyethangul', '', 6); // 글꼴 설정.
$pdf->MultiCell(90, 3, '출력시간 : '.G5_TIME_YMDHIS, '', 'L', 0, 1, '', '', true, 0, false, true, 3, 'T');

$co_h = 9;
$co_w = 93;
$co_title = 20;
$co_con1 = 73;
$co_con2 = 26;
$co_con3 = 27;

//업체주소
$addr1 = $office[office_addr1].$office[office_addr2].$office[office_addr3];
$addr2 = $rs[co_addr1].$rs[co_addr2].$rs[co_addr3];
//$addr .= $office[office_addr1].$office[office_addr2].$office[office_addr3];

$pdf->setFormDefaultProp(array('lineWidth'=>0, 'borderStyle'=>'solid', 'fillColor'=>array(), 'strokeColor'=>array(0, 0, 0)));


$pdf->SetFont('nanumbarungothicyethangul', '', 10); // 글꼴 설정.
$pdf->MultiCell($co_w, $co_h, '공 급 자 정 보', 'LT', 'C', 1, 0, '', '', true, 0, false, true, $co_h, 'M');
$pdf->MultiCell($co_w, $co_h, '공 급 받 는 자 정 보', 'LTR', 'C', 1, 1, '', '', true, 0, false, true, $co_h, 'M');

$pdf->SetFont('nanumbarungothicyethangul', '', 8); // 글꼴 설정.
$pdf->MultiCell($co_title, $co_h, '사업자번호', 'LT', 'C', 1, 0, '', '', true, 0, false, true, $co_h, 'M');
$pdf->MultiCell($co_con1, $co_h, $office[office_license], 'LT', 'C', 0, 0, '', '', true, 0, false, true, $co_h, 'M');
$pdf->MultiCell($co_title, $co_h, '사업자번호', 'LT', 'C', 1, 0, '', '', true, 0, false, true, $co_h, 'M');
$pdf->MultiCell($co_con1, $co_h, $rs[co_sn], 'LTR', 'C', 0, 1, '', '', true, 0, false, true, $co_h, 'M');

$pdf->MultiCell($co_title, $co_h, '회사명', 'LT', 'C', 1, 0, '', '', true, 0, false, true, $co_h, 'M');
$pdf->MultiCell($co_con1, $co_h, $office[office_name], 'LT', 'C', 0, 0, '', '', true, 0, false, true, $co_h, 'M');
$pdf->MultiCell($co_title, $co_h, '회사명', 'LT', 'C', 1, 0, '', '', true, 0, false, true, $co_h, 'M');
$pdf->MultiCell($co_con1, $co_h, $biz_name, 'LTR', 'C', 0, 1, '', '', true, 0, false, true, $co_h, 'M');

$pdf->MultiCell($co_title, $co_h, '대표자', 'LT', 'C', 1, 0, '', '', true, 0, false, true, $co_h, 'M');
$pdf->MultiCell($co_con2, $co_h, $office[office_ceo], 'LT', 'C', 0, 0, '', '', true, 0, false, true, $co_h, 'M');
$pdf->MultiCell($co_title, $co_h, '전화', 'LT', 'C', 1, 0, '', '', true, 0, false, true, $co_h, 'M');
$pdf->MultiCell($co_con3, $co_h, $office[office_tel], 'LT', 'C', 0, 0, '', '', true, 0, false, true, $co_h, 'M');
$pdf->MultiCell($co_title, $co_h, '대표자', 'LT', 'C', 1, 0, '', '', true, 0, false, true, $co_h, 'M');
$pdf->MultiCell($co_con2, $co_h, $rs[co_name], 'LT', 'C', 0, 0, '', '', true, 0, false, true, $co_h, 'M');
$pdf->MultiCell($co_title, $co_h, '전화', 'LT', 'C', 1, 0, '', '', true, 0, false, true, $co_h, 'M');
$pdf->MultiCell($co_con3, $co_h, $rs[co_tel1], 'LTR', 'C', 0, 1, '', '', true, 0, false, true, $co_h, 'M');

$pdf->MultiCell($co_title, $co_h, '업태', 'LT', 'C', 1, 0, '', '', true, 0, false, true, $co_h, 'M');
$pdf->MultiCell($co_con2, $co_h, $office[office_uptae], 'LT', 'C', 0, 0, '', '', true, 0, false, true, $co_h, 'M');
$pdf->MultiCell($co_title, $co_h, '종목', 'LT', 'C', 1, 0, '', '', true, 0, false, true, $co_h, 'M');
$pdf->MultiCell($co_con3, $co_h, $office[office_jongmok], 'LT', 'C', 0, 0, '', '', true, 0, false, true, $co_h, 'M');
$pdf->MultiCell($co_title, $co_h, '업태', 'LT', 'C', 1, 0, '', '', true, 0, false, true, $co_h, 'M');
$pdf->MultiCell($co_con2, $co_h, $rs[co_uptea], 'LT', 'C', 0, 0, '', '', true, 0, false, true, $co_h, 'M');
$pdf->MultiCell($co_title, $co_h, '종목', 'LT', 'C', 1, 0, '', '', true, 0, false, true, $co_h, 'M');
$pdf->MultiCell($co_con3, $co_h, $rs[co_jongmok], 'LTR', 'C', 0, 1, '', '', true, 0, false, true, $co_h, 'M');

$pdf->MultiCell($co_title, $co_h, '주소', 'LTB', 'C', 1, 0, '', '', true, 0, false, true, $co_h, 'M');
$pdf->MultiCell($co_con1, $co_h, $addr1, 'LTB', 'C', 0, 0, '', '', true, 0, false, true, $co_h, 'M');
$pdf->MultiCell($co_title, $co_h, '주소', 'LTB', 'C', 1, 0, '', '', true, 0, false, true, $co_h, 'M');
$pdf->MultiCell($co_con1, $co_h, $addr2, 'LTRB', 'C', 0, 1, '', '', true, 0, false, true, $co_h, 'M');

$pdf->Ln(3);
$pdf->SetFont('nanumbarungothicyethangul', '', 10); // 글꼴 설정.
$pdf->MultiCell(186, 9, '합계 : '.number_format($res[total_amount]).' 원(VAT포함)', 'LTBR', 'R', 0, 1, '', '', true, 0, false, true, 9, 'M');
$pdf->Ln(3);

$pdf->SetFont('nanumbarungothicyethangul', '', 7);
$pdf->setCellPaddings( $left = '1.2', $top = '1.2', $right = '0', $bottom = '1.2');

$tbl = <<<EOD
<style>
table { width:100%; border-spacing:0; border-collapse:collapse; }
th { border:0px solid #000000; vertical-align:middle; height:25px;line-height:16px; }
td { border:0px solid #000000; vertical-align:middle; height:17px;line-height:17px;}

.td_01 { width:5%; text-align:center; }
.td_02 { width:9.5%;  text-align:center; }
.td_03 { width:4%; text-align:center; }
.td_04 { width:10%; text-align:right; }
.td_05 { width:40%; text-align:left; }
.td_06 { width:9%; text-align:right; }
.td_07 { width:*; text-align:right; }
.td_08 { width:14%; text-align:left; }
</style>
<table>
	<thead>
		<tr style="text-align:center;background-color:#eeeeee;">
			<th class="td_01">No</th>
			<th class="td_02">작업일자</th>
			<th class="td_03">구분</th>
			<th class="td_04" align="center">차량번호</th>
			<th class="td_08" align="center">차량명칭</th>

			<th class="td_05" align="center">작업명 및 부품명</th>
			<th class="td_06" align="center">금액</th>
			<th class="td_07" align="center">비고(할인)</th>
		</tr>
	</thead>
	<tbody>
EOD;
$num=0;
for($i=0; $row=sql_fetch_array($result); $i++) {

	$num = $i+1; // 번호
	$subject = subject_list($row[wr_id]); //작업명 및 부품명칭 합치기
	if($row[ca_name]=="보험") {
		$insure	= insure_list($row[wr_id]); // 보험건일경우.
	} else {
		$insure="";
	}

	$amount_total_all = ($row[wr_15]!=0)? number_format($row[wr_15]):""; // 금액
	$work_price_dc = ($row[work_price_dc]!=0)? number_format($row[work_price_dc]):""; // 할인금액
	$wr_link1 = explode("^",$row[wr_link1]);
	$car_name = cut_str($wr_link1[1],12,'');

	$amount_hap	+= $row[wr_15];

	$tbl .= <<<EOD
		<tr align="center" nobr="true">
			<td class="td_01">$num</td>
			<td class="td_02">$row[wr_1]</td>
			<td class="td_03">$row[ca_name]</td>
			<td class="td_04">$row[wr_subject]</td>
			<td class="td_08">$car_name</td>
			<td class="td_05">$subject $insure</td>
			<td class="td_06">$amount_total_all</td>
			<td class="td_07">$work_price_dc</td>
		</tr>
EOD;
}
$amount_hap	= number_format($amount_hap);

$tbl .= <<<EOD
	</tbody>
	<tfoot>
		<tr style="background-color:#eeeeee;">
			<td colspan="6" style="text-align:right;">합계</td>
			<td style="text-align:right;">$amount_hap</td>
			<td style="text-align:right;"></td>
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
