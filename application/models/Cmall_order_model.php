<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Cmall order model class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

class Cmall_order_model extends CB_Model
{

	/**
	 * 테이블명
	 */
	public $_table = 'cmall_order';

	/**
	 * 사용되는 테이블의 프라이머리키
	 */
	public $primary_key = 'cor_id'; // 사용되는 테이블의 프라이머리키

	function __construct()
	{
		parent::__construct();
	}


	public function insert($data)
	{
		if ($data) {
			$return = $this->db->insert($this->_table, $data);
			return $return;
		} else {
			return FALSE;
		}
	}

	public function get_check_pg_order($cor_id, $cor_tno){

		$cor_id = preg_replace('/[^0-9]/', '', $cor_id);

		if (empty($cor_id) OR $cor_id < 1) {
			return;
		}

		$this->db->select('cit_id');
		$where = array(
			'cor_id' => $cor_id,
			'cor_tno' => $cor_tno
			);
		$this->db->where('cor_id', $where);
		$qry = $this->db->get($this->_table);

		$result = $qry->result_array();

		return $result;
	}


	public function get_admin_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
	{
		$select = 'cmall_order.*, member.mem_id, member.mem_userid, member.mem_nickname, member.mem_is_admin, member.mem_icon, member.mem_point';
		$join[] = array('table' => 'member', 'on' => 'cmall_order.mem_id = member.mem_id', 'type' => 'left');
		$result = $this->_get_list_common($select, $join, $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);
		return $result;
	}


	public function get_graph_count($type = 'd', $start_date = '', $end_date = '', $where = '')
	{
		if (empty($start_date) OR empty($end_date)) {
			return false;
		}
		$left = ($type === 'y') ? 4 : ($type === 'm' ? 7 : 10);

		$this->db->select_sum('cor_total_money');
		$this->db->select('count(*) as cnt, left(cor_approve_datetime, ' . $left . ') as day ', false);
		$this->db->where('cor_approve_datetime >=', $start_date . ' 00:00:00');
		$this->db->where('cor_approve_datetime <=', $end_date . ' 23:59:59');
		$this->db->where('cor_status', 1);
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

		$this->db->select_sum('cor_total_money');
		$this->db->select('count(*) as cnt, mem_id', false);
		$this->db->where('cor_status', 1);
		if ($where) {
			$this->db->where($where);
		}
		$this->db->where('cor_approve_datetime >=', $start_date . ' 00:00:00');
		$this->db->where('cor_approve_datetime <=', $end_date . ' 23:59:59');
		$this->db->group_by(array('mem_id'));
		$this->db->order_by($orderby);
		$qry = $this->db->get($this->_table);
		$result = $qry->result_array();

		return $result;
	}


	public function is_ordered_item($mem_id = 0, $cit_id = 0)
	{
		$mem_id = preg_replace('/[^0-9]/', '', $mem_id);
		if (empty($mem_id) OR $mem_id < 1) {
			return;
		}
		$cit_id = preg_replace('/[^0-9]/', '', $cit_id);
		if (empty($cit_id) OR $cit_id < 1) {
			return;
		}

		$this->db->select('count(*) as rownum');
		$this->db->join('cmall_order_detail', 'cmall_order.cor_id = cmall_order_detail.cor_id', 'inner');
		$where = array(
			'cmall_order.mem_id' => $mem_id,
			'cmall_order_detail.cit_id' => $cit_id,
		);
		$this->db->where($where);
		$qry = $this->db->get($this->_table);
		$rows = $qry->row_array();
		return $rows['rownum'];
	}


	public function is_ordered_item_detail($mem_id = 0, $cor_id = 0, $cde_id = 0)
	{
		$mem_id = preg_replace('/[^0-9]/', '', $mem_id);
		if (empty($mem_id) OR $mem_id < 1) {
			return;
		}
		$cor_id = preg_replace('/[^0-9]/', '', $cor_id);
		if (empty($cor_id) OR $cor_id < 1) {
			return;
		}
		$cde_id = preg_replace('/[^0-9]/', '', $cde_id);
		if (empty($cde_id) OR $cde_id < 1) {
			return;
		}

		$this->db->select('cmall_order.cor_id, cmall_order.cor_approve_datetime, cmall_order_detail.cod_download_days');
		$this->db->join('cmall_order_detail', 'cmall_order.cor_id = cmall_order_detail.cor_id', 'inner');
		$where = array(
			'cmall_order.mem_id' => $mem_id,
			'cmall_order.cor_status' => 1,
			'cmall_order.cor_id' => $cor_id,
			'cmall_order_detail.cde_id' => $cde_id,
		);
		$this->db->where($where);
		$qry = $this->db->get($this->_table);
		$rows = $qry->row_array();
		return $rows;
	}
}
