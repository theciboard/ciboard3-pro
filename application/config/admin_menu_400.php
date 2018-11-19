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


$config['admin_page_menu']['stat'] =
	array(
		'__config'					=> array('통계관리', 'fa-bar-chart-o'),
		'menu'						=> array(
			'statcounter'			=> array('접속자통계', ''),
			'boardcounter'			=> array('게시판별접속자', ''),
			'registercounter'		=> array('회원가입통계', ''),
			'searchkeyword'			=> array('인기검색어현황', ''),
			'currentvisitor'		=> array('현재접속자', ''),
			'registerlog'			=> array('회원가입경로', ''),
		),
	);
