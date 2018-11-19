<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Payment class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * Payment 담당하는 controller 입니다.
 */
class Payment extends CB_Controller
{

	/**
	 * 모델을 로딩합니다
	 */
	protected $models = array();

	/**
	 * 헬퍼를 로딩합니다
	 */
	protected $helpers = array('form', 'array', 'cmall');
	private $CI;

	function __construct()
	{
		parent::__construct();

		$this->CI = & get_instance();

		/**
		 * 라이브러리를 로딩합니다
		 */
		$this->load->library(array('querystring', 'email'));
	}


	public function kcp_return_result()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_payment_kcp_return_result';
		$this->load->event($eventname);

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		if ( ! $this->cbconfig->item('use_pg_test')) {
			switch ($this->input->ip_address()) {
				case '203.238.36.58' :
				case '203.238.36.160' :
				case '203.238.36.161' :
				case '203.238.36.173' :
				case '203.238.36.178' :
					break;
				default :
					$this->load->model(array('Member_model'));
					$select = 'mem_id, mem_email, mem_nickname, mem_phone';
					$superadminlist = $this->Member_model->get_superadmin_list($select);
					$egpcs_str = 'ENV[' . serialize($_ENV) . '] '
						. 'GET[' . serialize($_GET) . ']'
						. 'POST[' . serialize($_POST) . ']'
						. 'COOKIE[' . serialize($_COOKIE) . ']'
						. 'SESSION[' . serialize($_SESSION) . ']';

					foreach ($superadminlist as $akey => $aval) {
						$this->email->clear(true);
						$this->email->from($this->cbconfig->item('webmaster_email'), $this->cbconfig->item('webmaster_name'));
						$this->email->to(element('mem_email', $aval));

						$emailsubject = '올바르지 않은 접속이 발견되었습니다';
						$this->email->subject($emailsubject);

						$message = $this->input->server('PHP_SELF') . ' 에 ' . $this->input->ip_address() . ' 이 ' . cdate('Y-m-d H:i:s') . " 에 접속을 시도하였습니다.\n\n" . $egpcs_str;
						$this->email->message($message);
						$this->email->set_mailtype('text');

						$this->email->send();
					}
					exit;
			}
		}

		/* ============================================================================== */
		/* = PAGE : 공통 통보 PAGE = */
		/* = -------------------------------------------------------------------------- = */
		/* = Copyright (c) 2006 KCP Inc. All Rights Reserverd. = */
		/* ============================================================================== */

		/* ============================================================================== */
		/* = 01. 공통 통보 페이지 설명(필독!!) = */
		/* = -------------------------------------------------------------------------- = */
		/* = 에스크로 서비스의 경우, 가상계좌 입금 통보 데이터와 가상계좌 환불 = */
		/* = 통보 데이터, 구매확인/구매취소 통보 데이터, 배송시작 통보 데이터 등을 = */
		/* = KCP 를 통해 별도로 통보 받을 수 있습니다. 이러한 통보 데이터를 받기 = */
		/* = 위해 가맹점측은 결과를 전송받는 페이지를 마련해 놓아야 합니다. = */
		/* = 현재의 페이지를 업체에 맞게 수정하신 후, KCP 관리자 페이지에 등록해 = */
		/* = 주시기 바랍니다. 등록 방법은 연동 매뉴얼을 참고하시기 바랍니다. = */
		/* ============================================================================== */


		/* ============================================================================== */
		/* = 02. 공통 통보 데이터 받기 = */
		/* = -------------------------------------------------------------------------- = */
		$site_cd = $this->input->post('site_cd', null, ''); // 사이트 코드
		$tno = $this->input->post('tno', null, ''); // KCP 거래번호
		$order_no = $this->input->post('order_no', null, ''); // 주문번호
		$tx_cd = $this->input->post('tx_cd', null, ''); // 업무처리 구분 코드
		$tx_tm = $this->input->post('tx_tm', null, ''); // 업무처리 완료 시간
		/* = -------------------------------------------------------------------------- = */
		$ipgm_name = ''; // 주문자명
		$remitter = ''; // 입금자명
		$ipgm_mnyx = ''; // 입금 금액
		$bank_code = ''; // 은행코드
		$account = ''; // 가상계좌 입금계좌번호
		$op_cd = ''; // 처리구분 코드
		$noti_id = ''; // 통보 아이디
		/* = -------------------------------------------------------------------------- = */
		$refund_nm = ''; // 환불계좌주명
		$refund_mny = ''; // 환불금액
		$bank_code = ''; // 은행코드
		/* = -------------------------------------------------------------------------- = */
		$st_cd = ''; // 구매확인 코드
		$can_msg = ''; // 구매취소 사유
		/* = -------------------------------------------------------------------------- = */
		$waybill_no = ''; // 운송장 번호
		$waybill_corp = ''; // 택배 업체명

		/* = -------------------------------------------------------------------------- = */
		/* = 02-1. 가상계좌 입금 통보 데이터 받기 = */
		/* = -------------------------------------------------------------------------- = */
		if ($tx_cd === 'TX00') {
			$ipgm_name = $this->input->post('ipgm_name', null, ''); // 주문자명
			$remitter = $this->input->post('remitter', null, ''); // 입금자명
			$ipgm_mnyx = $this->input->post('ipgm_mnyx', null, ''); // 입금 금액
			$bank_code = $this->input->post('bank_code', null, ''); // 은행코드
			$account = $this->input->post('account', null, ''); // 가상계좌 입금계좌번호
			$op_cd = $this->input->post('op_cd', null, ''); // 처리구분 코드
			$noti_id = $this->input->post('noti_id', null, ''); // 통보 아이디
		}

		/* = -------------------------------------------------------------------------- = */
		/* = 02-2. 가상계좌 환불 통보 데이터 받기 = */
		/* = -------------------------------------------------------------------------- = */
		elseif ($tx_cd === 'TX01') {
			$refund_nm = $this->input->post('refund_nm', null, ''); // 환불계좌주명
			$refund_mny = $this->input->post('refund_mny', null, ''); // 환불금액
			$bank_code = $this->input->post('bank_code', null, ''); // 은행코드
		}

		/* = -------------------------------------------------------------------------- = */
		/* = 02-3. 구매확인/구매취소 통보 데이터 받기 = */
		/* = -------------------------------------------------------------------------- = */
		elseif ($tx_cd === 'TX02') {
			$st_cd = $this->input->post('st_cd', null, ''); // 구매확인 코드

			if ($st_cd === 'N') { // 구매확인 상태가 구매취소인 경우
				$can_msg = $this->input->post('can_msg', null, ''); // 구매취소 사유
			}
		}

		/* = -------------------------------------------------------------------------- = */
		/* = 02-4. 배송시작 통보 데이터 받기 = */
		/* = -------------------------------------------------------------------------- = */
		elseif ($tx_cd === 'TX03') {
			$waybill_no = $this->input->post('waybill_no', null, ''); // 운송장 번호
			$waybill_corp = $this->input->post('waybill_corp', null, ''); // 택배 업체명
		}
		/* ============================================================================== */


		/* ============================================================================== */
		/* = 03. 공통 통보 결과를 업체 자체적으로 DB 처리 작업하시는 부분입니다. = */
		/* = -------------------------------------------------------------------------- = */
		/* = 통보 결과를 DB 작업 하는 과정에서 정상적으로 통보된 건에 대해 DB 작업을 = */
		/* = 실패하여 DB update 가 완료되지 않은 경우, 결과를 재통보 받을 수 있는 = */
		/* = 프로세스가 구성되어 있습니다. 소스에서 result 라는 Form 값을 생성 하신 = */
		/* = 후, DB 작업이 성공 한 경우, result 의 값을 "0000" 로 세팅해 주시고, = */
		/* = DB 작업이 실패 한 경우, result 의 값을 "0000" 이외의 값으로 세팅해 주시 = */
		/* = 기 바랍니다. result 값이 "0000" 이 아닌 경우에는 재통보를 받게 됩니다. = */
		/* = -------------------------------------------------------------------------- = */

		/* = -------------------------------------------------------------------------- = */
		/* = 03-1. 가상계좌 입금 통보 데이터 DB 처리 작업 부분 = */
		/* = -------------------------------------------------------------------------- = */
		if ($tx_cd === 'TX00') {
			$this->load->model(array('Deposit_model', 'Cmall_order_model'));
			$where = array(
				'dep_id' => $order_no,
				'dep_tno' => $tno,
				'dep_status' => 0,
			);
			$deposit = $this->Deposit_model->get_one('', '', $where);

			if (element('dep_id', $deposit)) {
				$updatedata = array();
				$updatedata['dep_cash'] = $ipgm_mnyx;
				if ($ipgm_mnyx === element('dep_cash_request', $deposit)) {
					$updatedata['dep_status'] = 1;
					$updatedata['dep_deposit_datetime'] = cdate('Y-m-d H:i:s');
					$this->Deposit_model->update(element('dep_id', $deposit), $updatedata);
				}
			} else {
				$where = array(
					'cor_id' => $order_no,
					'cor_tno' => $tno,
					'cor_status' => 0,
				);
				$order = $this->Cmall_order_model->get_one('', '', $where);

				if (element('cor_id', $order)) {
					$updatedata = array();
					$updatedata['cor_cash'] = $ipgm_mnyx;
					if ($ipgm_mnyx === element('cor_cash_request', $order)) {
						$updatedata['cor_status'] = 1;
						$updatedata['cor_approve_datetime'] = cdate('Y-m-d H:i:s');
						$this->Cmall_order_model->update(element('cor_id', $order), $updatedata);
					}
				}
			}
		}

		/* = -------------------------------------------------------------------------- = */
		/* = 03-2. 가상계좌 환불 통보 데이터 DB 처리 작업 부분 = */
		/* = -------------------------------------------------------------------------- = */
		elseif ($tx_cd === 'TX01') {
		}

		/* = -------------------------------------------------------------------------- = */
		/* = 03-3. 구매확인/구매취소 통보 데이터 DB 처리 작업 부분 = */
		/* = -------------------------------------------------------------------------- = */
		elseif ($tx_cd === 'TX02') {
		}

		/* = -------------------------------------------------------------------------- = */
		/* = 03-4. 배송시작 통보 데이터 DB 처리 작업 부분 = */
		/* = -------------------------------------------------------------------------- = */
		elseif ($tx_cd === 'TX03') {
		}

		/* = -------------------------------------------------------------------------- = */
		/* = 03-5. 정산보류 통보 데이터 DB 처리 작업 부분 = */
		/* = -------------------------------------------------------------------------- = */
		elseif ($tx_cd === 'TX04') {
		}

		/* = -------------------------------------------------------------------------- = */
		/* = 03-6. 즉시취소 통보 데이터 DB 처리 작업 부분 = */
		/* = -------------------------------------------------------------------------- = */
		elseif ($tx_cd === 'TX05') {
		}

		/* = -------------------------------------------------------------------------- = */
		/* = 03-7. 취소 통보 데이터 DB 처리 작업 부분 = */
		/* = -------------------------------------------------------------------------- = */
		elseif ($tx_cd === 'TX06') {
		}

		/* = -------------------------------------------------------------------------- = */
		/* = 03-7. 발급계좌해지 통보 데이터 DB 처리 작업 부분 = */
		/* = -------------------------------------------------------------------------- = */
		elseif ($tx_cd === 'TX07') {
		}
		/* ============================================================================== */


		// 이벤트가 존재하면 실행합니다
		Events::trigger('after', $eventname);


		/* ============================================================================== */
		/* = 04. result 값 세팅 하기 = */
		/* ============================================================================== */
		echo '<html><body><form><input type="hidden" name="result" value="0000"></form></body></html>';
		exit;
	}


	public function lg_return_result()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_payment_lg_return_result';
		$this->load->event($eventname);

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		$this->load->library(array('paymentlib', 'depositlib'));
		$init = $this->paymentlib->lg_init();

		/**
		 * [상점 결제결과처리(DB) 페이지]
		 *
		 * 1) 위변조 방지를 위한 hashdata값 검증은 반드시 적용하셔야 합니다.
		 *
		 */
		$LGD_RESPCODE = $this->input->post('LGD_RESPCODE', null, ''); // 응답코드: 0000(성공) 그외 실패
		$LGD_RESPMSG = $this->input->post('LGD_RESPMSG', null, ''); // 응답메세지
		$LGD_MID = $this->input->post('LGD_MID', null, ''); // 상점아이디
		$LGD_OID = $this->input->post('LGD_OID', null, ''); // 주문번호
		$LGD_AMOUNT = $this->input->post('LGD_AMOUNT', null, ''); // 거래금액
		$LGD_TID = $this->input->post('LGD_TID', null, ''); // LG유플러스에서 부여한 거래번호
		$LGD_PAYTYPE = $this->input->post('LGD_PAYTYPE', null, ''); // 결제수단코드
		$LGD_PAYDATE = $this->input->post('LGD_PAYDATE', null, ''); // 거래일시(승인일시/이체일시)
		$LGD_HASHDATA = $this->input->post('LGD_HASHDATA', null, ''); // 해쉬값
		$LGD_FINANCECODE = $this->input->post('LGD_FINANCECODE', null, ''); // 결제기관코드(은행코드)
		$LGD_FINANCENAME = $this->input->post('LGD_FINANCENAME', null, ''); // 결제기관이름(은행이름)
		$LGD_ESCROWYN = $this->input->post('LGD_ESCROWYN', null, ''); // 에스크로 적용여부
		$LGD_TIMESTAMP = $this->input->post('LGD_TIMESTAMP', null, ''); // 타임스탬프
		$LGD_ACCOUNTNUM = $this->input->post('LGD_ACCOUNTNUM', null, ''); // 계좌번호(무통장입금)
		$LGD_CASTAMOUNT = $this->input->post('LGD_CASTAMOUNT', null, ''); // 입금총액(무통장입금)
		$LGD_CASCAMOUNT = $this->input->post('LGD_CASCAMOUNT', null, ''); // 현입금액(무통장입금)
		$LGD_CASFLAG = $this->input->post('LGD_CASFLAG', null, ''); // 무통장입금 플래그(무통장입금) - 'R':계좌할당, 'I':입금, 'C':입금취소
		$LGD_CASSEQNO = $this->input->post('LGD_CASSEQNO', null, ''); // 입금순서(무통장입금)
		$LGD_CASHRECEIPTNUM = $this->input->post('LGD_CASHRECEIPTNUM', null, ''); // 현금영수증 승인번호
		$LGD_CASHRECEIPTSELFYN = $this->input->post('LGD_CASHRECEIPTSELFYN', null, ''); // 현금영수증자진발급제유무 Y: 자진발급제 적용, 그외 : 미적용
		$LGD_CASHRECEIPTKIND = $this->input->post('LGD_CASHRECEIPTKIND', null, ''); // 현금영수증 종류 0: 소득공제용, 1: 지출증빙용
		$LGD_PAYER = $this->input->post('LGD_PAYER', null, ''); // 입금자명

		/**
		 * 구매정보
		 */
		$LGD_BUYER = $this->input->post('LGD_BUYER', null, ''); // 구매자
		$LGD_PRODUCTINFO = $this->input->post('LGD_PRODUCTINFO', null, ''); // 상품명
		$LGD_BUYERID = $this->input->post('LGD_BUYERID', null, ''); // 구매자 ID
		$LGD_BUYERADDRESS = $this->input->post('LGD_BUYERADDRESS', null, ''); // 구매자 주소
		$LGD_BUYERPHONE = $this->input->post('LGD_BUYERPHONE', null, ''); // 구매자 전화번호
		$LGD_BUYEREMAIL = $this->input->post('LGD_BUYEREMAIL', null, ''); // 구매자 이메일
		$LGD_BUYERSSN = $this->input->post('LGD_BUYERSSN', null, ''); // 구매자 주민번호
		$LGD_PRODUCTCODE = $this->input->post('LGD_PRODUCTCODE', null, ''); // 상품코드
		$LGD_RECEIVER = $this->input->post('LGD_RECEIVER', null, ''); // 수취인
		$LGD_RECEIVERPHONE = $this->input->post('LGD_RECEIVERPHONE', null, ''); // 수취인 전화번호
		$LGD_DELIVERYINFO = $this->input->post('LGD_DELIVERYINFO', null, ''); // 배송지

		$LGD_MERTKEY = element('pg_lg_key', $init); //LG유플러스에서 발급한 상점키로 변경해 주시기 바랍니다.

		$LGD_HASHDATA2 = md5($LGD_MID . $LGD_OID . $LGD_AMOUNT . $LGD_RESPCODE . $LGD_TIMESTAMP . $LGD_MERTKEY);

		/**
		 * 상점 처리결과 리턴메세지
		 *
		 * OK : 상점 처리결과 성공
		 * 그외 : 상점 처리결과 실패
		 *
		 * ※ 주의사항 : 성공시 'OK' 문자이외의 다른문자열이 포함되면 실패처리 되오니 주의하시기 바랍니다.
		 */
		$resultMSG = '결제결과 상점 DB처리(LGD_CASNOTEURL) 결과값을 입력해 주시기 바랍니다.';

		if ($LGD_HASHDATA2 === $LGD_HASHDATA) { //해쉬값 검증이 성공이면
			if ('0000' === $LGD_RESPCODE) { //결제가 성공이면
				if ('R' === $LGD_CASFLAG) {
					/*
					 * 무통장 할당 성공 결과 상점 처리(DB) 부분
					 * 상점 결과 처리가 정상이면 'OK'
					 */
					//if ( 무통장 할당 성공 상점처리결과 성공)
					$resultMSG = 'OK';
				} elseif ('I' === $LGD_CASFLAG) {
					/*
					 * 무통장 입금 성공 결과 상점 처리(DB) 부분
					 * 상점 결과 처리가 정상이면 'OK'
					 */
					$this->load->model(array('Deposit_model', 'Cmall_order_model'));
					$where = array(
						'dep_id' => $LGD_OID,
						'dep_tno' => $LGD_TID,
						'dep_status' => 0,
					);
					$deposit = $this->Deposit_model->get_one('', '', $where);

					if (element('dep_id', $deposit)) {
						$updatedata = array();
						$updatedata['dep_deposit'] = $LGD_AMOUNT;
						$updatedata['dep_cash'] = $LGD_AMOUNT;
						if ($LGD_AMOUNT === element('dep_cash_request', $deposit)) {
							$updatedata['dep_status'] = 1;
							$updatedata['dep_deposit_datetime'] = cdate('Y-m-d H:i:s');

							$sum = $this->Deposit_model->get_deposit_sum(element('mem_id', $deposit));
							$updatedata['dep_deposit_sum'] = $sum + $LGD_AMOUNT;

							$result = $this->Deposit_model->update(element('dep_id', $deposit), $updatedata);

							if( $result ){
								//회원의 예치금 업데이트 합니다.
								$this->depositlib->update_member_deposit( element('mem_id', $deposit) );
							}
						}
					} else {
						$where = array(
							'cor_id' => $LGD_OID,
							'cor_tno' => $LGD_TID,
							'cor_status' => 0,
						);
						$order = $this->Cmall_order_model->get_one('', '', $where);

						if (element('cor_id', $order)) {
							$updatedata = array();
							$updatedata['cor_cash'] = $LGD_AMOUNT;
							if ($LGD_AMOUNT === element('cor_cash_request', $order)) {
								$updatedata['cor_status'] = 1;
								$updatedata['cor_approve_datetime'] = cdate('Y-m-d H:i:s');
								$result = $this->Cmall_order_model->update(element('cor_id', $order), $updatedata);
							}
						}
					}


					//if ( 무통장 입금 성공 상점처리결과 성공)
					if ($result) {
						$resultMSG = 'OK';
					} else {
						$resultMSG = 'DB Error';
					}
				} elseif ('C' === $LGD_CASFLAG) {
					/*
					 * 무통장 입금취소 성공 결과 상점 처리(DB) 부분
					 * 상점 결과 처리가 정상이면 "OK"
					 */
					//if ( 무통장 입금취소 성공 상점처리결과 성공)
					$resultMSG = 'OK';
				}
			} else { //결제가 실패이면
				/*
				 * 거래실패 결과 상점 처리(DB) 부분
				 * 상점결과 처리가 정상이면 'OK'
				 */
				//if ( 결제실패 상점처리결과 성공)
				$resultMSG = 'OK';
			}
		} else { //해쉬값이 검증이 실패이면
			/*
			 * hashdata검증 실패 로그를 처리하시기 바랍니다.
			 */
			$resultMSG = '결제결과 상점 DB처리(LGD_CASNOTEURL) 해쉬값 검증이 실패하였습니다.';
		}

		// 이벤트가 존재하면 실행합니다
		Events::trigger('after', $eventname);

		echo $resultMSG;
		exit;
	}

	public function inicis_return_result()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_payment_inicis_return_result';
		$this->load->event($eventname);

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		$TEMP_IP = $this->input->ip_address();
		//$PG_IP = substr($TEMP_IP,0, 10);
		$PG_IP = $TEMP_IP;
		if ( ! $this->cbconfig->item('use_pg_test')) {
			switch ($PG_IP) {
				case '203.238.37.3' :
				case '203.238.37.15' :
				case '203.238.37.16' :
				case '203.238.37.25' :
				case '39.115.212.9' :
					break;
				default :
					$this->load->model(array('Member_model'));
					$select = 'mem_id, mem_email, mem_nickname, mem_phone';
					$superadminlist = $this->Member_model->get_superadmin_list($select);
					$egpcs_str = 'ENV[' . serialize($_ENV) . '] '
						. 'GET[' . serialize($_GET) . ']'
						. 'POST[' . serialize($_POST) . ']'
						. 'COOKIE[' . serialize($_COOKIE) . ']'
						. 'SESSION[' . serialize($_SESSION) . ']';

					foreach ($superadminlist as $akey => $aval) {
						$this->email->clear(true);
						$this->email->from($this->cbconfig->item('webmaster_email'), $this->cbconfig->item('webmaster_name'));
						$this->email->to(element('mem_email', $aval));

						$emailsubject = '올바르지 않은 접속이 발견되었습니다';
						$this->email->subject($emailsubject);

						$message = $this->input->server('PHP_SELF') . ' 에 ' . $this->input->ip_address() . ' 이 ' . cdate('Y-m-d H:i:s') . " 에 접속을 시도하였습니다.\n\n" . $egpcs_str;
						$this->email->message($message);
						$this->email->set_mailtype('text');
						$this->email->send();
					}
					exit;
			}
		}

		//**********************************************************************************
		//이니시스가 전달하는 가상계좌이체의 결과를 수신하여 DB 처리 하는 부분 입니다.
		//필요한 파라메터에 대한 DB 작업을 수행하십시오.
		//**********************************************************************************

		//**********************************************************************************
		// 이부분에 로그파일 경로를 수정해주세요.

		$INIpayHome = FCPATH . '/plugin/pg/inicis'; // 이니페이 홈디렉터리
		$INIpayLog = false; // 로그를 기록하려면 true 로 수정

		//**********************************************************************************


		$msg_id = $this->input->post('msg_id', null, ''); //메세지 타입
		$no_tid = $this->input->post('no_tid', null, ''); //거래번호
		$no_oid = $this->input->post('no_oid', null, ''); //상점 주문번호
		$id_merchant = $this->input->post('id_merchant', null, ''); //상점 아이디
		$cd_bank = $this->input->post('cd_bank', null, ''); //거래 발생 기관 코드
		$cd_deal = $this->input->post('cd_deal', null, ''); //취급 기관 코드
		$dt_trans = $this->input->post('dt_trans', null, ''); //거래 일자
		$tm_trans = $this->input->post('tm_trans', null, ''); //거래 시간
		$no_msgseq = $this->input->post('no_msgseq', null, ''); //전문 일련 번호
		$cd_joinorg = $this->input->post('cd_joinorg', null, ''); //제휴 기관 코드

		$dt_transbase = $this->input->post('dt_transbase', null, ''); //거래 기준 일자
		$no_transeq = $this->input->post('no_transeq', null, ''); //거래 일련 번호
		$type_msg = $this->input->post('type_msg', null, ''); //거래 구분 코드
		$cl_close = $this->input->post('cl_close', null, ''); //마감 구분코드
		$cl_kor = $this->input->post('cl_kor', null, ''); //한글 구분 코드
		$no_msgmanage = $this->input->post('no_msgmanage', null, ''); //전문 관리 번호
		$no_vacct = $this->input->post('no_vacct', null, ''); //가상계좌번호
		$amt_input = (int) $this->input->post('amt_input', null, ''); //입금금액
		$amt_check = $this->input->post('amt_check', null, ''); //미결제 타점권 금액
		$nm_inputbank = $this->input->post('nm_inputbank', null, ''); //입금 금융기관명
		$nm_input = $this->input->post('nm_input', null, ''); //입금 의뢰인
		$dt_inputstd = $this->input->post('dt_inputstd', null, ''); //입금 기준 일자
		$dt_calculstd = $this->input->post('dt_calculstd', null, ''); //정산 기준 일자
		$flg_close = $this->input->post('flg_close', null, ''); //마감 전화

		//가상계좌채번시 현금영수증 자동발급신청시에만 전달
		$dt_cshr = $this->input->post('dt_cshr', null, ''); //현금영수증 발급일자
		$tm_cshr = $this->input->post('tm_cshr', null, ''); //현금영수증 발급시간
		$no_cshr_appl = $this->input->post('no_cshr_appl', null, ''); //현금영수증 발급번호
		$no_cshr_tid = $this->input->post('no_cshr_tid', null, ''); //현금영수증 발급TID


		// 입금결과 처리
		$this->load->model(array('Deposit_model', 'Cmall_order_model'));
		$where = array(
			'dep_id' => $no_oid,
			'dep_app_no' => $no_vacct,
			'dep_status' => 0,
		);
		$deposit = $this->Deposit_model->get_one('', '', $where);

		$receipt_time = $dt_trans . $tm_trans;

		if (element('dep_id', $deposit)) {
			$updatedata = array();
			$updatedata['dep_cash'] = $amt_input;
			if ($amt_input === element('dep_cash_request', $deposit)) {
				$updatedata['dep_status'] = 1;
				$updatedata['dep_deposit_datetime'] = $receipt_time;
				$this->Deposit_model->update(element('dep_id', $deposit), $updatedata);
			}
		} else {
			$where = array(
				'cor_id' => $no_oid,
				'cor_app_no' => $no_vacct,
				'cor_status' => 0,
			);

			$order = $this->Cmall_order_model->get_one('', '', $where);

			if (element('cor_id', $order)) {
				$updatedata = array();
				$updatedata['cor_cash'] = $amt_input;
				if ($amt_input === (int) element('cor_cash_request', $order)) {
					$updatedata['cor_status'] = 1;
					$updatedata['cor_approve_datetime'] = $receipt_time;

					$result = $this->Cmall_order_model->update(element('cor_id', $order), $updatedata);
				}
			}
		}

		if ($INIpayLog) {
			$logfile = fopen($INIpayHome . '/log/result.log', 'a+');

			fwrite($logfile, '************************************************');
			fwrite($logfile, 'ID_MERCHANT : ' . $id_merchant . "\r\n");
			fwrite($logfile, 'NO_TID : ' . $no_tid . "\r\n");
			fwrite($logfile, 'NO_OID : ' . $no_oid . "\r\n");
			fwrite($logfile, 'NO_VACCT : ' . $no_vacct . "\r\n");
			fwrite($logfile, 'AMT_INPUT : ' . $amt_input . "\r\n");
			fwrite($logfile, 'NM_INPUTBANK : ' . $nm_inputbank . "\r\n");
			fwrite($logfile, 'NM_INPUT : ' . $nm_input . "\r\n");
			fwrite($logfile, '************************************************');

			fwrite($logfile, '전체 결과값'."\r\n");
			fwrite($logfile, $msg_id . "\r\n");
			fwrite($logfile, $no_tid . "\r\n");
			fwrite($logfile, $no_oid . "\r\n");
			fwrite($logfile, $id_merchant . "\r\n");
			fwrite($logfile, $cd_bank . "\r\n");
			fwrite($logfile, $dt_trans . "\r\n");
			fwrite($logfile, $tm_trans . "\r\n");
			fwrite($logfile, $no_msgseq . "\r\n");
			fwrite($logfile, $type_msg . "\r\n");
			fwrite($logfile, $cl_close . "\r\n");
			fwrite($logfile, $cl_kor . "\r\n");
			fwrite($logfile, $no_msgmanage . "\r\n");
			fwrite($logfile, $no_vacct . "\r\n");
			fwrite($logfile, $amt_input . "\r\n");
			fwrite($logfile, $amt_check . "\r\n");
			fwrite($logfile, $nm_inputbank . "\r\n");
			fwrite($logfile, $nm_input . "\r\n");
			fwrite($logfile, $dt_inputstd . "\r\n");
			fwrite($logfile, $dt_calculstd . "\r\n");
			fwrite($logfile, $flg_close . "\r\n");
			fwrite($logfile, "\r\n");

			fclose($logfile);
		}

		// 이벤트가 존재하면 실행합니다
		Events::trigger('after', $eventname);


		//************************************************************************************

		//위에서 상점 데이터베이스에 등록 성공유무에 따라서 성공시에는 'OK'를 이니시스로
		//리턴하셔야합니다. 아래 조건에 데이터베이스 성공시 받는 FLAG 변수를 넣으세요
		//(주의) OK를 리턴하지 않으시면 이니시스 지불 서버는 'OK'를 수신할때까지 계속 재전송을 시도합니다
		//기타 다른 형태의 PRINT( echo )는 하지 않으시기 바랍니다

		if ($result) {
			echo 'OK'; // 절대로 지우지마세요
		} else {
			echo 'DB Error';
		}
	}

	public function inicis_close() {
		$this->view = 'paymentlib/inicis/close';
	}

	public function inicis_popup() {
		$this->view = 'paymentlib/inicis/popup';
	}

	public function inicis_makesignature()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_payment_inicis_makesignature';
		$this->load->event($eventname);

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		$this->load->library(array('paymentlib'));
		$init = $this->paymentlib->inicis_init();

		$orderNumber = $this->session->userdata('unique_id');

		if( ! $orderNumber || 'inicis' !== $this->CI->cbconfig->item('use_payment_pg') ){
			die(json_encode(array('error'=>'세션 주문번호가 없거나, 잘못된 요청입니다.')));
		}

		$price = $this->input->post('price', null, '');
		$price = preg_replace('#[^0-9]#', '', $price);

		if(strlen($price) < 1)  {
			die(json_encode(array('error'=>'가격이 올바르지 않습니다.')));
		}

		//
		//###################################
		// 2. 가맹점 확인을 위한 signKey를 해시값으로 변경 (SHA-256방식 사용)
		//###################################

		$signKey = element('pg_inicis_sign', $init);
		$mKey = hash("sha256", $signKey);
		$timestamp = element('timestamp', $init);

		/*
		  //*** 위변조 방지체크를 signature 생성 ***
		  oid, price, timestamp 3개의 키와 값을
		  key=value 형식으로 하여 '&'로 연결한 하여 SHA-256 Hash로 생성 된값
		  ex) oid=INIpayTest_1432813606995&price=819000&timestamp=2012-02-01 09:19:04.004
		 * key기준 알파벳 정렬
		 * timestamp는 반드시 signature생성에 사용한 timestamp 값을 timestamp input에 그대로 사용하여야함
		 */
		$params = "oid=" . $orderNumber . "&price=" . $price . "&timestamp=" . $timestamp;
		$sign = hash("sha256", $params);

		// 이벤트가 존재하면 실행합니다
		Events::trigger('after', $eventname);

		die(json_encode(array('error'=>'', 'mKey'=>$mKey, 'timestamp'=>$timestamp, 'sign'=>$sign)));
	}

	public function inicis_encryptdata()
	{

		echo "삭제예정입니다.";
		return;
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_payment_inicis_encryptdata';
		$this->load->event($eventname);

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		$this->load->library(array('paymentlib'));
		$init = $this->paymentlib->inicis_init('nolib');

		/**************************
		 * 1. 라이브러리 인클루드 *
		 **************************/
		include(FCPATH . 'plugin/pg/inicis/libs/INILib.php');

		/***************************************
		 * 2. INIpay50 클래스의 인스턴스 생성 *
		 ***************************************/
		$inipay = new INIpay50;

		$inipay->SetField('inipayhome', FCPATH . 'plugin/pg/inicis'); // 이니페이 홈디렉터리(상점수정 필요)
		$inipay->SetField('debug', 'false'); // 로그모드('true'로 설정하면 상세로그가 생성됨.)

		$price = $this->input->post('price', null, '');

		/**************************
		 * 3. 암호화 대상/값 설정 *
		 **************************/
		$inipay->SetField('type', 'chkfake'); // 고정 (절대 수정 불가)
		$inipay->SetField('enctype', 'asym'); //asym:비대칭, symm:대칭(현재 asym으로 고정)

		/**************************************************************************************************
		 * admin 은 키패스워드 변수명입니다. 수정하시면 안됩니다. 1111의 부분만 수정해서 사용하시기 바랍니다.
		 * 키패스워드는 상점관리자 페이지(https://iniweb.inicis.com)의 비밀번호가 아닙니다. 주의해 주시기 바랍니다.
		 * 키패스워드는 숫자 4자리로만 구성됩니다. 이 값은 키파일 발급시 결정됩니다.
		 * 키패스워드 값을 확인하시려면 상점측에 발급된 키파일 안의 readme.txt 파일을 참조해 주십시오.
		 **************************************************************************************************/
		$inipay->SetField('admin', element('pg_inicis_key', $init)); // 키패스워드(키발급시 생성, 상점관리자 패스워드와 상관없음)
		$inipay->SetField('checkopt', 'false'); //base64함:false, base64안함:true(현재 false로 고정)

		//필수항목 : mid, price, nointerest, quotabase
		//추가가능 : INIregno, oid
		//*주의* : 추가가능한 항목중 암호화 대상항목에 추가한 필드는 반드시 hidden 필드에선 제거하고
		// SESSION이나 DB를 이용해 다음페이지(INIsecureresult.php)로 전달/셋팅되어야 합니다.
		$inipay->SetField('mid', element('pg_inicis_mid', $init)); // 상점아이디
		$inipay->SetField('price', $price); // 가격
		$inipay->SetField('nointerest', element('inipay_nointerest', $init)); // 무이자여부(no:일반, yes:무이자)
		$inipay->SetField('quotabase', element('inipay_quotabase', $init));//할부기간

		/********************************
		 * 4. 암호화 대상/값을 암호화함 *
		 ********************************/
		$inipay->startAction();

		/*********************
		 * 5. 암호화 결과 *
		 *********************/
		if ($inipay->GetResult('ResultCode') !== '00') {
			die('{"error":"' . $inipay->GetResult('ResultMsg') . '"}');
		}

		/*********************
		 * 6. 세션정보 저장 *
		 *********************/
		$this->session->set_userdata('INI_MID', element('pg_inicis_mid', $init)); //상점ID
		$this->session->set_userdata('INI_ADMIN', element('pg_inicis_key', $init)); // 키패스워드(키발급시 생성, 상점관리자 패스워드와 상관없음)
		$this->session->set_userdata('INI_PRICE', $price); //가격
		$this->session->set_userdata('INI_RN', $inipay->GetResult('rn')); //고정 (절대 수정 불가)
		$this->session->set_userdata('INI_ENCTYPE', $inipay->GetResult('enctype')); //고정 (절대 수정 불가)

		$ini_encfield = $inipay->GetResult('encfield');
		$ini_certid = $inipay->GetResult('certid');

		// 이벤트가 존재하면 실행합니다
		Events::trigger('after', $eventname);

		$result = array(
			'error' => '',
			'ini_encfield' => $ini_encfield,
			'ini_certid' => $ini_certid,
		);

		die(json_encode($result));
	}


	public function inicis_noti()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_payment_inicis_noti';
		$this->load->event($eventname);

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		//*******************************************************************************
		// FILE NAME : mx_rnoti.php
		// FILE DESCRIPTION :
		// 이니시스 smart phone 결제 결과 수신 페이지 샘플
		// 기술문의 : ts@inicis.com
		// HISTORY
		// 2010. 02. 25 최초작성
		// 2010 06. 23 WEB 방식의 가상계좌 사용시 가상계좌 채번 결과 무시 처리 추가(APP 방식은 해당 없음!!)
		// WEB 방식일 경우 이미 P_NEXT_URL 에서 채번 결과를 전달 하였으므로,
		// 이니시스에서 전달하는 가상계좌 채번 결과 내용을 무시 하시기 바랍니다.
		//*******************************************************************************

		$PGIP = $this->input->ip_address();

		if ( ! $this->cbconfig->item('use_pg_test')) {
			switch ($this->input->ip_address()) {
				case '211.219.96.165' :
				case '118.129.210.25' :
				case '183.109.71.153' :
					break;
				default :
					$this->load->model(array('Member_model'));
					$select = 'mem_id, mem_email, mem_nickname, mem_phone';
					$superadminlist = $this->Member_model->get_superadmin_list($select);
					$egpcs_str = 'ENV[' . serialize($_ENV) . '] '
							. 'GET[' . serialize($_GET) . ']'
							. 'POST[' . serialize($_POST) . ']'
							. 'COOKIE[' . serialize($_COOKIE) . ']'
							. 'SESSION[' . serialize($_SESSION) . ']';

					foreach ($superadminlist as $akey => $aval) {
						$this->email->clear(true);
						$this->email->from($this->cbconfig->item('webmaster_email'), $this->cbconfig->item('webmaster_name'));
						$this->email->to(element('mem_email', $aval));

						$emailsubject = '올바르지 않은 접속이 발견되었습니다';
						$this->email->subject($emailsubject);

						$message = $this->input->server('PHP_SELF') . ' 에 ' . $this->input->ip_address() . ' 이 ' . cdate('Y-m-d H:i:s') . " 에 접속을 시도하였습니다.\n\n" . $egpcs_str;
						$this->email->message($message);

						$this->email->send();
					}
					exit;
			}
		}


		if ($PGIP === '211.219.96.165' || $PGIP === '118.129.210.25' || $PGIP === '183.109.71.153') { //PG에서 보냈는지 IP로 체크

			// 이니시스 NOTI 서버에서 받은 Value
			$P_TID = $this->input->post('P_TID', null, ''); // 거래번호
			$P_MID = $this->input->post('P_MID', null, ''); // 상점아이디
			$P_AUTH_DT = $this->input->post('P_AUTH_DT', null, ''); // 승인일자
			$P_STATUS = $this->input->post('P_STATUS', null, ''); // 거래상태 (00:성공, 01:실패)
			$P_TYPE = $this->input->post('P_TYPE', null, ''); // 지불수단
			$P_OID = $this->input->post('P_OID', null, ''); // 상점주문번호
			$P_FN_CD1 = $this->input->post('P_FN_CD1', null, ''); // 금융사코드1
			$P_FN_CD2 = $this->input->post('P_FN_CD2', null, ''); // 금융사코드2
			$P_FN_NM = $this->input->post('P_FN_NM', null, ''); // 금융사명 (은행명, 카드사명, 이통사명)
			$P_AMT = $this->input->post('P_AMT', null, ''); // 거래금액
			$P_UNAME = $this->input->post('P_UNAME', null, ''); // 결제고객성명
			$P_RMESG1 = $this->input->post('P_RMESG1', null, ''); // 결과코드
			$P_RMESG2 = $this->input->post('P_RMESG2', null, ''); // 결과메시지
			$P_NOTI = $this->input->post('P_NOTI', null, ''); // 노티메시지(상점에서 올린 메시지)
			$P_AUTH_NO = $this->input->post('P_AUTH_NO', null, ''); // 승인번호
			$P_SRC_CODE = $this->input->post('P_SRC_CODE', null, ''); // 앱연동 결제구분

			//가상계좌가 아니면
			if( $P_STATUS == "00" && $P_TID && $P_MID && $P_TYPE != 'VBANK' ){

				$this->load->model(array('Deposit_model', 'Cmall_order_model', 'Payment_order_data_model'));

				$row = $this->Payment_order_data_model->get_one($P_OID);
				$data = cmall_tmp_replace_data($row['pod_data']);
				$pod_type = element('pod_type', $row);

				if( 'deposit' == $pod_type ){	  //예치금이면

					$where = array(
						'dep_id' => $P_OID,
						'dep_tno' => $P_TID
					);

					/*
					$deposit = $this->Deposit_model->get_one('', '', $where);

					if ( empty( element('dep_id', $deposit) ) ) {	 //예치금 주문 내역이 없을 경우 처리

					}
					*/

				} else {	//컨텐츠몰이면

					$where = array(
						'cor_id' => $P_OID,
						'cor_tno' => $P_TID,
					);
					$order = $this->Cmall_order_model->get_one('', '', $where);

					if ( empty( element('cor_id', $order) ) ) {	 //컨텐츠몰 주문된 내역이 없을 경우 처리

						$row = $this->Payment_order_data_model->get_one($P_OID);
						$data = cmall_tmp_replace_data($row['pod_data']);
						$cart_ids = $row['cart_id'];

						if($row && isset($data['unique_id']) && !empty($data['unique_id'])) {

							$PAY = array(
								'oid'   => $P_OID,
								'P_TID'	 => $P_TID,
								'P_MID'	 => $P_MID,
								'P_AUTH_DT' => $P_AUTH_DT,
								'P_STATUS'  => $P_STATUS,
								'P_TYPE'	=> $P_TYPE,
								'P_OID'	 => $P_OID,
								'P_FN_NM'   => iconv('euc-kr', 'utf-8', $P_FN_NM),
								'P_AUTH_NO' => $P_AUTH_NO,
								'P_AMT'	 => $P_AMT,
								'P_RMESG1'  => iconv('euc-kr', 'utf-8', $P_RMESG1)
								);

							$params = array();
							$exclude = array('res_cd', 'P_HASH', 'P_TYPE', 'P_AUTH_DT', 'P_VACT_BANK', 'P_AUTH_NO');

							foreach($data as $key=>$value) {
								if(!empty($exclude) && in_array($key, $exclude))
									continue;

								if(is_array($value)) {
									foreach($value as $k=>$v) {
										$_POST[$key][$k] = $params[$key][$k] = $v;
									}
								} else {
									$_POST[$key] = $params[$key] = $value;
								}
							}

							// TID, AMT 를 세션으로 주문완료 페이지 전달
							$hash = md5($PAY['P_TID'].$PAY['P_MID'].$PAY['P_AMT']);
							$this->CI->session->set_userdata('P_TID',  $PAY['P_TID']);
							$this->CI->session->set_userdata('P_AMT',  $PAY['P_AMT']);
							$this->CI->session->set_userdata('P_HASH', $hash);

							$_POST['res_cd'] = $params['res_cd'] = $PAY['P_STATUS'];
							$_POST['P_HASH'] = $params['P_HASH'] = $hash;
							$_POST['P_TYPE'] = $params['P_TYPE'] = $PAY['P_TYPE'];
							$_POST['P_AUTH_DT'] = $params['P_AUTH_DT'] = $PAY['P_AUTH_DT'];
							$_POST['P_VACT_BANK'] = $params['P_VACT_BANK'] = $PAY['P_FN_NM'];
							$_POST['P_AUTH_NO'] = $params['P_AUTH_NO'] = $PAY['P_AUTH_NO'];

							$this->load->library('paymentlib');

							$result = $this->paymentlib->inipay_result('mobile');
							$insertdata = array();

							$total_price_sum = $item_cct_price = element('total_price_sum', $data);
							$co_cash = $P_AMT;  //결제된 금액
							$order_deposit = element('order_deposit', $data);
							$od_status = 'order';   //주문상태

							switch ($P_TYPE) {
								case 'CARD':	//카드
								case 'ISP':	//신용카드 ISP

									$insertdata['cor_tno'] = element('tno', $result);
									$insertdata['cor_app_no'] = element('app_no', $result);
									$insertdata['cor_datetime'] = date('Y-m-d H:i:s');
									$insertdata['cor_approve_datetime'] = preg_replace(
										"/([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})/",
										"\\1-\\2-\\3 \\4:\\5:\\6",
										element('app_time', $result)
									);
									$insertdata['cor_total_money'] = $total_price_sum;
									$insertdata['cor_cash_request'] = element('amount', $result);
									$insertdata['cor_deposit'] = $order_deposit;
									$insertdata['cor_cash'] = element('amount', $result);
									$insertdata['cor_bank_info'] = element('card_name', $result);
									$insertdata['cor_status'] = 1;
									$insertdata['mem_realname'] = $this->CI->input->post('mem_realname', null, '');
									$insertdata['cor_pg'] = $this->CI->cbconfig->item('use_payment_pg');

									if ( ((int) $item_cct_price - (int) $order_deposit - $cor_cash) == 0 ) {
										$od_status = 'deposit';   //주문상태
									}
									break;
								case 'BANK':	//계좌이체

									$insertdata['cor_tno'] = element('tno', $result);
									$insertdata['cor_datetime'] = date('Y-m-d H:i:s');
									$insertdata['cor_approve_datetime'] = preg_replace(
										"/([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})/",
										"\\1-\\2-\\3 \\4:\\5:\\6",
										element('app_time', $result)
									);
									$insertdata['cor_total_money'] = $total_price_sum;
									$insertdata['cor_cash_request'] = element('amount', $result);
									$insertdata['cor_deposit'] = $order_deposit;
									$insertdata['cor_cash'] = element('amount', $result);
									$insertdata['cor_status'] = 1;
									$insertdata['mem_realname'] = $this->CI->input->post('mem_realname', null, '');
									$insertdata['cor_pg'] = $this->CI->cbconfig->item('use_payment_pg');

									if ( ((int) $item_cct_price - (int) $order_deposit - $cor_cash) == 0 ) {
										$od_status = 'deposit';   //주문상태
									}

									break;
								/*  //가상계좌는 해당 사항 없음
								case 'VBANK':   //가상계좌

									$insertdata['cor_tno'] = element('tno', $result);
									$insertdata['cor_datetime'] = date('Y-m-d H:i:s');
									$insertdata['cor_total_money'] = $total_price_sum;
									$insertdata['cor_cash_request'] = element('amount', $result);
									$insertdata['cor_deposit'] = $order_deposit;
									$insertdata['cor_cash'] = 0;
									$insertdata['cor_status'] = 0;
									$insertdata['mem_realname'] = element('depositor', $result);
									$insertdata['cor_bank_info'] = element('bankname', $result) . ' ' . element('account', $result);
									$insertdata['cor_pg'] = $this->CI->cbconfig->item('use_payment_pg');

									break;
								*/
								case 'MOBILE':   //휴대폰

									$insertdata['cor_tno'] = element('tno', $result);
									$insertdata['cor_app_no'] = element('commid', $result) . ' ' . element('mobile_no', $result);
									$insertdata['cor_datetime'] = date('Y-m-d H:i:s');
									$insertdata['cor_approve_datetime'] = preg_replace(
										"/([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})/",
										"\\1-\\2-\\3 \\4:\\5:\\6",
										element('app_time', $result)
									);
									$insertdata['cor_total_money'] = $total_price_sum;
									$insertdata['cor_cash_request'] = element('amount', $result);
									$insertdata['cor_deposit'] = $order_deposit;
									$insertdata['cor_cash'] = element('amount', $result);
									$insertdata['cor_status'] = 1;
									$insertdata['mem_realname'] = $this->CI->input->post('mem_realname', null, '');
									$insertdata['cor_pg'] = $this->CI->cbconfig->item('use_payment_pg');

									if ( ((int) $item_cct_price - (int) $order_deposit - $cor_cash) == 0 ) {
										$od_status = 'deposit';   //주문상태
									}

									break;
							}   //end switch

							$mem_id = $this->CI->input->post('mem_id', null, '');

							$this->load->model(array('Member_model', 'Cmall_cart_model', 'Cmall_item_model', 'Cmall_order_model', 'Cmall_order_detail_model'));
							$select = 'mem_nickname';
							$member = $this->Member_model->get_by_memid($mem_id, 'mem_nickname');

							$cor_id = $P_OID;   //주문번호

							$insertdata['cor_id'] = $cor_id;
							$insertdata['mem_id'] = $mem_id;
							$insertdata['mem_nickname'] = element('mem_nickname', $member);
							$insertdata['mem_email'] = $this->CI->input->post('mem_email', null, '');
							$insertdata['mem_phone'] = $this->CI->input->post('mem_phone', null, '');
							$insertdata['cor_pay_type'] = $this->CI->input->post('pay_type', null, '');
							$insertdata['cor_content'] = $this->CI->input->post('cor_content', null, '');
							$insertdata['cor_ip'] = $this->CI->input->ip_address();
							$insertdata['cor_useragent'] = $this->CI->agent->agent_string();
							$insertdata['is_test'] = $this->CI->cbconfig->item('use_pg_test');
							$insertdata['status'] = $od_status;

							$res = $this->Cmall_order_model->insert($insertdata);
							if ($res) {
								$where = array('mem_id' => $mem_id);
								$where_in = explode('-', $cart_ids);
								$cartorder = $this->Cmall_cart_model->get_cart_list_in($where, $where_in);

								if ($cartorder) {
									foreach ($cartorder as $key => $val) {

										$item = $this->Cmall_item_model
											->get_one(element('cit_id', $val), 'cit_download_days');
										$insertdetail = array(
											'cor_id' => $cor_id,
											'mem_id' => $mem_id,
											'cit_id' => element('cit_id', $val),
											'cde_id' => element('cde_id', $val),
											'cod_download_days' => element('cit_download_days', $item),
											'cod_count' => element('cct_count', $val),
											'cod_status' =>  $od_status,
										);
										$this->Cmall_order_detail_model->insert($insertdetail);
										$deletewhere = array(
											'mem_id' => $mem_id,
											'cit_id' => element('cit_id', $val),
											'cde_id' => element('cde_id', $val),
										);
										$this->Cmall_cart_model->delete_where($deletewhere);
									}
								}

								// 주문 정보 임시 데이터 삭제
								$where = array(
									'pod_id' => $cor_id,
									'pod_pg' => 'inicis'
								);
								$this->Payment_order_data_model->delete_where($where);

							}   //end if


						}
					}   // //주문된 내역이 없을 경우 처리

				}
			}	   //end if P_STATUS

			//WEB 방식의 경우 가상계좌 채번 결과 무시 처리
			//(APP 방식의 경우 해당 내용을 삭제 또는 주석 처리 하시기 바랍니다.)
			if ($P_TYPE === 'VBANK') { //결제수단이 가상계좌이며
				if ($P_STATUS !== '02') { //입금통보 '02' 가 아니면(가상계좌 채번 : 00 또는 01 경우)
					echo 'OK';
					return;
				}

				$result = false;

				$this->load->model(array('Deposit_model', 'Cmall_order_model'));
				$where = array(
					'dep_id' => $P_OID,
					'dep_tno' => $P_TID,
					'dep_status' => 0,
				);
				$deposit = $this->Deposit_model->get_one('', '', $where);

				if (element('dep_id', $deposit)) {
					$updatedata = array();
					$updatedata['dep_cash'] = $P_AMT;
					if ($P_AMT === element('dep_cash_request', $deposit)) {
						$updatedata['dep_status'] = 1;
						$updatedata['dep_deposit_datetime'] = $P_AUTH_DT;
						$result = $this->Deposit_model->update(element('dep_id', $deposit), $updatedata);
					}
				} else {
					$where = array(
						'cor_id' => $P_OID,
						'cor_tno' => $P_TID,
						'cor_status' => 0,
					);
					$order = $this->Cmall_order_model->get_one('', '', $where);

					if (element('cor_id', $order)) {
						$updatedata = array();
						$updatedata['cor_cash'] = $P_AMT;
						if ($P_AMT === element('cor_cash_request', $order)) {
							$updatedata['cor_status'] = 1;
							$updatedata['cor_approve_datetime'] = $P_AUTH_DT;
							$result = $this->Cmall_order_model->update(element('cor_id', $order), $updatedata);
						}
					}
				}

				if ($result) {
					echo 'OK';
					return;
				} else {
					echo 'FAIL';
					return;
				}
			}

			$PageCall_time = date('H:i:s');

			$value = array(
						'PageCall time' => $PageCall_time,
						'P_TID' => $P_TID,
						'P_MID' => $P_MID,
						'P_AUTH_DT' => $P_AUTH_DT,
						'P_STATUS' => $P_STATUS,
						'P_TYPE' => $P_TYPE,
						'P_OID' => $P_OID,
						'P_FN_CD1' => $P_FN_CD1,
						'P_FN_CD2' => $P_FN_CD2,
						'P_FN_NM' => $P_FN_NM,
						'P_AMT' => $P_AMT,
						'P_UNAME' => $P_UNAME,
						'P_RMESG1' => $P_RMESG1,
						'P_RMESG2' => $P_RMESG2,
						'P_NOTI' => $P_NOTI,
						'P_AUTH_NO' => $P_AUTH_NO,
					);

			// 결과 incis log 테이블 기록
			if($P_TYPE == 'BANK' || $P_SRC_CODE == 'A') {

				$this->load->model(array('Payment_inicis_log_model'));

				$insertdata = array(
					'pil_id' => $P_OID,
					'pil_type' => $pod_type,
					'P_TID' => $P_TID,
					'P_MID' => $P_MID,
					'P_AUTH_DT' => $P_AUTH_DT,
					'P_STATUS' => $P_STATUS,
					'P_TYPE' => $P_TYPE,
					'P_OID' => $P_OID,
					'P_FN_NM' => iconv('euc-kr', 'utf-8', $P_FN_NM),
					'P_AUTH_NO' => $P_AUTH_NO,
					'P_AMT' => $P_AMT,
					'P_RMESG1' => iconv('euc-kr', 'utf-8', $P_RMESG1),
				);

				$this->Payment_inicis_log_model->insert($insertdata);
			}

			// 결제처리에 관한 로그 기록
			//log_message('info', $value);

			/***********************************************************************************
			' 위에서 상점 데이터베이스에 등록 성공유무에 따라서 성공시에는 'OK'를 이니시스로 실패시는 'FAIL' 을
			' 리턴하셔야합니다. 아래 조건에 데이터베이스 성공시 받는 FLAG 변수를 넣으세요
			' (주의) OK를 리턴하지 않으시면 이니시스 지불 서버는 'OK'를 수신할때까지 계속 재전송을 시도합니다
			' 기타 다른 형태의 echo '' 는 하지 않으시기 바랍니다
			'***********************************************************************************/

			// 이벤트가 존재하면 실행합니다
			Events::trigger('after', $eventname);

			echo 'OK';
		}
	}


	public function inicis_approval($ptype = '')
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_payment_inicis_approval';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);


		if (empty($ptype)) {
			return;
		}

		$this->load->library('paymentlib');

		$view['view']['pg'] = $this->paymentlib->inicis_init('nolib');

		//현재 설정된 mid
		$inicis_mid = $view['view']['pg']['pg_inicis_mid'];
		$view['view']['ptype'] = $ptype;


		// 세션 초기화
		$this->session->set_userdata('P_TID', '');
		$this->session->set_userdata('P_AMT', '');
		$this->session->set_userdata('P_HASH', '');


		$view['view']['order_id'] = $oid = trim($this->input->post_get('P_NOTI', null, ''));

		$this->load->model('Payment_order_data_model');
		$row = $this->Payment_order_data_model->get_one($oid);
		$view['view']['data'] = $data = cmall_tmp_replace_data($row['pod_data']);

		if ($ptype === 'deposit') {
			$view['view']['order_action_url'] = site_url('deposit/update/mobile');
			$view['view']['page_return_url'] = site_url('deposit');
		} else {
			$view['view']['order_action_url'] = site_url('cmall/orderupdate/mobile');
			$view['view']['page_return_url'] = site_url('cmall');
		}

		if ($this->input->post_get('P_STATUS') !== '00') {
			alert('오류 : ' . iconv('euc-kr', 'utf-8', $this->input->post_get('P_RMESG1', null, '')) . ' 코드 : ' . $this->input->post_get('P_STATUS', null, ''), $view['view']['page_return_url']);
		} else {
			$post_data = array(
				'P_MID' => $inicis_mid,
				'P_TID' => $this->input->post_get('P_TID', null, ''),
			);

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $this->input->post_get('P_REQ_URL', null, ''));
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$return = curl_exec($ch);

			if (empty($return)) {
				alert('KG이니시스와 통신 오류로 결제등록 요청을 완료하지 못했습니다.\\n결제등록 요청을 다시 시도해 주십시오.', $view['view']['page_return_url']);
			}

			// 결과를 배열로 변환
			parse_str($return, $ret);
			$view['view']['PAY'] = $PAY = array_map('trim', $ret);

			if ($PAY['P_STATUS'] !== '00') {
				alert('오류 : ' . iconv('euc-kr', 'utf-8', $PAY['P_RMESG1']) . ' 코드 : ' . $PAY['P_STATUS'], $view['view']['page_return_url']);
			}

			// TID, AMT 를 세션으로 주문완료 페이지 전달
			$view['view']['hash'] = $hash = md5($PAY['P_TID'] . $PAY['P_MID'] . $PAY['P_AMT']);
			$this->session->set_userdata('P_TID', $PAY['P_TID']);
			$this->session->set_userdata('P_AMT', $PAY['P_AMT']);
			$this->session->set_userdata('P_HASH', $hash);
		}

		$view['view']['body_script'] = 'onload="setPAYResult();"';

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 레이아웃을 정의합니다
		 */

		$layoutconfig = array(
			'layout' => 'layout_popup'
			);

		$view['layout'] = $this->managelayout->front($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = '_layout/bootstrap/layout_popup';
		$this->view = 'paymentlib/inicis/inicis_approval';
	}


	public function inicis_pay_return($ptype = '', $oid = '')
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_payment_inicis_pay_return';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		if (empty($ptype)) {
			return;
		}
		if (empty($oid)) {
			return;
		}

		$view = array();
		$view['view'] = array();

		$this->load->helper('cmall');
		$this->load->library('paymentlib');
		$view['view']['pg'] = $this->paymentlib->inicis_init('nolib');
		$view['view']['ptype'] = $ptype;

		// 세션 초기화
		$this->session->set_userdata('P_TID', '');
		$this->session->set_userdata('P_AMT', '');
		$this->session->set_userdata('P_HASH', '');

		$this->load->model(array('Payment_order_data_model', 'Payment_inicis_log_model'));
		$row = $this->Payment_order_data_model->get_one($oid);
		$view['view']['data'] = $data = cmall_tmp_replace_data($row['pod_data']);

		if( 'deposit' !== $ptype && empty($row) ){  //이미 결제가 완료 되었다면
			if( ($unique_id = $this->session->userdata('unique_id')) && $exist_order = get_cmall_order_data($unique_id) ){	//상품주문
				exists_inicis_cmall_order($unique_id, array(), $exist_order['cor_datetime']);
				exit;
			}
		}

		if ($ptype === 'deposit') {
			$view['view']['order_action_url'] = $order_action_url = site_url('deposit/update/mobile');
			$view['view']['page_return_url'] = $page_return_url = site_url('deposit');
		} else {
			$view['view']['order_action_url'] = $order_action_url = site_url('cmall/orderupdate/mobile');
			$view['view']['page_return_url'] = $page_return_url = site_url('cmall');
		}

		$row = $this->Payment_inicis_log_model->get_one($oid);

		if ( ! element('pil_id', $row)) {
			alert('결제 정보가 존재하지 않습니다.\\n\\n올바른 방법으로 이용해 주십시오.', $page_return_url);
		}

		if ($row['P_STATUS'] !== '00') {
			alert('오류 : ' . $row['P_RMESG1'] . ' 코드 : ' . $row['P_STATUS'], $page_return_url);
		}

		$view['view']['PAY'] = $PAY = array_map('trim', $row);

		// TID, AMT 를 세션으로 주문완료 페이지 전달
		$hash = md5($PAY['P_TID'] . $PAY['P_MID'] . $PAY['P_AMT']);
		$this->session->set_userdata('P_TID', $PAY['P_TID']);
		$this->session->set_userdata('P_AMT', $PAY['P_AMT']);
		$this->session->set_userdata('P_HASH', $hash);

		$this->Payment_inicis_log_model->delete($oid);

		$view['view']['body_script'] = 'onload="setPAYResult();"';

		$view['view']['hash'] = $hash;

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 레이아웃을 정의합니다
		 */

		$layoutconfig = array(
			'layout' => 'layout_popup'
			);

		$view['layout'] = $this->managelayout->front($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = '_layout/bootstrap/layout_popup';
		$this->view = 'paymentlib/inicis/inicis_pay_return';
	}


	public function orderdatasave()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_payment_orderdatasave';
		$this->load->event($eventname);

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		if (empty($_POST)) {
			die('정보가 넘어오지 않았습니다.');
		}

		$unique_id = '';
		if ($this->input->post('unique_id')) {
			$unique_id = $this->session->userdata('unique_id');
		}

		// 일정 기간이 경과된 임시 데이터 삭제
		$limit_time = cdate('Y-m-d H:i:s', (ctimestamp() - 86400 * 30));
		$where = array(
			'pod_datetime <' => $limit_time,
		);
		$this->load->model(array('Payment_order_data_model'));
		$this->Payment_order_data_model->delete_where($where);
		$where = array(
			'pod_id' => $unique_id
			);
		$this->Payment_order_data_model->delete_where($where);

		$poddata = base64_encode(serialize($_POST));

		$ptype = $this->input->post('ptype', null, '');

		$insertdata = array(
			'pod_id' =>$unique_id,
			'pod_type' => $ptype,
			'pod_pg' =>$this->cbconfig->item('use_payment_pg'),
			'pod_data' =>$poddata,
			'pod_datetime' =>cdate('Y-m-d H:i:s'),
			'pod_ip' => $this->input->ip_address(),
			'mem_id' => $this->member->item('mem_id'),
			'cart_id' => ($ptype == 'deposit') ? '' : $this->session->userdata('order_cct_id')
		);

		$this->Payment_order_data_model->insert($insertdata);

		// 이벤트가 존재하면 실행합니다
		Events::trigger('after', $eventname);

		die('');
	}


	public function kcp_order_approval()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_payment_kcp_order_approval';
		$this->load->event($eventname);

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		$this->load->library('paymentlib');
		$view['view']['pg'] = $this->paymentlib->kcp_init();

		require_once(FCPATH . 'plugin/pg/kcp/KCPComLibrary.php'); // library [수정불가]

		// 쇼핑몰 페이지에 맞는 문자셋을 지정해 주세요.
		$charSetType = 'utf-8'; // UTF-8인 경우 'utf-8'로 설정

		$siteCode = $this->input->get('site_cd', null, '');
		$orderID = $this->input->get('ordr_idxx', null, '');
		$paymentMethod = $this->input->get('pay_method', null, '');
		$escrow = ($this->input->get('escw_used') === 'Y') ? true : false;
		$productName = $this->input->get('good_name', null, '');

		// 아래 두값은 POST된 값을 사용하지 않고 서버에 SESSION에 저장된 값을 사용하여야 함.
		$paymentAmount = (int) $this->input->get('good_mny'); // 결제 금액
		$returnUrl = $this->input->get('Ret_URL', null, '');

		// Access Credential 설정
		$accessLicense = '';
		$signature = '';
		$timestamp = '';

		// Base Request Type 설정
		$detailLevel = '0';
		$requestApp = 'WEB';
		$requestID = $orderID;
		$userAgent = $this->agent->agent_string();
		$version = '0.1';

		$g_wsdl = site_url() . 'plugin/pg/kcp/'.element('pg_wsdl', $view['view']['pg']);

		try
		{
			$payService = new PayService($g_wsdl);

			$payService->setCharSet($charSetType);

			$payService->setAccessCredentialType($accessLicense, $signature, $timestamp);
			$payService->setBaseRequestType($detailLevel, $requestApp, $requestID, $userAgent, $version);
			$payService->setApproveReq($escrow, $orderID, $paymentAmount, $paymentMethod, $productName, $returnUrl, $siteCode);

			$approveRes = $payService->approve();

			// 이벤트가 존재하면 실행합니다
			Events::trigger('after', $eventname);

			printf( "%s,%s,%s,%s", $payService->resCD, $approveRes->approvalKey,
								$approveRes->payUrl, $payService->resMsg);

		}
		catch (SoapFault $ex)
		{
			printf( "%s,%s,%s,%s", "95XX", "", "", "연동 오류 (PHP SOAP 모듈 설치 필요)");
		}
		exit;

	}


	public function kcp_order_approval_form($ptype = '')
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_payment_kcp_order_approval_form';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		$this->load->library('paymentlib');
		$view['view']['pg'] = $this->paymentlib->kcp_init();
		$data = array();

		/* ============================================================================== */
		/* = PAGE : 결제 요청 PAGE = */
		/* = -------------------------------------------------------------------------- = */
		/* = 이 페이지는 주문 페이지를 통해서 결제자가 결제 요청을 하는 페이지 = */
		/* = 입니다. 아래의 ※ 필수, ※ 옵션 부분과 매뉴얼을 참조하셔서 연동을 = */
		/* = 진행하여 주시기 바랍니다. = */
		/* = -------------------------------------------------------------------------- = */
		/* = 연동시 오류가 발생하는 경우 아래의 주소로 접속하셔서 확인하시기 바랍니다.= */
		/* = 접속 주소 : http://testpay.kcp.co.kr/pgsample/FAQ/search_error.jsp = */
		/* = -------------------------------------------------------------------------- = */
		/* = Copyright (c) 2010.05 KCP Inc. All Rights Reserved. = */
		/* ============================================================================== */

		/* ============================================================================== */
		/* = 환경 설정 파일 Include = */
		/* = -------------------------------------------------------------------------- = */
		/* = ※ 필수 = */
		/* = 테스트 및 실결제 연동시 site_conf_inc.php파일을 수정하시기 바랍니다. = */
		/* = -------------------------------------------------------------------------- = */

		/* kcp와 통신후 kcp 서버에서 전송되는 결제 요청 정보*/
		$data['req_tx'] = $req_tx = $this->input->post('req_tx', null, ''); // 요청 종류
		$data['res_cd'] = $res_cd = $this->input->post('res_cd', null, ''); // 응답 코드
		$data['tran_cd'] = $tran_cd = $this->input->post('tran_cd', null, ''); // 트랜잭션 코드
		$data['ordr_idxx'] = $ordr_idxx = $this->input->post('ordr_idxx', null, ''); // 쇼핑몰 주문번호
		$data['good_name'] = $good_name = $this->input->post('good_name', null, ''); // 상품명
		$data['good_mny'] = $good_mny = $this->input->post('good_mny', null, ''); // 결제 총금액
		$data['buyr_name'] = $buyr_name = $this->input->post('buyr_name', null, ''); // 주문자명
		$data['buyr_tel1'] = $buyr_tel1 = $this->input->post('buyr_tel1', null, ''); // 주문자 전화번호
		$data['buyr_tel2'] = $buyr_tel2 = $this->input->post('buyr_tel2', null, ''); // 주문자 핸드폰 번호
		$data['buyr_mail'] = $buyr_mail = $this->input->post('buyr_mail', null, ''); // 주문자 E-mail 주소
		$data['use_pay_method'] = $use_pay_method = $this->input->post('use_pay_method', null, ''); // 결제 방법
		$data['enc_info'] = $enc_info = $this->input->post('enc_info', null, ''); // 암호화 정보
		$data['enc_data'] = $enc_data = $this->input->post('enc_data', null, ''); // 암호화 데이터
		$data['rcvr_name'] = $rcvr_name = $this->input->post('rcvr_name', null, ''); // 수취인 이름
		$data['rcvr_tel1'] = $rcvr_tel1 = $this->input->post('rcvr_tel1', null, ''); // 수취인 전화번호
		$data['rcvr_tel2'] = $rcvr_tel2 = $this->input->post('rcvr_tel2', null, ''); // 수취인 휴대폰번호
		$data['rcvr_mail'] = $rcvr_mail = $this->input->post('rcvr_mail', null, ''); // 수취인 E-Mail
		$data['rcvr_zipx'] = $rcvr_zipx = $this->input->post('rcvr_zipx', null, ''); // 수취인 우편번호
		$data['rcvr_add1'] = $rcvr_add1 = $this->input->post('rcvr_add1', null, ''); // 수취인 주소
		$data['rcvr_add2'] = $rcvr_add2 = $this->input->post('rcvr_add2', null, ''); // 수취인 상세주소

		/* 주문폼에서 전송되는 정보 */
		$data['ipgm_date'] = $ipgm_date = $this->input->post('ipgm_date', null, ''); // 입금마감일
		$data['pay_type'] = $pay_type = $this->input->post('pay_type', null, ''); // 결제방법
		$data['good_info'] = $good_info = $this->input->post('good_info', null, ''); // 에스크로 상품정보
		$data['bask_cntx'] = $bask_cntx = $this->input->post('bask_cntx', null, ''); // 장바구니 상품수
		$data['tablet_size'] = $tablet_size = $this->input->post('tablet_size', null, ''); // 모바일기기 화면비율

		$data['comm_tax_mny'] = $comm_tax_mny = $this->input->post('comm_tax_mny', null, ''); // 과세금액
		$data['comm_vat_mny'] = $comm_vat_mny = $this->input->post('comm_vat_mny', null, ''); // 부가세
		$data['comm_free_mny'] = $comm_free_mny = $this->input->post('comm_free_mny', null, ''); // 비과세금액

		/*
		 * 기타 파라메터 추가 부분 - Start -
		 */
		$data['param_opt_1'] = $param_opt_1= $this->input->post('param_opt_1', null, ''); // 기타 파라메터 추가 부분
		$data['param_opt_2'] = $param_opt_2 = $this->input->post('param_opt_2', null, ''); // 기타 파라메터 추가 부분
		$data['param_opt_3'] = $param_opt_3 = $this->input->post('param_opt_3', null, ''); // 기타 파라메터 추가 부분
		/*
		 * 기타 파라메터 추가 부분 - End -
		 */

		/* kcp 데이터 캐릭터셋 변환 */
		if ($res_cd) {
			$data['good_name'] = $good_name = iconv('euc-kr', 'utf-8', $good_name);
			$data['buyr_name'] = $buyr_name = iconv('euc-kr', 'utf-8', $buyr_name);
			$data['rcvr_name'] = $rcvr_name = iconv('euc-kr', 'utf-8', $rcvr_name);
			$data['rcvr_add1'] = $rcvr_add1 = iconv('euc-kr', 'utf-8', $rcvr_add1);
			$data['rcvr_add2'] = $rcvr_add2 = iconv('euc-kr', 'utf-8', $rcvr_add2);
		}

		switch ($pay_type) {
			case 'card':
				$pay_method = 'CARD';
				$ActionResult = 'card';
				break;
			case 'realtime':
				$pay_method = 'BANK';
				$ActionResult = 'acnt';
				break;
			case 'phone':
				$pay_method = 'MOBX';
				$ActionResult = 'mobx';
				break;
			case 'vbank':
				$pay_method = 'VCNT';
				$ActionResult = 'vcnt';
				break;
			default:
				$pay_method = '';
				$ActionResult = '';
				break;
		}

		$data['pay_method'] = $pay_method;
		$data['ActionResult'] = $ActionResult;

		if ($ptype === 'deposit') {
			$data['js_return_url'] = site_url('deposit');
			$data['order_action_url'] = site_url('deposit/update/mobile');
		} else {
			$data['js_return_url'] = site_url('cmall/');
			$data['order_action_url'] = site_url('cmall/orderupdate/mobile');
		}

		$view['view']['data'] = $data;
		$view['view']['ptype'] = $ptype;

		$this->load->model('Payment_order_data_model');
		$row = $this->Payment_order_data_model->get_one($ordr_idxx);
		$view['view']['pod_data'] = $pod_data = cmall_tmp_replace_data($row['pod_data']);


		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 레이아웃을 정의합니다
		 */
		$this->data = $view;
		$this->view = 'paymentlib/kcp/kcp_order_approval_form';
	}

	// pc 일때 요청
	public function lg_pc_xpay_request($ptype=''){

		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_payment_lg_pc_xpay_request';
		$this->load->event($eventname);

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		$this->load->library(array('paymentlib'));
		$init = $this->paymentlib->lg_init();

		/*
		 * 1. 기본결제 인증요청 정보 변경
		 *
		 * 기본정보를 변경하여 주시기 바랍니다.(파라미터 전달시 POST를 사용하세요)
		 */
		$LGD_OID = $this->input->post('LGD_OID', null, ''); //주문번호(상점정의 유니크한 주문번호를 입력하세요)
		$LGD_AMOUNT = $this->input->post('LGD_AMOUNT', null, ''); //결제금액("," 를 제외한 결제금액을 입력하세요)
		$LGD_TIMESTAMP = $this->input->post('LGD_TIMESTAMP', null, ''); //타임스탬프
		$LGD_BUYER				  = $this->input->post('LGD_BUYER', null, '');			  //구매자명
		$LGD_PRODUCTINFO			= $this->input->post('LGD_PRODUCTINFO', null, '');		//상품명
		$LGD_BUYEREMAIL			 = $this->input->post('LGD_BUYEREMAIL', null, '');		 //구매자 이메일
		$LGD_CUSTOM_FIRSTPAY		= $this->input->post('LGD_CUSTOM_FIRSTPAY', null, '');	//상점정의 초기결제수단
		$LGD_CUSTOM_SKIN			= 'red';							//상점정의 결제창 스킨
		$LGD_CUSTOM_USABLEPAY	   = $this->input->post('LGD_CUSTOM_USABLEPAY', null, '');   //디폴트 결제수단 (해당 필드를 보내지 않으면 결제수단 선택 UI 가 노출됩니다.)
		$LGD_WINDOW_VER			 = '2.5';							//결제창 버젼정보
		$LGD_WINDOW_TYPE			= element('LGD_WINDOW_TYPE', $init);				 //결제창 호출방식 (수정불가)
		$LGD_CUSTOM_SWITCHINGTYPE   = element('LGD_CUSTOM_SWITCHINGTYPE', $init);		//신용카드 카드사 인증 페이지 연동 방식 (수정불가)
		$LGD_CUSTOM_PROCESSTYPE	 = 'TWOTR';						  //수정불가

		/*
		 *************************************************
		 * 2. MD5 해쉬암호화 (수정하지 마세요) - BEGIN
		 *
		 * MD5 해쉬암호화는 거래 위변조를 막기위한 방법입니다.
		 *************************************************
		 *
		 * 해쉬 암호화 적용( LGD_MID + LGD_OID + LGD_AMOUNT + LGD_TIMESTAMP + LGD_MERTKEY )
		 * LGD_MID		  : 상점아이디
		 * LGD_OID		  : 주문번호
		 * LGD_AMOUNT	   : 금액
		 * LGD_TIMESTAMP	: 타임스탬프
		 * LGD_MERTKEY	  : 상점MertKey (mertkey는 상점관리자 -> 계약정보 -> 상점정보관리에서 확인하실수 있습니다)
		 *
		 * MD5 해쉬데이터 암호화 검증을 위해
		 * LG유플러스에서 발급한 상점키(MertKey)를 환경설정 파일(lgdacom/conf/mall.conf)에 반드시 입력하여 주시기 바랍니다.
		 */

		include(FCPATH . 'plugin/pg/lg/XPayClient.php');
		include(FCPATH . 'plugin/pg/lg/XPay.php');
		$xpay = new XPay(element('configPath', $init), element('CST_PLATFORM', $init));

		// Mert Key 설정
		$xpay->set_config_value('t' . element('LGD_MID', $init), element('pg_lg_key', $init));
		$xpay->set_config_value(element('LGD_MID', $init), element('pg_lg_key', $init));

		$xpay->Init_TX(element('LGD_MID', $init));
		$LGD_HASHDATA = md5(element('LGD_MID', $init) . $LGD_OID . $LGD_AMOUNT . $LGD_TIMESTAMP . $xpay->config[element('LGD_MID', $init)]);
		/*
		 *************************************************
		 * 2. MD5 해쉬암호화 (수정하지 마세요) - END
		 *************************************************
		 */

		$payReqMap = array();

		$payReqMap['CST_PLATFORM']			  = element('CST_PLATFORM', $init);				// 테스트, 서비스 구분
		$payReqMap['LGD_WINDOW_TYPE']		   = $LGD_WINDOW_TYPE;			 // 수정불가
		$payReqMap['CST_MID']				   = element('CST_MID', $init);					 // 상점아이디
		$payReqMap['LGD_MID']				   = element('LGD_MID', $init);					 // 상점아이디
		$payReqMap['LGD_OID']				   = $LGD_OID;					 // 주문번호
		$payReqMap['LGD_BUYER']				 = $LGD_BUYER;				   // 구매자
		$payReqMap['LGD_PRODUCTINFO']		   = $LGD_PRODUCTINFO;			 // 상품정보
		$payReqMap['LGD_AMOUNT']				= $LGD_AMOUNT;				  // 결제금액
		$payReqMap['LGD_BUYEREMAIL']			= $LGD_BUYEREMAIL;			  // 구매자 이메일
		$payReqMap['LGD_CUSTOM_SKIN']		   = $LGD_CUSTOM_SKIN;			 // 결제창 SKIN
		$payReqMap['LGD_CUSTOM_PROCESSTYPE']	= $LGD_CUSTOM_PROCESSTYPE;	  // 트랜잭션 처리방식
		$payReqMap['LGD_TIMESTAMP']			 = $LGD_TIMESTAMP;			   // 타임스탬프
		$payReqMap['LGD_HASHDATA']			  = $LGD_HASHDATA;				// MD5 해쉬암호값
		$payReqMap['LGD_RETURNURL']			 = element('LGD_RETURNURL', $init);			   // 응답수신페이지
		$payReqMap['LGD_VERSION']			   = element('LGD_VERSION', $init);				 // 버전정보 (삭제하지 마세요)
		$payReqMap['LGD_CUSTOM_USABLEPAY']	  = $LGD_CUSTOM_USABLEPAY;		// 디폴트 결제수단
		$payReqMap['LGD_CUSTOM_SWITCHINGTYPE']  = $LGD_CUSTOM_SWITCHINGTYPE;	// 신용카드 카드사 인증 페이지 연동 방식
		$payReqMap['LGD_WINDOW_VER']			= $LGD_WINDOW_VER;
		$payReqMap['LGD_ENCODING']			  = 'UTF-8';
		$payReqMap['LGD_ENCODING_RETURNURL']	= 'UTF-8';
		$payReqMap['is_pay_pc']	= '1';

		// 가상계좌(무통장) 결제연동을 하시는 경우  할당/입금 결과를 통보받기 위해 반드시 LGD_CASNOTEURL 정보를 LG 유플러스에 전송해야 합니다 .
		$payReqMap['LGD_CASNOTEURL']			= element('LGD_CASNOTEURL', $init);			  // 가상계좌 NOTEURL

		//Return URL에서 인증 결과 수신 시 셋팅될 파라미터 입니다.*/
		$payReqMap['LGD_RESPCODE']			  = '';
		$payReqMap['LGD_RESPMSG']			   = '';
		$payReqMap['LGD_PAYKEY']				= '';

		$this->session->set_userdata('PAYREQ_MAP', $payReqMap);

		// 이벤트가 존재하면 실행합니다
		Events::trigger('after', $eventname);

		die(json_encode(array('LGD_HASHDATA' => $LGD_HASHDATA, 'error' => '')));
	}

	//모바일 일때 요청
	public function xpay_approval($ptype = '')
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_payment_xpay_approval';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		if (empty($ptype)) {
			return;
		}

		$this->load->library(array('paymentlib'));
		$init = $this->paymentlib->lg_init();

		/*
		 * 1. 기본결제 인증요청 정보 변경
		 *
		 * 기본정보를 변경하여 주시기 바랍니다.(파라미터 전달시 POST를 사용하세요)
		 */
		$LGD_OID = $this->input->post('LGD_OID', null, ''); //주문번호(상점정의 유니크한 주문번호를 입력하세요)
		$LGD_AMOUNT = $this->input->post('LGD_AMOUNT', null, ''); //결제금액(',' 를 제외한 결제금액을 입력하세요)
		$LGD_BUYER = $this->input->post('LGD_BUYER', null, ''); //구매자명
		$LGD_PRODUCTINFO = $this->input->post('LGD_PRODUCTINFO', null, ''); //상품명
		$LGD_BUYEREMAIL = $this->input->post('LGD_BUYEREMAIL', null, ''); //구매자 이메일
		$LGD_CUSTOM_FIRSTPAY = $this->input->post('LGD_CUSTOM_FIRSTPAY', null, ''); //상점정의 초기결제수단
		$LGD_TAXFREEAMOUNT = $this->input->post('LGD_TAXFREEAMOUNT', null, ''); //비과세 금액
		$LGD_CASHRECEIPTYN = $this->input->post('LGD_CASHRECEIPTYN', null, ''); //현금영수증 사용설정
		$LGD_BUYERID = $this->input->post('LGD_BUYERID', null, ''); //구매자 ID
		$LGD_BUYERPHONE = $this->input->post('LGD_BUYERPHONE', null, ''); //구매자 휴대폰번호

		$LGD_RETURNURL = element('LGD_RETURNURL', $init) . $ptype;
		$LGD_KVPMISPNOTEURL = site_url('payment/lg_noteurl/' . $ptype);
		$LGD_KVPMISPWAPURL = site_url('payment/lg_mispwap/' . $ptype . '/' . $LGD_OID); //ISP 카드 결제시, URL 대신 앱명 입력시, 앱호출함
		$LGD_KVPMISPCANCELURL = site_url('payment/lg_cancelurl/' . $ptype);
		$LGD_MTRANSFERWAPURL = element('LGD_RETURNURL', $init) . $ptype;
		$LGD_MTRANSFERCANCELURL = site_url('payment/lg_cancelurl/' . $ptype);
		$LGD_MTRANSFERNOTEURL = site_url('payment/lg_noteurl/' . $ptype);

		if (preg_match('/iPhone|iPad/', $this->input->server('HTTP_USER_AGENT'))) {
			$LGD_MTRANSFERAUTOAPPYN = 'N';
		} else {
			$LGD_MTRANSFERAUTOAPPYN = 'A';
		}

		/*
		 *************************************************
		 * 2. MD5 해쉬암호화 (수정하지 마세요) - BEGIN
		 *
		 * MD5 해쉬암호화는 거래 위변조를 막기위한 방법입니다.
		 *************************************************
		 *
		 * 해쉬 암호화 적용( LGD_MID + LGD_OID + LGD_AMOUNT + LGD_TIMESTAMP + LGD_MERTKEY)
		 * LGD_MID : 상점아이디
		 * LGD_OID : 주문번호
		 * LGD_AMOUNT : 금액
		 * LGD_TIMESTAMP : 타임스탬프
		 * LGD_MERTKEY : 상점MertKey (mertkey는 상점관리자 -> 계약정보 -> 상점정보관리에서 확인하실수 있습니다)
		 *
		 * MD5 해쉬데이터 암호화 검증을 위해
		 * LG유플러스에서 발급한 상점키(MertKey)를 환경설정 파일(lgdacom/conf/mall.conf)에 반드시 입력하여 주시기 바랍니다.
		 */

		include(FCPATH . 'plugin/pg/lg/XPayClient.php');
		include(FCPATH . 'plugin/pg/lg/XPay.php');
		$xpay = new XPay(element('configPath', $init), element('CST_PLATFORM', $init));

		// Mert Key 설정
		$xpay->set_config_value('t' . element('LGD_MID', $init), element('pg_lg_key', $init));
		$xpay->set_config_value(element('LGD_MID', $init), element('pg_lg_key', $init));

		$xpay->Init_TX(element('LGD_MID', $init));
		$LGD_HASHDATA = md5(element('LGD_MID', $init) . $LGD_OID . $LGD_AMOUNT . element('LGD_TIMESTAMP', $init) . $xpay->config[element('LGD_MID', $init)]);
		$LGD_CUSTOM_PROCESSTYPE = 'TWOTR';
		/*
		 *************************************************
		 * 2. MD5 해쉬암호화 (수정하지 마세요) - END
		 *************************************************
		 */
		$CST_WINDOW_TYPE = 'submit'; // 수정불가
		$payReqMap['CST_PLATFORM'] = element('CST_PLATFORM', $init); // 테스트, 서비스 구분
		$payReqMap['CST_WINDOW_TYPE'] = $CST_WINDOW_TYPE; // 수정불가
		$payReqMap['CST_MID'] = element('CST_MID', $init); // 상점아이디
		$payReqMap['LGD_MID'] = element('LGD_MID', $init); // 상점아이디
		$payReqMap['LGD_OID'] = $LGD_OID; // 주문번호
		$payReqMap['LGD_BUYER'] = $LGD_BUYER; // 구매자
		$payReqMap['LGD_PRODUCTINFO'] = $LGD_PRODUCTINFO; // 상품정보
		$payReqMap['LGD_AMOUNT'] = $LGD_AMOUNT; // 결제금액
		$payReqMap['LGD_BUYEREMAIL'] = $LGD_BUYEREMAIL; // 구매자 이메일
		$payReqMap['LGD_CUSTOM_SKIN'] = element('LGD_CUSTOM_SKIN', $init); // 결제창 SKIN
		$payReqMap['LGD_CUSTOM_PROCESSTYPE'] = $LGD_CUSTOM_PROCESSTYPE; // 트랜잭션 처리방식
		$payReqMap['LGD_TIMESTAMP'] = element('LGD_TIMESTAMP', $init); // 타임스탬프
		$payReqMap['LGD_HASHDATA'] = $LGD_HASHDATA; // MD5 해쉬암호값
		$payReqMap['LGD_RETURNURL'] = $LGD_RETURNURL; // 응답수신페이지
		$payReqMap['LGD_VERSION'] = 'PHP_SmartXPay_1.0'; // 버전정보 (삭제하지 마세요)
		$payReqMap['LGD_CUSTOM_FIRSTPAY'] = $LGD_CUSTOM_FIRSTPAY; // 디폴트 결제수단
		$payReqMap['LGD_CUSTOM_SWITCHINGTYPE'] = 'SUBMIT'; // 신용카드 카드사 인증 페이지 연동 방식
		$payReqMap['LGD_ENCODING'] = 'UTF-8';
		$payReqMap['LGD_ENCODING_NOTEURL'] = 'UTF-8';
		$payReqMap['LGD_ENCODING_RETURNURL'] = 'UTF-8';
		$payReqMap['LGD_TAXFREEAMOUNT'] = $LGD_TAXFREEAMOUNT;
		$payReqMap['LGD_CASHRECEIPTYN'] = $LGD_CASHRECEIPTYN;
		$payReqMap['LGD_BUYERPHONE'] = $LGD_BUYERPHONE;
		$payReqMap['LGD_BUYERID'] = $LGD_BUYERID;

		/*
		 ****************************************************
		 * 안드로이드폰 신용카드 ISP(국민/BC)결제에만 적용 (시작)*
		 ****************************************************

		(주의)LGD_CUSTOM_ROLLBACK 의 값을 'Y'로 넘길 경우, LG U+ 전자결제에서 보낸 ISP(국민/비씨) 승인정보를 고객서버의 note_url에서 수신시 'OK' 리턴이 안되면 해당 트랜잭션은 무조건 롤백(자동취소)처리되고,
		LGD_CUSTOM_ROLLBACK 의 값 을 'C'로 넘길 경우, 고객서버의 note_url에서 'ROLLBACK' 리턴이 될 때만 해당 트랜잭션은 롤백처리되며 그외의 값이 리턴되면 정상 승인완료 처리됩니다.
		만일, LGD_CUSTOM_ROLLBACK 의 값이 'N' 이거나 null 인 경우, 고객서버의 note_url에서 'OK' 리턴이 안될시, 'OK' 리턴이 될 때까지 3분간격으로 2시간동안 승인결과를 재전송합니다.
		 */

		$payReqMap['LGD_CUSTOM_ROLLBACK'] = ''; // 비동기 ISP에서 트랜잭션 처리여부
		$payReqMap['LGD_KVPMISPNOTEURL'] = $LGD_KVPMISPNOTEURL; // 비동기 ISP(ex. 안드로이드) 승인결과를 받는 URL
		$payReqMap['LGD_KVPMISPWAPURL'] = $LGD_KVPMISPWAPURL; // 비동기 ISP(ex. 안드로이드) 승인완료후 사용자에게 보여지는 승인완료 URL
		$payReqMap['LGD_KVPMISPCANCELURL'] = $LGD_KVPMISPCANCELURL; // ISP 앱에서 취소시 사용자에게 보여지는 취소 URL

		/*
		 ****************************************************
		 * 안드로이드폰 신용카드 ISP(국민/BC)결제에만 적용 (끝) *
		 ****************************************************
		 */

		// 안드로이드 에서 신용카드 적용 ISP(국민/BC)결제에만 적용 (선택)
		// $payReqMap['LGD_KVPMISPAUTOAPPYN'] = 'Y';
		// Y: 안드로이드에서 ISP신용카드 결제시, 고객사에서 'App To App' 방식으로 국민, BC카드사에서 받은 결제 승인을 받고 고객사의 앱을 실행하고자 할때 사용

		// 가상계좌(무통장) 결제연동을 하시는 경우 할당/입금 결과를 통보받기 위해 반드시 LGD_CASNOTEURL 정보를 LG 유플러스에 전송해야 합니다 .
		$payReqMap['LGD_CASNOTEURL'] = element('LGD_CASNOTEURL', $init); // 가상계좌 NOTEURL

		// 계좌이체 파라미터
		//$payReqMap['LGD_MTRANSFERWAPURL'] = $LGD_MTRANSFERWAPURL;
		//$payReqMap['LGD_MTRANSFERCANCELURL'] = $LGD_MTRANSFERCANCELURL;
		$payReqMap['LGD_MTRANSFERNOTEURL'] = $LGD_MTRANSFERNOTEURL;
		$payReqMap['LGD_MTRANSFERAUTOAPPYN'] = $LGD_MTRANSFERAUTOAPPYN;

		//Return URL에서 인증 결과 수신 시 셋팅될 파라미터 입니다.*/
		$payReqMap['LGD_RESPCODE'] = '';
		$payReqMap['LGD_RESPMSG'] = '';
		$payReqMap['LGD_PAYKEY'] = '';

		$view['view']['payReqMap'] = $payReqMap;

		$this->session->set_userdata('PAYREQ_MAP', $payReqMap);

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 레이아웃을 정의합니다
		 */
		$this->data = $view;
		$this->view = 'paymentlib/lg/xpay_approval';
	}


	public function lg_returnurl($ptype = '')
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_payment_lg_returnurl';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		if (empty($ptype)) {
			return;
		}

		/*
		xpay_approval.php 에서 세션에 저장했던 파라미터 값이 유효한지 체크
		세션 유지 시간(로그인 유지시간)을 적당히 유지 하거나 세션을 사용하지 않는 경우 DB처리 하시기 바랍니다.
		 */

		$returnurl = ($ptype === 'deposit') ? site_url('deposit') : site_url('cmall');

		if ( ! $this->session->userdata('PAYREQ_MAP')) {
			alert('세션이 만료 되었거나 유효하지 않은 요청 입니다.', $returnurl);
		}

		$payReqMap = $this->session->userdata('PAYREQ_MAP'); //결제 요청시, Session에 저장했던 파라미터 MAP

		$view['view']['body_script'] = 'onload="setLGDResult();"';

		$view['view']['LGD_RESPCODE'] = $LGD_RESPCODE = $this->input->post_get('LGD_RESPCODE', null, '');
		$view['view']['LGD_RESPMSG'] = $LGD_RESPMSG = $this->input->post_get('LGD_RESPMSG', null, '');
		$view['view']['LGD_PAYKEY'] = $LGD_PAYKEY = '';

		$view['view']['LGD_OID'] = $LGD_OID = $payReqMap['LGD_OID'];

		if ($ptype === 'deposit') {
			$view['view']['order_action_url'] = $order_action_url = site_url('deposit/update');
			$view['view']['page_return_url'] = $page_return_url = site_url('deposit');
		} else {
			$view['view']['order_action_url'] = $order_action_url = site_url('cmall/orderupdate');
			$view['view']['page_return_url'] = $page_return_url = site_url('cmall');
		}

		$payReqMap['LGD_RESPCODE'] = $LGD_RESPCODE;
		$payReqMap['LGD_RESPMSG'] = $LGD_RESPMSG;

		if ($LGD_RESPCODE === '0000') {
			$view['view']['LGD_PAYKEY'] = $LGD_PAYKEY = $this->input->post_get('LGD_PAYKEY', null, '');
			$payReqMap['LGD_PAYKEY'] = $LGD_PAYKEY;
		} else {
			$view['view']['fail_msg'] = 'LGD_RESPCODE:' . $LGD_RESPCODE . ',LGD_RESPMSG:' . $LGD_RESPMSG; //인증 실패에 대한 처리 로직 추가
		}

		if( isset($payReqMap['is_pay_pc']) && isset($payReqMap['LGD_WINDOW_VER']) ){
			$view['view']['data'] = $payReqMap;
		} else {
			$this->load->model('Payment_order_data_model');
			$row = $this->Payment_order_data_model->get_one($LGD_OID);

			$view['view']['data'] = $data = cmall_tmp_replace_data($row['pod_data']);
		}

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 레이아웃을 정의합니다
		 */
		$this->data = $view;
		$this->view = 'paymentlib/lg/lg_returnurl';
	}


	public function lg_noteurl($ptype = '')
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_payment_lg_noteurl';
		$this->load->event($eventname);

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		if (empty($ptype)) {
			return;
		}

		$this->load->library(array('paymentlib'));
		$init = $this->paymentlib->lg_init();

		$LGD_RESPCODE = $this->input->post('LGD_RESPCODE', null, ''); // 응답코드: 0000(성공) 그외 실패
		$LGD_RESPMSG = $this->input->post('LGD_RESPMSG', null, ''); // 응답메세지
		$LGD_MID = $this->input->post('LGD_MID', null, ''); // 상점아이디
		$LGD_OID = $this->input->post('LGD_OID', null, ''); // 주문번호
		$LGD_AMOUNT = $this->input->post('LGD_AMOUNT', null, ''); // 거래금액
		$LGD_TID = $this->input->post('LGD_TID', null, ''); // LG유플러스에서 부여한 거래번호
		$LGD_PAYTYPE = $this->input->post('LGD_PAYTYPE', null, ''); // 결제수단코드
		$LGD_PAYDATE = $this->input->post('LGD_PAYDATE', null, ''); // 거래일시(승인일시/이체일시)
		$LGD_HASHDATA = $this->input->post('LGD_HASHDATA', null, ''); // 해쉬값
		$LGD_FINANCECODE = $this->input->post('LGD_FINANCECODE', null, ''); // 결제기관코드(카드종류/은행코드/이통사코드)
		$LGD_FINANCENAME = $this->input->post('LGD_FINANCENAME', null, ''); // 결제기관이름(카드이름/은행이름/이통사이름)
		$LGD_ESCROWYN = $this->input->post('LGD_ESCROWYN', null, ''); // 에스크로 적용여부
		$LGD_TRANSAMOUNT = $this->input->post('LGD_TRANSAMOUNT', null, ''); // 환율적용금액(신용카드)
		$LGD_EXCHANGERATE = $this->input->post('LGD_EXCHANGERATE', null, ''); // 환율(신용카드)
		$LGD_CARDNUM = $this->input->post('LGD_CARDNUM', null, ''); // 카드번호(신용카드)
		$LGD_CARDINSTALLMONTH = $this->input->post('LGD_CARDINSTALLMONTH', null, ''); // 할부개월수(신용카드)
		$LGD_CARDNOINTYN = $this->input->post('LGD_CARDNOINTYN', null, ''); // 무이자할부여부(신용카드) - '1'이면 무이자할부 '0'이면 일반할부
		$LGD_TIMESTAMP = $this->input->post('LGD_TIMESTAMP', null, ''); // 타임스탬프
		$LGD_FINANCEAUTHNUM = $this->input->post('LGD_FINANCEAUTHNUM', null, ''); // 결제기관 승인번호(신용카드, 계좌이체, 상품권)
		$LGD_PAYTELNUM = $this->input->post('LGD_PAYTELNUM', null, ''); // 결제에 이용된전화번호
		$LGD_ACCOUNTNUM = $this->input->post('LGD_ACCOUNTNUM', null, ''); // 계좌번호(계좌이체, 무통장입금)
		$LGD_CASTAMOUNT = $this->input->post('LGD_CASTAMOUNT', null, ''); // 입금총액(무통장입금)
		$LGD_CASCAMOUNT = $this->input->post('LGD_CASCAMOUNT', null, ''); // 현입금액(무통장입금)
		$LGD_CASFLAG = $this->input->post('LGD_CASFLAG', null, ''); // 무통장입금 플래그(무통장입금) - 'R':계좌할당, 'I':입금, 'C':입금취소
		$LGD_CASSEQNO = $this->input->post('LGD_CASSEQNO', null, ''); // 입금순서(무통장입금)
		$LGD_CASHRECEIPTNUM = $this->input->post('LGD_CASHRECEIPTNUM', null, ''); // 현금영수증 승인번호
		$LGD_CASHRECEIPTSELFYN = $this->input->post('LGD_CASHRECEIPTSELFYN', null, ''); // 현금영수증자진발급제유무 Y: 자진발급제 적용, 그외 : 미적용
		$LGD_CASHRECEIPTKIND = $this->input->post('LGD_CASHRECEIPTKIND', null, ''); // 현금영수증 종류 0: 소득공제용, 1: 지출증빙용
		$LGD_OCBSAVEPOINT = $this->input->post('LGD_OCBSAVEPOINT', null, ''); // OK캐쉬백 적립포인트
		$LGD_OCBTOTALPOINT = $this->input->post('LGD_OCBTOTALPOINT', null, ''); // OK캐쉬백 누적포인트
		$LGD_OCBUSABLEPOINT = $this->input->post('LGD_OCBUSABLEPOINT', null, ''); // OK캐쉬백 사용가능 포인트

		$LGD_BUYER = $this->input->post('LGD_BUYER', null, ''); // 구매자
		$LGD_PRODUCTINFO = $this->input->post('LGD_PRODUCTINFO', null, ''); // 상품명
		$LGD_BUYERID = $this->input->post('LGD_BUYERID', null, ''); // 구매자 ID
		$LGD_BUYERADDRESS = $this->input->post('LGD_BUYERADDRESS', null, ''); // 구매자 주소
		$LGD_BUYERPHONE = $this->input->post('LGD_BUYERPHONE', null, ''); // 구매자 전화번호
		$LGD_BUYEREMAIL = $this->input->post('LGD_BUYEREMAIL', null, ''); // 구매자 이메일
		$LGD_BUYERSSN = $this->input->post('LGD_BUYERSSN', null, ''); // 구매자 주민번호
		$LGD_PRODUCTCODE = $this->input->post('LGD_PRODUCTCODE', null, ''); // 상품코드
		$LGD_RECEIVER = $this->input->post('LGD_RECEIVER', null, ''); // 수취인
		$LGD_RECEIVERPHONE = $this->input->post('LGD_RECEIVERPHONE', null, ''); // 수취인 전화번호
		$LGD_DELIVERYINFO = $this->input->post('LGD_DELIVERYINFO', null, ''); // 배송지

		$LGD_MERTKEY = element('pg_lg_key', $init); //LG유플러스에서 발급한 상점키로 변경해 주시기 바랍니다.

		$LGD_HASHDATA2 = md5($LGD_MID . $LGD_OID . $LGD_AMOUNT . $LGD_RESPCODE . $LGD_TIMESTAMP . $LGD_MERTKEY);

		/*
		 * 상점 처리결과 리턴메세지
		 *
		 * OK : 상점 처리결과 성공
		 * 그외 : 상점 처리결과 실패
		 *
		 * ※ 주의사항 : 성공시 'OK' 문자이외의 다른문자열이 포함되면 실패처리 되오니 주의하시기 바랍니다.
		 */
		$resultMSG = '결제결과 상점 DB처리(NOTE_URL) 결과값을 입력해 주시기 바랍니다.';

		if ($LGD_HASHDATA2 === $LGD_HASHDATA) { //해쉬값 검증이 성공하면
			if ($LGD_RESPCODE === '0000') { //결제가 성공이면
				/*
				 * 거래성공 결과 상점 처리(DB) 부분
				 * 상점 결과 처리가 정상이면 'OK'
				 */
				//if ( 결제성공 상점처리결과 성공)
				$resultMSG = 'OK';
			} else { //결제가 실패이면
				/*
				 * 거래실패 결과 상점 처리(DB) 부분
				 * 상점결과 처리가 정상이면 'OK'
				 */
			//if ( 결제실패 상점처리결과 성공)
			$resultMSG = 'OK';
			}
		} else { //해쉬값 검증이 실패이면
			/*
			 * hashdata검증 실패 로그를 처리하시기 바랍니다.
			 */
			$resultMSG = '결제결과 상점 DB처리(NOTE_URL) 해쉬값 검증이 실패하였습니다.';
		}

		// 이벤트가 존재하면 실행합니다
		Events::trigger('after', $eventname);

		echo $resultMSG;
	}


	public function lg_cancelurl($ptype = '')
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_payment_lg_cancelurl';
		$this->load->event($eventname);

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		if (empty($ptype)) {
			return;
		}

		echo '<!-- 해당 페이지는 사용자가 ISP{국민/BC) 카드 결제를 중단하였을 때, 사용자에게 보여지는 페이지입니다. -->사용자가 ISP(국민/BC) 카드결제을 중단하였습니다.';
		exit;
	}


	public function lg_mispwap($LGD_OID = '')
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_payment_lg_mispwap';
		$this->load->event($eventname);

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		// 결제성공시에만, 고객사에서 생성한 주문번호 (LGD_OID)를 해당페이지로 전송합니다.
		// LGD_KVPMISPNOTEURL 에서 수신한 결제결과값과 연동하여 사용자에게 보여줄 결제완료화면을 구성하시기 바라며,
		// 결제결과는 LGD_KVPMISPNOTEURL 로 먼저 전송되므로 해당건의 DB연동된 결과를 이용하여 결제완료여부를 보이도록 합니다.

		////////////////////////////////////////////////////////////////////////////////////////////////////////
		// 만약, 고객사에서 'App To App' 방식으로 국민, BC카드사에서 받은 결제 승인을 받고 고객사의 앱을 실행하고자 할때
		// 고객사 앱은 initilize function에 응답받는 Custom URL을 호출하면 됩니다.
		// ex) window.location.href = smartxpay://TID=1234567890&OID=0987654321
		//
		// window.location.href = '고객사 앱명://' 로 호출하시면 됩니다.
		////////////////////////////////////////////////////////////////////////////////////////////////////////

		echo 'LGD_OID = ' . $LGD_OID;
		exit;
	}
}