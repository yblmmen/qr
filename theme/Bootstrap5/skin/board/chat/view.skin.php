<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가
include_once(G5_LIB_PATH.'/thumbnail.lib.php');
?>

<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>게시글 보기</title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>

<div id="write_result"></div>

<script>
$(document).ready(function(){
    $.ajax({
        url: 'write.php',
        type: 'POST',
        dataType: 'json',
        success: function(data){
            // 성공적으로 데이터를 받아왔을 때의 처리
            $('#write_result').html('<p>게시글이 작성되었습니다.</p>');
            console.log(data); // 데이터 확인을 위한 콘솔 로그
        },
        error: function(xhr, status, error){
            // 데이터를 받아오는 도중 에러가 발생했을 때의 처리
            $('#write_result').html('<p>게시글 작성에 실패했습니다.</p>');
            console.error(xhr); // 에러 확인을 위한 콘솔 로그
        }
    });
});
</script>

</body>
</html>
