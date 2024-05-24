<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
?>

<script>
// 글자수 제한
var char_min = parseInt(<?php echo $comment_min ?>); // 최소
var char_max = parseInt(<?php echo $comment_max ?>); // 최대
</script>

<ol class="list-unstyled">
    <?php
    $cmt_amt = count($list);
	$cmt_depth = $cmt_depth_old = 0;
    for ($i=0; $i<$cmt_amt; $i++) {
        $comment_id = $list[$i]['wr_id'];
		$cmt_depth = strlen($list[$i]['wr_comment_reply']);
        $comment = $list[$i]['content'];

		$comment = preg_replace("/\[\<a\s.*href\=\"(http|https|ftp|mms)\:\/\/([^[:space:]]+)\.(mp3|wma|wmv|asf|asx|mpg|mpeg)\".*\<\/a\>\]/i", "<script>doc_write(obj_movie('$1://$2.$3'));</script>", $comment);

		if($cmt_depth > $cmt_depth_old) echo '<ol class="list-unstyled ps-3 ps-lg-4">';
		if($cmt_depth < $cmt_depth_old) for($j=$cmt_depth; $j<$cmt_depth_old; $j++) echo '</ol>';

		$mb_info = get_member_info($list[$i]['mb_id'], $list[$i]['wr_name'], $list[$i]['wr_email'], $list[$i]['wr_homepage'], ['css'=>'text-dark fw-bold']);

		$list[$i]['datetime'] = substr($list[$i]['wr_datetime'],0,10) == G5_TIME_YMD ? substr($list[$i]['wr_datetime'], 11, 8) : substr($list[$i]['wr_datetime'], 2, 8);
	?>
	<li class="mb-4">
		<div class="anchor">
			<a name="c_<?php echo $comment_id ?>"></a>
		</div>
		<div class="d-flex mb-4">
			<img class="comm-icon rounded me-3" src="<?php echo $mb_info['img'] ?>">
			<div class="comm-body w-100">
				<div class="d-flex mb-2">
					<ul class="comm-name list-inline text-muted">
						<li class="list-inline-item">
							<?php echo $mb_info['name'] ?>
							<?php if ($is_ip_view) { ?>
							<small class="text-muted">(<?php echo $list[$i]['ip']; ?>)</small>
							<?php } ?>
							<small><i class="fa fa-clock-o"></i> <?php echo $list[$i]['datetime']; ?></small>
						</li>
					</ul>
					<div class="ms-auto">
						<button class="btn btn-sm" type="button" data-bs-toggle="dropdown"><i class="fa fa-ellipsis-v" aria-hidden="true"></i></button>
						<ul class="dropdown-menu">
							<?php include(G5_THEME_PATH.'/skin/sns/view_comment_list.sns.skin.php'); ?>
							<?php if ($list[$i]['is_reply']) { ?>
							<li><a href="<?php echo $c_reply_href;  ?>" onclick="comment_box('<?php echo $comment_id ?>', 'c'); return false;" class="dropdown-item">댓글</a></li>
							<?php } ?>
							<?php if ($list[$i]['is_edit']) { ?>
							<li><a href="<?php echo $c_edit_href;  ?>" onclick="comment_box('<?php echo $comment_id ?>', 'cu'); return false;" class="dropdown-item">수정</a></li>
							<?php } ?>
							<?php if ($list[$i]['is_del'])  { ?>
							<li><a href="<?php echo $list[$i]['del_link'];  ?>" onclick="return comment_delete();" class="dropdown-item">삭제</a></li>
							<?php } ?>
							<?php if ($report_href) { ?>
							<li><a href="<?php echo $report_href ?>" data-id="<?php echo $comment_id; ?>" class="dropdown-item report">신고</a></li>
							<?php } ?>
						</ul>
					</div>
				</div>
				<ul class="list-inline mb-2">
					<?php if (strstr($list[$i]['wr_option'], "secret")) { ?><i class="fa fa-lock"></i><?php } ?>
			        <?php echo $comment ?>
				</ul>
				<span id="edit_<?php echo $comment_id ?>" class=""></span><!-- 수정 -->
		        <span id="reply_<?php echo $comment_id ?>" class=""></span><!-- 답변 -->

		        <input type="hidden" value="<?php echo strstr($list[$i]['wr_option'],"secret") ?>" id="secret_comment_<?php echo $comment_id ?>">
		        <textarea id="save_comment_<?php echo $comment_id ?>" style="display:none"><?php echo get_text($list[$i]['content1'], 0) ?></textarea>
			</div>
		</div>
	</li>
	<?php 
		$cmt_depth_old = $cmt_depth;
	 } 

	for($j=1; $j<$cmt_depth; $j++) echo '</ol>';
	?>
</ol>

<?php if ($is_comment_write) {
    if($w == '')
        $w = 'c';
?>

<div id="bo_vc_w" class="mb-4">
	<form name="fviewcomment" id="fviewcomment" action="<?php echo $comment_action_url; ?>" onsubmit="return fviewcomment_submit(this);" method="post" autocomplete="off">
    <input type="hidden" name="w" value="<?php echo $w ?>" id="w">
    <input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
    <input type="hidden" name="wr_id" value="<?php echo $wr_id ?>">
    <input type="hidden" name="comment_id" value="<?php echo $c_id ?>" id="comment_id">
    <input type="hidden" name="sca" value="<?php echo $sca ?>">
    <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
    <input type="hidden" name="stx" value="<?php echo $stx ?>">
    <input type="hidden" name="spt" value="<?php echo $spt ?>">
    <input type="hidden" name="page" value="<?php echo $page ?>">
    <input type="hidden" name="is_good" value="">

	<div class="mb-2">
		<textarea id="wr_content" name="wr_content" maxlength="10000" required class="form-control required" rows="3" placeholder="댓글내용을 입력해주세요"><?php echo $c_wr_content; ?></textarea>
	</div>

	<div id="comment_info" class="row collapse">
		<div class="col-md-6">
			<?php if ($is_guest) { ?>
			<div class="input-group mb-2">
				<input type="text" name="wr_name" value="<?php echo get_cookie("ck_sns_name"); ?>" id="wr_name" required class="form-control" placeholder="이름">
				<input type="password" name="wr_password" id="wr_password" required class="form-control" placeholder="비밀번호">
			</div>
			<?php }	?>
			
			<div class="form-check mb-2">
				<input type="checkbox" name="wr_secret" value="secret" id="wr_secret" class="form-check-input">
				<label class="form-check-label" for="wr_secret">비밀글 사용</label>
			</div>

			<?php if($board['bo_use_sns'] && ($config['cf_facebook_appid'] || $config['cf_twitter_key'])) { ?>
			<div class="mb-2">
				<span id="bo_vc_send_sns"></span>
			</div>
			<?php } ?>

			<?php if ($is_guest) { ?>
			<div class="mb-2">
				<?php echo $captcha_html; ?>
			</div>
			<?php } ?>
		</div>

		<div class="col-md-6">
			<input type="submit" class="btn btn-primary float-end" value="댓글등록">
		</div>
	</div>
	</form>
</div>

<script>
var save_before = '';
var save_html = document.getElementById('bo_vc_w').innerHTML;

function good_and_write()
{
    var f = document.fviewcomment;
    if (fviewcomment_submit(f)) {
        f.is_good.value = 1;
        f.submit();
    } else {
        f.is_good.value = 0;
    }
}

function fviewcomment_submit(f)
{
    var pattern = /(^\s*)|(\s*$)/g; // \s 공백 문자

    f.is_good.value = 0;

    var subject = "";
    var content = "";
    $.ajax({
        url: g5_bbs_url+"/ajax.filter.php",
        type: "POST",
        data: {
            "subject": "",
            "content": f.wr_content.value
        },
        dataType: "json",
        async: false,
        cache: false,
        success: function(data, textStatus) {
            subject = data.subject;
            content = data.content;
        }
    });

    if (content) {
        alert("내용에 금지단어('"+content+"')가 포함되어있습니다");
        f.wr_content.focus();
        return false;
    }

    // 양쪽 공백 없애기
    var pattern = /(^\s*)|(\s*$)/g; // \s 공백 문자
    document.getElementById('wr_content').value = document.getElementById('wr_content').value.replace(pattern, "");
    if (char_min > 0 || char_max > 0)
    {
        check_byte('wr_content', 'char_count');
        var cnt = parseInt(document.getElementById('char_count').innerHTML);
        if (char_min > 0 && char_min > cnt)
        {
            alert("댓글은 "+char_min+"글자 이상 쓰셔야 합니다.");
            return false;
        } else if (char_max > 0 && char_max < cnt)
        {
            alert("댓글은 "+char_max+"글자 이하로 쓰셔야 합니다.");
            return false;
        }
    }
    else if (!document.getElementById('wr_content').value)
    {
        alert("댓글을 입력하여 주십시오.");
        return false;
    }

    if (typeof(f.wr_name) != 'undefined')
    {
        f.wr_name.value = f.wr_name.value.replace(pattern, "");
        if (f.wr_name.value == '')
        {
            alert('이름이 입력되지 않았습니다.');
            f.wr_name.focus();
            return false;
        }
    }

    if (typeof(f.wr_password) != 'undefined')
    {
        f.wr_password.value = f.wr_password.value.replace(pattern, "");
        if (f.wr_password.value == '')
        {
            alert('비밀번호가 입력되지 않았습니다.');
            f.wr_password.focus();
            return false;
        }
    }

    <?php if($is_guest) echo chk_captcha_js();  ?>

    set_comment_token(f);

    document.getElementById("btn_submit").disabled = "disabled";

    return true;
}

function comment_box(comment_id, work)
{
    var el_id,
        form_el = 'fviewcomment',
        respond = document.getElementById(form_el);

    // 댓글 아이디가 넘어오면 답변, 수정
    if (comment_id)
    {
        if (work == 'c')
            el_id = 'reply_' + comment_id;
        else
            el_id = 'edit_' + comment_id;
    }
    else
        el_id = 'bo_vc_w';

    if (save_before != el_id)
    {
        if (save_before)
        {
            document.getElementById(save_before).style.display = 'none';
        }

        document.getElementById(el_id).style.display = '';
        document.getElementById(el_id).appendChild(respond);
        //입력값 초기화
        document.getElementById('wr_content').value = '';
        
        // 댓글 수정
        if (work == 'cu')
        {
            document.getElementById('wr_content').value = document.getElementById('save_comment_' + comment_id).value;
            if (typeof char_count != 'undefined')
                check_byte('wr_content', 'char_count');
            if (document.getElementById('secret_comment_'+comment_id).value)
                document.getElementById('wr_secret').checked = true;
            else
                document.getElementById('wr_secret').checked = false;
        }

        document.getElementById('comment_id').value = comment_id;
        document.getElementById('w').value = work;

        if(save_before)
            $("#captcha_reload").trigger("click");

        save_before = el_id;
    }
}

function comment_delete()
{
    return confirm("이 댓글을 삭제하시겠습니까?");
}

comment_box('', 'c'); // 댓글 입력폼이 보이도록 처리하기위해서 추가 (root님)

<?php if($board['bo_use_sns'] && ($config['cf_facebook_appid'] || $config['cf_twitter_key'])) { ?>

$(function() {
    // sns 등록
    $("#bo_vc_send_sns").load(
        "<?php echo G5_THEME_URL; ?>/skin/sns/view_comment_write.sns.skin.php?bo_table=<?php echo $bo_table; ?>",
        function() {
            save_html = document.getElementById('bo_vc_w').innerHTML;
        }
    );
});
<?php } ?>

$(function() {
	$("#wr_content").focus(function(){
		$("#comment_info").collapse("show");
	});
});

</script>
<?php }else{ if($board['bo_comment_level'] == 2) { ?>
<div id="bo_vc_w" class="mb-4">
	<div class="input-group mb-2">
		<textarea id="wr_content" name="wr_content" disabled class="form-control required" rows="3" placeholder="로그인 후 댓글내용을 입력해주세요"></textarea>
	</div>
</div>
<?php }} ?>