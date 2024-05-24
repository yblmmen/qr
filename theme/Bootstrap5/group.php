<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

$g5['title'] = $group['gr_subject'];
include_once(G5_THEME_PATH.'/head.php');
include_once(G5_LIB_PATH.'/latest.lib.php');
?>

<div id="latest" class="row">
<?php
//  최신글
$sql = " select bo_table, bo_subject
            from {$g5['board_table']}
            where gr_id = '{$gr_id}'
              and bo_list_level <= '{$member['mb_level']}'
              and bo_device <> 'mobile' ";
if(!$is_admin)
    $sql .= " and bo_use_cert = '' ";
$sql .= " order by bo_order ";
$result = sql_query($sql);
for ($i=0; $row=sql_fetch_array($result); $i++) {
    $lt_style = "";
    if ($i%2==1) $lt_style = "margin-left:2%";
    else $lt_style = "";
?>
	<div class="col-md-6">
		<?=latest('theme/basic', $row['bo_table'], 5)?>
	</div>
<?php
}
?>
<!-- 메인화면 최신글 끝 -->
</div>
<?php
include_once(G5_THEME_PATH.'/tail.php');
