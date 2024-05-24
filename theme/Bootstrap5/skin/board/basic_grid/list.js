$(function(){
	$("#list2").jqGrid({
		url:data_url,
		datatype: "json",
		colNames:['번호','wr_id','분류','제품명(모델명)','상품코드','등록일시','창고입고일','관리팀','창고위치','크기','색상','재질','A/S가능여부','배송가능여부','용량'],
		colModel:[
			{name:'num',index:'num', width:50, align:'center', sortable: false, formatter:'integer'},	
			{name:'wr_id',index:'wr_id', key:true, hidden:true},
			{name:'ca_name',index:'ca_name', width:60, align:'center',
				cellattr:function(rowId, tv, rowObject, cm, rdata) {
					switch(rowObject.ca_name){
						case "공지" :
							return "class='txt_red txt_bold'";
							break;
						case "분류1" :
						case "분류2" :
						case "분류3" :
						case "분류4" :
						case "분류5" :
							return "class='txt_black txt_bold'";
							break;

					}
				}		
			},	
			{name:'wr_subject',index:'wr_subject', width:180, align:'left', classes:'td_hand'},
			{name:'wr_9',index:'wr_9', width:75, align:'center'},
			{name:'wr_datetime',index:'wr_datetime', width:120, align:'center', classes:'td_hand'},
			{name:'wr_10',index:'wr_10', width:70, align:'center'},
			{name:'wr_3',index:'wr_3', width:70, align:'center'},
			{name:'wr_1',index:'wr_1', width:70, align:'center'},
			{name:'wr_2',index:'wr_2', width:70, align:'center'},
			{name:'wr_4',index:'wr_4', width:70, align:'center'},
			{name:'wr_5',index:'wr_5', width:70, align:'center'},
			{name:'wr_6',index:'wr_6', width:75, align:'center'},
			{name:'wr_7',index:'wr_7', width:75, align:'center'},
			{name:'wr_8',index:'wr_8', width:70, align:'center'},
			
		],
		loadui: 'enable',
		height: 390,
		autowidth:true,
		rowNum : 50, // 기본 페이지 줄수
		formatoptions: {decimalSeperator : ','}, // 숫자에 콤마 찍기
		rowList : [10,15,20,25,30,35,40,50,70,100],
		emptyrecords : "<span class='txt_red txt_bold'>검색된 자료가 없습니다.</span>",
		pager: '#pager2', // 페이지정보 위치
		sortname: 'wr_num, wr_reply',  // 기본 정렬 
		sortorder: "asc", // 기본정렬순서
		viewrecords: true, // 우측 하단 레코드 정보.(페이지등/총갯수)
		shrinkToFit: false, // 데이타 가로 길이 제한 없앰
		gridview : true,		
		multiselect: true,
		scroll:true, // 페이지 넘김방식이 아닌 다음페이지의 리스트를 현재리스트아래쪽으로 계속해서 붙임.
		editurl:delete_url,
		jsonReader: { repeatitems : false },
		mtype:'POST', // 전송방식 (검색시 또는 페이지 이동시
		postData: {
				bo_table:$("#bo_table").val(),
				sfl : $("#sfl").val(),
				stx : $("#stx").val(),
				fd : $("#fd").val(),
				td : $("#td").val(),
				fd10 : $("#fd10").val(),
				td10 : $("#td10").val(),
				w1 : function() {
					var id = $("#fm_search2 input[name='w1[]']:checkbox:checked").map(function() {
						return $(this).val();
					}).get();
					return id;
				},
				wr2 : $("#wr2").val(),
				wr3 : $("#wr3").val(),
				wr4 : $("#wr4").val(),
				wr5 : $("#wr5").val(),
				wr6 : $("#wr6").val(),
				wr7 : $("#wr7").val(),
				wr8 : $("#wr8").val(),
				wr9 : $("#wr9").val(),
				wr10 : $("#wr10").val(),
				cn : $("#cn").val(),

		},
		caption: "&nbsp;",
		ondblClickRow: function (rowId, iRow, iCol, e) {  // 더블클릭 이벤트
			ahref(rowId); // 셀 더블클릭시 글상세보기
		},
		onSelectAll: function(aRowids, status) { // 전체선택 클릭시
			var uid = $("#list2").jqGrid('getGridParam','selarrrow');
			$("#chk_cnt").text(uid.length).css({"color":"red","font-weight":"bold"});
		},
		onSelectRow: function(aRowids, status) { // 단일 선택시
			var uid = $("#list2").jqGrid('getGridParam','selarrrow');
			$("#chk_cnt").text(uid.length).css({"color":"red","font-weight":"bold"});
		},
		onCellSelect: function (rowId, columnIndex, cellcontent, e) { // 클릭(선택)
   
			var cm = $("#list2").jqGrid('getGridParam','colModel');
			switch (cm[columnIndex].name) {
				case "wr_subject" : // 제품명(모델명) 원클릭시 글보기 화면으로
					ahref(rowId);
					break;
			}
		},
		loadComplete : function(data) {

			// 자료 불러오기 성공시 resetn 값과 records 값 체크
			if(data.resetn=="Y" && data.records>0 && data.page==1) {
				// 엑셀저장버튼 보여줌
				$("#excel, #print").show(); 
			} else {
				// 엑셀저장버튼 숨김
				$("#excel, #print").hide(); 
			}

			if(data.records==0) { // 검색자료 0
				popup_msg("검색된 자료가 없습니다");
			}
			// 캡션 부분에 페이지 정보 넣기.
			if(data.records>0) {
				$(".ui-jqgrid-title").text("자료수 : "+number_format(data.records)+" 개 (현재 "+data.page+" 페이지 / 총 "+data.total+" 페이지)").css("font-weight","normal");
			} else {
				$(".ui-jqgrid-title").text("검색된 자료가 없습니다.").css({"font-weight":"bold"});
			}
			
			// 그리드가 로드된후에는 체크박스가 항상 비체크이므로 체크박스 카운트를 초기화 한다.
			$("#chk_cnt").text('0');

		},
		
	});

	// Grid 하단에 기능 버튼 추가.
	$("#list2").jqGrid('navGrid','#pager2',
		{ edit:false, add:false, del:true, search:false, refresh:true },
		{}, // edit
		{}, // add
		{ 
			width:500, // 자료 삭제시 창 크기
			height:150
		}, // del
		{},// search
		{} //refresh

	);

	// 검색 - 초기화버튼 클릭시
	$("#reset").click(function() {
		$("#fm_search2 input, select")
			.not(':button, :submit, :reset, :hidden, :checkbox')
			.val('')
			.removeAttr('checked')
			.removeAttr('selected');
		$("#fm_search2 input[type='checkbox']").removeAttr('checked'); // 체크박스 체크해제
		Searching('reset');
		popup_msg("초기화 완료");
		
	});
	
	// 검색버튼 클릭시
	$('#btn_submit').click(function() {
		Searching(); // 검색(POST)
	});

	// 검색 - 검색어 입력후 엔터키 입력시 검색 실행
	$("#stx").keypress(function (e) {
		if (e.which == 13){
			$("#btn_submit").trigger("click");
		}
	});

	/* 달력 끝 */
	$(function(){
		$("#fd, #td, #fd10, #td10").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-10:c+10", minDate: "", maxDate: "" });
	});
	/* 달력 끝 */

});

// 검색 - 검색박스 POST 실행
function Searching(z) {
	$("#list2").jqGrid('clearGridData'); // 그리드를 비움
	$("#list2").jqGrid('setGridParam', { 
		mtype:"POST",
		postData : {
				bo_table:$("#bo_table").val(),
				sfl : $("#sfl").val(),
				stx : $("#stx").val(),
				fd : $("#fd").val(),
				td : $("#td").val(),
				fd10 : $("#fd10").val(),
				td10 : $("#td10").val(),
				w1 : function() {
					var id = $("#fm_search2 input[name='w1[]']:checkbox:checked").map(function() {
						return $(this).val();
					}).get();
					return id;
				},
				w2 : $("#w2").val(),
				w3 : $("#w3").val(),
				w4 : $("#w4").val(),
				w5 : $("#w5").val(),
				w6 : $("#w6").val(),
				w7 : $("#w7").val(),
				w8 : $("#w8").val(),
				w9 : $("#w9").val(),
				w10 : $("#w10").val(),
				cn : $("#cn").val(),
		}
	});
	$('#list2').trigger("reloadGrid",
		[{current: true}]
	);
}; 

// 엑셀저장 및 PDF인쇄.
function prInt(mode) {
	var string = $("#fmSearch").serialize();
	var bo_table = $("#bo_table").val();
	switch(mode) {
		case "excel" : // 엑셀파일 저장
			location.href=board_skin_url+'/excel.php?bo_table='+bo_table+'&'+string+'&mode=excel';
			break;
		case "pdf" : // PDF 기본 인쇄
			pdf_print('pdf_print.php?bo_table='+bo_table+'&'+string); // 다양한 인쇄 약식을 위해 프린트약식 파일을 직접 지정.
			break;
	}
};

// PDF 인쇄
function pdf_print(url) {
	var file_url = board_skin_url+"/"+url;
	newWin(file_url,740,600,1,0);
}


// 글수정
function ahref(wr_id) {
	var string = $("#fmSearch").serialize();
	var bo_table = $("#bo_table").val();
	location.href="./board.php?bo_table="+bo_table+"&wr_id="+wr_id+"&"+string;
}

// 레이어 팝업 및 경고창
function popup_msg(msg) {
	$('#popup_msg').text(msg);
	$("#popup_msg").css("top",Math.max(0,(($(window).height()-$("#popup_msg").outerHeight())/2)+$(window).scrollTop())+"px");
	$("#popup_msg").css("left",Math.max(0,(($(window).width()-$("#popup_msg").outerWidth())/2) + $(window).scrollLeft())+"px");
	setTimeout(function() {
		$('#popup_msg').fadeIn(500);
		setTimeout(function() {
			$('#popup_msg').fadeOut(500);
		}, 1500);
	},150);
}

//새창.
function newWin(file,width,height,scroll,resize){
	var leftPoint = (screen.width/2)-(width/2);
	var topPoint = (screen.height/2)-(height/2);
	scroll = (scroll =='' )? 'no' : scroll;
	resize = (resize =='' )? 'no' : resize;
	window.open (file,"","scrollbars="+scroll+",resizable="+resize+",width="+width+",height="+height+",top="+topPoint+",left="+leftPoint);
}
