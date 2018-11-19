<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Attendancecfg class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 관리자>기타기능>출석체크 controller 입니다.
 */
class Attendancecfg extends CB_Controller
{

	/**
	 * 관리자 페이지 상의 현재 디렉토리입니다
	 * 페이지 이동시 필요한 정보입니다
	 */
	public $pagedir = 'service/attendancecfg';

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
	 * 환경설정>출석체크 페이지입니다
	 */
	public function index()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_service_attendancecfg_index';
		$this->load->event($eventname);

		if ( ! $this->db->table_exists('attendance')) {
			redirect(admin_url('service/attendancecfg/install'));
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
				'field' => 'use_attendance',
				'label' => '출석체크 기능 사용',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'attendance_order',
				'label' => '정렬방법',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'attendance_page_count',
				'label' => '한페이지당 출력개수',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'attendance_show_attend_time',
				'label' => '출석시간표시여부 - PC',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'attendance_mobile_show_attend_time',
				'label' => '출석시간표시여부 - 모바일',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'attendance_date_style',
				'label' => '출석시간표시(PC)',
				'rules' => 'trim',
			),
			array(
				'field' => 'attendance_date_style_manual',
				'label' => '출석시간표시(PC) 매뉴얼',
				'rules' => 'trim',
			),
			array(
				'field' => 'attendance_mobile_date_style',
				'label' => '출석시간표시(모바일)',
				'rules' => 'trim',
			),
			array(
				'field' => 'attendance_mobile_date_style_manual',
				'label' => '출석시간표시(모바일) 매뉴얼',
				'rules' => 'trim',
			),
			array(
				'field' => 'attendance_memo_length',
				'label' => '인사말길이',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'layout_attendance',
				'label' => '일반레이아웃',
				'rules' => 'trim',
			),
			array(
				'field' => 'mobile_layout_attendance',
				'label' => '모바일레이아웃',
				'rules' => 'trim',
			),
			array(
				'field' => 'sidebar_attendance',
				'label' => '사이드바',
				'rules' => 'trim',
			),
			array(
				'field' => 'mobile_sidebar_attendance',
				'label' => '모바일사이드바',
				'rules' => 'trim',
			),
			array(
				'field' => 'skin_attendance',
				'label' => '일반스킨',
				'rules' => 'trim',
			),
			array(
				'field' => 'mobile_skin_attendance',
				'label' => '모바일스킨',
				'rules' => 'trim',
			),
			array(
				'field' => 'site_meta_title_attendance',
				'label' => '메타태그 Title',
				'rules' => 'trim',
			),
			array(
				'field' => 'site_meta_description_attendance',
				'label' => '메타태그 meta description',
				'rules' => 'trim',
			),
			array(
				'field' => 'site_meta_keywords_attendance',
				'label' => '메타태그 meta keywords',
				'rules' => 'trim',
			),
			array(
				'field' => 'site_meta_author_attendance',
				'label' => '메타태그 meta author',
				'rules' => 'trim',
			),
			array(
				'field' => 'site_page_name_attendance',
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
				'use_attendance', 'attendance_order', 'attendance_page_count', 'attendance_show_attend_time',
				'attendance_mobile_show_attend_time', 'attendance_date_style', 'attendance_date_style_manual',
				'attendance_mobile_date_style', 'attendance_mobile_date_style_manual', 'attendance_memo_length',
				'layout_attendance', 'mobile_layout_attendance', 'sidebar_attendance', 'mobile_sidebar_attendance',
				'skin_attendance', 'mobile_skin_attendance', 'site_meta_title_attendance',
				'site_meta_description_attendance', 'site_meta_keywords_attendance',
				'site_meta_author_attendance', 'site_page_name_attendance'
			);
			foreach ($array as $value) {
				$savedata[$value] = $this->input->post($value, null, '');
			}

			$this->Config_model->save($savedata);
			$view['view']['alert_message'] = '출석체크 기본설정이 저장되었습니다';
		}

		$getdata = $this->Config_model->get_all_meta();
		if ( ! isset($getdata['attendance_start_time'])) {

			$initdata['attendance_page_count'] = '100';
			$initdata['attendance_show_attend_time'] = '1';
			$initdata['attendance_mobile_show_attend_time'] = '1';
			$initdata['attendance_memo_length'] = '30';
			$initdata['attendance_start_time'] = '00:00:00';
			$initdata['attendance_end_time'] = '23:59:59';
			$initdata['attendance_point'] = '10';
			$initdata['attendance_default_memo'] = '안녕하세요^^
오늘 하루도 신나게~
좋은 하루입니다 ^^*
오늘도 행복하시길 !!
상쾌한 하루되세요 @,.@
반갑습니다.';
			$this->Config_model->save($initdata);

		}
		$getdata = $this->Config_model->get_all_meta();
		$view['view']['data'] = $getdata;

		$view['view']['data']['layout_attendance_option'] = get_skin_name(
			'_layout',
			set_value('layout_attendance', element('layout_attendance', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['mobile_layout_attendance_option'] = get_skin_name(
			'_layout',
			set_value('mobile_layout_attendance', element('mobile_layout_attendance', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['skin_attendance_option'] = get_skin_name(
			'attendance',
			set_value('skin_attendance', element('skin_attendance', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['mobile_skin_attendance_option'] = get_skin_name(
			'attendance',
			set_value('mobile_skin_attendance', element('mobile_skin_attendance', $getdata)),
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
	 * 환경설정>출석체크 페이지입니다
	 */
	public function points()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_service_attendancecfg_points';
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
				'field' => 'attendance_start_time',
				'label' => '출석가능시간 시작',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'attendance_end_time',
				'label' => '출석가능시간 종료',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'attendance_point',
				'label' => '출석포인트',
				'rules' => 'trim|required|numeric',
			),
			array(
				'field' => 'attendance_point_1',
				'label' => '1등포인트',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'attendance_point_2',
				'label' => '2등포인트',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'attendance_point_3',
				'label' => '3등포인트',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'attendance_point_4',
				'label' => '4등포인트',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'attendance_point_5',
				'label' => '5등포인트',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'attendance_point_6',
				'label' => '6등포인트',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'attendance_point_7',
				'label' => '7등포인트',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'attendance_point_8',
				'label' => '8등포인트',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'attendance_point_9',
				'label' => '9등포인트',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'attendance_point_10',
				'label' => '10등포인트',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'attendance_point_regular',
				'label' => '개근포인트',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'attendance_point_regular_days',
				'label' => '개긴포인트기간',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'attendance_default_memo',
				'label' => '기본인사말',
				'rules' => 'trim',
			),
			array(
				'field' => 'attendance_spam_keyword',
				'label' => '단어필터링',
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
				'attendance_start_time', 'attendance_end_time', 'attendance_point', 'attendance_point_1',
				'attendance_point_2', 'attendance_point_3', 'attendance_point_4', 'attendance_point_5',
				'attendance_point_6', 'attendance_point_7', 'attendance_point_8', 'attendance_point_9',
				'attendance_point_10', 'attendance_point_regular', 'attendance_point_regular_days',
				'attendance_default_memo', 'attendance_spam_keyword'
			);
			foreach ($array as $value) {
				$savedata[$value] = $this->input->post($value, null, '');
			}

			$this->Config_model->save($savedata);
			$view['view']['alert_message'] = '출석체크 설정이 저장되었습니다';
		}

		$getdata = $this->Config_model->get_all_meta();
		$view['view']['data'] = $getdata;

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 어드민 레이아웃을 정의합니다
		 */
		$layoutconfig = array('layout' => 'layout', 'skin' => 'points');
		$view['layout'] = $this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}

	/**
	 * 오래된 출석로그삭제 페이지입니다
	 */
	public function cleanlog()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_service_attendancecfg_cleanlog';
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
				'field' => 'day',
				'label' => '기간',
				'rules' => 'trim|required|numeric|is_natural',
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

			$this->load->model('Attendance_model');
			if ($this->input->post('criterion') && $this->input->post('day')) {
				$deletewhere = array(
					'att_datetime <=' => $this->input->post('criterion'),
				);
				$this->Attendance_model->delete_where($deletewhere);
				$view['view']['alert_message'] = '총 ' . number_format($this->input->post('log_count')) . ' 건의 '
					. $this->input->post('day') . '일 이상된 출석체크로그가 모두 삭제되었습니다';
			} else {
				$criterion = cdate('Y-m-d H:i:s', ctimestamp() - $this->input->post('day') * 24 * 60 * 60);
				$countwhere = array(
					'att_datetime <=' => $criterion,
				);
				$log_count = $this->Attendance_model->count_by($countwhere);
				$view['view']['criterion'] = $criterion;
				$view['view']['day'] = $this->input->post('day');
				$view['view']['log_count'] = $log_count;
				if ($log_count > 0) {
					$view['view']['msg'] = '총 ' . number_format($log_count) . ' 건의 ' . $this->input->post('day')
						. '일 이상된 출석체크로그가 발견되었습니다. 이를 모두 삭제하시겠습니까?';
				} else {
					$view['view']['alert_message'] = $this->input->post('day') . '일 이상된 출석체크로그가 발견되지 않았습니다';
				}
			}
		}

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 어드민 레이아웃을 정의합니다
		 */
		$layoutconfig = array('layout' => 'layout', 'skin' => 'cleanlog');
		$view['layout'] = $this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}

	public function install()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_service_attendancecfg_install';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		$view['view']['is_installed'] = '';
		if ( ! $this->db->table_exists('attendance')) {
			
			$this->load->dbforge();

			// attendance table
			$this->dbforge->add_field(array(
				'att_id' => array(
					'type' => 'INT',
					'constraint' => 11,
					'unsigned' => true,
					'auto_increment' => true,
				),
				'mem_id' => array(
					'type' => 'INT',
					'constraint' => 11,
					'unsigned' => true,
					'default' => '0',
				),
				'att_point' => array(
					'type' => 'INT',
					'constraint' => 11,
					'default' => '0',
				),
				'att_memo' => array(
					'type' => 'VARCHAR',
					'constraint' => '255',
					'null' => true,
				),
				'att_continuity' => array(
					'type' => 'INT',
					'constraint' => 11,
					'unsigned' => true,
					'default' => '0',
				),
				'att_ranking' => array(
					'type' => 'INT',
					'constraint' => 11,
					'unsigned' => true,
					'default' => '0',
				),
				'att_date' => array(
					'type' => 'DATE',
					'null' => true,
				),
				'att_datetime' => array(
					'type' => 'DATETIME',
					'null' => true,
				),
			));
			$this->dbforge->add_key('att_id', true);
			$this->dbforge->add_key(array('att_datetime', 'mem_id'));
			$this->dbforge->create_table('attendance', true);
			$this->db->query('ALTER TABLE ' . $this->db->dbprefix . 'attendance ADD UNIQUE KEY att_date_mem_id (`att_date`, `mem_id`)');

			$configdata = array(
				'site_meta_title_attendance' => '출석체크 - {홈페이지제목}',
			);
			$this->cache->delete('config-model-get');
			$this->cache->clean();
			$this->Config_model->save($configdata);

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
