<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Cbconfig class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * config table 을 관리하는 class 입니다.
 */
class Cbconfig extends CI_Controller
{

	private $CI;
	private $cfg;
	private $device_view_type;
	private $device_type;

	function __construct()
	{
		$this->CI = & get_instance();
	}


	/**
	 * config table 에서 정보를 얻습니다
	 */
	public function get_config()
	{
		$this->CI->load->model('Config_model');
		$this->cfg = $this->CI->Config_model->get_all_meta();
	}


	/**
	 * config table 의 item 을 얻습니다
	 */
	public function item($column = '')
	{
		if (empty($column)) {
			return false;
		}
		if (empty($this->cfg)) {
			$this->get_config();
		}
		if (empty($this->cfg)) {
			return false;
		}
		$config = $this->cfg;

		return isset($config[$column]) ? $config[$column] : false;
	}


	/**
	 * 모바일버전보기/PC버전보기 설정 저장합니다
	 */
	public function set_device_view_type($device_view_type)
	{
		$this->device_view_type = $device_view_type;
	}


	/**
	 * 모바일버전보기/PC버전보기 설정 불러옵니다
	 */
	public function get_device_view_type()
	{
		return $this->device_view_type;
	}


	/**
	 * 현재 접속한 디바이스가 PC인지 mobile 인지를 저장합니다
	 */
	public function set_device_type($device_type)
	{
		$this->device_type = $device_type;
	}


	/**
	 * 현재 접속한 디바이스가 PC인지 mobile 인지를 불러옵니다
	 */
	public function get_device_type()
	{
		return $this->device_type;
	}
}
