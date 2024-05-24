<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/custom.css">');

if($config['cf_cert_use'] && ($config['cf_cert_simple'] || $config['cf_cert_ipin'] || $config['cf_cert_hp'])) { ?>
    <script src="<?php echo G5_JS_URL ?>/certify.js?v=<?php echo G5_JS_VER; ?>"></script>    
<?php } ?>

<!-- 회원정보 찾기 시작 { -->
<div id="find_info" class="password new_win<?php if($config['cf_cert_use'] != 0 && $config['cf_cert_find'] != 0) { ?> cert<?php } ?>">
	<form name="fpasswordlost" action="<?php echo $action_url ?>" onsubmit="return fpasswordlost_submit(this);" method="post" autocomplete="off">
	
	<div class="text-center mb-5">
		<a href="<?php echo G5_URL ?>"><img src="<?php echo G5_IMG_URL ?>/logo.png" alt="<?php echo $config['cf_title']; ?>" class="logo"></a>
	</div>

	<div class="alert alert-danger mb-2" style="font-size: 80%">
		<!-- <h4 class="alert-heading"><?php echo $g5['title'] ?></h4>  -->
		가입시 입력한 <strong>이메일</strong>을 입력하세요.
		<?php if($config['cf_cert_use'] != 0 && $config['cf_cert_find'] != 0) { ?>
		<br />또는 아래 <strong>인증버튼</strong>을 클릭해 주세요.
		<?php } ?>
	</div>

	<div class="input-group mb-2">
		<input type="email" name="mb_email" class="form-control" placeholder="이메일" required>
		<button class="btn btn-primary" type="submit">확인</button>
	</div>

	<div class="mb-2">
		<?php echo captcha_html(); ?>
	</div>

	<?php if($config['cf_cert_use'] != 0 && $config['cf_cert_find'] != 0) { ?> 
	<div class="mt-4 new_win_con find_btn">
		<div class="cert_btn btn-group xs-100">
			<?php if(!empty($config['cf_cert_simple'])) { ?>
			<button type="button" id="win_sa_kakao_cert" class="btn btn-primary win_sa_cert" data-type="">간편인증</button>
			<?php } if(!empty($config['cf_cert_hp']) || !empty($config['cf_cert_ipin'])) { ?>
			<?php if(!empty($config['cf_cert_hp'])) { ?>
			<button type="button" id="win_hp_cert" class="btn btn-primary">휴대폰인증</button>
			<?php } if(!empty($config['cf_cert_ipin'])) { ?>
			<button type="button" id="win_ipin_cert" class="btn btn-primary">아이폰인증</button>
			<?php } ?>
			<?php } ?>
		</div>
	</div>
	<?php } ?>
</div>

<script>    
$(function() {
    $("#reg_zip_find").css("display", "inline-block");
    var pageTypeParam = "pageType=find";

	<?php if($config['cf_cert_use'] && $config['cf_cert_simple']) { ?>
	// TOSS 간편인증
	var url = "<?php echo G5_INICERT_URL; ?>/ini_request.php";
	var type = "";    
    var params = "";
    var request_url = "";
    
	
	$(".win_sa_cert").click(function() {
		type = $(this).data("type");
		params = "?directAgency=" + type + "&" + pageTypeParam;
        request_url = url + params;
        call_sa(request_url);
	});
    <?php } ?>
    <?php if($config['cf_cert_use'] && $config['cf_cert_ipin']) { ?>
    // 아이핀인증
    var params = "";
    $("#win_ipin_cert").click(function() {
        params = "?" + pageTypeParam;
        var url = "<?php echo G5_OKNAME_URL; ?>/ipin1.php"+params;
        certify_win_open('kcb-ipin', url);
        return;
    });

    <?php } ?>
    <?php if($config['cf_cert_use'] && $config['cf_cert_hp']) { ?>
    // 휴대폰인증
    var params = "";
    $("#win_hp_cert").click(function() {
        params = "?" + pageTypeParam;
        <?php     
        switch($config['cf_cert_hp']) {
            case 'kcb':                
                $cert_url = G5_OKNAME_URL.'/hpcert1.php';
                $cert_type = 'kcb-hp';
                break;
            case 'kcp':
                $cert_url = G5_KCPCERT_URL.'/kcpcert_form.php';
                $cert_type = 'kcp-hp';
                break;
            case 'lg':
                $cert_url = G5_LGXPAY_URL.'/AuthOnlyReq.php';
                $cert_type = 'lg-hp';
                break;
            default:
                echo 'alert("기본환경설정에서 휴대폰 본인확인 설정을 해주십시오");';
                echo 'return false;';
                break;
        }
        ?>
        
        certify_win_open("<?php echo $cert_type; ?>", "<?php echo $cert_url; ?>"+params);
        return;
    });
    <?php } ?>
});
function fpasswordlost_submit(f)
{
    <?php echo chk_captcha_js();  ?>

    return true;
}
</script>
<!-- } 회원정보 찾기 끝 -->