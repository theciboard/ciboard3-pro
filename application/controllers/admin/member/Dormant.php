<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Dormant class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 관리자>회원설정>휴면계정관리 controller 입니다.
 */
class Dormant extends CB_Controller
{

	/**
	 * 관리자 페이지 상의 현재 디렉토리입니다
	 * 페이지 이동시 필요한 정보입니다
	 */
	public $pagedir = 'member/dormant';

	public $dormant_days_text;

	/**
	 * 모델을 로딩합니다
	 */
	protected $models = array();

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
		$this->load->library(array('pagination', 'querystring'));
		$this->dormant_days_text = array(
			'30' => '30일',
			'90' => '90일',
			'180' => '180일',
			'365' => '1년',
			'730' => '2년',
			'1095' => '3년',
			'1460' => '4년',
			'1825' => '5년',
		);
	}

	/**
	 * 기본정보를 가져오는 메소드입니다
	 */
	public function index()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_member_dormant_index';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		/**
		 * Validation 라이브러리를 가져옵니다
		 */
		$this->load->library('form_validation');

		/**
		 * 모델을 로딩합니다
		 */
		$this->load->model('Config_model');

		/**
		 * 전송된 데이터의 유효성을 체크합니다
		 */
		$config = array(
			array(
				'field' => 'is_submit',
				'label' => '전송',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'member_dormant_days',
				'label' => '휴면계정 전환 조건',
				'rules' => 'trim|required|numeric|is_natural',
			),
			array(
				'field' => 'member_dormant_method',
				'label' => '휴면계정 정리 방법',
				'rules' => 'trim',
			),
			array(
				'field' => 'member_dormant_auto_clean',
				'label' => '자동정리여부',
				'rules' => 'trim',
			),
			array(
				'field' => 'member_dormant_auto_email',
				'label' => '자동메일발송여부',
				'rules' => 'trim',
			),
			array(
				'field' => 'member_dormant_auto_email_days',
				'label' => '자동메일발송 기간',
				'rules' => 'trim|required|numeric|is_natural',
			),
			array(
				'field' => 'member_dormant_reset_point',
				'label' => '포인트 몰수 여부',
				'rules' => 'trim|required|numeric|is_natural',
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

			$array = array(
				'member_dormant_days', 'member_dormant_method', 'member_dormant_auto_clean',
				'member_dormant_auto_email', 'member_dormant_auto_email_days', 'member_dormant_reset_point'
			);
			foreach ($array as $value) {
				$savedata[$value] = $this->input->post($value, null, '');
			}

			$this->Config_model->save($savedata);
			$view['view']['alert_message'] = '기본정보 설정이 저장되었습니다';
		}

		$getdata = $this->Config_model->get_all_meta();
		if ( ! isset($getdata['member_dormant_days']) OR ! $getdata['member_dormant_days']) $getdata['member_dormant_days'] = '365';
		if ( ! isset($getdata['member_dormant_auto_email_days']) OR ! $getdata['member_dormant_auto_email_days']) $getdata['member_dormant_auto_email_days'] = '30';
		$view['view']['data'] = $getdata;

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
	 * 휴면계정 일괄정리 하는 페이지입니다
	 */
	public function cleantodormant()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_member_dormant_cleantodormant';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		/**
		 * Validation 라이브러리를 가져옵니다
		 */
		$this->load->library('form_validation');

		/**
		 * 전송된 데이터의 유효성을 체크합니다
		 */
		$config = array(
			array(
				'field' => 'execute',
				'label' => '실행',
				'rules' => 'trim',
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

			$where = array();
			$dormant_days = $this->cbconfig->item('member_dormant_days') ? $this->cbconfig->item('member_dormant_days') : 365;
			$gap = $dormant_days * 24 * 60 * 60;
			$lastlogin = ctimestamp() - $gap;
			$lastlogin_datetime = cdate('Y-m-d H:i:s', $lastlogin);
			$where['mem_lastlogin_datetime <='] = $lastlogin_datetime;
			$where['mem_register_datetime <='] = $lastlogin_datetime;
			$view['view']['count'] = $this->Member_model->count_by($where);


			// 이벤트가 존재하면 실행합니다
			$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

			/**
			 * 어드민 레이아웃을 정의합니다
			 */
			$layoutconfig = array('layout' => 'layout', 'skin' => 'cleantodormant');
			$view['layout'] = $this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
			$this->data = $view;
			$this->layout = element('layout_skin_file', element('layout', $view));
			$this->view = element('view_skin_file', element('layout', $view));

		} else {
			/**
			 * 유효성 검사를 통과한 경우입니다.
			 * 즉 데이터의 insert 나 update 의 process 처리가 필요한 상황입니다
			 */

			// 이벤트가 존재하면 실행합니다
			$view['view']['event']['formruntrue'] = Events::trigger('formruntrue', $eventname);

			$where = array();
			$dormant_days = $this->cbconfig->item('member_dormant_days') ? $this->cbconfig->item('member_dormant_days') : 365;
			$gap = $dormant_days * 24 * 60 * 60;
			$lastlogin = ctimestamp() - $gap;
			$lastlogin_datetime = cdate('Y-m-d H:i:s', $lastlogin);
			$where['mem_lastlogin_datetime <='] = $lastlogin_datetime;
			$where['mem_register_datetime <='] = $lastlogin_datetime;
			$result = $this->Member_model->get('', 'mem_id', $where);
			if ($result) {
				foreach ($result as $value) {
					$mem_id = element('mem_id', $value);
					if ($this->cbconfig->item('member_dormant_method') === 'delete') {
						$this->member->delete_member($mem_id);
					} else {
						$this->member->archive_to_dormant($mem_id);
					}
				}
				exit(json_encode(array('success' => 'ok', 'message' => '정리가 완료되었습니다')));
			} else {
				exit(json_encode(array('success' => 'fail', 'message' => '정리할 대상이 없습니다')));
			}
		}
	}

	/**
	 * 안내메일 일괄발송 하는 페이지입니다
	 */
	public function emailtowaiting()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_member_dormant_emailtowaiting';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		/**
		 * Validation 라이브러리를 가져옵니다
		 */
		$this->load->library('form_validation');

		/**
		 * 모델을 로딩합니다
		 */
		$this->load->model('Member_dormant_notify_model');

		/**
		 * 전송된 데이터의 유효성을 체크합니다
		 */
		$config = array(
			array(
				'field' => 'execute',
				'label' => '실행',
				'rules' => 'trim',
			),
		);
		$this->form_validation->set_rules($config);

		$view['view']['period_text'] = $this->dormant_days_text[$this->cbconfig->item('member_dormant_days')];

		/**
		 * 유효성 검사를 하지 않는 경우, 또는 유효성 검사에 실패한 경우입니다.
		 * 즉 글쓰기나 수정 페이지를 보고 있는 경우입니다
		 */
		if ($this->form_validation->run() === false) {

			// 이벤트가 존재하면 실행합니다
			$view['view']['event']['formrunfalse'] = Events::trigger('formrunfalse', $eventname);

			$where = array();
			$dormant_days = $this->cbconfig->item('member_dormant_days') ? $this->cbconfig->item('member_dormant_days') : 365;
			$email_days = $this->cbconfig->item('member_dormant_auto_email_days') ? $this->cbconfig->item('member_dormant_auto_email_days') : 365;
			$gap = $dormant_days * 24 * 60 * 60;
			$email_gap = $email_days * 24 * 60 * 60;
			$lastlogin = ctimestamp() - $gap + $email_gap;
			$lastlogin_datetime = cdate('Y-m-d H:i:s', $lastlogin);
			$view['view']['count_unsent_email_member'] = $this->Member_dormant_notify_model->count_unsent_email_member($lastlogin_datetime);


			// 이벤트가 존재하면 실행합니다
			$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

			/**
			 * 어드민 레이아웃을 정의합니다
			 */
			$layoutconfig = array('layout' => 'layout', 'skin' => 'emailtowaiting');
			$view['layout'] = $this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
			$this->data = $view;
			$this->layout = element('layout_skin_file', element('layout', $view));
			$this->view = element('view_skin_file', element('layout', $view));

		} else {
			/**
			 * 유효성 검사를 통과한 경우입니다.
			 * 즉 데이터의 insert 나 update 의 process 처리가 필요한 상황입니다
			 */

			// 이벤트가 존재하면 실행합니다
			$view['view']['event']['formruntrue'] = Events::trigger('formruntrue', $eventname);

			/**
			 * Email 라이브러리를 가져옵니다
			 */
			$this->load->library('email');

			$where = array();
			$dormant_days = $this->cbconfig->item('member_dormant_days') ? $this->cbconfig->item('member_dormant_days') : 365;
			$email_days = $this->cbconfig->item('member_dormant_auto_email_days') ? $this->cbconfig->item('member_dormant_auto_email_days') : 365;
			$gap = $dormant_days * 24 * 60 * 60;
			$email_gap = $email_days * 24 * 60 * 60;
			$lastlogin = ctimestamp() - $gap + $email_gap;
			$lastlogin_datetime = cdate('Y-m-d H:i:s', $lastlogin);
			$result = $this->Member_dormant_notify_model->get_unsent_email_member($lastlogin_datetime);
			if ($result) {
				foreach ($result as $value) {
					$mem_id = element('mem_id', $value);
					// 메일 발송하기

					$searchconfig = array(
						'{홈페이지명}',
						'{회사명}',
						'{홈페이지주소}',
						'{회원아이디}',
						'{회원닉네임}',
						'{회원실명}',
						'{회원이메일}',
						'{메일수신여부}',
						'{쪽지수신여부}',
						'{문자수신여부}',
						'{최종로그인시간}',
						'{정리예정날짜}',
						'{정리기준}',
						'{정리방법}',
					);
					$receive_email = element('mem_receive_email', $value) ? '동의' : '거부';
					$receive_note = element('mem_use_note', $value) ? '동의' : '거부';
					$receive_sms = element('mem_receive_sms', $value) ? '동의' : '거부';
					$dormant_timestamp = strtotime(element('mem_lastlogin_datetime', $value)) + ($this->cbconfig->item('member_dormant_days') * 24 * 60 * 60);
					$dormant_date = cdate('Y년 m월 d일', $dormant_timestamp);
					$dormant_datetime = cdate('Y-m-d H:i:s', $dormant_timestamp);
					$replaceconfig = array(
						$this->cbconfig->item('site_title'),
						$this->cbconfig->item('company_name'),
						site_url(),
						element('mem_userid', $value),
						element('mem_nickname', $value),
						element('mem_username', $value),
						element('mem_email', $value),
						$receive_email,
						$receive_note,
						$receive_sms,
						cdate('Y년 m월 d일 H시 i분', strtotime(element('mem_lastlogin_datetime', $value))),
						$dormant_date,
						$this->dormant_days_text[$this->cbconfig->item('member_dormant_days')],
						($this->cbconfig->item('member_dormant_method') === 'delete' ? '삭제' : '별도의 저장소에 보관'),
					);
					$replaceconfig_escape = array(
						html_escape($this->cbconfig->item('site_title')),
						html_escape($this->cbconfig->item('company_name')),
						site_url(),
						html_escape(element('mem_userid', $value)),
						html_escape(element('mem_nickname', $value)),
						html_escape(element('mem_username', $value)),
						html_escape(element('mem_email', $value)),
						$receive_email,
						$receive_note,
						$receive_sms,
						cdate('Y년 m월 d일 H시 i분', strtotime(element('mem_lastlogin_datetime', $value))),
						$dormant_date,
						$this->dormant_days_text[$this->cbconfig->item('member_dormant_days')],
						($this->cbconfig->item('member_dormant_method') === 'delete' ? '삭제' : '별도의 저장소에 보관'),
					);

					$title = str_replace(
						$searchconfig,
						$replaceconfig,
						$this->cbconfig->item('send_email_dormant_notify_user_title')
					);
					$content = str_replace(
						$searchconfig,
						$replaceconfig_escape,
						$this->cbconfig->item('send_email_dormant_notify_user_content')
					);

					$this->email->clear(true);
					$this->email->from($this->cbconfig->item('webmaster_email'), $this->cbconfig->item('webmaster_name'));
					$this->email->to(element('mem_email', $value));
					$this->email->subject($title);
					$this->email->message($content);
					$this->email->send();

					$insertdata = array(
						'mem_id' => element('mem_id', $value),
						'mem_userid' => element('mem_userid', $value),
						'mem_email' => element('mem_email', $value),
						'mem_username' => element('mem_username', $value),
						'mem_nickname' => element('mem_nickname', $value),
						'mem_register_datetime' => element('mem_register_datetime', $value),
						'mem_lastlogin_datetime' => element('mem_lastlogin_datetime', $value),
						'mdn_dormant_datetime' => $dormant_datetime,
						'mdn_dormant_notify_datetime' => cdate('Y-m-d H:i:s'),
					);
					$this->Member_dormant_notify_model->insert($insertdata);

				}
				exit(json_encode(array('success' => 'ok', 'message' => '메일발송이 완료되었습니다')));
			} else {
				exit(json_encode(array('success' => 'fail', 'message' => '메일발송할 대상이 없습니다')));
			}
		}
	}

	/**
	 * 휴면대상에게 보낼 안내메일내용을 관리합니다
	 */
	public function emailcontent()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_config_dormant_emailcontent';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		/**
		 * Validation 라이브러리를 가져옵니다
		 */
		$this->load->library('form_validation');

		/**
		 * 모델을 로딩합니다
		 */
		$this->load->model('Config_model');

		/**
		 * Editor 헬퍼를 가져옵니다
		 */
		$this->load->helper('dhtml_editor');

		/**
		 * 전송된 데이터의 유효성을 체크합니다
		 */
		$config = array(
			array(
				'field' => 'is_submit',
				'label' => '전송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'send_email_dormant_notify_user_title',
				'label' => '발송메일 제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_email_dormant_notify_user_content',
				'label' => '발송메일 내용',
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

			$array = array(
				'send_email_dormant_notify_user_title', 'send_email_dormant_notify_user_content'
			);
			foreach ($array as $value) {
				$savedata[$value] = $this->input->post($value, null, '');
			}
			$this->Config_model->save($savedata);
			$view['view']['alert_message'] = '이메일변경시인증메일 발송양식 설정이 저장되었습니다';
		}

		$getdata = $this->Config_model->get_all_meta();
		$view['view']['data'] = $getdata;

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 어드민 레이아웃을 정의합니다
		 */
		$layoutconfig = array('layout' => 'layout', 'skin' => 'emailcontent');
		$view['layout'] = $this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}

	/**
	 * 안내메일 발송내역을 확인하는 페이지입니다
	 */
	public function emailsendlist()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_member_dormant_emailsendlist';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		/**
		 * 모델을 로딩합니다
		 */
		$this->load->model('Member_dormant_notify_model');

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		/**
		 * 페이지에 숫자가 아닌 문자가 입력되거나 1보다 작은 숫자가 입력되면 에러 페이지를 보여줍니다.
		 */
		$param =& $this->querystring;
		$page = (((int) $this->input->get('page')) > 0) ? ((int) $this->input->get('page')) : 1;
		$view['view']['sort'] = array(
			'mem_id' => $param->sort('mem_id', 'asc'),
			'mem_userid' => $param->sort('mem_userid', 'asc'),
			'mem_username' => $param->sort('mem_username', 'asc'),
			'mem_nickname' => $param->sort('mem_nickname', 'asc'),
			'mem_email' => $param->sort('mem_email', 'asc'),
			'mem_register_datetime' => $param->sort('mem_register_datetime', 'asc'),
			'mem_lastlogin_datetime' => $param->sort('mem_lastlogin_datetime', 'asc'),
			'mdn_dormant_datetime' => $param->sort('mdn_dormant_datetime', 'asc'),
			'mdn_dormant_notify_datetime' => $param->sort('mdn_dormant_notify_datetime', 'asc'),
		);
		$findex = $this->input->get('findex', null, 'mdn_id');
		$forder = $this->input->get('forder', null, 'desc');
		$sfield = $this->input->get('sfield', null, '');
		$skeyword = $this->input->get('skeyword', null, '');

		$per_page = admin_listnum();
		$offset = ($page - 1) * $per_page;

		/**
		 * 게시판 목록에 필요한 정보를 가져옵니다.
		 */
		$this->Member_model->allow_search_field = array('mdn_id', 'mem_id', 'mem_userid', 'mem_email', 'mem_username', 'mem_nickname', 'mem_register_datetime', 'mem_lastlogin_datetime', 'mdn_dormant_datetime', 'mdn_dormant_notify_datetime'); // 검색이 가능한 필드
		$this->Member_model->search_field_equal = array('mdn_id', 'mem_id'); // 검색중 like 가 아닌 = 검색을 하는 필드
		$this->Member_model->allow_order_field = array('mdn_id', 'mem_id', 'mem_userid', 'mem_username', 'mem_nickname', 'mem_email', 'mem_register_datetime', 'mem_lastlogin_datetime', 'mdn_dormant_datetime', 'mdn_dormant_notify_datetime'); // 정렬이 가능한 필드

		$where = array();
		$result = $this->Member_dormant_notify_model
			->get_admin_list($per_page, $offset, $where, '', $findex, $forder, $sfield, $skeyword);
		$list_num = $result['total_rows'] - ($page - 1) * $per_page;

		if (element('list', $result)) {
			foreach (element('list', $result) as $key => $val) {
				$result['list'][$key]['num'] = $list_num--;
			}
		}
		$view['view']['data'] = $result;

		/**
		 * primary key 정보를 저장합니다
		 */
		$view['view']['primary_key'] = $this->Member_model->primary_key;

		/**
		 * 페이지네이션을 생성합니다
		 */
		$config['base_url'] = admin_url($this->pagedir) . '/emailsendlist?' . $param->replace('page');
		$config['total_rows'] = $result['total_rows'];
		$config['per_page'] = $per_page;
		$this->pagination->initialize($config);
		$view['view']['paging'] = $this->pagination->create_links();
		$view['view']['page'] = $page;

		/**
		 * 쓰기 주소, 삭제 주소등 필요한 주소를 구합니다
		 */
		$search_option = array('mem_userid' => '회원아이디', 'mem_email' => '이메일', 'mem_username' => '회원명', 'mem_nickname' => '닉네임', 'mem_register_datetime' => '회원가입날짜', 'mem_lastlogin_datetime' => '최종로그인날짜', 'mdn_dormant_datetime' => '전환예정일', 'mdn_dormant_notify_datetime' => '메일발송날짜');
		$view['view']['skeyword'] = ($sfield && array_key_exists($sfield, $search_option)) ? $skeyword : '';
		$view['view']['search_option'] = search_option($search_option, $sfield);
		$view['view']['listall_url'] = admin_url($this->pagedir . '/emailsendlist');

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 어드민 레이아웃을 정의합니다
		 */
		$layoutconfig = array('layout' => 'layout', 'skin' => 'emailsendlist');
		$view['layout'] = $this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}

	/**
	 * 휴면처리 해야할 회원 목록을 가져오는 메소드입니다
	 */
	public function waitinglist()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_member_dormant_waitinglist';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		/**
		 * 모델을 로딩합니다
		 */
		$this->load->model(array('Member_dormant_notify_model', 'Member_meta_model'));

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		$view['view']['period_text'] = $this->dormant_days_text[$this->cbconfig->item('member_dormant_days')];

		if ($this->cbconfig->item('member_dormant_auto_clean')) {
			/**
			 * 어드민 레이아웃을 정의합니다
			 */
			$layoutconfig = array('layout' => 'layout', 'skin' => 'waitinglist');
			$view['layout'] = $this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
			$this->data = $view;
			$this->layout = element('layout_skin_file', element('layout', $view));
			$this->view = element('view_skin_file', element('layout', $view));
			return;
		}

		/**
		 * 페이지에 숫자가 아닌 문자가 입력되거나 1보다 작은 숫자가 입력되면 에러 페이지를 보여줍니다.
		 */
		$param =& $this->querystring;
		$page = (((int) $this->input->get('page')) > 0) ? ((int) $this->input->get('page')) : 1;
		$view['view']['sort'] = array(
			'mem_id' => $param->sort('mem_id', 'asc'),
			'mem_userid' => $param->sort('mem_userid', 'asc'),
			'mem_username' => $param->sort('mem_username', 'asc'),
			'mem_nickname' => $param->sort('mem_nickname', 'asc'),
			'mem_email' => $param->sort('mem_email', 'asc'),
			'mem_point' => $param->sort('mem_point', 'asc'),
			'mem_register_datetime' => $param->sort('mem_register_datetime', 'asc'),
			'mem_lastlogin_datetime' => $param->sort('mem_lastlogin_datetime', 'asc'),
			'mem_level' => $param->sort('mem_level', 'asc'),
		);
		$findex = $this->input->get('findex', null, 'member.mem_id');
		$forder = $this->input->get('forder', null, 'desc');
		$sfield = $this->input->get('sfield', null, '');
		$skeyword = $this->input->get('skeyword', null, '');

		$per_page = admin_listnum();
		$offset = ($page - 1) * $per_page;

		/**
		 * 게시판 목록에 필요한 정보를 가져옵니다.
		 */
		$this->Member_model->allow_search_field = array('mem_id', 'mem_userid', 'mem_email', 'mem_username', 'mem_nickname', 'mem_level', 'mem_homepage', 'mem_register_datetime', 'mem_register_ip', 'mem_lastlogin_datetime', 'mem_lastlogin_ip', 'mem_is_admin'); // 검색이 가능한 필드
		$this->Member_model->search_field_equal = array('mem_id', 'mem_level', 'mem_is_admin'); // 검색중 like 가 아닌 = 검색을 하는 필드
		$this->Member_model->allow_order_field = array('member.mem_id', 'mem_userid', 'mem_username', 'mem_nickname', 'mem_email', 'mem_point', 'mem_register_datetime', 'mem_lastlogin_datetime', 'mem_level'); // 정렬이 가능한 필드

		$where = array();
		$dormant_days = $this->cbconfig->item('member_dormant_days') ? $this->cbconfig->item('member_dormant_days') : 365;
		$gap = $dormant_days * 24 * 60 * 60;
		$lastlogin = ctimestamp() - $gap;
		$lastlogin_datetime = cdate('Y-m-d H:i:s', $lastlogin);
		$where['mem_lastlogin_datetime <='] = $lastlogin_datetime;
		$where['mem_register_datetime <='] = $lastlogin_datetime;
		$result = $this->Member_model
			->get_admin_list($per_page, $offset, $where, '', $findex, $forder, $sfield, $skeyword);
		$list_num = $result['total_rows'] - ($page - 1) * $per_page;

		if (element('list', $result)) {
			foreach (element('list', $result) as $key => $val) {
				$where = array(
					'mem_id' => element('mem_id', $val),
				);
				$result['list'][$key]['display_name'] = display_username(
					element('mem_userid', $val),
					element('mem_nickname', $val),
					element('mem_icon', $val)
				);
				$result['list'][$key]['meta'] = $this->Member_meta_model->get_all_meta(element('mem_id', $val));

				$result['list'][$key]['mdn_dormant_notify_datetime'] = '';
				$notify_where = array(
					'mem_id' => element('mem_id', $val),
				);
				$notify = $this->Member_dormant_notify_model->get('', '', $notify_where);
				if ($notify && is_array($notify)) {
					foreach ($notify as $nval) {
						$result['list'][$key]['mdn_dormant_notify_datetime'] .= display_datetime(element('mdn_dormant_notify_datetime', $nval), 'full') . '<br />';
					}
				}
				$lastloginday = new DateTime(substr(element('mem_lastlogin_datetime', $val), 0, 10));
				$today = new DateTime(cdate('Y-m-d'));
				$date_diff = date_diff($lastloginday, $today);
				$result['list'][$key]['daygap'] = $date_diff->days;

				$result['list'][$key]['num'] = $list_num--;
			}
		}
		$view['view']['data'] = $result;

		/**
		 * primary key 정보를 저장합니다
		 */
		$view['view']['primary_key'] = $this->Member_model->primary_key;

		/**
		 * 페이지네이션을 생성합니다
		 */
		$config['base_url'] = admin_url($this->pagedir) . '/waitinglist?' . $param->replace('page');
		$config['total_rows'] = $result['total_rows'];
		$config['per_page'] = $per_page;
		$this->pagination->initialize($config);
		$view['view']['paging'] = $this->pagination->create_links();
		$view['view']['page'] = $page;

		/**
		 * 쓰기 주소, 삭제 주소등 필요한 주소를 구합니다
		 */
		$search_option = array('mem_userid' => '회원아이디', 'mem_email' => '이메일', 'mem_username' => '회원명', 'mem_nickname' => '닉네임', 'mem_level' => '회원레벨', 'mem_homepage' => '홈페이지', 'mem_register_datetime' => '회원가입날짜', 'mem_register_ip' => '회원가입IP', 'mem_lastlogin_datetime' => '최종로그인날짜', 'mem_lastlogin_ip' => '최종로그인IP', 'mem_adminmemo' => '관리자메모');
		$view['view']['skeyword'] = ($sfield && array_key_exists($sfield, $search_option)) ? $skeyword : '';
		$view['view']['search_option'] = search_option($search_option, $sfield);
		$view['view']['listall_url'] = admin_url($this->pagedir . '/waitinglist');
		$view['view']['list_update_url'] = admin_url($this->pagedir . '/waitinglist_listupdate/?' . $param->output());
		$view['view']['list_delete_url'] = admin_url($this->pagedir . '/waitinglist_listdelete/?' . $param->output());

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 어드민 레이아웃을 정의합니다
		 */
		$layoutconfig = array('layout' => 'layout', 'skin' => 'waitinglist');
		$view['layout'] = $this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}

	/**
	 * 휴면처리해야할 회원을 별도의 저장소로 보관하는 경우 실행되는 메소드입니다
	 */
	public function waitinglist_listupdate()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_member_dormant_waitinglist_listupdate';
		$this->load->event($eventname);

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		/**
		 * 체크한 게시물의 삭제를 실행합니다
		 */
		if ($this->input->post('chk') && is_array($this->input->post('chk'))) {
			foreach ($this->input->post('chk') as $val) {
				if ($val) {
					$this->member->archive_to_dormant($val);
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
			'정상적으로 별도의 보관소로 이동되었습니다'
		);
		$param =& $this->querystring;
		$redirecturl = admin_url($this->pagedir . '/waitinglist?' . $param->output());

		redirect($redirecturl);
	}

	/**
	 * 휴면처리해야할 회원을 영구삭제한 경우 실행되는 메소드입니다
	 */
	public function waitinglist_listdelete()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_member_dormant_waitinglist_listdelete';
		$this->load->event($eventname);

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		/**
		 * 체크한 게시물의 삭제를 실행합니다
		 */
		if ($this->input->post('chk') && is_array($this->input->post('chk'))) {
			foreach ($this->input->post('chk') as $val) {
				if ($val) {
					$this->member->delete_member($val);
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
			'선택한 회원이 영구 삭제되었습니다'
		);
		$param =& $this->querystring;
		$redirecturl = admin_url($this->pagedir . '/waitinglist?' . $param->output());

		redirect($redirecturl);
	}

	/**
	 * 휴면처리 해야할 회원 목록을 가져오는 메소드입니다
	 */
	public function dormantlist()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_member_dormant_dormantlist';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 모델을 로딩합니다
		$this->load->model(array('Member_dormant_model', 'Member_meta_model'));

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		$view['view']['period_text'] = $this->dormant_days_text[$this->cbconfig->item('member_dormant_days')];

		/**
		 * 페이지에 숫자가 아닌 문자가 입력되거나 1보다 작은 숫자가 입력되면 에러 페이지를 보여줍니다.
		 */
		$param =& $this->querystring;
		$page = (((int) $this->input->get('page')) > 0) ? ((int) $this->input->get('page')) : 1;
		$view['view']['sort'] = array(
			'mem_id' => $param->sort('mem_id', 'asc'),
			'mem_userid' => $param->sort('mem_userid', 'asc'),
			'mem_username' => $param->sort('mem_username', 'asc'),
			'mem_nickname' => $param->sort('mem_nickname', 'asc'),
			'mem_email' => $param->sort('mem_email', 'asc'),
			'mem_point' => $param->sort('mem_point', 'asc'),
			'mem_register_datetime' => $param->sort('mem_register_datetime', 'asc'),
			'mem_lastlogin_datetime' => $param->sort('mem_lastlogin_datetime', 'asc'),
			'mem_level' => $param->sort('mem_level', 'asc'),
		);
		$findex = $this->input->get('findex', null, 'member_dormant.mem_id');
		$forder = $this->input->get('forder', null, 'desc');
		$sfield = $this->input->get('sfield', null, '');
		$skeyword = $this->input->get('skeyword', null, '');

		$per_page = admin_listnum();
		$offset = ($page - 1) * $per_page;

		/**
		 * 게시판 목록에 필요한 정보를 가져옵니다.
		 */
		$this->Member_dormant_model->allow_search_field = array('mem_id', 'mem_userid', 'mem_email', 'mem_username', 'mem_nickname', 'mem_level', 'mem_homepage', 'mem_register_datetime', 'mem_register_ip', 'mem_lastlogin_datetime', 'mem_lastlogin_ip', 'mem_is_admin'); // 검색이 가능한 필드
		$this->Member_dormant_model->search_field_equal = array('mem_id', 'mem_level', 'mem_is_admin'); // 검색중 like 가 아닌 = 검색을 하는 필드
		$this->Member_dormant_model->allow_order_field = array('member_dormant.mem_id', 'mem_userid', 'mem_username', 'mem_nickname', 'mem_email', 'mem_point', 'mem_register_datetime', 'mem_lastlogin_datetime', 'mem_level'); // 정렬이 가능한 필드

		$where = array();
		$dormant_days = $this->cbconfig->item('member_dormant_days') ? $this->cbconfig->item('member_dormant_days') : 365;
		$gap = $dormant_days * 24 * 60 * 60;
		$lastlogin = ctimestamp() - $gap;
		$lastlogin_datetime = cdate('Y-m-d H:i:s', $lastlogin);
		$where['mem_lastlogin_datetime <='] = $lastlogin_datetime;
		$result = $this->Member_dormant_model
			->get_admin_list($per_page, $offset, $where, '', $findex, $forder, $sfield, $skeyword);
		$list_num = $result['total_rows'] - ($page - 1) * $per_page;

		if (element('list', $result)) {
			foreach (element('list', $result) as $key => $val) {
				$where = array(
					'mem_id' => element('mem_id', $val),
				);
				$result['list'][$key]['meta'] = $this->Member_meta_model->get_all_meta(element('mem_id', $val));

				$lastloginday = new DateTime(substr(element('mem_lastlogin_datetime', $val), 0, 10));
				$today = new DateTime(cdate('Y-m-d'));
				$date_diff = date_diff($lastloginday, $today);
				$result['list'][$key]['daygap'] = $date_diff->days;

				$result['list'][$key]['num'] = $list_num--;
			}
		}
		$view['view']['data'] = $result;

		/**
		 * primary key 정보를 저장합니다
		 */
		$view['view']['primary_key'] = $this->Member_dormant_model->primary_key;

		/**
		 * 페이지네이션을 생성합니다
		 */
		$config['base_url'] = admin_url($this->pagedir) . '/dormantlist?' . $param->replace('page');
		$config['total_rows'] = $result['total_rows'];
		$config['per_page'] = $per_page;
		$this->pagination->initialize($config);
		$view['view']['paging'] = $this->pagination->create_links();
		$view['view']['page'] = $page;

		/**
		 * 쓰기 주소, 삭제 주소등 필요한 주소를 구합니다
		 */
		$search_option = array('mem_userid' => '회원아이디', 'mem_email' => '이메일', 'mem_username' => '회원명', 'mem_nickname' => '닉네임', 'mem_level' => '회원레벨', 'mem_homepage' => '홈페이지', 'mem_register_datetime' => '회원가입날짜', 'mem_register_ip' => '회원가입IP', 'mem_lastlogin_datetime' => '최종로그인날짜', 'mem_lastlogin_ip' => '최종로그인IP', 'mem_adminmemo' => '관리자메모');
		$view['view']['skeyword'] = ($sfield && array_key_exists($sfield, $search_option)) ? $skeyword : '';
		$view['view']['search_option'] = search_option($search_option, $sfield);
		$view['view']['listall_url'] = admin_url($this->pagedir . '/dormantlist');
		$view['view']['list_update_url'] = admin_url($this->pagedir . '/dormantlist_listupdate/?' . $param->output());
		$view['view']['list_delete_url'] = admin_url($this->pagedir . '/dormantlist_listdelete/?' . $param->output());

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 어드민 레이아웃을 정의합니다
		 */
		$layoutconfig = array('layout' => 'layout', 'skin' => 'dormantlist');
		$view['layout'] = $this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}

	/**
	 * 별도의 저장소에 보관되어있는 휴면중인 회원을 복원하는 경우 실행되는 메소드입니다
	 */
	public function dormantlist_listupdate()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_member_dormant_dormantlist_listupdate';
		$this->load->event($eventname);

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		/**
		 * 체크한 게시물의 삭제를 실행합니다
		 */
		if ($this->input->post('chk') && is_array($this->input->post('chk'))) {
			foreach ($this->input->post('chk') as $val) {
				if ($val) {
					$this->member->recover_from_dormant($val);
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
		$redirecturl = admin_url($this->pagedir . '/dormantlist?' . $param->output());

		redirect($redirecturl);
	}

	/**
	 * 휴면중인 회원을 영구삭제한 경우 실행되는 메소드입니다
	 */
	public function dormantlist_listdelete()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_member_dormant_dormantlist_listdelete';
		$this->load->event($eventname);

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		/**
		 * 체크한 게시물의 삭제를 실행합니다
		 */
		if ($this->input->post('chk') && is_array($this->input->post('chk'))) {
			foreach ($this->input->post('chk') as $val) {
				if ($val) {
					$this->member->delete_member($val);
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
			'선택한 회원이 영구 삭제되었습니다'
		);
		$param =& $this->querystring;
		$redirecturl = admin_url($this->pagedir . '/dormantlist?' . $param->output());

		redirect($redirecturl);
	}


}
