$(function() {
	$('.block').on('click', function() {
		if (confirm('이 회원을 차단 하시겠습니까?'))
		{
			$.ajax({
				url: g5_theme_api_url,
				type: 'post',
				contentType: 'application/json',
				data: JSON.stringify({'order': 'block', 'mb_id':$(this).data('id')}),
				dataType: 'json',
				success: function(data) {
					alert(data.msg);
				},
				error: function() {
					alert('오류가 발생하였습니다.\n\n잠시 후 다시 시도해 주세요.');
				}
			});
		}

		return false;
	});

	$('.report').on('click', function() {
		if (confirm('이 게시물을 신고 하시겠습니까?\n\n신고는 취소가 불가합니다.\n\n주의) 허위 신고시 신고자의 서비스 이용이 제한됩니다.'))
		{
			$.ajax({
				url: g5_theme_api_url,
				type: 'post',
				contentType: 'application/json',
				data: JSON.stringify({'order': 'report', 'bo_table':g5_bo_table, 'wr_id':$(this).data('id')}),
				dataType: 'json',
				success: function(data) {
					alert(data.msg);
				},
				error: function() {
					alert('오류가 발생하였습니다.\n\n잠시 후 다시 시도해 주세요.');
				}
			});
		}

		return false;
	});
});
