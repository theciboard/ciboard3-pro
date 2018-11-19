<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Sms member model class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

class Sms_member_model extends CB_Model
{

	/**
	 * 테이블명
	 */
	public $_table = 'sms_member';

	/**
	 * 사용되는 테이블의 프라이머리키
	 */
	public $primary_key = 'sme_id'; // 사용되는 테이블의 프라이머리키

	function __construct()
	{
		parent::__construct();
	}


	public function get_admin_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
	{
		$select = 'sms_member.*, member.mem_id, member.mem_userid, member.mem_nickname,
			member.mem_is_admin, member.mem_icon, sms_member_group.smg_name';
		$join[] = array('table' => 'member', 'on' => 'sms_member.mem_id = member.mem_id', 'type' => 'left');
		$join[] = array('table' => 'sms_member_group', 'on' => 'sms_member.smg_id = sms_member_group.smg_id', 'type' => 'left');
		$result = $this->_get_list_common($select, $join, $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);

		return $result;
	}


	public function get_group_select($smg_id = 0)
	{
		$smg_id = (int) $smg_id;

		$option = '<option value="0">그룹선택</option>';

		$this->db->order_by('smg_order', 'ASC');
		$this->db->select('smg_id, smg_name');
		$qry = $this->db->get('sms_member_group');
		foreach ($qry->result_array() as $row) {
			$option .= '<option value="' . $row['smg_id'] . '"';
			if ((int) $row['smg_id'] === $smg_id) {
				$option .= ' selected="selected" ';
			}
			$option .= '>' . $row['smg_name'] . '</option>';
		}

		return $option;
	}
}
