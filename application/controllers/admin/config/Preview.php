<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Preview class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 관리자>환경설정>Preview controller 입니다.
 */
class Preview extends CB_Controller
{

	/**
	 * 관리자 페이지 상의 현재 디렉토리입니다
	 * 페이지 이동시 필요한 정보입니다
	 */
	public $pagedir = 'config/preview';

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
	}

	/**
	 * Preview 페이지입니다
	 */
	public function preview($pagetype)
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_config_preview_preview';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		$pagename = array(
			'main' => '메인페이지',
			'board' => '게시판',
			'group' => '게시판그룹',
			'document' => '일반문서페이지',
			'faq' => 'FAQ',
			'register' => '회원가입',
			'findaccount' => '아이디패스워드찾기',
			'login' => '로그인',
			'mypage' => '마이페이지',
			'currentvisitor' => '현재접속자',
			'search' => '검색페이지',
			'tag' => '태그페이지',
			'note' => '쪽지',
			'profile' => '프로필',
			'formmail' => '폼메일',
			'notification' => '알림페이지',
			'helptool' => '헬프툴',
		);

		if ( ! element($pagetype, $pagename)) {
			alert('잘못된 접근입니다');
		}

		$previewurl = '';
		$pageurl = '';
		$use_sidebar_option = true;

		if ($pagetype === 'main'
			OR $pagetype === 'mypage'
			OR $pagetype === 'currentvisitor'
			OR $pagetype === 'notification'
			OR $pagetype === 'login'
			OR $pagetype === 'findaccount') {

			$pageurl = $pagetype;

		}
		if ($pagetype === 'board') {
			$this->load->model('Post_model');
			$where = array('post_del' => 0);
			$latest = $this->Post_model->get('', '', $where, 1, '', 'post_id', 'desc');
			$val = element(0, $latest);
			if (empty($val)) {
				alert_close('최소 1개 이상의 게시물이 작성된 후에 미리보기가 가능합니다');
			}
			$pageurl = 'board_post/post/' . element('post_id', $val);
		}
		if ($pagetype === 'group') {
			$this->load->model('Board_group_model');
			$val = $this->Board_group_model->get_one();

			if (empty($val)) {
				alert_close('최소 1개 이상의 그룹이 생성된 후에 미리보기가 가능합니다');
			}
			$pageurl = 'group/index/' . element('bgr_key', $val);
		}
		if ($pagetype === 'document') {
			$this->load->model('Document_model');
			$latest = $this->Document_model->get('', '', '', 1, '', 'doc_id', 'desc');
			$val = element(0, $latest);
			if (empty($val)) {
				alert_close('최소 1개 이상의 일반문서가 작성된 후에 미리보기가 가능합니다');
			}
			$pageurl = 'document/index/' . element('doc_key', $val);
		}
		if ($pagetype === 'faq') {
			$this->load->model('Faq_group_model');
			$latest = $this->Faq_group_model->get('', '', '', 1, '', 'fgr_id', 'desc');
			$val = element(0, $latest);
			if (empty($val)) {
				alert_close('최소 1개 이상의 FAQ가 작성된 후에 미리보기가 가능합니다');
			}
			$pageurl = 'faq/index/' . element('fgr_key', $val);
		}
		if ($pagetype === 'search') {
			$pageurl = 'search/?skeyword=검색&';
		}
		if ($pagetype === 'tag') {
			$this->load->model('Post_tag_model');
			$latest = $this->Post_tag_model->get('', '', '', 1, '', 'pta_id', 'desc');
			$val = element(0, $latest);
			if (empty($val)) {
				alert_close('최소 1개 이상의 태그가 작성된 후에 미리보기가 가능합니다');
			}
			$pageurl = 'tags/?tag=' . element('pta_tag', $val) . '&';
		}
		if ($pagetype === 'note') {
			$use_sidebar_option = false;
			$this->load->model('Note_model');
			$where = array(
				'recv_mem_id' => $this->member->item('mem_id'),
				'nte_type' => 1,
			);
			$latest = $this->Note_model->get('', '', $where, 1, '', 'nte_id', 'desc');
			$val = element(0, $latest);
			if (empty($val)) {
				alert_close('최소 1개 이상의 쪽지를 수신한 후에 미리보기가 가능합니다');
			}
			$pageurl = 'note/view/recv/' . element('nte_id', $val);
		}
		if ($pagetype === 'profile') {
			$use_sidebar_option = false;
			$pageurl = 'profile/index/' . $this->member->item('mem_userid');
		}
		if ($pagetype === 'formmail') {
			$use_sidebar_option = false;
			$pageurl = 'formmail/write/' . $this->member->item('mem_userid');
		}
		if ($pagetype === 'helptool') {
			$use_sidebar_option = false;
			$pageurl = 'helptool/emoticon';
		}
		if ($pagetype === 'register') {
			$pageurl = 'register/form';
		}

		$previewurl = admin_url('preview/adminshow/' . $pageurl);
		$previewurl .= '?layout=' . $this->input->get('layout', null, '') . '&sidebar=' . $this->input->get('sidebar', null, '') . '&skin=' . $this->input->get('skin', null, '') . '&is_mobile=' . $this->input->get('is_mobile', null, '');

		$view['view']['pagetype'] = $pagetype;
		$view['view']['previewurl'] = $previewurl;
		$view['view']['use_sidebar_option'] = $use_sidebar_option;
		$view['view']['pagename'] = element($pagetype, $pagename);
		$view['view']['layout_option'] = get_skin_name(
			'_layout',
			$this->input->get('layout', null, ''),
			'기본설정따름'
		);
		$view['view']['skin_option'] = get_skin_name(
			$pagetype,
			$this->input->get('skin', null, ''),
			'기본설정따름'
		);
		$view['view']['mobile_layout_option'] = get_skin_name(
			'_layout',
			$this->input->get('mobile_layout', null, ''),
			'기본설정따름'
		);
		$view['view']['mobile_skin_option'] = get_skin_name(
			$pagetype,
			$this->input->get('mobile_skin', null, ''),
			'기본설정따름'
		);

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 어드민 레이아웃을 정의합니다
		 */
		$layoutconfig = array('layout' => 'layout_popup', 'skin' => 'index');
		$view['layout'] = $this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}
}
