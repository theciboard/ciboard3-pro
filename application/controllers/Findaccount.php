<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Findaccount class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 회원정보 찾기에 관련도니 controller 입니다.
 */
class Findaccount extends CB_Controller
{

	/**
	 * 모델을 로딩합니다
	 */
	protected $models = array( 'Member_auth_email');

	/**
	 * 헬퍼를 로딩합니다
	 */
	protected $helpers = array('form', 'array', 'string');

	function __construct()
	{
		parent::__construct();

		/**
		 * 라이브러리를 로딩합니다
		 */
		$this->load->library(array('querystring', 'email'));
	}


	/**
	 * 아이디/패스워드찾기 페이지입니다
	 */
	public function index()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_findaccount_index';
		$this->load->event($eventname);

		if ($this->member->is_member() !== false
			&& ! (
				$this->member->is_admin() === 'super' && $this->uri->segment(1) === config_item('uri_segment_admin')
			)) {
			redirect();
		}

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		$this->load->library(array('form_validation'));

		if ( ! function_exists('password_hash')) {
			$this->load->helper('password');
		}

		/**
		 * 전송된 데이터의 유효성을 체크합니다
		 */
		$config = array();
		if ($this->input->post('findtype') === 'findidpw') {
			$config[] = array(
				'field' => 'idpw_email',
				'label' => '이메일',
				'rules' => 'trim|required|valid_email|callback__existemail',
			);
		} elseif ($this->input->post('findtype') === 'verifyemail') {
			$config[] = array(
				'field' => 'verify_email',
				'label' => '이메일',
				'rules' => 'trim|required|valid_email|callback__verifyemail',
			);
		} elseif ($this->input->post('findtype') === 'changeemail') {
			$config[] = array(
				'field' => 'change_userid',
				'label' => '아이디',
				'rules' => 'trim|required|alphanumunder|min_length[3]|max_length[20]',
			);
			$config[] = array(
				'field' => 'change_password',
				'label' => '패스워드',
				'rules' => 'trim|required|callback__check_id_pw[' . $this->input->post('change_userid') . ']',
			);
			$config[] = array(
				'field' => 'change_email',
				'label' => '이메일',
				'rules' => 'trim|required|valid_email|callback__change_email',
			);
		}

		$this->form_validation->set_rules($config);
		/**
		 * 유효성 검사를 하지 않는 경우, 또는 유효성 검사에 실패한 경우입니다.
		 * 즉 글쓰기나 수정 페이지를 보고 있는 경우입니다
		 */
		if ($this->form_validation->run() === false) {

			// 이벤트가 존재하면 실행합니다
			$view['view']['event']['formrunfalse'] = Events::trigger('formrunfalse', $eventname);

		} else {

			// 이벤트가 존재하면 실행합니다
			$view['view']['event']['formruntrue'] = Events::trigger('formruntrue', $eventname);

			if ($this->input->post('findtype') === 'findidpw') {

				// 이벤트가 존재하면 실행합니다
				$view['view']['event']['findidpw_before'] = Events::trigger('findidpw_before', $eventname);

				$mb = $this->Member_model->get_by_email($this->input->post('idpw_email'));
				if ( ! $mb) {
					$this->load->model('Member_dormant_model');
					$mb = $this->Member_dormant_model->get_by_email($this->input->post('idpw_email'));
				}

				$mem_id = (int) element('mem_id', $mb);
				$mae_type = 3;

				$vericode = array('$', '/', '.');
				$verificationcode = str_replace(
					$vericode,
					'',
					password_hash($mem_id . '-' . $this->input->post('idpw_email') . '-' . random_string('alnum', 10), PASSWORD_BCRYPT)
				);

				$beforeauthdata = array(
					'mem_id' => $mem_id,
					'mae_type' => $mae_type,
				);
				$this->Member_auth_email_model->delete_where($beforeauthdata);
				$authdata = array(
					'mem_id' => $mem_id,
					'mae_key' => $verificationcode,
					'mae_type' => $mae_type,
					'mae_generate_datetime' => cdate('Y-m-d H:i:s'),
				);
				$this->Member_auth_email_model->insert($authdata);

				$verify_url = site_url('verify/resetpassword?user=' . element('mem_userid', $mb) . '&code=' . $verificationcode);

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
					'{회원아이피}',
					'{패스워드변경주소}',
				);
				$receive_email = element('mem_receive_email', $mb) ? '동의' : '거부';
				$receive_note = element('mem_use_note', $mb) ? '동의' : '거부';
				$receive_sms = element('mem_receive_sms', $mb) ? '동의' : '거부';
				$replaceconfig = array(
					$this->cbconfig->item('site_title'),
					$this->cbconfig->item('company_name'),
					site_url(),
					element('mem_userid', $mb),
					element('mem_nickname', $mb),
					element('mem_username', $mb),
					element('mem_email', $mb),
					$receive_email,
					$receive_note,
					$receive_sms,
					$this->input->ip_address(),
					$verify_url,
				);
				$replaceconfig_escape = array(
					html_escape($this->cbconfig->item('site_title')),
					html_escape($this->cbconfig->item('company_name')),
					site_url(),
					element('mem_userid', $mb),
					html_escape(element('mem_nickname', $mb)),
					html_escape(element('mem_username', $mb)),
					html_escape(element('mem_email', $mb)),
					$receive_email,
					$receive_note,
					$receive_sms,
					$this->input->ip_address(),
					$verify_url,
				);

				$title = str_replace(
					$searchconfig,
					$replaceconfig,
					$this->cbconfig->item('send_email_findaccount_user_title')
				);
				$content = str_replace(
					$searchconfig,
					$replaceconfig_escape,
					$this->cbconfig->item('send_email_findaccount_user_content')
				);

				$this->email->clear(true);
				$this->email->from($this->cbconfig->item('webmaster_email'), $this->cbconfig->item('webmaster_name'));
				$this->email->to($this->input->post('idpw_email'));
				$this->email->subject($title);
				$this->email->message($content);
				$this->email->send();

				$view['view']['message'] = $this->input->post('idpw_email') . '로 인증메일이 발송되었습니다. <br />발송된 인증메일을 확인하신 후에 회원님의 정보 확인이 가능합니다';

				// 이벤트가 존재하면 실행합니다
				$view['view']['event']['findidpw_after'] = Events::trigger('findidpw_after', $eventname);

			} elseif ($this->input->post('findtype') === 'verifyemail') {

				// 이벤트가 존재하면 실행합니다
				$view['view']['event']['verifyemail_before'] = Events::trigger('verifyemail_before', $eventname);

				$mb = $this->Member_model->get_by_email($this->input->post('verify_email'));
				if ( ! $mb) {
					$this->load->model('Member_dormant_model');
					$mb = $this->Member_dormant_model->get_by_email($this->input->post('verify_email'));
				}
				$mem_id = (int) element('mem_id', $mb);
				$mae_type = 2;

				$vericode = array('$', '/', '.');
				$verificationcode = str_replace(
					$vericode,
					'',
					password_hash($mem_id . '-' . $this->input->post('verify_email') . '-' . random_string('alnum', 10), PASSWORD_BCRYPT)
				);

				$beforeauthdata = array(
					'mem_id' => $mem_id,
					'mae_type' => $mae_type,
				);
				$this->Member_auth_email_model->delete_where($beforeauthdata);
				$authdata = array(
					'mem_id' => $mem_id,
					'mae_key' => $verificationcode,
					'mae_type' => $mae_type,
					'mae_generate_datetime' => cdate('Y-m-d H:i:s'),
				);
				$this->Member_auth_email_model->insert($authdata);

				$verify_url = site_url('verify/confirmemail?user=' . element('mem_userid', $mb) . '&code=' . $verificationcode);

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
					'{회원아이피}',
					'{메일인증주소}',
				);
				$receive_email = element('mem_receive_email', $mb) ? '동의' : '거부';
				$receive_note = element('mem_use_note', $mb) ? '동의' : '거부';
				$receive_sms = element('mem_receive_sms', $mb) ? '동의' : '거부';
				$replaceconfig = array(
					$this->cbconfig->item('site_title'),
					$this->cbconfig->item('company_name'),
					site_url(),
					element('mem_userid', $mb),
					element('mem_nickname', $mb),
					element('mem_username', $mb),
					element('mem_email', $mb),
					$receive_email,
					$receive_note,
					$receive_sms,
					$this->input->ip_address(),
					$verify_url,
				);
				$replaceconfig_escape = array(
					html_escape($this->cbconfig->item('site_title')),
					html_escape($this->cbconfig->item('company_name')),
					site_url(),
					element('mem_userid', $mb),
					html_escape(element('mem_nickname', $mb)),
					html_escape(element('mem_username', $mb)),
					html_escape(element('mem_email', $mb)),
					$receive_email,
					$receive_note,
					$receive_sms,
					$this->input->ip_address(),
					$verify_url,
				);

				$title = str_replace(
					$searchconfig,
					$replaceconfig,
					$this->cbconfig->item('send_email_resendverify_user_title')
				);
				$content = str_replace(
					$searchconfig,
					$replaceconfig_escape,
					$this->cbconfig->item('send_email_resendverify_user_content')
				);

				$this->email->clear(true);
				$this->email->from($this->cbconfig->item('webmaster_email'), $this->cbconfig->item('webmaster_name'));
				$this->email->to($this->input->post('verify_email'));
				$this->email->subject($title);
				$this->email->message($content);
				$this->email->send();

				$view['view']['message'] = $this->input->post('verify_email') . '로 인증메일이 발송되었습니다. <br />발송된 인증메일을 확인하신 후에 사이트 이용이 가능합니다';

				// 이벤트가 존재하면 실행합니다
				$view['view']['event']['verifyemail_after'] = Events::trigger('verifyemail_after', $eventname);

			} elseif ($this->input->post('findtype') === 'changeemail') {

				// 이벤트가 존재하면 실행합니다
				$view['view']['event']['changeemail_before'] = Events::trigger('changeemail_before', $eventname);

				$mb = $this->Member_model->get_by_userid($this->input->post('change_userid'));
				if ( ! $mb) {
					$this->load->model('Member_dormant_model');
					$mb = $this->Member_dormant_model->get_by_userid($this->input->post('change_userid'));
				}

				$mem_id = (int) element('mem_id', $mb);
				$mae_type = 2;

				$vericode = array('$', '/', '.');
				$verificationcode = str_replace(
					$vericode,
					'',
					password_hash($mem_id . '-' . $this->input->post('change_email') . '-' . random_string('alnum', 10), PASSWORD_BCRYPT)
				);

				$beforeauthdata = array(
					'mem_id' => $mem_id,
					'mae_type' => $mae_type,
				);
				$this->Member_auth_email_model->delete_where($beforeauthdata);
				$authdata = array(
					'mem_id' => $mem_id,
					'mae_key' => $verificationcode,
					'mae_type' => $mae_type,
					'mae_generate_datetime' => cdate('Y-m-d H:i:s'),
				);
				$this->Member_auth_email_model->insert($authdata);

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
					'{회원아이피}',
					'{패스워드변경주소}',
				);
				$receive_email = element('mem_receive_email', $mb) ? '동의' : '거부';
				$receive_note = element('mem_use_note', $mb) ? '동의' : '거부';
				$receive_sms = element('mem_receive_sms', $mb) ? '동의' : '거부';
				$replaceconfig = array(
					$this->cbconfig->item('site_title'),
					$this->cbconfig->item('company_name'),
					site_url(),
					element('mem_userid', $mb),
					element('mem_nickname', $mb),
					element('mem_username', $mb),
					element('mem_email', $mb),
					$receive_email,
					$receive_note,
					$receive_sms,
					$this->input->ip_address(),
					$verify_url,
				);
				$replaceconfig_escape = array(
					html_escape($this->cbconfig->item('site_title')),
					html_escape($this->cbconfig->item('company_name')),
					site_url(),
					element('mem_userid', $mb),
					html_escape(element('mem_nickname', $mb)),
					html_escape(element('mem_username', $mb)),
					html_escape(element('mem_email', $mb)),
					$receive_email,
					$receive_note,
					$receive_sms,
					$this->input->ip_address(),
					$verify_url,
				);

				$title = str_replace(
					$searchconfig,
					$replaceconfig,
					$this->cbconfig->item('send_email_findaccount_user_title')
				);
				$content = str_replace(
					$searchconfig,
					$replaceconfig_escape,
					$this->cbconfig->item('send_email_findaccount_user_content')
				);

				$this->email->clear(true);
				$this->email->from($this->cbconfig->item('webmaster_email'), $this->cbconfig->item('webmaster_name'));
				$this->email->to($this->input->post('change_email'));
				$this->email->subject($title);
				$this->email->message($content);
				$this->email->send();

				$view['view']['message'] = $this->input->post('change_email') . '로 인증메일이 발송되었습니다. <br />발송된 인증메일을 확인하신 후에 사이트 이용이 가능합니다';

				// 이벤트가 존재하면 실행합니다
				$view['view']['event']['changeemail_after'] = Events::trigger('changeemail_after', $eventname);
			}
		}

		$view['view']['canonical'] = site_url('findaccount');

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 레이아웃을 정의합니다
		 */
		$page_title = $this->cbconfig->item('site_meta_title_findaccount');
		$meta_description = $this->cbconfig->item('site_meta_description_findaccount');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_findaccount');
		$meta_author = $this->cbconfig->item('site_meta_author_findaccount');
		$page_name = $this->cbconfig->item('site_page_name_findaccount');

		$layoutconfig = array(
			'path' => 'findaccount',
			'layout' => 'layout',
			'skin' => 'findaccount',
			'layout_dir' => $this->cbconfig->item('layout_findaccount'),
			'mobile_layout_dir' => $this->cbconfig->item('mobile_layout_findaccount'),
			'use_sidebar' => $this->cbconfig->item('sidebar_findaccount'),
			'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_findaccount'),
			'skin_dir' => $this->cbconfig->item('skin_findaccount'),
			'mobile_skin_dir' => $this->cbconfig->item('mobile_skin_findaccount'),
			'page_title' => $page_title,
			'meta_description' => $meta_description,
			'meta_keywords' => $meta_keywords,
			'meta_author' => $meta_author,
			'page_name' => $page_name,
		);
		$view['layout'] = $this->managelayout->front($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}


	/**
	 * 존재하는 이메일인지 체크합니다
	 */
	public function _existemail($str)
	{
		$userinfo = $this->Member_model
			->get_by_email($str, 'mem_id, mem_email, mem_denied, mem_email_cert');
		if ( ! $userinfo) {
			$this->load->model('Member_dormant_model');
			$userinfo = $this->Member_dormant_model
				->get_by_email($str, 'mem_id, mem_email, mem_denied, mem_email_cert');
		}
		if ( ! element('mem_id', $userinfo)) {
			$this->form_validation->set_message(
				'_existemail',
				'존재하지 않는 이메일주소입니다'
			);
			return false;
		}
		if (element('mem_denied', $userinfo)) {
			$this->form_validation->set_message(
				'_existemail',
				'회원님의 계정은 접근이 금지되어 있습니다'
			);
			return false;
		} elseif ($this->cbconfig->item('use_register_email_auth') && ! element('mem_email_cert', $userinfo)) {
			$this->form_validation->set_message(
				'_existemail',
				'회원님은 아직 이메일 인증을 받지 않으셨습니다<br> 아래 인증메일 재발송 란에서 이메일을 받아 인증해주시기 바랍니다'
			);
			return false;
		}

		return true;
	}


	/**
	 * 이메일이 실제 디비에 존재하는지 체크합니다
	 */
	public function _verifyemail($str)
	{
		if ( ! $this->cbconfig->item('use_register_email_auth')) {
			$this->form_validation->set_message(
				'_verifyemail',
				'이 웹사이트는 이메일 인증 기능을 사용하고 있지 않습니다'
			);
			return false;
		}

		$userinfo = $this->Member_model
			->get_by_email($str, 'mem_id, mem_email, mem_denied, mem_email_cert');
		if ( ! $userinfo) {
			$this->load->model('Member_dormant_model');
			$userinfo = $this->Member_dormant_model
				->get_by_email($str, 'mem_id, mem_email, mem_denied, mem_email_cert');
		}
		if ( ! element('mem_id', $userinfo)) {
			$this->form_validation->set_message(
				'_verifyemail',
				'존재하지 않는 이메일주소입니다'
			);
			return false;
		}
		if (element('mem_denied', $userinfo)) {
			$this->form_validation->set_message(
				'_verifyemail',
				'회원님의 계정은 접근이 금지되어 있습니다'
			);
			return false;
		}
		if (element('mem_email_cert', $userinfo)) {
			$this->form_validation->set_message(
				'_verifyemail',
				'회원님의 계정은 이미 인증이 완료되어 있습니다'
			);
			return false;
		}

		return true;
	}


	/**
	 * 이메일 변경시 기존에 다른 회원에 의해 사용되고 있는 이메일인지 체크합니다
	 */
	public function _change_email($str)
	{
		if ( ! $this->cbconfig->item('use_register_email_auth')) {
			$this->form_validation->set_message(
				'_change_email',
				'이 웹사이트는 이메일 인증 기능을 사용하고 있지 않습니다'
			);
			return false;
		}

		$userinfo = $this->Member_model
			->get_by_email($str, 'mem_id, mem_email, mem_denied, mem_email_cert');
		if ( ! $userinfo) {
			$this->load->model('Member_dormant_model');
			$userinfo = $this->Member_dormant_model
				->get_by_email($str, 'mem_id, mem_email, mem_denied, mem_email_cert');
		}
		if (element('mem_id', $userinfo)) {
			$this->form_validation->set_message(
				'_change_email',
				'이 이메일은 이미 다른 회원에 의해 사용되어지고 있는 이메일입니다'
			);
			return false;
		}
		if (element('mem_denied', $userinfo)) {
			$this->form_validation->set_message(
				'_change_email',
				'회원님의 계정은 접근이 금지되어 있습니다'
			);
			return false;
		}
		if (element('mem_email_cert', $userinfo)) {
			$this->form_validation->set_message(
				'_change_email',
				'회원님의 계정은 이미 인증이 완료되어 있습니다'
			);
			return false;
		}

		return true;
	}


	/**
	 * 아이디와 패스워드가 일치하는지 체크합니다
	 */
	public function _check_id_pw($password, $userid)
	{
		if ( ! $this->cbconfig->item('use_register_email_auth')) {
			$this->form_validation->set_message(
				'_check_id_pw',
				'이 웹사이트는 이메일 인증 기능을 사용하고 있지 않습니다'
			);
			return false;
		}

		if ( ! function_exists('password_hash')) {
			$this->load->helper('password');
		}

		$max_login_try_count = (int) $this->cbconfig->item('max_login_try_count');
		$max_login_try_limit_second = (int) $this->cbconfig->item('max_login_try_limit_second');

		$loginfailnum = 0;
		$loginfailmessage = '';
		if ($max_login_try_count && $max_login_try_limit_second) {
			$select = 'mll_id, mll_success, mem_id, mll_ip, mll_datetime';
			$where = array(
				'mll_ip' => $this->input->ip_address(),
				'mll_datetime > ' => strtotime(ctimestamp() - 86400 * 30),
			);
			$findex = 'mll_id';
			$forder = 'DESC';
			$this->load->model('Member_login_log_model');
			$logindata = $this->Member_login_log_model
				->get('', $select, $where, '', '', $findex, $forder);

			if ($logindata && is_array($logindata)) {
				foreach ($logindata as $key => $val) {
					if (element('mll_success', $val) === '0') {
						$loginfailnum++;
					}
					if (element('mll_success', $val) === '1') {
						break;
					}
				}
			}
			if ($loginfailnum > 0 && $loginfailnum % $max_login_try_count === 0) {
				$lastlogintrydatetime = $logindata[0]['mll_datetime'];
				$next_login = strtotime($lastlogintrydatetime)
					+ $max_login_try_limit_second
					- ctimestamp();
				if ($next_login > 0) {
					$this->form_validation->set_message(
						'_check_id_pw',
						'회원님은 패스워드를 연속으로 ' . $loginfailnum . '회 잘못 입력하셨기 때문에 '
						. $next_login . '초 후에 다시 시도가 가능합니다'
					);
					return false;
				}
			}
			$loginfailmessage = '<br />회원님은 ' . ($loginfailnum + 1)
				. '회 연속으로 패스워드를 잘못입력하셨습니다. ';
		}

		$userselect = 'mem_id, mem_password, mem_denied';
		$userinfo = $this->Member_model->get_by_userid($userid, $userselect);
		if ( ! $userinfo) {
			$this->load->model('Member_dormant_model');
			$userinfo = $this->Member_dormant_model->get_by_userid($userid, $userselect);
		}

		$hash = password_hash($password, PASSWORD_BCRYPT);

		if ( ! element('mem_id', $userinfo) OR ! element('mem_password', $userinfo)) {

			$this->form_validation->set_message(
				'_check_id_pw',
				'회원 아이디와 패스워드가 서로 맞지 않습니다' . $loginfailmessage
			);
			$this->member->update_login_log(0, $userid, 0, '회원아이디가 존재하지 않습니다');

			return false;

		} elseif ( ! password_verify($password, element('mem_password', $userinfo))) {
			$this->form_validation->set_message(
				'_check_id_pw',
				'회원 아이디와 패스워드가 서로 맞지 않습니다' . $loginfailmessage
			);
			$this->member->update_login_log(element('mem_id', $userinfo), $userid, 0, '패스워드가 올바르지 않습니다');

			return false;

		} elseif (element('mem_denied', $userinfo)) {
			if (element('mem_denied', $userinfo)) {
				$this->form_validation->set_message(
					'_check_id_pw',
					'회원님의 계정은 접근이 금지되어 있습니다'
				);
				$this->member->update_login_log(element('mem_id', $userinfo), $userid, 0, '차단된 회원아이디입니다');

				return false;
			}
		}

		return true;
	}
}
