<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if($wt=="popup") {
    $return_url = $board_skin_url."/view.php?bo_table=".$bo_table."&wr_id=".$wr_id."&wt=popup";
} else {
    $return_url = G5_BBS_URL."/board.php?bo_table=".$bo_table."&wr_id=".$wr_id;
}

goto_url($return_url);

