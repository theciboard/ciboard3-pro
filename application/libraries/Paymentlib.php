<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Paymentlib class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 결제관련 class 입니다.
 */
class Paymentlib extends CI_Controller
{

	private $CI;
	private $xpay;
	private $inipay;

	private $inicis_authMap;	//이니시스
	private $httpUtil;	  //이니시스

	function __construct()
	{
		$this->CI = & get_instance();
		$this->CI->load->helper(array('array'));
	}


	public function kcp_init()
	{
		$data = array();

		$default = (array) $this->CI->cbconfig->get_config(true);  //설정을 가져온다.

		if ($this->CI->cbconfig->item('use_pg_test')) {

			$data['is_test'] = '_test';
			$data['pg_conf_js_url'] = 'https://testpay.kcp.co.kr/plugin/payplus_web.jsp';
			$data['pg_conf_gw_url'] = 'testpaygw.kcp.co.kr';	//모바일에서 사용

			if (element('de_escrow_use', $default) == 1) {
				// 에스크로결제 테스트
				$data['pg_kcp_mid'] = "T0007";
				$data['pg_kcp_key'] = '4Ho4YsuOZlLXUZUdOxM1Q7X__';
			} else {
				$data['pg_kcp_mid'] = 'T0000';
				$data['pg_kcp_key'] = '3grptw1.zW0GSo4PQdaGvsF__';
				$data['pg_wsdl'] = 'KCPPaymentService.wsdl';	  //모바일에서 사용
			}

		} else {
			$data['is_test'] = '';
			$data['pg_kcp_mid'] = "SR".$this->CI->cbconfig->item('pg_kcp_mid');
			$data['pg_kcp_key'] = $this->CI->cbconfig->item('pg_kcp_key');
			$data['pg_conf_js_url'] = 'https://pay.kcp.co.kr/plugin/payplus_web.jsp';
			$data['pg_wsdl'] = 'real_KCPPaymentService.wsdl';	 //모바일에서 사용
			$data['pg_conf_gw_url'] = 'paygw.kcp.co.kr';	//모바일에서 사용
		}

		$data['pg_conf_home_dir'] = FCPATH . 'plugin/pg/kcp';
		$data['pg_conf_key_dir'] = '';
		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
			$data['pg_conf_log_dir'] = FCPATH . 'plugin/pg/kcp/log';
			$data['pg_conf_key_dir'] = FCPATH . 'plugin/pg/kcp/bin/pub.key';
		}

		if (preg_match("/^T000/", $data['pg_kcp_mid']) || $this->CI->cbconfig->item('use_pg_test')) {
			$data['pg_conf_gw_url'] = 'testpaygw.kcp.co.kr'; // real url : paygw.kcp.co.kr, test url : testpaygw.kcp.co.kr
		} else {
			$data['pg_conf_gw_url'] = 'paygw.kcp.co.kr';
			if (!preg_match("/^SR/", $data['pg_kcp_mid'])) {
				alert("SR 로 시작하지 않는 KCP SITE CODE 는 지원하지 않습니다.");
			}
		}

		// KCP SITE KEY 입력 체크
		if ($this->CI->cbconfig->item('use_payment_card') OR $this->CI->cbconfig->item('use_payment_realtime') OR $this->CI->cbconfig->item('use_payment_vbank') OR $this->CI->cbconfig->item('use_payment_phone') OR
		$this->CI->cbconfig->item('use_payment_easy')) {
			if (empty($data['pg_kcp_mid'])) {
				alert('KCP SITE KEY를 입력해 주십시오.');
				return;
			}
		}

		// 테스트 결제 때 PAYCO site_cd, site_key 재설정
		if($this->CI->cbconfig->item('use_pg_test') && element('od_settle_case', $_POST) == '간편결제') {
			$data['pg_kcp_mid'] = 'S6729';
			$data['pg_kcp_key'] = '';
		}

		$data['pg_conf_log_level'] = '3'; // 변경불가
		$data['pg_conf_gw_port'] = '8090'; // 포트번호(변경불가)
		$data['pg_module_type']	=	'01';		  // 변경불가
		//$data['site_name']  = $this->CI->cbconfig->item('cmall_name');		  // 회사이름설정
		$data['site_name'] = $this->CI->cbconfig->item('company_name');	  // 회사이름설정

		return $data;
	}


	public function lg_init()
	{
		$data = array();

		$data['CST_PLATFORM'] = $this->CI->cbconfig->item('use_pg_test') ? 'test' : 'service'; //LG유플러스 결제 서비스 선택(test:테스트, service:서비스)
		$data['CST_MID'] = 'si_'.$this->CI->cbconfig->item('pg_lg_mid'); //상점아이디(LG유플러스으로 부터 발급받으신 상점아이디를 입력하세요)
																					//테스트 아이디는 't'를 반드시 제외하고 입력하세요.
		$data['LGD_MID'] = (('test' === $data['CST_PLATFORM']) ? 't' : '') . $data['CST_MID']; //상점아이디(자동생성)
		$data['pg_lg_key'] = $this->CI->cbconfig->item('pg_lg_key');	//mert key

		if( $data['CST_PLATFORM'] === 'test' && $data['LGD_MID'] === 'tsi_' ){
			$data['CST_MID'] = 'dacomst7';
			$data['LGD_MID'] = 'tdacomst7';
			$data['pg_lg_key'] = '95160cce09854ef44d2edb2bfb05f9f3';
		}

		$data['LGD_TIMESTAMP'] = date('YmdHis'); //타임스탬프
		$data['LGD_BUYERIP'] = $this->CI->input->ip_address(); //구매자IP
		$data['LGD_BUYERID'] = ''; //구매자ID
		$data['LGD_CUSTOM_SKIN'] = 'red'; //상점정의 결제창 스킨 (red, purple, yellow)
		$data['LGD_WINDOW_VER'] = '2.5'; //결제창 버젼정보
		$data['LGD_MERTKEY'] = ''; //상점MertKey(mertkey는 상점관리자 -> 계약정보 -> 상점정보관리에서 확인하실수 있습니다)

		$data['LGD_WINDOW_TYPE'] = 'iframe';										 //결제창 호출 방식
		$data['LGD_CUSTOM_SWITCHINGTYPE'] = 'IFRAME';									   //신용카드 카드사 인증 페이지 연동 방식
		$data['LGD_RETURNURL']		  = site_url('payment/lg_returnurl/');				  //LGD_RETURNURL 을 설정하여 주시기 바랍니다. 반드시 현재 페이지와 동일한 프로트콜 및  호스트이어야 합니다. 아래 부분을 반드시 수정하십시요.
		$data['LGD_VERSION']			= 'PHP_Non-ActiveX_Standard';					   // 버전정보 (삭제하지 마세요)

		// 결제가능 수단
		$useablepay = array();
		$data['LGD_CUSTOM_USABLEPAY'] = '';
		if ($this->CI->cbconfig->item('use_payment_realtime')) {
			$useablepay[] = 'SC0030';
		}
		if ($this->CI->cbconfig->item('use_payment_vbank')) {
			$useablepay[] = 'SC0040';
		}
		if ($this->CI->cbconfig->item('use_payment_card')) {
			$useablepay[] = 'SC0010';
		}
		if ($this->CI->cbconfig->item('use_payment_phone')) {
			$useablepay[] = 'SC0060';
		}
		if (count($useablepay) > 0) {
			$data['LGD_CUSTOM_USABLEPAY'] = implode('-', $useablepay);
		}

		$data['configPath'] = FCPATH . 'plugin/pg/lg'; //LG유플러스에서 제공한 환경파일('/conf/lgdacom.conf') 위치 지정.

		$data['LGD_CASNOTEURL'] = site_url('payment/lg_return_result');

		return $data;
	}


	public function inicis_init($type = '')
	{
		$data = array();

		if ($this->CI->cbconfig->item('use_pg_test')) {	 //테스트결제
			// 일반결제 테스트
			$data['pg_inicis_mid'] = 'INIpayTest';
			$data['pg_inicis_key'] = '1111';
			$data['pg_inicis_sign'] = 'SU5JTElURV9UUklQTEVERVNfS0VZU1RS';

			//$data['ini_js_url'] = 'https://stgstdpay.inicis.com/stdjs/INIStdPay.js';
			$data['ini_js_url'] = 'https://stdpay.inicis.com/stdjs/INIStdPay.js';
		} else {	//실결제
			$data['pg_inicis_mid'] = 'SIR'.$this->CI->cbconfig->item('pg_inicis_mid');
			$data['pg_inicis_key'] = $this->CI->cbconfig->item('pg_inicis_key');
			$data['pg_inicis_sign'] = $this->CI->cbconfig->item('pg_inicis_websign');

			$data['ini_js_url'] = 'https://stdpay.inicis.com/stdjs/INIStdPay.js';
		}


		if ($type !== 'nolib') {
			/**************************
			 * 1. 라이브러리 인클루드 *
			 **************************/
			include_once('plugin/pg/inicis/libs/INILib.php');
			include_once('plugin/pg/inicis/libs/INIStdPayUtil.php');
			include_once('plugin/pg/inicis/libs/sha256.inc.php');

			$data['pgid'] = $pgid;
			$data['BANK_CODE'] = $BANK_CODE;
			$data['CARD_CODE'] = $CARD_CODE;

			/***************************************
			 * 2. INIpay50 클래스의 인스턴스 생성 *
			 ***************************************/
			$this->inipay = new INIpay50;

			$this->inipay->SetField('inipayhome', FCPATH . 'plugin/pg/inicis'); // 이니페이 홈디렉터리(상점수정 필요)
			$this->inipay->SetField('debug', "false"); // 로그모드('true'로 설정하면 상세로그가 생성됨.)

			$data['util'] = new INIStdPayUtil();
			$data['timestamp'] = $data['util']->getTimestamp(); // util에 의해서 자동생성

		}

		$data['inipay_nointerest'] = 'no'; //무이자여부(no:일반, yes:무이자)
		$data['inipay_quotabase'] = '선택:일시불:2개월:3개월:4개월:5개월:6개월:7개월:8개월:9개월:10개월:11개월:12개월'; // 할부기간

		if('deposit' == $type){	 //예치금
			$data['returnUrl'] = site_url('deposit/inicisweb');
		} else {		//컨텐츠몰
			$data['returnUrl'] = site_url('cmall/inicisweb');
		}

		$data['closeUrl'] = site_url('payment/inicis_close');
		$data['popupUrl'] = site_url('payment/inicis_popup');

		$data['BANK_CODE'] = array(
			'03' => '기업은행',
			'04' => '국민은행',
			'05' => '외환은행',
			'07' => '수협중앙회',
			'11' => '농협중앙회',
			'20' => '우리은행',
			'23' => 'SC 제일은행',
			'31' => '대구은행',
			'32' => '부산은행',
			'34' => '광주은행',
			'37' => '전북은행',
			'39' => '경남은행',
			'53' => '한국씨티은행',
			'71' => '우체국',
			'81' => '하나은행',
			'88' => '신한은행',
			'D1' => '동양종합금융증권',
			'D2' => '현대증권',
			'D3' => '미래에셋증권',
			'D4' => '한국투자증권',
			'D5' => '우리투자증권',
			'D6' => '하이투자증권',
			'D7' => 'HMC 투자증권',
			'D8' => 'SK 증권',
			'D9' => '대신증권',
			'DA' => '하나대투증권',
			'DB' => '굿모닝신한증권',
			'DC' => '동부증권',
			'DD' => '유진투자증권',
			'DE' => '메리츠증권',
			'DF' => '신영증권'
		);

		$data['CARD_CODE'] = array(
			'01' => '외환',
			'03' => '롯데',
			'04' => '현대',
			'06' => '국민',
			'11' => 'BC',
			'12' => '삼성',
			'14' => '신한',
			'15' => '한미',
			'16' => 'NH',
			'17' => '하나 SK',
			'21' => '해외비자',
			'22' => '해외마스터',
			'23' => 'JCB',
			'24' => '해외아멕스',
			'25' => '해외다이너스'
		);

		$data['PAY_METHOD'] = array(
			'VCard' => '신용카드',
			'Card' => '신용카드',
			'DirectBank' => '계좌이체',
			'HPP' => '휴대폰',
			'VBank' => '가상계좌'
		);

		return $data;
	}


	public function kcp_pp_ax_hub()
	{
		include('plugin/pg/kcp/pp_ax_hub_lib.php');
		$config = $this->kcp_init();

		$result = array();

		/* ============================================================================== */
		/* = 01. 지불 요청 정보 설정 = */
		/* = -------------------------------------------------------------------------- = */
		$result['req_tx'] = $this->CI->input->post('req_tx', null, ''); // 요청 종류
		$result['tran_cd'] = $this->CI->input->post('tran_cd', null, ''); // 처리 종류
		/* = -------------------------------------------------------------------------- = */
		$result['cust_ip'] = getenv('REMOTE_ADDR'); // 요청 IP
		$result['ordr_idxx'] = $this->CI->input->post('ordr_idxx', null, ''); // 쇼핑몰 주문번호
		$result['good_name'] = addslashes($this->CI->input->post('good_name', null, '')); // 상품명
		$result['good_mny'] = $this->CI->input->post('good_mny', null, ''); // 결제 총금액
		/* = -------------------------------------------------------------------------- = */
		$result['res_cd'] = ''; // 응답코드
		$result['res_msg'] = ''; // 응답메시지
		$result['res_en_msg'] = ''; // 응답 영문 메세지
		$result['tno'] = $this->CI->input->post('tno', null, ''); // KCP 거래 고유 번호
		/* = -------------------------------------------------------------------------- = */
		$result['buyr_name'] = addslashes($this->CI->input->post('buyr_name', null, '')); // 주문자명
		$result['buyr_tel1'] = $this->CI->input->post('buyr_tel1', null, ''); // 주문자 전화번호
		$result['buyr_tel2'] = $this->CI->input->post('buyr_tel2', null, ''); // 주문자 핸드폰 번호
		$result['buyr_mail'] = $this->CI->input->post('buyr_mail', null, ''); // 주문자 E-mail 주소
		/* = -------------------------------------------------------------------------- = */
		$result['mod_type'] = $this->CI->input->post('mod_type', null, ''); // 변경TYPE VALUE 승인취소시 필요
		$result['mod_desc'] = $this->CI->input->post('mod_desc', null, ''); // 변경사유
		/* = -------------------------------------------------------------------------- = */
		$result['use_pay_method'] = $this->CI->input->post('use_pay_method', null, ''); // 결제 방법
		$result['bSucc'] = ''; // 업체 DB 처리 성공 여부
		/* = -------------------------------------------------------------------------- = */
		$result['app_time'] = ''; // 승인시간 (모든 결제 수단 공통)
		$result['amount'] = ''; // KCP 실제 거래 금액
		$result['total_amount'] = 0; // 복합결제시 총 거래금액
		$result['coupon_mny'] = '';						// 쿠폰금액
		/* = -------------------------------------------------------------------------- = */
		$result['card_cd'] = ''; // 신용카드 코드
		$result['card_name'] = ''; // 신용카드 명
		$result['app_no'] = ''; // 신용카드 승인번호
		$result['noinf'] = ''; // 신용카드 무이자 여부
		$result['quota'] = ''; // 신용카드 할부개월
		$result['partcanc_yn'] = '';						// 부분취소 가능유무
		$result['card_bin_type_01'] = ''; // 카드구분1
		$result['card_bin_type_02'] = ''; // 카드구분2
		$result['card_mny'] = '';						// 카드결제금액
		/* = -------------------------------------------------------------------------- = */
		$result['bank_name'] = ''; // 은행명
		$result['bank_code'] = '';						// 은행코드
		$result['bk_mny'] = '';						// 계좌이체결제금액
		/* = -------------------------------------------------------------------------- = */
		$result['bankname'] = ''; // 입금할 은행명
		$result['depositor'] = ''; // 입금할 계좌 예금주 성명
		$result['account'] = ''; // 입금할 계좌 번호
		$result['va_date'] = '';						// 가상계좌 입금마감시간
		/* = -------------------------------------------------------------------------- = */
		$result['pnt_issue'] = ''; // 결제 포인트사 코드
		$result['pt_idno'] = ''; // 결제 및 인증 아이디
		$result['pnt_amount'] = ''; // 적립금액 or 사용금액
		$result['pnt_app_time'] = ''; // 승인시간
		$result['pnt_app_no'] = ''; // 승인번호
		$result['add_pnt'] = ''; // 발생 포인트
		$result['use_pnt'] = ''; // 사용가능 포인트
		$result['rsv_pnt'] = ''; // 총 누적 포인트
		/* = -------------------------------------------------------------------------- = */
		$result['commid'] = ''; // 통신사 코드
		$result['mobile_no'] = ''; // 휴대폰 번호
		/* = -------------------------------------------------------------------------- = */
		$result['tk_shop_id'] = $this->CI->input->post('tk_shop_id', null, ''); // 가맹점 고객 아이디
		$result['tk_van_code'] = ''; // 발급사 코드
		$result['tk_app_no'] = ''; // 상품권 승인 번호
		/* = -------------------------------------------------------------------------- = */
		$result['cash_yn'] = $this->CI->input->post('cash_yn', null, ''); // 현금영수증 등록 여부
		$result['cash_authno'] = ''; // 현금 영수증 승인 번호
		$result['cash_tr_code'] = $this->CI->input->post('cash_tr_code', null, ''); // 현금 영수증 발행 구분
		$result['cash_id_info'] = $this->CI->input->post('cash_id_info', null, ''); // 현금 영수증 등록 번호
		/* ============================================================================== */
		/* = 01-1. 에스크로 지불 요청 정보 설정 = */
		/* = -------------------------------------------------------------------------- = */
		$result['escw_used'] = $this->CI->input->post('escw_used', null, ''); // 에스크로 사용 여부
		$result['pay_mod'] = $this->CI->input->post('pay_mod', null, ''); // 에스크로 결제처리 모드
		$result['deli_term'] = $this->CI->input->post('deli_term', null, ''); // 배송 소요일
		$result['bask_cntx'] = $this->CI->input->post('bask_cntx', null, ''); // 장바구니 상품 개수
		$result['good_info'] = $this->CI->input->post('good_info', null, ''); // 장바구니 상품 상세 정보
		$result['rcvr_name'] = addslashes($this->CI->input->post('rcvr_name', null, '')); // 수취인 이름
		$result['rcvr_tel1'] = $this->CI->input->post('rcvr_tel1', null, ''); // 수취인 전화번호
		$result['rcvr_tel2'] = $this->CI->input->post('rcvr_tel2', null, ''); // 수취인 휴대폰번호
		$result['rcvr_mail'] = $this->CI->input->post('rcvr_mail', null, ''); // 수취인 E-Mail
		$result['rcvr_zipx'] = $this->CI->input->post('rcvr_zipx', null, ''); // 수취인 우편번호
		$result['rcvr_add1'] = addslashes($this->CI->input->post('rcvr_add1', null, '')); // 수취인 주소
		$result['rcvr_add2'] = addslashes($this->CI->input->post('rcvr_add2', null, '')); // 수취인 상세주소
		$result['escw_yn'] = '';						// 에스크로 여부

		$result['vcnt_yn'] = $this->CI->input->post('vcnt_yn', null, '');
		$result['trace_no'] = $this->CI->input->post('trace_no', null, '');

		/* ============================================================================== */
		/* = 02. 인스턴스 생성 및 초기화 = */
		/* = -------------------------------------------------------------------------- = */
		/* = 결제에 필요한 인스턴스를 생성하고 초기화 합니다. = */
		/* = -------------------------------------------------------------------------- = */
		$c_PayPlus = new C_PP_CLI;

		$c_PayPlus->mf_clear();
		/* ============================================================================== */


		/* ============================================================================== */
		/* = 03. 처리 요청 정보 설정, 실행 = */
		/* = -------------------------------------------------------------------------- = */

		/* = -------------------------------------------------------------------------- = */
		/* = 03-1. 승인 요청 = */
		/* = -------------------------------------------------------------------------- = */
		if ($result['req_tx'] === 'pay') {

			$c_PayPlus->mf_set_ordr_data('ordr_mony', $result['good_mny']);
			$c_PayPlus->mf_set_encx_data($this->CI->input->post('enc_data', null, ''), $this->CI->input->post('enc_info', null, ''));

		}

		/* = -------------------------------------------------------------------------- = */
		/* = 03-2. 취소/매입 요청 = */
		/* = -------------------------------------------------------------------------- = */
		elseif ($result['req_tx'] === 'mod') {

			$result['tran_cd'] = '00200000';

			$c_PayPlus->mf_set_modx_data('tno', $result['tno']); // KCP 원거래 거래번호
			$c_PayPlus->mf_set_modx_data('mod_type', $result['mod_type']); // 원거래 변경 요청 종류
			$c_PayPlus->mf_set_modx_data('mod_ip', $result['cust_ip']); // 변경 요청자 IP
			$c_PayPlus->mf_set_modx_data('mod_desc', $result['mod_desc']); // 변경 사유
		}

		/* = -------------------------------------------------------------------------- = */
		/* = 03-3. 에스크로 상태변경 요청 = */
		/* = -------------------------------------------------------------------------- = */
		elseif ($result['req_tx'] = 'mod_escrow') {
			$result['tran_cd'] = '00200000';

			$c_PayPlus->mf_set_modx_data('tno', $result['tno']);						// KCP 원거래 거래번호
			$c_PayPlus->mf_set_modx_data('mod_type', $result['mod_type']);						// 원거래 변경 요청 종류
			$c_PayPlus->mf_set_modx_data('mod_ip', $result['cust_ip']);						// 변경 요청자 IP
			$c_PayPlus->mf_set_modx_data('mod_desc', $result['mod_desc']);						// 변경 사유

			if ($result['mod_type'] === 'STE1') {												// 상태변경 타입이 [배송요청]인 경우
				$c_PayPlus->mf_set_modx_data('deli_numb', $this->CI->input->post('deli_numb', null, '')); // 운송장 번호
				$c_PayPlus->mf_set_modx_data('deli_corp', $this->CI->input->post('deli_corp', null, '')); // 택배 업체명
			} elseif ($result['mod_type'] === 'STE2' || $result['mod_type'] === 'STE4') { // 상태변경 타입이 [즉시취소] 또는 [취소]인 계좌이체, 가상계좌의 경우
				if ($result['vcnt_yn'] === 'Y') {
					$c_PayPlus->mf_set_modx_data('refund_account', $this->CI->input->post('refund_account', null, '')); // 환불수취계좌번호
					$c_PayPlus->mf_set_modx_data('refund_nm', $this->CI->input->post('refund_nm', null, '')); // 환불수취계좌주명
					$c_PayPlus->mf_set_modx_data('bank_code', $this->CI->input->post('bank_code', null, '')); // 환불수취은행코드
				}
			}
		}

		/* = -------------------------------------------------------------------------- = */
		/* = 04. 실행 = */
		/* = -------------------------------------------------------------------------- = */
		if ($result['tran_cd']) {
		$c_PayPlus->mf_do_tx($result['trace_no'], element('pg_conf_home_dir', $config), element('pg_kcp_mid', $config), element('pg_kcp_key', $config), $result['tran_cd'], '',
								element('pg_conf_gw_url', $config), element('pg_conf_gw_port', $config), 'payplus_cli_slib', $result['ordr_idxx'],
								$result['cust_ip'], '3', 0, 0, element('pg_conf_key_dir', $config), element('pg_conf_log_dir', $config)); // 응답 전문 처리
			$result['res_cd'] = $c_PayPlus->m_res_cd; // 결과 코드
			$result['res_msg'] = $c_PayPlus->m_res_msg; // 결과 메시지
			/* $result['res_en_msg'] = $c_PayPlus->mf_get_res_data('res_en_msg'); // 결과 영문 메세지 */
		} else {
			$c_PayPlus->m_res_cd = '9562';
			$c_PayPlus->m_res_msg = '연동 오류|Payplus Plugin이 설치되지 않았거나 tran_cd값이 설정되지 않았습니다.';
		}

		if ($result['res_cd'] !== '0000') {
			$result['res_msg'] = iconv('euc-kr', 'utf-8', $result['res_msg']);
			alert($result['res_cd'] . ' : ' . $result['res_msg']);
		}


		/* ============================================================================== */


		/* ============================================================================== */
		/* = 05. 승인 결과 처리 = */
		/* = -------------------------------------------------------------------------- = */
		if ($result['req_tx'] === 'pay') {
			if ($result['res_cd'] === '0000') {
				$result['tno'] = $c_PayPlus->mf_get_res_data('tno'); // KCP 거래 고유 번호
				$result['amount'] = $c_PayPlus->mf_get_res_data('amount'); // KCP 실제 거래 금액
				$result['pnt_issue'] = $c_PayPlus->mf_get_res_data('pnt_issue'); // 결제 포인트사 코드
				$result['coupon_mny'] = $c_PayPlus->mf_get_res_data('coupon_mny'); // 쿠폰금액

				/* = -------------------------------------------------------------------------- = */
				/* = 05-1. 신용카드 승인 결과 처리 = */
				/* = -------------------------------------------------------------------------- = */
				if ($result['use_pay_method'] === '100000000000') {
					$result['card_cd'] = $c_PayPlus->mf_get_res_data('card_cd'); // 카드사 코드
					$result['card_name'] = iconv('cp949', 'utf-8', $c_PayPlus->mf_get_res_data('card_name')); // 카드 종류
					$result['app_time'] = $c_PayPlus->mf_get_res_data('app_time'); // 승인 시간
					$result['app_no'] = $c_PayPlus->mf_get_res_data('app_no'); // 승인 번호
					$result['noinf'] = $c_PayPlus->mf_get_res_data('noinf'); // 무이자 여부 ('Y' : 무이자)
					$result['quota'] = $c_PayPlus->mf_get_res_data('quota'); // 할부 개월 수
					$result['partcanc_yn'] = $c_PayPlus->mf_get_res_data('partcanc_yn'); // 부분취소 가능유무
					$result['card_bin_type_01'] = $c_PayPlus->mf_get_res_data('card_bin_type_01'); // 카드구분1
					$result['card_bin_type_02'] = $c_PayPlus->mf_get_res_data('card_bin_type_02'); // 카드구분2
					$result['card_mny'] = $c_PayPlus->mf_get_res_data('card_mny'); // 카드결제금액

					/* = -------------------------------------------------------------- = */
					/* = 05-1.1. 복합결제(포인트+신용카드) 승인 결과 처리 = */
					/* = -------------------------------------------------------------- = */
					if ($result['pnt_issue'] === 'SCSK' || $result['pnt_issue'] === 'SCWB') {
						$result['pt_idno'] = $c_PayPlus->mf_get_res_data ('pt_idno'); // 결제 및 인증 아이디
						$result['pnt_amount'] = $c_PayPlus->mf_get_res_data ('pnt_amount'); // 적립금액 or 사용금액
						$result['pnt_app_time'] = $c_PayPlus->mf_get_res_data ('pnt_app_time'); // 승인시간
						$result['pnt_app_no'] = $c_PayPlus->mf_get_res_data ('pnt_app_no'); // 승인번호
						$result['add_pnt'] = $c_PayPlus->mf_get_res_data ('add_pnt'); // 발생 포인트
						$result['use_pnt'] = $c_PayPlus->mf_get_res_data ('use_pnt'); // 사용가능 포인트
						$result['rsv_pnt'] = $c_PayPlus->mf_get_res_data ('rsv_pnt'); // 총 누적 포인트
						$result['total_amount'] = $result['amount'] + $result['pnt_amount']; // 복합결제시 총 거래금액
					}
				}

				/* = -------------------------------------------------------------------------- = */
				/* = 05-2. 계좌이체 승인 결과 처리 = */
				/* = -------------------------------------------------------------------------- = */
				if ($result['use_pay_method'] === '010000000000') {
					$result['app_time'] = $c_PayPlus->mf_get_res_data('app_time'); // 승인 시간
					$result['bank_name'] = iconv('cp949', 'utf-8', $c_PayPlus->mf_get_res_data('bank_name')); // 은행명
					$result['bank_code'] = $c_PayPlus->mf_get_res_data('bank_code'); // 은행코드
					$result['bk_mny'] = $c_PayPlus->mf_get_res_data('bk_mny'); // 계좌이체결제금액
				}

				/* = -------------------------------------------------------------------------- = */
				/* = 05-3. 가상계좌 승인 결과 처리 = */
				/* = -------------------------------------------------------------------------- = */
				if ($result['use_pay_method'] === '001000000000') {
					$result['bankname'] = $c_PayPlus->mf_get_res_data('bankname'); // 입금할 은행 이름
					$result['depositor'] = $c_PayPlus->mf_get_res_data('depositor'); // 입금할 계좌 예금주
					$result['account'] = $c_PayPlus->mf_get_res_data('account'); // 입금할 계좌 번호
					$result['va_date'] = $result['cor_vbank_expire'] = $c_PayPlus->mf_get_res_data('va_date'); // 가상계좌 입금마감시간
				}

				/* = -------------------------------------------------------------------------- = */
				/* = 05-4. 포인트 승인 결과 처리 = */
				/* = -------------------------------------------------------------------------- = */
				if ($result['use_pay_method'] === '000100000000') {
					$result['pt_idno'] = $c_PayPlus->mf_get_res_data('pt_idno'); // 결제 및 인증 아이디
					$result['pnt_amount'] = $c_PayPlus->mf_get_res_data('pnt_amount'); // 적립금액 or 사용금액
					$result['pnt_app_time'] = $c_PayPlus->mf_get_res_data('pnt_app_time'); // 승인시간
					$result['pnt_app_no'] = $c_PayPlus->mf_get_res_data('pnt_app_no'); // 승인번호
					$result['add_pnt'] = $c_PayPlus->mf_get_res_data('add_pnt'); // 발생 포인트
					$result['use_pnt'] = $c_PayPlus->mf_get_res_data('use_pnt'); // 사용가능 포인트
					$result['rsv_pnt'] = $c_PayPlus->mf_get_res_data('rsv_pnt'); // 적립 포인트
				}

				/* = -------------------------------------------------------------------------- = */
				/* = 05-5. 휴대폰 승인 결과 처리 = */
				/* = -------------------------------------------------------------------------- = */
				if ($result['use_pay_method'] === '000010000000') {
					$result['app_time'] = $c_PayPlus->mf_get_res_data('hp_app_time'); // 승인 시간
					$result['commid'] = $c_PayPlus->mf_get_res_data('commid'); // 통신사 코드
					$result['mobile_no'] = $c_PayPlus->mf_get_res_data('mobile_no'); // 휴대폰 번호
				}

				/* = -------------------------------------------------------------------------- = */
				/* = 05-6. 상품권 승인 결과 처리 = */
				/* = -------------------------------------------------------------------------- = */
				if ($result['use_pay_method'] === '000000001000') {
					$result['app_time'] = $c_PayPlus->mf_get_res_data('tk_app_time'); // 승인 시간
					$result['tk_van_code'] = $c_PayPlus->mf_get_res_data('tk_van_code'); // 발급사 코드
					$result['tk_app_no'] = $c_PayPlus->mf_get_res_data('tk_app_no'); // 승인 번호
				}

				/* = -------------------------------------------------------------------------- = */
				/* = 05-7. 현금영수증 결과 처리 = */
				/* = -------------------------------------------------------------------------- = */
				$result['cash_yn'] = $c_PayPlus->mf_get_res_data('cash_yn'); // 현금영수증 등록여부
				$result['cash_authno'] = $c_PayPlus->mf_get_res_data('cash_authno'); // 현금 영수증 승인 번호
				$result['cash_tr_code'] = $c_PayPlus->mf_get_res_data('cash_tr_code'); // 현금영수증 등록구분

				/* = -------------------------------------------------------------------------- = */
				/* = 05-8. 에스크로 여부 결과 처리 = */
				/* = -------------------------------------------------------------------------- = */
				$result['escw_yn'] = $c_PayPlus->mf_get_res_data('escw_yn'); // 에스크로 여부
			}
		}

		return $result;
	}


	public function kcp_pp_ax_hub_cancel($result, $return_msg=false)
	{
		$config = $this->kcp_init();

		include('plugin/pg/kcp/pp_ax_hub_lib.php');

		// locale ko_KR.euc-kr 로 설정
		setlocale(LC_CTYPE, 'ko_KR.euc-kr');

		/* ============================================================================== */
		/* = 07. 승인 결과 DB처리 실패시 : 자동취소 = */
		/* = -------------------------------------------------------------------------- = */
		/* = 승인 결과를 DB 작업 하는 과정에서 정상적으로 승인된 건에 대해 = */
		/* = DB 작업을 실패하여 DB update 가 완료되지 않은 경우, 자동으로 = */
		/* = 승인 취소 요청을 하는 프로세스가 구성되어 있습니다. = */
		/* = = */
		/* = DB 작업이 실패 한 경우, bSucc 라는 변수(String)의 값을 'false' = */
		/* = 로 설정해 주시기 바랍니다. (DB 작업 성공의 경우에는 'false' 이외의 = */
		/* = 값을 설정하시면 됩니다.) = */
		/* = -------------------------------------------------------------------------- = */

		$bSucc = 'false'; // DB 작업 실패 또는 금액 불일치의 경우 'false' 로 세팅

		$c_PayPlus = new C_PP_CLI;

		/* = -------------------------------------------------------------------------- = */
		/* = 07-1. DB 작업 실패일 경우 자동 승인 취소 = */
		/* = -------------------------------------------------------------------------- = */
		if (element('req_tx', $result) === 'pay') {
			if (element('res_cd', $result) === '0000') {
				if ($bSucc === 'false') {
					$c_PayPlus->mf_clear();

					$result['tran_cd'] = '00200000';

					/* ============================================================================== */
					/* = 07-1.자동취소시 에스크로 거래인 경우 = */
					/* = -------------------------------------------------------------------------- = */
					// 취소시 사용하는 mod_type
					$bSucc_mod_type = '';

					if (element('escw_yn', $result) === 'Y' && element('use_pay_method', $result) === '001000000000') {
						// 에스크로 가상계좌 건의 경우 가상계좌 발급취소(STE5)
						$bSucc_mod_type = 'STE5';
					} elseif (element('escw_yn', $result) === 'Y') {
						// 에스크로 가상계좌 이외 건은 즉시취소(STE2)
						$bSucc_mod_type = 'STE2';
					} else {
						// 에스크로 거래 건이 아닌 경우(일반건)(STSC)
						$bSucc_mod_type = 'STSC';
					}
					/* = -------------------------------------------------------------------------- = */
					/* = 07-1. 자동취소시 에스크로 거래인 경우 처리 END = */
					/* = ========================================================================== = */

					$c_PayPlus->mf_set_modx_data('tno', element('tno', $result)); // KCP 원거래 거래번호
					$c_PayPlus->mf_set_modx_data('mod_type', $bSucc_mod_type); // 원거래 변경 요청 종류
					$c_PayPlus->mf_set_modx_data('mod_ip', element('cust_ip', $result)); // 변경 요청자 IP

					$refund_msg = element('refund_msg', $result) ? element('refund_msg', $result) : '결제금액 오류';

					$c_PayPlus->mf_set_modx_data('mod_desc', $refund_msg); // 변경 사유

					$c_PayPlus->mf_do_tx(element('tno', $result), element('pg_conf_home_dir', $config), element('pg_kcp_mid', $config),
										element('pg_kcp_key', $config), $result['tran_cd'], '',
										element('pg_conf_gw_url', $config), element('pg_conf_gw_port', $config), 'payplus_cli_slib',
										$result['ordr_idxx'], element('cust_ip', $result), '3',
										0, 0, element('pg_conf_key_dir', $config), element('pg_conf_log_dir', $config));

					$res_cd = $c_PayPlus->m_res_cd;
					$res_msg = $c_PayPlus->m_res_msg;
				}
			}
		} // End of [res_cd = '0000']
		/* ============================================================================== */

		// locale 설정 초기화
		setlocale(LC_CTYPE, '');

		if( $return_msg ){
			$res_cd = isset( $res_cd ) ? $res_cd : 'fail';

			if( $res_cd == '0000' ){
				return 'success';
			}

			return $res_cd;
		}
	}


	public function xpay_result()
	{
		$config = $this->lg_init();

		$result = array();

		/*
		 * [최종결제요청 페이지(STEP2-2)]
		 *
		 * LG유플러스으로 부터 내려받은 LGD_PAYKEY(인증Key)를 가지고 최종 결제요청.(파라미터 전달시 POST를 사용하세요)
		 */

		/* ※ 중요
		 * 환경설정 파일의 경우 반드시 외부에서 접근이 가능한 경로에 두시면 안됩니다.
		 * 해당 환경파일이 외부에 노출이 되는 경우 해킹의 위험이 존재하므로 반드시 외부에서 접근이 불가능한 경로에 두시기 바랍니다.
		 * 예) [Window 계열] C:\inetpub\wwwroot\lgdacom ==> 절대불가(웹 디렉토리)
		 */

		/*
		 *************************************************
		 * 1.최종결제 요청 - BEGIN
		 * (단, 최종 금액체크를 원하시는 경우 금액체크 부분 주석을 제거 하시면 됩니다.)
		 *************************************************
		 */
		$LGD_PAYKEY = $this->CI->input->post('LGD_PAYKEY', null, '');

		include('plugin/pg/lg/XPayClient.php');
		include('plugin/pg/lg/XPay.php');

		$this->xpay = new XPay(element('configPath', $config), element('CST_PLATFORM', $config));

		// Mert Key 설정
		$this->xpay->set_config_value('t' . element('LGD_MID', $config), element('pg_lg_key', $config));
		$this->xpay->set_config_value(element('LGD_MID', $config), element('pg_lg_key', $config));

		$this->xpay->Init_TX(element('LGD_MID', $config));

		$this->xpay->Set('LGD_TXNAME', 'PaymentByKey');
		$this->xpay->Set('LGD_PAYKEY', $LGD_PAYKEY);

		//금액을 체크하시기 원하는 경우 아래 주석을 풀어서 이용하십시요.
		//$DB_AMOUNT = 'DB나 세션에서 가져온 금액'; //반드시 위변조가 불가능한 곳(DB나 세션)에서 금액을 가져오십시요.
		//$this->xpay->Set('LGD_AMOUNTCHECKYN', 'Y');
		//$this->xpay->Set('LGD_AMOUNT', $DB_AMOUNT);

		/*
		 *************************************************
		 * 1.최종결제 요청(수정하지 마세요) - END
		 *************************************************
		 */

		/*
		 * 2. 최종결제 요청 결과처리
		 *
		 * 최종 결제요청 결과 리턴 파라미터는 연동메뉴얼을 참고하시기 바랍니다.
		 */
		if ($this->xpay->TX()) {
			//1)결제결과 화면처리(성공,실패 결과 처리를 하시기 바랍니다.)
			/*
			echo '결제요청이 완료되었습니다. <br>';
			echo 'TX Response_code = ' . $this->xpay->Response_Code() . '<br>';
			echo 'TX Response_msg = ' . $this->xpay->Response_Msg() . '<p>';

			echo '거래번호 : ' . $this->xpay->Response('LGD_TID',0) . '<br>';
			echo '상점아이디 : ' . $this->xpay->Response('LGD_MID',0) . '<br>';
			echo '상점주문번호 : ' . $this->xpay->Response('LGD_OID',0) . '<br>';
			echo '결제금액 : ' . $this->xpay->Response('LGD_AMOUNT',0) . '<br>';
			echo '결과코드 : ' . $this->xpay->Response('LGD_RESPCODE',0) . '<br>';
			echo '결과메세지 : ' . $this->xpay->Response('LGD_RESPMSG',0) . '<p>';

			$keys = $this->xpay->Response_Names();
			foreach ($keys as $name) {
				echo $name . ' = ' . $this->xpay->Response($name, 0) . '<br>';
			}

			echo '<p>';
			exit;
			 */

			if ('0000' === $this->xpay->Response_Code()) {
				//최종결제요청 결과 성공 DB처리
				$result['tno'] = $this->xpay->Response('LGD_TID',0);
				$result['amount'] = $this->xpay->Response('LGD_AMOUNT',0);
				$result['app_time'] = $this->xpay->Response('LGD_PAYDATE',0);
				$result['bank_name'] = $this->xpay->Response('LGD_FINANCENAME',0);
				$result['depositor'] = $this->xpay->Response('LGD_PAYER',0);
				$result['account'] = $this->xpay->Response('LGD_FINANCENAME',0) . ' ' . $this->xpay->Response('LGD_ACCOUNTNUM',0) . ' ' . $this->xpay->Response('LGD_SAOWNER',0);
				$result['commid'] = $this->xpay->Response('LGD_FINANCENAME',0);
				$result['mobile_no'] = $this->xpay->Response('LGD_TELNO',0);
				$result['app_no'] = $this->xpay->Response('LGD_FINANCEAUTHNUM',0);
				$result['card_name'] = $this->xpay->Response('LGD_FINANCENAME',0);
				$result['pay_type'] = $this->xpay->Response('LGD_PAYTYPE',0);
				$result['escw_yn'] = $this->xpay->Response('LGD_ESCROWYN',0);
			} else {
				//최종결제요청 결과 실패 DB처리
				//echo '최종결제요청 결과 실패 DB처리하시기 바랍니다.<br>';

				alert($this->xpay->Response_Msg() . ' 코드 : ' . $this->xpay->Response_Code());
			}
		} else {
			//2)API 요청실패 화면처리
			/*
			echo '결제요청이 실패하였습니다. <br>';
			echo 'TX Response_code = ' . $this->xpay->Response_Code() . '<br>';
			echo 'TX Response_msg = ' . $this->xpay->Response_Msg() . '<p>';

			//최종결제요청 결과 실패 DB처리
			echo '최종결제요청 결과 실패 DB처리하시기 바랍니다.<br>';
			 */
			alert($this->xpay->Response_Msg() . ' 코드 : ' . $this->xpay->Response_Code());
		}

		return $result;
	}

	public function xpay_admin_cancel($result , $return_msg=false){
		$config = $this->lg_init();

		$LGD_TID = element('tno', $result);

		include('plugin/pg/lg/XPayClient.php');
		include('plugin/pg/lg/XPay.php');

		$this->xpay = new XPay(element('configPath', $config), element('CST_PLATFORM', $config));

		// Mert Key 설정
		$this->xpay->set_config_value('t' . element('LGD_MID', $config), element('pg_lg_key', $config));
		$this->xpay->set_config_value(element('LGD_MID', $config), element('pg_lg_key', $config));

		$this->xpay->Init_TX(element('LGD_MID', $config));

		$this->xpay->Set('LGD_TXNAME', 'Cancel');
		$this->xpay->Set('LGD_TID', $LGD_TID);

		$pg_res_cd = '';

		if ($this->xpay->TX()) {
			$res_cd = $this->xpay->Response_Code();
			if($res_cd != '0000' && $res_cd != 'AV11') {
				$pg_res_cd = $res_cd;
				$pg_res_msg = $this->xpay->Response_Msg();
			}
		} else {
			$pg_res_cd = $this->xpay->Response_Code();
			$pg_res_msg = $this->xpay->Response_Msg();
		}

		if( $return_msg ){
			return ($pg_res_cd == '') ? 'success' : $pg_res_cd;
		}
	}

	public function xpay_cancel($result)
	{
		$this->xpay->Rollback('결제금액 불일치 [TID:' . $this->xpay->Response('LGD_TID',0) . ',MID:' . $this->xpay->Response('LGD_MID',0) . ',OID:' . $this->xpay->Response('LGD_OID',0) . ']');
	}

	public function inipay_mobile_result(){

		$config = $this->inicis_init();

		$mid = element('pg_inicis_mid', $config);
		$P_TID = $this->CI->session->userdata('P_TID');
		$P_AMT = $this->CI->session->userdata('P_AMT');

		// 세션비교
		$hash = md5($P_TID.$mid.$P_AMT);

		if($hash != $this->CI->input->post('P_HASH')) {
			alert('결제 정보가 일치하지 않습니다. 올바른 방법으로 이용해 주십시오.');
		}

		//최종결제요청 결과 성공 DB처리
		$result = array(
			'tno'			 => $P_TID,
			'amount'		  => $P_AMT,
			'app_time'	   => $this->CI->input->post('P_AUTH_DT', true, ''),
			'pay_method'	  => $this->CI->input->post('P_TYPE', true, ''),
			'depositor'	   => $this->CI->input->post('P_UNAME', true, ''),
			'commid'		  => $this->CI->input->post('P_HPP_CORP', true, ''),
			'mobile_no'	   => $this->CI->input->post('P_APPL_NUM', true, ''),
			'app_no'		  => $this->CI->input->post('P_AUTH_NO', true, ''),
			'card_name'	   => $this->CI->input->post('P_CARD_ISSUER', true, ''),
		);

		$pay_method = $result['pay_method'];
		//$result['pay_type'] = $pay_type = element($pay_method, $config['PAY_METHOD']);
		$result['pay_type'] = $pay_type = $this->CI->input->post('pay_type', true, '');

		switch($pay_type) {
			case 'realtime':
			case '계좌이체':
				$result['bank_name'] = $this->CI->input->post('P_VACT_BANK', true, '');
				break;
			case 'vbank':
			case '가상계좌':
				$result['bankname']  = $this->CI->input->post('P_VACT_BANK', true, '');
				$result['account']   = $this->CI->input->post('P_VACT_NUM', true, '').' '.$this->CI->input->post('P_VACT_NAME', true, '');
				$result['app_no']	= $this->CI->input->post('P_VACT_NUM', true, '');
				$result['cor_vbank_expire'] = $this->CI->input->post('P_VACT_DATE', true, '');
				break;
			default:
				break;
		}

		// 세션 초기화
		$this->CI->session->set_userdata('P_TID', '');
		$this->CI->session->set_userdata('P_AMT', '');
		$this->CI->session->set_userdata('P_HASH', '');

		return $result;
	}

	public function inipay_result($agent_type='')
	{

		if( $agent_type == 'mobile' ){
			return $this->inipay_mobile_result();
		}

		$result = array();

		$config = $this->inicis_init();

		$inicis_pay_result = false;

		try {

		require_once('plugin/pg/inicis/libs/HttpClient.php');
		require_once('plugin/pg/inicis/libs/json_lib.php');

			//#############################
			// 인증결과 파라미터 일괄 수신
			//#############################

			//#####################
			// 인증이 성공일 경우만
			//#####################
			if (strcmp('0000', $this->CI->input->post_get('resultCode', null, '')) == 0) {

				//############################################
				// 1.전문 필드 값 설정(***가맹점 개발수정***)
				//############################################

				$charset = 'UTF-8';		// 리턴형식[UTF-8,EUC-KR](가맹점 수정후 고정)

				$format = 'JSON';		// 리턴형식[XML,JSON,NVP](가맹점 수정후 고정)
				// 추가적 noti가 필요한 경우(필수아님, 공백일 경우 미발송, 승인은 성공시, 실패시 모두 Noti발송됨) 미사용
				//String notiUrl	= "";

				$authToken = $this->CI->input->post_get('authToken', null, '');   // 취소 요청 tid에 따라서 유동적(가맹점 수정후 고정)

				$authUrl = $this->CI->input->post_get('authUrl', null, '');   // 승인요청 API url(수신 받은 값으로 설정, 임의 세팅 금지)

				$netCancel = $this->CI->input->post_get('netCancelUrl', null, '');   // 망취소 API url(수신 받은f값으로 설정, 임의 세팅 금지)

				$mid = element('pg_inicis_mid', $config);
				$signKey = element('pg_inicis_sign', $config);
				$timestamp = element('timestamp', $config);
				$util = element('util', $config);
				$BANK_CODE = element('BANK_CODE', $config);

				///$mKey = $util->makeHash(signKey, "sha256"); // 가맹점 확인을 위한 signKey를 해시값으로 변경 (SHA-256방식 사용)
				$mKey = hash("sha256", $signKey);

				//#####################
				// 2.signature 생성
				//#####################
				$signParam = array();
				$signParam['authToken'] = $authToken;  // 필수
				$signParam['timestamp'] = $timestamp;  // 필수
				// signature 데이터 생성 (모듈에서 자동으로 signParam을 알파벳 순으로 정렬후 NVP 방식으로 나열해 hash)
				$signature = $util->makeSignature($signParam);


				//#####################
				// 3.API 요청 전문 생성
				//#####################
				$authMap = array();
				$authMap['mid'] = $mid;   // 필수
				$authMap['authToken'] = $authToken; // 필수
				$authMap['signature'] = $signature; // 필수
				$authMap['timestamp'] = $timestamp; // 필수
				$authMap['charset'] = $charset;  // default=UTF-8
				$authMap['format'] = $format;  // default=XML
				//if(null != notiUrl && notiUrl.length() > 0){
				//  authMap.put("notiUrl"	   ,notiUrl);
				//}


				$this->inicis_authMap = $authMap;

				try {

					$this->httpUtil = new HttpClient();

					//#####################
					// 4.API 통신 시작
					//#####################

					$authResultString = "";
					if ($this->httpUtil->processHTTP($authUrl, $authMap)) {
						$authResultString = $this->httpUtil->body;
					} else {
						echo "Http Connect Error\n";
						echo $this->httpUtil->errormsg;

						throw new Exception("Http Connect Error");
					}

					//############################################################
					//5.API 통신결과 처리(***가맹점 개발수정***)
					//############################################################

					$resultMap = json_decode($authResultString, true);

					$tid = $resultMap['tid'];
					$oid = $resultMap['MOID'];

					/*************************  결제보안 추가 2016-05-18 START ****************************/
					$secureMap = array();
					$secureMap['mid']	   = $mid;						 //mid
					$secureMap['tstamp']	= $timestamp;				   //timestemp
					$secureMap['MOID']	  = $resultMap['MOID'];		   //MOID
					$secureMap['TotPrice']  = $resultMap['TotPrice'];	   //TotPrice

					// signature 데이터 생성
					$secureSignature = $util->makeSignatureAuth($secureMap);
					/*************************  결제보안 추가 2016-05-18 END ****************************/

					$page_return_url  = site_url('cmall/order');

					if ((strcmp('0000', $resultMap['resultCode']) == 0) && (strcmp($secureSignature, $resultMap['authSignature']) == 0) ) { //결제보안 추가 2016-05-18
						/*						 * ***************************************************************************
						 * 여기에 가맹점 내부 DB에 결제 결과를 반영하는 관련 프로그램 코드를 구현한다.

						  [중요!] 승인내용에 이상이 없음을 확인한 뒤 가맹점 DB에 해당건이 정상처리 되었음을 반영함
						  처리중 에러 발생시 망취소를 한다.
						 * **************************************************************************** */

						//최종결제요청 결과 성공 DB처리
						$tno		= $resultMap['tid'];
						$amount	 = $resultMap['TotPrice'];
						$app_time   = $resultMap['applDate'].$resultMap['applTime'];
						$pay_method = $resultMap['payMethod'];
						$pay_type   = element($pay_method, element('PAY_METHOD', $config));
						$depositor  = element('VACT_InputName', $resultMap);
						$commid	 = '';
						$mobile_no  = $resultMap['HPP_Num'];
						$app_no	 = $resultMap['applNum'];
						$card_code = element('CARD_Code', $resultMap);
						$card_name   = element($card_code, element('CARD_CODE', $config));

						$result = array(
							'tno' => $tno,
							'amount' => $amount,
							'app_time' => $app_time,
							'pay_method' => $pay_method,
							'pay_type' => $pay_type,
							'depositor' => $depositor,
							'commid' => $commid,
							'mobile_no' => $mobile_no,
							'app_no'	=> $app_no,
							'card_code' => $card_code,
							'card_name' => $card_name
							);

						switch($pay_type) {
							case '계좌이체':
								$result['bank_name'] = isset($BANK_CODE[$resultMap['ACCT_BankCode']]) ? $BANK_CODE[$resultMap['ACCT_BankCode']] : '';
								break;
							case '가상계좌':
								$result['bankname']  = isset($BANK_CODE[$resultMap['VACT_BankCode']]) ? $BANK_CODE[$resultMap['VACT_BankCode']] : '';
								$result['account']   = $resultMap['VACT_Num'].' '.$resultMap['VACT_Name'];
								$result['app_no']	= $resultMap['VACT_Num'];
								break;
							default:
								break;
						}

						$inicis_pay_result = true;

					} else {
						$s = '(오류코드:'.$resultMap['resultCode'].') '.$resultMap['resultMsg'];
						alert($s, $page_return_url);
					}

					// 수신결과를 파싱후 resultCode가 "0000"이면 승인성공 이외 실패
					// 가맹점에서 스스로 파싱후 내부 DB 처리 후 화면에 결과 표시
					// payViewType을 popup으로 해서 결제를 하셨을 경우
					// 내부처리후 스크립트를 이용해 opener의 화면 전환처리를 하세요
					//throw new Exception("강제 Exception");
				} catch (Exception $e) {
					//	$s = $e->getMessage() . ' (오류코드:' . $e->getCode() . ')';
					//####################################
					// 실패시 처리(***가맹점 개발수정***)
					//####################################
					//---- db 저장 실패시 등 예외처리----//
					$s = $e->getMessage() . ' (오류코드:' . $e->getCode() . ')';
					echo $s;

					//#####################
					// 망취소 API
					//#####################

					$netcancelResultString = ""; // 망취소 요청 API url(고정, 임의 세팅 금지)
					if ($this->httpUtil->processHTTP($netCancel, $authMap)) {
						$netcancelResultString = $this->httpUtil->body;
					} else {
						echo "Http Connect Error\n";
						echo $this->httpUtil->errormsg;

						throw new Exception("Http Connect Error");
					}

					echo "## 망취소 API 결과 ##";

					$netcancelResultString = str_replace("<", "&lt;", $$netcancelResultString);
					$netcancelResultString = str_replace(">", "&gt;", $$netcancelResultString);

					echo "<pre>", $netcancelResultString . "</pre>";
					// 취소 결과 확인
				}
			} else {

				//#############
				// 인증 실패시
				//#############
				echo "<br/>";
				echo "####인증실패####";

				echo "<pre>" . var_dump($_REQUEST) . "</pre>";
			}
		} catch (Exception $e) {
			$s = $e->getMessage() . ' (오류코드:' . $e->getCode() . ')';
			alert($s);
		}

		if( !$inicis_pay_result ){
			die("<br><br>결제 에러가 일어났습니다. 에러 이유는 위와 같습니다.");
		}

		return $result;

		/*********************
		 * 3. 지불 정보 설정 *
		 *********************/
		$this->inipay->SetField('type', 'securepay'); // 고정 (절대 수정 불가)
		$this->inipay->SetField('pgid', 'INIphp' . element('pgid', $config)); // 고정 (절대 수정 불가)
		$this->inipay->SetField('subpgip', '203.238.3.10'); // 고정 (절대 수정 불가)
		$this->inipay->SetField('admin', $this->CI->session->userdata('INI_ADMIN')); // 키패스워드(상점아이디에 따라 변경)
		$this->inipay->SetField('uid', $uid); // INIpay User ID (절대 수정 불가)
		$this->inipay->SetField('goodname', iconv('utf-8', 'euc-kr', $this->CI->input->post('goodname', null, ''))); // 상품명
		$this->inipay->SetField('currency', $this->CI->input->post('currency', null, '')); // 화폐단위

		$this->inipay->SetField('mid', $this->CI->session->userdata('INI_MID')); // 상점아이디
		$this->inipay->SetField('rn', $this->CI->session->userdata('INI_RN')); // 웹페이지 위변조용 RN값
		$this->inipay->SetField('price', $this->CI->session->userdata('INI_PRICE')); // 가격
		$this->inipay->SetField('enctype', $this->CI->session->userdata('INI_ENCTYPE')); // 고정 (절대 수정 불가)


		/*----------------------------------------------------------------------------------------
		price 등의 중요데이터는
		브라우저상의 위변조여부를 반드시 확인하셔야 합니다.

		결제 요청페이지에서 요청된 금액과
		실제 결제가 이루어질 금액을 반드시 비교하여 처리하십시오.

		설치 메뉴얼 2장의 결제 처리페이지 작성부분의 보안경고 부분을 확인하시기 바랍니다.
		적용참조문서: 이니시스홈페이지->가맹점기술지원자료실->기타자료실 의
					'결제 처리 페이지 상에 결제 금액 변조 유무에 대한 체크' 문서를 참조하시기 바랍니다.
		예제)
		원 상품 가격 변수를 OriginalPrice 하고 원 가격 정보를 리턴하는 함수를 Return_OrgPrice()라 가정하면
		다음 같이 적용하여 원가격과 웹브라우저에서 Post되어 넘어온 가격을 비교 한다.

		$OriginalPrice = Return_OrgPrice();
		$PostPrice = $_SESSION['INI_PRICE'];
		if ($OriginalPrice !== $PostPrice)
		{
			//결제 진행을 중단하고 금액 변경 가능성에 대한 메시지 출력 처리
			//처리 종료
		}

		----------------------------------------------------------------------------------------*/
		$this->inipay->SetField('buyername', iconv('utf-8', 'euc-kr', $this->CI->input->post('buyername', null, ''))); // 구매자 명
		$this->inipay->SetField('buyertel', $this->CI->input->post('buyertel', null, '')); // 구매자 연락처(휴대폰 번호 또는 유선전화번호)
		$this->inipay->SetField('buyeremail', $this->CI->input->post('buyeremail', null, '')); // 구매자 이메일 주소
		$this->inipay->SetField('paymethod', $this->CI->input->post('paymethod', null, '')); // 지불방법 (절대 수정 불가)
		$this->inipay->SetField('encrypted', $this->CI->input->post('encrypted', null, '')); // 암호문
		$this->inipay->SetField('sessionkey', $this->CI->input->post('sessionkey', null, '')); // 암호문
		$this->inipay->SetField('url', site_url()); // 실제 서비스되는 상점 SITE URL로 변경할것
		$this->inipay->SetField('cardcode', $this->CI->input->post('cardcode', null, '')); // 카드코드 리턴
		$this->inipay->SetField('parentemail', $this->CI->input->post('parentemail', null, '')); // 보호자 이메일 주소(핸드폰, 전화결제시에 14세 미만의 고객이 결제하면 부모 이메일로 결제 내용통보 의무, 다른결제 수단 사용시에 삭제 가능)

		/*-----------------------------------------------------------------*
		 * 수취인 정보 * *
		 *-----------------------------------------------------------------*
		 * 실물배송을 하는 상점의 경우에 사용되는 필드들이며 *
		 * 아래의 값들은 INIsecurepay.html 페이지에서 포스트 되도록 *
		 * 필드를 만들어 주도록 하십시요. *
		 * 컨텐츠 제공업체의 경우 삭제하셔도 무방합니다. *
		 *-----------------------------------------------------------------*/
		$this->inipay->SetField('recvname', iconv('utf-8', 'euc-kr', $this->CI->input->post('recvname', null, ''))); // 수취인 명
		$this->inipay->SetField('recvtel', $this->CI->input->post('recvtel', null, '')); // 수취인 연락처
		$this->inipay->SetField('recvaddr', iconv('utf-8', 'euc-kr', $this->CI->input->post('recvaddr', null, ''))); // 수취인 주소
		$this->inipay->SetField('recvpostnum', $this->CI->input->post('recvpostnum', null, '')); // 수취인 우편번호
		$this->inipay->SetField('recvmsg', $this->CI->input->post('recvmsg', null, '')); // 전달 메세지

		$this->inipay->SetField('joincard', $this->CI->input->post('joincard', null, '')); // 제휴카드코드
		$this->inipay->SetField('joinexpire', $this->CI->input->post('joinexpire', null, '')); // 제휴카드유효기간
		$this->inipay->SetField('id_customer', $this->CI->input->post('id_customer', null, '')); //user_id


		/****************
		 * 4. 지불 요청 *
		 ****************/
		$this->inipay->startAction();

		/****************************************************************************************************************
		 * 5. 결제 결과
		 *
		 * 1 모든 결제 수단에 공통되는 결제 결과 데이터
		 * 거래번호 : $inipay->GetResult('TID')
		 * 결과코드 : $inipay->GetResult('ResultCode') ('00'이면 지불 성공)
		 * 결과내용 : $inipay->GetResult('ResultMsg') (지불결과에 대한 설명)
		 * 지불방법 : $inipay->GetResult('PayMethod') (매뉴얼 참조)
		 * 상점주문번호 : $inipay->GetResult('MOID')
		 * 결제완료금액 : $inipay->GetResult('TotPrice')
		 *
		 * 결제 되는 금액 =>원상품가격과 결제결과금액과 비교하여 금액이 동일하지 않다면
		 * 결제 금액의 위변조가 의심됨으로 정상적인 처리가 되지않도록 처리 바랍니다. (해당 거래 취소 처리)
		 *
		 *
		 * 2. 신용카드,ISP,핸드폰, 전화 결제, 은행계좌이체, OK CASH BAG Point 결제 결과 데이터
		 * (무통장입금, 문화 상품권 포함)
		 * 이니시스 승인날짜 : $inipay->GetResult('ApplDate') (YYYYMMDD)
		 * 이니시스 승인시각 : $inipay->GetResult('ApplTime') (HHMMSS)
		 *
		 * 3. 신용카드 결제 결과 데이터
			 *
		 * 신용카드 승인번호 : $inipay->GetResult('ApplNum')
		 * 할부기간 : $inipay->GetResult('CARD_Quota')
		 * 무이자할부 여부 : $inipay->GetResult('CARD_Interest') ('1'이면 무이자할부)
		 * 신용카드사 코드 : $inipay->GetResult('CARD_Code') (매뉴얼 참조)
		 * 카드발급사 코드 : $inipay->GetResult('CARD_BankCode') (매뉴얼 참조)
		 * 본인인증 수행여부 : $inipay->GetResult('CARD_AuthType') ('00'이면 수행)
		 * 각종 이벤트 적용 여부 : $inipay->GetResult('EventCode')
		 *
		 * ** 달러결제 시 통화코드와 환률 정보 **
		 * 해당 통화코드 : $inipay->GetResult('OrgCurrency')
		 * 환율 : $inipay->GetResult('ExchangeRate')
		 *
		 * 아래는 '신용카드 및 OK CASH BAG 복합결제' 또는'신용카드 지불시에 OK CASH BAG적립'시에 추가되는 데이터
		 * OK Cashbag 적립 승인번호 : $inipay->GetResult('OCB_SaveApplNum')
		 * OK Cashbag 사용 승인번호 : $inipay->GetResult('OCB_PayApplNum')
		 * OK Cashbag 승인일시 : $inipay->GetResult('OCB_ApplDate') (YYYYMMDDHHMMSS)
		 * OCB 카드번호 : $inipay->GetResult('OCB_Num')
		 * OK Cashbag 복합결재시 신용카드 지불금액 : $inipay->GetResult('CARD_ApplPrice')
		 * OK Cashbag 복합결재시 포인트 지불금액 : $inipay->GetResult('OCB_PayPrice')
		 *
		 * 4. 실시간 계좌이체 결제 결과 데이터
		 *
		 * 은행코드 : $inipay->GetResult('ACCT_BankCode')
		 * 현금영수증 발행결과코드 : $inipay->GetResult('CSHR_ResultCode')
		 * 현금영수증 발행구분코드 : $inipay->GetResult('CSHR_Type')
		 * *
		 * 5. OK CASH BAG 결제수단을 이용시에만 결제 결과 데이터
		 * OK Cashbag 적립 승인번호 : $inipay->GetResult('OCB_SaveApplNum')
		 * OK Cashbag 사용 승인번호 : $inipay->GetResult('OCB_PayApplNum')
		 * OK Cashbag 승인일시 : $inipay->GetResult('OCB_ApplDate') (YYYYMMDDHHMMSS)
		 * OCB 카드번호 : $inipay->GetResult('OCB_Num')
		 *
			 * 6. 무통장 입금 결제 결과 데이터 *
		 * 가상계좌 채번에 사용된 주민번호 : $inipay->GetResult('VACT_RegNum') *
		 * 가상계좌 번호 : $inipay->GetResult('VACT_Num') *
		 * 입금할 은행 코드 : $inipay->GetResult('VACT_BankCode') *
		 * 입금예정일 : $inipay->GetResult('VACT_Date') (YYYYMMDD) *
		 * 송금자 명 : $inipay->GetResult('VACT_InputName') *
		 * 예금주 명 : $inipay->GetResult('VACT_Name') *
		 * *
		 * 7. 핸드폰, 전화 결제 결과 데이터('실패 내역 자세히 보기'에서 필요, 상점에서는 필요없는 정보임) *
			 * 전화결제 사업자 코드 : $inipay->GetResult('HPP_GWCode') *
		 * *
		 * 8. 핸드폰 결제 결과 데이터 *
		 * 휴대폰 번호 : $inipay->GetResult('HPP_Num') (핸드폰 결제에 사용된 휴대폰번호) *
		 * *
		 * 9. 전화 결제 결과 데이터 *
		 * 전화번호 : $inipay->GetResult('ARSB_Num') (전화결제에 사용된 전화번호) *
		 * *
		 * 10. 문화 상품권 결제 결과 데이터 *
		 * 컬쳐 랜드 ID : $inipay->GetResult('CULT_UserID') *
		 * *
		 * 11. K-merce 상품권 결제 결과 데이터 (K-merce ID, 틴캐시 아이디 공통사용) *
		 * K-merce ID : $inipay->GetResult('CULT_UserID') *
		 * *
		 * 12. 모든 결제 수단에 대해 결제 실패시에만 결제 결과 데이터 *
		 * 에러코드 : $inipay->GetResult('ResultErrorCode') *
		 * *
		 * 13.현금영수증 발급 결과코드 (은행계좌이체시에만 리턴) *
		 * $inipay->GetResult('CSHR_ResultCode') *
		 * *
		 * 14.틴캐시 잔액 데이터 *
		 * $inipay->GetResult('TEEN_Remains') *
		 * 틴캐시 ID : $inipay->GetResult('CULT_UserID') *
		 * 15.게임문화 상품권 *
		 * 사용 카드 갯수 : $inipay->GetResult('GAMG_Cnt') *
		 * *
		 ****************************************************************************************************************/

		if ($this->inipay->GetResult('ResultCode') === '00') {
			//최종결제요청 결과 성공 DB처리
			$result['tno'] = $this->inipay->GetResult('TID');
			$result['amount'] = $this->inipay->GetResult('TotPrice');
			$result['app_time'] = $this->inipay->GetResult('ApplDate') . $this->inipay->GetResult('ApplTime');
			$result['pay_method'] = $pay_method = $this->inipay->GetResult('PayMethod');
			$result['pay_type'] = $pay_type = $config['PAY_METHOD'][$pay_method];
			$result['depositor'] = iconv('euc-kr', 'utf-8', $this->inipay->GetResult('VACT_InputName'));
			$result['commid'] = '';
			$result['mobile_no'] = $this->inipay->GetResult('HPP_Num');
			$result['app_no'] = $this->inipay->GetResult('ApplNum');
			$result['card_name'] = $config['CARD_CODE'][$this->inipay->GetResult('CARD_Code')];
			switch ($pay_type) {
				case '계좌이체':
					$result['bank_name'] = $config['BANK_CODE'][$this->inipay->GetResult('ACCT_BankCode')];
					break;
				case '가상계좌':
					$result['bankname'] = $config['BANK_CODE'][$this->inipay->GetResult('VACT_BankCode')];
					$result['account'] = $this->inipay->GetResult('VACT_Num') . ' ' . iconv('euc-kr', 'utf-8', $this->inipay->GetResult('VACT_Name'));
					$result['app_no'] = $this->inipay->GetResult('VACT_Num');
					break;
				default:
					break;
			}
			return $result;
		} else {
			alert(iconv('euc-kr', 'utf-8', $this->inipay->GetResult('ResultMsg')) . ' 코드 : ' . $this->inipay->GetResult('ResultCode'));
		}
	}

	public function inipay_admin_cancel($result, $return_msg=false){
		$config = $this->inicis_init();

		/*********************
		 * 3. 취소 정보 설정 *
		 *********************/
		$this->inipay->SetField("type",	  "cancel");						// 고정 (절대 수정 불가)
		$this->inipay->SetField("mid",	   element('pg_inicis_mid', $config));	   // 상점아이디
		/**************************************************************************************************
		 * admin 은 키패스워드 변수명입니다. 수정하시면 안됩니다. 1111의 부분만 수정해서 사용하시기 바랍니다.
		 * 키패스워드는 상점관리자 페이지(https://iniweb.inicis.com)의 비밀번호가 아닙니다. 주의해 주시기 바랍니다.
		 * 키패스워드는 숫자 4자리로만 구성됩니다. 이 값은 키파일 발급시 결정됩니다.
		 * 키패스워드 값을 확인하시려면 상점측에 발급된 키파일 안의 readme.txt 파일을 참조해 주십시오.
		 **************************************************************************************************/
		$this->inipay->SetField("admin",	 element('pg_inicis_key', $config)); //비대칭 사용키 키패스워드
		$this->inipay->SetField("tid",	   element('tno', $result));				   // 취소할 거래의 거래아이디
		$this->inipay->SetField("cancelmsg", element('refund_msg', $result));					 // 취소사유
		$this->inipay->SetField("log", "false");					 // 취소로그를 생성하지 않습니다.

		/****************
		 * 4. 취소 요청 *
		 ****************/
		$this->inipay->startAction();

		/****************************************************************
		 * 5. 취소 결과										   	*
		 *															*
		 * 결과코드 : $inipay->getResult('ResultCode') ("00"이면 취소 성공)  	*
		 * 결과내용 : $inipay->getResult('ResultMsg') (취소결과에 대한 설명) 	*
		 * 취소날짜 : $inipay->getResult('CancelDate') (YYYYMMDD)		  	*
		 * 취소시각 : $inipay->getResult('CancelTime') (HHMMSS)				*
		 * 현금영수증 취소 승인번호 : $inipay->getResult('CSHR_CancelNum')	*
		 * (현금영수증 발급 취소시에만 리턴됨)						  *
		 ****************************************************************/

		$res_cd  = $this->inipay->getResult('ResultCode');
		$res_msg = $this->inipay->getResult('ResultMsg');

		$pg_res_cd = '';

		if($res_cd != '00') {

			$pg_res_cd = $res_cd;
			$pg_res_msg = iconv('euc-kr', 'utf-8', $res_msg);

			log_message('error', '이니시스 취소 에러: '.$res_cd.' 이유 : '.$pg_res_msg);
		}

		if( $return_msg ){
			return ($pg_res_cd == '') ? 'success' : $pg_res_cd;
		}
	}

	public function inipay_cancel($result, $agent_type='')
	{
		/*******************************************************************
		 * 7. DB연동 실패 시 강제취소 *
		 * *
		 * 지불 결과를 DB 등에 저장하거나 기타 작업을 수행하다가 실패하는 *
		 * 경우, 아래의 코드를 참조하여 이미 지불된 거래를 취소하는 코드를 *
		 * 작성합니다. *
		 *******************************************************************/

		$cancelFlag = 'true';

		// $cancelFlag를 'ture'로 변경하는 condition 판단은 개별적으로
		// 수행하여 주십시오.

		if ($cancelFlag === 'true') {

			if( $agent_type === 'mobile' ){	 // 모바일

				$result['refund_msg'] = 'DB FAIL';	// 취소사유

				$this->inipay_admin_cancel($result);

			} else {	// PC

				$netCancel = $this->CI->input->post_get('netCancelUrl', null, '');   // 망취소 API url(수신 받은f값으로 설정, 임의 세팅 금지)
				$this->httpUtil->processHTTP($netCancel, $this->inicis_authMap);

			}

		}
	}
}