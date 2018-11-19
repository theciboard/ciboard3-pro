<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Sitemap class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * Sitemap 과 관련된 controller 입니다.
 */
class Sitemap extends CB_Controller
{

	/**
	 * 모델을 로딩합니다
	 */
	protected $models = array('Board_meta', 'Post');

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
	 * 사이트맵 페이지 함수입니다
	 */
	public function index()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_sitemap_index';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		if ( ! $this->cbconfig->item('use_sitemap')) {
			alert('사이트맵 기능을 사용하고 있지 않습니다');
			return false;
		}

		$whereboard = array(
			'bmt_key' => 'use_sitemap',
			'bmt_value' => '1',
		);
		$sitemap = array();
		$boardlist = $this->Board_meta_model->get('', '', $whereboard);
		if ($boardlist && is_array($boardlist)) {
			foreach ($boardlist as $key => $value) {
				$sitemap[] = array(
					'loc' => site_url('sitemap_' . element('brd_id', $value) . '.xml'),
					'brd_id' => element('brd_id', $value)
				);
			}
		}
		$view['view']['sitemap'] = $sitemap;

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 레이아웃을 정의합니다
		 */
		header('content-type: text/xml');
		header('cache-control: no-cache, must-revalidate');
		header('pragma: no-cache');

		$this->data = $view;
		$skin = $this->cbconfig->item('skin_helptool') ? $this->cbconfig->item('skin_helptool') : $this->cbconfig->item('skin_default');
		$this->view = 'helptool/' . $skin . '/sitemap';
	}

	/**
	 * 사이트맵 페이지 함수입니다
	 */
	public function board($brd_id = '')
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_sitemap_detail';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		if ( ! $this->cbconfig->item('use_sitemap')) {
			alert('사이트맵 기능을 사용하고 있지 않습니다');
			return false;
		}
		if ( ! isset($brd_id)) {
			alert('잘못된 접근입니다');
			return false;
		}

		$board_id = $this->board->item_id('brd_id', $brd_id);
		if ( ! $board_id) {
			alert('존재하지 않는 게시판입니다');
			return false;
		}
		$board = $this->board->item_all($board_id);

		if ( ! element('use_sitemap', $board)) {
			alert('이 게시판은 sitemap 기능을 사용하지 않습니다');
			return false;
		}

		$where = array(
			'brd_id' => $brd_id,
			'post_secret' => 0,
			'post_del' => 0,
		);
		$limit = $this->cbconfig->item('sitemap_count') ? $this->cbconfig->item('sitemap_count') : 100;
		$result = $this->Post_model->get_rss_list($where, '', $limit);
		if (element('list', $result)) {
			foreach (element('list', $result) as $key => $val) {
				$result['list'][$key]['link'] = post_url(element('brd_key', $board), element('post_id', $val));
			}
		}

		$view['view']['data'] = $result;


		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		header('content-type: text/xml');
		header('cache-control: no-cache, must-revalidate');
		header('pragma: no-cache');

		/**
		 * 레이아웃을 정의합니다
		 */
		$this->data = $view;
		$skin = $this->cbconfig->item('skin_helptool') ? $this->cbconfig->item('skin_helptool') : $this->cbconfig->item('skin_default');
		$this->view = 'helptool/' . $skin . '/sitemap_board.php';
	}
}
