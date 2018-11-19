<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Like model class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

class Like_model extends CB_Model
{

	/**
	 * 테이블명
	 */
	public $_table = 'like';

	/**
	 * 사용되는 테이블의 프라이머리키
	 */
	public $primary_key = 'lik_id'; // 사용되는 테이블의 프라이머리키

	function __construct()
	{
		parent::__construct();
	}


	public function get_admin_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
	{
		$select = 'like.*, member.mem_id, member.mem_userid, member.mem_nickname, member.mem_is_admin, member.mem_icon';
		$join[] = array('table' => 'member', 'on' => 'like.mem_id = member.mem_id', 'type' => 'left');
		$result = $this->_get_list_common($select, $join, $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);
		return $result;
	}


	public function get_post_like_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
	{
		$select = 'like.*, post.mem_id as post_mem_id, post.post_userid, post.post_username, post.post_nickname, post.post_id,
			post.brd_id, post.post_datetime, post.post_hit, post.post_secret, post.post_title, post.post_like, post.post_dislike, post.post_image, post.post_del';
		$join[] = array('table' => 'post', 'on' => 'like.target_id = post.post_id', 'type' => 'inner');

		$result = $this->_get_list_common($select, $join, $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);
		return $result;
	}


	public function get_comment_like_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
	{
		$select = 'like.*, comment.cmt_id, comment.mem_id as cmt_mem_id, comment.cmt_userid, comment.cmt_username,
			comment.cmt_nickname, comment.post_id, comment.cmt_datetime, comment.cmt_secret, comment.cmt_content,
			comment.cmt_content, comment.cmt_like, comment.cmt_dislike, comment.cmt_del';
		$join[] = array('table' => 'comment', 'on' => 'like.target_id = comment.cmt_id', 'type' => 'inner');

		$result = $this->_get_list_common($select, $join, $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);
		return $result;
	}


	public function get_like_count($type = 'd', $start_date = '', $end_date = '', $brd_id = 0, $orderby = 'asc')
	{
		if (empty($start_date) OR empty($end_date)) {
			return false;
		}
		$left = ($type === 'y') ? 4 : ($type === 'm' ? 7 : 10);
		if (strtolower($orderby) !== 'desc') $orderby = 'asc';

		$this->db->select('count(*) as cnt, left(lik_datetime, ' . $left . ') as day ', false);
		$this->db->where('lik_type', 1);
		$this->db->where('left(lik_datetime, 10) >=', $start_date);
		$this->db->where('left(lik_datetime, 10) <=', $end_date);
		$brd_id = (int) $brd_id;
		if ($brd_id) {
			$this->db->where('brd_id', $brd_id);
		}
		$this->db->group_by('day');
		$this->db->order_by('lik_datetime', $orderby);
		$qry = $this->db->get($this->_table);
		$result = $qry->result_array();

		return $result;
	}


	public function get_dislike_count($type = 'd', $start_date = '', $end_date = '', $brd_id = 0, $orderby = 'asc')
	{
		if (empty($start_date) OR empty($end_date)) {
			return false;
		}
		$left = ($type === 'y') ? 4 : ($type === 'm' ? 7 : 10);
		if (strtolower($orderby) !== 'desc') $orderby = 'asc';

		$this->db->select('count(*) as cnt, left(lik_datetime, ' . $left . ') as day ', false);
		$this->db->where('lik_type', 2);
		$this->db->where('left(lik_datetime, 10) >=', $start_date);
		$this->db->where('left(lik_datetime, 10) <=', $end_date);
		$brd_id = (int) $brd_id;
		if ($brd_id) {
			$this->db->where('brd_id', $brd_id);
		}
		$this->db->group_by('day');
		$this->db->order_by('lik_datetime', $orderby);
		$qry = $this->db->get($this->_table);
		$result = $qry->result_array();

		return $result;
	}
}
