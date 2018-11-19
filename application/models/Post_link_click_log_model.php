<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Post Link Click Log model class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

class Post_link_click_log_model extends CB_Model
{

	/**
	 * 테이블명
	 */
	public $_table = 'post_link_click_log';

	/**
	 * 사용되는 테이블의 프라이머리키
	 */
	public $primary_key = 'plc_id'; // 사용되는 테이블의 프라이머리키

	function __construct()
	{
		parent::__construct();
	}


	public function get_admin_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
	{
		$select = 'post_link_click_log.*, post.mem_id as post_mem_id, post.post_userid, post.post_nickname,
			post.brd_id, post.post_datetime, post.post_hit, post.post_secret, post.post_title, post_link.*';
		$join[] = array('table' => 'post_link', 'on' => 'post_link_click_log.pln_id = post_link.pln_id', 'type' => 'inner');
		$join[] = array('table' => 'post', 'on' => 'post_link.post_id = post.post_id', 'type' => 'inner');
		$result = $this->_get_list_common($select, $join, $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);

		return $result;
	}


	public function get_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
	{
		$select = 'post_link_click_log.*, post.mem_id as post_mem_id, post.post_userid, post.post_nickname,
			post.brd_id, post.post_datetime, post.post_hit, post.post_secret, post.post_title, post_link.*';
		$join[] = array('table' => 'post_link', 'on' => 'post_link_click_log.pln_id = post_link.pln_id', 'type' => 'inner');
		$join[] = array('table' => 'post', 'on' => 'post_link.post_id = post.post_id', 'type' => 'inner');
		$result = $this->_get_list_common($select, $join, $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);

		return $result;
	}


	public function get_link_click_count($type = 'd', $start_date = '', $end_date = '', $brd_id = 0, $orderby = 'asc')
	{
		if (empty($start_date) OR empty($end_date)) {
			return false;
		}
		$left = ($type === 'y') ? 4 : ($type === 'm' ? 7 : 10);
		if (strtolower($orderby) !== 'desc') $orderby = 'asc';

		$this->db->select('count(*) as cnt, left(plc_datetime, ' . $left . ') as day ', false);
		$this->db->where('left(plc_datetime, 10) >=', $start_date);
		$this->db->where('left(plc_datetime, 10) <=', $end_date);
		$brd_id = (int) $brd_id;
		if ($brd_id) {
			$this->db->where('brd_id', $brd_id);
		}
		$this->db->group_by('day');
		$this->db->order_by('plc_datetime', $orderby);
		$qry = $this->db->get($this->_table);
		$result = $qry->result_array();

		return $result;
	}
}
