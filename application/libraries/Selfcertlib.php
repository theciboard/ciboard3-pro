<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Selfcertlib class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 권한이 있는지 없는지 판단하는 class 입니다.
 */
class Selfcertlib extends CI_Controller
{

	private $CI;

	function __construct()
	{
		$this->CI = & get_instance();
	}



	/**
	 * 본인인증 확인 기능을 체크하고 권한이 없을 시에 본인인증 페이지로 이동합니다.
	 */
	public function is_selfcert($access_type = '', $selfcert_type='')
	{
		if ( ! $this->CI->cbconfig->item('use_selfcert')) {
			return true;
		}
		if ( ! $this->CI->cbconfig->item('use_selfcert_ipin') && ! $this->CI->cbconfig->item('use_selfcert_phone')) {
			return true;
		}
		if ($this->CI->member->is_member() === false) {
			return true;
		}
		if ($this->CI->member->is_admin() === 'super') {
			return true;
		}
		if ( ! $selfcert_type) {
			return true;
		}
		if ($selfcert_type == '1') {
			if ( ! $this->CI->member->item('selfcert_type')) {
				return false;
			}
		} else if ($selfcert_type == '2') {
			if ($this->CI->member->item('selfcert_type') && ! is_adult($this->CI->member->item('selfcert_birthday'))) {
				return false;
			} else if ( ! $this->CI->member->item('selfcert_type')) {
				return false;
			}
		}
		return true;
	}

	public function selfcertcheck($access_type = '', $selfcert_type='')
	{
		$accessable = $this->is_selfcert($access_type, $selfcert_type);

		if ($selfcert_type == '1') {
			if ($access_type == 'list') {
				$message = '본인 인증 후에 목록 페이지 접근이 가능합니다';
			} else if ($access_type == 'view') {
				$message = '본인 인증 후에 글열람 페이지 접근이 가능합니다';
			} else if ($access_type == 'write') {
				$message = '본인 인증 후에 글쓰기 페이지 접근이 가능합니다';
			} else if ($access_type == 'comment') {
				$message = '본인 인증 후에 코멘트 작성이 가능합니다';
			}
		} else if ($selfcert_type == '2') {
			if ($this->CI->member->item('selfcert_type') && ! is_adult($this->CI->member->item('selfcert_birthday'))) {
				if ($access_type == 'list') {
					$message = '회원님은 성인 인증을 받지 않으셨으므로 이 페이지 접근이 금지되어 있습니다';
				} else if ($access_type == 'view') {
					$message = '회원님은 성인 인증을 받지 않으셨으므로 이 페이지 접근이 금지되어 있습니다';
				} else if ($access_type == 'write') {
					$message = '회원님은 성인 인증을 받지 않으셨으므로 이 페이지 접근이 금지되어 있습니다';
				} else if ($access_type == 'comment') {
					$message = '회원님은 성인 인증을 받지 않으셨으므로 코멘트 작성이 불가능합니다';
				}
				alert($message, '/');
				exit;
			} else if ( ! $this->CI->member->item('selfcert_type')) {
				if ($access_type == 'list') {
					$message = '성인 인증 후에 목록 페이지 접근이 가능합니다';
				} else if ($access_type == 'view') {
					$message = '성인 인증 후에 글열람 페이지 접근이 가능합니다';
				} else if ($access_type == 'write') {
					$message = '성인 인증 후에 글쓰기 페이지 접근이 가능합니다';
				} else if ($access_type == 'comment') {
					$message = '성인 인증 후에 코멘트 작성이 가능합니다';
				}
			}
		}
		if ($accessable) {
			return true;
		} else {
			$this->CI->session->set_flashdata('message', $message);
			redirect(site_url('selfcert?redirecturl=' . urlencode(current_full_url())));
		}
	}
}
