<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Pendingbank class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 관리자>예치금>무통장입금알림 controller 입니다.
 */
class Pendingbank extends CB_Controller
{

	/**
	 * 관리자 페이지 상의 현재 디렉토리입니다
	 * 페이지 이동시 필요한 정보입니다
	 */
	public $pagedir = 'deposit/pendingbank';

	/**
	 * 모델을 로딩합니다
	 */
	protected $models = array('Deposit');

	/**
	 * 이 컨트롤러의 메인 모델 이름입니다
	 */
	protected $modelname = 'Deposit_model';

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
		$this->load->library(array('pagination', 'querystring', 'depositlib'));
	}

	/**
	 * 목록을 가져오는 메소드입니다
	 */
	public function index()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_deposit_pendingbank_index';
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

		$view['view']['sort'] = array(
			'dep_id' => $param->sort('dep_id', 'asc'),
			'dep_pay_type' => $param->sort('dep_pay_type', 'asc'),
		);
		$findex = $this->input->get('findex') ? $this->input->get('findex') : $this->{$this->modelname}->primary_key;
		$forder = $this->input->get('forder', null, 'desc');
		$sfield = $this->input->get('sfield', null, '');
		$skeyword = $this->input->get('skeyword', null, '');

		$per_page = admin_listnum();
		$offset = ($page - 1) * $per_page;

		/**
		 * 게시판 목록에 필요한 정보를 가져옵니다.
		 */
		$this->{$this->modelname}->allow_search_field = array('deposit.mem_id', 'dep_deposit_request', 'dep_cash_request', 'dep_point', 'dep_pay_type', 'dep_content', 'dep_admin_memo', 'dep_ip', 'deposit.mem_nickname', 'member.mem_userid'); // 검색이 가능한 필드
		$this->{$this->modelname}->search_field_equal = array('deposit.mem_id', 'dep_deposit_request', 'dep_cash_request', 'dep_point', 'dep_pay_type'); // 검색중 like 가 아닌 = 검색을 하는 필드
		$this->{$this->modelname}->allow_order_field = array('dep_id', 'deposit.mem_id', 'dep_pay_type'); // 정렬이 가능한 필드

		$where = array(
			'dep_from_type' => 'cash',
			'dep_pay_type' => 'bank',
		);
		if ($this->input->get('dep_status') === 'Y') {
			$where['dep_status'] = 1;
		}
		if ($this->input->get('dep_status') === 'N') {
			$where['dep_status'] = 0;
		}

		$result = $this->{$this->modelname}
			->get_admin_list($per_page, $offset, $where, '', $findex, $forder, $sfield, $skeyword);
		if (element('list', $result)) {
			foreach (element('list', $result) as $key => $val) {
				$result['list'][$key]['display_name'] = display_username(
					element('mem_userid', $val),
					element('mem_nickname', $val)
				);
			}
		}
		$view['view']['data'] = $result;


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
		$search_option = array('dep_deposit_request' => $this->cbconfig->item('deposit_name'), 'dep_cash_request' => '총결제해야할 금액', 'dep_ip' => 'IP', 'deposit.mem_nickname' => '회원명', 'member.mem_userid' => '회원아이디');
		$view['view']['skeyword'] = ($sfield && array_key_exists($sfield, $search_option)) ? $skeyword : '';
		$view['view']['search_option'] = search_option($search_option, $sfield);
		$view['view']['listall_url'] = admin_url($this->pagedir);
		$view['view']['list_delete_url'] = admin_url($this->pagedir . '/listdelete/?' . $param->output());

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
	 * 게시판 글쓰기 또는 수정 페이지를 가져오는 메소드입니다
	 */
	public function write($pid = 0)
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_deposit_pendingbank_write';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		/**
		 * 프라이머리키에 숫자형이 입력되지 않으면 에러처리합니다
		 */
		if ($pid) {
			$pid = (int) $pid;
			if (empty($pid) OR $pid < 1) {
				show_404();
			}
		}
		$primary_key = $this->{$this->modelname}->primary_key;

		/**
		 * 수정 페이지일 경우 기존 데이터를 가져옵니다
		 */
		$getdata = array();
		if ($pid) {
			$getdata = $this->{$this->modelname}->get_one($pid);
			$getdata['member'] = $member = $this->Member_model->get_one(element('mem_id', $getdata));

			if (element('dep_status', $getdata) === '1') {
				alert('이미 완납처리한 입금내역은 더 이상 수정할 수 없습니다');
			}
		}

		/**
		 * Validation 라이브러리를 가져옵니다
		 */
		$this->load->library('form_validation');


		/**
		 * 전송된 데이터의 유효성을 체크합니다
		 */
		$config = array(
			array(
				'field' => 'dep_cash_status',
				'label' => '결제상태',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'dep_cash',
				'label' => '실제결제한 금액',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'dep_deposit_datetime',
				'label' => '결제일시',
				'rules' => 'trim',
			),
			array(
				'field' => 'dep_admin_memo',
				'label' => '관리자 메모',
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

			$view['view']['data'] = $getdata;

			/**
			 * primary key 정보를 저장합니다
			 */
			$view['view']['primary_key'] = $primary_key;

			// 이벤트가 존재하면 실행합니다
			$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

			/**
			 * 어드민 레이아웃을 정의합니다
			 */
			$layoutconfig = array('layout' => 'layout', 'skin' => 'write');
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
			 * 게시물을 수정하는 경우입니다
			 */
			if ($this->input->post($primary_key)) {
				$updatedata = array(
					'dep_admin_memo' => $this->input->post('dep_admin_memo', null, ''),
				);
				if ($this->input->post('dep_cash_status') === 'not') {
					$updatedata['dep_cash'] = 0;
					$msg = '정상적으로 수정되었습니다';
				}
				if ($this->input->post('dep_cash_status') === 'some') {
					$updatedata['dep_cash'] = (int) $this->input->post('dep_cash', null, 0);
					$msg = '정상적으로 수정되었습니다';
				}
				if ($this->input->post('dep_cash_status') === 'all') {

					$sum = $this->Deposit_model->get_deposit_sum(element('mem_id', $getdata));
					$deposit_sum = $sum + element('dep_deposit_request', $getdata);

					$updatedata['dep_deposit_datetime'] = $this->input->post('dep_deposit_datetime');
					$updatedata['dep_cash'] = element('dep_cash_request', $getdata);
					$updatedata['dep_deposit'] = element('dep_deposit_request', $getdata);
					$updatedata['dep_deposit_sum'] = $deposit_sum;
					$updatedata['dep_status'] = 1;
					$msg = '완납처리가 되었습니다';
				}
				$this->{$this->modelname}->update($this->input->post($primary_key), $updatedata);

				if ($this->input->post('dep_cash_status') === 'all' && ! element('dep_status', $getdata)) {
					$this->depositlib->update_member_deposit(element('mem_id', $getdata));
					$this->depositlib->alarm('approve_bank_to_deposit', element('dep_id', $getdata));
				}

				$this->session->set_flashdata('message', $msg);
			}

			// 이벤트가 존재하면 실행합니다
			Events::trigger('after', $eventname);

			/**
			 * 게시물의 신규입력 또는 수정작업이 끝난 후 목록 페이지로 이동합니다
			 */
			$param =& $this->querystring;
			$redirecturl = admin_url($this->pagedir . '?' . $param->output());
			redirect($redirecturl);
		}
	}

	/**
	 * 목록 페이지에서 선택삭제를 하는 경우 실행되는 메소드입니다
	 */
	public function listdelete()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_deposit_pendingbank_listdelete';
		$this->load->event($eventname);

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		/**
		 * 체크한 게시물의 삭제를 실행합니다
		 */
		if ($this->input->post('chk') && is_array($this->input->post('chk'))) {
			foreach ($this->input->post('chk') as $val) {
				if ($val) {
					$this->{$this->modelname}->delete($val);
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
}
