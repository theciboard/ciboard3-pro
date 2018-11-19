<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Member Userid model class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

class Member_userid_model extends CB_Model
{

	/**
	 * 테이블명
	 */
	public $_table = 'member_userid';

	/**
	 * 사용되는 테이블의 프라이머리키
	 */
	public $primary_key = 'mem_id'; // 사용되는 테이블의 프라이머리키

	function __construct()
	{
		parent::__construct();
	}
}
