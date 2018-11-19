<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Smscfg class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 관리자>SMS 설정>SMS 환경설정 controller 입니다.
 */
class Smscfg extends CB_Controller
{

	/**
	 * 관리자 페이지 상의 현재 디렉토리입니다
	 * 페이지 이동시 필요한 정보입니다
	 */
	public $pagedir = 'sms/smscfg';

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

		/**
		 * 라이브러리를 로딩합니다
		 */
		$this->load->library(array('querystring'));
	}


	/**
	 * SMS 설정>SMS 환경설정 페이지입니다
	 */
	public function index()
	{

		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_sms_smscfg_index';
		$this->load->event($eventname);

		if ( ! $this->db->table_exists('sms_member')) {
			redirect(admin_url('sms/smscfg/install'));
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
				'field' => 'use_sms',
				'label' => 'SMS 사용',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'sms_icode_id',
				'label' => '아이코드 아이디',
				'rules' => 'trim',
			),
			array(
				'field' => 'sms_icode_pw',
				'label' => '아이코드 비밀번호',
				'rules' => 'trim|callback__sms_icode_check',
			),
			array(
				'field' => 'sms_admin_phone',
				'label' => '회신번호',
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

			$array = array('use_sms', 'sms_icode_id', 'sms_icode_pw', 'sms_admin_phone',);

			foreach ($array as $value) {
				$savedata[$value] = $this->input->post($value, null, '');
			}
			if ($this->input->post('sms_icode_id') && $this->input->post('sms_icode_pw')) {
				$this->load->library('smslib');
				$smsinfo = $this->smslib->get_icode_info($this->input->post('sms_icode_id'), $this->input->post('sms_icode_pw'));
				if (element('payment', $smsinfo) === 'C') {
					$savedata['sms_icode_port'] = '7296';
				} else {
					$savedata['sms_icode_port'] = '7295';
				}
			}

			$this->Config_model->save($savedata);
			$view['view']['alert_message'] = 'SMS 설정이 저장되었습니다';
		}

		$getdata = $this->Config_model->get_all_meta();
		if (element('sms_icode_id', $getdata) && element('sms_icode_pw', $getdata)) {
			$this->load->library('smslib');
			$getdata['smsinfo'] = $this->smslib->get_icode_info(element('sms_icode_id', $getdata), element('sms_icode_pw', $getdata));
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

	/**
	 * 아이코드 아이디와 패스워드가 맞는지 체크합니다
	 */
	public function _sms_icode_check($pw = '')
	{
		$id = $this->input->post('sms_icode_id');

		if (empty($id) && empty($pw)) {
			return true;
		}
		$this->load->library('smslib');
		$result = $this->smslib->get_icode_info($id, $pw);
		if (element('code', $result) === '202') {
			$this->form_validation->set_message(
				'_sms_icode_check',
				'아이코드 아이디와 패스워드가 맞지 않습니다'
			);
			return false;
		}
		return true;
	}


	public function install()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_sms_smscfg_install';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		$view['view']['is_installed'] = '';
		if ( ! $this->db->table_exists('sms_member')) {
			
			$this->load->dbforge();


			// sms_favorite table
			$this->dbforge->add_field(array(
				'sfa_id' => array(
					'type' => 'INT',
					'constraint' => 11,
					'unsigned' => true,
					'auto_increment' => true,
				),
				'sfa_title' => array(
					'type' => 'VARCHAR',
					'constraint' => '255',
					'default' => '',
				),
				'sfa_content' => array(
					'type' => 'TEXT',
					'null' => true,
				),
				'sfa_datetime' => array(
					'type' => 'DATETIME',
					'null' => true,
				),
			));
			$this->dbforge->add_key('sfa_id', true);
			if ($this->dbforge->create_table('sms_favorite', true) === false) {
				return false;
			}


			// sms_member table
			$this->dbforge->add_field(array(
				'sme_id' => array(
					'type' => 'INT',
					'constraint' => 11,
					'unsigned' => true,
					'auto_increment' => true,
				),
				'smg_id' => array(
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
				'sme_name' => array(
					'type' => 'VARCHAR',
					'constraint' => '255',
					'default' => '',
				),
				'sme_phone' => array(
					'type' => 'VARCHAR',
					'constraint' => '255',
					'default' => '',
				),
				'sme_receive' => array(
					'type' => 'TINYINT',
					'constraint' => 4,
					'unsigned' => true,
					'default' => '0',
				),
				'sme_datetime' => array(
					'type' => 'DATETIME',
					'null' => true,
				),
				'sme_memo' => array(
					'type' => 'TEXT',
					'null' => true,
				),
			));
			$this->dbforge->add_key('sme_id', true);
			$this->dbforge->add_key('smg_id');
			$this->dbforge->add_key('mem_id');
			if ($this->dbforge->create_table('sms_member', true) === false) {
				return false;
			}


			// sms_member_group table
			$this->dbforge->add_field(array(
				'smg_id' => array(
					'type' => 'INT',
					'constraint' => 11,
					'unsigned' => true,
					'auto_increment' => true,
				),
				'smg_name' => array(
					'type' => 'VARCHAR',
					'constraint' => '255',
					'default' => '',
				),
				'smg_order' => array(
					'type' => 'MEDIUMINT',
					'constraint' => 6,
					'unsigned' => true,
					'default' => '0',
				),
				'smg_datetime' => array(
					'type' => 'DATETIME',
					'null' => true,
				),
			));
			$this->dbforge->add_key('smg_id', true);
			if ($this->dbforge->create_table('sms_member_group', true) === false) {
				return false;
			}


			// sms_send_content table
			$this->dbforge->add_field(array(
				'ssc_id' => array(
					'type' => 'INT',
					'constraint' => 11,
					'unsigned' => true,
					'auto_increment' => true,
				),
				'ssc_content' => array(
					'type' => 'TEXT',
					'null' => true,
				),
				'send_mem_id' => array(
					'type' => 'INT',
					'constraint' => 11,
					'unsigned' => true,
					'default' => '0',
				),
				'ssc_send_phone' => array(
					'type' => 'VARCHAR',
					'constraint' => '255',
					'default' => '',
				),
				'ssc_booking' => array(
					'type' => 'DATETIME',
					'null' => true,
				),
				'ssc_total' => array(
					'type' => 'MEDIUMINT',
					'constraint' => 6,
					'unsigned' => true,
					'default' => '0',
				),
				'ssc_success' => array(
					'type' => 'MEDIUMINT',
					'constraint' => 6,
					'unsigned' => true,
					'default' => '0',
				),
				'ssc_fail' => array(
					'type' => 'MEDIUMINT',
					'constraint' => 6,
					'unsigned' => true,
					'default' => '0',
				),
				'ssc_datetime' => array(
					'type' => 'DATETIME',
					'null' => true,
				),
				'ssc_memo' => array(
					'type' => 'TEXT',
					'null' => true,
				),
			));
			$this->dbforge->add_key('ssc_id', true);
			if ($this->dbforge->create_table('sms_send_content', true) === false) {
				return false;
			}


			// sms_send_history table
			$this->dbforge->add_field(array(
				'ssh_id' => array(
					'type' => 'INT',
					'constraint' => 11,
					'unsigned' => true,
					'auto_increment' => true,
				),
				'ssc_id' => array(
					'type' => 'INT',
					'constraint' => 11,
					'unsigned' => true,
					'default' => '0',
				),
				'send_mem_id' => array(
					'type' => 'INT',
					'constraint' => 11,
					'unsigned' => true,
					'default' => '0',
				),
				'recv_mem_id' => array(
					'type' => 'INT',
					'constraint' => 11,
					'unsigned' => true,
					'default' => '0',
				),
				'ssh_name' => array(
					'type' => 'VARCHAR',
					'constraint' => '255',
					'default' => '',
				),
				'ssh_phone' => array(
					'type' => 'VARCHAR',
					'constraint' => '255',
					'default' => '',
				),
				'ssh_success' => array(
					'type' => 'TINYINT',
					'constraint' => 4,
					'unsigned' => true,
					'default' => '0',
				),
				'ssh_datetime' => array(
					'type' => 'DATETIME',
					'null' => true,
				),
				'ssh_memo' => array(
					'type' => 'TEXT',
					'null' => true,
				),
				'ssh_log' => array(
					'type' => 'TEXT',
					'null' => true,
				),
			));
			$this->dbforge->add_key('ssh_id', true);
			$this->dbforge->add_key('ssc_id');
			if ($this->dbforge->create_table('sms_send_history', true) === false) {
				return false;
			}

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
