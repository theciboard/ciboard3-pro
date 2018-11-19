<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Banner Click Log model class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

class Banner_click_log_model extends CB_Model
{

	/**
	 * 테이블명
	 */
	public $_table = 'banner_click_log';

	/**
	 * 사용되는 테이블의 프라이머리키
	 */
	public $primary_key = 'bcl_id'; // 사용되는 테이블의 프라이머리키

	function __construct()
	{
		parent::__construct();
	}


	public function get_admin_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
	{
		$select = 'banner_click_log.*, banner.bng_name, banner.ban_title, banner.ban_datetime, banner.ban_hit, banner.ban_image, banner.ban_url';
		$join[] = array('table' => 'banner', 'on' => 'banner_click_log.ban_id = banner.ban_id', 'type' => 'inner');
		$result = $this->_get_list_common($select, $join, $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);
		return $result;
	}


	public function get_banner_click_count($type = 'd', $start_date = '', $end_date = '', $orderby = 'asc')
	{
		if (empty($start_date) OR empty($end_date)) {
			return false;
		}
		$left = ($type === 'y') ? 4 : ($type === 'm' ? 7 : 10);
		if (strtolower($orderby) !== 'desc') $orderby = 'asc';

		$this->db->select('count(*) as cnt, left(bcl_datetime, ' . $left . ') as day ', false);
		$this->db->where('left(bcl_datetime, 10) >=', $start_date);
		$this->db->where('left(bcl_datetime, 10) <=', $end_date);
		$this->db->group_by('day');
		$this->db->order_by('bcl_datetime', $orderby);
		$qry = $this->db->get($this->_table);
		$result = $qry->result_array();

		return $result;
	}
}
