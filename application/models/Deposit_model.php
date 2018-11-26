<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Deposit model class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

class Deposit_model extends CB_Model
{

	/**
	 * 테이블명
	 */
	public $_table = 'deposit';

	/**
	 * 사용되는 테이블의 프라이머리키
	 */
	public $primary_key = 'dep_id'; // 사용되는 테이블의 프라이머리키

	function __construct()
	{
		parent::__construct();
	}


	public function insert($data=FALSE)
	{
		if ($data !== FALSE) {
			$return = $this->db->insert($this->_table, $data);
			return $return;
		} else {
			return FALSE;
		}
	}


	public function get_admin_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
	{
		$select = 'deposit.*, member.mem_id, member.mem_userid, member.mem_nickname, member.mem_is_admin, member.mem_icon, member.mem_point';
		$join[] = array('table' => 'member', 'on' => 'deposit.mem_id = member.mem_id', 'type' => 'left');
		$result = $this->_get_list_common($select, $join, $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);
		return $result;
	}


	public function get_deposit_sum($mem_id = 0)
	{
		$mem_id = (int) $mem_id;
		if (empty($mem_id) OR $mem_id < 1) {
			return 0;
		}
		$this->db->select_sum('dep_deposit');
		$this->db->where(array('mem_id' => $mem_id, 'dep_status' => 1));
		$result = $this->db->get($this->_table);
		$sum = $result->row_array();

		return isset($sum['dep_deposit']) ? $sum['dep_deposit'] : 0;
	}


	public function get_graph_count($type = 'd', $start_date = '', $end_date = '', $where = '')
	{
		if (empty($start_date) OR empty($end_date)) {
			return false;
		}
		$left = ($type === 'y') ? 4 : ($type === 'm' ? 7 : 10);

		$this->db->select_sum('dep_deposit');
		$this->db->select_sum('dep_cash');
		$this->db->select_sum('dep_point');
		$this->db->select('count(*) as cnt, left(dep_deposit_datetime, ' . $left . ') as day ', false);
		$this->db->where('dep_deposit_datetime >=', $start_date . ' 00:00:00');
		$this->db->where('dep_deposit_datetime <=', $end_date . ' 23:59:59');
		$this->db->where('dep_status', 1);
		if ($where) {
			$this->db->where($where);
		}
		$this->db->group_by(array('day'));
		$qry = $this->db->get($this->_table);
		$result = $qry->result_array();

		return $result;
	}


	public function get_graph_paycount($type = 'd', $start_date = '', $end_date = '', $where = '', $orderby = '')
	{
		if (empty($start_date) OR empty($end_date)) {
			return false;
		}
		if (empty($orderby)) {
			$orderby = 'cnt desc';
		}
		$left = ($type === 'y') ? 4 : ($type === 'm' ? 7 : 10);

		$this->db->select_sum('dep_deposit');
		$this->db->select_sum('dep_cash');
		$this->db->select_sum('dep_point');
		$this->db->select('count(*) as cnt, mem_id', false);
		$this->db->where('dep_status', 1);
		if ($where) {
			$this->db->where($where);
		}
		$this->db->where('dep_deposit_datetime >=', $start_date . ' 00:00:00');
		$this->db->where('dep_deposit_datetime <=', $end_date . ' 23:59:59');
		$this->db->group_by(array('mem_id'));
		$this->db->order_by($orderby);
		$qry = $this->db->get($this->_table);
		$result = $qry->result_array();

		return $result;
	}
}
