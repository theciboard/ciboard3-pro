<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Testemail class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 관리자>환경설정>메일발송테스트 controller 입니다.
 */
class Testemail extends CB_Controller
{

	/**
	 * 관리자 페이지 상의 현재 디렉토리입니다
	 * 페이지 이동시 필요한 정보입니다
	 */
	public $pagedir = 'config/testemail';

	/**
	 * 모델을 로딩합니다
	 */
	protected $models = array();

	/**
	 * 이 컨트롤러의 메인 모델 이름입니다
	 */
	protected $modelname = '';

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
	}

	/**
	 * 테스트 이메일을 보내는 페이지입니다
	 */
	public function index()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_config_testemail_index';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		$getdata['webmaster_email'] = $this->cbconfig->item('webmaster_email');
		$getdata['webmaster_name'] = $this->cbconfig->item('webmaster_name');
		$getdata['site_title'] = $this->cbconfig->item('site_title');

		/**
		 * Validation 라이브러리를 가져옵니다
		 */
		$this->load->library('form_validation');

		/**
		 * 전송된 데이터의 유효성을 체크합니다
		 */
		$config = array(
			array(
				'field' => 'recv_email',
				'label' => '받는이 이메일 주소',
				'rules' => 'trim|required|valid_email',
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

			$this->load->library('email');
			$this->email->from(element('webmaster_email', $getdata), element('webmaster_name', $getdata));
			$this->email->to($this->input->post('recv_email'));

			$this->email->subject('이메일 발송 테스트입니다');
			$emailform['emailform'] = $getdata;
			$message = $this->load->view('admin/' . ADMIN_SKIN . '/' . $this->pagedir . '/email_form', $emailform, true);
			$this->email->message($message);

			if ($this->email->send() === false) {
				$view['view']['alert_message'] = '이메일을 발송하지 못하였습니다. 메일 설정을 확인하여주세요';
			} else {
				$view['view']['alert_message'] = '이메일을 발송하였습니다';
			}
			//echo $this->email->print_debugger();

		}

		$view['view']['data'] = $getdata;

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 어드민 레이아웃을 정의합니다
		 */
		$layoutconfig = array('layout' => 'layout', 'skin' => 'index');
		$view['layout'] = $this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}
}
