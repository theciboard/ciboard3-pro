<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Tags class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 게시물 태그 목록을 열람시 필요한 controller 입니다.
 */
class Tags extends CB_Controller
{

	/**
	 * 모델을 로딩합니다
	 */
	protected $models = array('Post_file', 'Post_tag');

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
	 * 태그 페이지 함수입니다
	 */
	public function index()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_tags_index';
		$this->load->event($eventname);

		$tag = $this->input->get('tag', null, '');
		if (empty($tag)) {
			show_404();
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

		$per_page = 15;
		$offset = ($page - 1) * $per_page;

		$result = $this->Post_tag_model->get_tag_list($per_page, $offset, $tag);

		$list_num = $result['total_rows'] - ($page - 1) * $per_page;
		if (element('list', $result)) {
			foreach (element('list', $result) as $key => $val) {
				$images = '';
				if (element('post_image', $val)) {
					$imagewhere = array(
						'post_id' => element('post_id', $val),
						'pfi_is_image' => 1,
					);
					$images = $this->Post_file_model
						->get_one('', '', $imagewhere, '', '', 'pfi_id', 'ASC');
				}
				$result['list'][$key]['images'] = $images;
				$result['list'][$key]['post_url'] = post_url(element('brd_key', $val), element('post_id', $val));
				$dbmember = $this->Member_model->get_by_memid(element('mem_id', $val), 'mem_icon');
				$result['list'][$key]['display_name'] = display_username(
					element('post_userid', $val),
					element('post_nickname', $val),
					element('mem_icon', $dbmember),
					'Y'
				);
				$result['list'][$key]['display_datetime'] = display_datetime(
					element('post_datetime', $val),
					'user',
					'Y-m-d H:i'
				);
				$result['list'][$key]['content'] = cut_str(strip_tags(element('post_content', $val)),200);
				$result['list'][$key]['is_mobile'] = (element('post_device', $val) === 'mobile') ? true : false;
			}
		}

		$view['view']['data'] = $result;

		/**
		 * 페이지네이션을 생성합니다
		 */
		$config['base_url'] = site_url('tags/') . '?' . $param->replace('page');
		$config['total_rows'] = $result['total_rows'];
		$config['per_page'] = $per_page;
		if ($this->cbconfig->get_device_view_type() === 'mobile') {
			$config['num_links'] = 3;
		} else {
			$config['num_links'] = 5;
		}
		$this->pagination->initialize($config);
		$view['view']['paging'] = $this->pagination->create_links();
		$view['view']['page'] = $page;

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 레이아웃을 정의합니다
		 */
		$page_title = $this->cbconfig->item('site_meta_title_tag');
		$meta_description = $this->cbconfig->item('site_meta_description_tag');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_tag');
		$meta_author = $this->cbconfig->item('site_meta_author_tag');
		$page_name = $this->cbconfig->item('site_page_name_tag');

		$searchconfig = array(
			'{태그명}',
		);
		$replaceconfig = array(
			$tag,
		);

		$page_title = str_replace($searchconfig, $replaceconfig, $page_title);
		$meta_description = str_replace($searchconfig, $replaceconfig, $meta_description);
		$meta_keywords = str_replace($searchconfig, $replaceconfig, $meta_keywords);
		$meta_author = str_replace($searchconfig, $replaceconfig, $meta_author);
		$page_name = str_replace($searchconfig, $replaceconfig, $page_name);

		$layoutconfig = array(
			'path' => 'tag',
			'layout' => 'layout',
			'skin' => 'tag',
			'layout_dir' => $this->cbconfig->item('layout_tag'),
			'mobile_layout_dir' => $this->cbconfig->item('mobile_layout_tag'),
			'use_sidebar' => $this->cbconfig->item('sidebar_tag'),
			'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_tag'),
			'skin_dir' => $this->cbconfig->item('skin_tag'),
			'mobile_skin_dir' => $this->cbconfig->item('mobile_skin_tag'),
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
