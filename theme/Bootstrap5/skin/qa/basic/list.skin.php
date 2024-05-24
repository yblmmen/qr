<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// 선택옵션으로 인해 셀합치기가 가변적으로 변함
$colspan = 6;
if ($is_checkbox) $colspan++;

$list_pages = chg_paging($list_pages);

add_stylesheet('<link rel="stylesheet" href="'.$qa_skin_url.'/custom.css">', 0);
?>

<div>

	<blockquote><h3><?php echo $qaconfig['qa_title'] ?></h3></blockquote>

	<?php if ($qaconfig['qa_category']) { ?>
    <!-- 카테고리 시작 { -->
	<ul class="nav nav-tabs">

		<li class="nav-item">
			<a class="nav-link <?php if($sca=='') echo 'active'; ?> text-dark" href="<?php echo $category_href ?>">전체</a>
		</li>
		<?php
			$categories = explode('|', $qaconfig['qa_category']);
			foreach($categories as $category)
			{
		?>
		<li class="nav-item">
			<a class="nav-link <?php if($category==$sca) echo 'active'; ?> text-dark" href="<?php echo $category_href.'?sca='.urlencode($category); ?>"><?php echo $category ?></a>
		</li>
		<?php
			}
		?>
	</ul>
    <!-- } 카테고리 끝 -->
    <?php } ?>

	<form name="fqalist" id="fqalist" action="./qadelete.php" onsubmit="return fqalist_submit(this);" method="post">
	<input type="hidden" name="stx" value="<?php echo $stx; ?>">
	<input type="hidden" name="sca" value="<?php echo $sca; ?>">
	<input type="hidden" name="page" value="<?php echo $page; ?>">

	<table class="table xs-full mb-4">
		<thead>
			<tr class="d-none d-md-table-row">
				<?php if ($is_checkbox) { ?>
				<th style="width: 2rem;">
					<input type="checkbox" id="chkall" class="form-check-input" onclick="if(this.checked) all_checked(true); else all_checked(false);">
				</th>
				<?php } ?>
				<th class="d-none d-md-table-cell" style="width: 4rem;">번호</th>
				<th>제목</th>
				<th class="d-none d-md-table-cell" style="width: 9rem;">글쓴이</th>
				<th class="d-none d-md-table-cell" style="width: 6rem;">날짜</th>
				<th style="width: 5rem;">상태</th>
			</tr>
		</thead>
		<tbody>
			<?php for ($i=0; $i<count($list); $i++) { ?>
			<tr>
				<?php if ($is_checkbox) { ?>
				<td>
					<input type="checkbox" name="chk_qa_id[]" value="<?php echo $list[$i]['qa_id'] ?>" id="chk_qa_id_<?php echo $i ?>" class="form-check-input">
				</td>
				<?php } ?>
				<td class="d-none d-md-table-cell"><?php echo $list[$i]['num']; ?></td>
				<td>
					<span class="badge bg-primary"><?php echo $list[$i]['category']; ?></span>
					<a href="<?php echo $list[$i]['view_href']; ?>" class="text-dark">
						<?php echo $list[$i]['subject']; ?>
						<?php if ($list[$i]['icon_file']) echo ' <i class="fa fa-download" aria-hidden="true"></i>' ; ?>
					</a>
				</td>
				<td class="d-none d-md-table-cell"><?php echo $list[$i]['name']; ?></td>
				<td class="d-none d-md-table-cell"><?php echo $list[$i]['date']; ?></td>
				<td><span class=" <?php echo ($list[$i]['qa_status'] ? 'txt_done' : 'txt_rdy'); ?>"><?php echo ($list[$i]['qa_status'] ? '완료' : '대기'); ?></span></td>
			</tr>
			<?php } ?>

			<?php if ($i == 0) { echo '<tr><td colspan="'.$colspan.'">게시물이 없습니다.</td></tr>'; } ?>
		</tbody>
	</table>
</div>

<?php if($list_pages) { ?>
<div class="d-flex justify-content-center justify-content-sm-end mb-4">
	<?php echo $list_pages;  ?>
</div>
<?php } ?>

<div class="d-flex flex-sm-row flex-column justify-content-sm-between mb-4">
	<div class="d-flex justify-content-center mb-2 mb-sm-0">
		<?php if($is_checkbox) { ?>
		<div class="btn-group xs-100">
			<button type="submit" name="btn_submit" value="선택삭제" title="선택삭제" onclick="document.pressed=this.value" class="btn btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i> 삭제</button>
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

</form>

<?php if($is_checkbox) { ?>
<noscript>
<p>자바스크립트를 사용하지 않는 경우<br>별도의 확인 절차 없이 바로 선택삭제 처리하므로 주의하시기 바랍니다.</p>
</noscript>
<?php } ?>

<?php if ($is_checkbox) { ?>
<script>
function all_checked(sw) {
    var f = document.fqalist;

    for (var i=0; i<f.length; i++) {
        if (f.elements[i].name == "chk_qa_id[]")
            f.elements[i].checked = sw;
    }
}

function fqalist_submit(f) {
    var chk_count = 0;

    for (var i=0; i<f.length; i++) {
        if (f.elements[i].name == "chk_qa_id[]" && f.elements[i].checked)
            chk_count++;
    }

    if (!chk_count) {
        alert(document.pressed + "할 게시물을 하나 이상 선택하세요.");
        return false;
    }

    if(document.pressed == "선택삭제") {
        if (!confirm("선택한 게시물을 정말 삭제하시겠습니까?\n\n한번 삭제한 자료는 복구할 수 없습니다"))
            return false;
    }

    return true;
}
</script>
<?php } ?>
<!-- } 게시판 목록 끝 -->