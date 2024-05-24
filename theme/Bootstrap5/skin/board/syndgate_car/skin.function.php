<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

function br() {
    echo "<br/>";
}

Class Frm_Search {

	var $option_text;
	var $option_value;
	var $temp1;
	var $temp2;


	function var_mode($m, $arr) {

		// 배열오류이면...
		if(!is_array($arr)) {
			$arr = array ("msg"=>"배열오류");
		 }
        
		$text = implode("|", $arr);
		$value = implode("|", array_keys($arr));
        
		switch($m) {
			case "A" : // key, value
				$t = $text;
				$v = $value;
			break;
			case "B" : // value, value
				$t = $text;
				$v = $text;
			break;
			default : // key, value
				$t = $text;
				$v = $value;
			break;
		}
		$this->option_text = $t;
		$this->option_value = $v;
	}

	// 검색박스 검색조건 셀렉트 박스 생성 (모드, 배열, name, id, class, val)
	function Select($s_title='', $s_name, $s_id='', $s_class='', $s_val, $required='') {

		if($s_title) {
			$data1 = explode("|", $s_title."|".$this->option_text);
			$data2 = explode("|", "|".$this->option_value);
		} else {
			$data1 = explode("|", $this->option_text);
			$data2 = explode("|", $this->option_value);
		}

		$this->temp1 = $data1;
		$this->temp2 = $data2;

		for($i=0; $i < count($data1); $i++){ $dataA[$i] = trim($data1[$i]); }
		for($i=0; $i < count($data2); $i++){ $dataB[$i] = trim($data2[$i]); }
        
        for($i=0; $i < count($data2); $i++){
			$selected = ( $s_val == $dataB[$i] )? "selected":"";
			$opt .="<option value='".$dataB[$i]."' ".$selected.">".$dataA[$i]."</option>";
        }
        
        if($s_id) {
            $id = "id='{$s_id}'";
        }

		$rst = "<select name='".$s_name."' {$id} class='".$s_class."' ".$required.">";
		$rst .= $opt;
		$rst .= "</select>";
		return $rst;
	}
}

function doc_affected_rows($link=null)
{
    global $g5;
    if(!$link)
        $link = $g5['connect_db'];
    if(function_exists('mysqli_affected_rows') && G5_MYSQLI_USE)
        return mysqli_affected_rows($link);
    else
        return mysql_affected_rows($link);
}



function get_memInfo($wr_id) {
    
    global $write_table;

	$sql = "select * from {$write_table} where wr_id = '{$wr_id}' ";
    $rs = sql_fetch($sql);
    return $rs;
}


//자신의 글인지 체크하기 위해
function get_memInfos($wr_id,$md_id) {
    
    global $write_table;

    ## 직원정보 자료 수정 g5_write_tel 
	$sql = "select count('wr_id') as cnt from {$write_table} where wr_id = '{$wr_id}' and mb_id = '{$md_id}' ; ";
    $rs = sql_fetch($sql);

    return $rs['cnt'];
}


function sql_affected_rows($link=null) {
    global $g5;
    if(!$link)
        $link = $g5['connect_db'];
    if(function_exists('mysqli_affected_rows') && G5_MYSQLI_USE)
        return mysqli_affected_rows($link);
    else
        return mysql_affected_rows($link);
}
?>
