<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
include_once($board_skin_path."/skin.function.php");
$Search_box = new Frm_search(); // 검색폼 관련 Class

add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css">', 0);
add_stylesheet("<link rel='stylesheet' href='".$board_skin_url."/js/rumiPopup.css'>");
add_javascript('<script src="'.$board_skin_url.'/js/doc.js"></script>');
add_javascript("<script src='".$board_skin_url."/js/jquery.rumiPopup.js'></script>");
add_stylesheet('<link type="text/css" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/themes/base/jquery-ui.css" rel="stylesheet" />', 0);


#### 자료 삭제
if($mode=="drop"){
	for($i=0; $i < count($check); $i++) {
		$sql="delete from {$write_table} where wr_id = '{$check[$i]}' ";
		sql_query($sql);
	}
	goto_url("?bo_table={$bo_table}");
}

$sql_common = " from {$write_table}";
$sql_search = " where (1) ";
$sql_order = " order by wr_id desc ";
$sql = "select count(*) as cnt $sql_common $sql_search $sql_order ";
$row = sql_fetch($sql, true);

$total_count = $row['cnt'];

//한페이지에 보여질 리스트 숫자..
$rows = 100;
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if (!$page) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = "select
			*
        $sql_common
        $sql_search
        $sql_order
		limit
			$from_record, $rows ";
$result = sql_query($sql,true);

// 페이지 처리
$write_pages = get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, '?'.$qstr2.'&amp;page=');

//검색조건
$sfl_array = array(
	'wr_subject' => '모델명',
	'wr_content' => '제조사', 
	'wr_1' => '시리얼'
);

// 선택자
$grid_list	= "list";		// 그리드가 그려질 식별자 ID
$grid_pager	= "pager";		// 그리드 페이지가 표시될 식별자 ID
$grid_sch	= "searchbox";	// 그리드 검색박스 식별자 ID
$grid_form	= "fmSearch";	// 그리드 상단 검색 FORM NAMEE
$grid_js_url= $board_skin_url."/js/car.js?ver=".G5_JS_VER; // JS 파일명 및 경로
$edit_url	= $board_skin_url."/car_data.php?bo_table=".$bo_table; // DATA 파일명 및 경로
?>
<script>
// ****.js 파일에서 사용될 전역 변수
var Grid = {
	search:"<?php echo '#'.$grid_sch; ?>",
	list : "<?php echo '#'.$grid_list; ?>",
	pager: "<?php echo '#'.$grid_pager; ?>",
	form : "<?php echo '#'.$grid_form; ?>",
	editUrl : "<?php echo $edit_url; ?>",
	bo_table : "<?php echo $bo_table; ?>",
	board_skin_url : "<?php echo $board_skin_url; ?>"
}

// doc.js 파일 함수에서 사용될 전역 변수
var cfg = Grid;
//엑셀 저장을 위한 주소
var theme_url  = "<?php echo G5_THEME_URL ?>/skin/board/syndgate_car";
</script>

<link rel="stylesheet" type="text/css" media="screen" href="<?php echo $board_skin_url; ?>/js/css/ui.jqgrid.css" />
<script type="text/ecmascript" src="<?php echo $board_skin_url; ?>/js/js/i18n/grid.locale-kr.js"></script>
<script type="text/ecmascript" src="<?php echo $board_skin_url; ?>/js/js/jquery.jqGrid.js"></script>
<script type="text/ecmascript" src="<?php echo $grid_js_url;?>"></script>


<div id="grid_basic">
	<div id="<?php echo $grid_sch;?>" class="grid_fm_search">
		<form name="<?php echo $grid_form;?>" id="<?php echo $grid_form;?>">
		<input type="hidden" name="bo_table" value="<?php echo $bo_table; ?>" />
		<table>
			<colgroup>
				<col width="9%" />
				<col width="16%" />
				<col width="9%" />
				<col width="16%" />
				<col width="9%" />
				<col width="16%" />
				<col width="9%" />
				<col width="16%" />
			</colgroup>
			<tr>
				<th>검색</th>
				<td colspan="7">
					<?php
					$Search_box->var_mode('A', $sfl_array);
					echo $Search_box->Select('', 'sfl', 'sfl', 'sfl', $sfl);
					?>
					<input type="text" name="stx" id="stx" class="stx" size="20" maxlength="40" value="<?php echo $stx; ?>" />
					<button type="button" id="btn_submit" class="btn_submit" title="검색"><i class='fa fa-search' aria-hidden='true'></i></button>
					<button type="button" id="reset" class="reset" title='초기화'><i class="fa fa-refresh" aria-hidden="true"></i></button>

					<button type="button" name="new_member" class="new" id="new_member" onclick="memberadd('')";>신규등록</button>
					<button type="button" name="doc_list" class="new" id="doc_list">자산번호목록</button>
					<button type="button" name="doc_xls" class="new" id="doc_xls">엑셀저장</button>
				</td>
			</tr>
		</table>
	</form>
	</div>
</div>

<table id="<?php echo $grid_list;?>"></table>
<div id="<?php echo $grid_pager;?>"></div>