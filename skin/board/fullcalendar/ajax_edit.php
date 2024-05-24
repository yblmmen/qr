<?php
include_once('./_common.php');

$id = isset($_POST["id"]) ? intval($_POST["id"]) : 0;

$que1 = sql_fetch("select wr_id,wr_subject,wr_content,wr_name,wr_1,wr_2,wr_3,wr_4 from {$write_table} where wr_id = '{$id}'");

echo json_encode($que1);