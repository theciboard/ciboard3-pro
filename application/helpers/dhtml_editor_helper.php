<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Dhtml Editor helper
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

if ( ! function_exists('display_dhtml_editor')) {
	// Dhtml Editor 띄우기
	function display_dhtml_editor($name = '', $content = '', $classname = '', $is_dhtml_editor = true, $editor_type = 'smarteditor')
	{
		$editorclassname = '';
		$style = '';
		if ($editor_type === 'smarteditor' && $is_dhtml_editor) {
			$editor_url = site_url('plugin/editor/smarteditor');
			$editorclassname = 'smarteditor';
			$style = 'style="width:98%;"';
		}
		if ($editor_type === 'ckeditor' && $is_dhtml_editor) {
			$editor_url = site_url('plugin/editor/ckeditor');
			$editorclassname = 'ckeditor';
			$style = 'style="width:98%;"';
		}

		$html = '';

		if ($editor_type === 'smarteditor' && $is_dhtml_editor
			&& ! defined('LOAD_DHTML_EDITOR_JS')) {

			$html .= "\n" . '<script src="' . $editor_url . '/js/service/HuskyEZCreator.js"></script>';
			$html .= "\n" . '<script type="text/javascript">var editor_url = "' . $editor_url . '", oEditors = [], ed_nonce = "'.ft_nonce_create('smarteditor').'";</script>';
			$html .= "\n" . '<script src="' . $editor_url . '/editor_config.js"></script>';
			define('LOAD_DHTML_EDITOR_JS', true);

		}
		if ($editor_type === 'ckeditor' && $is_dhtml_editor
			&& ! defined('LOAD_DHTML_EDITOR_JS')) {

			$html .= "\n" . '<script src="' . $editor_url . '/ckeditor.js"></script>';
			$html .= "\n" . '<script type="text/javascript">var editor_url = "' . $editor_url . '";</script>';
			$html .= "\n" . '<script src="' . $editor_url . '/config.js"></script>';
			define('LOAD_DHTML_EDITOR_JS', true);
		}
		$html .= "\n<textarea id=\"" . $name . "\" name=\"" . $name . "\" class=\"" . $editorclassname . ' ' . $classname . "\" " . $style . ">" . $content . "</textarea>";

		return $html;
	}
}

// This method creates a key / value pair for a url string
if(!function_exists('ft_nonce_create_query_string')){
	function ft_nonce_create_query_string( $action = '', $user = '' ){
		return "_nonce=".ft_nonce_create( $action , $user );
	}
}

if(!function_exists('ft_nonce_get_unique_key')){
	function ft_nonce_get_unique_key(){

		static $cache = null;

		if( $cache !== null ){
			return $cache;
		}

		$CI = & get_instance();

		$cache = sha1($CI->config->item('encryption_key').session_id());

		return $cache;
	}
}

if(!function_exists('ft_get_secret_key')){
	function ft_get_secret_key($secret){
		return md5(ft_nonce_get_unique_key().$secret);
	}
}

if(!function_exists('ft_nonce_get_session_key')){
	function ft_nonce_get_session_key(){
		return substr(md5(ft_nonce_get_unique_key()), 5);
	}
}

// This method creates an nonce. It should be called by one of the previous two functions.
if(!function_exists('ft_nonce_create')){
	function ft_nonce_create( $action = '',$user='', $timeoutSeconds=3600 ){

		$CI = & get_instance();

		$secret = ft_get_secret_key($action.$user);

		$CI->session->set_userdata('token_'.ft_nonce_get_session_key(), $secret);

		$salt = ft_nonce_generate_hash();
		$time = time();
		$maxTime = $time + $timeoutSeconds;
		$nonce = $salt . "|" . $maxTime . "|" . sha1( $salt . $secret . $maxTime );
		return $nonce;

	}
}

// This method validates an nonce
if(!function_exists('ft_nonce_is_valid')){
	function ft_nonce_is_valid( $nonce, $action = '', $user='' ){

		$CI = & get_instance();

		$secret = ft_get_secret_key($action.$user);

		$token = $CI->session->userdata('token_'.ft_nonce_get_session_key());

		if ($secret != $token){
			return false;
		}

		if (is_string($nonce) == false) {
			return false;
		}
		$a = explode('|', $nonce);
		if (count($a) != 3) {
			return false;
		}
		$salt = $a[0];
		$maxTime = intval($a[1]);
		$hash = $a[2];
		$back = sha1( $salt . $secret . $maxTime );
		if ($back != $hash) {
			return false;
		}
		if (time() > $maxTime) {
			return false;
		}
		return true;
	}
}

// This method generates the nonce timestamp
if(!function_exists('ft_nonce_generate_hash')){
	function ft_nonce_generate_hash(){
		$length = 10;
		$chars='1234567890qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM';
		$ll = strlen($chars)-1;
		$o = '';
		while (strlen($o) < $length) {
			$o .= $chars[ rand(0, $ll) ];
		}
		return $o;
	}
}