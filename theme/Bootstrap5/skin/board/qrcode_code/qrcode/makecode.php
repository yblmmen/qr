<?php
include_once("./_common.php");
include_once("qrlib.php");

if (empty($board['bo_table'])) {
    echo "
		<script type='text/javascript'>
		<!--
			alert('Board does not exist.');
			this.close();
		//-->
		</script>";
    exit;
}

if (empty($bo_table)) {
    echo "
		<script type='text/javascript'>
		<!--
			alert('Value does not exist.');
			this.close();
		//-->
		</script>";
    exit;
}

if (empty($write['wr_id'])) {
    echo "
		<script type='text/javascript'>
		<!--
			alert('Post does not exist.');
			this.close();
		//-->
		</script>";
    exit;
}

// 전달값 처리
$qrcode_url = G5_BBS_URL . '/board.php?' . $_SERVER['QUERY_STRING'];
$filename = $write['wr_id'] . '.png';
$errorCorrectionLevel = 'H';
$matrixPointSize = 8;
$filedir = $bo_table;

// 디렉토리 경로 생성
$qrcode_path = G5_DATA_PATH . '/wzqrcode';
$qrcode_save_path = G5_DATA_PATH . '/wzqrcode/' . $bo_table;
$qrcode_save_url = G5_DATA_URL . '/wzqrcode/' . $bo_table;
$qrcode_full_path = $qrcode_save_path . '/' . $filename;

// 코드 이미지는 한 번만 생성
if (!file_exists($qrcode_full_path)) {
    if (!is_dir($qrcode_path)) {
        @mkdir($qrcode_path, G5_DIR_PERMISSION);
        @chmod($qrcode_path, G5_DIR_PERMISSION);
    }

    if (!is_dir($qrcode_save_path)) {
        @mkdir($qrcode_save_path, G5_DIR_PERMISSION);
        @chmod($qrcode_save_path, G5_DIR_PERMISSION);
    }

    // 코드 생성
    QRcode::png($qrcode_url, $qrcode_full_path, $errorCorrectionLevel, $matrixPointSize, 2);
}

echo '<img src="' . $qrcode_save_url . '/' . $filename . '" class="qrcode-image" border="0" />';
?>
