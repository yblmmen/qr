<?php
include_once('./_common.php');
if($_GET[del] == "days"){
	
	$days = $_GET['days'];
	$deldate = date("Y-m-d 00:00:00", strtotime("-{$days} days"));
	sql_query(" delete from g5_write_{$bo_table} where wr_datetime < '{$deldate}' ");
	alert($days."일 지난 자료가 삭제되었습니다");
	
}else if($_GET[del] == "all"){
	
	sql_query(" TRUNCATE TABLE g5_write_{$bo_table} ");
	alert("자료를 초기화 했습니다");
	
}else{
	
	$todaydel = G5_TIME_YMD;
	sql_query(" delete from g5_write_{$bo_table} where wr_datetime LIKE '{$todaydel}%' ");
	alert("금일자료를 삭제 했습니다");
	
}


?>

