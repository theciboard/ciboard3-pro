<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Stat hook class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

class _Stat
{

	function init()
	{
		$CI =& get_instance();

		if ($CI->uri->segment(1) === 'install') {
			return;
		}
		if ($CI->input->is_ajax_request() === true) {
			return;
		}


		if ( ! get_cookie('user_ip') OR $CI->uri->segment(1) !== config_item('uri_segment_admin')) {

			// 방문자 기록
			if (get_cookie('user_ip') !== $CI->input->ip_address()) {
				$CI->load->model('Stat_count_model');
				$CI->load->model('Stat_count_date_model');

				$cookie_name = 'user_ip';
				$cookie_value = $CI->input->ip_address();
				$cookie_expire = 86400; // 1일간 저장
				set_cookie($cookie_name, $cookie_value, $cookie_expire);

				$sco_agent = $CI->agent->agent_string() ? $CI->agent->agent_string() : '';
				$insertdata = array(
					'sco_ip' => $CI->input->ip_address(),
					'sco_date' => cdate('Y-m-d'),
					'sco_time' => cdate('H:i:s'),
					'sco_referer' => $CI->agent->referrer(),
					'sco_current' => current_full_url(),
					'sco_agent' => $sco_agent,
				);
				$result = $CI->Stat_count_model->insert($insertdata);

				// 정상으로 INSERT 되었다면 방문자 합계에 반영
				if ($result) {
					$CI->Stat_count_date_model->add_visit_date();
				}
			}

			if ( ! $CI->session->userdata('site_referer')) {
				$CI->session->set_userdata(
					'site_referer',
					$CI->agent->referrer()
				);
			}
		}
	}
}
