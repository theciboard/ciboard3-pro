<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Comment_list class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 게시물 열람 페이지에 보이는 댓글 목록에 관한 controller 입니다.
 */
class Comment_list extends CB_Controller
{

	/**
	 * 모델을 로딩합니다
	 */
	protected $models = array('Post', 'Comment', 'Comment_meta');

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
	 * 댓글 목록을 ajax 로 가져옵니다
	 */
	public function lists($post_id = 0)
	{

		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_comment_list_lists';
		$this->load->event($eventname);

		$post_id = (int) $post_id;
		if (empty($post_id) OR $post_id < 1) {
			show_404();
		}

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		$post = $this->Post_model->get_one($post_id);
		if ( ! element('post_id', $post)) {
			show_404();
		}

		$board = $this->board->item_all(element('brd_id', $post));
		$mem_id = (int) $this->member->item('mem_id');

		$alertmessage = $this->member->is_member()
			? '회원님은 이 댓글 목록을 볼 수 있는 권한이 없습니다'
			: '비회원은 이 댓글 접근할 권한이 없습니다.\\n\\n회원이시라면 로그인 후 이용해 보십시오';
		$check = array(
			'group_id' => element('bgr_id', $board),
			'board_id' => element('brd_id', $board),
		);
		$this->accesslevel->check(
			element('access_view', $board),
			element('access_view_level', $board),
			element('access_view_group', $board),
			$alertmessage,
			$check
		);


		// 본인인증 사용하는 경우 - 시작
		if (element('access_view_selfcert', $board)) {
			$this->load->library(array('selfcertlib'));
			$this->selfcertlib->selfcertcheck('view', element('access_view_selfcert', $board));
		}
		// 본인인증 사용하는 경우 - 끝


		$is_admin = $this->member->is_admin(
			array(
				'board_id' => element('brd_id', $board),
				'group_id' => element('bgr_id', $board)
			)
		);

		$check = array(
			'group_id' => element('bgr_id', $board),
			'board_id' => element('brd_id', $board),
		);
		$can_comment_write = $this->accesslevel->is_accessable(
			element('access_comment', $board),
			element('access_comment_level', $board),
			element('access_comment_group', $board),
			$check
		);

		/**
		 * 페이지에 숫자가 아닌 문자가 입력되거나 1보다 작은 숫자가 입력되면 에러 페이지를 보여줍니다.
		 */
		$param =& $this->querystring;
		$findex = strtolower(element('comment_order', $board)) === 'desc'
			? 'cmt_num, cmt_reply' : 'cmt_num desc, cmt_reply';
		if ($this->cbconfig->get_device_view_type() === 'mobile') {
			$per_page = element('mobile_comment_count', $board)
				? (int) element('mobile_comment_count', $board) : 0;
		} else {
			$per_page = element('comment_count', $board) ? (int) element('comment_count', $board) : 0;
		}

		$page = (int) $this->input->get('page');
		if ($page === 0) $page = 1;
		if (empty($page)) {
			if (strtolower(element('comment_order', $board)) === 'desc') {
				$page = 1;
			} else {
				$page = $per_page ? ceil(element('post_comment_count', $post) / $per_page) : 1;
				if ($page === 0) {
					$page = 1;
				}
			}
		}
		if ($page < 1) {
			show_404();
		}

		$offset = ($page - 1) * $per_page;

		$this->Comment_model->allow_search_field = array('cmt_id', 'post_id', 'cmt_content', 'cmt_userid', 'cmt_nickname'); // 검색이 가능한 필드
		$this->Comment_model->search_field_equal = array('cmt_id', 'cmt_userid', 'cmt_nickname'); // 검색중 like 가 아닌 = 검색을 하는 필드

		$image_width = ($this->cbconfig->get_device_view_type() === 'mobile')
			? element('post_mobile_image_width', $board)
			: element('post_image_width', $board);
		$use_sideview = ($this->cbconfig->get_device_view_type() === 'mobile')
			? element('use_mobile_sideview', $board)
			: element('use_sideview', $board);
		$use_sideview_icon = ($this->cbconfig->get_device_view_type() === 'mobile')
			? element('use_mobile_sideview_icon', $board)
			: element('use_sideview_icon', $board);
		$comment_date_style = ($this->cbconfig->get_device_view_type() === 'mobile')
			? element('mobile_comment_date_style', $board)
			: element('comment_date_style', $board);
		$comment_date_style_manual = ($this->cbconfig->get_device_view_type() === 'mobile')
			? element('mobile_comment_date_style_manual', $board)
			: element('comment_date_style_manual', $board);
		$comment_best = ($this->cbconfig->get_device_view_type() === 'mobile')
			? element('mobile_comment_best', $board)
			: element('comment_best', $board);

		$board['use_comment_profile'] = ($this->cbconfig->get_device_view_type() === 'mobile')
			? element('use_mobile_comment_profile', $board)
			: element('use_comment_profile', $board);

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['step1'] = Events::trigger('step1', $eventname);

		/**
		 * 상단에 베스트 부분에 필요한 정보를 가져옵니다.
		 */
		$bestresult = '';
		if ($comment_best) {
			$bestresult = $this->Comment_model
				->get_best_list($post_id, $comment_best, element('comment_best_like_num', $board));
			if ($bestresult) {
				foreach ($bestresult as $key => $val) {
					$bestresult[$key]['meta'] = $meta = $this->Comment_meta_model->get_all_meta(element('cmt_id', $val));
					$bestresult[$key]['content'] = '';

					$is_blind = (element('comment_blame_blind_count', $board) > 0 && element('cmt_blame', $val) >= element('comment_blame_blind_count', $board)) ? true : false;

					if ($is_blind === true) {
						$bestresult[$key]['content'] .= '<div class="alert alert-danger">신고가 접수된 게시글입니다. 본인과 관리자만 확인이 가능합니다</div>';
					}
					if (element('cmt_secret', $val)) {
						$bestresult[$key]['content'] .= '<span class="label label-warning">비밀글입니다</span>';
					}
					if (($is_blind === false && ! element('cmt_secret', $val)) OR $is_admin !== false OR (element('mem_id', $val) && (int) element('mem_id', $val) === $mem_id)) {
						$bestresult[$key]['content'] .= display_html_content(
							element('cmt_content', $val),
							element('cmt_html', $val),
							$image_width,
							$autolink = true,
							$popup = true
						);
						if (element('comment_syntax_highlighter', $board)) {
							$bestresult[$key]['content'] = preg_replace_callback(
								"/(\[code\]|\[code=(.*)\])(.*)\[\/code\]/iUs",
								"content_syntaxhighlighter",
								$bestresult[$key]['content']
							); // SyntaxHighlighter
						}
					}
					if (element('cmt_del', $val)) {
						$bestresult[$key]['content'] = '<div class="alert alert-danger">이 게시물은 '
							. html_escape(element('delete_mem_nickname', $meta)) . '님에 의해 '
							. html_escape(element('delete_datetime', $meta)) . ' 에 삭제 되었습니다</div>';
					}
					if (element('mem_id', $val) >= 0) {
						$bestresult[$key]['display_name'] = display_username(
							element('cmt_userid', $val),
							element('cmt_nickname', $val),
							($use_sideview_icon ? element('mem_icon', $val) : ''),
							($use_sideview ? 'Y' : 'N')
						);
					} else {
						$bestresult[$key]['display_name'] = '익명사용자';
					}
					$bestresult[$key]['display_datetime'] = display_datetime(
						element('cmt_datetime', $val),
						$comment_date_style,
						$comment_date_style_manual
					);
					$bestresult[$key]['is_mobile'] = (element('cmt_device', $val) === 'mobile') ? true : false;
					$bestresult[$key]['display_ip'] = '';

					$show_comment_ip = ($this->cbconfig->get_device_view_type() === 'mobile')
						? element('show_mobile_comment_ip', $board)
						: element('show_comment_ip', $board);
					if ($this->member->is_admin() === 'super' OR $show_comment_ip === '2') {
						$bestresult[$key]['display_ip'] = display_ipaddress(element('cmt_ip', $val), '1111');
					} elseif ($show_comment_ip === '1') {
						$bestresult[$key]['display_ip'] = display_ipaddress(element('cmt_ip', $val), $this->cbconfig->item('ip_display_style'));
					}
					$bestresult[$key]['member_photo_url']
						= member_photo_url(element('mem_photo', $val), 64, 64)
						? member_photo_url(element('mem_photo', $val), 64, 64)
						: site_url('assets/images/member_default.gif');
				}
			}
		}

		/**
		 * 게시판 목록에 필요한 정보를 가져옵니다.
		 */
		$where = array(
			'post_id' => $post_id,
			'cmt_del <>' => 2,
		);
		$result = $this->Comment_model
			->get_comment_list($per_page, $offset, $where, '', $findex, $sfield = '', $skeyword = '');
		$list_num = $result['total_rows'] - ($page - 1) * $per_page;

		if (element('list', $result)) {
			foreach (element('list', $result) as $key => $val) {
				$result['list'][$key]['meta'] = $meta
					= $this->Comment_meta_model->get_all_meta(element('cmt_id', $val));
				$result['list'][$key]['content'] = '';

				$is_blind = (element('comment_blame_blind_count', $board) > 0 && element('cmt_blame', $val) >= element('comment_blame_blind_count', $board)) ? true : false;

				if ($is_blind === true) {
					$result['list'][$key]['content'] .= '<div class="alert alert-danger">신고가 접수된 게시글입니다. 본인과 관리자만 확인이 가능합니다</div>';
				}
				if (element('cmt_secret', $val)) {
					$result['list'][$key]['content'] .= '<span class="label label-warning">비밀글입니다</span>';
				}
				if (($is_blind === false && ! element('cmt_secret', $val)) OR $is_admin !== false OR (element('mem_id', $val) && abs(element('mem_id', $val)) === $mem_id)
					OR (element('mem_id', $post) && abs(element('mem_id', $post)) === $mem_id)) {
					$result['list'][$key]['content'] .= display_html_content(
						element('cmt_content', $val),
						element('cmt_html', $val),
						$image_width,
						$autolink = true,
						$popup = true
					);
					if (element('comment_syntax_highlighter', $board)) {
						$result['list'][$key]['content'] = preg_replace_callback(
							"/(\[code\]|\[code=(.*)\])(.*)\[\/code\]/iUs",
							"content_syntaxhighlighter",
							$result['list'][$key]['content']
						); // SyntaxHighlighter
					}
				}
				if (element('cmt_del', $val)) {
					$result['list'][$key]['content'] = '<div class="alert alert-danger">이 게시물은 '
						. html_escape(element('delete_mem_nickname', $meta)) . '님에 의해 '
						. html_escape(element('delete_datetime', $meta)) . ' 에 삭제 되었습니다</div>';
				}
				if (element('mem_id', $val) >= 0) {
					$result['list'][$key]['display_name'] = display_username(
						element('cmt_userid', $val),
						element('cmt_nickname', $val),
						($use_sideview_icon ? element('mem_icon', $val) : ''),
						($use_sideview ? 'Y' : 'N')
					);
				} else {
					$result['list'][$key]['display_name'] = '익명사용자';
				}
				$result['list'][$key]['display_datetime'] = display_datetime(
					element('cmt_datetime', $val),
					$comment_date_style,
					$comment_date_style_manual
				);
				$result['list'][$key]['is_mobile'] = (element('cmt_device', $val) === 'mobile') ? true : false;
				$result['list'][$key]['display_ip'] = '';

				$result['list'][$key]['lucky'] = '';
				if (element('comment-lucky', $meta)) {
					$result['list'][$key]['lucky'] = element('comment_lucky_name', $board). '에 당첨되어 <span class="luckypoint">' . number_format(element('comment-lucky', $meta)) . '</span> 포인트 지급되었습니다.';
				}
				if ($this->member->is_admin() === 'super'
					OR element('show_comment_ip', $board) === '2') {
					$result['list'][$key]['display_ip'] = display_ipaddress(element('cmt_ip', $val), '1111');
				} elseif (element('show_comment_ip', $board) === '1') {
					$result['list'][$key]['display_ip'] = display_ipaddress(element('cmt_ip', $val), $this->cbconfig->item('ip_display_style'));
				}
				$result['list'][$key]['member_photo_url']
					= member_photo_url(element('mem_photo', $val), 64, 64)
					? member_photo_url(element('mem_photo', $val), 64, 64)
					: site_url('assets/images/member_default.gif');

				$result['list'][$key]['cmt_depth'] = strlen($result['list'][$key]['cmt_reply']) * 30;

				$result['list'][$key]['can_update'] = false;
				$result['list'][$key]['can_delete'] = false;
				$result['list'][$key]['can_reply'] = false;
				if ( ! element('post_del', $post) && ! element('cmt_del', $val)) {
					if ( ! element('mem_id', $val)) {
						$result['list'][$key]['can_delete'] = true;
					}
					if ($is_admin !== false
						OR (element('mem_id', $val) && $mem_id === abs(element('mem_id', $val)))) {
						$result['list'][$key]['can_update'] = true;
						$result['list'][$key]['can_delete'] = true;
					}
					if ($key > 0 && $is_admin === false) {
						if (element('cmt_reply', $val)) {
							$prev_reply = substr(
								element('cmt_reply', $val),
								0,
								strlen(element('cmt_reply', $val)) - 1
							);
							if ($prev_reply === $result['list'][$key-1]['cmt_reply']) {
								$result['list'][$key-1]['can_update'] = false;
								$result['list'][$key-1]['can_delete'] = false;
							}
						}
					}
					if (element('block_delete', $board) && $is_admin === false) {
						$result['list'][$key]['can_delete'] = false;
					}
					if (strlen(element('cmt_reply', $val)) < 5 && $can_comment_write === true) {
						$result['list'][$key]['can_reply'] = true;
					}
				}
			}
		}

		$view['view']['data'] = $result;
		$view['view']['best_list'] = $bestresult;
		$view['view']['board'] = $board;
		$view['view']['post'] = $post;
		$view['view']['is_admin'] = $is_admin;

		/**
		 * primary key 정보를 저장합니다
		 */
		$view['view']['primary_key'] = $this->Post_model->primary_key;

		/**
		 * 페이지네이션을 생성합니다
		 */
		$config['base_url'] = site_url('comment_list/lists/' . $post_id) . '?' . $param->replace('page');
		$config['total_rows'] = $result['total_rows'];
		$config['per_page'] = $per_page;

		if ( ! $this->input->get('page')) {
			$_GET['page'] = (string) $page;
		}

		$config['_attributes'] = 'onClick="comment_page(\'' . $post_id . '\', $(this).attr(\'data-ci-pagination-page\'));return false;"';
		if ($this->cbconfig->get_device_view_type() === 'mobile') {
			$config['num_links'] = element('mobile_comment_page_count', $board)
				? element('mobile_comment_page_count', $board) : 3;
		} else {
			$config['num_links'] = element('comment_page_count', $board)
				? element('comment_page_count', $board) : 5;
		}
		$this->pagination->initialize($config);
		$view['view']['paging'] = $this->pagination->create_links();
		$view['view']['page'] = $page;

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 레이아웃을 정의합니다
		 */
		$skindir = ($this->cbconfig->get_device_view_type() === 'mobile')
			? element('board_mobile_skin', $board)
			: element('board_skin', $board);
		if (empty($skindir)) {
			$skindir = ($this->cbconfig->get_device_view_type() === 'mobile')
				? $this->cbconfig->item('mobile_skin_board')
				: $this->cbconfig->item('skin_board');
		}
		if (empty($skindir)) {
			$skindir = ($this->cbconfig->get_device_view_type() === 'mobile')
				? $this->cbconfig->item('mobile_skin_default')
				: $this->cbconfig->item('skin_default');
		}
		if (empty($skindir)) {
			$skindir = 'basic';
		}
		$skin = 'board/' . $skindir . '/comment_list';

		$this->data = $view;
		$this->view = $skin;
	}


	/**
	 * 비회원이 작성한 댓글 삭제시 패스워드를 물을 때 필요합니다.
	 */
	public function password($post_id = 0, $cmt_id = 0)
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_comment_list_password';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		$cmt_id = (int) $cmt_id;
		if (empty($cmt_id) OR $cmt_id < 1) {
			show_404();
		}

		$post_id = (int) $post_id;
		if (empty($post_id) OR $post_id < 1) {
			show_404();
		}


		$comment = $this->Comment_model->get_one($cmt_id);
		if ( ! element('cmt_id', $comment)) {
			show_404();
		}
		if ((int) element('post_id', $comment) !== $post_id) {
			show_404();
		}

		$post = $this->Post_model->get_one($post_id);
		if ( ! element('post_id', $post)) {
			show_404();
		}

		$board = $this->board->item_all(element('brd_id', $post));

		/**
		 * Validation 라이브러리를 가져옵니다
		 */
		$this->load->library('form_validation');

		/**
		 * 전송된 데이터의 유효성을 체크합니다
		 */
		$config = array(
			array(
				'field' => 'modify_password',
				'label' => '패스워드',
				'rules' => 'trim|required',
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

			$modify_password = $this->input->post('modify_password');
			$return = $this->board->delete_comment_check($cmt_id, $modify_password);
			$result = json_decode($return, true);

			if (element('error', $result)) {
				$view['view']['message'] = element('error', $result);
			}
			if (element('password', $result)) {
				$view['view']['message'] = element('password', $result);
			}
			if (element('success', $result)) {
				redirect(post_url(element('brd_key', $board), $post_id));
				return;
			}
		}

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 레이아웃을 정의합니다
		 */
		$view['view']['info'] = '댓글 삭제를 위한 패스워드 입력페이지입니다.<br />패스워드를 입력하시면 댓글 삭제가 가능합니다';

		$page_title = element('board_name', $board) . ' 댓글수정';
		$layout_dir = element('board_layout', $board) ? element('board_layout', $board) : $this->cbconfig->item('layout_board');
		$mobile_layout_dir = element('board_mobile_layout', $board) ? element('board_mobile_layout', $board) : $this->cbconfig->item('mobile_layout_board');
		$use_sidebar = element('board_sidebar', $board) ? element('board_sidebar', $board) : $this->cbconfig->item('sidebar_board');
		$use_mobile_sidebar = element('board_mobile_sidebar', $board) ? element('board_mobile_sidebar', $board) : $this->cbconfig->item('mobile_sidebar_board');
		$skin_dir = element('board_skin', $board) ? element('board_skin', $board) : $this->cbconfig->item('skin_board');
		$mobile_skin_dir = element('board_mobile_skin', $board) ? element('board_mobile_skin', $board) : $this->cbconfig->item('mobile_skin_board');
		$layoutconfig = array(
			'path' => 'board',
			'layout' => 'layout',
			'skin' => 'password',
			'layout_dir' => $layout_dir,
			'mobile_layout_dir' => $mobile_layout_dir,
			'use_sidebar' => $use_sidebar,
			'use_mobile_sidebar' => $use_mobile_sidebar,
			'skin_dir' => $skin_dir,
			'mobile_skin_dir' => $mobile_skin_dir,
			'page_title' => $page_title,
		);
		$view['layout'] = $this->managelayout->front($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
		return true;
	}
}
