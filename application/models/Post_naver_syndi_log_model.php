<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Post naver syndi log model class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

class Post_naver_syndi_log_model extends CB_Model
{

	/**
	 * 테이블명
	 */
	public $_table = 'post_naver_syndi_log';

	/**
	 * 사용되는 테이블의 프라이머리키
	 */
	public $primary_key = 'pns_id'; // 사용되는 테이블의 프라이머리키

	function __construct()
	{
		parent::__construct();
	}


	public function get_admin_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
	{
		$select = 'post_naver_syndi_log.*, post.mem_id as post_mem_id, post.post_userid, post.post_nickname,
			post.brd_id, post.post_datetime, post.post_hit, post.post_secret, post.post_title';
		$join[] = array('table' => 'post', 'on' => 'post_naver_syndi_log.post_id = post.post_id', 'type' => 'inner');
		$result = $this->_get_list_common($select, $join, $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);

		return $result;
	}

}
