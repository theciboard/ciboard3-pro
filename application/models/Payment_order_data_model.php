<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Payment order data model class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

class Payment_order_data_model extends CB_Model
{

	/**
	 * 테이블명
	 */
	public $_table = 'payment_order_data';

	/**
	 * 사용되는 테이블의 프라이머리키
	 */
	public $primary_key = 'pod_id'; // 사용되는 테이블의 프라이머리키

	function __construct()
	{
		parent::__construct();
	}
}
