/**
 * 달력 스케쥴 드래그하여 날짜 변경
 * @param {*} start 시작날짜
 * @param {*} end 종료날짜
 * @param {*} wr_id 글번호
 */
function dragEditDateChange(start, end, wr_id, allDay) {

	var rst = [];
	$.ajax({
		type : 'POST',
		url : cfg.board_skin_url+"/drag-update.php?bo_table="+g5_bo_table,
		data : {
			"wr_id" : wr_id, // 글번호
			"start" : start, // 시작날짜
			"end" : end, // 종료날짜
			"allDay" : allDay // 종료날짜
		},
		dataType: "json",
		async: false,
		cache: false,
		error : function(error) {
		},
		success : function(data) {
			rst = data;
		},
		complete : function() {
		}
	});

	return rst;
}

function fullcalendarSetup() {
	rumiPopup.popup({
		width : 950,
		height : 650,
		fadeIn : true,
		fadeinTime : 200,
		iframe : true,
		url : cfg.board_skin_url+'/setting.php?bo_table='+ g5_bo_table,
		title : "Fullcalendar 설정",
		print : true,
		reloadBtn : true,
		button : {
			"저장":function(){
				$("#rumiIframe").contents().find("#btn_submit").trigger("click");
			},
			"닫기" : function(){
				rumiPopup.close();
			},
		},
		open : function(){
			$("div.rumiButton button:contains('닫기')").css({"background":"#555"});

			setTimeout(function() {
				var pv = $("#rumiIframe").contents().find("#popupCheck").val();
				if(pv == undefined || !pv) {
					if(mode == "write") {
						alert("권한이 없거나 페이지에 접근할 수 없습니다.");
						console.count();
					}
					rumiPopup.close();
				}
			},1000);
		},
		close : function() {
			//calendarRefresh(); // 창닫을때 이벤트 업데이트
			parent.document.location.reload();
		}
	});
}

/**
 * 글쓰기 / 글보기 팝업
 * @param {*} mode 글쓰기(write) 또는 글보기(view)
 * @param {*} wr_id 글번호
 * @param {*} startDate 일정시작일
 * @param {*} endDate 일정종료일
 * @param {*} allday 일정 종일 여부
 * @returns
 */
function boardwrite(mode, wr_id, startDate, endDate, allday) {

	if(cfg.writeType=="popup") {
		var viewUrl = cfg.board_skin_url+"/view.php?bo_table="+g5_bo_table+"&wr_id="+wr_id+"&start="+startDate+"&end="+endDate;
	} else {
		var viewUrl = g5_bbs_url+"/board.php?bo_table="+g5_bo_table+"&wr_id="+wr_id+"&start="+startDate+"&end="+endDate;
	}

	start = (startDate) ? "start="+startDate : "";
	end = (endDate) ? "&end="+endDate : "";
	allday = (allday) ? "&allday=1" : "";

	// 짧은 URL 사용으로 인한  물음표(?) 포함 여부 확인
	var para =  (cfg.bbs_write_url.indexOf("?") != -1) ? "&" : "?"

	var url = (mode == "view") ? viewUrl : cfg.bbs_write_url + para + start + end + allday + "&wt=popup";
	var title = (mode == "view") ? "일정보기" : "일정 등록/수정";

	if(cfg.writeType!="popup") {
		location.href = url+'&wt=none';
		return false;
	}

	rumiPopup.popup({
		width : 1000,
		height : 90,
		fadeIn : true,
		fadeinTime : 200,
		iframe : true,
		url : url,
		title : title,
		print : true,
		reloadBtn : true,
		button : {
			// "저장":function(){
			// 	$("#rumiIframe").contents().find("#btn_submit").trigger("click");
			// },
			// "취소":function(){
			// 	$("#rumiIframe").contents().find(".btn_cancel").trigger("click");
			// },
			// "수정":function(){
			// 	$("#rumiIframe").contents().find("#btn_edit").trigger("click");
			// 	var ss = $("#rumiIframe").contents().find("#btn_edit");
			// 	console.log(ss);
			// },
			// "삭제":function(){
			// 	$("#rumiIframe").contents().find("#delete").trigger("click");
			// },
			"닫기" : function(){
				rumiPopup.close();
			},
		},
		open : function(){
			$("div.rumiButton button:contains('닫기')").css({"background":"#555"});
			// $("div.rumiButton button:contains('저장')").hide();
			// $("div.rumiButton button:contains('취소')").hide();
			// $("div.rumiButton button:contains('수정')").hide();
			// $("div.rumiButton button:contains('삭제')").hide();

			setTimeout(function() {
				var pv = $("#rumiIframe").contents().find("#popupCheck").val();
				if(pv == undefined || !pv) {
					if(mode == "write") {
						alert("권한이 없거나 페이지에 접근할 수 없습니다.");
						console.count();
					}
					rumiPopup.close();
				}
			}, 1000);
		},
		close : function() {
			calendarRefresh(); // 창닫을때 이벤트 업데이트
		}
	});
}

function get_ymd(date) {
	date = new Date(date)
	var year = date.getFullYear();
	var month = date.getMonth() + 1;
	var day = date.getDate();
	month = (month < 10) ? "0"+month : month;
	day = (day < 10) ? "0"+day : day;
	return year+"-"+month+"-"+day;
}

function timeConvet(info) {
	var days = [];
	var startStr;
	var endStr;
	if(info.allDay == true) {
		var endStr = new Date(info.endStr);
		endStr = endStr.setMinutes(endStr.getMinutes() - 541);
		startStr = info.startStr;
		endStr = get_ymd(endStr);
	}
	else {
		startStr = info.startStr;
		endStr = info.endStr;
	}

	days = {
		"start" : startStr,
		"end" :endStr
	}

	return days;

}

function yyyymmdd(ti, mode) {

	var date = new Date(ti);
	if(mode == "td") {
		date = date.setMinutes(date.getMinutes() - 541);
	}
	else {
		date = date.setMinutes(date.getMinutes());
	}
	date = new Date(date)
	var year = date.getFullYear();
	var month = date.getMonth() + 1;
	var day = date.getDate();
	month = (month < 10) ? "0"+month : month;
	day = (day < 10) ? "0"+day : day;

	return year+"-"+month+"-"+day;

}

function get_lunar_db(startDate, endDate) {

	startDate = yyyymmdd(startDate, "fd");
	endDate = yyyymmdd(endDate, "td");

	var lunar = [];
	$.ajax({
		type : 'POST',
		url : cfg.board_skin_url+"/lunar_data.php",
		data : {
			"startDate" : startDate,
			"endDate" : endDate,
			"checkID": cfg.rumi_uid,
		},
		dataType: "json",
		async: false,
		cache: false,
		error : function(error) {
		},
		success : function(data) {
			lunar = data;
		},
		complete : function() {
		}
	});

	return lunar;
}