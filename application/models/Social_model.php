<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Social model class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

class Social_model extends CB_Model
{

	/**
	 * 테이블명
	 */
	public $_table = 'social';

	/**
	 * 사용되는 테이블의 프라이머리키
	 */
	public $parent_key1 = 'soc_type';
	public $parent_key2 = 'soc_account_id';

	public $meta_key = 'soc_key';

	public $meta_value = 'soc_value';

	public $cache_prefix= 'social/social-model-get-'; // 캐시 사용시 프리픽스

	public $cache_time = 86400; // 캐시 저장시간

	function __construct()
	{
		parent::__construct();

		check_cache_dir('social');
	}


	public function get_all_meta($soc_type = '', $soc_account_id = '')
	{
		if (empty($soc_type)) {
			return false;
		}
		if (empty($soc_account_id)) {
			return false;
		}

		$cachename = $this->cache_prefix . $soc_type . $soc_account_id;
		$data = array();
		if ( ! $data = $this->cache->get($cachename)) {
			$result = array();
			$res = $this->get('', $select = '', array('soc_type' => $soc_type, 'soc_account_id' => $soc_account_id));
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


	public function save($soc_type = '', $soc_account_id = '', $savedata = '')
	{
		if (empty($soc_type)) {
			return false;
		}
		if (empty($soc_account_id)) {
			return false;
		}

		if ($savedata && is_array($savedata)) {
			foreach ($savedata as $column => $value) {
				$this->meta_update($soc_type, $soc_account_id, $column, $value);
			}
		}
		$this->cache->delete($this->cache_prefix . $soc_type . $soc_account_id);
	}


	public function deletemeta($soc_type = '', $soc_account_id = '')
	{
		if (empty($soc_type)) {
			return false;
		}
		if (empty($soc_account_id)) {
			return false;
		}
		$this->delete_where(array('soc_type' => $soc_type, 'soc_account_id' => $soc_account_id));
		$this->cache->delete($this->cache_prefix . $soc_type . $soc_account_id);
	}


	public function meta_update($soc_type = '', $soc_account_id = '', $column = '', $value = false)
	{
		if (empty($soc_type)) {
			return false;
		}
		if (empty($soc_account_id)) {
			return false;
		}

		$column = trim($column);
		if (empty($column)) {
			return false;
		}

		$old_value = $this->item($soc_type, $soc_account_id, $column);
		if (empty($value)) {
			$value = '';
		}
		if ($value === $old_value) {
			return false;
		}

		if (false === $old_value) {
			return $this->add_meta($soc_type, $soc_account_id, $column, $value);
		}

		return $this->update_meta($soc_type, $soc_account_id, $column, $value);
	}


	public function item($soc_type = '', $soc_account_id = '', $column = '')
	{
		if (empty($soc_type)) {
			return false;
		}
		if (empty($soc_account_id)) {
			return false;
		}
		if (empty($column)) {
			return false;
		}

		$result = $this->get_all_meta($soc_type, $soc_account_id);

		return isset($result[ $column ]) ? $result[ $column ] : false;
	}


	public function add_meta($soc_type = '', $soc_account_id = '', $column = '', $value = '')
	{
		if (empty($soc_type)) {
			return false;
		}
		if (empty($soc_account_id)) {
			return false;
		}
		$column = trim($column);
		if (empty($column)) {
			return false;
		}

		$updatedata = array(
			'soc_type' => $soc_type,
			'soc_account_id' => $soc_account_id,
			'soc_key' => $column,
			'soc_value' => $value,
		);
		$this->db->insert($this->_table, $updatedata);

		return true;
	}


	public function update_meta($soc_type = '', $soc_account_id = '', $column = '', $value = '')
	{
		if (empty($soc_type)) {
			return false;
		}
		if (empty($soc_account_id)) {
			return false;
		}

		$column = trim($column);
		if (empty($column)) {
			return false;
		}

		$this->db->where('soc_type', $soc_type);
		$this->db->where('soc_account_id', $soc_account_id);
		$this->db->where($this->meta_key, $column);
		$data = array($this->meta_value => $value);
		$this->db->update($this->_table, $data);

		return true;
	}
}
