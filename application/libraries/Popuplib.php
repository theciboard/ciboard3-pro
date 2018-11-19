<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Popuplib class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 팝업을 관리하는 class 입니다.
 */
class Popuplib extends CI_Controller
{

	private $CI;
	private $today_popup_data;

	function __construct()
	{
		$this->CI = & get_instance();
	}


	/**
	 * 오늘 보여줘야할 팝업이 있는지를 체크합니다
	 */
	public function get_today_popup_data()
	{
		if (empty($this->today_popup_data)) {
			$this->CI->load->model( array('Popup_model'));
			$this->today_popup_data = $this->CI->Popup_model->get_today_list();
		}

		return $this->today_popup_data;
	}


	/**
	 * 팝업을 보여줍니다
	 */
	public function display_popup()
	{
		/**
		 * 레이아웃을 정의합니다
		 */
		$pagetitle = '팝업알림';
		$skin_file = 'popup';

		$skindir = ($this->CI->cbconfig->get_device_view_type() === 'mobile')
			? $this->CI->cbconfig->item('mobile_skin_popup')
			: $this->CI->cbconfig->item('skin_popup');
		if (empty($skindir)) {
			$skindir = ($this->CI->cbconfig->get_device_view_type() === 'mobile')
				? $this->CI->cbconfig->item('mobile_skin_default')
				: $this->CI->cbconfig->item('skin_default');
		}

		$view_skin_file = 'popup/' . $skindir . '/' . $skin_file;

		$view = array();
		$view['view'] = array();

		$view['view'] = $this->get_today_popup_data();
		$list = array();
		if (element('list', element('view', $view))) {
			foreach (element('list', element('view', $view)) as $key => $value) {
				if (get_cookie('popup_layer_' . element('pop_id', $value))) {
					continue;
				}
				if ($this->CI->cbconfig->get_device_view_type() === 'mobile'
					&& element('pop_device', $value) === 'pc') {
					continue;
				}
				if ($this->CI->cbconfig->get_device_view_type() !== 'mobile'
					&& element('pop_device', $value) === 'mobile') {
					continue;
				}
				if ( ! element('pop_page', $value)
					&& $this->CI->uri->segment(1)) {
					continue;
				}

				$content = element('pop_content', $value);
				$thumb_width = ($this->CI->cbconfig->get_device_view_type() === 'mobile')
					? $this->CI->cbconfig->item('popup_mobile_thumb_width')
					: $this->CI->cbconfig->item('popup_thumb_width');
				$autolink = ($this->CI->cbconfig->get_device_view_type() === 'mobile')
					? $this->CI->cbconfig->item('use_popup_mobile_auto_url')
					: $this->CI->cbconfig->item('use_popup_auto_url');
				$popup = ($this->CI->cbconfig->get_device_view_type() === 'mobile')
					? $this->CI->cbconfig->item('popup_mobile_content_target_blank')
					: $this->CI->cbconfig->item('popup_content_target_blank');

				$value['content'] = display_html_content(
					$content,
					element('pop_content_html_type', $value),
					$thumb_width,
					$autolink,
					$popup,
					$writer_is_admin = true
				);

				$list[] = $value;
			}
		}
		$view['view']['popup'] = $list;
		if ($list) {
			return $this->CI->load->view($view_skin_file, $view, true);
		}
	}
}
