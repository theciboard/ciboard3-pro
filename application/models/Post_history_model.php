<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Post History model class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

class Post_history_model extends CB_Model
{

	/**
	 * 테이블명
	 */
	public $_table = 'post_history';

	/**
	 * 사용되는 테이블의 프라이머리키
	 */
	public $primary_key = 'phi_id'; // 사용되는 테이블의 프라이머리키

	function __construct()
	{
		parent::__construct();
	}


	public function get_admin_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
	{
		$select = 'post_history.*, post.mem_id as post_mem_id, post.post_userid, post.post_nickname,
			post.brd_id, post.post_datetime, post.post_hit, post.post_secret, member.mem_id, member.mem_userid,
			member.mem_username, member.mem_nickname, member.mem_is_admin, member.mem_icon';
		$join[] = array('table' => 'post', 'on' => 'post_history.post_id = post.post_id', 'type' => 'inner');
		$join[] = array('table' => 'member', 'on' => 'post_history.mem_id = member.mem_id', 'type' => 'left');
		$result = $this->_get_list_common($select, $join, $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);

		return $result;
	}


	public function get_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
	{
		$select = 'post_history.*, post.mem_id as post_mem_id, post.post_userid, post.post_nickname,
			post.brd_id, post.post_datetime, post.post_hit, post.post_secret, member.mem_id, member.mem_userid,
			member.mem_username, member.mem_nickname, member.mem_is_admin, member.mem_icon';
		$join[] = array('table' => 'post', 'on' => 'post_history.post_id = post.post_id', 'type' => 'inner');
		$join[] = array('table' => 'member', 'on' => 'post_history.mem_id = member.mem_id', 'type' => 'left');
		$result = $this->_get_list_common($select, $join, $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);

		return $result;
	}
}
