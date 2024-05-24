<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

include G5_THEME_PATH.'/tail.php';  return;

$admin = get_admin("super");
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
				© Copyright 2019. All Rights Reserved.
			</div>
			<div class="col-lg-5 d-flex align-items-center justify-content-center justify-content-lg-end">
				<nav id="sub-menu" class="mb-0">
					<ul class="list-unstyled list-inline">
						<li class="list-inline-item"><a href="<?php echo get_pretty_url('content', 'company'); ?>" class="text-muted"> 회사소개</a></li>
						<li class="list-inline-item"><a href="<?php echo get_pretty_url('content', 'privacy'); ?>" class="text-muted"> 개인정보</a></li>
						<li class="list-inline-item"><a href="<?php echo get_pretty_url('content', 'provision'); ?>" class="text-muted"> 이용약관</a></li>
					</ul>
				</nav>
			</div>
		</div>
	</div>
</footer>

<span><b>회사명</b> <?php echo $default['de_admin_company_name']; ?></span>
<span><b>주소</b> <?php echo $default['de_admin_company_addr']; ?></span><br>
<span><b>사업자 등록번호</b> <?php echo $default['de_admin_company_saupja_no']; ?></span>
<span><b>대표</b> <?php echo $default['de_admin_company_owner']; ?></span>
<span><b>전화</b> <?php echo $default['de_admin_company_tel']; ?></span>
<span><b>팩스</b> <?php echo $default['de_admin_company_fax']; ?></span><br>
<!-- <span><b>운영자</b> <?php echo $admin['mb_name']; ?></span><br> -->
<span><b>통신판매업신고번호</b> <?php echo $default['de_admin_tongsin_no']; ?></span>
<span><b>개인정보 보호책임자</b> <?php echo $default['de_admin_info_name']; ?></span><br>
<?php if ($default['de_admin_buga_no']) echo '<span><b>부가통신사업신고번호</b> '.$default['de_admin_buga_no'].'</span>'; ?>
<div id="ft_copy">Copyright &copy; 2001-2013 <?php echo $default['de_admin_company_name']; ?>. All Rights Reserved.</div>

<?php
$sec = get_microtime() - $begin_time;
$file = $_SERVER['SCRIPT_NAME'];

if ($config['cf_analytics']) {
    echo $config['cf_analytics'];
}
?>

<script src="<?php echo G5_JS_URL; ?>/sns.js"></script>
<!-- } 하단 끝 -->

<?php
include_once(G5_THEME_PATH.'/tail.sub.php');