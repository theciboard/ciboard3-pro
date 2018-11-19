<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Board Admin model class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

class Board_admin_model extends CB_Model
{

	/**
	 * 테이블명
	 */
	public $_table = 'board_admin';

	/**
	 * 사용되는 테이블의 프라이머리키
	 */
	public $primary_key = 'bam_id'; // 사용되는 테이블의 프라이머리키

	function __construct()
	{
		parent::__construct();
	}


	public function get_board_admin_member($brd_id = 0)
	{
		$brd_id = (int) $brd_id;
		if (empty($brd_id)) {
			return false;
		}

		$this->db->select('member.mem_id, member.mem_nickname, member.mem_email, member.mem_phone ', false);
		$this->db->from('member');
		$this->db->join('board_admin', 'member.mem_id = board_admin.mem_id', 'left');
		$this->db->where('member.mem_denied', 0);
		$this->db->where('board_admin.brd_id', $brd_id);
		$this->db->order_by('member.mem_id', 'asc');
		$qry = $this->db->get();
		$result = $qry->result_array();
		return $result;
	}
}
