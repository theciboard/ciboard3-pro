<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Stat Count Date model class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

class Stat_count_date_model extends CB_Model
{

	/**
	 * 테이블명
	 */
	public $_table = 'stat_count_date';

	/**
	 * 사용되는 테이블의 프라이머리키
	 */
	public $primary_key = 'scd_date'; // 사용되는 테이블의 프라이머리키

	function __construct()
	{
		parent::__construct();
	}


	public function add_visit_date()
	{
		$sql = 'INSERT INTO ' . $this->db->dbprefix($this->_table);
		$sql .= " (scd_date, scd_count) VALUES ('" . cdate('Y-m-d') . "', 1) ";
		$sql .= " ON DUPLICATE KEY UPDATE scd_count= scd_count + 1 ";

		return $this->db->query($sql);
	}


	public function get_by_time_day($start_date = '', $end_date = '', $orderby = 'asc')
	{
		if (empty($start_date) OR empty($end_date)) {
			return false;
		}
		if (strtolower($orderby) !== 'desc') $orderby = 'asc';

		$this->db->where('scd_date >=', $start_date);
		$this->db->where('scd_date <=', $end_date);
		$this->db->order_by('scd_date', $orderby);
		$qry = $this->db->get($this->_table);
		$result = $qry->result_array();

		return $result;
	}


	public function get_by_time_month($start_date = '', $end_date = '', $orderby = 'asc')
	{
		if (empty($start_date) OR empty($end_date)) {
			return false;
		}
		if (strtolower($orderby) !== 'desc') $orderby = 'asc';

		$this->db->select_sum('scd_count');
		$this->db->select('SUBSTRING(scd_date,1,7) as date', false);
		$this->db->where('scd_date >=', $start_date);
		$this->db->where('scd_date <=', $end_date);
		$this->db->group_by('date');
		$this->db->order_by('scd_date', $orderby);
		$qry = $this->db->get($this->_table);
		$result = $qry->result_array();

		return $result;
	}


	public function get_by_time_year($start_date = '', $end_date = '', $orderby = 'asc')
	{
		if (empty($start_date) OR empty($end_date)) {
			return false;
		}
		if (strtolower($orderby) !== 'desc') $orderby = 'asc';

		$this->db->select_sum('scd_count');
		$this->db->select('SUBSTRING(scd_date,1,4) as date', false);
		$this->db->where('scd_date >=', $start_date);
		$this->db->where('scd_date <=', $end_date);
		$this->db->group_by('date');
		$this->db->order_by('scd_date', $orderby);
		$qry = $this->db->get($this->_table);
		$result = $qry->result_array();

		return $result;
	}


	public function get_by_time_week($start_date = '', $end_date = '', $orderby = 'asc')
	{
		if (empty($start_date) OR empty($end_date)) {
			return false;
		}
		if (strtolower($orderby) !== 'desc') $orderby = 'asc';

		$this->db->select_sum('scd_count');
		$this->db->select('WEEKDAY(scd_date) as date', false);
		$this->db->where('scd_date >=', $start_date);
		$this->db->where('scd_date <=', $end_date);
		$this->db->group_by('date');
		$this->db->order_by('date', $orderby);
		$qry = $this->db->get($this->_table);
		$result = $qry->result_array();

		return $result;
	}
}
