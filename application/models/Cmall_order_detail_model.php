<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Cmall order detail model class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

class Cmall_order_detail_model extends CB_Model
{

	/**
	 * 테이블명
	 */
	public $_table = 'cmall_order_detail';

	/**
	 * 사용되는 테이블의 프라이머리키
	 */
	public $primary_key = 'cod_id'; // 사용되는 테이블의 프라이머리키

	function __construct()
	{
		parent::__construct();
	}


	public function get_by_item($cor_id = 0)
	{
		$cor_id = preg_replace('/[^0-9]/', '', $cor_id);
		if (empty($cor_id) OR $cor_id < 1) {
			return;
		}

		$this->db->select('cit_id');
		$this->db->where('cor_id', $cor_id);
		$this->db->group_by('cit_id');
		$qry = $this->db->get($this->_table);
		$result = $qry->result_array();

		return $result;
	}


	public function get_detail_by_item($cor_id = 0, $cit_id = 0)
	{
		$cor_id = preg_replace('/[^0-9]/', '', $cor_id);
		if (empty($cor_id) OR $cor_id < 1) {
			return;
		}
		$cit_id = preg_replace('/[^0-9]/', '', $cit_id);
		if (empty($cit_id) OR $cit_id < 1) {
			return;
		}

		$this->db->select('cmall_item_detail.*, cmall_order_detail.cod_count, cmall_order_detail.cod_download_days, cmall_order_detail.cod_status');
		$this->db->from('cmall_order_detail');
		$this->db->join('cmall_item_detail', 'cmall_order_detail.cde_id = cmall_item_detail.cde_id', 'inner');
		$this->db->where('cmall_order_detail.cor_id', $cor_id);
		$this->db->where('cmall_order_detail.cit_id', $cit_id);
		$qry = $this->db->get();
		$result = $qry->result_array();

		return $result;
	}
}
