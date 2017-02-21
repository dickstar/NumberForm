<?php
	//phpunit --bootstrap src/NumberFormClass.php tests/NumberFormClassTest.php
	
	/**
	 * 數字類別
	 *		數字自訂顯示相關的方法
	 */
	class NumberForm {
					
		/**
		 * 取得中文字串翻轉
		 *
		 * @param String 中文字串
		 * @return String
		 * @license http://blog.zhengshuiguang.com/php/strrev-cn.html
		 */
		private function strrev_cht($str){
			$len = strlen($str);
			$r = array();
			$n = 0;
			$m = 0;
			for($i = 0; $i < $len; $i++)
			{
				$x = substr($str, $i, 1);
				$a  = base_convert(ord($x), 10, 2);
				$a = substr('00000000'.$a, -8);
				if (substr($a, 0, 1) == 0)
				{
					array_unshift($r, substr($str, $i, 1));
				}elseif (substr($a, 0, 3) == 110){
					array_unshift($r, substr($str, $i, 2));
					$i += 1;
				}elseif (substr($a, 0, 4) == 1110){
					array_unshift($r, substr($str, $i, 3));
					$i += 2;
				}else{
					array_unshift($r, '');
				}
				if (++$m >= $len)
				{
					break;
				}
			}
			return implode('', $r);
		}
		
		/**
		 * 輸入阿拉伯數字取得中文數字
		 *
		 * @param Integer
		 * @return String
		 * @link https://github.com/zbryikt/numconvert
		 */
		function chinese($number) {
			$result = "";		
			if(!is_int($number)) {
				throw new InvalidArgumentException('Chinese function only accepts integers.');
			}
			if ($number <= 1000000 && $number > 0) {
				$word = strval($number);
				$reverse_word = strrev($word);			
				for ($i = 0; $i < strlen($number); $i++) {								
					if ($reverse_word[$i] != 0) {
						switch ($i) {
							case 0:											
								break;
							case 1:							
								$result .= "十";								
								break;
							case 2:
								if (strpos($result, '十') === false && $result != "") {								
									$result .= "零";								
								}
								$result .= "百";
								break;
							case 3:							
								if (strpos($result, '百') === false && $result != "") {																
									$result .= "零";									
								}							
								$result .= "千";
								break;
							case 4:
								if (strpos($result, '千') === false && $result != "") {																
									$result .= "零";									
								}	
								$result .= "萬";
								break;
							case 5:
								if (strpos($result, '萬') == true) {								
									if (mb_substr($result, -1) == "兩") {
										$result = mb_substr($result, 0, -1) . "二";
									}									
									$result .= "十";																		
								}
								else {																
									if (strpos($result, '千') === false && strpos($result, '萬') === false && $result != "") {													
										$result .= "零";																			
									}
									if (strpos($result, '萬') === false && $result != "") {
										$result .= "萬";										
									}
									if (strpos($result, '千') === false && $result == "") {										
										$result .= "萬";										
									}
									if (mb_substr($result, -1) == "兩") {
										$result = mb_substr($result, 0, -1) . "二";
									}									
									$result .= "十";									
								}															
								break;
							case 6:
								$result .= "萬百";
								break;
						}	
					}				
					switch ($reverse_word[$i]) {
						case 1:
							//顯示十萬，而避免出現一十萬
							if ($i == 1 && $number > 99) {
								$result .= "一";
							}
							else if (mb_substr($result, -1) != "十"){																
								$result .= "一";
							}
							break;
						case 2:
							if (mb_substr($result, -1) == "十") {
								$result .= "二";
							}
							else if (mb_substr($result, -1) == "百") {
								$result .= "兩";
							}
							else if (mb_substr($result, -1) == "千") {
								$result .= "兩";
							}
							else if (mb_substr($result, -1) == "萬") {								
								$result .= "兩";								
							}
							else if ($i == 0) {
								$result .= "二";
							}
							else {
								$result .= "兩";
							}							
							break;
						case 3:
							$result .= "三";
							break;
						case 4:
							$result .= "四";
							break;
						case 5:
							$result .= "五";
							break;
						case 6:
							$result .= "六";
							break;
						case 7:
							$result .= "七";
							break;
						case 8:						
							$result .= "八";
							break;
						case 9:
							$result .= "九";
							break;
					}						
				}
			}	
			else if ($number === 0){
				$result = "零";
			}
			else {
				throw new InvalidArgumentException('Chinese function only accepts integers between 0 ~ 1000000.');
			}			
			return $this->strrev_cht($result);					
		}
		
		/**
		 * 將中文數字(遞迴版本)適當的處理尾數重覆的零和最前面有一十的狀況 
		 *
		 * @param String
		 * @return String		 
		 */
		function chinese_recursive($number) {
			$result = "";
			if (!is_int($number)) {
				throw new InvalidArgumentException('Chinese function only accepts integers.');
			}
			else if ($number == 0) {
				$result = "零";
			}
			else if ($number > 0 && $number <= 1000000) {
				$result = $this->chinese_recursive_pre($number);
				if (mb_substr(strval($result), -1) == "零" && $number > 9) {
					$result = mb_substr($result, 0, -1);
				}									
				if (mb_substr(strval($result), 0, 2) == "一十") {
					$result = mb_substr($result, 1);
				}				
				$result = str_replace("十兩", "十二", $result);
			}
			else {
				throw new InvalidArgumentException('Chinese function only accepts integers between 0 ~ 1000000.');
			}			
			return $result;
		}
		
		/**
		 * 輸入阿拉伯數字取得中文數字(遞迴版本)，有重覆零
		 *
		 * @param Integer
		 * @return String
		 * @link https://github.com/zbryikt/numconvert
		 */
		function chinese_recursive_pre($number) {		
			if ($number < 10) {				
				return $this->chineseNumber($number);		
			}						
			else if ($number <= 1000000) {
				return $this->chineseNumber(strval($number)[0], $this->chineseUnit($number)) . $this->chineseUnit($number) . $this->chinese_recursive_pre(intval(substr($number, 1)));				
			}
		}
		
		/**
		 * 依照數字單位給予中文數字單位(遞迴版本)
		 *
		 * @param integer
		 * @return String		 
		 */
		function chineseUnit($input) {
			$result = "";
			switch ($input) {
				case $input == 1000000:
					$result = "百萬";
					break;
				case $input > 99999:
					if (($input % 100000) < 10000) {
						$result = "十萬";
					}
					else {
						$result = "十";
					}				
					break;
				case $input > 9999:
					$result = "萬";
					break;			
				case $input > 999:
					$result = "千";
					break;			
				case $input > 99:
					$result = "百";
					break;			
				case $input > 9:
					$result = "十";
					break;
			}
			if ($input >= 100000 && strval($input)[2] == "0" && strval($input)[1] == "0") {
				$result .= "零";							
			}
			if ($input < 100000 && strval($input)[1] == "0") {	
				$result .= "零";						
			}			
			return $result;
		}
		
		/**
		 * 阿拉伯數字轉換成中文數字(遞迴版本)
		 *
		 * @param Integer
		 * @return String		 
		 */
		function chineseNumber() {
			$input = func_get_arg(0);				
			$result = "";
			switch ($input) {
				case 0:										
					//$result = "零";
					break;
				case 1:
					$result = "一";
					break;
				case 2:		
					if (func_num_args() == 2) {
						$previous_word = mb_substr(func_get_arg(1), 0, 1);								
						//echo func_get_arg(1) . "\n\r";
						if ($previous_word == "十") {
							$result = "二";
						}
						else if ($previous_word  == "百") {
							$result = "兩";
						}
						else if ($previous_word  == "千") {
							$result = "兩";
						}
						else if ($previous_word  == "萬") {
							$result = "兩";
						}				
						else {
							$result = "兩";
						}			
					}	
					else {
						$result = "二";
					}
					break;
				case 3:
					$result = "三";
					break;
				case 4:
					$result = "四";
					break;
				case 5:
					$result = "五";
					break;
				case 6:
					$result = "六";
					break;
				case 7:
					$result = "七";
					break;
				case 8:
					$result = "八";
					break;
				case 9:
					$result = "九";
					break;
			}
			return $result;
		}
	}
	
	//$number = new NumberForm();	
	// for ($i = 0; $i <= 1000000; $i++) {	
		// echo "迴圈:" . $number->chinese($i) . "\r\n";		
	// }
	
	// for ($i = 0; $i <= 1000000; $i++) {			
		// echo "遞迴:" . $number->chinese_recursive($i) . "\r\n";		
	// }	
?>