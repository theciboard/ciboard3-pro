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


$config['admin_page_menu']['board'] =
	array(
		'__config'					=> array('게시판설정', 'fa-pencil-square-o'),
		'menu'						=> array(
			'boards'				=> array('게시판관리', ''),
			'boardgroup'			=> array('게시판그룹관리', ''),
			'trash'					=> array('휴지통', ''),
			'trash_comment'			=> array('휴지통', '', 'hide'),
			'posthistory'			=> array('게시물변경로그', ''),
			'naversyndilog'			=> array('네이버신디케이션로그', ''),
			'post'					=> array('게시물관리', ''),
			'comment'				=> array('댓글관리', ''),
			'tag'					=> array('태그 관리', ''),
			'fileupload'			=> array('파일업로드', ''),
			'filedownload'			=> array('파일다운로드', ''),
			'editorimage'			=> array('에디터이미지', ''),
			'linkclick'				=> array('링크클릭', ''),
			'like'					=> array('추천/비추', ''),
			'blame'					=> array('신고', ''),
		),
	);
