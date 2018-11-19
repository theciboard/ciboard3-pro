<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Formmail class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 다른 회원에게 폼메일 전송시 작동하는 controller 입니다.
 */
class Formmail extends CB_Controller
{

	/**
	 * 모델을 로딩합니다
	 */
	protected $models = array();

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
		$this->load->library(array('querystring'));
	}


	/**
	 * 폼메일 작성 페이지입니다
	 */
	public function write($userid = '')
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_formmail_write';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		/**
		 * 로그인이 필요한 페이지입니다
		 */
		required_user_login();

		if ($this->cbconfig->get_device_view_type() !== 'mobile'
			&& ! $this->cbconfig->item('use_sideview_email')) {
			alert_close('이메일 기능을 사용하지 않는 사이트입니다');
			return false;
		} elseif ($this->cbconfig->get_device_view_type() === 'mobile'
			&& ! $this->cbconfig->item('use_mobile_sideview_email')) {
			alert_close('모바일 버전에서는 이메일 기능을 사용하지 않는 사이트입니다');
			return false;
		} elseif ( ! $this->member->item('mem_receive_email')
			&& $this->member->is_admin() !== 'super') {
			alert_close('회원님은 메일수신에 체크하지 않으셨습니다. 회원정보 수정 페이지에 가셔서, 메일 수신에 체크하신 후에 사용이 가능합니다');
			return false;
		}

		if (empty($userid)) {
			alert_close('상대방이 지정되지 않았습니다');
			return false;
		}
		$view['view']['userid'] = $userid;

		$memberselect = 'mem_id, mem_denied, mem_receive_email, mem_nickname, mem_email';
		$view['view']['member'] = $member
			= $this->Member_model->get_by_userid($userid, $memberselect);

		if ( ! element('mem_id', $member)) {
			alert_close('존재하지 않는 회원 아이디입니다');
			return false;
		}
		if (element('mem_denied', $member)) {
			alert_close('탈퇴 또는 차단된 회원입니다');
			return false;
		}

		if ( ! element('mem_receive_email', $member) && $this->member->is_admin() !== 'super') {
			alert_close(html_escape(element('mem_nickname', $member)) . ' 님은 메일 수신을 원하지 않으십니다');
			return false;
		}
		$view['view']['use_dhtml'] = false;
		if ($this->cbconfig->item('use_formmail_dhtml')) {
			$view['view']['use_dhtml'] = true;
		}

		/**
		 * Validation 라이브러리를 가져옵니다
		 */
		$this->load->library('form_validation');

		/**
		 * 전송된 데이터의 유효성을 체크합니다
		 */
		$config = array(
			array(
				'field' => 'title',
				'label' => '제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'content',
				'label' => '내용',
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

			/**
			 * 레이아웃을 정의합니다
			 */
			$page_title = $this->cbconfig->item('site_meta_title_formmail');
			$meta_description = $this->cbconfig->item('site_meta_description_formmail');
			$meta_keywords = $this->cbconfig->item('site_meta_keywords_formmail');
			$meta_author = $this->cbconfig->item('site_meta_author_formmail');
			$page_name = $this->cbconfig->item('site_page_name_formmail');

			$layoutconfig = array(
				'path' => 'formmail',
				'layout' => 'layout_popup',
				'skin' => 'formmail',
				'layout_dir' => $this->cbconfig->item('layout_formmail'),
				'mobile_layout_dir' => $this->cbconfig->item('mobile_layout_formmail'),
				'skin_dir' => $this->cbconfig->item('skin_formmail'),
				'mobile_skin_dir' => $this->cbconfig->item('mobile_skin_formmail'),
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

		} else {

			// 이벤트가 존재하면 실행합니다
			$view['view']['event']['formruntrue'] = Events::trigger('formruntrue', $eventname);

			// 메일 발송
			$this->load->library('email');
			$this->email->from($this->member->item('mem_email'), $this->member->item('mem_nickname'));
			$this->email->to(element('mem_email', $member));

			$this->email->subject($this->input->post('title'));
			$content_type = $this->cbconfig->item('use_formmail_dhtml') ? 1 : 0;
			$this->email->message(display_html_content(
				$this->input->post('content'),
				$content_type,
				800
			));
			$this->email->send();

			alert_close(element('mem_nickname', $member) . ' 님에게 메일을 발송하였습니다. ');
		}
	}
}
