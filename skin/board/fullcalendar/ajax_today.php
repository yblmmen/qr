<?php

$accessCode = !empty($board['bo_1']) ? intval($board['bo_1']) : 0;

if($c_user == "all" || $accessCode == 1) {
	$result = sql_query("select wr_subject, wr_content,wr_name,wr_1,wr_2 from {$write_table} where (left(wr_1,10) <= '{$to_day}' and left(wr_2,10) >= '{$to_day}')  order by wr_subject");
} else {
	$result = sql_query("select wr_subject, wr_content,wr_name,wr_1,wr_2 from {$write_table} where (left(wr_1,10) <= '{$to_day}' and left(wr_2,10) >= '{$to_day}' and wr_name = '{$c_user}')  order by wr_subject");
}

$cont = "";

if($result) {

	if($accessCode == 1) {
		foreach($result as $field) {
			if(strlen($field["wr_1"]) > 11 && substr($field["wr_2"],0,10) == $to_day) {
				$cont = $cont."<p>☆ (".$field["wr_name"].") ".substr($field["wr_1"],11,5)." ".$field['wr_subject']."</p>";
			} elseif(strlen($field["wr_1"]) == 10 && substr($field["wr_2"],0,10) > $to_day) {
				$cont = $cont."<p>☆ (".$field["wr_name"].") ".$field['wr_subject']."</p>";
			}
		}

	} else {

		foreach($result as $field) {
			if(strlen($field["wr_1"]) > 11 && substr($field["wr_2"],0,10) == $to_day) {
				$cont = $cont."<p>☆ ".substr($field["wr_1"],11,5)." ".$field['wr_subject']."</p>";
			} elseif(strlen($field["wr_1"]) == 10 && substr($field["wr_2"],0,10) > $to_day) {
				$cont = $cont."<p>☆ ".$field['wr_subject']."</p>";
			}
		}

	}

}

if($cont == "") {
	echo "<p>● 오늘 일정이 없습니다.</p>";
} else {
	echo $cont;
}
