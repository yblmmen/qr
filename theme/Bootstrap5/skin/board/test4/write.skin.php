<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css" media="screen">', 0);
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.4.0/jszip.min.js"></script>
<script src="http://code.jquery.com/jquery-1.12.4.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<link rel="stylesheet" href="http://kendo.cdn.telerik.com/2016.3.914/styles/kendo.common.min.css"/>
<link rel="stylesheet" href="../js/kendo.metro.min.css" />
<link rel="stylesheet" href="../js/kendo.metro.mobile.min.css" />
<script src="../js/kendo.all.min.js"></script>
<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<script src="//d1p7wdleee1q2z.cloudfront.net/post/search.min.js"></script>
<script src="../js/jquery.form.js"></script> 
<style>
.k-dropdown .k-dropdown-wrap
{
	height: 28px;
	width:92%;
	display: block;
}
</style>
<?php if($is_dhtml_editor) { ?>
	<style>
		#wr_content { border:0; display:none; }
	</style>

<?php } ?>
<script src="http://jonthornton.github.io/jquery-timepicker/jquery.timepicker.js"></script>
<link rel="stylesheet" media="all" type="text/css" href="http://jonthornton.github.io/jquery-timepicker/jquery.timepicker.css" />


<div id="bo_w" class="write-wrap<?php echo (G5_IS_MOBILE) ? ' font-14' : '';?>">
    <div class="well">
		<h2><?php echo $g5['title'] ?></h2>
	</div>

    <!-- 게시물 작성/수정 시작 { -->
    <form name="fwrite" id="fwrite" action="<?=$board_skin_url?>/write_update.php" onsubmit="return fwrite_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off" role="form" class="form-horizontal">
    <input type="hidden" name="uid" value="<?php echo get_uniqid(); ?>">
    <input type="hidden" name="w" value="<?php echo $w ?>">
    <input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
	<input type="hidden" name="wr_id" value="<?php echo $wr_id ?>">
	<input type="hidden" name="wr_datetime" value="<?php echo $write['wr_datetime'] ?>">
    <input type="hidden" name="sca" value="<?php echo $sca ?>">
    <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
    <input type="hidden" name="stx" value="<?php echo $stx ?>">
    <input type="hidden" name="spt" value="<?php echo $spt ?>">
    <input type="hidden" name="sst" value="<?php echo $sst ?>">
    <input type="hidden" name="sod" value="<?php echo $sod ?>">
    <input type="hidden" name="page" value="<?php echo $page ?>">
  


	<div class="form-group">
	<label class="col-sm-2 control-label" for="wr_homepage">일정</label>
	<div class="col-sm-3">
		<div class="control-label input-group input-group-sm">
			<span class="input-group-addon">시작</span>
			<input type="text" name="wr_1" value="<?php echo ($write['wr_1']) ? $write['wr_1']:DATE('Y-m-d');?>" id="wr_1" <?php echo $is_required;?> class="form-control input-sm" size="5" maxlength="20">
			<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
			<input type="text" name="wr_3" id="wr_3" value="<?=$write['wr_3']?>" class="form-control input-sm" size="5" maxlength="20"><span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
		</div>
	</div>
	<div class="col-sm-3">
		<div class="control-label input-group input-group-sm">
			<span class="input-group-addon">종료</span>
			<input type="text" name="wr_2" value="<?php echo ($write['wr_2']) ? $write['wr_2']:DATE('Y-m-d');?>" id="wr_2" <?php echo $is_required;?> class="form-control input-sm" size="5" maxlength="20">
			<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
			<input type="text" name="wr_4" id="wr_4" value="<?=$write['wr_4']?>" class="form-control input-sm" size="5" maxlength="20"><span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
		</div>
	</div>
     </div>
	 <div class="form-group">
		<label class="col-sm-2 control-label" for="wr_homepage">성명</label>
		<div class="col-sm-3">
			<div class="control-label input-group input-group-sm">		
			<span class="input-group-addon">성명</span>
				<input type="text" name="wr_subject" id="wr_subject" size="25" class="form-control input-sm" maxlength="20">

			</div>
		</div>
   	</div>
	 <div class="form-group">
		<label class="col-sm-2 control-label" for="wr_homepage">소속/직위</label>
		<div class="col-sm-3">
			<div class="control-label input-group input-group-sm">		
				<span class="input-group-addon">소속</span>
				<input type="text" name="wr_5" id="wr_5" value="<?php echo ($write['wr_5']) ? $write['wr_5']:$member['mb_2'];?>" class="form-control input-sm" size="25" maxlength="20" readonly>
				<span class="input-group-addon"><i class="fa fa-id-card-o"></i></span>
			</div>
		</div>
		<div class="col-sm-3">
			<div class="control-label input-group input-group-sm">
				<span class="input-group-addon">직위</span>
				<input type="text" name="wr_6" id="wr_6" value="<?php echo ($write['wr_6']) ? $write['wr_6']:$member['mb_26'];?>" class="form-control input-sm" maxlength="20" readonly>
				<span class="input-group-addon"><i class="fa fa-qq"></i></span>	
			</div>
		</div>
   	</div>
	 <div class="form-group">
		<label class="col-sm-2 control-label" for="wr_homepage">연차구분</label>
		<div class="col-sm-3">
			<div class="control-label input-group input-group-sm">		
				<input type="text" name="wr_7" id="wr_7" size="25" class="form-control input-sm" maxlength="20">
				<span class="input-group-addon"><i class="fa fa-id-card-o"></i></span>
			</div>
		</div>
   	</div>
	<div class="form-group">
        <label class="col-sm-2 control-label" for="wr_homepage">행선지</label>
		<div class="col-sm-3">
			<div class="control-label input-group input-group-sm">
				<input type="text" name="wr_8" value="<?=$write['wr_8']?>" class="form-control input-sm" maxlength="20">
				<span class="input-group-addon"><i class="fa fa-bicycle"></i></span>	
			</div>
		</div>
   	</div>
	<div class="form-group">
        <label class="col-sm-2 control-label" for="wr_homepage">연락처</label>
		<div class="col-sm-3">
			<div class="control-label input-group input-group-sm">
				<input type="text" name="wr_9"  id="wr_9" value="<?php echo ($write['wr_9']) ? $write['wr_9']:$member['mb_hp'];?>"  class="form-control input-sm" maxlength="20">
				<span class="input-group-addon"><i class="fa fa-bicycle"></i></span>	
			</div>
		</div>
	</div>
    <div class="form-group">
		<label class="col-sm-2 control-label">사유</label>
		<div class="col-sm-3">
			<div class="control-label input-group input-group-sm">
			<input type="text" name="wr_content" value="<?=$write['wr_content']?>" class="form-control input-sm" maxlength="255" style="width:500px;height:50px">
			</div>
		</div>
	</div>
	<div class="form-group">
	<label class="col-sm-2 control-label" for="wr_homepage">결재</label>
	<div class="col-sm-3">
		<div class="control-label input-group input-group-sm">
			<span class="input-group-addon">검토</span>
			<input type="text" name="wr_11" id="wr_11" class="form-control input-sm" size="5" maxlength="20">
		</div>
	</div>
	<div class="col-sm-3">
		<div class="control-label input-group input-group-sm">
			<span class="input-group-addon">승인</span>
			<input type="text" name="wr_13" id="wr_13" class="form-control input-sm" maxlength="20">
		</div>
		</div>
     </div>	



	<!--<?php if($is_admin || ($boset['tag'] && $member['mb_level'] >= $boset['tag'])) { //태그 ?>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="as_tag">태그</label>
			<div class="col-sm-10">
				<input type="text" name="as_tag" id="as_tag" value="<?php echo $write['as_tag']; ?>" class="form-control input-sm" size="50">
			</div>
		</div>
	<?php } ?>
	<?php for ($i=1; $is_link && $i<=G5_LINK_COUNT; $i++) { ?>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="wr_link<?php echo $i ?>">링크 #<?php echo $i ?></label>
			<div class="col-sm-10">
				<input type="text" name="wr_link<?php echo $i ?>" value="<?php if($w=="u"){ echo $write['wr_link'.$i]; } ?>" id="wr_link<?php echo $i ?>" class="form-control input-sm" size="50">
				<?php if($i == "1") { ?>
					<div class="text-muted font-12" style="margin-top:4px;">
						유튜브, 비메오 등 동영상 공유주소 등록시 해당 동영상은 본문 자동실행
					</div>
				<?php } ?>
			</div>
		</div>
	<?php } ?>-->
	<?php if ($is_file) { ?>
		<style>
		#variableFiles { width:100%; margin:0; border:0; }
		#variableFiles td { padding:0px 0px 7px; border:0; }
		#variableFiles input[type=file] { box-shadow : none; border: 1px solid #ccc !important; outline:none; }
		#variableFiles .form-group { margin-left:0; margin-right:0; margin-bottom:7px; }
		#variableFiles .checkbox-inline { padding-top:0px; font-weight:normal; }
		</style>
		<div class="form-group">
			<label class="col-sm-2 control-label">첨부파일</label>
			<div class="col-sm-10">
				<button class="btn btn-sm btn-color" type="button" onclick="add_file();"><i class="fa fa-plus-circle fa-lg"></i> 추가하기</button>
				<button class="btn btn-sm btn-black" type="button" onclick="del_file();"><i class="fa fa-times-circle fa-lg"></i> 삭제하기</button>
			</div>
		</div>
		<div class="form-group" style="margin-bottom:0;">
			<div class="col-sm-10 col-sm-offset-2">
				<table id="variableFiles"></table>
			</div>
		</div>
		<script>
		var flen = 0;
		function add_file(delete_code) {
			var upload_count = <?php echo (int)$board['bo_upload_count']; ?>;
			if (upload_count && flen >= upload_count) {
				alert("이 게시판은 "+upload_count+"개 까지만 파일 업로드가 가능합니다.");
				return;
			}

			var objTbl;
			var objNum;
			var objRow;
			var objCell;
			var objContent;
			if (document.getElementById)
				objTbl = document.getElementById("variableFiles");
			else
				objTbl = document.all["variableFiles"];

			objNum = objTbl.rows.length;
			objRow = objTbl.insertRow(objNum);
			objCell = objRow.insertCell(0);

			objContent = "<div class='row'>";
			objContent += "<div class='col-sm-7'><div class='form-group'><div class='input-group input-group-sm'><span class='input-group-addon'>파일 "+objNum+"</span><input type='file' class='form-control input-sm' name='bf_file[]' title='파일 용량 <?php echo $upload_max_filesize; ?> 이하만 업로드 가능'></div></div></div>";
			if (delete_code) {
				objContent += delete_code;
		    } else {
				<?php if ($is_file_content) { ?>
				objContent += "<div class='col-sm-5'><div class='form-group'><input type='text'name='bf_content[]' class='form-control input-sm' placeholder='이미지에 대한 내용을 입력하세요.'></div></div>";
				<?php } ?>
				;
			}
			objContent += "</div>";

			objCell.innerHTML = objContent;

			flen++;
		}

		<?php echo $file_script; //수정시에 필요한 스크립트?>

		function del_file() {
			// file_length 이하로는 필드가 삭제되지 않아야 합니다.
			var file_length = <?php echo (int)$file_length; ?>;
			var objTbl = document.getElementById("variableFiles");
			if (objTbl.rows.length - 1 > file_length) {
				objTbl.deleteRow(objTbl.rows.length - 1);
				flen--;
			}
		}
		</script>

		
	<?php } ?>



    <div class="write-btn pull-center">
        <button type="submit" id="btn_submit" accesskey="s" class="btn btn-color btn-sm"><i class="fa fa-check"></i> <b>작성완료</b></button>
        <a href="./board.php?bo_table=<?php echo $bo_table ?>" class="btn btn-black btn-sm" role="button">취소</a>
    </div>

	<div class="clearfix"></div>

	</form>

    <script>

	function fwrite_submit(f) {
        <?php echo $editor_js; // 에디터 사용시 자바스크립트에서 내용을 폼필드로 넣어주며 내용이 입력되었는지 검사함   ?>

        var subject = "";
        var content = "";
        $.ajax({
            url: g5_bbs_url+"/ajax.filter.php",
            type: "POST",
            data: {
                "subject": f.wr_subject.value,
                "content": f.wr_content.value
            },
            dataType: "json",
            async: false,
            cache: false,
            success: function(data, textStatus) {
                subject = data.subject;
                content = data.content;
            }
        });

        if (subject) {
            alert("제목에 금지단어('"+subject+"')가 포함되어있습니다");
            f.wr_subject.focus();
            return false;
        }

        if (content) {
            alert("내용에 금지단어('"+content+"')가 포함되어있습니다");
            if (typeof(ed_wr_content) != "undefined")
                ed_wr_content.returnFalse();
            else
                f.wr_content.focus();
            return false;
        }

        if (document.getElementById("char_count")) {
            if (char_min > 0 || char_max > 0) {
                var cnt = parseInt(check_byte("wr_content", "char_count"));
                if (char_min > 0 && char_min > cnt) {
                    alert("내용은 "+char_min+"글자 이상 쓰셔야 합니다.");
                    return false;
                }
                else if (char_max > 0 && char_max < cnt) {
                    alert("내용은 "+char_max+"글자 이하로 쓰셔야 합니다.");
                    return false;
                }
            }
        }

        <?php echo $captcha_js; // 캡챠 사용시 자바스크립트에서 입력된 캡챠를 검사함  ?>

        document.getElementById("btn_submit").disabled = "disabled";

        return true;
    }

	$(function(){
		$("#wr_content").addClass("form-control input-sm write-content");
	});
	$(function(){
	$("#wr_1, #wr_2").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-2:c+3" });
	$("#wr_content").addClass("form-control input-sm write-content");
	$("#wr_3, #wr_4").timepicker({
		timeFormat: "H:i"
	});

})
    </script>
 <script>
	$(document).ready(function() {
		$("#wr_subject").kendoDropDownList({
			autoBind: false,
			dataTextField: "mb_name",
			dataValueField: "mb_id",
			filter: "startswith",
			select: onSelect,
			dataSource: {
				type: "json",
				transport: {
                     read: "<?=$board_skin_url?>/humandata2.php"
					},
					schema: {data: "data"},
					group:{field:"mb_2"}

			},		
		});
		function onSelect(e) {
				    var dataItem = this.dataItem(e.item); 
//					alert(dataItem.mb_2);
				    $("#wr_5").val(dataItem.mb_2);
				    $("#wr_6").val(dataItem.mb_26);
				    $("#wr_9").val(dataItem.mb_hp);
//				    var wr_1=$("#wr_1").val();
//			        var wr_2=$("#wr_2").val();
//				    var test1=new Date(wr1.substring(0,4));
//				    var test1=new Date(wr_1.substring(0,4),wr_1.substring(5,7),wr_1.substring(8,10), 0, 0, 0);
// 				    var test2=new Date(wr_2.substring(0,4),wr_2.substring(5,7),wr_2.substring(8,10), 0, 0, 0);
//                  var wr_3=$("#wr_3").val();
//				    var wr_4=$("#wr_4").val();
//				    var test3=new Date(0,0,0,wr_3.substring(0,2), 0, 0);
//					var test4=new Date(0,0,0,wr_4.substring(0,2), 0, 0);


				// var test=$("#wr_3").val();
				   //var test2=$("#wr_4").val();
//	alert((test2-test1)/86400000)
//	alert((test4-test3)/3600000)

  };  
<?php if($write['wr_subject']=='') { ?>
		$("#wr_subject").data('kendoDropDownList').value("<?=$member['mb_id']?>");
	<?php }
	else { ?> $("#wr_subject").data('kendoDropDownList').value("<?=$write['wr_subject']?>");
	<?php }
?>
			<?php if($member['mb_level']<4) { ?>
					$("#wr_subject").data('kendoDropDownList').readonly();
			<?php } ?>
	});

</script>
<script>
	$(document).ready(function() {
		$("#wr_11").kendoDropDownList({
			autoBind: false,
//            placeholder: "검색하실 사람을입력하세요",
			dataTextField: "mb_name",
			dataValueField: "mb_id",
			filter: "contains",
			dataSource: {
				type: "json",
				transport: {
                     read: "<?=$board_skin_url?>/humandata.php"
					},
					schema: {data: "data"}
			},		
		});
	<?php if($write['wr_11']<>'') { ?>
		$("#wr_11").data('kendoDropDownList').value("<?=$write['wr_11']?>");
	<?php }
	else{?>
		$("#wr_11").data('kendoDropDownList').value("<?=$member['mb_56']?>");
	
	<?}?>
	
});
</script>
<script>
	$(document).ready(function() {
		$("#wr_13").kendoDropDownList({
			autoBind: false,
//            placeholder: "검색하실 사람을입력하세요",
			dataTextField: "mb_name",
			dataValueField: "mb_id",
			filter: "contains",
			dataSource: {
				type: "json",
				transport: {
                     read: "<?=$board_skin_url?>/humandata1.php"
					},
					schema: {data: "data"}
			},		
		});

	<?php if($write['wr_13']<>'') { ?>
		$("#wr_13").data('kendoDropDownList').value("<?=$write['wr_13']?>");
	<?php }
	else{?>
		$("#wr_13").data('kendoDropDownList').value("<?=$member['mb_57']?>");
	
	<?}?>
});
</script>
<script>
	$(document).ready(function() {
     var data = [
                        { text: "연차", value: "연차" },
		                { text: "반차", value: "반차" },
						{ text: "외근", value: "외근" },
                        { text: "조퇴", value: "조퇴"},
                        { text: "외출", value: "외출" },
                        { text: "훈련", value: "훈련" },
                        { text: "병가", value: "병가" },
                        { text: "공가", value: "공가" },
                        { text: "기타", value: "기타" }
      ];
		$("#wr_7").kendoDropDownList({
			dataTextField: "text",
			dataValueField: "value",
			dataSource: data,		
		});
	<?php if($write['wr_7']<>'') { ?>
		$("#wr_7").data('kendoDropDownList').value("<?=$write['wr_7']?>");
	<?php }?>
});
</script>
</div>
<!-- } 게시물 작성/수정 끝 -->
</div>