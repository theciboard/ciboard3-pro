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
 * 관리자>예치금관리>메일/쪽지발송양식 controller 입니다.
 */
class Emailform extends CB_Controller
{

	/**
	 * 관리자 페이지 상의 현재 디렉토리입니다
	 * 페이지 이동시 필요한 정보입니다
	 */
	public $pagedir = 'deposit/emailform';

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

		/**
		 * 라이브러리를 로딩합니다
		 */
		$this->load->library(array('pagination', 'querystring', 'depositlib'));
	}


	/**
	 * 메일/쪽지발송양식 페이지입니다
	 */
	public function index()
	{
		$this->cash_to_deposit();
	}

	/**
	 * 메일/쪽지발송양식>카드/이체 등으로 예치금 구매시 양식 페이지입니다
	 */
	public function cash_to_deposit()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_deposit_emailform_cash_to_deposit';
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
				'field' => 'deposit_email_admin_cash_to_deposit',
				'label' => '최고관리자에게메일발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_email_user_cash_to_deposit',
				'label' => '구매자에게메일발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_email_alluser_cash_to_deposit',
				'label' => '구매자에게메일발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_note_admin_cash_to_deposit',
				'label' => '최고관리자에게쪽지발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_note_user_cash_to_deposit',
				'label' => '구매자에게쪽지발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_sms_admin_cash_to_deposit',
				'label' => '최고관리자에게문자(SMS)발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_sms_user_cash_to_deposit',
				'label' => '구매자에게문자(SMS)발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_sms_alluser_cash_to_deposit',
				'label' => '구매자에게문자(SMS)발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_email_admin_cash_to_deposit_title',
				'label' => '최고관리자에게 보낼 메일 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'deposit_email_admin_cash_to_deposit_content',
				'label' => '최고관리자에게 보낼 메일 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'deposit_email_user_cash_to_deposit_title',
				'label' => '구매자에게 보낼 메일 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'deposit_email_user_cash_to_deposit_content',
				'label' => '구매자에게 보낼 메일 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'deposit_note_admin_cash_to_deposit_title',
				'label' => '최고관리자에게 보낼 쪽지 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'deposit_note_admin_cash_to_deposit_content',
				'label' => '최고관리자에게 보낼 쪽지 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'deposit_note_user_cash_to_deposit_title',
				'label' => '구매자에게 보낼 쪽지 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'deposit_note_user_cash_to_deposit_content',
				'label' => '구매자에게 보낼 쪽지 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'deposit_sms_admin_cash_to_deposit_content',
				'label' => '최고관리자에게 보낼 문자 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'deposit_sms_user_cash_to_deposit_content',
				'label' => '구매자에게 보낼 문자 내용',
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
				'deposit_email_admin_cash_to_deposit', 'deposit_email_user_cash_to_deposit',
				'deposit_email_alluser_cash_to_deposit', 'deposit_note_admin_cash_to_deposit',
				'deposit_note_user_cash_to_deposit', 'deposit_sms_admin_cash_to_deposit',
				'deposit_sms_user_cash_to_deposit', 'deposit_sms_alluser_cash_to_deposit',
				'deposit_email_admin_cash_to_deposit_title', 'deposit_email_admin_cash_to_deposit_content',
				'deposit_email_user_cash_to_deposit_title', 'deposit_email_user_cash_to_deposit_content',
				'deposit_note_admin_cash_to_deposit_title', 'deposit_note_admin_cash_to_deposit_content',
				'deposit_note_user_cash_to_deposit_title', 'deposit_note_user_cash_to_deposit_content',
				'deposit_sms_admin_cash_to_deposit_content', 'deposit_sms_user_cash_to_deposit_content'
			);
			foreach ($array as $value) {
				$savedata[$value] = $this->input->post($value, null, '');
			}

			$this->Config_model->save($savedata);
			$view['view']['alert_message'] = '카드/이체 등으로 예치금 구매시 설정이 저장되었습니다';
		}

		$getdata = $this->Config_model->get_all_meta();
		$view['view']['data'] = $getdata;

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 어드민 레이아웃을 정의합니다
		 */
		$layoutconfig = array('layout' => 'layout', 'skin' => 'cash_to_deposit');
		$view['layout'] = $this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}

	/**
	 * 메일/쪽지발송양식>무통장입금으로 예치금 구매시 양식 페이지입니다
	 */
	public function bank_to_deposit()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_deposit_emailform_bank_to_deposit';
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
				'field' => 'deposit_email_admin_bank_to_deposit',
				'label' => '최고관리자에게메일발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_email_user_bank_to_deposit',
				'label' => '구매자에게메일발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_email_alluser_bank_to_deposit',
				'label' => '구매자에게메일발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_note_admin_bank_to_deposit',
				'label' => '최고관리자에게쪽지발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_note_user_bank_to_deposit',
				'label' => '구매자에게쪽지발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_sms_admin_bank_to_deposit',
				'label' => '최고관리자에게문자(SMS)발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_sms_user_bank_to_deposit',
				'label' => '구매자에게문자(SMS)발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_sms_alluser_bank_to_deposit',
				'label' => '구매자에게문자(SMS)발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_email_admin_bank_to_deposit_title',
				'label' => '최고관리자에게 보낼 메일 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'deposit_email_admin_bank_to_deposit_content',
				'label' => '최고관리자에게 보낼 메일 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'deposit_email_user_bank_to_deposit_title',
				'label' => '구매자에게 보낼 메일 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'deposit_email_user_bank_to_deposit_content',
				'label' => '구매자에게 보낼 메일 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'deposit_note_admin_bank_to_deposit_title',
				'label' => '최고관리자에게 보낼 쪽지 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'deposit_note_admin_bank_to_deposit_content',
				'label' => '최고관리자에게 보낼 쪽지 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'deposit_note_user_bank_to_deposit_title',
				'label' => '구매자에게 보낼 쪽지 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'deposit_note_user_bank_to_deposit_content',
				'label' => '구매자에게 보낼 쪽지 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'deposit_sms_admin_bank_to_deposit_content',
				'label' => '최고관리자에게 보낼 문자 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'deposit_sms_user_bank_to_deposit_content',
				'label' => '구매자에게 보낼 문자 내용',
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
				'deposit_email_admin_bank_to_deposit', 'deposit_email_user_bank_to_deposit',
				'deposit_email_alluser_bank_to_deposit', 'deposit_note_admin_bank_to_deposit',
				'deposit_note_user_bank_to_deposit', 'deposit_sms_admin_bank_to_deposit',
				'deposit_sms_user_bank_to_deposit', 'deposit_sms_alluser_bank_to_deposit',
				'deposit_email_admin_bank_to_deposit_title', 'deposit_email_admin_bank_to_deposit_content',
				'deposit_email_user_bank_to_deposit_title', 'deposit_email_user_bank_to_deposit_content',
				'deposit_note_admin_bank_to_deposit_title', 'deposit_note_admin_bank_to_deposit_content',
				'deposit_note_user_bank_to_deposit_title', 'deposit_note_user_bank_to_deposit_content',
				'deposit_sms_admin_bank_to_deposit_content', 'deposit_sms_user_bank_to_deposit_content'
			);
			foreach ($array as $value) {
				$savedata[$value] = $this->input->post($value, null, '');
			}

			$this->Config_model->save($savedata);
			$view['view']['alert_message'] = '무통장입금으로 예치금 구매시 설정이 저장되었습니다';
		}

		$getdata = $this->Config_model->get_all_meta();
		$view['view']['data'] = $getdata;

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 어드민 레이아웃을 정의합니다
		 */
		$layoutconfig = array('layout' => 'layout', 'skin' => 'bank_to_deposit');
		$view['layout'] = $this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}

	/**
	 * 메일/쪽지발송양식>무통장입금으로 예치금 구매 완료시 양식 페이지입니다
	 */
	public function approve_bank_to_deposit()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_deposit_emailform_approve_bank_to_deposit';
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
				'field' => 'deposit_email_admin_approve_bank_to_deposit',
				'label' => '최고관리자에게메일발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_email_user_approve_bank_to_deposit',
				'label' => '구매자에게메일발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_email_alluser_approve_bank_to_deposit',
				'label' => '구매자에게메일발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_note_admin_approve_bank_to_deposit',
				'label' => '최고관리자에게쪽지발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_note_user_approve_bank_to_deposit',
				'label' => '구매자에게쪽지발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_sms_admin_approve_bank_to_deposit',
				'label' => '최고관리자에게문자(SMS)발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_sms_user_approve_bank_to_deposit',
				'label' => '구매자에게문자(SMS)발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_sms_alluser_approve_bank_to_deposit',
				'label' => '구매자에게문자(SMS)발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_email_admin_approve_bank_to_deposit_title',
				'label' => '최고관리자에게 보낼 메일 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'deposit_email_admin_approve_bank_to_deposit_content',
				'label' => '최고관리자에게 보낼 메일 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'deposit_email_user_approve_bank_to_deposit_title',
				'label' => '구매자에게 보낼 메일 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'deposit_email_user_approve_bank_to_deposit_content',
				'label' => '구매자에게 보낼 메일 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'deposit_note_admin_approve_bank_to_deposit_title',
				'label' => '최고관리자에게 보낼 쪽지 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'deposit_note_admin_approve_bank_to_deposit_content',
				'label' => '최고관리자에게 보낼 쪽지 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'deposit_note_user_approve_bank_to_deposit_title',
				'label' => '구매자에게 보낼 쪽지 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'deposit_note_user_approve_bank_to_deposit_content',
				'label' => '구매자에게 보낼 쪽지 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'deposit_sms_admin_approve_bank_to_deposit_content',
				'label' => '최고관리자에게 보낼 문자 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'deposit_sms_user_approve_bank_to_deposit_content',
				'label' => '구매자에게 보낼 문자 내용',
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
				'deposit_email_admin_approve_bank_to_deposit', 'deposit_email_user_approve_bank_to_deposit',
				'deposit_email_alluser_approve_bank_to_deposit', 'deposit_note_admin_approve_bank_to_deposit',
				'deposit_note_user_approve_bank_to_deposit', 'deposit_sms_admin_approve_bank_to_deposit',
				'deposit_sms_user_approve_bank_to_deposit', 'deposit_sms_alluser_approve_bank_to_deposit',
				'deposit_email_admin_approve_bank_to_deposit_title', 'deposit_email_admin_approve_bank_to_deposit_content',
				'deposit_email_user_approve_bank_to_deposit_title', 'deposit_email_user_approve_bank_to_deposit_content',
				'deposit_note_admin_approve_bank_to_deposit_title', 'deposit_note_admin_approve_bank_to_deposit_content',
				'deposit_note_user_approve_bank_to_deposit_title', 'deposit_note_user_approve_bank_to_deposit_content',
				'deposit_sms_admin_approve_bank_to_deposit_content', 'deposit_sms_user_approve_bank_to_deposit_content'
			);
			foreach ($array as $value) {
				$savedata[$value] = $this->input->post($value, null, '');
			}

			$this->Config_model->save($savedata);
			$view['view']['alert_message'] = '무통장입금으로 예치금 구매시 설정이 저장되었습니다';
		}

		$getdata = $this->Config_model->get_all_meta();
		$view['view']['data'] = $getdata;

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 어드민 레이아웃을 정의합니다
		 */
		$layoutconfig = array('layout' => 'layout', 'skin' => 'approve_bank_to_deposit');
		$view['layout'] = $this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}

	/**
	 * 메일/쪽지발송양식>포인트로 예치금 구매시 양식 페이지입니다
	 */
	public function point_to_deposit()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_deposit_emailform_point_to_deposit';
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
				'field' => 'deposit_email_admin_point_to_deposit',
				'label' => '최고관리자에게메일발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_email_user_point_to_deposit',
				'label' => '구매자에게메일발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_email_alluser_point_to_deposit',
				'label' => '구매자에게메일발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_note_admin_point_to_deposit',
				'label' => '최고관리자에게쪽지발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_note_user_point_to_deposit',
				'label' => '구매자에게쪽지발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_sms_admin_point_to_deposit',
				'label' => '최고관리자에게문자(SMS)발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_sms_user_point_to_deposit',
				'label' => '구매자에게문자(SMS)발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_sms_alluser_point_to_deposit',
				'label' => '구매자에게문자(SMS)발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_email_admin_point_to_deposit_title',
				'label' => '최고관리자에게 보낼 메일 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'deposit_email_admin_point_to_deposit_content',
				'label' => '최고관리자에게 보낼 메일 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'deposit_email_user_point_to_deposit_title',
				'label' => '구매자에게 보낼 메일 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'deposit_email_user_point_to_deposit_content',
				'label' => '구매자에게 보낼 메일 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'deposit_note_admin_point_to_deposit_title',
				'label' => '최고관리자에게 보낼 쪽지 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'deposit_note_admin_point_to_deposit_content',
				'label' => '최고관리자에게 보낼 쪽지 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'deposit_note_user_point_to_deposit_title',
				'label' => '구매자에게 보낼 쪽지 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'deposit_note_user_point_to_deposit_content',
				'label' => '구매자에게 보낼 쪽지 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'deposit_sms_admin_point_to_deposit_content',
				'label' => '최고관리자에게 보낼 문자 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'deposit_sms_user_point_to_deposit_content',
				'label' => '구매자에게 보낼 문자 내용',
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
				'deposit_email_admin_point_to_deposit', 'deposit_email_user_point_to_deposit',
				'deposit_email_alluser_point_to_deposit', 'deposit_note_admin_point_to_deposit',
				'deposit_note_user_point_to_deposit', 'deposit_sms_admin_point_to_deposit',
				'deposit_sms_user_point_to_deposit', 'deposit_sms_alluser_point_to_deposit',
				'deposit_email_admin_point_to_deposit_title', 'deposit_email_admin_point_to_deposit_content',
				'deposit_email_user_point_to_deposit_title', 'deposit_email_user_point_to_deposit_content',
				'deposit_note_admin_point_to_deposit_title', 'deposit_note_admin_point_to_deposit_content',
				'deposit_note_user_point_to_deposit_title', 'deposit_note_user_point_to_deposit_content',
				'deposit_sms_admin_point_to_deposit_content', 'deposit_sms_user_point_to_deposit_content'
			);
			foreach ($array as $value) {
				$savedata[$value] = $this->input->post($value, null, '');
			}

			$this->Config_model->save($savedata);
			$view['view']['alert_message'] = '무통장입금으로 예치금 구매시 설정이 저장되었습니다';
		}

		$getdata = $this->Config_model->get_all_meta();
		$view['view']['data'] = $getdata;

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 어드민 레이아웃을 정의합니다
		 */
		$layoutconfig = array('layout' => 'layout', 'skin' => 'point_to_deposit');
		$view['layout'] = $this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}

	/**
	 * 메일/쪽지발송양식>예치금으로 포인트 구매시 양식 페이지입니다
	 */
	public function deposit_to_point()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_deposit_emailform_deposit_to_point';
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
				'field' => 'deposit_email_admin_deposit_to_point',
				'label' => '최고관리자에게메일발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_email_user_deposit_to_point',
				'label' => '구매자에게메일발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_email_alluser_deposit_to_point',
				'label' => '구매자에게메일발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_note_admin_deposit_to_point',
				'label' => '최고관리자에게쪽지발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_note_user_deposit_to_point',
				'label' => '구매자에게쪽지발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_sms_admin_deposit_to_point',
				'label' => '최고관리자에게문자(SMS)발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_sms_user_deposit_to_point',
				'label' => '구매자에게문자(SMS)발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_sms_alluser_deposit_to_point',
				'label' => '구매자에게문자(SMS)발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_email_admin_deposit_to_point_title',
				'label' => '최고관리자에게 보낼 메일 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'deposit_email_admin_deposit_to_point_content',
				'label' => '최고관리자에게 보낼 메일 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'deposit_email_user_deposit_to_point_title',
				'label' => '구매자에게 보낼 메일 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'deposit_email_user_deposit_to_point_content',
				'label' => '구매자에게 보낼 메일 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'deposit_note_admin_deposit_to_point_title',
				'label' => '최고관리자에게 보낼 쪽지 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'deposit_note_admin_deposit_to_point_content',
				'label' => '최고관리자에게 보낼 쪽지 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'deposit_note_user_deposit_to_point_title',
				'label' => '구매자에게 보낼 쪽지 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'deposit_note_user_deposit_to_point_content',
				'label' => '구매자에게 보낼 쪽지 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'deposit_sms_admin_deposit_to_point_content',
				'label' => '최고관리자에게 보낼 문자 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'deposit_sms_user_deposit_to_point_content',
				'label' => '구매자에게 보낼 문자 내용',
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
				'deposit_email_admin_deposit_to_point', 'deposit_email_user_deposit_to_point',
				'deposit_email_alluser_deposit_to_point', 'deposit_note_admin_deposit_to_point',
				'deposit_note_user_deposit_to_point', 'deposit_sms_admin_deposit_to_point',
				'deposit_sms_user_deposit_to_point', 'deposit_sms_alluser_deposit_to_point',
				'deposit_email_admin_deposit_to_point_title', 'deposit_email_admin_deposit_to_point_content',
				'deposit_email_user_deposit_to_point_title', 'deposit_email_user_deposit_to_point_content',
				'deposit_note_admin_deposit_to_point_title', 'deposit_note_admin_deposit_to_point_content',
				'deposit_note_user_deposit_to_point_title', 'deposit_note_user_deposit_to_point_content',
				'deposit_sms_admin_deposit_to_point_content', 'deposit_sms_user_deposit_to_point_content'
			);
			foreach ($array as $value) {
				$savedata[$value] = $this->input->post($value, null, '');
			}

			$this->Config_model->save($savedata);
			$view['view']['alert_message'] = '무통장입금으로 예치금 구매시 설정이 저장되었습니다';
		}

		$getdata = $this->Config_model->get_all_meta();
		$view['view']['data'] = $getdata;

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 어드민 레이아웃을 정의합니다
		 */
		$layoutconfig = array('layout' => 'layout', 'skin' => 'deposit_to_point');
		$view['layout'] = $this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}
}
