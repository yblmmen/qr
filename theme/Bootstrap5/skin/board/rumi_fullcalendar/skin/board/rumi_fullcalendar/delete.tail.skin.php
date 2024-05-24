<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
include_once($board_skin_path.'/config.php');
if($popup=="popup") {
?>
    <script>
    parent.calendarRefresh(); // 창닫을때 이벤트 업데이트
    parent.rumiPopup.close();
    </script>
    <?php
    exit;
}