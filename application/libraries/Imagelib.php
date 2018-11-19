<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Imagelib class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * image 를 관리하는 class 입니다.
 */
class Imagelib extends CI_Controller
{

	private $CI;

	function __construct()
	{
		$this->CI = & get_instance();
	}


	/**
	 * 본문 내용중 외부 이미지주소를 서버로 가져온 후에 내부 주소로 변경합니다
	 */
	public function replace_external_image($content = '')
	{
		if (empty($content)) {
			return;
		}

		$patten = "/<img[^>]*src=[\"']?([^>\"']+)[\"']?[^>]*>/i";

		preg_match_all($patten, $content, $match);

		if (isset($match[1]) && $match[1]) {
			foreach ($match[1] as $link) {
				$url = @parse_url($link);
				if ( ! empty($url['host']) && $url['host'] !== $this->CI->input->server('HTTP_HOST')) {
					$image = $this->save_external_image($link, $url['path']);
					if ($image)	{
						$content = str_replace($link, $image, $content);
					}
				}
			}
		}

		return $content;
	}


	public function save_external_image($url = '', $path = '')
	{
		if (empty($url)) {
			return;
		}

		$ch = curl_init ($url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
		$err = curl_error($ch);
		if (empty($err)) {
			$rawdata = curl_exec($ch);
		}
		curl_close ($ch);

		if ($rawdata) {
			$ext = get_extension($path);

			$upload_path = config_item('uploads_dir') . '/editor/';
			if (is_dir($upload_path) === false) {
				mkdir($upload_path, 0707);
				$file = $upload_path . 'index.php';
				$f = @fopen($file, 'w');
				@fwrite($f, '');
				@fclose($f);
				@chmod($file, 0644);
			}
			$upload_path .= cdate('Y') . '/';
			if (is_dir($upload_path) === false) {
				mkdir($upload_path, 0707);
				$file = $upload_path . 'index.php';
				$f = @fopen($file, 'w');
				@fwrite($f, '');
				@fclose($f);
				@chmod($file, 0644);
			}
			$upload_path .= cdate('m') . '/';
			if (is_dir($upload_path) === false) {
				mkdir($upload_path, 0707);
				$file = $upload_path . 'index.php';
				$f = @fopen($file, 'w');
				@fwrite($f, '');
				@fclose($f);
				@chmod($file, 0644);
			}

			list($usec, $sec) = explode(' ', microtime());
			$file_name = md5(uniqid(mt_rand())) . '_' . str_replace('.', '', $sec . $usec) . '.' . $ext;
			$save_dir = $upload_path. $file_name;
			$save_url = site_url(config_item('uploads_dir') . '/editor/' . cdate('Y') . '/' . cdate('m') . '/' . $file_name);

			$fp = fopen($save_dir, 'w');
			fwrite($fp, $rawdata);
			fclose($fp);

			if (file_exists($save_dir)) {
				return $save_url;
			}
		}

		return;
	}
}
