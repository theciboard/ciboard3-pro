<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Board Group Admin model class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

class Board_group_admin_model extends CB_Model
{

	/**
	 * 테이블명
	 */
	public $_table = 'board_group_admin';

	/**
	 * 사용되는 테이블의 프라이머리키
	 */
	public $primary_key = 'bga_id'; // 사용되는 테이블의 프라이머리키

	function __construct()
	{
		parent::__construct();
	}


	public function get_board_group_admin_member($bgr_id = 0)
	{
		$bgr_id = (int) $bgr_id;
		if (empty($bgr_id)) {
			return false;
		}

		$this->db->select('member.mem_id, member.mem_nickname, member.mem_email, member.mem_phone ', false);
		$this->db->from('member');
		$this->db->join('board_group_admin', 'member.mem_id = board_group_admin.mem_id', 'left');
		$this->db->where('member.mem_denied', 0);
		$this->db->where('board_group_admin.bgr_id', $bgr_id);
		$this->db->order_by('member.mem_id', 'asc');
		$qry = $this->db->get();
		$result = $qry->result_array();
		return $result;
	}
}
