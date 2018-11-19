<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Scheduler class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 관리자>환경설정>스케쥴러 관리 controller 입니다.
 */
class Scheduler extends CB_Controller
{

	/**
	 * 관리자 페이지 상의 현재 디렉토리입니다
	 * 페이지 이동시 필요한 정보입니다
	 */
	public $pagedir = 'config/scheduler';

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
	 * 스케쥴러 관리 페이지입니다
	 */
	public function index()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_config_scheduler_index';
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
				'field' => 's',
				'label' => '스케쥴러',
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

			$updatedata = $this->input->post();

			if ( ! isset($updatedata['library_name']) OR ! is_array($updatedata['library_name'])) {
				$savedata = array(
					'scheduler' => '',
				);
				$this->Config_model->save($savedata);
				$view['view']['alert_message'] = '정상적으로 저장되었습니다';
			} else {
				$array_count_values = array_count_values($updatedata['library_name']);
				$fail = false;
				if ($fail === false) {
					foreach ($array_count_values as $akey => $aval) {
						if ($aval > 1) {
							$view['view']['warning_message'] = $akey . ' 값이 ' . $aval . ' 회 중복 입력되었습니다. 라이브러리명이 중복되지 않게 입력해주세요';
							$fail = true;
							break;
						}
					}
				}
				if ($fail === false) {
					foreach (element('library_name', $updatedata) as $fkey => $fval) {
						if (empty($fval)) {
							$view['view']['warning_message'] = '비어있는 라이브러리명이 있습니다. 라이브러리 값을 빠뜨리지 말고 입력해주세요';
							$fail = true;
							break;
						}
					}
				}
				if ($fail === false) {
					$order = 0;
					$update = array();

					$alldata = $this->Config_model->get_all_meta();

					$origindata = json_decode(element('scheduler', $alldata), true);

					foreach (element('key', $updatedata) as $key => $value) {

						if ($value) {
							$update[$value] = array(
								'library_name' => element($order, element('library_name', $updatedata)),
								'interval_field_name' => element($value, element('interval_field_name', $updatedata)),
								'lasttime' => element('lasttime', element(element($order, element('library_name', $updatedata)), $origindata)),

							);
						} else {
							$update[$updatedata['library_name'][$order]] = array(
								'library_name' => element($order, element('library_name', $updatedata)),
								'interval_field_name' => element($key, element('interval_field_name', $updatedata)),
							);
						}
						$order++;
					}

					$savedata = array(
						'scheduler' => json_encode($update),
					);
					$this->Config_model->save($savedata);
					$view['view']['alert_message'] = '정상적으로 저장되었습니다';
				}
			}
		}

		$getdata = $this->Config_model->get_all_meta();

		$getdata['result'] = json_decode(element('scheduler', $getdata), true);
		$getdata['scheduler_interval'] = json_decode(element('scheduler_interval', $getdata), true);

		if ( ! element('scheduler_interval', $getdata) ) {
			alert('스케쥴 주기명을 먼저 생성하신 후에 스케쥴러 등록이 가능합니다. 스케쥴 주기명 생성 페이지로 이동합니다', admin_url($this->pagedir . '/interval'));
		}

		if ($getdata['result'] && is_array($getdata['result'])) {
			foreach ($getdata['result'] as $key => $value) {
				$getdata['result'][$key]['class_exists'] = '';
				if (file_exists(APPPATH . 'libraries/Scheduler/' . $key . '.php')) {
					$this->load->library('Scheduler/' . $key);
					if (class_exists($key)) {
						$getdata['result'][$key]['class_exists'] = '1';
					}
				}
				$getdata['result'][$key]['lastexecutetime'] = element('lasttime', $value) ? cdate("Y-m-d H:i:s", element('lasttime', $value)) : '-';
				$getdata['result'][$key]['nextexecutetime'] = '-';
				if (
					element('lasttime', $value)
					&& ($scheduler_interval = element('scheduler_interval', $getdata))
					&& ($field_name = element(element('interval_field_name', $value), $scheduler_interval))
					&& element('interval', $field_name)
				) {
					$nexttime = element('lasttime', $value) + element('interval', $field_name);
					$getdata['result'][$key]['nextexecutetime'] = $nexttime ? cdate("Y-m-d H:i:s", $nexttime) : '-';
				}
			}
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
	 * 스케쥴러 주기 관리 페이지입니다
	 */
	public function interval()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_config_scheduler_interval';
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
				'field' => 's',
				'label' => '스케쥴러',
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

			$updatedata = $this->input->post();

			if ( ! isset($updatedata['field_name']) OR ! is_array($updatedata['field_name'])) {
				$savedata = array(
					'scheduler_interval' => '',
				);
				$this->Config_model->save($savedata);
				$view['view']['alert_message'] = '정상적으로 저장되었습니다';
			} else {
				$array_count_values = array_count_values($updatedata['field_name']);
				$fail = false;
				if ($fail === false) {
					foreach ($array_count_values as $akey => $aval) {
						if ($aval > 1) {
							$view['view']['warning_message'] = $akey . ' 값이 ' . $aval . ' 회 중복 입력되었습니다. ID 값이 중복되지 않게 입력해주세요';
							$fail = true;
							break;
						}
					}
				}
				if ($fail === false) {
					foreach (element('field_name', $updatedata) as $fkey => $fval) {
						if (empty($fval)) {
							$view['view']['warning_message'] = '비어있는 ID 값이 있습니다. ID 값을 빠뜨리지 말고 입력해주세요';
							$fail = true;
							break;
						}
					}
				}
				if ($fail === false) {
					foreach (element('display_name', $updatedata) as $fkey => $fval) {
						if (empty($fval)) {
							$view['view']['warning_message'] = '비어있는 주기명이 있습니다. 입력항목제목 값을 빠뜨리지 말고 입력해주세요';
							$fail = true;
							break;
						}
					}
				}
				if ($fail === false) {
					$order = 0;
					$update = array();

					foreach (element('key', $updatedata) as $key => $value) {
						if ($value) {
							$update[$value] = array(
								'field_name' => element($order, element('field_name', $updatedata)),
								'interval' => element($order, element('interval', $updatedata)),
								'display_name' => element($order, element('display_name', $updatedata)),
							);
						} else {
							$update[$updatedata['field_name'][$order]] = array(
								'field_name' => element($order, element('field_name', $updatedata)),
								'interval' => element($order, element('interval', $updatedata)),
								'display_name' => element($order, element('display_name', $updatedata)),
							);
						}
						$order++;
					}

					$savedata = array(
						'scheduler_interval' => json_encode($update),
					);
					$this->Config_model->save($savedata);
					$view['view']['alert_message'] = '정상적으로 저장되었습니다';
				}
			}
		}

		$getdata = $this->Config_model->get_all_meta();

		$getdata['scheduler'] = json_decode(element('scheduler', $getdata), true);
		$getdata['result'] = json_decode(element('scheduler_interval', $getdata), true);

		if ($getdata['scheduler'] && is_array($getdata['scheduler'])) {
			foreach ($getdata['scheduler'] as $key => $value) {
				if ( ! isset($getdata['result'][element('interval_field_name', $value)]['registered_scheduler'])) {
					$getdata['result'][element('interval_field_name', $value)]['registered_scheduler'] = 0;
				}
				$getdata['result'][element('interval_field_name', $value)]['registered_scheduler']++;
			}
		}

		$view['view']['data'] = $getdata;

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 어드민 레이아웃을 정의합니다
		 */
		$layoutconfig = array('layout' => 'layout', 'skin' => 'interval');
		$view['layout'] = $this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}


	/**
	 * 스케쥴러 실행 페이지입니다
	 */
	public function execute($libraryname)
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_config_scheduler_execute';
		$this->load->event($eventname);

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		if (file_exists(APPPATH . 'libraries/Scheduler/' . $libraryname . '.php')) {
			$this->load->library('Scheduler/' . $libraryname);
			$s = new $libraryname();
			if (method_exists($s, 'scheduler')) {
				$s->scheduler();

				$scheduler_data = $this->cbconfig->item('scheduler');
				$scheduler_array = json_decode($scheduler_data, true);
				$interval_data = $this->cbconfig->item('scheduler_interval');
				$interval_array = json_decode($interval_data, true);

				$scheduler_array[$libraryname]['lasttime'] = ctimestamp();

				$savedata['scheduler'] = json_encode($scheduler_array);
				$this->load->model('Config_model');
				$this->Config_model->save($savedata);
				exit(json_encode(array('success' => 'ok', 'message' => $libraryname . ' 라이브러리가 실행되었습니다.')));
			} else {
				exit(json_encode(array('success' => 'fail', 'message' => $libraryname . ' 클래스 안에 scheduler 메소드가 존재하지 않습니다.')));
			}
		} else {
			exit(json_encode(array('success' => 'fail', 'message' => $libraryname . ' 라이브러리가 존재하지 않습니다.')));
		}
	}
}
