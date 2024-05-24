<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가
include_once(G5_LIB_PATH.'/thumbnail.lib.php');

add_stylesheet('<link rel="stylesheet" href="'.$qa_skin_url.'/custom.css">', 0);

$mb_info = get_member_info($view['mb_id'], $view['qa_name'], $view['qa_email']);
//$view['datetime'] = substr($view['wr_datetime'],0,10) == G5_TIME_YMD ? substr($view['wr_datetime'], 11, 8) : substr($view['wr_datetime'], 2, 8);
?>

<script src="<?php echo G5_JS_URL; ?>/viewimageresize.js"></script>

<div>

	<blockquote><h3><?php echo $view['subject']; ?></h3></blockquote>

	<div class="d-flex mb-2">
		<img class="view-icon rounded me-3" src="<?php echo $mb_info['img'] ?>">
		<div>
			<ul class="list-inline mb-0">
				<li class="list-inline-item">
					<a href="#" data-bs-toggle="dropdown" class="text-dark"><?php echo get_text($view['name']); ?></a>
					<?php echo $mb_info['menu'] ?>
				</li>
			</ul>
			<ul class="list-inline text-muted small pt-1">
				<li class="list-inline-item"><i class="fa fa-clock-o"></i> <?php echo $view['datetime'] ?></li>
				<?php if($view['email']) { ?>
				<li class="list-inline-item"><i class="fa fa-envelope"></i> <?php echo $view['email']; ?></li>
				<?php } ?>
				<?php if($view['hp']) { ?>
				<li class="list-inline-item"><i class="fa fa-phone-square"></i> <?php echo $view['hp']; ?></li>
				<?php } ?>
			</ul>
		</div>
	</div>

	<?php if($view['img_count']) { ?>
	<div class="mb-4">
		<?php
			for ($i=0; $i<=$view['img_count']; $i++)
			{
				//echo $view['img_file'][$i];
				echo get_view_thumbnail($view['img_file'][$i], $qaconfig['qa_image_width']);
			}
		?>
	</div>
	<?php } ?>

	<!-- 본문 내용 시작 { -->
	<div class="mb-4">
		<?php echo get_view_thumbnail($view['content'], $qaconfig['qa_image_width']); ?>
	</div>
	<!-- } 본문 내용 끝 -->

	<?php if($view['download_count']) { ?>
	<ul class="list-group mb-4">
		<!-- 첨부파일 -->
		<?php  for ($i=0; $i<$view['download_count']; $i++) { ?>
		<li class="list-group-item">
			<i class="fa fa-download"></i>
			<a href="<?php echo $view['download_href'][$i];  ?>" class="text-dark"><?php echo $view['download_source'][$i] ?></a>
		</li>
		<?php } ?>
	</ul>
	<?php } ?>

	<div class="d-flex flex-sm-row flex-column justify-content-sm-between mb-4">
		<div class="d-flex justify-content-center mb-2 mb-sm-0">
			<?php if($update_href||$delete_href) { ?>
			<div class="btn-group xs-100">
				<?php if ($update_href) { ?><a href="<?php echo $update_href ?>" class="btn btn-danger" title="수정"><i class="fa fa-pencil-square-o"></i> 수정</a><?php } ?>
				<?php if ($delete_href) { ?><a href="<?php echo $delete_href ?>" class="btn btn-danger" onclick="del(this.href); return false;" title="삭제"><i class="fa fa-trash-o"></i> 삭제</a><?php } ?>
			</div>
			<?php } ?>
		</div>
		<div class="d-flex justify-content-center">
			<div class="btn-group xs-100">
				<?php if ($list_href) { ?>
				<a href="<?php echo $list_href ?>" class="btn btn-primary" title="목록"><i class="fa fa-list" aria-hidden="true"></i> 목록</a><?php } ?>
				<?php if ($write_href) { ?>
				<a href="<?php echo $write_href ?>" class="btn btn-primary" title="문의등록"><i class="fa fa-pencil" aria-hidden="true"></i> 문의하기</a><?php } ?>
			</div>
		</div>
	</div>

	<?php if ($prev_href || $next_href) { ?>
	<div>
	<ul class="list-group mb-4">
		<?php if ($prev_href) { ?><li class="list-group-item"><small class="text-muted"><i class="fa fa-caret-up"></i> 이전글</small> <a href="<?php echo $prev_href ?>" class="text-dark"><?php echo $prev_qa_subject;?></a></li><?php } ?>
		<?php if ($next_href) { ?><li class="list-group-item"><small class="text-muted"><i class="fa fa-caret-down"></i> 다음글</small> <a href="<?php echo $next_href ?>" class="text-dark"><?php echo $next_qa_subject;?></a></li><?php } ?>
	</ul>
	</div>
	<?php } ?>
</div>

<?php
// 질문글에서 답변이 있으면 답변 출력, 답변이 없고 관리자이면 답변등록폼 출력
if(!$view['qa_type']) {
	if($view['qa_status'] && $answer['qa_id'])
		include_once($qa_skin_path.'/view.answer.skin.php');
	else
		include_once($qa_skin_path.'/view.answerform.skin.php');
}
?>

<script>
$(function() {
    $("a.view_image").click(function() {
        window.open(this.href, "large_image", "location=yes,links=no,toolbar=no,top=10,left=10,width=10,height=10,resizable=yes,scrollbars=no,status=no");
        return false;
    });
});
</script>