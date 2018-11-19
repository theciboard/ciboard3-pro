<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Levelupcfg class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 관리자>기타기능>레벨업설정 controller 입니다.
 */
class Levelupcfg extends CB_Controller
{

	/**
	 * 관리자 페이지 상의 현재 디렉토리입니다
	 * 페이지 이동시 필요한 정보입니다
	 */
	public $pagedir = 'service/levelupcfg';

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
	 * 환경설정>레벨업 페이지입니다
	 */
	public function index()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_service_levelupcfg_index';
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
				'field' => 'use_levelup',
				'label' => '레벨업 기능 사용',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'layout_levelup',
				'label' => '일반레이아웃',
				'rules' => 'trim',
			),
			array(
				'field' => 'mobile_layout_levelup',
				'label' => '모바일레이아웃',
				'rules' => 'trim',
			),
			array(
				'field' => 'sidebar_levelup',
				'label' => '사이드바',
				'rules' => 'trim',
			),
			array(
				'field' => 'mobile_sidebar_levelup',
				'label' => '모바일사이드바',
				'rules' => 'trim',
			),
			array(
				'field' => 'skin_levelup',
				'label' => '일반스킨',
				'rules' => 'trim',
			),
			array(
				'field' => 'mobile_skin_levelup',
				'label' => '모바일스킨',
				'rules' => 'trim',
			),
			array(
				'field' => 'site_meta_title_levelup',
				'label' => '메타태그 Title',
				'rules' => 'trim',
			),
			array(
				'field' => 'site_meta_description_levelup',
				'label' => '메타태그 meta description',
				'rules' => 'trim',
			),
			array(
				'field' => 'site_meta_keywords_levelup',
				'label' => '메타태그 meta keywords',
				'rules' => 'trim',
			),
			array(
				'field' => 'site_meta_author_levelup',
				'label' => '메타태그 meta author',
				'rules' => 'trim',
			),
			array(
				'field' => 'site_page_name_levelup',
				'label' => '메타태그 page name',
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

			$levelupconfig = array();
			$levelupconfig['use'] = array();
			if ($this->input->post('chk')) {
				foreach ($this->input->post('chk') as $val) {
					$levelupconfig['use'][] = $val;
				}
			}
			$levelupconfig['register'] = $this->input->post('register', null, '');
			$levelupconfig['point_required'] = $this->input->post('point_required', null, '');
			$levelupconfig['point_use'] = $this->input->post('point_use', null, '');
			$levelupconfig['post_num'] = $this->input->post('post_num', null, '');
			$levelupconfig['comment_num'] = $this->input->post('comment_num', null, '');

			$savedata['levelupconfig'] = json_encode($levelupconfig);

			$array = array(
				'use_levelup', 'layout_levelup', 'mobile_layout_levelup', 'sidebar_levelup',
				'mobile_sidebar_levelup', 'skin_levelup', 'mobile_skin_levelup', 'site_meta_title_levelup',
				'site_meta_description_levelup', 'site_meta_keywords_levelup', 'site_meta_author_levelup',
				'site_page_name_levelup'
			);
			foreach ($array as $value) {
				$savedata[$value] = $this->input->post($value, null, '');
			}

			$this->Config_model->save($savedata);
			$view['view']['alert_message'] = '레벨업 설정이 저장되었습니다';
		}

		$getdata = $this->Config_model->get_all_meta();
		$view['view']['data'] = $getdata;

		$view['view']['data']['layout_levelup_option'] = get_skin_name(
			'_layout',
			set_value('layout_levelup', element('layout_levelup', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['mobile_layout_levelup_option'] = get_skin_name(
			'_layout',
			set_value('mobile_layout_levelup', element('mobile_layout_levelup', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['skin_levelup_option'] = get_skin_name(
			'levelup',
			set_value('skin_levelup', element('skin_levelup', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['mobile_skin_levelup_option'] = get_skin_name(
			'levelup',
			set_value('mobile_skin_levelup', element('mobile_skin_levelup', $getdata)),
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
