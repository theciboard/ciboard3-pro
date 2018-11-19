<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// 검색 파라미터
class Querystring
{

	private $CI;

	public function __construct()
	{
		$this->CI =& get_instance();
	}


	// 전체 주소
	public function output()
	{
		return $this->CI->input->server('QUERY_STRING', null, '');
	}


	// 쿼리스트링 수정
	public function replace($key = '', $val = '', $query_string = '')
	{
		if ( ! $key) {
			return false;
		}

		$query_string = $query_string ? $query_string : $this->CI->input->server('QUERY_STRING', null, '');
		parse_str($query_string, $qr);

		// remove from query string
		if ($key) {
			if ($val) {
				$qr[$key] = $val;
			} else {
				unset($qr[$key]);
			}
		}
		// return result
		$return = '';
		if (count($qr) > 0) {
			$return = http_build_query($qr);
		}

		return $return;
	}


	// 필드 정렬
	public function sort($findex, $forder = 'desc')
	{
		if ($this->CI->input->get('findex') === $findex) {
			$param_qstr = $this->replace('forder', (strtolower($this->CI->input->get('forder')) === 'asc') ? 'desc' : 'asc');
		} else {
			$param_qstr = $this->replace('forder', '', $this->replace('findex', '')) . '&amp;findex=' . $findex . '&amp;forder=' . $forder;
		}

		return '?' . $param_qstr;
	}
}
