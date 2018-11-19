<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Selfcert class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 본인인증 관련 페이지에 필요한 controller 입니다.
 */
class Selfcert extends CB_Controller
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
		$this->load->library(array('querystring'));

		if ( ! $this->cbconfig->item('use_selfcert')) {
			alert('이 웹사이트는 본인 인증기능을 사용하지 않습니다.');
		}

	}

	/**
	 * 본인인증 페이지입니다
	 */
	public function index()
	{

		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_selfcert_index';
		$this->load->event($eventname);

		/**
		 * 로그인이 필요한 페이지입니다
		 */
		required_user_login();

		if ( ! $this->cbconfig->item('use_selfcert')) {
			alert('이 웹사이트는 본인 인증기능을 사용하지 않습니다.');
		}

		if ($this->member->item('selfcert_type')) {
			alert('회원님은 이미 본인인증을 받으셨습니다.');
		}

		$mem_id = (int) $this->member->item('mem_id');

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_result_layout'] = Events::trigger('before_result_layout', $eventname);

		$page_title = $this->cbconfig->item('site_meta_title_membermodify');
		$meta_description = $this->cbconfig->item('site_meta_description_membermodify');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_membermodify');
		$meta_author = $this->cbconfig->item('site_meta_author_membermodify');
		$page_name = $this->cbconfig->item('site_page_name_membermodify');

		$layoutconfig = array(
			'path' => 'selfcert',
			'layout' => 'layout',
			'skin' => 'index',
			'layout_dir' => $this->cbconfig->item('layout_selfcert'),
			'mobile_layout_dir' => $this->cbconfig->item('mobile_layout_selfcert'),
			'use_sidebar' => $this->cbconfig->item('sidebar_selfcert'),
			'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_selfcert'),
			'skin_dir' => $this->cbconfig->item('skin_selfcert'),
			'mobile_skin_dir' => $this->cbconfig->item('mobile_skin_selfcert'),
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
	 * 아이핀 본인인증 페이지입니다
	 */
	 public function ipin()
	{
		if ( ! $this->cbconfig->item('use_selfcert_ipin')) {
			alert('이 웹사이트는 아이핀 본인 인증기능을 사용하지 않습니다.');
		}
		if( ! $this->_selfcert_tried_count('ipin')) {
			alert_close('오늘 본인 인증 기능을 ' . $this->cbconfig->item('selfcert_try_limit') . '회 사용하셔서 더 이상 이용하실 수가 없습니다.');
		}
		if ($this->member->item('mem_id') && $this->member->item('selfcert_type')) {
			alert('회원님은 이미 본인인증을 받으셨습니다.');
		}

		if ($this->input->get('redirecturl')) {
			$this->session->set_userdata('redirecturl', $this->input->get('redirecturl'));
		} else {
			$this->session->unset_userdata('redirecturl');
		}

		if($this->cbconfig->item('use_selfcert_ipin') == 'kcb') {
			$kcbconfig = $this->_kcb_ipin_config();

			$option = "C";// Option

			// 명령어
			$cmd = $kcbconfig['exe'] . ' ' . $kcbconfig['keypath'] . ' ' . $kcbconfig['selfcert_kcb_mid'] . '"' . $kcbconfig['reserved1'] .'" "' . $kcbconfig['reserved2'] . '" ' . $kcbconfig['EndPointURL'] . ' ' . $kcbconfig['logpath'] . ' ' . $option;

			// 실행
			exec($cmd, $out, $ret);

			if($ret == 127) {
				alert_close("모듈실행 파일이 존재하지 않습니다.\\n\\n" . basename($exe) . " 파일이 plugin/selfcert/kcb/bin 안에 있어야 합니다.");
			}

			if($ret == 126) {
				alert_close("모듈실행 파일의 실행권한이 없습니다.\\n\\nchmod 755 " . basename($exe) . " 과 같이 실행권한을 부여해 주십시오.");
			}

			if($ret == -1) {
				alert_close("모듈실행 파일의 실행권한이 없습니다.\\n\\ncmd.exe의 IUSER 실행권한이 있는지 확인하여 주십시오.");
			}

			$view['view']['kcbconfig'] = $kcbconfig;
			$view['view']['ret'] = $ret;
			$view['view']['pubkey'] = element(0, $out);
			$view['view']['sig'] = element(1, $out);
			$view['view']['curtime'] = element(2, $out);

			$this->load->view('selfcertplugins/kcb/ipin_form', $view);
		}
	}

	/**
	 * 아이핀 본인인증 결과 리턴페이지입니다
	 */
	 public function kcb_ipin_return()
	{
		if ( ! $this->cbconfig->item('use_selfcert_ipin') OR $this->cbconfig->item('use_selfcert_ipin') != 'kcb') {
			alert('이 웹사이트는 KCB 아이핀 본인 인증기능을 사용하지 않습니다.');
		}
		if( ! $this->_selfcert_tried_count('ipin')) {
			alert_close('오늘 본인 인증 기능을 ' . $this->cbconfig->item('selfcert_try_limit') . '회 사용하셔서 더 이상 이용하실 수가 없습니다.');
		}

		$kcbconfig = $this->_kcb_ipin_config();


		//아이핀팝업에서 조회한 PERSONALINFO이다.
		$encPsnlInfo = $this->input->post('encPsnlInfo');
		if(preg_match('~[^0-9a-zA-Z+/=]~', $encPsnlInfo, $match)) {echo "입력 값 확인이 필요합니다"; exit;}

		//KCB서버 공개키
		$WEBPUBKEY = trim($this->input->post('WEBPUBKEY'));
		if(preg_match('~[^0-9a-zA-Z+/=]~', $WEBPUBKEY, $match)) {echo "입력 값 확인이 필요합니다"; exit;}

		//KCB서버 서명값
		@$WEBSIGNATURE = trim($this->input->post('WEBSIGNATURE'));
		if(preg_match('~[^0-9a-zA-Z+/=]~', $WEBSIGNATURE, $match)) {echo "입력 값 확인이 필요합니다"; exit;}

		//아이핀 서버와 통신을 위한 키파일 생성
		// 파라미터 정의
		$cpubkey = $WEBPUBKEY;	//server publickey
		$csig = $WEBSIGNATURE;	//server signature
		$encdata = $encPsnlInfo; //PERSONALINFO
		$option = "SU";

		// 명령어
		$cmd = $kcbconfig['exe'] . ' ' . $kcbconfig['keypath'] . ' ' . $kcbconfig['selfcert_kcb_mid'] . '"' . $kcbconfig['reserved1'] .'" "' . $kcbconfig['reserved2'] . '" ' . $kcbconfig['EndPointURL'] . ' ' . $kcbconfig['logpath'] . ' ' . $option;

		// 실행
		exec($cmd, $out, $ret);

		$mem_id = (int) $this->member->item('mem_id');

		if($ret != 0) {
			if($ret <=200)
				$resultCd=sprintf("B%03d", $ret);
			else
				$resultCd=sprintf("S%03d", $ret);

			alert_close("아이핀 본인확인 중 오류가 발생했습니다. 오류코드 : " . $resultCd . "\\n\\n문의는 코리아크레딧뷰로 고객센터 02-708-1000 로 해주십시오.");
		}

		// 결과라인에서 값을 추출
		$selfcertinfo =array();
		if($out) {
			foreach($out as $a => $b) {
				if($a < 13) {
					$selfcertinfo[$a] = $b;
				}
			}
		}

		/*
		$field_name_IPIN_DEC = array(
			"dupInfo",	// 0
			"coinfo1",	// 1
			"coinfo2",	// 2
			"ciupdate",	// 3
			"virtualNo",	// 4
			"cpCode",	// 5
			"realName",	// 6
			"cpRequestNumber",	// 7
			"age",	// 8
			"sex",	// 9
			"nationalInfo",	// 10
			"birthDate",	// 11
			"authInfo",	// 12
		);
		*/

		$selfcertinfo['selfcert_type'] = 'ipin';

		// 인증내역기록
		$insertdata = array(
			'mem_id' => $mem_id,
			'msh_company' => 'KCB',
			'msh_certtype' => 'ipin',
			'msh_cert_key' => element(0, $selfcertinfo),
			'msh_datetime' => cdate('Y-m-d H:i:s'),
			'msh_ip' => $this->input->ip_address(),
		);

		$this->load->model('Member_selfcert_history_model');
		$this->Member_selfcert_history_model->insert($insertdata);

		// 중복정보 체크
		$already = $this->_is_already_selfcert($mem_id, element(0, $selfcertinfo));
		if (element('mem_id', $already)) {
			$meminfo = $this->Member_model->get_one(element('mem_id', $already), 'mem_userid');
			alert_close("입력하신 본인확인 정보로 가입된 내역이 존재합니다.\\n회원아이디 : " . element('mem_userid', $meminfo));
		}

		$this->session->set_userdata(
			'selfcertinfo',
			$selfcertinfo
		);

		$view['view']['selfcert_type'] = 'ipin';

		$view['view']['redirecturl'] = $this->session->userdata('redirecturl');
		$this->session->unset_userdata('redirecturl');

		$this->load->view('selfcertplugins/kcb/ipin_result', $view);

	}


	/**
	 * 휴대폰 본인인증 페이지입니다
	 */
	 public function phone()
	{
		if ( ! $this->cbconfig->item('use_selfcert_phone')) {
			alert('이 웹사이트는 휴대폰 본인 인증기능을 사용하지 않습니다.');
		}
		if( ! $this->_selfcert_tried_count('phone')) {
			alert_close('오늘 본인 인증 기능을 ' . $this->cbconfig->item('selfcert_try_limit') . '회 사용하셔서 더 이상 이용하실 수가 없습니다.');
		}
		if ($this->member->item('mem_id') && $this->member->item('selfcert_type')) {
			alert('회원님은 이미 본인인증을 받으셨습니다.');
		}

		if ($this->input->get('redirecturl')) {
			$this->session->set_userdata('redirecturl', $this->input->get('redirecturl'));
		} else {
			$this->session->unset_userdata('redirecturl');
		}

		if($this->cbconfig->item('use_selfcert_phone') == 'kcb') {
			$kcbconfig = $this->_kcb_phone_config();

			$option = "Q";// Option

			// 명령어
			$cmd = $kcbconfig['exe'] . ' ' . $kcbconfig['svcTxSeqno'] . ' "' . $kcbconfig['name'] . '" ' . $kcbconfig['birthday'] . ' ' . $kcbconfig['gender'] . ' ' . $kcbconfig['ntvFrnrTpCd'] . ' ' . $kcbconfig['mblTelCmmCd'] . ' ' . $kcbconfig['mbphnNo'] . ' ' . $kcbconfig['rsv1'] . ' ' . $kcbconfig['rsv2'] . ' ' . $kcbconfig['rsv3'] . ' "' . $kcbconfig['returnMsg'] . '" ' . $kcbconfig['returnUrl'] . ' ' . $kcbconfig['inTpBit'] . ' ' . $kcbconfig['hsCertMsrCd'] . ' ' . $kcbconfig['hsCertRqstCausCd'] . ' ' . $kcbconfig['selfcert_kcb_mid'] . ' ' . $kcbconfig['clientIp'] . ' ' . $kcbconfig['clientDomain'] . ' ' . $kcbconfig['endPointUrl'] . ' ' . $kcbconfig['logpath'] . ' ' . $option;


			// 실행
			exec($cmd, $out, $ret);

			if($ret == 127) {
				alert_close("모듈실행 파일이 존재하지 않습니다.\\n\\n" . basename($exe) . " 파일이 plugin/selfcert/kcb/bin 안에 있어야 합니다.");
			}

			if($ret == 126) {
				alert_close("모듈실행 파일의 실행권한이 없습니다.\\n\\nchmod 755 " . basename($exe) . " 과 같이 실행권한을 부여해 주십시오.");
			}

			if($ret == -1) {
				alert_close("모듈실행 파일의 실행권한이 없습니다.\\n\\ncmd.exe의 IUSER 실행권한이 있는지 확인하여 주십시오.");
			}

			/**************************************************************************
			okname 응답 정보
			**************************************************************************/

			if ($ret == 0) {//성공일 경우 변수를 결과에서 얻음
				$view['view']['retcode'] = $out[0];
				$view['view']['retmsg'] = $out[1];
				$view['view']['e_rqstData'] = $out[2];
			}
			else {
				if($ret <=200)
					$view['view']['retcode'] = sprintf("B%03d", $ret);
				else
					$view['view']['retcode'] = sprintf("S%03d", $ret);
			}

			$view['view']['kcbconfig'] = $kcbconfig;

			$this->load->view('selfcertplugins/kcb/phone_form', $view);
		}

		if($this->cbconfig->item('use_selfcert_phone') == 'kcp') {

			setlocale(LC_CTYPE, 'ko_KR.euc-kr');

			$kcpconfig = $this->_kcp_phone_config();

			$this->load->model('Unique_id_model');
			$view['view']['ordr_idxx'] = $ordr_idxx = $this->Unique_id_model->get_id($this->input->ip_address()); // 거래번호. 동일문자열을 두번 사용할 수 없음. ( 20자리의 문자열. 0-9,A-Z,a-z 사용.)

			$ct_cert = new C_CT_CLI;
			$ct_cert->mf_clear();

			$year			= "00";
			$month			= "00";
			$day			= "00";
			$user_name		= "";
			$sex_code		= "";
			$local_code		= "";

			// !!up_hash 데이터 생성시 주의 사항
			// year , month , day 가 비어 있는 경우 "00" , "00" , "00" 으로 설정이 됩니다
			// 그외의 값은 없을 경우 ""(null) 로 세팅하시면 됩니다.
			// up_hash 데이터 생성시 site_cd 와 ordr_idxx 는 필수 값입니다.
			$hash_data = $kcpconfig['site_cd'] .
						 $ordr_idxx .
						 $user_name .
						 $year .
						 $month .
						 $day .
						 $sex_code .
						 $local_code;

			$view['view']['up_hash'] = $up_hash = $ct_cert->make_hash_data( $kcpconfig['home_dir'], $hash_data );

			$ct_cert->mf_clear();

			$view['view']['kcpconfig'] = $kcpconfig;

			$this->load->view('selfcertplugins/kcp/phone_form', $view);
		}

		if($this->cbconfig->item('use_selfcert_phone') == 'lg') {

			/*
			 * [본인확인 요청페이지]
			 *
			 * 샘플페이지에서는 기본 파라미터만 예시되어 있으며, 별도로 필요하신 파라미터는 연동메뉴얼을 참고하시어 추가 하시기 바랍니다.
			 */

			//LG유플러스 결제 서비스 선택(test:테스트, service:서비스)
			if ($this->cbconfig->item('use_selfcert_test')) {
				$view['view']['CST_PLATFORM'] = $CST_PLATFORM = 'test';
			} else {
				$view['view']['CST_PLATFORM'] = $CST_PLATFORM = 'service';
			}
			$view['view']['CST_MID'] = $CST_MID = $this->cbconfig->item('selfcert_lg_mid'); // 상점아이디(LG유플러스으로 부터 발급받으신 상점아이디를 입력하세요)
																			//테스트 아이디는 't'를 반드시 제외하고 입력하세요.
			$view['view']['LGD_MID'] = $LGD_MID = (('test' == $CST_PLATFORM) ? 't':'').$CST_MID; //상점아이디(자동생성)
			$view['view']['LGD_BUYER'] = $LGD_BUYER = '홍길동';						 // 성명 (보안을 위해 DB난 세션에서 가져오세요)
			$view['view']['LGD_BUYERSSN'] = $LGD_BUYERSSN	= '0000000000000';		// 주민등록번호 (보안을 위해 DB나 세션에서 가져오세요)
																			// 휴대폰 본인인증을 사용할 경우 주민번호는 '0' 13자리를 넘기세요. 예)0000000000000
																			// 기타 인증도 사용할 경우 실 주민등록번호 (보안을 위해 DB나 세션에 저장처리 권장)
			$view['view']['LGD_MOBILE_SUBAUTH_SITECD'] = $LGD_MOBILE_SUBAUTH_SITECD = '';			// 신용평가사에서 부여받은 회원사 고유 코드
																			// (CI값만 필요한 경우 옵션, DI값도 필요한 경우 필수)
			$view['view']['LGD_TIMESTAMP'] = $LGD_TIMESTAMP		= date('YmdHis');			// 타임스탬프 (YYYYMMDDhhmmss)
			$view['view']['LGD_CUSTOM_SKIN'] = $LGD_CUSTOM_SKIN			= 'red';							// 상점정의 인증창 스킨 (red, blue, cyan, green, yellow)

			/*
			 *************************************************
			 * 2. MD5 해쉬암호화 (수정하지 마세요) - BEGIN
			 *
			 * MD5 해쉬암호화는 거래 위변조를 막기위한 방법입니다.
			 *************************************************
			 *
			 * 해쉬 암호화 적용( LGD_MID + LGD_BUYERSSN + LGD_TIMESTAMP + LGD_MERTKEY )
			 * LGD_MID		  : 상점아이디
			 * LGD_BUYERSSN	 : 주민등록번호
			 * LGD_TIMESTAMP	: 타임스탬프
			 * LGD_MERTKEY	  : 상점MertKey (mertkey는 상점관리자 -> 계약정보 -> 상점정보관리에서 확인하실수 있습니다)
			 *
			 * MD5 해쉬데이터 암호화 검증을 위해
			 * LG유플러스에서 발급한 상점키(MertKey)를 환경설정 파일(lgdacom/conf/mall.conf)에 반드시 입력하여 주시기 바랍니다.
			 */

			$view['view']['LGD_MERTKEY'] = $LGD_MERTKEY	= $this->cbconfig->item('selfcert_lg_key');
			$view['view']['LGD_HASHDATA'] = $LGD_HASHDATA = md5($LGD_MID.$LGD_BUYERSSN.$LGD_TIMESTAMP.$LGD_MERTKEY);


			$this->load->view('selfcertplugins/lg/phone_form', $view);


		}
	}


	/**
	 * KCB 휴대폰 본인인증 결과 리턴 페이지입니다
	 */
	 public function kcb_phone_return()
	{
		if ( ! $this->cbconfig->item('use_selfcert_phone') OR $this->cbconfig->item('use_selfcert_phone') != 'kcb') {
			alert('이 웹사이트는 KCB 휴대폰 본인 인증기능을 사용하지 않습니다.');
		}
		if( ! $this->_selfcert_tried_count('phone')) {
			alert_close('오늘 본인 인증 기능을 ' . $this->cbconfig->item('selfcert_try_limit') . '회 사용하셔서 더 이상 이용하실 수가 없습니다.');
		}

		$kcbconfig = $this->_kcb_phone_config();

		/* 공통 리턴 항목 */
		$idcfMbrComCd			= $this->input->post('idcf_mbr_com_cd');	// 고객사코드
		$hsCertSvcTxSeqno		= $this->input->post('hs_cert_svc_tx_seqno'); // 거래번호
		$rqstSiteNm				= $this->input->post('rqst_site_nm');		 // 접속도메인
		$hsCertRqstCausCd		= $this->input->post('hs_cert_rqst_caus_cd'); // 인증요청사유코드 2byte (00:회원가입, 01:성인인증, 02:회원정보수정, 03:비밀번호찾기, 04:상품구매, 99:기타)

		$resultCd				= $this->input->post('result_cd');			// 결과코드
		$resultMsg				= $this->input->post('result_msg');			// 결과메세지
		$certDtTm				= $this->input->post('cert_dt_tm');			// 인증일시

		if($resultCd != 'B000') {
			alert_close('휴대폰 본인확인 중 오류가 발생했습니다. 오류코드 : '.$resultCd.'\\n\\n문의는 코리아크레딧뷰로 고객센터 02-708-1000 로 해주십시오.');
		}

		/**************************************************************************
		 * 모듈 호출	; 생년월일 본인 확인서비스 결과 데이터를 복호화한다.
		 **************************************************************************/
		$encInfo = $this->input->post('encInfo');
		if(preg_match('~[^0-9a-zA-Z+/=]~', $encInfo, $match)) {echo "입력 값 확인이 필요합니다"; exit;}

		//KCB서버 공개키
		$WEBPUBKEY = trim($this->input->post('WEBPUBKEY'));
		if(preg_match('~[^0-9a-zA-Z+/=]~', $WEBPUBKEY, $match)) {echo "입력 값 확인이 필요합니다"; exit;}

		//KCB서버 서명값
		$WEBSIGNATURE = trim($this->input->post('WEBSIGNATURE'));
		if(preg_match('~[^0-9a-zA-Z+/=]~', $WEBSIGNATURE, $match)) {echo "입력 값 확인이 필요합니다"; exit;}

		// ########################################################################
		// # 암호화키 파일 설정 (절대경로) - 파일은 주어진 파일명으로 자동 생성 됨
		// ########################################################################
		$keypath = FCPATH . 'plugin/selfcert/kcb/key/safecert_' . $idcfMbrComCd . '.key';

		$cpubkey = $WEBPUBKEY;	//server publickey
		$csig = $WEBSIGNATURE;	//server signature

		// ########################################################################
		// # 로그 경로 지정 및 권한 부여 (절대경로)
		// # 옵션값에 'L'을 추가하는 경우에만 로그가 생성됨.
		// ########################################################################
		$option = 'SU';

		// 명령어
		$cmd = $kcbconfig['exe'] . ' ' . $keypath . ' ' . $idcfMbrComCd . ' ' . $kcbconfig['endPointUrl'] . ' ' . $WEBPUBKEY . ' ' . $WEBSIGNATURE . ' ' . $encInfo . ' ' . $kcbconfig['logpath'] . ' ' . $option;

		// 실행
		exec($cmd, $out, $ret);

		$mem_id = (int) $this->member->item('mem_id');

		$selfcertinfo =array();
		$resultinfo =array();
		if($ret == 0) {
			// 결과라인에서 값을 추출
			foreach($out as $a => $b) {
				if($a < 17) {
					$resultinfo[$a] = $b;
				}
			}
			$resultCd = $resultinfo[0];
		}
		else {
			if($ret <=200)
				$resultCd=sprintf("B%03d", $ret);
			else
				$resultCd=sprintf("S%03d", $ret);
		}



		/*
		echo "처리결과코드		:$resultCd  <br/>";
		echo "처리결과메시지	:$resultinfo[1]  <br/>";
		echo "거래일련번호		:$resultinfo[2]  <br/>";
		echo "인증일시			:$resultinfo[3]  <br/>";
		echo "DI				:$resultinfo[4]  <br/>";
		echo "CI				:$resultinfo[5]  <br/>";
		echo "성명				:$resultinfo[7]  <br/>";
		echo "생년월일			:$resultinfo[8]  <br/>";
		echo "성별				:$resultinfo[9]  <br/>";
		echo "내외국인구분		:$resultinfo[10] <br/>";
		echo "통신사코드		:$resultinfo[11] <br/>";
		echo "휴대폰번호		:$resultinfo[12] <br/>";
		echo "리턴메시지		:$resultinfo[16] <br/>";
		*/
		$comm_array = array('01' => 'SKT', '02' => 'KT', '03' => 'LGU+', '04' => 'SKT알뜰폰', '05' => 'KT알뜰폰', '06' => 'LGU+알뜰폰');
		$selfcertinfo['selfcert_comm_id'] = $comm_id = element($resultinfo[11], $comm_array);				// 이동통신사 코드
		$selfcertinfo['selfcert_phone'] = get_phone($resultinfo[12]); // 전화번호
		$selfcertinfo['selfcert_username'] = $resultinfo[7];				// 이름
		$birth_day_origin = $resultinfo[8];				// 생년월일
		$selfcertinfo['selfcert_birthday'] = $birth_day_origin ? substr($birth_day_origin, 0 , 4) . '-' . substr($birth_day_origin, 4 , 2) . '-' . substr($birth_day_origin, 6 , 2) : '';
		$selfcertinfo['selfcert_sex'] = $resultinfo[9] == '0' ? '2' : '1';				// 성별코드
		$selfcertinfo['selfcert_local_code'] = $resultinfo[10];				// 내/외국인 정보
		$selfcertinfo['selfcert_ci'] = $resultinfo[5];				// CI
		$selfcertinfo['selfcert_key'] = $resultinfo[4];				// DI 중복가입 확인값
		$selfcertinfo['selfcert_dec_res_cd'] = $resultinfo[0];				// 암호화된 결과코드
		$selfcertinfo['selfcert_dec_mes_msg'] = $resultinfo[1];				// 암호화된 결과메시지

		$selfcertinfo['selfcert_type'] = 'phone';

		// 인증내역기록
		$insertdata = array(
			'mem_id' => $mem_id,
			'msh_company' => 'KCB',
			'msh_certtype' => 'phone',
			'msh_cert_key' => element('selfcert_key', $selfcertinfo),
			'msh_datetime' => cdate('Y-m-d H:i:s'),
			'msh_ip' => $this->input->ip_address(),
		);

		$this->load->model('Member_selfcert_history_model');
		$this->Member_selfcert_history_model->insert($insertdata);

		// 중복정보 체크
		$already = $this->_is_already_selfcert($mem_id, element('selfcert_key', $selfcertinfo));
		if (element('mem_id', $already)) {
			$meminfo = $this->Member_model->get_one(element('mem_id', $already), 'mem_userid');
			alert_close("입력하신 본인확인 정보로 가입된 내역이 존재합니다.\\n회원아이디 : " . element('mem_userid', $meminfo));
		}

		$this->session->set_userdata(
			'selfcertinfo',
			$selfcertinfo
		);

		if ($mem_id) {
			$selfcert_phone = element('selfcert_phone', $selfcertinfo);
			$selfcert_username = element('selfcert_username', $selfcertinfo);
			$selfcert_birthday = element('selfcert_birthday', $selfcertinfo);
			$selfcert_sex = element('selfcert_sex', $selfcertinfo);
			$selfcert_key = element('selfcert_key', $selfcertinfo);
			$metadata = array(
				'selfcert_type' => element('selfcert_type', $selfcertinfo),
				'selfcert_company' => $this->cbconfig->item('use_selfcert_phone'),
				'selfcert_comm_id' => element('selfcert_comm_id', $selfcertinfo),
				'selfcert_phone' => $selfcert_phone,
				'selfcert_username' => $selfcert_username,
				'selfcert_birthday' => $selfcert_birthday,
				'selfcert_sex' => $selfcert_sex,
				'selfcert_key' => $selfcert_key,
			);
			$updatedata = array(
				'mem_username' => $selfcert_username,
				'mem_phone' => $selfcert_phone,
				'mem_birthday' => $selfcert_birthday,
				'mem_sex' => $selfcert_sex,
			);
			$this->load->model('Member_meta_model');
			$this->Member_meta_model->save($mem_id, $metadata);
			$this->Member_model->update($mem_id, $updatedata);
		}

		$view['view']['selfcert_type'] = 'phone';

		$view['view']['redirecturl'] = $this->session->userdata('redirecturl');
		$this->session->unset_userdata('redirecturl');

		$this->load->view('selfcertplugins/kcb/phone_result', $view);

	}


	/**
	 * KCP 휴대폰 본인인증 결과 리턴 페이지입니다
	 */
	 public function kcp_phone_return()
	{
		if ( ! $this->cbconfig->item('use_selfcert_phone') OR $this->cbconfig->item('use_selfcert_phone') != 'kcp') {
			alert('이 웹사이트는 KCP 휴대폰 본인 인증기능을 사용하지 않습니다.');
		}
		if( ! $this->_selfcert_tried_count('phone')) {
			alert_close('오늘 본인 인증 기능을 ' . $this->cbconfig->item('selfcert_try_limit') . '회 사용하셔서 더 이상 이용하실 수가 없습니다.');
		}

		$kcpconfig = $this->_kcp_phone_config();

		$site_cd		= "";
		$ordr_idxx		= "";

		$cert_no		= "";
		$cert_enc_use	= "";
		$enc_info		= "";
		$enc_data		= "";
		$req_tx			= "";

		$enc_cert_data	= "";
		$cert_info		= "";

		$tran_cd		= "";
		$res_cd			= "";
		$res_msg		= "";

		$dn_hash		= "";

		/*------------------------------------------------------------------------*/
		/*  :: 전체 파라미터 남기기											   */
		/*------------------------------------------------------------------------*/

		// request 로 넘어온 값 처리
		$key = array_keys($_POST);
		$sbParam ="";

		for($i=0; $i<count($key); $i++)
		{
			$nmParam = $key[$i];
			$valParam = $this->input->post($nmParam);

			if ( $nmParam == 'site_cd' )
			{
				$site_cd = f_get_parm_str ( $valParam );
			}

			if ( $nmParam == 'ordr_idxx' )
			{
				$ordr_idxx = f_get_parm_str ( $valParam );
			}

			if ( $nmParam == 'res_cd' )
			{
				$res_cd = f_get_parm_str ( $valParam );
			}

			if ( $nmParam == 'cert_enc_use' )
			{
				$cert_enc_use = f_get_parm_str ( $valParam );
			}

			if ( $nmParam == 'req_tx' )
			{
				$req_tx = f_get_parm_str ( $valParam );
			}

			if ( $nmParam == 'cert_no' )
			{
				$cert_no = f_get_parm_str ( $valParam );
			}

			if ( $nmParam == 'enc_cert_data' )
			{
				$enc_cert_data = f_get_parm_str ( $valParam );
			}

			if ( $nmParam == 'dn_hash' )
			{
				$dn_hash = f_get_parm_str ( $valParam );
			}

			// 부모창으로 넘기는 form 데이터 생성 필드
			$sbParam .= "<input type='hidden' name='" . $nmParam . "' value='" . f_get_parm_str( $valParam ) . "'/>";
		}

		$ct_cert = new C_CT_CLI;
		$ct_cert->mf_clear();


		// 결과 처리
		if( $cert_enc_use == "Y" )
		{
			$mem_id = (int) $this->member->item('mem_id');

			if( $res_cd == "0000" )
			{
				// dn_hash 검증
				// KCP 가 리턴해 드리는 dn_hash 와 사이트 코드, 주문번호 , 인증번호를 검증하여
				// 해당 데이터의 위변조를 방지합니다
				 $veri_str = $site_cd.$ordr_idxx.$cert_no; // 사이트 코드 + 주문번호 + 인증거래번호

				if ( $ct_cert->check_valid_hash ( $kcpconfig['home_dir'] , $dn_hash , $veri_str ) != "1" )
				{
					// 검증 실패시 처리 영역
					if(PHP_INT_MAX == 2147483647) // 32-bit
						$bin_exe = '/bin/ct_cli';
					else
						$bin_exe = '/bin/ct_cli_x64';

					echo "dn_hash 변조 위험있음 (".FCPATH . 'plugin/selfcert/kcp/' . $bin_exe . " 파일에 실행권한이 있는지 확인하세요.)";
					exit;
					// 오류 처리 ( dn_hash 변조 위험있음)
				}

				// 가맹점 DB 처리 페이지 영역

				// 인증데이터 복호화 함수
				// 해당 함수는 암호화된 enc_cert_data 를
				// site_cd 와 cert_no 를 가지고 복화화 하는 함수 입니다.
				// 정상적으로 복호화 된경우에만 인증데이터를 가져올수 있습니다.
				$opt = "1" ; // 복호화 인코딩 옵션 ( UTF - 8 사용시 "1" )
				$ct_cert->decrypt_enc_cert( $kcpconfig['home_dir'] , $site_cd , $cert_no , $enc_cert_data , $opt );

				$selfcertinfo['selfcert_comm_id'] = $comm_id = $ct_cert->mf_get_key_value("comm_id"	);				// 이동통신사 코드
				$selfcertinfo['selfcert_phone'] = get_phone($ct_cert->mf_get_key_value("phone_no")); // 전화번호
				$selfcertinfo['selfcert_username'] = $ct_cert->mf_get_key_value("user_name");				// 이름
				$birth_day_origin = $ct_cert->mf_get_key_value("birth_day");				// 생년월일
				$selfcertinfo['selfcert_birthday'] = $birth_day_origin ? substr($birth_day_origin, 0 , 4) . '-' . substr($birth_day_origin, 4 , 2) . '-' . substr($birth_day_origin, 6 , 2) : '';
				$selfcertinfo['selfcert_sex'] = $ct_cert->mf_get_key_value("sex_code") == '02' ? '2' : '1';				// 성별코드
				$selfcertinfo['selfcert_local_code'] = $ct_cert->mf_get_key_value("local_code" );				// 내/외국인 정보
				$selfcertinfo['selfcert_ci'] = $ct_cert->mf_get_key_value("ci"		 );				// CI
				$selfcertinfo['selfcert_key'] = $ct_cert->mf_get_key_value("di"		 );				// DI 중복가입 확인값
				$selfcertinfo['selfcert_dec_res_cd'] = $ct_cert->mf_get_key_value("res_cd"	 );				// 암호화된 결과코드
				$selfcertinfo['selfcert_dec_mes_msg'] = $ct_cert->mf_get_key_value("res_msg"	);				// 암호화된 결과메시지


				// 정상인증인지 체크
				if( ! $selfcertinfo['selfcert_phone'])
					alert_close("정상적인 인증이 아닙니다. 올바른 방법으로 이용해 주세요.");

				$selfcertinfo['selfcert_type'] = 'phone';

				// 인증내역기록
				$insertdata = array(
					'mem_id' => $mem_id,
					'msh_company' => 'KCP',
					'msh_certtype' => 'phone',
					'msh_cert_key' => element('selfcert_key', $selfcertinfo),
					'msh_datetime' => cdate('Y-m-d H:i:s'),
					'msh_ip' => $this->input->ip_address(),
				);

				$this->load->model('Member_selfcert_history_model');
				$this->Member_selfcert_history_model->insert($insertdata);

				// 중복정보 체크
				$already = $this->_is_already_selfcert($mem_id, element('selfcert_key', $selfcertinfo));
				if (element('mem_id', $already)) {
					$meminfo = $this->Member_model->get_one(element('mem_id', $already), 'mem_userid');
					alert_close("입력하신 본인확인 정보로 가입된 내역이 존재합니다.\\n회원아이디 : " . element('mem_userid', $meminfo));
				}

				$this->session->set_userdata(
					'selfcertinfo',
					$selfcertinfo
				);
				if ($mem_id) {
					$selfcert_phone = element('selfcert_phone', $selfcertinfo);
					$selfcert_username = element('selfcert_username', $selfcertinfo);
					$selfcert_birthday = element('selfcert_birthday', $selfcertinfo);
					$selfcert_sex = element('selfcert_sex', $selfcertinfo);
					$selfcert_key = element('selfcert_key', $selfcertinfo);
					$metadata = array(
						'selfcert_type' => element('selfcert_type', $selfcertinfo),
						'selfcert_company' => $this->cbconfig->item('use_selfcert_phone'),
						'selfcert_comm_id' => element('selfcert_comm_id', $selfcertinfo),
						'selfcert_phone' => $selfcert_phone,
						'selfcert_username' => $selfcert_username,
						'selfcert_birthday' => $selfcert_birthday,
						'selfcert_sex' => $selfcert_sex,
						'selfcert_key' => $selfcert_key,
					);
					$updatedata = array(
						'mem_username' => $selfcert_username,
						'mem_phone' => $selfcert_phone,
						'mem_birthday' => $selfcert_birthday,
						'mem_sex' => $selfcert_sex,
					);
					$this->load->model('Member_meta_model');
					$this->Member_meta_model->save($mem_id, $metadata);
					$this->Member_model->update($mem_id, $updatedata);
				}

				$view['view']['selfcert_type'] = 'phone';

			}
			else if( $res_cd != "0000" )
			{
				// 인증실패
				alert_close('코드 : '.$this->input->post('res_cd').' '.urldecode($this->input->post('res_msg')));
				exit;
			}
		}
		else if( $cert_enc_use != "Y" )
		{
			// 암호화 인증 안함
			alert_close("휴대폰 본인확인을 취소 하셨습니다.");
			exit;
		}

		$ct_cert->mf_clear();

		$view['view']['redirecturl'] = $this->session->userdata('redirecturl');
		$this->session->unset_userdata('redirecturl');

		$this->load->view('selfcertplugins/kcp/phone_result', $view);

	}


	/**
	 * LG 휴대폰 본인인증 결과 리턴 페이지입니다
	 */
	 public function lg_phone_return()
	{
		if ( ! $this->cbconfig->item('use_selfcert_phone') OR $this->cbconfig->item('use_selfcert_phone') != 'lg') {
			alert('이 웹사이트는 LG 휴대폰 본인 인증기능을 사용하지 않습니다.');
		}
		if( ! $this->_selfcert_tried_count('phone')) {
			alert_close('오늘 본인 인증 기능을 ' . $this->cbconfig->item('selfcert_try_limit') . '회 사용하셔서 더 이상 이용하실 수가 없습니다.');
		}

		$_POST = array_map_deep('conv_unescape_nl', $_POST);

		/*
		 * [본인확인 처리 페이지]
		 *
		 * LG유플러스으로 부터 내려받은 LGD_AUTHONLYKEY(인증Key)를 가지고 최종 인증요청.(파라미터 전달시 POST를 사용하세요)
		 */

		/*
		 *************************************************
		 * 1.최종인증 요청 - BEGIN
		 *************************************************
		 */

		//LG유플러스 결제 서비스 선택(test:테스트, service:서비스)
		if ($this->cbconfig->item('use_selfcert_test')) {
			$view['view']['CST_PLATFORM'] = $CST_PLATFORM = 'service';
		} else {
			$view['view']['CST_PLATFORM'] = $CST_PLATFORM = 'test';
		}
		$view['view']['CST_MID'] = $CST_MID = $this->cbconfig->item('selfcert_lg_mid');				// 상점아이디(LG유플러스으로 부터 발급받으신 상점아이디를 입력하세요)
																									//테스트 아이디는 't'를 반드시 제외하고 입력하세요.
		$view['view']['LGD_MID'] = $LGD_MID = (('test' == $CST_PLATFORM) ? 't' : '').$CST_MID;		//상점아이디(자동생성)
		$LGD_AUTHONLYKEY		= $_POST['LGD_AUTHONLYKEY'];										//LG유플러스으로부터 부여받은 인증키
		$LGD_PAYTYPE			= $_POST['LGD_PAYTYPE'];											//인증요청타입 (신용카드:ASC001, 휴대폰:ASC002, 계좌:ASC004)

		require_once(FCPATH . 'plugin/selfcert/lg/XPayClient.php');
		require_once(FCPATH . 'plugin/selfcert/lg/XPay.php');

		$configPath = FCPATH . 'plugin/selfcert/lg'; //LG유플러스에서 제공한 환경파일("/conf/lgdacom.conf,/conf/mall.conf") 위치 지정.

		$xpay = new XPay($configPath, $CST_PLATFORM);

		// Mert Key 설정
		$xpay->set_config_value('t'.$LGD_MID, $this->cbconfig->item('selfcert_lg_key'));
		$xpay->set_config_value($LGD_MID, $this->cbconfig->item('selfcert_lg_key'));

		$xpay->Init_TX($LGD_MID);
		$xpay->Set("LGD_TXNAME", "AuthOnlyByKey");
		$xpay->Set("LGD_AUTHONLYKEY", $LGD_AUTHONLYKEY);
		$xpay->Set("LGD_PAYTYPE", $LGD_PAYTYPE);


		/*
		 *************************************************
		 * 1.최종인증 요청(수정하지 마세요) - END
		 *************************************************
		 */

		/*
		 * 2. 최종인증 요청 결과처리
		 *
		 * 최종 인증요청 결과 리턴 파라미터는 연동메뉴얼을 참고하시기 바랍니다.
		 */
		if ($xpay->TX()) {
			//1)인증결과 화면처리(성공,실패 결과 처리를 하시기 바랍니다.)
			/*
			echo "인증요청이 완료되었습니다. <br>";
			echo "TX Response_code = " . $xpay->Response_Code() . "<br>";
			echo "TX Response_msg = " . $xpay->Response_Msg() . "<p>";

			$keys = $xpay->Response_Names();
			foreach($keys as $name) {
				echo $name . " = " . $xpay->Response($name, 0) . "<br>";
			}

			echo "<p>";
			*/

			$mem_id = (int) $this->member->item('mem_id');

			if( "0000" == $xpay->Response_Code() ) {
				//인증요청 결과 성공 DB처리
				//echo "인증요청 결과 성공 DB처리하시기 바랍니다.<br>";

				$selfcertinfo['cert_no'] = $cert_no = $xpay->Response('LGD_TID', 0);								// LG 인증처리번호
				$selfcertinfo['selfcert_comm_id'] = $comm_id = $xpay->Response('LGD_FINANCECODE', 0);				// 이동통신사 코드
				$selfcertinfo['selfcert_phone'] = $phone_no = get_phone($xpay->Response('LGD_MOBILENUM', 0));		// 전화번호
				$selfcertinfo['selfcert_username'] = $user_name = $xpay->Response('LGD_MOBILE_SUBAUTH_NAME', 0);	// 이름
				$birth_day = $xpay->Response('LGD_MOBILE_SUBAUTH_BIRTH', 0);										// 생년월일
				$selfcertinfo['sex_code'] = $sex_code = $xpay->Response('LGD_MOBILE_SUBAUTH_SEX', 0);				// 성별코드
				$selfcertinfo['ci'] = $ci = $xpay->Response('LGD_AUTHSUB_CI', 0);									// CI
				$selfcertinfo['selfcert_key'] = $di = $xpay->Response('LGD_AUTHSUB_DI', 0);							// DI 중복가입 확인값

				// 내/외국인
				if($sex_code > 4)
					$selfcertinfo['local_code'] = $local_code = 2; // 외국인
				else
					$selfcertinfo['local_code'] = $local_code = 1; // 내국인

				// 남/여구분
				if($sex_code % 2 == 0)
					$selfcertinfo['mb_sex'] = $mb_sex = 'F';
				else
					$selfcertinfo['mb_sex'] = $mb_sex = 'M';

				// 생년월일
				if($sex_code < 5) {
					if($sex_code <= 2)
						$birth_prefix = '19';
					else
						$birth_prefix = '20';
				} else {
					if($sex_code <= 6)
						$birth_prefix = '19';
					else
						$birth_prefix = '20';
				}
				$selfcertinfo['birth_day'] = $birth_day = $birth_prefix.$birth_day;

				// 정상인증인지 체크
				if(!$phone_no)
					alert_close("정상적인 인증이 아닙니다. 올바른 방법으로 이용해 주세요.");

				$selfcertinfo['selfcert_type'] = 'phone';

				// 인증내역기록
				$insertdata = array(
					'mem_id' => $mem_id,
					'msh_company' => 'LG',
					'msh_certtype' => 'phone',
					'msh_cert_key' => element('selfcert_key', $selfcertinfo),
					'msh_datetime' => cdate('Y-m-d H:i:s'),
					'msh_ip' => $this->input->ip_address(),
				);

				$this->load->model('Member_selfcert_history_model');
				$this->Member_selfcert_history_model->insert($insertdata);


				// 중복정보 체크
				$already = $this->_is_already_selfcert($mem_id, element('selfcert_key', $selfcertinfo));
				if (element('mem_id', $already)) {
					$meminfo = $this->Member_model->get_one(element('mem_id', $already), 'mem_userid');
					alert_close("입력하신 본인확인 정보로 가입된 내역이 존재합니다.\\n회원아이디 : " . element('mem_userid', $meminfo));
				}

				$this->session->set_userdata(
					'selfcertinfo',
					$selfcertinfo
				);

				if ($mem_id) {
					$selfcert_phone = element('selfcert_phone', $selfcertinfo);
					$selfcert_username = element('selfcert_username', $selfcertinfo);
					$selfcert_birthday = element('selfcert_birthday', $selfcertinfo);
					$selfcert_sex = element('selfcert_sex', $selfcertinfo);
					$selfcert_key = element('selfcert_key', $selfcertinfo);
					$metadata = array(
						'selfcert_type' => element('selfcert_type', $selfcertinfo),
						'selfcert_company' => $this->cbconfig->item('use_selfcert_phone'),
						'selfcert_comm_id' => element('selfcert_comm_id', $selfcertinfo),
						'selfcert_phone' => $selfcert_phone,
						'selfcert_username' => $selfcert_username,
						'selfcert_birthday' => $selfcert_birthday,
						'selfcert_sex' => $selfcert_sex,
						'selfcert_key' => $selfcert_key,
					);
					$updatedata = array(
						'mem_username' => $selfcert_username,
						'mem_phone' => $selfcert_phone,
						'mem_birthday' => $selfcert_birthday,
						'mem_sex' => $selfcert_sex,
					);
					$this->load->model('Member_meta_model');
					$this->Member_meta_model->save($mem_id, $metadata);
					$this->Member_model->update($mem_id, $updatedata);
				}

				$view['view']['selfcert_type'] = 'phone';

				$view['view']['redirecturl'] = $this->session->userdata('redirecturl');
				$this->session->unset_userdata('redirecturl');

				$this->load->view('selfcertplugins/lg/phone_result', $view);

			} else {
				//인증요청 결과 실패 DB처리
				//echo "인증요청 결과 실패 DB처리하시기 바랍니다.<br>";

				alert_close('인증요청이 실패하였습니다.\\n\\n코드 : '.$xpay->Response_Code().' '.$xpay->Response_Msg());
				exit;
			}
		} else {
			//2)API 요청실패 화면처리
			/*
			echo "인증요청이 실패하였습니다. <br>";
			echo "TX Response_code = " . $xpay->Response_Code() . "<br>";
			echo "TX Response_msg = " . $xpay->Response_Msg() . "<p>";

			//인증요청 결과 실패 DB처리
			echo "인증요청 결과 실패 DB처리하시기 바랍니다.<br>";
			*/

			alert_close('인증요청이 실패하였습니다.\\n\\n코드 : '.$xpay->Response_Code().' '.$xpay->Response_Msg());
			exit;
		}

	}


	/**
	 * 본인인증 사용회수 체크 페이지입니다
	 */
	public function _selfcert_tried_count($type = '')
	{

		if ( ! $this->cbconfig->item('selfcert_try_limit')) {
			return true;
		}

		$this->load->model('Member_selfcert_history_model');

		$date = cdate('Y-m-d');

		$count = $this->Member_selfcert_history_model->tried_count($type, $date, $this->member->item('mem_id'), $this->input->ip_address());

		if ($count['cnt'] >= $this->cbconfig->item('selfcert_try_limit')) {
			return false;
		}

		return true;
	}

	public function _kcb_ipin_config()
	{

		$key_dir = FCPATH . 'plugin/selfcert/kcb/key';
		if(!is_dir($key_dir)) {
			alert_close('plugin/selfcert/kcb 에 key 디렉토리를 생성해 주십시오.\\n\\n디렉토리 생성 후 쓰기권한을 부여해 주십시오. 예: chmod 707 key');
		}

		if (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN') {
			$sapi_type = php_sapi_name();
			if (substr($sapi_type, 0, 3) == 'cgi') {
				if (!(is_readable($key_dir) && is_executable($key_dir)))
				{
					alert_close("plugin/selfcert/kcb/key 디렉토리의 퍼미션을 705로 변경하여 주십시오.\\nchmod 705 key 또는 chmod uo+rx key");
				}
			} else {
				if (!(is_readable($key_dir) && is_writeable($key_dir) && is_executable($key_dir)))
				{
					alert_close("plugin/selfcert/kcb/key 디렉토리의 퍼미션을 707로 변경하여 주십시오.\\n\\nchmod 707 key 또는 chmod uo+rwx key");
				}
			}
		}

		$return = array();

		// 실행모듈
		if(strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN') {
			if(PHP_INT_MAX == 2147483647) // 32-bit
				$exe = FCPATH . 'plugin/selfcert/kcb/bin/okname';
			else
				$exe = FCPATH . 'plugin/selfcert/kcb/bin/okname_x64';
		} else {
			if(PHP_INT_MAX == 2147483647) // 32-bit
				$exe = FCPATH . 'plugin/selfcert/kcb/bin/okname.exe';
			else
				$exe = FCPATH . 'plugin/selfcert/kcb/bin/oknamex64.exe';
		}

		$return['exe'] = $exe;

		if ($this->cbconfig->item('use_selfcert_test')) {
			// 테스트일 경우
			$return['selfcert_kcb_mid'] = 'P00000000000';
			$return['idpUrl'] = 'https://tmpin.ok-name.co.kr:5443/tis/ti/POTI90B_SendCertInfo.jsp';
			$return['EndPointURL'] = 'http://twww.ok-name.co.kr:8888/KcbWebService/OkNameService'; //EndPointURL, 테스트 서버
			$return['kcbForm_action'] = 'https://tmpin.ok-name.co.kr:5443/tis/ti/POTI01A_LoginRP.jsp';
		} else {
			// 실서비스일 경우
			$return['selfcert_kcb_mid'] = $this->cbconfig->item('selfcert_kcb_mid');
			$return['idpUrl'] = 'https://ipin.ok-name.co.kr/tis/ti/POTI90B_SendCertInfo.jsp';
			$return['EndPointURL'] = 'http://www.ok-name.co.kr/KcbWebService/OkNameService'; // 운영 서버
			$return['kcbForm_action'] = 'https://ipin.ok-name.co.kr/tis/ti/POTI01A_LoginRP.jsp';
		}

		$return['idpCode']		= 'V';
		$return['returnUrl']	= site_url('selfcert/kcb_ipin_return');	// 아이핀 인증을 마치고 돌아올 페이지 주소
		$return['keypath']		= FCPATH . 'plugin/selfcert/kcb/key/okname.key';	// 키파일이 생성될 위치. 웹서버에 해당파일을 생성할 권한 필요.
		$return['reserved1']	= '0'; //reserved1
		$return['reserved2']	= '0'; //reserved2
		$return['logpath']		= APPPATH . 'logs';

		return $return;
	}


	public function _kcb_phone_config()
	{

		$key_dir = FCPATH . 'plugin/selfcert/kcb/key';
		if(!is_dir($key_dir)) {
			alert_close('plugin/selfcert/kcb 에 key 디렉토리를 생성해 주십시오.\\n\\n디렉토리 생성 후 쓰기권한을 부여해 주십시오. 예: chmod 707 key');
		}

		if (strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN') {
			$sapi_type = php_sapi_name();
			if (substr($sapi_type, 0, 3) == 'cgi') {
				if (!(is_readable($key_dir) && is_executable($key_dir)))
				{
					alert_close("plugin/selfcert/kcb/key 디렉토리의 퍼미션을 705로 변경하여 주십시오.\\nchmod 705 key 또는 chmod uo+rx key");
				}
			} else {
				if (!(is_readable($key_dir) && is_writeable($key_dir) && is_executable($key_dir)))
				{
					alert_close("plugin/selfcert/kcb/key 디렉토리의 퍼미션을 707로 변경하여 주십시오.\\n\\nchmod 707 key 또는 chmod uo+rwx key");
				}
			}
		}

		$return = array();

		/**************************************************************************
		 * okname 생년월일 본인 확인서비스 파라미터
		 **************************************************************************/

		$return['inTpBit'] = '0';								// 입력구분코드(고정값 '0' : KCB팝업에서 개인정보 입력)
		$return['name'] = 'x';									// 성명 (고정값 'x')
		$return['birthday'] = 'x';								// 생년월일 (고정값 'x')
		$return['gender'] = 'x';								// 성별 (고정값 'x')
		$return['ntvFrnrTpCd'] = 'x';							// 내외국인구분 (고정값 'x')
		$return['mblTelCmmCd'] = 'x';							// 이동통신사코드 (고정값 'x')
		$return['mbphnNo'] = 'x';								// 휴대폰번호 (고정값 'x')

		$this->load->model('Unique_id_model');
		$return['svcTxSeqno'] = $this->Unique_id_model->get_id($this->input->ip_address()); // 거래번호. 동일문자열을 두번 사용할 수 없음. ( 20자리의 문자열. 0-9,A-Z,a-z 사용.)

		$return['clientIp'] = $this->input->server('SERVER_ADDR');				// 회원사 IP, $_SERVER["SERVER_ADDR"] 사용가능.
		$return['clientDomain'] = $this->input->server('HTTP_HOST');			// 회원사 도메인, $_SERVER["HTTP_HOST"] 사용가능.

		$return['rsv1'] = '0';										// 예약 항목
		$return['rsv2'] = '0';										// 예약 항목
		$return['rsv3'] = '0';										// 예약 항목

		$return['hsCertMsrCd'] = '10';								// 인증수단코드 2byte (10:핸드폰)
		$return['hsCertRqstCausCd'] = '00';							// 인증요청사유코드 2byte (00:회원가입, 01:성인인증, 02:회원정보수정, 03:비밀번호찾기, 04:상품구매, 99:기타)

		$return['returnMsg'] = 'x';									// 리턴메시지 (고정값 'x')

		//okname 실행 정보
		// ########################################################################
		// # 모듈 경로 지정 및 권한 부여 (절대경로)
		// ########################################################################

		// 실행모듈
		if(strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN') {
			if(PHP_INT_MAX == 2147483647) // 32-bit
				$exe = FCPATH . 'plugin/selfcert/kcb/bin/okname';
			else
				$exe = FCPATH . 'plugin/selfcert/kcb/bin/okname_x64';
		} else {
			if(PHP_INT_MAX == 2147483647) // 32-bit
				$exe = FCPATH . 'plugin/selfcert/kcb/bin/okname.exe';
			else
				$exe = FCPATH . 'plugin/selfcert/kcb/bin/oknamex64.exe';
		}

		$return['exe'] = $exe;

		$return['logpath'] = APPPATH . 'logs';
		$targetId = '';									 // 타겟ID (팝업오픈 스크립트의 window.name 과 동일하게 설정

		if($this->cbconfig->item('use_selfcert_test')) {
			// 테스트일 경우
			$return['selfcert_kcb_mid'] = 'P00000000000';
			$return['commonSvlUrl'] = 'https://tsafe.ok-name.co.kr:2443/CommonSvl';
			$return['endPointUrl'] = 'http://tsafe.ok-name.co.kr:29080/KcbWebService/OkNameService';
		} else {
			// 실서비스일 경우
			$return['selfcert_kcb_mid'] = $this->cbconfig->item('selfcert_kcb_mid');
			$return['commonSvlUrl'] = 'https://safe.ok-name.co.kr/CommonSvl';
			$return['endPointUrl'] = 'http://safe.ok-name.co.kr/KcbWebService/OkNameService';
		}

		if( ! $return['selfcert_kcb_mid'])
			alert('기본환경설정에서 KCB 회원사ID를 입력해 주십시오.');

		// ########################################################################
		// # 리턴 URL 설정
		// ########################################################################
		$return['returnUrl'] = site_url('selfcert/kcb_phone_return');			// 본인인증 완료후 리턴될 URL (도메인 포함 full path)


		return $return;
	}


	public function _kcp_phone_config()
	{

		$return['home_dir'] = $home_dir = FCPATH . 'plugin/selfcert/kcp'; // ct_cli 절대경로 ( bin 전까지 )

		// DI 를 위한 중복확인 식별 아이디
		//web_siteid 값이 없으면 KCP 에서 지정한 값으로 설정됨
		$web_siteid = '';

		if($this->cbconfig->item('use_selfcert_test')) {
			// 테스트일 경우
			$return['site_cd'] = 'S6186';
			$return['cert_url'] = 'https://testcert.kcp.co.kr/kcp_cert/cert_view.jsp';
		} else {
			$return['site_cd'] = $this->cbconfig->item('selfcert_kcp_mid');
			$return['cert_url'] = 'https://cert.kcp.co.kr/kcp_cert/cert_view.jsp';
		}

		if( ! $return['site_cd'])
			alert_close('KCP 휴대폰 본인확인 서비스 사이트코드가 없습니다.\\관리자 > 기본환경설정에 KCP 사이트코드를 입력해 주십시오.');


		// KCP 인증 라이브러리
		require_once $home_dir . '/lib/ct_cli_lib.php';
		require_once $home_dir . '/lib/kcp.lib.php';

		return $return;
	}

	public function _is_already_selfcert($mem_id = 0, $selfkey = '')
	{
		$this->db->select('mem_id');
		$this->db->where('mmt_key', 'selfcert_key');
		$this->db->where('mmt_value', $selfkey);
		$this->db->where('mem_id <>', $mem_id);
		$qry = $this->db->get('member_meta');
		$result = $qry->row_array();

		return $result;

	}
}
