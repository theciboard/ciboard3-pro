<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Post Poll model class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

class Post_poll_model extends CB_Model
{

	/**
	 * 테이블명
	 */
	public $_table = 'post_poll';

	/**
	 * 사용되는 테이블의 프라이머리키
	 */
	public $primary_key = 'ppo_id'; // 사용되는 테이블의 프라이머리키

	function __construct()
	{
		parent::__construct();
	}


	public function get_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
	{
		$select = 'post_poll.*, post.mem_id as post_mem_id, post.post_userid, post.post_nickname, post.brd_id,
			post.post_datetime, post.post_hit, post.post_secret, board.brd_key, board.brd_name';
		$join[] = array('table' => 'post', 'on' => 'post_poll.post_id = post.post_id', 'type' => 'inner');
		$join[] = array('table' => 'board', 'on' => 'post.brd_id = board.brd_id', 'type' => 'inner');
		$result = $this->_get_list_common($select, $join, $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);
		return $result;
	}
}
