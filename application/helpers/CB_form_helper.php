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

if ( ! function_exists('set_value'))
{
	/**
	 * Form Value
	 *
	 * Grabs a value from the POST array for the specified field so you can
	 * re-populate an input field or textarea. If Form Validation
	 * is active it retrieves the info from the validation class
	 *
	 * @param	string	$field		Field name
	 * @param	string	$default	Default value
	 * @param	bool	$html_escape	Whether to escape HTML special characters or not
	 * @return	string
	 */
	function set_value($field, $default = '', $html_escape = TRUE)
	{

		if (isset($_POST[$field])) {
			return ($html_escape) ? html_escape($_POST[$field]) : $_POST[$field];
		} else {
			return ($html_escape) ? html_escape($default) : $default;
		}
	}
}

if ( ! function_exists('set_select'))
{
	/**
	 * Set Select
	 *
	 * Let's you set the selected value of a <select> menu via data in the POST array.
	 * If Form Validation is active it retrieves the info from the validation class
	 *
	 * @param	string
	 * @param	string
	 * @param	bool
	 * @return	string
	 */
	function set_select($field, $value = '', $default = FALSE)
	{
		$CI =& get_instance();

		if (isset($CI->form_validation) && is_object($CI->form_validation) && $CI->form_validation->has_rule($field)) {
			return $CI->form_validation->set_select($field, $value, $default);
		} else if (($input = $CI->input->post($field, FALSE)) === NULL) {
			return ($default === TRUE) ? ' selected="selected"' : '';
		}

		$value = (string) $value;
		if (is_array($input)) {
			// Note: in_array('', array(0)) returns TRUE, do not use it
			foreach ($input as &$v) {
				if ($value === $v) {
					return ' selected="selected"';
				}
			}

			return '';
		}

		return ($input === $value) ? ' selected="selected"' : '';
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('set_checkbox'))
{
	/**
	 * Set Checkbox
	 *
	 * Let's you set the selected value of a checkbox via the value in the POST array.
	 * If Form Validation is active it retrieves the info from the validation class
	 *
	 * @param	string
	 * @param	string
	 * @param	bool
	 * @return	string
	 */
	function set_checkbox($field, $value = '', $default = FALSE)
	{
		$CI =& get_instance();

		if (isset($CI->form_validation) && is_object($CI->form_validation) && $CI->form_validation->has_rule($field)) {
			return $CI->form_validation->set_checkbox($field, $value, $default);
		} else if (($input = $CI->input->post($field, FALSE)) === NULL) {
			return ($default === TRUE) ? ' checked="checked"' : '';
		}

		$value = (string) $value;
		if (is_array($input)) {
			// Note: in_array('', array(0)) returns TRUE, do not use it
			foreach ($input as &$v) {
				if ($value === $v) {
					return ' checked="checked"';
				}
			}

			return '';
		}

		return ($input === $value) ? ' checked="checked"' : '';
	}
}

// ------------------------------------------------------------------------

if ( ! function_exists('set_radio'))
{
	/**
	 * Set Radio
	 *
	 * Let's you set the selected value of a radio field via info in the POST array.
	 * If Form Validation is active it retrieves the info from the validation class
	 *
	 * @param	string	$field
	 * @param	string	$value
	 * @param	bool	$default
	 * @return	string
	 */
	function set_radio($field, $value = '', $default = FALSE)
	{
		$CI =& get_instance();

		if (isset($CI->form_validation) && is_object($CI->form_validation) && $CI->form_validation->has_rule($field)) {
			return $CI->form_validation->set_radio($field, $value, $default);
		} else if (($input = $CI->input->post($field, FALSE)) === NULL) {
			return ($default === TRUE) ? ' checked="checked"' : '';
		}

		return ($input === (string) $value) ? ' checked="checked"' : '';
	}
}

if ( ! function_exists('check_use_captcha'))
{
	function check_use_captcha($member, $board=array(), $mode=''){

		$use_captcha = false;

		if( $member->is_admin() !== false ){	//관리자면 캡챠 사용 안함
			return false;
		}

		if( $member->is_member() === false ){	//비회원은 캡챠 사용
			$use_captcha = true;
		}

		if( element('board_use_captcha', $board) && $mode !== 'cu' ){ //글쓰기일때만
			$use_captcha = true;
		}

		return $use_captcha;
	}
}