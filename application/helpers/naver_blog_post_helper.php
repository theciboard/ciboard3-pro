<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Baisc helper
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 네이버 블로그에 포스팅하기
 */
if ( ! function_exists('naver_blog_post')) {

	function naver_blog_post($title, $description, $board)
	{

		$CI =& get_instance();

		if ( ! $CI->cbconfig->item('use_naver_blog_post')) {
			return;
		}
		if ( ! $CI->cbconfig->item('naver_blog_userid')) {
			return;
		}
		if ( ! $CI->cbconfig->item('naver_blog_api_key')) {
			return;
		}

		// 이 게시판은 네이버 블로그 자동등록 기능을 사용하지 않습니다
		if ( ! element('use_naver_blog_post', $board)) {
			return;
		}

		$blog_api_url = "https://api.blog.naver.com/xmlrpc";
		$user_id = $CI->cbconfig->item('naver_blog_userid');
		$blogid = $CI->cbconfig->item('naver_blog_userid');
		$password = $CI->cbconfig->item('naver_blog_api_key');
		$publish = true;

		include_once(FCPATH . 'plugin/xmlrpc/xmlrpc.lib.php');

		$client = new xmlrpc_client($blog_api_url);


		$client->setSSLVerifyPeer(false); // 기본값은 true인데, false로 설정하지 않으면 SSL 에러남.
		$GLOBALS['xmlrpc_internalencoding']='UTF-8'; // 기본값은 ISO-8859-1, 기본값 사용시 한글 깨짐.

		$struct = array(
		'title' => new xmlrpcval($title, "string"),
		'description' => new xmlrpcval($description, "string")
		);

		$f = new xmlrpcmsg("metaWeblog.newPost",
			array(
				new xmlrpcval($blogid, "string"),
				new xmlrpcval($user_id, "string"),
				new xmlrpcval($password, "string"),
				new xmlrpcval($struct , "struct"),
				new xmlrpcval($publish, "boolean")
			)
		);
		$f->request_charset_encoding = 'UTF-8';

		//echo '<pre>'; print_r($f); exit;

		return $response = $client->send($f);
	}
}
