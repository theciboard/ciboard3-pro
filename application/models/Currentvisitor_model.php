<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Currentvisitor model class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

class Currentvisitor_model extends CB_Model
{

	/**
	 * 테이블명
	 */
	public $_table = 'currentvisitor';

	/**
	 * 사용되는 테이블의 프라이머리키
	 */
	public $primary_key = 'cur_ip'; // 사용되는 테이블의 프라이머리키

	function __construct()
	{
		parent::__construct();
	}


	public function add_visitor($cur_ip = '', $mem_id = 0, $cur_mem_name = '', $cur_datetime = '', $cur_page = '', $cur_url = '', $cur_referer = '', $cur_useragent = '')
	{
		$mem_id = (int) $mem_id;
		if (empty($mem_id)) {
			$mem_id = 0;
		}
		if (empty($cur_mem_name)) {
			$cur_mem_name = '';
		}

		$updatedata = array(
			'cur_ip' => $cur_ip,
			'mem_id' => $mem_id,
			'cur_mem_name' => $cur_mem_name,
			'cur_datetime' => $cur_datetime,
			'cur_page' => $cur_page,
			'cur_url' => $cur_url,
			'cur_referer' => $cur_referer,
			'cur_useragent' => $cur_useragent,
		);
		$result = $this->db->replace($this->_table, $updatedata);

		return $result;
	}


	public function get_admin_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
	{
		$select = 'currentvisitor.*, member.mem_id, member.mem_userid, member.mem_nickname, member.mem_is_admin, member.mem_icon';
		$join[] = array('table' => 'member', 'on' => 'currentvisitor.mem_id = member.mem_id', 'type' => 'left');
		$result = $this->_get_list_common($select, $join, $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);
		return $result;
	}


	public function get_current_list($curdatetime = '', $limit = '', $offset = '')
	{
		$this->db->select('currentvisitor.*, member.mem_id, member.mem_userid, member.mem_nickname, member.mem_is_admin, member.mem_icon');
		$this->db->from($this->_table);
		$this->db->join('member', 'currentvisitor.mem_id = member.mem_id', 'left');
		$this->db->where(array('cur_datetime >' => $curdatetime));
		$this->db->group_start();
		$this->db->where(array('member.mem_is_admin' => 0));
		$this->db->or_where(array('member.mem_is_admin' => null));
		$this->db->group_end();
		$this->db->order_by('cur_datetime', 'desc');
		if ($limit) {
			$this->db->limit($limit, $offset);
		}
		$qry = $this->db->get();
		$result['list'] = $qry->result_array();

		$this->db->select('count(*) as rownum');
		$this->db->from($this->_table);
		$this->db->join('member', 'currentvisitor.mem_id = member.mem_id', 'left');
		$this->db->where(array('cur_datetime >' => $curdatetime));
		$this->db->group_start();
		$this->db->where(array('member.mem_is_admin' => 0));
		$this->db->or_where(array('member.mem_is_admin' => null));
		$this->db->group_end();
		$qry = $this->db->get();
		$rows = $qry->row_array();
		$result['total_rows'] = $rows['rownum'];

		return $result;
	}


	public function get_current_count($curdatetime = '')
	{
		if (empty($curdatetime)) {
			$curdatetime = cdate('Y-m-d H:i:s', ctimestamp() - 600);
		}

		$this->db->select('count(*) as rownum');
		$this->db->join('member', 'currentvisitor.mem_id = member.mem_id', 'left');
		$this->db->where(array('cur_datetime >' => $curdatetime));
		$this->db->group_start();
		$this->db->where(array('member.mem_is_admin' => 0));
		$this->db->or_where(array('member.mem_is_admin' => null));
		$this->db->group_end();
		$qry = $this->db->get($this->_table);
		$rows = $qry->row_array();

		return $rows['rownum'];
	}
}
