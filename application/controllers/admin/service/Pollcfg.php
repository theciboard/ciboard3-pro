<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Pollcfg class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 관리자>기타기능>설문조사 controller 입니다.
 */
class Pollcfg extends CB_Controller
{

	/**
	 * 관리자 페이지 상의 현재 디렉토리입니다
	 * 페이지 이동시 필요한 정보입니다
	 */
	public $pagedir = 'service/pollcfg';

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
		$eventname = 'event_admin_service_pollcfg_index';
		$this->load->event($eventname);

		if ( ! $this->db->table_exists('post_poll')) {
			redirect(admin_url('service/pollcfg/install'));
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
				'field' => 'use_poll_list',
				'label' => '설문조사 기능 사용',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'layout_poll',
				'label' => '일반레이아웃',
				'rules' => 'trim',
			),
			array(
				'field' => 'mobile_layout_poll',
				'label' => '모바일레이아웃',
				'rules' => 'trim',
			),
			array(
				'field' => 'sidebar_poll',
				'label' => '사이드바',
				'rules' => 'trim',
			),
			array(
				'field' => 'mobile_sidebar_poll',
				'label' => '모바일사이드바',
				'rules' => 'trim',
			),
			array(
				'field' => 'skin_poll',
				'label' => '일반스킨',
				'rules' => 'trim',
			),
			array(
				'field' => 'mobile_skin_poll',
				'label' => '모바일스킨',
				'rules' => 'trim',
			),
			array(
				'field' => 'site_meta_title_poll',
				'label' => '메타태그 Title',
				'rules' => 'trim',
			),
			array(
				'field' => 'site_meta_description_poll',
				'label' => '메타태그 meta description',
				'rules' => 'trim',
			),
			array(
				'field' => 'site_meta_keywords_poll',
				'label' => '메타태그 meta keywords',
				'rules' => 'trim',
			),
			array(
				'field' => 'site_meta_author_poll',
				'label' => '메타태그 meta author',
				'rules' => 'trim',
			),
			array(
				'field' => 'site_page_name_poll',
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
				'use_poll_list', 'layout_poll', 'mobile_layout_poll', 'sidebar_poll', 'mobile_sidebar_poll',
				'skin_poll', 'mobile_skin_poll', 'site_meta_title_poll', 'site_meta_description_poll',
				'site_meta_keywords_poll', 'site_meta_author_poll', 'site_page_name_poll'
			);
			foreach ($array as $value) {
				$savedata[$value] = $this->input->post($value, null, '');
			}

			$this->Config_model->save($savedata);
			$view['view']['alert_message'] = '설문조사모음 설정이 저장되었습니다';
		}

		$getdata = $this->Config_model->get_all_meta();
		$view['view']['data'] = $getdata;

		$view['view']['data']['layout_poll_option'] = get_skin_name(
			'_layout',
			set_value('layout_poll', element('layout_poll', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['mobile_layout_poll_option'] = get_skin_name(
			'_layout',
			set_value('mobile_layout_poll', element('mobile_layout_poll', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['skin_poll_option'] = get_skin_name(
			'poll',
			set_value('skin_poll', element('skin_poll', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['mobile_skin_poll_option'] = get_skin_name(
			'poll',
			set_value('mobile_skin_poll', element('mobile_skin_poll', $getdata)),
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


	/**
	 * 환경설정>설문조사 게시판별 설정 페이지입니다
	 */
	public function boards()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_service_pollcfg_boards';
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
					$metadata['use_poll'] = $this->input->post('use_poll[' . $boarddata['brd_id'] . ']', null, '');
					$metadata['use_mobile_poll'] = $this->input->post('use_mobile_poll[' . $boarddata['brd_id'] . ']', null, '');
					$metadata['access_poll_write'] = $this->input->post('access_poll_write[' . $boarddata['brd_id'] . ']', null, '');
					$metadata['access_poll_write_level'] = $this->input->post('access_poll_write_level[' . $boarddata['brd_id'] . ']', null, '');
					$metadata['access_poll_write_group'] = json_encode($this->input->post('access_poll_write_group[' . $boarddata['brd_id'] . ']', null, ''));
					$metadata['access_poll_attend'] = $this->input->post('access_poll_attend[' . $boarddata['brd_id'] . ']', null, '');
					$metadata['access_poll_attend_level'] = $this->input->post('access_poll_attend_level[' . $boarddata['brd_id'] . ']', null, '');
					$metadata['access_poll_attend_group'] = json_encode($this->input->post('access_poll_attend_group[' . $boarddata['brd_id'] . ']', null, ''));

					$this->Board_meta_model->save($boarddata['brd_id'], $metadata);
				}
			}

			$view['view']['alert_message'] = '설문조사모음 설정이 저장되었습니다';
		}

		$boardlist = $this->Board_model->get_board_list();
		if ($boardlist) {
			foreach ($boardlist as $key => $boarddata) {
				$boardlist[$key]['use_poll'] = $this->Board_meta_model->item(element('brd_id', $boarddata), 'use_poll');
				$boardlist[$key]['use_mobile_poll'] = $this->Board_meta_model->item(element('brd_id', $boarddata), 'use_mobile_poll');
				$boardlist[$key]['access_poll_write'] = $this->Board_meta_model->item(element('brd_id', $boarddata), 'access_poll_write');
				$boardlist[$key]['access_poll_write_level'] = $this->Board_meta_model->item(element('brd_id', $boarddata), 'access_poll_write_level');
				$boardlist[$key]['access_poll_write_group'] = $this->Board_meta_model->item(element('brd_id', $boarddata), 'access_poll_write_group');
				$boardlist[$key]['access_poll_attend'] = $this->Board_meta_model->item(element('brd_id', $boarddata), 'access_poll_attend');
				$boardlist[$key]['access_poll_attend_level'] = $this->Board_meta_model->item(element('brd_id', $boarddata), 'access_poll_attend_level');
				$boardlist[$key]['access_poll_attend_group'] = $this->Board_meta_model->item(element('brd_id', $boarddata), 'access_poll_attend_group');
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


	public function install()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_service_pollcfg_install';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		$view['view']['is_installed'] = '';
		if ( ! $this->db->table_exists('post_poll')) {
			
			$this->load->dbforge();

			// post_poll table
			$this->dbforge->add_field(array(
				'ppo_id' => array(
					'type' => 'INT',
					'constraint' => 11,
					'unsigned' => true,
					'auto_increment' => true,
				),
				'post_id' => array(
					'type' => 'INT',
					'constraint' => 11,
					'unsigned' => true,
					'default' => '0',
				),
				'brd_id' => array(
					'type' => 'INT',
					'constraint' => 11,
					'unsigned' => true,
					'default' => '0',
				),
				'ppo_start_datetime' => array(
					'type' => 'DATETIME',
					'null' => true,
				),
				'ppo_end_datetime' => array(
					'type' => 'DATETIME',
					'null' => true,
				),
				'ppo_title' => array(
					'type' => 'VARCHAR',
					'constraint' => '255',
					'default' => '',
				),
				'ppo_count' => array(
					'type' => 'INT',
					'constraint' => 11,
					'unsigned' => true,
					'default' => '0',
				),
				'ppo_choose_count' => array(
					'type' => 'TINYINT',
					'constraint' => 4,
					'unsigned' => true,
					'default' => '0',
				),
				'ppo_after_comment' => array(
					'type' => 'TINYINT',
					'constraint' => 4,
					'unsigned' => true,
					'default' => '0',
				),
				'ppo_point' => array(
					'type' => 'INT',
					'constraint' => 11,
					'default' => '0',
				),
				'ppo_datetime' => array(
					'type' => 'DATETIME',
					'null' => true,
				),
				'ppo_ip' => array(
					'type' => 'VARCHAR',
					'constraint' => '50',
					'default' => '',
				),
				'mem_id' => array(
					'type' => 'INT',
					'constraint' => 11,
					'unsigned' => true,
					'default' => '0',
				),
			));
			$this->dbforge->add_key('ppo_id', true);
			$this->dbforge->add_key('post_id');
			$this->dbforge->create_table('post_poll', true);


			// post_poll_item table
			$this->dbforge->add_field(array(
				'ppi_id' => array(
					'type' => 'INT',
					'constraint' => 11,
					'unsigned' => true,
					'auto_increment' => true,
				),
				'ppo_id' => array(
					'type' => 'INT',
					'constraint' => 11,
					'unsigned' => true,
					'default' => '0',
				),
				'ppi_item' => array(
					'type' => 'VARCHAR',
					'constraint' => '255',
					'default' => '',
				),
				'ppi_count' => array(
					'type' => 'INT',
					'constraint' => 11,
					'unsigned' => true,
					'default' => '0',
				),
			));
			$this->dbforge->add_key('ppi_id', true);
			$this->dbforge->add_key('ppo_id');
			$this->dbforge->create_table('post_poll_item', true);


			// post_poll_item_poll table
			$this->dbforge->add_field(array(
				'ppp_id' => array(
					'type' => 'INT',
					'constraint' => 11,
					'unsigned' => true,
					'auto_increment' => true,
				),
				'ppo_id' => array(
					'type' => 'INT',
					'constraint' => 11,
					'unsigned' => true,
					'default' => '0',
				),
				'ppi_id' => array(
					'type' => 'INT',
					'constraint' => 11,
					'unsigned' => true,
					'default' => '0',
				),
				'mem_id' => array(
					'type' => 'INT',
					'constraint' => 11,
					'unsigned' => true,
					'default' => '0',
				),
				'ppp_datetime' => array(
					'type' => 'DATETIME',
					'null' => true,
				),
				'ppp_ip' => array(
					'type' => 'VARCHAR',
					'constraint' => '50',
					'default' => '',
				),
			));
			$this->dbforge->add_key('ppp_id', true);
			$this->dbforge->add_key('ppo_id');
			$this->dbforge->add_key('ppi_id');
			$this->dbforge->add_key('mem_id');
			$this->dbforge->create_table('post_poll_item_poll', true);

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
