<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Blame model class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

class Blame_model extends CB_Model
{

	/**
	 * 테이블명
	 */
	public $_table = 'blame';

	/**
	 * 사용되는 테이블의 프라이머리키
	 */
	public $primary_key = 'bla_id'; // 사용되는 테이블의 프라이머리키

	function __construct()
	{
		parent::__construct();
	}


	public function get_admin_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
	{

		$select = 'blame.*, member.mem_id, member.mem_userid, member.mem_nickname, member.mem_is_admin, member.mem_icon';
		$join[] = array('table' => 'member', 'on' => 'blame.mem_id = member.mem_id', 'type' => 'left');
		$result = $this->_get_list_common($select, $join, $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);
		return $result;
	}


	public function get_blame_count($type = 'd', $start_date = '', $end_date = '', $brd_id = 0, $orderby = 'asc')
	{
		if (empty($start_date) OR empty($end_date)) {
			return false;
		}
		$left = ($type === 'y') ? 4 : ($type === 'm' ? 7 : 10);
		if (strtolower($orderby) !== 'desc') $orderby = 'asc';

		$this->db->select('count(*) as cnt, left(bla_datetime, ' . $left . ') as day ', false);
		$this->db->where('left(bla_datetime, 10) >=', $start_date);
		$this->db->where('left(bla_datetime, 10) <=', $end_date);

		$brd_id = (int) $brd_id;
		if ($brd_id) {
			$this->db->where('brd_id', $brd_id);
		}
		$this->db->group_by('day');
		$this->db->order_by('bla_datetime', $orderby);
		$qry = $this->db->get($this->_table);
		$result = $qry->result_array();

		return $result;

	}


	public function get_blame_count_by_board($start_date = '', $end_date = '')
	{
		if (empty($start_date) OR empty($end_date)) {
			return false;
		}

		$this->db->select('count(*) as cnt, brd_id', false);
		$this->db->where('left(bla_datetime, 10) >=', $start_date);
		$this->db->where('left(bla_datetime, 10) <=', $end_date);
		$this->db->group_by('brd_id');
		$this->db->order_by('cnt', 'desc');
		$qry = $this->db->get($this->_table);
		$result = $qry->result_array();

		return $result;
	}
}
