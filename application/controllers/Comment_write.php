<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Comment_write class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 댓글 작성시 업데이트 할 때 작동하는 controller 입니다.
 */
class Comment_write extends CB_Controller
{

	/**
	 * 모델을 로딩합니다
	 */
	protected $models = array('Post', 'Comment');

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
		$this->load->library(array('querystring', 'accesslevel', 'email', 'notelib', 'point'));
	}


	/**
	 * 댓글 작성시 업데이트하는 함수입니다
	 */
	public function update()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_comment_write_update';
		$this->load->event($eventname);

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		$post_id = (int) $this->input->post('post_id');

		if (empty($post_id) OR $post_id < 1) {
			$result = array('error' => '잘못된 접근입니다');
			exit(json_encode($result));
		}

		$post = $this->Post_model->get_one($post_id);

		if ( ! element('post_id', $post)) {
			$result = array('error' => '잘못된 접근입니다');
			exit(json_encode($result));
		}

		$board = $this->board->item_all(element('brd_id', $post));
		$mem_id = (int) $this->member->item('mem_id');

		$mode = ($this->input->post('mode') === 'cu') ? 'cu' : 'c';

		if ($mode === 'cu') {
			$cmt_id = (int) $this->input->post('cmt_id');
			if (empty($cmt_id) OR $cmt_id < 1) {
				$result = array('error' => '잘못된 접근입니다');
				exit(json_encode($result));
			}
			$comment = $this->Comment_model->get_one($cmt_id);
			if ( ! element('cmt_id', $comment)) {
				$result = array('error' => '잘못된 접근입니다');
				exit(json_encode($result));
			}
			if (element('cmt_del', $comment)) {
				$result = array('error' => '삭제된 글은 수정하실 수 없습니다');
				exit(json_encode($result));
			}
		}

		if (element('notice_comment_block', $board) && element('post_notice', $post)) {
			$result = array('error' => '공지사항 글에는 댓글을 입력하실 수 없습니다.');
			exit(json_encode($result));
		}

		$check = array(
			'group_id' => element('bgr_id', $board),
			'board_id' => element('brd_id', $board),
		);
		$is_accessable = $this->accesslevel->is_accessable(
			element('access_comment', $board),
			element('access_comment_level', $board),
			element('access_comment_group', $board),
			$check
		);
		if ($is_accessable === false) {
			$alertmessage = $this->member->is_member()
				? '회원님은 댓글을 작성할 수 있는 권한이 없습니다'
				: '비회원은 댓글을 작성할 수 있는 권한이 없습니다.<br>회원이시라면 로그인 후 이용해 보십시오';
			$result = array('error' => $alertmessage);
			exit(json_encode($result));
		}


		// 본인인증 사용하는 경우 - 시작
		if (element('access_comment_selfcert', $board))
		{
			$this->load->library('selfcertlib');
			$is_selfcert = $this->selfcertlib->is_selfcert('comment', element('access_comment_selfcert', $board));
			if ($is_selfcert === false) {
				$alertmessage = $this->member->is_member()
					? '회원님은 댓글을 작성할 수 있는 권한이 없습니다<br />본인인증 후에 댓글 작성이 가능합니다.'
					: '비회원은 댓글을 작성할 수 있는 권한이 없습니다.<br>회원이시라면 로그인 후 이용해 보십시오';
				$result = array('error' => $alertmessage);
				exit(json_encode($result));
			}
		}
		// 본인인증 사용하는 경우 - 끝

		$is_admin = $this->member->is_admin(
			array(
				'board_id' => element('brd_id', $board),
				'group_id' => element('bgr_id', $board),
			)
		);

		$origin = '';
		$reply = '';
		if ($this->input->post('cmt_id') && $mode === 'c') {
			$parent_id = (int) $this->input->post('cmt_id');
			if (empty($parent_id) OR $parent_id < 1) {
				$result = array('error' => '잘못된 접근입니다');
				exit(json_encode($result));
			}
			$origin = $this->Comment_model->get_one($parent_id);
			if ( ! element('cmt_id', $origin)) {
				$result = array('error' => '잘못된 접근입니다');
				exit(json_encode($result));
			}
			if (element('cmt_del', $origin)) {
				$result = array('error' => '삭제된 글에는 답변을 입력하실 수 없습니다');
				exit(json_encode($result));
			}
			if (strlen(element('cmt_reply', $origin)) >= 5) {
				$result = array('error' => '더 이상 답변하실 수 없습니다.\\n답변은 5단계 까지만 가능합니다');
				exit(json_encode($result));
			}

			$reply_len = strlen(element('cmt_reply', $origin)) + 1;
			$begin_reply_char = 'A';
			$end_reply_char = 'Z';
			$reply_number = +1;
			$this->db->select('MAX(SUBSTRING(cmt_reply, ' . $reply_len . ', 1)) as reply', false);
			$this->db->where('cmt_num', element('cmt_num', $origin));
			$this->db->where('SUBSTRING(cmt_reply, ' . $reply_len . ', 1) <>', '');
			if (element('cmt_id', $origin)) {
				$this->db->like('cmt_reply', element('cmt_reply', $origin), 'after');
			}
			$result = $this->db->get('comment');
			$row = $result->row_array();

			if ( ! element('reply', $row)) {
				$reply_char = $begin_reply_char;
			} elseif (element('reply', $row) === $end_reply_char) { // A~Z은 26 입니다.
				$result = array('error' => '더 이상 답변하실 수 없습니다.\\n답변은 26개 까지만 가능합니다');
				exit(json_encode($result));
			} else {
				$reply_char = chr(ord(element('reply', $row)) + $reply_number);
			}
			$reply = element('cmt_reply', $origin) . $reply_char;
		}

		if ($mode === 'cu') {
			if (element('protect_comment_day', $board) > 0 && $is_admin === false) {
				if (ctimestamp() - strtotime(element('cmt_datetime', $comment)) >= element('protect_comment_day', $board) * 86400) {
					$result = array('error' => '이 게시판은 ' . element('protect_comment_day', $board) . '일 이상된 댓글의 수정을 금지합니다');
					exit(json_encode($result));
				}
			}

			if ( ! $mem_id) {
				$result = array('error' => '비회원은 수정 권한이 없습니다');
				exit(json_encode($result));
			}
			if ( ! element('mem_id', $comment) && $is_admin === false) {
				$result = array('error' => '비회원이 작성하신 글은 수정할 수 없습니다');
				exit(json_encode($result));
			}
			if (element('mem_id', $comment)
				&& abs(element('mem_id', $comment)) !== $mem_id
				&& $is_admin === false) {
				$result = array('error' => '다른 회원님의 댓글은 수정할 수 없습니다');
				exit(json_encode($result));
			}
		}

		if ($mode === 'c'
			&& $this->session->userdata('lastest_post_time')
			&& $this->cbconfig->item('new_post_second')) {
			if ($this->session->userdata('lastest_post_time') >= ( ctimestamp() - $this->cbconfig->item('new_post_second')) && $is_admin === false) {
				$result = array('error' => '너무 빠른 시간내에 게시물을 연속해서 올릴 수 없습니다.<br />'
					. ($this->cbconfig->item('new_post_second') - (ctimestamp() - $this->session->userdata('lastest_post_time'))) . '초 후 글쓰기가 가능합니다');
				exit(json_encode($result));
			}
		}
		if (element('comment_possible_day', $board) > 0 && $is_admin === false
			&& $mode === 'c' && ! $this->input->post('cmt_id')) {
			if (ctimestamp() - strtotime(element('post_datetime', $post)) >= element('comment_possible_day', $board) * 86400) {
				$result = array('error' => '이 게시판은 ' . element('comment_possible_day', $board) . '일 이상된 게시글에 댓글 입력을 금지합니다');
				exit(json_encode($result));
			}
		}

		$is_comment_name = ($this->member->is_member() === false) ? true : false;
		$can_comment_secret = (element('use_comment_secret', $board) === '1' && $this->member->is_member()) ? true : false;

		/**
		 * Validation 라이브러리를 가져옵니다
		 */
		$this->load->library('form_validation');

		/**
		 * 전송된 데이터의 유효성을 체크합니다
		 */
		$config = array(
			array(
				'field' => 'cmt_content',
				'label' => '내용',
				'rules' => 'trim|required',
			),
		);
		if ($is_comment_name) {
			$password_length = $this->cbconfig->item('password_length');
			$config[] = array(
				'field' => 'cmt_nickname',
				'label' => '닉네임',
				'rules' => 'trim|required|min_length[2]|max_length[20]|callback__mem_nickname_check',
			);
			$config[] = array(
				'field' => 'cmt_password',
				'label' => '패스워드',
				'rules' => 'trim|required|min_length[' . $password_length . ']|callback__mem_password_check',
			);
		}
		if ($this->member->is_member() === false) {
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
		$this->form_validation->set_rules($config);
		$form_validation = $this->form_validation->run();


		/**
		 * 유효성 검사를 하지 않는 경우, 또는 유효성 검사에 실패한 경우입니다.
		 * 즉 글쓰기나 수정 페이지를 보고 있는 경우입니다
		 */
		if ($form_validation === false) {

			// 이벤트가 존재하면 실행합니다
			Events::trigger('formrunfalse', $eventname);

			$result = array('error' => validation_errors('<div class="alert alert-warning" role="alert">', '</div>'));
			exit(json_encode($result));

		} else {
			/**
			 * 유효성 검사를 통과한 경우입니다.
			 * 즉 데이터의 insert 나 update 의 process 처리가 필요한 상황입니다
			 */

			// 이벤트가 존재하면 실행합니다
			Events::trigger('formruntrue', $eventname);

			$content_type = 0;
			$cmt_content
				= ($this->input->post('cmt_content') === '<p></p>' OR $this->input->post('cmt_content') === '<p>&nbsp;</p>')
				? '' : $this->input->post('cmt_content');

			if ($mode === 'c') {
				if ($origin) {
					$cmt_num = element('cmt_num', $origin);
					$cmt_reply = $reply;
				} else {
					$cmt_num = $this->Comment_model->next_comment_num();
					$cmt_reply = '';
				}
				$updatedata = array(
					'cmt_num' => $cmt_num,
					'cmt_reply' => $cmt_reply,
					'cmt_content' => $cmt_content,
					'cmt_html' => $content_type,
					'cmt_datetime' => cdate('Y-m-d H:i:s'),
					'cmt_updated_datetime' => cdate('Y-m-d H:i:s'),
					'cmt_ip' => $this->input->ip_address(),
					'post_id' => element('post_id', $post),
					'brd_id' => element('brd_id', $post),
				);

				if ($mem_id) {
					if (element('use_anonymous', $board)) {
						$updatedata['mem_id'] = (-1) * $mem_id;
						$updatedata['cmt_userid'] = '';
						$updatedata['cmt_username'] = '익명사용자';
						$updatedata['cmt_nickname'] = '익명사용자';
						$updatedata['cmt_email'] = '';
						$updatedata['cmt_homepage'] = '';
					} else {
						$updatedata['mem_id'] = $mem_id;
						$updatedata['cmt_userid'] = $this->member->item('mem_userid');
						$updatedata['cmt_username'] = $this->member->item('mem_username');
						$updatedata['cmt_nickname'] = $this->member->item('mem_nickname');
						$updatedata['cmt_email'] = $this->member->item('mem_email');
						$updatedata['cmt_homepage'] = $this->member->item('mem_homepage');
					}
				}

				if ($is_comment_name) {
					if ( ! function_exists('password_hash')) {
						$this->load->helper('password');
					}
					$updatedata['cmt_nickname'] = $this->input->post('cmt_nickname', null, '');
					$updatedata['cmt_password'] = password_hash($this->input->post('cmt_password', null, ''), PASSWORD_BCRYPT);
				}

				if ($can_comment_secret) {
					$updatedata['cmt_secret'] = $this->input->post('cmt_secret') ? 1 : 0;
				}
				if ($this->member->is_member() && element('use_comment_secret', $board) === '2') {
					$updatedata['cmt_secret'] = 1;
				}

				$updatedata['cmt_device'] = ($this->cbconfig->get_device_type() === 'mobile')
					? 'mobile' : 'desktop';
				$cmt_id = $this->Comment_model->insert($updatedata);
				$this->Post_model->comment_updated($post_id, cdate('Y-m-d H:i:s'));

				if ($this->cbconfig->item('use_notification')
					&& $this->cbconfig->item('notification_comment')) {
					$this->load->library('notificationlib');
					$not_message = $updatedata['cmt_nickname'] . '님께서 [' . element('post_title', $post) . '] 에 댓글을 남기셨습니다';
					$not_url = post_url(element('brd_key', $board), $post_id) . '#comment_' . $cmt_id;
					$this->notificationlib->set_noti(
						abs(element('mem_id', $post)),
						$mem_id,
						'comment',
						$cmt_id,
						$not_message,
						$not_url
					);
				}
				if ($origin
					&& $cmt_reply
					&& $this->cbconfig->item('use_notification')
					&& $this->cbconfig->item('notification_comment_comment')
					&& abs(element('mem_id', $post)) !== abs(element('mem_id', $origin))) {
					$this->load->library('notificationlib');
					$not_message = $updatedata['cmt_nickname'] . '님께서 [' . element('post_title', $post) . '] 글의 회원님의 댓글에 답변댓글을 남기셨습니다';
					$not_url = post_url(element('brd_key', $board), $post_id) . '#comment_' . $cmt_id;
					$this->notificationlib->set_noti(
						abs(element('mem_id', $origin)),
						$mem_id,
						'comment_comment',
						$cmt_id,
						$not_message,
						$not_url
					);
				}

				if (element('use_point', $board)) {
					$point = $this->point->insert_point(
						$mem_id,
						element('point_comment', $board),
						element('board_name', $board) . ' ' . $cmt_id . ' 댓글 작성',
						'comment',
						$cmt_id,
						'댓글 작성'
					);
				}
				if (element('use_comment_lucky', $board)) {
					$rand = rand(1,100);
					if (element('comment_lucky_percent', $board) >= $rand) {
						$luckypoint = rand(element('comment_lucky_point_min', $board), element('comment_lucky_point_max', $board));
						$luckytitle = element('board_name', $board) . ' ' . $cmt_id . ' ' . element('comment_lucky_name', $board) . ' 당첨';
						$point = $this->point->insert_point(
							$mem_id,
							$luckypoint,
							$luckytitle,
							'lucky-comment',
							$cmt_id,
							'럭키포인트'
						);
						$metadata = array(
							'comment-lucky' => $luckypoint,
						);
						$this->load->model('Comment_meta_model');
						$this->Comment_meta_model->save($cmt_id, $metadata);
					}
				}

				$emailsendlistadmin = array();
				$notesendlistadmin = array();
				$smssendlistadmin = array();
				$emailsendlistpostwriter = array();
				$notesendlistpostwriter = array();
				$smssendlistpostwriter = array();
				$emailsendlistcmtwriter = array();
				$notesendlistcmtwriter = array();
				$smssendlistcmtwriter = array();
				$post_writer = array();

				if (element('send_email_comment_super_admin', $board)
					OR element('send_note_comment_super_admin', $board)
					OR element('send_sms_comment_super_admin', $board)) {
					$mselect = 'mem_id, mem_email, mem_nickname, mem_phone';
					$superadminlist = $this->Member_model->get_superadmin_list($mselect);
				}
				if (element('send_email_comment_group_admin', $board)
					OR element('send_note_comment_group_admin', $board)
					OR element('send_sms_comment_group_admin', $board)) {
					$this->load->model('Board_group_admin_model');
					$groupadminlist = $this->Board_group_admin_model
						->get_board_group_admin_member(element('bgr_id', $board));
				}
				if (element('send_email_comment_board_admin', $board)
					OR element('send_note_comment_board_admin', $board)
					OR element('send_sms_comment_board_admin', $board)) {
					$this->load->model('Board_admin_model');
					$boardadminlist = $this->Board_admin_model
						->get_board_admin_member(element('brd_id', $board));
				}
				if (element('send_email_comment_super_admin', $board) && $superadminlist) {
					foreach ($superadminlist as $key => $value) {
						$emailsendlistadmin[$value['mem_id']] = $value;
					}
				}
				if (element('send_email_comment_group_admin', $board) && $groupadminlist) {
					foreach ($groupadminlist as $key => $value) {
						$emailsendlistadmin[$value['mem_id']] = $value;
					}
				}
				if (element('send_email_comment_board_admin', $board) && $boardadminlist) {
					foreach ($boardadminlist as $key => $value) {
						$emailsendlistadmin[$value['mem_id']] = $value;
					}
				}
				if (element('send_email_comment_post_writer', $board)
					OR element('send_note_comment_post_writer', $board)
					OR element('send_sms_comment_post_writer', $board)
					OR element('post_receive_email', $post)) {
					$post_writer = $this->Member_model->get_one(element('mem_id', $post));
				}
				if ((element('mem_email', $post_writer) && element('post_receive_email', $post))
					OR (element('send_email_comment_post_writer', $board) && element('mem_receive_email', $post_writer))) {
					$emailsendlistpostwriter['mem_email'] = $post['post_email'];
				}
				if (element('send_email_comment_comment_writer', $board)) {
					$emailsendlistcmtwriter['mem_email'] = $this->member->item('mem_email');
				}
				if (element('send_note_comment_super_admin', $board) && $superadminlist) {
					foreach ($superadminlist as $key => $value) {
						$notesendlistadmin[$value['mem_id']] = $value;
					}
				}
				if (element('send_note_comment_group_admin', $board) && $groupadminlist) {
					foreach ($groupadminlist as $key => $value) {
						$notesendlistadmin[$value['mem_id']] = $value;
					}
				}
				if (element('send_note_comment_board_admin', $board) && $boardadminlist) {
					foreach ($boardadminlist as $key => $value) {
						$notesendlistadmin[$value['mem_id']] = $value;
					}
				}
				if (element('send_note_comment_post_writer', $board)
					&& element('mem_use_note', $post_writer)) {
					$notesendlistpostwriter['mem_id'] = element('mem_id', $post_writer);
				}
				if (element('send_note_comment_comment_writer', $board)
					&& $this->member->item('mem_use_note')) {
					$notesendlistcmtwriter['mem_id'] = $mem_id;
				}
				if (element('send_sms_comment_super_admin', $board) && $superadminlist) {
					foreach ($superadminlist as $key => $value) {
						$smssendlistadmin[$value['mem_id']] = $value;
					}
				}
				if (element('send_sms_comment_group_admin', $board) && $groupadminlist) {
					foreach ($groupadminlist as $key => $value) {
						$smssendlistadmin[$value['mem_id']] = $value;
					}
				}
				if (element('send_sms_comment_board_admin', $board) && $boardadminlist) {
					foreach ($boardadminlist as $key => $value) {
						$smssendlistadmin[$value['mem_id']] = $value;
					}
				}
				if (element('send_sms_comment_post_writer', $board)
					&& element('mem_phone', $post_writer)
					&& element('mem_receive_sms', $post_writer)) {
					$smssendlistpostwriter['mem_id'] = element('mem_id', $post_writer);
					$smssendlistpostwriter['mem_nickname'] = element('mem_nickname', $post_writer);
					$smssendlistpostwriter['mem_phone'] = element('mem_phone', $post_writer);
				}
				if (element('send_sms_comment_comment_writer', $board)
					&& $this->member->item('mem_phone')
					&& $this->member->item('mem_receive_sms')) {
					$smssendlistcmtwriter['mem_id'] = $mem_id;
					$smssendlistcmtwriter['mem_nickname'] = $this->member->item('mem_nickname');
					$smssendlistcmtwriter['mem_phone'] = $this->member->item('mem_phone');
				}

				$searchconfig = array(
					'{홈페이지명}',
					'{회사명}',
					'{홈페이지주소}',
					'{댓글내용}',
					'{댓글작성자닉네임}',
					'{댓글작성자아이디}',
					'{댓글작성시간}',
					'{댓글주소}',
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
					display_html_content($cmt_content, 0),
					$updatedata['cmt_nickname'],
					$this->member->item('mem_userid'),
					cdate('Y-m-d H:i:s'),
					post_url(element('brd_key', $board), element('post_id', $post)) . '#comment_' . $cmt_id,
					element('post_title', $post),
					display_html_content(element('post_content', $post), element('post_html', $post), element('post_image_width', $board), $autolink, $popup),
					element('post_nickname', $post),
					element('post_userid', $post),
					element('post_datetime', $post),
					post_url(element('brd_key', $board), element('post_id', $post)),
					element('brd_name', $board),
					board_url(element('brd_key', $board)),
				);
				$replaceconfig_escape = array(
					html_escape($this->cbconfig->item('site_title')),
					html_escape($this->cbconfig->item('company_name')),
					site_url(),
					display_html_content($cmt_content, 0),
					html_escape($updatedata['cmt_nickname']),
					$this->member->item('mem_userid'),
					cdate('Y-m-d H:i:s'),
					post_url(element('brd_key', $board), element('post_id', $post)) . '#comment_' . $cmt_id,
					html_escape(element('post_title', $post)),
					display_html_content(element('post_content', $post), element('post_html', $post), element('post_image_width', $board), $autolink, $popup),
					html_escape(element('post_nickname', $post)),
					element('post_userid', $post),
					element('post_datetime', $post),
					post_url(element('brd_key', $board), element('post_id', $post)),
					html_escape(element('brd_name', $board)),
					board_url(element('brd_key', $board)),
				);

				if ($emailsendlistadmin) {
					$title = str_replace(
						$searchconfig,
						$replaceconfig,
						$this->cbconfig->item('send_email_comment_admin_title')
					);
					$content = str_replace(
						$searchconfig,
						$replaceconfig_escape,
						$this->cbconfig->item('send_email_comment_admin_content')
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
						$this->cbconfig->item('send_email_comment_post_writer_title')
					);
					$content = str_replace(
						$searchconfig,
						$replaceconfig_escape,
						$this->cbconfig->item('send_email_comment_post_writer_content')
					);
					$this->email->clear(true);
					$this->email->from($this->cbconfig->item('webmaster_email'), $this->cbconfig->item('webmaster_name'));
					$this->email->to(element('mem_email', $emailsendlistpostwriter));
					$this->email->subject($title);
					$this->email->message($content);
					$this->email->send();
				}
				if ($emailsendlistcmtwriter) {
					$title = str_replace(
						$searchconfig,
						$replaceconfig,
						$this->cbconfig->item('send_email_comment_comment_writer_title')
					);
					$content = str_replace(
						$searchconfig,
						$replaceconfig_escape,
						$this->cbconfig->item('send_email_comment_comment_writer_content')
					);
					$this->email->clear(true);
					$this->email->from($this->cbconfig->item('webmaster_email'), $this->cbconfig->item('webmaster_name'));
					$this->email->to(element('mem_email', $emailsendlistcmtwriter));
					$this->email->subject($title);
					$this->email->message($content);
					$this->email->send();
				}
				if ($notesendlistadmin) {
					$title = str_replace(
						$searchconfig,
						$replaceconfig,
						$this->cbconfig->item('send_note_comment_admin_title')
					);
					$content = str_replace(
						$searchconfig,
						$replaceconfig_escape,
						$this->cbconfig->item('send_note_comment_admin_content')
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
						$this->cbconfig->item('send_note_comment_post_writer_title')
					);
					$content = str_replace(
						$searchconfig,
						$replaceconfig_escape,
						$this->cbconfig->item('send_note_comment_post_writer_content')
					);
					$note_result = $this->notelib->send_note(
						$sender = 0,
						$receiver = element('mem_id', $notesendlistpostwriter),
						$title,
						$content,
						1
					);
				}
				if ($notesendlistcmtwriter && element('mem_id', $notesendlistcmtwriter)) {
					$title = str_replace(
						$searchconfig,
						$replaceconfig,
						$this->cbconfig->item('send_note_comment_comment_writer_title')
					);
					$content = str_replace(
						$searchconfig,
						$replaceconfig_escape,
						$this->cbconfig->item('send_note_comment_comment_writer_content')
					);
					$note_result = $this->notelib->send_note(
						$sender = 0,
						$receiver = element('mem_id', $notesendlistcmtwriter),
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
							$this->cbconfig->item('send_sms_comment_admin_content')
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
						$smsresult = $this->smslib->send($receiver, $sender, $content, $date = '', '댓글 작성 알림');
					}
				}
				if ($smssendlistpostwriter) {
					if (file_exists(APPPATH . 'libraries/Smslib.php')) {
						$this->load->library(array('smslib'));
						$content = str_replace(
							$searchconfig,
							$replaceconfig,
							$this->cbconfig->item('send_sms_comment_post_writer_content')
						);
						$sender = array(
							'phone' => $this->cbconfig->item('sms_admin_phone'),
						);
						$receiver = array();
						$receiver[] = $smssendlistpostwriter;
						$this->load->library('smslib');
						$smsresult = $this->smslib->send($receiver, $sender, $content, $date = '', '댓글 작성 알림');
					}
				}
				if ($smssendlistcmtwriter) {
					if (file_exists(APPPATH . 'libraries/Smslib.php')) {
						$this->load->library(array('smslib'));
						$content = str_replace(
							$searchconfig,
							$replaceconfig,
							$this->cbconfig->item('send_sms_comment_comment_writer_content')
						);
						$sender = array(
							'phone' => $this->cbconfig->item('sms_admin_phone'),
						);
						$receiver = array();
						$receiver[] = $smssendlistcmtwriter;
						$smsresult = $this->smslib->send($receiver, $sender, $content, $date = '', '댓글 작성 알림');
					}
				}

				$this->session->set_userdata(
					'lastest_post_time',
					ctimestamp()
				);

				// 이벤트가 존재하면 실행합니다
				Events::trigger('after_insert', $eventname);

				$result = array('success' => '댓글이 등록되었습니다');
				exit(json_encode($result));

			} else {

				$updatedata = array(
					'cmt_content' => $cmt_content,
					'cmt_html' => $content_type,
					'cmt_updated_datetime' => cdate('Y-m-d H:i:s'),
					'cmt_ip' => $this->input->ip_address(),
				);

				if ($can_comment_secret) {
					$updatedata['cmt_secret'] = $this->input->post('cmt_secret') ? 1 : 0;
				}
				if ($this->member->is_member() && element('use_comment_secret', $board) === '2') {
					$updatedata['cmt_secret'] = 1;
				}

				$updatedata['cmt_device'] = ($this->cbconfig->get_device_type() === 'mobile')
					? 'mobile' : 'desktop';

				$this->Comment_model->update($cmt_id, $updatedata);

				// 이벤트가 존재하면 실행합니다
				Events::trigger('after_update', $eventname);

				$result = array('success' => '댓글이 수정되었습니다');
				exit(json_encode($result));
			}
		}
	}


	/**
	 * 댓글입력시 비회원이 입력한 경우 닉네임을 체크합니다
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
	 * 새로 입력한 패스워드가 규약에 맞는지 체크합니다.
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
	 * 댓글입력시 비회원이 입력한 경우 captcha를 체크합니다
	 */
	public function _check_captcha($str)
	{
		$captcha = $this->session->userdata('captcha');
		if ( ! is_array($captcha) OR ! element('word', $captcha)
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
}
