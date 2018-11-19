<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Pointrankingcfg class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 관리자>기타기능>포인트랭킹 controller 입니다.
 */
class Pointrankingcfg extends CB_Controller
{

	/**
	 * 관리자 페이지 상의 현재 디렉토리입니다
	 * 페이지 이동시 필요한 정보입니다
	 */
	public $pagedir = 'service/pointrankingcfg';

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
	 * 환경설정>포인트랭킹 피드 페이지입니다
	 */
	public function index()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_service_pointrankingconfig_index';
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
				'field' => 'use_pointranking',
				'label' => '포인트랭킹 기능 사용',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'layout_pointranking',
				'label' => '일반레이아웃',
				'rules' => 'trim',
			),
			array(
				'field' => 'mobile_layout_pointranking',
				'label' => '모바일레이아웃',
				'rules' => 'trim',
			),
			array(
				'field' => 'sidebar_pointranking',
				'label' => '사이드바',
				'rules' => 'trim',
			),
			array(
				'field' => 'mobile_sidebar_pointranking',
				'label' => '모바일사이드바',
				'rules' => 'trim',
			),
			array(
				'field' => 'skin_pointranking',
				'label' => '일반스킨',
				'rules' => 'trim',
			),
			array(
				'field' => 'mobile_skin_pointranking',
				'label' => '모바일스킨',
				'rules' => 'trim',
			),
			array(
				'field' => 'site_meta_title_pointranking',
				'label' => '메타태그 Title',
				'rules' => 'trim',
			),
			array(
				'field' => 'site_meta_description_pointranking',
				'label' => '메타태그 meta description',
				'rules' => 'trim',
			),
			array(
				'field' => 'site_meta_keywords_pointranking',
				'label' => '메타태그 meta keywords',
				'rules' => 'trim',
			),
			array(
				'field' => 'site_meta_author_pointranking',
				'label' => '메타태그 meta author',
				'rules' => 'trim',
			),
			array(
				'field' => 'site_page_name_pointranking',
				'label' => '메타태그 page name',
				'rules' => 'trim',
			),
			array(
				'field' => 'site_meta_title_pointranking_month',
				'label' => '월별 메타태그 Title',
				'rules' => 'trim',
			),
			array(
				'field' => 'site_meta_description_pointranking_month',
				'label' => '월별 메타태그 meta description',
				'rules' => 'trim',
			),
			array(
				'field' => 'site_meta_keywords_pointranking_month',
				'label' => '월별 메타태그 meta keywords',
				'rules' => 'trim',
			),
			array(
				'field' => 'site_meta_author_pointranking_month',
				'label' => '월별 메타태그 meta author',
				'rules' => 'trim',
			),
			array(
				'field' => 'site_page_name_pointranking_month',
				'label' => '월별 메타태그 page name',
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
				'use_pointranking', 'layout_pointranking', 'mobile_layout_pointranking', 'sidebar_pointranking',
				'mobile_sidebar_pointranking', 'skin_pointranking', 'mobile_skin_pointranking',
				'site_meta_title_pointranking', 'site_meta_description_pointranking',
				'site_meta_keywords_pointranking', 'site_meta_author_pointranking', 'site_page_name_pointranking',
				'site_meta_title_pointranking_month', 'site_meta_description_pointranking_month',
				'site_meta_keywords_pointranking_month', 'site_meta_author_pointranking_month',
				'site_page_name_pointranking_month'
			);
			foreach ($array as $value) {
				$savedata[$value] = $this->input->post($value, null, '');
			}

			$this->Config_model->save($savedata);
			$view['view']['alert_message'] = '포인트 랭킹 설정이 저장되었습니다';
		}

		$getdata = $this->Config_model->get_all_meta();
		$view['view']['data'] = $getdata;

		$view['view']['data']['layout_pointranking_option'] = get_skin_name(
			'_layout',
			set_value('layout_pointranking', element('layout_pointranking', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['mobile_layout_pointranking_option'] = get_skin_name(
			'_layout',
			set_value('mobile_layout_pointranking', element('mobile_layout_pointranking', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['skin_pointranking_option'] = get_skin_name(
			'pointranking',
			set_value('skin_pointranking', element('skin_pointranking', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['mobile_skin_pointranking_option'] = get_skin_name(
			'pointranking',
			set_value('mobile_skin_pointranking', element('mobile_skin_pointranking', $getdata)),
			'기본설정따름'
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
}
