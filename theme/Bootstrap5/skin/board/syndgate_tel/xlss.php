<?php
header('Content-Type: application/vnd.ms-excel');
header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Content-Disposition: attachment; filename="list_' . date("ymd", time()) . '.xls"');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
header('content-transfer-encoding: binary');
$bo_table = $_GET['bo_table'];
include_once('_common.php');

//회원이냐?
if($member['mb_id']) {

$sql = "select * from $write_table where wr_is_comment = 0 order by wr_num";
$result = sql_query($sql);
$i=1;
while($row = sql_fetch_array($result)) {
	$list[$i]['자산번호'] = $i;
	$list[$i]['모델명'] = $row['wr_subject'];
	$list[$i]['제조사'] = $row['wr_content'];
	$list[$i]['시리얼'] = $row['wr_1'];
	$list[$i]['상세정보'] = $row['wr_2'];
	$i++;
}
?>
<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
<head>
<meta http-equiv=Content-Type content="text/html; charset=utf-8">
<meta name=ProgId content=Excel.Sheet>
<meta name=Generator content="Microsoft Excel 15">
<style id="통합 문서1_28651_Styles">
<!--table{mso-displayed-decimal-separator:"\.";mso-displayed-thousand-separator:"\,";}
.font528651{color:windowtext;font-size:8.0pt;font-weight:400;font-style:normal;text-decoration:none;font-family:"맑은 고딕", monospace;mso-font-charset:129;}
.xl6328651{padding-top:1px;padding-right:1px;padding-left:1px;mso-ignore:padding;color:black;font-size:10.0pt;font-weight:400;font-style:normal;text-decoration:none;font-family:"맑은 고딕", monospace;mso-font-charset:129;mso-number-format:General;text-align:general;vertical-align:middle;mso-background-source:auto;mso-pattern:auto;white-space:nowrap;}
.xl6428651{padding-top:1px;padding-right:1px;padding-left:1px;mso-ignore:padding;color:black;font-size:10.0pt;font-weight:400;font-style:normal;text-decoration:none;font-family:"맑은 고딕", monospace;mso-font-charset:129;mso-number-format:General;text-align:center;vertical-align:middle;border:.5pt solid gray;mso-background-source:auto;mso-pattern:auto;white-space:nowrap;}
.xl6528651{padding-top:1px;padding-right:1px;padding-left:1px;mso-ignore:padding;color:black;font-size:10.0pt;font-weight:400;font-style:normal;text-decoration:none;font-family:"맑은 고딕", monospace;mso-font-charset:129;mso-number-format:"Short Date";text-align:center;vertical-align:middle;border:.5pt solid gray;mso-background-source:auto;mso-pattern:auto;white-space:nowrap;}
.xl6628651{padding-top:1px;padding-right:1px;padding-left:1px;mso-ignore:padding;color:black;font-size:10.0pt;font-weight:400;font-style:normal;text-decoration:none;font-family:"맑은 고딕", monospace;mso-font-charset:129;mso-number-format:General;text-align:general;vertical-align:middle;border:.5pt solid gray;mso-background-source:auto;mso-pattern:auto;white-space:nowrap;}
.xl6728651{padding-top:1px;padding-right:1px;padding-left:1px;mso-ignore:padding;color:black;font-size:10.0pt;font-weight:400;font-style:normal;text-decoration:none;font-family:"맑은 고딕", monospace;mso-font-charset:129;mso-number-format:"_-* \#\,\#\#0_-\;\\-* \#\,\#\#0_-\;_-* \0022-\0022_-\;_-\@_-";text-align:general;vertical-align:middle;border:.5pt solid gray;mso-background-source:auto;mso-pattern:auto;white-space:nowrap;}
.xl6828651{padding-top:1px;padding-right:1px;padding-left:1px;mso-ignore:padding;color:black;font-size:10.0pt;font-weight:400;font-style:normal;text-decoration:none;font-family:"맑은 고딕", monospace;mso-font-charset:129;mso-number-format:General;text-align:center;vertical-align:middle;border:.5pt solid gray;background:#E7E6E6;mso-pattern:black none;white-space:nowrap;}
ruby{ruby-align:left;}
rt{color:windowtext;font-size:8.0pt;font-weight:400;font-style:normal;text-decoration:none;font-family:"맑은 고딕", monospace;mso-font-charset:129;mso-char-type:none;}
-->
</style>
</head>

<body>

<div id="통합 문서1_28651" align=center x:publishsource="Excel">
<table border=0 cellpadding=0 cellspacing=0 width=1094 class=xl6328651 style='border-collapse:collapse;table-layout:fixed;width:822pt'>
	<col class=xl6328651 width=42 style='mso-width-source:userset;mso-width-alt:1344;width:32pt'>
	<col class=xl6328651 width=100 style='mso-width-source:userset;mso-width-alt:2848;width:100pt'>
	<col class=xl6328651 width=116 style='mso-width-source:userset;mso-width-alt:3712;width:116pt'>
	<col class=xl6328651 width=133 style='mso-width-source:userset;mso-width-alt:4256;width:133pt'>
	<col class=xl6328651 width=300 style='width:300pt'>
	<tr height=21 style='mso-height-source:userset;height:15.95pt'>
		<td height=21 class=xl6828651 width=42 style='height:15.95pt;width:32pt'>자산번호</td>
		<td class=xl6828651 width=100 style='border-left:none;width:100pt'>모델명</td>
		<td class=xl6828651 width=116 style='border-left:none;width:116pt'>제조사</td>
		<td class=xl6828651 width=133 style='border-left:none;width:133pt'>시리얼</td>
		<td class=xl6828651 width=300 style='border-left:none;width:300pt'>상세정보</td>
	</tr>
<?php foreach($list as $row) { ?>
	<tr height=21 style='mso-height-source:userset;height:15.95pt'>
		<td height=21 class=xl6428651 style='height:15.95pt;border-top:none'><?php echo $row['자산번호'] ?></td>
		<td class=xl6528651 style='border-top:none;border-left:none'><?php echo $row['모델명'] ?></td>
		<td class=xl6628651 style='border-top:none;border-left:none'><?php echo $row['제조사'] ?></td>
		<td class=xl6628651 style='border-top:none;border-left:none'><?php echo $row['시리얼'] ?></td>
		<td class=xl6428651 style='border-top:none;border-left:none'><?php echo $row['상세정보'] ?></td>
	</tr>
<? } ?>
	<![if supportMisalignedColumns]>
	<tr height=0 style='display:none'>
		<td width=42 style='width:32pt'></td>
		<td width=100 style='width:100pt'></td>
		<td width=116 style='width:116pt'></td>
		<td width=133 style='width:133pt'></td>
		<td width=300 style='width:300pt'></td>
	</tr>
	<![endif]>
</table>
</div>
</body>
</html>
<?php }?>