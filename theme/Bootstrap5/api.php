<?
	include '_common.php';
	include_once(G5_THEME_PATH.'/functions.php');

	$data = json_decode(file_get_contents('php://input'), true);

	switch($data['order'])
	{
		case 'report':
			$sql = " select * from {$g5['board_table']}_report where bo_table='{$data['bo_table']}' and wr_id='{$data['wr_id']}' and mb_id='{$member['mb_id']}' limit 1; ";
			$row = sql_fetch($sql);
			if(!empty($row) && $row['si_id'])
			{
				$msg = '신고한 게시물입니다.';
			}else{
				$sql = " insert into {$g5['board_table']}_report (bo_table, wr_id, mb_id, si_datetime) values ('{$data['bo_table']}', '{$data['wr_id']}', '{$member['mb_id']}', now()); ";
				sql_query($sql);

				include_once(G5_LIB_PATH.'/mailer.lib.php');
				$view = get_write($g5['write_prefix'].$data['bo_table'], $data['wr_id']);
				$view['wr_href'] = get_pretty_url($data['bo_table'], $data['wr_id']);
				mailer($config['cf_admin_email_name'], $config['cf_admin_email'], $config['cf_admin_email'], '게시글 신고 접수', "{$member['mb_name']}({$_SERVER['REMOTE_ADDR']})님이 ".G5_TIME_YMDHIS." 에 게시물을 신고하였습니다.\n제목: {$view['wr_subject']}\n링크:{$view['wr_href']}", 2);

				$msg = '정상적으로 신고 하셨습니다.'."\n\n".'확인 후 조치하겠습니다.';
			}

			break;

		case 'block':
			if($config['cf_admin'] == $data['mb_id'])
			{
				$msg = '관리자를 차단할 수 없습니다.';
				break;
			}

			$sql = " select * from {$g5['member_table']}_block where bl_recv_mb_id = '{$member['mb_id']}' and bl_send_mb_id = '{$data['mb_id']}' limit 1; ";
			$row = sql_fetch($sql);
			if(!empty($row) && $row['bl_id'])
			{
				$msg = '차단된 회원입니다.';
			}else{
				$sql = " insert into {$g5['member_table']}_block (bl_recv_mb_id, bl_send_mb_id, bl_datetime) values ('{$member['mb_id']}', '{$data['mb_id']}', now()); ";
				sql_query($sql);

				$msg = '해당 회원이 차단되었습니다.';
			}
			
			break;
	}

	if(isset($msg)) echo json_encode(['msg'=>$msg]);
