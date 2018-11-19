<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Cmall qna model class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

class Cmall_qna_model extends CB_Model
{

	/**
	 * 테이블명
	 */
	public $_table = 'cmall_qna';

	/**
	 * 사용되는 테이블의 프라이머리키
	 */
	public $primary_key = 'cqa_id'; // 사용되는 테이블의 프라이머리키

	function __construct()
	{
		parent::__construct();
	}


	public function get_admin_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
	{
		$select = 'cmall_qna.*, member.mem_id, member.mem_userid, member.mem_nickname, member.mem_is_admin,
			member.mem_icon, cmall_item.cit_datetime, cmall_item.cit_hit, cmall_item.cit_name, cmall_item.cit_key';
		$join[] = array('table' => 'cmall_item', 'on' => 'cmall_qna.cit_id = cmall_item.cit_id', 'type' => 'inner');
		$join[] = array('table' => 'member', 'on' => 'cmall_qna.mem_id = member.mem_id', 'type' => 'left');
		$result = $this->_get_list_common($select, $join, $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);
		return $result;
	}


	public function get_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
	{
		$select = 'cmall_qna.*, member.mem_id, member.mem_userid, member.mem_nickname, member.mem_is_admin, member.mem_icon';
		$join[] = array('table' => 'member', 'on' => 'cmall_qna.mem_id = member.mem_id', 'type' => 'left');
		$result = $this->_get_list_common($select, $join, $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);
		return $result;
	}


	public function get_qna_count($cit_id = 0)
	{
		$cit_id = (int) $cit_id;
		if (empty($cit_id) OR $cit_id < 1) {
			return;
		}

		$this->db->select('count(*) as cnt, cit_id', false);
		$this->db->where('cit_id', $cit_id);
		$this->db->group_by(array('cit_id'));
		$qry = $this->db->get($this->_table);
		$result = $qry->row_array();

		return $result;
	}
}
