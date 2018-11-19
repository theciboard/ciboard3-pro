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


$config['admin_page_menu']['member'] =
	array(
		'__config'					=> array('회원설정', 'fa-users'),
		'menu'						=> array(
			'members'				=> array('회원관리', ''),
			'membergroup'			=> array('회원그룹관리', ''),
			'points'				=> array('포인트관리', ''),
			'memberfollow'			=> array('팔로우현황', ''),
			'nickname'				=> array('닉네임변경이력', ''),
			'memberlevelhistory'	=> array('레벨히스토리', ''),
			'loginlog'				=> array('로그인현황', ''),
			'dormant'				=> array('휴면계정관리', ''),
		),
	);
