<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Cbconfigs class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 관리자>환경설정>레이아웃설정 controller 입니다.
 */
class Layoutskin extends CB_Controller
{

	/**
	 * 관리자 페이지 상의 현재 디렉토리입니다
	 * 페이지 이동시 필요한 정보입니다
	 */
	public $pagedir = 'config/layoutskin';

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
	protected $helpers = array('form', 'array');

	function __construct()
	{
		parent::__construct();
	}

	/**
	 * 환경설정>레이아웃설정 페이지입니다
	 */
	public function index()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_config_layoutskin_index';
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
				'field' => 'layout_default',
				'label' => '기본레이아웃',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'sidebar_default',
				'label' => '기본사이드바',
				'rules' => 'trim',
			),
			array(
				'field' => 'mobile_layout_default',
				'label' => '기본모바일레이아웃',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'mobile_sidebar_default',
				'label' => '기본모바일사이드바',
				'rules' => 'trim',
			),
			array(
				'field' => 'skin_default',
				'label' => '기본일반스킨',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'mobile_skin_default',
				'label' => '기본모바일스킨',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'layout_main',
				'label' => '메인페이지레이아웃',
				'rules' => 'trim',
			),
			array(
				'field' => 'sidebar_main',
				'label' => '메인페이지사이드바',
				'rules' => 'trim',
			),
			array(
				'field' => 'mobile_layout_main',
				'label' => '메인페이지모바일레이아웃',
				'rules' => 'trim',
			),
			array(
				'field' => 'mobile_sidebar_main',
				'label' => '메인페이지모바일사이드바',
				'rules' => 'trim',
			),
			array(
				'field' => 'skin_main',
				'label' => '메인페이지일반스킨',
				'rules' => 'trim',
			),
			array(
				'field' => 'mobile_skin_main',
				'label' => '메인페이지모바일스킨',
				'rules' => 'trim',
			),
			array(
				'field' => 'layout_board',
				'label' => '게시판레이아웃',
				'rules' => 'trim',
			),
			array(
				'field' => 'sidebar_board',
				'label' => '게시판사이드바',
				'rules' => 'trim',
			),
			array(
				'field' => 'mobile_layout_board',
				'label' => '게시판모바일레이아웃',
				'rules' => 'trim',
			),
			array(
				'field' => 'mobile_sidebar_board',
				'label' => '게시판모바일사이드바',
				'rules' => 'trim',
			),
			array(
				'field' => 'skin_board',
				'label' => '게시판일반스킨',
				'rules' => 'trim',
			),
			array(
				'field' => 'mobile_skin_board',
				'label' => '게시판모바일스킨',
				'rules' => 'trim',
			),
			array(
				'field' => 'layout_group',
				'label' => '게시판그룹레이아웃',
				'rules' => 'trim',
			),
			array(
				'field' => 'sidebar_group',
				'label' => '게시판그룹사이드바',
				'rules' => 'trim',
			),
			array(
				'field' => 'mobile_layout_group',
				'label' => '게시판그룹모바일레이아웃',
				'rules' => 'trim',
			),
			array(
				'field' => 'mobile_sidebar_group',
				'label' => '게시판그룹모바일사이드바',
				'rules' => 'trim',
			),
			array(
				'field' => 'skin_group',
				'label' => '게시판그룹일반스킨',
				'rules' => 'trim',
			),
			array(
				'field' => 'mobile_skin_group',
				'label' => '게시판그룹모바일스킨',
				'rules' => 'trim',
			),
			array(
				'field' => 'layout_document',
				'label' => '일반문서레이아웃',
				'rules' => 'trim',
			),
			array(
				'field' => 'sidebar_document',
				'label' => '일반문서사이드바',
				'rules' => 'trim',
			),
			array(
				'field' => 'mobile_layout_document',
				'label' => '일반문서모바일레이아웃',
				'rules' => 'trim',
			),
			array(
				'field' => 'mobile_sidebar_document',
				'label' => '일반문서모바일사이드바',
				'rules' => 'trim',
			),
			array(
				'field' => 'skin_document',
				'label' => '일반문서일반스킨',
				'rules' => 'trim',
			),
			array(
				'field' => 'mobile_skin_document',
				'label' => '일반문서모바일스킨',
				'rules' => 'trim',
			),
			array(
				'field' => 'layout_faq',
				'label' => 'FAQ레이아웃',
				'rules' => 'trim',
			),
			array(
				'field' => 'sidebar_faq',
				'label' => 'FAQ사이드바',
				'rules' => 'trim',
			),
			array(
				'field' => 'mobile_layout_faq',
				'label' => 'FAQ모바일레이아웃',
				'rules' => 'trim',
			),
			array(
				'field' => 'mobile_sidebar_faq',
				'label' => 'FAQ모바일사이드바',
				'rules' => 'trim',
			),
			array(
				'field' => 'skin_faq',
				'label' => 'FAQ일반스킨',
				'rules' => 'trim',
			),
			array(
				'field' => 'mobile_skin_faq',
				'label' => 'FAQ모바일스킨',
				'rules' => 'trim',
			),
			array(
				'field' => 'layout_register',
				'label' => '회원가입레이아웃',
				'rules' => 'trim',
			),
			array(
				'field' => 'sidebar_register',
				'label' => '회원가입사이드바',
				'rules' => 'trim',
			),
			array(
				'field' => 'mobile_layout_register',
				'label' => '회원가입모바일레이아웃',
				'rules' => 'trim',
			),
			array(
				'field' => 'mobile_sidebar_register',
				'label' => '회원가입모바일사이드바',
				'rules' => 'trim',
			),
			array(
				'field' => 'skin_register',
				'label' => '회원가입일반스킨',
				'rules' => 'trim',
			),
			array(
				'field' => 'mobile_skin_register',
				'label' => '회원가입모바일스킨',
				'rules' => 'trim',
			),
			array(
				'field' => 'layout_findaccount',
				'label' => '아이디패스워드찾기레이아웃',
				'rules' => 'trim',
			),
			array(
				'field' => 'sidebar_findaccount',
				'label' => '아이디패스워드찾기사이드바',
				'rules' => 'trim',
			),
			array(
				'field' => 'mobile_layout_findaccount',
				'label' => '아이디패스워드찾기모바일레이아웃',
				'rules' => 'trim',
			),
			array(
				'field' => 'mobile_sidebar_findaccount',
				'label' => '아이디패스워드찾기모바일사이드바',
				'rules' => 'trim',
			),
			array(
				'field' => 'skin_findaccount',
				'label' => '아이디패스워드찾기일반스킨',
				'rules' => 'trim',
			),
			array(
				'field' => 'mobile_skin_findaccount',
				'label' => '아이디패스워드찾기모바일스킨',
				'rules' => 'trim',
			),
			array(
				'field' => 'layout_login',
				'label' => '로그인레이아웃',
				'rules' => 'trim',
			),
			array(
				'field' => 'sidebar_login',
				'label' => '로그인사이드바',
				'rules' => 'trim',
			),
			array(
				'field' => 'mobile_layout_login',
				'label' => '로그인모바일레이아웃',
				'rules' => 'trim',
			),
			array(
				'field' => 'mobile_sidebar_login',
				'label' => '로그인모바일사이드바',
				'rules' => 'trim',
			),
			array(
				'field' => 'skin_login',
				'label' => '로그인일반스킨',
				'rules' => 'trim',
			),
			array(
				'field' => 'mobile_skin_login',
				'label' => '로그인모바일스킨',
				'rules' => 'trim',
			),
			array(
				'field' => 'layout_mypage',
				'label' => '마이페이지레이아웃',
				'rules' => 'trim',
			),
			array(
				'field' => 'sidebar_mypage',
				'label' => '마이페이지사이드바',
				'rules' => 'trim',
			),
			array(
				'field' => 'mobile_layout_mypage',
				'label' => '마이페이지모바일레이아웃',
				'rules' => 'trim',
			),
			array(
				'field' => 'mobile_sidebar_mypage',
				'label' => '마이페이지모바일사이드바',
				'rules' => 'trim',
			),
			array(
				'field' => 'skin_mypage',
				'label' => '마이페이지일반스킨',
				'rules' => 'trim',
			),
			array(
				'field' => 'mobile_skin_mypage',
				'label' => '마이페이지모바일스킨',
				'rules' => 'trim',
			),
			array(
				'field' => 'layout_currentvisitor',
				'label' => '현재접속자레이아웃',
				'rules' => 'trim',
			),
			array(
				'field' => 'sidebar_currentvisitor',
				'label' => '현재접속자사이드바',
				'rules' => 'trim',
			),
			array(
				'field' => 'mobile_layout_currentvisitor',
				'label' => '현재접속자모바일레이아웃',
				'rules' => 'trim',
			),
			array(
				'field' => 'mobile_sidebar_currentvisitor',
				'label' => '현재접속자모바일사이드바',
				'rules' => 'trim',
			),
			array(
				'field' => 'skin_currentvisitor',
				'label' => '현재접속자일반스킨',
				'rules' => 'trim',
			),
			array(
				'field' => 'mobile_skin_currentvisitor',
				'label' => '현재접속자모바일스킨',
				'rules' => 'trim',
			),
			array(
				'field' => 'layout_search',
				'label' => '검색페이지레이아웃',
				'rules' => 'trim',
			),
			array(
				'field' => 'sidebar_search',
				'label' => '검색페이지사이드바',
				'rules' => 'trim',
			),
			array(
				'field' => 'mobile_layout_search',
				'label' => '검색페이지모바일레이아웃',
				'rules' => 'trim',
			),
			array(
				'field' => 'mobile_sidebar_search',
				'label' => '검색페이지모바일사이드바',
				'rules' => 'trim',
			),
			array(
				'field' => 'skin_search',
				'label' => '검색페이지일반스킨',
				'rules' => 'trim',
			),
			array(
				'field' => 'mobile_skin_search',
				'label' => '검색페이지모바일스킨',
				'rules' => 'trim',
			),
			array(
				'field' => 'layout_tag',
				'label' => '태그페이지레이아웃',
				'rules' => 'trim',
			),
			array(
				'field' => 'sidebar_tag',
				'label' => '태그페이지사이드바',
				'rules' => 'trim',
			),
			array(
				'field' => 'mobile_layout_tag',
				'label' => '태그페이지모바일레이아웃',
				'rules' => 'trim',
			),
			array(
				'field' => 'mobile_sidebar_tag',
				'label' => '태그페이지모바일사이드바',
				'rules' => 'trim',
			),
			array(
				'field' => 'skin_tag',
				'label' => '태그페이지일반스킨',
				'rules' => 'trim',
			),
			array(
				'field' => 'mobile_skin_tag',
				'label' => '태그페이지모바일스킨',
				'rules' => 'trim',
			),
			array(
				'field' => 'layout_note',
				'label' => '쪽지레이아웃',
				'rules' => 'trim',
			),
			array(
				'field' => 'mobile_layout_note',
				'label' => '쪽지모바일레이아웃',
				'rules' => 'trim',
			),
			array(
				'field' => 'skin_note',
				'label' => '쪽지스킨',
				'rules' => 'trim',
			),
			array(
				'field' => 'mobile_skin_note',
				'label' => '쪽지모바일스킨',
				'rules' => 'trim',
			),
			array(
				'field' => 'layout_profile',
				'label' => '프로필레이아웃',
				'rules' => 'trim',
			),
			array(
				'field' => 'mobile_layout_profile',
				'label' => '프로필모바일레이아웃',
				'rules' => 'trim',
			),
			array(
				'field' => 'skin_profile',
				'label' => '프로필스킨',
				'rules' => 'trim',
			),
			array(
				'field' => 'mobile_skin_profile',
				'label' => '프로필모바일스킨',
				'rules' => 'trim',
			),
			array(
				'field' => 'layout_formmail',
				'label' => '폼메일레이아웃',
				'rules' => 'trim',
			),
			array(
				'field' => 'mobile_layout_formmail',
				'label' => '폼메일모바일레이아웃',
				'rules' => 'trim',
			),
			array(
				'field' => 'skin_formmail',
				'label' => '폼메일스킨',
				'rules' => 'trim',
			),
			array(
				'field' => 'mobile_skin_formmail',
				'label' => '폼메일모바일스킨',
				'rules' => 'trim',
			),
			array(
				'field' => 'layout_notification',
				'label' => '알림레이아웃',
				'rules' => 'trim',
			),
			array(
				'field' => 'sidebar_notification',
				'label' => '알림사이드바',
				'rules' => 'trim',
			),
			array(
				'field' => 'mobile_layout_notification',
				'label' => '알림모바일레이아웃',
				'rules' => 'trim',
			),
			array(
				'field' => 'mobile_sidebar_notification',
				'label' => '알림모바일사이드바',
				'rules' => 'trim',
			),
			array(
				'field' => 'skin_notification',
				'label' => '알림스킨',
				'rules' => 'trim',
			),
			array(
				'field' => 'mobile_skin_notification',
				'label' => '알림모바일스킨',
				'rules' => 'trim',
			),
			array(
				'field' => 'layout_helptool',
				'label' => '헬프툴레이아웃',
				'rules' => 'trim',
			),
			array(
				'field' => 'mobile_layout_helptool',
				'label' => '헬프툴모바일레이아웃',
				'rules' => 'trim',
			),
			array(
				'field' => 'skin_helptool',
				'label' => '헬프툴스킨',
				'rules' => 'trim',
			),
			array(
				'field' => 'mobile_skin_helptool',
				'label' => '헬프툴모바일스킨',
				'rules' => 'trim',
			),
			array(
				'field' => 'skin_popup',
				'label' => '팝업스킨',
				'rules' => 'trim',
			),
			array(
				'field' => 'mobile_skin_popup',
				'label' => '팝업모바일스킨',
				'rules' => 'trim',
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
				'layout_default', 'mobile_layout_default', 'sidebar_default', 'mobile_sidebar_default',
				'skin_default', 'mobile_skin_default', 'layout_main', 'mobile_layout_main', 'sidebar_main',
				'mobile_sidebar_main', 'skin_main', 'mobile_skin_main', 'layout_board',
				'mobile_layout_board', 'sidebar_board', 'mobile_sidebar_board', 'skin_board',
				'mobile_skin_board', 'layout_group', 'mobile_layout_group', 'sidebar_group',
				'mobile_sidebar_group', 'skin_group', 'mobile_skin_group', 'layout_document',
				'mobile_layout_document', 'sidebar_document', 'mobile_sidebar_document',
				'skin_document', 'mobile_skin_document', 'layout_faq', 'mobile_layout_faq', 'sidebar_faq',
				'mobile_sidebar_faq', 'skin_faq', 'mobile_skin_faq', 'layout_register', 'mobile_layout_register',
				'sidebar_register', 'mobile_sidebar_register', 'skin_register', 'mobile_skin_register',
				'layout_findaccount', 'mobile_layout_findaccount', 'sidebar_findaccount',
				'mobile_sidebar_findaccount', 'skin_findaccount', 'mobile_skin_findaccount', 'layout_login',
				'mobile_layout_login', 'sidebar_login', 'mobile_sidebar_login', 'skin_login',
				'mobile_skin_login', 'layout_mypage', 'mobile_layout_mypage', 'sidebar_mypage',
				'mobile_sidebar_mypage', 'skin_mypage', 'mobile_skin_mypage', 'layout_currentvisitor',
				'mobile_layout_currentvisitor', 'sidebar_currentvisitor', 'mobile_sidebar_currentvisitor',
				'skin_currentvisitor', 'mobile_skin_currentvisitor', 'layout_search', 'mobile_layout_search',
				'sidebar_search', 'mobile_sidebar_search', 'skin_search', 'mobile_skin_search', 'layout_tag',
				'mobile_layout_tag', 'sidebar_tag', 'mobile_sidebar_tag', 'skin_tag', 'mobile_skin_tag',
				'layout_note', 'mobile_layout_note', 'skin_note', 'mobile_skin_note', 'layout_profile',
				'mobile_layout_profile', 'skin_profile', 'mobile_skin_profile', 'layout_formmail',
				'mobile_layout_formmail', 'skin_formmail', 'mobile_skin_formmail', 'layout_notification',
				'mobile_layout_notification', 'sidebar_notification', 'mobile_sidebar_notification',
				'skin_notification', 'mobile_skin_notification', 'layout_helptool', 'mobile_layout_helptool',
				'skin_helptool', 'mobile_skin_helptool', 'skin_popup', 'mobile_skin_popup'
			);
			foreach ($array as $value) {
				$savedata[$value] = $this->input->post($value, null, '');
			}

			$this->Config_model->save($savedata);
			$view['view']['alert_message'] = '디자인 설정이 저장되었습니다';
		}

		$getdata = $this->Config_model->get_all_meta();
		$view['view']['data'] = $getdata;
		$view['view']['data']['layout_default_option'] = get_skin_name(
			'_layout',
			set_value('layout_default', element('layout_default', $getdata))
		);
		$view['view']['data']['mobile_layout_default_option'] = get_skin_name(
			'_layout',
			set_value('mobile_layout_default', element('mobile_layout_default', $getdata))
		);
		$view['view']['data']['skin_default_option'] = get_skin_name(
			'main',
			set_value('skin_default', element('skin_default', $getdata))
		);
		$view['view']['data']['mobile_skin_default_option'] = get_skin_name(
			'main',
			set_value('mobile_skin_default', element('mobile_skin_default', $getdata))
		);

		$view['view']['data']['layout_main_option'] = get_skin_name(
			'_layout',
			set_value('layout_main', element('layout_main', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['mobile_layout_main_option'] = get_skin_name(
			'_layout',
			set_value('mobile_layout_main', element('mobile_layout_main', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['skin_main_option'] = get_skin_name(
			'main',
			set_value('skin_main', element('skin_main', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['mobile_skin_main_option'] = get_skin_name(
			'main',
			set_value('mobile_skin_main', element('mobile_skin_main', $getdata)),
			'기본설정따름'
		);

		$view['view']['data']['layout_board_option'] = get_skin_name(
			'_layout',
			set_value('layout_board', element('layout_board', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['mobile_layout_board_option'] = get_skin_name(
			'_layout',
			set_value('mobile_layout_board', element('mobile_layout_board', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['skin_board_option'] = get_skin_name(
			'board',
			set_value('skin_board', element('skin_board', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['mobile_skin_board_option'] = get_skin_name(
			'board',
			set_value('mobile_skin_board', element('mobile_skin_board', $getdata)),
			'기본설정따름'
		);

		$view['view']['data']['layout_group_option'] = get_skin_name(
			'_layout',
			set_value('layout_group', element('layout_group', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['mobile_layout_group_option'] = get_skin_name(
			'_layout',
			set_value('mobile_layout_group', element('mobile_layout_group', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['skin_group_option'] = get_skin_name(
			'group',
			set_value('skin_group', element('skin_group', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['mobile_skin_group_option'] = get_skin_name(
			'group',
			set_value('mobile_skin_group', element('mobile_skin_group', $getdata)),
			'기본설정따름'
		);

		$view['view']['data']['layout_document_option'] = get_skin_name(
			'_layout',
			set_value('layout_document', element('layout_document', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['mobile_layout_document_option'] = get_skin_name(
			'_layout',
			set_value('mobile_layout_document', element('mobile_layout_document', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['skin_document_option'] = get_skin_name(
			'document',
			set_value('skin_document', element('skin_document', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['mobile_skin_document_option'] = get_skin_name(
			'document',
			set_value('mobile_skin_document', element('mobile_skin_document', $getdata)),
			'기본설정따름'
		);

		$view['view']['data']['layout_faq_option'] = get_skin_name(
			'_layout',
			set_value('layout_faq', element('layout_faq', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['mobile_layout_faq_option'] = get_skin_name(
			'_layout',
			set_value('mobile_layout_faq', element('mobile_layout_faq', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['skin_faq_option'] = get_skin_name(
			'faq',
			set_value('skin_faq', element('skin_faq', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['mobile_skin_faq_option'] = get_skin_name(
			'faq',
			set_value('mobile_skin_faq', element('mobile_skin_faq', $getdata)),
			'기본설정따름'
		);

		$view['view']['data']['layout_register_option'] = get_skin_name(
			'_layout',
			set_value('layout_register', element('layout_register', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['mobile_layout_register_option'] = get_skin_name(
			'_layout',
			set_value('mobile_layout_register', element('mobile_layout_register', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['skin_register_option'] = get_skin_name(
			'register',
			set_value('skin_register', element('skin_register', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['mobile_skin_register_option'] = get_skin_name(
			'register',
			set_value('mobile_skin_register', element('mobile_skin_register', $getdata)),
			'기본설정따름'
		);

		$view['view']['data']['layout_findaccount_option'] = get_skin_name(
			'_layout',
			set_value('layout_findaccount', element('layout_findaccount', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['mobile_layout_findaccount_option'] = get_skin_name(
			'_layout',
			set_value('mobile_layout_findaccount', element('mobile_layout_findaccount', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['skin_findaccount_option'] = get_skin_name(
			'findaccount',
			set_value('skin_findaccount', element('skin_findaccount', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['mobile_skin_findaccount_option'] = get_skin_name(
			'findaccount',
			set_value('mobile_skin_findaccount', element('mobile_skin_findaccount', $getdata)),
			'기본설정따름'
		);

		$view['view']['data']['layout_login_option'] = get_skin_name(
			'_layout',
			set_value('layout_login', element('layout_login', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['mobile_layout_login_option'] = get_skin_name(
			'_layout',
			set_value('mobile_layout_login', element('mobile_layout_login', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['skin_login_option'] = get_skin_name(
			'login',
			set_value('skin_login', element('skin_login', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['mobile_skin_login_option'] = get_skin_name(
			'login',
			set_value('mobile_skin_login', element('mobile_skin_login', $getdata)),
			'기본설정따름'
		);

		$view['view']['data']['layout_mypage_option'] = get_skin_name(
			'_layout',
			set_value('layout_mypage', element('layout_mypage', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['mobile_layout_mypage_option'] = get_skin_name(
			'_layout',
			set_value('mobile_layout_mypage', element('mobile_layout_mypage', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['skin_mypage_option'] = get_skin_name(
			'mypage',
			set_value('skin_mypage', element('skin_mypage', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['mobile_skin_mypage_option'] = get_skin_name(
			'mypage',
			set_value('mobile_skin_mypage', element('mobile_skin_mypage', $getdata)),
			'기본설정따름'
		);

		$view['view']['data']['layout_currentvisitor_option'] = get_skin_name(
			'_layout',
			set_value('layout_currentvisitor', element('layout_currentvisitor', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['mobile_layout_currentvisitor_option'] = get_skin_name(
			'_layout',
			set_value('mobile_layout_currentvisitor', element('mobile_layout_currentvisitor', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['skin_currentvisitor_option'] = get_skin_name(
			'currentvisitor',
			set_value('skin_currentvisitor', element('skin_currentvisitor', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['mobile_skin_currentvisitor_option'] = get_skin_name(
			'currentvisitor',
			set_value('mobile_skin_currentvisitor', element('mobile_skin_currentvisitor', $getdata)),
			'기본설정따름'
		);

		$view['view']['data']['layout_search_option'] = get_skin_name(
			'_layout',
			set_value('layout_search', element('layout_search', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['mobile_layout_search_option'] = get_skin_name(
			'_layout',
			set_value('mobile_layout_search', element('mobile_layout_search', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['skin_search_option'] = get_skin_name(
			'search',
			set_value('skin_search', element('skin_search', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['mobile_skin_search_option'] = get_skin_name(
			'search',
			set_value('mobile_skin_search', element('mobile_skin_search', $getdata)),
			'기본설정따름'
		);

		$view['view']['data']['layout_tag_option'] = get_skin_name(
			'_layout',
			set_value('layout_tag', element('layout_tag', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['mobile_layout_tag_option'] = get_skin_name(
			'_layout',
			set_value('mobile_layout_tag', element('mobile_layout_tag', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['skin_tag_option'] = get_skin_name(
			'tag',
			set_value('skin_tag', element('skin_tag', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['mobile_skin_tag_option'] = get_skin_name(
			'tag',
			set_value('mobile_skin_tag', element('mobile_skin_tag', $getdata)),
			'기본설정따름'
		);

		$view['view']['data']['layout_note_option'] = get_skin_name(
			'_layout',
			set_value('layout_note', element('layout_note', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['mobile_layout_note_option'] = get_skin_name(
			'_layout',
			set_value('mobile_layout_note', element('mobile_layout_note', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['skin_note_option'] = get_skin_name(
			'note',
			set_value('skin_note', element('skin_note', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['mobile_skin_note_option'] = get_skin_name(
			'note',
			set_value('mobile_skin_note', element('mobile_skin_note', $getdata)),
			'기본설정따름'
		);

		$view['view']['data']['layout_profile_option'] = get_skin_name(
			'_layout',
			set_value('layout_profile', element('layout_profile', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['mobile_layout_profile_option'] = get_skin_name(
			'_layout',
			set_value('mobile_layout_profile', element('mobile_layout_profile', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['skin_profile_option'] = get_skin_name(
			'profile',
			set_value('skin_profile', element('skin_profile', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['mobile_skin_profile_option'] = get_skin_name(
			'profile',
			set_value('mobile_skin_profile', element('mobile_skin_profile', $getdata)),
			'기본설정따름'
		);

		$view['view']['data']['layout_formmail_option'] = get_skin_name(
			'_layout',
			set_value('layout_formmail', element('layout_formmail', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['mobile_layout_formmail_option'] = get_skin_name(
			'_layout',
			set_value('mobile_layout_formmail', element('mobile_layout_formmail', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['skin_formmail_option'] = get_skin_name(
			'formmail',
			set_value('skin_formmail', element('skin_formmail', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['mobile_skin_formmail_option'] = get_skin_name(
			'formmail',
			set_value('mobile_skin_formmail', element('mobile_skin_formmail', $getdata)),
			'기본설정따름'
		);

		$view['view']['data']['layout_notification_option'] = get_skin_name(
			'_layout',
			set_value('layout_notification', element('layout_notification', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['mobile_layout_notification_option'] = get_skin_name(
			'_layout',
			set_value('mobile_layout_notification', element('mobile_layout_notification', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['skin_notification_option'] = get_skin_name(
			'notification',
			set_value('skin_notification', element('skin_notification', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['mobile_skin_notification_option'] = get_skin_name(
			'notification',
			set_value('mobile_skin_notification', element('mobile_skin_notification', $getdata)),
			'기본설정따름'
		);

		$view['view']['data']['layout_helptool_option'] = get_skin_name(
			'_layout',
			set_value('layout_helptool', element('layout_helptool', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['mobile_layout_helptool_option'] = get_skin_name(
			'_layout',
			set_value('mobile_layout_helptool', element('mobile_layout_helptool', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['skin_helptool_option'] = get_skin_name(
			'helptool',
			set_value('skin_helptool', element('skin_helptool', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['mobile_skin_helptool_option'] = get_skin_name(
			'helptool',
			set_value('mobile_skin_helptool', element('mobile_skin_helptool', $getdata)),
			'기본설정따름'
		);

		$view['view']['data']['skin_popup_option'] = get_skin_name(
			'popup',
			set_value('skin_popup', element('skin_popup', $getdata))
		);
		$view['view']['data']['mobile_skin_popup_option'] = get_skin_name(
			'popup',
			set_value('mobile_skin_popup', element('mobile_skin_popup', $getdata))
		);

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

	/**
	 * 기본환경설정 > 메타태그 설정입니다
	 */
	public function metatag()
	{

		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_config_layoutskin_metatag';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		$view['view']['pagelist'] = $pagelist = array(
			array(
				'key' => 'main',
				'name' => '메인페이지',
				'controllers' => array('Main/index'),
				'description' => '',
			),
			array(
				'key' => 'board_list',
				'name' => '게시판 목록',
				'controllers' => array('Board_post/lists'),
				'description' => '{게시판명}',
			),
			array(
				'key' => 'board_post',
				'name' => '게시글 열람',
				'controllers' => array('Board_post/post'),
				'description' => '{게시판명}, {게시판아이디}, {글제목}, {작성자명}',
			),
			array(
				'key' => 'board_write',
				'name' => '새글 작성',
				'controllers' => array('Board_write/write'),
				'description' => '{게시판명}, {게시판아이디}',
			),
			array(
				'key' => 'board_modify',
				'name' => '글 수정',
				'controllers' => array('Board_write/modify'),
				'description' => '{게시판명}, {게시판아이디}, {글제목}, {작성자명}',
			),
			array(
				'key' => 'group',
				'name' => '게시판그룹',
				'controllers' => array('Group/index'),
				'description' => '{그룹명}, {그룹아이디}',
			),
			array(
				'key' => 'document',
				'name' => '일반문서페이지',
				'controllers' => array('Document/index'),
				'description' => '{문서제목}, {문서아이디}',
			),
			array(
				'key' => 'faq',
				'name' => 'FAQ',
				'controllers' => array('Faq/index'),
				'description' => '{FAQ제목}, {FAQ아이디}',
			),
			array(
				'key' => 'register',
				'name' => '회원가입 > 약관동의',
				'controllers' => array('Register/index'),
				'description' => '',
			),
			array(
				'key' => 'register_form',
				'name' => '회원가입 > 가입폼작성',
				'controllers' => array('Register/form'),
				'description' => '',
			),
			array(
				'key' => 'register_result',
				'name' => '회원가입 > 가입결과',
				'controllers' => array('Register/result'),
				'description' => '',
			),
			array(
				'key' => 'findaccount',
				'name' => '아이디패스워드찾기',
				'controllers' => array('Findaccount/index'),
				'description' => '',
			),
			array(
				'key' => 'login',
				'name' => '로그인',
				'controllers' => array('Login/index'),
				'description' => '',
			),
			array(
				'key' => 'mypage',
				'name' => '마이페이지 홈',
				'controllers' => array('Mypage/index'),
				'description' => '',
			),
			array(
				'key' => 'mypage_post',
				'name' => '마이페이지 > 나의작성글',
				'controllers' => array('Mypage/post'),
				'description' => '',
			),
			array(
				'key' => 'mypage_comment',
				'name' => '마이페이지 > 나의 작성댓글',
				'controllers' => array('Mypage/comment'),
				'description' => '',
			),
			array(
				'key' => 'mypage_point',
				'name' => '마이페이지 > 포인트',
				'controllers' => array('Mypage/point'),
				'description' => '',
			),
			array(
				'key' => 'mypage_followinglist',
				'name' => '마이페이지 > Following',
				'controllers' => array('Mypage/followinglist'),
				'description' => '',
			),
			array(
				'key' => 'mypage_followedlist',
				'name' => '마이페이지 > Followed',
				'controllers' => array('Mypage/followedlist'),
				'description' => '',
			),
			array(
				'key' => 'mypage_like_post',
				'name' => '마이페이지 > 추천(원글)',
				'controllers' => array('Mypage/like_post'),
				'description' => '',
			),
			array(
				'key' => 'mypage_like_comment',
				'name' => '마이페이지 > 추천(댓글)',
				'controllers' => array('Mypage/like_comment'),
				'description' => '',
			),
			array(
				'key' => 'mypage_scrap',
				'name' => '마이페이지 > 스크랩',
				'controllers' => array('Mypage/scrap'),
				'description' => '',
			),
			array(
				'key' => 'mypage_loginlog',
				'name' => '마이페이지 > 로그인기록',
				'controllers' => array('Mypage/loginlog'),
				'description' => '',
			),
			array(
				'key' => 'membermodify',
				'name' => '회원정보수정',
				'controllers' => array('Membermodify/index', 'Membermodify/modify', 'Membermodify/password_modify'),
				'description' => '',
			),
			array(
				'key' => 'membermodify_memberleave',
				'name' => '회원탈퇴',
				'controllers' => array('Membermodify/memberleave'),
				'description' => '',
			),
			array(
				'key' => 'currentvisitor',
				'name' => '현재접속자',
				'controllers' => array('Currentvisitor/index'),
				'description' => '{현재접속자수}',
			),
			array(
				'key' => 'search',
				'name' => '검색페이지',
				'controllers' => array('Search/index'),
				'description' => '{검색어}',
			),
			array(
				'key' => 'note_list',
				'name' => '쪽지목록',
				'controllers' => array('Note/lists'),
				'description' => '',
			),
			array(
				'key' => 'note_view',
				'name' => '쪽지열람',
				'controllers' => array('Note/view'),
				'description' => '{쪽지제목}',
			),
			array(
				'key' => 'note_write',
				'name' => '쪽지쓰기',
				'controllers' => array('Note/write'),
				'description' => '',
			),
			array(
				'key' => 'profile',
				'name' => '프로필',
				'controllers' => array('Profile/index'),
				'description' => '{프로필회원명}, {프로필회원아이디}',
			),
			array(
				'key' => 'formmail',
				'name' => '폼메일',
				'controllers' => array('Formmail/write'),
				'description' => '',
			),
			array(
				'key' => 'notification',
				'name' => '알림페이지',
				'controllers' => array('Notification/index'),
				'description' => '',
			),
			array(
				'key' => 'tag',
				'name' => '태그페이지',
				'controllers' => array('Tags/index'),
				'description' => '{태그명}',
			),
		);


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
				'field' => 'site_meta_title_default',
				'label' => '기본설정 Title',
				'rules' => 'trim',
			),
			array(
				'field' => 'site_meta_description_default',
				'label' => '기본설정 meta description',
				'rules' => 'trim',
			),
			array(
				'field' => 'site_meta_keywords_default',
				'label' => '기본설정 meta keywords',
				'rules' => 'trim',
			),
			array(
				'field' => 'site_meta_author_default',
				'label' => '기본설정 meta author',
				'rules' => 'trim',
			),
		);
		foreach ($pagelist as $pval) {
			$config[] = array(
				'field' => 'site_meta_title_' . element('key', $pval),
				'label' => element('name', $pval) . ' Title',
				'rules' => 'trim',
			);
			$config[] = array(
				'field' => 'site_meta_description_' . element('key', $pval),
				'label' => element('name', $pval) . ' meta description',
				'rules' => 'trim',
			);
			$config[] = array(
				'field' => 'site_meta_keywords_' . element('key', $pval),
				'label' => element('name', $pval) . ' meta keywords',
				'rules' => 'trim',
			);
			$config[] = array(
				'field' => 'site_meta_author_' . element('key', $pval),
				'label' => element('name', $pval) . ' meta author',
				'rules' => 'trim',
			);
			$config[] = array(
				'field' => 'site_page_name_' . element('key', $pval),
				'label' => element('name', $pval) . ' page name',
				'rules' => 'trim',
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
			/**
			 * 유효성 검사를 통과한 경우입니다.
			 * 즉 데이터의 insert 나 update 의 process 처리가 필요한 상황입니다
			 */

			// 이벤트가 존재하면 실행합니다
			$view['view']['event']['formruntrue'] = Events::trigger('formruntrue', $eventname);

			$array = array(
				'site_meta_title_default', 'site_meta_description_default', 'site_meta_keywords_default', 'site_meta_author_default', 'site_page_name_default');
			foreach ($pagelist as $pval) {
				$array[] = 'site_meta_title_' . element('key', $pval);
				$array[] = 'site_meta_description_' . element('key', $pval);
				$array[] = 'site_meta_keywords_' . element('key', $pval);
				$array[] = 'site_meta_author_' . element('key', $pval);
				$array[] = 'site_page_name_' . element('key', $pval);
			}
			foreach ($array as $value) {
				$savedata[$value] = $this->input->post($value, null, '');
			}

			$this->Config_model->save($savedata);
			$view['view']['alert_message'] = '메타태그 설정이 저장되었습니다';
		}

		$getdata = $this->Config_model->get_all_meta();
		$view['view']['data'] = $getdata;

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 어드민 레이아웃을 정의합니다
		 */
		$layoutconfig = array('layout' => 'layout', 'skin' => 'metatag');
		$view['layout'] = $this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}

	/**
	 * 기본환경설정 > 파비콘등록 설정입니다
	 */
	public function favicon()
	{

		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_config_layoutskin_favicon';
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
				'field' => 'site_favicon_del',
				'label' => '삭제',
				'rules' => 'trim|numeric',
			),
		);
		$this->form_validation->set_rules($config);
		$form_validation = $this->form_validation->run();
		$file_error = '';
		$site_favicon = '';

		if ($form_validation) {
			$this->load->library('upload');
			if (isset($_FILES) && isset($_FILES['site_favicon']) && isset($_FILES['site_favicon']['name']) && $_FILES['site_favicon']['name']) {
				$upload_path = config_item('uploads_dir') . '/favicon/';
				if (is_dir($upload_path) === false) {
					mkdir($upload_path, 0707);
					$file = $upload_path . 'index.php';
					$f = @fopen($file, 'w');
					@fwrite($f, '');
					@fclose($f);
					@chmod($file, 0644);
				}

				$uploadconfig = array();
				$uploadconfig['upload_path'] = $upload_path;
				$uploadconfig['allowed_types'] = 'ico';
				$uploadconfig['max_width'] = '16';
				$uploadconfig['max_height'] = '16';
				$uploadconfig['encrypt_name'] = true;

				$this->upload->initialize($uploadconfig);

				if ($this->upload->do_upload('site_favicon')) {
					$img = $this->upload->data();
					$site_favicon = element('file_name', $img);
				} else {
					$file_error = $this->upload->display_errors();

				}
			}
		}

		/**
		 * 유효성 검사를 하지 않는 경우, 또는 유효성 검사에 실패한 경우입니다.
		 * 즉 글쓰기나 수정 페이지를 보고 있는 경우입니다
		 */
		if ($form_validation === false OR $file_error !== '') {

			// 이벤트가 존재하면 실행합니다
			$view['view']['event']['formrunfalse'] = Events::trigger('formrunfalse', $eventname);

			$view['view']['message'] = $file_error;

		} else {
			/**
			 * 유효성 검사를 통과한 경우입니다.
			 * 즉 데이터의 insert 나 update 의 process 처리가 필요한 상황입니다
			 */

			// 이벤트가 존재하면 실행합니다
			$view['view']['event']['formruntrue'] = Events::trigger('formruntrue', $eventname);

			$savedata = array();
			if ($site_favicon) {
				$savedata['site_favicon'] = $site_favicon;
			} elseif ($this->input->post('site_favicon_del')) {
				$savedata['site_favicon'] = '';
			}
			if ($site_favicon OR $this->input->post('site_favicon_del')) {
				@unlink(config_item('uploads_dir') . '/favicon/' . $this->cbconfig->item('site_favicon'));
			}


			$this->Config_model->save($savedata);
			$view['view']['alert_message'] = '파비콘 설정이 저장되었습니다';
		}

		$getdata = $this->Config_model->get_all_meta();
		$view['view']['data'] = $getdata;

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 어드민 레이아웃을 정의합니다
		 */
		$layoutconfig = array('layout' => 'layout', 'skin' => 'favicon');
		$view['layout'] = $this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}
}
