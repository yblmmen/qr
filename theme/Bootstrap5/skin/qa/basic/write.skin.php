<?php
/*
   1:1문의 설정에서 이메일 필수 입력을 체크 한 경우 답변을 수정할때 이메일을 입력하라고 뜨는 버그인가?
   1:1문의 글 작성 후 수정했을때 답변메일받기, 답변문자받기 가 갱신되지 않는 버그인가?
*/

if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

add_stylesheet('<link rel="stylesheet" href="'.$qa_skin_url.'/custom.css">', 0);
?>

<div>

	<blockquote><h3><?php echo $qaconfig['qa_title'] ?></h3></blockquote>

    <form name="fwrite" id="fwrite" action="<?php echo $action_url ?>" onsubmit="return fwrite_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off">
    <input type="hidden" name="w" value="<?php echo $w ?>">
    <input type="hidden" name="qa_id" value="<?php echo $qa_id ?>">
    <input type="hidden" name="sca" value="<?php echo $sca ?>">
    <input type="hidden" name="stx" value="<?php echo $stx ?>">
    <input type="hidden" name="page" value="<?php echo $page ?>">
    <input type="hidden" name="token" value="<?php echo $token ?>">    <?php
    $option = '';
    $option_hidden = '';
    $option = '';
			
	if ($is_dhtml_editor) {
		$option_hidden .= '<input type="hidden" name="qa_html" value="1">';
	} else {
		$option .= '<div class="form-check form-check-inline"><input type="checkbox" id="qa_html" name="qa_html" onclick="html_auto_br(this);" value="'.$html_value.'"  class="form-check-input" '.$html_checked.'><label class="form-check-label" for="qa_html">HTML</label></div>';
	}

	if ($is_email) {
		$option .= '<div class="form-check form-check-inline"><input type="checkbox" id="qa_email_recv" name="qa_email_recv" value="1" class="form-check-input"'.(isset($write['qa_email_recv']) && $write['qa_email_recv'] ? ' checked="checked"':'').'><label class="form-check-label" for="qa_email_recv">답변메일</label></div>';
	}

	if ($is_hp&&$qaconfig['qa_use_sms']) {
		$option .= '<div class="form-check form-check-inline"><input type="checkbox" id="qa_sms_recv" name="qa_sms_recv" value="1" class="form-check-input"'.(isset($write['qa_sms_recv']) && $write['qa_sms_recv'] ? ' checked="checked"':'').'><label class="form-check-label" for="qa_sms_recv">답변문자</label></div>';
	}

	echo $option_hidden;
    ?>

	<?php if ($category_option) { ?>
	<div class="form-group row mb-2">
		<label class="col-sm-2 col-form-label">분류</label>
		<div class="col-sm-10">
			<select class="form-select" name="qa_category" id="qa_category" required>
				<option value="">분류를 선택하세요</option>
				<?php echo $category_option ?>
			</select>
		</div>
	</div>
	<?php } ?>

	<?php if ($is_email) { ?>
	<div class="form-group row mb-2">
		<label class="col-sm-2 col-form-label">이메일</label>
		<div class="col-sm-10">
			<div class="input-group">
				<input class="form-control" type="text" name="qa_email" value="<?php echo get_text($write['qa_email']); ?>" id="qa_email" <?php echo $req_email; ?> placeholder="이메일">
			</div>
		</div>
	</div>
	<?php } ?>

	<?php if ($is_hp) { ?>
	<div class="form-group row mb-2">
		<label class="col-sm-2 col-form-label">휴대폰</label>
		<div class="col-sm-10">
			<input class="form-control" type="text" name="qa_hp" value="<?php echo get_text($write['qa_hp']); ?>" id="qa_hp" <?php echo $req_hp; ?> placeholder="휴대폰">
		</div>
	</div>
	<?php } ?>

	<div class="form-group row mb-2">
		<label class="col-sm-2 col-form-label">제목</label>
		<div class="col-sm-10">
			<input class="form-control" type="text" name="qa_subject" value="<?php echo get_text($write['qa_subject']); ?>" id="qa_subject" required placeholder="제목">
		</div>
	</div>

	<div class="form-group row mb-2">
		<label class="col-sm-2 col-form-label">내용</label>
		<div class="qa_content col-sm-10 <?php echo $is_dhtml_editor ? $config['cf_editor'] : ''; ?>">
			<?php $editor_html = str_replace('<textarea id="qa_content" name="qa_content"', '<textarea id="qa_content" name="qa_content" class="form-control" rows="12"', $editor_html); ?>

			<?php echo $editor_html; // 에디터 사용시는 에디터로, 아니면 textarea 로 노출 ?>
		</div>
	</div>

	<?php if ($option) { ?>
	<div class="form-group row mb-2">
		<label class="col-sm-2 col-form-label">옵션</label>
		<div class="col-sm-10 pt-2">
			<?php echo $option ?>
		</div>
	</div>
	<?php } ?>

	<?php for ($i=0; $i<2; $i++) { ?>
	<div class="form-group row mb-2">
		<label class="col-sm-2 col-form-label">파일 #<?php echo $i+1 ?></label>
		<div class="col-sm-10">
			<div class="input-group">
				<input type="file" name="bf_file[<?php echo $i+1 ?>]" id="bf_file_<?php echo $i+1 ?>" class="form-control" title="<?php echo $upload_max_filesize ?> 이하만 업로드 가능">
			</div>

			<?php if($w == 'u' && $write['qa_file'.($i+1)]) { ?>
			<div class="form-check">
				<input type="checkbox" id="bf_file_del<?php echo $i+1 ?>" name="bf_file_del[<?php echo $i+1;  ?>]" value="1" class="form-check-input">
				<label class="form-check-label" for="bf_file_del<?php echo $i+1 ?>"><?php echo $write['qa_source'.($i+1)]; ?> 파일 삭제</label>
			</div>
			<?php } ?>
		</div>
	</div>
	<?php } ?>

	<div class="d-flex justify-content-end my-4">
		<div class="btn-group xs-100">
			<input type="submit" value="작성완료" id="btn_submit" accesskey="s" class="btn btn-primary">
			<a href="<?php echo $list_href; ?>" class="btn btn-outline-primary">취소</a>
		</div>
	</div>

	</form>
</div>

<script>
function html_auto_br(obj)
{
	if (obj.checked) {
		result = confirm("자동 줄바꿈을 하시겠습니까?\n\n자동 줄바꿈은 게시물 내용중 줄바뀐 곳을<br>태그로 변환하는 기능입니다.");
		if (result)
			obj.value = "2";
		else
			obj.value = "1";
	}
	else
		obj.value = "";
}

function fwrite_submit(f)
{
	<?php echo $editor_js; // 에디터 사용시 자바스크립트에서 내용을 폼필드로 넣어주며 내용이 입력되었는지 검사함   ?>

	var subject = "";
	var content = "";
	$.ajax({
		url: g5_bbs_url+"/ajax.filter.php",
		type: "POST",
		data: {
			"subject": f.qa_subject.value,
			"content": f.qa_content.value
		},
		dataType: "json",
		async: false,
		cache: false,
		success: function(data, textStatus) {
			subject = data.subject;
			content = data.content;
		}
	});

	if (subject) {
		alert("제목에 금지단어('"+subject+"')가 포함되어있습니다");
		f.qa_subject.focus();
		return false;
	}

	if (content) {
		alert("내용에 금지단어('"+content+"')가 포함되어있습니다");
		if (typeof(ed_qa_content) != "undefined")
			ed_qa_content.returnFalse();
		else
			f.qa_content.focus();
		return false;
	}

	<?php if ($is_hp) { ?>
	var hp = f.qa_hp.value.replace(/[0-9\-]/g, "");
	if(hp.length > 0) {
		alert("휴대폰번호는 숫자, - 으로만 입력해 주십시오.");
		return false;
	}
	<?php } ?>

	document.getElementById("btn_submit").disabled = "disabled";

	return true;
}
</script>