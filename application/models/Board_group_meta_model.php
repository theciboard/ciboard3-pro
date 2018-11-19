<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Board Group Meta model class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

class Board_group_meta_model extends CB_Model
{

	/**
	 * 테이블명
	 */
	public $_table = 'board_group_meta';

	/**
	 * 사용되는 테이블의 프라이머리키
	 */
	public $parent_key = 'bgr_id';

	public $meta_key = 'bgm_key';

	public $meta_value = 'bgm_value';

	public $cache_prefix= 'board_group_meta/board-group-meta-model-get-'; // 캐시 사용시 프리픽스

	public $cache_time = 86400; // 캐시 저장시간

	function __construct()
	{
		parent::__construct();

		check_cache_dir('board_group_meta');
	}


	public function get_all_meta($bgr_id = 0)
	{
		if (empty($bgr_id)) {
			return false;
		}

		$cachename = $this->cache_prefix . $bgr_id;
		$data = array();
		if ( ! $data = $this->cache->get($cachename)) {
			$result = array();
			$res = $this->get($primary_value = '', $select = '', array($this->parent_key => $bgr_id));
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


	public function save($bgr_id = 0, $savedata = '')
	{
		if (empty($bgr_id)) {
			return false;
		}
		if ($savedata && is_array($savedata)) {
			foreach ($savedata as $column => $value) {
				$this->meta_update($bgr_id, $column, $value);
			}
		}
		$this->cache->delete($this->cache_prefix . $bgr_id);
	}


	public function deletemeta($bgr_id = 0)
	{
		if (empty($bgr_id)) {
			return false;
		}
		$this->delete_where(array($this->parent_key => $bgr_id));
		$this->cache->delete($this->cache_prefix . $bgr_id);
	}


	public function meta_update($bgr_id = 0, $column = '', $value = false)
	{
		if (empty($bgr_id)) {
			return false;
		}
		$column = trim($column);
		if (empty($column)) {
			return false;
		}

		$old_value = $this->item($bgr_id, $column);
		if (empty($value)) {
			$value = '';
		}
		if ($value === $old_value) {
			return false;
		}
		if (false === $old_value) {
			return $this->add_meta($bgr_id, $column, $value);
		}
		return $this->update_meta($bgr_id, $column, $value);
	}


	public function item($bgr_id = 0, $column = '')
	{
		if (empty($bgr_id)) {
			return false;
		}
		if (empty($column)) {
			return false;
		}
		$result = $this->get_all_meta($bgr_id);
		return isset($result[ $column ]) ? $result[ $column ] : false;
	}


	public function add_meta($bgr_id = 0, $column = '', $value = '')
	{
		if (empty($bgr_id)) {
			return false;
		}
		$column = trim($column);
		if (empty($column)) {
			return false;
		}

		$updatedata = array(
			'bgr_id' => $bgr_id,
			'bgm_key' => $column,
			'bgm_value' => $value,
		);
		$this->db->replace($this->_table, $updatedata);

		return true;
	}


	public function update_meta($bgr_id = 0, $column = '', $value = '')
	{
		if (empty($bgr_id)) {
			return false;
		}
		$column = trim($column);
		if (empty($column)) {
			return false;
		}
		$this->db->where($this->parent_key, $bgr_id);
		$this->db->where($this->meta_key, $column);
		$data = array($this->meta_value => $value);
		$this->db->update($this->_table, $data);
		return true;
	}
}
