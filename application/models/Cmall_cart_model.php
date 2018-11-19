<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Cmall cart model class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

class Cmall_cart_model extends CB_Model
{

	/**
	 * 테이블명
	 */
	public $_table = 'cmall_cart';

	/**
	 * 사용되는 테이블의 프라이머리키
	 */
	public $primary_key = 'cct_id'; // 사용되는 테이블의 프라이머리키

	function __construct()
	{
		parent::__construct();
	}


	public function get_admin_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
	{
		$where['cmall_cart.cct_cart'] = 1;
		$select = 'cmall_cart.*, member.mem_id, member.mem_userid, member.mem_nickname,
			member.mem_is_admin, member.mem_icon, cmall_item.cit_name, cmall_item.cit_key, cmall_item.cit_file_1, cmall_item_detail.cde_title';
		$join[] = array('table' => 'cmall_item', 'on' => 'cmall_cart.cit_id = cmall_item.cit_id', 'type' => 'inner');
		$join[] = array('table' => 'cmall_item_detail', 'on' => 'cmall_cart.cde_id = cmall_item_detail.cde_id', 'type' => 'inner');
		$join[] = array('table' => 'member', 'on' => 'cmall_cart.mem_id = member.mem_id', 'type' => 'left');
		$result = $this->_get_list_common($select, $join, $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);
		return $result;
	}


	public function get_cart_list($where = '', $findex = '', $forder = '', $limit = '')
	{
		$this->db->select('cmall_cart.*, cmall_item.cit_name, cmall_item.cit_key, cmall_item.cit_file_1, cmall_item.cit_price');
		$this->db->join('cmall_item', 'cmall_cart.cit_id = cmall_item.cit_id', 'inner');
		if ($where) {
			$this->db->where($where);
		}
		$this->db->where(array('cmall_cart.cct_cart' => 1));
		$this->db->where(array('cmall_item.cit_status' => 1));
		$this->db->order_by($findex, $forder);
		$this->db->group_by('cmall_cart.cit_id');
		if ($limit) {
			$this->db->limit($limit);
		}
		$qry = $this->db->get($this->_table);
		$result = $qry->result_array();
		return $result;
	}


	public function get_cart_detail($mem_id = 0, $cit_id = 0)
	{
		$mem_id = (int) $mem_id;
		if (empty($mem_id) OR $mem_id < 1) {
			return;
		}
		$cit_id = (int) $cit_id;
		if (empty($cit_id) OR $cit_id < 1) {
			return;
		}

		$this->db->select('cmall_item_detail.*, cmall_cart.cct_count, cct_datetime');
		$this->db->join('cmall_item_detail', 'cmall_item_detail.cde_id = cmall_cart.cde_id', 'inner');
		$this->db->where(array('cmall_cart.cit_id' => $cit_id));
		$this->db->where(array('cmall_cart.mem_id' => $mem_id));
		$this->db->where(array('cmall_cart.cct_cart' => 1));
		$this->db->where(array('cmall_item_detail.cde_status' => 1));
		$this->db->order_by('cmall_item_detail.cde_id', 'asc');
		$qry = $this->db->get($this->_table);
		$result = $qry->result_array();

		return $result;
	}


	public function get_order_list($where = '', $findex = '', $forder = '', $limit = '')
	{
		$this->db->select('cmall_cart.*, cmall_item.cit_name, cmall_item.cit_key, cmall_item.cit_file_1, cmall_item.cit_price, cmall_item.cit_download_days');
		$this->db->join('cmall_item', 'cmall_cart.cit_id = cmall_item.cit_id', 'inner');
		if ($where) {
			$this->db->where($where);
		}
		$this->db->where(array('cmall_cart.cct_order' => 1));
		$this->db->order_by($findex, $forder);
		$this->db->group_by('cmall_cart.cit_id');
		if ($limit) {
			$this->db->limit($limit);
		}
		$qry = $this->db->get($this->_table);
		$result = $qry->result_array();

		return $result;
	}

	public function get_cart_list_in( $where = '', $where_in = '', $limit = '', $offset = '' ){

		if ($where) {
			$this->db->where($where);
		}
		if ($where_in) {
			$this->db->where_in('cct_id', $where_in);
		}
		$this->db->order_by('cct_datetime', 'desc');
		if ($limit) {
			$this->db->limit($limit, $offset);
		}

		$qry = $this->db->get($this->_table);
		$result = $qry->result_array();
		return $result;

	}

	public function get_order_detail($mem_id = 0, $cit_id = 0)
	{
		$mem_id = (int) $mem_id;
		if (empty($mem_id) OR $mem_id < 1) {
			return;
		}
		$cit_id = (int) $cit_id;
		if (empty($cit_id) OR $cit_id < 1) {
			return;
		}

		$this->db->select('cmall_item_detail.*, cmall_cart.cct_count, cct_datetime');
		$this->db->join('cmall_item_detail', 'cmall_item_detail.cde_id = cmall_cart.cde_id', 'inner');
		$this->db->where(array('cmall_cart.cit_id' => $cit_id));
		$this->db->where(array('cmall_cart.mem_id' => $mem_id));
		$this->db->where(array('cmall_cart.cct_order' => 1));
		$this->db->order_by('cmall_item_detail.cde_id', 'asc');
		$qry = $this->db->get($this->_table);
		$result = $qry->result_array();

		return $result;
	}


	public function get_item_is_cart($cde_id = 0, $mem_id = 0)
	{
		$cde_id = (int) $cde_id;
		if (empty($cde_id) OR $cde_id < 1) {
			return;
		}
		$mem_id = (int) $mem_id;
		if (empty($mem_id) OR $mem_id < 1) {
			return;
		}
		$where = array(
			'cde_id' => $cde_id,
			'mem_id' => $mem_id,
			'cct_cart' => 1,
		);
		$this->db->where($where);
		$this->db->order_by('cde_id', 'ASC');
		$qry = $this->db->get($this->_table);
		$result = $qry->row_array();

		return $result;
	}


	public function get_rank($start_date = '', $end_date = '')
	{
		if (empty($start_date) OR empty($end_date)) {
			return false;
		}

		$this->db->where('left(cct_datetime, 10) >=', $start_date);
		$this->db->where('left(cct_datetime, 10) <=', $end_date);
		$this->db->where('cct_cart', 1);
		$this->db->select('cmall_cart.cit_id, cmall_item.cit_name');
		$this->db->join('cmall_item', 'cmall_cart.cit_id = cmall_item.cit_id', 'inner');
		$qry = $this->db->get($this->_table);
		$result = $qry->result_array();

		return $result;
	}
}
