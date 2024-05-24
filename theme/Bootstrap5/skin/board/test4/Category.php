<?include_once("_common.php");?>
<?php
header("Content-type: application/json");
$verb = $_SERVER["REQUEST_METHOD"];
$table="g6_write_CommonCode";
$option=$_GET['option'];
if ($verb == "GET") {
	$arr = array();
	$rs = sql_query("SELECT wr_2,wr_3,wr_4 FROM {$table} where wr_2 like '{$option}' and wr_3<>''");
	
	while($obj = mysqli_fetch_object($rs)) {
		$arr[] = $obj;
	}
	
	echo "{\"data\":" .json_encode($arr). "}";	
}
?>
