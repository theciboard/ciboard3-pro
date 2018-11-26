<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Board Category model class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

class Board_category_model extends CB_Model
{

	/**
	 * 테이블명
	 */
	public $_table = 'board_category';

	/**
	 * 사용되는 테이블의 프라이머리키
	 */
	public $primary_key = 'bca_id'; // 사용되는 테이블의 프라이머리키

	function __construct()
	{
		parent::__construct();
	}


	public function get_all_category($brd_id = 0)
	{
		$brd_id = (int) $brd_id;
		if (empty($brd_id)) {
			return;
		}

		$cachename = 'category-all-' . $brd_id;
		if ( ! $result = $this->cache->get($cachename)) {
			$where = array('brd_id' => $brd_id);
			$return = $this->get($primary_value = '', $select = '', $where, $limit = '', $offset = 0, $findex = 'bca_order', $forder = 'asc');
			if ($return) {
				foreach ($return as $key => $value) {
					$result[$value['bca_parent']][] = $value;
				}
				$this->cache->save($cachename, $result);
			}
		}
		return $result;
	}


	public function get_category_info($brd_id = 0, $bca_key = '')
	{
		$brd_id = (int) $brd_id;
		if (empty($brd_id)) {
			return;
		}
		if (empty($bca_key)) {
			return;
		}

		$cachename = 'category-' . $brd_id;
		if ( ! $result = $this->cache->get($cachename)) {
			$where = array('brd_id' => $brd_id);
			$return = $this->get($primary_value = '', $select = '', $where, $limit = '', $offset = 0, $findex = 'bca_order', $forder = 'asc');
			if ($return) {
				foreach ($return as $key => $value) {
					$result[$value['bca_key']] = $value;
				}
				$this->cache->save($cachename, $result);
			}
		}
		return isset($result[$bca_key]) ? $result[$bca_key] : '';
	}


	public function next_key($parent, $brd_id)
	{
		if ((string) $parent === '0') {
			$this->db->select('bca_key');
			$this->db->where(array('brd_id' => $brd_id));
			$res = $this->db->get($this->_table);
			$result = $res->result_array();
			$max = 0;
			foreach ($result as $key => $value) {
				$float = floatval($value['bca_key']);
				if ($float > $max) {
					$max = $float;
				}
			}
			return intval($max + 1);
		}
		if (strpos($parent, '.') === false) {
			$this->db->select('bca_key');
			$this->db->where(array('brd_id' => $brd_id));
			$this->db->like('bca_key', $parent . '.', 'after');
			$res = $this->db->get($this->_table);
			$result = $res->result_array();
			$max = 0;
			foreach ($result as $key => $value) {
				$float = $value['bca_key'];
				if ($float > $max) {
					$max = $float;
				}
			}

			if ($max) {
				$key_explode = explode('.', $max);
				$digit = substr($key_explode[1], 0, 3) + 1;
				$ret = sprintf("%03d", $digit);
				return $key_explode[0] . '.' . $ret;
			} else {
				return $parent . '.001';
			}
		} else {
			$this->db->select('bca_key');
			$this->db->where(array('brd_id' => $brd_id));
			$this->db->like('bca_key', $parent, 'after');
			$res = $this->db->get($this->_table);
			$result = $res->result_array();
			$max = 0;
			foreach ($result as $key => $value) {
				$float = $value['bca_key'];
				if ($float > $max) {
					$max = $float;
				}
			}
			if ((string) $max === (string) $parent) {
				return $parent . '001';
			} else {
				$key_explode = explode('.', $max);
				$parent_explode = explode('.', $parent);
				$parentlen = strlen($parent_explode[1]);
				$digit = substr($key_explode[1], 0, $parentlen + 3) + 1;
				$res = sprintf("%0" . ($parentlen + 3) . "d", $digit);
				return $key_explode[0] . '.' . $res;
			}
		}
	}
}
