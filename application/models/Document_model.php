<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Document model class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

class Document_model extends CB_Model
{

	/**
	 * 테이블명
	 */
	public $_table = 'document';

	/**
	 * 사용되는 테이블의 프라이머리키
	 */
	public $primary_key = 'doc_id'; // 사용되는 테이블의 프라이머리키

	function __construct()
	{
		parent::__construct();
	}


	public function get_admin_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
	{
		$select = 'document.*, member.mem_id, member.mem_userid, member.mem_nickname, member.mem_is_admin, member.mem_icon';
		$join[] = array('table' => 'member', 'on' => 'document.doc_updated_mem_id = member.mem_id', 'type' => 'left');
		$result = $this->_get_list_common($select, $join, $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);
		return $result;
	}


	public function update_hit($primary_value = '')
	{
		if (empty($primary_value)) {
			return false;
		}

		$this->db->where($this->primary_key, $primary_value);
		$this->db->set('doc_hit', 'doc_hit+1', false);
		$result = $this->db->update($this->_table);

		return $result;
	}
}
