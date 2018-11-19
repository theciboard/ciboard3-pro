<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Post Poll Item model class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

class Post_poll_item_model extends CB_Model
{

	/**
	 * 테이블명
	 */
	public $_table = 'post_poll_item';

	/**
	 * 사용되는 테이블의 프라이머리키
	 */
	public $primary_key = 'ppi_id'; // 사용되는 테이블의 프라이머리키

	function __construct()
	{
		parent::__construct();
	}
}
