<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Helptool class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 각종 헬프페이지에 관련된 controller 입니다.
 */
class Helptool extends CB_Controller
{

	/**
	 * 모델을 로딩합니다
	 */
	protected $models = array('Post');

	/**
	 * 헬퍼를 로딩합니다
	 */
	protected $helpers = array('form', 'array', 'file', 'string');

	function __construct()
	{
		parent::__construct();

		/**
		 * 라이브러리를 로딩합니다
		 */
		$this->load->library(array('pagination', 'querystring'));
	}


	/**
	 * 이미지 크게 보기
	 */
	public function viewimage()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_helptool_viewimage';
		$this->load->event($eventname);

		$view = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		$view['view']['imgurl'] = $this->input->get('imgurl', null, '');

		/**
		 * 레이아웃을 정의합니다
		 */
		$page_title = '이미지 보기';
		$layoutconfig = array(
			'path' => 'helptool',
			'layout' => 'layout_popup',
			'skin' => 'viewimage',
			'layout_dir' => $this->cbconfig->item('layout_helptool'),
			'mobile_layout_dir' => $this->cbconfig->item('mobile_layout_helptool'),
			'skin_dir' => $this->cbconfig->item('skin_helptool'),
			'mobile_skin_dir' => $this->cbconfig->item('mobile_skin_helptool'),
			'page_title' => $page_title,
		);
		$view['layout'] = $this->managelayout->front($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}


	/**
	 * 이모티콘 보기
	 */
	public function emoticon()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_helptool_emoticon';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);
		$view['view']['emoticon'] = get_filenames(config_item('uploads_dir') . '/emoticon');

		/**
		 * 레이아웃을 정의합니다
		 */
		$page_title = '이모티콘';
		$layoutconfig = array(
			'path' => 'helptool',
			'layout' => 'layout_popup',
			'skin' => 'emoticon',
			'layout_dir' => $this->cbconfig->item('layout_helptool'),
			'mobile_layout_dir' => $this->cbconfig->item('mobile_layout_helptool'),
			'skin_dir' => $this->cbconfig->item('skin_helptool'),
			'mobile_skin_dir' => $this->cbconfig->item('mobile_skin_helptool'),
			'page_title' => $page_title,
		);
		$view['layout'] = $this->managelayout->front($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}


	/**
	 * 특수문자 보기
	 */
	public function specialchars()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_helptool_specialchars';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();


		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		$chars = "、 。 · ‥ … ¨ 〃 ― ∥ ＼ ∼ ‘ ’ “ ” 〔 〕 〈 〉 《 》 「 」 『 』 【 】 ± × ÷ ≠ ≤ ≥ ∞ ∴ ° ′ ″ ℃ Å ￠ ￡ ￥ ♂ ♀ ∠ ⊥ ⌒ ∂ ∇ ≡ ≒ § ※ ☆ ★ ○ ● ◎ ◇ ◆ □ ■ △ ▲ ▽ ▼ → ← ↑ ↓ ↔ 〓 ≪ ≫ √ ∽ ∝ ∵ ∫ ∬ ∈ ∋ ⊆ ⊇ ⊂ ⊃ ∩ ∧ ∨ ￢ ⇒ ⇔ ∀ ∃ ´ ～ ˇ ˘ ˝ ˚ ˙ ¸ ˛ ¡ ¿ ː ∮ ∑ ∏ ¤ ℉ ‰ ◁ ◀ ▷ ▶ ♤ ♠ ♡ ♥ ♧ ♣ ⊙ ◈ ▣ ◐ ◑ ▒ ▤ ▥ ▨ ▧ ▦ ▩ ♨ ☏ ☎ ☜ ☞ ¶ † ‡ ↕ ↗ ↙ ↖ ↘ ♭ ♩ ♪ ♬ ㉿ ㈜ № ㏇ ™ ㏂ ㏘ ℡";

		$view['view']['char'] = explode(' ', $chars);

		/**
		 * 레이아웃을 정의합니다
		 */
		$page_title = '특수문자';
		$layoutconfig = array(
			'path' => 'helptool',
			'layout' => 'layout_popup',
			'skin' => 'specialchars',
			'layout_dir' => $this->cbconfig->item('layout_helptool'),
			'mobile_layout_dir' => $this->cbconfig->item('mobile_layout_helptool'),
			'skin_dir' => $this->cbconfig->item('skin_helptool'),
			'mobile_skin_dir' => $this->cbconfig->item('mobile_skin_helptool'),
			'page_title' => $page_title,
		);
		$view['layout'] = $this->managelayout->front($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}


	/**
	 * 게시물변경로그 보기
	 */
	public function post_history($post_id = 0)
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_helptool_post_history';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		$post_id = (int) $post_id;
		if (empty($post_id) OR $post_id < 1) {
			alert('잘못된 접근입니다');
			return false;
		}

		$select = 'post_id, brd_id, mem_id, post_title';
		$post = $this->Post_model->get_one($post_id, $select);

		if ( ! element('post_id', $post)) {
			alert('존재하지 않는 게시물입니다');
			return false;
		}

		$board = $this->board->item_all(element('brd_id', $post));

		if ( ! element('use_posthistory', $board)) {
			alert('게시물 변경로그를 사용하지 않는 게시판입니다');
			return false;
		}

		$is_admin = $this->member->is_admin(
			array(
				'board_id' => element('brd_id', $board),
				'group_id' => element('bgr_id', $board),
			)
		);

		if ($is_admin === false) {
			alert('접근권한이 없습니다');
			return false;
		}

		/**
		 * 페이지에 숫자가 아닌 문자가 입력되거나 1보다 작은 숫자가 입력되면 에러 페이지를 보여줍니다.
		 */
		$param =& $this->querystring;
		$page = (((int) $this->input->get('page')) > 0) ? ((int) $this->input->get('page')) : 1;
		$this->load->model('Post_history_model');
		$findex = $this->Post_history_model->primary_key;
		$forder = 'desc';

		$per_page = 10;
		$offset = ($page - 1) * $per_page;

		/**
		 * 게시판 목록에 필요한 정보를 가져옵니다.
		 */
		$where = array(
			'post.post_id' => $post_id,
		);
		$result = $this->Post_history_model
			->get_list($per_page, $offset, $where, '', $findex, $forder);
		$list_num = $result['total_rows'] - ($page - 1) * $per_page;

		if (element('list', $result)) {
			foreach (element('list', $result) as $key => $val) {
				$result['list'][$key]['display_name'] = display_username(
					element('mem_userid', $val),
					element('mem_nickname', $val)
				);
				$result['list'][$key]['post_display_name'] = display_username(
					element('post_userid', $val),
					element('post_nickname', $val)
				);
				$result['list'][$key]['num'] = $list_num--;
			}
		}
		$view['view']['data'] = $result;

		/**
		 * 페이지네이션을 생성합니다
		 */
		$config['base_url'] = site_url('helptool/post_history/' . $post_id) . '?' . $param->replace('page');
		$config['total_rows'] = $result['total_rows'];
		$config['per_page'] = $per_page;
		$this->pagination->initialize($config);
		$view['view']['paging'] = $this->pagination->create_links();
		$view['view']['page'] = $page;

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 레이아웃을 정의합니다
		 */
		$page_title = element('post_title', $post) . ' > 게시물 변경 로그';
		$layoutconfig = array(
			'path' => 'helptool',
			'layout' => 'layout_popup',
			'skin' => 'post_history',
			'layout_dir' => $this->cbconfig->item('layout_helptool'),
			'mobile_layout_dir' => $this->cbconfig->item('mobile_layout_helptool'),
			'skin_dir' => $this->cbconfig->item('skin_helptool'),
			'mobile_skin_dir' => $this->cbconfig->item('mobile_skin_helptool'),
			'page_title' => $page_title,
		);
		$view['layout'] = $this->managelayout->front($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}


	/**
	 * 게시물변경로그 상세 보기
	 */
	public function post_history_view($post_id = 0, $phi_id = 0)
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_helptool_post_history_view';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		$post_id = (int) $post_id;
		if (empty($post_id) OR $post_id < 1) {
			alert('잘못된 접근입니다');
			return false;
		}

		$phi_id = (int) $phi_id;
		if (empty($phi_id) OR $phi_id < 1) {
			alert('잘못된 접근입니다');
			return false;
		}

		$select = 'post_id, brd_id, mem_id';
		$post = $this->Post_model->get_one($post_id, $select);

		if ( ! element('post_id', $post)) {
			alert('존재하지 않는 게시물입니다');
			return false;
		}

		$board = $this->board->item_all(element('brd_id', $post));

		if ( ! element('use_posthistory', $board)) {
			alert('게시물 변경로그를 사용하지 않는 게시판입니다');
			return false;
		}

		$is_admin = $this->member->is_admin(
			array(
				'board_id' => element('brd_id', $board),
				'group_id' => element('bgr_id', $board),
			)
		);

		if ($is_admin === false) {
			alert('접근권한이 없습니다');
			return false;
		}

		$param =& $this->querystring;

		$this->load->model('Post_history_model');
		$result = $this->Post_history_model->get_one($phi_id);

		if ( ! element('phi_id', $result)) {
			alert('존재하지 않는 게시물입니다');
			return false;
		}

		$select = 'mem_id, mem_userid, mem_nickname, mem_icon';
		$result['member'] = $dbmember = $this->Member_model
			->get_by_memid(element('mem_id', $result), $select);
		$result['display_name'] = display_username(
			element('mem_userid', $dbmember),
			element('mem_nickname', $dbmember)
		);
		$result['post'] = $post = $this->Post_model->get_one(element('post_id', $result));
		if ($post) {
			$result['board'] = $board = $this->board->item_all(element('brd_id', $post));
		}
		$image_width = ($this->cbconfig->get_device_view_type() === 'mobile')
			? element('post_mobile_image_width', $board)
			: element('post_image_width', $board);
		$result['post_display_name'] = display_username(
			element('post_userid', $post),
			element('post_nickname', $post)
		);
		$result['content'] = display_html_content(
			element('phi_content', $result),
			element('phi_content_html_type', $result),
			$image_width
		);

		$where = array(
			'post_id' => element('post_id', $result),
			'phi_id <' => element('phi_id', $result),
		);
		$prev = $this->Post_history_model->get('', '', $where, 1, 0, 'phi_id', 'DESC');
		if ($prev && element(0, $prev)) {
			$p = element(0, $prev);
			$p['content'] = display_html_content(
				element('phi_content', $p),
				element('phi_content_html_type', $p),
				$image_width
			);
			$result['prev'] = $p;
		}

		$view['view']['data'] = $result;

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 레이아웃을 정의합니다
		 */
		$page_title = element('post_title', $post) . ' > 게시물 변경 로그';
		$layoutconfig = array(
			'path' => 'helptool',
			'layout' => 'layout_popup',
			'skin' => 'post_history_view',
			'layout_dir' => $this->cbconfig->item('layout_helptool'),
			'mobile_layout_dir' => $this->cbconfig->item('mobile_layout_helptool'),
			'skin_dir' => $this->cbconfig->item('skin_helptool'),
			'mobile_skin_dir' => $this->cbconfig->item('mobile_skin_helptool'),
			'page_title' => $page_title,
		);
		$view['layout'] = $this->managelayout->front($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}


	/**
	 * 다운로드로그 보기
	 */
	public function download_log($post_id = 0)
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_helptool_download_log';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		$post_id = (int) $post_id;
		if (empty($post_id) OR $post_id < 1) {
			alert('잘못된 접근입니다');
			return false;
		}

		$this->load->model('Post_file_download_log_model');

		$select = 'post_id, brd_id, mem_id, post_title';
		$post = $this->Post_model->get_one($post_id, $select);

		if ( ! element('post_id', $post)) {
			alert('존재하지 않는 게시물입니다');
			return false;
		}

		$board = $this->board->item_all(element('brd_id', $post));

		if ( ! element('use_download_log', $board)) {
			alert('다운로드 로그를 사용하지 않는 게시판입니다');
			return false;
		}

		$is_admin = $this->member->is_admin(
			array(
				'board_id' => element('brd_id', $board),
				'group_id' => element('bgr_id', $board),
			)
		);
		if ($is_admin === false) {
			alert('접근권한이 없습니다');
			return false;
		}

		/**
		 * 페이지에 숫자가 아닌 문자가 입력되거나 1보다 작은 숫자가 입력되면 에러 페이지를 보여줍니다.
		 */
		$param =& $this->querystring;
		$page = (((int) $this->input->get('page')) > 0) ? ((int) $this->input->get('page')) : 1;
		$findex = $this->Post_file_download_log_model->primary_key;
		$forder = 'desc';

		$per_page = 10;
		$offset = ($page - 1) * $per_page;

		/**
		 * 게시판 목록에 필요한 정보를 가져옵니다.
		 */
		$where = array(
			'post.post_id' => $post_id,
		);
		$result = $this->Post_file_download_log_model
			->get_list($per_page, $offset, $where, '', $findex, $forder);
		$list_num = $result['total_rows'] - ($page - 1) * $per_page;
		if (element('list', $result)) {
			foreach (element('list', $result) as $key => $val) {
				$select = 'mem_id, mem_userid, mem_nickname, mem_icon';
				$result['list'][$key]['member'] = $dbmember = $this->Member_model
					->get_by_memid(element('mem_id', $val), $select);
				$result['list'][$key]['display_name'] = display_username(
					element('mem_userid', $dbmember),
					element('mem_nickname', $dbmember)
				);
				$result['list'][$key]['post_display_name'] = display_username(
					element('post_userid', $val),
					element('post_nickname', $val)
				);
				$result['list'][$key]['num'] = $list_num--;
			}
		}
		$view['view']['data'] = $result;

		/**
		 * 페이지네이션을 생성합니다
		 */
		$config['base_url'] = site_url('helptool/download_log/' . $post_id) . '?' . $param->replace('page');
		$config['total_rows'] = $result['total_rows'];
		$config['per_page'] = $per_page;
		$this->pagination->initialize($config);
		$view['view']['paging'] = $this->pagination->create_links();
		$view['view']['page'] = $page;

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 레이아웃을 정의합니다
		 */
		$page_title = element('post_title', $post) . ' > 다운로드 로그';
		$layoutconfig = array(
			'path' => 'helptool',
			'layout' => 'layout_popup',
			'skin' => 'download_log',
			'layout_dir' => $this->cbconfig->item('layout_helptool'),
			'mobile_layout_dir' => $this->cbconfig->item('mobile_layout_helptool'),
			'skin_dir' => $this->cbconfig->item('skin_helptool'),
			'mobile_skin_dir' => $this->cbconfig->item('mobile_skin_helptool'),
			'page_title' => $page_title,
		);
		$view['layout'] = $this->managelayout->front($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}


	/**
	 * 링크클릭로그 보기
	 */
	public function link_click_log($post_id = 0)
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_helptool_link_click_log';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		$post_id = (int) $post_id;
		if (empty($post_id) OR $post_id < 1) {
			alert('잘못된 접근입니다');
			return false;
		}

		$this->load->model('Post_link_click_log_model');

		$select = 'post_id, brd_id, mem_id, post_title';
		$post = $this->Post_model->get_one($post_id, $select);

		if ( ! element('post_id', $post)) {
			alert('존재하지 않는 게시물입니다');
			return false;
		}

		$board = $this->board->item_all(element('brd_id', $post));

		if ( ! element('use_link_click_log', $board)) {
			alert('링크클릭로그를 사용하지 않는 게시판입니다');
			return false;
		}

		$is_admin = $this->member->is_admin(
			array(
				'board_id' => element('brd_id', $board),
				'group_id' => element('bgr_id', $board),
			)
		);

		if ($is_admin === false) {
			alert('접근권한이 없습니다');
			return false;
		}

		/**
		 * 페이지에 숫자가 아닌 문자가 입력되거나 1보다 작은 숫자가 입력되면 에러 페이지를 보여줍니다.
		 */
		$param =& $this->querystring;
		$page = (((int) $this->input->get('page')) > 0) ? ((int) $this->input->get('page')) : 1;
		$findex = $this->Post_link_click_log_model->primary_key;
		$forder = 'desc';

		$per_page = 10;
		$offset = ($page - 1) * $per_page;

		/**
		 * 게시판 목록에 필요한 정보를 가져옵니다.
		 */
		$where = array(
			'post.post_id' => $post_id,
		);
		$result = $this->Post_link_click_log_model
			->get_list($per_page, $offset, $where, '', $findex, $forder);
		$list_num = $result['total_rows'] - ($page - 1) * $per_page;
		if (element('list', $result)) {
			foreach (element('list', $result) as $key => $val) {
				$select = 'mem_id, mem_userid, mem_nickname, mem_icon';
				$result['list'][$key]['member'] = $dbmember = $this->Member_model
					->get_by_memid(element('mem_id', $val), $select);
				$result['list'][$key]['display_name'] = display_username(
					element('mem_userid', $dbmember),
					element('mem_nickname', $dbmember),
					element('mem_icon', $dbmember)
				);
				$result['list'][$key]['post_display_name'] = display_username(
					element('post_userid', $val),
					element('post_nickname', $val)
				);
				$result['list'][$key]['num'] = $list_num--;
			}
		}
		$view['view']['data'] = $result;

		/**
		 * 페이지네이션을 생성합니다
		 */
		$config['base_url'] = site_url('helptool/link_click_log/' . $post_id) . '?' . $param->replace('page');
		$config['total_rows'] = $result['total_rows'];
		$config['per_page'] = $per_page;
		$this->pagination->initialize($config);
		$view['view']['paging'] = $this->pagination->create_links();
		$view['view']['page'] = $page;

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 레이아웃을 정의합니다
		 */
		$page_title = element('post_title', $post) . ' > 링크클릭 로그';
		$layoutconfig = array(
			'path' => 'helptool',
			'layout' => 'layout_popup',
			'skin' => 'link_click_log',
			'layout_dir' => $this->cbconfig->item('layout_helptool'),
			'mobile_layout_dir' => $this->cbconfig->item('mobile_layout_helptool'),
			'skin_dir' => $this->cbconfig->item('skin_helptool'),
			'mobile_skin_dir' => $this->cbconfig->item('mobile_skin_helptool'),
			'page_title' => $page_title,
		);
		$view['layout'] = $this->managelayout->front($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}


	/**
	 * 게시물 복사 밎 이동
	 */
	public function post_copy($type = 'copy', $post_id = '')
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_helptool_post_copy';
		$this->load->event($eventname);

		$is_admin = $this->member->is_admin();

		if ($is_admin !== 'super') {
			alert('접근권한이 없습니다');
			return false;
		}

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		$this->load->model(array(
			'Blame_model', 'Board_model', 'Board_group_model',
			'Comment_model', 'Like_model', 'Post_extra_vars_model',
			'Post_file_model', 'Post_file_download_log_model', 'Post_history_model',
			'Post_link_model', 'Post_link_click_log_model', 'Post_meta_model',
			'Post_tag_model', 'Scrap_model'
		));

		$post_id_list = '';
		if ($this->input->post('chk_post_id')) {
			$post_id_list = '';
			$chk_post_id = $this->input->post('chk_post_id');
			foreach ($chk_post_id as $val) {
				if (empty($post_id)) {
					$post_id = $val;
				}
				$post_id_list .= $val . ',';
			}
		} elseif ($post_id) {
			$post_id_list = $post_id;
		}
		if ($this->input->post('post_id_list')) {
			$post_id_list = $this->input->post('post_id_list');
		}
		$view['view']['post_id_list'] = $post_id_list;

		$post = $this->Post_model->get_one($post_id);
		$board = $this->board->item_all(element('brd_id', $post));

		if ($type !== 'move') {
			$type = 'copy';
		}
		$view['view']['post'] = $post;
		$view['view']['board'] = $board;
		$view['view']['typetext'] = $typetext = ($type === 'copy') ? '복사' : '이동';

		$config = array(
			array(
				'field' => 'is_submit',
				'label' => '체크',
				'rules' => 'trim',
			),
		);
		$this->load->library('form_validation');
		$this->form_validation->set_rules($config);
		$form_validation = $this->form_validation->run();

		/**
		 * 유효성 검사를 하지 않는 경우, 또는 유효성 검사에 실패한 경우입니다.
		 * 즉 글쓰기나 수정 페이지를 보고 있는 경우입니다
		 */
		if ($form_validation === false OR ! $this->input->post('is_submit')) {

			// 이벤트가 존재하면 실행합니다
			$view['view']['event']['formrunfalse'] = Events::trigger('formrunfalse', $eventname);

			$result = $this->Board_model->get_board_list();
			if ($result && is_array($result)) {
				foreach ($result as $key => $value) {
					$result[$key]['group'] = $this->Board_group_model
						->get_one(element('bgr_id', $value));
				}
			}
			$view['view']['list'] = $result;

			// 이벤트가 존재하면 실행합니다
			$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

			/**
			 * 레이아웃을 정의합니다
			 */
			$page_title = element('post_title', $post) . ' > 게시물 ' . $typetext;
			$layoutconfig = array(
				'path' => 'helptool',
				'layout' => 'layout_popup',
				'skin' => 'post_copy',
				'layout_dir' => $this->cbconfig->item('layout_helptool'),
				'mobile_layout_dir' => $this->cbconfig->item('mobile_layout_helptool'),
				'skin_dir' => $this->cbconfig->item('skin_helptool'),
				'mobile_skin_dir' => $this->cbconfig->item('mobile_skin_helptool'),
				'page_title' => $page_title,
			);
			$view['layout'] = $this->managelayout->front($layoutconfig, $this->cbconfig->get_device_view_type());
			$this->data = $view;
			$this->layout = element('layout_skin_file', element('layout', $view));
			$this->view = element('view_skin_file', element('layout', $view));

		} else {

			// 이벤트가 존재하면 실행합니다
			$view['view']['event']['formruntrue'] = Events::trigger('formruntrue', $eventname);

			$old_brd_id = element('brd_id', $board);
			$new_brd_id = (int) $this->input->post('chk_brd_id');

			if ($post_id_list) {
				$arr = explode(',', $post_id_list);
				if ($arr) {
					$arrsize = count($arr);
					for ($k= $arrsize-1; $k>= 0; $k--) {
						$post_id = element($k, $arr);
						if (empty($post_id)) {
							continue;
						}

						$post = $this->Post_model->get_one($post_id);
						$board = $this->board->item_all(element('brd_id', $post));

						if ($type === 'copy') {
							// 게시글 복사

							// 이벤트가 존재하면 실행합니다
							$view['view']['event']['copy_before'] = Events::trigger('copy_before', $eventname);

							$post_num = $this->Post_model->next_post_num();

							$post_content = $post['post_content'];
							if ($this->cbconfig->item('use_copy_log')) {
								$br = $post['post_html'] ? '<br /><br />' : "\n";
								$post_content .= $br . '[이 게시물은 '
									. $this->member->item('mem_nickname') . ' 님에 의해 '
									. cdate('Y-m-d H:i:s') . ' '
									. element('brd_name', $board) . ' 에서 복사됨]';
							}
							$insertdata = array(
								'post_num' => $post_num,
								'post_reply' => $post['post_reply'],
								'brd_id' => $new_brd_id,
								'post_title' => $post['post_title'],
								'post_content' => $post_content,
								'mem_id' => $post['mem_id'],
								'post_userid' => $post['post_userid'],
								'post_username' => $post['post_username'],
								'post_nickname' => $post['post_nickname'],
								'post_email' => $post['post_email'],
								'post_homepage' => $post['post_homepage'],
								'post_datetime' => $post['post_datetime'],
								'post_password' => $post['post_password'],
								'post_updated_datetime' => $post['post_updated_datetime'],
								'post_update_mem_id' => $post['post_update_mem_id'],
								'post_link_count' => $post['post_link_count'],
								'post_secret' => $post['post_secret'],
								'post_html' => $post['post_html'],
								'post_notice' => $post['post_notice'],
								'post_receive_email' => $post['post_receive_email'],
								'post_hit' => $post['post_hit'],
								'post_ip' => $post['post_ip'],
								'post_device' => $post['post_device'],
								'post_file' => $post['post_file'],
								'post_image' => $post['post_image'],
								'post_del' => $post['post_del'],
							);
							$new_post_id = $this->Post_model->insert($insertdata);

							$postwhere = array(
								'post_id' => $post_id,
							);
							$filedata = $this->Post_file_model->get('', '', $postwhere);
							if ($filedata) {
								foreach ($filedata as $data) {
									$exp = explode('/', $data['pfi_filename']);
									$new_file_name = $exp[0] . '/' . $exp['1'] . '/' . random_string('alnum',30) . '.' . $data['pfi_type'];
									$fileinsert = array(
										'post_id' => $new_post_id,
										'brd_id' => $new_brd_id,
										'mem_id' => $data['mem_id'],
										'pfi_originname' => $data['pfi_originname'],
										'pfi_filename' => $new_file_name,
										'pfi_filesize' => $data['pfi_filesize'],
										'pfi_width' => $data['pfi_width'],
										'pfi_height' => $data['pfi_height'],
										'pfi_type' => $data['pfi_type'],
										'pfi_is_image' => $data['pfi_is_image'],
										'pfi_datetime' => $data['pfi_datetime'],
										'pfi_ip' => $data['pfi_ip'],
									);
									$this->Post_file_model->insert($fileinsert);
									copy(
										config_item('uploads_dir') . '/post/' . $data['pfi_filename'],
										config_item('uploads_dir') . '/post/' . $new_file_name
									);
								}
							}

							$postwhere = array(
								'post_id' => $post_id,
							);
							$linkdata = $this->Post_link_model->get('', '', $postwhere);
							if ($linkdata) {
								foreach ($linkdata as $data) {
									$linkinsert = array(
										'post_id' => $new_post_id,
										'brd_id' => $new_brd_id,
										'pln_url' => $data['pln_url'],
									);
									$this->Post_link_model->insert($linkinsert);
								}
							}

							$postwhere = array(
								'post_id' => $post_id,
							);
							$metadata = $this->Post_meta_model->get('', '', $postwhere);
							if ($metadata) {
								foreach ($metadata as $data) {
									$metainsert = array(
										'post_id' => $new_post_id,
										'brd_id' => $new_brd_id,
										'pmt_key' => $data['pmt_key'],
										'pmt_value' => $data['pmt_value'],
									);
									$this->Post_meta_model->insert($metainsert);
								}
							}

							$postwhere = array(
								'post_id' => $post_id,
							);
							$tagdata = $this->Post_tag_model->get('', '', $postwhere);
							if ($tagdata) {
								foreach ($tagdata as $data) {
									$taginsert = array(
										'post_id' => $new_post_id,
										'brd_id' => $new_brd_id,
										'pta_tag' => $data['pta_tag'],
									);
									$this->Post_tag_model->insert($taginsert);
								}
							}

							// 이벤트가 존재하면 실행합니다
							$view['view']['event']['copy_after'] = Events::trigger('copy_after', $eventname);

						}
						if ($type === 'move') {

							// 이벤트가 존재하면 실행합니다
							$view['view']['event']['move_before'] = Events::trigger('move_before', $eventname);

							// post table update
							$postupdate = array(
								'brd_id' => $new_brd_id,
							);

							if ($this->cbconfig->item('use_copy_log')) {
								$post_content = $post['post_content'];
								$br = $post['post_html'] ? '<br /><br />' : "\n";
								$post_content .= $br . '[이 게시물은 '
									. $this->member->item('mem_nickname') . ' 님에 의해 '
									. cdate('Y-m-d H:i:s') . ' '
									. element('brd_name', $board) . ' 에서 이동됨]';
								$postupdate['post_content'] = $post_content;
							}

							$this->Post_model->update($post_id, $postupdate);


							$dataupdate = array(
								'brd_id' => $new_brd_id,
							);
							$where = array(
								'target_id' => $post_id,
								'target_type' => 1,
							);
							$this->Blame_model->update('', $dataupdate, $where);
							$this->Like_model->update('', $dataupdate, $where);

							$where = array(
								'post_id' => $post_id,
							);
							$this->Comment_model->update('', $dataupdate, $where);
							$this->Post_extra_vars_model->update('', $dataupdate, $where);
							$this->Post_file_model->update('', $dataupdate, $where);
							$this->Post_file_download_log_model->update('', $dataupdate, $where);
							$this->Post_history_model->update('', $dataupdate, $where);
							$this->Post_link_model->update('', $dataupdate, $where);
							$this->Post_link_click_log_model->update('', $dataupdate, $where);
							$this->Post_meta_model->update('', $dataupdate, $where);
							$this->Post_tag_model->update('', $dataupdate, $where);
							$this->Scrap_model->update('', $dataupdate, $where);

							// 이벤트가 존재하면 실행합니다
							$view['view']['event']['move_after'] = Events::trigger('move_after', $eventname);

						}
					}
				}
			}

			// 이벤트가 존재하면 실행합니다
			$view['view']['event']['after'] = Events::trigger('after', $eventname);

			$alert = ($type === 'copy') ? '게시글 복사가 완료되었습니다' : '게시글 이동이 완료되었습니다';
			alert_close($alert);
		}
	}


	/**
	 * 게시물 카테고리 변경하기
	 */
	public function post_change_category($post_id = '')
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_helptool_post_change_category';
		$this->load->event($eventname);

		$is_admin = $this->member->is_admin();

		if ($is_admin === false) {
			alert('접근권한이 없습니다');
			return false;
		}

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		$this->load->model('Board_category_model');

		$post_id_list = '';
		if ($this->input->post('chk_post_id')) {
			$post_id_list = '';
			$chk_post_id = $this->input->post('chk_post_id');
			foreach ($chk_post_id as $val) {
				if (empty($post_id)) {
					$post_id = $val;
				}
				$post_id_list .= $val . ',';
			}
		} elseif ($post_id) {
			$post_id_list = $post_id;
		}
		if ($this->input->post('post_id_list')) {
			$post_id_list = $this->input->post('post_id_list');
		}
		$view['view']['post_id_list'] = $post_id_list;

		$post = $this->Post_model->get_one($post_id);
		$board = $this->board->item_all(element('brd_id', $post));

		$view['view']['post'] = $post;
		$view['view']['board'] = $board;

		$config = array(
			array(
				'field' => 'is_submit',
				'label' => '체크',
				'rules' => 'trim',
			),
		);
		$this->load->library('form_validation');
		$this->form_validation->set_rules($config);
		$form_validation = $this->form_validation->run();

		/**
		 * 유효성 검사를 하지 않는 경우, 또는 유효성 검사에 실패한 경우입니다.
		 * 즉 글쓰기나 수정 페이지를 보고 있는 경우입니다
		 */
		if ($form_validation === false OR ! $this->input->post('is_submit')) {

			// 이벤트가 존재하면 실행합니다
			$view['view']['event']['formrunfalse'] = Events::trigger('formrunfalse', $eventname);

			$view['view']['data'] = $this->Board_category_model
				->get_all_category(element('brd_id', $board));

			// 이벤트가 존재하면 실행합니다
			$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

			/**
			 * 레이아웃을 정의합니다
			 */
			$page_title = element('brd_name', $board) . ' > 카테고리 변경';
			$layoutconfig = array(
				'path' => 'helptool',
				'layout' => 'layout_popup',
				'skin' => 'post_change_category',
				'layout_dir' => $this->cbconfig->item('layout_helptool'),
				'mobile_layout_dir' => $this->cbconfig->item('mobile_layout_helptool'),
				'skin_dir' => $this->cbconfig->item('skin_helptool'),
				'mobile_skin_dir' => $this->cbconfig->item('mobile_skin_helptool'),
				'page_title' => $page_title,
			);
			$view['layout'] = $this->managelayout->front($layoutconfig, $this->cbconfig->get_device_view_type());
			$this->data = $view;
			$this->layout = element('layout_skin_file', element('layout', $view));
			$this->view = element('view_skin_file', element('layout', $view));

		} else {

			// 이벤트가 존재하면 실행합니다
			$view['view']['event']['formruntrue'] = Events::trigger('formruntrue', $eventname);

			if ($post_id_list) {
				$arr = explode(',', $post_id_list);
				if ($arr) {
					$arrsize = count($arr);
					for ($k= $arrsize-1; $k>= 0; $k--) {
						$post_id = element($k, $arr);
						if (empty($post_id)) {
							continue;
						}
						$post = $this->Post_model->get_one($post_id);
						$board = $this->board->item_all(element('brd_id', $post));
						$chk_post_category = $this->input->post('chk_post_category', null, '');

						$postupdate = array(
							'post_category' => $chk_post_category,
						);
						$this->Post_model->update($post_id, $postupdate);
					}
				}
			}
			alert_close('카테고리가 변경되었습니다');
		}
	}


	/**
	 * 구글지도
	 */
	public function googlemap()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_helptool_googlemap';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		/**
		 * 레이아웃을 정의합니다
		 */
		$page_title = '구글지도';
		$layoutconfig = array(
			'path' => 'helptool',
			'layout' => 'layout_popup',
			'skin' => 'googlemap',
			'layout_dir' => $this->cbconfig->item('layout_helptool'),
			'mobile_layout_dir' => $this->cbconfig->item('mobile_layout_helptool'),
			'skin_dir' => $this->cbconfig->item('skin_helptool'),
			'mobile_skin_dir' => $this->cbconfig->item('mobile_skin_helptool'),
			'page_title' => $page_title,
		);
		$view['layout'] = $this->managelayout->front($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		//$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}


	/**
	 * 구글지도
	 */
	public function googlemap_search()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_helptool_googlemap_search';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		/**
		 * 레이아웃을 정의합니다
		 */
		$page_title = '구글지도';
		$layoutconfig = array(
			'path' => 'helptool',
			'layout' => 'layout_popup',
			'skin' => 'googlemap_search',
			'layout_dir' => $this->cbconfig->item('layout_helptool'),
			'mobile_layout_dir' => $this->cbconfig->item('mobile_layout_helptool'),
			'skin_dir' => $this->cbconfig->item('skin_helptool'),
			'mobile_skin_dir' => $this->cbconfig->item('mobile_skin_helptool'),
			'page_title' => $page_title,
		);
		$view['layout'] = $this->managelayout->front($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}
}
