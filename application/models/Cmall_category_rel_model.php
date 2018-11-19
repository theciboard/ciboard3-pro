<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Cmall category rel model class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

class Cmall_category_rel_model extends CB_Model
{

	/**
	 * 테이블명
	 */
	public $_table = 'cmall_category_rel';

	/**
	 * 사용되는 테이블의 프라이머리키
	 */
	public $primary_key = 'ccr_id'; // 사용되는 테이블의 프라이머리키

	function __construct()
	{
		parent::__construct();
	}


	public function save_category($cit_id = 0, $category = '')
	{
		$cit_id = (int) $cit_id;
		if (empty($cit_id) OR $cit_id < 1) {
			return;
		}
		$deletewhere = array(
			'cit_id' => $cit_id,
		);
		$this->delete_where($deletewhere);

		if ($category) {
			foreach ($category as $cval) {
				$insertdata = array(
					'cit_id' => $cit_id,
					'cca_id' => $cval,
				);
				$this->insert($insertdata);
			}
		}
	}
}
