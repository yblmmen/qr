<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
$delete_str = "";
if ($w == 'x') $delete_str = "댓";
if ($w == 'u') $g5['title'] = $delete_str."글 수정";
else if ($w == 'd' || $w == 'x') $g5['title'] = $delete_str."글 삭제";
else $g5['title'] = $g5['title'];

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/custom.css">');
?>

<div class="password">
	<form name="fboardpassword" action="<?php echo $action;  ?>" method="post">
	<input type="hidden" name="w" value="<?php echo $w ?>">
	<input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
	<input type="hidden" name="wr_id" value="<?php echo $wr_id ?>">
	<input type="hidden" name="comment_id" value="<?php echo $comment_id ?>">
	<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
	<input type="hidden" name="stx" value="<?php echo $stx ?>">
	<input type="hidden" name="page" value="<?php echo $page ?>">

	<div class="text-center mb-5">
		<a href="<?php echo G5_URL ?>"><img src="<?php echo G5_IMG_URL ?>/logo.png" alt="<?php echo $config['cf_title']; ?>" class="logo"></a>
	</div>

	<?php
		switch($w)
		{
			case 'u': 
				$content = '작성자 본인이라면, 글 작성시 입력한 비밀번호를 입력하여 글을 수정할 수 있습니다.'; 
				break;

			case 'd':
			case 'x':
				$content = '작성자 본인이라면, 글 작성시 입력한 비밀번호를 입력하여 글을 삭제할 수 있습니다.';
				break;

			default:
				$content = '작성자와 관리자만 열람하실 수 있습니다.<br> 본인이라면 비밀번호를 입력하세요.';
		}
	?>	
	<div class="alert alert-danger mb-4" style="font-size:80%">
		<h4 class="alert-heading"><?php echo $g5['title'] ?></h4>
		<p class="mb-0"><?php echo $content ?></p>
	</div>

	<div class="input-group mb-4">
		<input type="password" name="wr_password" class="form-control frm_input required" maxLength="20" placeholder="비밀번호" required>
		<button class="btn btn-primary" type="submit">확인</button>
	</div>

	</form>
</div>