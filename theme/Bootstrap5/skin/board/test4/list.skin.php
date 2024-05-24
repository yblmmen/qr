<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css" media="screen">', 0);

?>
    <link rel="stylesheet" href="https://kendo.cdn.telerik.com/2019.3.1023/styles/kendo.common-nova.min.css" />
    <link rel="stylesheet" href="https://kendo.cdn.telerik.com/2019.3.1023/styles/kendo.nova.min.css" />
    <link rel="stylesheet" href="https://kendo.cdn.telerik.com/2019.3.1023/styles/kendo.nova.mobile.min.css" />

    <script src="https://kendo.cdn.telerik.com/2019.3.1023/js/jquery.min.js"></script>
    <script src="https://kendo.cdn.telerik.com/2019.3.1023/js/jszip.min.js"></script>
    <script src="https://kendo.cdn.telerik.com/2019.3.1023/js/kendo.all.min.js"></script>

<section class="board-list<?php echo (G5_IS_MOBILE) ? ' font-14' : '';?>"> 




		<div id="example">
  <button class="k-button" id="save">저장</button>
    <button class="k-button" id="cancel">Cancel changes</button>
  <div id="spreadsheet" style="width: 100%;"></div>

  
    <script>
        $("#spreadsheet").kendoSpreadsheet();
    </script>