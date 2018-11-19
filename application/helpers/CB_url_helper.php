<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Url libraries helper
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */


/**
 * query string 을 포함한 현재페이지 주소 전체를 return 합니다
 */
if ( ! function_exists('current_full_url')) {
	function current_full_url()
	{
		$CI =& get_instance();

		$url = $CI->config->site_url($CI->uri->uri_string());
		$return = ($CI->input->server('QUERY_STRING'))
			? $url . '?' . $CI->input->server('QUERY_STRING') : $url;
		return $return;
	}
}


/**
 * 페이지 이동시 이 함수를 이용하면, gotourl 페이지를 거쳐가므로 referer 를 숨길 수 있습니다
 */
if ( ! function_exists('goto_url')) {
	function goto_url($url = '')
	{
		if (empty($url)) {
			return false;
		}
		$result = site_url('gotourl/?url=' . urlencode($url));
		return $result;
	}
}


/**
 * Admin 페이지 주소를 return 합니다
 */
if ( ! function_exists('admin_url')) {
	function admin_url($url = '')
	{
		$url = trim($url, '/');
		return site_url(config_item('uri_segment_admin') . '/' . $url);
	}
}


/**
 * 게시판 목록 주소를 return 합니다
 */
if ( ! function_exists('board_url')) {
	function board_url($key = '')
	{
		$key = trim($key, '/');
		return site_url(config_item('uri_segment_board') . '/' . $key);
	}
}


/**
 * 게시물 열람 페이지 주소를 return 합니다
 */
if ( ! function_exists('post_url')) {
	function post_url($key = '', $post_id = '')
	{
		$key = trim($key, '/');
		$post_id = trim($post_id, '/');

		$post_url = '';
		if (strtoupper(config_item('uri_segment_post_type')) === 'B') {
			$post_url = site_url($key . '/' . config_item('uri_segment_post') . '/' . $post_id);
		} elseif (strtoupper(config_item('uri_segment_post_type')) === 'C') {
			$post_url = site_url(config_item('uri_segment_post') . '/' . $key . '/' . $post_id);
		} else {
			$post_url = site_url(config_item('uri_segment_post') . '/' . $post_id);
		}
		return $post_url;
	}
}


/**
 * 게시물 작성 페이지 주소를 return 합니다
 */
if ( ! function_exists('write_url')) {
	function write_url($key = '')
	{
		$key = trim($key, '/');
		return site_url(config_item('uri_segment_write') . '/' . $key);
	}
}


/**
 * 게시물 답변 페이지 주소를 return 합니다
 */
if ( ! function_exists('reply_url')) {
	function reply_url($key = '')
	{
		$key = trim($key, '/');
		return site_url(config_item('uri_segment_reply') . '/' . $key);
	}
}


/**
 * 게시물 수정 페이지 주소를 return 합니다
 */
if ( ! function_exists('modify_url')) {
	function modify_url($key = '')
	{
		$key = trim($key, '/');
		return site_url(config_item('uri_segment_modify') . '/' . $key);
	}
}


/**
 * 게시물 그룹 페이지 주소를 return 합니다
 */
if ( ! function_exists('group_url')) {
	function group_url($key = '')
	{
		$key = trim($key, '/');
		return site_url(config_item('uri_segment_group') . '/' . $key);
	}
}


/**
 * RSS 페이지 주소를 return 합니다
 */
if ( ! function_exists('rss_url')) {
	function rss_url($key = '')
	{
		$key = trim($key, '/');
		return site_url(config_item('uri_segment_rss') . '/' . $key);
	}
}


/**
 * FAQ 페이지 주소를 return 합니다
 */
if ( ! function_exists('faq_url')) {
	function faq_url($key = '')
	{
		$key = trim($key, '/');
		return site_url(config_item('uri_segment_faq') . '/' . $key);
	}
}


/**
 * 일반문서 페이지 주소를 return 합니다
 */
if ( ! function_exists('document_url')) {
	function document_url($key = '')
	{
		$key = trim($key, '/');
		return site_url(config_item('uri_segment_document') . '/' . $key);
	}
}
