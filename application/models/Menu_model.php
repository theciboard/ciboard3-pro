<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Menu model class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

class Menu_model extends CB_Model
{

	/**
	 * 테이블명
	 */
	public $_table = 'menu';

	/**
	 * 사용되는 테이블의 프라이머리키
	 */
	public $primary_key = 'men_id'; // 사용되는 테이블의 프라이머리키

	function __construct()
	{
		parent::__construct();
	}


	public function get_all_menu($device_view_type = '')
	{
		$cachename = $device_view_type === 'mobile'
			? 'pagemenu-mobile' : 'pagemenu-desktop';

		if ( ! $result = $this->cache->get($cachename)) {

			$where = $device_view_type === 'mobile'
				? array('men_mobile' => 1) : array('men_desktop' => 1);

			$return = $this->get('', '', $where, '', 0, 'men_order', 'asc');

			if ($return) {
				foreach ($return as $key => $value) {
					$result[$value['men_parent']][$value['men_id']] = $value;
				}
				$this->cache->save($cachename, $result);
			}
		}
		return $result;
	}
}
