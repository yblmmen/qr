<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

switch(substr($_SERVER['SCRIPT_FILENAME'], strlen(G5_PATH)))
{
	case '/bbs/password_lost.php':
	case '/bbs/member_cert_refresh.php':
	case '/bbs/register.php':
	case '/bbs/register_form.php':
	case '/bbs/register_result.php':
	case '/plugin/social/register_member.php':
		include_once(G5_THEME_PATH."/tail.sub.php");
		return;
		break;
}
?>
	<?php if($g5['sidebar']['right']) { ?>
	</div>

	<div class="col-lg-3">
		<?php @include G5_PATH.'/sidebar.right.php'; ?>
	</div>
	<?php } ?>

</div>

<footer id="footer">
	<div class="container py-2">
		<div class="row py-4">
			<div class="col-lg-2 d-flex align-items-center justify-content-center justify-content-lg-start mb-2 mb-lg-0">
				<a href="<?php echo G5_URL ?>" class="logo pr-0 pr-lg-3">
					<img src="<?php echo G5_IMG_URL ?>/logo.png" height="33">
				</a>
			</div>
			<div class="col-lg-5 d-flex align-items-center justify-content-center justify-content-lg-start mb-4 mb-lg-0">
				© Copyright <?php echo date('Y', G5_SERVER_TIME) ?>. All Rights Reserved.
			</div>
			<!-- <div class="col-lg-5 d-flex align-items-center justify-content-center justify-content-lg-end">
				<nav id="sub-menu" class="mb-0">
					<ul class="list-unstyled list-inline">
						 <li class="list-inline-item"><a href="<?php echo get_pretty_url('content', 'company'); ?>" class="text-muted"> 회사소개</a></li>
						<li class="list-inline-item"><a href="<?php echo get_pretty_url('content', 'privacy'); ?>" class="text-muted"> 개인정보</a></li>
						<li class="list-inline-item"><a href="<?php echo get_pretty_url('content', 'provision'); ?>" class="text-muted"> 이용약관</a></li> 
					</ul>
				</nav>
			</div>-->
		</div>
	</div>
</footer>

<script src="<?php echo G5_THEME_URL; ?>/js/common.js?ver=2401251"></script>

<?php
if ($config['cf_analytics']) {
    echo $config['cf_analytics'];
}
?>

<!-- } 하단 끝 -->

<?php
include_once(G5_THEME_PATH."/tail.sub.php");