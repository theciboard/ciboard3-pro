<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Password helper
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

if ( ! function_exists('password_hash')) {
	function password_hash($password = '', $key = '1')
	{
		if (empty($password)) {
			return false;
		}

		include_once(APPPATH . 'libraries/PasswordHash.php');

		$hasher = new PasswordHash();
		$hash = $hasher->HashPassword($password);

		return $hash;
	}
}


if ( ! function_exists('password_verify')) {
	function password_verify($password = '', $hash = '')
	{
		if (empty($password)) {
			return false;
		}
		if (empty($hash)) {
			return false;
		}

		include_once(APPPATH . 'libraries/PasswordHash.php');

		$hasher = new PasswordHash();

		return $hasher->CheckPassword($password, $hash);
	}
}
