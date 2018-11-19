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


$config['admin_page_menu']['deposit'] =
	array(
		'__config'					=> array('예치금관리', 'fa-money'),
		'menu'						=> array(
			'depositcfg'			=> array('예치금환경설정', ''),
			'emailform'				=> array('메일/쪽지발송양식', ''),
			'pendingbank'			=> array('무통장입금알림', ''),
			'depositlist'			=> array('예치금변동내역', ''),
			'depositstat'			=> array('예치금통계', ''),
		),
	);
