$(function(){
	$(Grid.list).jqGrid({
		url : Grid.editUrl,
        datatype : "json",
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
			"fd" : $("#fd").val(),
			"td" : $("#td").val(),
			"ca_name" : $("#ca_name").val(),			
			"sfl" : $("#sfl").val(),
			"stx" : $("#stx").val()
		},
		caption: "&nbsp;",
		ondblClickRow: function (rowId, iRow, iCol, e) {  // 더블클릭 이벤트
			var vi = $(Grid.list).jqGrid('getRowData', rowId).view;
			var mode = vi.replace(/[^\uAC00-\uD7AF\u1100-\u11FF\u3130-\u318F]/gi,"");
			if(mode=="열람불가") {
				alert("열람 권한이 없습니다.");
				return false;
			}

			var vi = $(Grid.list).jqGrid('getRowData', rowId).wr_3;
			var mode = vi.replace(/[^\uAC00-\uD7AF\u1100-\u11FF\u3130-\u318F]/gi,"");
	
			ahref(rowId,'','');
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

	// Grid 하단에 기능 버튼 추가.
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

	// 검색 - 초기화버튼 클릭시
	$("#reset").click(function() {
		$(Grid.search+" input, select")
			.not(':button, :submit, :reset, :hidden')
			.val('')
			.removeAttr('checked')
			.removeAttr('selected');
		$("#sfl option:eq(0)").attr("selected", "selected");
		Searching('reset');
		//popup_msg("초기화 완료");
		
	});
	
	// 검색버튼 클릭시
	$('#btn_submit').click(function() {
		Searching(); // 검색(POST)
	});

	// 검색 - 검색어 입력후 엔터키 입력시 검색 실행
	$("#stx").keypress(function (e) {
		if (e.which == 13){
			$("#btn_submit").trigger("click");
			return false;
		}
	});

	$(document).on("click", ".cateBtns", function() {
		var cate = $(this).attr("data-id");
		$("#ca_name").val(cate);
		$("#btn_submit").trigger("click");		
		$(".cateBtns").removeClass("active");
		$(this).addClass("active");
	});


	$(".sch_M").click(function() {
		$(".sch_M").removeClass("active");
		$(this).addClass("active");
	});

	
});
// 검색 - 검색박스 POST 실행
function Searching(z) {
	$(Grid.list).jqGrid('clearGridData');
	$(Grid.list).jqGrid('setGridParam', { 
		mtype:"POST",
		postData : {
			"fd" : $("#fd").val(),
			"td" : $("#td").val(),
			"ca_name" : $("#ca_name").val(),			
			"sfl" : $("#sfl").val(),
			"stx" : $("#stx").val()
		}
	});
	$(Grid.list).trigger("reloadGrid",
		[{current: false}] //// 새로고침시 현재 체크상태 유지(true) / 비유지(false)
	);
}; 

// 글수정
function ahref(wr_id) {
	var string = $(Grid.form).serialize();
	location.href=g5_bbs_url+"/board.php?bo_table="+Grid.bo_table+"&wr_id="+wr_id+"&"+string;
	$.ajax({
		type : 'POST',
		url : Grid.board_skin_url+"/ajax.pretty_url.php",
		data : {
			"bo_table" : Grid.bo_table,
			"wr_id" : wr_id,
			"qstr" : string
		},
		dataType: "json",
		async: false,
		cache: false,
		error : function(error) {
			//alert("Error!");
		},
		success : function(data) {
			if(data) {
				var url = data.replace(/&amp;/g, '&');			
				location.href = url;
			}
		},
		complete : function() {
		}
	});
}

