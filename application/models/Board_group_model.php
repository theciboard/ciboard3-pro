<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Board Group model class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

class Board_group_model extends CB_Model
{

	/**
	 * 테이블명
	 */
	public $_table = 'board_group';

	/**
	 * 사용되는 테이블의 프라이머리키
	 */
	public $primary_key = 'bgr_id'; // 사용되는 테이블의 프라이머리키

	public $cache_prefix = 'board_group/board-group-model-get-'; // 캐시 사용시 프리픽스

	public $cache_time = 86400; // 캐시 저장시간

	function __construct()
	{
		parent::__construct();

		check_cache_dir('board_group');
	}


	public function get_group_list($where = '')
	{
		$result = $this->get('', '', $where, '', 0, 'bgr_order', 'ASC');
		return $result;
	}


	public function get_one($bgr_id = 0, $select = '', $where = '')
	{
		$use_cache = false;
		if ($bgr_id && empty($select) && empty($where)) {
			$use_cache = true;
		}

		if ($use_cache) {
			$cachename = $this->cache_prefix . $bgr_id;
			if ( ! $result = $this->cache->get($cachename)) {
				$result = parent::get_one($bgr_id);
				$this->cache->save($cachename, $result, $this->cache_time);
			}
		} else {
			$result = parent::get_one($bgr_id, $select, $where);
		}
		return $result;
	}


	public function delete($bgr_id = 0, $where= '')
	{
		if (empty($bgr_id)) {
			return false;
		}
		$result = parent::delete($bgr_id);
		$this->cache->delete($this->cache_prefix . $bgr_id);

		return $result;
	}


	public function update($bgr_id = 0, $updatedata = '', $where = '')
	{
		if (empty($bgr_id)) {
			return false;
		}
		$result = parent::update($bgr_id, $updatedata);
		if ($result) {
			$this->cache->delete($this->cache_prefix . $bgr_id);
		}
		return $result;
	}
}
