<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Accesslevel class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 권한이 있는지 없는지 판단하는 class 입니다.
 */
class Accesslevel extends CI_Controller
{

	private $CI;

	function __construct()
	{
		$this->CI = & get_instance();
	}


	/**
	 * 접근권한이 있는지를 판단합니다
	 */
	public function is_accessable($access_type = '', $level = '', $group = '', $check = array())
	{
		$access_type = (string) $access_type;
		if (empty($access_type)) { // 모든 사용자
			return true;
		} elseif ($access_type === '1') { // 로그인 사용자
			if ($this->CI->member->is_member() === false) {
				return false;
			}
			return true;
		} elseif ($access_type === '100') { // 관리자
			if ($this->CI->member->is_admin($check) === false) {
				return false;
			}
			return true;
		} elseif ($access_type === '2') { // 특정그룹사용자
			if ($this->CI->member->is_admin($check) !== false) {
				return true;
			}
			if ($this->CI->member->is_member() === false) {
				return false;
			}
			$mygroup = $this->CI->member->group();
			$groups = json_decode($group, true);
			$_flag = false;
			if ($mygroup && is_array($mygroup)) {
				foreach ($mygroup as $key => $value) {
					if (is_array($groups) && in_array(element('mgr_id', $value), $groups)) {
						$_flag = true;
						break;
					}
				}
			}
			if ($_flag === false) {
				return false;
			}
			return true;
		} elseif ($access_type === '3') { // 특정레벨이상인자
			if ($this->CI->member->is_admin($check) !== false) {
				return true;
			}
			if ($this->CI->member->is_member() === false) {
				return false;
			}
			if ($this->CI->member->item('mem_level') < $level) {
				return false;
			}
			return true;

		} elseif ($access_type === '4') { // 특정그룹 OR 특정레벨
			if ($this->CI->member->is_admin($check) !== false) {
				return true;
			}
			if ($this->CI->member->is_member() === false) {
				return false;
			}
			$_flag = false;
			if ($this->CI->member->item('mem_level') >= $level) {
				$_flag = true;
			}
			if ($_flag === false) {
				$mygroup = $this->CI->member->group();
				$groups = json_decode($group, true);
				if ($mygroup && is_array($mygroup)) {
					foreach ($mygroup as $key => $value) {
						if (is_array($groups) && in_array(element('mgr_id', $value), $groups)) {
							$_flag = true;
							break;
						}
					}
				}
			}
			if ($_flag === false) {
				return false;
			}
			return true;

		} elseif ($access_type === '5') { // 특정그룹 AND 특정레벨
			if ($this->CI->member->is_admin($check) !== false) {
				return true;
			}
			if ($this->CI->member->is_member() === false) {
				return false;
			}
			if ($this->CI->member->item('mem_level') < $level) {
				return false;
			}
			$_flag = false;
			$mygroup = $this->CI->member->group();
			$groups = json_decode($group, true);
			if ($mygroup && is_array($mygroup)) {
				foreach ($mygroup as $key => $value) {
					if (is_array($groups) && in_array(element('mgr_id', $value), $groups)) {
						$_flag = true;
						break;
					}
				}
			}
			if ($_flag === false) {
				return false;
			}
			return true;
		}
	}


	/**
	 * 접근권한이 없으면 alert 를 띄웁니다
	 */
	public function check($access_type = '', $level = '', $group = '', $alertmessage = '', $check = array())
	{
		if (empty($alertmessage)) {
			$alertmessage = '접근 권한이 없습니다';
		}
		$accessable = $this->is_accessable($access_type, $level, $group, $check);

		if ($accessable) {
			return true;
		} else {
			alert($alertmessage);
			return false;
		}
	}
}
