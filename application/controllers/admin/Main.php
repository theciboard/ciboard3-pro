<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Main class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 관리자 메인 controller 입니다.
 */
class Main extends CB_Controller
{

	/**
	 * 관리자 페이지 상의 현재 디렉토리입니다
	 * 페이지 이동시 필요한 정보입니다
	 */
	public $pagedir = '';

	/**
	 * 모델을 로딩합니다
	 */
	protected $models = array('Post', 'Comment', 'Point');

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
	 * 관리자 메인 페이지입니다
	 */
	public function index()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_main_index';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		$view['view']['latest_member'] = $this->Member_model->get_admin_list(7, '', '', '', 'mem_id', 'desc', '', '');
		if (isset($view['view']['latest_member']['list']) && is_array($view['view']['latest_member']['list'])) {
			foreach ($view['view']['latest_member']['list'] as $key => $val) {
				$view['view']['latest_member']['list'][$key]['display_name'] = display_username(
					element('mem_userid', $val),
					element('mem_nickname', $val)
				);
			}
		}

		$view['view']['latest_point'] = $this->Point_model->get_admin_list(7, '', '', '', 'poi_id', 'desc', '', '');
		if (isset($view['view']['latest_point']['list']) && is_array($view['view']['latest_point']['list'])) {
			foreach ($view['view']['latest_point']['list'] as $key => $val) {
				$view['view']['latest_point']['list'][$key]['display_name'] = display_username(
					element('mem_userid', $val),
					element('mem_nickname', $val)
				);
			}
		}

		$view['view']['latest_post'] = $this->Post_model->get_admin_list(7, '', '', '', 'post_id', 'desc', '', '');
		if (isset($view['view']['latest_post']['list']) && is_array($view['view']['latest_post']['list'])) {
			foreach ($view['view']['latest_post']['list'] as $key => $val) {
				$brd_key = $this->board->item_id('brd_key', element('brd_id', $val));
				$view['view']['latest_post']['list'][$key]['post_url'] = post_url($brd_key, element('post_id', $val));
				$view['view']['latest_post']['list'][$key]['display_name'] = display_username(
					element('post_userid', $val),
					element('post_nickname', $val)
				);
			}
		}

		$view['view']['latest_comment'] = $this->Comment_model->get_admin_list(7, '', '', '', 'cmt_id', 'desc', '', '');
		if (isset($view['view']['latest_comment']['list']) && is_array($view['view']['latest_comment']['list'])) {
			foreach ($view['view']['latest_comment']['list'] as $key => $val) {
				$post = $this->Post_model->get_one(element('post_id', $val), 'brd_id');
				$brd_key = $this->board->item_id('brd_key', element('brd_id', $post));
				$view['view']['latest_comment']['list'][$key]['post_url'] = post_url($brd_key, element('post_id', $val)) . '#comment_' . element('cmt_id', $val);
				$view['view']['latest_comment']['list'][$key]['display_name'] = display_username(
					element('cmt_userid', $val),
					element('cmt_nickname', $val)
				);
			}
		}

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 어드민 레이아웃을 정의합니다
		 */
		$layoutconfig = array('layout' => 'layout', 'skin' => 'main');
		$view['layout'] = $this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}
}
