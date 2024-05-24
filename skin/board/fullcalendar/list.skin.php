<?php
if(!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/custom.css">', 0);

include_once("holiday.php");
$holiday = new Holiday();

$accessCode = !empty($board['bo_1']) ? intval($board['bo_1']) : 0;

$to_day = date("Y-m-d");
$wname = $holiday->getWeekname($to_day);
$cur_date = isset($_REQUEST["cur_date"]) ? $_REQUEST["cur_date"] : $to_day;
//$bgcolors = ["#ff6633","#9900ff","#ffa94d","#0099ff","#cc6699","#00ccff","#99ff00","#006699","#330000","#ffffff"];

 if($accessCode == 1) {
	$c_user = isset($_GET["c_user"]) ? html_purifier($_GET["c_user"]) : "all";
	$users = sql_query("select distinct wr_name from {$write_table}");
 }
?>

<script src="<?=$board_skin_url?>/dist/index.global.min.js"></script>
<script src="<?= $board_skin_url ?>/datepicker.js"></script>
<script src="https://unpkg.com/@popperjs/core@2"></script>
<script src="https://unpkg.com/tippy.js@6"></script>

<form name="newloadForm" id="newloadForm" method="post" action="./board.php?bo_table=<?=$bo_table?><?= ($accessCode == 1) ? "&c_user=".$c_user : "" ?>">
     <input type="hidden" name="cur_date" id="cur_date" value="">
</form>

<div class="container mb-5 p-0">
	<h3 class="mb-5"><?= $board["bo_subject"] ?></h3>
	<div class="container mb-5">
	  <div class="row">
		<div class=" bg-info pt-2 text-center" style="width:155px">
			<h5 class="fw-bold text-danger"><?=date("n. j")?>(<?=$wname?>)</h5>
			<h5 class="fw-bold">오늘의 일정</h5>
		<?php if($accessCode == 1) { ?>
			<select class="form-select mb-2" name="c_user" aria-label="사용자선택" onchange="window.location.href=this.value">
			  <option value="./board.php?bo_table=<?=$bo_table?>"<?= ($c_user == "all") ? " selected" : ""; ?>> &nbsp;모두보기</option>
			<?php foreach($users as $field) { ?>
			  <option value="./board.php?bo_table=<?=$bo_table?>&c_user=<?=$field["wr_name"]?>"<?= ($c_user == $field["wr_name"]) ? " selected" : ""; ?>> &nbsp;<?= $field["wr_name"] ?>님</option>
			<?php } ?>
			</select>
		<?php } ?>
		</div>
		<div class="col ps-4 py-2 bg-light">
		<?php include_once("ajax_today.php"); ?>
		</div>
	  </div>
  </div>
<div id='calendar'></div>
<p class="mt-2 text-success">※ 일정입력은 마우스로 클릭하거나 끌어서 입력. 하루, 기간별 일정은 <strong>월</strong> 화면에서, 시간별 일정은 <strong>주</strong> 화면에서 입력. 월단위로 입력</p>
<p class="text-success">※ 마우스로 이동, 기간 수정 가능함, 막대를 이동, 줄이거나 늘이면 수정됨.</p>

</div>

<!-- Modal -->
<?php //  if($admin_href) { ?>
<div class="modal fade" id="eventModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true" data-bs-focus="false">
  <div class="modal-dialog modal-lg" style="max-width:580px">
    <div class="modal-content">
	<form action="#" name="eventform" id="eventform">
		<input type="hidden" name="bo_table" value="<?=$bo_table?>">
		<input type="hidden" name="id" id="eventId">
		<?php if($accessCode <> 1) { ?>
		<input type="hidden" name="wr_name" id="wr_name" value="관리자">
		<?php } ?>
		<input type="hidden" name="allDay" id="allDay" value="true">
      <div class="modal-header bg-light py-2">
        <h5 class="modal-title" id="eventModalLabel"><i class="bi bi-calendar-plus-fill"></i> 일정등록</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-2 py-3">
		<div class="container">
			<div class="row">
		<?php if($accessCode == 1) { ?>
				<div class="col-12 my-3">
					 <div class="input-group">
						 <span class="input-group-text">사용자</span>
						 <select name="select_name" id="select_name" class="form-select">
							<option value="" id="wr_name2">추가등록</option>
						<?php foreach($users as $field) { ?>
						  <option value="<?=$field['wr_name']?>"<?= ($c_user == $field['wr_name']) ? " selected" : ""; ?>><?= $field["wr_name"] ?></option>
						<?php } ?>
						</select>
						 <input type="text" class="form-control" name="wr_name" id="wr_name" value="<?= ($c_user == "all") ? "" : $c_user; ?>" required title="등록자 입력" placeholder="사용자입력">
					</div>
				<script>
					$( "#select_name").change(function(){
						$("#wr_name").val($("#select_name").val());
						$("#wr_name").focus();
					});
				</script>
				</div>
		<?php } ?>
				<div class="col-12 mb-3">
					 <div class="input-group">
						 <span class="input-group-text" style="width:68px">제 목</span>
						 <input type="text" class="form-control" name="wr_subject" id="wr_subject" required>
					</div>
				</div>
				<div class="col-12 mb-3">
					 <div class="input-group">
						 <span class="input-group-text" style="width:68px">내 용</span>
						<textarea class="form-control" name="wr_content" id="wr_content" rows="5" required></textarea>
					</div>
				</div>
				<div class="col-12 mb-3">
					 <div class="input-group">
						 <span class="input-group-text">시작일</span>
						 <input type="text" class="form-control text-center datepicker" id="wr_1" name="wr_1" required>
						 <span class="input-group-text">종료일</span>
						<input type="text" class="form-control text-center datepicker" id="wr_2" name="wr_2" required>
					</div>
				</div>
				<div class="col-12 mb-3">
					 <div class="input-group">
						 <span class="input-group-text">배경색</span>
						 <input type="color" name="wr_3" class="form-control form-control-color" id="wr_3" value="#3788d8" title="배경색 선택">
						 <span class="input-group-text">글자색</span>
						 <input type="color" name="wr_4" class="form-control form-control-color" id="wr_4" value="#f5f5f5" title="글자색 선택">
					</div>
				</div>
<!--
				<div class="col-12 mb-3">
					 <div class="input-group">
						 <span class="input-group-text">배경색</span>
						 <?php foreach($bgcolors as $value) { ?>
						<div class="input-group-text" style="background:<?=$value?>"><input class="form-check-input fs-5" name="wr_3" type="radio" value="<?=$value?>"></div>
						<?php } ?>
					</div>
				</div>
				<div class="col-12 mb-3">
					 <div class="input-group">
						 <span class="input-group-text">글자색</span>
						 <?php foreach($bgcolors as $value) { ?>
						<div class="input-group-text" style="background:<?=$value?>"><input class="form-check-input fs-5" name="wr_4" type="radio" value="<?=$value?>"></div>
						<?php } ?>
					</div>
				</div>
		-->

			</div>
		</div>
	  </div>
      <div class="modal-footer bg-light py-1" id="eventBtn">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">닫기</button>
		<button type="button" class="btn btn-primary" id="btnSave" onclick="save()">등록</button>
	  </div>
	</form>
    </div>
  </div>
</div>
<?php // } ?>

<div class="modal fade" id="monthModal" aria-hidden="true" aria-labelledby="monthModalLabel" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-light">
        <h1 class="modal-title fs-5" id="monthModalLabel">월별일정표</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="monthModalBody"> </div>
    </div>
  </div>
</div>

<script>

	var save_method;
	var site_url ="<?= $board_skin_url ?>";
	var first_date;

	document.addEventListener('DOMContentLoaded', function() {
		var calendarEl = document.getElementById('calendar');
		var calendar = new FullCalendar.Calendar(calendarEl, {

		datesSet: (info) => {
			first_date = info.startStr.substring(0, 10).replace(/\-/g, '-');
		},
		customButtons: {
			mymonthButton: {
				text: '월별일정보기',
				click: function() {
					view_form(first_date);
				}
			},
			mymonthsButton: {
				text: '월',
				click: function() {
					cal_reload(first_date);
				}
			}
		},
		headerToolbar: {
			left: 'mymonthButton',
			center: 'title',
		//	right: 'mymonthButton,mymonthsButton,timeGridWeek,timeGridDay,listWeek'
			right: 'today,prev,mymonthsButton,timeGridWeek,next'

		},

		views: {
			timeGrid: {
				titleFormat: { year: 'numeric', month: '2-digit', day: '2-digit' }
			}
		},

//		buttonText:{year:"년도",month:"월",week:"주",day:"일",today:"오늘",listWeek:"주별"},
		buttonText:{year:"년도",month:"월",week:"주",today:"오늘"},

		locale: 'ko',
		dayCellContent: function(arg){
			return arg.date.getDate();
		},

		initialDate: '<?=$cur_date?>',
		navLinks: true,
		selectable: true,
		selectMirror: true,
	//	eventOrder:"order",	//정렬순서 -는 desc
		editable: true,
		dayMaxEvents: false, // allow "more" link when too many events
		height: "auto",

		eventDidMount: function(info) {
            tippy(info.el, {
                content:  info.event.extendedProps.description,	//이벤트 내용 툴팁
				allowHTML: true,
            });
        },

		events: function(info, successCallback, failureCallback) {
			$.ajax({
				url:  site_url+'/ajax_data.php',
				type: 'post',
				data: {
					tdate: info.startStr,
					bo_table: '<?=$bo_table?>',
					c_user: '<?=$c_user?>',
				},
				dataType: 'json',
				success: function (json) {
					successCallback( json );

					$.ajax( {
						url:  site_url+'/ajax_holiday.php',
						type: 'post',
						data: {
							tdate: first_date,
							bo_table: '<?=$bo_table?>',
						},
						dataType: 'json',
						async: false,
						success: function (holiday) {

							var dates = [];
							var datex = $(".fc-daygrid-day-number");
							document.querySelectorAll("[data-date]").forEach(function(element) {
								dates.push(element.dataset.date);
							});

							for(i in holiday) {
								var element = dates.findIndex(v => v === i);
								if(element != -1) {
								var day = datex[element].innerText;
console.log(day);
								if(day.length > 3) day = day.substr(-1);
								datex[element].innerText = day + ' ' + holiday[dates[element]];
								datex[element].style.color = 'red';
								datex[element].className += ' holiday-text';
								}
							}
						},
						error: function (jqXHR, textStatus, errorThrown) {
							alert("데이터 처리중 에러가 발생했습니다.");
						}
					});
				},
				error: function (jqXHR, textStatus, errorThrown) {
					alert("데이터 처리중 에러가 발생했습니다.");
				}
			});
		},

		select: function(arg) {
			save_method = "add";
			var start = arg.startStr;
			if (arg.endStr == null) {
				var end = start;
			} else {
				var end = arg.endStr;
			}
			$('#wr_1').val(start);
			$('#wr_2').val(end);
			$("#eventModal").modal("show");
		},

		eventDrop: function(arg) {
			var start = arg.event.startStr;
			if (arg.event.endStr == "") {
				var end = start;
			} else {
				var end = arg.event.endStr;
			}

			$.ajax({
				url: site_url+'/ajax_update2.php',
				type:"post",
				data: {
					id: arg.event.id,
					wr_1: start,
					wr_2: end,
					bo_table: '<?=$bo_table?>',
				},
			});
		},

		eventResize: function(arg) {
			var id = arg.event.id;
			var start = arg.event.startStr;
			if (arg.event.endStr == "") {
				var end = start;
			} else {
				var end = arg.event.endStr;
			}

			$.ajax({
				url: site_url+'/ajax_update2.php',
				type:"POST",
				data: {
					id: id,
					wr_subject : arg.event._def.title,
					wr_1: start,
					wr_2: end,
					bo_table: '<?=$bo_table?>',
				}
			});
		},

		eventClick: function(arg) {
			var id = arg.event.id;
			save_method = "update";
			$('#eventId').val(id);
			$('#deleteEvent').attr('data-id', id);

			$.ajax({
				url: site_url+'/ajax_edit.php',
				type:"POST",
				dataType: 'json',
				data: {
					id: id,
					bo_table: '<?=$bo_table?>',
				},
				success: function(data) {
					var link = '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">취소</button> <button class="btn btn-danger" onclick="delete_form('+id+',\''+data.wr_1+'\')">삭제</button><button type="button" class="btn btn-primary" id="btnSave" onclick="save('+id+')">수정</button>';
					$('#eventId').val(id);
					$('#wr_subject').val(data.wr_subject);
					$('#wr_content').val(data.wr_content);
					$('#wr_name').val(data.wr_name);
					$('#wr_name2').text("등록자");
					$('#wr_1').val(data.wr_1);
					$('#wr_2').val(data.wr_2);
					$('#wr_3').val(data.wr_3);
					$('#wr_4').val(data.wr_4);
					$("#eventBtn").html(link);
/*
					$("input[name='wr_3']").each(function() {
						var $this = $(this);
						if($this.val() == data.wr_3)
						$this.attr('checked', true);
					});

					$("input[name='wr_4']").each(function() {
						var $this = $(this);
						if($this.val() == data.wr_4)
						$this.attr('checked', true);
					});
*/
					$('#eventModal').modal("show");
				  }
			  });
			}
		});
		calendar.render();
	});

	function save(id) {
		var url;
		var formdata = new FormData(document.querySelector('#eventform'));

		if($("#wr_subject").val() == "") {
			alert("제목을 입력해주세요");
			$("#wr_subject").focus();
			return;
		}

		if($("#wr_name").val() == "") {
			alert("사용자를 입력해주세요");
			$("#wr_name").focus();
			return;
		}

		if(save_method == "add") {
			url = site_url+"/ajax_save.php";
		} else	{
			url = site_url+"/ajax_update.php";
		}

		$.ajax({
			url : url,
			type: "POST",
			data: formdata,
			processData: false,
			contentType: false,
			success: function(data) {
				$("#eventModal").modal("hide");
				const obj = JSON.parse(data);
				cal_reload(obj.tdate);
			},
			error: function (jqXHR, textStatus, errorThrown) {
				alert("데이터 처리중 에러가 발생했습니다.");
			}
		});
	}

	function delete_form(id,tdate) {

		if(confirm("자료를 삭제하시겠습니까?")) {
			$.ajax({
				url : site_url+"/ajax_delete.php",
				type: "POST",
				data: {
					id: id,
					tdate: tdate,
					bo_table: "<?=$bo_table?>",
				},
				success: function(data) {
					$("#eventModal").modal("hide");
					const obj = JSON.parse(data);
					cal_reload(obj.tdate);
				},
				error: function (jqXHR, textStatus, errorThrown) {
					alert("삭제 도중 에러가 발생했습니다.");
				}
			});
		}
	}

	function view_form(id) {

		$.ajax({
			url : site_url+"/ajax_data_month.php",
			type: "POST",
			data: {
				tdate: id,
				bo_table: "<?=$bo_table?>",
			},
			success: function(resp) {
				$("#monthModalBody").html(resp);
				$("#monthModal").modal("show");
			},
			error: function (jqXHR, textStatus, errorThrown) {
				alert("에러가 발생했습니다.");
			}
		});
	}

	function cal_reload(cdate) {
		var today = new Date(cdate);
		today.setDate(today.getDate() + 7);
		var tcdate = today.toISOString();
		tcdate = tcdate.substring(0,8)+'01';

//		location.href = g5_bbs_url + "/board.php?bo_table=<?=$bo_table?>&cur_date="+tcdate;	//새로고침시 알림 메시지가 귀찮으면 이 구문을 사용하고 아래 2개 주석처리
		$("#cur_date").val(tcdate);
		$("#newloadForm").submit();
	}

</script>