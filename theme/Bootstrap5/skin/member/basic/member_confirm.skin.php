<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/custom.css">');
?>

<div class="mb_confirm">
	<div class="text-center mb-5">
		<a href="<?php echo G5_URL ?>"><img src="<?php echo G5_IMG_URL ?>/logo.png" alt="<?php echo $config['cf_title']; ?>" class="logo"></a>
	</div>

	<form name="fmemberconfirm" action="<?php echo $url ?>" method="post">
    <input type="hidden" name="mb_id" value="<?php echo $member['mb_id'] ?>">
    <input type="hidden" name="w" value="u">

	<div class="card">
		<div class="card-header text-white bg-danger">비밀번호 확인</div>
		<div class="card-body">
			<div class="input-group">
				<input type="password" name="mb_password" class="form-control frm_input required" maxLength="20" placeholder="비밀번호" required>
				<button class="btn btn-primary" type="submit">확인</button>
			</div>
		</div>
	</div>

	</form>
</div>