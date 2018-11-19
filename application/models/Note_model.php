<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Note model class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

class Note_model extends CB_Model
{

	/**
	 * 테이블명
	 */
	public $_table = 'note';

	/**
	 * 사용되는 테이블의 프라이머리키
	 */
	public $primary_key = 'nte_id'; // 사용되는 테이블의 프라이머리키

	function __construct()
	{
		parent::__construct();
	}


	public function get_note($where = '')
	{
		if (empty($where)) {
			return;
		}
		$select = 'note.*, member.mem_id, member.mem_userid, member.mem_nickname, member.mem_is_admin, member.mem_icon';
		$this->db->select($select);
		$this->db->from($this->_table);
		if (isset($where['send_mem_id']) && $where['send_mem_id']) {
			$this->db->join('member', 'note.recv_mem_id = member.mem_id', 'left');
		} elseif (isset($where['recv_mem_id']) && $where['recv_mem_id']) {
			$this->db->join('member', 'note.send_mem_id = member.mem_id', 'left');
		}
		$this->db->where($where);
		$result = $this->db->get();

		return $result->row_array();
	}


	public function get_send_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
	{
		$select = 'note.*, member.mem_id, member.mem_userid, member.mem_nickname, member.mem_is_admin, member.mem_icon';
		$join[] = array('table' => 'member', 'on' => 'note.recv_mem_id = member.mem_id', 'type' => 'left');
		$result = $this->_get_list_common($select, $join, $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);

		return $result;
	}


	public function get_recv_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
	{
		$select = 'note.*, member.mem_id, member.mem_userid, member.mem_nickname, member.mem_is_admin, member.mem_icon';
		$join[] = array('table' => 'member', 'on' => 'note.send_mem_id = member.mem_id', 'type' => 'left');
		$result = $this->_get_list_common($select, $join, $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);

		return $result;
	}
}
