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

## DB 시작
// 거래처 셀렉트박스 생성
$sql = "select
			id_no,
			co_company
		from
			cm_purchase_company
		where
			co_section in ('2','3') and mb_id = '$member[mb_id]'
		order by
			co_company asc ";
$res = sql_query($sql, true);
while ($data = sql_fetch_array($res)) {
	$customer[$data['id_no']] = $data['co_company'];
}


$con_search = '';
$mbid = "a.mb_id = '".$member[mb_id]."'";
$table = "cm_work_sub a left join cm_carlist b on (a.car_no_id = b.id_no)";
$sql_common = " from $table";
$sql_search = " where $mbid ";


// 검색실행 - 작업일자
if($fd && $td) {
	$sql_search .= " and a.work_start between '$fd' and '$td'";
	$con_search .= "작업날짜:{$fd}~{$td}/";

}

// 검색실행 - 접수구분
if(strlen($sec)>0) {
	$sql_search .= " and a.work_section = '$sec' ";
	$con_search .= "접수구분:".$WORK_SECTION_S[$sec]."/";
}

// 검색실행 - 거래처
if($cs) {
	$sql_search .= " and a.customer_no = '$cs' ";
	$con_search .= "거래처:".$customer[$cs]."/";
}

// 검색실행 - 보험사명
if($inn) {
	$sql_search .= " and (
							select
								count(*) as cnt
							from
								cm_insure
							where
								ins_name = '".$inn."'
								and work_uid = a.work_uid) > '0' ";
	$con_search .= "보험사명:".$INSURE_NAME[$inn]."/";

}

// 검색실행 - 보험담당자
if($ind) {
	$sql_search .= " and (
							select
								count(*) as cnt
							from
								cm_insure
							where
								ins_damdang = '".$ind."'
								and work_uid = a.work_uid) > '0' ";
	$con_search .= "보험담당:{$ind}/";

}

// 검색실행 - 보험접수구분
if($ins) {
	$sql_search .= " and (
							select
								count(*) as cnt
							from
								cm_insure
							where
								ins_section = '".$ins."'
								and work_uid = a.work_uid) > '0' ";
	$con_search .= "보험접수:".$INSURE_SECTION[$ins]."/";

}

// 검색실행 - 보험진행상태
if($intt) {
	$sql_search .= " and (
							select
								count(*) as cnt
							from
								cm_insure
							where
								ins_status = '".$intt."'
								and work_uid = a.work_uid) > '0' ";
	$con_search .= "보험진행상태:".$INSURE_STATUS[$intt]."/";
}


//검색실행 - 기타조건 (차량번호, 차량명칭, 고객아이디, 고객명)
if ($stx) {
	$stxx = explode(" ", $stx);
    $sql_search .= " and ( ";
    switch ($sfl) {
        case "car_no" : // 차량번호
            $sql_search .= " (a.car_no like '%$stx%') ";
			$con_search .= "차량번호:{$stx}/";
            break;
		case "car_name" : // 차량명칭
            $sql_search .= " (b.car_name like '%$stx%') ";
			$con_search .= "차량명:{$stx}/";
            break;
		case "mb_id" : // 고객아이디
            $sql_search .= " (b.mb_name = '$stx') ";
			$con_search .= "고객ID:{$stx}/";
            break;
		case "mb_name" : // 고객이름
            $sql_search .= " (b.mb_name = '$stx') ";
			$con_search .= "고객명:{$stx}/";
            break;
		case "mb_hp" : // 고객 휴대폰번호
            $sql_search .= " (b.mb_hp = '$stx') ";
			$con_search .= "휴대전화:{$stx}/";
            break;
		case "mb_tel" : // 고객 일반전화번호
            $sql_search .= " (b.mb_tel = '$stx') ";
			$con_search .= "일반전화:{$stx}/";
            break;
		default :
            $sql_search .= " ($sfl like '%$stx%') ";
            break;
    }
    $sql_search .= " ) ";
}

$sql_order = " order by a.work_uid desc ";

$sql = " select
			a.*,
			b.car_name,
			b.mb_id,
			b.mb_name,
			b.mb_hp,
			b.mb_tel
          $sql_common
          $sql_search
          $sql_order ";
$result = sql_query($sql, true);
$con_search = substr( $con_search , 0, strlen($con_search )-1); // 마지막 한글자 삭제
## DB 끝
##################################
##################################


$pdf->AddPage();
$pdf->SetFillColor(238,238,238); // 배경 음영색 (회색)

$pdf->SetFont('nanumbarungothicyethangul', '', 7); // 글꼴 설정.
$pdf->MultiCell(20, 6, '회사명', 'LTB', 'C', 1, 0, '', '', true, 0, false, true, 6, 'M');
$pdf->MultiCell(35, 6, $office[office_name], 'LTB', 'C', 0, 0, '', '', true, 0, false, true, 6, 'M');
$pdf->MultiCell(20, 6, '사업자번호', 'LTB', 'C', 1, 0, '', '', true, 0, false, true, 6, 'M');
$pdf->MultiCell(35, 6, $office[office_license], 'LTB', 'C', 0, 0, '', '', true, 0, false, true, 6, 'M');
$pdf->MultiCell(20, 6, '전화', 'LTB', 'C', 1, 0, '', '', true, 0, false, true, 6, 'M');
$pdf->MultiCell(35, 6, $office[office_tel], 'LTB', 'C', 0, 0, '', '', true, 0, false, true, 6, 'M');
$pdf->MultiCell(20, 6, '팩스', 'LTB', 'C', 1, 0, '', '', true, 0, false, true, 6, 'M');
$pdf->MultiCell(34, 6, $office[office_fax], 'LTBR', 'C', 0, 0, '', '', true, 0, false, true, 6, 'M');
$pdf->MultiCell(20, 6, '출력시간', 'LTB', 'C', 1, 0, '', '', true, 0, false, true, 6, 'M');
$pdf->MultiCell(34, 6, G5_TIME_YMDHIS, 'LTBR', 'C', 0, 1, '', '', true, 0, false, true, 6, 'M');

$pdf->MultiCell(20, 6, '문서제목', 'LTB', 'C', 1, 0, '', '', true, 0, false, true, 6, 'M');
$pdf->MultiCell(35, 6, '작업현황', 'LBR', 'L', 0, 0, '', '', true, 0, false, true, 6, 'M');
$pdf->MultiCell(20, 6, '주요검색조건', 'LTB', 'C', 1, 0, '', '', true, 0, false, true, 6, 'M');
$pdf->MultiCell(144, 6, $con_search, 'LTB', 'L', 0, 0, '', '', true, 0, false, true, 6, 'M');
$pdf->MultiCell(20, 6, '검색결과', 'LTB', 'C', 1, 0, '', '', true, 0, false, true, 6, 'M');
$pdf->MultiCell(34, 6, '총 '.$total_count.'건', 'LTBR', 'C', 0, 1, '', '', true, 0, false, true, 6, 'M');

$pdf->Ln(2);


$pdf->SetFont('nanumbarungothicyethangul', '', 7);
$pdf->setCellPaddings( $left = '1.2', $top = '1.2', $right = '1.2', $bottom = '1.2');

$tbl = <<<EOD
<style>
table { width:100%; border-spacing:0; border-collapse:collapse; }
th { border:0px solid #000000; }
td { border:0px solid #000000; vertical-align:middle; }

.td_01 { width:4%; }
.td_02 { width:4%; }
.td_03 { width:7%; }
.td_04 { width:7%; }
.td_05 { width:7%; }

.td_06 { width:12%; }
.td_07 { width:12%;}
.td_08 { width:12%; text-align:right;}
.td_09 { width:7%; text-align:right; }
.td_10 { width:7%; text-align:right; }

.td_11 { width:7%; text-align:right; }
.td_12 { width:7%; text-align:right; }
.td_13 { width:*; text-align:right; }
</style>
<table>
	<thead>
		<tr style="text-align:center;background-color:#eeeeee;">
			<th class="td_01">번호</th>
			<th class="td_02">구분</th>
			<th class="td_03">작업번호</th>
			<th class="td_04">작업날짜</th>
			<th class="td_05">출고일</th>

			<th class="td_06">거래처</th>
			<th class="td_07">고객명</th>
			<th class="td_08">차량번호</th>
			<th class="td_09">차량명칭</th>
			<th class="td_10">주행거리</th>

			<th class="td_11">견적금액</th>
			<th class="td_12">결제금액</th>
			<th class="td_13">미수금액</th>
		</tr>
	</thead>
	<tbody>
EOD;
$num=0;
for($i=0; $row=sql_fetch_array($result); $i++) {

	$num = $i+1; // 번호
	$work_section	= $WORK_SECTION_S[$row[work_section]];
	$customer		= $customer[$row[customer_no]];
	$car_name		= cut_str($row['car_name'],10,'');
	$car_km			= number_format($row['car_km']);

	$amount_all		= number_format($row['amount_total_all']);
	$amount_payment = number_format($row['amount_payment']);
	$amount_misu	= number_format($row['amount_misu']);

	$tbl .= <<<EOD
		<tr align="center" nobr="true">
			<td class="td_01">$num</td>
			<td class="td_02">$work_section</td>
			<td class="td_03">$row[work_uid]</td>
			<td class="td_04">$row[work_start]</td>
			<td class="td_05">$row[work_out]</td>
			<td class="td_06">$customer</td>
			<td class="td_07">$row[mb_name]</td>
			<td class="td_08">$row[car_no]</td>
			<td class="td_09">$car_name</td>
			<td class="td_10">$car_km</td>
			<td class="td_11">$amount_all</td>
			<td class="td_12">$amount_payment</td>
			<td class="td_13">$amount_misu</td>
		</tr>
EOD;
}

$tbl .= <<<EOD
	</tbody>
</table>
EOD;

$pdf->writeHTML($tbl, true, false, false, false, '');
//$tbl = '메모리 사용량 : '.number_format(memory_get_usage()).'Byte ('.(memory_get_usage() / 1000).'KB)';
//$pdf->writeHTML($tbl, true, false, false, false, '');
$pdf->Output('bank_account_lsit3.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
