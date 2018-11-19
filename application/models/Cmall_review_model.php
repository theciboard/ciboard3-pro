<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Cmall review model class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

class Cmall_review_model extends CB_Model
{

	/**
	 * 테이블명
	 */
	public $_table = 'cmall_review';

	/**
	 * 사용되는 테이블의 프라이머리키
	 */
	public $primary_key = 'cre_id'; // 사용되는 테이블의 프라이머리키

	function __construct()
	{
		parent::__construct();
	}


	public function get_admin_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
	{
		$select = 'cmall_review.*, member.mem_id, member.mem_userid, member.mem_nickname, member.mem_is_admin,
			member.mem_icon, cmall_item.cit_datetime, cmall_item.cit_hit, cmall_item.cit_name, cmall_item.cit_key';
		$join[] = array('table' => 'cmall_item', 'on' => 'cmall_review.cit_id = cmall_item.cit_id', 'type' => 'inner');
		$join[] = array('table' => 'member', 'on' => 'cmall_review.mem_id = member.mem_id', 'type' => 'left');
		$result = $this->_get_list_common($select, $join, $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);
		return $result;
	}


	public function get_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
	{
		$select = 'cmall_review.*, member.mem_id, member.mem_userid, member.mem_nickname, member.mem_is_admin, member.mem_icon';
		$join[] = array('table' => 'member', 'on' => 'cmall_review.mem_id = member.mem_id', 'type' => 'left');
		$result = $this->_get_list_common($select, $join, $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);
		return $result;
	}


	public function get_review_count($cit_id = 0)
	{
		$cit_id = (int) $cit_id;
		if (empty($cit_id) OR $cit_id < 1) {
			return;
		}

		$this->db->select_sum('cre_score');
		$this->db->select('count(*) as cnt, cit_id', false);
		$this->db->where('cre_status', 1);
		$this->db->where('cit_id', $cit_id);
		$this->db->group_by(array('cit_id'));
		$qry = $this->db->get($this->_table);
		$result = $qry->row_array();

		return $result;
	}
}
