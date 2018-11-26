<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Banner model class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

class Banner_model extends CB_Model
{

	/**
	 * 테이블명
	 */
	public $_table = 'banner';

	/**
	 * 사용되는 테이블의 프라이머리키
	 */
	public $primary_key = 'ban_id'; // 사용되는 테이블의 프라이머리키

	public $cache_time = 86400; // 캐시 저장시간

	function __construct()
	{
		parent::__construct();

		check_cache_dir('banner');
	}


	public function get_banner($position = '', $type = '', $limit = '')
	{
		if (empty($position)) {
			return;
		}
		if (strtolower($type) !== 'order') {
			$type = 'random';
		}

		$cachename = 'banner/banner-' . $position . '-' . $type . '-' . cdate('Y-m-d');

		if ( ! $result = $this->cache->get($cachename)) {
			$this->db->from($this->_table);
			$this->db->where('bng_name', $position);
			$this->db->where('ban_activated', 1);
			$this->db->group_start();
			$this->db->where(array('ban_start_date <=' => cdate('Y-m-d')));
			$this->db->or_where(array('ban_start_date' => null));
			$this->db->group_end();
			$this->db->group_start();
			$this->db->where('ban_end_date >=', cdate('Y-m-d'));
			$this->db->or_where('ban_end_date', '0000-00-00');
			$this->db->or_where(array('ban_end_date' => ''));
			$this->db->or_where(array('ban_end_date' => null));
			$this->db->group_end();
			$this->db->order_by('ban_order', 'DESC');
			$res = $this->db->get();
			$result = $res->result_array();

			$this->cache->save($cachename, $result, $this->cache_time);
		}

		if ($type === 'random') {
			shuffle($result);
		}
		if ($limit) {
			$result = array_slice($result, 0, $limit);
		}
		return $result;
	}
}
