<?php
include_once("./_common.php");

$startDate = $_POST['startDate'];
$endDate = $_POST['endDate'];
$checkID = $_POST['checkID'];

if(!$startDate || !$endDate || $checkID != $_SESSION['rumi_fullcalendar_ss']) {
    exit;
}

$sql = "SELECT
            *
        FROM
            `cm_lunar`
        WHERE
            `soldate` BETWEEN '{$startDate}' AND '{$endDate}' ";
$result = sql_query($sql, FALSE);

$list = array();
while($row=sql_fetch_array($result)) {
    $lunleap = ($row['lunleap'] == 1) ? "윤" : "";
    $list[] = array(
        "date" => $row['soldate'],
        "day" => $row['lunmonth'].".".$row['lunday'].$lunleap,
        "holiday" => $row['holiday1']
    );
}

echo json_encode($list);