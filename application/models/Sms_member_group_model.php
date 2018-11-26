<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Sms member group model class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

class Sms_member_group_model extends CB_Model
{

	/**
	 * 테이블명
	 */
	public $_table = 'sms_member_group';

	/**
	 * 사용되는 테이블의 프라이머리키
	 */
	public $primary_key = 'smg_id'; // 사용되는 테이블의 프라이머리키

	function __construct()
	{
		parent::__construct();
	}


	public function update_group($data = '')
	{
		$order = 1;
		if (element('smg_id', $data) && is_array(element('smg_id', $data))) {
			foreach (element('smg_id', $data) as $key => $value) {
				if ( ! element($key, element('smg_name', $data))) {
					continue;
				}
				if ($value) {
					$updatedata = array(
						'smg_name' => $data['smg_name'][$key],
						'smg_order' => $order,
						'smg_datetime' => cdate('Y-m-d H:i:s'),
					);
					$this->update($value, $updatedata);
				} else {
					$insertdata = array(
						'smg_name' => $data['smg_name'][$key],
						'smg_order' => $order,
						'smg_datetime' => cdate('Y-m-d H:i:s'),
					);
					$this->insert($insertdata);
				}
			$order++;
			}
		}
		$deletewhere = array(
			'smg_datetime !=' => cdate('Y-m-d H:i:s'),
		);
		$this->delete_where($deletewhere);
	}
}
