<?include_once("_common.php");?>
<?php
header("Content-type: application/json");
$verb = $_SERVER["REQUEST_METHOD"];
$table="g6_member";
if ($verb == "GET") {
	$arr = array();
		if($member[mb_id]=='admin'){
				$rs = sql_query("SELECT mb_id,mb_name,mb_2,mb_26,mb_56,mb_hp FROM {$table} order by mb_name asc ");
		}
		else{
	$rs = sql_query("SELECT mb_id,mb_name,mb_2,mb_26,mb_56,mb_hp FROM {$table} where mb_id<>'admin' and mb_13='{$member['mb_13']}'  order by mb_2 asc,mb_name asc ");
		}
	while($obj = mysqli_fetch_object($rs)) {
		$arr[] = $obj;
	}
	
	echo "{\"data\":" .json_encode($arr). "}";	
}
?>