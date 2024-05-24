<?php
include_once("_common.php");
$g5['title'] = "엑셀 업로드";
include_once(G5_PATH.'/_head.php');
?>
<style>
.new_excel{border:3px solid #ccc; padding:0 20px 20px 20px; margin-top:20px;}
.new_excel h1{margin:10px 0;}
.excel_info {margin-bottom:10px; line-height:18px;}
.btn_confirm {margin-top:15px;}
</style>

<div class="new_excel">
    <h1><?php echo $g5['title']?></h1>

    <div class="excel_info">
        <p>
			엑셀파일을 저장하실 때는 <strong>Excel 97 - 2003 통합문서 (*.xls)</strong> 로 저장하셔야 합니다.
        </p>
		 <p>
			<a href="<?php echo $board_skin_url?>/0_hs_excel_0.xls">신규등록 엑셀파일 샘플 다운로드 Click</a>.
        </p>
	</div>

    <form name="fitemexcelup" id="fitemexcelup" method="post" action="./excel_up2.php" enctype="multipart/form-data" autocomplete="off">
	<input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
    <div id="excelfile_upload" style="text-align:center;">
        <label for="excelfile">파일유형 선택</label>
		<input type="radio" name="ex_type" value="1">신규파일 &nbsp; 
		<input type="radio" name="ex_type" value="2">수정파일&nbsp; 
       <input type="file" name="excelfile" id="excelfile">
    </div>

    <div class="btn_confirm01 btn_confirm">
        <input type="submit" value="엑셀파일 등록" class="btn_submit">
		 <a href="<?php echo G5_BBS_URL?>/board.php?bo_table=<?php echo $bo_table?>" class="btn_submit" style="color:#FFF; text-decoration:none;">게시판 바로가기</a>
    </div>

    </form>

</div>

<?php
include_once(G5_PATH.'/_tail.php');
?>
