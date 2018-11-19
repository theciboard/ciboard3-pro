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


$config['admin_page_menu']['cmall'] =
	array(
		'__config'					=> array('컨텐츠몰관리', 'fa-shopping-cart'),
		'menu'						=> array(
			'cmallcfg'				=> array('컨텐츠몰환경설정', ''),
			'emailform'				=> array('메일/쪽지발송양식', ''),
			'cmallorder'			=> array('주문내역', ''),
			'pendingbank'			=> array('무통장입금알림', ''),
			'orderlist'				=> array('구매내역', ''),
			'cmallcategory'			=> array('분류관리', ''),
			'cmallitem'				=> array('상품관리', ''),
			'qna'					=> array('상품문의', ''),
			'review'				=> array('상품사용후기', ''),
			'wishlist'				=> array('보관함현황', ''),
			'cmallcart'				=> array('장바구니현황', ''),
			'itemdownload'			=> array('상품다운로드로그', ''),
			'itemhistory'			=> array('상품내용변경로그', ''),
			'linkclick'				=> array('상품데모링크클릭', ''),
			'cmallstat'				=> array('구매통계', ''),
		),
	);
