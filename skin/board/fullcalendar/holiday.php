<?php

class Holiday
{
	protected $lunarMonthType = array();
	protected $SolarToLunar = array();
	protected $LunarToSolar = array();
	protected $error = "";
	protected $solar_start = "1881-01-30";
	protected $lunar_start = '18810101';
	protected $monthTypeMark = "1212122322121-1212121221220-1121121222120-2112132122122-2112112121220-2121211212120-2212321121212-2122121121210-2122121212120-1232122121212-1212121221220-1121123221222-1121121212220-1212112121220-2121231212121-2221211212120-1221212121210-2123221212121-2121212212120-1211212232212-1211212122210-2121121212220-1212132112212-2212112112210-2212211212120-1221412121212-1212122121210-2112212122120-1231212122212-1211212122210-2121123122122-2121121122120-2212112112120-2212231212112-2122121212120-1212122121210-2132122122121-2112121222120-1211212322122-1211211221220-2121121121220-2122132112122-1221212121120-2121221212110-2122321221212-1121212212210-2112121221220-1231211221222-1211211212220-1221123121221-2221121121210-2221212112120-1221241212112-1212212212120-1121212212210-2114121212221-2112112122210-2211211412212-2211211212120-2212121121210-2212214112121-2122122121120-1212122122120-1121412122122-1121121222120-2112112122120-2231211212122-2121211212120-2212121321212-2122121121210-2122121212120-1212142121212-1211221221220-1121121221220-2114112121222-1212112121220-2121211232122-1221211212120-1221212121210-2121223212121-2121212212120-1211212212210-2121321212221-2121121212220-1212112112210-2223211211221-2212211212120-1221212321212-1212122121210-2112212122120-1211232122212-1211212122210-2121121122210-2212312112212-2212112112120-2212121232112-2122121212110-2212122121210-2112124122121-2112121221220-1211211221220-2121321122122-2121121121220-2122112112322-1221212112120-1221221212110-2122123221212-1121212212210-2112121221220-1211231212222-1211211212220-1221121121220-1223212112121-2221212112120-1221221232112-1212212122120-1121212212210-2112132212221-2112112122210-2211211212210-2221321121212-2212121121210-2212212112120-1232212122112-1212122122120-1121212322122-1121121222120-2112112122120-2211231212122-2121211212120-2122121121210-2124212112121-2122121212120-1212121223212-1211212221220-1121121221220-2112132121222-1212112121220-2121211212120-2122321121212-1221212121210-2121221212120-1232121221212-1211212212210-2121123212221-2121121212220-1212112112220-1221231211221-2212211211220-1212212121210-2123212212121-2112122122120-1211212322212-1211212122210-2121121122120-2212114112122-2212112112120-2212121211210-2212232121211-2122122121210-2112122122120-1231212122212-1211211221220-2121121321222-2121121121220-2122112112120-2122141211212-1221221212110-2121221221210-2114121221221";

	function __construct() {

		$dateCount = array(0,29,30,29,30);

		//문자열을 배열로 컷팅.
        $typemarks = explode("-",$this->monthTypeMark);
        foreach ($typemarks as $typemark)
        {
            $arr = str_split($typemark);
            $lunarMonthType[] = $arr;
        }

        //인덱스 구축.
        $solarDate = new DateTime($this->solar_start);
        $lastSol = $solarDate->format('Ymd');
        $lastLuna = $this->lunar_start;
        $lunarYear = (int) substr($this->lunar_start, 0, 4);
        foreach ($lunarMonthType as $yearArr) {
            $accArr = array();

            $lunarMonth = 0;
            foreach ($yearArr as $monthType) {
                if ($monthType == '0')
                    continue;
                $dcnt = $dateCount[$monthType];

                $isLeapMonth = false;
                if ($monthType == '3' || $monthType == '4')
                    $isLeapMonth = true;
                else
                    $lunarMonth++;

                $lunarYMD = sprintf('%d%02d%02d%s', $lunarYear, $lunarMonth, 1, $isLeapMonth ? 'L' : ' ');

                if (isset($this->SolarToLunar[$solarDate->format('Ym')]) == false) {
                    $this->SolarToLunar[$solarDate->format('Ym')][$lastSol] = $lastLuna;
                }

                $this->SolarToLunar[$solarDate->format('Ym')][$solarDate->format('Ymd')] = $lunarYMD;
                $this->LunarToSolar[$lunarYMD] = $solarDate->format('Ymd');

                $lastSol = $solarDate->format('Ymd');
                $lastLuna = $lunarYMD;

                $solarDate->add(new DateInterval('P' . $dcnt . 'D'));
            }
            $lunarYear++;
        }

    }


	//  음력-->양력 날짜 구하기
	function getSolarDate($yyyymmdd, $isLeapMonth = false)
	{
		$lunarYear = substr($yyyymmdd,0,4);
		$lunarMonth = substr($yyyymmdd,4,2);
		$lunarDate = substr($yyyymmdd,6,2);

		$this->error = "";

		$nearKey = sprintf('%d%02d%02d%s', $lunarYear, $lunarMonth, 1, $isLeapMonth ? 'L' : ' ');

		if (isset($this->LunarToSolar[$nearKey]) == false)
		{
			$this->error = '계산할수 있는 범위가 아닙니다.';
			return null;
		}

		$solarPinDate = $this->LunarToSolar[$nearKey];

		$keyDate = substr($nearKey, 6, 2);
		$keyIsLeapMonth = ('L' == substr($nearKey, 8, 1) ? true : false);
		if ($keyIsLeapMonth != $isLeapMonth)
		{
			$this->error = ($isLeapMonth ? "윤달" : "평달") . $lunarYear-$lunarMonth-$lunarDate . "는 없음";
			return null;
		}

		$diff = $lunarDate - $keyDate;
		$date = DateTime::createFromFormat('Ymd', $solarPinDate);
		$date->add(new DateInterval('P' . $diff . 'D'));

		return $date->format('Y-m-d');
	}


	//  양력-->음력 날짜 구하기
	function getlunarDate($date)
	{
		$getYEAR = date('Y', strtotime($date));
		$getMONTH = date('m', strtotime($date));
		$getDAY = date('d', strtotime($date));

		$arrayDATA = explode("-",$this->monthTypeMark);
		$arrayLDAYSTR="31-0-31-30-31-30-31-31-30-31-30-31";
		$arrayLDAY = explode("-",$arrayLDAYSTR);

		$dt = $arrayDATA;

		for ($x=0; $x<=168; $x++)
		{
			$dt[$x] = 0;

			for ($y=0;$y<12;$y++)
			{
				switch (substr($arrayDATA[$x],$y,1))
				{
					case 1:
						$dt[$x] += 29;
						break;
					case 3:
						$dt[$x] += 29;
						break;
					case 2:
						$dt[$x] += 30;
						break;
					case 4:
						$dt[$x] += 30;
						break;
				}
			}

			switch (substr($arrayDATA[$x],12,1))
			{
				case 0:
					break;
				case 1:
					$dt[$x] += 29;
					break;
				case 3:
					$dt[$x] += 29;
					break;
				case 2:
					$dt[$x] += 30;
					break;
				case 4:
					$dt[$x] += 30;
					break;
			}
		}

		$td1 = 1880 * 365 + (int)(1880/4) - (int)(1880/100) + (int)(1880/400) + 30;
		$k11 = $getYEAR - 1;
		$td2 = $k11 * 365 + (int)($k11/4) - (int)($k11/100) + (int)($k11/400);

		if ($getYEAR % 400 == 0 || $getYEAR % 100 != 0 && $getYEAR % 4 == 0) {
			$arrayLDAY[1] = 29;
		} else {
			$arrayLDAY[1] = 28;
		}

		for ($x=0;$x<=$getMONTH-2;$x++) {
			$td2 += $arrayLDAY[$x];
		}

		$td2 += $getDAY;
		$td = $td2 - $td1 + 1;
		$td0 = $dt[0];

		for ($x=0;$x<=168;$x++) {
			if ($td <= $td0) {
				break;
			}
			$td0 += $dt[$x+1];
		}

		$ryear = $x + 1881;
		$td0 -= $dt[$x];
		$td -= $td0;

		if (substr($arrayDATA[$x], 12, 1) == 0) {
			$ycount = 11;
		} else {
			$ycount = 12;
		}
		$m2 = 0;

		for ($y=0; $y <= $ycount; $y++) {
			if (substr($arrayDATA[$x],$y,1) <= 2) {
				$m2++;
				$m1 = substr($arrayDATA[$x],$y,1) + 28;
			} else {
				$m1 = substr($arrayDATA[$x],$y,1) + 26;
			}
			if ($td <= $m1) {
				break;
			}
			$td = $td - $m1;
		}

		$lunarDate  = $ryear."-".sprintf('%02d', $m2)."-".sprintf('%02d', $td);

		return $lunarDate;
	}

	//공휴일 처리
	function getHolidays($f_year)
	{
		$solar_0101 = $f_year."0101";
		$solar_0301 = $f_year."0301";
		$solar_0408 = $f_year."0408";
		$solar_0505 = $f_year."0505";
		$solar_0606 = $f_year."0606";
		$solar_0815 = $f_year."0815";
		$solar_1003 = $f_year."1003";
		$solar_1009 = $f_year."1009";
		$solar_1225 = $f_year."1225";

		// 음력->양력으로 변환
		$lunar_0101 = $this->getSolarDate($solar_0101, false);		//설날
		$lunar_0408 = $this->getSolarDate($solar_0408, false);		//석가탄신일
		$lunar_0815 = $this->getSolarDate($solar_0815, false);		//추석

		//요일 변환
		$wd_0101 = date('w', strtotime($lunar_0101));		//설날
		$wd_0301 = date('w', strtotime($solar_0301));		//삼일절
		$wd_0408 = date('w', strtotime($lunar_0408));		//석탄일
		$wd_0505 = date('w', strtotime($solar_0505));		//어린이날
		$wd_0606 = date('w', strtotime($solar_0606));		//현충일
		$wd_0815 = date('w', strtotime($solar_0815));		//광복절
		$ws_0815 = date('w', strtotime($lunar_0815));		//추석
		$wd_1003 = date('w', strtotime($solar_1003));		//개천절
		$wd_1009 = date('w', strtotime($solar_1009));		//한글날
		$wd_1225 = date('w', strtotime($solar_1225));		//성탄절

		$holiday_date = "0101,0301,0505,0606,0815,1003,1009,1225";
		$holiday_name = "신정,삼일절,어린이날,현충일,광복절,개천절,한글날,성탄절";

		//설날
		$sub_date1 = date("md", strtotime($lunar_0101." -1 day"));
		$sub_date2 = date("md", strtotime($lunar_0101));
		$sub_date3 = date("md", strtotime($lunar_0101." +1 day"));
		$holiday_date = $holiday_date.",".$sub_date1.",".$sub_date2.",".$sub_date3;
		$holiday_name = $holiday_name.",설연휴,설날,설연휴";

		if($wd_0101 == 0 || $wd_0101 == 1 || $wd_0101 == 6) {
			$sub_date = date("md", strtotime($lunar_0101." +2 days"));
			$holiday_date = $holiday_date.",".$sub_date;
			$holiday_name = $holiday_name.",대체휴일";
		}

		//삼일절
		if($wd_0301 == 6) {
			$sub_date = date("md", strtotime($solar_0301." +2 days"));
			$holiday_date = $holiday_date.",".$sub_date;
			$holiday_name = $holiday_name.",대체휴일";
		} elseif ($wd_0301 == 0) {
			$sub_date = date("md", strtotime($solar_0301." +1 day"));
			$holiday_date = $holiday_date.",".$sub_date;
			$holiday_name = $holiday_name.",대체휴일";
		}

		//어린이날
		if(substr($lunar_0408, -5) == "05-05" && ($wd_0505 <> 6 || $wd_0505 <> 0)) {
			$sub_date = date("md", strtotime($solar_0505." +1 day"));
			$holiday_date = $holiday_date.",".$sub_date;
			$holiday_name = $holiday_name.",대체휴일";
			$holiday_name = str_replace("어린이날","어린이날/석탄일",$holiday_name);
		}

		if($wd_0505 == 6) {
			$sub_date = date("md", strtotime($solar_0505." +2 days"));
			$holiday_date = $holiday_date.",".$sub_date;
			$holiday_name = $holiday_name.",대체휴일";
		} elseif ($wd_0505 == 0) {
			$sub_date = date("md", strtotime($solar_0505." +1 day"));
			$holiday_date = $holiday_date.",".$sub_date;
			$holiday_name = $holiday_name.",대체휴일";
		}

		//석가탄신일
		$sub_date = date("md", strtotime($lunar_0408));
		$holiday_date = $holiday_date.",".$sub_date;
		$holiday_name = $holiday_name.",석가탄신일";

		if($wd_0408 == 0 || $wd_0408 == 1 || $wd_0408 == 6) {
			$sub_date = date("md", strtotime($lunar_0408." +2 days"));
			$holiday_date = $holiday_date.",".$sub_date;
			$holiday_name = $holiday_name.",대체휴일";
		}

		//현충일
		if($wd_0606 == 6) {
			$sub_date = date("md", strtotime($solar_0606." +2 days"));
			$holiday_date = $holiday_date.",".$sub_date;
			$holiday_name = $holiday_name.",대체휴일";
		} elseif ($wd_0606 == 0) {
			$sub_date = date("md", strtotime($solar_0606." +1 day"));
			$holiday_date = $holiday_date.",".$sub_date;
			$holiday_name = $holiday_name.",대체휴일";
		}

		//광복절
		if($wd_0815 == 6) {
			$sub_date = date("md", strtotime($solar_0815." +2 days"));
			$holiday_date = $holiday_date.",".$sub_date;
			$holiday_name = $holiday_name.",대체휴일";
		} elseif ($wd_0815 == 0) {
			$sub_date = date("md", strtotime($solar_0815." +1 day"));
			$holiday_date = $holiday_date.",".$sub_date;
			$holiday_name = $holiday_name.",대체휴일";
		}

		//개천절
		if($wd_1003 == 6) {
			$sub_date = date("md", strtotime($solar_1003." +2 days"));
			$holiday_date = $holiday_date.",".$sub_date;
			$holiday_name = $holiday_name.",대체휴일";
		} elseif ($wd_1003 == 0) {
			$sub_date = date("md", strtotime($solar_1003." +1 day"));
			$holiday_date = $holiday_date.",".$sub_date;
			$holiday_name = $holiday_name.",대체휴일";
		}

		//한글날
		if($wd_1009 == 6) {
			$sub_date = date("md", strtotime($solar_1009." +2 days"));
			$holiday_date = $holiday_date.",".$sub_date;
			$holiday_name = $holiday_name.",대체휴일";
		} elseif ($wd_1009 == 0) {
			$sub_date = date("md", strtotime($solar_1009." +1 day"));
			$holiday_date = $holiday_date.",".$sub_date;
			$holiday_name = $holiday_name.",대체휴일";
		}

		//추석연휴
		$sub_date1 = date("md", strtotime($lunar_0815." -1 day"));
		$sub_date2 = date("md", strtotime($lunar_0815));
		$sub_date3 = date("md", strtotime($lunar_0815." +1 day"));
		$holiday_date = $holiday_date.",".$sub_date1.",".$sub_date2.",".$sub_date3;
		$holiday_name = $holiday_name.",추석연휴,추석,추석연휴";

		if($sub_date2 == "1003" && ($ws_0815 <> 0 || $ws_0815 <> 1)) {
			$sub_date = date("md", strtotime($lunar_0815." +2 days"));
			$holiday_date = $holiday_date.",".$sub_date;
			$holiday_name = $holiday_name.",대체휴일";
			$holiday_name = str_replace("개천절","개천절/추석",$holiday_name);
		}

		if($sub_date2 == "1004" && $ws_0815 == 6) {
			$sub_date = date("md", strtotime($lunar_0815." +3 days"));
			$holiday_date = $holiday_date.",".$sub_date;
			$holiday_name = $holiday_name.",대체휴일";
			$holiday_name = str_replace("개천절","개천절/추석연휴",$holiday_name);
		}

		if($sub_date2 == "1002" && $ws_0815 == 0) {
			$sub_date = date("md", strtotime($lunar_0815." +3 days"));
			$holiday_date = $holiday_date.",".$sub_date;
			$holiday_name = $holiday_name.",대체휴일";
			$holiday_name = str_replace("개천절","개천절/추석연휴",$holiday_name);
		}

		if($ws_0815 == 0 || $ws_0815 == 1 || $ws_0815 == 6) {
			$sub_date = date("md", strtotime($lunar_0815." +2 days"));
			$holiday_date = $holiday_date.",".$sub_date;
			$holiday_name = $holiday_name.",대체휴일";
		}

		//성탄절
		if($wd_1225 == 6) {
			$sub_date = date("md", strtotime($solar_1225." +2 days"));
			$holiday_date = $holiday_date.",".$sub_date;
			$holiday_name = $holiday_name.",대체휴일";
		} elseif ($wd_1225 == 0) {
			$sub_date = date("md", strtotime($solar_1225." +1 day"));
			$holiday_date = $holiday_date.",".$sub_date;
			$holiday_name = $holiday_name.",대체휴일";
		}

		return array(	'f_day' => explode(",",$holiday_date), 'f_dayname' => explode(",",$holiday_name));

	}

	//휴일명
	function getHolidayname($date)
	{
		$year = date("Y",  strtotime($date));
		$md = date("md",  strtotime($date));

		$holi_day = $this->getHolidays($year);
		$f_day = $holi_day['f_day'];
		$f_dayname = $holi_day['f_dayname'];

		if(in_array($md,$f_day)) {
			$holiday_name = $f_dayname[array_search($md,$f_day)];
		} else {
			$holiday_name = "";
		}

		return $holiday_name;
	}

	//요일명
	function getWeekname($date)
    {
		$yoil = array("일","월","화","수","목","금","토");
		$week_name = $yoil[date("w", strtotime($date))];
		return $week_name;
    }

	//날짜(요일)  2023. 6. 6.(요일)
	function getWeekname2($date)
    {
		$yoil = array("일","월","화","수","목","금","토");
		$s_week = $yoil[date("w", strtotime($date))];
		$t_date = date("Y. n. j.", strtotime($date))."(".$s_week.")";
		return $t_date;
    }
}