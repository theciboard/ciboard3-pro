<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Pointranking class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 메인 페이지를 담당하는 controller 입니다.
 */
class Pointranking extends CB_Controller
{

	/**
	 * 모델을 로딩합니다
	 */
	protected $models = array('Point');

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
		$this->load->library(array('querystring'));
	}


	/**
	 * 전체 메인 페이지입니다
	 */
	public function index()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_pointranking_index';
		$this->load->event($eventname);

		if ( ! $this->cbconfig->item('use_pointranking')) {
			alert('이 웹사이트는 포인트 랭킹 기능을 사용하지 않습니다');
		}

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		$result = $this->Point_model->point_ranking_all($limit = '100');
		if ($result) {
			$order = 1;
			$ranking = 1;
			$prev = -1;
			foreach ($result as $key => $val) {
				$result[$key]['display_name'] = display_username(
					element('mem_userid', $val),
					element('mem_nickname', $val),
					element('mem_icon', $val)
				);
				if ($prev > element('poi_point', $val)) {
					$ranking = $order;
				}
				$result[$key]['ranking'] = $ranking;
				$prev = element('poi_point', $val);
				$order++;
			}
		}
		$view['view']['data'] = $result;

		$view['view']['canonical'] = site_url('pointranking');

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 레이아웃을 정의합니다
		 */
		$page_title = $this->cbconfig->item('site_meta_title_pointranking');
		$meta_description = $this->cbconfig->item('site_meta_description_pointranking');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_pointranking');
		$meta_author = $this->cbconfig->item('site_meta_author_pointranking');
		$page_name = $this->cbconfig->item('site_page_name_pointranking');

		$layoutconfig = array(
			'path' => 'pointranking',
			'layout' => 'layout',
			'skin' => 'pointranking',
			'layout_dir' => $this->cbconfig->item('layout_pointranking'),
			'mobile_layout_dir' => $this->cbconfig->item('mobile_layout_pointranking'),
			'use_sidebar' => $this->cbconfig->item('sidebar_pointranking'),
			'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_pointranking'),
			'skin_dir' => $this->cbconfig->item('skin_pointranking'),
			'mobile_skin_dir' => $this->cbconfig->item('mobile_skin_pointranking'),
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


	/**
	 * 전체 메인 페이지입니다
	 */
	public function month($year = 0, $month = 0)
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_pointranking_month';
		$this->load->event($eventname);

		if ( ! $this->cbconfig->item('use_pointranking')) {
			alert('이 웹사이트는 포인트 랭킹 기능을 사용하지 않습니다');
		}

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		$year = (int) $year;
		if ($year<1000 OR $year > 2999) {
			$year = cdate('Y');
		}

		$month = (int) $month;
		if ($month < 1 OR $month > 12) {
			$month = (int) cdate('m');
		}

		$result = $this->Point_model->point_ranking_month($year, $month, $limit = 100);
		if ($result) {
			$order = 1;
			$ranking = 1;
			$prev = -1;
			foreach ($result as $key => $val) {
				$result[$key]['display_name'] = display_username(
					element('mem_userid', $val),
					element('mem_nickname', $val),
					element('mem_icon', $val)
				);
				if ($prev > element('poi_point', $val)) {
					$ranking = $order;
				}
				$result[$key]['ranking'] = $ranking;
				$prev = element('poi_point', $val);
				$order++;
			}
		}
		$view['view']['data'] = $result;

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 레이아웃을 정의합니다
		 */
		$page_title = $this->cbconfig->item('site_meta_title_pointranking_month');
		$meta_description = $this->cbconfig->item('site_meta_description_pointranking_month');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_pointranking_month');
		$meta_author = $this->cbconfig->item('site_meta_author_pointranking_month');
		$page_name = $this->cbconfig->item('site_page_name_pointranking_month');

		$layoutconfig = array(
			'path' => 'pointranking',
			'layout' => 'layout',
			'skin' => 'pointranking_month',
			'layout_dir' => $this->cbconfig->item('layout_pointranking'),
			'mobile_layout_dir' => $this->cbconfig->item('mobile_layout_pointranking'),
			'use_sidebar' => $this->cbconfig->item('sidebar_pointranking'),
			'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_pointranking'),
			'skin_dir' => $this->cbconfig->item('skin_pointranking'),
			'mobile_skin_dir' => $this->cbconfig->item('mobile_skin_pointranking'),
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
