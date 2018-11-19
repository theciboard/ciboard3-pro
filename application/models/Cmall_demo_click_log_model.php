<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Cmall demo click log model class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

class Cmall_demo_click_log_model extends CB_Model
{

	/**
	 * 테이블명
	 */
	public $_table = 'cmall_demo_click_log';

	/**
	 * 사용되는 테이블의 프라이머리키
	 */
	public $primary_key = 'cdc_id'; // 사용되는 테이블의 프라이머리키

	function __construct()
	{
		parent::__construct();
	}


	public function get_admin_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
	{
		$select = 'cmall_demo_click_log.*, cmall_item.mem_id as item_mem_id, cmall_item.cit_datetime, cmall_item.cit_hit, cmall_item.cit_name, cmall_item.cit_key, cmall_item.cit_file_1';
		$join[] = array('table' => 'cmall_item', 'on' => 'cmall_demo_click_log.cit_id = cmall_item.cit_id', 'type' => 'inner');
		$result = $this->_get_list_common($select, $join, $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);
		return $result;
	}


	public function get_link_click_count($type = 'd', $start_date = '', $end_date = '', $orderby = 'asc')
	{
		if (empty($start_date) OR empty($end_date)) {
			return false;
		}
		$left = ($type === 'y') ? 4 : ($type === 'm' ? 7 : 10);
		if (strtolower($orderby) !== 'desc') $orderby = 'asc';

		$this->db->select('count(*) as cnt, left(cdc_datetime, ' . $left . ') as day ', false);
		$this->db->where('left(cdc_datetime, 10) >=', $start_date);
		$this->db->where('left(cdc_datetime, 10) <=', $end_date);
		$this->db->group_by('day');
		$this->db->order_by('cdc_datetime', $orderby);
		$qry = $this->db->get($this->_table);
		$result = $qry->result_array();

		return $result;
	}
}
