<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Sms send history model class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

class Sms_send_history_model extends CB_Model
{

	/**
	 * 테이블명
	 */
	public $_table = 'sms_send_history';

	/**
	 * 사용되는 테이블의 프라이머리키
	 */
	public $primary_key = 'ssh_id'; // 사용되는 테이블의 프라이머리키

	function __construct()
	{
		parent::__construct();
	}


	public function get_admin_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
	{
		$select = 'sms_send_history.*, sms_send_content.*, sms_member.* ';
		$join[] = array('table' => 'sms_member', 'on' => 'sms_send_history.recv_mem_id = sms_member.mem_id', 'type' => 'left');
		$join[] = array('table' => 'sms_send_content', 'on' => 'sms_send_history.ssc_id = sms_send_content.ssc_id', 'type' => 'left');
		$result = $this->_get_list_common($select, $join, $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);

		return $result;
	}
}
