<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Scheduler hook class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

class _Scheduler
{

	function init()
	{
		$CI =& get_instance();

		if (config_item('enable_scheduler') === false) {
			return;
		}
		if ($CI->input->is_ajax_request() === true) {
			return;
		}
		if ($CI->input->method() !== 'get') {
			return;
		}
		if ($CI->uri->segment(1) === 'install') {
			return;
		}

		$cachename = 'check_scheduler_executed';
		$cachetime = (int) config_item('check_scheduler_second');

		if ( ! $CI->cache->get($cachename)) {

			$scheduler_data = $CI->cbconfig->item('scheduler');
			$scheduler_array = json_decode($scheduler_data, true);
			$interval_data = $CI->cbconfig->item('scheduler_interval');
			$interval_array = json_decode($interval_data, true);

			$libraryname = '';
			$find_scheduler = false;
			if ($scheduler_array && is_array($scheduler_array)) {
				foreach ($scheduler_array as $value) {
					if ( ! element('lasttime', $value)) {
						if (file_exists(APPPATH . 'libraries/Scheduler/' . element('library_name', $value) . '.php')) {
							$find_scheduler = true;
							$libraryname = element('library_name', $value);
							break;
						}
					} else {
						$interval = element('interval', element(element('interval_field_name', $value), $interval_array));
						if (element('lasttime', $value) + $interval < ctimestamp()) {
							if (file_exists(APPPATH . 'libraries/Scheduler/' . element('library_name', $value) . '.php')) {
								echo element('lasttime', $value) . '//'. $interval . '//'. ctimestamp();
								$find_scheduler = true;
								$libraryname = element('library_name', $value);
								break;
							}
						}
					}
				}
			}

			if ($find_scheduler && $libraryname) {

				$CI->load->library('Scheduler/' . $libraryname);
				$s = new $libraryname();
				if (method_exists($s, 'scheduler')) {

					$s->scheduler();

					$scheduler_array[$libraryname]['lasttime'] = ctimestamp();
					$savedata = array();
					$savedata['scheduler'] = json_encode($scheduler_array);
					$CI->Config_model->save($savedata);

				}
			}

			$CI->cache->save($cachename, '1', $cachetime);
		}
	}
}
