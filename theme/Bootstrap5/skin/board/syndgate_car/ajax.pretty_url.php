<?php
include_once("./_common.php");
$url = get_pretty_url($bo_table, $wr_id, $qstr);
echo json_encode($url);
?>