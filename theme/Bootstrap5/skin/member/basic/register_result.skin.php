<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/custom.css">');

if(!is_use_email_certify()) goto_url(G5_URL);
?>

<div class="register">
	<div class="text-center mb-5">
		<a href="<?php echo G5_URL ?>"><img src="<?php echo G5_IMG_URL ?>/logo.png" alt="<?php echo $config['cf_title']; ?>" class="logo"></a>
	</div>

	<p>
        <strong><?php echo get_text($mb['mb_name']); ?></strong>님의 회원가입을 축하합니다.
    </p>

    <?php if (is_use_email_certify()) {  ?>
    <p>
        회원 가입 시 입력하신 이메일 주소로 인증메일이 발송되었습니다.<br>
        발송된 인증메일을 확인하신 후 인증처리를 하시면 사이트를 원활하게 이용하실 수 있습니다.
    </p>
    <p>
		아이디 : <strong><?php echo $mb['mb_id'] ?></strong><br>
		이메일 : <strong><?php echo $mb['mb_email'] ?></strong>
    </p>
    <p>
        이메일 주소를 잘못 입력하셨다면, 사이트 관리자에게 문의해주시기 바랍니다.
    </p>
    <?php }  ?>

    <p>
        회원님의 비밀번호는 아무도 알 수 없는 암호화 코드로 저장되므로 안심하셔도 좋습니다.<br>
        아이디, 비밀번호 분실시에는 회원가입시 입력하신 이메일 주소를 이용하여 찾을 수 있습니다.
    </p>

    <p>
        회원 탈퇴는 언제든지 가능하며 일정기간이 지난 후, 회원님의 정보는 삭제하고 있습니다.<br>
        감사합니다.
    </p>

	<div class="text-center">
		<a href="<?php echo G5_URL ?>/" class="btn btn-primary w-100">메인으로</a>
	</div>
</div>