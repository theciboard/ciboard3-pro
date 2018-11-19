<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Member class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * member table 을 관리하는 class 입니다.
 */
class Member extends CI_Controller
{

	private $CI;

	private $mb;

	private $member_group;


	function __construct()
	{
		$this->CI = & get_instance();
		$this->CI->load->model( array('Member_model'));
		$this->CI->load->helper( array('array'));
	}


	/**
	 * 접속한 유저가 회원인지 아닌지를 판단합니다
	 */
	public function is_member()
	{
		if ($this->CI->session->userdata('mem_id')) {
			return $this->CI->session->userdata('mem_id');
		} else {
			return false;
		}
	}


	/**
	 * 접속한 유저가 관리자인지 아닌지를 판단합니다
	 */
	public function is_admin($check = array())
	{
		if ($this->item('mem_is_admin')) {
			return 'super';
		}
		if (element('group_id', $check)) {
			$this->CI->load->library('board_group');
			$is_group_admin = $this->CI->board_group->is_admin(element('group_id', $check));
			if ($is_group_admin) {
				return 'group';
			}
		}
		if (element('board_id', $check)) {
			$this->CI->load->library('board');
			$is_board_admin = $this->CI->board->is_admin(element('board_id', $check));
			if ($is_board_admin) {
				return 'board';
			}
		}
		return false;
	}


	/**
	 * member, member_extra_vars, member_meta 테이블에서 정보를 가져옵니다
	 */
	public function get_member()
	{
		if ($this->is_member()) {
			if (empty($this->mb)) {
				$member = $this->CI->Member_model->get_by_memid($this->is_member());
				$extras = $this->get_all_extras(element('mem_id', $member));
				if (is_array($extras)) {
					$member = array_merge($member, $extras);
				}
				$metas = $this->get_all_meta(element('mem_id', $member));
				if (is_array($metas)) {
					$member = array_merge($member, $metas);
				}
				$member['social'] = $this->get_all_social_meta(element('mem_id', $member));
				$this->mb = $member;
			}
			return $this->mb;
		} else {
			return false;
		}
	}


	/**
	 * get_member 에서 가져온 데이터의 item 을 보여줍니다
	 */
	public function item($column = '')
	{
		if (empty($column)) {
			return false;
		}
		if (empty($this->mb)) {
			$this->get_member();
		}
		if (empty($this->mb)) {
			return false;
		}
		$member = $this->mb;

		return isset($member[$column]) ? $member[$column] : false;
	}


	/**
	 * get_member 에서 가져온 데이터의 item 을 보여줍니다
	 */
	public function socialitem($column = '')
	{
		if (empty($column)) {
			return false;
		}
		if (empty($this->mb)) {
			$this->get_member();
		}
		if (empty($this->mb)) {
			return false;
		}
		$member = $this->mb;

		return isset($member['social']) && isset($member['social'][$column]) ? $member['social'][$column] : false;
	}


	/**
	 * 회원이 속한 그룹 정보를 가져옵니다
	 */
	public function group()
	{
		if (empty($this->member_group)) {
			$where = array(
				'mem_id' => $this->item('mem_id'),
			);
			$this->CI->load->model('Member_group_member_model');
			$this->member_group = $this->CI->Member_group_member_model->get('', '', $where, '', 0, 'mgm_id', 'ASC');
		}
		return $this->member_group;
	}


	/**
	 * member_extra_vars 테이블에서 가져옵니다
	 */
	public function get_all_extras($mem_id = 0)
	{
		if (empty($mem_id)) {
			return false;
		}

		$this->CI->load->model('Member_extra_vars_model');
		$result = $this->CI->Member_extra_vars_model->get_all_meta($mem_id);
		return $result;
	}


	/**
	 * member_meta 테이블에서 가져옵니다
	 */
	public function get_all_meta($mem_id = 0)
	{
		$mem_id = (int) $mem_id;
		if (empty($mem_id) OR $mem_id < 1) {
			return false;
		}

		$this->CI->load->model('Member_meta_model');
		$result = $this->CI->Member_meta_model->get_all_meta($mem_id);
		return $result;
	}


	/**
	 * social_meta 테이블에서 가져옵니다
	 */
	public function get_all_social_meta($mem_id = 0)
	{
		$mem_id = (int) $mem_id;
		if (empty($mem_id) OR $mem_id < 1) {
			return false;
		}
		if ($this->CI->db->table_exists('social_meta') === false) {
			return;
		}

		$this->CI->load->model('Social_meta_model');
		$result = $this->CI->Social_meta_model->get_all_meta($mem_id);
		return $result;
	}

	/**
	 * 로그인 기록을 남깁니다
	 */
	public function update_login_log($mem_id= 0, $userid = '', $success= 0, $reason = '')
	{
		$success = $success ? 1 : 0;
		$mem_id = (int) $mem_id ? (int) $mem_id : 0;
		$reason = isset($reason) ? $reason : '';
		$referer = $this->CI->input->get_post('url', null, '');
		$loginlog = array(
			'mll_success' => $success,
			'mem_id' => $mem_id,
			'mll_userid' => $userid,
			'mll_datetime' => cdate('Y-m-d H:i:s'),
			'mll_ip' => $this->CI->input->ip_address(),
			'mll_reason' => $reason,
			'mll_useragent' => $this->CI->agent->agent_string(),
			'mll_url' => current_full_url(),
			'mll_referer' => $referer,
		);
		$this->CI->load->model('Member_login_log_model');
		$this->CI->Member_login_log_model->insert($loginlog);

		return true;
	}

	/**
	 * 회원삭제 남깁니다
	 */
	public function delete_member($mem_id = 0)
	{
		$mem_id = (int) $mem_id;
		if (empty($mem_id) OR $mem_id < 1) {
			return false;
		}

		$this->CI->load->model(
			array(
				'Autologin_model', 'Board_admin_model', 'Board_group_admin_model', 'Follow_model',
				'Member_model', 'Member_auth_email_model', 'Member_dormant_model',
				'Member_dormant_notify_model', 'Member_extra_vars_model', 'Member_group_member_model',
				'Member_level_history_model', 'Member_login_log_model', 'Member_meta_model',
				'Member_register_model', 'Notification_model', 'Point_model',
				'Scrap_model', 'Social_meta_model',
				'Tempsave_model', 'Member_userid_model',
			)
		);

		$deletewhere = array(
			'mem_id' => $mem_id,
		);
		$this->CI->Autologin_model->delete_where($deletewhere);
		$this->CI->Board_admin_model->delete_where($deletewhere);
		$this->CI->Board_group_admin_model->delete_where($deletewhere);
		$this->CI->Follow_model->delete_where($deletewhere);
		$this->CI->Member_model->delete_where($deletewhere);
		$this->CI->Member_auth_email_model->delete_where($deletewhere);
		$this->CI->Member_dormant_model->delete_where($deletewhere);
		$this->CI->Member_dormant_notify_model->delete_where($deletewhere);
		$this->CI->Member_extra_vars_model->delete_where($deletewhere);
		$this->CI->Member_group_member_model->delete_where($deletewhere);
		$this->CI->Member_level_history_model->delete_where($deletewhere);
		$this->CI->Member_login_log_model->delete_where($deletewhere);
		$this->CI->Member_meta_model->delete_where($deletewhere);
		$this->CI->Member_register_model->delete_where($deletewhere);
		$this->CI->Notification_model->delete_where($deletewhere);
		$this->CI->Point_model->delete_where($deletewhere);
		$this->CI->Scrap_model->delete_where($deletewhere);
		$this->CI->Social_meta_model->delete_where($deletewhere);
		$this->CI->Tempsave_model->delete_where($deletewhere);

		$this->CI->Member_userid_model->update($mem_id, array('mem_status' => 1));

		return true;
	}

	/**
	 * 휴면처리시 별도의 저장소로 이동하는 프로세스입니다
	 */
	public function archive_to_dormant($mem_id = 0)
	{
		$mem_id = (int) $mem_id;
		if (empty($mem_id) OR $mem_id < 1) {
			return false;
		}

		$this->CI->load->model(array('Member_model', 'Member_dormant_model', 'Member_meta_model', 'Member_userid_model'));

		$data = $this->CI->Member_model->get_one($mem_id);
		$cleanpoint = (-1) * element('mem_point', $data);
		$point_content = '휴면회원 전환으로 인한 포인트 초기화 (' . cdate('Y-m-d H:i:s') . ')';

		if ($this->CI->cbconfig->item('member_dormant_reset_point')) {
			$this->CI->load->library('point');
			$point = $this->CI->point->insert_point(
				$mem_id,
				$cleanpoint,
				$point_content,
				'@member_dormant',
				$mem_id,
				$mem_id . '-' . uniqid('')
			);
		}

		$this->CI->Member_dormant_model->insert($data);
		$this->CI->Member_model->delete($mem_id);
		$metadata = array('archived_dormant_datetime' => cdate('Y-m-d H:i:s'));
		$this->CI->Member_meta_model->save($mem_id, $metadata);
		$this->CI->Member_userid_model->update($mem_id, array('mem_status' => 2));


		return true;
	}

	/**
	 * 휴면상태에 있던 회원을 원래 회원 디비로 복원하는 프로세스입니다
	 */
	public function recover_from_dormant($mem_id = 0)
	{
		$mem_id = (int) $mem_id;
		if (empty($mem_id) OR $mem_id < 1) {
			return false;
		}

		$this->CI->load->model(array('Member_model', 'Member_dormant_model', 'Member_meta_model', 'Member_userid_model'));

		$data = $this->CI->Member_dormant_model->get_one($mem_id);
		$this->CI->Member_model->insert($data);
		$this->CI->Member_dormant_model->delete($mem_id);
		$metadata = array('archived_dormant_datetime' => '');
		$this->CI->Member_meta_model->save($mem_id, $metadata);
		$this->CI->Member_userid_model->update($mem_id, array('mem_status' => 0));

		return true;
	}

}
