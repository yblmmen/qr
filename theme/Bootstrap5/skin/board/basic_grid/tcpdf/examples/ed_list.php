<?php
include_once('./_common.php');
include_once(G5_PATH.'/lib/common.lib.php');
set_time_limit(0);
ini_set('memory_limit', '640M');

require_once('./tcpdf_include.php');

//$pdf = new TCPDF('P-가로인쇄, L-세로인쇄', PDF_UNIT, 'A4', true, 'UTF-8', false);
$pdf = new TCPDF('P', PDF_UNIT, 'A4', true, 'UTF-8', false);

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


// 교육방법 풀기
function another_list($arr) {
	global $ANOTHER;
	$another = explode("|",$arr);
	foreach($another as $val) {
		$another_out .= ", ".$ANOTHER[$val];
	}
	$another_out = substr($another_out,2);
	return $another_out;
}

// 은행 리스트 배열 만들기 및 팀명불러오기
$sql = "select
			*
		from
			cm_education
		where
			id_no = '".$id_no."' ";
$rs = sql_fetch($sql);

$ed_subject = $SUBJECT[$rs['ed_subject']];
$ed_member = $rs['ed_member']; // 대상인원
$ed_people = $rs['ed_people']; // 참석인원

$ed_datetime = $rs['ed_date']."    ( ".$rs['ed_time1']." ~ ".$rs['ed_time2'].",  교육시간 ".$rs['ed_minutes']."분 )";

$ed_space = $rs['ed_space']; // 교육장소
$ed_teacher = $rs['ed_teacher']; // 강사
$ed_absent = $rs['ed_absent']; // 불참 사유
$ed_another = another_list($rs['ed_another']); // 교육방법
$ed_content = $rs['ed_content'];

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

$pdf->SetFont('nanumbarungothicyethangul', '', 20); // 글꼴 설정.
$document = "안     전     교     육     일     지";
$pdf->MultiCell(118, 20, $document, 'LTB', 'C', 0, 0, '', '', true, 0, false, true, 20, 'M');

$pdf->SetFont('nanumbarungothicyethangul', '', 11); // 글꼴 설정.
$pdf->MultiCell(8, 20, '결재', 'LTB', 'C', 1, 0, '', '', true, 0, false, true, 20, 'M');
$pdf->MultiCell(15, 6, '담당', 'LTB', 'C', 1, 0, '', '', true, 0, false, true, 6, 'M');
$pdf->MultiCell(15, 6, '', 'LTB', 'C', 1, 0, '', '', true, 0, false, true, 6, 'M');
$pdf->MultiCell(15, 6, '', 'LTB', 'C', 1, 0, '', '', true, 0, false, true, 6, 'M');
$pdf->MultiCell(15, 6, '', 'LTBR', 'C', 1, 1, '', '', true, 0, false, true, 6, 'M');

$pdf->MultiCell(15, 14, '', 'LB', 'C', 0, 0, 138, '', true, 0, false, true, 6, 'M');
$pdf->MultiCell(15, 14, '', 'LB', 'C', 0, 0, '', '', true, 0, false, true, 6, 'M');
$pdf->MultiCell(15, 14, '', 'LB', 'C', 0, 0, '', '', true, 0, false, true, 6, 'M');
$pdf->MultiCell(15, 14, '', 'LBR', 'C', 0, 1, '', '', true, 0, false, true, 6, 'M');

$pdf->SetFont('nanumbarungothicyethangul', '', 9); // 글꼴 설정.
$pdf->setCellPaddings(3,0,0,0);

$pdf->MultiCell(28, 11, '교육명', 'LTB', 'C', 1, 0, '', '', true, 0, false, true, 11, 'M');
$pdf->MultiCell(158, 11, $ed_subject, 'LBR', 'L', 0, 1, '', '', true, 0, false, true, 11, 'M');

$pdf->MultiCell(28, 11, '사업자영', 'LTB', 'C', 1, 0, '', '', true, 0, false, true, 11, 'M');
$pdf->MultiCell(96, 11, $off[office_name], 'LBR', 'L', 0, 0, '', '', true, 0, false, true, 11, 'M');
$pdf->MultiCell(28, 11, '대상인원', 'LTB', 'C', 1, 0, '', '', true, 0, false, true, 11, 'M');
$pdf->MultiCell(34, 11, $ed_member." 명", 'LBR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');

$pdf->MultiCell(28, 11, '교육일시', 'LTB', 'C', 1, 0, '', '', true, 0, false, true, 11, 'M');
$pdf->MultiCell(96, 11, $ed_datetime, 'LBR', 'C', 0, 0, '', '', true, 0, false, true, 11, 'M');
$pdf->MultiCell(28, 11, '참석인원', 'LTB', 'C', 1, 0, '', '', true, 0, false, true, 11, 'M');
$pdf->MultiCell(34, 11, $ed_people." 명", 'LBR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');

$pdf->MultiCell(28, 11, '장소', 'LTB', 'C', 1, 0, '', '', true, 0, false, true, 11, 'M');
$pdf->MultiCell(96, 11, $ed_space, 'LBR', 'L', 0, 0, '', '', true, 0, false, true, 11, 'M');
$pdf->MultiCell(28, 11, '강사', 'LTB', 'C', 1, 0, '', '', true, 0, false, true, 11, 'M');
$pdf->MultiCell(34, 11, $ed_teacher, 'LBR', 'C', 0, 1, '', '', true, 0, false, true, 11, 'M');

$pdf->MultiCell(28, 11, '불참사유', 'LTB', 'C', 1, 0, '', '', true, 0, false, true, 11, 'M');
$pdf->MultiCell(158, 11, $ed_absent, 'LBR', 'L', 0, 1, '', '', true, 0, false, true, 11, 'M');

$pdf->MultiCell(28, 11, '교육방법', 'LTB', 'C', 1, 0, '', '', true, 0, false, true, 11, 'M');
$pdf->MultiCell(158, 11, $ed_another, 'LBR', 'L', 0, 1, '', '', true, 0, false, true, 11, 'M');


$pdf->MultiCell(28, 65, '교육내용', 'LTB', 'C', 1, 0, '', '', true, 0, false, true, 65, 'M');
$pdf->setCellPaddings(3,4,0,0);
$pdf->MultiCell(158, 65, $ed_content, 'LBR', 'L', 0, 1, '', '', true, 0, false, true, 65, 'T');



$pdf->Ln(3);

$pdf->setCellPaddings(0,0,0,0);
$pdf->MultiCell(8, 8, 'NO', 'LTB', 'C', 1, 0, '', '', true, 0, false, true, 8, 'M');
$pdf->MultiCell(24, 8, '소속', 'LTB', 'C', 1, 0, '', '', true, 0, false, true, 8, 'M');
$pdf->MultiCell(30, 8, '성명', 'LTB', 'C', 1, 0, '', '', true, 0, false, true, 8, 'M');
$pdf->MultiCell(30, 8, '서명', 'LTBR', 'C', 1, 0, '', '', true, 0, false, true, 8, 'M');

$pdf->MultiCell(2, 8, '', '', 'C', 0, 0, '', '', true, 0, false, true, 8, 'M');

$pdf->MultiCell(8, 8, 'NO', 'LTB', 'C', 1, 0, '', '', true, 0, false, true, 8, 'M');
$pdf->MultiCell(24, 8, '소속', 'LTB', 'C', 1, 0, '', '', true, 0, false, true, 8, 'M');
$pdf->MultiCell(30, 8, '성명', 'LTB', 'C', 1, 0, '', '', true, 0, false, true, 8, 'M');
$pdf->MultiCell(30, 8, '서명', 'LTBR', 'C', 1, 1, '', '', true, 0, false, true, 8, 'M');


$s=11;
for($i=0;$i<10;$i++) {
	$pdf->MultiCell(8, 8, $i+1, 'LB', 'C', 0, 0, '', '', true, 0, false, true, 8, 'M');
	$pdf->MultiCell(24, 8, '', 'LB', 'C', 0, 0, '', '', true, 0, false, true, 8, 'M');
	$pdf->MultiCell(30, 8, '', 'LB', 'C', 0, 0, '', '', true, 0, false, true, 8, 'M');
	$pdf->MultiCell(30, 8, '', 'LBR', 'C', 0, 0, '', '', true, 0, false, true, 8, 'M');

	$pdf->MultiCell(2, 8, '', '', 'C', 0, 0, '', '', true, 0, false, true, 8, 'M');

	$pdf->MultiCell(8, 8, $s, 'LB', 'C', 0, 0, '', '', true, 0, false, true, 8, 'M');
	$pdf->MultiCell(24, 8, '', 'LB', 'C', 0, 0, '', '', true, 0, false, true, 8, 'M');
	$pdf->MultiCell(30, 8, '', 'LB', 'C', 0, 0, '', '', true, 0, false, true, 8, 'M');
	$pdf->MultiCell(30, 8, '', 'LBR', 'C', 0, 1, '', '', true, 0, false, true, 8, 'M');
	$s++;
}

$pdf->writeHTML($tbl, true, false, false, false, '');
//$tbl = '메모리 사용량 : '.number_format(memory_get_usage()).'Byte ('.(memory_get_usage() / 1000).'KB)';
//$pdf->writeHTML($tbl, true, false, false, false, '');
$pdf->Output('carway_education_report.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
