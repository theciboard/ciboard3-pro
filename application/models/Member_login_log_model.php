<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Member Login Log model class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

class Member_login_log_model extends CB_Model
{

	/**
	 * 테이블명
	 */
	public $_table = 'member_login_log';

	/**
	 * 사용되는 테이블의 프라이머리키
	 */
	public $primary_key = 'mll_id'; // 사용되는 테이블의 프라이머리키

	function __construct()
	{
		parent::__construct();
	}


	public function get_admin_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
	{
		$select = 'member_login_log.*, member.mem_id, member.mem_userid, member.mem_nickname, member.mem_is_admin, member.mem_icon';
		$join[] = array('table' => 'member', 'on' => 'member_login_log.mem_id = member.mem_id', 'type' => 'left');
		$result = $this->_get_list_common($select, $join, $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);
		return $result;
	}


	public function get_login_success_count($type = 'd', $start_date = '', $end_date = '', $orderby = 'asc')
	{
		if (empty($start_date) OR empty($end_date)) {
			return false;
		}
		$left = ($type === 'y') ? 4 : ($type === 'm' ? 7 : 10);
		if (strtolower($orderby) !== 'desc') $orderby = 'asc';

		$this->db->select('count(*) as cnt, left(mll_datetime, ' . $left . ') as day ', false);
		$this->db->where('mll_success', 1);
		$this->db->where('left(mll_datetime, 10) >=', $start_date);
		$this->db->where('left(mll_datetime, 10) <=', $end_date);
		$this->db->group_by('day');
		$this->db->order_by('mll_datetime', $orderby);
		$qry = $this->db->get($this->_table);
		$result = $qry->result_array();

		return $result;
	}


	public function get_login_fail_count($type = 'd', $start_date = '', $end_date = '', $orderby = 'asc')
	{
		if (empty($start_date) OR empty($end_date)) {
			return false;
		}
		$left = ($type === 'y') ? 4 : ($type === 'm' ? 7 : 10);
		if (strtolower($orderby) !== 'desc') $orderby = 'asc';

		$this->db->select('count(*) as cnt, left(mll_datetime, ' . $left . ') as day ', false);
		$this->db->where('mll_success', 0);
		$this->db->where('left(mll_datetime, 10) >=', $start_date);
		$this->db->where('left(mll_datetime, 10) <=', $end_date);
		$this->db->group_by('day');
		$this->db->order_by('mll_datetime', $orderby);
		$qry = $this->db->get($this->_table);
		$result = $qry->result_array();

		return $result;
	}
}
