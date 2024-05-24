<?include_once("_common.php");?>
<?php
header("Content-type: application/json");
$verb = $_SERVER["REQUEST_METHOD"];
$table="free";
$table_connect="g5_write_free";
$bo_table="free";
if ($verb == "GET") {
	sql_query("set @rownum:=0");
	if($member['mb_level']>6){	}
	$rs = sql_query("SELECT @rownum:=@rownum+1 as No,
													wr_id,
													wr_1,
													wr_2,
													wr_3,
													wr_4,
													wr_5,
													wr_6,
													wr_7,
													wr_8,
													wr_9,
													wr_10,

													if(wr_10='등록',1,0) as test,
													wr_11,
													wr_12,
													wr_13,
													wr_14,
													wr_15,
													wr_16,
													wr_17,
													wr_18,
													wr_19,
													wr_20,
													wr_21,
													wr_22,
													wr_23,
													wr_24,
													wr_25,
													wr_26,
													wr_subject
													FROM {$table_connect}
													order by wr_14 asc
													");

	
	while($obj = mysqli_fetch_object($rs)) {
		$arr[] = $obj;
	}
	echo json_encode($arr);	
}
	if ($verb == "POST") {
	$wr_id= sql_real_escape_string($_POST["wr_id"]);
	$wr_1= sql_real_escape_string($_POST["wr_1"]);
	$wr_2= sql_real_escape_string($_POST["wr_2"]);
	$wr_3= sql_real_escape_string($_POST["wr_3"]);
	$wr_4= sql_real_escape_string($_POST["wr_4"]);
	$wr_5= sql_real_escape_string($_POST["wr_5"]);
	$wr_6= sql_real_escape_string($_POST["wr_6"]);
	$wr_7= sql_real_escape_string($_POST["wr_7"]);
	$wr_8= sql_real_escape_string($_POST["wr_8"]);
	$wr_9= sql_real_escape_string($_POST["wr_9"]);
	$wr_10= sql_real_escape_string($_POST["wr_10"]);
	$wr_11= sql_real_escape_string($_POST["wr_11"]);
	$wr_12= sql_real_escape_string($_POST["wr_12"]);
	$wr_13= sql_real_escape_string($_POST["wr_13"]);
	$wr_14= sql_real_escape_string($_POST["wr_14"]);
	$wr_15= sql_real_escape_string($_POST["wr_15"]);
	$wr_16= sql_real_escape_string($_POST["wr_16"]);
	$wr_17= sql_real_escape_string($_POST["wr_17"]);
	$wr_18= sql_real_escape_string($_POST["wr_18"]);
	$wr_19= sql_real_escape_string($_POST["wr_19"]);
	$wr_20= sql_real_escape_string($_POST["wr_20"]);
	$wr_21= sql_real_escape_string($_POST["wr_21"]);
	$wr_22= sql_real_escape_string($_POST["wr_22"]);
	$wr_23= sql_real_escape_string($_POST["wr_23"]);
	$wr_24= sql_real_escape_string($_POST["wr_24"]);
	$wr_25= sql_real_escape_string($_POST["wr_25"]);
	$wr_26= sql_real_escape_string($_POST["wr_26"]);
	$wr_subject= sql_real_escape_string($_POST["wr_subject"]);
		$rs = sql_query("UPDATE {$table_connect} 
		            SET 
                    mb_id = '$member[mb_id]',
					wr_1 = '{$wr_1}',
					wr_2 = '{$wr_2}',
					wr_3 = '{$wr_3}',
					wr_4 = '{$wr_4}',
					wr_5 = '{$wr_5}',
					wr_6 = '{$wr_6}',
					wr_7 = '{$wr_7}',
					wr_8 = '{$wr_8}',					
					wr_9 = '{$wr_9}',
					wr_10 = '{$wr_10}',
					wr_11 = '{$wr_11}',
					wr_12 = '{$wr_12}',
					wr_13 = '{$wr_13}',
					wr_14 = '{$wr_14}',
					wr_15 = '{$wr_15}',
					wr_16 = '{$wr_16}',
					wr_17 = '{$wr_17}',
					wr_18 = '{$wr_18}',
					wr_19 = '{$wr_19}',
					wr_20 = '{$wr_20}',
					wr_21 = '{$wr_21}',
					wr_22 = '{$wr_22}',
					wr_23 = '{$wr_23}',
					wr_24 = '{$wr_24}',
					wr_25 = '{$wr_25}',
					wr_26 = '{$wr_26}',
					wr_subject = '{$wr_subject}'
					WHERE wr_id = '{$wr_id}' ");
	if ($rs) {
		echo json_encode($rs);
	}
	else {
		header("HTTP/1.1 500 Internal Server Error");
		echo "Update failed for wr_id: " .$wr_id;
	}
}if ($verb == "PUT") {
	$request_vars = Array();
	parse_str(file_get_contents('php://input'), $request_vars);

	$ca_name= sql_real_escape_string($_GET["ca_name"]);
	$wr_subject = sql_real_escape_string($request_vars["wr_subject"]);
	$wr_id= sql_real_escape_string($request_vars["wr_id"]);
	$wr_1 = sql_real_escape_string($request_vars["wr_1"]);
	$wr_2 = sql_real_escape_string($request_vars["wr_2"]);
	$wr_3 = sql_real_escape_string($request_vars["wr_3"]);
	$wr_4 = sql_real_escape_string($request_vars["wr_4"]);
	$wr_5 = sql_real_escape_string($request_vars["wr_5"]);
	$wr_6 = sql_real_escape_string($request_vars["wr_6"]);
	$wr_7 = sql_real_escape_string($request_vars["wr_7"]);
	$wr_8 = sql_real_escape_string($request_vars["wr_8"]);
	$wr_9 = sql_real_escape_string($request_vars["wr_9"]);
	$wr_10 = sql_real_escape_string($request_vars["wr_10"]);
	$wr_11 = sql_real_escape_string($request_vars["wr_11"]);
	$wr_12 = sql_real_escape_string($request_vars["wr_12"]);
	$wr_13 = sql_real_escape_string($request_vars["wr_13"]);
	$wr_14 = sql_real_escape_string($request_vars["wr_14"]);
	$wr_15 = sql_real_escape_string($request_vars["wr_15"]);
	$wr_16 = sql_real_escape_string($request_vars["wr_16"]);
	$wr_17 = sql_real_escape_string($request_vars["wr_17"]);
	$wr_18 = sql_real_escape_string($request_vars["wr_18"]);
	$wr_19 = sql_real_escape_string($request_vars["wr_19"]);
	$wr_20 = sql_real_escape_string($request_vars["wr_20"]);
	$wr_21 = sql_real_escape_string($request_vars["wr_21"]);
	$wr_22 = sql_real_escape_string($request_vars["wr_22"]);
	$wr_23 = sql_real_escape_string($request_vars["wr_23"]);
	$wr_24 = sql_real_escape_string($request_vars["wr_24"]);
	$wr_25 = sql_real_escape_string($request_vars["wr_25"]);
	$wr_26 = sql_real_escape_string($request_vars["wr_26"]);

    $chkquery="select * from {$table_connect} where wr_1='{$wr_1}' and wr_2='{$wr_2}' and wr_3='{$wr_3}' and wr_4='{$wr_4}' and wr_5='{$wr_5}' and wr_6='{$wr_6}' and wr_7='{$wr_7}' and wr_8='{$wr_8}' and wr_9='{$wr_9}' and wr_10='{$wr_10}' ";
	$chk=sql_query($chkquery);
	while($row=sql_fetch_array($chk)){
	$chk_wrid=$row[wr_id];
	}
	if($chk_wrid==''){

	$wr_num = get_next_num($table_connect);
	$sql = " insert into $table_connect
                set wr_num = '$wr_num',
                     wr_reply = '$wr_reply',
                     wr_comment = 0,
                     ca_name = '$ca_name',
                     wr_option = '$html,$secret,$mail',
                     wr_subject = '$wr_subject',
                     wr_content = '$wr_subject',
                     wr_link1 = '$wr_link1',
                     wr_link2 = '$wr_link2',
                     wr_link1_hit = 0,
                     wr_link2_hit = 0,
                     wr_hit = 0,
                     wr_good = 0,
                     wr_nogood = 0,
                     mb_id = '$member[mb_id]',
                     wr_password = '$wr_password',
                     wr_name = '$wr_name',
                     wr_email = '$wr_email',
                     wr_homepage = '$wr_homepage',
                     wr_datetime = '".G5_TIME_YMDHIS."',
                     wr_last = '".G5_TIME_YMDHIS."',
                     wr_ip = '{$_SERVER['REMOTE_ADDR']}',
                     wr_1 = '$wr_1',
                     wr_2 = '$wr_2',
                     wr_3 = '$wr_3',
                     wr_4 = '$wr_4',
                     wr_5 = '$wr_5',
                     wr_6 = '$wr_6',
                     wr_7 = '$wr_7',
                     wr_8 = '$wr_8',
                     wr_9 = '$wr_9',
					 wr_10 = '등록',
					 wr_11 = '$wr_11',
					 wr_12 = '$wr_12',
					 wr_13 = '$wr_13',
					 wr_14 = '$wr_14',
					 wr_15 = '$wr_15',
					 wr_16 = '$wr_16',
					 wr_17 = '$wr_17',
					 wr_18 = '$wr_18',
					 wr_19 = '$wr_19',
					 wr_20 = '$wr_20',
					 wr_21 = '$wr_21',
					 wr_22 = '$wr_22',
					 wr_23 = '$wr_23',
					 wr_24 = '$wr_24',
					 wr_25 = '$wr_25',
					 wr_26 = '$wr_26'			 					 
					 ";

			$rs=sql_query($sql);
			
			$wr_id = sql_insert_id();

			
			// 부모 아이디에 UPDATE
			sql_query(" update $table_connect set wr_parent = '$wr_id' where wr_id = '$wr_id' ");

			// 새글 INSERT
			sql_query(" insert into {$g5['board_new_table']} ( bo_table, wr_id, wr_parent, bn_datetime, mb_id ) values ( '{$bo_table}', '{$wr_id}', '{$wr_id}', '".G5_TIME_YMDHIS."', '{$mb_id}' ) ");
			sql_query(" delete from test_write_member1 where wr_4 = '{$wr_4}' ");
			// 게시글 1 증가

	}
	if ($rs) {
		echo true;
	}
	else {
		header("HTTP/1.1 500 Internal Server Error".$rs);
		echo false;
	}
}
?>
