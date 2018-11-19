<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Member Nickname model class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

class Member_nickname_model extends CB_Model
{

	/**
	 * 테이블명
	 */
	public $_table = 'member_nickname';

	/**
	 * 사용되는 테이블의 프라이머리키
	 */
	public $primary_key = 'mni_id'; // 사용되는 테이블의 프라이머리키

	function __construct()
	{
		parent::__construct();
	}


	public function get_admin_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
	{
		$select = 'member_nickname.*, member.mem_id, member.mem_userid, member.mem_username, member.mem_nickname, member.mem_is_admin, member.mem_icon';
		$join[] = array('table' => 'member', 'on' => 'member_nickname.mem_id = member.mem_id', 'type' => 'left');
		$result = $this->_get_list_common($select, $join, $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);

		return $result;
	}
}
