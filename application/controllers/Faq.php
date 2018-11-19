<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Faq class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * FAQ 페이지를 보여주는 controller 입니다.
 */
class Faq extends CB_Controller
{

	/**
	 * 모델을 로딩합니다
	 */
	protected $models = array('Faq', 'Faq_group');

	/**
	 * 헬퍼를 로딩합니다
	 */
	protected $helpers = array('form', 'array', 'url');

	function __construct()
	{
		parent::__construct();

		/**
		 * 라이브러리를 로딩합니다
		 */
		$this->load->library(array('pagination', 'querystring'));
	}


	/**
	 * FAQ 페이지입니다
	 */
	public function index($fgr_key = '')
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_faq_index';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		if (empty($fgr_key)) {
			show_404();
		}

		$where = array(
			'fgr_key' => $fgr_key,
		);
		$faqgroup = $this->Faq_group_model->get_one('', '', $where);

		if ( ! element('fgr_id', $faqgroup)) {
			show_404();
		}

		/**
		 * 페이지에 숫자가 아닌 문자가 입력되거나 1보다 작은 숫자가 입력되면 에러 페이지를 보여줍니다.
		 */
		$param =& $this->querystring;
		$page = (((int) $this->input->get('page')) > 0) ? ((int) $this->input->get('page')) : 1;
		$findex = 'faq_order';
		$forder = 'asc';
		$sfield = array('faq_title', 'faq_content', 'faq_mobile_content');
		$skeyword = $this->input->get('skeyword', null, '');

		$per_page = $this->cbconfig->item('list_count') ? (int) $this->cbconfig->item('list_count') : 20;
		$offset = ($page - 1) * $per_page;

		/**
		 * 게시판 목록에 필요한 정보를 가져옵니다.
		 */
		$this->Faq_model->allow_search_field = array('faq_title', 'faq_content', 'faq_mobile_content'); // 검색이 가능한 필드
		$this->Faq_model->search_field_equal = array(); // 검색중 like 가 아닌 = 검색을 하는 필드
		$this->Faq_model->allow_order_field = array('faq_order'); // 정렬이 가능한 필드
		$where = array(
			'fgr_id' => element('fgr_id', $faqgroup),
		);
		$result = $this->Faq_model
			->get_list($per_page, $offset, $where, '', $findex, $forder, $sfield, $skeyword);
		if (element('list', $result)) {
			foreach (element('list', $result) as $key => $val) {
				$content = ($this->cbconfig->get_device_view_type() === 'mobile')
					? (element('faq_mobile_content', $val) ? element('faq_mobile_content', $val)
					: element('faq_content', $val)) : element('faq_content', $val);

				$thumb_width = ($this->cbconfig->get_device_view_type() === 'mobile')
					? $this->cbconfig->item('faq_mobile_thumb_width')
					: $this->cbconfig->item('faq_thumb_width');

				$autolink = ($this->cbconfig->get_device_view_type() === 'mobile')
					? $this->cbconfig->item('use_faq_mobile_auto_url')
					: $this->cbconfig->item('use_faq_auto_url');

				$popup = ($this->cbconfig->get_device_view_type() === 'mobile')
					? $this->cbconfig->item('faq_mobile_content_target_blank')
					: $this->cbconfig->item('faq_content_target_blank');

				$result['list'][$key]['title'] = display_html_content(
					element('faq_title', $val),
					element('faq_content_html_type', $val),
					$thumb_width,
					$autolink,
					$popup,
					$writer_is_admin = true
				);

				$result['list'][$key]['content'] = display_html_content(
					$content,
					element('faq_content_html_type', $val),
					$thumb_width,
					$autolink,
					$popup,
					$writer_is_admin = true
				);
			}
		}

		$list_num = $result['total_rows'] - ($page - 1) * $per_page;
		$view['view']['data'] = $result;
		$view['view']['faqgroup'] = $faqgroup;

		/**
		 * 페이지네이션을 생성합니다
		 */
		$config['base_url'] = faq_url($fgr_key) . '?' . $param->replace('page');
		$config['total_rows'] = $result['total_rows'];
		$config['per_page'] = $per_page;
		$this->pagination->initialize($config);
		$view['view']['paging'] = $this->pagination->create_links();
		$view['view']['page'] = $page;

		$view['view']['canonical'] = faq_url($fgr_key);

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 레이아웃을 정의합니다
		 */
		$page_title = $this->cbconfig->item('site_meta_title_faq');
		$meta_description = $this->cbconfig->item('site_meta_description_faq');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_faq');
		$meta_author = $this->cbconfig->item('site_meta_author_faq');
		$page_name = $this->cbconfig->item('site_page_name_faq');

		$searchconfig = array(
			'{FAQ제목}',
			'{FAQ아이디}',
		);
		$replaceconfig = array(
			element('fgr_title', $faqgroup),
			$fgr_key,
		);

		$page_title = str_replace($searchconfig, $replaceconfig, $page_title);
		$meta_description = str_replace($searchconfig, $replaceconfig, $meta_description);
		$meta_keywords = str_replace($searchconfig, $replaceconfig, $meta_keywords);
		$meta_author = str_replace($searchconfig, $replaceconfig, $meta_author);
		$page_name = str_replace($searchconfig, $replaceconfig, $page_name);

		$layout_dir = element('fgr_layout', $faqgroup) ?
			element('fgr_layout', $faqgroup) : $this->cbconfig->item('layout_faq');
		$mobile_layout_dir = element('fgr_mobile_layout', $faqgroup)
			? element('fgr_mobile_layout', $faqgroup)
			: $this->cbconfig->item('mobile_layout_faq');
		$use_sidebar = element('fgr_sidebar', $faqgroup)
			? element('fgr_sidebar', $faqgroup)
			: $this->cbconfig->item('sidebar_faq');
		$use_mobile_sidebar = element('fgr_mobile_sidebar', $faqgroup)
			? element('fgr_mobile_sidebar', $faqgroup)
			: $this->cbconfig->item('mobile_sidebar_faq');
		$skin_dir = element('fgr_skin', $faqgroup)
			? element('fgr_skin', $faqgroup) : $this->cbconfig->item('skin_faq');
		$mobile_skin_dir = element('fgr_mobile_skin', $faqgroup)
			? element('fgr_mobile_skin', $faqgroup) : $this->cbconfig->item('mobile_skin_faq');
		$layoutconfig = array(
			'path' => 'faq',
			'layout' => 'layout',
			'skin' => 'faq',
			'layout_dir' => $layout_dir,
			'mobile_layout_dir' => $mobile_layout_dir,
			'use_sidebar' => $use_sidebar,
			'use_mobile_sidebar' => $use_mobile_sidebar,
			'skin_dir' => $skin_dir,
			'mobile_skin_dir' => $mobile_skin_dir,
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
