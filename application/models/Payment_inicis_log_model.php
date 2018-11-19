<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Payment inicis log model class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

class Payment_inicis_log_model extends CB_Model
{

	/**
	 * 테이블명
	 */
	public $_table = 'payment_inicis_log';

	/**
	 * 사용되는 테이블의 프라이머리키
	 */
	public $primary_key = 'pil_id'; // 사용되는 테이블의 프라이머리키

	function __construct()
	{
		parent::__construct();
	}
}
