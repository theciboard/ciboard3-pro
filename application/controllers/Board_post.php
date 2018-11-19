<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Board_post class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 게시판 목록과 게시물 열람 페이지에 관한 controller 입니다.
 */
class Board_post extends CB_Controller
{

	/**
	 * 모델을 로딩합니다
	 */
	protected $models = array('Post', 'Post_meta', 'Post_extra_vars');

	/**
	 * 헬퍼를 로딩합니다
	 */
	protected $helpers = array('form', 'array', 'number');

	function __construct()
	{
		parent::__construct();

		/**
		 * 라이브러리를 로딩합니다
		 */
		$this->load->library(array('pagination', 'querystring', 'accesslevel', 'videoplayer', 'point'));
	}


	/**
	 * 게시판 목록입니다.
	 */
	public function lists($brd_key = '')
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_board_post_lists';
		$this->load->event($eventname);

		if (empty($brd_key)) {
			show_404();
		}

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		$view['view']['list'] = $list = $this->_get_list($brd_key);
		$view['view']['board_key'] = element('brd_key', element('board', $list));

		// stat_count_board ++
		$this->_stat_count_board(element('brd_id', element('board', $list)));

		$view['view']['is_admin'] = $is_admin = $this->member->is_admin(
			array(
				'board_id' => element('brd_id', element('board', $list)),
				'group_id' => element('bgr_id', element('board', $list)),
			)
		);

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 레이아웃을 정의합니다
		 */
		$page_title = $this->cbconfig->item('site_meta_title_board_list');
		$meta_description = $this->cbconfig->item('site_meta_description_board_list');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_board_list');
		$meta_author = $this->cbconfig->item('site_meta_author_board_list');
		$page_name = $this->cbconfig->item('site_page_name_board_list');

		$searchconfig = array(
			'{게시판명}',
		);
		$replaceconfig = array(
			element('board_name', element('board', $list)),
		);

		$page_title = str_replace($searchconfig, $replaceconfig, $page_title);
		$meta_description = str_replace($searchconfig, $replaceconfig, $meta_description);
		$meta_keywords = str_replace($searchconfig, $replaceconfig, $meta_keywords);
		$meta_author = str_replace($searchconfig, $replaceconfig, $meta_author);
		$page_name = str_replace($searchconfig, $replaceconfig, $page_name);

		$list_skin_file = element('use_gallery_list', element('board', $list)) ? 'gallerylist' : 'list';
		$layout_dir = element('board_layout', element('board', $list)) ? element('board_layout', element('board', $list)) : $this->cbconfig->item('layout_board');
		$mobile_layout_dir = element('board_mobile_layout', element('board', $list)) ? element('board_mobile_layout', element('board', $list)) : $this->cbconfig->item('mobile_layout_board');
		$use_sidebar = element('board_sidebar', element('board', $list)) ? element('board_sidebar', element('board', $list)) : $this->cbconfig->item('sidebar_board');
		$use_mobile_sidebar = element('board_mobile_sidebar', element('board', $list)) ? element('board_mobile_sidebar', element('board', $list)) : $this->cbconfig->item('mobile_sidebar_board');
		$skin_dir = element('board_skin', element('board', $list)) ? element('board_skin', element('board', $list)) : $this->cbconfig->item('skin_board');
		$mobile_skin_dir = element('board_mobile_skin', element('board', $list)) ? element('board_mobile_skin', element('board', $list)) : $this->cbconfig->item('mobile_skin_board');
		$layoutconfig = array(
			'path' => 'board',
			'layout' => 'layout',
			'skin' => $list_skin_file,
			'layout_dir' => $layout_dir,
			'mobile_layout_dir' => $mobile_layout_dir,
			'use_sidebar' => $use_sidebar,
			'use_mobile_sidebar' => $use_mobile_sidebar,
			'skin_dir' => $skin_dir,
			'mobile_skin_dir' => $mobile_skin_dir,
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
	 * 게시물 열람 페이지입니다
	 */
	public function post($post_id = 0, $print = false)
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_board_post_post';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		/**
		 * 프라이머리키에 숫자형이 입력되지 않으면 에러처리합니다
		 */
		$post_id = (int) $post_id;
		if (empty($post_id) OR $post_id < 1) {
			show_404();
		}

		$post = $this->Post_model->get_one($post_id);
		$post['meta'] = $this->Post_meta_model->get_all_meta($post_id);
		$post['extravars'] = $this->Post_extra_vars_model->get_all_meta($post_id);
		$view['view']['post'] = $post;

		$mem_id = (int) $this->member->item('mem_id');

		if ( ! element('post_id', $post)) {
			show_404();
		}
		if (element('post_del', $post) > 1) {
			show_404();
		}

		$board = $this->board->item_all(element('brd_id', $post));

		if ( ! element('brd_id', $board)) {
			show_404();
		}

		$skeyword = $this->input->get('skeyword', null, '');

		if ($print === false && $this->uri->segment('1') !== config_item('uri_segment_admin')) {
			if (strtoupper(config_item('uri_segment_post_type')) === 'B') {
				if ($this->uri->segment('1') !== element('brd_key', $board)) {
					show_404();
				}
			} elseif (strtoupper(config_item('uri_segment_post_type')) === 'C') {
				if ($this->uri->segment('2') !== element('brd_key', $board)) {
					show_404();
				}
			}
		}

		$alertmessage = $this->member->is_member()
			? '회원님은 내용을 볼 수 있는 권한이 없습니다'
			: '비회원은 내용을 볼 수 있는 권한이 없습니다.\\n\\n회원이시라면 로그인 후 이용해 보십시오';

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

		$view['view']['is_admin'] = $is_admin = $this->member->is_admin(
			array(
				'board_id' => element('brd_id', $board),
				'group_id' => element('bgr_id', $board)
			)
		);
		$view['view']['board_key'] = element('brd_key', $board);

		if (element('use_personal', $board) && $this->member->is_member() === false) {
			alert('이 게시판은 1:1 게시판입니다. 비회원은 접근할 수 없습니다');
			return false;
		}


		if ($print && ! element('use_print', $board)) {
			alert('이 게시판은 프린트 기능을 지원하지 않습니다');
			return false;
		}

		if (element('post_secret', $post)) {
			if (element('mem_id', $post)) {
				if ($is_admin === false && $mem_id !== abs(element('mem_id', $post))) {
					alert('비밀글은 본인과 관리자만 확인 가능합니다');
					return false;
				}
			} else {
				if ($is_admin !== false) {
					$this->session->set_userdata(
						'view_secret_' . element('post_id', $post),
						'1'
					);
				}
				if ( ! $this->session->userdata('view_secret_' . element('post_id', $post))
					&& $this->input->post('modify_password')) {
					if ( ! function_exists('password_hash')) {
						$this->load->helper('password');
					}

					if ( password_verify($this->input->post('modify_password'), element('post_password', $post))) {
						$this->session->set_userdata(
							'view_secret_' . element('post_id', $post),
							'1'
						);
						redirect(current_url());
					} else {
						$view['view']['message'] = '패스워드가 잘못 입력되었습니다';
					}
				}
				if ( ! $this->session->userdata('view_secret_' . element('post_id', $post))) {

					// 이벤트가 존재하면 실행합니다
					$view['view']['event']['before_secret_layout']
						= Events::trigger('before_secret_layout', $eventname);

					/**
					 * 레이아웃을 정의합니다
					 */
					$view['view']['info'] = '비밀글 열람을 위한 패스워드 입력페이지입니다.<br />패스워드를 입력하시면 비밀글 열람이 가능합니다';
					$page_title = element('board_name', $board) . ' 글열람';
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
		}

		if ($mem_id > 0 && $mem_id !== abs(element('mem_id', $post))
			&& element('use_point', $board)) {
			$point = $this->point->insert_point(
				$mem_id,
				element('point_read', $board),
				element('board_name', $board) . ' ' . $post_id . ' 게시글열람',
				'post_read',
				$post_id,
				'게시글열람'
			);

			if (element('point_read', $board) < 0 && $point < 0
				&& $this->cbconfig->item('block_read_zeropoint')) {
				$this->point->delete_point(
					$mem_id,
					'post_read',
					$post_id,
					'게시글열람'
				);
				alert('회원님은 포인트가 부족하므로 글을 열람하실 수 없습니다. 글 읽기시 ' . (element('point_read', $board) * -1) . ' 포인트가 차감됩니다');
				return false;
			}
		}
		if (element('use_personal', $board) && $is_admin === false
			&& $mem_id !== abs(element('mem_id', $post))) {
			alert('1:1 게시판은 본인의 글 이외의 열람이 금지되어있습니다.');
			return false;
		}

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['step1'] = Events::trigger('step1', $eventname);

		$this->_stat_count_board(element('brd_id', $board)); // stat_count_board ++

		// 세션 생성
		if ( ! $this->session->userdata('post_id_' . $post_id)) {
			$this->Post_model->update_plus($post_id, 'post_hit', 1);
			$this->session->set_userdata(
				'post_id_' . $post_id,
				'1'
			);
		}

		$use_sideview = ($this->cbconfig->get_device_view_type() === 'mobile')
			? element('use_mobile_sideview', $board)
			: element('use_sideview', $board);
		$use_sideview_icon = ($this->cbconfig->get_device_view_type() === 'mobile')
			? element('use_mobile_sideview_icon', $board)
			: element('use_sideview_icon', $board);
		$view_date_style = ($this->cbconfig->get_device_view_type() === 'mobile')
			? element('mobile_view_date_style', $board)
			: element('view_date_style', $board);
		$view_date_style_manual = ($this->cbconfig->get_device_view_type() === 'mobile')
			? element('mobile_view_date_style_manual', $board)
			: element('view_date_style_manual', $board);

		if (element('mem_id', $post) >= 0) {
			$dbmember = $this->Member_model
				->get_by_memid(element('mem_id', $post), 'mem_icon');
			$view['view']['post']['display_name'] = display_username(
				element('post_userid', $post),
				element('post_nickname', $post),
				($use_sideview_icon ? element('mem_icon', $dbmember) : ''),
				($use_sideview ? 'Y' : 'N')
			);
		} else {
			$view['view']['post']['display_name'] = '익명사용자';
		}
		$view['view']['post']['display_datetime'] = display_datetime(
			element('post_datetime', $post),
			$view_date_style,
			$view_date_style_manual
		);
		$view['view']['post']['is_mobile'] = (element('post_device', $post) === 'mobile') ? true : false;
		$view['view']['post']['category'] = '';
		if (element('use_category', $board) && element('post_category', $post)) {
			$this->load->model('Board_category_model');
			$view['view']['post']['category'] = $this->Board_category_model
				->get_category_info(element('brd_id', $post), element('post_category', $post));
		}

		$view['view']['post']['display_ip'] = '';

		$show_ip = ($this->cbconfig->get_device_view_type() === 'mobile')
			? element('show_mobile_ip', $board)
			: element('show_ip', $board);

		if ($this->member->is_admin() === 'super' OR $show_ip === '2') {
			$view['view']['post']['display_ip'] = display_ipaddress(element('post_ip', $post), '1111');
		} elseif ($show_ip === '1') {
			$view['view']['post']['display_ip'] = display_ipaddress(element('post_ip', $post), $this->cbconfig->item('ip_display_style'));
		}
		$image_width = ($this->cbconfig->get_device_view_type() === 'mobile')
			? element('post_mobile_image_width', $board)
			: element('post_image_width', $board);

		$board['target_blank'] = $target_blank
			= ($this->cbconfig->get_device_view_type() === 'mobile')
			? element('mobile_content_target_blank', $board)
			: element('content_target_blank', $board);

		$board['show_url_qrcode'] = ($this->cbconfig->get_device_view_type() === 'mobile')
			? element('use_mobile_url_qrcode', $board)
			: element('use_url_qrcode', $board);

		$board['show_attached_url_qrcode'] = ($this->cbconfig->get_device_view_type() === 'mobile')
			? element('use_mobile_attached_url_qrcode', $board)
			: element('use_attached_url_qrcode', $board);

		$link_player = '';
		$view['view']['link'] = $link = array();

		if (element('post_link_count', $post)) {
			$this->load->model('Post_link_model');
			$linkwhere = array(
				'post_id' => $post_id,
			);
			$view['view']['link'] = $link = $this->Post_link_model
				->get('', '', $linkwhere, '', '', 'pln_id', 'ASC');
			if ($link && is_array($link)) {
				foreach ($link as $key => $value) {
					$view['view']['link'][$key]['link_link'] = site_url('postact/link/' . element('pln_id', $value));
					if (element('use_autoplay', $board)) {
						$link_player .= $this->videoplayer->
							get_video(prep_url(element('pln_url', $value)));
					}
				}
			}
		}
		$view['view']['link_count'] = $link_count = count($link);

		$file_player = '';
		if (element('post_file', $post) OR element('post_image', $post)) {
			$this->load->model('Post_file_model');
			$filewhere = array(
				'post_id' => $post_id,
			);
			$view['view']['file'] = $file = $this->Post_file_model
				->get('', '', $filewhere, '', '', 'pfi_id', 'ASC');
			$view['view']['file_download'] = array();
			$view['view']['file_image'] = array();

			$play_extension = array('acc', 'flv', 'f4a', 'f4v', 'mov', 'mp3', 'mp4', 'm4a', 'm4v', 'oga', 'ogg', 'rss', 'webm');

			if ($file && is_array($file)) {
				foreach ($file as $key => $value) {
					if (element('pfi_is_image', $value)) {
						$value['origin_image_url'] = site_url(config_item('uploads_dir') . '/post/' . element('pfi_filename', $value));
						$value['thumb_image_url'] = thumb_url('post', element('pfi_filename', $value), $image_width);
						$view['view']['file_image'][] = $value;
					} else {
						$value['download_link'] = site_url('postact/download/' . element('pfi_id', $value));
						$view['view']['file_download'][] = $value;
						if (element('use_autoplay', $board) && in_array(element('pfi_type', $value), $play_extension)) {
							$file_player .= $this->videoplayer->get_jwplayer(site_url(config_item('uploads_dir') . '/post/' . element('pfi_filename', $value)), $image_width);
						}
					}
				}
			}
			$view['view']['file_count'] = count($file);
			$view['view']['file_download_count'] = count($view['view']['file_download']);
			$view['view']['file_image_count'] = count($view['view']['file_image']);
		}

		if (element('use_poll', $board) OR element('use_mobile_poll', $board)) {
			$this->load->model(array('Post_poll_model', 'Post_poll_item_model', 'Post_poll_item_poll_model'));
			$pollwhere = array(
				'post_id' => $post_id,
			);
			$poll = $this->Post_poll_model->get_one('', '', $pollwhere);
			$pollwhere = array(
				'ppo_id' => element('ppo_id', $poll),
			);
			$poll_item = $this->Post_poll_item_model
				->get('', '', $pollwhere, '', '', 'ppi_id', 'ASC');

			if (empty($poll['ppo_start_datetime'])
				OR $poll['ppo_start_datetime'] === '0000-00-00 00:00:00') {
				$poll['ppo_start_datetime'] = '';
			}
			if (empty($poll['ppo_end_datetime'])
				OR $poll['ppo_end_datetime'] === '0000-00-00 00:00:00') {
				$poll['ppo_end_datetime'] = '';
			}
			$poll['poll_period'] = '';
			if ($poll['ppo_start_datetime']) {
				$poll['poll_period'] .= cdate('Y월 m일 d일 H시', strtotime($poll['ppo_start_datetime']));
			}
			$poll['poll_period'] .= '~';
			if ($poll['ppo_end_datetime']) {
				$poll['poll_period'] .= cdate('Y월 m일 d일 H시', strtotime($poll['ppo_end_datetime']));
			}
			if (empty($poll['ppo_start_datetime']) && empty($poll['ppo_end_datetime'])) {
				$poll['poll_period'] = '제한 없음';
			}
			$poll['ended_poll'] = false;
			if ($poll['ppo_end_datetime'] && $poll['ppo_end_datetime'] < cdate('Y-m-d H:i:s')) {
				$poll['ended_poll'] = true;
			}

			$post_poll_count = 0;
			if ($this->member->is_member()) {
				$where = array(
					'ppo_id' => element('ppo_id', $poll),
					'mem_id' => $mem_id,
				);
				$post_poll_count = $this->Post_poll_item_poll_model->count_by($where);
			}
			if ($post_poll_count > 0 OR $poll['ended_poll']) {
				if ($post_poll_count) {
					$poll['attended'] = true;
				}
				$sum_count = 0;
				$max = 0;

				if ($poll_item && is_array($poll_item)) {
					foreach ($poll_item as $key => $value) {
						if ($value['ppi_count'] > $max) {
							$max = $value['ppi_count'];
						}
						$sum_count+= $value['ppi_count'];
					}
					foreach ($poll_item as $key => $value) {
						$rate = $sum_count ? ($value['ppi_count'] / $sum_count * 100) : 0;
						$poll_item[$key]['rate'] = $rate;
						$s_rate = number_format($rate, 1);
						$poll_item[$key]['s_rate'] = $s_rate;

						$bar = $max ? (int) ($value['ppi_count'] / $max * 100) : 0;
						$poll_item[$key]['bar'] = $bar;
					}
				}

			}
			$view['view']['poll'] = $poll;
			$view['view']['poll_item'] = $poll_item;
		}

		$autourl = ($this->cbconfig->get_device_view_type() === 'mobile')
			? element('use_mobile_auto_url', $board)
			: element('use_auto_url', $board);

		$autolink = $autourl ? true : false;
		$popup = $target_blank ? true : false;

		$view['view']['post']['content'] = '';

		if (element('post_del', $post)) {

			$view['view']['post']['post_title'] = '게시물이 삭제되었습니다';
			$view['view']['post']['content'] = '<div class="alert alert-danger">이 게시물은 '
				. html_escape(element('delete_mem_nickname', element('meta', $post)))
				. '님에 의해 '
				. html_escape(element('delete_datetime', element('meta', $post)))
				. ' 에 삭제 되었습니다</div>';

		} else {
			$is_blind = (element('blame_blind_count', $board) > 0 && element('post_blame', $post) >= element('blame_blind_count', $board)) ? true : false;
			if ($is_blind === true) {
				$view['view']['post']['content'] .= '<div class="alert alert-danger">신고가 접수된 게시글입니다. 본인과 관리자만 확인이 가능합니다</div>';
			}

			if ($is_blind === false OR $is_admin !== false
				OR (element('mem_id', $post) && abs(element('mem_id', $post)) === $mem_id)) {
				$view['view']['post']['content'] .= $file_player . $link_player
					. display_html_content(
						element('post_content', $post),
						element('post_html', $post),
						$image_width,
						$autolink,
						$popup
					);

				if (element('syntax_highlighter', $board)) {
					if (element('post_html', $post)) {
						$view['view']['post']['content'] = preg_replace_callback(
							"/(\[code\]|\[code=(.*)\])(.*)\[\/code\]/iUs",
							"content_syntaxhighlighter_html",
							$view['view']['post']['content']
						); // SyntaxHighlighter
					} else {
						$view['view']['post']['content'] = preg_replace_callback(
							"/(\[code\]|\[code=(.*)\])(.*)\[\/code\]/iUs",
							"content_syntaxhighlighter",
							$view['view']['post']['content']
						); // SyntaxHighlighter
					}
				}
			}

			$view['view']['tag'] = '';
			if (element('use_post_tag', $board)) {
				$this->load->model('Post_tag_model');
				$tagwhere = array(
					'post_id' => $post_id,
				);
				$view['view']['post']['tag'] = $tag = $this->Post_tag_model
					->get('', '', $tagwhere, '', '', 'pta_id', 'ASC');
			}

			$extravars = element('extravars', $board);
			$form = json_decode($extravars, true);
			$extra_content = '';
			$k = 0;
			if ($form && is_array($form)) {
				foreach ($form as $key => $value) {
					if ( ! element('use', $value)) {
						continue;
					}

					$item = element(element('field_name', $value), element('extravars', $post));
					$extra_content[$k]['field_name'] = element('field_name', $value);
					$extra_content[$k]['display_name'] = element('display_name', $value);
					if (element('field_type', $value) === 'checkbox') {
						$tmp_value = json_decode($item);
						$tmp = '';
						if ($tmp_value) {
							foreach ($tmp_value as $val) {
								if ($tmp) {
									$tmp .= ', ';
								}
								$tmp .= $val;
							}
						}
						$item = $tmp;
					}
					$extra_content[$k]['output'] = $item;
					$k++;
				}
			}

			$view['view']['extra_content'] = $extra_content;
		}
		$show_list_from_view = ($this->cbconfig->get_device_view_type() === 'mobile')
			? element('mobile_show_list_from_view', $board)
			: element('show_list_from_view', $board);

		$board['headercontent'] = ($this->cbconfig->get_device_view_type() === 'mobile')
			? element('mobile_header_content', $board)
			: element('header_content', $board);

		if (empty($show_list_from_view)) {
			$board['footercontent'] = ($this->cbconfig->get_device_view_type() === 'mobile')
				? element('mobile_footer_content', $board)
				: element('footer_content', $board);
		}

		$skindir = ($this->cbconfig->get_device_view_type() === 'mobile')
			? (element('board_mobile_skin', $board)
				? element('board_mobile_skin', $board)
				: element('board_skin', $board))
			: element('board_skin', $board);
		$skinurl = base_url( VIEW_DIR . 'board/' . $skindir);

		$view['view']['post_url'] = $post_url = post_url(element('brd_key', $board), $post_id);

		$param =& $this->querystring;

		$view['view']['board'] = $board;
		$this->load->model('Scrap_model');
		$countwhere = array(
			'post_id' => element('post_id', $post),
		);
		$view['view']['post']['scrap_count'] = $this->Scrap_model->count_by($countwhere);

		$view['view']['comment']['is_cmt_name'] = $is_cmt_name
			= ($this->member->is_member() === false) ? true : false;

		$view['view']['comment']['use_emoticon']
			= ($this->cbconfig->get_device_view_type() === 'mobile')
			? element('use_mobile_comment_emoticon', $board)
			: element('use_comment_emoticon', $board);

		$view['view']['comment']['use_specialchars']
			= ($this->cbconfig->get_device_view_type() === 'mobile')
			? element('use_mobile_comment_specialchars', $board)
			: element('use_comment_specialchars', $board);

		$view['view']['comment']['show_textarea']
			= ($this->cbconfig->get_device_view_type() === 'mobile')
			? element('mobile_always_show_comment_textarea', $board)
			: element('always_show_comment_textarea', $board);

		$check = array(
			'group_id' => element('bgr_id', $board),
			'board_id' => element('brd_id', $board)
		);
		$can_write = $this->accesslevel->is_accessable(
			element('access_write', $board),
			element('access_write_level', $board),
			element('access_write_group', $board),
			$check
		);
		$can_comment_write = $this->accesslevel->is_accessable(
			element('access_comment', $board),
			element('access_comment_level', $board),
			element('access_comment_group', $board),
			$check
		);

		$can_comment_write_message = '';
		if ($can_comment_write === false) {
			$can_comment_write_message = '비회원은 댓글쓰기 권한이 없습니다. 회원이시라면 로그인후 이용해보십시오';
		}
		$can_reply = $this->accesslevel->is_accessable(
			element('access_reply', $board),
			element('access_reply_level', $board),
			element('access_reply_group', $board),
			$check
		);

		$can_modify = ($is_admin !== false OR ! element('mem_id', $post)
			OR (element('mem_id', $post) && $mem_id === abs(element('mem_id', $post)))) ? true : false;
		$can_delete = ($is_admin !== false OR ! element('mem_id', $post)
			OR (element('mem_id', $post) && $mem_id === abs(element('mem_id', $post)))) ? true : false;

		$view['view']['write_url'] = '';
		if ($can_write === true) {
			$view['view']['write_url'] = write_url(element('brd_key', $board));
		} elseif ($this->cbconfig->get_device_view_type() !== 'mobile'
			&& element('always_show_write_button', $board)) {
			$view['view']['write_url'] = 'javascript:alert(\'비회원은 글쓰기 권한이 없습니다.\\n\\n회원이시라면 로그인 후 이용해 보십시오.\');';
		} elseif ($this->cbconfig->get_device_view_type() === 'mobile'
			&& element('mobile_always_show_write_button', $board)) {
			$view['view']['write_url'] = 'javascript:alert(\'비회원은 글쓰기 권한이 없습니다.\\n\\n회원이시라면 로그인 후 이용해 보십시오.\');';
		}

		$view['view']['reply_url'] = ($can_reply === true && ! element('post_del', $post))
			? reply_url(element('post_id', $post)) : '';
		$view['view']['modify_url'] = ($can_modify && ! element('post_del', $post))
			? modify_url(element('post_id', $post) . '?' . $param->output()) : '';
		$view['view']['delete_url'] = ($can_delete && ! element('post_del', $post))
			? site_url('postact/delete/' . element('post_id', $post) . '?' . $param->output()) : '';

		if ($skeyword) {
			$view['view']['list_url'] = board_url(element('brd_key', $board));
			$view['view']['search_list_url'] = board_url(element('brd_key', $board) . '?' . $param->output());
		} else {
			$view['view']['list_url'] = board_url(element('brd_key', $board) . '?' . $param->output());
			$view['view']['search_list_url'] = '';
		}
		$view['view']['trash_url'] = site_url('boards/trash/' . element('post_id', $post) . '?' . $param->output());

		if (element('notice_comment_block', $board) && element('post_notice', $post)) {
			$can_comment_write = false;
			$can_comment_write_message = '공지사항 글에는 댓글 입력이 제한되어 있습니다.';
		}
		if (element('post_del', $post)) {
			$can_comment_write = false;
			$can_comment_write_message = '삭제된 글에는 댓글 입력이 제한되어 있습니다.';
		}

		$use_sns_button = false;
		if ($this->cbconfig->get_device_view_type() !== 'mobile' && element('use_sns', $board)) {
			$use_sns_button = true;
		}
		if ($this->cbconfig->get_device_view_type() === 'mobile'
			&& element('use_mobile_sns', $board)) {
			$use_sns_button = true;
		}
		$view['view']['use_sns_button'] = $use_sns_button;

		$highlight_keyword = '';
		if ($skeyword) {
			$key_explode = explode(' ', $skeyword);
			if ($key_explode) {
				foreach ($key_explode as $seval) {
					if ($highlight_keyword) {
						$highlight_keyword .= ',';
					}
					$highlight_keyword .= '\'' . html_escape($seval) . '\'';
				}
			}
		}
		$view['view']['highlight_keyword'] = $highlight_keyword;

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['step2'] = Events::trigger('step2', $eventname);


		$view['view']['next_post'] = '';
		$view['view']['prev_post'] = '';
		$use_prev_next = false;
		if ($this->cbconfig->get_device_view_type() !== 'mobile'
			&& element('use_prev_next_post', $board)) {
			$use_prev_next = true;
		}
		if ($this->cbconfig->get_device_view_type() === 'mobile'
			&& element('use_mobile_prev_next_post', $board)) {
			$use_prev_next = true;
		}
		if ($use_prev_next) {
			$where = array();
			$where['brd_id'] = element('brd_id', $post);
			$where['post_del <>'] =2;
			$where['post_secret'] = 0;
			if (element('except_notice', $board)
				&& $this->cbconfig->get_device_view_type() !== 'mobile') {
				$where['post_notice'] = 0;
			}
			if (element('mobile_except_notice', $board)
				&& $this->cbconfig->get_device_view_type() === 'mobile') {
				$where['post_notice'] = 0;
			}
			if (element('use_personal', $board) && $is_admin === false) {
				$where['post.mem_id'] = $mem_id;
			}
			$sfield = $sfieldchk = $this->input->get('sfield', null, '');
			if ($sfield === 'post_both') {
				$sfield = array('post_title', 'post_content');
			}
			$skeyword = $this->input->get('skeyword', null, '');
			$view['view']['next_post'] = $next_post
				= $this->Post_model
				->get_prev_next_post(
					element('post_id', $post),
					element('post_num', $post),
					'next',
					$where,
					$sfield,
					$skeyword
				);

			if (element('post_id', $next_post)) {
				$view['view']['next_post']['url'] = post_url(element('brd_key', $board), element('post_id', $next_post)) . '?' . $param->output();
			}

			$view['view']['prev_post'] = $prev_post
				= $this->Post_model
				->get_prev_next_post(
					element('post_id', $post),
					element('post_num', $post),
					'prev',
					$where,
					$sfield,
					$skeyword
				);
			if (element('post_id', $prev_post)) {
				$view['view']['prev_post']['url'] = post_url(element('brd_key', $board), element('post_id', $prev_post)) . '?' . $param->output();
			}
		}

		$view['view']['comment']['can_comment_write'] = $can_comment_write;
		$view['view']['comment']['can_comment_write_message']
			= $can_comment_write_message;
		$view['view']['comment']['can_comment_view'] = true;

		$view['view']['comment']['is_comment_name']
			= ($this->member->is_member() === false) ? true : false;
		$view['view']['comment']['can_comment_secret']
			= (element('use_comment_secret', $board) === '1' && $this->member->is_member())
			? true : false;
		$view['view']['comment']['cmt_secret']
			= element('use_comment_secret_selected', $board) ? '1' : '';

		$password_length = $this->cbconfig->item('password_length');
		$view['view']['comment']['password_length'] = $password_length;
		$view['view']['comment']['cmt_content']
			= ($this->cbconfig->get_device_view_type() === 'mobile')
			? element('mobile_comment_default_content', $board)
			: element('comment_default_content', $board);

		if ($show_list_from_view) {
			$view['view']['list'] = $list = $this->_get_list(element('brd_key', $board), 1);
		}


		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 레이아웃을 정의합니다
		 */
		$page_title = $this->cbconfig->item('site_meta_title_board_post');
		$meta_description = $this->cbconfig->item('site_meta_description_board_post');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_board_post');
		$meta_author = $this->cbconfig->item('site_meta_author_board_post');
		$page_name = $this->cbconfig->item('site_page_name_board_post');

		$searchconfig = array(
			'{게시판명}',
			'{게시판아이디}',
			'{글제목}',
			'{작성자명}',
		);
		$replaceconfig = array(
			element('board_name', $board),
			element('brd_key', $board),
			element('post_title', $post),
			element('post_nickname', $post),
		);

		$page_title = str_replace($searchconfig, $replaceconfig, $page_title);
		$meta_description = str_replace($searchconfig, $replaceconfig, $meta_description);
		$meta_keywords = str_replace($searchconfig, $replaceconfig, $meta_keywords);
		$meta_author = str_replace($searchconfig, $replaceconfig, $meta_author);
		$page_name = str_replace($searchconfig, $replaceconfig, $page_name);

		if ($print === false) {

			// 이벤트가 존재하면 실행합니다
			$view['view']['event']['before_post_layout'] = Events::trigger('before_post_layout', $eventname);

			$view['view']['short_url'] = $view['view']['canonical'] = post_url(element('brd_key', $board), $post_id);

			if(element('use_bitly', $board)) {
				if(element('bitly_url', element('meta', $post))) {
					$view['view']['short_url'] = element('bitly_url', element('meta', $post));
				} elseif($this->cbconfig->item('bitly_access_token')) {
					$this->load->helper('bitly_helper');
					$bitlyparams = array();
					$bitlyparams['access_token'] = $this->cbconfig->item('bitly_access_token');
					$bitlyparams['longUrl'] = post_url(element('brd_key', $board), $post_id);
					$bitlyparams['domain'] = 'bit.ly';
					$bitlyresult = bitly_get('shorten', $bitlyparams);
					if(element('status_code', $bitlyresult) === 200) {
						$bitlydata = array('bitly_url' => element('url', element('data', $bitlyresult)));
						$this->Post_meta_model->save($post_id, element('brd_id', $board), $bitlydata);
						$view['view']['short_url'] = element('url', element('data', $bitlyresult));
					}
				}
			}

			$layout_dir = element('board_layout', $board) ? element('board_layout', $board) : $this->cbconfig->item('layout_board');
			$mobile_layout_dir = element('board_mobile_layout', $board) ? element('board_mobile_layout', $board) : $this->cbconfig->item('mobile_layout_board');
			$use_sidebar = element('board_sidebar', $board) ? element('board_sidebar', $board) : $this->cbconfig->item('sidebar_board');
			$use_mobile_sidebar = element('board_mobile_sidebar', $board) ? element('board_mobile_sidebar', $board) : $this->cbconfig->item('mobile_sidebar_board');
			$skin_dir = element('board_skin', $board) ? element('board_skin', $board) : $this->cbconfig->item('skin_board');
			$mobile_skin_dir = element('board_mobile_skin', $board) ? element('board_mobile_skin', $board) : $this->cbconfig->item('mobile_skin_board');
			$layoutconfig = array(
				'path' => 'board',
				'layout' => 'layout',
				'skin' => 'post',
				'layout_dir' => $layout_dir,
				'mobile_layout_dir' => $mobile_layout_dir,
				'use_sidebar' => $use_sidebar,
				'use_mobile_sidebar' => $use_mobile_sidebar,
				'skin_dir' => $skin_dir,
				'mobile_skin_dir' => $mobile_skin_dir,
				'page_title' => $page_title,
				'meta_description' => $meta_description,
				'meta_keywords' => $meta_keywords,
				'meta_author' => $meta_author,
				'page_name' => $page_name,
			);
			$view['layout'] = $this->managelayout->front($layoutconfig, $this->cbconfig->get_device_view_type());
			$this->data = $view;
			$this->layout = element('layout_skin_file', element('layout', $view));
			if ($show_list_from_view) {
				$list_skin_file = element('use_gallery_list', $board) ? 'gallerylist' : 'list';
				$listskindir = ($this->cbconfig->get_device_view_type() === 'mobile')
					? $mobile_skin_dir : $skin_dir;
				if (empty($listskindir)) {
					$listskindir
						= ($this->cbconfig->get_device_view_type() === 'mobile')
						? $this->cbconfig->item('mobile_skin_default')
						: $this->cbconfig->item('skin_default');
				}
				$this->view = array(
					element('view_skin_file', element('layout', $view)),
					'board/' . $listskindir . '/' . $list_skin_file,
				);
			} else {
				$this->view = element('view_skin_file', element('layout', $view));
			}
		} else {

			// 이벤트가 존재하면 실행합니다
			$view['view']['event']['before_print_layout'] = Events::trigger('before_print_layout', $eventname);

			$layoutconfig = array(
				'path' => 'helptool',
				'layout' => 'layout_popup',
				'skin' => 'print',
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


	/**
	 * 게시판 목록페이지입니다.
	 */
	public function _get_list($brd_key, $from_view = '')
	{

		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_board_post_get_list';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('list_before', $eventname);

		$return = array();
		$board = $this->_get_board($brd_key);
		$mem_id = (int) $this->member->item('mem_id');

		$alertmessage = $this->member->is_member()
			? '회원님은 이 게시판 목록을 볼 수 있는 권한이 없습니다'
			: '비회원은 이 게시판에 접근할 권한이 없습니다.\\n\\n회원이시라면 로그인 후 이용해 보십시오';

		$check = array(
			'group_id' => element('bgr_id', $board),
			'board_id' => element('brd_id', $board),
		);
		$this->accesslevel->check(
			element('access_list', $board),
			element('access_list_level', $board),
			element('access_list_group', $board),
			$alertmessage,
			$check
		);
		// 본인인증 사용하는 경우 - 시작
		if (element('access_list_selfcert', $board)) {
			$this->load->library(array('selfcertlib'));
			$this->selfcertlib->selfcertcheck('list', element('access_list_selfcert', $board));
		}
		// 본인인증 사용하는 경우 - 끝

		if (element('use_personal', $board) && $this->member->is_member() === false) {
			alert('이 게시판은 1:1 게시판입니다. 비회원은 접근할 수 없습니다');
			return false;
		}
		$skindir = ($this->cbconfig->get_device_view_type() === 'mobile')
			? (element('board_mobile_skin', $board) ? element('board_mobile_skin', $board)
			: element('board_skin', $board)) : element('board_skin', $board);

		$skinurl = base_url( VIEW_DIR . 'board/' . $skindir);

		$view['view']['is_admin'] = $is_admin = $this->member->is_admin(
			array(
				'board_id' => element('brd_id', $board),
				'group_id' => element('bgr_id', $board)
			)
		);

		/**
		 * 페이지에 숫자가 아닌 문자가 입력되거나 1보다 작은 숫자가 입력되면 에러 페이지를 보여줍니다.
		 */
		$param =& $this->querystring;
		$page = (((int) $this->input->get('page')) > 0) ? ((int) $this->input->get('page')) : 1;
		$order_by_field = element('order_by_field', $board)
			? element('order_by_field', $board)
			: 'post_num, post_reply';

		$findex = $this->input->get('findex', null, $order_by_field);
		$sfield = $sfieldchk = $this->input->get('sfield', null, '');
		if ($sfield === 'post_both') {
			$sfield = array('post_title', 'post_content');
		}
		$skeyword = $this->input->get('skeyword', null, '');
		if ($this->cbconfig->get_device_view_type() === 'mobile') {
			$per_page = element('mobile_list_count', $board)
				? (int) element('mobile_list_count', $board) : 10;
		} else {
			$per_page = element('list_count', $board)
				? (int) element('list_count', $board) : 20;
		}
		$offset = ($page - 1) * $per_page;

		$this->Post_model->allow_search_field = array('post_id', 'post_title', 'post_content', 'post_both', 'post_category', 'post_userid', 'post_nickname'); // 검색이 가능한 필드
		$this->Post_model->search_field_equal = array('post_id', 'post_userid', 'post_nickname'); // 검색중 like 가 아닌 = 검색을 하는 필드

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['step1'] = Events::trigger('list_step1', $eventname);

		/**
		 * 상단에 공지사항 부분에 필요한 정보를 가져옵니다.
		 */

		$except_all_notice= false;
		if (element('except_all_notice', $board)
			&& $this->cbconfig->get_device_view_type() !== 'mobile') {
			$except_all_notice = true;
		}
		if (element('mobile_except_all_notice', $board)
			&& $this->cbconfig->get_device_view_type() === 'mobile') {
			$except_all_notice = true;
		}
		$use_subject_style = ($this->cbconfig->get_device_view_type() === 'mobile')
			? element('use_mobile_subject_style', $board)
			: element('use_subject_style', $board);
		$use_sideview = ($this->cbconfig->get_device_view_type() === 'mobile')
			? element('use_mobile_sideview', $board)
			: element('use_sideview', $board);
		$use_sideview_icon = ($this->cbconfig->get_device_view_type() === 'mobile')
			? element('use_mobile_sideview_icon', $board)
			: element('use_sideview_icon', $board);
		$list_date_style = ($this->cbconfig->get_device_view_type() === 'mobile')
			? element('mobile_list_date_style', $board)
			: element('list_date_style', $board);
		$list_date_style_manual = ($this->cbconfig->get_device_view_type() === 'mobile')
			? element('mobile_list_date_style_manual', $board)
			: element('list_date_style_manual', $board);

		if (element('use_gallery_list', $board)) {
			$this->load->model('Post_file_model');

			$board['gallery_cols'] = $gallery_cols
				= ($this->cbconfig->get_device_view_type() === 'mobile')
				? element('mobile_gallery_cols', $board)
				: element('gallery_cols', $board);

			$board['gallery_image_width'] = $gallery_image_width
				= ($this->cbconfig->get_device_view_type() === 'mobile')
				? element('mobile_gallery_image_width', $board)
				: element('gallery_image_width', $board);

			$board['gallery_image_height'] = $gallery_image_height
				= ($this->cbconfig->get_device_view_type() === 'mobile')
				? element('mobile_gallery_image_height', $board)
				: element('gallery_image_height', $board);

			$board['gallery_percent'] = floor( 102 / $board['gallery_cols']) - 2;
		}

		if (element('use_category', $board)) {
			$this->load->model('Board_category_model');
			$board['category'] = $this->Board_category_model
				->get_all_category(element('brd_id', $board));
		}
		
		if (element('use_poll', $board) OR element('use_mobile_poll', $board)) {
			$this->load->model('Post_poll_model');
		}

		$noticeresult = $this->Post_model
			->get_notice_list(element('brd_id', $board), $except_all_notice, $sfield, $skeyword);
		if ($noticeresult) {
			foreach ($noticeresult as $key => $val) {

				$notice_brd_key = $this->board->item_id('brd_key', element('brd_id', $val));
				$noticeresult[$key]['post_url'] = post_url($notice_brd_key, element('post_id', $val));

				$noticeresult[$key]['meta'] = $meta
					= $this->Post_meta_model->get_all_meta(element('post_id', $val));


				if ($this->cbconfig->get_device_view_type() === 'mobile') {
					$noticeresult[$key]['title'] = element('mobile_subject_length', $board)
						? cut_str(element('post_title', $val), element('mobile_subject_length', $board))
						: element('post_title', $val);
				} else {
					$noticeresult[$key]['title'] = element('subject_length', $board)
						? cut_str(element('post_title', $val), element('subject_length', $board))
						: element('post_title', $val);
				}
				if (element('post_del', $val)) {
					$noticeresult[$key]['title'] = '게시물이 삭제 되었습니다';
				}

				if (element('mem_id', $val) >= 0) {
					$noticeresult[$key]['display_name'] = display_username(
						element('post_userid', $val),
						element('post_nickname', $val),
						($use_sideview_icon ? element('mem_icon', $val) : ''),
						($use_sideview ? 'Y' : 'N')
					);
				} else {
					$noticeresult[$key]['display_name'] = '익명사용자';
				}
				$noticeresult[$key]['display_datetime'] = display_datetime(element('post_datetime', $val), $list_date_style, $list_date_style_manual);
				$noticeresult[$key]['category'] = '';
				if (element('use_category', $board) && element('post_category', $val)) {
						$noticeresult[$key]['category']
							= $this->Board_category_model
							->get_category_info(element('brd_id', $val), element('post_category', $val));
				}
				if ($param->output()) {
					$noticeresult[$key]['post_url'] .= '?' . $param->output();
				}
				$noticeresult[$key]['title_color'] = $use_subject_style
					? element('post_title_color', $meta) : '';
				$noticeresult[$key]['title_font'] = $use_subject_style
					? element('post_title_font', $meta) : '';
				$noticeresult[$key]['title_bold'] = $use_subject_style
					? element('post_title_bold', $meta) : '';
				$noticeresult[$key]['is_mobile'] = (element('post_device', $val) === 'mobile') ? true : false;
			}
		}
		/**
		 * 게시판 목록에 필요한 정보를 가져옵니다.
		 */
		$where = array(
			'brd_id' => $this->board->item_key('brd_id', $brd_key),
		);
		$where['post_del <>'] = 2;
		if (element('except_notice', $board)
			&& $this->cbconfig->get_device_view_type() !== 'mobile') {
			$where['post_notice'] = 0;
		}
		if (element('mobile_except_notice', $board)
			&& $this->cbconfig->get_device_view_type() === 'mobile') {
			$where['post_notice'] = 0;
		}
		if (element('use_personal', $board) && $is_admin === false) {
			$where['post.mem_id'] = $mem_id;
		}

		$category_id = (int) $this->input->get('category_id');
		if (empty($category_id) OR $category_id < 1) {
			$category_id = '';
		}
		$result = $this->Post_model
			->get_post_list($per_page, $offset, $where, $category_id, $findex, $sfield, $skeyword);
		$list_num = $result['total_rows'] - ($page - 1) * $per_page;
		if (element('list', $result)) {
			foreach (element('list', $result) as $key => $val) {
				$result['list'][$key]['post_url'] = post_url(element('brd_key', $board), element('post_id', $val));

				$result['list'][$key]['meta'] = $meta
					= $this->Post_meta_model
					->get_all_meta(element('post_id', $val));

				if ($this->cbconfig->get_device_view_type() === 'mobile') {
					$result['list'][$key]['title'] = element('mobile_subject_length', $board)
						? cut_str(element('post_title', $val), element('mobile_subject_length', $board))
						: element('post_title', $val);
				} else {
					$result['list'][$key]['title'] = element('subject_length', $board)
						? cut_str(element('post_title', $val), element('subject_length', $board))
						: element('post_title', $val);
				}
				if (element('post_del', $val)) {
					$result['list'][$key]['title'] = '게시물이 삭제 되었습니다';
				}
				$is_blind = (element('blame_blind_count', $board) > 0 && element('post_blame', $val) >= element('blame_blind_count', $board)) ? true : false;
				if ($is_blind) {
					$result['list'][$key]['title'] = '신고가 접수된 게시글입니다.';
				}

				if (element('mem_id', $val) >= 0) {
					$result['list'][$key]['display_name'] = display_username(
						element('post_userid', $val),
						element('post_nickname', $val),
						($use_sideview_icon ? element('mem_icon', $val) : ''),
						($use_sideview ? 'Y' : 'N')
					);
				} else {
					$result['list'][$key]['display_name'] = '익명사용자';
				}

				$result['list'][$key]['display_datetime'] = display_datetime(
					element('post_datetime', $val),
					$list_date_style,
					$list_date_style_manual
				);
				$result['list'][$key]['category'] = '';
				if (element('use_category', $board) && element('post_category', $val)) {
					$result['list'][$key]['category']
						= $this->Board_category_model
						->get_category_info(element('brd_id', $val), element('post_category', $val));
				}
				$result['list'][$key]['ppo_id'] = '';
				if (element('use_poll', $board) OR element('use_mobile_poll', $board)) {
					$poll_where = array('post_id' => element('post_id', $val));
					$post_poll = $this->Post_poll_model->get_one('', '', $poll_where);
					$result['list'][$key]['ppo_id'] = element('ppo_id', $post_poll);
				}
				if ($param->output()) {
					$result['list'][$key]['post_url'] .= '?' . $param->output();
				}
				$result['list'][$key]['num'] = $list_num--;
				$result['list'][$key]['is_hot'] = false;

				$hot_icon_day = ($this->cbconfig->get_device_view_type() === 'mobile')
					? element('mobile_hot_icon_day', $board)
					: element('hot_icon_day', $board);

				$hot_icon_hit = ($this->cbconfig->get_device_view_type() === 'mobile')
					? element('mobile_hot_icon_hit', $board)
					: element('hot_icon_hit', $board);

				if ($hot_icon_day && ( ctimestamp() - strtotime(element('post_datetime', $val)) <= $hot_icon_day * 86400)) {
					if ($hot_icon_hit && $hot_icon_hit <= element('post_hit', $val)) {
						$result['list'][$key]['is_hot'] = true;
					}
				}
				$result['list'][$key]['is_new'] = false;
				$new_icon_hour = ($this->cbconfig->get_device_view_type() === 'mobile')
					? element('mobile_new_icon_hour', $board)
					: element('new_icon_hour', $board);

				if ($new_icon_hour && ( ctimestamp() - strtotime(element('post_datetime', $val)) <= $new_icon_hour * 3600)) {
					$result['list'][$key]['is_new'] = true;
				}

				$result['list'][$key]['title_color'] = ($use_subject_style && element('post_title_color', $meta)) ? element('post_title_color', $meta) : '';
				$result['list'][$key]['title_font'] = ($use_subject_style && element('post_title_font', $meta)) ? element('post_title_font', $meta) : '';
				$result['list'][$key]['title_bold'] = ($use_subject_style && element('post_title_bold', $meta)) ? element('post_title_bold', $meta) : '';
				$result['list'][$key]['is_mobile'] = (element('post_device', $val) === 'mobile') ? true : false;

				$result['list'][$key]['thumb_url'] = '';
				$result['list'][$key]['origin_image_url'] = '';
				if (element('use_gallery_list', $board)) {
					if (element('post_image', $val)) {
						$filewhere = array(
							'post_id' => element('post_id', $val),
							'pfi_is_image' => 1,
						);
						$file = $this->Post_file_model
							->get_one('', '', $filewhere, '', '', 'pfi_id', 'ASC');
						$result['list'][$key]['thumb_url'] = thumb_url('post', element('pfi_filename', $file), $gallery_image_width, $gallery_image_height);
						$result['list'][$key]['origin_image_url'] = thumb_url('post', element('pfi_filename', $file));
					} else {
						$thumb_url = get_post_image_url(element('post_content', $val), $gallery_image_width, $gallery_image_height);
						$result['list'][$key]['thumb_url'] = $thumb_url
							? $thumb_url
							: thumb_url('', '', $gallery_image_width, $gallery_image_height);

						$result['list'][$key]['origin_image_url'] = $thumb_url;
					}
				}
			}
		}

		$return['data'] = $result;
		$return['notice_list'] = $noticeresult;
		if (empty($from_view)) {
			$board['headercontent'] = ($this->cbconfig->get_device_view_type() === 'mobile')
				? element('mobile_header_content', $board)
				: element('header_content', $board);
		}
		$board['footercontent'] = ($this->cbconfig->get_device_view_type() === 'mobile')
			? element('mobile_footer_content', $board)
			: element('footer_content', $board);

		$board['cat_display_style'] = ($this->cbconfig->get_device_view_type() === 'mobile')
			? element('mobile_category_display_style', $board)
			: element('category_display_style', $board);

		$return['board'] = $board;

		$return['point_info'] = '';
		if ($this->cbconfig->item('use_point')
			&& element('use_point', $board)
			&& element('use_point_info', $board)) {

			$point_info = '';
			if (element('point_write', $board)) {
				$point_info .= '원글작성 : ' . element('point_write', $board) . '<br />';
			}
			if (element('point_comment', $board)) {
				$point_info .= '댓글작성 : ' . element('point_comment', $board) . '<br />';
			}
			if (element('point_fileupload', $board)) {
				$point_info .= '파일업로드 : ' . element('point_fileupload', $board) . '<br />';
			}
			if (element('point_filedownload', $board)) {
				$point_info .= '파일다운로드 : ' . element('point_filedownload', $board) . '<br />';
			}
			if (element('point_filedownload_uploader', $board)) {
				$point_info .= '파일다운로드시업로더에게 : ' . element('point_filedownload_uploader', $board) . '<br />';
			}
			if (element('point_read', $board)) {
				$point_info .= '게시글조회 : ' . element('point_read', $board) . '<br />';
			}
			if (element('point_post_like', $board)) {
				$point_info .= '원글추천함 : ' . element('point_post_like', $board) . '<br />';
			}
			if (element('point_post_dislike', $board)) {
				$point_info .= '원글비추천함 : ' . element('point_post_dislike', $board) . '<br />';
			}
			if (element('point_post_liked', $board)) {
				$point_info .= '원글추천받음 : ' . element('point_post_liked', $board) . '<br />';
			}
			if (element('point_post_disliked', $board)) {
				$point_info .= '원글비추천받음 : ' . element('point_post_disliked', $board) . '<br />';
			}
			if (element('point_comment_like', $board)) {
				$point_info .= '댓글추천함 : ' . element('point_comment_like', $board) . '<br />';
			}
			if (element('point_comment_dislike', $board)) {
				$point_info .= '댓글비추천함 : ' . element('point_comment_dislike', $board) . '<br />';
			}
			if (element('point_comment_liked', $board)) {
				$point_info .= '댓글추천받음 : ' . element('point_comment_liked', $board) . '<br />';
			}
			if (element('point_comment_disliked', $board)) {
				$point_info .= '댓글비추천받음 : ' . element('point_comment_disliked', $board) . '<br />';
			}

			$return['point_info'] = $point_info;
		}

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['step2'] = Events::trigger('list_step2', $eventname);


		/**
		 * primary key 정보를 저장합니다
		 */
		$return['primary_key'] = $this->Post_model->primary_key;

		$highlight_keyword = '';
		if ($skeyword) {
			if ( ! $this->session->userdata('skeyword_' . $skeyword)) {
				$sfieldarray = array(
					'post_title',
					'post_content',
					'post_both',
				);
				if (in_array($sfieldchk, $sfieldarray)) {
					$this->load->model('Search_keyword_model');
					$searchinsert = array(
						'sek_keyword' => $skeyword,
						'sek_datetime' => cdate('Y-m-d H:i:s'),
						'sek_ip' => $this->input->ip_address(),
						'mem_id' => $mem_id,
					);
					$this->Search_keyword_model->insert($searchinsert);
					$this->session->set_userdata(
						'skeyword_' . $skeyword,
						1
					);
				}
			}
			$key_explode = explode(' ', $skeyword);
			if ($key_explode) {
				foreach ($key_explode as $seval) {
					if ($highlight_keyword) {
						$highlight_keyword .= ',';
					}
					$highlight_keyword .= '\'' . html_escape($seval) . '\'';
				}
			}
		}
		$return['highlight_keyword'] = $highlight_keyword;

		/**
		 * 페이지네이션을 생성합니다
		 */
		$config['base_url'] = board_url($brd_key) . '?' . $param->replace('page');
		$config['total_rows'] = $result['total_rows'];
		$config['per_page'] = $per_page;
		if ($this->cbconfig->get_device_view_type() === 'mobile') {
			$config['num_links'] = element('mobile_page_count', $board)
				? element('mobile_page_count', $board) : 3;
		} else {
			$config['num_links'] = element('page_count', $board)
				? element('page_count', $board) : 5;
		}
		$this->pagination->initialize($config);
		$return['paging'] = $this->pagination->create_links();
		$return['page'] = $page;

		/**
		 * 쓰기 주소, 삭제 주소등 필요한 주소를 구합니다
		 */
		$search_option = array(
			'post_title' => '제목',
			'post_content' => '내용'
		);
		$return['search_option'] = search_option($search_option, $sfield);
		if ($skeyword) {
			$return['list_url'] = board_url(element('brd_key', $board));
			$return['search_list_url'] = board_url(element('brd_key', $board) . '?' . $param->output());
		} else {
			$return['list_url'] = board_url(element('brd_key', $board) . '?' . $param->output());
			$return['search_list_url'] = '';
		}

		$check = array(
			'group_id' => element('bgr_id', $board),
			'board_id' => element('brd_id', $board),
		);
		$can_write = $this->accesslevel->is_accessable(
			element('access_write', $board),
			element('access_write_level', $board),
			element('access_write_group', $board),
			$check
		);

		$return['write_url'] = '';
		if ($can_write === true) {
			$return['write_url'] = write_url($brd_key);
		} elseif ($this->cbconfig->get_device_view_type() !== 'mobile' && element('always_show_write_button', $board)) {
			$return['write_url'] = 'javascript:alert(\'비회원은 글쓰기 권한이 없습니다.\\n\\n회원이시라면 로그인 후 이용해 보십시오.\');';
		} elseif ($this->cbconfig->get_device_view_type() === 'mobile' && element('mobile_always_show_write_button', $board)) {
			$return['write_url'] = 'javascript:alert(\'비회원은 글쓰기 권한이 없습니다.\\n\\n회원이시라면 로그인 후 이용해 보십시오.\');';
		}

		$return['list_delete_url'] = site_url('postact/listdelete/' . $brd_key . '?' . $param->output());

		return $return;
	}


	/**
	 * board, board_meta 정보를 얻습니다
	 */
	public function _get_board($brd_key)
	{
		$board_id = $this->board->item_key('brd_id', $brd_key);
		if (empty($board_id)) {
			show_404();
		}
		$board = $this->board->item_all($board_id);
		return $board;
	}


	/**
	 * 방문로그를 남깁니다
	 */
	public function _stat_count_board($brd_id = 0)
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_board_post_stat_count_board';
		$this->load->event($eventname);

		if (empty($brd_id)) {
			return false;
		}

		// 이벤트가 존재하면 실행합니다
		Events::trigger('count_before', $eventname);

		// 방문자 기록
		if ( ! get_cookie('board_id_' . $brd_id)) {
			$cookie_name = 'board_id_' . $brd_id;
			$cookie_value = '1';
			$cookie_expire = 86400; // 1일간 저장
			set_cookie($cookie_name, $cookie_value, $cookie_expire);

			$this->load->model('Stat_count_board_model');
			$this->Stat_count_board_model->add_visit_board($brd_id);

		}
	}
}
