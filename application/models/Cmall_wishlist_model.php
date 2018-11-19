<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Cmall wishlist model class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

class Cmall_wishlist_model extends CB_Model
{

	/**
	 * 테이블명
	 */
	public $_table = 'cmall_wishlist';

	/**
	 * 사용되는 테이블의 프라이머리키
	 */
	public $primary_key = 'cwi_id'; // 사용되는 테이블의 프라이머리키

	function __construct()
	{
		parent::__construct();
	}


	public function get_admin_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
	{
		$select = 'cmall_wishlist.*, member.mem_id, member.mem_userid, member.mem_nickname, member.mem_is_admin,
			member.mem_icon, cmall_item.cit_name, cmall_item.cit_key, cmall_item.cit_file_1';
		$join[] = array('table' => 'cmall_item', 'on' => 'cmall_wishlist.cit_id = cmall_item.cit_id', 'type' => 'inner');
		$join[] = array('table' => 'member', 'on' => 'cmall_wishlist.mem_id = member.mem_id', 'type' => 'left');
		$result = $this->_get_list_common($select, $join, $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);
		return $result;
	}


	public function get_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
	{
		$select = 'cmall_wishlist.*, cmall_item.cit_name, cmall_item.cit_key, cmall_item.cit_file_1';
		$join[] = array('table' => 'cmall_item', 'on' => 'cmall_wishlist.cit_id = cmall_item.cit_id', 'type' => 'inner');
		$result = $this->_get_list_common($select, $join, $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);
		return $result;
	}


	public function get_rank($start_date = '', $end_date = '')
	{
		if (empty($start_date) OR empty($end_date)) {
			return false;
		}

		$this->db->where('left(cwi_datetime, 10) >=', $start_date);
		$this->db->where('left(cwi_datetime, 10) <=', $end_date);
		$this->db->select('cmall_wishlist.cit_id, cmall_item.cit_name');
		$this->db->join('cmall_item', 'cmall_wishlist.cit_id = cmall_item.cit_id', 'inner');
		$qry = $this->db->get($this->_table);
		$result = $qry->result_array();

		return $result;
	}
}
