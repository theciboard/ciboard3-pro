<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Cmall item detail model class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

class Cmall_item_detail_model extends CB_Model
{

	/**
	 * 테이블명
	 */
	public $_table = 'cmall_item_detail';

	/**
	 * 사용되는 테이블의 프라이머리키
	 */
	public $primary_key = 'cde_id'; // 사용되는 테이블의 프라이머리키

	function __construct()
	{
		parent::__construct();
	}


	public function get_all_detail($cit_id = 0)
	{
		$cit_id = (int) $cit_id;
		if (empty($cit_id) OR $cit_id < 1) {
			return;
		}

		$this->db->select('*');
		$this->db->where('cit_id', $cit_id);
		$this->db->where('cde_status', 1);
		$this->db->order_by('cde_id', 'ASC');
		$qry = $this->db->get($this->_table);
		$result = $qry->result_array();

		return $result;
	}


	public function get_all_cart_detail($cit_id = 0)
	{
		$cit_id = (int) $cit_id;
		if (empty($cit_id) OR $cit_id < 1) {
			return;
		}

		$this->db->select('cmall_item_detail.cde_id, cmall_item_detail.cit_id, cmall_item_detail.cde_title, cmall_item_detail.cde_price,
			cmall_item_detail.cde_originname, cmall_item_detail.cde_filename,
			cmall_item_detail.cde_download, cmall_item_detail.cde_status');
		$this->db->where('cmall_item_detail.cit_id', $cit_id);
		$this->db->where('cmall_item_detail.cde_status', 1);
		$this->db->order_by('cmall_item_detail.cde_id', 'ASC');
		$qry = $this->db->get($this->_table);
		$result = $qry->result_array();

		return $result;
	}
}
