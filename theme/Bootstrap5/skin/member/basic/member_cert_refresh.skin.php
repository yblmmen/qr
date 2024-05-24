<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

add_stylesheet('<link rel="stylesheet" href="'.$member_skin_url.'/custom.css">');
if ($config['cf_cert_use'] && ($config['cf_cert_simple'] || $config['cf_cert_ipin'] || $config['cf_cert_hp']))
    add_javascript('<script src="'.G5_JS_URL.'/certify.js?v='.G5_JS_VER.'"></script>', 0);
?>
<!-- 기존 회원 본인인증 시작 { -->
<div class="member_cert_refresh">
    <form name="fcertrefreshform" id="member_cert_refresh" action="<?php echo $action_url ?>" onsubmit="return fcertrefreshform_submit(this);" method="POST" autocomplete="off">
    <input type="hidden" name="w" value="<?php echo $w ?>">
	<input type="hidden" name="url" value="<?php echo $urlencode ?>">
	<input type="hidden" name="cert_type" value="<?php echo $member['mb_certify']; ?>">
    <input type="hidden" name="mb_id" value="<?php echo $member['mb_id']; ?>">
    <input type="hidden" name="mb_hp" value="<?php echo $member['mb_hp']; ?>">
    <input type="hidden" name="mb_name" value="<?php echo $member['mb_name']; ?>">
	<input type="hidden" name="cert_no" value="">

	<div class="text-center mb-5">
		<a href="<?php echo G5_URL ?>"><img src="<?php echo G5_IMG_URL ?>/logo.png" alt="<?php echo $config['cf_title']; ?>" class="logo"></a>
	</div>

	<div class="mb-4">
		<div class="form-check">
			<input type="checkbox" id="agree21" name="agree2" value="1" class="form-check-input">
			<label class="form-check-label" for="agree21">추가 개인정보처리방침 동의</label>
		</div>
		<textarea class="form-control" rows="5" style="font-size: 0.8rem;">
목적 : 이용자 식별 및 본인여부 확인
항목 : 생년월일<?php echo (empty($member['mb_dupinfo']))? ", 휴대폰 번호(아이핀 제외)" : ""; ?>, 암호화된 개인식별부호(CI)
기간 : 회원 탈퇴 시까지
		</textarea>
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

    </form>
</div>

    <script>
        $(function() {
            var pageTypeParam = "pageType=register";
            var f = document.fcertrefreshform;

            <?php if ($config['cf_cert_use'] && $config['cf_cert_simple']) { ?>
                // 이니시스 간편인증
                var url = "<?php echo G5_INICERT_URL; ?>/ini_request.php";
                var type = "";
                var params = "";
                var request_url = "";

                $(".win_sa_cert").click(function() {
                    if (!fcertrefreshform_submit(f)) return false;
                    type = $(this).data("type");
                    params = "?directAgency=" + type + "&" + pageTypeParam;
                    request_url = url + params;
                    call_sa(request_url);
                });
            <?php } ?>

            <?php if ($config['cf_cert_use'] && $config['cf_cert_ipin']) { ?>
                // 아이핀인증
                var params = "";
                $("#win_ipin_cert").click(function() {
                    if (!fcertrefreshform_submit(f)) return false;
                    params = "?" + pageTypeParam;
                    var url = "<?php echo G5_OKNAME_URL; ?>/ipin1.php" + params;
                    certify_win_open('kcb-ipin', url);
                    return;
                });
            <?php } ?>

            <?php if ($config['cf_cert_use'] && $config['cf_cert_hp']) { ?>
                // 휴대폰인증
                var params = "";
                $("#win_hp_cert").click(function() {
                    if (!fcertrefreshform_submit(f)) return false;
                    params = "?" + pageTypeParam;
                    <?php
                    switch ($config['cf_cert_hp']) {
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

                    certify_win_open("<?php echo $cert_type; ?>", "<?php echo $cert_url; ?>" + params);
                    return;
                });
            <?php } ?>
        });
        
        function fcertrefreshform_submit(f) {
            if (!f.agree2.checked) {
                alert("추가 개인정보처리방침에 동의하셔야 인증을 진행하실 수 있습니다.");
                f.agree2.focus();
                return false;
            }

            return true;
        }
    </script>
</div>
<!-- } 기존 회원 본인인증 끝 -->