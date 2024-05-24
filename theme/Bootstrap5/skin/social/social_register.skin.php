<?php
if(!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if(!$config['cf_social_login_use'])return;    //소셜 로그인을 사용하지 않으면

$social_pop_once = false;

$self_url = G5_BBS_URL."/login.php";

//새창을 사용한다면
if(G5_SOCIAL_USE_POPUP ) {
    $self_url = G5_SOCIAL_LOGIN_URL.'/popup.php';
}
?>

<!--
<div class="sns-wrap text-center mb-4">
	<?php if(social_service_check('naver')){     //네이버 로그인을 사용한다면 ?>
	<a href="<?php echo $self_url;?>?provider=naver&amp;url=<?php echo $urlencode;?>" class="social_link" title="네이버"><img src="<?php echo get_social_skin_url().'/img/sns_naver_s.png' ?>"></a>
	<?php } ?>

	<?php if(social_service_check('kakao')){     //카카오 로그인을 사용한다면 ?>
	<a href="<?php echo $self_url;?>?provider=kakao&amp;url=<?php echo $urlencode;?>" class="social_link" title="카카오"><img src="<?php echo get_social_skin_url().'/img/sns_kakao_s.png' ?>"></a>
	<?php }     //end if ?>
	<?php if(social_service_check('facebook')){     //페이스북 로그인을 사용한다면 ?>
	<a href="<?php echo $self_url;?>?provider=facebook&amp;url=<?php echo $urlencode;?>" class="social_link" title="페이스북"><img src="<?php echo get_social_skin_url().'/img/sns_facebook_s.png' ?>"></a>
	<?php }     //end if ?>
	<?php if(social_service_check('google')){     //구글 로그인을 사용한다면 ?>
	<a href="<?php echo $self_url;?>?provider=google&amp;url=<?php echo $urlencode;?>" class="social_link" title="구글"><img src="<?php echo get_social_skin_url().'/img/sns_google_s.png' ?>"></a>
	<?php }     //end if ?>
	<?php if(social_service_check('twitter')){     //트위터 로그인을 사용한다면 ?>
	<a href="<?php echo $self_url;?>?provider=twitter&amp;url=<?php echo $urlencode;?>" class="social_link" title="트위터"><img src="<?php echo get_social_skin_url().'/img/sns_twitter_s.png' ?>"></a>
	<?php }     //end if ?>
	<?php if(social_service_check('payco')){     //페이코 로그인을 사용한다면 ?>
	<a href="<?php echo $self_url;?>?provider=payco&amp;url=<?php echo $urlencode;?>" class="social_link" title="페이코"><img src="<?php echo get_social_skin_url().'/img/sns_payco_s.png' ?>"></a>
	<?php }     //end if ?>
</div>
-->

<?php if($config['cf_social_login_use']) { ?>
<div class="sns-wrap d-grid gap-2">
	<?php if(social_service_check('naver')){     //네이버 로그인을 사용한다면 ?>
	<a href="<?php echo $self_url;?>?provider=naver&amp;url=<?php echo $urlencode;?>" class="btn btn-light text-light social_link" style="background-color: #1EC800" title="네이버"><img src="<?php echo get_social_skin_url().'/img/sns_naver_s.png' ?>"> 네이버로 회원가입</a>
	<?php } ?>

	<?php if(social_service_check('kakao')){     //카카오 로그인을 사용한다면 ?>
	<a href="<?php echo $self_url;?>?provider=kakao&amp;url=<?php echo $urlencode;?>" class="btn btn-light text-dark social_link" style="background-color: #FFEB00" title="카카오"><img src="<?php echo get_social_skin_url().'/img/sns_kakao_s.png' ?>"> 카카오로 회원가입</a>
	<?php }     //end if ?>
	<?php if(social_service_check('facebook')){     //페이스북 로그인을 사용한다면 ?>
	<a href="<?php echo $self_url;?>?provider=facebook&amp;url=<?php echo $urlencode;?>" class="btn btn-light text-light social_link" style="background-color: #3B579D" title="페이스북"><img src="<?php echo get_social_skin_url().'/img/sns_facebook_s.png' ?>"> 페이스북으로 회원가입</a>
	<?php }     //end if ?>
	<?php if(social_service_check('google')){     //구글 로그인을 사용한다면 ?>
	<a href="<?php echo $self_url;?>?provider=google&amp;url=<?php echo $urlencode;?>" class="btn btn-light text-light social_link" style="background-color: #DB4A3A" title="구글"><img src="<?php echo get_social_skin_url().'/img/sns_google_s.png' ?>"> 구글로 회원가입</a>
	<?php }     //end if ?>
	<?php if(social_service_check('twitter')){     //트위터 로그인을 사용한다면 ?>
	<a href="<?php echo $self_url;?>?provider=twitter&amp;url=<?php echo $urlencode;?>" class="btn btn-light text-light social_link" style="background-color: #1DA1F2" title="트위터"><img src="<?php echo get_social_skin_url().'/img/sns_twitter_s.png' ?>"> 트위터로 회원가입</a>
	<?php }     //end if ?>
	<?php if(social_service_check('payco')){     //페이코 로그인을 사용한다면 ?>
	<a href="<?php echo $self_url;?>?provider=payco&amp;url=<?php echo $urlencode;?>" class="btn btn-light text-light social_link" style="background-color: #DF0B00" title="페이코"><img src="<?php echo get_social_skin_url().'/img/sns_payco_s.png' ?>"> 페이코로 회원가입</a>
	<?php }     //end if ?>
</div>

<hr />

<div class="d-grid gap-2 mb-4">
	<a class="btn btn-primary" href="javascript:changeForm();"><?=$config['cf_title']?>(으)로 회원가입</a>
</div>

<script>
	jQuery(function($){
		$("#default").hide();
	});

	function changeForm()
	{
		$("#default").show();
		$("#social").hide();
	}
</script>

<?php } ?>

<?php if(G5_SOCIAL_USE_POPUP && !$social_pop_once ){
$social_pop_once = true;
?>
<script>
	jQuery(function($){
		$(".sns-wrap").on("click", "a.social_link", function(e){
			e.preventDefault();

			var pop_url = $(this).attr("href");
			var newWin = window.open(
				pop_url, 
				"social_sing_on", 
				"location=0,status=0,scrollbars=1,width=600,height=500"
			);

			if(!newWin || newWin.closed || typeof newWin.closed=='undefined')
				 alert('브라우저에서 팝업이 차단되어 있습니다. 팝업 활성화 후 다시 시도해 주세요.');

			return false;
		});
	});
</script>
<?php } ?>
