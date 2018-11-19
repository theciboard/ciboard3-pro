<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Search Keyword model class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

class Search_keyword_model extends CB_Model
{

	/**
	 * 테이블명
	 */
	public $_table = 'search_keyword';

	/**
	 * 사용되는 테이블의 프라이머리키
	 */
	public $primary_key = 'sek_id'; // 사용되는 테이블의 프라이머리키

	function __construct()
	{
		parent::__construct();
	}


	public function get_admin_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
	{
		$select = 'search_keyword.*, member.mem_id, member.mem_userid, member.mem_nickname, member.mem_is_admin, member.mem_icon';
		$join[] = array('table' => 'member', 'on' => 'search_keyword.mem_id = member.mem_id', 'type' => 'left');
		$result = $this->_get_list_common($select, $join, $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);
		return $result;
	}


	public function get_rank($start_date = '', $end_date = '')
	{
		if (empty($start_date) OR empty($end_date)) {
			return false;
		}

		$this->db->where('left(sek_datetime, 10) >=', $start_date);
		$this->db->where('left(sek_datetime, 10) <=', $end_date);
		$this->db->select('sek_keyword');
		$qry = $this->db->get($this->_table);
		$result = $qry->result_array();

		return $result;
	}
}
