<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Post Tag model class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

class Post_tag_model extends CB_Model
{

	/**
	 * 테이블명
	 */
	public $_table = 'post_tag';

	/**
	 * 사용되는 테이블의 프라이머리키
	 */
	public $primary_key = 'pta_id'; // 사용되는 테이블의 프라이머리키

	function __construct()
	{
		parent::__construct();
	}


	public function get_post_tag_count($type = 'd', $start_date = '', $end_date = '', $brd_id = 0)
	{
		if (empty($start_date) OR empty($end_date)) {
			return false;
		}
		$left = ($type === 'y') ? 4 : ($type === 'm' ? 7 : 10);

		$this->db->select('count(*) as cnt, pta_tag ', false);
		$this->db->from('post_tag');
		$this->db->join('post', 'post.post_id = post_tag.post_id', 'left');
		$this->db->where('left(post_datetime, 10) >=', $start_date);
		$this->db->where('left(post_datetime, 10) <=', $end_date);
		$this->db->where('post_del', 0);
		$brd_id = (int) $brd_id;
		if ($brd_id) {
			$this->db->where('post.brd_id', $brd_id);
		}
		$this->db->group_by('pta_tag');
		$this->db->order_by('cnt', 'desc');
		$qry = $this->db->get();
		$result = $qry->result_array();

		return $result;
	}


	public function get_admin_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
	{
		$select = 'post_tag.*, post.mem_id as post_mem_id, post.post_userid, post.post_nickname, post.brd_id, post.post_datetime, post.post_hit, post.post_secret, post.post_title';
		$join[] = array('table' => 'post', 'on' => 'post_tag.post_id = post.post_id', 'type' => 'inner');
		$result = $this->_get_list_common($select, $join, $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);

		return $result;
	}


	/**
	 * List 페이지 커스테마이징 함수
	 */
	public function get_tag_list ($limit = '', $offset = '', $tag = '')
	{
		if (empty($tag)) {
			return false;
		}

		$this->db->select('post.*, board.brd_key, board.brd_name, board.brd_mobile_name, board.brd_order, board.brd_search ');
		$this->db->from('post ');
		$this->db->join('board', 'post.brd_id = board.brd_id', 'inner');
		$this->db->join('post_tag', 'post.post_id = post_tag.post_id', 'inner');

		$where = array(
			'board.brd_search' => 1,
			'post.post_secret' => 0,
			'post_tag.pta_tag' => $tag,
			'post.post_del' => 0,
		);
		$this->db->where($where);
		$this->db->order_by('post.post_num, post.post_reply');
		if ($limit) {
			$this->db->limit($limit, $offset);
		}

		$qry = $this->db->get();

		$result['list'] = $qry->result_array();

		$this->db->select('count(*) cnt');
		$this->db->from('post');
		$this->db->join('board', 'post.brd_id = board.brd_id', 'inner');
		$this->db->join('post_tag', 'post.post_id = post_tag.post_id', 'inner');
		$this->db->where($where);
		$qry = $this->db->get();
		$cnt = $qry->row_array();
		$result['total_rows'] = element('cnt', $cnt);

		return $result;
	}


	public function get_popular_tags($start_date = '', $limit = '')
	{
		$this->db->select('count(*) as cnt, pta_tag ', false);
		$this->db->from('post_tag');
		$this->db->join('post', 'post.post_id = post_tag.post_id', 'left');
		$this->db->where('left(post_datetime, 10) >=', $start_date);
		$this->db->where('post_del', 0);
		$this->db->group_by('pta_tag');
		$this->db->order_by('cnt', 'desc');
		if ($limit) {
			$this->db->limit($limit);
		}
		$qry = $this->db->get();
		$result = $qry->result_array();

		return $result;
	}
}
