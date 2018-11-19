<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Comment model class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

class Comment_model extends CB_Model
{

	/**
	 * 테이블명
	 */
	public $_table = 'comment';

	/**
	 * 사용되는 테이블의 프라이머리키
	 */
	public $primary_key = 'cmt_id'; // 사용되는 테이블의 프라이머리키

	public $allow_order = array('cmt_num, cmt_reply', 'cmt_num desc, cmt_reply');


	function __construct()
	{
		parent::__construct();
	}


	public function get_admin_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
	{
		$select = 'comment.*, post.mem_id as post_mem_id, post.post_userid, post.post_username, post.post_nickname,
			post.brd_id, post.post_datetime, post.post_hit, post.post_secret, post.post_title';
		$join[] = array('table' => 'post', 'on' => 'comment.post_id = post.post_id', 'type' => 'inner');
		$result = $this->_get_list_common($select, $join, $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);
		return $result;
	}


	/**
	 * List 페이지 커스테마이징 함수
	 */
	public function get_comment_list($limit = '', $offset = '', $where = '', $like = '', $orderby = '', $sfield = '', $skeyword = '', $sop = 'OR')
	{
		if ( ! in_array(strtolower($orderby), $this->allow_order)) {
			$orderby = 'cmt_num, cmt_reply';
		}
		$sop = (strtoupper($sop) === 'AND') ? 'AND' : 'OR';
		if (empty($sfield)) {
			$sfield = 'cmt_content';
		}

		$search_where = array();
		$search_like = array();
		$search_or_like = array();
		if ($sfield && is_array($sfield)) {
			foreach ($sfield as $skey => $sval) {
				$ssf = $sval;
				if ($skeyword && $ssf && in_array($ssf, $this->allow_search_field)) {
					if (in_array($ssf, $this->search_field_equal)) {
						$search_where[$ssf] = $skeyword;
					} else {
						$swordarray = explode(' ', $skeyword);
						foreach ($swordarray as $str) {
							if (empty($ssf)) {
								continue;
							}
							if ($sop === 'AND') {
								$search_like[] = array($ssf => $str);
							} else {
								$search_or_like[] = array($ssf => $str);
							}
						}
					}
				}
			}
		} else {
			$ssf = $sfield;
			if ($skeyword && $ssf && in_array($ssf, $this->allow_search_field)) {
				if (in_array($ssf, $this->search_field_equal)) {
					$search_where[$ssf] = $skeyword;
				} else {
					$swordarray = explode(' ', $skeyword);
					foreach ($swordarray as $str) {
						if (empty($ssf)) {
							continue;
						}
						if ($sop === 'AND') {
							$search_like[] = array($ssf => $str);
						} else {
							$search_or_like[] = array($ssf => $str);
						}
					}
				}
			}
		}

		$this->db->select('comment.*, member.mem_id, member.mem_userid, member.mem_nickname, member.mem_icon, member.mem_photo, member.mem_point');
		$this->db->from($this->_table);
		$this->db->join('member', 'comment.mem_id = member.mem_id', 'left');

		if ($where) {
			$this->db->where($where);
		}
		if ($search_where) {
			$this->db->where($search_where);
		}
		if ($like) {
			$this->db->like($like);
		}
		if ($search_like) {
			foreach ($search_like as $item) {
				foreach ($item as $skey => $sval) {
					$this->db->like($skey, $sval);
				}
			}
		}
		if ($search_or_like) {
			$this->db->group_start();
			foreach ($search_or_like as $item) {
				foreach ($item as $skey => $sval) {
					$this->db->or_like($skey, $sval);
				}
			}
			$this->db->group_end();
		}

		$this->db->order_by($orderby);
		if ($limit) {
			$this->db->limit($limit, $offset);
		}
		$qry = $this->db->get();
		$result['list'] = $qry->result_array();

		$this->db->select('count(*) as rownum');
		$this->db->from($this->_table);
		$this->db->join('member', 'comment.mem_id = member.mem_id', 'left');
		if ($where) {
			$this->db->where($where);
		}
		if ($search_where) {
			$this->db->where($search_where);
		}
		if ($like) {
			$this->db->like($like);
		}
		if ($search_like) {
			foreach ($search_like as $item) {
				foreach ($item as $skey => $sval) {
					$this->db->like($skey, $sval);
				}
			}
		}
		if ($search_or_like) {
			$this->db->group_start();
			foreach ($search_or_like as $item) {
				foreach ($item as $skey => $sval) {
					$this->db->or_like($skey, $sval);
				}
			}
			$this->db->group_end();
		}
		$qry = $this->db->get();
		$rows = $qry->row_array();
		$result['total_rows'] = $rows['rownum'];

		return $result;
	}


	public function get_latest($where = '', $like = '', $limit = '', $findex = 'cmt_id', $forder = 'DESC')
	{

		$this->db->select('comment.*, post.mem_id as post_mem_id, post.post_userid, post.post_username, post.post_nickname,
			post.brd_id, post.post_datetime, post.post_hit, post.post_secret, post.post_title');
		$this->db->join('post', 'comment.post_id = post.post_id AND post.post_secret= 0 AND post.post_del= 0', 'inner');
		if ($where) {
			$this->db->where($where);
		}
		if ($like) {
			$this->db->like($like);
		}
		if ($limit) {
			$this->db->limit($limit);
		}
		$this->db->order_by($findex, $forder);
		$qry = $this->db->get($this->_table);
		$result = $qry->result_array();

		return $result;
	}


	/**
	 * List 페이지 커스테마이징 함수
	 */
	public function get_best_list($post_id = 0, $limit = 3, $like_num= 0)
	{
		$post_id = (int) $post_id;
		if (empty($post_id) OR $post_id < 1) {
			return false;
		}
		$like_num = (int) $like_num;

		$where = array(
			'post_id' => $post_id,
			'cmt_del' => 0,
			'cmt_secret' => 0,
			'cmt_like >=' => $like_num,
		);

		$orderby = 'cmt_like desc';

		$this->db->select('comment.*, member.mem_id, member.mem_userid, member.mem_nickname, member.mem_icon, member.mem_photo, member.mem_point');
		$this->db->from($this->_table);
		$this->db->join('member', 'comment.mem_id = member.mem_id', 'left');

		if ($where) {
			$this->db->where($where);
		}

		$this->db->order_by($orderby);
		if ($limit) {
			$this->db->limit($limit);
		}
		$qry = $this->db->get();
		$result = $qry->result_array();

		return $result;
	}


	public function next_comment_num()
	{
		$this->db->select_min('cmt_num');
		$result = $this->db->get($this->_table);
		$row = $result->row_array();
		$row['cmt_num'] = (isset($row['cmt_num']) && is_numeric($row['cmt_num'])) ? $row['cmt_num'] : 0;
		$cmt_num = $row['cmt_num'] - 1;

		return $cmt_num;
	}


	public function count_reply_comment($cmt_id = 0, $post_id = 0, $cmt_num = 0, $cmt_reply = '')
	{
		$cmt_id = (int) $cmt_id;
		if (empty($cmt_id) OR $cmt_id < 1) {
			return;
		}
		$post_id = (int) $post_id;
		if (empty($post_id) OR $post_id < 1) {
			return;
		}

		$where = array(
			'cmt_id <>' => $cmt_id,
			'post_id' => $post_id,
			'cmt_num' => $cmt_num,
		);
		$this->db->select('count(*) cnt');
		$this->db->where($where);
		$this->db->like('cmt_reply', $cmt_reply, 'after');
		$result = $this->db->get($this->_table);
		$row = $result->row_array();
		$row['cnt'] = (isset($row['cnt'])) ? $row['cnt'] : 0;

		return $row['cnt'];
	}
}
