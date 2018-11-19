<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Captcha class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 비회원이 접근시 보여주는 captcha 에 관한 controller 입니다.
 */
class Captcha extends CB_Controller
{

	function __construct()
	{
		parent::__construct();

		/**
		 * 라이브러리를 로딩합니다
		 */
		$this->load->helper(array('captcha', 'string'));
	}


	/**
	 * 캡차 세션 생성후 보여주는 함수입니다.
	 */
	function show($width = '', $height = '')
	{
		$this->output->set_content_type('application/json');

		if ( ! $width) {
			$width = 150;
		}
		if ( ! $height) {
			$height = 40;
		}

		$img_path = config_item('uploads_dir') . '/captcha/';
		if ( ! is_dir($img_path)) {
			@mkdir($img_path, 0755);
			@chmod($img_path, 0755);
			$file = $img_path . 'index.php';
			$f = @fopen($file, 'w');
			@fwrite($f, '');
			@fclose($f);
			@chmod($file, 0644);
		}

		$data = array(
			'img_path' => $img_path,
			'img_url' => site_url(config_item('uploads_dir') . '/captcha') . '/',
			'img_width' => $width,
			'img_height' => $height,
			'font_size' => 15,
			'font_path' => FCPATH . 'assets/fonts/BreeSerif-Regular.ttf',
			'pool' => '23456789abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ',
			'word_length' => '8',
			'img_id' => 'captcha',
		);

		$cap = create_captcha($data);

		$save = array(
			'captcha_time' => $cap['time'],
			'ip_address' => $this->input->ip_address(),
			'word' => $cap['word'],
		);
		$this->session->set_userdata('captcha', $save);

		exit(json_encode($cap));
	}


	/**
	 * 구글 캡차 생성후 보여주는 함수입니다.
	 */
	function recaptcha()
	{
		$html = '<div id="google-recaptcha">';
		$html .= '<script src="https://www.google.com/recaptcha/api.js"></script>';

		if ( (int) $this->cbconfig->item('use_recaptcha') === 2 ){

			$html .= '<div id="recaptcha" class="g-recaptcha" data-sitekey="' . $this->cbconfig->item('recaptcha_sitekey') . '" data-callback="recaptcha_validate" data-badge="inline" data-size="invisible"></div>';
			$html .= '<script>jQuery("#recaptcha").hide().parent("div").hide();</script>';

		} else {

			$html .= '<div class="g-recaptcha" data-sitekey="' . $this->cbconfig->item('recaptcha_sitekey') . '"></div>';

		}

		$html .= '</div>';

		echo $html;
	}
}
