<?php
include_once('./_common.php');

$id = isset($_POST["id"]) ? intval($_POST["id"]) : 0;

$wr_subject = html_purifier($_POST["wr_subject"]);
$wr_content = html_purifier($_POST["wr_content"]);
$wr_name = html_purifier($_POST["wr_name"]);

for ($i=1; $i<=10; $i++) {
    $var = "wr_".$i;
    $$var = "";
    if (isset($_POST['wr_'.$i]) && settype($_POST['wr_'.$i], 'string')) {
        $$var = html_purifier(trim($_POST['wr_'.$i]));
    }
}

    $sql = " update {$write_table}
                set wr_subject = '{$wr_subject}',
                     wr_content = '{$wr_content}',
                     wr_name = '{$wr_name}',
                     wr_1 = '{$wr_1}',
                     wr_2 = '{$wr_2}',
                     wr_3 = '{$wr_3}',
                     wr_4 = '{$wr_4}',
                     wr_5 = '{$wr_5}',
                     wr_6 = '{$wr_6}',
                     wr_7 = '{$wr_7}',
                     wr_8 = '{$wr_8}',
                     wr_9 = '{$wr_9}',
                     wr_10= '{$wr_10}'
              where wr_id = '{$id}' ";
sql_query($sql);

$wr_1 = substr($wr_1,0,8)."01";
echo json_encode(array("tdate"=>$wr_1, "ok"=>"ok"));