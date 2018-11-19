<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Deposit class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 예치금 페이지를 보여주는 controller 입니다.
 */
class Deposit extends CB_Controller
{

	/**
	 * 모델을 로딩합니다
	 */
	protected $models = array();

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

		if ( ! $this->cbconfig->item('use_deposit')) {
			alert('이 웹사이트는 ' . html_escape($this->cbconfig->item('deposit_name')) . ' 기능을 사용하지 않습니다');
			return;
		}
	}


	/**
	 * 예치금 페이지를 보여주는 함수입니다
	 */
	public function index()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_deposit_index';
		$this->load->event($eventname);

		/**
		 * 로그인이 필요한 페이지입니다
		 */
		required_user_login();

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		$cashtodep = array();
		$cashtodeposit = $this->cbconfig->item('deposit_cash_to_deposit_unit');
		$cashtodeposit = preg_replace("/[\r|\n|\r\n]+/", ',', $cashtodeposit);
		$cashtodeposit = preg_replace("/\s+/", '', $cashtodeposit);
		if ($cashtodeposit) {
			$cashtodeposit = explode(',', trim($cashtodeposit, ','));
			$cashtodeposit = array_unique($cashtodeposit);
			if ($cashtodeposit) {
				foreach ($cashtodeposit as $key => $val) {
					$cashtodep[] = explode(':', $val);
				}
			}
		}
		if (empty($cashtodep)) {
			alert('충전 목록이 존재하지 않습니다');
			return;
		}
		$view['view']['cashtodep'] = $cashtodep;

		$this->load->model('Unique_id_model');
		$unique_id = $this->Unique_id_model->get_id($this->input->ip_address());
		$view['view']['unique_id'] = $unique_id;
		$view['view']['good_name'] = $this->cbconfig->item('deposit_name') . ' 충전';
		$this->session->set_userdata(
			'unique_id',
			$unique_id
		);

		$view['view']['use_pg'] = $use_pg = false;
		if ($this->cbconfig->item('use_payment_card')
			OR $this->cbconfig->item('use_payment_realtime')
			OR $this->cbconfig->item('use_payment_vbank')
			OR $this->cbconfig->item('use_payment_phone')) {
			$view['view']['use_pg'] = $use_pg = true;
		}

		if ($this->cbconfig->item('use_payment_pg') === 'kcp' && $use_pg) {
			$this->load->library('paymentlib');
			$view['view']['pg'] = $this->paymentlib->kcp_init();
			/*	 //삭제예정
			if ($this->cbconfig->get_device_type() !== 'mobile') {
				$view['view']['body_script'] = 'onLoad="CheckPayplusInstall();"';
			}
			*/
		}

		if ($this->cbconfig->item('use_payment_pg') === 'lg' && $use_pg) {
			$this->load->library('paymentlib');
			$view['view']['pg'] = $this->paymentlib->lg_init();
			/*	 //삭제예정
			if ($this->cbconfig->get_device_type() !== 'mobile') {
				$view['view']['body_script'] = 'onload="isActiveXOK();"';
			}
			*/
		}

		if ($this->cbconfig->item('use_payment_pg') === 'inicis' && $use_pg) {
			$this->load->library('paymentlib');
			$view['view']['pg'] = $this->paymentlib->inicis_init('deposit');
			/*	 //삭제예정
			if ($this->cbconfig->get_device_type() !== 'mobile') {
				$view['view']['body_script'] = 'onload="enable_click();"';
			}
			*/
		}

		$view['view']['ptype'] = 'deposit';

		$view['view']['form1name'] = ($this->cbconfig->get_device_type() === 'mobile')
			? 'mform_1' : 'form_1';
		$view['view']['form2name'] = ($this->cbconfig->get_device_type() === 'mobile')
			? 'mform_2' : 'form_2';
		$view['view']['form3name'] = ($this->cbconfig->get_device_type() === 'mobile')
			? 'mform_3' : 'form_3';
		$view['view']['form4name'] = ($this->cbconfig->get_device_type() === 'mobile')
			? 'mform_4' : 'form_4';

		$where = array(
			'mem_id' => $this->member->item('mem_id'),
			'dep_status' => 1,
		);
		$this->load->model('Deposit_model');
		$view['view']['list'] = $this->Deposit_model
			->get('', '', $where, '7', 0, 'dep_id', 'DESC');
		$view['view']['count'] = $this->Deposit_model->count_by($where);

		$view['view']['canonical'] = site_url('deposit');

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 레이아웃을 정의합니다
		 */
		$page_title = $this->cbconfig->item('site_meta_title_deposit');
		$meta_description = $this->cbconfig->item('site_meta_description_deposit');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_deposit');
		$meta_author = $this->cbconfig->item('site_meta_author_deposit');
		$page_name = $this->cbconfig->item('site_page_name_deposit');

		$layoutconfig = array(
			'path' => 'deposit',
			'layout' => 'layout',
			'skin' => 'deposit',
			'layout_dir' => $this->cbconfig->item('layout_deposit'),
			'mobile_layout_dir' => $this->cbconfig->item('mobile_layout_deposit'),
			'use_sidebar' => $this->cbconfig->item('sidebar_deposit'),
			'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_deposit'),
			'skin_dir' => $this->cbconfig->item('skin_deposit'),
			'mobile_skin_dir' => $this->cbconfig->item('mobile_skin_deposit'),
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
	 * 예치금 나의 사용 내역을 보여주는 함수입니다
	 */
	public function mylist()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_deposit_mylist';
		$this->load->event($eventname);

		/**
		 * 로그인이 필요한 페이지입니다
		 */
		required_user_login();

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		$this->load->model('Deposit_model');

		/**
		 * 페이지에 숫자가 아닌 문자가 입력되거나 1보다 작은 숫자가 입력되면 에러 페이지를 보여줍니다.
		 */
		$param =& $this->querystring;
		$page = (((int) $this->input->get('page')) > 0) ? ((int) $this->input->get('page')) : 1;
		$findex = $this->Deposit_model->primary_key;
		$forder = 'desc';

		$per_page = $this->cbconfig->item('list_count') ? (int) $this->cbconfig->item('list_count') : 20;
		$offset = ($page - 1) * $per_page;

		/**
		 * 게시판 목록에 필요한 정보를 가져옵니다.
		 */
		$where = array(
			'mem_id' => $this->member->item('mem_id'),
			'dep_status' => 1,
		);
		$result = $this->Deposit_model
			->get_list($per_page, $offset, $where, '', $findex, $forder);
		$list_num = $result['total_rows'] - ($page - 1) * $per_page;
		if (element('list', $result)) {
			foreach (element('list', $result) as $key => $val) {
				$result['list'][$key]['num'] = $list_num--;
			}
		}
		$view['view']['data'] = $result;

		/**
		 * 페이지네이션을 생성합니다
		 */
		$config['base_url'] = site_url('deposit/mylist') . '?' . $param->replace('page');
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
		$page_title = $this->cbconfig->item('site_meta_title_deposit_mylist');
		$meta_description = $this->cbconfig->item('site_meta_description_deposit_mylist');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_deposit_mylist');
		$meta_author = $this->cbconfig->item('site_meta_author_deposit_mylist');
		$page_name = $this->cbconfig->item('site_page_name_deposit_mylist');

		$layoutconfig = array(
			'path' => 'deposit',
			'layout' => 'layout',
			'skin' => 'mylist',
			'layout_dir' => $this->cbconfig->item('layout_deposit'),
			'mobile_layout_dir' => $this->cbconfig->item('mobile_layout_deposit'),
			'use_sidebar' => $this->cbconfig->item('sidebar_deposit'),
			'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_deposit'),
			'skin_dir' => $this->cbconfig->item('skin_deposit'),
			'mobile_skin_dir' => $this->cbconfig->item('mobile_skin_deposit'),
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

	public function inicisweb(){
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_deposit_inicis_pc_pay';
		$this->load->event($eventname);

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		$this->load->library(array('paymentlib'));
		$init = $this->paymentlib->inicis_init('deposit');

		if( 'inicis' !== $this->cbconfig->item('use_payment_pg') ){
			die(json_encode(array('error'=>'올바른 방법으로 이용해 주십시오.')));
		}

		$request_mid = $this->input->post('mid', null, '');
		$session_order_num = $this->session->userdata('unique_id');

		if( ($request_mid != element('pg_inicis_mid', $init)) || ! $session_order_num ){
			alert("잘못된 요청입니다.");
		}

		$orderNumber = $this->input->post('orderNumber', true, 0);

		if( !$orderNumber ){
			alert("주문번호가 없습니다.");
		}

		$this->load->model('Payment_order_data_model');
		$row = $this->Payment_order_data_model->get_one($orderNumber);
		$params = array();
		$data = cmall_tmp_replace_data($row['pod_data']);

		if( !$data ){
			alert("임시 주문 정보가 저장되지 않았습니다. \\n 다시 실행해 주세요.");
		}

		foreach($data as $key=>$value) {
			if(is_array($value)) {
				foreach($value as $k=>$v) {
					$_POST[$key][$k] = $params[$key][$k] = $v;
				}
			} else {
				$_POST[$key] = $params[$key] = $value;
			}
		}

		// 이벤트가 존재하면 실행합니다
		Events::trigger('after', $eventname);

		$this->update();
	}

	/**
	 * 결제 업데이트 함수입니다
	 */
	public function update($agent_type='')
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_deposit_update';
		$this->load->event($eventname);

		$pay_type = $this->input->post('pay_type', null, '');

		/**
		 * 로그인이 필요한 페이지입니다
		 */
		required_user_login();

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		if ( ! $this->cbconfig->item('use_deposit_cash_to_deposit')) {
			alert('충전이 불가능합니다. 관리자에게 문의하여 주십시오');
		}

		if ( 'bank' != $pay_type && $this->cbconfig->item('use_payment_pg') === 'lg'
			&& ! $this->input->post('LGD_PAYKEY')) {
			alert('결제등록 요청 후 주문해 주십시오');
		}

		if ( ! $this->session->userdata('unique_id') OR ! $this->input->post('unique_id')
			OR $this->session->userdata('unique_id') !== $this->input->post('unique_id')) {
			alert('잘못된 접근입니다');
		}

		$this->load->library('paymentlib');

		$moneyreal = (int) $this->input->post('money_value');
		$depositreal = (int) $this->input->post('deposit_real');
		$mem_realname = $this->input->post('mem_realname', null, '');

		$cashtodep = array();
		$reallist = false;
		$cashtodeposit = $this->cbconfig->item('deposit_cash_to_deposit_unit');
		$cashtodeposit = preg_replace("/[\r|\n|\r\n]+/", ',', $cashtodeposit);
		$cashtodeposit = preg_replace("/\s+/", '', $cashtodeposit);
		if ($cashtodeposit) {
			$cashtodeposit = explode(',', trim($cashtodeposit, ','));
			$cashtodeposit = array_unique($cashtodeposit);
			if ($cashtodeposit) {
				foreach ($cashtodeposit as $key => $val) {
					$exp = explode(':', $val);
					if ($moneyreal == $exp[0] && $depositreal == $exp[1]) {
						$reallist = true;
					}
				}
			}
		}
		if ($reallist === false) {
			alert('충전 목록이 존재하지 않습니다');
		}

		$insertdata = array();
		$result = '';

		if ($pay_type === 'bank') {
			$insertdata['dep_datetime'] = date('Y-m-d H:i:s');
			$insertdata['dep_deposit_datetime'] = null;
			$insertdata['dep_deposit_request'] = $depositreal;
			$insertdata['dep_deposit'] = 0;
			$insertdata['mem_realname'] = $mem_realname;
			$insertdata['dep_cash_request'] = $moneyreal;
			$insertdata['dep_cash'] = 0;
			$insertdata['dep_status'] = 0;
			$insertdata['dep_content'] = $this->cbconfig->item('deposit_name') . ' 적립 (무통장입금)';

		} elseif ($pay_type === 'realtime') {
			if ($this->cbconfig->item('use_payment_pg') === 'kcp') {
				$result = $this->paymentlib->kcp_pp_ax_hub();
			} elseif ($this->cbconfig->item('use_payment_pg') === 'lg') {
				$result = $this->paymentlib->xpay_result();
			} elseif ($this->cbconfig->item('use_payment_pg') === 'inicis') {
				$result = $this->paymentlib->inipay_result($agent_type);
			}

			$insertdata['dep_tno'] = element('tno', $result);
			$insertdata['dep_datetime'] = date('Y-m-d H:i:s');
			$insertdata['dep_deposit_datetime'] = preg_replace(
				"/([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})/",
				"\\1-\\2-\\3 \\4:\\5:\\6",
				element('app_time', $result)
			);
			$insertdata['dep_deposit_request'] = $depositreal;
			$insertdata['dep_deposit'] = $depositreal;
			$insertdata['dep_cash_request'] = element('amount', $result);
			$insertdata['dep_cash'] = element('amount', $result);
			$insertdata['dep_status'] = 1;
			$insertdata['mem_realname'] = $mem_realname;
			$insertdata['dep_pg'] = $this->cbconfig->item('use_payment_pg');
			$insertdata['dep_content'] = $this->cbconfig->item('deposit_name') . ' 적립 (실시간계좌이체)';

		 } elseif ($pay_type === 'vbank') {

			if ($this->cbconfig->item('use_payment_pg') === 'kcp') {
				$result = $this->paymentlib->kcp_pp_ax_hub();
			} elseif ($this->cbconfig->item('use_payment_pg') === 'lg') {
				$result = $this->paymentlib->xpay_result();
			} elseif ($this->cbconfig->item('use_payment_pg') === 'inicis') {
				$result = $this->paymentlib->inipay_result($agent_type);
			}

			$insertdata['dep_tno'] = element('tno', $result);
			$insertdata['dep_datetime'] = date('Y-m-d H:i:s');
			$insertdata['dep_deposit_request'] = $depositreal;
			$insertdata['dep_deposit'] = 0;
			$insertdata['dep_cash_request'] = element('amount', $result);
			$insertdata['dep_cash'] = 0;
			$insertdata['dep_status'] = 0;
			$insertdata['mem_realname'] = element('depositor', $result);
			$insertdata['dep_vbank_expire'] = element('cor_vbank_expire', $result) ? date("Y-m-d", strtotime(element('cor_vbank_expire', $result))) : '0000-00-00 00:00:00';
			$insertdata['dep_bank_info'] = element('bankname', $result) . ' ' . element('account', $result);
			$insertdata['dep_pg'] = $this->cbconfig->item('use_payment_pg');
			$insertdata['dep_content'] = $this->cbconfig->item('deposit_name') . ' 적립 (가상계좌)';

		} elseif ($pay_type === 'phone') {

			if ($this->cbconfig->item('use_payment_pg') === 'kcp') {
				$result = $this->paymentlib->kcp_pp_ax_hub();
			} elseif ($this->cbconfig->item('use_payment_pg') === 'lg') {
				$result = $this->paymentlib->xpay_result();
			} elseif ($this->cbconfig->item('use_payment_pg') === 'inicis') {
				$result = $this->paymentlib->inipay_result($agent_type);
			}

			$insertdata['dep_tno'] = element('tno', $result);
			$insertdata['dep_app_no'] = element('commid', $result) . ' ' . element('mobile_no', $result);
			$insertdata['dep_datetime'] = date('Y-m-d H:i:s');
			$insertdata['dep_deposit_datetime'] = preg_replace(
				"/([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})/",
				"\\1-\\2-\\3 \\4:\\5:\\6",
				element('app_time', $result)
			);
			$insertdata['dep_deposit_request'] = $depositreal;
			$insertdata['dep_deposit'] = $depositreal;
			$insertdata['dep_cash_request'] = element('amount', $result);
			$insertdata['dep_cash'] = element('amount', $result);
			$insertdata['dep_status'] = 1;
			$insertdata['mem_realname'] = $mem_realname;
			$insertdata['dep_pg'] = $this->cbconfig->item('use_payment_pg');
			$insertdata['dep_content'] = $this->cbconfig->item('deposit_name') . ' 적립 (핸드폰결제)';

		} elseif ($pay_type === 'card') {

			if ($this->cbconfig->item('use_payment_pg') === 'kcp') {
				$result = $this->paymentlib->kcp_pp_ax_hub();
			} elseif ($this->cbconfig->item('use_payment_pg') === 'lg') {
				$result = $this->paymentlib->xpay_result();
			} elseif ($this->cbconfig->item('use_payment_pg') === 'inicis') {
				$result = $this->paymentlib->inipay_result($agent_type);
			}

			$insertdata['dep_tno'] = element('tno', $result);
			$insertdata['dep_app_no'] = element('app_no', $result);
			$insertdata['dep_datetime'] = date('Y-m-d H:i:s');
			$insertdata['dep_deposit_datetime'] = preg_replace(
				"/([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})/",
				"\\1-\\2-\\3 \\4:\\5:\\6",
				element('app_time', $result)
			);
			$insertdata['dep_deposit_request'] = $depositreal;
			$insertdata['dep_deposit'] = $depositreal;
			$insertdata['dep_cash_request'] = element('amount', $result);
			$insertdata['dep_cash'] = element('amount', $result);
			$insertdata['dep_bank_info'] = element('card_name', $result);
			$insertdata['dep_status'] = 1;
			$insertdata['mem_realname'] = $mem_realname;
			$insertdata['dep_pg'] = $this->cbconfig->item('use_payment_pg');
			$insertdata['dep_content'] = $this->cbconfig->item('deposit_name') . ' 적립 (신용카드결제)';

		} else {
			alert('결제 수단이 잘못 입력되었습니다');
		}

		// 주문금액과 결제금액이 일치하는지 체크
		if (element('tno', $result) && (int) element('amount', $result) !== $moneyreal) {
			if ($this->cbconfig->item('use_payment_pg') === 'kcp') {
				$this->paymentlib->kcp_pp_ax_hub_cancel($result);
			} elseif ($this->cbconfig->item('use_payment_pg') === 'lg') {
				$this->paymentlib->xpay_cancel($result);
			} elseif ($this->cbconfig->item('use_payment_pg') === 'inicis') {
				$this->paymentlib->inipay_cancel($result);
			}
			alert('결제가 완료되지 않았습니다. 다시 시도해주십시오', site_url('deposit'));
		}

		// 정보 입력
		$dep_id = $this->session->userdata('unique_id');
		$insertdata['dep_id'] = $dep_id;
		$insertdata['mem_id'] = $this->member->item('mem_id');
		$insertdata['mem_nickname'] = $this->member->item('mem_nickname');
		$insertdata['mem_email'] = $this->input->post('mem_email', null, '');
		$insertdata['mem_phone'] = $this->input->post('mem_phone', null, '');
		$insertdata['dep_from_type'] = 'cash';
		$insertdata['dep_to_type'] = 'deposit';
		$insertdata['dep_pay_type'] = $pay_type;
		$insertdata['dep_ip'] = $this->input->ip_address();
		$insertdata['dep_useragent'] = $this->agent->agent_string();
		$insertdata['is_test'] = $this->cbconfig->item('use_pg_test');

		$this->load->model('Deposit_model');
		$res = $this->Deposit_model->insert($insertdata);
		if ( ! $res) {
			if ($pay_type !== 'bank') {
				if ($this->cbconfig->item('use_payment_pg') === 'kcp') {
					$this->paymentlib->kcp_pp_ax_hub_cancel($result);
				} elseif ($this->cbconfig->item('use_payment_pg') === 'lg') {
					$this->paymentlib->xpay_cancel($result);
				} elseif ($this->cbconfig->item('use_payment_pg') === 'inicis') {
					$this->paymentlib->inipay_cancel($result);
				}
			}
			alert('결제가 완료되지 않았습니다. 다시 시도해주십시오', site_url('deposit'));
		}

		if ($pay_type === 'bank') {
			$this->depositlib->alarm('bank_to_deposit', $dep_id);
		} elseif ($pay_type !== 'bank') {
			$this->depositlib->alarm('cash_to_deposit', $dep_id);
		}

		//회원의 예치금 업데이트 합니다.
		$this->depositlib->update_member_deposit( $this->member->item('mem_id') );

		// 이벤트가 존재하면 실행합니다
		Events::trigger('after', $eventname);

		$this->session->set_userdata('unique_id', '');

		redirect('deposit/result/' . $dep_id);
	}


	/**
	 * 결제 후 결과 페이지입니다
	 */
	public function result($dep_id = 0)
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_deposit_result';
		$this->load->event($eventname);

		/**
		 * 로그인이 필요한 페이지입니다
		 */
		required_user_login();

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		if (empty($dep_id) OR $dep_id < 1) {
			alert('잘못된 접근입니다');
		}

		$this->load->model('Deposit_model');

		$deposit = $this->Deposit_model->get_one($dep_id);

		if ( ! element('dep_id', $deposit)) {
			alert('잘못된 접근입니다');
		}

		if ((int) element('mem_id', $deposit) !== (int) $this->member->item('mem_id')) {
			alert('잘못된 접근입니다');
		}

		$view['view']['data'] = $deposit;


		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 레이아웃을 정의합니다
		 */
		$page_title = $this->cbconfig->item('site_meta_title_deposit_result');
		$meta_description = $this->cbconfig->item('site_meta_description_deposit_result');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_deposit_result');
		$meta_author = $this->cbconfig->item('site_meta_author_deposit_result');
		$page_name = $this->cbconfig->item('site_page_name_deposit_result');

		$layoutconfig = array(
			'path' => 'deposit',
			'layout' => 'layout',
			'skin' => 'deposit_result',
			'layout_dir' => $this->cbconfig->item('layout_deposit'),
			'mobile_layout_dir' => $this->cbconfig->item('mobile_layout_deposit'),
			'use_sidebar' => $this->cbconfig->item('sidebar_deposit'),
			'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_deposit'),
			'skin_dir' => $this->cbconfig->item('skin_deposit'),
			'mobile_skin_dir' => $this->cbconfig->item('mobile_skin_deposit'),
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
	 * 포인트 -> 예치금 전환 페이지
	 */
	public function point_to_deposit()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_deposit_point_to_deposit';
		$this->load->event($eventname);

		if ( ! $this->cbconfig->item('use_deposit_point_to_deposit')) {
			alert_close('포인트를 이용한 ' . $this->cbconfig->item('deposit_name') . ' 구매 기능을 지원하지 않습니다');
			return;
		}
		if ($this->member->is_member() === false) {
			alert_close('접근 권한이 없습니다');
			return;
		}
		if ($this->cbconfig->item('deposit_point_min') && $this->cbconfig->item('deposit_point_min') > $this->member->item('mem_point')) {
			alert_close('최소 ' . $this->cbconfig->item('deposit_point_min') . ' 포인트 이상 이용 가능합니다');
			return;
		}

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
				'field' => 'point',
				'label' => '포인트',
				'rules' => 'trim|required|is_natural_no_zero|callback__point_to_deposit_check',
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

			/**
			 * 레이아웃을 정의합니다
			 */
			$page_title = '포인트를 ' . $this->cbconfig->item('deposit_name') . '(으)로 전환';
			$layoutconfig = array(
				'path' => 'deposit',
				'layout' => 'layout_popup',
				'skin' => 'point_to_deposit',
				'layout_dir' => $this->cbconfig->item('layout_deposit'),
				'mobile_layout_dir' => $this->cbconfig->item('mobile_layout_deposit'),
				'skin_dir' => $this->cbconfig->item('skin_deposit'),
				'mobile_skin_dir' => $this->cbconfig->item('mobile_skin_deposit'),
				'page_title' => $page_title,
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
			$view['view']['event']['formruntrue'] = Events::trigger('formruntrue', $eventname);

			$deposit = floor($this->input->post('point') / $this->cbconfig->item('deposit_point'));
			$content = '[포인트 -> ' . $this->cbconfig->item('deposit_name') . ' 전환] 포인트 '
				. number_format($this->input->post('point')) . ' -> '
				. $this->cbconfig->item('deposit_name') . ' ' . $deposit . ' '
				. $this->cbconfig->item('deposit_unit');
			$return = $this->depositlib->do_point_to_deposit(
				$this->member->item('mem_id'),
				$this->input->post('point'),
				$pay_type = 'point',
				$content,
				$admin_memo = ''
			);
			$result = json_decode($return, true);
			if (element('result', $result) === 'fail') {
				alert_close(html_escape(element('reason', $result)));
			}
			$this->depositlib->alarm('point_to_deposit', element('dep_id', $result));
			alert_refresh_close('전환이 완료되었습니다.');
		}
	}


	/**
	 * 예치금 -> 포인트 전환 페이지
	 */
	public function deposit_to_point()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_deposit_deposit_to_point';
		$this->load->event($eventname);

		if ( ! $this->cbconfig->item('use_deposit_deposit_to_point')) {
			alert_close($this->cbconfig->item('deposit_name') . '을(를) 포인트로 전환하는 기능을 지원하지 않습니다');
			return;
		}
		if ($this->member->is_member() === false) {
			alert_close('접근 권한이 없습니다');
			return;
		}
		if ($this->cbconfig->item('deposit_refund_point_min')
			&& $this->cbconfig->item('deposit_refund_point_min') > $this->member->item('total_deposit')) {
			alert_close('최소 ' . $this->cbconfig->item('deposit_refund_point_min')
				. $this->cbconfig->item('deposit_unit') . ' 이상 이용 가능합니다');
			return;
		}

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
				'field' => 'deposit',
				'label' => $this->cbconfig->item('deposit_name'),
				'rules' => 'trim|required|is_natural_no_zero|callback__deposit_to_point_check',
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

			/**
			 * 레이아웃을 정의합니다
			 */
			$page_title = $this->cbconfig->item('deposit_name') . '을(를) 포인트로 전환';
			$layoutconfig = array(
				'path' => 'deposit',
				'layout' => 'layout_popup',
				'skin' => 'deposit_to_point',
				'layout_dir' => $this->cbconfig->item('layout_deposit'),
				'mobile_layout_dir' => $this->cbconfig->item('mobile_layout_deposit'),
				'skin_dir' => $this->cbconfig->item('skin_deposit'),
				'mobile_skin_dir' => $this->cbconfig->item('mobile_skin_deposit'),
				'page_title' => $page_title,
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
			$view['view']['event']['formruntrue'] = Events::trigger('formruntrue', $eventname);

			$point = $this->input->post('deposit') * $this->cbconfig->item('deposit_refund_point');

			$content = '[' . $this->cbconfig->item('deposit_name') . ' -> 포인트 전환] '
				. $this->cbconfig->item('deposit_name') . ' ' . $this->input->post('deposit')
				. $this->cbconfig->item('deposit_unit') . ' -> 포인트 ' . number_format($point) . '점';

			$return = $this->depositlib->do_deposit_to_point(
				$this->member->item('mem_id'),
				$this->input->post('deposit'),
				$pay_type = '',
				$content,
				$admin_memo = ''
			);

			$result = json_decode($return, true);

			if (element('result', $result) === 'fail') {
				alert_close(html_escape(element('reason', $result)));
			}
			$this->depositlib->alarm('deposit_to_point', element('dep_id', $result));
			alert_refresh_close('전환이 완료되었습니다.');
		}
	}


	public function _deposit_to_point_check($deposit)
	{
		if ($deposit > $this->member->item('total_deposit')) {
			$this->form_validation->set_message(
				'_deposit_to_point_check',
				'입력된 값이 회원님이 보유하신 값보다 큽니다'
			);
			return false;
		}
		return true;
	}


	public function _point_to_deposit_check($point)
	{
		if ($point > $this->member->item('mem_point')) {
			$this->form_validation->set_message(
				'_point_to_deposit_check',
				'입력된 값이 회원님이 보유하신 값보다 큽니다'
			);
			return false;
		}
		return true;
	}
}