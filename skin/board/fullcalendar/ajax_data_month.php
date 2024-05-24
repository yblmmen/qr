<?php
include_once('./_common.php');

$accessCode = !empty($board['bo_1']) ? intval($board['bo_1']) : 0;

$tdate = isset($_POST['tdate']) ? html_purifier($_POST['tdate']) : "";

if($tdate == "") {
	$yymm =  date("Y-m");
} else {
	$tdate = date("Y-m-d", strtotime("{$tdate} +7 days"));
	$yymm =  substr($tdate,0,7);
}

$que1 = sql_query("select wr_subject,wr_name,wr_1,wr_2,wr_content from {$write_table} where left(wr_1,7) = '{$yymm}' order by wr_1,wr_subject");
$x=0;
?>
<table class='table table-hover align-middle table-bordered text-center'>
	<tr class='table-light text-center'>
		<th width="170">일정</th>
<?php if($accessCode == 1) { ?>
		<th>사용자</th>
<?php } ?>
		<th>제목</th>
		<th>내용</th>
	</tr>
<?php
foreach($que1 as $field) {
$x++;

$c1 = substr($field['wr_1'],0,10);
$c2 = substr($field['wr_2'],0,10);
$d2 = date("Y-m-d",strtotime("{$c2} -1 day"));

if(strlen($field['wr_1']) < 12) {
	if($c1 == $d2) {
		$rdate = date("y.m.d",strtotime(substr($field['wr_1'],0,10)));
	} else {
		$rdate = date("y.m.d",strtotime(substr($field['wr_1'],0,10)))."~".date("m.d",strtotime($d2));
	}
} else {
	$rdate = date("y.m.d",strtotime(substr($field['wr_1'],0,10)))." <span class='text-success'>(".substr($field['wr_1'],11,5)."~".substr($field['wr_2'],11,5).")</span>";
}
?>
<tr>
	<td class="text-start"><?= $rdate ?></td>
<?php if($accessCode == 1) { ?>
	<td><?= $field['wr_name'] ?></td>
<?php } ?>
	<td><?= $field['wr_subject'] ?></td>
	<td><?= $field['wr_content'] ?></td>
</tr>
<?php
}

if($x==0) {

echo "<tr>
	<td colspan='3' class='text-center'>자료가 없습니다.</td>
</tr>";
}
?>
</table>