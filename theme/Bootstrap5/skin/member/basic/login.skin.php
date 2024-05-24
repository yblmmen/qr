<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/custom.css">');
?>

<div class="mb_login">
<form name="flogin" action="<?php echo $login_action_url ?>" onsubmit="return flogin_submit(this);" method="post">
	<input type="hidden" name="url" value="<?php echo $login_url ?>">

	<div class="text-center mb-5">
		<a href="<?php echo G5_URL ?>"><img src="<?php echo G5_IMG_URL ?>/logo.png" alt="<?php echo $config['cf_title']; ?>" class="logo"></a>
	</div>

	<?php
    // 소셜로그인 사용시 소셜로그인 버튼
    @include_once(get_social_skin_path().'/social_login.skin.php');
    ?>

	<div>
		<div>
			<input type="user" name="mb_id" class="form-control" placeholder="아이디" required autofocus>
			<input type="password" name="mb_password" class="form-control" placeholder="비밀번호" required>
		</div>
		<div class="form-check mb-4">
			<input type="checkbox" name="auto_login" id="login_auto_login" class="form-check-input">
			<label class="form-check-label" for="login_auto_login">로그인 상태 유지</label>
		</div>
		<button class="btn btn-primary w-100 mb-4" type="submit">로그인</button>
		<div class="text-center">
			<a href="./register.php">회원 가입</a> |
			<a href="<?php echo G5_BBS_URL ?>/password_lost.php">암호를 분실하셨나요?</a>
		</div>
	</div>

</form>
</div>

<script>
$(function(){
    $("#login_auto_login").click(function(){
        if (this.checked) {
            this.checked = confirm("자동로그인을 사용하시면 다음부터 회원아이디와 비밀번호를 입력하실 필요가 없습니다.\n\n공공장소에서는 개인정보가 유출될 수 있으니 사용을 자제하여 주십시오.\n\n자동로그인을 사용하시겠습니까?");
        }
    });
});

function flogin_submit(f)
{
    if( $( document.body ).triggerHandler( 'login_sumit', [f, 'flogin'] ) !== false ){
        return true;
    }
    return false;
}
</script>