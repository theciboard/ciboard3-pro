<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Verify class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 이메일 인증 시 필요한 controller 입니다.
 */
class Verify extends CB_Controller
{

	/**
	 * 모델을 로딩합니다
	 */
	protected $models = array('Member_auth_email');

	/**
	 * 헬퍼를 로딩합니다
	 */
	protected $helpers = array('form', 'array');

	function __construct()
	{
		parent::__construct();

		/**
		 * 라이브러리를 로딩합니다
		 */
		$this->load->library(array('querystring'));
		if ( ! function_exists('password_hash')) {
			$this->load->helper('password');
		}
	}


	/**
	 * 이메일 인증 함수입니다.
	 */
	public function confirmemail()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_verify_confirmemail';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		if ( ! $this->input->get('code')) {
			show_404();
		}
		if ( ! $this->input->get('user')) {
			show_404();
		}
		if ($this->member->is_member()) {
			redirect();
		}
		if ( ! $this->cbconfig->item('use_register_email_auth')) {
			alert('이 웹사이트는 이메일 인증 기능을 사용하고 있지 않습니다');
		}

		$where = array(
			'mae_key' => $this->input->get('code'),
		);
		$result = $this->Member_auth_email_model->get_one('', '', $where);

		if ( ! element('mae_id', $result)) {
			$view['view']['message'] = '잘못된 접근입니다';
		} elseif ( ! (element('mae_type', $result) === '1' OR element('mae_type', $result) === '2')) {
			$view['view']['message'] = '잘못된 접근입니다';
		} elseif ( ! empty($result['mae_use_datetime']) && element('mae_use_datetime', $result) !== '0000-00-00 00:00:00') {
			$view['view']['message'] = '회원님은 이미 인증을 받으셨습니다';
		} elseif (strtotime(element('mae_generate_datetime', $result)) < ctimestamp()- 86400) {
			$view['view']['message'] = '24 시간 이내에 인증을 받으셔야 합니다';
		} elseif (element('mae_expired', $result)) {
			$view['view']['message'] = '잘못된 접근입니다';
		} else {

			$select = 'mem_id, mem_userid, mem_denied, mem_email_cert';
			$dbmember = $this->Member_model->get_by_memid(element('mem_id', $result), $select);

			if ( ! element('mem_id', $dbmember)) {
				$view['view']['message'] = '잘못된 접근입니다';
			} elseif (element('mem_userid', $dbmember) !== $this->input->get('user')) {
				$view['view']['message'] = '잘못된 접근입니다';
			} elseif (element('mem_denied', $dbmember)) {
				$view['view']['message'] = '접근이 금지된 아이디입니다';
			} elseif (element('mem_email_cert', $dbmember)) {
				$view['view']['message'] = '회원님은 이미 인증을 받으셨습니다';
			} else {

				$updatedata = array(
					'mem_email_cert' => 1,
				);
				$this->Member_model->update(element('mem_id', $result), $updatedata);
				$metadata = array(
					'meta_email_cert_datetime' => cdate('Y-m-d H:i:s'),
				);
				$this->load->model('Member_meta_model');
				$this->Member_meta_model->save(element('mem_id', $result), $metadata);

				$updateemail = array(
					'mae_use_datetime' => cdate('Y-m-d H:i:s'),
					'mae_expired' => 1
				);
				$view['view']['message'] = '이메일 인증이 완료되었습니다.<br />감사합니다';
				$this->Member_auth_email_model->update(element('mae_id', $result), $updateemail);

				$this->member->update_login_log(element('mem_id', $dbmember), element('mem_userid', $dbmember), 1, '이메일 인증 후 로그인 성공');
				$this->session->set_userdata('mem_id', element('mem_id', $dbmember));
			}
		}

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 레이아웃을 정의합니다
		 */
		$page_title = '이메일 인증';
		$layoutconfig = array(
			'path' => 'findaccount',
			'layout' => 'layout',
			'skin' => 'verifyemail',
			'layout_dir' => $this->cbconfig->item('layout_findaccount'),
			'mobile_layout_dir' => $this->cbconfig->item('mobile_layout_findaccount'),
			'use_sidebar' => $this->cbconfig->item('sidebar_findaccount'),
			'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_findaccount'),
			'skin_dir' => $this->cbconfig->item('skin_findaccount'),
			'mobile_skin_dir' => $this->cbconfig->item('mobile_skin_findaccount'),
			'page_title' => $page_title,
		);
		$view['layout'] = $this->managelayout->front($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}


	/**
	 * 패스워드 리셋위한 함수입니다.
	 */
	public function resetpassword()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_verify_resetpassword';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		if ( ! $this->input->get('code')) {
			show_404();
		}
		if ( ! $this->input->get('user')) {
			show_404();
		}
		if ($this->member->is_member()) {
			redirect();
		}

		$this->load->library(array('form_validation'));

		$password_length = $this->cbconfig->item('password_length');
		$view['view']['password_length'] = $password_length;

		$where = array(
			'mae_key' => $this->input->get('code'),
		);
		$result = $this->Member_auth_email_model->get_one('', '', $where);

		$view['view']['error_message'] = '';
		$view['view']['successs_message'] = '';
		if ( ! element('mae_id', $result)) {
			$view['view']['error_message'] = '잘못된 접근입니다';
		} elseif ( ! empty($result['mae_use_datetime']) && element('mae_use_datetime', $result) !== '0000-00-00 00:00:00') {
			$view['view']['error_message'] = '회원님은 이미 패스워드 변경을 하셨습니다';
		} elseif (strtotime(element('mae_generate_datetime', $result)) < ctimestamp()- 86400) {
			$view['view']['message'] = '24 시간 이내에 인증을 받으셔야 합니다';
		} elseif (element('mae_type', $result) !== '3') {
			$view['view']['error_message'] = '잘못된 접근입니다';
		} else {
			$is_dormant_member = false;
			$select = 'mem_id, mem_userid, mem_denied, mem_email_cert';
			$dbmember = $this->Member_model->get_by_memid(element('mem_id', $result), $select);
			if ( ! $dbmember) {
				$this->load->model('Member_dormant_model');
				$dbmember = $this->Member_dormant_model->get_by_memid(element('mem_id', $result), $select);
				if ($dbmember) {
					$is_dormant_member = true;
				}
			}
			if ( ! element('mem_id', $dbmember)) {
				$view['view']['error_message'] = '잘못된 접근입니다';
			} elseif (element('mem_userid', $dbmember) !== $this->input->get('user')) {
				$view['view']['error_message'] = '잘못된 접근입니다';
			} elseif (element('mem_denied', $dbmember)) {
				$view['view']['error_message'] = '회원님의 계정은 접근이 금지되어 있습니다';
			} elseif ($this->cbconfig->item('use_register_email_auth') && ! element('mem_email_cert', $dbmember)) {
				$view['view']['error_message'] = '회원님은 회원가입 후, 또는 이메일 정보 변경후 아직 이메일 인증을 받지 않으셨습니다';
			}
			$view['view']['mem_userid'] = element('mem_userid', $dbmember);

		}

		$config = array(
			array(
				'field' => 'new_password',
				'label' => '패스워드',
				'rules' => 'trim|required|min_length[' . $password_length . ']|callback__mem_password_check',
			),
			array(
				'field' => 'new_password_re',
				'label' => '패스워드',
				'rules' => 'trim|required|min_length[' . $password_length . ']',
			),
		);

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

			if (empty($view['view']['error_message'])) {

				if ($is_dormant_member) {
					$this->member->recover_from_dormant(element('mem_id', $result));
				}

				$hash = password_hash($this->input->post('new_password'), PASSWORD_BCRYPT);
				$updatedata = array(
					'mem_password' => $hash,
				);
				$this->Member_model->update(element('mem_id', $result), $updatedata);
				$metadata = array(
					'meta_change_pw_datetime' => cdate('Y-m-d H:i:s'),
				);
				$this->load->model('Member_meta_model');
				$this->Member_meta_model->save(element('mem_id', $result), $metadata);

				$updateemail = array(
					'mae_use_datetime' => cdate('Y-m-d H:i:s'),
					'mae_expired' => 1
				);
				$this->Member_auth_email_model->update(element('mae_id', $result), $updateemail);

				$view['view']['success_message'] = '회원님의 패스워드가 변경되었습니다.<br />감사합니다';

				$this->member->update_login_log(element('mem_id', $result), element('mem_userid', $result), 1, '패스워드 변경 후 로그인 성공');
				$this->session->set_userdata('mem_id', element('mem_id', $result));
			}
		}

		$password_description = '비밀번호는 ' . $password_length . '자리 이상이어야 ';
		if ($this->cbconfig->item('password_uppercase_length') OR $this->cbconfig->item('password_numbers_length') OR $this->cbconfig->item('password_specialchars_length')) {
			$password_description .= '하며 ';
			if ($this->cbconfig->item('password_uppercase_length')) {
				$password_description .= ', ' . $this->cbconfig->item('password_uppercase_length') . '개의 대문자';
			}
			if ($this->cbconfig->item('password_numbers_length')) {
				$password_description .= ', ' . $this->cbconfig->item('password_numbers_length') . '개의 숫자';
			}
			if ($this->cbconfig->item('password_specialchars_length')) {
				$password_description .= ', ' . $this->cbconfig->item('password_specialchars_length') . '개의 특수문자';
			}
			$password_description .= '를 포함해야 ';
		}
		$password_description .= '합니다';

		$view['view']['info'] = $password_description;

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 레이아웃을 정의합니다
		 */
		$page_title = '패스워드 변경';
		$layoutconfig = array(
			'path' => 'findaccount',
			'layout' => 'layout',
			'skin' => 'findaccount_change_pw',
			'layout_dir' => $this->cbconfig->item('layout_findaccount'),
			'mobile_layout_dir' => $this->cbconfig->item('mobile_layout_findaccount'),
			'use_sidebar' => $this->cbconfig->item('sidebar_findaccount'),
			'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_findaccount'),
			'skin_dir' => $this->cbconfig->item('skin_findaccount'),
			'mobile_skin_dir' => $this->cbconfig->item('mobile_skin_findaccount'),
			'page_title' => $page_title,
		);
		$view['layout'] = $this->managelayout->front($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}


	/**
	 * 새로 입력한 패스워드가 규약에 맞는지 체크합니다.
	 */
	public function _mem_password_check($str)
	{
		$uppercase = $this->cbconfig->item('password_uppercase_length');
		$number = $this->cbconfig->item('password_numbers_length');
		$specialchar = $this->cbconfig->item('password_specialchars_length');

		$this->load->helper('chkstring');
		$str_uc = count_uppercase($str);
		$str_num = count_numbers($str);
		$str_spc = count_specialchars($str);

		if ($str_uc < $uppercase OR $str_num < $number OR $str_spc < $specialchar) {

			$description = '비밀번호는 ';
			if ($str_uc < $uppercase) {
				$description .= ' ' . $uppercase . '개 이상의 대문자';
			}
			if ($str_num < $number) {
				$description .= ' ' . $number . '개 이상의 숫자';
			}
			if ($str_spc < $specialchar) {
				$description .= ' ' . $specialchar . '개 이상의 특수문자';
			}
			$description .= '를 포함해야 합니다';

			$this->form_validation->set_message(
				'_mem_password_check',
				$description
			);
			return false;
		}

		return true;
	}
}
