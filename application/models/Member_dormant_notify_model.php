<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Member Dormant Notify model class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

class Member_dormant_notify_model extends CB_Model
{

	/**
	 * 테이블명
	 */
	public $_table = 'member_dormant_notify';

	/**
	 * 사용되는 테이블의 프라이머리키
	 */
	public $primary_key = 'mdn_id'; // 사용되는 테이블의 프라이머리키

	function __construct()
	{
		parent::__construct();
	}


	/**
	 * 휴면회원으로 전환되기 전에 이메일을 보내야할 대상에 있는 회원 명단
	 */
	public function get_unsent_email_member($lastlogindatetime)
	{
		$this->db->select('member.*');
		$this->db->from('member');
		$this->db->join('member_dormant_notify', 'member.mem_id = member_dormant_notify.mem_id', 'left');
		$this->db->where(array('member_dormant_notify.mem_id' => null));
		$this->db->where(array('member.mem_lastlogin_datetime <=' => $lastlogindatetime));
		$this->db->where(array('member.mem_register_datetime <=' => $lastlogindatetime));
		$this->db->where(array('member.mem_email <>' => ''));
		$this->db->limit(100);

		$qry = $this->db->get();
		$result = $qry->result_array();

		return $result;
	}


	/**
	 * 휴면회원으로 전환되기 전에 이메일을 보내야할 대상에 있는 회원 수
	 */
	public function count_unsent_email_member($lastlogindatetime)
	{
		$this->db->select('count(*) as rownum');
		$this->db->from('member');
		$this->db->join('member_dormant_notify', 'member.mem_id = member_dormant_notify.mem_id', 'left');
		$this->db->where(
			array(
				'member_dormant_notify.mem_id' => null,
				'member.mem_lastlogin_datetime <=' => $lastlogindatetime,
				'member.mem_register_datetime <=' => $lastlogindatetime,
				'member.mem_email <>' => ''
			)
		);

		$qry = $this->db->get();
		$rows = $qry->row_array();

		return $rows['rownum'];
	}

}
