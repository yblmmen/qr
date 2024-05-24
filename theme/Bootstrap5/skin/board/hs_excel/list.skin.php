<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
//기능파일 인클루드
include_once("$board_skin_path/list_num.php");
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php');

// 선택옵션으로 인해 셀합치기가 가변적으로 변함
$colspan = 9;

if ($is_checkbox) $colspan++;
if ($is_good) $colspan++;
if ($is_nogood) $colspan++;

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css">', 0);
?>

<h2 id="container_title" style="margin-top:30px;"><?php echo $board['bo_subject'] ?></h2>

<!-- 게시판 목록 시작 { -->
<div id="bo_list" style="width:<?php echo $width; ?>; overflow:auto;">

    <!-- 게시판 카테고리 시작 { -->
    <?php if ($is_category) { ?>
    
    <?php } ?>
    <!-- } 게시판 카테고리 끝 -->

    <!-- 게시판 페이지 정보 및 버튼 시작 { -->
    <div class="bo_fx">
        <div id="bo_list_total">
            <span>Total <?php echo number_format($total_count) ?>건</span>
            <?php echo $page ?> 페이지
			<form name="fsearch" method="get" style="">
			<input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
			<input type="hidden" name="sca" value="<?php echo $sca ?>">
			<input type="hidden" name="sop" value="and">
			<input type="hidden" name="sfl" value="wr_subject||wr_content||wr_1||wr_2||wr_3||wr_4||wr_5">
			<div style="margin:5px 0;">
				<input type="text" id="fr_date"  name="fr_date" value="<?php echo $fr_date; ?>" class="frm_input" size="10" maxlength="10" placeholder="시작일" > ~
				<input type="text" id="to_date"  name="to_date" value="<?php echo $to_date; ?>" class="frm_input" size="10" maxlength="10" placeholder="종료일" > 
				<input type="text" name="stx" value="<?php echo stripslashes($stx) ?>" id="stx" class="frm_input" size="15" maxlength="20" placeholder="필드검색" >
			</div>
			<div style="margin:5px 0;">
				<button type="button" onclick="javascript:set_date('오늘');">오늘</button>
				<button type="button" onclick="javascript:set_date('어제');">어제</button>
				<button type="button" onclick="javascript:set_date('이번주');">이번주</button>
				<button type="button" onclick="javascript:set_date('이번달');">이번달</button>
				<button type="button" onclick="javascript:set_date('지난주');">지난주</button>
				<button type="button" onclick="javascript:set_date('지난달');">지난달</button>
				<button type="button" onclick="javascript:set_date('전체');">전체</button>
				<input type="submit" value="검색" class="btn_submit" style="padding:6px 7px 5px 7px;">
				
				<a class="ex_btn"  href="<?php echo $board_skin_url?>/excel_down.php?bo_table=<?php echo $bo_table?>&fr_date=<?php echo $_GET[fr_date];?>&to_date=<?php echo $_GET[to_date];?>&stx=<?php echo $stx;?>">엑셀다운</a>
				<a class="ex_btn"  href="<?php echo $board_skin_url?>/excel_up1.php?bo_table=<?php echo $bo_table?>">엑셀업</a>
				<a class="ex_btn"  href="<?php echo $board_skin_url?>/excel_up3.php?bo_table=<?php echo $bo_table?>">서버업</a>
			</div>
			</form>
			
			
			
        </div>
    </div>
    <!-- } 게시판 페이지 정보 및 버튼 끝 -->

    <form name="fboardlist" id="fboardlist" action="./board_list_update.php" onsubmit="return fboardlist_submit(this);" method="post">
    <input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
    <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
    <input type="hidden" name="stx" value="<?php echo $stx ?>">
    <input type="hidden" name="spt" value="<?php echo $spt ?>">
    <input type="hidden" name="sca" value="<?php echo $sca ?>">
    <input type="hidden" name="page" value="<?php echo $page ?>">
    <input type="hidden" name="sw" value="">
	<input type="hidden" name="jogun" value="<?php echo $_GET[jogun]?>">
	<input type="hidden" name="fr_date" value="<?php echo $_GET[fr_date]?>">
	<input type="hidden" name="to_date" value="<?php echo $_GET[to_date]?>">

    <div class="tbl_head01 tbl_wrap">
        <table>
        <caption><?php echo $board['bo_subject'] ?> 목록</caption>
        <thead>
        <tr>
            <?php if ($is_checkbox) { ?>
            <th scope="col" style="padding:0 7px;">
                <label for="chkall" class="sound_only">현재 페이지 게시물 전체</label>
                <input type="checkbox" id="chkall" onclick="if (this.checked) all_checked(true); else all_checked(false);">
            </th>
            <?php } ?>
				<th scope="col">No</th>	
				<th scope="col">Date</th>	
				<th scope="col">자산번호</th>
				<th scope="col">제조사</th>					 <!-- 1 -->
				<th scope="col">모델명</th>					 <!-- 2 -->
				<th scope="col">시리얼</th>					 <!-- 3 -->
				<th scope="col">내용</th>					 <!-- 4 -->
				<th scope="col">기타</th>					 <!-- 5 -->
				<!--
				<th scope="col">필드6</th>					
				<th scope="col">필드7</th>					
				<th scope="col">필드8</th>					
				<th scope="col">필드9</th>					
				<th scope="col">필드10</th>	
				-->
				<th scope="col">수정</th>
        </tr>
        </thead>
        <tbody>
        <?php
        for ($i=0; $i<count($list); $i++) {
		$up_date = date("y/m/d", strtotime($list[$i]['wr_datetime']));
		?>
        <tr>
           <?php if ($is_checkbox) { ?>
            <td class="td_chk">
                <label for="chk_wr_id_<?php echo $i ?>" class="sound_only"><?php echo $list[$i]['subject'] ?></label>
                <input type="checkbox" name="chk_wr_id[]" value="<?php echo $list[$i]['wr_id'] ?>" id="chk_wr_id_<?php echo $i ?>">
            </td>
            <?php } ?>
				<td class="td_num"><?php echo $list[$i]['num'];?></td><!-- 번호 -->
				<td class="td_date" title="<?php echo $list[$i]['wr_datetime'] ?>"><a href="<?php echo $list[$i]['href'] ?>"><?php echo $up_date;?></a></td><!-- 등록일 -->
				<td class="td_subject"><input type="text" name="wr_subject[]" value="<?php echo $list[$i]['subject'] ?>" id="wr_subject_<?php echo $i ?>" class="lists"></td><!-- 제목 -->

				<td class="td_field" title="<?php echo $list[$i]['wr_1'] ?>"><input type="text" name="wr_1[]" value="<?php echo $list[$i]['wr_1'] ?>" id="wr_1_<?php echo $i ?>" class="lists"></td>
				<td class="td_field" title="<?php echo $list[$i]['wr_2'] ?>"><input type="text" name="wr_2[]" value="<?php echo $list[$i]['wr_2'] ?>" id="wr_2_<?php echo $i ?>" class="lists"></td>
				<td class="td_field" title="<?php echo $list[$i]['wr_3'] ?>"><input type="text" name="wr_3[]" value="<?php echo $list[$i]['wr_3'] ?>" id="wr_3_<?php echo $i ?>" class="lists"></td>
				<td class="td_field" title="<?php echo $list[$i]['wr_4'] ?>"><input type="text" name="wr_4[]" value="<?php echo $list[$i]['wr_4'] ?>" id="wr_4_<?php echo $i ?>" class="lists"></td>
				<td class="td_field" title="<?php echo $list[$i]['wr_5'] ?>"><input type="text" name="wr_5[]" value="<?php echo $list[$i]['wr_5'] ?>" id="wr_5_<?php echo $i ?>" class="lists"></td>
				<!--
				<td class="td_field" title="<?php echo $list[$i]['wr_6'] ?>"><input type="text" name="wr_6[]" value="<?php echo $list[$i]['wr_6'] ?>" id="wr_6" class="lists"></td>
				<td class="td_field" title="<?php echo $list[$i]['wr_7'] ?>"><input type="text" name="wr_7[]" value="<?php echo $list[$i]['wr_7'] ?>" id="wr_7" class="lists"></td>
				<td class="td_field" title="<?php echo $list[$i]['wr_8'] ?>"><input type="text" name="wr_8[]" value="<?php echo $list[$i]['wr_8'] ?>" id="wr_8" class="lists"></td>
				<td class="td_field" title="<?php echo $list[$i]['wr_9'] ?>"><input type="text" name="wr_9[]" value="<?php echo $list[$i]['wr_9'] ?>" id="wr_9" class="lists"></td>
				<td class="td_field" title="<?php echo $list[$i]['wr_10'] ?>"><input type="text" name="wr_10[]" value="<?php echo $list[$i]['wr_10'] ?>" id="wr_10" class="lists"></td>
				-->
				<td class="td_conf"><span id="flip_<?php echo $i ?>">수정</span></td>
				<script>
				$(document).ready(function(){
					$("#flip_<?php echo $i ?>").click(function(){
						var bo_table = "<?=$bo_table?>";
						var wr_id = "<?php echo $list[$i]['wr_id'] ?>";
						var value0 = $("#wr_subject_<?php echo $i ?>").val();
						var value1 = $("#wr_1_<?php echo $i ?>").val();
						var value2 = $("#wr_2_<?php echo $i ?>").val();
						var value3 = $("#wr_3_<?php echo $i ?>").val();
						var value4 = $("#wr_4_<?php echo $i ?>").val();
						var value5 = $("#wr_5_<?php echo $i ?>").val();  
						 $.ajax({
							  type: "POST",
							  url: "<?php echo $board_skin_url?>/list_up.php",
							  data: { bo_table:bo_table, wr_id:wr_id, wr_subject:value0, wr_1:value1, wr_2:value2, wr_3:value3, wr_4:value4, wr_5:value5 },
							  success: function () {
								   //do somthing here
								   location.reload();
								   alert("수정되었습니다")
							  }
						 });
					 });
				});
				</script>
		</tr>
        <?php } ?>
        <?php if (count($list) == 0) { echo '<tr><td colspan="'.$colspan.'" class="empty_table">게시물이 없습니다.</td></tr>'; } ?>
        </tbody>
        </table>
    </div>

    <?php if ($list_href || $is_checkbox || $write_href) { ?>
    <div class="bo_fx">
        <?php if ($is_checkbox) { ?>
        <ul class="btn_bo_adm">
				<li><input type="submit" name="btn_submit" value="선택삭제" onclick="document.pressed=this.value"></li>
				<li><button type="button" onclick="javascript:click_del('1','90');">90일 이전 자료 삭제</li> 
				<li><button type="button" onclick="javascript:click_del('2','0');">자료 초기화</li> 
				<li><button type="button" onclick="javascript:click_del('3','0');">금일자료 삭제</li>
        </ul>
        <?php } ?>

        <?php if ($list_href || $write_href) { ?>
        <ul class="btn_bo_user">
            <?php if ($list_href) { ?><li><a href="<?php echo $list_href ?>" class="btn_b01">목록</a></li><?php } ?>
            <?php if ($write_href) { ?><li><a href="<?php echo $write_href ?>" class="btn_b02">글쓰기</a></li><?php } ?>
        </ul>
        <?php } ?>
    </div>
    <?php } ?>
    </form>
</div>

<?php if($is_checkbox) { ?>
<noscript>
<p>자바스크립트를 사용하지 않는 경우<br>별도의 확인 절차 없이 바로 선택삭제 처리하므로 주의하시기 바랍니다.</p>
</noscript>
<?php } ?>

<!-- 페이지 -->
<?php echo $write_pages;  ?>


<script>
function click_del(code,days)
{
	if (code == "1") {
		var result = confirm(days+'일 이전 자료를 삭제하시겠습니까?');

        if(result) {
           //yes
            window.location.href = '<?php echo $board_skin_url?>/truncate.php?del=days&bo_table=<?php echo $bo_table?>&days='+days; //90일 이전 자료 삭제
        } else {
            //no
        }

	} else if (code == "2") {
		var result = confirm('자료를 초기화하시겠습니까?');

        if(result) {
           //yes
            window.location.href = '<?php echo $board_skin_url?>/truncate.php?del=all&bo_table=<?php echo $bo_table?>'; //자료 초기화
        } else {
            //no
        }
		
	} else if (code == "3") {
		var result = confirm('금일자료를 삭제하시겠습니까?');

        if(result) {
           //yes
            window.location.href = '<?php echo $board_skin_url?>/truncate.php?del=today&bo_table=<?php echo $bo_table?>'; //금일자료 삭제
        } else {
            //no
        }
		
	}
}

$(function(){
	$("#fr_date, #to_date").datepicker({ 
		changeMonth: true, 
		changeYear: true, 
		dateFormat: "yy-mm-dd", 
		showButtonPanel: true
	});
});

function set_date(today)
{
    <?php
    $date_term = date('w', G5_SERVER_TIME);
    $week_term = $date_term + 7;
    $last_term = strtotime(date('Y-m-01', G5_SERVER_TIME));
    ?>
    if (today == "오늘") {
        document.getElementById("fr_date").value = "<?php echo G5_TIME_YMD; ?>";
        document.getElementById("to_date").value = "<?php echo G5_TIME_YMD; ?>";
    } else if (today == "어제") {
        document.getElementById("fr_date").value = "<?php echo date('Y-m-d', G5_SERVER_TIME - 86400); ?>";
        document.getElementById("to_date").value = "<?php echo date('Y-m-d', G5_SERVER_TIME - 86400); ?>";
    } else if (today == "이번주") {
        document.getElementById("fr_date").value = "<?php echo date('Y-m-d', strtotime('-'.$date_term.' days', G5_SERVER_TIME)); ?>";
        document.getElementById("to_date").value = "<?php echo date('Y-m-d', G5_SERVER_TIME); ?>";
    } else if (today == "이번달") {
        document.getElementById("fr_date").value = "<?php echo date('Y-m-01', G5_SERVER_TIME); ?>";
        document.getElementById("to_date").value = "<?php echo date('Y-m-d', G5_SERVER_TIME); ?>";
    } else if (today == "지난주") {
        document.getElementById("fr_date").value = "<?php echo date('Y-m-d', strtotime('-'.$week_term.' days', G5_SERVER_TIME)); ?>";
        document.getElementById("to_date").value = "<?php echo date('Y-m-d', strtotime('-'.($week_term - 6).' days', G5_SERVER_TIME)); ?>";
    } else if (today == "지난달") {
        document.getElementById("fr_date").value = "<?php echo date('Y-m-01', strtotime('-1 Month', $last_term)); ?>";
        document.getElementById("to_date").value = "<?php echo date('Y-m-t', strtotime('-1 Month', $last_term)); ?>";
    } else if (today == "전체") {
        document.getElementById("fr_date").value = "";
        document.getElementById("to_date").value = "";
		window.location.href = '<?php echo G5_BBS_URL?>/board.php?bo_table=<?php echo $bo_table?>';
    }
}

</script>

<?php if ($is_checkbox) { ?>
<script>
function all_checked(sw) {
    var f = document.fboardlist;

    for (var i=0; i<f.length; i++) {
        if (f.elements[i].name == "chk_wr_id[]")
            f.elements[i].checked = sw;
    }
}

function fboardlist_submit(f) {
    var chk_count = 0;

    for (var i=0; i<f.length; i++) {
        if (f.elements[i].name == "chk_wr_id[]" && f.elements[i].checked)
            chk_count++;
    }

    if (!chk_count) {
        alert(document.pressed + "할 게시물을 하나 이상 선택하세요.");
        return false;
    }

    if(document.pressed == "선택복사") {
        select_copy("copy");
        return;
    }

    if(document.pressed == "선택이동") {
        select_copy("move");
        return;
    }

    if(document.pressed == "선택삭제") {
        if (!confirm("선택한 게시물을 정말 삭제하시겠습니까?\n\n한번 삭제한 자료는 복구할 수 없습니다\n\n답변글이 있는 게시글을 선택하신 경우\n답변글도 선택하셔야 게시글이 삭제됩니다."))
            return false;

        f.removeAttribute("target");
        f.action = "./board_list_update.php";
    }

    return true;
}

// 선택한 게시물 복사 및 이동
function select_copy(sw) {
    var f = document.fboardlist;

    if (sw == "copy")
        str = "복사";
    else
        str = "이동";

    var sub_win = window.open("", "move", "left=50, top=50, width=500, height=550, scrollbars=1");

    f.sw.value = sw;
    f.target = "move";
    f.action = "./move.php";
    f.submit();
}
</script>
<?php } ?>
<!-- } 게시판 목록 끝 -->
