<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Gotourl class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 다른 페이지로 이동시 중간에 거쳐가는 controller 입니다.
 * admin 페이지에서 외부 페이이지로 이동시 이 페이지를 거쳐가면 referer 가 이 주소로 남기 때문에 admin 주소를 referer 에서 감출 수 있습니다
 */
class Gotourl extends CB_Controller
{

	function __construct()
	{
		parent::__construct();
	}


	/**
	 * url 이동 관련 함수입니다
	 */
	public function index()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_gotourl_index';
		$this->load->event($eventname);

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		$url = $this->input->get('url');
		if (empty($url)) {
			$url = '/';
		}
		redirect($url, 'refresh');
	}


	/**
	 * banner url 이동 관련 함수입니다
	 */
	public function banner($ban_id = 0)
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_gotourl_banner';
		$this->load->event($eventname);

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		$ban_id = (int) $ban_id;
		if (empty($ban_id) OR $ban_id < 1) {
			show_404();
		}

		$this->load->model(array('Banner_model'));

		$banner = $this->Banner_model->get_one($ban_id);
		if ( ! element('ban_id', $banner)) {
			show_404();
		}
		if ( ! element('ban_activated', $banner)) {
			show_404();
		}
		if ( ! element('ban_url', $banner)) {
			show_404();
		}

		if ( ! $this->session->userdata('banner_click_' . $ban_id )) {

			$this->session->set_userdata(
				'banner_click_' . $ban_id,
				'1'
			);

			$mem_id = (int) $this->member->item('mem_id');
			$insertdata = array(
				'ban_id' => element('ban_id', $banner),
				'mem_id' => $mem_id,
				'bcl_datetime' => cdate('Y-m-d H:i:s'),
				'bcl_ip' => $this->input->ip_address(),
				'bcl_referer' => $this->agent->referrer(),
				'bcl_url' => element('ban_url', $banner),
				'bcl_useragent' => $this->agent->agent_string(),
			);
			$this->load->model(array('Banner_click_log_model'));
			$this->Banner_click_log_model->insert($insertdata);

			$this->Banner_model->update_plus($ban_id, 'ban_hit', 1);
		}

		// 이벤트가 존재하면 실행합니다
		Events::trigger('after', $eventname);

		redirect(prep_url(element('ban_url', $banner)));
	}
}
