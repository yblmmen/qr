<?php
include_once('./_common.php');

$cdate = isset($_POST['id']) ? html_purifier($_POST["id"]) : 0;

$que1 = sql_fetch("select wr_1,wr_2,wr_3,wr_4,wr_5,wr_6,wr_7 from {$write_table} where wr_1 = '{$cdate2}'");

return json_encode($que1);