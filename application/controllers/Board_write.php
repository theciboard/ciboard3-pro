<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Board_write class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 게시물 작성, 수정, 답변에 관한 controller 입니다.
 */
class Board_write extends CB_Controller
{

	/**
	 * 모델을 로딩합니다
	 */
	protected $models = array('Post', 'Post_link', 'Post_file', 'Post_extra_vars', 'Post_meta');

	/**
	 * 헬퍼를 로딩합니다
	 */
	protected $helpers = array('form', 'array', 'dhtml_editor');

	function __construct()
	{
		parent::__construct();

		/**
		 * 라이브러리를 로딩합니다
		 */
		$this->load->library(array('querystring', 'accesslevel', 'email', 'notelib', 'point', 'imagelib'));
	}


	/**
	 * 게시물 작성 페이지입니다
	 */
	public function write($brd_key = '')
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_board_write_write';
		$this->load->event($eventname);

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		if (empty($brd_key)) {
			show_404();
		}

		$board_id = $this->board->item_key('brd_id', $brd_key);
		if (empty($board_id)) {
			show_404();
		}
		$board = $this->board->item_all($board_id);

		$board['is_use_captcha'] = false;

		if( check_use_captcha($this->member, $board) ){
			$board['is_use_captcha'] = true;
		}

		$alertmessage = $this->member->is_member()
			? '회원님은 글을 작성할 수 있는 권한이 없습니다'
			: '비회원은 글을 작성할 수 있는 권한이 없습니다.\\n\\n회원이시라면 로그인 후 이용해 보십시오';

		$check = array(
			'group_id' => element('bgr_id', $board),
			'board_id' => element('brd_id', $board),
		);
		$this->accesslevel->check(
			element('access_write', $board),
			element('access_write_level', $board),
			element('access_write_group', $board),
			$alertmessage,
			$check
		);

		// 본인인증 사용하는 경우 - 시작
		if (element('access_write_selfcert', $board)) {
			$this->load->library(array('selfcertlib'));
			$this->selfcertlib->selfcertcheck('write', element('access_write_selfcert', $board));
		}
		// 본인인증 사용하는 경우 - 끝

		// 이벤트가 존재하면 실행합니다
		Events::trigger('after', $eventname);

		$this->_write_common($board);
	}


	/**
	 * 게시물 답변 페이지입니다
	 */
	public function reply($origin_id = 0)
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_board_write_reply';
		$this->load->event($eventname);

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		/**
		 * 프라이머리키에 숫자형이 입력되지 않으면 에러처리합니다
		 */
		$origin_id = (int) $origin_id;
		if (empty($origin_id) OR $origin_id < 1) {
			show_404();
		}

		$origin = $this->Post_model->get_one($origin_id);

		if ( ! element('post_id', $origin)) {
			show_404();
		}

		$board = $this->board->item_all(element('brd_id', $origin));
		if ( ! element('brd_id', $board)) {
			show_404();
		}

		$alertmessage = $this->member->is_member()
			? '회원님은 답변할 수 있는 권한이 없습니다'
			: '비회원은 답변할 수 있는 권한이 없습니다.\\n\\n회원이시라면 로그인 후 이용해 보십시오';

		$check = array(
			'group_id' => element('bgr_id', $board),
			'board_id' => element('brd_id', $board),
		);
		$this->accesslevel->check(
			element('access_reply', $board),
			element('access_reply_level', $board),
			element('access_reply_group', $board),
			$alertmessage,
			$check
		);

		// 본인인증 사용하는 경우 - 시작
		if (element('access_write_selfcert', $board)) {
			$this->load->library(array('selfcertlib'));
			$this->selfcertlib->selfcertcheck('write', element('access_write_selfcert', $board));
		}
		// 본인인증 사용하는 경우 - 끝


		if (element('post_del', $origin)) {
			alert('삭제된 글에는 답변을 입력하실 수 없습니다');
			return;
		}

		if (strlen(element('post_reply', $origin)) >= 10) {
			alert('더 이상 답변하실 수 없습니다.\\n답변은 10단계 까지만 가능합니다');
			return;
		}

		$reply_len = strlen(element('post_reply', $origin)) + 1;
		if (element('reply_order', $board) !== 'desc') {
			$begin_reply_char = 'A';
			$end_reply_char = 'Z';
			$reply_number = +1;
			$this->db->select('MAX(SUBSTRING(post_reply, ' . $reply_len . ', 1)) as reply', false);
			$this->db->where('post_num', element('post_num', $origin));
			$this->db->where('SUBSTRING(post_reply, ' . $reply_len . ', 1) <>', '');
			if (element('post_id', $origin)) {
				$this->db->like('post_reply', element('post_reply', $origin), 'after');
			}
			$result = $this->db->get('post');
			$row = $result->row_array();

		} else {
			$begin_reply_char = 'Z';
			$end_reply_char = 'A';
			$reply_number = -1;
			$this->db->select('MIN(SUBSTRING(post_reply, ' . $reply_len . ', 1)) as reply', false);
			$this->db->where('post_num', element('post_num', $origin));
			$this->db->where('SUBSTRING(post_reply, ' . $reply_len . ', 1) <>', '');
			if (element('post_id', $origin)) {
				$this->db->like('post_reply', element('post_reply', $origin), 'after');
			}
			$result = $this->db->get('post');
			$row = $result->row_array();
		}

		if ( ! element('reply', $row)) {
			$reply_char = $begin_reply_char;
		} elseif (element('reply', $row) === $end_reply_char) { // A~Z은 26 입니다.
			alert('더 이상 답변하실 수 없습니다.\\n답변은 26개 까지만 가능합니다');
		} else {
			$reply_char = chr(ord(element('reply', $row)) + $reply_number);
		}
		$reply = element('post_reply', $origin) . $reply_char;

		// 이벤트가 존재하면 실행합니다
		Events::trigger('after', $eventname);

		$this->_write_common($board, $origin, $reply);
	}


	/**
	 * 게시물 작성과 답변에 공통으로 쓰입니다
	 */
	public function _write_common($board, $origin = '', $reply = '')
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_board_write_write_common';
		$this->load->event($eventname);

		$param =& $this->querystring;

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('common_before', $eventname);

		$view['view']['post'] = array();

		$view['view']['board'] = $board;
		$view['view']['board_key'] = element('brd_key', $board);
		$mem_id = (int) $this->member->item('mem_id');

		$primary_key = $this->Post_model->primary_key;

		$view['view']['is_admin'] = $is_admin = $this->member->is_admin(
			array(
				'board_id' => element('brd_id', $board),
				'group_id' => element('bgr_id', $board),
			)
		);

		// 글 한개만 작성 가능
		if (element('use_only_one_post', $board) && $is_admin === false) {
			if ($this->member->is_member() === false) {
				alert('비회원은 글을 작성할 수 있는 권한이 없습니다. 회원이사라면 로그인 후 이용해주세요');
			}
			$mywhere = array(
				'brd_id' => element('brd_id', $board),
				'mem_id' => $mem_id,
			);
			$cnt = $this->Post_model->count_by($mywhere);
			if ($cnt) {
				alert('이 게시판은 한 사람이 하나의 글만 등록 가능합니다.');
			}
		}

		// 글쓰기 기간제한
		if (element('write_possible_days', $board) && $is_admin === false) {
			if ($this->member->is_member() === false) {
				alert('비회원은 글을 작성할 수 있는 권한이 없습니다. 회원이사라면 로그인 후 이용해주세요');
			}

			if ((ctimestamp() - strtotime($this->member->item('mem_register_datetime'))) < element('write_possible_days', $board) * 86400 ) {
				alert('이 게시판은 회원가입한지 ' . element('write_possible_days', $board) . '일이 지난 회원만 게시물 작성이 가능합니다');
			}
		}

		if ($this->session->userdata('lastest_post_time') && $this->cbconfig->item('new_post_second')) {
			if ($this->session->userdata('lastest_post_time') >= ( ctimestamp() - $this->cbconfig->item('new_post_second')) && $is_admin === false) {
				alert('너무 빠른 시간내에 게시물을 연속해서 올릴 수 없습니다.\\n\\n' . ($this->cbconfig->item('new_post_second') - (ctimestamp() - $this->session->userdata('lastest_post_time'))) . '초 후 글쓰기가 가능합니다');
			}
		}

		if (element('use_point', $board)
			&& $this->cbconfig->item('block_write_zeropoint')
			&& element('point_write', $board) < 0
			&& ($this->member->item('mem_point') + element('point_write', $board)) < 0 ) {
			alert('회원님은 포인트가 부족하므로 글을 작성하실 수 없습니다. 글 작성시 ' . (element('point_write', $board) * -1) . ' 포인트가 차감됩니다');
			return false;
		}

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['step1'] = Events::trigger('common_step1', $eventname);

		$view['view']['post']['is_post_name'] = $is_post_name
			= ($this->member->is_member() === false) ? true : false;
		$view['view']['post']['post_title']
			= ($this->cbconfig->get_device_view_type() === 'mobile')
			? element('mobile_post_default_title', $board)
			: element('post_default_title', $board);
		$view['view']['post']['post_content']
			= ($this->cbconfig->get_device_view_type() === 'mobile')
			? element('mobile_post_default_content', $board)
			: element('post_default_content', $board);
		$view['view']['post']['can_post_notice'] = $can_post_notice = ($is_admin !== false) ? true : false;
		$view['view']['post']['can_post_secret'] = $can_post_secret
			= element('use_post_secret', $board) === '1' ? true : false;
		$view['view']['post']['post_secret'] = element('use_post_secret_selected', $board) ? '1' : '';
		$view['view']['post']['can_post_receive_email'] = $can_post_receive_email
			= element('use_post_receive_email', $board) ? true : false;

		$extravars = element('extravars', $board);
		$form = json_decode($extravars, true);
		$use_subj_style = ($this->cbconfig->get_device_view_type() === 'mobile')
			? element('use_mobile_subject_style', $board)
			: element('use_subject_style', $board);
		$use_poll = ($this->cbconfig->get_device_view_type() === 'mobile')
			? element('use_mobile_poll', $board)
			: element('use_poll', $board);

		/**
		 * Validation 라이브러리를 가져옵니다
		 */
		$this->load->library('form_validation');

		/**
		 * 전송된 데이터의 유효성을 체크합니다
		 */
		$config = array(
			array(
				'field' => 'post_title',
				'label' => '제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'post_content',
				'label' => '내용',
				'rules' => 'trim|required',
			),
		);
		if ($form && is_array($form)) {
			foreach ($form as $key => $value) {
				if ( ! element('use', $value)) {
					continue;
				}
				$required = element('required', $value) ? '|required' : '';
				if (element('field_type', $value) === 'checkbox') {
					$config[] = array(
						'field' => element('field_name', $value) . '[]',
						'label' => element('display_name', $value),
						'rules' => 'trim' . $required,
					);
				} else {
					$config[] = array(
						'field' => element('field_name', $value),
						'label' => element('display_name', $value),
						'rules' => 'trim' . $required,
					);
				}
			}
		}

		if ($is_post_name) {
			$config[] = array(
				'field' => 'post_nickname',
				'label' => '닉네임',
				'rules' => 'trim|required|min_length[2]|max_length[20]|callback__mem_nickname_check',
			);
			$config[] = array(
				'field' => 'post_email',
				'label' => '이메일',
				'rules' => 'trim|required|valid_email|max_length[50]|callback__mem_email_check',
			);
			$config[] = array(
				'field' => 'post_homepage',
				'label' => '홈페이지',
				'rules' => 'prep_url|valid_url',
			);
		}
		if ($this->member->is_member() === false) {
			$password_length = $this->cbconfig->item('password_length');
			$config[] = array(
				'field' => 'post_password',
				'label' => '패스워드',
				'rules' => 'trim|required|min_length[' . $password_length . ']|callback__mem_password_check',
			);
		}

		if ( check_use_captcha($this->member, $board) ) {
			if ($this->cbconfig->item('use_recaptcha')) {
				$config[] = array(
					'field' => 'g-recaptcha-response',
					'label' => '자동등록방지문자',
					'rules' => 'trim|required|callback__check_recaptcha',
				);
			} else {
				$config[] = array(
					'field' => 'captcha_key',
					'label' => '자동등록방지문자',
					'rules' => 'trim|required|callback__check_captcha',
				);
			}
		}
		if ($use_subj_style) {
			$config[] = array(
				'field' => 'post_title_color',
				'label' => '제목색상',
				'rules' => 'trim|exact_length[7]',
			);
			$config[] = array(
				'field' => 'post_title_font',
				'label' => '제목폰트',
				'rules' => 'trim',
			);
			$config[] = array(
				'field' => 'post_title_bold',
				'label' => '제목볼드',
				'rules' => 'trim|exact_length[1]',
			);
		}
		if (element('use_category', $board) && $is_admin === false) {
			$config[] = array(
				'field' => 'post_category',
				'label' => '카테고리',
				'rules' => 'trim|required',
			);
		}
		$this->form_validation->set_rules($config);
		$form_validation = $this->form_validation->run();

		$file_error = '';
		$uploadfiledata = array();

		if (element('use_upload_file', $board)) {
			$check = array(
				'group_id' => element('bgr_id', $board),
				'board_id' => element('brd_id', $board),
			);
			$use_upload = $this->accesslevel->is_accessable(
				element('access_upload', $board),
				element('access_upload_level', $board),
				element('access_upload_group', $board),
				$check
			);
		} else {
			$use_upload = false;
		}
		$view['view']['board']['use_upload'] = $use_upload;
		$view['view']['board']['upload_file_count']
			= ($this->cbconfig->get_device_view_type() === 'mobile')
			? element('mobile_upload_file_num', $board)
			: element('upload_file_num', $board);

		$use_post_dhtml
			= ($this->cbconfig->get_device_view_type() === 'mobile')
			? element('use_mobile_post_dhtml', $board)
			: element('use_post_dhtml', $board);

		if ($use_post_dhtml) {
			$check = array(
				'group_id' => element('bgr_id', $board),
				'board_id' => element('brd_id', $board),
			);
			$use_dhtml = $this->accesslevel->is_accessable(
				element('access_dhtml', $board),
				element('access_dhtml_level', $board),
				element('access_dhtml_group', $board),
				$check
			);
		} else {
			$use_dhtml = false;
		}
		$view['view']['board']['use_dhtml'] = $use_dhtml;
		if ($use_subj_style) {
			$check = array(
				'group_id' => element('bgr_id', $board),
				'board_id' => element('brd_id', $board),
			);
			$use_subject_style = $this->accesslevel->is_accessable(
				element('access_subject_style', $board),
				element('access_subject_style_level', $board),
				element('access_subject_style_group', $board),
				$check
			);
		} else {
			$use_subject_style = false;
		}
		$view['view']['board']['use_subject_style'] = $use_subject_style;
		if ($use_poll) {
			$check = array(
				'group_id' => element('bgr_id', $board),
				'board_id' => element('brd_id', $board),
			);
			$can_poll_write = $this->accesslevel->is_accessable(
				element('access_poll_write', $board),
				element('access_poll_write_level', $board),
				element('access_poll_write_group', $board),
				$check
			);
		} else {
			$can_poll_write = false;
		}
		$view['view']['board']['can_poll_write'] = $can_poll_write;

		if (element('use_post_tag', $board)) {
			$check = array(
				'group_id' => element('bgr_id', $board),
				'board_id' => element('brd_id', $board),
			);
			$can_tag_write = $this->accesslevel->is_accessable(
				element('access_tag_write', $board),
				element('access_tag_write_level', $board),
				element('access_tag_write_group', $board),
				$check
			);
		} else {
			$can_tag_write = false;
		}
		$view['view']['board']['can_tag_write'] = $can_tag_write;

		$view['view']['board']['link_count']
			= ($this->cbconfig->get_device_view_type() === 'mobile')
			? element('mobile_link_num', $board)
			: element('link_num', $board);

		$view['view']['board']['use_emoticon']
			= ($this->cbconfig->get_device_view_type() === 'mobile')
			? element('use_mobile_post_emoticon', $board)
			: element('use_post_emoticon', $board);

		$view['view']['board']['use_specialchars']
			= ($this->cbconfig->get_device_view_type() === 'mobile')
			? element('use_mobile_post_specialchars', $board)
			: element('use_post_specialchars', $board);

		$view['view']['board']['headercontent']
			= ($this->cbconfig->get_device_view_type() === 'mobile')
			? element('mobile_header_content', $board)
			: element('header_content', $board);

		$view['view']['board']['footercontent']
			= ($this->cbconfig->get_device_view_type() === 'mobile')
			? element('mobile_footer_content', $board)
			: element('footer_content', $board);

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['step2'] = Events::trigger('common_step2', $eventname);

		if ($use_upload === true && $form_validation && element('use_upload_file', $board)) {

			$this->load->library('upload');

			if (isset($_FILES) && isset($_FILES['post_file']) && isset($_FILES['post_file']['name']) && is_array($_FILES['post_file']['name'])) {
				$filecount = count($_FILES['post_file']['name']);
				$upload_path = config_item('uploads_dir') . '/post/';
				if (is_dir($upload_path) === false) {
					mkdir($upload_path, 0707);
					$file = $upload_path . 'index.php';
					$f = @fopen($file, 'w');
					@fwrite($f, '');
					@fclose($f);
					@chmod($file, 0644);
				}
				$upload_path .= cdate('Y') . '/';
				if (is_dir($upload_path) === false) {
					mkdir($upload_path, 0707);
					$file = $upload_path . 'index.php';
					$f = @fopen($file, 'w');
					@fwrite($f, '');
					@fclose($f);
					@chmod($file, 0644);
				}
				$upload_path .= cdate('m') . '/';
				if (is_dir($upload_path) === false) {
					mkdir($upload_path, 0707);
					$file = $upload_path . 'index.php';
					$f = @fopen($file, 'w');
					@fwrite($f, '');
					@fclose($f);
					@chmod($file, 0644);
				}

				foreach ($_FILES['post_file']['name'] as $i => $value) {
					if ($value) {
						$uploadconfig = array();
						$uploadconfig['upload_path'] = $upload_path;
						$uploadconfig['allowed_types']
							= element('upload_file_extension', $board)
							? element('upload_file_extension', $board) : '*';
						$uploadconfig['max_size'] = element('upload_file_max_size', $board) * 1024;
						$uploadconfig['encrypt_name'] = true;

						$this->upload->initialize($uploadconfig);
						$_FILES['userfile']['name'] = $_FILES['post_file']['name'][$i];
						$_FILES['userfile']['type'] = $_FILES['post_file']['type'][$i];
						$_FILES['userfile']['tmp_name'] = $_FILES['post_file']['tmp_name'][$i];
						$_FILES['userfile']['error'] = $_FILES['post_file']['error'][$i];
						$_FILES['userfile']['size'] = $_FILES['post_file']['size'][$i];
						if ($this->upload->do_upload()) {
							$filedata = $this->upload->data();

							$uploadfiledata[$i] = array();
							$uploadfiledata[$i]['pfi_filename'] = cdate('Y') . '/' . cdate('m') . '/' . element('file_name', $filedata);
							$uploadfiledata[$i]['pfi_originname'] = element('orig_name', $filedata);
							$uploadfiledata[$i]['pfi_filesize'] = intval(element('file_size', $filedata) * 1024);
							$uploadfiledata[$i]['pfi_width'] = element('image_width', $filedata) ? element('image_width', $filedata) : 0;
							$uploadfiledata[$i]['pfi_height'] = element('image_height', $filedata) ? element('image_height', $filedata) : 0;
							$uploadfiledata[$i]['pfi_type'] = str_replace('.', '', element('file_ext', $filedata));
							$uploadfiledata[$i]['is_image'] = element('is_image', $filedata) ? 1 : 0;
						} else {
							$file_error = $this->upload->display_errors();
							break;
						}
					}
				}
			}
		}


		/**
		 * 유효성 검사를 하지 않는 경우, 또는 유효성 검사에 실패한 경우입니다.
		 * 즉 글쓰기나 수정 페이지를 보고 있는 경우입니다
		 */
		if ($form_validation === false OR $file_error) {

			// 이벤트가 존재하면 실행합니다
			$view['view']['event']['formrunfalse'] = Events::trigger('common_formrunfalse', $eventname);

			if ($file_error) {
				$view['view']['message'] = $file_error;
			}

			/**
			 * primary key 정보를 저장합니다
			 */
			$view['view']['primary_key'] = $primary_key;

			$extra_content = array();

			$k= 0;
			if ($form && is_array($form)) {
				foreach ($form as $key => $value) {
					if ( ! element('use', $value)) {
						continue;
					}
					$required = element('required', $value) ? 'required' : '';

					$extra_content[$k]['field_name'] = element('field_name', $value);
					$extra_content[$k]['display_name'] = element('display_name', $value);
					$extra_content[$k]['input'] = '';

					//field_type : text, url, email, phone, textarea, radio, select, checkbox, date
					if (element('field_type', $value) === 'text'
						OR element('field_type', $value) === 'url'
						OR element('field_type', $value) === 'email'
						OR element('field_type', $value) === 'phone'
						OR element('field_type', $value) === 'date') {

						if (element('field_type', $value) === 'date') {
							$extra_content[$k]['input'] .= '<input type="text" id="' . element('field_name', $value) . '" name="' . element('field_name', $value) . '" class="form-control input datepicker" value="' . set_value(element('field_name', $value)) . '" readonly="readonly" ' . $required . ' />';
						} elseif (element('field_type', $value) === 'phone') {
							$extra_content[$k]['input'] .= '<input type="text" id="' . element('field_name', $value) . '" name="' . element('field_name', $value) . '" class="form-control input validphone" value="' . set_value(element('field_name', $value)) . '" ' . $required . ' />';
						} else {
							$extra_content[$k]['input'] .= '<input type="' . element('field_type', $value) . '" id="' . element('field_name', $value) . '" name="' . element('field_name', $value) . '" class="form-control input" value="' . set_value(element('field_name', $value)) . '" ' . $required . '/>';
						}
					} elseif (element('field_type', $value) === 'textarea') {
							$extra_content[$k]['input'] .= '<textarea id="' . element('field_name', $value) . '" name="' . element('field_name', $value) . '" class="form-control input" ' . $required . '>' . set_value(element('field_name', $value)) . '</textarea>';
					} elseif (element('field_type', $value) === 'radio') {
						$extra_content[$k]['input'] .= '<div class="checkbox">';
						$options = explode("\n", element('options', $value));
						$i =1;
						if ($options) {
							foreach ($options as $okey => $oval) {
								$radiovalue = $oval;
								$extra_content[$k]['input'] .= '<label for="' . element('field_name', $value) . '_' . $i . '"><input type="radio" name="' . element('field_name', $value) . '" id="' . element('field_name', $value) . '_' . $i . '" value="' . $radiovalue . '" ' . set_radio(element('field_name', $value), $radiovalue) . ' /> ' . $oval . ' </label> ';
								$i++;
							}
						}
						$extra_content[$k]['input'] .= '</div>';
					} elseif (element('field_type', $value) === 'checkbox') {
							$extra_content[$k]['input'] .= '<div class="checkbox">';
							$options = explode("\n", element('options', $value));
							$i =1;
							if ($options) {
								foreach ($options as $okey => $oval) {
									$extra_content[$k]['input'] .= '<label for="' . element('field_name', $value) . '_' . $i . '"><input type="checkbox" name="' . element('field_name', $value) . '[]" id="' . element('field_name', $value) . '_' . $i . '" value="' . $oval . '" ' . set_checkbox(element('field_name', $value), $oval) . ' /> ' . $oval . ' </label> ';
									$i++;
								}
							}
							$extra_content[$k]['input'] .= '</div>';
					} elseif (element('field_type', $value) === 'select') {
							$extra_content[$k]['input'] .= '<div class="input-group">';
							$extra_content[$k]['input'] .= '<select name="' . element('field_name', $value) . '" class="form-control input" ' . $required . '>';
							$extra_content[$k]['input'] .= '<option value="" >선택하세요</option> ';
							$options = explode("\n", element('options', $value));
							if ($options) {
								foreach ($options as $okey => $oval) {
									$extra_content[$k]['input'] .= '<option value="' . $oval . '" ' . set_select(element('field_name', $value), $oval) . ' >' . $oval . '</option> ';
								}
							}
							$extra_content[$k]['input'] .= '</select>';
							$extra_content[$k]['input'] .= '</div>';
					}
					$k++;
				}
			}

			$view['view']['extra_content'] = $extra_content;

			if (element('use_category', $board)) {
				$this->load->model('Board_category_model');
				$view['view']['category']
					= $this->Board_category_model
					->get_all_category(element('brd_id', $board));
			}

			$view['view']['has_tempsave'] = false;
			if ($this->member->is_member() && element('use_tempsave', $board)) {
				$this->load->model('Tempsave_model');
				$twhere = array(
					'brd_id' => element('brd_id', $board),
					'mem_id' => $mem_id,
				);
				$tempsave = $this->Tempsave_model
					->get_one('', '', $twhere);

				if (element('tmp_id', $tempsave)) {
					$view['view']['has_tempsave'] = true;
				}
			}

			// 이벤트가 존재하면 실행합니다
			$view['view']['event']['before_layout'] = Events::trigger('common_before_layout', $eventname);

			/**
			 * 레이아웃을 정의합니다
			 */
			$page_title = $this->cbconfig->item('site_meta_title_board_write');
			$meta_description = $this->cbconfig->item('site_meta_description_board_write');
			$meta_keywords = $this->cbconfig->item('site_meta_keywords_board_write');
			$meta_author = $this->cbconfig->item('site_meta_author_board_write');
			$page_name = $this->cbconfig->item('site_page_name_board_write');

			$searchconfig = array(
				'{게시판명}',
				'{게시판아이디}',
			);
			$replaceconfig = array(
				element('board_name', $board),
				element('brd_key', $board),
			);

			$page_title = str_replace($searchconfig, $replaceconfig, $page_title);
			$meta_description = str_replace($searchconfig, $replaceconfig, $meta_description);
			$meta_keywords = str_replace($searchconfig, $replaceconfig, $meta_keywords);
			$meta_author = str_replace($searchconfig, $replaceconfig, $meta_author);
			$page_name = str_replace($searchconfig, $replaceconfig, $page_name);

			$layout_dir = element('board_layout', $board) ? element('board_layout', $board) : $this->cbconfig->item('layout_board');
			$mobile_layout_dir = element('board_mobile_layout', $board) ? element('board_mobile_layout', $board) : $this->cbconfig->item('mobile_layout_board');
			$use_sidebar = element('board_sidebar', $board) ? element('board_sidebar', $board) : $this->cbconfig->item('sidebar_board');
			$use_mobile_sidebar = element('board_mobile_sidebar', $board) ? element('board_mobile_sidebar', $board) : $this->cbconfig->item('mobile_sidebar_board');
			$skin_dir = element('board_skin', $board) ? element('board_skin', $board) : $this->cbconfig->item('skin_board');
			$mobile_skin_dir = element('board_mobile_skin', $board) ? element('board_mobile_skin', $board) : $this->cbconfig->item('mobile_skin_board');
			$layoutconfig = array(
				'path' => 'board',
				'layout' => 'layout',
				'skin' => 'write',
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

		} else {

			/**
			 * 유효성 검사를 통과한 경우입니다.
			 * 즉 데이터의 insert 나 update 의 process 처리가 필요한 상황입니다
			 */

			// 이벤트가 존재하면 실행합니다
			$view['view']['event']['formruntrue'] = Events::trigger('common_formruntrue', $eventname);

			$content_type = $use_dhtml ? 1 : 0;

			if ($origin) {
				$post_num = element('post_num', $origin);
				$post_reply = $reply;
			} else {
				$post_num = $this->Post_model->next_post_num();
				$post_reply = '';
			}
			$metadata = array();

			$post_title = $this->input->post('post_title', null, '');
			$post_content = $this->input->post('post_content', null, '');
			if (element('save_external_image', $board)) {
				$post_content = $this->imagelib->replace_external_image($post_content);
			}

			$updatedata = array(
				'post_num' => $post_num,
				'post_reply' => $post_reply,
				'post_title' => $post_title,
				'post_content' => $post_content,
				'post_html' => $content_type,
				'post_datetime' => cdate('Y-m-d H:i:s'),
				'post_updated_datetime' => cdate('Y-m-d H:i:s'),
				'post_ip' => $this->input->ip_address(),
				'brd_id' => element('brd_id', $board),
			);

			if ($mem_id) {
				if (element('use_anonymous', $board)) {
					$updatedata['mem_id'] = (-1) * $mem_id;
					$updatedata['post_userid'] = '';
					$updatedata['post_username'] = '익명사용자';
					$updatedata['post_nickname'] = '익명사용자';
					$updatedata['post_email'] = '';
					$updatedata['post_homepage'] = '';
				} else {
					$updatedata['mem_id'] = $mem_id;
					$updatedata['post_userid'] = $this->member->item('mem_userid');
					$updatedata['post_username'] = $this->member->item('mem_username');
					$updatedata['post_nickname'] = $this->member->item('mem_nickname');
					$updatedata['post_email'] = $this->member->item('mem_email');
					$updatedata['post_homepage'] = $this->member->item('mem_homepage');
				}
			}

			if ($is_post_name) {
				$updatedata['post_nickname'] = $this->input->post('post_nickname', null, '');
				$updatedata['post_email'] = $this->input->post('post_email', null, '');
				$updatedata['post_homepage'] = $this->input->post('post_homepage', null, '');
			}

			if ($this->member->is_member() === false && $this->input->post('post_password')) {
				if ( ! function_exists('password_hash')) {
					$this->load->helper('password');
				}
				$updatedata['post_password'] = password_hash($this->input->post('post_password'), PASSWORD_BCRYPT);
			}

			if ($can_post_notice) {
				$updatedata['post_notice'] = $this->input->post('post_notice', null, 0);
			}
			if ($can_post_secret) {
				$updatedata['post_secret'] = $this->input->post('post_secret') ? 1 : 0;
			}
			if (element('use_post_secret', $board) === '2') {
				$updatedata['post_secret'] = 1;
			}
			if ($can_post_receive_email) {
				$updatedata['post_receive_email'] = $this->input->post('post_receive_email') ? 1 : 0;
			}
			if ($use_subject_style) {
				$metadata['post_title_color'] = $this->input->post('post_title_color', null, '');
				$metadata['post_title_font'] = $this->input->post('post_title_font', null, '');
				$metadata['post_title_bold'] = $this->input->post('post_title_bold', null, '');
			}
			if (element('use_category', $board)) {
				$updatedata['post_category'] = $this->input->post('post_category', null, '');
			}

			$updatedata['post_device']
				= ($this->cbconfig->get_device_type() === 'mobile') ? 'mobile' : 'desktop';

			$post_id = $this->Post_model->insert($updatedata);

			if ($can_post_secret && $this->input->post('post_secret')) {
				$this->session->set_userdata(
					'view_secret_' . $post_id,
					'1'
				);
			}

			if ($mem_id > 0 && element('use_point', $board)) {
				$point = $this->point->insert_point(
					$mem_id,
					element('point_write', $board),
					element('board_name', $board) . ' ' . $post_id . ' 게시글 작성',
					'post',
					$post_id,
					'게시글 작성'
				);
			}

			$extradata = array();
			if ($form && is_array($form)) {
				foreach ($form as $key => $value) {
					if ( ! element('use', $value)) {
						continue;
					}
					if (element('func', $value) === 'basic') {
						continue;
					}
					$extradata[element('field_name', $value)] = $this->input->post(element('field_name', $value), null, '');
				}
				$this->Post_extra_vars_model
					->save($post_id, element('brd_id', $board), $extradata);
			}

			if ($reply && $origin && $this->cbconfig->item('use_notification') && $this->cbconfig->item('notification_reply')) {
				$this->load->library('notificationlib');
				$not_message = $updatedata['post_nickname'] . '님께서 [' . element('post_title', $origin) . '] 에 답변을 남기셨습니다';
				$not_url = post_url(element('brd_key', $board), $post_id);
				$this->notificationlib->set_noti(
					element('mem_id', $origin),
					$mem_id,
					'reply',
					$post_id,
					$not_message,
					$not_url
				);
			}

			if (isset($metadata) && $metadata) {
				$this->Post_meta_model
					->save($post_id, element('brd_id', $board), $metadata);
			}

			if (element('use_posthistory', $board)) {
				$this->load->model('Post_history_model');
				$historydata = array(
					'post_id' => $post_id,
					'brd_id' => element('brd_id', $board),
					'mem_id' => $mem_id,
					'phi_title' => $post_title,
					'phi_content' => $post_content,
					'phi_content_html_type' => $content_type,
					'phi_ip' => $this->input->ip_address(),
					'phi_datetime' => cdate('Y-m-d H:i:s'),
				);
				$this->Post_history_model->insert($historydata);
			}
			$post_link = $this->input->post('post_link');
			if ($post_link && is_array($post_link) && count($post_link) > 0) {
				foreach ($post_link as $pkey => $pval) {
					if ($pval) {
						$linkupdate = array(
							'post_id' => $post_id,
							'brd_id' => element('brd_id', $board),
							'pln_url' => prep_url($pval),
						);
						$this->Post_link_model->insert($linkupdate);
					}
				}
				$postupdate = array(
					'post_link_count' => count($post_link),
				 );
				$this->Post_model->update($post_id, $postupdate);
			}
			if ($can_poll_write) {
				$this->load->model(array('Post_poll_model', 'Post_poll_item_model', 'Post_poll_item_poll_model'));
				$post_poll_item = $this->input->post('poll_item');
				$has_poll_item = false;
				foreach ($post_poll_item as $pkey => $pval) {
					if ($pval) {
						$has_poll_item = true;
					}
				}
				if ($post_poll_item && $has_poll_item) {
					$start_time = sprintf("%02d", $this->input->post('ppo_start_time'));
					$start_datetime = $this->input->post('ppo_start_date')
						? $this->input->post('ppo_start_date') . ' ' . $start_time . ':00:00' : null;
					$end_time = sprintf("%02d", $this->input->post('ppo_end_time'));
					$end_datetime = $this->input->post('ppo_end_date')
						? $this->input->post('ppo_end_date') . ' ' . $end_time . ':00:00' : null;
					$ppo_choose_count = $this->input->post('ppo_choose_count') ? $this->input->post('ppo_choose_count') : 0;
					$ppo_after_comment = $this->input->post('ppo_after_comment') ? $this->input->post('ppo_after_comment') : 0;
					$polldata = array(
						'post_id' => $post_id,
						'brd_id' => element('brd_id', $board),
						'ppo_start_datetime' => $start_datetime,
						'ppo_end_datetime' => $end_datetime,
						'ppo_title' => $this->input->post('ppo_title', null, ''),
						'ppo_choose_count' => $ppo_choose_count,
						'ppo_after_comment' => $ppo_after_comment,
						'ppo_datetime' => cdate('Y-m-d H:i:s'),
						'ppo_ip' => $this->input->ip_address(),
						'mem_id' => $mem_id,
					);
					if ($is_admin !== false) {
						$polldata['ppo_point'] = $this->input->post('ppo_point') ? $this->input->post('ppo_point') : 0;
					}
					$ppo_id = $this->Post_poll_model->insert($polldata);
					foreach ($post_poll_item as $pkey => $pval) {
						if ($pval) {
							$itemdata = array(
								'ppo_id' => $ppo_id,
								'ppi_item' => $pval,
							);
							$this->Post_poll_item_model->insert($itemdata);
						}
					}
				}
			}

			if ($this->member->is_member() && element('use_tempsave', $board)) {
				$this->load->model('Tempsave_model');
				$tempwhere = array(
					'brd_id' => element('brd_id', $board),
					'mem_id' => $mem_id,
				);
				$this->Tempsave_model->delete_where($tempwhere);
			}

			$file_updated = false;
			if ($use_upload && $uploadfiledata
				&& is_array($uploadfiledata) && count($uploadfiledata) > 0) {
				foreach ($uploadfiledata as $pkey => $pval) {
					if ($pval) {
						$fileupdate = array(
							'post_id' => $post_id,
							'brd_id' => element('brd_id', $board),
							'mem_id' => $mem_id,
							'pfi_originname' => element('pfi_originname', $pval),
							'pfi_filename' => element('pfi_filename', $pval),
							'pfi_filesize' => element('pfi_filesize', $pval),
							'pfi_width' => element('pfi_width', $pval),
							'pfi_height' => element('pfi_height', $pval),
							'pfi_type' => element('pfi_type', $pval),
							'pfi_is_image' => element('is_image', $pval),
							'pfi_datetime' => cdate('Y-m-d H:i:s'),
							'pfi_ip' => $this->input->ip_address(),
						);
						$file_id = $this->Post_file_model->insert($fileupdate);
						if ( ! element('is_image', $pval)) {
							if (element('use_point', $board)) {
								$point = $this->point->insert_point(
									$mem_id,
									element('point_fileupload', $board),
									element('board_name', $board) . ' ' . $post_id . ' 파일 업로드',
									'fileupload',
									$file_id,
									'파일 업로드'
								);
							}
						}
						$file_updated = true;
					}
				}
			}
			$result = $this->Post_file_model->get_post_file_count($post_id);
			$postupdatedata = array();
			if ($result && is_array($result)) {
				foreach ($result as $value) {
					if (element('pfi_is_image', $value)) {
						$postupdatedata['post_image'] = element('cnt', $value);
					} else {
						$postupdatedata['post_file'] = element('cnt', $value);
					}
				}
				$this->Post_model->update($post_id, $postupdatedata);
			}

			if (element('use_post_tag', $board) && $can_tag_write) {
				$this->load->model('Post_tag_model');
				$deletewhere = array(
					'post_id' => $post_id,
				);
				$this->Post_tag_model->delete_where($deletewhere);
				if ($this->input->post('post_tag')) {
					$tags = explode(',', $this->input->post('post_tag'));
					if ($tags && is_array($tags)) {
						foreach ($tags as $key => $value) {
							$value = trim($value);
							if ($value) {
								$tagdata = array(
									'post_id' => $post_id,
									'brd_id' => element('brd_id', $board),
									'pta_tag' => $value,
								);
								$this->Post_tag_model->insert($tagdata);
							}
						}
					}
				}
			}

			$emailsendlistadmin = array();
			$notesendlistadmin = array();
			$smssendlistadmin = array();
			$emailsendlistpostwriter = array();
			$notesendlistpostwriter = array();
			$smssendlistpostwriter = array();

			if (element('send_email_post_super_admin', $board)
				OR element('send_note_post_super_admin', $board)
				OR element('send_sms_post_super_admin', $board)) {
				$mselect = 'mem_id, mem_email, mem_nickname, mem_phone';
				$superadminlist = $this->Member_model->get_superadmin_list($mselect);
			}
			if (element('send_email_post_group_admin', $board)
				OR element('send_note_post_group_admin', $board)
				OR element('send_sms_post_group_admin', $board)) {
				$this->load->model('Board_group_admin_model');
				$groupadminlist = $this->Board_group_admin_model
					->get_board_group_admin_member(element('bgr_id', $board));
			}
			if (element('send_email_post_board_admin', $board)
				OR element('send_note_post_board_admin', $board)
				OR element('send_sms_post_board_admin', $board)) {
				$this->load->model('Board_admin_model');
				$boardadminlist = $this->Board_admin_model
					->get_board_admin_member(element('brd_id', $board));
			}

			if (element('send_email_post_super_admin', $board) && $superadminlist) {
				foreach ($superadminlist as $key => $value) {
					$emailsendlistadmin[$value['mem_id']] = $value;
				}
			}
			if (element('send_email_post_group_admin', $board) && $groupadminlist) {
				foreach ($groupadminlist as $key => $value) {
					$emailsendlistadmin[$value['mem_id']] = $value;
				}
			}
			if (element('send_email_post_board_admin', $board) && $boardadminlist) {
				foreach ($boardadminlist as $key => $value) {
					$emailsendlistadmin[$value['mem_id']] = $value;
				}
			}
			if (element('send_email_post_writer', $board)
				&& $this->member->item('mem_receive_email')) {
				$emailsendlistpostwriter['mem_email'] = $updatedata['post_email'];
			}
			if (element('send_note_post_super_admin', $board) && $superadminlist) {
				foreach ($superadminlist as $key => $value) {
					$notesendlistadmin[$value['mem_id']] = $value;
				}
			}
			if (element('send_note_post_group_admin', $board) && $groupadminlist) {
				foreach ($groupadminlist as $key => $value) {
					$notesendlistadmin[$value['mem_id']] = $value;
				}
			}
			if (element('send_note_post_board_admin', $board) && $boardadminlist) {
				foreach ($boardadminlist as $key => $value) {
					$notesendlistadmin[$value['mem_id']] = $value;
				}
			}
			if (element('send_note_post_writer', $board) && $this->member->item('mem_use_note')) {
				$notesendlistpostwriter['mem_id'] = $mem_id;
			}
			if (element('send_sms_post_super_admin', $board) && $superadminlist) {
				foreach ($superadminlist as $key => $value) {
					$smssendlistadmin[$value['mem_id']] = $value;
				}
			}
			if (element('send_sms_post_group_admin', $board) && $groupadminlist) {
				foreach ($groupadminlist as $key => $value) {
					$smssendlistadmin[$value['mem_id']] = $value;
				}
			}
			if (element('send_sms_post_board_admin', $board) && $boardadminlist) {
				foreach ($boardadminlist as $key => $value) {
					$smssendlistadmin[$value['mem_id']] = $value;
				}
			}
			if (element('send_sms_post_writer', $board)
				&& $this->member->item('mem_phone')
				&& $this->member->item('mem_receive_sms')) {
				$smssendlistpostwriter['mem_id'] = $mem_id;
				$smssendlistpostwriter['mem_nickname'] = $this->member->item('mem_nickname');
				$smssendlistpostwriter['mem_phone'] = $this->member->item('mem_phone');
			}

			$searchconfig = array(
				'{홈페이지명}',
				'{회사명}',
				'{홈페이지주소}',
				'{게시글제목}',
				'{게시글내용}',
				'{게시글작성자닉네임}',
				'{게시글작성자아이디}',
				'{게시글작성시간}',
				'{게시글주소}',
				'{게시판명}',
				'{게시판주소}',
			);
			$autolink = element('use_auto_url', $board) ? true : false;
			$popup = element('content_target_blank', $board) ? true : false;
			$replaceconfig = array(
				$this->cbconfig->item('site_title'),
				$this->cbconfig->item('company_name'),
				site_url(),
				$post_title,
				display_html_content($post_content, $content_type, element('post_image_width', $board), $autolink, $popup),
				$updatedata['post_nickname'],
				$updatedata['post_userid'],
				cdate('Y-m-d H:i:s'),
				post_url(element('brd_key', $board), $post_id),
				element('brd_name', $board),
				board_url(element('brd_key', $board)),
			);
			$replaceconfig_escape = array(
				html_escape($this->cbconfig->item('site_title')),
				html_escape($this->cbconfig->item('company_name')),
				site_url(),
				html_escape($post_title),
				display_html_content($post_content, $content_type, element('post_image_width', $board), $autolink, $popup),
				html_escape($updatedata['post_nickname']),
				$updatedata['post_userid'],
				cdate('Y-m-d H:i:s'),
				post_url(element('brd_key', $board), $post_id),
				html_escape(element('brd_name', $board)),
				board_url(element('brd_key', $board)),
			);
			if ($emailsendlistadmin) {
				$title = str_replace(
					$searchconfig,
					$replaceconfig,
					$this->cbconfig->item('send_email_post_admin_title')
				);
				$content = str_replace(
					$searchconfig,
					$replaceconfig_escape,
					$this->cbconfig->item('send_email_post_admin_content')
				);
				foreach ($emailsendlistadmin as $akey => $aval) {
					$this->email->clear(true);
					$this->email->from($this->cbconfig->item('webmaster_email'), $this->cbconfig->item('webmaster_name'));
					$this->email->to(element('mem_email', $aval));
					$this->email->subject($title);
					$this->email->message($content);
					$this->email->send();
				}
			}
			if ($emailsendlistpostwriter) {
				$title = str_replace(
					$searchconfig,
					$replaceconfig,
					$this->cbconfig->item('send_email_post_writer_title')
				);
				$content = str_replace(
					$searchconfig,
					$replaceconfig_escape,
					$this->cbconfig->item('send_email_post_writer_content')
				);
				$this->email->clear(true);
				$this->email->from($this->cbconfig->item('webmaster_email'), $this->cbconfig->item('webmaster_name'));
				$this->email->to(element('mem_email', $emailsendlistpostwriter));
				$this->email->subject($title);
				$this->email->message($content);
				$this->email->send();
			}
			if ($notesendlistadmin) {
				$title = str_replace(
					$searchconfig,
					$replaceconfig,
					$this->cbconfig->item('send_note_post_admin_title')
				);
				$content = str_replace(
					$searchconfig,
					$replaceconfig_escape,
					$this->cbconfig->item('send_note_post_admin_content')
				);
				foreach ($notesendlistadmin as $akey => $aval) {
					$note_result = $this->notelib->send_note(
						$sender = 0,
						$receiver = element('mem_id', $aval),
						$title,
						$content,
						1
					);
				}
			}
			if ($notesendlistpostwriter && element('mem_id', $notesendlistpostwriter)) {
				$title = str_replace(
					$searchconfig,
					$replaceconfig,
					$this->cbconfig->item('send_note_post_writer_title')
				);
				$content = str_replace(
					$searchconfig,
					$replaceconfig_escape,
					$this->cbconfig->item('send_note_post_writer_content')
				);
				$note_result = $this->notelib->send_note(
					$sender = 0,
					$receiver = element('mem_id', $notesendlistpostwriter),
					$title,
					$content,
					1
				);
			}
			if ($smssendlistadmin) {
				if (file_exists(APPPATH . 'libraries/Smslib.php')) {
					$this->load->library(array('smslib'));
					$content = str_replace(
						$searchconfig,
						$replaceconfig,
						$this->cbconfig->item('send_sms_post_admin_content')
					);
					$sender = array(
						'phone' => $this->cbconfig->item('sms_admin_phone'),
					);
					$receiver = array();
					foreach ($smssendlistadmin as $akey => $aval) {
						$receiver[] = array(
							'mem_id' => element('mem_id', $aval),
							'name' => element('mem_nickname', $aval),
							'phone' => element('mem_phone', $aval),
						);
					}
					$smsresult = $this->smslib->send($receiver, $sender, $content, $date = '', '게시글 작성 알림');
				}
			}
			if ($smssendlistpostwriter) {
				if (file_exists(APPPATH . 'libraries/Smslib.php')) {
					$this->load->library(array('smslib'));
					$content = str_replace(
						$searchconfig,
						$replaceconfig,
						$this->cbconfig->item('send_sms_post_writer_content')
					);
					$sender = array(
						'phone' => $this->cbconfig->item('sms_admin_phone'),
					);
					$receiver = array();
					$receiver[] = $smssendlistpostwriter;
					$smsresult = $this->smslib->send($receiver, $sender, $content, $date = '', '게시글 작성 알림');
				}
			}

			if ( ! element('post_secret', $updatedata)) {
				// 네이버 신디케이션 보내기
				$this->_naver_syndi($post_id, $board, '입력');

				// 네이버 블로그 자동글쓰기
				if ($is_admin !== false && $this->cbconfig->item('use_naver_blog_post') && element('use_naver_blog_post', $board)) {
					$this->load->helper('naver_blog_post');
					naver_blog_post($post_title, display_html_content($post_content, $content_type, element('post_image_width', $board), $autolink, $popup), $board);
				}
			}

			$this->session->set_flashdata(
				'message',
				'게시물이 정상적으로 입력되었습니다'
			);
			$this->session->set_userdata(
				'lastest_post_time',
				ctimestamp()
			);

			// 이벤트가 존재하면 실행합니다
			Events::trigger('common_after', $eventname);

			/**
			 * 게시물의 신규입력 또는 수정작업이 끝난 후 뷰 페이지로 이동합니다
			 */
			$redirecturl = post_url(element('brd_key', $board), $post_id);
			redirect($redirecturl);
		}
	}

	/**
	 * 게시물 수정 페이지입니다
	 */
	public function modify($post_id = 0)
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_board_write_modify';
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

		/**
		 * 수정 페이지일 경우 기존 데이터를 가져옵니다
		 */
		$post = $this->Post_model->get_one($post_id);
		if ( ! element('post_id', $post)) {
			show_404();
		}
		if (element('post_del', $post)) {
			alert('삭제된 글은 수정하실 수 없습니다');
			return false;
		}

		$post['extravars'] = $this->Post_extra_vars_model->get_all_meta($post_id);
		$post['meta'] = $this->Post_meta_model->get_all_meta($post_id);
		$post['post_content'] = html_purifier($post['post_content']);

		$view['view']['post'] = $post;
		$board = $this->board->item_all(element('brd_id', $post));
		if ( ! element('brd_id', $board)) {
			show_404();
		}

		$view['view']['board'] = $board;
		$view['view']['board_key'] = element('brd_key', $board);

		$mem_id = (int) $this->member->item('mem_id');

		$postwhere = array(
			'post_id' => $post_id,
		);
		$view['view']['link'] = $link
			= $this->Post_link_model
			->get('', '', $postwhere, '', '', 'pln_id', 'ASC');
		$view['view']['file'] = $file
			= $this->Post_file_model
			->get('', '', $postwhere, '', '', 'pfi_id', 'ASC');
		if ($file && is_array($file)) {
			foreach ($file as $key => $value) {
				$view['view']['file'][$key]['download_link']
					= site_url('postact/download/' . element('pfi_id', $value));
			}
		}

		$view['view']['is_admin'] = $is_admin = $this->member->is_admin(
			array(
				'board_id' => element('brd_id', $board),
				'group_id' => element('bgr_id', $board),
			)
		);

		if (element('protect_post_day', $board) > 0 && $is_admin === false) {
			if (ctimestamp() - strtotime(element('post_datetime', $post)) >= element('protect_post_day', $board) * 86400) {
				alert('이 게시판은 ' . element('protect_post_day', $board) . '일 이상된 게시글의 수정을 금지합니다');
				return false;
			}
		}
		if (element('protect_comment_num', $board) > 0 && $is_admin === false) {
			if (element('protect_comment_num', $board) <= element('post_comment_count', $post)) {
				alert(element('protect_comment_num', $board) . '개 이상의 댓글이 달린 게시글은 수정할 수 없습니다');
				return false;
			}
		}

		if (element('mem_id', $post)) {
			if ($is_admin === false && $mem_id !== abs(element('mem_id', $post))) {
				alert('회원님은 이 글을 수정할 권한이 없습니다');
				return false;
			}
		} else {
			if ($is_admin !== false) {
				$this->session->set_userdata(
					'can_modify_' . element('post_id', $post),
					'1'
				);
			}
			if ( ! $this->session->userdata('can_modify_' . element('post_id', $post))
				&& $this->input->post('modify_password')) {
				if ( ! function_exists('password_hash')) {
					$this->load->helper('password');
				}
				if ( password_verify($this->input->post('modify_password'), element('post_password', $post))) {
					$this->session->set_userdata(
						'can_modify_' . element('post_id', $post),
						'1'
					);
					redirect(current_url());
				} else {
					$view['view']['message'] = '패스워드가 잘못 입력되었습니다';
				}
			}
			if ( ! $this->session->userdata('can_modify_' . element('post_id', $post))) {

				// 이벤트가 존재하면 실행합니다
				$view['view']['event']['before_password_layout'] = Events::trigger('before_password_layout', $eventname);

				/**
				 * 레이아웃을 정의합니다
				 */
				$view['view']['info'] = '게시글 수정을 위한 패스워드 입력페이지입니다.<br />패스워드를 입력하시면 게시글 수정이 가능합니다';
				$page_title = element('board_name', $board) . ' 글수정';
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

		if (element('use_upload_file', $board)) {
			$check = array(
				'group_id' => element('bgr_id', $board),
				'board_id' => element('brd_id', $board),
			);
			$use_upload = $this->accesslevel->is_accessable(
				element('access_upload', $board),
				element('access_upload_level', $board),
				element('access_upload_group', $board),
				$check
			);
		} else {
			$use_upload = false;
		}
		$view['view']['board']['use_upload'] = $use_upload;
		$view['view']['board']['upload_file_count']
			= ($this->cbconfig->get_device_view_type() === 'mobile')
			? element('mobile_upload_file_num', $board)
			: element('upload_file_num', $board);

		$use_post_dhtml = ($this->cbconfig->get_device_view_type() === 'mobile')
			? element('use_mobile_post_dhtml', $board)
			: element('use_post_dhtml', $board);

		if ($use_post_dhtml) {
			$check = array(
				'group_id' => element('bgr_id', $board),
				'board_id' => element('brd_id', $board),
			);
			$use_dhtml = $this->accesslevel->is_accessable(
				element('access_dhtml', $board),
				element('access_dhtml_level', $board),
				element('access_dhtml_group', $board),
				$check
			);
		} else {
			$use_dhtml = false;
		}
		$view['view']['board']['use_dhtml'] = $use_dhtml;
		$use_subj_style = ($this->cbconfig->get_device_view_type() === 'mobile')
			? element('use_mobile_subject_style', $board)
			: element('use_subject_style', $board);

		$use_poll = ($this->cbconfig->get_device_view_type() === 'mobile')
			? element('use_mobile_poll', $board)
			: element('use_poll', $board);

		if ($use_subj_style) {
			$check = array(
				'group_id' => element('bgr_id', $board),
				'board_id' => element('brd_id', $board),
			);
			$use_subject_style = $this->accesslevel->is_accessable(
				element('access_subject_style', $board),
				element('access_subject_style_level', $board),
				element('access_subject_style_group', $board),
				$check
			);
		} else {
			$use_subject_style = false;
		}
		$view['view']['board']['use_subject_style'] = $use_subject_style;
		if ($use_poll) {
			$this->load->model(array('Post_poll_model', 'Post_poll_item_model', 'Post_poll_item_poll_model'));
			$postwhere = array(
				'post_id' => $post_id,
			);
			$view['view']['poll'] = $poll
				= $this->Post_poll_model->get_one('', '', $postwhere);
			$view['view']['poll_item'] = $poll_item = '';
			if (element('ppo_id', $poll)) {
				$itemwhere = array(
					'ppo_id' => element('ppo_id', $poll),
				);
				$view['view']['poll_item'] = $poll_item
					= $this->Post_poll_item_model->get('', '', $itemwhere, '', '', 'ppi_id', 'ASC');
			}
			$check = array(
				'group_id' => element('bgr_id', $board),
				'board_id' => element('brd_id', $board),
			);
			$can_poll_write = $this->accesslevel->is_accessable(
				element('access_poll_write', $board),
				element('access_poll_write_level', $board),
				element('access_poll_write_group', $board),
				$check
			);
		} else {
			$can_poll_write = false;
		}
		$view['view']['board']['can_poll_write'] = $can_poll_write;

		if (element('use_post_tag', $board)) {
			$check = array(
				'group_id' => element('bgr_id', $board),
				'board_id' => element('brd_id', $board),
			);
			$can_tag_write = $this->accesslevel->is_accessable(
				element('access_tag_write', $board),
				element('access_tag_write_level', $board),
				element('access_tag_write_group', $board),
				$check
			);
		} else {
			$can_tag_write = false;
		}
		$view['view']['board']['can_tag_write'] = $can_tag_write;

		$view['view']['board']['link_count']
			= ($this->cbconfig->get_device_view_type() === 'mobile')
			? element('mobile_link_num', $board)
			: element('link_num', $board);
		$view['view']['board']['use_emoticon']
			= ($this->cbconfig->get_device_view_type() === 'mobile')
			? element('use_mobile_post_emoticon', $board)
			: element('use_post_emoticon', $board);
		$view['view']['board']['use_specialchars']
			= ($this->cbconfig->get_device_view_type() === 'mobile')
			? element('use_mobile_post_specialchars', $board)
			: element('use_post_specialchars', $board);

		$extravars = element('extravars', $board);
		$form = json_decode($extravars, true);

		$view['view']['board']['headercontent']
			= ($this->cbconfig->get_device_view_type() === 'mobile')
			? element('mobile_header_content', $board)
			: element('header_content', $board);
		$view['view']['board']['footercontent']
			= ($this->cbconfig->get_device_view_type() === 'mobile')
			? element('mobile_footer_content', $board)
			: element('footer_content', $board);

		$view['view']['post']['is_post_name'] = $is_post_name
			= ($this->member->is_member() === false OR ($is_admin !== false && $mem_id !== abs(element('mem_id', $post)))) ? true : false;
		$view['view']['post']['can_post_notice'] = $can_post_notice = ($is_admin !== false) ? true : false;
		$view['view']['post']['can_post_secret'] = $can_post_secret
			= element('use_post_secret', $board) === '1' ? true : false;
		$view['view']['post']['can_post_receive_email'] = $can_post_receive_email = element('use_post_receive_email', $board) ? true : false;

		$primary_key = $this->Post_model->primary_key;

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['step1'] = Events::trigger('step1', $eventname);

		/**
		 * Validation 라이브러리를 가져옵니다
		 */
		$this->load->library('form_validation');

		/**
		 * 전송된 데이터의 유효성을 체크합니다
		 */
		$config = array(
			array(
				'field' => 'post_id',
				'label' => 'POSTID',
				'rules' => 'trim|required|numeric',
			),
			array(
				'field' => 'post_title',
				'label' => '제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'post_content',
				'label' => '내용',
				'rules' => 'trim|required',
			),
		);

		if ($form && is_array($form)) {
			foreach ($form as $key => $value) {
				if ( ! element('use', $value)) {
					continue;
				}
				$required = element('required', $value) ? '|required' : '';
				if (element('field_type', $value) === 'checkbox') {
					$config[] = array(
						'field' => element('field_name', $value) . '[]',
						'label' => element('display_name', $value),
						'rules' => 'trim' . $required,
					);
				} else {
					$config[] = array(
						'field' => element('field_name', $value),
						'label' => element('display_name', $value),
						'rules' => 'trim' . $required,
					);
				}
			}
		}

		if ($is_post_name) {
			$config[] = array(
				'field' => 'post_nickname',
				'label' => '닉네임',
				'rules' => 'trim|required|min_length[2]|max_length[20]|callback__mem_nickname_check',
			);
			$config[] = array(
				'field' => 'post_email',
				'label' => '이메일',
				'rules' => 'trim|valid_email|max_length[50]|callback__mem_email_check',
			);
			$config[] = array(
				'field' => 'post_homepage',
				'label' => '홈페이지',
				'rules' => 'prep_url|valid_url',
			);
		}
		if ($this->member->is_member() === false) {
			$password_length = $this->cbconfig->item('password_length');
			$config[] = array(
				'field' => 'post_password',
				'label' => '패스워드',
				'rules' => 'trim|required|min_length[' . $password_length . ']|callback__mem_password_check',
			);
			if ($this->cbconfig->item('use_recaptcha')) {
				$config[] = array(
					'field' => 'g-recaptcha-response',
					'label' => '자동등록방지문자',
					'rules' => 'trim|required|callback__check_recaptcha',
				);
			} else {
				$config[] = array(
					'field' => 'captcha_key',
					'label' => '자동등록방지문자',
					'rules' => 'trim|required|callback__check_captcha',
				);
			}
		}
		if ($use_subject_style) {
			$config[] = array(
				'field' => 'post_title_color',
				'label' => '제목색상',
				'rules' => 'trim|exact_length[7]',
			);
			$config[] = array(
				'field' => 'post_title_font',
				'label' => '제목폰트',
				'rules' => 'trim',
			);
			$config[] = array(
				'field' => 'post_title_bold',
				'label' => '제목볼드',
				'rules' => 'trim|exact_length[1]',
			);
		}
		if (element('use_category', $board) && $is_admin === false) {
			$config[] = array(
				'field' => 'post_category',
				'label' => '카테고리',
				'rules' => 'trim|required',
			);
		}

		$this->form_validation->set_rules($config);
		$form_validation = $this->form_validation->run();
		$file_error = '';
		$uploadfiledata = array();
		$uploadfiledata2 = array();
		if ($use_upload === true && $form_validation && element('use_upload_file', $board)) {
			$this->load->library('upload');
			if (isset($_FILES) && isset($_FILES['post_file']) && isset($_FILES['post_file']['name']) && is_array($_FILES['post_file']['name'])) {
				$filecount = count($_FILES['post_file']['name']);
				$upload_path = config_item('uploads_dir') . '/post/';
				if (is_dir($upload_path) === false) {
					mkdir($upload_path, 0707);
					$file = $upload_path . 'index.php';
					$f = @fopen($file, 'w');
					@fwrite($f, '');
					@fclose($f);
					@chmod($file, 0644);
				}
				$upload_path .= cdate('Y') . '/';
				if (is_dir($upload_path) === false) {
					mkdir($upload_path, 0707);
					$file = $upload_path . 'index.php';
					$f = @fopen($file, 'w');
					@fwrite($f, '');
					@fclose($f);
					@chmod($file, 0644);
				}
				$upload_path .= cdate('m') . '/';
				if (is_dir($upload_path) === false) {
					mkdir($upload_path, 0707);
					$file = $upload_path . 'index.php';
					$f = @fopen($file, 'w');
					@fwrite($f, '');
					@fclose($f);
					@chmod($file, 0644);
				}

				foreach ($_FILES['post_file']['name'] as $i => $value) {
					if ($value) {
						$uploadconfig = array();
						$uploadconfig['upload_path'] = $upload_path;
						$uploadconfig['allowed_types'] = element('upload_file_extension', $board)
							? element('upload_file_extension', $board) : '*';
						$uploadconfig['max_size'] = element('upload_file_max_size', $board) * 1024;
						$uploadconfig['encrypt_name'] = true;

						$this->upload->initialize($uploadconfig);
						$_FILES['userfile']['name'] = $_FILES['post_file']['name'][$i];
						$_FILES['userfile']['type'] = $_FILES['post_file']['type'][$i];
						$_FILES['userfile']['tmp_name'] = $_FILES['post_file']['tmp_name'][$i];
						$_FILES['userfile']['error'] = $_FILES['post_file']['error'][$i];
						$_FILES['userfile']['size'] = $_FILES['post_file']['size'][$i];
						if ($this->upload->do_upload()) {
							$filedata = $this->upload->data();

							$uploadfiledata[$i] = array();
							$uploadfiledata[$i]['pfi_filename'] = cdate('Y') . '/' . cdate('m') . '/' . element('file_name', $filedata);
							$uploadfiledata[$i]['pfi_originname'] = element('orig_name', $filedata);
							$uploadfiledata[$i]['pfi_filesize'] = intval(element('file_size', $filedata) * 1024);
							$uploadfiledata[$i]['pfi_width'] = element('image_width', $filedata) ? element('image_width', $filedata) : 0;
							$uploadfiledata[$i]['pfi_height'] = element('image_height', $filedata) ? element('image_height', $filedata) : 0;
							$uploadfiledata[$i]['pfi_type'] = str_replace('.', '', element('file_ext', $filedata));
							$uploadfiledata[$i]['is_image'] = element('is_image', $filedata) ? element('is_image', $filedata) : 0;
						} else {
							$file_error = $this->upload->display_errors();
							break;
						}
					}
				}
			}
			if (isset($_FILES) && isset($_FILES['post_file_update'])
				&& isset($_FILES['post_file_update']['name'])
				&& is_array($_FILES['post_file_update']['name'])
				&& $file_error === '') {
				$filecount = count($_FILES['post_file_update']['name']);
				$upload_path = config_item('uploads_dir') . '/post/';
				if (is_dir($upload_path) === false) {
					mkdir($upload_path, 0707);
					$file = $upload_path . 'index.php';
					$f = @fopen($file, 'w');
					@fwrite($f, '');
					@fclose($f);
					@chmod($file, 0644);
				}
				$upload_path .= cdate('Y') . '/';
				if (is_dir($upload_path) === false) {
					mkdir($upload_path, 0707);
					$file = $upload_path . 'index.php';
					$f = @fopen($file, 'w');
					@fwrite($f, '');
					@fclose($f);
					@chmod($file, 0644);
				}
				$upload_path .= cdate('m') . '/';
				if (is_dir($upload_path) === false) {
					mkdir($upload_path, 0707);
					$file = $upload_path . 'index.php';
					$f = @fopen($file, 'w');
					@fwrite($f, '');
					@fclose($f);
					@chmod($file, 0644);
				}

				foreach ($_FILES['post_file_update']['name'] as $i => $value) {
					if ($value) {
						$uploadconfig = array();
						$uploadconfig['upload_path'] = $upload_path;
						$uploadconfig['allowed_types'] = element('upload_file_extension', $board)
							? element('upload_file_extension', $board) : '*';
						$uploadconfig['max_size'] = element('upload_file_max_size', $board) * 1024;
						$uploadconfig['encrypt_name'] = true;
						$this->upload->initialize($uploadconfig);
						$_FILES['userfile']['name'] = $_FILES['post_file_update']['name'][$i];
						$_FILES['userfile']['type'] = $_FILES['post_file_update']['type'][$i];
						$_FILES['userfile']['tmp_name'] = $_FILES['post_file_update']['tmp_name'][$i];
						$_FILES['userfile']['error'] = $_FILES['post_file_update']['error'][$i];
						$_FILES['userfile']['size'] = $_FILES['post_file_update']['size'][$i];
						if ($this->upload->do_upload()) {
							$filedata = $this->upload->data();

							$oldpostfile = $this->Post_file_model->get_one($i);
							if ((int) element('post_id', $oldpostfile) !== (int) element('post_id', $post)) {
								alert('잘못된 접근입니다');
							}
							@unlink(config_item('uploads_dir') . '/post/' . element('pfi_filename', $oldpostfile));

							$uploadfiledata2[$i] = array();
							$uploadfiledata2[$i]['pfi_id'] = $i;
							$uploadfiledata2[$i]['pfi_filename'] = cdate('Y') . '/' . cdate('m') . '/' . element('file_name', $filedata);
							$uploadfiledata2[$i]['pfi_originname'] = element('orig_name', $filedata);
							$uploadfiledata2[$i]['pfi_filesize'] = intval(element('file_size', $filedata) * 1024);
							$uploadfiledata2[$i]['pfi_width'] = element('image_width', $filedata)
								? element('image_width', $filedata) : 0;
							$uploadfiledata2[$i]['pfi_height'] = element('image_height', $filedata)
								? element('image_height', $filedata) : 0;
							$uploadfiledata2[$i]['pfi_type'] = str_replace('.', '', element('file_ext', $filedata));
							$uploadfiledata2[$i]['is_image'] = element('is_image', $filedata)
								? element('is_image', $filedata) : 0;
						} else {
							$file_error = $this->upload->display_errors();
							break;
						}
					}
				}
			}
		}


		/**
		 * 유효성 검사를 하지 않는 경우, 또는 유효성 검사에 실패한 경우입니다.
		 * 즉 글쓰기나 수정 페이지를 보고 있는 경우입니다
		 */
		if ($form_validation === false OR $file_error) {

			// 이벤트가 존재하면 실행합니다
			$view['view']['event']['formrunfalse'] = Events::trigger('formrunfalse', $eventname);

			/**
			 * primary key 정보를 저장합니다
			 */
			$view['view']['primary_key'] = $primary_key;

			if ($file_error) {
				$view['view']['message'] = $file_error;
			}

			if (element('use_post_tag', $board) && $can_tag_write) {
				$this->load->model('Post_tag_model');
				$view['view']['post']['post_tag'] = '';
				$postwhere = array(
					'post_id' => $post_id,
				);
				$tag = $this->Post_tag_model->get('', '', $postwhere, '', '', 'pta_id', 'ASC');
				if ($tag && is_array($tag)) {
					foreach ($tag as $key => $value) {
						if (element('pta_tag', $value)) {
							$view['view']['post']['post_tag'] .= trim(element('pta_tag', $value)) . ',';
						}
					}
				}
			}

			$extra_content = '';
			$k = 0;
			if ($form && is_array($form)) {
				foreach ($form as $key => $value) {
					if ( ! element('use', $value)) {
						continue;
					}
					$required = element('required', $value) ? 'required' : '';

					$item = element(element('field_name', $value), element('extravars', $post));
					$extra_content[$k]['field_name'] = element('field_name', $value);
					$extra_content[$k]['display_name'] = element('display_name', $value);
					$extra_content[$k]['input'] = '';

					//field_type : text, url, email, phone, textarea, radio, select, checkbox, date
					if (element('field_type', $value) === 'text'
						OR element('field_type', $value) === 'url'
						OR element('field_type', $value) === 'email'
						OR element('field_type', $value) === 'phone'
						OR element('field_type', $value) === 'date') {
						if (element('field_type', $value) === 'date') {
							$extra_content[$k]['input'] .= '<input type="text" id="' . element('field_name', $value) . '" name="' . element('field_name', $value) . '" class="form-control input datepicker" value="' . set_value(element('field_name', $value), $item) . '" readonly="readonly" ' . $required . ' />';
						} elseif (element('field_type', $value) === 'phone') {
							$extra_content[$k]['input'] .= '<input type="text" id="' . element('field_name', $value) . '" name="' . element('field_name', $value) . '" class="form-control input validphone" value="' . set_value(element('field_name', $value), $item) . '" ' . $required . ' />';
						} else {
							$extra_content[$k]['input'] .= '<input type="' . element('field_type', $value) . '" id="' . element('field_name', $value) . '" name="' . element('field_name', $value) . '" class="form-control input" value="' . set_value(element('field_name', $value), $item) . '" ' . $required . ' />';
						}
					} elseif (element('field_type', $value) === 'textarea') {
						$extra_content[$k]['input'] .= '<textarea id="' . element('field_name', $value) . '" name="' . element('field_name', $value) . '" class="form-control input" ' . $required . ' >' . set_value(element('field_name', $value), $item) . '</textarea>';
					} elseif (element('field_type', $value) === 'radio') {
						$extra_content[$k]['input'] .= '<div class="checkbox">';
						$options = explode("\n", element('options', $value));
						$i =1;
						if ($options) {
							foreach ($options as $okey => $oval) {
								$oval = trim($oval);
								$radiovalue = $oval;
								$extra_content[$k]['input'] .= '<label for="' . element('field_name', $value) . '_' . $i . '"><input type="radio" name="' . element('field_name', $value) . '" id="' . element('field_name', $value) . '_' . $i . '" value="' . $radiovalue . '" ' . set_radio(element('field_name', $value), $radiovalue, ($item === $radiovalue ? true : false)) . ' /> ' . $oval . ' </label> ';
							$i++;
							}
						}
						$extra_content[$k]['input'] .= '</div>';
					} elseif (element('field_type', $value) === 'checkbox') {
						$extra_content[$k]['input'] .= '<div class="checkbox">';
						$options = explode("\n", element('options', $value));
						$item = json_decode($item, true);
						$i =1;
						if ($options) {
							foreach ($options as $okey => $oval) {
								$oval = trim($oval);
								$chkvalue = is_array($item) && in_array($oval, $item) ? $oval : '';
								$extra_content[$k]['input'] .= '<label for="' . element('field_name', $value) . '_' . $i . '"><input type="checkbox" name="' . element('field_name', $value) . '[]" id="' . element('field_name', $value) . '_' . $i . '" value="' . $oval . '" ' . set_checkbox(element('field_name', $value), $oval, ($chkvalue === $oval ? true : false)) . ' /> ' . $oval . ' </label> ';
							$i++;
							}
						}
						$extra_content[$k]['input'] .= '</div>';
					} elseif (element('field_type', $value) === 'select') {
						$extra_content[$k]['input'] .= '<div class="input-group">';
						$extra_content[$k]['input'] .= '<select name="' . element('field_name', $value) . '" class="form-control input" ' . $required . '>';
						$extra_content[$k]['input'] .= '<option value="" >선택하세요</option> ';
						$options = explode("\n", element('options', $value));
						if ($options) {
							foreach ($options as $okey => $oval) {
								$oval = trim($oval);
								$extra_content[$k]['input'] .= '<option value="' . $oval . '" ' . set_select(element('field_name', $value), $oval, ($item === $oval ? true : false)) . ' >' . $oval . '</option> ';
							}
						}
						$extra_content[$k]['input'] .= '</select>';
						$extra_content[$k]['input'] .= '</div>';
					}
					$k++;
				}
			}

			$view['view']['extra_content'] = $extra_content;
			if (element('use_category', $board)) {
				$this->load->model('Board_category_model');
				$view['view']['category'] = $this->Board_category_model
					->get_all_category(element('brd_id', $board));
			}

			// 이벤트가 존재하면 실행합니다
			$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

			/**
			 * 레이아웃을 정의합니다
			 */
			$page_title = $this->cbconfig->item('site_meta_title_board_modify');
			$meta_description = $this->cbconfig->item('site_meta_description_board_modify');
			$meta_keywords = $this->cbconfig->item('site_meta_keywords_board_modify');
			$meta_author = $this->cbconfig->item('site_meta_author_board_modify');
			$page_name = $this->cbconfig->item('site_page_name_board_modify');

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

			$layout_dir = element('board_layout', $board) ? element('board_layout', $board) : $this->cbconfig->item('layout_board');
			$mobile_layout_dir = element('board_mobile_layout', $board) ? element('board_mobile_layout', $board) : $this->cbconfig->item('mobile_layout_board');
			$use_sidebar = element('board_sidebar', $board) ? element('board_sidebar', $board) : $this->cbconfig->item('sidebar_board');
			$use_mobile_sidebar = element('board_mobile_sidebar', $board) ? element('board_mobile_sidebar', $board) : $this->cbconfig->item('mobile_sidebar_board');
			$skin_dir = element('board_skin', $board) ? element('board_skin', $board) : $this->cbconfig->item('skin_board');
			$mobile_skin_dir = element('board_mobile_skin', $board) ? element('board_mobile_skin', $board) : $this->cbconfig->item('mobile_skin_board');
			$layoutconfig = array(
				'path' => 'board',
				'layout' => 'layout',
				'skin' => 'write',
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

		} else {

			/**
			 * 유효성 검사를 통과한 경우입니다.
			 * 즉 데이터의 insert 나 update 의 process 처리가 필요한 상황입니다
			 */

			if( $this->input->post($primary_key) != $post_id ){
				// $_POST['post_id'] 값과 $_GET['post_id'] 값이 틀린 경우입니다.
			}

			// 이벤트가 존재하면 실행합니다
			$view['view']['event']['formruntrue'] = Events::trigger('formruntrue', $eventname);

			$content_type = $use_dhtml ? 1 : 0;

			$post_title = $this->input->post('post_title', null, '');
			$post_content = $this->input->post('post_content', null, '');
			if (element('save_external_image', $board)) {
				$post_content = $this->imagelib->replace_external_image($post_content);
			}

			$metadata = array();
			$updatedata = array(
				'post_title' => $post_title,
				'post_content' => $post_content,
				'post_html' => $content_type,
				'post_updated_datetime' => cdate('Y-m-d H:i:s'),
				'post_update_mem_id' => $mem_id,
			);

			if ($is_post_name) {
				$updatedata['post_nickname'] = $this->input->post('post_nickname', null, '');
				$updatedata['post_email'] = $this->input->post('post_email', null, '');
				$updatedata['post_homepage'] = $this->input->post('post_homepage', null, '');
			}
			if ($this->member->is_member() === false) {
				if ($this->input->post('post_password')) {
					if ( ! function_exists('password_hash')) {
						$this->load->helper('password');
					}
					$updatedata['post_password'] = password_hash($this->input->post('post_password'), PASSWORD_BCRYPT);
				}
			}
			if ($can_post_notice) {
				$updatedata['post_notice'] = $this->input->post('post_notice', null, 0);
			}
			if ($can_post_secret) {
				$updatedata['post_secret'] = $this->input->post('post_secret') ? 1 : 0;
			}
			if (element('use_post_secret', $board) === '2') {
				$updatedata['post_secret'] = 1;
			}
			if ($can_post_receive_email) {
				$updatedata['post_receive_email'] = $this->input->post('post_receive_email') ? 1 : 0;
			}
			if ($use_subject_style) {
				$metadata['post_title_color'] = $this->input->post('post_title_color', null, '');
				$metadata['post_title_font'] = $this->input->post('post_title_font', null, '');
				$metadata['post_title_bold'] = $this->input->post('post_title_bold', null, '');
			}
			if (element('use_category', $board)) {
				$updatedata['post_category'] = $this->input->post('post_category', null, '');
			}

			$extradata = array();
			if ($form && is_array($form)) {
				foreach ($form as $key => $value) {
					if ( ! element('use', $value)) {
						continue;
					}
					if (element('func', $value) === 'basic') {
						continue;
					}
					$extradata[element('field_name', $value)] = $this->input->post(element('field_name', $value), null, '');
				}
				$this->Post_extra_vars_model->save($post_id, element('brd_id', $board), $extradata);
			}

			if (isset($metadata) && $metadata) {
				$this->Post_meta_model
					->save($post_id, element('brd_id', $board), $metadata);
			}

			if (element('use_posthistory', $board)) {
				$this->load->model('Post_history_model');
				$historydata = array(
					'post_id' => $post_id,
					'brd_id' => element('brd_id', $board),
					'mem_id' => $mem_id,
					'phi_title' => $post_title,
					'phi_content' => $post_content,
					'phi_content_html_type' => $content_type,
					'phi_ip' => $this->input->ip_address(),
					'phi_datetime' => cdate('Y-m-d H:i:s'),
				);
				$this->Post_history_model->insert($historydata);
			}
			$post_link_update = $this->input->post('post_link_update');
			$link_count = 0;
			if ($post_link_update && is_array($post_link_update) && count($post_link_update) > 0) {
				foreach ($post_link_update as $pkey => $pval) {
					if ($pval) {
						$linkupdate = array(
							'pln_url' => prep_url($pval),
						);
						$this->Post_link_model->update($pkey, $linkupdate);
						$link_count++;
					} else {
						$this->Post_link_model->delete($pkey);
					}
				}
			}
			$post_link = $this->input->post('post_link');
			if ($post_link && is_array($post_link) && count($post_link) > 0) {
				foreach ($post_link as $pkey => $pval) {
					if ($pval) {
						$linkupdate = array(
							'post_id' => $post_id,
							'brd_id' => element('brd_id', $board),
							'pln_url' => prep_url($pval),
						);
						$this->Post_link_model->insert($linkupdate);
						$link_count++;
					}
				}
			}
			$updatedata['post_link_count'] = $link_count;

			if ($can_poll_write) {
				if ($ppo_id = (int) $this->input->post('ppo_id')) {
					$post_poll_data = $this->Post_poll_model->get_one($ppo_id);
					if (element('post_id', $post_poll_data) == $post_id) {
						$post_poll_item = $this->input->post('poll_item');
						$post_poll_item_update = $this->input->post('poll_item_update');
						if ($post_poll_item OR $post_poll_item_update) {
							$start_time = sprintf("%02d", $this->input->post('ppo_start_time'));
							$start_datetime = $this->input->post('ppo_start_date')
								? $this->input->post('ppo_start_date') . ' ' . $start_time . ':00:00' : null;
							$end_time = sprintf("%02d", $this->input->post('ppo_end_time'));
							$end_datetime = $this->input->post('ppo_end_date')
								? $this->input->post('ppo_end_date') . ' ' . $end_time . ':00:00' : null;
							$ppo_choose_count = $this->input->post('ppo_choose_count') ? $this->input->post('ppo_choose_count') : 0;
							$ppo_after_comment = $this->input->post('ppo_after_comment') ? $this->input->post('ppo_after_comment') : 0;
							$polldata = array(
								'ppo_start_datetime' => $start_datetime,
								'ppo_end_datetime' => $end_datetime,
								'ppo_title' => $this->input->post('ppo_title', null, ''),
								'ppo_choose_count' => $ppo_choose_count,
								'ppo_after_comment' => $ppo_after_comment,
							);
							if ($is_admin !== false) {
								$polldata['ppo_point'] = $this->input->post('ppo_point') ? $this->input->post('ppo_point') : 0;
							}
							$this->Post_poll_model->update(element('ppo_id', $post_poll_data), $polldata);
							if ($post_poll_item_update) {
								foreach ($post_poll_item_update as $pkey => $pval) {
									$item = $this->Post_poll_item_model->get_one($pkey);
									if (element('ppi_id', $item) && element('ppi_id', $item) == $pkey) {
										if (element('ppo_id', $item) == element('ppo_id', $post_poll_data)) {
											if ($pval) {
												$itemdata = array(
													'ppi_item' => $pval,
												);
												$this->Post_poll_item_model->update($pkey, $itemdata);
											} else {
												$this->Post_poll_item_model->delete($pkey);
												$pipwhere = array(
													'ppi_id' => $pkey,
												);
												$this->Post_poll_item_poll_model->delete_where($pipwhere);
											}
										}
									}
								}
							}
							if ($post_poll_item) {
								foreach ($post_poll_item as $pkey => $pval) {
									if ($pval) {
										$itemdata = array(
											'ppo_id' => element('ppo_id', $post_poll_data),
											'ppi_item' => $pval,
										);
										$this->Post_poll_item_model->insert($itemdata);
									}
								}
							}
						} else {
							$pwhere = array(
								'ppo_id' => element('ppo_id', $post_poll_data),
							);
							$this->Post_poll_model->delete(element('ppo_id', $post_poll_data));
							$this->Post_poll_item_model->delete_where($pwhere);
							$this->Post_poll_item_poll_model->delete_where($pwhere);
						}
					}

					$pwhere = array(
						'ppo_id' => $ppo_id,
					);
					$cnt = $this->Post_poll_item_model->count_by($pwhere);
					if ($cnt === 0) {
						$this->Post_poll_model->delete($ppo_id);
						$this->Post_poll_item_model->delete_where($pwhere);
						$this->Post_poll_item_poll_model->delete_where($pwhere);
					}

				} else {
					$post_poll_item = $this->input->post('poll_item');
					$has_poll_item = false;
					foreach ($post_poll_item as $pkey => $pval) {
						if ($pval) {
							$has_poll_item = true;
						}
					}
					if ($post_poll_item && $has_poll_item) {
						$start_time = sprintf("%02d", $this->input->post('ppo_start_time'));
						$start_datetime = $this->input->post('ppo_start_date')
							? $this->input->post('ppo_start_date') . ' ' . $start_time . ':00:00' : '';
						$end_time = sprintf("%02d", $this->input->post('ppo_end_time'));
						$end_datetime = $this->input->post('ppo_end_date')
							? $this->input->post('ppo_end_date') . ' ' . $end_time . ':00:00' : '';
						$ppo_choose_count = $this->input->post('ppo_choose_count') ? $this->input->post('ppo_choose_count') : 0;
						$ppo_after_comment = $this->input->post('ppo_after_comment') ? $this->input->post('ppo_after_comment') : 0;

						$polldata = array(
							'post_id' => $post_id,
							'brd_id' => element('brd_id', $board),
							'ppo_start_datetime' => $start_datetime,
							'ppo_end_datetime' => $end_datetime,
							'ppo_title' => $this->input->post('ppo_title', null, ''),
							'ppo_choose_count' => $ppo_choose_count,
							'ppo_after_comment' => $ppo_after_comment,
							'ppo_datetime' => cdate('Y-m-d H:i:s'),
							'ppo_ip' => $this->input->ip_address(),
							'mem_id' => $mem_id,
						);
						if ($is_admin !== false) {
							$polldata['ppo_point'] = $this->input->post('ppo_point') ? $this->input->post('ppo_point') : 0;
						}
						$ppo_id = $this->Post_poll_model->insert($polldata);
						foreach ($post_poll_item as $pkey => $pval) {
							if ($pval) {
								$itemdata = array(
									'ppo_id' => $ppo_id,
									'ppi_item' => $pval,
								);
								$this->Post_poll_item_model->insert($itemdata);
							}
						}
					}
				}
			}

			$file_updated = false;
			$file_changed = false;
			if ($use_upload
				&& $uploadfiledata
				&& is_array($uploadfiledata)
				&& count($uploadfiledata) > 0) {
				foreach ($uploadfiledata as $pkey => $pval) {
					if ($pval) {
						$fileupdate = array(
							'post_id' => $post_id,
							'brd_id' => element('brd_id', $board),
							'mem_id' => $mem_id,
							'pfi_originname' => element('pfi_originname', $pval),
							'pfi_filename' => element('pfi_filename', $pval),
							'pfi_filesize' => element('pfi_filesize', $pval),
							'pfi_width' => element('pfi_width', $pval),
							'pfi_height' => element('pfi_height', $pval),
							'pfi_type' => element('pfi_type', $pval),
							'pfi_is_image' => element('is_image', $pval),
							'pfi_datetime' => cdate('Y-m-d H:i:s'),
							'pfi_ip' => $this->input->ip_address(),
						);
						$file_id = $this->Post_file_model->insert($fileupdate);
						if ( ! element('is_image', $pval)) {
							if (element('use_point', $board)) {
								$point = $this->point->insert_point(
									$mem_id,
									element('point_fileupload', $board),
									element('board_name', $board) . ' ' . $post_id . ' 파일 업로드',
									'fileupload',
									$file_id,
									'파일 업로드'
								);
							}
						}
						$file_updated = true;
					}
				}
				$file_changed = true;
			}
			if ($use_upload
				&& $uploadfiledata2
				&& is_array($uploadfiledata2)
				&& count($uploadfiledata2) > 0) {
				foreach ($uploadfiledata2 as $pkey => $pval) {
					if ($pval) {
						$fileupdate = array(
							'mem_id' => $mem_id,
							'pfi_originname' => element('pfi_originname', $pval),
							'pfi_filename' => element('pfi_filename', $pval),
							'pfi_filesize' => element('pfi_filesize', $pval),
							'pfi_width' => element('pfi_width', $pval),
							'pfi_height' => element('pfi_height', $pval),
							'pfi_type' => element('pfi_type', $pval),
							'pfi_is_image' => element('is_image', $pval),
							'pfi_datetime' => cdate('Y-m-d H:i:s'),
							'pfi_ip' => $this->input->ip_address(),
						);
						$this->Post_file_model->update($pkey, $fileupdate);
						if ( ! element('is_image', $pval)) {
							if (element('use_point', $board)) {
								$point = $this->point->insert_point(
									$mem_id,
									element('point_fileupload', $board),
									element('board_name', $board) . ' ' . $post_id . ' 파일 업로드',
									'fileupload',
									$pkey,
									'파일 업로드'
								);
							}
						} else {
							$this->point->delete_point(
								$mem_id,
								'fileupload',
								$pkey,
								'파일 업로드'
							);
						}
						$file_changed = true;
					}
				}
			}
			if ($use_upload && $this->input->post('post_file_del')) {
				foreach ($this->input->post('post_file_del') as $key => $val) {
					if ($val === '1' && ! isset($uploadfiledata2[$key])) {
						$oldpostfile = $this->Post_file_model->get_one($key);
						if ( ! element('post_id', $oldpostfile) OR (int) element('post_id', $oldpostfile) !== (int) element('post_id', $post)) {
							alert('잘못된 접근입니다.');
						}
						@unlink(config_item('uploads_dir') . '/post/' . element('pfi_filename', $oldpostfile));
						$this->Post_file_model->delete($key);
						$this->point->delete_point(
							$mem_id,
							'fileupload',
							$key,
							'파일 업로드'
						);
						$file_changed = true;
					}
				}
			}

			$updatedata['post_image'] = 0;
			$updatedata['post_file'] = 0;
			$result = $this->Post_file_model->get_post_file_count($post_id);
			if ($result && is_array($result)) {
				$total_cnt = 0;
				foreach ($result as $value) {
					if (element('pfi_is_image', $value)) {
						$updatedata['post_image'] = element('cnt', $value);
						$total_cnt += element('cnt', $value);
					} else {
						$updatedata['post_file'] = element('cnt', $value);
						$total_cnt += element('cnt', $value);
					}
				}
			}

			if (element('use_post_tag', $board) && $can_tag_write) {
				$this->load->model('Post_tag_model');
				$deletewhere = array(
					'post_id' => $post_id,
				);
				$this->Post_tag_model->delete_where($deletewhere);
				if ($this->input->post('post_tag')) {
					$tags = explode(',', $this->input->post('post_tag'));
					if ($tags && is_array($tags)) {
						foreach ($tags as $key => $value) {
							$value = trim($value);
							if ($value) {
								$tagdata = array(
									'post_id' => $post_id,
									'brd_id' => element('brd_id', $board),
									'pta_tag' => $value,
								);
								$this->Post_tag_model->insert($tagdata);
							}
						}
					}
				}
			}

			// 이벤트가 존재하면 실행합니다
			Events::trigger('before_post_update', $eventname);

			$this->Post_model->update($post_id, $updatedata);

			// 네이버 신디케이션 보내기
			if ( ! element('post_secret', $updatedata)) {
				$this->_naver_syndi($post_id, $board, '수정');
			}

			// 이벤트가 존재하면 실행합니다
			Events::trigger('after', $eventname);


			$this->session->set_flashdata(
				'message',
				'게시물이 정상적으로 수정되었습니다'
			);

			/**
			 * 게시물의 신규입력 또는 수정작업이 끝난 후 뷰 페이지로 이동합니다
			 */
			$param =& $this->querystring;
			$redirecturl = post_url(element('brd_key', $board), $post_id) . '?' . $param->output();

			redirect($redirecturl);
		}
	}


	/**
	 * 게시물 작성시 비회원이 작성한 경우 닉네임체크합니다
	 */
	public function _mem_nickname_check($str)
	{
		$this->load->helper('chkstring');
		if (chkstring($str, _HANGUL_ + _ALPHABETIC_ + _NUMERIC_) === false) {
			$this->form_validation->set_message(
				'_mem_nickname_check',
				'닉네임은 공백없이 한글, 영문, 숫자만 입력 가능합니다'
			);
			return false;
		}

		if (preg_match("/[\,]?{$str}/i", $this->cbconfig->item('denied_nickname_list'))) {
			$this->form_validation->set_message(
				'_mem_nickname_check',
				$str . ' 은(는) 예약어로 사용하실 수 없는 닉네임입니다'
			);
			return false;
		}
		return true;
	}


	/**
	 * 게시물 작성시 비회원이 작성한 경우 이메일체크합니다
	 */
	public function _mem_email_check($str)
	{
		list($emailid, $emaildomain) = explode('@', $str);
		$denied_list = explode(',', $this->cbconfig->item('denied_email_list'));
		$emaildomain = trim($emaildomain);
		$denied_list = array_map('trim', $denied_list);
		if (in_array($emaildomain, $denied_list)) {
			$this->form_validation->set_message(
				'_mem_email_check',
				$emaildomain . ' 은(는) 사용하실 수 없는 이메일입니다'
			);
			return false;
		}
		return true;
	}


	/**
	 * 게시물 작성시 비회원이 작성한 경우 또는 게시판에서 캡챠를 사용시 captcha체크합니다
	 */
	public function _check_captcha($str)
	{
		$captcha = $this->session->userdata('captcha');
		if ( ! is_array($captcha)
			OR ! element('word', $captcha)
			OR strtolower(element('word', $captcha)) !== strtolower($str)) {
			$this->session->unset_userdata('captcha');
			$this->form_validation->set_message(
				'_check_captcha',
				'자동등록방지코드가 잘못되었습니다'
			);
			return false;
		}
		return true;
	}


	/**
	 * 회원가입시 recaptcha 체크하는 함수입니다
	 */
	public function _check_recaptcha($str)
	{
		$url = 'https://www.google.com/recaptcha/api/siteverify';
		$data = array(
			'secret' => $this->cbconfig->item('recaptcha_secret'),
			'response' => $str,
		);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, sizeof($data));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);
		curl_close($ch);

		$obj = json_decode($result);

		if ((string) $obj->success !== '1') {
			$this->form_validation->set_message(
				'_check_recaptcha',
				'자동등록방지코드가 잘못되었습니다'
			);
			return false;
		}

		return true;
	}


	/**
	 * 패스워드가 환경설정에 정한 글자수를 채웠는지를 체크합니다
	 */
	public function _mem_password_check($str)
	{
		$uppercase = $this->cbconfig->item('password_uppercase_length');
		$number = $this->cbconfig->item('password_numbers_length');
		$specialchar = $this->cbconfig->item('password_specialchars_length');

		$this->load->helper('chkstring');
		$str_uc = count_uppercase($str);
		$str_num = count_numbers($str);
		$str_spc = count_specialchars($str);

		if ($str_uc < $uppercase OR $str_num < $number OR $str_spc < $specialchar) {

			$description = '비밀번호는 ';
			if ($str_uc < $uppercase) {
				$description .= ' ' . $uppercase . '개 이상의 대문자';
			}
			if ($str_num < $number) {
				$description .= ' ' . $number . '개 이상의 숫자';
			}
			if ($str_spc < $specialchar) {
				$description .= ' ' . $specialchar . '개 이상의 특수문자';
			}
			$description .= '를 포함해야 합니다';

			$this->form_validation->set_message(
				'_mem_password_check',
				$description
			);
			return false;

		}

		return true;
	}


	/**
	 * 네이버 신디케이션 전송 관련 함수입니다
	 */
	public function _naver_syndi($post_id, $board, $status='')
	{
		if ( ! $this->cbconfig->item('naver_syndi_key')) {
			return;
		}

		// curl library 가 지원되어야 합니다.
		if ( ! function_exists('curl_init')) {
			return;
		}

		// 이 게시판은 신디케이션 기능을 사용하지 않습니다
		if ( ! element('use_naver_syndi', $board)) {
			return;
		}

		// 비회원이 읽기가 가능한 게시판만 신디케이션을 지원합니다
		if (element('access_view', $board)) {
			return;
		}

		// 1:1 게시판은 신디케이션을 지원하지 않습니다
		if (element('use_personal', $board)) {
			return;
		}

		$httpheader = 'Authorization: Bearer ' . $this->cbconfig->item('naver_syndi_key');
		$ping_url = urlencode(site_url('postact/naversyndi/' . $post_id));
		$client_opt = array(
			CURLOPT_URL => 'https://apis.naver.com/crawl/nsyndi/v2',
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => 'ping_url=' . $ping_url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_CONNECTTIMEOUT => 10,
			CURLOPT_TIMEOUT => 10,
			CURLOPT_HTTPHEADER => array('Host: apis.naver.com', 'Pragma: no-cache', 'Accept: */*', $httpheader)
		);

		$ch = curl_init();
		curl_setopt_array($ch, $client_opt);
		$exec = curl_exec($ch);
		curl_close($ch);

		if ($this->cbconfig->item('use_naver_syndi_log')) {
			$this->load->library('simplexml');
			//use the method to parse the data from xml
			$xmlData = $this->simplexml->xml_parse($exec);

			$mem_id = (int) $this->member->item('mem_id');
			$logdata = array(
				'post_id' => $post_id,
				'mem_id' => $mem_id,
				'pns_status' => $status,
				'pns_return_code' => element('error_code', $xmlData, ''),
				'pns_return_message' => element('message', $xmlData, ''),
				'pns_receipt_number' => element('receipt_number', $xmlData, ''),
				'pns_datetime' => cdate('Y-m-d H:i:s'),
			);
			$this->load->model('Post_naver_syndi_log_model');
			$this->Post_naver_syndi_log_model->insert($logdata);
		}
		return $exec;
	}
}
