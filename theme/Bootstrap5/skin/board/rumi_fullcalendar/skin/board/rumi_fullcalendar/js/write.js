$(function() {
    /*
    if(w == 'r') { // 글 답변시
        $("div.rumiButton button:contains('취소')", parent.document).show();
        $("div.rumiButton button:contains('수정')", parent.document).hide();
    } else if(w == 'u') { // 글수정시
        $("div.rumiButton button:contains('취소')", parent.document).show();
        $("div.rumiButton button:contains('수정')", parent.document).hide();
    } else {
        $("div.rumiButton button:contains('취소')", parent.document).hide();
        $("div.rumiButton button:contains('수정')", parent.document).hide();
    }

    $("div.rumiButton button:contains('삭제')", parent.document).hide();
    $("div.rumiButton button:contains('저장')", parent.document).show();
    */

    $('.frm-calendar').datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: 'yy-mm-dd',
		showButtonPanel: true,
		yearRange: 'c-60:c+5',
		minDate: '',
		maxDate: '',
		beforeShow: function() {
			setTimeout(function(){
				$('.ui-datepicker').css('z-index', 100);
			}, 0);
		},
		onSelect: function(dateText) {
        	console.log("Selected date: " + dateText + "; input's current value: " + this.value);
    	}

    });

    $("#bo_w").on('click', '.btn-color', function() {
        var id = $(this).attr('data-id');
        var color = $(this).attr('data-color');
        if(id == "background") {
            $(".color-sample").css({"background":color});
            $('#wr_4').val(color);
        } else {
            $(".color-sample").css({"color":color});
            $('#wr_3').val(color);
        }
    });

    $(".user-color").click(function() {
        jscolor.installByClassName("jscolor");
    });

    $(".user-color").blur(function() {
        var id = $(this).attr("data-id");
        var color = $(this).val();
        if(id == "background") {
            $(".color-sample").css({"background":color});
            $('#wr_4').val(color);
        } else {
            $(".color-sample").css({"color":color});
            $('#wr_3').val(color);
        }
    });

    // 종일 선택시
    $('#allday').click(function() {
        var chk = $(this).is(':checked');
        if(chk) {
            $('.stime').prop({'required':false, 'disabled':true});
        } else {
            $('.stime').prop({'required':true, 'disabled':false});
        }
    });

    // 반복 선택
    $('.repeat').click(function() {
        var chk = $(this).val();
        repeatChk(chk);
    });

    if(w=="u") {
        var chk = $('.repeat:checked').val();
        console.log(chk);
        repeatChk(chk);
    }
});


function repeatChk(chk) {
    switch(chk) {
        case '10' :
        case '20' :
        case '80' :
        case '90' :
            $('.repeatCycle').css('display', 'none');
            $('.repeatExpire').css('display', 'flex');
            $('#wr_7').prop('required', true);
            $('#wr_8').prop('required', false);
            break;
        case '1' :
        case '30' :
        case '99' :
            $('.repeatCycle').css('display', 'flex');
            $('.repeatExpire').css('display', 'flex');
            $('#wr_7').prop('required', true);
            $('#wr_8').prop('required', true);
            break;
        default :
            $('.repeatCycle').css('display', 'none');
            $('.repeatExpire').css('display', 'none');
            $('#wr_7').prop('required', false);
            $('#wr_8').prop('required', false);
            break;
    }
}