<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
include_once($board_skin_path.'/config.php');
if($popup=="popup") {
    delete_cache_latest($bo_table);
    goto_url($board_skin_url.'/view.php?bo_table='.$bo_table.'&amp;wr_id='.$write['wr_parent'].'&amp;page='.$page. $qstr);
}