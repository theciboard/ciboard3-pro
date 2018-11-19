<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Depositlist class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 관리자>예치금>충전내역 controller 입니다.
 */
class Depositlist extends CB_Controller
{

	/**
	 * 관리자 페이지 상의 현재 디렉토리입니다
	 * 페이지 이동시 필요한 정보입니다
	 */
	public $pagedir = 'deposit/depositlist';

	/**
	 * 모델을 로딩합니다
	 */
	protected $models = array('Deposit', 'Unique_id');

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
		$eventname = 'event_admin_deposit_depositlist_index';
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

		$findex = $this->input->get('findex', null, 'dep_deposit_datetime');
		$forder = $this->input->get('forder', null, 'desc');
		$sfield = $this->input->get('sfield', null, '');
		$skeyword = $this->input->get('skeyword', null, '');

		$per_page = admin_listnum();
		$offset = ($page - 1) * $per_page;

		/**
		 * 게시판 목록에 필요한 정보를 가져옵니다.
		 */
		$this->{$this->modelname}->allow_search_field = array('dep_id', 'member.mem_nickname', 'deposit.mem_realname', 'member.mem_userid', 'dep_content', 'dep_deposit', 'dep_cash', 'dep_admin_memo', 'dep_ip', 'dep_point', 'member.mem_id'); // 검색이 가능한 필드
		$this->{$this->modelname}->search_field_equal = array('member.mem_id', 'dep_id', 'dep_deposit', 'dep_cash', 'dep_point'); // 검색중 like 가 아닌 = 검색을 하는 필드
		$this->{$this->modelname}->allow_order_field = array('dep_id', 'dep_deposit_datetime'); // 정렬이 가능한 필드

		$where = array('dep_status' => 1);
		$view['view']['deptype'] = $deptype = $this->depositlib->deptype;
		if ($this->input->get('dep_from_type') && array_key_exists($this->input->get('dep_from_type'), $deptype)) {
			$where['dep_from_type'] = $this->input->get('dep_from_type');
		}
		if ($this->input->get('dep_to_type') && array_key_exists($this->input->get('dep_to_type'), $deptype)) {
			$where['dep_to_type'] = $this->input->get('dep_to_type');
		}
		if ($this->input->get('dep_pay_type')) {
			$where['dep_pay_type'] = $this->input->get('dep_pay_type');
		}

		$result = $this->{$this->modelname}
			->get_admin_list($per_page, $offset, $where, '', $findex, $forder, $sfield, $skeyword);
		$list_num = $result['total_rows'] - ($page - 1) * $per_page;
		if (element('list', $result)) {
			foreach (element('list', $result) as $key => $val) {
				$result['list'][$key]['display_name'] = display_username(
					element('mem_userid', $val),
					element('mem_nickname', $val),
					element('mem_icon', $val)
				);
				$result['list'][$key]['dep_type_display'] = element(element('dep_from_type', $val), $deptype) . '=>' . element(element('dep_to_type', $val), $deptype);
				$result['list'][$key]['num'] = $list_num--;
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
		$search_option = array('member.mem_nickname' => '회원명', 'deposit.mem_realname' => '회원실명', 'member.mem_userid' => '회원아이디', 'dep_content' => '내용', 'dep_deposit' => '예치금', 'dep_cash' => '결제금액', 'dep_point' => '포인트');
		$view['view']['skeyword'] = ($sfield && array_key_exists($sfield, $search_option)) ? $skeyword : '';
		$view['view']['search_option'] = search_option($search_option, $sfield);
		$view['view']['listall_url'] = admin_url($this->pagedir);
		$view['view']['write_url'] = admin_url($this->pagedir . '/write');

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
		$eventname = 'event_admin_deposit_depositlist_write';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		$view['view']['deptype'] = $deptype = $this->depositlib->deptype;
		$view['view']['paymethodtype'] = $paymethodtype = $this->depositlib->paymethodtype;

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
				'field' => 'dep_content',
				'label' => '내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'dep_admin_memo',
				'label' => '관리자 메모',
				'rules' => 'trim',
			),
		);
		if ($this->input->post($primary_key)) {

		} else {
			$config[] = array(
				'field' => 'mem_userid',
				'label' => '회원아이디',
				'rules' => 'trim|required',
			);
			$config[] = array(
				'field' => 'dep_deposit',
				'label' => '예치금',
				'rules' => 'trim|numeric|callback__deposit_sum_check',
			);
		}
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
			$writeskin = $pid ? 'modify' : 'write';
			$layoutconfig = array('layout' => 'layout', 'skin' => $writeskin);
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
					'dep_content' => $this->input->post('dep_content', null, ''),
					'dep_admin_memo' => $this->input->post('dep_admin_memo', null, ''),
				);
				$this->{$this->modelname}->update($this->input->post($primary_key), $updatedata);
				$this->session->set_flashdata(
					'message',
					'정상적으로 수정되었습니다'
				);
			} else {
				/**
				 * 게시물을 새로 입력하는 경우입니다
				 */
				$mb = $this->Member_model->get_by_userid($this->input->post('mem_userid'), 'mem_id, mem_nickname');
				$mem_id = element('mem_id', $mb);

				$sum = $this->Deposit_model->get_deposit_sum($mem_id);
				$deposit_sum = $sum + $this->input->post('dep_deposit', null, 0);

				$dep_id = $this->Unique_id_model->get_id($this->input->ip_address());

				if ($this->input->post('dep_deposit') > 0) {
					$dep_from_type = 'service';
					$dep_to_type = 'deposit';
					$dep_pay_type = 'service';
				} else {
					$dep_from_type = 'deposit';
					$dep_to_type = 'service';
					$dep_pay_type = '';
				}

				$dep_deposit = $this->input->post('dep_deposit') ? $this->input->post('dep_deposit') : 0;
				$insertdata = array(
					'dep_id' => $dep_id,
					'mem_id' => $mem_id,
					'mem_nickname' => element('mem_nickname', $mb),
					'dep_from_type' => $dep_from_type,
					'dep_to_type' => $dep_to_type,
					'dep_pay_type' => $dep_pay_type,
					'dep_deposit_request' => $dep_deposit,
					'dep_deposit' => $dep_deposit,
					'dep_deposit_sum' => $deposit_sum,
					'dep_content' => $this->input->post('dep_content', null, ''),
					'dep_admin_memo' => $this->input->post('dep_admin_memo', null, ''),
					'dep_datetime' => cdate('Y-m-d H:i:s'),
					'dep_deposit_datetime' => cdate('Y-m-d H:i:s'),
					'dep_ip' => $this->input->ip_address(),
					'dep_useragent' => $this->agent->agent_string(),
					'dep_status' => 1,
				);
				$this->{$this->modelname}->insert($insertdata);
				$this->session->set_flashdata(
					'message',
					'정상적으로 수정되었습니다'
				);
			}

			$this->depositlib->update_member_deposit($mem_id);

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
	 * 금액이 올바르게 입력되었는지를 체크합니다
	 */
	public function _deposit_sum_check($deposit)
	{
		is_numeric($deposit) OR $deposit = 0;
		$deposit = (int) $deposit;

		$mb = $this->Member_model->get_by_userid($this->input->post('mem_userid'), 'mem_id, mem_denied');
		if ( ! element('mem_id', $mb)) {
			$this->form_validation->set_message(
				'_deposit_sum_check',
				$this->input->post('mem_userid') . ' 은 존재하지 않는 회원아이디입니다'
			);
			return false;
		}
		if (element('mem_denied', $mb)) {
			$this->form_validation->set_message(
				'_deposit_sum_check',
				$this->input->post('mem_userid') . ' 은 탈퇴, 차단된 회원아이디입니다'
			);
			return false;
		}

		if ($deposit === 0) {
			$this->form_validation->set_message(
				'_deposit_sum_check',
				'예치금 변동 금액을 양수 또는 음수로 입력하여 주십시오'
			);
			return false;
		}

		$sum = $this->Deposit_model->get_deposit_sum(element('mem_id', $mb));
		$deposit_sum = $sum + $deposit;

		if ($deposit_sum < 0) {
			$this->form_validation->set_message(
				'_deposit_sum_check',
				' 회원님의 총 잔액이 0 보다 작아지므로 진행할 수 없습니다'
			);
			return false;
		}

		return true;
	}
}
