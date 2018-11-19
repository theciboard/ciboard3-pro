<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *--------------------------------------------------------------------------
 *Admin Page 에 보일 메뉴를 정의합니다.
 *--------------------------------------------------------------------------
 *
 * Admin Page 에 새로운 메뉴 추가시 이곳에서 수정해주시면 됩니다.
 *
 */


$config['admin_page_menu']['sms'] =
	array(
		'__config'					=> array('SMS 설정', 'fa-phone'),
		'menu'						=> array(
			'smscfg'				=> array('SMS 환경설정', ''),
			'memberupdate'			=> array('회원정보 업데이트', ''),
			'smssend'				=> array('문자발송하기', ''),
			'smshistory'			=> array('전송내역(건별)', ''),
			'smshistorynum'			=> array('전송내역(번호별)', ''),
			'smsfavorite'			=> array('자주보내는 문자관리', ''),
			'phonegroup'			=> array('휴대폰번호그룹', ''),
			'phonelist'				=> array('휴대폰번호관리', ''),
		),
	);
