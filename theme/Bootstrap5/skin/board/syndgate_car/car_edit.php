<?php
include_once('_common.php');
include_once(G5_PATH."/head.sub.php");
include_once($board_skin_path."/skin.function.php");
$Search_box = new Frm_search(); // 검색폼 관련 Class

// 회원의 인증을 확인하는 함수
function checkMemberAuthentication($member_id) {
    // 여기에 회원 인증을 확인하는 코드를 작성합니다. 
    // 예를 들어, 회원의 로그인 상태, 회원 등급, 특정 조건 등을 확인할 수 있습니다.
    // 이 예시에서는 단순히 mb_id가 있는지만을 확인하는 것으로 가정합니다.
    if(isset($member_id)) {
        // 회원 인증이 완료된 상태
        return true;
    } else {
        // 회원 인증이 되지 않은 상태
        return false;
    }
}

if (!$board['bo_table']) {
   alert('존재하지 않는 게시판입니다.', G5_URL);
}

check_device($board['bo_device']);

if (isset($write['wr_is_comment']) && $write['wr_is_comment']) {
    goto_url(get_pretty_url($bo_table, $write['wr_parent'], '#c_'.$wr_id));
}

if (!$bo_table) {
    $msg = "bo_table 값이 넘어오지 않았습니다.\\n\\nboard.php?bo_table=code 와 같은 방식으로 넘겨 주세요.";
    alert($msg);
}

$g5['board_title'] = ((G5_IS_MOBILE && $board['bo_mobile_subject']) ? $board['bo_mobile_subject'] : $board['bo_subject']);

// wr_id 값이 있으면 글읽기
if ((isset($wr_id) && $wr_id) || (isset($wr_seo_title) && $wr_seo_title)) {
    // 글이 없을 경우 해당 게시판 목록으로 이동
    if (!isset($write['wr_id'])) {
        $msg = '글이 존재하지 않습니다.\\n\\n글이 삭제되었거나 이동된 경우입니다.';
        alert($msg, get_pretty_url($bo_table));
    }

    // 그룹접근 사용
    if (isset($group['gr_use_access']) && $group['gr_use_access']) {
        if ($is_guest) {
            $msg = "비회원은 이 게시판에 접근할 권한이 없습니다.\\n\\n회원이시라면 로그인 후 이용해 보십시오.";
            alert($msg, G5_BBS_URL.'/login.php?wr_id='.$wr_id.$qstr.'&amp;url='.urlencode(get_pretty_url($bo_table, $wr_id, $qstr)));
        }

        // 그룹관리자 이상이 아니라 게시판 관리자 이상이라면 통과
        if ($is_admin != "super" && $is_admin != "group") {
        // 게시판 관리자 또는 그룹에 속한 회원인지 확인
        $sql = "SELECT COUNT(*) AS cnt FROM {$g5['group_member_table']} WHERE gr_id = '{$board['gr_id']}' AND mb_id = '{$member['mb_id']}'";
        $row = sql_fetch($sql);
        if (!$row['cnt'] && $member['mb_id'] != BOARD_ADMIN_ID) {
        alert("접근 권한이 없으므로 글읽기가 불가합니다.\\n\\n궁금하신 사항은 관리자에게 문의 바랍니다.", G5_URL);
    }
}

    }

    // 로그인된 회원의 권한이 설정된 읽기 권한보다 작다면
    if ($member['mb_level'] < $board['bo_read_level']) {
        if ($is_member)
            alert('글을 읽을 권한이 없습니다.', G5_URL);
        else
            alert('글을 읽을 권한이 없습니다.\\n\\n회원이시라면 로그인 후 이용해 보십시오.', G5_BBS_URL.'/login.php?wr_id='.$wr_id.$qstr.'&amp;url='.urlencode(get_pretty_url($bo_table, $wr_id, $qstr)));
    }

    // 본인확인을 사용한다면
    if ($board['bo_use_cert'] != '' && $config['cf_cert_use'] && !$is_admin) {
        // 인증된 회원만 가능
        if ($is_guest) {
            alert('이 게시판은 본인확인 하신 회원님만 글읽기가 가능합니다.\\n\\n회원이시라면 로그인 후 이용해 보십시오.', G5_BBS_URL.'/login.php?wr_id='.$wr_id.$qstr.'&amp;url='.urlencode(get_pretty_url($bo_table, $wr_id, $qstr)));
        }

        if (strlen($member['mb_dupinfo']) == 64 && $member['mb_certify']) { // 본인 인증 된 계정 중에서 di로 저장 되었을 경우에만
            goto_url(G5_BBS_URL."/member_cert_refresh.php?url=".urlencode(get_pretty_url($bo_table, $wr_id, $qstr)));
        }

        if ($board['bo_use_cert'] == 'cert' && !$member['mb_certify']) {            
            alert('이 게시판은 본인확인 하신 회원님만 글읽기가 가능합니다.\\n\\n회원정보 수정에서 본인확인을 해주시기 바랍니다.', G5_URL);
        }

        if ($board['bo_use_cert'] == 'adult' && !$member['mb_adult']) {
            alert('이 게시판은 본인확인으로 성인인증 된 회원님만 글읽기가 가능합니다.\\n\\n현재 성인인데 글읽기가 안된다면 회원정보 수정에서 본인확인을 다시 해주시기 바랍니다.', G5_URL);
        }
    }

    // 자신의 글이거나 관리자라면 통과
    if (($write['mb_id'] && $write['mb_id'] === $member['mb_id']) || $is_admin) {
        ;
    } else {
        // 비밀글이라면
        if (strstr($write['wr_option'], "secret"))
        {
            // 회원이 비밀글을 올리고 관리자가 답변글을 올렸을 경우
            // 회원이 관리자가 올린 답변글을 바로 볼 수 없던 오류를 수정
            $is_owner = false;
            if ($write['wr_reply'] && $member['mb_id'])
            {
                $sql = " select mb_id from {$write_table}
                            where wr_num = '{$write['wr_num']}'
                            and wr_reply = ''
                            and wr_is_comment = 0 ";
                $row = sql_fetch($sql);
                if ($row['mb_id'] === $member['mb_id'])
                    $is_owner = true;
            }

            $ss_name = 'ss_secret_'.$bo_table.'_'.$write['wr_num'];

            if (!$is_owner)
            {
                //$ss_name = "ss_secret_{$bo_table}_{$wr_id}";
                // 한번 읽은 게시물의 번호는 세션에 저장되어 있고 같은 게시물을 읽을 경우는 다시 비밀번호를 묻지 않습니다.
                // 이 게시물이 저장된 게시물이 아니면서 관리자가 아니라면
                //if ("$bo_table|$write['wr_num']" != get_session("ss_secret"))
                if (!get_session($ss_name))
                    goto_url(G5_BBS_URL.'/password.php?w=s&amp;bo_table='.$bo_table.'&amp;wr_id='.$wr_id.$qstr);
            }

            set_session($ss_name, TRUE);
        }
    }

    // 한번 읽은글은 브라우저를 닫기전까지는 카운트를 증가시키지 않음
    $ss_name = 'ss_view_'.$bo_table.'_'.$wr_id;
    if (!get_session($ss_name))
    {
        sql_query(" update {$write_table} set wr_hit = wr_hit + 1 where wr_id = '{$wr_id}' ");

        // 자신의 글이면 통과
        if ($write['mb_id'] && $write['mb_id'] === $member['mb_id']) {
            ;
        } else if ($is_guest && $board['bo_read_level'] == 1 && $write['wr_ip'] == $_SERVER['REMOTE_ADDR']) {
            // 비회원이면서 읽기레벨이 1이고 등록된 아이피가 같다면 자신의 글이므로 통과
            ;
        } else {
            // 글읽기 포인트가 설정되어 있다면
            if ($config['cf_use_point'] && $board['bo_read_point'] && $member['mb_point'] + $board['bo_read_point'] < 0)
                alert('보유하신 포인트('.number_format($member['mb_point']).')가 없거나 모자라서 글읽기('.number_format($board['bo_read_point']).')가 불가합니다.\\n\\n포인트를 모으신 후 다시 글읽기 해 주십시오.');

            insert_point($member['mb_id'], $board['bo_read_point'], ((G5_IS_MOBILE && $board['bo_mobile_subject']) ? $board['bo_mobile_subject'] : $board['bo_subject']).' '.$wr_id.' 글읽기', $bo_table, $wr_id, '읽기');
        }

        set_session($ss_name, TRUE);
    }

    $g5['title'] = strip_tags(conv_subject($write['wr_subject'], 255))." > ".$g5['board_title'];
} else {
    if ($member['mb_level'] < $board['bo_list_level']) {
        if ($member['mb_id'])
            alert('목록을 볼 권한이 없습니다.', G5_URL);
        else
            alert('목록을 볼 권한이 없습니다.\\n\\n회원이시라면 로그인 후 이용해 보십시오.', G5_BBS_URL.'/login.php?'.$qstr.'&url='.urlencode(G5_BBS_URL.'/board.php?bo_table='.$bo_table.($qstr?'&amp;':'')));
    }

    // 본인확인을 사용한다면
    if ($board['bo_use_cert'] != '' && $config['cf_cert_use'] && !$is_admin) {
        // 인증된 회원만 가능
        if ($is_guest) {
            alert('이 게시판은 본인확인 하신 회원님만 글읽기가 가능합니다.\\n\\n회원이시라면 로그인 후 이용해 보십시오.', G5_BBS_URL.'/login.php?wr_id='.$wr_id.$qstr.'&amp;url='.urlencode(get_pretty_url($bo_table, $wr_id, $qstr)));
        }

        if (strlen($member['mb_dupinfo']) == 64 && $member['mb_certify']) { // 본인 인증 된 계정 중에서 di로 저장 되었을 경우에만
            goto_url(G5_BBS_URL."/member_cert_refresh.php?url=".urlencode(get_pretty_url($bo_table, $wr_id, $qstr)));
        }

        if ($board['bo_use_cert'] == 'cert' && !$member['mb_certify']) {            
            alert('이 게시판은 본인확인 하신 회원님만 글읽기가 가능합니다.\\n\\n회원정보 수정에서 본인확인을 해주시기 바랍니다.', G5_URL);
        }

        if ($board['bo_use_cert'] == 'adult' && !$member['mb_adult']) {
            alert('이 게시판은 본인확인으로 성인인증 된 회원님만 글읽기가 가능합니다.\\n\\n현재 성인인데 글읽기가 안된다면 회원정보 수정에서 본인확인을 다시 해주시기 바랍니다.', G5_URL);
        }
    }

    $g5['title'] = $g5['board_title'];
}

if($mode) {
	switch($mode){

		case "save":
    $chd = get_memInfos($wr_id,$member['mb_id']);
    if($chd || $is_admin) {
        // URL 생성
        $board_url = 'http://' . $_SERVER['HTTP_HOST'] . '/theme/Bootstrap5/skin/board/syndgate_car/car_edit.php?bo_table=' . $bo_table . '&pg=1&wr_id=' . $wr_id;
        ## 정보 수정
        $sql = "update
                    {$write_table}
                set
                        wr_subject  = '".$_POST['wr_subject']."',
                        wr_content      = '".$_POST['wr_content']."',
                        wr_1        = '".$_POST['wr_1']."',
                        wr_2        = '".$_POST['wr_2']."',
                        wr_3        = '".$board_url."',  
                        wr_4        = '".$_POST['wr_4']."',
                        wr_5        = '".$_POST['wr_5']."',
                        wr_6        = '".$_POST['wr_6']."',
                        wr_7        = '".$_POST['wr_7']."',
                        wr_8        = '".$_POST['wr_8']."',
                        wr_9        = '".$_POST['wr_9']."',
                        wr_10   = '".$_POST['wr_10']."'
                where
                        wr_id = '".$_POST['wr_id']."' ";
        sql_query($sql, true);
    }
    goto_url("?bo_table={$bo_table}&wr_id={$wr_id}");
    break;


		case "new":
			// 사원 등록.
			$wr_num = get_next_num($write_table);
			$sql = "insert into
						{$write_table}
					set
						 wr_num = '".$wr_num."',
                     	 wr_comment = 0,
    	                 ca_name = '".$_POST['ca_name']."',
	                     wr_option  = '".$_POST['wr_option']."',
        	             wr_subject  = '".$_POST['wr_subject']."',
            	         wr_content  = '".$_POST['wr_content']."',
	                     wr_link1  = '".$_POST['wr_link1']."',
	                     wr_link2  = '".$_POST['wr_link2']."',
	                     wr_link1_hit = 0,
	                     wr_link2_hit = 0,
	                     wr_hit = 0,
	                     wr_good = 0,
	                     wr_nogood = 0,
	                     mb_id = '{$member['mb_id']}',
	                     wr_password = '{$member['mb_password2']}',
	                     wr_name = '{$member['mb_name']}',
	                     wr_datetime = '".G5_TIME_YMDHIS."',
	                     wr_last = '".G5_TIME_YMDHIS."',
	                     wr_ip = '{$_SERVER['REMOTE_ADDR']}',
	                     wr_1 = '".$_POST['wr_1']."',
	                     wr_2 = '".$_POST['wr_2']."',
	                     wr_3 = '".$_POST['wr_3']."',
	                     wr_4 = '".$_POST['wr_4']."',
	                     wr_5 = '".$_POST['wr_5']."',
	                     wr_6 = '".$_POST['wr_6']."',
	                     wr_7 = '".$_POST['wr_7']."',
	                     wr_8 = '".$_POST['wr_8']."',
	                     wr_9 = '".$_POST['wr_9']."',
	                     wr_10 = '".$_POST['wr_10']."'

					";
			sql_query($sql);
			$wr_id = sql_insert_id();
			goto_url("?bo_table={$bo_table}&wr_id={$wr_id}");
			break;

		case "delete":
			$chd = get_memInfos($wr_id,$member['mb_id']);
			if($chd || $is_admin) {
			$sql = "delete from	{$write_table}	where	wr_id = '$wr_id' ";
			sql_query($sql);
			echo "<script>alert('자료가 삭제되었습니다.'); location.href='?bo_table={$bo_table}'; </script>";
			} else
			echo "<script>alert('글 등록자만 삭제가 가능합니다.'); location.href='?bo_table={$bo_table}&wr_id={$wr_id}'; </script>";
			break;
	}
}

if($wr_id) {
	$rs = get_memInfo($wr_id);
}

add_javascript(G5_POSTCODE_JS, 0);
add_javascript('<script src="'.$board_skin_url.'/js/doc.js"></script>');
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css">', 0);

?>

<script>
$(document).ready(function(){

	var idno = $(".mode").val();
	
	if(idno == 'new') { // 신규등록이면 취소 버튼을 숨긴다.
		$(":button:contains('삭제')", parent.document).hide();
	} else {
	<?php 
		$chd = get_memInfos($wr_id,$member['mb_id']);
		if($chd  || $is_admin) { 	
	?>
		$(":button:contains('삭제')", parent.document).show();
	<?php } else {?>
		$(":button:contains('저장')", parent.document).hide();
		$(":button:contains('삭제')", parent.document).hide();
	<?php } ?>
	}

	$("#mb_kind").change(function() {
		//var val = $(this).val();
		select_in($(this).val());
	});
});
</script>

<div id="mem_edit">
	
	<form name="fmData"  method="post" action="./car_edit.php" onsubmit="return fmData_check(this);">
	<input type="hidden" name="mode" class="mode" value='<?php echo ($wr_id)? "save":"new"; ?>' />
	<input type="hidden" name="wr_id" id="wr_id" value="<?php echo $wr_id?>" />
	<input type="hidden" name="id_chk" id="id_chk" value="<?php echo $rs['mb_id'];?>" />
	<input type="hidden" name="bo_table" value="<?php echo $bo_table ?>" />

	<table>
		<colgroup>
			<col width="25%">
			<col width="75%">
		</colgroup>
 
		<tr>
			<th>자산번호</th>
			<td><input type="text" name="wr_subject" class="mb_name" value="<?php echo $rs['wr_subject'];?>" required/>	
			</td>
		</tr>
		<tr>
			<th>모델명</th>
			<td><input type="text" name="wr_content" class="mb_name" value="<?php echo $rs['wr_content'];?>" required/></td>
		</tr>
		<tr>
			<th>제조사</th>
			<td>
				<input type="text" name="wr_1" class="mb_name" value="<?php echo $rs['wr_1'];?>" required/>	
			</td>
		</tr>
		<tr>
			<th>시리얼</th>
			<td>
				<input type="text" name="wr_4" class="mb_name" value="<?php echo $rs['wr_4'];?>" required/>	
			</td>
		</tr>
		<tr>
			<th>기타</th>
			<td>
				<textarea name="wr_2" class="mb_content" rows="5"><?php echo $rs['wr_2']; ?></textarea>
			</td>
		</tr>
	</table>
	<div class="bottom_msg">
	</div>

	<div class="button_zone">
		<button type="button" name="save" id="save" onclick="submit_mode('save');">저장</button>
		<button type="button" name="save" id="delete" onclick="submit_mode('delete');">삭제</button>
		<button type="button" name="cancel" id="cancel">취소</button>
	</div>
	</form>
</div>

<iframe name="ifm_chk" style="display:none;"></iframe>

<script language='Javascript'>
function submit_mode(mode) {
	var f = document.fmData;

	// wr_subject
	if(f.wr_subject.value == '') {
		alert("[자산번호]은 필수 입력항목입니다.");
		f.wr_subject.focus();
		$(".wr_subject").css("background","#FFFF00");
		return false;
	}

	// 모델명
	if(f.wr_content.value == '') {
		alert("[모델명]은 필수 입력항목입니다.");
		f.wr_content.focus();
		$(".wr_content").css("background","#FFFF00");
		return false;
	}

	if(mode=='save') {
			
		f.action = "./car_edit.php";
		f.submit();

	} else if(mode=='delete') {
		
		if(confirm("한번 삭제된 자료는 복구가 불가능합니다.\n\n그래도 삭제를 하시겠습니까?")) {
			f.mode.value="delete";
			f.action = "./car_edit.php";
			f.submit();
		} else {
			return false;
		}
	}
}
</script>
<?php include_once(G5_PATH."/tail.sub.php"); ?>