<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Smssend class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 관리자>SMS 설정>문자보내기 controller 입니다.
 */
class Smssend extends CB_Controller
{

	/**
	 * 관리자 페이지 상의 현재 디렉토리입니다
	 * 페이지 이동시 필요한 정보입니다
	 */
	public $pagedir = 'sms/smssend';

	/**
	 * 모델을 로딩합니다
	 */
	protected $models = array('Sms_member', 'Sms_member_group', 'Sms_favorite');

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
		$this->load->library(array('querystring', 'smslib'));
	}


	/**
	 * SMS 설정>문자보내기 페이지입니다
	 */
	public function index()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_sms_smssend_index';
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
				'field' => 'sfa_content',
				'label' => '메세지 내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'send_list',
				'label' => '받는이 목록',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'ssc_send_phone',
				'label' => '회신번호',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'phone_booking',
				'label' => '예약',
				'rules' => 'trim',
			),
			array(
				'field' => 'book_year',
				'label' => '예약 (년)',
				'rules' => 'trim',
			),
			array(
				'field' => 'book_month',
				'label' => '예약 (월)',
				'rules' => 'trim',
			),
			array(
				'field' => 'book_day',
				'label' => '예약 (일)',
				'rules' => 'trim',
			),
			array(
				'field' => 'book_hour',
				'label' => '예약 (시)',
				'rules' => 'trim',
			),
			array(
				'field' => 'book_minute',
				'label' => '예약 (분)',
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

		} else {
			/**
			 * 유효성 검사를 통과한 경우입니다.
			 * 즉 데이터의 insert 나 update 의 process 처리가 필요한 상황입니다
			 */

			// 이벤트가 존재하면 실행합니다
			$view['view']['event']['formruntrue'] = Events::trigger('formruntrue', $eventname);

			$list = array();
			$phones = array();

			$send_list = explode('/', $this->input->post('send_list'));
			$chk_overlap = 1; // 중복번호를 체크함
			$overlap = 0;
			$duplicate_data = array();
			$duplicate_data['phone'] = array();
			$str_serialize = '';
			while ($row = array_shift($send_list)) {
				$item = explode(',', $row);

				for ($i =1, $max = count($item); $i <$max; $i++) {
					if ( ! trim($item[$i])) {
						continue;
					}

					switch ($item[0]) {
						case 'g': // 그룹전송

							$where = array(
								'smg_id' => $item[1],
								'sme_receive' => 1,
							);
							$data = $this->Sms_member_model->get('', '', $where);
							if ($data) {
								foreach ($data as $row) {
									$row['phone'] = get_phone($row['sme_phone'], 0);
									$row['name'] = element('sme_name', $row);
									$row['mem_id'] = element('mem_id', $row);
									if ($chk_overlap && array_overlap($phones, $row['phone'])) {
										$overlap++;
										array_push($duplicate_data['phone'], $row['phone']);
										continue;
									}
									array_push($list, $row);
									array_push($phones, $row['phone']);
								}
							}

							break;

						case 'h': // 전화번호 직적입력

							$item[$i] = explode(':', $item[$i]);
							$phone = get_phone($item[$i][1], 0);
							$name = $item[$i][0];

							if ($chk_overlap && array_overlap($phones, $phone)) {
								$overlap++;
								array_push($duplicate_data['phone'], $phone);
								continue;
							}
							array_push($list, array('phone' => $phone, 'name' => $name));
							array_push($phones, $phone);

							break;

						case 'p': // 개인 선택

							$where = array(
								'sme_id' => $item[$i],
								'sme_receive' => 1,
							);
							$row = $this->Sms_member_model->get_one('', '', $where);
							$row['phone'] = get_phone($row['sme_phone'], 0);
							$row['name'] = element('sme_name', $row);
							$row['mem_id'] = element('mem_id', $row);

							if ($chk_overlap && array_overlap($phones, $row['phone'])) {
								$overlap++;
								array_push($duplicate_data['phone'], $row['phone']);
								continue;
							}
							array_push($list, $row);
							array_push($phones, $row['phone']);

							break;

					}
				}
			}

			if ( count($duplicate_data['phone'])) { //중복된 번호가 있다면
				$duplicate_data['total'] = $overlap;
				$str_serialize = serialize($duplicate_data);
			}

			$sme_total = count($list);

			// 예약전송
			if ($this->input->post('book_year')
				&& $this->input->post('book_month')
				&& $this->input->post('book_day')
				&& $this->input->post('book_hour')
				&& $this->input->post('book_minute')) {

				$ssc_booking = $this->input->post('book_year') . '-' . $this->input->post('book_month') . '-' . $this->input->post('book_day') . ' '
					. $this->input->post('book_hour') . ' : ' . $this->input->post('book_minute');
				$booking = $this->input->post('book_year') . $this->input->post('book_month') . $this->input->post('book_day')
					. $this->input->post('book_hour') . $this->input->post('book_minute');

			} else {
				$ssc_booking = '';
				$booking = '';
			}

			$sender = array(
				'mem_id' => $this->member->item('mem_id'),
				'name' => $this->member->item('mem_nickname'),
				'phone' => $this->input->post('ssc_send_phone', null, ''),
			);
			$this->load->library('smslib');
			$smsresult = $this->smslib->send($list, $sender, $this->input->post('sfa_content', null, ''), $booking, $smstype = '관리자페이지문자발송');

			$result = json_decode($smsresult, true);

			$view['view']['alert_message'] = element('message', $result);
		}


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
	 * 자주보내는 문자관리
	 */
	public function ajax_sms_write_form()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_sms_smssend_ajax_sms_write_form';
		$this->load->event($eventname);

		$this->output->set_content_type('application/json');

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		/**
		 * 페이지에 숫자가 아닌 문자가 입력되거나 1보다 작은 숫자가 입력되면 에러 페이지를 보여줍니다.
		 */
		$param =& $this->querystring;
		$page = (((int) $this->input->get('page')) > 0) ? ((int) $this->input->get('page')) : 1;
		$findex = $this->input->get('findex') ? $this->input->get('findex') : $this->Sms_favorite_model->primary_key;
		$forder = $this->input->get('forder', null, 'desc');
		$sfield = $this->input->get('sfield', null, '');
		$skeyword = $this->input->get('skeyword', null, '');

		$per_page = 6;
		$offset = ($page - 1) * $per_page;

		/**
		 * 게시판 목록에 필요한 정보를 가져옵니다.
		 */
		$this->Sms_favorite_model->allow_search_field = array('sfa_id', 'sfa_title', 'sfa_content'); // 검색이 가능한 필드
		$this->Sms_favorite_model->search_field_equal = array('sfa_id'); // 검색중 like 가 아닌 = 검색을 하는 필드
		$this->Sms_favorite_model->allow_order_field = array('sfa_id'); // 정렬이 가능한 필드
		$result = $this->Sms_favorite_model
			->get_admin_list($per_page, $offset, '', '', $findex, $forder, $sfield, $skeyword);
		$list_text = '';
		if (element('list', $result)) {
			foreach (element('list', $result) as $key => $val) {
				$list_text .= '<div class="px170 pr20 pull-left">
					<div class="thumbnail" style="height:160px;"><textarea readonly="readonly" class="form-control" rows="6" id="sfa_contents_' . $val['sfa_id'] . '" onclick="emoticon_list.go(' . $val['sfa_id'] . ')">' . $val['sfa_content'] . '</textarea></div>
						<p>' . cut_str($val['sfa_title'], 10) . '</p>
				</div>';
			}
		}
		if (empty($list_text)) {
			$list_text .= '<div class="px200 pr20 pull-left">데이터가 없습니다.</div>';
		}

		$arr_ajax_msg['error'] = '';
		$arr_ajax_msg['list_text'] = $list_text;
		$arr_ajax_msg['page'] = $page;
		$arr_ajax_msg['total_count'] = $result['total_rows'];
		$arr_ajax_msg['total_page'] = ceil($result['total_rows']/$per_page);

		exit(json_encode($arr_ajax_msg));
	}

	public function ajax_sms_write_group()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_sms_smssend_ajax_sms_write_group';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		$countwhere = array(
			'smg_id' => 0,
			'sme_receive' => 1,
			'sme_phone <>' => '',
		);
		$receive_num = $this->Sms_member_model->count_by($countwhere);
		$result_no_group[0] = array(
			'smg_id' => 0,
			'smg_name' => '미분류',
			'smg_order' => 0,
			'receive_num' => $receive_num,
		);

		$result = $this->Sms_member_group_model->get('', '', '', '', '', $findex = 'smg_order', $forder = 'ASC');
		if ($result) {
			foreach ($result as $key => $val) {
				$countwhere = array(
					'smg_id' => element('smg_id', $val),
					'sme_receive' => 1,
					'sme_phone <>' => '',
				);
				$result[$key]['receive_num'] = $this->Sms_member_model->count_by($countwhere);
			}
		}
		$view['view']['data_no_group'] = $result_no_group;
		$view['view']['data'] = $result;

		/**
		 * primary key 정보를 저장합니다
		 */
		$view['view']['primary_key'] = $this->Sms_member_group_model->primary_key;

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 어드민 레이아웃을 정의합니다
		 */
		$this->data = $view;
		$this->view = 'admin/' . ADMIN_SKIN . '/' . $this->pagedir . '/ajax_sms_write_group';
	}

	public function ajax_sms_write_person()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_sms_smssend_ajax_sms_write_person';
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
		$findex = $this->input->get('findex') ? $this->input->get('findex') : $this->Sms_member_model->primary_key;
		$forder = $this->input->get('forder', null, 'desc');
		$sfield = $this->input->get('sfield', null, '');
		$skeyword = $this->input->get('skeyword', null, '');

		$per_page = 6;
		$offset = ($page - 1) * $per_page;

		/**
		 * 게시판 목록에 필요한 정보를 가져옵니다.
		 */
		$this->Sms_member_model->allow_search_field = array('sme_id', 'sme_name', 'sme_phone'); // 검색이 가능한 필드
		$this->Sms_member_model->search_field_equal = array('sme_id'); // 검색중 like 가 아닌 = 검색을 하는 필드
		$this->Sms_member_model->allow_order_field = array('sme_id'); // 정렬이 가능한 필드

		$where = array();
		if ($this->input->get('smg_id') === 'n') {
			$where['sms_member.smg_id'] = 0;
		} elseif ($this->input->get('smg_id')) {
			$where['sms_member.smg_id'] = $this->input->get('smg_id');
		}
		$result = $this->Sms_member_model
			->get_admin_list($per_page, $offset, $where, '', $findex, $forder, $sfield, $skeyword);

		$view['view']['data'] = $result;
		$view['view']['group'] = $this->Sms_member_group_model->get('', '', '', '', 0, 'smg_order', 'ASC');

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 어드민 레이아웃을 정의합니다
		 */
		$this->data = $view;
		$this->view = 'admin/' . ADMIN_SKIN . '/' . $this->pagedir . '/ajax_sms_write_person';
	}
}
