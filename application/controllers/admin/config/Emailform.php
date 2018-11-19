<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Emailform class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 관리자>환경설정>메일/쪽지발송양식 controller 입니다.
 */
class Emailform extends CB_Controller
{

	/**
	 * 관리자 페이지 상의 현재 디렉토리입니다
	 * 페이지 이동시 필요한 정보입니다
	 */
	public $pagedir = 'config/emailform';

	/**
	 * 모델을 로딩합니다
	 */
	protected $models = array('Config');

	/**
	 * 이 컨트롤러의 메인 모델 이름입니다
	 */
	protected $modelname = 'Config_model';

	/**
	 * 헬퍼를 로딩합니다
	 */
	protected $helpers = array('form', 'array', 'dhtml_editor');

	function __construct()
	{
		parent::__construct();
	}


	/**
	 * 메일/쪽지발송양식>회원가입시 양식 페이지입니다
	 */
	public function index()
	{
		$this->register();
	}

	/**
	 * 메일/쪽지발송양식>회원가입시 양식 페이지입니다
	 */
	public function register()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_config_emailform_register';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		/**
		 * Validation 라이브러리를 가져옵니다
		 */
		$this->load->library('form_validation');

		/**
		 * 전송된 데이터의 유효성을 체크합니다
		 */
		$config = array(
			array(
				'field' => 'is_submit',
				'label' => '전송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'send_email_register_admin',
				'label' => '최고관리자에게메일발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'send_email_register_user',
				'label' => '회원에게메일발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'send_email_register_alluser',
				'label' => '회원에게메일발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'send_note_register_admin',
				'label' => '최고관리자에게쪽지발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'send_note_register_user',
				'label' => '회원에게쪽지발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'send_sms_register_admin',
				'label' => '최고관리자에게문자(SMS)발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'send_sms_register_user',
				'label' => '회원에게문자(SMS)발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'send_sms_register_alluser',
				'label' => '회원에게문자(SMS)발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'send_email_register_admin_title',
				'label' => '최고관리자에게 보낼 메일 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_email_register_admin_content',
				'label' => '최고관리자에게 보낼 메일 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_email_register_user_title',
				'label' => '회원에게 보낼 메일 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_email_register_user_content',
				'label' => '회원에게 보낼 메일 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_email_register_user_verifytitle',
				'label' => '이메일인증기능사용시 회원에게 보낼 메일 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_email_register_user_verifycontent',
				'label' => '이메일인증기능사용시 회원에게 보낼 메일 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_note_register_admin_title',
				'label' => '최고관리자에게 보낼 쪽지 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_note_register_admin_content',
				'label' => '최고관리자에게 보낼 쪽지 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_note_register_user_title',
				'label' => '회원에게 보낼 쪽지 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_note_register_user_content',
				'label' => '회원에게 보낼 쪽지 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_sms_register_admin_content',
				'label' => '최고관리자에게 발송문자내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_sms_register_user_content',
				'label' => '회원에게 발송문자내용',
				'rules' => 'trim|required',
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
			/**
			 * 유효성 검사를 통과한 경우입니다.
			 * 즉 데이터의 insert 나 update 의 process 처리가 필요한 상황입니다
			 */

			// 이벤트가 존재하면 실행합니다
			$view['view']['event']['formruntrue'] = Events::trigger('formruntrue', $eventname);

			$array = array(
				'send_email_register_admin', 'send_email_register_user', 'send_email_register_alluser',
				'send_note_register_admin', 'send_note_register_user', 'send_sms_register_admin',
				'send_sms_register_user', 'send_sms_register_alluser', 'send_email_register_admin_title',
				'send_email_register_admin_content', 'send_email_register_user_title',
				'send_email_register_user_content', 'send_email_register_user_verifytitle',
				'send_email_register_user_verifycontent', 'send_note_register_admin_title',
				'send_note_register_admin_content', 'send_note_register_user_title',
				'send_note_register_user_content', 'send_sms_register_admin_content',
				'send_sms_register_user_content'
			);
			foreach ($array as $value) {
				$savedata[$value] = $this->input->post($value, null, '');
			}
			$this->Config_model->save($savedata);
			$view['view']['alert_message'] = '회원가입시 발송양식 설정이 저장되었습니다';
		}

		$getdata = $this->Config_model->get_all_meta();
		$view['view']['data'] = $getdata;

		$view['view']['sms_library_exists'] =  (file_exists(APPPATH . 'libraries/Smslib.php')) ? '1' : '';

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 어드민 레이아웃을 정의합니다
		 */
		$layoutconfig = array('layout' => 'layout', 'skin' => 'register');
		$view['layout'] = $this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}


	/**
	 * 메일/쪽지발송양식>패스워드 변경시 양식 페이지입니다
	 */
	public function changepw()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_config_emailform_changepw';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		/**
		 * Validation 라이브러리를 가져옵니다
		 */
		$this->load->library('form_validation');

		/**
		 * 전송된 데이터의 유효성을 체크합니다
		 */
		$config = array(
			array(
				'field' => 'is_submit',
				'label' => '전송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'send_email_changepw_admin',
				'label' => '최고관리자에게메일발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'send_email_changepw_user',
				'label' => '회원에게메일발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'send_email_changepw_alluser',
				'label' => '회원에게메일발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'send_note_changepw_admin',
				'label' => '최고관리자에게쪽지발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'send_note_changepw_user',
				'label' => '회원에게쪽지발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'send_sms_changepw_admin',
				'label' => '최고관리자에게문자(SMS)발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'send_sms_changepw_user',
				'label' => '회원에게문자(SMS)발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'send_sms_changepw_alluser',
				'label' => '회원에게문자(SMS)발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'send_email_changepw_admin_title',
				'label' => '최고관리자에게 보낼 메일 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_email_changepw_admin_content',
				'label' => '최고관리자에게 보낼 메일 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_email_changepw_user_title',
				'label' => '회원에게 보낼 메일 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_email_changepw_user_content',
				'label' => '회원에게 보낼 메일 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_note_changepw_admin_title',
				'label' => '최고관리자에게 보낼 쪽지 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_note_changepw_admin_content',
				'label' => '최고관리자에게 보낼 쪽지 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_note_changepw_user_title',
				'label' => '회원에게 보낼 쪽지 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_note_changepw_user_content',
				'label' => '회원에게 보낼 쪽지 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_sms_changepw_admin_content',
				'label' => '최고관리자에게 발송문자내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_sms_changepw_user_content',
				'label' => '회원에게 발송문자내용',
				'rules' => 'trim|required',
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
			/**
			 * 유효성 검사를 통과한 경우입니다.
			 * 즉 데이터의 insert 나 update 의 process 처리가 필요한 상황입니다
			 */

			// 이벤트가 존재하면 실행합니다
			$view['view']['event']['formruntrue'] = Events::trigger('formruntrue', $eventname);

			$array = array(
				'send_email_changepw_admin', 'send_email_changepw_user',
				'send_email_changepw_alluser', 'send_note_changepw_admin', 'send_note_changepw_user',
				'send_sms_changepw_admin', 'send_sms_changepw_user', 'send_sms_changepw_alluser',
				'send_email_changepw_admin_title', 'send_email_changepw_admin_content',
				'send_email_changepw_user_title', 'send_email_changepw_user_content',
				'send_note_changepw_admin_title', 'send_note_changepw_admin_content',
				'send_note_changepw_user_title', 'send_note_changepw_user_content',
				'send_sms_changepw_admin_content', 'send_sms_changepw_user_content'
			);
			foreach ($array as $value) {
				$savedata[$value] = $this->input->post($value, null, '');
			}
			$this->Config_model->save($savedata);
			$view['view']['alert_message'] = '회원가입시 발송양식 설정이 저장되었습니다';
		}

		$getdata = $this->Config_model->get_all_meta();
		$view['view']['data'] = $getdata;

		$view['view']['sms_library_exists'] =  (file_exists(APPPATH . 'libraries/Smslib.php')) ? '1' : '';
		
		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 어드민 레이아웃을 정의합니다
		 */
		$layoutconfig = array('layout' => 'layout', 'skin' => 'changepw');
		$view['layout'] = $this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}

	/**
	 * 메일/쪽지발송양식>회원탈퇴시 양식 페이지입니다
	 */
	public function memberleave()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_config_emailform_memberleave';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		/**
		 * Validation 라이브러리를 가져옵니다
		 */
		$this->load->library('form_validation');

		/**
		 * 전송된 데이터의 유효성을 체크합니다
		 */
		$config = array(
			array(
				'field' => 'is_submit',
				'label' => '전송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'send_email_memberleave_admin',
				'label' => '최고관리자에게메일발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'send_email_memberleave_user',
				'label' => '회원에게메일발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'send_email_memberleave_alluser',
				'label' => '회원에게메일발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'send_note_memberleave_admin',
				'label' => '최고관리자에게쪽지발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'send_sms_memberleave_admin',
				'label' => '최고관리자에게문자(SMS)발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'send_sms_memberleave_user',
				'label' => '회원에게문자(SMS)발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'send_sms_memberleave_alluser',
				'label' => '회원에게문자(SMS)발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'send_email_memberleave_admin_title',
				'label' => '최고관리자에게 보낼 메일 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_email_memberleave_admin_content',
				'label' => '최고관리자에게 보낼 메일 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_email_memberleave_user_title',
				'label' => '회원에게 보낼 메일 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_email_memberleave_user_content',
				'label' => '회원에게 보낼 메일 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_note_memberleave_admin_title',
				'label' => '최고관리자에게 보낼 쪽지 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_note_memberleave_admin_content',
				'label' => '최고관리자에게 보낼 쪽지 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_sms_memberleave_admin_content',
				'label' => '최고관리자에게 발송문자내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_sms_memberleave_user_content',
				'label' => '회원에게 발송문자내용',
				'rules' => 'trim|required',
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
			/**
			 * 유효성 검사를 통과한 경우입니다.
			 * 즉 데이터의 insert 나 update 의 process 처리가 필요한 상황입니다
			 */

			// 이벤트가 존재하면 실행합니다
			$view['view']['event']['formruntrue'] = Events::trigger('formruntrue', $eventname);

			$array = array(
				'send_email_memberleave_admin', 'send_email_memberleave_user',
				'send_email_memberleave_alluser', 'send_note_memberleave_admin',
				'send_sms_memberleave_admin', 'send_sms_memberleave_user',
				'send_sms_memberleave_alluser', 'send_email_memberleave_admin_title',
				'send_email_memberleave_admin_content', 'send_email_memberleave_user_title',
				'send_email_memberleave_user_content', 'send_note_memberleave_admin_title',
				'send_note_memberleave_admin_content', 'send_sms_memberleave_admin_content',
				'send_sms_memberleave_user_content'
			);
			foreach ($array as $value) {
				$savedata[$value] = $this->input->post($value, null, '');
			}
			$this->Config_model->save($savedata);
			$view['view']['alert_message'] = '회원가입시 발송양식 설정이 저장되었습니다';
		}

		$getdata = $this->Config_model->get_all_meta();
		$view['view']['data'] = $getdata;

		$view['view']['sms_library_exists'] =  (file_exists(APPPATH . 'libraries/Smslib.php')) ? '1' : '';

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 어드민 레이아웃을 정의합니다
		 */
		$layoutconfig = array('layout' => 'layout', 'skin' => 'memberleave');
		$view['layout'] = $this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}

	/**
	 * 메일/쪽지발송양식>이메일 정보 변경시 양식 페이지입니다
	 */
	public function changeemail()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_config_emailform_changeemail';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		/**
		 * Validation 라이브러리를 가져옵니다
		 */
		$this->load->library('form_validation');

		/**
		 * 전송된 데이터의 유효성을 체크합니다
		 */
		$config = array(
			array(
				'field' => 'is_submit',
				'label' => '전송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'send_email_changeemail_user_title',
				'label' => '이메일 변경시 발송메일 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_email_changeemail_user_content',
				'label' => '이메일 변경시 발송메일 내용',
				'rules' => 'trim|required',
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
			/**
			 * 유효성 검사를 통과한 경우입니다.
			 * 즉 데이터의 insert 나 update 의 process 처리가 필요한 상황입니다
			 */

			// 이벤트가 존재하면 실행합니다
			$view['view']['event']['formruntrue'] = Events::trigger('formruntrue', $eventname);

			$array = array(
				'send_email_changeemail_user_title', 'send_email_changeemail_user_content'
			);
			foreach ($array as $value) {
				$savedata[$value] = $this->input->post($value, null, '');
			}
			$this->Config_model->save($savedata);
			$view['view']['alert_message'] = '이메일변경시인증메일 발송양식 설정이 저장되었습니다';
		}

		$getdata = $this->Config_model->get_all_meta();
		$view['view']['data'] = $getdata;

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 어드민 레이아웃을 정의합니다
		 */
		$layoutconfig = array('layout' => 'layout', 'skin' => 'changeemail');
		$view['layout'] = $this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}

	/**
	 * 메일/쪽지발송양식>회원정보찾기 양식 페이지입니다
	 */
	public function findaccount()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_config_emailform_findaccount';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		/**
		 * Validation 라이브러리를 가져옵니다
		 */
		$this->load->library('form_validation');

		/**
		 * 전송된 데이터의 유효성을 체크합니다
		 */
		$config = array(
			array(
				'field' => 'is_submit',
				'label' => '전송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'send_email_findaccount_user_title',
				'label' => 'ID/PW찾기 발송메일 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_email_findaccount_user_content',
				'label' => 'ID/PW찾기 발송메일 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_email_resendverify_user_title',
				'label' => '인증메일재발송 발송메일 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_email_resendverify_user_content',
				'label' => '인증메일재발송 발송메일 내용',
				'rules' => 'trim|required',
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
			/**
			 * 유효성 검사를 통과한 경우입니다.
			 * 즉 데이터의 insert 나 update 의 process 처리가 필요한 상황입니다
			 */

			// 이벤트가 존재하면 실행합니다
			$view['view']['event']['formruntrue'] = Events::trigger('formruntrue', $eventname);

			$array = array(
				'send_email_findaccount_user_title', 'send_email_findaccount_user_content',
				'send_email_resendverify_user_title', 'send_email_resendverify_user_content'
			);
			foreach ($array as $value) {
				$savedata[$value] = $this->input->post($value, null, '');
			}
			$this->Config_model->save($savedata);
			$view['view']['alert_message'] = '회원정보찾기 발송양식 설정이 저장되었습니다';
		}

		$getdata = $this->Config_model->get_all_meta();
		$view['view']['data'] = $getdata;

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 어드민 레이아웃을 정의합니다
		 */
		$layoutconfig = array('layout' => 'layout', 'skin' => 'findaccount');
		$view['layout'] = $this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}

	/**
	 * 메일/쪽지발송양식>게시글작성시 양식 페이지입니다
	 */
	public function post()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_config_emailform_post';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		/**
		 * Validation 라이브러리를 가져옵니다
		 */
		$this->load->library('form_validation');

		/**
		 * 전송된 데이터의 유효성을 체크합니다
		 */
		$config = array(
			array(
				'field' => 'is_submit',
				'label' => '전송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'send_email_post_admin_title',
				'label' => '게시글작성시 관리자에게 발송메일 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_email_post_admin_content',
				'label' => '게시글작성시 관리자에게 발송메일 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_email_post_writer_title',
				'label' => '게시글작성시 게시글작성자에게 발송메일 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_email_post_writer_content',
				'label' => '게시글작성시 게시글작성자에게 발송메일 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_note_post_admin_title',
				'label' => '게시글작성시 관리자에게 발송쪽지 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_note_post_admin_content',
				'label' => '게시글작성시 관리자에게 발송쪽지 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_note_post_writer_title',
				'label' => '게시글작성시 게시글작성자에게 발송쪽지 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_note_post_writer_content',
				'label' => '게시글작성시 게시글작성자에게 발송쪽지 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_sms_post_admin_content',
				'label' => '관리자에게 발송문자내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_sms_post_writer_content',
				'label' => '게시글 작성 게시글작성자에게 발송문자내용',
				'rules' => 'trim|required',
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
			/**
			 * 유효성 검사를 통과한 경우입니다.
			 * 즉 데이터의 insert 나 update 의 process 처리가 필요한 상황입니다
			 */

			// 이벤트가 존재하면 실행합니다
			$view['view']['event']['formruntrue'] = Events::trigger('formruntrue', $eventname);

			$array = array(
				'send_email_post_admin_title', 'send_email_post_admin_content',
				'send_email_post_writer_title', 'send_email_post_writer_content',
				'send_note_post_admin_title', 'send_note_post_admin_content',
				'send_note_post_writer_title', 'send_note_post_writer_content',
				'send_sms_post_admin_content', 'send_sms_post_writer_content'
			);
			foreach ($array as $value) {
				$savedata[$value] = $this->input->post($value, null, '');
			}
			$this->Config_model->save($savedata);
			$view['view']['alert_message'] = '게시글 입력시 발송양식 설정이 저장되었습니다';
		}

		$getdata = $this->Config_model->get_all_meta();
		$view['view']['data'] = $getdata;

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 어드민 레이아웃을 정의합니다
		 */
		$layoutconfig = array('layout' => 'layout', 'skin' => 'post');
		$view['layout'] = $this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}

	/**
	 * 메일/쪽지발송양식>댓글작성시 양식 페이지입니다
	 */
	public function comment()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_config_emailform_comment';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		/**
		 * Validation 라이브러리를 가져옵니다
		 */
		$this->load->library('form_validation');

		/**
		 * 전송된 데이터의 유효성을 체크합니다
		 */
		$config = array(
			array(
				'field' => 'is_submit',
				'label' => '전송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'send_email_comment_admin_title',
				'label' => '댓글작성시 관리자에게 발송메일 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_email_comment_admin_content',
				'label' => '댓글작성시 관리자에게 발송메일 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_email_comment_post_writer_title',
				'label' => '댓글작성시 원글작성자에게 발송메일 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_email_comment_post_writer_content',
				'label' => '댓글작성시 원글작성자에게 발송메일 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_email_comment_comment_writer_title',
				'label' => '댓글작성시 댓글작성자에게 발송메일 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_email_comment_comment_writer_content',
				'label' => '댓글작성시 댓글작성자에게 발송메일 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_note_comment_admin_title',
				'label' => '댓글작성시 관리자에게 발송쪽지 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_note_comment_admin_content',
				'label' => '댓글작성시 관리자에게 발송쪽지 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_note_comment_post_writer_title',
				'label' => '댓글작성시 원글작성자에게 발송쪽지 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_note_comment_post_writer_content',
				'label' => '댓글작성시 원글작성자에게 발송쪽지 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_note_comment_comment_writer_title',
				'label' => '댓글작성시 댓글작성자에게 발송쪽지 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_note_comment_comment_writer_content',
				'label' => '댓글작성시 댓글작성자에게 발송쪽지 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_sms_comment_admin_content',
				'label' => '관리자에게 발송문자내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_sms_comment_post_writer_content',
				'label' => '댓글 작성 원글작성자에게 발송문자내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_sms_comment_comment_writer_content',
				'label' => '댓글 작성 댓글작성자에게 발송문자내용',
				'rules' => 'trim|required',
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
			/**
			 * 유효성 검사를 통과한 경우입니다.
			 * 즉 데이터의 insert 나 update 의 process 처리가 필요한 상황입니다
			 */

			// 이벤트가 존재하면 실행합니다
			$view['view']['event']['formruntrue'] = Events::trigger('formruntrue', $eventname);

			$array = array(
				'send_email_comment_admin_title', 'send_email_comment_admin_content',
				'send_email_comment_post_writer_title', 'send_email_comment_post_writer_content',
				'send_email_comment_comment_writer_title',
				'send_email_comment_comment_writer_content', 'send_note_comment_admin_title',
				'send_note_comment_admin_content', 'send_note_comment_post_writer_title',
				'send_note_comment_post_writer_content', 'send_note_comment_comment_writer_title',
				'send_note_comment_comment_writer_content', 'send_sms_comment_admin_content',
				'send_sms_comment_post_writer_content', 'send_sms_comment_comment_writer_content'
			);
			foreach ($array as $value) {
				$savedata[$value] = $this->input->post($value, null, '');
			}
			$this->Config_model->save($savedata);
			$view['view']['alert_message'] = '댓글글 입력시 발송양식 설정이 저장되었습니다';
		}

		$getdata = $this->Config_model->get_all_meta();
		$view['view']['data'] = $getdata;

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 어드민 레이아웃을 정의합니다
		 */
		$layoutconfig = array('layout' => 'layout', 'skin' => 'comment');
		$view['layout'] = $this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}

	/**
	 * 메일/쪽지발송양식>게시글신고시 양식 페이지입니다
	 */
	public function blame()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_config_emailform_blame';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		/**
		 * Validation 라이브러리를 가져옵니다
		 */
		$this->load->library('form_validation');

		/**
		 * 전송된 데이터의 유효성을 체크합니다
		 */
		$config = array(
			array(
				'field' => 'is_submit',
				'label' => '전송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'send_email_blame_admin_title',
				'label' => '게시글신고시 관리자에게 발송메일 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_email_blame_admin_content',
				'label' => '게시글신고시 관리자에게 발송메일 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_email_blame_post_writer_title',
				'label' => '게시글신고시 원글작성자에게 발송메일 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_email_blame_post_writer_content',
				'label' => '게시글신고시 원글작성자에게 발송메일 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_note_blame_admin_title',
				'label' => '게시글신고시 관리자에게 발송쪽지 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_note_blame_admin_content',
				'label' => '게시글신고시 관리자에게 발송쪽지 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_note_blame_post_writer_title',
				'label' => '게시글신고시 원글작성자에게 발송쪽지 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_note_blame_post_writer_content',
				'label' => '게시글신고시 원글작성자에게 발송쪽지 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_sms_blame_admin_content',
				'label' => '관리자에게 발송문자내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_sms_blame_post_writer_content',
				'label' => '게시글 신고시 원글작성자에게 발송문자내용',
				'rules' => 'trim|required',
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
			/**
			 * 유효성 검사를 통과한 경우입니다.
			 * 즉 데이터의 insert 나 update 의 process 처리가 필요한 상황입니다
			 */

			// 이벤트가 존재하면 실행합니다
			$view['view']['event']['formruntrue'] = Events::trigger('formruntrue', $eventname);

			$array = array(
				'send_email_blame_admin_title', 'send_email_blame_admin_content',
				'send_email_blame_post_writer_title', 'send_email_blame_post_writer_content',
				'send_note_blame_admin_title', 'send_note_blame_admin_content',
				'send_note_blame_post_writer_title', 'send_note_blame_post_writer_content',
				'send_sms_blame_admin_content', 'send_sms_blame_post_writer_content'
			);
			foreach ($array as $value) {
				$savedata[$value] = $this->input->post($value, null, '');
			}
			$this->Config_model->save($savedata);
			$view['view']['alert_message'] = '게시글 신고시 발송양식 설정이 저장되었습니다';
		}

		$getdata = $this->Config_model->get_all_meta();
		$view['view']['data'] = $getdata;

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 어드민 레이아웃을 정의합니다
		 */
		$layoutconfig = array('layout' => 'layout', 'skin' => 'blame');
		$view['layout'] = $this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}

	/**
	 * 메일/쪽지발송양식>댓글글신고시 양식 페이지입니다
	 */
	public function comment_blame()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_config_emailform_comment_blame';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		/**
		 * Validation 라이브러리를 가져옵니다
		 */
		$this->load->library('form_validation');

		/**
		 * 전송된 데이터의 유효성을 체크합니다
		 */
		$config = array(
			array(
				'field' => 'is_submit',
				'label' => '전송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'send_email_comment_blame_admin_title',
				'label' => '댓글신고시 관리자에게 발송메일 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_email_comment_blame_admin_content',
				'label' => '댓글신고시 관리자에게 발송메일 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_email_comment_blame_post_writer_title',
				'label' => '댓글신고시 원글작성자에게 발송메일 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_email_comment_blame_post_writer_content',
				'label' => '댓글신고시 원글작성자에게 발송메일 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_email_comment_blame_comment_writer_title',
				'label' => '댓글신고시 댓글작성자에게 발송메일 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_email_comment_blame_comment_writer_content',
				'label' => '댓글신고시 댓글작성자에게 발송메일 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_note_comment_blame_admin_title',
				'label' => '댓글신고시 관리자에게 발송쪽지 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_note_comment_blame_admin_content',
				'label' => '댓글신고시 관리자에게 발송쪽지 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_note_comment_blame_post_writer_title',
				'label' => '댓글신고시 원글작성자에게 발송쪽지 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_note_comment_blame_post_writer_content',
				'label' => '댓글신고시 원글작성자에게 발송쪽지 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_note_comment_blame_comment_writer_title',
				'label' => '댓글신고시 댓글작성자에게 발송쪽지 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_note_comment_blame_comment_writer_content',
				'label' => '댓글신고시 댓글작성자에게 발송쪽지 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_sms_comment_blame_admin_content',
				'label' => '관리자에게 발송문자내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_sms_comment_blame_post_writer_content',
				'label' => '댓글 신고시 원글작성자에게 발송문자내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_sms_comment_blame_comment_writer_content',
				'label' => '댓글 신고시 댓글작성자에게 발송문자내용',
				'rules' => 'trim|required',
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
			/**
			 * 유효성 검사를 통과한 경우입니다.
			 * 즉 데이터의 insert 나 update 의 process 처리가 필요한 상황입니다
			 */

			// 이벤트가 존재하면 실행합니다
			$view['view']['event']['formruntrue'] = Events::trigger('formruntrue', $eventname);

			$array = array(
				'send_email_comment_blame_admin_title', 'send_email_comment_blame_admin_content',
				'send_email_comment_blame_post_writer_title', 'send_email_comment_blame_post_writer_content',
				'send_email_comment_blame_comment_writer_title', 'send_email_comment_blame_comment_writer_content',
				'send_note_comment_blame_admin_title', 'send_note_comment_blame_admin_content',
				'send_note_comment_blame_post_writer_title', 'send_note_comment_blame_post_writer_content',
				'send_note_comment_blame_comment_writer_title', 'send_note_comment_blame_comment_writer_content',
				'send_sms_comment_blame_admin_content', 'send_sms_comment_blame_post_writer_content',
				'send_sms_comment_blame_comment_writer_content'
			);
			foreach ($array as $value) {
				$savedata[$value] = $this->input->post($value, null, '');
			}
			$this->Config_model->save($savedata);
			$view['view']['alert_message'] = '댓글 신고시 발송양식 설정이 저장되었습니다';
		}

		$getdata = $this->Config_model->get_all_meta();
		$view['view']['data'] = $getdata;

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 어드민 레이아웃을 정의합니다
		 */
		$layoutconfig = array('layout' => 'layout', 'skin' => 'comment_blame');
		$view['layout'] = $this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}
}
