<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
$wr_1 = ($_POST['wr_5']) ? $_POST['wr_1']." 00:00:00" : $_POST['wr_1']." ".$_POST['fr_h'].":".$_POST['fr_m'].":00";
$wr_2 = ($_POST['wr_5']) ? $_POST['wr_2']." 23:59:59" : $_POST['wr_2']." ".$_POST['to_h'].":".$_POST['to_m'].":00";
$wr_7 = ($_POST['wr_7']) ? $_POST['wr_7']." 23:59:59" : "";