<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Cmall download log model class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

class Cmall_download_log_model extends CB_Model
{

	/**
	 * 테이블명
	 */
	public $_table = 'cmall_download_log';

	/**
	 * 사용되는 테이블의 프라이머리키
	 */
	public $primary_key = 'cdo_id'; // 사용되는 테이블의 프라이머리키

	function __construct()
	{
		parent::__construct();
	}


	public function get_admin_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
	{
		$select = 'cmall_download_log.*, cmall_item.cit_datetime, cmall_item.cit_hit, cmall_item.cit_key,
			cmall_item.cit_name, cmall_item.cit_file_1, cmall_item_detail.cde_title, cmall_item_detail.cde_price,
			cmall_item_detail.cde_originname, cmall_item_detail.cde_filename, cmall_item_detail.cde_download,
			cmall_item_detail.cde_filesize, cmall_item_detail.cde_type, cmall_item_detail.cde_is_image,
			cmall_item_detail.cde_datetime, cmall_item_detail.cde_ip';
		$join[] = array('table' => 'cmall_item_detail', 'on' => 'cmall_download_log.cde_id = cmall_item_detail.cde_id', 'type' => 'inner');
		$join[] = array('table' => 'cmall_item', 'on' => 'cmall_item_detail.cit_id = cmall_item.cit_id', 'type' => 'inner');
		$result = $this->_get_list_common($select, $join, $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);

		return $result;
	}


	public function get_file_download_count($type = 'd', $start_date = '', $end_date = '', $orderby = 'asc')
	{
		if (empty($start_date) OR empty($end_date)) {
			return false;
		}

		$left = ($type === 'y') ? 4 : ($type === 'm' ? 7 : 10);
		if (strtolower($orderby) !== 'desc') $orderby = 'asc';

		$this->db->select('count(*) as cnt, left(cdo_datetime, ' . $left . ') as day ', false);
		$this->db->where('left(cdo_datetime, 10) >=', $start_date);
		$this->db->where('left(cdo_datetime, 10) <=', $end_date);
		$this->db->group_by('day');
		$this->db->order_by('cdo_datetime', $orderby);
		$qry = $this->db->get($this->_table);
		$result = $qry->result_array();

		return $result;
	}
}
