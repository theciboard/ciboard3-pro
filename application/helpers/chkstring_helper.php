<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Chkstring libraries helper
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */


/**
 * 입력값 검사 상수
 */
defined('_ALPHAUPPER_')		or define('_ALPHAUPPER_', 1); // 영대문자
defined('_ALPHALOWER_')		or define('_ALPHALOWER_', 2); // 영소문자
defined('_ALPHABETIC_')		or define('_ALPHABETIC_', 4); // 영대,소문자
defined('_NUMERIC_')		or define('_NUMERIC_', 8);	// 숫자
defined('_HANGUL_')			or define('_HANGUL_', 16);	// 한글
defined('_SPACE_')			or define('_SPACE_', 32);	// 공백
defined('_SPECIAL_')		or define('_SPECIAL_', 64); // 특수문자

/**
 * 문자열이 한글, 영문, 숫자, 특수문자로 구성되어 있는지 검사
 */
if ( ! function_exists('chkstring')) {
	function chkstring($str = '', $options = '')
	{
		$CI =& get_instance();

		if (empty($str)) {
			return false;
		}

		$s = '';
		for ($i = 0; $i < strlen($str); $i++) {
			$c = $str[$i];
			$oc = ord($c);

			// 한글
			if ($oc >= 0xA0 && $oc <= 0xFF) {
				if (strtoupper(config_item('charset')) === 'UTF-8') {
					if ($options & _HANGUL_) {
						$s .= $c . $str[$i+1] . $str[$i+2];
					}
					$i+= 2;
				} else {
					// 한글은 2바이트 이므로 문자하나를 건너뜀
					$i++;
					if ($options & _HANGUL_) {
						$s .= $c . $str[$i];
					}
				}
			} elseif ($oc >= 0x30 && $oc <= 0x39) {
				// 숫자
				if ($options & _NUMERIC_) {
					$s .= $c;
				}
			} elseif ($oc >= 0x41 && $oc <= 0x5A) {
				// 영대문자
				if (($options & _ALPHABETIC_) OR ($options & _ALPHAUPPER_)) {
					$s .= $c;
				}
			} elseif ($oc >= 0x61 && $oc <= 0x7A) {
				// 영소문자
				if (($options & _ALPHABETIC_) OR ($options & _ALPHALOWER_)) {
					$s .= $c;
				}
			} elseif ($oc >= 0x20) {
				// 공백
				if ($options & _SPACE_) {
					$s .= $c;
				}
			} else {
				if ($options & _SPECIAL_) {
					$s .= $c;
				}
			}
		}
		// 넘어온 값과 비교하여 같으면 참, 틀리면 거짓
		return ($str === $s);
	}
}


/**
 * count the number of times an expression appears in a string
 *
 * @access private
 *
 * @param String $str
 * @param String $exp
 *
 * @return int
 */
if ( ! function_exists('count_occurrences')) {
	function count_occurrences($str, $exp)
	{
		$match = array();
		preg_match_all($exp, $str, $match);

		return count($match[0]);
	}
}


/**
 * count the number of lowercase characters in a string
 *
 * @access private
 *
 * @param String $str
 *
 * @return int
 */
if ( ! function_exists('count_lowercase')) {
	function count_lowercase($str)
	{
		return count_occurrences($str, '/[a-z]/');
	}
}


/**
 * count the number of uppercase characters in a string
 *
 * @access private
 *
 * @param String $str
 *
 * @return int
 */
if ( ! function_exists('count_uppercase')) {
	function count_uppercase($str)
	{
		return count_occurrences($str, '/[A-Z]/');
	}
}


/**
 * count the number of numbers characters in a string
 *
 * @access private
 *
 * @param String $str
 *
 * @return int
 */
if ( ! function_exists('count_numbers')) {
	function count_numbers($str)
	{
		return count_occurrences($str, '/[0-9]/');
	}
}


/**
 * count the number of special characters in a string
 *
 * @access private
 *
 * @param String $str
 *
 * @return int
 */
if ( ! function_exists('count_specialchars')) {
	function count_specialchars($str)
	{
		return count_occurrences($str, '/[!@#$%^&*()]/');
	}
}
