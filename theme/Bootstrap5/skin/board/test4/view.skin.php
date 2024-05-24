<style type="text/css">
.tg  {border-collapse:collapse;border-spacing:0;border-color:#999;}
.tg td{font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:#999;color:#444;}
.tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:#999;color:#fff;background-color:#26ADE4;}
.tg .tg-7un6{background-color:#ffffff;color:#000000;text-align:center;vertical-align:top}
.tg .tg-nrw1{font-size:10px;text-align:center;vertical-align:top}
.tg .tg-baqh{text-align:center;vertical-align:top;height:30px}
.tg .tg-nob6{color:#000000;text-align:center;vertical-align:top}
.tg .tg-lqy6{text-align:center;vertical-align:top}
.tg .tg-qhmr{font-size:28px;background-color:#c0c0c0;color:#ffffff;text-align:center}
.tg .tg-qtbq{font-size:10px;background-color:#ffffff;color:#000000;text-align:center;vertical-align:top}
.tg .tg-fefd{color:#000000;vertical-align:top;text-align:center}
.tg .tg-25al{font-size:10px;vertical-align:top;}
.tg .tg-ddj9{background-color:#c0c0c0;color:#000000;text-align:center;vertical-align:middle;height:40px;}
.tg .tg-6qw1{background-color:#c0c0c0;text-align:center;vertical-align:middle;}
.tg .tg-yw4l{vertical-align:middle;text-align:center}
.tg .tg-qems{background-color:#c0c0c0;color:#000000;text-align:center}
</style>
<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가
$query = sql_query("SELECT * FROM g6_member where mb_id='{$view['wr_subject']}'");
while($row=sql_fetch_array($query)){
	$arr[0]=$row[mb_name];
}
$query1 = sql_query("SELECT * FROM g6_member where mb_id='{$view['wr_11']}'");
while($row1=sql_fetch_array($query1)){
	$arr[1]=$row1[mb_name];
}
$query2 = sql_query("SELECT * FROM g6_member where mb_id='{$view['wr_13']}'");
while($row2=sql_fetch_array($query2)){
	$arr[2]=$row2[mb_name];
}

if($wr_datetime !=$view['wr_datetime'] ||$wr_id !=$view['wr_id']){
            alert(aslang('alert', 'is_bo_group'),'./board.php?bo_table='.$bo_table);
}
$attach_list = '';
if ($view['link']) {
	// 링크
	for ($i=1; $i<=count($view['link']); $i++) {
		if ($view['link'][$i]) {
			$attach_list .= '<a class="list-group-item break-word" href="'.$view['link_href'][$i].'" target="_blank">';
			$attach_list .= '<span class="label label-warning pull-right view-cnt">'.number_format($view['link_hit'][$i]).'</span>';
			$attach_list .= '<i class="fa fa-link"></i> '.cut_str($view['link'][$i], 70).'</a>'.PHP_EOL;
		}
	}
}

// 가변 파일
$j = 0;
for ($i=0; $i<count($view['file']); $i++) {
 if (isset($view['file'][$i]['source']) && $view['file'][$i]['source']) {
		if ($board['bo_download_point'] < 0 && $j == 0) {
			$attach_list .= '<a class="list-group-item"><i class="fa fa-bell red"></i> 다운로드시 <b>'.number_format(abs($board['bo_download_point'])).'</b>'.AS_MP.' 차감 (최초 1회 / 재다운로드시 차감없음)</a>'.PHP_EOL;
		}
		$file_tooltip = '';
		if($view['file'][$i]['content']) {
			$file_tooltip = ' data-original-title="'.strip_tags($view['file'][$i]['content']).'" data-toggle="tooltip"';
		}
		$attach_list .= '<a class="list-group-item break-word view_file_download at-tip" href="'.$view['file'][$i]['href'].'"'.$file_tooltip.'>';
		$attach_list .= '<span class="label label-primary pull-right view-cnt">'.number_format($view['file'][$i]['download']).'</span>';
		$attach_list .= '<i class="fa fa-download"></i> '.$view['file'][$i]['source'].' ('.$view['file'][$i]['size'].') &nbsp;';
		$attach_list .= '<span class="en font-11 text-muted"><i class="fa fa-clock-o"></i> '.apms_datetime(strtotime($view['file'][$i]['datetime']), "Y.m.d").'</span></a>'.PHP_EOL;
		$j++;
	}
}

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css" media="screen">', 0);

?>
<?php if($boset['video']) { ?>
	<style>.view-wrap .apms-autowrap { max-width:<?php echo (G5_IS_MOBILE) ? '100%' : $boset['video'];?> !important;}</style>
<?php } ?>
<script src="<?php echo G5_JS_URL; ?>/viewimageresize.js"></script>

<div class="view-wrap<?php echo (G5_IS_MOBILE) ? ' view-mobile font-14' : '';?>">
	<h1><?php echo cut_str(get_text($view['wr_subject']), 70); ?></h1>
	<div class="panel panel-default view-head<?php echo ($attach_list) ? '' : ' no-attach';?>">
		<div class="panel-heading">
			<div class="font-12 text-muted">
				<i class="fa fa-user"></i>
				<?php echo $view['name']; //등록자 ?><?php echo ($is_ip_view) ? '<span class="print-hide hidden-xs">&nbsp;('.$ip.')</span>' : ''; ?>
				<?php if($view['ca_name']) { ?>
					<span class="hidden-xs">
						<span class="sp"></span>
						<i class="fa fa-tag"></i>
						<?php echo $view['ca_name']; //분류 ?>
					</span>
				<?php } ?>

				<span class="sp"></span>
				<i class="fa fa-comment"></i>
				<?php echo ($view['wr_comment']) ? '<b class="red">'.number_format($view['wr_comment']).'</b>' : 0; //댓글수 ?>

				<span class="pull-right">
					<i class="fa fa-clock-o"></i>
					<?php echo apms_date($view['date'], 'orangered'); //시간 ?>
				</span>
			</div>
		</div>
	   <?php
			if($attach_list) {
				echo '<div class="list-group font-12">'.$attach_list.'</div>'.PHP_EOL;
			}
		?>
	</div>

	<?php if ($is_torrent) echo apms_addon('torrent-basic'); // 토렌트 파일정보 ?>

	

	<div class="view-content">

<table class="tg" style="undefined;table-layout: fixed; width: auto">
<colgroup>
<col style="width: 83px">
<col style="width: 83px">
<col style="width: 83px">
<col style="width: 83px">
<col style="width: 83px">
<col style="width: 83px">
<col style="width: 29px">
<col style="width: 42px">
<col style="width: 42px">
<col style="width: 83px">
<col style="width: 83px">
</colgroup>
  <tr>
    <th class="tg-qhmr" colspan="6" rowspan="2">근태 처리신청서</th>
    <td class="tg-nrw1" rowspan="2">관리부서</td>
    <td class="tg-baqh" colspan="2">담당</td>
    <td class="tg-baqh">팀장</td>
    <td class="tg-baqh">임원</td>
  </tr>
  <tr>
    <td class="tg-yw4l" colspan="2"></td>
    <td class="tg-yw4l"></td>
    <td class="tg-yw4l"></td>
  </tr>
  <tr>
    <td class="tg-25al" colspan="11">※ 아래와 같이 근태 처리원을 제출하오니 허락하여 주시기 바랍니다.</td>
  </tr>
   <tr>
    <td class="tg-ddj9" colspan="2">소속</td>
    <td class="tg-lqy6" colspan="3"><?=$view['wr_5']?></td>
    <td class="tg-6qw1" colspan="2">작성일자</td>
    <td class="tg-yw4l" colspan="4"><?=$view['wr_12']?></td>
  </tr>
  <tr>
      <td class="tg-ddj9" colspan="2">직위</td>
    <td class="tg-lqy6" colspan="3"><?=$view['wr_6']?></td>
    <td class="tg-6qw1" colspan="2" rowspan="2">근태일자</td>
    <td class="tg-yw4l" colspan="4" rowspan="2"><?=$view['wr_1']?>&nbsp;시간:<?=$view['wr_3']?>&nbsp;<br>~<?=$view['wr_2']?>&nbsp;시간:<?=$view['wr_4']?></td>
  </tr>
  <tr>
    <td class="tg-ddj9" colspan="2">성명(대리인)</td>
    <td class="tg-lqy6" colspan="3" ><?=$arr[0]?></td>

  </tr>
  <tr>
    <td class="tg-ddj9" colspan="2">구분</td>
    <td class="tg-yw4l" colspan="3"><?=$view['wr_7']?></td>
    <td class="tg-6qw1" colspan="2" rowspan="2">기&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;간</td>
    <td class="tg-yw4l" colspan="4" style="text-align:right"><?php echo ($view['wr_16']>=1) ? $view['wr_16']:"";?>일간&nbsp;&nbsp;&nbsp;&nbsp;</td>

  </tr>
  <tr>
    <td class="tg-ddj9" colspan="2">사유</td>
    <td class="tg-yw4l" colspan="3"><?=$view['wr_content']?></td>
	    <td class="tg-yw4l" colspan="4" style="text-align:right"><?php echo ($view['wr_16']=='0.5') ? "4":"";?>시간&nbsp;&nbsp;&nbsp;&nbsp;</td>

  </tr>
    <tr>
    <td class="tg-qems" colspan="2" >행선지</td>
    <td class="tg-yw4l" colspan="3" ><?=$view['wr_8']?></td>
    <td class="tg-baqh" >반장확인</td>
	    <td class="tg-nrw1" rowspan="2">담당부서</td>
    <td class="tg-baqh" colspan="2">담당</td>
    <td class="tg-baqh">팀장</td>
    <td class="tg-baqh">임원</td>
	</tr>
  <tr>


    <td class="tg-qems" colspan="2" >연락처</td>
    <td class="tg-yw4l" colspan="3" ><?=$view['wr_9']?></td>
    <td class="tg-yw4l" ></td>
    <td class="tg-yw4l" colspan="2" style="font-size:11px;vertical-align:bottom;">
	
	<?if (is_file("../data/apms/sign/".$view['mb_id'].".jpg")){ ?>
	<img src="../../../data/apms/sign/<?=$view['mb_id']?>.jpg" style="max-width: 70px; height: auto;"><br> 
	<?php } 
	else{?>
	<img src="../../../data/apms/sign/admin.jpg" style="max-width: 70px; height: auto;"><br> 
	<?php } 
	?>

	<?=$view['wr_10']?></td>	
	<?php if ($view['wr_11'] && $view['wr_12']) { ?>
	<td class="tg-yw4l" style="font-size:11px;vertical-align:bottom"><img src="../../../data/apms/sign/<?=$view['wr_11']?>.jpg" style="max-width: 80px; height: auto;"><br> <?=$view['wr_12']?></td>	
	<?php } 
	else{?>
    <td class="tg-yw4l" style="font-size:11px;"><?=$arr[1]?></td>	
	<?php }
	?>
    <?php if ($view['wr_13'] && $view['wr_14']) { ?>
	<td class="tg-yw4l" style="font-size:11px;vertical-align:bottom"><img src="../../../data/apms/sign/<?=$view['wr_13']?>.jpg" style="max-width: 80px; height: auto;"><br> <?=$view['wr_14']?></td>	
	<?php } 
	else{?>
    <td class="tg-yw4l" style="font-size:11px;"><?=$arr[2]?></td>	
	<?php }
	?>
  </tr>
  <tr>

  </tr>
</table>
	</div>


	<?php
		// 이미지 하단 출력
		if($v_img_count && $is_img_tail) {
			echo '<div class="view-img">'.PHP_EOL;
			for ($i=0; $i<=count($view['file']); $i++) {
				if ($view['file'][$i]['view']) {
					echo get_view_thumbnail($view['file'][$i]['view']);
				}
			}
			echo '</div>'.PHP_EOL;
		}
	?>

	<?php if ($good_href || $nogood_href) { ?>
		<div class="print-hide view-good-box">
			<?php if ($good_href) { ?>
				<span class="view-good">
					<a href="#" onclick="apms_good('<?php echo $bo_table;?>', '<?php echo $wr_id;?>', 'good', 'wr_good'); return false;">
						<b id="wr_good"><?php echo number_format($view['wr_good']) ?></b>
						<br>
						<i class="fa fa-thumbs-up"></i>
					</a>
				</span>
			<?php } ?>
			<?php if ($nogood_href) { ?>
				<span class="view-nogood">
					<a href="#" onclick="apms_good('<?php echo $bo_table;?>', '<?php echo $wr_id;?>', 'nogood', 'wr_nogood'); return false;">
						<b id="wr_nogood"><?php echo number_format($view['wr_nogood']) ?></b>
						<br>
						<i class="fa fa-thumbs-down"></i>
					</a>
				</span>
			<?php } ?>
		</div>
		<p></p>
	<?php } ?>

	<?php if ($is_tag) { // 태그 ?>
		<p class="view-tag font-12"><i class="fa fa-tags"></i> <?php echo $tag_list;?></p>
	<?php } ?>
<form name="approval" method="post">
<input type="hidden" name="state" value="<?php echo $view['wr_subject'] ?>">
<input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
<input type="hidden" name="wr_id" value="<?php echo $wr_id ?>">
<input type="hidden" name="wr_15" value="<?php echo $view['wr_15'] ?>">
<input type="hidden" name="wr_11" value="<?php echo $view['wr_11'] ?>">
<input type="hidden" name="wr_13" value="<?php echo $view['wr_13'] ?>">
<input type="hidden" name="mb_id" value="<?php echo $view['mb_id'] ?>">
<input type="hidden" name="wr_name" value="<?php echo $view['wr_name'] ?>">
<input type="hidden" name="wr_datetime" value="<?php echo $view['wr_datetime'] ?>">
	<div class="print-hide view-icon">
		<div class="pull-right">
			<div class="form-group">
				<?php if ($is_admin||$member[mb_level]>8||($member[mb_id]==$view['wr_11']&&$view['wr_12']==''&&$view['wr_15']=='검토대기')||($member[mb_id]==$view['wr_13']&&$view['wr_14']==''&&$view['wr_15']=='승인대기') ){ ?>
				<button onclick="javascript:reject()" class="btn btn-black btn-xs"><i class="fa fa-reply" aria-hidden="true"></i> <span class="hidden-xs">반려</span></button>
				<button onclick="javascript:approve()" class="btn btn-black btn-xs"><i class="fa fa fa-pencil-square-o" aria-hidden="true"></i> <span class="hidden-xs">결재</span></button>
				<?php } ?>
				<button onclick="apms_print('','<?php echo $view['wr_datetime'] ?>');" class="btn btn-black btn-xs"><i class="fa fa-print"></i> <span class="hidden-xs">프린트</span></button>
				<?php if ($is_admin) { ?>
					<?php if ($view['is_lock']) { // 글이 잠긴상태이면 ?>
						<button onclick="apms_shingo('<?php echo $bo_table;?>', '<?php echo $wr_id;?>', 'unlock');" class="btn btn-black btn-xs"><i class="fa fa-unlock"></i> <span class="hidden-xs">해제</span></button>
					<?php } else { ?>
						<button onclick="apms_shingo('<?php echo $bo_table;?>', '<?php echo $wr_id;?>', 'lock');" class="btn btn-black btn-xs"><i class="fa fa-lock"></i> <span class="hidden-xs">잠금</span></button>
					<?php } ?>
				<?php } ?>
			</div>
		</div>
		<div class="pull-left">
			<div class="form-group">
				<?php include_once(G5_SNS_PATH."/view.sns.skin.php"); // SNS ?>
			</div>
		</div>
		<div class="clearfix"></div>
	</div>
</form>
	<?php if($is_signature) echo apms_addon('sign-basic'); // 회원서명 ?>

	<h3 class="view-comment">Comments</h3>
	<?php include_once('./view_comment.php'); ?>

	<div class="clearfix"></div>

	<div class="print-hide view-btn text-right">
		<div class="btn-group">
			<?php if ($delete_href&&($view['wr_15']=="검토대기"||$view['wr_15']=="결재반려"||$member[mb_level]>8||$is_admin)) { ?>
				<a href="<?php echo $delete_href ?>" class="btn btn-black btn-sm" title="삭제" onclick="del(this.href); return false;">
					<i class="fa fa-times"></i><span class="hidden-xs"> 삭제</span>
				</a>
			<?php } ?>
			<?php if ($update_href&&($view['wr_15']=="검토대기"||$view['wr_15']=="결재반려"||$is_admin)) { ?>
				<a href="<?php echo $update_href ?>" class="btn btn-black btn-sm" title="수정">
					<i class="fa fa-plus"></i><span class="hidden-xs"> 수정</span>
				</a>
			<?php } ?>
			<?php if ($search_href) { ?>
				<a href="<?php echo $search_href ?>" class="btn btn-black btn-sm">
					<i class="fa fa-search"></i><span class="hidden-xs"> 검색</span>
				</a>
			<?php } ?>
			<a href="<?php echo $list_href ?>" class="btn btn-black btn-sm">
				<i class="fa fa-bars"></i><span class="hidden-xs"> 목록</span>
			</a>
			<?php if ($write_href) { ?>
				<a href="<?php echo $write_href ?>" class="btn btn-color btn-sm">
					<i class="fa fa-pencil"></i><span class="hidden-xs"> 글쓰기</span>
				</a>
			<?php } ?>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<script>
function board_move(href){
	window.open(href, "boardmove", "left=50, top=50, width=500, height=550, scrollbars=1");
}
$(function() {
	$("a.view_image").click(function() {
		window.open(this.href, "large_image", "location=yes,links=no,toolbar=no,top=10,left=10,width=10,height=10,resizable=yes,scrollbars=no,status=no");
		return false;
	});
	<?php if ($board['bo_download_point'] < 0) { ?>
	$("a.view_file_download").click(function() {
		if(!g5_is_member) {
			alert("다운로드 권한이 없습니다.\n회원이시라면 로그인 후 이용해 보십시오.");
			return false;
		}

		var msg = "파일을 다운로드 하시면 포인트가 차감(<?php echo number_format($board['bo_download_point']) ?>점)됩니다.\n\n포인트는 게시물당 한번만 차감되며 다음에 다시 다운로드 하셔도 중복하여 차감하지 않습니다.\n\n그래도 다운로드 하시겠습니까?";

		if(confirm(msg)) {
			var href = $(this).attr("href")+"&js=on";
			$(this).attr("href", href);

			return true;
		} else {
			return false;
		}
	});
	<?php } ?>
});
</script>
<script>
function approve()
{
	var f = document.approval; 
    if (!confirm("결재 진행 하시겠습니까?")) 
        return; 
    f.action = "<?=$board_skin_url?>/approve.php"; 
    f.submit(); 
}
</script>
<script>
function reject()
{
	var f = document.approval; 
    if (!confirm("결재 반려 하시겠습니까?")) 
        return; 
    f.action = "<?=$board_skin_url?>/reject.php"; 
    f.submit(); 
}
</script>
