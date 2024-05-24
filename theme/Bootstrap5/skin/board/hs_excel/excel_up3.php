<?php
include_once('./_common.php');

// 상품이 많을 경우 대비 설정변경
set_time_limit(0);
ini_set('memory_limit', '50M');

$file = $_FILES['excelfile']['tmp_name'];

include_once(G5_LIB_PATH.'/Excel/reader.php');

$data = new Spreadsheet_Excel_Reader();

// Set output Encoding.
$data->setOutputEncoding('UTF-8');

$data->read($board_skin_path."/order.xls");

/*


 $data->sheets[0]['numRows'] - count rows
 $data->sheets[0]['numCols'] - count columns
 $data->sheets[0]['cells'][$i][$j] - data from $i-row $j-column

 $data->sheets[0]['cellsInfo'][$i][$j] - extended info about cell

    $data->sheets[0]['cellsInfo'][$i][$j]['type'] = "date" | "number" | "unknown"
        if 'type' == "unknown" - use 'raw' value, because  cell contain value with format '0.00';
    $data->sheets[0]['cellsInfo'][$i][$j]['raw'] = value if cell without format
    $data->sheets[0]['cellsInfo'][$i][$j]['colspan']
    $data->sheets[0]['cellsInfo'][$i][$j]['rowspan']
*/

error_reporting(E_ALL ^ E_NOTICE);

$write_table = "g5_write_{$bo_table}";

for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) { //$i == 몇번째 라인부터 등록할건지 
    $total_count++;

    $mb_id = $member['mb_id'];
    $wr_name = $member['mb_nick'];
    $wr_password = $member['mb_password'];
    $wr_email = $member['mb_email'];
    $wr_homepage = $member['mb_homepage'];

    $wr_num = get_next_num($write_table);
    $wr_reply = '';

    $wr_subject    = addslashes($data->sheets[0]['cells'][$i][2]);    //필드1
    $wr_1    = addslashes($data->sheets[0]['cells'][$i][3]);    //필드1
    $wr_2    = addslashes($data->sheets[0]['cells'][$i][4]);    //필드2
    $wr_3    = addslashes($data->sheets[0]['cells'][$i][5]);    //필드3
    $wr_4    = addslashes($data->sheets[0]['cells'][$i][6]);    //필드4
    $wr_5    = addslashes($data->sheets[0]['cells'][$i][7]);    //필드5

    $wr_6    = addslashes($data->sheets[0]['cells'][$i][8]);    //필드6
    $wr_7    = addslashes($data->sheets[0]['cells'][$i][9]);    //필드7
    $wr_8    = addslashes($data->sheets[0]['cells'][$i][10]);    //필드8
    $wr_9    = addslashes($data->sheets[0]['cells'][$i][11]);    //필드9
    $wr_10   = addslashes($data->sheets[0]['cells'][$i][12]);    //필드10

    $sql = " insert into $write_table
            set wr_num = '$wr_num',
                 wr_reply = '$wr_reply',
                 wr_comment = 0,
                 ca_name = '$ca_name',
                 wr_option = '$html,$secret,$mail',
                 wr_subject = '$wr_subject',
                 wr_content = '$wr_subject',
                 wr_link1 = '$wr_link1',
                 wr_link2 = '$wr_link2',
                 wr_link1_hit = 0,
                 wr_link2_hit = 0,
                 wr_hit = 0,
                 wr_good = 0,
                 wr_nogood = 0,
                 mb_id = '$mb_id',
                 wr_password = '$wr_password',
                 wr_name = '$wr_name',
                 wr_email = '$wr_email',
                 wr_homepage = '$wr_homepage',
                 wr_datetime = '".G5_TIME_YMDHIS."',
                 wr_last = '".G5_TIME_YMDHIS."',
                 wr_ip = '{$_SERVER['REMOTE_ADDR']}',
                 wr_1 = '$wr_1',
                 wr_2 = '$wr_2',
                 wr_3 = '$wr_3',
                 wr_4 = '$wr_4',
                 wr_5 = '$wr_5',
                 wr_6 = '$wr_6',
                 wr_7 = '$wr_7',
                 wr_8 = '$wr_8',
                 wr_9 = '$wr_9',
                 wr_10 = '$wr_10'";

    // Perform a query on the database
    mysqli_query($conn, $sql);

    $wr_id = mysqli_insert_id($conn);

    // 부모 아이디에 UPDATE
    mysqli_query($conn, " update $write_table set wr_parent = '$wr_id' where wr_id = '$wr_id' ");

    // 새글 INSERT
    mysqli_query($conn, " insert into {$g5['board_new_table']} ( bo_table, wr_id, wr_parent, bn_datetime, mb_id ) values ( '{$bo_table}', '{$wr_id}', '{$wr_id}', '".G5_TIME_YMDHIS."', '{$mb_id}' ) ");

    // 게시글 1 증가
    mysqli_query($conn, "update {$g5['board_table']} set bo_count_write = bo_count_write + 1 where bo_table = '{$bo_table}'");

}//for end

alert("업로드 되었습니다",G5_BBS_URL."/board.php?bo_table=".$bo_table);
?>
