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
 * 관리자>컨텐츠몰관리>메일/쪽지발송양식 controller 입니다.
 */
class Emailform extends CB_Controller
{

	/**
	 * 관리자 페이지 상의 현재 디렉토리입니다
	 * 페이지 이동시 필요한 정보입니다
	 */
	public $pagedir = 'cmall/emailform';

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
		$this->load->library(array('querystring', 'cmalllib'));
	}


	/**
	 * 메일/쪽지발송양식 페이지입니다
	 */
	public function index()
	{
		$this->cash_to_contents();
	}

	/**
	 * 메일/쪽지발송양식>카드/이체 등으로 컨텐츠 구매시 양식 페이지입니다
	 */
	public function cash_to_contents()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_cmall_emailform_cash_to_contents';
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
				'field' => 'cmall_email_admin_cash_to_contents',
				'label' => '최고관리자에게메일발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'cmall_email_user_cash_to_contents',
				'label' => '회원에게메일발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'cmall_email_alluser_cash_to_contents',
				'label' => '회원에게메일발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'cmall_note_admin_cash_to_contents',
				'label' => '최고관리자에게쪽지발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'cmall_note_user_cash_to_contents',
				'label' => '회원에게쪽지발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'cmall_sms_admin_cash_to_contents',
				'label' => '최고관리자에게문자(SMS)발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'cmall_sms_user_cash_to_contents',
				'label' => '회원에게문자(SMS)발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'cmall_sms_alluser_cash_to_contents',
				'label' => '회원에게문자(SMS)발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'cmall_email_admin_cash_to_contents_title',
				'label' => '최고관리자에게 보낼 메일 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'cmall_email_user_cash_to_contents_title',
				'label' => '회원에게 보낼 메일 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'cmall_note_admin_cash_to_contents_title',
				'label' => '최고관리자에게 보낼 쪽지 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'cmall_note_admin_cash_to_contents_content',
				'label' => '최고관리자에게 보낼 쪽지 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'cmall_note_user_cash_to_contents_title',
				'label' => '회원에게 보낼 쪽지 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'cmall_note_user_cash_to_contents_content',
				'label' => '회원에게 보낼 쪽지 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'cmall_sms_admin_cash_to_contents_content',
				'label' => '최고관리자에게 보낼 문자 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'cmall_sms_user_cash_to_contents_content',
				'label' => '회원에게 보낼 문자 내용',
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
				'cmall_email_admin_cash_to_contents', 'cmall_email_user_cash_to_contents',
				'cmall_email_alluser_cash_to_contents', 'cmall_note_admin_cash_to_contents',
				'cmall_note_user_cash_to_contents', 'cmall_sms_admin_cash_to_contents',
				'cmall_sms_user_cash_to_contents', 'cmall_sms_alluser_cash_to_contents',
				'cmall_email_admin_cash_to_contents_title', 'cmall_email_user_cash_to_contents_title',
				'cmall_note_admin_cash_to_contents_title',
				'cmall_note_admin_cash_to_contents_content', 'cmall_note_user_cash_to_contents_title',
				'cmall_note_user_cash_to_contents_content',
				'cmall_sms_admin_cash_to_contents_content',
				'cmall_sms_user_cash_to_contents_content'
			);
			foreach ($array as $value) {
				$savedata[$value] = $this->input->post($value, null, '');
			}

			$this->Config_model->save($savedata);
			$view['view']['alert_message'] = '카드/이체 등으로 컨텐츠 구매시 설정이 저장되었습니다';
		}

		$getdata = $this->Config_model->get_all_meta();
		$view['view']['data'] = $getdata;

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 어드민 레이아웃을 정의합니다
		 */
		$layoutconfig = array('layout' => 'layout', 'skin' => 'cash_to_contents');
		$view['layout'] = $this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}

	/**
	 * 메일/쪽지발송양식>무통장입금으로 컨텐츠구매요청시 양식 페이지입니다
	 */
	public function bank_to_contents()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_cmall_emailform_bank_to_contents';
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
				'field' => 'cmall_email_admin_bank_to_contents',
				'label' => '최고관리자에게메일발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'cmall_email_user_bank_to_contents',
				'label' => '회원에게메일발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'cmall_email_alluser_bank_to_contents',
				'label' => '회원에게메일발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'cmall_note_admin_bank_to_contents',
				'label' => '최고관리자에게쪽지발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'cmall_note_user_bank_to_contents',
				'label' => '회원에게쪽지발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'cmall_sms_admin_bank_to_contents',
				'label' => '최고관리자에게문자(SMS)발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'cmall_sms_user_bank_to_contents',
				'label' => '회원에게문자(SMS)발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'cmall_sms_alluser_bank_to_contents',
				'label' => '회원에게문자(SMS)발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'cmall_email_admin_bank_to_contents_title',
				'label' => '최고관리자에게 보낼 메일 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'cmall_email_user_bank_to_contents_title',
				'label' => '회원에게 보낼 메일 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'cmall_note_admin_bank_to_contents_title',
				'label' => '최고관리자에게 보낼 쪽지 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'cmall_note_admin_bank_to_contents_content',
				'label' => '최고관리자에게 보낼 쪽지 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'cmall_note_user_bank_to_contents_title',
				'label' => '회원에게 보낼 쪽지 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'cmall_note_user_bank_to_contents_content',
				'label' => '회원에게 보낼 쪽지 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'cmall_sms_admin_bank_to_contents_content',
				'label' => '최고관리자에게 보낼 문자 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'cmall_sms_user_bank_to_contents_content',
				'label' => '회원에게 보낼 문자 내용',
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
				'cmall_email_admin_bank_to_contents', 'cmall_email_user_bank_to_contents',
				'cmall_email_alluser_bank_to_contents', 'cmall_note_admin_bank_to_contents',
				'cmall_note_user_bank_to_contents', 'cmall_sms_admin_bank_to_contents',
				'cmall_sms_user_bank_to_contents', 'cmall_sms_alluser_bank_to_contents',
				'cmall_email_admin_bank_to_contents_title', 'cmall_email_user_bank_to_contents_title',
				'cmall_note_admin_bank_to_contents_title',
				'cmall_note_admin_bank_to_contents_content',
				'cmall_note_user_bank_to_contents_title', 'cmall_note_user_bank_to_contents_content',
				'cmall_sms_admin_bank_to_contents_content',
				'cmall_sms_user_bank_to_contents_content'
			);
			foreach ($array as $value) {
				$savedata[$value] = $this->input->post($value, null, '');
			}

			$this->Config_model->save($savedata);
			$view['view']['alert_message'] = '무통장입금으로 컨텐츠구매요청시 설정이 저장되었습니다';
		}

		$getdata = $this->Config_model->get_all_meta();
		$view['view']['data'] = $getdata;

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 어드민 레이아웃을 정의합니다
		 */
		$layoutconfig = array('layout' => 'layout', 'skin' => 'bank_to_contents');
		$view['layout'] = $this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}

	/**
	 * 메일/쪽지발송양식>무통장입금 완료처리시 양식 페이지입니다
	 */
	public function approve_bank_to_contents()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_cmall_emailform_approve_bank_to_contents';
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
				'field' => 'cmall_email_admin_approve_bank_to_contents',
				'label' => '최고관리자에게메일발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'cmall_email_user_approve_bank_to_contents',
				'label' => '회원에게메일발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'cmall_email_alluser_approve_bank_to_contents',
				'label' => '회원에게메일발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'cmall_note_admin_approve_bank_to_contents',
				'label' => '최고관리자에게쪽지발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'cmall_note_user_approve_bank_to_contents',
				'label' => '회원에게쪽지발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'cmall_sms_admin_approve_bank_to_contents',
				'label' => '최고관리자에게문자(SMS)발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'cmall_sms_user_approve_bank_to_contents',
				'label' => '회원에게문자(SMS)발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'cmall_sms_alluser_approve_bank_to_contents',
				'label' => '회원에게문자(SMS)발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'cmall_email_admin_approve_bank_to_contents_title',
				'label' => '최고관리자에게 보낼 메일 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'cmall_email_user_approve_bank_to_contents_title',
				'label' => '회원에게 보낼 메일 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'cmall_note_admin_approve_bank_to_contents_title',
				'label' => '최고관리자에게 보낼 쪽지 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'cmall_note_admin_approve_bank_to_contents_content',
				'label' => '최고관리자에게 보낼 쪽지 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'cmall_note_user_approve_bank_to_contents_title',
				'label' => '회원에게 보낼 쪽지 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'cmall_note_user_approve_bank_to_contents_content',
				'label' => '회원에게 보낼 쪽지 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'cmall_sms_admin_approve_bank_to_contents_content',
				'label' => '최고관리자에게 보낼 문자 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'cmall_sms_user_approve_bank_to_contents_content',
				'label' => '회원에게 보낼 문자 내용',
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
				'cmall_email_admin_approve_bank_to_contents', 'cmall_email_user_approve_bank_to_contents',
				'cmall_email_alluser_approve_bank_to_contents', 'cmall_note_admin_approve_bank_to_contents',
				'cmall_note_user_approve_bank_to_contents', 'cmall_sms_admin_approve_bank_to_contents',
				'cmall_sms_user_approve_bank_to_contents', 'cmall_sms_alluser_approve_bank_to_contents',
				'cmall_email_admin_approve_bank_to_contents_title', 'cmall_email_user_approve_bank_to_contents_title',
				'cmall_note_admin_approve_bank_to_contents_title', 'cmall_note_admin_approve_bank_to_contents_content',
				'cmall_note_user_approve_bank_to_contents_title', 'cmall_note_user_approve_bank_to_contents_content',
				'cmall_sms_admin_approve_bank_to_contents_content', 'cmall_sms_user_approve_bank_to_contents_content'
			);
			foreach ($array as $value) {
				$savedata[$value] = $this->input->post($value, null, '');
			}

			$this->Config_model->save($savedata);
			$view['view']['alert_message'] = '무통장입금 완료처리시 설정이 저장되었습니다';
		}

		$getdata = $this->Config_model->get_all_meta();
		$view['view']['data'] = $getdata;

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 어드민 레이아웃을 정의합니다
		 */
		$layoutconfig = array('layout' => 'layout', 'skin' => 'approve_bank_to_contents');
		$view['layout'] = $this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}

	/**
	 * 메일/쪽지발송양식>상품후기 작성시 양식 페이지입니다
	 */
	public function write_product_review()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_cmall_emailform_write_product_review';
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
				'field' => 'cmall_email_admin_write_product_review',
				'label' => '최고관리자에게메일발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'cmall_email_user_write_product_review',
				'label' => '회원에게메일발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'cmall_email_alluser_write_product_review',
				'label' => '회원에게메일발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'cmall_note_admin_write_product_review',
				'label' => '최고관리자에게쪽지발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'cmall_note_user_write_product_review',
				'label' => '회원에게쪽지발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'cmall_sms_admin_write_product_review',
				'label' => '최고관리자에게문자(SMS)발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'cmall_sms_user_write_product_review',
				'label' => '회원에게문자(SMS)발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'cmall_sms_alluser_write_product_review',
				'label' => '회원에게문자(SMS)발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'cmall_email_admin_write_product_review_title',
				'label' => '최고관리자에게 보낼 메일 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'cmall_email_admin_write_product_review_content',
				'label' => '최고관리자에게 보낼 메일 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'cmall_email_user_write_product_review_title',
				'label' => '회원에게 보낼 메일 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'cmall_email_user_write_product_review_content',
				'label' => '회원에게 보낼 메일 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'cmall_note_admin_write_product_review_title',
				'label' => '최고관리자에게 보낼 쪽지 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'cmall_note_admin_write_product_review_content',
				'label' => '최고관리자에게 보낼 쪽지 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'cmall_note_user_write_product_review_title',
				'label' => '회원에게 보낼 쪽지 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'cmall_note_user_write_product_review_content',
				'label' => '회원에게 보낼 쪽지 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'cmall_sms_admin_write_product_review_content',
				'label' => '최고관리자에게 보낼 문자 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'cmall_sms_user_write_product_review_content',
				'label' => '회원에게 보낼 문자 내용',
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
				'cmall_email_admin_write_product_review', 'cmall_email_user_write_product_review', 'cmall_email_alluser_write_product_review', 'cmall_note_admin_write_product_review', 'cmall_note_user_write_product_review', 'cmall_sms_admin_write_product_review', 'cmall_sms_user_write_product_review', 'cmall_sms_alluser_write_product_review', 'cmall_email_admin_write_product_review_title', 'cmall_email_admin_write_product_review_content', 'cmall_email_user_write_product_review_title', 'cmall_email_user_write_product_review_content', 'cmall_note_admin_write_product_review_title', 'cmall_note_admin_write_product_review_content', 'cmall_note_user_write_product_review_title', 'cmall_note_user_write_product_review_content', 'cmall_sms_admin_write_product_review_content', 'cmall_sms_user_write_product_review_content'
			);
			foreach ($array as $value) {
				$savedata[$value] = $this->input->post($value, null, '');
			}

			$this->Config_model->save($savedata);
			$view['view']['alert_message'] = '상품후기 작성시 설정이 저장되었습니다';
		}

		$getdata = $this->Config_model->get_all_meta();
		$view['view']['data'] = $getdata;

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 어드민 레이아웃을 정의합니다
		 */
		$layoutconfig = array('layout' => 'layout', 'skin' => 'write_product_review');
		$view['layout'] = $this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}

	/**
	 * 메일/쪽지발송양식>상품문의 작성시 양식 페이지입니다
	 */
	public function write_product_qna()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_cmall_emailform_write_product_qna';
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
				'field' => 'cmall_email_admin_write_product_qna',
				'label' => '최고관리자에게메일발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'cmall_email_user_write_product_qna',
				'label' => '회원에게메일발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'cmall_note_admin_write_product_qna',
				'label' => '최고관리자에게쪽지발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'cmall_note_user_write_product_qna',
				'label' => '회원에게쪽지발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'cmall_sms_admin_write_product_qna',
				'label' => '최고관리자에게문자(SMS)발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'cmall_sms_user_write_product_qna',
				'label' => '회원에게문자(SMS)발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'cmall_email_admin_write_product_qna_title',
				'label' => '최고관리자에게 보낼 메일 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'cmall_email_admin_write_product_qna_content',
				'label' => '최고관리자에게 보낼 메일 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'cmall_email_user_write_product_qna_title',
				'label' => '회원에게 보낼 메일 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'cmall_email_user_write_product_qna_content',
				'label' => '회원에게 보낼 메일 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'cmall_note_admin_write_product_qna_title',
				'label' => '최고관리자에게 보낼 쪽지 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'cmall_note_admin_write_product_qna_content',
				'label' => '최고관리자에게 보낼 쪽지 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'cmall_note_user_write_product_qna_title',
				'label' => '회원에게 보낼 쪽지 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'cmall_note_user_write_product_qna_content',
				'label' => '회원에게 보낼 쪽지 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'cmall_sms_admin_write_product_qna_content',
				'label' => '최고관리자에게 보낼 문자 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'cmall_sms_user_write_product_qna_content',
				'label' => '회원에게 보낼 문자 내용',
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
				'cmall_email_admin_write_product_qna', 'cmall_email_user_write_product_qna',
				'cmall_note_admin_write_product_qna', 'cmall_note_user_write_product_qna',
				'cmall_sms_admin_write_product_qna', 'cmall_sms_user_write_product_qna',
				'cmall_email_admin_write_product_qna_title',
				'cmall_email_admin_write_product_qna_content',
				'cmall_email_user_write_product_qna_title', 'cmall_email_user_write_product_qna_content',
				'cmall_note_admin_write_product_qna_title',
				'cmall_note_admin_write_product_qna_content', 'cmall_note_user_write_product_qna_title',
				'cmall_note_user_write_product_qna_content',
				'cmall_sms_admin_write_product_qna_content',
				'cmall_sms_user_write_product_qna_content'
			);
			foreach ($array as $value) {
				$savedata[$value] = $this->input->post($value, null, '');
			}

			$this->Config_model->save($savedata);
			$view['view']['alert_message'] = '상품문의 작성시 설정이 저장되었습니다';
		}

		$getdata = $this->Config_model->get_all_meta();
		$view['view']['data'] = $getdata;

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 어드민 레이아웃을 정의합니다
		 */
		$layoutconfig = array('layout' => 'layout', 'skin' => 'write_product_qna');
		$view['layout'] = $this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}

	/**
	 * 메일/쪽지발송양식>상품문의 답변시 양식 페이지입니다
	 */
	public function write_product_qna_reply()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_cmall_emailform_write_product_qna_reply';
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
				'field' => 'cmall_email_admin_write_product_qna_reply',
				'label' => '최고관리자에게메일발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'cmall_email_user_write_product_qna_reply',
				'label' => '회원에게메일발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'cmall_note_admin_write_product_qna_reply',
				'label' => '최고관리자에게쪽지발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'cmall_note_user_write_product_qna_reply',
				'label' => '회원에게쪽지발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'cmall_sms_admin_write_product_qna_reply',
				'label' => '최고관리자에게문자(SMS)발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'cmall_sms_user_write_product_qna_reply',
				'label' => '회원에게문자(SMS)발송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'cmall_email_admin_write_product_qna_reply_title',
				'label' => '최고관리자에게 보낼 메일 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'cmall_email_admin_write_product_qna_reply_content',
				'label' => '최고관리자에게 보낼 메일 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'cmall_email_user_write_product_qna_reply_title',
				'label' => '회원에게 보낼 메일 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'cmall_email_user_write_product_qna_reply_content',
				'label' => '회원에게 보낼 메일 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'cmall_note_admin_write_product_qna_reply_title',
				'label' => '최고관리자에게 보낼 쪽지 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'cmall_note_admin_write_product_qna_reply_content',
				'label' => '최고관리자에게 보낼 쪽지 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'cmall_note_user_write_product_qna_reply_title',
				'label' => '회원에게 보낼 쪽지 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'cmall_note_user_write_product_qna_reply_content',
				'label' => '회원에게 보낼 쪽지 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'cmall_sms_admin_write_product_qna_reply_content',
				'label' => '최고관리자에게 보낼 문자 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'cmall_sms_user_write_product_qna_reply_content',
				'label' => '회원에게 보낼 문자 내용',
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
				'cmall_email_admin_write_product_qna_reply', 'cmall_email_user_write_product_qna_reply',
				'cmall_note_admin_write_product_qna_reply', 'cmall_note_user_write_product_qna_reply',
				'cmall_sms_admin_write_product_qna_reply', 'cmall_sms_user_write_product_qna_reply',
				'cmall_email_admin_write_product_qna_reply_title',
				'cmall_email_admin_write_product_qna_reply_content',
				'cmall_email_user_write_product_qna_reply_title',
				'cmall_email_user_write_product_qna_reply_content',
				'cmall_note_admin_write_product_qna_reply_title',
				'cmall_note_admin_write_product_qna_reply_content',
				'cmall_note_user_write_product_qna_reply_title',
				'cmall_note_user_write_product_qna_reply_content',
				'cmall_sms_admin_write_product_qna_reply_content',
				'cmall_sms_user_write_product_qna_reply_content'
			);
			foreach ($array as $value) {
				$savedata[$value] = $this->input->post($value, null, '');
			}

			$this->Config_model->save($savedata);
			$view['view']['alert_message'] = '상품문의 답변시 설정이 저장되었습니다';
		}

		$getdata = $this->Config_model->get_all_meta();
		$view['view']['data'] = $getdata;

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 어드민 레이아웃을 정의합니다
		 */
		$layoutconfig = array('layout' => 'layout', 'skin' => 'write_product_qna_reply');
		$view['layout'] = $this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}
}
