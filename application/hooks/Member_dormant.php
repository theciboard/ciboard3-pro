<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Member_dormant hook class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

class _Member_dormant
{

	private $CI;

	public $dormant_days_text;

	/**
	 * 휴면회원으로 전환, 휴면회원이 곧 될 대상자에게 메일을 발송하는 프로세스를 담당하고 있습니다.
	 */
	function init()
	{
		$this->CI =& get_instance();

		if ($this->CI->input->is_ajax_request() === true) {
			return;
		}
		if ($this->CI->input->method() !== 'get') {
			return;
		}
		if ($this->CI->uri->segment(1) === 'install') {
			return;
		}

		if ($this->CI->cbconfig->item('member_dormant_auto_clean')) {
			$cachename = 'check_hook_member_dormant_auto_clean';
			$cachetime = 25000;
			if ( ! $this->CI->cache->get($cachename)) {
				$this->auto_clean();
				$this->CI->cache->save($cachename, '1', $cachetime);
			}
		}

		if ($this->CI->cbconfig->item('member_dormant_auto_email')) {
			$cachename = 'check_hook_member_dormant_auto_email';
			$cachetime = 21000;
			if ( ! $this->CI->cache->get($cachename)) {
				$this->auto_email();
				$this->CI->cache->save($cachename, '1', $cachetime);
			}
		}
	}

	/**
	 * 일정 시간이 지나 휴면회원 대상자가 된 회원을 삭제하거나 별도의 저장소로 옮기는 프로세스를 담당합니다
	 */
	function auto_clean()
	{
		$where = array();
		$dormant_days = $this->CI->cbconfig->item('member_dormant_days') ? $this->CI->cbconfig->item('member_dormant_days') : 365;
		$gap = $dormant_days * 24 * 60 * 60;
		$lastlogin = ctimestamp() - $gap;
		$lastlogin_datetime = cdate('Y-m-d H:i:s', $lastlogin);
		$where['mem_lastlogin_datetime <='] = $lastlogin_datetime;
		$where['mem_register_datetime <='] = $lastlogin_datetime;
		$this->CI->load->model('Member_model');
		$result = $this->CI->Member_model->get('', 'mem_id', $where);
		if ($result) {
			$this->CI->load->model('Member_dormant_model');
			foreach ($result as $value) {
				$mem_id = element('mem_id', $value);
				if ($this->CI->cbconfig->item('member_dormant_method') === 'delete') {
					$this->CI->member->delete_member($mem_id);
				} else {
					$this->CI->member->archive_to_dormant($mem_id);
				}
			}
		}
	}


	/**
	 * 일정 시간이 지나 휴면회원 대상자가 되기 전에 이메일로 회원에게 알려주는 프로세스를 담당합니다
	 */
	function auto_email()
	{
		/**
		 * Email 라이브러리를 가져옵니다
		 */
		$this->CI->load->library('email');

		$this->dormant_days_text = array(
			'30' => '30일',
			'90' => '90일',
			'180' => '180일',
			'365' => '1년',
			'730' => '2년',
			'1095' => '3년',
			'1460' => '4년',
			'1825' => '5년',
		);

		$where = array();
		$dormant_days = $this->CI->cbconfig->item('member_dormant_days') ? $this->CI->cbconfig->item('member_dormant_days') : 365;
		$email_days = $this->CI->cbconfig->item('member_dormant_auto_email_days') ? $this->CI->cbconfig->item('member_dormant_auto_email_days') : 365;
		$gap = $dormant_days * 24 * 60 * 60;
		$email_gap = $email_days * 24 * 60 * 60;
		$lastlogin = ctimestamp() - $gap + $email_gap;
		$lastlogin_datetime = cdate('Y-m-d H:i:s', $lastlogin);
		$this->CI->load->model('Member_dormant_notify_model');
		$result = $this->CI->Member_dormant_notify_model->get_unsent_email_member($lastlogin_datetime);
		if ($result) {
			foreach ($result as $value) {
				$mem_id = element('mem_id', $value);
				// 메일 발송하기

				$searchconfig = array(
					'{홈페이지명}',
					'{회사명}',
					'{홈페이지주소}',
					'{회원아이디}',
					'{회원닉네임}',
					'{회원실명}',
					'{회원이메일}',
					'{메일수신여부}',
					'{쪽지수신여부}',
					'{문자수신여부}',
					'{최종로그인시간}',
					'{정리예정날짜}',
					'{정리기준}',
					'{정리방법}',
				);
				$receive_email = element('mem_receive_email', $value) ? '동의' : '거부';
				$receive_note = element('mem_use_note', $value) ? '동의' : '거부';
				$receive_sms = element('mem_receive_sms', $value) ? '동의' : '거부';
				$dormant_timestamp = strtotime(element('mem_lastlogin_datetime', $value)) + ($this->CI->cbconfig->item('member_dormant_days') * 24 * 60 * 60);
				$dormant_date = cdate('Y년 m월 d일', $dormant_timestamp);
				$dormant_datetime = cdate('Y-m-d H:i:s', $dormant_timestamp);
				$replaceconfig = array(
					$this->CI->cbconfig->item('site_title'),
					$this->CI->cbconfig->item('company_name'),
					site_url(),
					element('mem_userid', $value),
					element('mem_nickname', $value),
					element('mem_username', $value),
					element('mem_email', $value),
					$receive_email,
					$receive_note,
					$receive_sms,
					cdate('Y년 m월 d일 H시 i분', strtotime(element('mem_lastlogin_datetime', $value))),
					$dormant_date,
					$this->dormant_days_text[$this->CI->cbconfig->item('member_dormant_days')],
					($this->CI->cbconfig->item('member_dormant_method') === 'delete' ? '삭제' : '별도의 저장소에 보관'),
				);
				$replaceconfig_escape = array(
					html_escape($this->CI->cbconfig->item('site_title')),
					html_escape($this->CI->cbconfig->item('company_name')),
					site_url(),
					html_escape(element('mem_userid', $value)),
					html_escape(element('mem_nickname', $value)),
					html_escape(element('mem_username', $value)),
					html_escape(element('mem_email', $value)),
					$receive_email,
					$receive_note,
					$receive_sms,
					cdate('Y년 m월 d일 H시 i분', strtotime(element('mem_lastlogin_datetime', $value))),
					$dormant_date,
					$this->dormant_days_text[$this->CI->cbconfig->item('member_dormant_days')],
					($this->CI->cbconfig->item('member_dormant_method') === 'delete' ? '삭제' : '별도의 저장소에 보관'),
				);

				$title = str_replace(
					$searchconfig,
					$replaceconfig,
					$this->CI->cbconfig->item('send_email_dormant_notify_user_title')
				);
				$content = str_replace(
					$searchconfig,
					$replaceconfig_escape,
					$this->CI->cbconfig->item('send_email_dormant_notify_user_content')
				);

				$this->CI->email->clear(true);
				$this->CI->email->from($this->CI->cbconfig->item('webmaster_email'), $this->CI->cbconfig->item('webmaster_name'));
				$this->CI->email->to(element('mem_email', $value));
				$this->CI->email->subject($title);
				$this->CI->email->message($content);
				$this->CI->email->send();

				$insertdata = array(
					'mem_id' => element('mem_id', $value),
					'mem_userid' => element('mem_userid', $value),
					'mem_email' => element('mem_email', $value),
					'mem_username' => element('mem_username', $value),
					'mem_nickname' => element('mem_nickname', $value),
					'mem_register_datetime' => element('mem_register_datetime', $value),
					'mem_lastlogin_datetime' => element('mem_lastlogin_datetime', $value),
					'mdn_dormant_datetime' => $dormant_datetime,
					'mdn_dormant_notify_datetime' => cdate('Y-m-d H:i:s'),
				);
				$this->CI->Member_dormant_notify_model->insert($insertdata);
			}
		}
	}
}
