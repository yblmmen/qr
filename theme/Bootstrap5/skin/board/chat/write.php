<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가
include_once(G5_LIB_PATH.'/thumbnail.lib.php');

//header("Content-type:text/html;charset=utf-8");


		$wr_num = get_next_num($write_table);

		$member = get_member($mb_id);

		$wr_password = $member['mb_password'];

		$wr_email = '';
		$wr_homepage = '';

		$wr_subject = $wr_name;

		$sql = " insert into $write_table
					set wr_num = '$wr_num',
						 wr_reply = '$wr_reply',
						 wr_comment = 0,
						 wr_option = 'html1',
						 wr_subject = '$wr_subject',
						 wr_content = '$message',
						 wr_link1_hit = 0,
						 wr_link2_hit = 0,
						 wr_hit = 0,
						 wr_good = 0,
						 wr_nogood = 0,
						 mb_id = '{$member['mb_id']}',
						 wr_password = '$wr_password',
						 wr_name = '$wr_name',
						 wr_datetime = '".G5_TIME_YMDHIS."',
						 wr_last = '".G5_TIME_YMDHIS."',
						 wr_ip = '{$_SERVER['REMOTE_ADDR']}'
						 ";
		sql_query($sql);

		$wr_id = sql_insert_id();

		// 부모 아이디에 UPDATE
		sql_query(" update $write_table set wr_parent = '$wr_id' where wr_id = '$wr_id' ");

		 // 게시글 1 증가
		 sql_query("update {$g5['board_table']} set bo_count_write = bo_count_write + 1 where bo_table = '{$bo_table}'");

		$data = array(
			"bo_table" => $bo_table,
			"ok" => 100
		);
		echo json_encode($data);
?>