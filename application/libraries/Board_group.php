<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Board group class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * board group table 을 주로 관리하는 class 입니다.
 */
class Board_group extends CI_Controller
{

	private $CI;
	private $group_id;
	private $group_key;
	private $group;
	private $admin;
	private $call_admin;

	function __construct()
	{
		$this->CI = & get_instance();
	}


	/**
	 * board group 테이블에서 가져옵니다
	 */
	public function get_board_group($bgr_id = 0, $bgr_key = '')
	{
		if (empty($bgr_id) && empty($bgr_key)) {
			return false;
		}

		$this->CI->load->model('Board_group_model');
		if ($bgr_id) {
			$group = $this->CI->Board_group_model->get_one($bgr_id);
		} elseif ($bgr_key) {
			$where = array(
				'bgr_key' => $bgr_key,
			);
			$group = $this->CI->Board_group_model->get_one('', '', $where);
		} else {
			return false;
		}
		if (element('bgr_id', $group)) {
			$group_meta = $this->get_all_meta(element('bgr_id', $group));
			if (is_array($group_meta)) {
				$group = array_merge($group, $group_meta);
			}
		}
		if (element('bgr_id', $group)) {
			$this->group_id[element('bgr_id', $group)] = $group;
		}
		if (element('bgr_key', $group)) {
			$this->group_key[element('bgr_key', $group)] = $group;
		}
	}


	/**
	 * board group 의 모든 meta 정보를 가져옵니다 테이블에서 가져옵니다
	 */
	public function get_all_meta($bgr_id = 0)
	{
		$bgr_id = (int) $bgr_id;
		if (empty($bgr_id) OR $bgr_id < 1) {
			return false;
		}

		$this->CI->load->model('Board_group_meta_model');
		$result = $this->CI->Board_group_meta_model->get_all_meta($bgr_id);
		return $result;
	}


	/**
	 * group 의 item 을 bgr_id 기반으로 얻습니다
	 */
	public function item_id($column = '', $bgr_id = 0)
	{
		if (empty($column)) {
			return false;
		}
		$bgr_id = (int) $bgr_id;
		if (empty($bgr_id) OR $bgr_id < 1) {
			return false;
		}
		if (empty($this->group_id[$bgr_id])) {
			$this->get_board_group($bgr_id, '');
		}
		if (empty($this->group_id[$bgr_id])) {
			return false;
		}
		$group = $this->group_id[$bgr_id];
		return isset($group[$column]) ? $group[$column] : false;

	}


	/**
	 * group 의 item 을 bgr_key 기반으로 얻습니다
	 */
	public function item_key($column = '', $bgr_key = '')
	{
		if (empty($column)) {
			return false;
		}
		if (empty($bgr_key)) {
			return false;
		}
		if (empty($this->group_key[$bgr_key])) {
			$this->get_board_group('', $bgr_key);
		}
		if (empty($this->group_key[$bgr_key])) {
			return false;
		}
		$group = $this->group_key[$bgr_key];
		return isset($group[$column]) ? $group[$column] : false;

	}


	/**
	 * group 의 모든 item 을 bgr_id 기반으로 얻습니다
	 */
	public function item_all($bgr_id = 0)
	{
		$bgr_id = (int) $bgr_id;
		if (empty($bgr_id) OR $bgr_id < 1) {
			return false;
		}
		if (empty($this->group_id[$bgr_id])) {
			$this->get_board_group($bgr_id, '');
		}
		if (empty($this->group_id[$bgr_id])) {
			return false;
		}

		return $this->group_id[$bgr_id];
	}


	/**
	 * group 의 모든 정보를 얻습니다
	 */
	public function get_group($bgr_id = 0)
	{
		$bgr_id = (int) $bgr_id;
		if (empty($bgr_id) OR $bgr_id < 1) {
			return false;
		}

		$this->CI->load->model('Board_group_model');
		$group = $this->CI->Board_group_model->get_one($bgr_id);
		$group_meta = $this->get_all_meta($bgr_id);
		if (is_array($group_meta)) {
			$group = array_merge($group, $group_meta);
		}
		$this->group[$bgr_id] = $group;
	}


	/**
	 * group 의 admin 인지를 판단합니다
	 */
	public function is_admin($bgr_id = 0)
	{
		$bgr_id = (int) $bgr_id;
		if (empty($bgr_id) OR $bgr_id < 1) {
			return false;
		}
		if ( ! $this->CI->member->item('mem_id')) {
			return false;
		}
		if ($this->call_admin) {
			return $this->admin;
		}
		$this->call_admin = true;
		$countwhere = array(
			'bgr_id' => $bgr_id,
			'mem_id' => $this->CI->member->item('mem_id'),
		);
		$this->CI->load->model('Board_group_admin_model');
		$count = $this->CI->Board_group_admin_model->count_by($countwhere);
		if ($count) {
			$this->admin = true;
		} else {
			$this->admin = false;
		}

		return $this->admin;
	}
}
