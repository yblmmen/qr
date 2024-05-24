<?php
//if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
// 리스트화면에서 채크된 자료를 일괄로 삭제하는 파일입니다.
include_once("./_common.php");

if(!$is_admin) {
	//관리자가 아니면 종료
	echo "관리자 접근 아님";
	exit;
}

// 그리드게시판에서 oper=del, id=xx,xx,xx 형태로 넘어옴.
if($_POST['oper']!='del') {
	// oper 값이 넘어오지 않으면 페이지 종료, POST로만 받기
	exit;
}

// 삭제할 게시물의 정보를 배열로 변환
$tmp_array = explode(",", $_POST['id']);
//print_r($tmp_array);
//exit;

$count_write = 0;
$count_comment = 0;
$chk_count = count($tmp_array);
$bo_table = $_GET['bo_table'];

if($chk_count > (G5_IS_MOBILE ? $board['bo_mobile_page_rows'] : $board['bo_page_rows'])) {
	echo "올바른 방법으로 이용해 주셩";
	exit;
 //alert('올바른 방법으로 이용해 주십시오.');
}


// 거꾸로 읽는 이유는 답변글부터 삭제가 되어야 하기 때문임
for ($i=$chk_count-1; $i>=0; $i--)
{
    $write = sql_fetch(" select * from $write_table where wr_id = '$tmp_array[$i]' ");

    if ($is_admin == 'super') // 최고관리자 통과
        ;
    else if ($is_admin == 'group') // 그룹관리자
    {
        $mb = get_member($write['mb_id']);
        if ($member['mb_id'] == $group['gr_admin']) // 자신이 관리하는 그룹인가?
        {
            if ($member['mb_level'] >= $mb['mb_level']) // 자신의 레벨이 크거나 같다면 통과
                ;
            else
                continue;
        }
        else
            continue;
    }
    else if ($is_admin == 'board') // 게시판관리자이면
    {
        $mb = get_member($write['mb_id']);
        if ($member['mb_id'] == $board['bo_admin']) // 자신이 관리하는 게시판인가?
            if ($member['mb_level'] >= $mb['mb_level']) // 자신의 레벨이 크거나 같다면 통과
                ;
            else
                continue;
        else
            continue;
    }
    else if ($member['mb_id'] && $member['mb_id'] == $write['mb_id']) // 자신의 글이라면
    {
        ;
    }
    else if ($wr_password && !$write['mb_id'] && check_password($wr_password, $write['wr_password'])) // 비밀번호가 같다면
    {
        ;
    }
    else
        continue;   // 나머지는 삭제 불가

    $len = strlen($write['wr_reply']);
    if ($len < 0) $len = 0;
    $reply = substr($write['wr_reply'], 0, $len);

    // 원글만 구한다.
    $sql = " select count(*) as cnt from $write_table
                where wr_reply like '$reply%'
                and wr_id <> '{$write['wr_id']}'
                and wr_num = '{$write['wr_num']}'
                and wr_is_comment = 0 ";
    $row = sql_fetch($sql);
    if ($row['cnt'])
            continue;

    // 나라오름님 수정 : 원글과 코멘트수가 정상적으로 업데이트 되지 않는 오류를 잡아 주셨습니다.
    //$sql = " select wr_id, mb_id, wr_comment from {$write_table} where wr_parent = '{$write[wr_id]}' order by wr_id ";
    $sql = " select wr_id, mb_id, wr_is_comment, wr_content from $write_table where wr_parent = '{$write['wr_id']}' order by wr_id ";
    $result = sql_query($sql);
    while ($row = sql_fetch_array($result))
    {
        // 원글이라면
        if (!$row['wr_is_comment'])
        {
            // 원글 포인트 삭제
            if (!delete_point($row['mb_id'], $bo_table, $row['wr_id'], '쓰기'))
                insert_point($row['mb_id'], $board['bo_write_point'] * (-1), "{$board['bo_subject']} {$row['wr_id']} 글 삭제");

            // 업로드된 파일이 있다면
            $sql2 = " select * from {$g5['board_file_table']} where bo_table = '$bo_table' and wr_id = '{$row['wr_id']}' ";
            $result2 = sql_query($sql2);
            while ($row2 = sql_fetch_array($result2)) {
                // 파일삭제
                @unlink(G5_DATA_PATH.'/file/'.$bo_table.'/'.$row2['bf_file']);

                // 썸네일삭제
                if(preg_match("/\.({$config['cf_image_extension']})$/i", $row2['bf_file'])) {
                    delete_board_thumbnail($bo_table, $row2['bf_file']);
                }
            }

            // 에디터 썸네일 삭제
            delete_editor_thumbnail($row['wr_content']);

            // 파일테이블 행 삭제
            sql_query(" delete from {$g5['board_file_table']} where bo_table = '$bo_table' and wr_id = '{$row['wr_id']}' ");

            $count_write++;
        }
        else
        {
            // 코멘트 포인트 삭제
            if (!delete_point($row['mb_id'], $bo_table, $row['wr_id'], '댓글'))
                insert_point($row['mb_id'], $board['bo_comment_point'] * (-1), "{$board['bo_subject']} {$write['wr_id']}-{$row['wr_id']} 댓글삭제");

            $count_comment++;
        }
    }

    // 게시글 삭제
    sql_query(" delete from $write_table where wr_parent = '{$write['wr_id']}' ");

    // 최근게시물 삭제
    sql_query(" delete from {$g5['board_new_table']} where bo_table = '$bo_table' and wr_parent = '{$write['wr_id']}' ");

    // 스크랩 삭제
    sql_query(" delete from {$g5['scrap_table']} where bo_table = '$bo_table' and wr_id = '{$write['wr_id']}' ");

    $bo_notice = board_notice($board['bo_notice'], $write['wr_id']);
    sql_query(" update {$g5['board_table']} set bo_notice = '$bo_notice' where bo_table = '$bo_table' ");
    $board['bo_notice'] = $bo_notice;
}

// 글숫자 감소
if ($count_write > 0 || $count_comment > 0)
    sql_query(" update {$g5['board_table']} set bo_count_write = bo_count_write - '$count_write', bo_count_comment = bo_count_comment - '$count_comment' where bo_table = '$bo_table' ");

delete_cache_latest($bo_table);
?>
