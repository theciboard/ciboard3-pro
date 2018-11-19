<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Levelup class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 설문조사 담당하는 controller 입니다.
 */
class Poll extends CB_Controller
{

	/**
	 * 모델을 로딩합니다
	 */
	protected $models = array('Post_poll');

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
		$this->load->library(array('pagination', 'querystring', 'accesslevel'));
	}


	/**
	 * 설문조사 페이지입니다
	 */
	public function index()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_poll_index';
		$this->load->event($eventname);

		if ( ! $this->cbconfig->item('use_poll_list')) {
			alert('이 웹사이트는 설문조사모음 페이지 기능을 사용하지 않습니다');
		}

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		/**
		 * 페이지에 숫자가 아닌 문자가 입력되거나 1보다 작은 숫자가 입력되면 에러 페이지를 보여줍니다.
		 */
		$param =& $this->querystring;
		$page = (((int) $this->input->get('page')) > 0) ? ((int) $this->input->get('page')) : 1;
		$findex = $this->Post_poll_model->primary_key;
		$forder = 'desc';
		$sfield = $this->input->get('sfield', null, '');
		$skeyword = $this->input->get('skeyword', null, '');

		$this->Post_poll_model->allow_search_field = array('post_title', 'post_content', 'ppo_title'); // 검색이 가능한 필드
		$this->Post_poll_model->search_field_equal = array(); // 검색중 like 가 아닌 = 검색을 하는 필드

		$per_page = $this->cbconfig->item('list_count') ? (int) $this->cbconfig->item('list_count') : 20;
		$offset = ($page - 1) * $per_page;

		/**
		 * 게시판 목록에 필요한 정보를 가져옵니다.
		 */
		$result = $this->Post_poll_model
			->get_list($per_page, $offset, '', '', $findex, $forder, $sfield, $skeyword);
		$list_num = $result['total_rows'] - ($page - 1) * $per_page;
		if (element('list', $result)) {
			foreach (element('list', $result) as $key => $val) {
				$result['list'][$key]['post_url'] = post_url(element('brd_key', $val), element('post_id', $val));
				$result['list'][$key]['num'] = $list_num--;
				$result['list'][$key]['period'] = '';
				$result['list'][$key]['brd_name'] = $this->board->item_id('brd_name', element('brd_id', $val));
				if (element('ppo_end_datetime', $val) > cdate('Y-m-d H:i:s')
					OR empty($val['ppo_end_datetime'])
					OR element('ppo_end_datetime', $val) === '0000-00-00 00:00:00') {
					$result['list'][$key]['period'] = '진행중';
				} elseif (element('ppo_start_datetime', $val) > cdate('Y-m-d H:i:s')) {
					$result['list'][$key]['period'] = '진행전';
				} else {
					$result['list'][$key]['period'] = '설문완료';
				}

			}
		}
		$view['view']['data'] = $result;

		/**
		 * 페이지네이션을 생성합니다
		 */
		$config['base_url'] = site_url('poll') . '?' . $param->replace('page');
		$config['total_rows'] = $result['total_rows'];
		$config['per_page'] = $per_page;
		$this->pagination->initialize($config);
		$view['view']['paging'] = $this->pagination->create_links();
		$view['view']['page'] = $page;

		$view['view']['canonical'] = site_url('poll');
		if ($this->input->get('page')) {
			$view['view']['canonical'] .= '?page=' . $this->input->get('page');
		}

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 레이아웃을 정의합니다
		 */
		$page_title = $this->cbconfig->item('site_meta_title_poll');
		$meta_description = $this->cbconfig->item('site_meta_description_poll');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_poll');
		$meta_author = $this->cbconfig->item('site_meta_author_poll');
		$page_name = $this->cbconfig->item('site_page_name_poll');

		$layoutconfig = array(
			'path' => 'poll',
			'layout' => 'layout',
			'skin' => 'poll',
			'layout_dir' => $this->cbconfig->item('layout_poll'),
			'mobile_layout_dir' => $this->cbconfig->item('mobile_layout_poll'),
			'use_sidebar' => $this->cbconfig->item('sidebar_poll'),
			'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_poll'),
			'skin_dir' => $this->cbconfig->item('skin_poll'),
			'mobile_skin_dir' => $this->cbconfig->item('mobile_skin_poll'),
			'page_title' => $page_title,
			'meta_description' => $meta_description,
			'meta_keywords' => $meta_keywords,
			'meta_author' => $meta_author,
			'page_name' => $page_name,
		);
		$view['layout'] = $this->managelayout->front($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}



	/**
	 * 게시물에서 설문조사하기
	 */
	public function post_poll($post_id = 0, $ppo_id = 0)
	{

		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_poll_post_poll';
		$this->load->event($eventname);

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		$result = array();
		$this->output->set_content_type('application/json');

		$post_id = (int) $post_id;
		if (empty($post_id) OR $post_id < 1) {
			$result = array('error' => '잘못된 접근입니다.');
			exit(json_encode($result));
		}

		$ppo_id = (int) $ppo_id;
		if (empty($ppo_id) OR $ppo_id < 1) {
			$result = array('error' => '잘못된 접근입니다.');
			exit(json_encode($result));
		}

		$this->load->model(array('Post_model', 'Post_poll_model', 'Post_poll_item_model', 'Post_poll_item_poll_model', 'Comment_model'));

		$select = 'post_id, brd_id, post_del';
		$post = $this->Post_model->get_one($post_id, $select);

		if ( ! $this->session->userdata('post_id_' . element('post_id', $post))) {
			$result = array('error' => '해당 게시물에서만 접근 가능합니다');
			exit(json_encode($result));
		}
		if ( ! element('post_id', $post)) {
			$result = array('error' => '존재하지 않는 게시물입니다');
			exit(json_encode($result));
		}
		if (element('post_del', $post)) {
			$result = array('error' => '삭제된 게시물에서는 설문조사가 불가능합니다');
			exit(json_encode($result));
		}

		$board = $this->board->item_all(element('brd_id', $post));

		$is_admin = $this->member->is_admin(
			array(
				'board_id' => element('brd_id', $board),
				'group_id' => element('bgr_id', $board),
			)
		);

		$use_poll = ($this->cbconfig->get_device_view_type() === 'mobile')
			? element('use_mobile_poll', $board) : element('use_poll', $board);

		if (empty($use_poll)) {
			$result = array('error' => '이 게시판은 현재 설문조사 기능을 사용하지 않습니다');
			exit(json_encode($result));
		}
		$check = array(
			'group_id' => element('bgr_id', $board),
			'board_id' => element('brd_id', $board),
		);
		$can_poll_attend = $this->accesslevel->is_accessable(
			element('access_poll_attend', $board),
			element('access_poll_attend_level', $board),
			element('access_poll_attend_group', $board),
			$check
		);
		if ($can_poll_attend === false) {
			$errormessage = $this->member->is_member()
				? '회원님은 설문조사 참여 권한이 없습니다'
				: '비회원은 설문조사 참여 권한이 없습니다. 회원이시라면 로그인 후 이용해 보십시오';
			$result = array('error' => $errormessage);
			exit(json_encode($result));
		}

		$mem_id = (int) $this->member->item('mem_id');

		$post_poll = $this->Post_poll_model->get_one($ppo_id);

		if (element('post_id', $post) !== element('post_id', $post_poll)) {
			$result = array('error' => '잘못된 접근입니다');
			exit(json_encode($result));
		}

		if (empty($post_poll['ppo_start_datetime'])
			OR element('ppo_start_datetime', $post_poll) === '0000-00-00 00:00:00') {
			$post_poll['ppo_start_datetime'] = '';
		}
		if (empty($post_poll['ppo_end_datetime'])
			OR element('ppo_end_datetime', $post_poll) === '0000-00-00 00:00:00') {
			$post_poll['ppo_end_datetime'] = '';
		}

		if (element('ppo_start_datetime', $post_poll)
			&& element('ppo_start_datetime', $post_poll) > cdate('Y-m-d H:i:s')) {
			$result = array('error' => '아직 설문조사 시작 기간 전입니다.');
			exit(json_encode($result));
		}
		if (element('ppo_end_datetime', $post_poll)
			&& element('ppo_end_datetime', $post_poll) < cdate('Y-m-d H:i:s')) {
			$result = array('error' => '설문조사 기간이 이미 지났습니다.');
			exit(json_encode($result));
		}

		$ppi_item = $this->input->post('ppi_item');
		if (empty($ppi_item)) {
			$result = array('error' => '선택된 답변이 없습니다.');
			exit(json_encode($result));
		}
		if (count($ppi_item) > element('ppo_choose_count', $post_poll)) {
			$result = array('error' => '답변은 ' . element('ppo_choose_count', $post_poll) . '개 이하로만 선택이 가능합니다');
			exit(json_encode($result));
		}

		$where = array(
			'ppo_id' => $ppo_id,
			'mem_id' => $mem_id,
		);
		$post_poll_count = $this->Post_poll_item_poll_model->count_by($where);
		if ($post_poll_count > 0) {
			$result = array('error' => '회원님은 이미 이 설문에 참여해주셨습니다. 중복 참여는 불가능합니다');
			exit(json_encode($result));
		}
		if (element('ppo_after_comment', $post_poll)) {
			$where = array(
				'post_id' => element('post_id', $post),
				'mem_id' => $mem_id,
			);
			$cmt_count = $this->Comment_model->count_by($where);
			if ($cmt_count === 0) {
				$result = array('error' => '댓글 작성 후 설문에 응답하실 수 있습니다');
				exit(json_encode($result));
			}
		}

		foreach ($ppi_item as $pkey => $pval) {
			$insertdata = array(
				'ppo_id' => $ppo_id,
				'ppi_id' => $pval,
				'mem_id' => $mem_id,
				'ppp_datetime' => cdate('Y-m-d H:i:s'),
				'ppp_ip' => $this->input->ip_address(),
			);
			$this->Post_poll_item_poll_model->insert($insertdata);
			$this->Post_poll_item_model->update_plus($pval, 'ppi_count', 1);
		}
		$this->Post_poll_model->update_plus($ppo_id, 'ppo_count', 1);

		if (element('ppo_point', $post_poll)) {
			$this->point->insert_point(
				$mem_id,
				element('ppo_point', $post_poll),
				element('board_name', $board) . ' ' . element('post_id', $post) . ' 설문참여',
				'post_poll',
				$ppo_id,
				'설문참여'
			);
		}

		// 이벤트가 존재하면 실행합니다
		Events::trigger('after', $eventname);

		$result = array('success' => '설문조사에 응해주셔서 감사합니다');
		exit(json_encode($result));
	}


	/**
	 * 게시물에서 설문조사한 후에 결과 불러오기
	 */
	public function post_poll_result($post_id = 0, $ppo_id = 0)
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_poll_post_poll_result';
		$this->load->event($eventname);

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		$result = array();
		$this->output->set_content_type('application/json');

		$post_id = (int) $post_id;
		if (empty($post_id) OR $post_id < 1) {
			$result = array('error' => '잘못된 접근입니다.');
			exit(json_encode($result));
		}

		$ppo_id = (int) $ppo_id;
		if (empty($ppo_id) OR $ppo_id < 1) {
			$result = array('error' => '잘못된 접근입니다.');
			exit(json_encode($result));
		}

		$this->load->model(array('Post_model', 'Post_poll_model', 'Post_poll_item_model', 'Post_poll_item_poll_model'));

		$select = 'post_id, brd_id, post_del';
		$post = $this->Post_model->get_one($post_id, $select);

		if ( ! $this->session->userdata('post_id_' . element('post_id', $post))) {
			$result = array('error' => '해당 게시물에서만 접근 가능합니다');
			exit(json_encode($result));
		}
		if ( ! element('post_id', $post)) {
			$result = array('error' => '존재하지 않는 게시물입니다');
			exit(json_encode($result));
		}
		if (element('post_del', $post)) {
			$result = array('error' => '삭제된 게시물에서는 설문조사가 불가능합니다');
			exit(json_encode($result));
		}

		$board = $this->board->item_all(element('brd_id', $post));
		$is_admin = $this->member->is_admin(
			array(
				'board_id' => element('brd_id', $board),
				'group_id' => element('bgr_id', $board),
			)
		);

		$use_poll = ($this->cbconfig->get_device_view_type() === 'mobile')
			? element('use_mobile_poll', $board) : element('use_poll', $board);

		if (empty($use_poll)) {
			$result = array('error' => '이 게시판은 현재 설문조사 기능을 사용하지 않습니다');
			exit(json_encode($result));
		}
		$post_poll = $this->Post_poll_model->get_one($ppo_id);

		if (element('post_id', $post) !== element('post_id', $post_poll)) {
			$result = array('error' => '잘못된 접근입니다');
			exit(json_encode($result));
		}

		if (empty($post_poll['ppo_start_datetime'])
			OR element('ppo_start_datetime', $post_poll) === '0000-00-00 00:00:00') {
			$post_poll['ppo_start_datetime'] = '';
		}
		if (empty($post_poll['ppo_end_datetime'])
			OR element('ppo_end_datetime', $post_poll) === '0000-00-00 00:00:00') {
			$post_poll['ppo_end_datetime'] = '';
		}

		$itemwhere = array(
			'ppo_id' => element('ppo_id', $post_poll),
		);
		$poll_item = $this->Post_poll_item_model->get('', '', $itemwhere, '', '', 'ppi_id', 'ASC');

		$sum_count = 0;
		$max = 0;
		if ($poll_item && is_array($poll_item)) {
			foreach ($poll_item as $key => $value) {
				if ($value['ppi_count'] > $max) {
					$max = $value['ppi_count'];
				}
				$sum_count+= $value['ppi_count'];
				$poll_item[$key]['ppi_item'] = html_escape($poll_item[$key]['ppi_item']);
			}
			foreach ($poll_item as $key => $value) {
				$rate = $sum_count ? ($value['ppi_count'] / $sum_count * 100) : 0;
				$poll_item[$key]['rate'] = $rate;
				$s_rate = number_format($rate, 1);
				$poll_item[$key]['s_rate'] = $s_rate;

				$bar = $max ? (int)($value['ppi_count'] / $max * 100) : 0;
				$poll_item[$key]['bar'] = $bar;
			}
		}

		// 이벤트가 존재하면 실행합니다
		Events::trigger('after', $eventname);

		$result = array(
			'success' => 'ok',
			'poll' => $post_poll,
			'poll_item' => $poll_item,
		);
		exit(json_encode($result));
	}
}
