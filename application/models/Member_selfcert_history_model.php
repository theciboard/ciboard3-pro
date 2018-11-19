<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Member Selfcert History model class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

class Member_selfcert_history_model extends CB_Model
{

	/**
	 * 테이블명
	 */
	public $_table = 'member_selfcert_history';

	/**
	 * 사용되는 테이블의 프라이머리키
	 */
	public $primary_key = 'msh_id'; // 사용되는 테이블의 프라이머리키

	function __construct()
	{
		parent::__construct();
	}


	public function tried_count($type = '', $date = '', $mem_id = '', $ip = '')
	{

		$this->db->select('count(*) as cnt', false);
		$this->db->where('msh_certtype', $type);
		$this->db->where('left(msh_datetime, 10) =', $date);

		if ($mem_id) {
			$this->db->where('mem_id', $mem_id);
		} else {
			$this->db->where('msh_ip', $ip);
		}

		$qry = $this->db->get($this->_table);
		$result = $qry->row_array();

		return $result;

	}
}
