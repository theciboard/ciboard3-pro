<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Rss class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * RSS 보기 때 필요한 controller 입니다.
 */
class Rss extends CB_Controller
{

	/**
	 * 모델을 로딩합니다
	 */
	protected $models = array('Board_meta', 'Post', 'Board_category');

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
	 * RSS 페이지 함수입니다
	 */
	public function index($brd_key = '')
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_rss_index';
		$this->load->event($eventname);

		$rsstype = $brd_key ? 'board' : 'all';

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		if ($rsstype === 'board') {
			$board_id = $this->board->item_key('brd_id', $brd_key);
			if (empty($board_id)) {
				show_404();
			}
			$board = $this->board->item_all($board_id);
			if (element('access_view', $board) > 0 OR element('access_list', $board) > 0) {
				alert('비회원이 볼 수 없는 게시판은 RSS 접근이 금지되어 있습니다');
				return false;
			}
			if ( ! element('use_rss_feed', $board)) {
				alert('이 게시판은 RSS 보기가 금지되어 있습니다');
				return false;
			}
		} else {
			if ( ! $this->cbconfig->item('use_total_rss_feed')) {
				alert('통합게시판의 사용이 금지되어 있습니다');
				return false;
			}
		}

		header('content-type: text/xml');
		header('cache-control: no-cache, must-revalidate');
		header('pragma: no-cache');

		/**
		 * 페이지에 숫자가 아닌 문자가 입력되거나 1보다 작은 숫자가 입력되면 에러 페이지를 보여줍니다.
		 */
		if ($rsstype === 'board') {
			$limit = element('rss_feed_post_count', $board) ? element('rss_feed_post_count', $board) : 50;
		} else {
			$limit = $this->cbconfig->item('total_rss_feed_count') ? $this->cbconfig->item('total_rss_feed_count') : 50;
		}

		/**
		 * 게시판 목록에 필요한 정보를 가져옵니다.
		 */
		$boardarray = array();
		if ($rsstype === 'board') {
			$where = array(
				'brd_id' => element('brd_id', $board),
				'post_secret' => 0,
				'post_del' => 0,
			);
		} else {
			$whereboard = array(
				'bmt_key' => 'use_rss_total_feed',
				'bmt_value' => '1',
			);
			$boardlist = $this->Board_meta_model->get('', '', $whereboard);
			if ($boardlist && is_array($boardlist)) {
				foreach ($boardlist as $key => $value) {
					$boardarray[] = element('brd_id', $value);
				}
			}
			$where = array(
				'post_secret' => 0,
				'post_del' => 0,
			);
		}
		$result = $this->Post_model->get_rss_list($where, $boardarray, $limit);
		if (element('list', $result)) {
			foreach (element('list', $result) as $key => $val) {
				$result['list'][$key]['title'] = element('post_title', $val);
				$result['list'][$key]['pubdate'] = cdate('c', strtotime(element('post_datetime', $val)));
				$result['list'][$key]['author'] = element('post_nickname', $val);
				$result['list'][$key]['content'] = '';
				$result['list'][$key]['category'] = '';
				if ($rsstype === 'board') {
					if (element('rss_feed_content', $board) === '1') {
						$result['list'][$key]['content'] = strip_tags(element('post_content', $val));
					} elseif (element('rss_feed_content', $board) === '2') {
						$result['list'][$key]['content'] = display_html_content(
							element('post_content', $val),
							element('post_html', $val),
							700
						);
					}
				} else {
					if ($this->cbconfig->item('total_rss_feed_content') === '1') {
						$result['list'][$key]['content'] = strip_tags(element('post_content', $val));
					} elseif ($this->cbconfig->item('total_rss_feed_content') === '2') {
						$result['list'][$key]['content'] = display_html_content(
							element('post_content', $val),
							element('post_html', $val),
							700
						);
					}
					$board = $this->board->item_all(element('brd_id', $val));
				}
				if (element('use_category', $board) && element('post_category', $val)) {
					$category = $this->Board_category_model
						->get_category_info(element('brd_id', $val), element('post_category', $val));
					$result['list'][$key]['category'] = element('bca_value', $category);
				}
				$result['list'][$key]['link'] = post_url(element('brd_key', $board), element('post_id', $val));
			}
		}

		$view['view']['data'] = $result;
		if ($rsstype === 'board') {
			$view['view']['title'] = element('brd_name', $board);
			$view['view']['url'] = board_url(element('brd_key', $board));
			$view['view']['copyright'] = element('rss_feed_copyright', $board)
				? element('rss_feed_copyright', $board)
				: $this->cbconfig->item('total_rss_feed_copyright');
			$view['view']['description'] = element('rss_feed_description', $board)
				? element('rss_feed_description', $board)
				: $this->cbconfig->item('total_rss_feed_description');
		} else {
			$view['view']['title'] = $this->cbconfig->item('total_rss_feed_title')
				? $this->cbconfig->item('total_rss_feed_title')
				: $this->cbconfig->item('site_title');
			$view['view']['url'] = site_url();
			$view['view']['copyright'] = $this->cbconfig->item('total_rss_feed_copyright');
			$view['view']['description'] = $this->cbconfig->item('total_rss_feed_description');
		}
		$view['view']['board'] = isset($board) ? $board : '';

		/**
		 * primary key 정보를 저장합니다
		 */
		$view['view']['primary_key'] = $this->Post_model->primary_key;

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 레이아웃을 정의합니다
		 */
		$this->data = $view;
		$skin = $this->cbconfig->item('skin_helptool') ? $this->cbconfig->item('skin_helptool') : $this->cbconfig->item('skin_default');
		$this->view = 'helptool/' . $skin . '/rss';
	}
}
