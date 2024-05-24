<?include_once("_common.php");?>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<?
$table="g6_write_".$bo_table;
$rs=sql_query("UPDATE {$table} SET wr_12 = '',wr_14 = '',wr_15='결재반려' WHERE wr_id = '{$wr_id}' ");
$rs1=sql_query("update {$g5['board_new_table']}
                set  wr_3 = '결재반려'
               where wr_id = '{$wr_id}' and bo_table='{$bo_table}'");
sql_query("insert into {$g5['apms_response']} ( bo_table, wr_id, mb_id,my_id,comment_cnt,regdate,type,subject,my_name) values ( '{$bo_table}','{$wr_id}','{$mb_id}', '{$member['mb_id']}', '1','{$wr_datetime}','2','문서가 반려 되었습니다','{$wr_name}') ");
	sql_query(" update {$g5['member_table']} set as_response = as_response + 1 where mb_id = '{$mb_id}' ");
?>
<meta http-equiv="refresh" content="0; url=<?=$g5[path]?>/bbs/board.php?bo_table=<?=$bo_table?>&wr_id=<?=$wr_id?>&wr_datetime=<?=$wr_datetime?>">