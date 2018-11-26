<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Follow model class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

class Follow_model extends CB_Model
{

	/**
	 * 테이블명
	 */
	public $_table = 'follow';

	/**
	 * 사용되는 테이블의 프라이머리키
	 */
	public $primary_key = 'fol_id'; // 사용되는 테이블의 프라이머리키

	function __construct()
	{
		parent::__construct();
	}


	public function get_following_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
	{
		$select = 'follow.*, member.mem_id, member.mem_userid, member.mem_level, member.mem_nickname,
			member.mem_is_admin, member.mem_icon, member.mem_lastlogin_datetime';
		$join[] = array('table' => 'member', 'on' => 'follow.target_mem_id = member.mem_id', 'type' => 'left');

		$result = $this->_get_list_common($select, $join, $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);
		return $result;
	}


	public function get_followed_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
	{
		$select = 'follow.*, member.mem_id, member.mem_userid, member.mem_level, member.mem_nickname,
			member.mem_is_admin, member.mem_icon, member.mem_lastlogin_datetime';
		$join[] = array('table' => 'member', 'on' => 'follow.mem_id = member.mem_id', 'type' => 'left');

		$result = $this->_get_list_common($select, $join, $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);
		return $result;
	}
}
