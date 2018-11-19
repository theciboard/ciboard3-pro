<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2015, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (http://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2015, British Columbia Institute of Technology (http://bcit.ca/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	http://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Form Validation Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Validation
 * @author		EllisLab Dev Team
 * @link		http://codeigniter.com/user_guide/libraries/form_validation.html
 */
/*
CI_Form_validation 에서 is_unique 변형
db 업데이트시 자기 자신의 값과는 비교하지 않음
예를 들어 email 이 is_unique 임을 체크할 때
본인의 이메일을 수정 가능하다고 할 때, 기존 자기 이메일의 값과는 비교하지 않음
*/

class CB_Form_validation extends CI_Form_validation
{

	protected $CI;
	protected $_field_data = array();
	protected $_config_rules = array();
	protected $_error_array = array();
	protected $_error_messages = array();
	protected $_error_prefix = '<p>';
	protected $_error_suffix = '</p>';
	protected $error_string = '';
	protected $_safe_form_data = false;

	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();
	}

	// --------------------------------------------------------------------

	/**
	 * Match one field to another
	 *
	 * @access public
	 * @param string
	 * @param field
	 * @return bool
	 */
	public function is_unique($str, $field)
	{
		if (substr_count($field, '.') === 3) {
			list($table, $field, $id_field, $id_val) = explode('.', $field);
			$query = $this->CI->db->limit(1)->where($field, $str)->where($id_field . ' != ', $id_val)->get($table);
		} else {
			list($table, $field) = explode('.', $field);
			$query = $this->CI->db->limit(1)->get_where($table, array($field => $str));
		}
		return $query->num_rows() === 0;
	}
	// --------------------------------------------------------------------

	/**
	 * Alpha-numeric with underscores and dashes
	 *
	 * @access public
	 * @param string
	 * @return bool
	 */
	public function alphanumunder($str)
	{
		return ( ! preg_match("/^([-a-z0-9_])+$/i", $str)) ? false : true;
	}

	public function valid_url($str)
	{
		$pattern = "|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i";
		if ( ! preg_match($pattern, $str)) {
			return false;
		}

		return true;
	}

	public function valid_phone($value)
	{
		$value = trim($value);
		if ($value === '') {
			return true;
		} else {
			if (preg_match('/^\(?[0-9]{2,3}\)?[-. ]?[0-9]{3,4}[-. ]?[0-9]{4}$/', $value)) {
				return preg_replace('/^\(?([0-9]{2,3})\)?[-. ]?([0-9]{3,4})[-. ]?([0-9]{4})$/', '$1-$2-$3', $value);
			} else {
				return false;
			}
		}
	}
}
