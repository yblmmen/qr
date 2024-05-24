<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/custom.css">');
?>

<div class="register">
<form name="fregister" action="<?php echo $register_action_url ?>" onsubmit="return fregister_submit(this);" method="POST" autocomplete="off">

	<div class="text-center mb-5">
		<a href="<?php echo G5_URL ?>"><img src="<?php echo G5_IMG_URL ?>/logo.png" alt="<?php echo $config['cf_title']; ?>" class="logo"></a>
	</div>

	<div id="social">
	<?php
	// 소셜로그인 사용시 소셜로그인 버튼
	@include_once(get_social_skin_path().'/social_register.skin.php');
	?>
	</div>

	<div id="default">
		<div class="mb-4">
			<div class="form-check mb-2">
				<input type="checkbox" name="chk_all" id="chk_all" class="form-check-input">
				<label class="form-check-label fw-bold" for="chk_all">이용약관에 모두 동의합니다.</label>
			</div>
		</div>
		<hr>
		<div class="mb-4">
			<div class="form-check mb-2">
				<input type="checkbox" id="agree11" name="agree" value="1" class="form-check-input">
				<label class="form-check-label" for="agree11">회원가입약관 동의</label>
			</div>
			<textarea class="form-control" rows="5" style="font-size: 0.8rem;"><?php echo get_text($config['cf_stipulation']) ?></textarea>
		</div>
		<div class="mb-4">
			<div class="form-check mb-2">
				<input type="checkbox" id="agree21" name="agree2" value="1" class="form-check-input">
				<label class="form-check-label" for="agree21">개인정보처리방침안내 동의</label>
			</div>
			<textarea class="form-control" rows="5" style="font-size: 0.8rem;"><?php echo get_text($config['cf_privacy']) ?></textarea>
		</div>

		<input class="btn btn-primary w-100" type="submit" value="다음">
	</div>
</form>
</div>

<script>
	function fregister_submit(f)
	{
		if (!f.agree.checked) {
			alert("회원가입약관의 내용에 동의하셔야 회원가입 하실 수 있습니다.");
			f.agree.focus();
			return false;
		}

		if (!f.agree2.checked) {
			alert("개인정보처리방침안내의 내용에 동의하셔야 회원가입 하실 수 있습니다.");
			f.agree2.focus();
			return false;
		}

		return true;
	}

	jQuery(function($){
		// 모두선택
		$("input[name=chk_all]").click(function() {
			if ($(this).prop('checked')) {
				$("input[name^=agree]").prop('checked', true);
			} else {
				$("input[name^=agree]").prop("checked", false);
			}
		});
	});
</script>
<!-- } 회원가입 약관 동의 끝 -->
