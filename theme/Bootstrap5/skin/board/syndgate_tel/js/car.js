$(function(){
	$(Grid.list).jqGrid({
		url : Grid.editUrl,
        datatype : "json",
		colNames:['자산번호','wr_id', '모델명', '제조사', '시리얼','상세정보'],
		colModel : [
			{ name:'num',index:'num', width:50, align:'center', sortable: false, formatter:'integer'},
			{ name:'wr_id',index:'wr_id', width:40, align:'center', key:true, hidden:true },
			{ name:'wr_subject',index:'wr_subject', width:300, align:'center'},
			{ name:'wr_content',index:'wr_content', width:100, align:'center' },
			{ name:'wr_1',index:'wr_1', width:120, align:'center' },
			{ name:'wr_2',index:'wr_2', width:310, align:'center' },
		],
		loadui: 'enable',
		height: 450,
		autowidth:true,
		rowNum : 25, // 기본 페이지 줄수
		formatoptions: {decimalSeperator : ','}, // 숫자에 콤마 찍기
		rowList : [10,15,20,25,30,35,40,50,70,100],
		emptyrecords : "<span class='txt_red txt_bold'>검색된 자료가 없습니다.</span>",
		pager: Grid.pager, // 페이지정보 위치
		sortname: 'a.wr_id',  // 기본 정렬 
		sortorder: "desc", // 기본정렬순서
		viewrecords: true, // 우측 하단 레코드 정보.(페이지등/총갯수)
		shrinkToFit: false, // 데이타 가로 길이 제한 없앰
		gridview : true,		
		multiselect: true, // 체크박스 (다중선택)
		scroll:true,
		editurl:Grid.editUrl, // 선택삭제
		jsonReader: { repeatitems : false },
		mtype:'POST', // 전송방식 (검색시 또는 페이지 이동시
		postData: {
			"wr_id" : $("#wr_id").val(),
			"wr_1" : $("#wr_1").val(),
			"sfl" : $("#sfl").val(),
			"stx" : $("#stx").val()
		},
		caption: "&nbsp;",
        ondblClickRow: function (rowId, iRow, iCol, e) {  // 더블클릭 이벤트
		// 선택한자료의 회원아이디불러오기
            memberadd(rowId);
		},
		onCellSelect: function (rowId, columnIndex, cellcontent, e) { // 클릭(선택)

		},
		loadComplete : function(data) {
            
			// 캡션 부분에 페이지 정보 넣기.
			if(data.records>0) {
				$(".ui-jqgrid-title").text("자료수 : "+number_format(data.records)+" 개 (현재 "+data.page+" 페이지 / 총 "+data.total+" 페이지)    상세보기 - 더블클릭").css("font-weight","normal");
			} else {
				$(".ui-jqgrid-title").text("검색된 자료가 없습니다.").css({"font-weight":"bold"});
			}
		},
		
	});

	// 필수 : Grid 하단에 기능 버튼 추가.
	$(Grid.list).jqGrid('navGrid',Grid.pager,
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

	// 필수 : 검색 - 초기화버튼 클릭시
	$("#reset").click(function() {
		$(Grid.search+" input, select")
			.not(':button, :submit, :reset, :hidden')
			.val('')
			.removeAttr('checked')
            .removeAttr('selected');
        $("#sfl option:eq(0)").attr("selected", "selected");
		Searching('reset');
	});
	
	// 필수 : 검색버튼 클릭시
	$('#btn_submit').click(function() {
		Searching(); // 검색(POST)
	});

	// 필수 : 검색 - 검색어 입력후 엔터키 입력시 검색 실행
	$("#stx").keypress(function (e) {
		if (e.which == 13){
			$("#btn_submit").trigger("click");
			return false;
		}
	});
    
    $("#doc_list").click(function() {
        location.href = '?bo_table='+Grid.bo_table;
    });

    $("#doc_xls").click(function() {
        location.href = theme_url +'/xlss.php?bo_table='+Grid.bo_table;
    });
});
// 필수함수 : 검색 - 검색박스 POST 실행 
function Searching(z) {
	$(Grid.list).jqGrid('clearGridData');
	$(Grid.list).jqGrid('setGridParam', { 
		mtype:"POST",
		postData : {
			"sfl" : $("#sfl").val(),
			"stx" : $("#stx").val()
		}
	});
	$(Grid.list).trigger("reloadGrid",
		[{current: false}] //// 새로고침시 현재 체크상태 유지(true) / 비유지(false)
	);
}; 

// 필수함수 : 글수정시 (페이지 이동)
function ahref(wr_id) {
	var string = $(Grid.form).serialize();
	location.href=g5_bbs_url+"/board.php?bo_table="+Grid.bo_table+"&wr_id="+wr_id+"&"+string;
}

// 필수함수 : 그리드영역 새로고침 : 팝업창 닫을때 새로고침할때 사용되는 함수.
function jqGridReload() {
    $(Grid.list).trigger("reloadGrid",
		[{current: true}] //// 새로고침시 현재 체크상태 유지(true) / 비유지(false)
	);
}

