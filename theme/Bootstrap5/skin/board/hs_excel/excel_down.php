<?php
include_once('./_common.php');

$csv = 'xls';

// MS엑셀 XLS 데이터로 다운로드 받음
if ($csv == 'xls')
{
    $searches = '';

    if($_GET['stx']) { 
        $searches .= "and (wr_subject = '{$_GET['stx']}' or wr_content = '{$_GET['stx']}' or wr_1 = '{$_GET['stx']}' or wr_2 = '{$_GET['stx']}' or wr_3 = '{$_GET['stx']}'  or wr_3 = '{$_GET['stx']}'  or wr_3 = '{$_GET['stx']}')";
    }

    if($_GET['fr_date'] && $_GET['to_date']){

        $fr_date_a = $_GET['fr_date']." 00:00:00";
        $to_date_a = $_GET['to_date']." 23:59:59";

        $searches .= "and (wr_datetime between '{$fr_date_a}' and '{$to_date_a}') ";
    }

    $sql = " SELECT * FROM g5_write_{$bo_table} where wr_is_comment = '0' {$searches}";
    $result = sql_query($sql);
    $cnt = mysqli_num_rows($result);
    if (!$cnt)
        alert("출력할 내역이 없습니다.");

    /*================================================================================
    php_writeexcel http://www.bettina-attack.de/jonny/view.php/projects/php_writeexcel/
    =================================================================================*/

    include_once(G5_LIB_PATH.'/Excel/php_writeexcel/class.writeexcel_workbook.inc.php');
    include_once(G5_LIB_PATH.'/Excel/php_writeexcel/class.writeexcel_worksheet.inc.php');

    $fname = tempnam(G5_DATA_PATH, $board.".xls");
    $workbook = new writeexcel_workbook($fname);
    $worksheet = $workbook->addworksheet();

    // Put Excel data
    $data = array('No','id(수정금지)','날짜','자산번호','제조사','모델명','시리얼','내용','기타');
    $data = array_map('iconv_euckr', $data);

    $col = 0;
    foreach($data as $cell) {
        $worksheet->write(0, $col++, $cell);
    }


    for($i=1; $row=sql_fetch_array($result); $i++)
    {
        $row = array_map('iconv_euckr', $row);
        
        $up_date = date("y/m/d", strtotime($row['wr_datetime']));
         $worksheet->write($i, 0, $i);
         $worksheet->write($i, 1, $row['wr_id']);
         $worksheet->write($i, 2, $up_date);
         $worksheet->write($i, 3, $row['wr_subject']);
         $worksheet->write($i, 4, $row['wr_1']);
         $worksheet->write($i, 5, $row['wr_2']);
         $worksheet->write($i, 6, $row['wr_3']);
         $worksheet->write($i, 7, $row['wr_4']);
         $worksheet->write($i, 8, $row['wr_5']);
         /*
         $worksheet->write($i, 9, $row['wr_6']);
         $worksheet->write($i, 10, $row['wr_7']);
         $worksheet->write($i, 11, $row['wr_8']);
         $worksheet->write($i, 12, $row['wr_9']);
         $worksheet->write($i, 13, $row['wr_10']);
         */
    }

    $workbook->close();
    
    header("Content-charset=utf-8");
    header("Content-Type: application/x-msexcel; name=\"{$bo_table}-".date("ymd", time()).".xls\"");
    header("Content-Disposition: inline; filename=\"{$bo_table}-".date("ymd", time()).".xls\"");
    $fh=fopen($fname, "rb");
    fpassthru($fh);
    unlink($fname);

    exit;
}

if (mysqli_num_rows($result) == 0)
{
    echo "<script>alert('출력할 내역이 없습니다.'); window.close();</script>";
    exit;
}
?>
