<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Scrap model class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

class Scrap_model extends CB_Model
{

	/**
	 * 테이블명
	 */
	public $_table = 'scrap';

	/**
	 * 사용되는 테이블의 프라이머리키
	 */
	public $primary_key = 'scr_id'; // 사용되는 테이블의 프라이머리키

	function __construct()
	{
		parent::__construct();
	}


	public function get_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
	{
		$select = 'scrap.*, post.mem_id as post_mem_id, post.post_userid, post.post_nickname, post.post_id, post.brd_id,
			post.post_datetime, post.post_hit, post.post_secret, post.post_title, post.post_like, post.post_dislike, post.post_image';
		$join[] = array('table' => 'post', 'on' => 'scrap.post_id = post.post_id', 'type' => 'inner');

		$result = $this->_get_list_common($select, $join, $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);
		return $result;
	}
}
