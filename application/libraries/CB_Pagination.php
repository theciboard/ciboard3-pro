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
 * Pagination Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Pagination
 * @author		EllisLab Dev Team
 * @link		http://codeigniter.com/user_guide/libraries/pagination.html
 */
class CB_Pagination extends CI_Pagination
{

	public $num_links = 5;
	public $cur_page = 1;
	public $use_page_numbers = true;
	public $first_link = '<span aria-hidden="true">&laquo;</span>';
	public $next_link = '<span aria-hidden="true">&gt;</span>';
	public $prev_link = '<span aria-hidden="true">&lt;</span>';
	public $last_link = '<span aria-hidden="true">&raquo;</span>';
	public $first_tag_open = '<li>';
	public $first_tag_close = '</li>';
	public $last_tag_open = '<li>';
	public $last_tag_close = '</li>';
	public $cur_tag_open = '<li class="active"><a>';
	public $cur_tag_close = '</a></li>';
	public $next_tag_open = '<li>';
	public $next_tag_close = '</li>';
	public $prev_tag_open = '<li>';
	public $prev_tag_close = '</li>';
	public $full_tag_open = '<ul class="pagination">';
	public $full_tag_close = '</ul>';
	public $num_tag_open = '<li>';
	public $num_tag_close = '</li>';
	public $page_query_string = true;
	public $query_string_segment = 'page';

	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();
	}
}
