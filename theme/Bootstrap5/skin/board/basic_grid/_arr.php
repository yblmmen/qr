<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// SELECT 박스의 옵션을 만들어주는 함수.
function option_str($data1,$data2,$option_name=''){
	$data1=explode("|",$data1);
	$data2=explode("|",$data2);
	for($i=0; $i < count($data1); $i++){$dataA[$i]=trim($data1[$i]);}
	for($i=0; $i < count($data2); $i++){$dataB[$i]=trim($data2[$i]);}
	for($i=0; $i < count($data2); $i++){
		$selected=($option_name==$dataB[$i])? "selected":"";
		$result .="<option value='$dataB[$i]' $selected>$dataA[$i]</option>";
	}
	return($result);
}

// 체크박스 만들어주는 함수
function checkbox($data1,$data2,$db_data,$name,$rows=3){
	$data1=explode("|",$data1);
	$data2=explode("|",$data2);
	for($i=0; $i < count($data1); $i++){$dataA[$i]=$data1[$i];}
	for($i=0; $i < count($data2); $i++){$dataB[$i]=$data2[$i];}
	$check=explode("|",$db_data);
	$result .="<ul id='chb'>";
	$j=0;

	for($i=0; $i < count($data2); $i++)	{
		if($dataB[$i]==$check[$j]) {
			$checked="checked";
			$j++;
		} else {
			$checked="";
		}
		$result .="<li><input type=checkbox value='$dataB[$i]' id='$name_$i' name='${name}[]' $checked /><label for='$name_$i'>{$dataA[$i]}</label></li>";
	}
	$result .="</ul>";
	return($result);
}

// 아래의 배열은 사용자가 원하는형태로 변경해서 사용이 가능합니다.

// 여분필드1 - 창고
$WR1 = array(
			"A" => "창고 A동",
			"B" => "창고 B동",
			"C" => "창고 C동",
			"D" => "창고 D동",
			"E" => "창고 E동",
			"F" => "창고 F동"
		);

// 여분필드2 - 부피
$WR2 = array(
			"1" => "극소",
			"2" => "소",
			"3" => "중",
			"4" => "대"
			);

// 여분필드3 - 관리팀
$WR3 = array(
			"1" => "관리 1팀",
			"2" => "관리 2팀",
			"3" => "관리 3팀",
			"4" => "관리 4팀",
			"5" => "관리 5팀"
			);

// 여분필드4 - 색상
$WR4 = array(
			"1" => "붉은색",
			"2" => "파란색",
			"3" => "오렌지색",
			"4" => "검정색",
			"5" => "녹색",
			"6" => "흰색",
			"7" => "주황색",
			"8" => "회색",
			"9" => "아이보리",
			"10" => "카키",
			"11" => "보라색"
			);
// 여분필드5 - 재질
$WR5 = array(
			"1" => "종이",
			"2" => "스테인레스",
			"3" => "천",
			"4" => "플라스틱",
			"5" => "비닐",
			"6" => "유리",
			"7" => "스틸(철)",
			"8" => "알루미늄",
			"9" => "MDF",
			"10" => "합판",
			"11" => "아크릴",
			"100" => "기타"
			);

// 여분필드6 - A/S가능여부
$WR6 = array(
			"1" => "A/S 가능",
			"2" => "A/S 불가",
			);

// 여분필드7 - 배송가능여부
$WR7 = array(
			"1" => "배송가능",
			"2" => "배송불가",
			);

// 여분필드8 - 용량
$WR8 = array(
			"1" => "100ml",
			"2" => "300ml",
			"3" => "500ml",
			"4" => "700ml",
			"5" => "1000ml",
			"6" => "1500ml",
			"7" => "2000ml",
			);


// 아래는 셀렉트박스로 만들기 위해서 각 배열을 구분자로 분리함.
foreach($WR1 as $k=>$v) {
	$wr1_t .= "|".$v;
	$wr1_k .= "|".$k;
}

foreach($WR2 as $k=>$v) {
	$wr2_t .= "|".$v;
	$wr2_k .= "|".$k;
}

foreach($WR3 as $k=>$v) {
	$wr3_t .= "|".$v;
	$wr3_k .= "|".$k;
}

foreach($WR4 as $k=>$v) {
	$wr4_t .= "|".$v;
	$wr4_k .= "|".$k;
}

foreach($WR5 as $k=>$v) {
	$wr5_t .= "|".$v;
	$wr5_k .= "|".$k;
}

foreach($WR6 as $k=>$v) {
	$wr6_t .= "|".$v;
	$wr6_k .= "|".$k;
}

foreach($WR7 as $k=>$v) {
	$wr7_t .= "|".$v;
	$wr7_k .= "|".$k;
}

foreach($WR8 as $k=>$v) {
	$wr8_t .= "|".$v;
	$wr8_k .= "|".$k;
}
?>