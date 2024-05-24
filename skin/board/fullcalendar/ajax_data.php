<?php
include_once('./_common.php');

$accessCode = !empty($board['bo_1']) ? intval($board['bo_1']) : 0;

$tdate = isset($_POST['tdate']) ? html_purifier($_POST['tdate']) : date("Y-m-d");
$c_user = isset($_POST['c_user']) ? html_purifier($_POST['c_user']) : 'all';
$tdate = substr($tdate,0,10);
$tdate = date("Y-m-d", strtotime("{$tdate} +10 day"));
$yymm = substr($tdate,0,7);

$datas = [];

if($accessCode == 1) {
	if($c_user == "all") {
	$result = sql_query("select wr_id,wr_subject,wr_content,wr_name,wr_1,wr_2,wr_3,wr_4 from {$write_table} where left(wr_1,7) = '{$yymm}' order by wr_subject");
	} else {
	$result = sql_query("select wr_id,wr_subject,wr_content,wr_name,wr_1,wr_2,wr_3,wr_4 from {$write_table} where left(wr_1,7) = '{$yymm}' and wr_name = '{$c_user}' order by wr_subject");
	}
} else {
	$result = sql_query("select wr_id,wr_subject,wr_content,wr_name,wr_1,wr_2,wr_3,wr_4 from {$write_table} where left(wr_1,7) = '{$yymm}' order by wr_subject");
}

$x=0;

foreach($result as $field) {
	$x++;
	if($accessCode == 1) {
		$datas[] = array("id"=>$field['wr_id'],"title"=>"(".mb_substr($field['wr_name'],0,1).") ".$field['wr_subject'], "start"=>$field['wr_1'], "end"=>$field['wr_2'], "description"=>nl2br($field['wr_content']), "color"=>$field['wr_3'], "textColor"=>$field['wr_4']);
	} else {
		$datas[] = array("id"=>$field['wr_id'],"title"=>$field['wr_subject'], "start"=>$field['wr_1'], "end"=>$field['wr_2'], "description"=>nl2br($field['wr_content']), "color"=>$field['wr_3'], "textColor"=>$field['wr_4'],"order"=>1);
	}

}

echo json_encode($datas);