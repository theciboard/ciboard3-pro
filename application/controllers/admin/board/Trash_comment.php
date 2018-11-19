<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Trash_comment class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 관리자>게시판설정>휴지통(댓글) controller 입니다.
 */
class Trash_comment extends CB_Controller
{

	/**
	 * 관리자 페이지 상의 현재 디렉토리입니다
	 * 페이지 이동시 필요한 정보입니다
	 */
	public $pagedir = 'board/trash_comment';

	/**
	 * 모델을 로딩합니다
	 */
	protected $models = array('Post', 'Comment', 'Board', 'Comment_meta');

	/**
	 * 이 컨트롤러의 메인 모델 이름입니다
	 */
	protected $modelname = 'Comment_model';

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
		$this->load->library(array('pagination', 'querystring'));
	}

	/**
	 * 목록을 가져오는 메소드입니다
	 */
	public function index()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_board_trash_comment_index';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		/**
		 * 페이지에 숫자가 아닌 문자가 입력되거나 1보다 작은 숫자가 입력되면 에러 페이지를 보여줍니다.
		 */
		$param =& $this->querystring;
		$page = (((int) $this->input->get('page')) > 0) ? ((int) $this->input->get('page')) : 1;
		$findex = 'cmt_id';
		$forder = 'desc';
		$sfield = $this->input->get('sfield', null, '');
		$skeyword = $this->input->get('skeyword', null, '');

		$per_page = admin_listnum();
		$offset = ($page - 1) * $per_page;

		/**
		 * 게시판 목록에 필요한 정보를 가져옵니다.
		 */
		$this->{$this->modelname}->allow_search_field = array('cmt_id', 'cmt_content', 'mem_id', 'cmt_username', 'cmt_nickname', 'cmt_email', 'cmt_datetime', 'cmt_ip', 'cmt_device'); // 검색이 가능한 필드
		$this->{$this->modelname}->search_field_equal = array('cmt_id', 'mem_id'); // 검색중 like 가 아닌 = 검색을 하는 필드
		$this->{$this->modelname}->allow_order_field = array('cmt_id desc'); // 정렬이 가능한 필드
		$where = array(
			'cmt_del' => 2,
		);
		if ($brdid = (int) $this->input->get('brd_id')) {
			$where['post.brd_id'] = $brdid;
		}
		$result = $this->{$this->modelname}
			->get_admin_list($per_page, $offset, $where, '', $findex, $forder, $sfield, $skeyword);
		$list_num = $result['total_rows'] - ($page - 1) * $per_page;
		if (element('list', $result)) {
			foreach (element('list', $result) as $key => $val) {
				$result['list'][$key]['meta'] = $meta = $this->Comment_meta_model->get_all_meta(element('cmt_id', $val));
				$result['list'][$key]['member'] = $dbmember = $this->Member_model->get_by_memid($meta['trash_mem_id'], 'mem_id, mem_userid, mem_nickname, mem_icon');

				$result['list'][$key]['display_name'] = display_username(
					element('mem_userid', $dbmember),
					element('mem_nickname', $dbmember)
				);
				$result['list'][$key]['post'] = $post = $this->Post_model->get_one(element('post_id', $val));
				$result['list'][$key]['board'] = $board = $this->board->item_all(element('brd_id', $post));
				$result['list'][$key]['content'] = display_html_content(
					$val['cmt_content'],
					$val['cmt_html'],
					element('post_image_width', $board)
				);
				$result['list'][$key]['num'] = $list_num--;

				if ($board) {
					$result['list'][$key]['boardurl'] = board_url(element('brd_key', $board));
					$result['list'][$key]['posturl'] = post_url(element('brd_key', $board), element('post_id', $val) . '#comment_id=' . element('cmt_id', $val));
				}
			}
		}
		$view['view']['data'] = $result;

		$view['view']['boardlist'] = $this->Board_model->get_board_list();

		/**
		 * primary key 정보를 저장합니다
		 */
		$view['view']['primary_key'] = $this->{$this->modelname}->primary_key;

		/**
		 * 페이지네이션을 생성합니다
		 */
		$config['base_url'] = admin_url($this->pagedir) . '?' . $param->replace('page');
		$config['total_rows'] = $result['total_rows'];
		$config['per_page'] = $per_page;
		$this->pagination->initialize($config);
		$view['view']['paging'] = $this->pagination->create_links();
		$view['view']['page'] = $page;

		/**
		 * 쓰기 주소, 삭제 주소등 필요한 주소를 구합니다
		 */
		$search_option = array('cmt_content' => '내용', 'cmt_nickname' => '닉네임', 'cmt_email' => '이메일', 'cmt_datetime' => '작성일', 'cmt_ip' => 'IP');
		$view['view']['skeyword'] = ($sfield && array_key_exists($sfield, $search_option)) ? $skeyword : '';
		$view['view']['search_option'] = search_option($search_option, $sfield);
		$view['view']['listall_url'] = admin_url($this->pagedir);
		$view['view']['list_delete_url'] = admin_url($this->pagedir . '/listdelete/?' . $param->output());
		$view['view']['list_truncate_url'] = admin_url($this->pagedir . '/truncate/?' . $param->output());
		$view['view']['list_recover_url'] = admin_url($this->pagedir . '/listrecover/?' . $param->output());

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
	 * 상세 페이지입니다
	 */
	public function view($cmt_id = 0)
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_board_trash_comment_view';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		$cmt_id = (int) $cmt_id;
		if (empty($cmt_id) OR $cmt_id < 1) {
			show_404();
		}

		$param =& $this->querystring;

		$result = $this->{$this->modelname}->get_one($cmt_id);
		$result['meta'] = $this->Comment_meta_model->get_all_meta($cmt_id);

		if ( ! element('cmt_id', $result)) {
			show_404();
		}
		if (element('cmt_del', $result) !== '2') {
			show_404();
		}

		$result['cmt_display_name'] = display_username(
			element('cmt_userid', $result),
			element('cmt_nickname', $result)
		);
		$select = 'mem_id, mem_userid, mem_nickname, mem_icon';
		$result['member'] = $dbmember
			= $this->Member_model->get_by_memid($result['meta']['trash_mem_id'], $select);
		$result['display_name'] = display_username(
			element('mem_userid', $dbmember),
			element('mem_nickname', $dbmember),
			element('mem_icon', $dbmember)
		);
		$result['post'] = $post = $this->Post_model->get_one(element('post_id', $result));
		$result['board'] = $board = $this->board->item_all($post['brd_id']);
		if ($board) {
			$result['boardurl'] = board_url(element('brd_key', $board));
			$result['posturl'] = post_url(element('brd_key', $board), element('post_id', $result) . '#comment_id=' . element('cmt_id', $result));
		}
		$result['content'] = display_html_content(
			$result['cmt_content'],
			$result['cmt_html'],
			element('post_image_width', $board)
		);

		$view['view']['data'] = $result;

		$view['view']['delete_url'] = admin_url($this->pagedir . '/delete/' . $cmt_id . '?' . $param->output());
		$view['view']['recover_url'] = admin_url($this->pagedir . '/recover/' . $cmt_id . '?' . $param->output());

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 어드민 레이아웃을 정의합니다
		 */
		$layoutconfig = array('layout' => 'layout', 'skin' => 'view');
		$view['layout'] = $this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}

	/**
	 * 목록 페이지에서 선택삭제를 하는 경우 실행되는 메소드입니다
	 */
	public function listdelete()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_board_trash_comment_listdelete';
		$this->load->event($eventname);

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		/**
		 * 체크한 게시물의 삭제를 실행합니다
		 */
		if ($this->input->post('chk') && is_array($this->input->post('chk'))) {
			foreach ($this->input->post('chk') as $val) {
				if ($val) {
					$this->board->delete_comment($val);
				}
			}
		}

		// 이벤트가 존재하면 실행합니다
		Events::trigger('after', $eventname);

		/**
		 * 삭제가 끝난 후 목록페이지로 이동합니다
		 */
		$this->session->set_flashdata(
			'message',
			'정상적으로 삭제되었습니다'
		);
		$param =& $this->querystring;
		$redirecturl = admin_url($this->pagedir . '?' . $param->output());
		redirect($redirecturl);
	}

	/**
	 * 목록 페이지에서 복원을 하는 경우 실행되는 메소드입니다
	 */
	public function listrecover()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_board_trash_comment_listrecover';
		$this->load->event($eventname);

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		/**
		 * 체크한 게시물의 삭제를 실행합니다
		 */
		if ($this->input->post('chk') && is_array($this->input->post('chk'))) {
			foreach ($this->input->post('chk') as $val) {
				if ($val) {
					$updatedata = array(
						'cmt_del' => 0,
					);
					$this->Comment_model->update($val, $updatedata);
					$this->Comment_meta_model->delete_meta_column('trash_ip');
					$this->Comment_meta_model->delete_meta_column('trash_mem_id');
					$this->Comment_meta_model->delete_meta_column('trash_datetime');
				}
			}
		}

		// 이벤트가 존재하면 실행합니다
		Events::trigger('after', $eventname);

		/**
		 * 삭제가 끝난 후 목록페이지로 이동합니다
		 */
		$this->session->set_flashdata(
			'message',
			'정상적으로 복원되었습니다'
		);
		$param =& $this->querystring;
		$redirecturl = admin_url($this->pagedir . '?' . $param->output());
		redirect($redirecturl);
	}

	/**
	 * 휴지통을 비웁니다
	 */
	public function truncate()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_board_trash_comment_truncate';
		$this->load->event($eventname);

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		$where = array(
			'cmt_del' => 2,
		);
		$result = $this->Comment_model->get('', '', $where);

		if ($result && is_array($result)) {
			foreach ($result as $val) {
				if (element('cmt_id', $val)) {
					$post_id = element('cmt_id', $val);
					$this->board->delete_comment($cmt_id);
				}
			}
		}

		// 이벤트가 존재하면 실행합니다
		Events::trigger('after', $eventname);

		/**
		 * 삭제가 끝난 후 목록페이지로 이동합니다
		 */
		$this->session->set_flashdata(
			'message',
			'정상적으로 삭제되었습니다'
		);
		$param =& $this->querystring;
		$redirecturl = admin_url($this->pagedir . '?' . $param->output());
		redirect($redirecturl);
	}

	/**
	 * 뷰페이지에서 선택삭제를 하는 경우 실행되는 메소드입니다
	 */
	public function delete($cmt_id = 0)
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_board_trash_comment_delete';
		$this->load->event($eventname);

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		$cmt_id = (int) $cmt_id;
		if (empty($cmt_id) OR $cmt_id < 1) {
			show_404();
		}

		$this->board->delete_comment($cmt_id);

		// 이벤트가 존재하면 실행합니다
		Events::trigger('after', $eventname);

		/**
		 * 삭제가 끝난 후 목록페이지로 이동합니다
		 */
		$this->session->set_flashdata(
			'message',
			'정상적으로 삭제되었습니다'
		);
		$param =& $this->querystring;
		$redirecturl = admin_url($this->pagedir . '?' . $param->output());
		redirect($redirecturl);
	}

	/**
	 * 뷰페이지에서 선택삭제를 하는 경우 실행되는 메소드입니다
	 */
	public function recover($cmt_id = 0)
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_board_trash_comment_recover';
		$this->load->event($eventname);

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		$cmt_id = (int) $cmt_id;
		if (empty($cmt_id) OR $cmt_id < 1) {
			show_404();
		}

		$updatedata = array(
			'cmt_del' => 0,
		);
		$this->Comment_model->update($cmt_id, $updatedata);
		$this->Comment_meta_model->delete_meta_column('trash_ip');
		$this->Comment_meta_model->delete_meta_column('trash_mem_id');
		$this->Comment_meta_model->delete_meta_column('trash_datetime');

		// 이벤트가 존재하면 실행합니다
		Events::trigger('after', $eventname);

		/**
		 * 삭제가 끝난 후 목록페이지로 이동합니다
		 */
		$this->session->set_flashdata(
			'message',
			'정상적으로 복원되었습니다'
		);
		$param =& $this->querystring;
		$redirecturl = admin_url($this->pagedir . '?' . $param->output());
		redirect($redirecturl);
	}
}
