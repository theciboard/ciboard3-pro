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
 * 관리자>환경설정>기본환경설정 controller 입니다.
 */
class Cbconfigs extends CB_Controller
{

	/**
	 * 관리자 페이지 상의 현재 디렉토리입니다
	 * 페이지 이동시 필요한 정보입니다
	 */
	public $pagedir = 'config/cbconfigs';

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
	 * 기본환경설정>기본정보 페이지입니다
	 */
	public function index()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_config_cbconfigs_index';
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
				'rules' => 'trim|required',
			),
			array(
				'field' => 'site_title',
				'label' => '홈페이지 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'site_logo',
				'label' => '홈페이지 로고',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'admin_logo',
				'label' => '관리자페이지 로고',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'footer_script',
				'label' => '하단스크립트',
				'rules' => 'trim',
			),
			array(
				'field' => 'webmaster_name',
				'label' => '웹마스터 이름',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'webmaster_email',
				'label' => '웹마스터 이메일주소',
				'rules' => 'trim|required|valid_email',
			),
			array(
				'field' => 'spam_word',
				'label' => '단어 필터링',
				'rules' => 'trim',
			),
			array(
				'field' => 'white_iframe',
				'label' => '허용하는 Iframe 주소',
				'rules' => 'trim',
			),
			array(
				'field' => 'jwplayer6_key',
				'label' => 'JWPLAYER6 KEY',
				'rules' => 'trim',
			),
			array(
				'field' => 'naver_blog_api_key',
				'label' => '네이버 블로그 글쓰기 API',
				'rules' => 'trim',
			),
			array(
				'field' => 'naver_syndi_key',
				'label' => '네이버 신디케이션 연동키',
				'rules' => 'trim',
			),
			array(
				'field' => 'use_naver_syndi_log',
				'label' => '네이버 신디케이션 핑 전송 로그',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'bitly_access_token',
				'label' => 'Bitly Access Token',
				'rules' => 'trim',
			),
			array(
				'field' => 'kakao_apikey',
				'label' => '카카오 API KEY',
				'rules' => 'trim',
			),
			array(
				'field' => 'new_post_second',
				'label' => '새로운 글쓰기',
				'rules' => 'trim|required|numeric|is_natural',
			),
			array(
				'field' => 'open_currentvisitor',
				'label' => '현재접속자 공개',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'currentvisitor_minute',
				'label' => '현재접속자 기준',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'use_copy_log',
				'label' => '게시물 복사, 이동시 로그',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'max_level',
				'label' => '최고레벨',
				'rules' => 'trim|required|is_natural|is_natural_no_zero|less_than_equal_to[1000]',
			),
			array(
				'field' => 'ip_display_style',
				'label' => 'IP 공개시 표시형식',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'list_count',
				'label' => '한 페이지에 보이는 게시물 수',
				'rules' => 'trim|numeric|is_natural',
			),
			array(
				'field' => 'use_recaptcha',
				'label' => '구글 캡챠 사용 여부',
				'rules' => 'trim|numeric|is_natural',
			),
			array(
				'field' => 'recaptcha_sitekey',
				'label' => '구글 reCaptcha Sitekey',
				'rules' => 'trim',
			),
			array(
				'field' => 'recaptcha_secret',
				'label' => '구글 reCaptcha Secret',
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
				'site_title', 'site_logo', 'admin_logo', 'footer_script', 'webmaster_name', 'webmaster_email',
				'spam_word', 'white_iframe', 'jwplayer6_key', 'naver_blog_api_key', 'naver_syndi_key',
				'use_naver_syndi_log', 'bitly_access_token', 'kakao_apikey',
				'new_post_second', 'open_currentvisitor', 'currentvisitor_minute', 'use_copy_log', 'max_level',
				'ip_display_style', 'list_count', 'use_recaptcha', 'recaptcha_sitekey', 'recaptcha_secret'
			);
			foreach ($array as $value) {
				$savedata[$value] = $this->input->post($value, null, '');
			}

			$this->Config_model->save($savedata);
			$view['view']['alert_message'] = '기본정보 설정이 저장되었습니다';
		}

		$getdata = $this->Config_model->get_all_meta();
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

	/**
	 * 기본환경설정>접근기능 페이지입니다
	 */
	public function access()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_config_cbconfigs_access';
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
				'rules' => 'trim|required',
			),
			array(
				'field' => 'admin_ip_whitelist',
				'label' => '관리자페이지 접근가능 IP',
				'rules' => 'trim',
			),
			array(
				'field' => 'site_ip_blacklist',
				'label' => '사이트 접근 불가 IP',
				'rules' => 'trim',
			),
			array(
				'field' => 'site_ip_whitelist',
				'label' => '사이트 접근 가능 IP',
				'rules' => 'trim',
			),
			array(
				'field' => 'site_blacklist_title',
				'label' => '사이트 차단시 안내문 제목',
				'rules' => 'trim',
			),
			array(
				'field' => 'site_blacklist_content',
				'label' => '사이트 차단시 안내문 내용',
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
				'admin_ip_whitelist', 'site_ip_blacklist', 'site_ip_whitelist',
				'site_blacklist_title', 'site_blacklist_content',
			);
			foreach ($array as $value) {
				$savedata[$value] = $this->input->post($value, null, '');
			}

			$this->Config_model->save($savedata);
			$view['view']['alert_message'] = '접근기능 설정이 저장되었습니다';
		}

		$getdata = $this->Config_model->get_all_meta();
		$view['view']['data'] = $getdata;

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 어드민 레이아웃을 정의합니다
		 */
		$layoutconfig = array('layout' => 'layout', 'skin' => 'access');
		$view['layout'] = $this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}

	/**
	 * 기본환경설정>포인트기능 페이지입니다
	 */
	public function point()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_config_cbconfigs_point';
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
				'rules' => 'trim|required',
			),
			array(
				'field' => 'use_point',
				'label' => '포인트기능',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'point_register',
				'label' => '회원가입시포인트',
				'rules' => 'trim|required|numeric',
			),
			array(
				'field' => 'point_login',
				'label' => '로그인시 포인트',
				'rules' => 'trim|required|numeric',
			),
			array(
				'field' => 'point_recommended',
				'label' => '회원가입시 추천인에게',
				'rules' => 'trim|required|numeric',
			),
			array(
				'field' => 'point_recommender',
				'label' => '추천인존재시 가입자에게',
				'rules' => 'trim|required|numeric',
			),
			array(
				'field' => 'block_download_zeropoint',
				'label' => '다운로드 금지',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'block_read_zeropoint',
				'label' => '글열람 금지',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'block_write_zeropoint',
				'label' => '글작성 금지',
				'rules' => 'trim|numeric',
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
				'use_point', 'point_register', 'point_login', 'point_recommended', 'point_recommender',
				'block_download_zeropoint', 'block_read_zeropoint', 'block_write_zeropoint',
			);
			foreach ($array as $value) {
				$savedata[$value] = $this->input->post($value, null, '');
			}

			$this->Config_model->save($savedata);
			$view['view']['alert_message'] = '포인트기능 설정이 저장되었습니다';
		}

		$getdata = $this->Config_model->get_all_meta();
		$view['view']['data'] = $getdata;

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 어드민 레이아웃을 정의합니다
		 */
		$layoutconfig = array('layout' => 'layout', 'skin' => 'point');
		$view['layout'] = $this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}


	/**
	 * 기본환경설정>일반기능/에디터 페이지입니다
	 */
	public function general()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_config_cbconfigs_general';
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
				'rules' => 'trim|required',
			),
			array(
				'field' => 'use_sideview',
				'label' => '사이드뷰',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'use_mobile_sideview',
				'label' => '사이드뷰 - 모바일',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'use_sideview_email',
				'label' => '사이드뷰이메일',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'use_mobile_sideview_email',
				'label' => '사이드뷰이메일 - 모바일',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'post_editor_type',
				'label' => '본문 에디터 종류',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'use_document_dhtml',
				'label' => '일반문서 DHTML 사용',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'document_editor_type',
				'label' => '일반문서 에디터 종류',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'document_thumb_width',
				'label' => '일반문서 첨부파일 가로크기',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'document_mobile_thumb_width',
				'label' => '일반문서 첨부파일 가로크기 - 모바일',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'document_content_target_blank',
				'label' => '일반문서 링크 새창',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'document_mobile_content_target_blank',
				'label' => '일반문서 링크 새창 - 모바일',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'use_document_auto_url',
				'label' => '일반문서 본문안의 URL 자동링크',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'use_document_mobile_auto_url',
				'label' => '일반문서 본문안의 URL 자동링크 - 모바일',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'use_faq_dhtml',
				'label' => 'FAQ DHTML 사용',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'faq_editor_type',
				'label' => 'FAQ 에디터 종류',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'faq_thumb_width',
				'label' => 'FAQ 첨부파일 가로크기',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'faq_mobile_thumb_width',
				'label' => 'FAQ 첨부파일 가로크기 - 모바일',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'faq_content_target_blank',
				'label' => 'FAQ 링크 새창',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'faq_mobile_content_target_blank',
				'label' => 'FAQ 링크 새창 - 모바일',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'use_faq_auto_url',
				'label' => 'FAQ 본문안의 URL 자동링크',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'use_faq_mobile_auto_url',
				'label' => 'FAQ 본문안의 URL 자동링크 - 모바일',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'use_popup_dhtml',
				'label' => '팝업 DHTML 사용',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'popup_editor_type',
				'label' => '팝업 에디터 종류',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'popup_thumb_width',
				'label' => '팝업 첨부파일 가로크기',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'popup_mobile_thumb_width',
				'label' => '팝업 첨부파일 가로크기 - 모바일',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'popup_content_target_blank',
				'label' => '팝업 링크 새창',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'popup_mobile_content_target_blank',
				'label' => '팝업 링크 새창 - 모바일',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'use_popup_auto_url',
				'label' => '팝업 본문안의 URL 자동링크',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'use_popup_mobile_auto_url',
				'label' => '팝업 본문안의 URL 자동링크 - 모바일',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'use_formmail_dhtml',
				'label' => '폼메일 DHTML 사용',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'formmail_editor_type',
				'label' => '폼메일 에디터 종류',
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
				'use_sideview', 'use_mobile_sideview', 'use_sideview_email',
				'use_mobile_sideview_email', 'post_editor_type', 'use_document_dhtml',
				'document_editor_type', 'document_thumb_width', 'document_mobile_thumb_width',
				'document_content_target_blank', 'document_mobile_content_target_blank',
				'use_document_auto_url', 'use_document_mobile_auto_url', 'use_faq_dhtml',
				'faq_editor_type', 'faq_thumb_width', 'faq_mobile_thumb_width',
				'faq_content_target_blank', 'faq_mobile_content_target_blank', 'use_faq_auto_url',
				'use_faq_mobile_auto_url', 'use_popup_dhtml', 'popup_editor_type', 'popup_thumb_width',
				'popup_mobile_thumb_width', 'popup_content_target_blank',
				'popup_mobile_content_target_blank', 'use_popup_auto_url', 'use_popup_mobile_auto_url',
				'use_formmail_dhtml', 'formmail_editor_type',
			);
			foreach ($array as $value) {
				$savedata[$value] = $this->input->post($value, null, '');
			}

			$this->Config_model->save($savedata);
			$view['view']['alert_message'] = '일반기능/에디터 설정이 저장되었습니다';
		}

		$getdata = $this->Config_model->get_all_meta();
		$view['view']['data'] = $getdata;

		$view['view']['data']['post_editor_type_option'] = get_skin_name(
			'editor',
			set_value('post_editor_type', element('post_editor_type', $getdata)),
			'',
			$path = 'plugin'
		);
		$view['view']['data']['faq_editor_type_option'] = get_skin_name(
			'editor',
			set_value('faq_editor_type', element('faq_editor_type', $getdata)),
			'',
			$path = 'plugin'
		);
		$view['view']['data']['document_editor_type_option'] = get_skin_name(
			'editor',
			set_value('document_editor_type', element('document_editor_type', $getdata)),
			'',
			$path = 'plugin'
		);
		$view['view']['data']['popup_editor_type_option'] = get_skin_name(
			'editor',
			set_value('popup_editor_type', element('popup_editor_type', $getdata)),
			'',
			$path = 'plugin'
		);
		$view['view']['data']['formmail_editor_type_option'] = get_skin_name(
			'editor',
			set_value('formmail_editor_type', element('formmail_editor_type', $getdata)),
			'',
			$path = 'plugin'
		);

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 어드민 레이아웃을 정의합니다
		 */
		$layoutconfig = array('layout' => 'layout', 'skin' => 'general');
		$view['layout'] = $this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}

	/**
	 * 기본환경설정>쪽지설정 페이지입니다
	 */
	public function note()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_config_cbconfigs_note';
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
				'rules' => 'trim|required',
			),
			array(
				'field' => 'use_note',
				'label' => '쪽지기능',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'note_list_page',
				'label' => '한페이지에보이는쪽지수',
				'rules' => 'trim|required|numeric',
			),
			array(
				'field' => 'note_mobile_list_page',
				'label' => '한페이지에보이는쪽지수 - 모바일',
				'rules' => 'trim|required|numeric',
			),
			array(
				'field' => 'use_note_dhtml',
				'label' => '쪽지 DHTML 사용',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'use_note_mobile_dhtml',
				'label' => '쪽지 DHTML 사용 - 모바일',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'note_editor_type',
				'label' => '쪽지 에디터 종류',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'point_note',
				'label' => '쪽지발송시 차감포인트',
				'rules' => 'trim|required|numeric',
			),
			array(
				'field' => 'use_note_file',
				'label' => '쪽지에 첨부파일 기능사용',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'point_note_file',
				'label' => '첨부파일업로드시 차감포인트',
				'rules' => 'trim|required|numeric',
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
				'use_note', 'note_list_page', 'note_mobile_list_page', 'use_note_dhtml',
				'use_note_mobile_dhtml', 'note_editor_type', 'point_note',
				'use_note_file', 'point_note_file',
			);
			foreach ($array as $value) {
				$savedata[$value] = $this->input->post($value, null, '');
			}

			$this->Config_model->save($savedata);
			$view['view']['alert_message'] = '쪽지기능 설정이 저장되었습니다';
		}

		$getdata = $this->Config_model->get_all_meta();
		$view['view']['data'] = $getdata;

		$view['view']['data']['note_editor_type_option'] = get_skin_name(
			'editor',
			set_value('note_editor_type', element('note_editor_type', $getdata)),
			'',
			$path = 'plugin'
		);

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 어드민 레이아웃을 정의합니다
		 */
		$layoutconfig = array('layout' => 'layout', 'skin' => 'note');
		$view['layout'] = $this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}

	/**
	 * 기본환경설정>알림입니다
	 */
	public function notification()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_config_cbconfigs_notification';
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
				'field' => 'use_notification',
				'label' => '알림기능',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'notification_reply',
				'label' => '내 글에 답변글이 달렸을 때 알림',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'notification_comment',
				'label' => '내 글에 댓글이 달렸을 때 알림',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'notification_comment_comment',
				'label' => '내 댓글에 댓글이 달렸을 때 알림',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'notification_note',
				'label' => '쪽지가 도착하였을 때 알림',
				'rules' => 'trim|numeric',
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

			$array = array('use_notification', 'notification_reply', 'notification_comment', 'notification_comment_comment', 'notification_note');
			foreach ($array as $value) {
				$savedata[$value] = $this->input->post($value, null, '');
			}

			$this->Config_model->save($savedata);
			$view['view']['alert_message'] = '알림 설정이 저장되었습니다';
		}

		$getdata = $this->Config_model->get_all_meta();
		$view['view']['data'] = $getdata;

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 어드민 레이아웃을 정의합니다
		 */
		$layoutconfig = array('layout' => 'layout', 'skin' => 'notification');
		$view['layout'] = $this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}


	/**
	 * 기본환경설정>회사정보입니다
	 */
	public function company()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_config_cbconfigs_company';
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
				'field' => 'company_name',
				'label' => '회사명',
				'rules' => 'trim',
			),
			array(
				'field' => 'company_reg_no',
				'label' => '사업자등록번호',
				'rules' => 'trim',
			),
			array(
				'field' => 'company_owner',
				'label' => '대표자명',
				'rules' => 'trim',
			),
			array(
				'field' => 'company_phone',
				'label' => '대표전화번호',
				'rules' => 'trim',
			),
			array(
				'field' => 'company_fax',
				'label' => '팩스번호',
				'rules' => 'trim',
			),
			array(
				'field' => 'company_retail_sale_no',
				'label' => '통신판매업신고번호',
				'rules' => 'trim',
			),
			array(
				'field' => 'company_added_sale_no',
				'label' => '부가통신 사업자번호',
				'rules' => 'trim',
			),
			array(
				'field' => 'company_zipcode',
				'label' => '사업장 우편번호',
				'rules' => 'trim',
			),
			array(
				'field' => 'company_address',
				'label' => '사업장 주소',
				'rules' => 'trim',
			),
			array(
				'field' => 'company_admin_name',
				'label' => '정보관리책임자명',
				'rules' => 'trim',
			),
			array(
				'field' => 'company_admin_email',
				'label' => '정보관리책임자 email',
				'rules' => 'trim|valid_email',
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
				'company_name', 'company_reg_no', 'company_owner', 'company_phone',
				'company_fax', 'company_retail_sale_no', 'company_added_sale_no',
				'company_zipcode', 'company_address',
				'company_admin_name', 'company_admin_email');
			foreach ($array as $value) {
				$savedata[$value] = $this->input->post($value, null, '');
			}

			$this->Config_model->save($savedata);
			$view['view']['alert_message'] = '회사정보가 저장되었습니다';
		}

		$getdata = $this->Config_model->get_all_meta();
		$view['view']['data'] = $getdata;

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 어드민 레이아웃을 정의합니다
		 */
		$layoutconfig = array('layout' => 'layout', 'skin' => 'company');
		$view['layout'] = $this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}
}
