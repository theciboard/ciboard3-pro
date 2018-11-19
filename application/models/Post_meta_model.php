<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Post Meta model class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

class Post_meta_model extends CB_Model
{

	/**
	 * 테이블명
	 */
	public $_table = 'post_meta';

	/**
	 * 사용되는 테이블의 프라이머리키
	 */
	public $parent_key = 'post_id';

	public $meta_key = 'pmt_key';

	public $meta_value = 'pmt_value';

	public $cache_prefix= 'post_meta/post-meta-model-get-'; // 캐시 사용시 프리픽스

	public $cache_time = 86400; // 캐시 저장시간

	function __construct()
	{
		parent::__construct();

		check_cache_dir('post_meta');
	}


	public function get_all_meta($post_id = 0)
	{
		$post_id = (int) $post_id;
		if (empty($post_id) OR $post_id < 1) {
			return false;
		}

		$cachename = $this->cache_prefix . $post_id;
		$data = array();
		if ( ! $data = $this->cache->get($cachename)) {
			$result = array();
			$res = $this->get($primary_value = '', $select = '', array($this->parent_key => $post_id));
			if ($res && is_array($res)) {
				foreach ($res as $val) {
					$result[$val[$this->meta_key]] = $val[$this->meta_value];
				}
			}
			$data['result'] = $result;
			$data['cached'] = '1';
			$this->cache->save($cachename, $data, $this->cache_time);
		}
		return isset($data['result']) ? $data['result'] : false;
	}


	public function save($post_id = 0, $brd_id = 0, $savedata = '')
	{
		$post_id = (int) $post_id;
		if (empty($post_id) OR $post_id < 1) {
			return false;
		}

		if ($savedata && is_array($savedata)) {
			foreach ($savedata as $column => $value) {
				$this->meta_update($post_id, $brd_id, $column, $value);
			}
		}
		$this->cache->delete($this->cache_prefix . $post_id);
	}


	public function deletemeta($post_id = 0)
	{
		$post_id = (int) $post_id;
		if (empty($post_id) OR $post_id < 1) {
			return false;
		}
		$this->delete_where(array($this->parent_key => $post_id));
		$this->cache->delete($this->cache_prefix . $post_id);
	}


	public function meta_update($post_id = 0, $brd_id = 0, $column = '', $value = false)
	{
		$post_id = (int) $post_id;
		if (empty($post_id) OR $post_id < 1) {
			return false;
		}
		$brd_id = (int) $brd_id;
		if (empty($brd_id) OR $brd_id < 1) {
			return false;
		}
		$column = trim($column);
		if (empty($column)) {
			return false;
		}

		$old_value = $this->item($post_id, $column);
		if (empty($value)) {
			$value = '';
		}
		if ($value === $old_value) {
			return false;
		}

		if (false === $old_value) {
			return $this->add_meta($post_id, $brd_id, $column, $value);
		}

		return $this->update_meta($post_id, $column, $value);
	}


	public function item($post_id = 0, $column = '')
	{
		$post_id = (int) $post_id;
		if (empty($post_id) OR $post_id < 1) {
			return false;
		}
		if (empty($column)) {
			return false;
		}

		$result = $this->get_all_meta($post_id);

		return isset($result[ $column ]) ? $result[ $column ] : false;
	}


	public function add_meta($post_id = 0, $brd_id = 0, $column = '', $value = '')
	{
		$post_id = (int) $post_id;
		if (empty($post_id) OR $post_id < 1) {
			return false;
		}
		$brd_id = (int) $brd_id;
		if (empty($brd_id) OR $brd_id < 1) {
			return false;
		}

		$column = trim($column);
		if (empty($column)) {
			return false;
		}

		$updatedata = array(
			'post_id' => $post_id,
			'brd_id' => $brd_id,
			'pmt_key' => $column,
			'pmt_value' => $value,
		);
		$this->db->replace($this->_table, $updatedata);

		return true;
	}


	public function update_meta($post_id = 0, $column = '', $value = '')
	{
		$post_id = (int) $post_id;
		if (empty($post_id) OR $post_id < 1) {
			return false;
		}
		$column = trim($column);
		if (empty($column)) {
			return false;
		}

		$this->db->where($this->parent_key, $post_id);
		$this->db->where($this->meta_key, $column);
		$data = array($this->meta_value => $value);
		$this->db->update($this->_table, $data);

		return true;
	}


	public function delete_meta_column($post_id = 0, $column = '')
	{
		$post_id = (int) $post_id;
		if (empty($post_id) OR $post_id < 1) {
			return false;
		}
		$column = trim($column);
		if (empty($column)) {
			return false;
		}

		$this->delete_where(array($this->parent_key, $post_id, $this->meta_key => $column));

		return true;
	}
}
