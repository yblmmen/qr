<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

$sql = " select * from {$g5['new_win_table']}
          where '".G5_TIME_YMDHIS."' between nw_begin_time and nw_end_time
            and nw_device IN ( 'both', 'pc' )
          order by nw_id asc ";
$result = sql_query($sql, false);
?>

<?php
for ($i=0; $nw=sql_fetch_array($result); $i++)
{
    // 이미 체크 되었다면 Continue
	if (isset($_COOKIE["hd_pops_{$nw['nw_id']}"]) && $_COOKIE["hd_pops_{$nw['nw_id']}"])
        continue;
?>

<!-- The Modal -->
<div class="modal fade" id="hd_pops_<?php echo $nw['nw_id'] ?>">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<!--
			<div class="modal-header">
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			-->
			<div class="modal-body">
				<?php echo conv_content($nw['nw_content'], 1); ?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-sm btn-secondary hd_pops_reject hd_pops_<?php echo $nw['nw_id']; ?> <?php echo $nw['nw_disable_hours']; ?>" data-dismiss="modal"><strong><?php echo $nw['nw_disable_hours']; ?></strong>시간 동안 다시 열람하지 않습니다.</button>
			</div>

		</div>
	</div>
</div>

<script>
	var hd_pops_<?php echo $nw['nw_id'] ?> = new bootstrap.Modal($("#hd_pops_<?php echo $nw['nw_id'] ?>"));
	hd_pops_<?php echo $nw['nw_id'] ?>.show();
</script>

<?php }
?>

<script>
$(function() {
    $(".hd_pops_reject").click(function() {
		var id = $(this).attr('class').split(' ');
        var ck_name = id[4];
        var exp_time = parseInt(id[5]);
        set_cookie(ck_name, 1, exp_time, g5_cookie_domain);

		(new Function(ck_name+".hide();"))();
    });
});
</script>
<!-- } 팝업레이어 끝 -->