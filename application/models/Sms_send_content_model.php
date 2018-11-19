<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Sms send content model class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

class Sms_send_content_model extends CB_Model
{

	/**
	 * 테이블명
	 */
	public $_table = 'sms_send_content';

	/**
	 * 사용되는 테이블의 프라이머리키
	 */
	public $primary_key = 'ssc_id'; // 사용되는 테이블의 프라이머리키

	function __construct()
	{
		parent::__construct();
	}


	public function get_admin_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
	{
		$select = 'sms_send_content.*, member.mem_id, member.mem_userid, member.mem_nickname, member.mem_is_admin, member.mem_icon';
		$join[] = array('table' => 'member', 'on' => 'sms_send_content.send_mem_id = member.mem_id', 'type' => 'left');
		$result = $this->_get_list_common($select, $join, $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);

		return $result;
	}
}
