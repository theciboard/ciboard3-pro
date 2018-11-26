<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Member Dormant model class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

class Member_dormant_model extends CB_Model
{

	/**
	 * 테이블명
	 */
	public $_table = 'member_dormant';

	/**
	 * 사용되는 테이블의 프라이머리키
	 */
	public $primary_key = 'mem_id'; // 사용되는 테이블의 프라이머리키

	public $search_sfield = '';

	function __construct()
	{
		parent::__construct();
	}


	public function get_by_memid($memid = 0, $select = '')
	{
		$memid = (int) $memid;
		if (empty($memid) OR $memid < 1) {
			return false;
		}
		$where = array('mem_id' => $memid);
		return $this->get_one('', $select, $where);
	}


	public function get_by_userid($userid = '', $select = '')
	{
		if (empty($userid)) {
			return false;
		}
		$where = array('mem_userid' => $userid);
		return $this->get_one('', $select, $where);
	}


	public function get_by_email($email = '', $select = '')
	{
		if (empty($email)) {
			return false;
		}
		$where = array('mem_email' => $email);
		return $this->get_one('', $select, $where);
	}


	public function get_by_both($str = '', $select = '')
	{
		if (empty($str)) {
			return false;
		}
		if ($select) {
			$this->db->select($select);
		}
		$this->db->from($this->_table);
		$this->db->where('mem_userid', $str);
		$this->db->or_where('mem_email', $str);
		$result = $this->db->get();
		return $result->row_array();
	}
}
