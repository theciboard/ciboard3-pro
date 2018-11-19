<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Cmall item history model class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

class Cmall_item_history_model extends CB_Model
{

	/**
	 * 테이블명
	 */
	public $_table = 'cmall_item_history';

	/**
	 * 사용되는 테이블의 프라이머리키
	 */
	public $primary_key = 'chi_id'; // 사용되는 테이블의 프라이머리키

	function __construct()
	{
		parent::__construct();
	}


	public function get_admin_list($limit = '', $offset = '', $where = '', $like = '', $findex = '', $forder = '', $sfield = '', $skeyword = '', $sop = 'OR')
	{
		$select = 'cmall_item_history.*, cmall_item.cit_name, cmall_item.cit_key, cmall_item.cit_datetime,
			cmall_item.cit_hit, member.mem_id, member.mem_userid, member.mem_nickname,
			member.mem_is_admin, member.mem_icon';
		$join[] = array('table' => 'cmall_item', 'on' => 'cmall_item_history.cit_id = cmall_item.cit_id', 'type' => 'inner');
		$join[] = array('table' => 'member', 'on' => 'cmall_item_history.mem_id = member.mem_id', 'type' => 'left');
		$result = $this->_get_list_common($select, $join, $limit, $offset, $where, $like, $findex, $forder, $sfield, $skeyword, $sop);
		return $result;
	}
}
