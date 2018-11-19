<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Currentvisitor class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 현재 접속자를 보여주는 controller 입니다.
 */
class Currentvisitor extends CB_Controller
{

	/**
	 * 모델을 로딩합니다
	 */
	protected $models = array('Currentvisitor');

	/**
	 * 헬퍼를 로딩합니다
	 */
	protected $helpers = array('form', 'array');

	function __construct()
	{
		parent::__construct();

		/**
		 * 라이브러리를 로딩합니다
		 */
		$this->load->library(array('pagination', 'querystring'));
	}


	/**
	 * 현재접속자 페이지입니다
	 */
	public function index()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_currentvisitor_index';
		$this->load->event($eventname);

		if ( ! $this->cbconfig->item('open_currentvisitor') && $this->member->is_admin() === false) {
			alert('이 웹사이트는 현재접속자 기능을 사용하지 않습니다');
		}

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		/**
		 * 페이지에 숫자가 아닌 문자가 입력되거나 1보다 작은 숫자가 입력되면 에러 페이지를 보여줍니다.
		 */
		$param =& $this->querystring;
		$page = (((int) $this->input->get('page')) > 0) ? ((int) $this->input->get('page')) : 1;

		$per_page = 100;
		$offset = ($page - 1) * $per_page;

		$minute = (int) $this->cbconfig->item('currentvisitor_minute');
		if ($minute < 1) {
			$minute = 10;
		}
		$curdatetime = cdate('Y-m-d H:i:s', ctimestamp() - $minute * 60);

		$cachename = 'delete_old_currentvisitor_cache';
		$cachetime = 60;
		if ( ! $result = $this->cache->get($cachename)) {
			$deletewhere = array(
				'cur_datetime < ' => $curdatetime,
			);
			$this->Currentvisitor_model->delete_where($deletewhere);
			$this->cache->save($cachename, cdate('Y-m-d H:i:s'), $cachetime);
		}

		$result = $this->Currentvisitor_model
			->get_current_list($curdatetime, $per_page, $offset);
		$list_num = ($page - 1) * $per_page + 1;
		$visitor = array();
		if (element('list', $result)) {
			foreach (element('list', $result) as $key => $value) {
				$data = $value;
				if (element('mem_userid', $value)) {
					$data['name_or_ip'] = display_username(
						element('mem_userid', $value),
						element('mem_nickname', $value),
						element('mem_icon', $value)
					);
				} else {
					$ip_display_style = ($this->member->is_admin() === 'super')
						? '1111' : $this->cbconfig->item('ip_display_style');
					$data['name_or_ip'] = display_ipaddress(element('cur_ip', $value), $ip_display_style);
				}
				$data['num'] = $list_num++;
				$visitor[] = $data;
			}
		}
		$view['view']['list'] = $visitor;
		$view['view']['total_rows'] = $result['total_rows'];

		/**
		 * 페이지네이션을 생성합니다
		 */
		$config['base_url'] = site_url('currentvisitor') . '?' . $param->replace('page');
		$config['total_rows'] = $result['total_rows'];
		$config['per_page'] = $per_page;
		$this->pagination->initialize($config);
		$view['view']['paging'] = $this->pagination->create_links();
		$view['view']['page'] = $page;

		$view['view']['canonical'] = site_url('currentvisitor');

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 레이아웃을 정의합니다
		 */
		$page_title = $this->cbconfig->item('site_meta_title_currentvisitor');
		$meta_description = $this->cbconfig->item('site_meta_description_currentvisitor');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_currentvisitor');
		$meta_author = $this->cbconfig->item('site_meta_author_currentvisitor');
		$page_name = $this->cbconfig->item('site_page_name_currentvisitor');

		$searchconfig = array(
			'{현재접속자수}',
		);
		$replaceconfig = array(
			$result['total_rows'],
		);

		$page_title = str_replace($searchconfig, $replaceconfig, $page_title);
		$meta_description = str_replace($searchconfig, $replaceconfig, $meta_description);
		$meta_keywords = str_replace($searchconfig, $replaceconfig, $meta_keywords);
		$meta_author = str_replace($searchconfig, $replaceconfig, $meta_author);
		$page_name = str_replace($searchconfig, $replaceconfig, $page_name);

		$layoutconfig = array(
			'path' => 'currentvisitor',
			'layout' => 'layout',
			'skin' => 'currentvisitor',
			'layout_dir' => $this->cbconfig->item('layout_currentvisitor'),
			'mobile_layout_dir' => $this->cbconfig->item('mobile_layout_currentvisitor'),
			'use_sidebar' => $this->cbconfig->item('sidebar_currentvisitor'),
			'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_currentvisitor'),
			'skin_dir' => $this->cbconfig->item('skin_currentvisitor'),
			'mobile_skin_dir' => $this->cbconfig->item('mobile_skin_currentvisitor'),
			'page_title' => $page_title,
			'meta_description' => $meta_description,
			'meta_keywords' => $meta_keywords,
			'meta_author' => $meta_author,
			'page_name' => $page_name,
		);
		$view['layout'] = $this->managelayout->front($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}
}
