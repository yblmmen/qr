<?php
include_once("_common.php");

if(!$is_admin) {
    die("접근 권한이 없습니다.");
}

include_once($board_skin_path.'/function.lib.php');

// 음력테이블 존재 여부.
$is_install = lunarTableCheck();

if($is_install) {
    die("{$table} 테이블이 존재합니다.");
}

$file = implode('', file('./cm_lunar.sql'));
eval("\$file = \"$file\";");
// var_dump($file);

$file = preg_replace('/^--.*$/m', '', $file);
$f = explode(';', $file);
for ($i=0; $i<count($f); $i++) {
    if (trim($f[$i]) == '') {
        continue;
    }

    $sql = get_db_create_replace($f[$i]);
    sql_query($sql, true, $dblink);
}

$response = new stdClass();
if($i > 0) {
    $response->rst = true;
    $response->msg = "음력데이터 테이블이 생성되었습니다.";
} else {
    $response->rst = false;
    $response->msg = "음력데이터 테이블 생성중 오류가 발생하였습니다.";
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);