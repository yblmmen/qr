<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

Class form_option {

	var $mode, $arr;

	function var_mode($m, $arr) {

		// 배열오류이면...
		if(!is_array($arr)) {
			$arr = array ("msg"=>"배열오류");
		 }

		$this->mode = $m;
		$this->arr = $arr;

	}

	public function Month($A, $B, $fd, $td) {
		$aa = str_replace(".", "", $A);
		$bb = str_replace(".", "", $B);
		$total  = "<input type=\"text\" name=\"{$aa}\" value=\"{$fd}\" id=\"{$aa}\" class=\"sch_ipt_fr {$aa} frm-calendar width80\" placeholder=\"검색시작일\" />";
		$total .= " ~ ";
		$total .= "<input type=\"text\" name=\"{$bb}\" value='".$td."' id=\"{$bb}\" class=\"sch_ipt_to {$bb} frm-calendar width80\" placeholder=\"검색종료일\" />";
		$total .= "<span class=\"sch_btn_wrap\">";
		$total .= "<button type=\"button\" class='sch_M' onclick=\"SetToDays('{$A}', '{$B}'); \">오늘</button>&nbsp;";
		$total .= "<button type=\"button\" class='sch_M' onclick=\"SetPrevMonthDays('{$A}', '{$B}'); \">전월</button>&nbsp;";
		$total .= "<button type=\"button\" class='sch_M' onclick=\"SetCurrentMonthDays('{$A}', '{$B}');  \">당월</button>&nbsp;";
		$total .= "<button type=\"button\" class='sch_M' onclick=\"SetWeek_befor('{$A}', '{$B}'); \">지난주</button>&nbsp;";
		$total .= "<button type=\"button\" class='sch_M' onclick=\"SetWeek('{$A}', '{$B}'); \">이번주</button>";
		$total .= "</span>";
		return $total;
	}

	// 검색박스 검색조건 셀렉트 박스 생성 ('선택', name, id, cass, value, required='')
	function Select($s_title, $s_name, $s_id, $s_class, $s_val, $required="", $attr="") {
		$id = ($s_id) ? "id=\"{$s_id}\"" : "";
		$rst = "<select name=\"{$s_name}\" {$id} class=\"{$s_class}\" {$required} {$attr}>";
		$rst .= ($s_title) ? "<option value=\"\">{$s_title}</option>" : "";
		foreach($this->arr as $k=>$v) {
			$key = ($this->mode=="A") ? $k : $v;
			$selected = ($s_val == $key) ? "selected" : "";
			$rst .= "<option value=\"{$key}\" {$selected}>{$v}</option>";
        }
		$rst .= "</select>";
		return $rst;
	}

	function Radio($s_title, $s_name, $s_id, $s_class, $s_val, $required="", $attr="") {
		$idx = 0;
		$result = "<ul class='option-radio-wrap'>";
		foreach($this->arr as $k=>$v) {
			$key = ($this->mode=="A") ? $k : $v;
			$checked = ($s_val == $key) ? "checked" : "";
			$result .= "<li><input type=\"radio\" name=\"{$s_name}\" id=\"{$s_id}_{$idx}\" class=\"{$s_class}\" value=\"{$key}\" {$checked} {$required} {$attr} /><label for=\"{$s_id}_{$idx}\">{$v}</label></li>";
			$idx++;
		}
		$result .= "</ul>";
		return $result;
	}

	function Checkbox($s_title, $s_name, $s_id, $s_class, $s_val, $attr="") {
		$val = explode(",", $s_val);
		$idx = 0;
		$result = "<ul class=\"option-checkbox-wrap\">";
		foreach($this->arr as $k=>$v) {
			$key = ($this->mode=="A") ? $k : $v;
			$checked = (in_array($key, $val)==true) ? "checked" : "";
			$result .= "<li><input type=\"checkbox\" value=\"{$key}\" name=\"{$s_name}[]\" id=\"{$s_id}_{$idx}\" {$checked} class=\"{$s_class}\" {$attr} /> <label for=\"{$s_id}_{$idx}\">{$v}</label></li>";
			$idx++;
		}
		$result .="</ul>";
		return $result;
	}

	// (name, id, class, DB값, 시작숫자, 종료숫자, 증가수, TEXT값, Select에 추가될 속성)
	function Int($init, $name, $id, $class, $val, $start, $end, $plus, $txt="", $required="", $attr="") {
		$id = ($id) ? "id=\"{$id}\"" : "";
		$result = "<select name=\"{$name}\" {$id} class=\"{$class}\" {$required} {$attr}>";
		$result .= ($init) ? "<option value=\"\">{$init}</option>" : "";
		for($i = $start; $i <= $end; $i += $plus){
			$selected = ($val == $i) ? "selected" : "";
			$result .="<option value=\"{$i}\" {$selected}>{$i}{$txt}</option>";
		}
		$result .= "</select>";
		return $result;
	}
}