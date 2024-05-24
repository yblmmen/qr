<?php
include_once('./_common.php');

$sql = " update g5_write_{$_POST[bo_table]} 
		set wr_subject = '{$_POST[wr_subject]}',
			wr_1 = '{$_POST[wr_1]}', 
			wr_2 = '{$_POST[wr_2]}', 
			wr_3 = '{$_POST[wr_3]}', 
			wr_4 = '{$_POST[wr_4]}', 
			wr_5 = '{$_POST[wr_5]}'
		where wr_id = '{$_POST[wr_id]}' ";
    sql_query($sql);

?>
