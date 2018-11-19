<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Depositcfg class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 관리자>예치금>예치금환경설정 controller 입니다.
 */
class Depositcfg extends CB_Controller
{

	/**
	 * 관리자 페이지 상의 현재 디렉토리입니다
	 * 페이지 이동시 필요한 정보입니다
	 */
	public $pagedir = 'deposit/depositcfg';

	/**
	 * 모델을 로딩합니다
	 */
	protected $models = array('Config');

	/**
	 * 이 컨트롤러의 메인 모델 이름입니다
	 */
	protected $modelname = 'Config_model';

	/**
	 * 헬퍼를 로딩합니다
	 */
	protected $helpers = array('form', 'array');

	function __construct()
	{
		parent::__construct();
	}

	/**
	 * 예치금환경설정>기본설정 페이지입니다
	 */
	public function index()
	{

		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_deposit_depositcfg_index';
		$this->load->event($eventname);

		if ( ! $this->db->table_exists('deposit')) {
			redirect(admin_url('deposit/depositcfg/install'));
		}

		if ( ! file_exists(APPPATH . 'libraries/Smslib.php')) {
			alert('sms 문자 플러그인을 설치 후에 이용이 가능합니다.');
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
				'field' => 'is_submit',
				'label' => '전송',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'use_deposit',
				'label' => '예치금 기능사용',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_name',
				'label' => '예치금 이름',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'deposit_unit',
				'label' => '예치금 단위',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'use_deposit_cash_to_deposit',
				'label' => '현금/카드로 구매가능',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_cash_to_deposit_unit',
				'label' => '현금충전시 금액',
				'rules' => 'trim',
			),
			array(
				'field' => 'deposit_charge_point',
				'label' => '예치금 구매시 적립포인트',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'use_deposit_point_to_deposit',
				'label' => '포인트로 예치금 구매 가능',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_point',
				'label' => '예치금포인트',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_point_min',
				'label' => '최소몇포인트',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'use_deposit_deposit_to_point',
				'label' => '예치금을 포인트로 전환',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_refund_point',
				'label' => '몇포인트로',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_refund_point_min',
				'label' => '최소 몇 예치금',
				'rules' => 'trim|numeric',
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
				'use_deposit', 'deposit_name', 'deposit_unit', 'use_deposit_cash_to_deposit',
				'deposit_cash_to_deposit_unit', 'deposit_charge_point', 'use_deposit_point_to_deposit',
				'deposit_point', 'deposit_point_min', 'use_deposit_deposit_to_point',
				'deposit_refund_point', 'deposit_refund_point_min',
			);
			foreach ($array as $value) {
				$savedata[$value] = $this->input->post($value, null, '');
			}

			$this->Config_model->save($savedata);
			$view['view']['alert_message'] = '기본설정이 저장되었습니다';
		}

		$getdata = $this->Config_model->get_all_meta();
		if ( ! isset($getdata['deposit_name'])) {

			$initdata['deposit_name'] = '예치금';
			$initdata['deposit_unit'] = '원';
			$initdata['use_deposit_cash_to_deposit'] = '1';
			$initdata['deposit_cash_to_deposit_unit'] = '10000:10000
20000:20000
30000:30000
50000:50000';
			$initdata['deposit_point'] = '1';
			$initdata['deposit_point_min'] = '1000';
			$initdata['deposit_refund_point'] = '1';
			$initdata['deposit_refund_point_min'] = '1000';
			$initdata['deposit_sendcont_admin_cash_to_deposit'] = '{고객명}님 충전 - 예치금 : {예치금액}원, 결제 : {결제금액}원 - {회사명}';
			$initdata['deposit_sendcont_user_cash_to_deposit'] = '충전완료 - 예치금 : {예치금액}원, 결제 : {결제금액}원 - {회사명}';
			$initdata['deposit_sendcont_admin_bank_to_deposit'] = '{고객명}님 무통장요청 - 예치금 : {예치금액}원, 결제 : {결제금액}원 - {회사명}';
			$initdata['deposit_sendcont_user_bank_to_deposit'] = '무통장입금요청, 입금확인시 자동 충전 됩니다, 결제금액 : {결제금액}원 - {회사명}';
			$initdata['deposit_sendcont_admin_approve_bank_to_deposit'] = '{고객명}님 입금처리완료 - 예치금 : {예치금액}원, 결제 : {결제금액}원 - {회사명}';
			$initdata['deposit_sendcont_user_approve_bank_to_deposit'] = '입금처리완료- 예치금 : {예치금액}원, 결제금액 : {결제금액}원 - {회사명}';
			$initdata['deposit_sendcont_admin_point_to_deposit'] = '{고객명}님 포인트로 예치금구매 - 예치금 : {예치금액}원, 포인트 : {포인트}점 - {회사명}';
			$initdata['deposit_sendcont_user_point_to_deposit'] = '예치금구매 : {예치금액}원, 결제포인트 : {포인트}점 - {회사명}';
			$initdata['deposit_sendcont_admin_deposit_to_point'] = '{고객명}님 포인트로 전환 - 예치금 : {예치금액}원, 포인트 : {포인트}점 - {회사명}';
			$initdata['deposit_sendcont_user_deposit_to_point'] = '포인트로 전환 : 예치금 : {예치금액}원, 전환포인트 : {포인트}점 - {회사명}';
			$this->Config_model->save($initdata);

		}
		$getdata = $this->Config_model->get_all_meta();
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
	 * 예치금환경설정>레이아웃 페이지입니다
	 */
	public function layout()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_deposit_depositcfg_layout';
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
				'field' => 'is_submit',
				'label' => '전송',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'layout_deposit',
				'label' => '일반레이아웃',
				'rules' => 'trim',
			),
			array(
				'field' => 'mobile_layout_deposit',
				'label' => '모바일레이아웃',
				'rules' => 'trim',
			),
			array(
				'field' => 'sidebar_deposit',
				'label' => '사이드바',
				'rules' => 'trim',
			),
			array(
				'field' => 'mobile_sidebar_deposit',
				'label' => '모바일사이드바',
				'rules' => 'trim',
			),
			array(
				'field' => 'skin_deposit',
				'label' => '일반스킨',
				'rules' => 'trim',
			),
			array(
				'field' => 'mobile_skin_deposit',
				'label' => '모바일스킨',
				'rules' => 'trim',
			),
			array(
				'field' => 'site_meta_title_deposit',
				'label' => '예치금 페이지 메타태그 Title',
				'rules' => 'trim',
			),
			array(
				'field' => 'site_meta_description_deposit',
				'label' => '예치금 페이지 메타태그 meta description',
				'rules' => 'trim',
			),
			array(
				'field' => 'site_meta_keywords_deposit',
				'label' => '예치금 페이지 메타태그 meta keywords',
				'rules' => 'trim',
			),
			array(
				'field' => 'site_meta_author_deposit',
				'label' => '예치금 페이지 메타태그 meta author',
				'rules' => 'trim',
			),
			array(
				'field' => 'site_page_name_deposit',
				'label' => '예치금 페이지 메타태그 page name',
				'rules' => 'trim',
			),
			array(
				'field' => 'site_meta_title_deposit_mylist',
				'label' => '나의사용내역 페이지 메타태그 Title',
				'rules' => 'trim',
			),
			array(
				'field' => 'site_meta_description_deposit_mylist',
				'label' => '나의사용내역 페이지 메타태그 meta description',
				'rules' => 'trim',
			),
			array(
				'field' => 'site_meta_keywords_deposit_mylist',
				'label' => '나의사용내역 페이지 메타태그 meta keywords',
				'rules' => 'trim',
			),
			array(
				'field' => 'site_meta_author_deposit_mylist',
				'label' => '나의사용내역 페이지 메타태그 meta author',
				'rules' => 'trim',
			),
			array(
				'field' => 'site_page_name_deposit_mylist',
				'label' => '나의사용내역 페이지 메타태그 page name',
				'rules' => 'trim',
			),
			array(
				'field' => 'site_meta_title_deposit_result',
				'label' => '예치금결제후결과 페이지 메타태그 Title',
				'rules' => 'trim',
			),
			array(
				'field' => 'site_meta_description_deposit_result',
				'label' => '예치금결제후결과 페이지 메타태그 meta description',
				'rules' => 'trim',
			),
			array(
				'field' => 'site_meta_keywords_deposit_result',
				'label' => '예치금결제후결과 페이지 메타태그 meta keywords',
				'rules' => 'trim',
			),
			array(
				'field' => 'site_meta_author_deposit_result',
				'label' => '예치금결제후결과 페이지 메타태그 meta author',
				'rules' => 'trim',
			),
			array(
				'field' => 'site_page_name_deposit_result',
				'label' => '예치금결제후결과 페이지 메타태그 page name',
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

			$array = array(
				'layout_deposit', 'mobile_layout_deposit', 'sidebar_deposit', 'mobile_sidebar_deposit',
				'skin_deposit', 'mobile_skin_deposit', 'site_meta_title_deposit',
				'site_meta_description_deposit', 'site_meta_keywords_deposit', 'site_meta_author_deposit',
				'site_page_name_deposit', 'site_meta_title_deposit_mylist',
				'site_meta_description_deposit_mylist', 'site_meta_keywords_deposit_mylist',
				'site_meta_author_deposit_mylist', 'site_page_name_deposit_mylist',
				'site_meta_title_deposit_result', 'site_meta_description_deposit_result',
				'site_meta_keywords_deposit_result', 'site_meta_author_deposit_result',
				'site_page_name_deposit_result',
			);
			foreach ($array as $value) {
				$savedata[$value] = $this->input->post($value, null, '');
			}

			$this->Config_model->save($savedata);
			$view['view']['alert_message'] = '레이아웃설정이 저장되었습니다';
		}

		$getdata = $this->Config_model->get_all_meta();
		$view['view']['data'] = $getdata;

		$view['view']['data']['layout_deposit_option'] = get_skin_name(
			'_layout',
			set_value('layout_deposit', element('layout_deposit', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['mobile_layout_deposit_option'] = get_skin_name(
			'_layout',
			set_value('mobile_layout_deposit', element('mobile_layout_deposit', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['skin_deposit_option'] = get_skin_name(
			'deposit',
			set_value('skin_deposit', element('skin_deposit', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['mobile_skin_deposit_option'] = get_skin_name(
			'deposit',
			set_value('mobile_skin_deposit', element('mobile_skin_deposit', $getdata)),
			'기본설정따름'
		);

		$view['view']['data']['layout_deposit_popup_option'] = get_skin_name(
			'_layout',
			set_value('layout_deposit_popup', element('layout_deposit_popup', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['mobile_layout_deposit_popup_option'] = get_skin_name(
			'_layout',
			set_value('mobile_layout_deposit_popup', element('mobile_layout_deposit_popup', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['skin_deposit_popup_option'] = get_skin_name(
			'deposit_popup',
			set_value('skin_deposit_popup', element('skin_deposit_popup', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['mobile_skin_deposit_popup_option'] = get_skin_name(
			'deposit_popup',
			set_value('mobile_skin_deposit_popup', element('mobile_skin_deposit_popup', $getdata)),
			'기본설정따름'
		);

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 어드민 레이아웃을 정의합니다
		 */
		$layoutconfig = array('layout' => 'layout', 'skin' => 'layout');
		$view['layout'] = $this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}


	/**
	 * 예치금환경설정>SMS 설정 페이지입니다
	 */
	public function smsconfig()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_deposit_depositcfg_smsconfig';
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
				'field' => 'is_submit',
				'label' => '전송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'use_sms',
				'label' => 'SMS 사용',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'sms_icode_id',
				'label' => '아이코드 아이디',
				'rules' => 'trim',
			),
			array(
				'field' => 'sms_icode_pw',
				'label' => '아이코드 비밀번호',
				'rules' => 'trim|callback__sms_icode_check',
			),
			array(
				'field' => 'sms_admin_phone',
				'label' => '회신번호',
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

			$array = array('use_sms', 'sms_icode_id', 'sms_icode_pw', 'sms_admin_phone',);

			foreach ($array as $value) {
				$savedata[$value] = $this->input->post($value, null, '');
			}
			if ($this->input->post('sms_icode_id') && $this->input->post('sms_icode_pw')) {
				$this->load->library('smslib');
				$smsinfo = $this->smslib->get_icode_info($this->input->post('sms_icode_id'), $this->input->post('sms_icode_pw'));
				if (element('payment', $smsinfo) === 'C') {
					$savedata['sms_icode_port'] = '7296';
				} else {
					$savedata['sms_icode_port'] = '7295';
				}
			}

			$this->Config_model->save($savedata);
			$view['view']['alert_message'] = 'SMS 설정이 저장되었습니다';
		}

		$getdata = $this->Config_model->get_all_meta();
		if (element('sms_icode_id', $getdata) && element('sms_icode_pw', $getdata)) {
			$this->load->library('smslib');
			$getdata['smsinfo'] = $this->smslib->get_icode_info(element('sms_icode_id', $getdata), element('sms_icode_pw', $getdata));
		}
		$view['view']['data'] = $getdata;

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 어드민 레이아웃을 정의합니다
		 */
		$layoutconfig = array('layout' => 'layout', 'skin' => 'smsconfig');
		$view['layout'] = $this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}

	/**
	 * 예치금환경설정>결제기능 페이지입니다
	 */
	public function paymentconfig()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_deposit_depositcfg_paymentconfig';
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
				'field' => 'is_submit',
				'label' => '전송',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'use_payment_bank',
				'label' => '무통장입금',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'use_payment_card',
				'label' => '카드결제',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'use_payment_realtime',
				'label' => '실시간계좌이체',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'use_payment_vbank',
				'label' => '가상계좌이체',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'use_payment_phone',
				'label' => '핸드폰결제',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'use_payment_pg',
				'label' => '결제대행사',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'pg_kcp_mid',
				'label' => 'KCP ID',
				'rules' => 'trim',
			),
			array(
				'field' => 'pg_kcp_key',
				'label' => 'KCP KEY',
				'rules' => 'trim',
			),
			array(
				'field' => 'pg_inicis_mid',
				'label' => 'INICIS ID',
				'rules' => 'trim',
			),
			array(
				'field' => 'pg_inicis_key',
				'label' => 'INICIS KEY',
				'rules' => 'trim',
			),
			array(
				'field' => 'pg_lg_mid',
				'label' => 'LG ID',
				'rules' => 'trim',
			),
			array(
				'field' => 'pg_lg_key',
				'label' => 'LG KEY',
				'rules' => 'trim',
			),
			array(
				'field' => 'use_pg_no_interest',
				'label' => '무이자할부사용',
				'rules' => 'trim',
			),
			array(
				'field' => 'use_pg_test',
				'label' => '실결제여부',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'payment_bank_info',
				'label' => '계좌안내(무통장입금시)',
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

			$array = array(
				'use_payment_bank', 'use_payment_card', 'use_payment_realtime', 'use_payment_vbank',
				'use_payment_phone', 'use_payment_pg', 'pg_kcp_mid', 'pg_kcp_key', 'pg_inicis_mid',
				'pg_inicis_key', 'pg_lg_mid', 'pg_lg_key', 'use_pg_no_interest', 'use_pg_test',
				'payment_bank_info'
			);
			foreach ($array as $value) {
				$savedata[$value] = $this->input->post($value, null, '');
			}

			$this->Config_model->save($savedata);
			$view['view']['alert_message'] = '결제기능 설정이 저장되었습니다';
		}

		$getdata = $this->Config_model->get_all_meta();
		$view['view']['data'] = $getdata;

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 어드민 레이아웃을 정의합니다
		 */
		$layoutconfig = array('layout' => 'layout', 'skin' => 'paymentconfig');
		$view['layout'] = $this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}

	/**
	 * 예치금환경설정>알림설정 페이지입니다
	 */
	public function alarm()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_deposit_depositcfg_alarm';
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
				'field' => 'is_submit',
				'label' => '전송',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'deposit_email_admin_cash_to_deposit',
				'label' => '카드/이체 등으로 예치금 구매시',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_email_user_cash_to_deposit',
				'label' => '카드/이체 등으로 예치금 구매시',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_email_alluser_cash_to_deposit',
				'label' => '카드/이체 등으로 예치금 구매시',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_note_admin_cash_to_deposit',
				'label' => '카드/이체 등으로 예치금 구매시',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_note_user_cash_to_deposit',
				'label' => '카드/이체 등으로 예치금 구매시',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_sms_admin_cash_to_deposit',
				'label' => '카드/이체 등으로 예치금 구매시',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_sms_user_cash_to_deposit',
				'label' => '카드/이체 등으로 예치금 구매시',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_sms_alluser_cash_to_deposit',
				'label' => '카드/이체 등으로 예치금 구매시',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_email_admin_bank_to_deposit',
				'label' => '무통장입금으로 예치금구매요청시',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_email_user_bank_to_deposit',
				'label' => '무통장입금으로 예치금구매요청시',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_email_alluser_bank_to_deposit',
				'label' => '무통장입금으로 예치금구매요청시',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_note_admin_bank_to_deposit',
				'label' => '무통장입금으로 예치금구매요청시',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_note_user_bank_to_deposit',
				'label' => '무통장입금으로 예치금구매요청시',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_sms_admin_bank_to_deposit',
				'label' => '무통장입금으로 예치금구매요청시',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_sms_user_bank_to_deposit',
				'label' => '무통장입금으로 예치금구매요청시',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_sms_alluser_bank_to_deposit',
				'label' => '무통장입금으로 예치금구매요청시',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_email_admin_approve_bank_to_deposit',
				'label' => '무통장입금 완료처리시',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_email_user_approve_bank_to_deposit',
				'label' => '무통장입금 완료처리시',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_email_alluser_approve_bank_to_deposit',
				'label' => '무통장입금 완료처리시',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_note_admin_approve_bank_to_deposit',
				'label' => '무통장입금 완료처리시',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_note_user_approve_bank_to_deposit',
				'label' => '무통장입금 완료처리시',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_sms_admin_approve_bank_to_deposit',
				'label' => '무통장입금 완료처리시',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_sms_user_approve_bank_to_deposit',
				'label' => '무통장입금 완료처리시',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_sms_alluser_approve_bank_to_deposit',
				'label' => '무통장입금 완료처리시',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_email_admin_point_to_deposit',
				'label' => '포인트로 예치금 구매시',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_email_user_point_to_deposit',
				'label' => '포인트로 예치금 구매시',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_email_alluser_point_to_deposit',
				'label' => '포인트로 예치금 구매시',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_note_admin_point_to_deposit',
				'label' => '포인트로 예치금 구매시',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_note_user_point_to_deposit',
				'label' => '포인트로 예치금 구매시',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_sms_admin_point_to_deposit',
				'label' => '포인트로 예치금 구매시',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_sms_user_point_to_deposit',
				'label' => '포인트로 예치금 구매시',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_sms_alluser_point_to_deposit',
				'label' => '포인트로 예치금 구매시',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_email_admin_deposit_to_point',
				'label' => '예치금을 포인트로 전환시',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_email_user_deposit_to_point',
				'label' => '예치금을 포인트로 전환시',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_email_alluser_deposit_to_point',
				'label' => '예치금을 포인트로 전환시',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_note_admin_deposit_to_point',
				'label' => '예치금을 포인트로 전환시',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_note_user_deposit_to_point',
				'label' => '예치금을 포인트로 전환시',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_sms_admin_deposit_to_point',
				'label' => '예치금을 포인트로 전환시',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_sms_user_deposit_to_point',
				'label' => '예치금을 포인트로 전환시',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'deposit_sms_alluser_deposit_to_point',
				'label' => '예치금을 포인트로 전환시',
				'rules' => 'trim|numeric',
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
				'deposit_email_admin_cash_to_deposit', 'deposit_email_user_cash_to_deposit',
				'deposit_email_alluser_cash_to_deposit', 'deposit_note_admin_cash_to_deposit',
				'deposit_note_user_cash_to_deposit', 'deposit_sms_admin_cash_to_deposit',
				'deposit_sms_user_cash_to_deposit', 'deposit_sms_alluser_cash_to_deposit',
				'deposit_email_admin_bank_to_deposit', 'deposit_email_user_bank_to_deposit',
				'deposit_email_alluser_bank_to_deposit', 'deposit_note_admin_bank_to_deposit',
				'deposit_note_user_bank_to_deposit', 'deposit_sms_admin_bank_to_deposit',
				'deposit_sms_user_bank_to_deposit', 'deposit_sms_alluser_bank_to_deposit',
				'deposit_email_admin_approve_bank_to_deposit', 'deposit_email_user_approve_bank_to_deposit',
				'deposit_email_alluser_approve_bank_to_deposit', 'deposit_note_admin_approve_bank_to_deposit',
				'deposit_note_user_approve_bank_to_deposit', 'deposit_sms_admin_approve_bank_to_deposit',
				'deposit_sms_user_approve_bank_to_deposit', 'deposit_sms_alluser_approve_bank_to_deposit',
				'deposit_email_admin_point_to_deposit', 'deposit_email_user_point_to_deposit',
				'deposit_email_alluser_point_to_deposit', 'deposit_note_admin_point_to_deposit',
				'deposit_note_user_point_to_deposit', 'deposit_sms_admin_point_to_deposit',
				'deposit_sms_user_point_to_deposit', 'deposit_sms_alluser_point_to_deposit',
				'deposit_email_admin_deposit_to_point', 'deposit_email_user_deposit_to_point',
				'deposit_email_alluser_deposit_to_point', 'deposit_note_admin_deposit_to_point',
				'deposit_note_user_deposit_to_point', 'deposit_sms_admin_deposit_to_point',
				'deposit_sms_user_deposit_to_point', 'deposit_sms_alluser_deposit_to_point',
			);
			foreach ($array as $value) {
				$savedata[$value] = $this->input->post($value, null, '');
			}

			$this->Config_model->save($savedata);
			$view['view']['alert_message'] = '알림기능 설정이 저장되었습니다';
		}

		$getdata = $this->Config_model->get_all_meta();
		$view['view']['data'] = $getdata;

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 어드민 레이아웃을 정의합니다
		 */
		$layoutconfig = array('layout' => 'layout', 'skin' => 'alarm');
		$view['layout'] = $this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}

	/**
	 * 아이코드 아이디와 패스워드가 맞는지 체크합니다
	 */
	public function _sms_icode_check($pw = '')
	{
		$id = $this->input->post('sms_icode_id');

		if (empty($id) && empty($pw)) {
			return true;
		}
		$this->load->library('smslib');
		$result = $this->smslib->get_icode_info($id, $pw);
		if (element('code', $result) === '202') {
			$this->form_validation->set_message(
				'_sms_icode_check',
				'아이코드 아이디와 패스워드가 맞지 않습니다'
			);
			return false;
		}
		return true;
	}


	public function install()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_deposit_depositcfg_install';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		$view['view']['is_installed'] = '';
		if ( ! $this->db->table_exists('deposit')) {
			
			$this->load->dbforge();



			// deposit table
			$this->dbforge->add_field(array(
				'dep_id' => array(
					'type' => 'BIGINT',
					'constraint' => 20,
					'unsigned' => true,
				),
				'mem_id' => array(
					'type' => 'INT',
					'constraint' => 11,
					'unsigned' => true,
					'default' => '0',
				),
				'mem_nickname' => array(
					'type' => 'VARCHAR',
					'constraint' => '100',
					'default' => '',
				),
				'mem_realname' => array(
					'type' => 'VARCHAR',
					'constraint' => '100',
					'default' => '',
				),
				'mem_email' => array(
					'type' => 'VARCHAR',
					'constraint' => '255',
					'default' => '',
				),
				'mem_phone' => array(
					'type' => 'VARCHAR',
					'constraint' => '255',
					'default' => '',
				),
				'dep_from_type' => array(
					'type' => 'VARCHAR',
					'constraint' => '100',
					'default' => '',
				),
				'dep_to_type' => array(
					'type' => 'VARCHAR',
					'constraint' => '100',
					'default' => '',
				),
				'dep_deposit_request' => array(
					'type' => 'INT',
					'constraint' => 11,
					'default' => '0',
				),
				'dep_deposit' => array(
					'type' => 'INT',
					'constraint' => 11,
					'default' => '0',
				),
				'dep_deposit_sum' => array(
					'type' => 'INT',
					'constraint' => 11,
					'default' => '0',
				),
				'dep_cash_request' => array(
					'type' => 'INT',
					'constraint' => 11,
					'default' => '0',
				),
				'dep_cash' => array(
					'type' => 'INT',
					'constraint' => 11,
					'default' => '0',
				),
				'dep_point' => array(
					'type' => 'INT',
					'constraint' => 11,
					'default' => '0',
				),
				'dep_content' => array(
					'type' => 'VARCHAR',
					'constraint' => '255',
					'default' => '',
				),
				'dep_pay_type' => array(
					'type' => 'VARCHAR',
					'constraint' => '100',
					'default' => '',
				),
				'dep_pg' => array(
					'type' => 'VARCHAR',
					'constraint' => '255',
					'default' => '',
				),
				'dep_tno' => array(
					'type' => 'VARCHAR',
					'constraint' => '255',
					'default' => '',
				),
				'dep_app_no' => array(
					'type' => 'VARCHAR',
					'constraint' => '255',
					'default' => '',
				),
				'dep_bank_info' => array(
					'type' => 'VARCHAR',
					'constraint' => '255',
					'default' => '',
				),
				'dep_admin_memo' => array(
					'type' => 'TEXT',
					'null' => true,
				),
				'dep_datetime' => array(
					'type' => 'DATETIME',
					'null' => true,
				),
				'dep_deposit_datetime' => array(
					'type' => 'DATETIME',
					'null' => true,
				),
				'dep_ip' => array(
					'type' => 'VARCHAR',
					'constraint' => '50',
					'default' => '',
				),
				'dep_useragent' => array(
					'type' => 'VARCHAR',
					'constraint' => '255',
					'default' => '',
				),
				'dep_status' => array(
					'type' => 'TINYINT',
					'constraint' => 4,
					'unsigned' => true,
					'default' => '0',
				),
				'dep_vbank_expire' => array(
					'type' => 'DATETIME',
					'null' => true,
				),
				'is_test' => array(
					'type' => 'CHAR',
					'constraint' => '1',
					'default' => '',
				),
				'status' => array(
					'type' => 'VARCHAR',
					'constraint' => '255',
					'default' => '',
				),
				'dep_refund_price' => array(
					'type' => 'INT',
					'constraint' => 11,
					'default' => '0',
				),
				'dep_order_history' => array(
					'type' => 'TEXT',
					'null' => true,
				),
			));
			$this->dbforge->add_key('dep_id', true);
			$this->dbforge->add_key('mem_id');
			$this->dbforge->add_key('dep_pay_type');
			$this->dbforge->add_key('dep_datetime');
			$this->dbforge->add_key('dep_deposit_datetime');
			$this->dbforge->add_key('dep_status');
			if ($this->dbforge->create_table('deposit', true) === false) {
				return false;
			}


			// payment_inicis_log table
			$this->dbforge->add_field(array(
				'pil_id' => array(
					'type' => 'BIGINT',
					'constraint' => 11,
					'unsigned' => true,
				),
				'pil_type' => array(
					'type' => 'VARCHAR',
					'constraint' => '255',
					'default' => '',
				),
				'P_TID' => array(
					'type' => 'VARCHAR',
					'constraint' => '255',
					'default' => '',
				),
				'P_MID' => array(
					'type' => 'VARCHAR',
					'constraint' => '255',
					'default' => '',
				),
				'P_AUTH_DT' => array(
					'type' => 'VARCHAR',
					'constraint' => '255',
					'default' => '',
				),
				'P_STATUS' => array(
					'type' => 'VARCHAR',
					'constraint' => '255',
					'default' => '',
				),
				'P_TYPE' => array(
					'type' => 'VARCHAR',
					'constraint' => '255',
					'default' => '',
				),
				'P_OID' => array(
					'type' => 'VARCHAR',
					'constraint' => '255',
					'default' => '',
				),
				'P_FN_NM' => array(
					'type' => 'VARCHAR',
					'constraint' => '255',
					'default' => '',
				),
				'P_AMT' => array(
					'type' => 'INT',
					'constraint' => 11,
					'unsigned' => true,
					'default' => '0',
				),
				'P_AUTH_NO' => array(
					'type' => 'VARCHAR',
					'constraint' => '255',
					'default' => '',
				),
				'P_RMESG1' => array(
					'type' => 'VARCHAR',
					'constraint' => '255',
					'default' => '',
				),
			));
			$this->dbforge->add_key('pil_id');
			if ($this->dbforge->create_table('payment_inicis_log', true) === false) {
				return false;
			}


			// payment_order_data table
			$this->dbforge->add_field(array(
				'pod_id' => array(
					'type' => 'BIGINT',
					'constraint' => 11,
					'unsigned' => true,
				),
				'pod_pg' => array(
					'type' => 'VARCHAR',
					'constraint' => '255',
					'default' => '',
				),
				'pod_type' => array(
					'type' => 'VARCHAR',
					'constraint' => '255',
					'default' => '',
				),
				'pod_data' => array(
					'type' => 'TEXT',
					'null' => true,
				),
				'pod_datetime' => array(
					'type' => 'DATETIME',
					'null' => true,
				),
				'pod_ip' => array(
					'type' => 'VARCHAR',
					'constraint' => '50',
					'default' => '',
				),
				'mem_id' => array(
					'type' => 'INT',
					'constraint' => 11,
					'default' => '0',
				),
				'cart_id' => array(
					'type' => 'VARCHAR',
					'constraint' => '255',
					'default' => '0',
				),
			));
			$this->dbforge->add_key('pod_id');
			if ($this->dbforge->create_table('payment_order_data', true) === false) {
				return false;
			}


			$view['view']['is_installed'] = '1';

		}


	
		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 어드민 레이아웃을 정의합니다
		 */
		$layoutconfig = array('layout' => 'layout', 'skin' => 'install');
		$view['layout'] = $this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));

	}
}
