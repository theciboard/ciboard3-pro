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


$config['admin_page_menu']['config'] =
	array(
		'__config'					=> array('환경설정', 'fa-gears'), // 1차 메뉴, 순서대로 (메뉴명, 아이콘클래스(font-awesome))
		'menu'						=> array(
			'cbconfigs'				=> array('기본환경설정', ''), // 2차 메뉴, 순서대로 (메뉴명, a태그에 속성 부여)
			'layoutskin'			=> array('레이아웃/메타태그', ''),
			'memberconfig'			=> array('회원가입설정', ''),
			'emailform'				=> array('메일/쪽지발송양식', ''),
			'rssconfig'				=> array('RSS 피드 / 사이트맵', ''),
			'testemail'				=> array('메일발송테스트', ''),
			'scheduler'				=> array('스케쥴러 관리', ''),
			'cbversion'				=> array('버전정보', ''),
			'dbupgrade'				=> array('DB 업그레이드', ''),
			'browscapupdate'		=> array('Browscap 업데이트', ''),
			'optimize'				=> array('복구/최적화', ''),
			'cleanlog'				=> array('오래된로그삭제', ''),
			'phpinfo'				=> array('phpinfo', 'target="_blank"'),
		),
	);
