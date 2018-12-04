<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Unique id model class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

class Unique_id_model extends CB_Model
{

	/**
	 * 테이블명
	 */
	public $_table = 'unique_id';

	/**
	 * 사용되는 테이블의 프라이머리키
	 */
	public $primary_key = 'unq_id'; // 사용되는 테이블의 프라이머리키

	function __construct()
	{
		parent::__construct();
	}


	public function get_id($ip)
	{
		$this->db->query('LOCK TABLE ' . $this->db->dbprefix . $this->_table . ' WRITE');
		while (true) {
			$mt_arr = explode(' ', microtime());
			$key = cdate('YmdHis') . str_pad((int)($mt_arr[0]*10000), 4, "0", STR_PAD_LEFT);
			$insertdata = array(
				'unq_id' => $key,
				'unq_ip' => $ip,
			);
			$result = $this->db->insert($this->_table, $insertdata);

			if ($result) {
				break; // 쿼리가 정상이면 빠진다.
			}

			usleep(100); // 10000분의 1초를 쉰다
		}
		$this->db->query('UNLOCK TABLES');

		return $key;
	}
}
