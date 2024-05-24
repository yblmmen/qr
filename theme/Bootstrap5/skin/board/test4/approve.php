<?include_once("_common.php");?>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<?
$table="g6_write_".$bo_table;
if($wr_15=="검토대기"){
$rs=sql_query("UPDATE {$table} SET wr_12 = '".date("Y-m-d")."',wr_15='승인대기' WHERE wr_id = '{$wr_id}' ");
$rs1=sql_query("update {$g5['board_new_table']}
                set  wr_3 = '승인대기'
               where wr_id = '{$wr_id}' and bo_table='{$bo_table}'");
$query = sql_query("SELECT * FROM {$g5['apms_response']} where mb_id='{$wr_13}' and regdate='{$wr_datetime}' and subject='승인할 문서가 등록되었습니다'");
while($row=sql_fetch_array($query)){
	$chk_wrid=$row[wr_id];
}
if($chk_wrid==''){
sql_query("insert into {$g5['apms_response']} ( bo_table, wr_id, mb_id,my_id,comment_cnt,regdate,type,subject,my_name) values ( '{$bo_table}','{$wr_id}','{$wr_13}', '{$wr_11}', '1','{$wr_datetime}','2','승인할 문서가 등록되었습니다','{$wr_name}') ");
	sql_query(" update {$g5['member_table']} set as_response = as_response + 1 where mb_id = '{$wr_13}' ");
}
}
else if ($wr_15=="승인대기"){
$rs=sql_query("UPDATE {$table} SET wr_14 = '".date("Y-m-d")."',wr_15='승인완료' WHERE wr_id = '{$wr_id}' ");
$rs1=sql_query("update {$g5['board_new_table']}
                set  wr_3 = '승인완료'
               where wr_id = '{$wr_id}' and bo_table='{$bo_table}'");
$query = sql_query("SELECT * FROM {$g5['apms_response']} where mb_id='{$mb_id}' and regdate='{$wr_datetime}' and subject='문서가 승인 되었습니다'");
while($row=sql_fetch_array($query)){
	$chk_wrid=$row[wr_id];
}
if($chk_wrid==''){
sql_query("insert into {$g5['apms_response']} ( bo_table, wr_id, mb_id,my_id,comment_cnt,regdate,type,subject,my_name) values ( '{$bo_table}','{$wr_id}','{$mb_id}', '{$wr_13}', '1','{$wr_datetime}','2','문서가 승인 되었습니다','{$wr_name}') ");
	sql_query(" update {$g5['member_table']} set as_response = as_response + 1 where mb_id = '{$mb_id}' ");
}
}
?>
<meta http-equiv="refresh" content="0; url=<?=$g5[path]?>/bbs/board.php?bo_table=<?=$bo_table?>&wr_id=<?=$wr_id?>&wr_datetime=<?=$wr_datetime?>">