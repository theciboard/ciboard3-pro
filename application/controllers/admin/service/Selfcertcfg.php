<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Selfcertcfg class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 관리자>기타기능>본인인증 controller 입니다.
 */
class Selfcertcfg extends CB_Controller
{

	/**
	 * 관리자 페이지 상의 현재 디렉토리입니다
	 * 페이지 이동시 필요한 정보입니다
	 */
	public $pagedir = 'service/selfcertcfg';

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
	 * 환경설정>설문조사 피드 페이지입니다
	 */
	public function index()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_service_selfcertcfg_index';
		$this->load->event($eventname);

		if ( ! $this->db->table_exists('member_selfcert_history')) {
			redirect(admin_url('service/selfcertcfg/install'));
		}
		
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
				'field' => 'use_selfcert',
				'label' => '본인확인 서비스 기능',
				'rules' => 'trim|numeric|is_natural|callback__selfcert_required',
			),
			array(
				'field' => 'use_selfcert_required',
				'label' => '회원가입시 본인확인 필수',
				'rules' => 'trim|numeric|is_natural',
			),
			array(
				'field' => 'selfcert_try_limit',
				'label' => '본인확인 회수 제한',
				'rules' => 'trim|numeric|is_natural',
			),
			array(
				'field' => 'use_selfcert_test',
				'label' => '테스트 여부',
				'rules' => 'trim|numeric|is_natural',
			),
			array(
				'field' => 'use_selfcert_ipin',
				'label' => '아이핀 본인확인',
				'rules' => 'trim',
			),
			array(
				'field' => 'use_selfcert_phone',
				'label' => '휴대폰 본인확인',
				'rules' => 'trim',
			),
			array(
				'field' => 'selfcert_kcb_mid',
				'label' => '코리아크레딧뷰로 KCB 회원사ID',
				'rules' => 'trim',
			),
			array(
				'field' => 'selfcert_kcp_mid',
				'label' => '한국사이버결제 KCP 사이트코드',
				'rules' => 'trim',
			),
			array(
				'field' => 'selfcert_lg_mid',
				'label' => 'LG유플러스 상점아이디',
				'rules' => 'trim',
			),
			array(
				'field' => 'selfcert_lg_key',
				'label' => 'LG유플러스 상점아이디',
				'rules' => 'trim',
			),
			array(
				'field' => 'layout_selfcert',
				'label' => '일반레이아웃',
				'rules' => 'trim',
			),
			array(
				'field' => 'mobile_layout_selfcert',
				'label' => '모바일레이아웃',
				'rules' => 'trim',
			),
			array(
				'field' => 'sidebar_selfcert',
				'label' => '사이드바',
				'rules' => 'trim',
			),
			array(
				'field' => 'mobile_sidebar_selfcert',
				'label' => '모바일사이드바',
				'rules' => 'trim',
			),
			array(
				'field' => 'skin_selfcert',
				'label' => '일반스킨',
				'rules' => 'trim',
			),
			array(
				'field' => 'mobile_skin_selfcert',
				'label' => '모바일스킨',
				'rules' => 'trim',
			),
			array(
				'field' => 'site_meta_title_selfcert',
				'label' => '메타태그 Title',
				'rules' => 'trim',
			),
			array(
				'field' => 'site_meta_description_selfcert',
				'label' => '메타태그 meta description',
				'rules' => 'trim',
			),
			array(
				'field' => 'site_meta_keywords_selfcert',
				'label' => '메타태그 meta keywords',
				'rules' => 'trim',
			),
			array(
				'field' => 'site_meta_author_selfcert',
				'label' => '메타태그 meta author',
				'rules' => 'trim',
			),
			array(
				'field' => 'site_page_name_selfcert',
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

			$array = array(
				'use_selfcert', 'use_selfcert_required', 'selfcert_try_limit',
				'use_selfcert_test', 'use_selfcert_ipin', 'use_selfcert_phone',
				'selfcert_kcb_mid', 'selfcert_kcp_mid', 'selfcert_lg_mid', 'selfcert_lg_key',
				'layout_selfcert', 'mobile_layout_selfcert', 'sidebar_selfcert', 'mobile_sidebar_selfcert',
				'skin_selfcert', 'mobile_skin_selfcert', 'site_meta_title_selfcert', 'site_meta_description_selfcert',
				'site_meta_keywords_selfcert', 'site_meta_author_selfcert', 'site_page_name_selfcert'
			);
			$need_to_valid_check = array('selfcert_kcb_mid', 'selfcert_kcp_mid', 'selfcert_lg_mid', 'selfcert_lg_key');
			foreach ($array as $value) {
				$savedata[$value] = $this->input->post($value, null, '');
				if (in_array($value, $need_to_valid_check)) {
					$savedata[$value] = preg_replace('/[^a-z0-9_\-\.]/i', '', $savedata[$value]);
				}
			}
			$this->Config_model->save($savedata);
			$view['view']['alert_message'] = '본인확인 설정이 저장되었습니다';
		}

		$getdata = $this->Config_model->get_all_meta();
		if ( ! isset($getdata['selfcert_try_limit']) OR ! $getdata['selfcert_try_limit']) {
			$getdata['selfcert_try_limit'] = 0;
		}
		$view['view']['data'] = $getdata;

		$view['view']['data']['layout_selfcert_option'] = get_skin_name(
			'_layout',
			set_value('layout_selfcert', element('layout_selfcert', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['mobile_layout_selfcert_option'] = get_skin_name(
			'_layout',
			set_value('mobile_layout_selfcert', element('mobile_layout_selfcert', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['skin_selfcert_option'] = get_skin_name(
			'selfcert',
			set_value('skin_selfcert', element('skin_selfcert', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['mobile_skin_selfcert_option'] = get_skin_name(
			'selfcert',
			set_value('mobile_skin_selfcert', element('mobile_skin_selfcert', $getdata)),
			'기본설정따름'
		);

		// 본인확인 모듈 실행권한 체크
		if (element('use_selfcert', $getdata)) {
			$this->load->helper('module_exec_check');
			if ($error = module_exec_check(element('use_selfcert_ipin', $getdata))) echo '<script type="text/javascript">alert("' . html_escape($error) . '");</script>';
			if (element('use_selfcert_ipin', $getdata) != element('use_selfcert_phone', $getdata) && $error = module_exec_check(element('use_selfcert_phone', $getdata))) echo '<script type="text/javascript">alert("' . html_escape($error) . '");</script>';
		}

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
	 * 환경설정>본인인증 게시판별 설정 페이지입니다
	 */
	public function boards()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_service_selfcertcfg_boards';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		$this->load->model(array('Board_model', 'Board_meta_model'));

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

			$boardlist = $this->Board_model->get_board_list();
			if ($boardlist) {
				foreach ($boardlist as $key => $boarddata) {
					$metadata = array();
					$metadata['access_list_selfcert'] = $this->input->post('access_list_selfcert[' . $boarddata['brd_id'] . ']', null, '');
					$metadata['access_view_selfcert'] = $this->input->post('access_view_selfcert[' . $boarddata['brd_id'] . ']', null, '');
					$metadata['access_write_selfcert'] = $this->input->post('access_write_selfcert[' . $boarddata['brd_id'] . ']', null, '');
					$metadata['access_comment_selfcert'] = $this->input->post('access_comment_selfcert[' . $boarddata['brd_id'] . ']', null, '');

					$this->Board_meta_model->save($boarddata['brd_id'], $metadata);
				}
			}

			$view['view']['alert_message'] = '본인인증 설정이 저장되었습니다';
		}

		$boardlist = $this->Board_model->get_board_list();
		if ($boardlist) {
			foreach ($boardlist as $key => $boarddata) {
				$boardlist[$key]['access_list_selfcert'] = $this->Board_meta_model->item(element('brd_id', $boarddata), 'access_list_selfcert');
				$boardlist[$key]['access_view_selfcert'] = $this->Board_meta_model->item(element('brd_id', $boarddata), 'access_view_selfcert');
				$boardlist[$key]['access_write_selfcert'] = $this->Board_meta_model->item(element('brd_id', $boarddata), 'access_write_selfcert');
				$boardlist[$key]['access_comment_selfcert'] = $this->Board_meta_model->item(element('brd_id', $boarddata), 'access_comment_selfcert');
			}
		}
		$view['view']['boardlist'] = $boardlist;
		
		$getdata = array();
		$getdata['config_max_level'] = $this->cbconfig->item('max_level');
		$this->load->model('Member_group_model');
		$getdata['mgroup'] = $this->Member_group_model
			->get_admin_list('', '', '', '', 'mgr_order', 'asc');
		$view['view']['data'] = $getdata;


		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 어드민 레이아웃을 정의합니다
		 */
		$layoutconfig = array('layout' => 'layout', 'skin' => 'boards');
		$view['layout'] = $this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}

	public function _selfcert_required($str)
	{
		if ($this->input->post('use_selfcert') && ! $this->input->post('use_selfcert_ipin') && ! $this->input->post('use_selfcert_phone')) {
			$this->form_validation->set_message(
				'_selfcert_required',
				'본인확인 서비스 기능을 사용시, 아이핀본인확인 또는 휴대폰본인확인 서비스 중 하나는 선택하셔야 합니다'
			);
			return false;
		}
		return true;
	}



	public function install()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_service_selfcertcfg_install';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		$view['view']['is_installed'] = '';
		if ( ! $this->db->table_exists('member_selfcert_history')) {
			
			$this->load->dbforge();

			$this->dbforge->add_field(array(
				'msh_id' => array(
					'type' => 'INT',
					'constraint' => 11,
					'unsigned' => TRUE,
					 'auto_increment' => TRUE,
				),
				'mem_id' => array(
					'type' => 'INT',
					'constraint' => 11,
					'unsigned' => TRUE,
					'default' => '0',
				),
				'msh_company' => array(
					'type' => 'VARCHAR',
					'constraint' => '255',
					'default' => '',
				),
				'msh_certtype' => array(
					'type' => 'VARCHAR',
					'constraint' => '255',
					'default' => '',
				),
				'msh_cert_key' => array(
					'type' => 'VARCHAR',
					'constraint' => '255',
					'default' => '',
				),
				'msh_datetime' => array(
					'type' => 'DATETIME',
					'null' => true,
				),
				'msh_ip' => array(
					'type' => 'VARCHAR',
					'constraint' => '50',
					'default' => '',
				),
			));
			$this->dbforge->add_key('msh_id', TRUE);
			$this->dbforge->add_key('mem_id');
			$this->dbforge->create_table('member_selfcert_history');


			$view['view']['is_installed'] = '1';
		}

	
		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 어드민 레이아웃을 정의합니다
		 */
		$layoutconfig = array('layout' => 'layout', 'skin' => 'install');
		$view['layout'] = $this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));

	}
}
