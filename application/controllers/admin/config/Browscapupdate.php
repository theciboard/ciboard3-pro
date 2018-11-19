<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Browscapupdate class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 관리자>환경설정>Browscap 업데이트 controller 입니다.
 */
class Browscapupdate extends CB_Controller
{

	/**
	 * 관리자 페이지 상의 현재 디렉토리입니다
	 * 페이지 이동시 필요한 정보입니다
	 */
	public $pagedir = 'config/browscapupdate';

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

		/**
		 * 라이브러리를 로딩합니다
		 */
		$this->load->library(array('querystring'));
	}

	/**
	 * Browscap 업데이트 페이지입니다
	 */
	public function index()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_config_browscapupdate_index';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		/**
		 * 어드민 레이아웃을 정의합니다
		 */
		$layoutconfig = array('layout' => 'layout', 'skin' => 'index');
		$view['layout'] = $this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}

	public function update()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_config_browscapupdate_update';
		$this->load->event($eventname);

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		$dir = FCPATH . 'plugin/browscap';
		if ( ! (is_readable($dir) && is_writeable($dir))) {
			exit(json_encode(array('success' => 'no', 'message' => 'plugin/browscap 경로가 파일쓰기가 금지되어있습니다. 퍼미션 확인 후에 다시 실행바랍니다')));
		}

		ini_set('memory_limit', '-1');
		require_once FCPATH . 'plugin/browscap/Browscap.php';

		$browscap = new phpbrowscap\Browscap($dir);
		$browscap->updateMethod = 'cURL';
		$browscap->cacheFilename = 'browscap_cache.php';
		$browscap->updateCache();

		// 이벤트가 존재하면 실행합니다
		Events::trigger('after', $eventname);

		exit(json_encode(array('success' => 'ok', 'message' => 'Browscap 업데이트가 완료되었습니다')));
	}
}
