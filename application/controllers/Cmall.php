<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Cmall class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 컨텐츠몰 페이지에 관한 controller 입니다.
 */
class Cmall extends CB_Controller
{

	/**
	 * 모델을 로딩합니다
	 */
	protected $models = array();

	/**
	 * 헬퍼를 로딩합니다
	 */
	protected $helpers = array('form', 'array', 'cmall', 'dhtml_editor');

	function __construct()
	{
		parent::__construct();

		/**
		 * 라이브러리를 로딩합니다
		 */
		$this->load->library(array('pagination', 'querystring', 'accesslevel', 'cmalllib'));

		if ( ! $this->cbconfig->item('use_cmall')) {
			alert('이 웹사이트는 ' . html_escape($this->cbconfig->item('cmall_name')) . ' 기능을 사용하지 않습니다');
			return;
		}
	}


	/**
	 * 컨텐츠몰 메인페이지입니다
	 */
	public function index()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_cmall_index';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		$this->load->model('Cmall_item_model');

		$config = array(
			'cit_type1' => '1',
			'limit' => '4',
		);
		$view['view']['type1'] = $this->Cmall_item_model->get_latest($config);

		$config = array(
			'cit_type2' => '1',
			'limit' => '4',
		);
		$view['view']['type2'] = $this->Cmall_item_model->get_latest($config);

		$config = array(
			'cit_type3' => '1',
			'limit' => '4',
		);
		$view['view']['type3'] = $this->Cmall_item_model->get_latest($config);

		$config = array(
			'cit_type4' => '1',
			'limit' => '4',
		);
		$view['view']['type4'] = $this->Cmall_item_model->get_latest($config);

		$view['view']['canonical'] = site_url('cmall');

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 레이아웃을 정의합니다
		 */
		$page_title = $this->cbconfig->item('site_meta_title_cmall');
		$meta_description = $this->cbconfig->item('site_meta_description_cmall');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_cmall');
		$meta_author = $this->cbconfig->item('site_meta_author_cmall');
		$page_name = $this->cbconfig->item('site_page_name_cmall');

		$searchconfig = array(
			'{컨텐츠몰명}',
		);
		$replaceconfig = array(
			$this->cbconfig->item('cmall_name'),
		);

		$page_title = str_replace($searchconfig, $replaceconfig, $page_title);
		$meta_description = str_replace($searchconfig, $replaceconfig, $meta_description);
		$meta_keywords = str_replace($searchconfig, $replaceconfig, $meta_keywords);
		$meta_author = str_replace($searchconfig, $replaceconfig, $meta_author);
		$page_name = str_replace($searchconfig, $replaceconfig, $page_name);

		$layoutconfig = array(
			'path' => 'cmall',
			'layout' => 'layout',
			'skin' => 'cmall',
			'layout_dir' => $this->cbconfig->item('layout_cmall'),
			'mobile_layout_dir' => $this->cbconfig->item('mobile_layout_cmall'),
			'use_sidebar' => $this->cbconfig->item('sidebar_cmall'),
			'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_cmall'),
			'skin_dir' => $this->cbconfig->item('skin_cmall'),
			'mobile_skin_dir' => $this->cbconfig->item('mobile_skin_cmall'),
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


	public function lists($category_id = '')
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_cmall_lists';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		$this->load->model(array('Cmall_item_model'));
		/**
		 * 페이지에 숫자가 아닌 문자가 입력되거나 1보다 작은 숫자가 입력되면 에러 페이지를 보여줍니다.
		 */
		$param =& $this->querystring;
		$page = (((int) $this->input->get('page')) > 0) ? ((int) $this->input->get('page')) : 1;

		$alertmessage = $this->member->is_member()
			? '회원님은 상품 목록을 볼 수 있는 권한이 없습니다'
			: '비회원은 상품목록에 접근할 권한이 없습니다.\\n\\n회원이시라면 로그인 후 이용해 보십시오';
		$access_list = $this->cbconfig->item('access_cmall_list');
		$access_list_level = $this->cbconfig->item('access_cmall_list_level');
		$access_list_group = $this->cbconfig->item('access_cmall_list_group');
		$this->accesslevel->check(
			$access_list,
			$access_list_level,
			$access_list_group,
			$alertmessage,
			''
		);

		$findex = ($this->input->get('findex') && in_array($this->input->get('findex'), $allow_order_field)) ? $this->input->get('findex') : 'cit_order asc';
		$sfield = $this->input->get('sfield', null, '');
		if ($sfield === 'cit_both') {
			$sfield = array('cit_name', 'cit_content');
		}
		$skeyword = $this->input->get('skeyword', null, '');

		$per_page = $this->cbconfig->item('list_count') ? (int) $this->cbconfig->item('list_count') : 20;
		$offset = ($page - 1) * $per_page;

		$this->Cmall_item_model->allow_search_field = array('cit_id', 'cit_name', 'cit_content', 'cit_both', 'cit_price'); // 검색이 가능한 필드
		$this->Cmall_item_model->search_field_equal = array('cit_id'); // 검색중 like 가 아닌 = 검색을 하는 필드

		/**
		 * 게시판 목록에 필요한 정보를 가져옵니다.
		 */
		$where = array();
		$where['cit_status'] = 1;
		$result = $this->Cmall_item_model
			->get_item_list($per_page, $offset, $where, $category_id, $findex, $sfield, $skeyword);
		$list_num = $result['total_rows'] - ($page - 1) * $per_page;
		if (element('list', $result)) {
			foreach (element('list', $result) as $key => $val) {
				$result['list'][$key]['item_url'] = cmall_item_url(element('cit_key', $val));
				$result['list'][$key]['num'] = $list_num--;
			}
		}
		$view['view']['data'] = $result;

		$view['view']['category_nav'] = $this->cmalllib->get_nav_category($category_id);
		$view['view']['category_all'] = $this->cmalllib->get_all_category();
		$view['view']['category_id'] = $category_id;

		/**
		 * 페이지네이션을 생성합니다
		 */
		$config['base_url'] = site_url('cmall/lists/' . $category_id) . '?' . $param->replace('page');
		$config['total_rows'] = $result['total_rows'];
		$config['per_page'] = $per_page;
		$this->pagination->initialize($config);
		$view['view']['paging'] = $this->pagination->create_links();
		$view['view']['page'] = $page;


		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 레이아웃을 정의합니다
		 */
		$page_title = $this->cbconfig->item('site_meta_title_cmall_list');
		$meta_description = $this->cbconfig->item('site_meta_description_cmall_list');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_cmall_list');
		$meta_author = $this->cbconfig->item('site_meta_author_cmall_list');
		$page_name = $this->cbconfig->item('site_page_name_cmall_list');

		$searchconfig = array(
			'{컨텐츠몰명}',
		);
		$replaceconfig = array(
			$this->cbconfig->item('cmall_name'),
		);

		$page_title = str_replace($searchconfig, $replaceconfig, $page_title);
		$meta_description = str_replace($searchconfig, $replaceconfig, $meta_description);
		$meta_keywords = str_replace($searchconfig, $replaceconfig, $meta_keywords);
		$meta_author = str_replace($searchconfig, $replaceconfig, $meta_author);
		$page_name = str_replace($searchconfig, $replaceconfig, $page_name);

		$layoutconfig = array(
			'path' => 'cmall',
			'layout' => 'layout',
			'skin' => 'lists',
			'layout_dir' => $this->cbconfig->item('layout_cmall'),
			'mobile_layout_dir' => $this->cbconfig->item('mobile_layout_cmall'),
			'use_sidebar' => $this->cbconfig->item('sidebar_cmall'),
			'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_cmall'),
			'skin_dir' => $this->cbconfig->item('skin_cmall'),
			'mobile_skin_dir' => $this->cbconfig->item('mobile_skin_cmall'),
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


	public function item($cit_key = '')
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_cmall_item';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		$mem_id = (int) $this->member->item('mem_id');

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		if (empty($cit_key)) {
			show_404();
		}
		$this->load->model(array('Cmall_item_model', 'Cmall_item_meta_model', 'Cmall_item_detail_model'));

		$where = array(
			'cit_key' => $cit_key,
		);
		$data = $this->Cmall_item_model->get_one('', '', $where);
		if ( ! element('cit_id', $data)) {
			show_404();
		}
		if ( ! element('cit_status', $data)) {
			alert('이 상품은 현재 판매하지 않습니다');
		}

		$data['meta'] = $this->Cmall_item_meta_model->get_all_meta(element('cit_id', $data));
		$data['detail'] = $this->Cmall_item_detail_model->get_all_detail(element('cit_id', $data));

		$alertmessage = $this->member->is_member()
			? '회원님은 상품 페이지를 볼 수 있는 권한이 없습니다'
			: '비회원은 상품 페이지를 볼 수 있는 권한이 없습니다.\\n\\n회원이시라면 로그인 후 이용해 보십시오';
		$access_read = $this->cbconfig->item('access_cmall_read');
		$access_read_level = $this->cbconfig->item('access_cmall_read_level');
		$access_read_group = $this->cbconfig->item('access_cmall_read_group');
		$this->accesslevel->check(
			$access_read,
			$access_read_level,
			$access_read_group,
			$alertmessage,
			''
		);

		if ($this->input->post('stype')) {
			if ( ! $mem_id) {
				$this->session->set_flashdata(
					'message',
					'로그인 후 이용이 가능합니다'
				);
				redirect('login?url=' . urlencode(current_full_url()));
			}

			$cit_id = (int) $this->input->post('cit_id');
			if (empty($cit_id) OR $cit_id < 1) {
				show_404();
			}

			if ($this->input->post('stype') === 'wish') {
				$return = $this->cmalllib->addwish($mem_id, $cit_id);
				if ($return) {
					redirect('cmall/wishlist');
				}
			} elseif ($this->input->post('stype') === 'cart'
				&& $this->input->post('chk_detail')
				&& is_array($this->input->post('chk_detail'))
				&& $this->input->post('detail_qty')) {
				$return = $this->cmalllib->addcart(
					$mem_id,
					$cit_id,
					$this->input->post('chk_detail'),
					$this->input->post('detail_qty')
				);
				if ($return) {
					redirect('cmall/cart');
				}
			} elseif ($this->input->post('stype') === 'order'
				&& $this->input->post('chk_detail')
				&& is_array($this->input->post('chk_detail'))
				&& $this->input->post('detail_qty')) {
				$return = $this->cmalllib->addorder(
					$mem_id,
					$cit_id,
					$this->input->post('chk_detail'),
					$this->input->post('detail_qty')
				);
				if ($return) {
					redirect('cmall/order');
				}
			}
		}

		if ( ! $this->session->userdata('cmall_item_id_' . element('cit_id', $data))) {
			$this->Cmall_item_model->update_hit(element('cit_id', $data));
			$this->session->set_userdata(
				'cmall_item_id_' . element('cit_id', $data),
				'1'
			);
		}

		$data['content'] = ($this->cbconfig->get_device_view_type() === 'mobile')
			? (
					element('cit_mobile_content', $data)
					? element('cit_mobile_content', $data)
					: element('cit_content', $data)
				)
			: element('cit_content', $data);
		$thumb_width = ($this->cbconfig->get_device_view_type() === 'mobile')
			? $this->cbconfig->item('cmall_product_mobile_thumb_width')
			: $this->cbconfig->item('cmall_product_thumb_width');
		$autolink = ($this->cbconfig->get_device_view_type() === 'mobile')
			? $this->cbconfig->item('use_cmall_product_mobile_auto_url')
			: $this->cbconfig->item('use_cmall_product_auto_url');
		$popup = ($this->cbconfig->get_device_view_type() === 'mobile')
			? $this->cbconfig->item('cmall_product_mobile_content_target_blank')
			: $this->cbconfig->item('cmall_product_content_target_blank');
		$data['content'] = display_html_content(
			element('content', $data),
			element('cit_content_html_type', $data),
			$thumb_width,
			$autolink,
			$popup,
			$writer_is_admin = true
		);

		$data['header_content'] = ($this->cbconfig->get_device_view_type() === 'mobile')
			? display_html_content(element('mobile_header_content', element('meta', $data)), 1, $thumb_width)
			: display_html_content(element('header_content', element('meta', $data)), 1, $thumb_width);

		$data['footer_content'] = ($this->cbconfig->get_device_view_type() === 'mobile')
			? display_html_content(element('mobile_footer_content', element('meta', $data)), 1, $thumb_width)
			: display_html_content(element('footer_content', element('meta', $data)), 1, $thumb_width);

		$view['view']['data'] = $data;
		$view['view']['item_key'] = $cit_key;

		$layout_dir = (element('item_layout', element('meta', $data))) ? element('item_layout', element('meta', $data)) : $this->cbconfig->item('layout_cmall');
		$mobile_layout_dir = (element('item_mobile_layout', element('meta', $data))) ? element('item_mobile_layout', element('meta', $data)) : $this->cbconfig->item('mobile_layout_cmall');
		$use_sidebar = (element('item_layout', element('meta', $data))) ? element('item_sidebar', element('meta', $data)) : $this->cbconfig->item('sidebar_cmall');
		$use_mobile_sidebar = (element('item_mobile_layout', element('meta', $data))) ? element('item_mobile_sidebar', element('meta', $data)) : $this->cbconfig->item('mobile_sidebar_cmall');
		$skin_dir = (element('item_skin', element('meta', $data))) ? element('item_skin', element('meta', $data)) : $this->cbconfig->item('skin_cmall');
		$mobile_skin_dir = (element('item_mobile_skin', element('meta', $data))) ? element('item_mobile_skin', element('meta', $data)) : $this->cbconfig->item('mobile_skin_cmall');

		$view['view']['canonical'] = cmall_item_url($cit_key);

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 레이아웃을 정의합니다
		 */
		$page_title = $this->cbconfig->item('site_meta_title_cmall_item');
		$meta_description = $this->cbconfig->item('site_meta_description_cmall_item');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_cmall_item');
		$meta_author = $this->cbconfig->item('site_meta_author_cmall_item');
		$page_name = $this->cbconfig->item('site_page_name_cmall_item');

		$searchconfig = array(
			'{컨텐츠몰명}',
			'{상품명}',
			'{판매가격}',
			'{기본설명}',
		);
		$replaceconfig = array(
			$this->cbconfig->item('cmall_name'),
			element('cit_name', $data),
			element('cit_price', $data),
			element('cit_summary', $data),
		);

		$page_title = str_replace($searchconfig, $replaceconfig, $page_title);
		$meta_description = str_replace($searchconfig, $replaceconfig, $meta_description);
		$meta_keywords = str_replace($searchconfig, $replaceconfig, $meta_keywords);
		$meta_author = str_replace($searchconfig, $replaceconfig, $meta_author);
		$page_name = str_replace($searchconfig, $replaceconfig, $page_name);

		$layoutconfig = array(
			'path' => 'cmall',
			'layout' => 'layout',
			'skin' => 'item',
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


	public function cartoption()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_cmall_cartoption';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		$cit_id = (int) $this->input->post('cit_id');
		if (empty($cit_id) OR $cit_id < 1) {
			show_404();
		}

		if ($this->member->is_member() === false) {
			show_404();
		}
		$mem_id = (int) $this->member->item('mem_id');

		$this->load->model(array('Cmall_item_model', 'Cmall_item_detail_model', 'Cmall_cart_model'));

		$item = $this->Cmall_item_model->get_one($cit_id);
		if ( ! element('cit_id', $item)) {
			show_404();
		}

		$detail = $this->Cmall_item_detail_model->get_all_cart_detail($cit_id);
		if ($detail) {
			foreach ($detail as $key => $value) {
				$detail[$key]['cart'] = $this->Cmall_cart_model
					->get_item_is_cart(element('cde_id', $value), $mem_id);
			}
		}

		if ( ! element('cit_id', $item)) {
			show_404();
		}

		$view['view']['item'] = $item;
		$view['view']['detail'] = $detail;

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 레이아웃을 정의합니다
		 */
		$skindir = ($this->cbconfig->get_device_view_type() === 'mobile')
			? $this->cbconfig->item('mobile_skin_cmall')
			: $this->cbconfig->item('skin_cmall');
		if (empty($skindir)) {
			$skindir = ($this->cbconfig->get_device_view_type() === 'mobile')
				? $this->cbconfig->item('mobile_skin_default')
				: $this->cbconfig->item('skin_default');
		}
		if (empty($skindir)) {
			$skindir = 'basic';
		}
		$skin = 'cmall/' . $skindir . '/cartoption';

		$this->data = $view;
		$this->view = $skin;
	}


	public function itemimage($cit_key = '')
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_cmall_itemimage';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		if (empty($cit_key)) {
			show_404();
		}
		$this->load->model(array('Cmall_item_model'));

		$where = array(
			'cit_key' => $cit_key,
		);
		$data = $this->Cmall_item_model->get_one('', '', $where);
		if ( ! element('cit_id', $data)) {
			show_404();
		}

		$view['view']['data'] = $data;

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 레이아웃을 정의합니다
		 */
		$page_title = element('cit_name', $data) . ' > ' . $this->cbconfig->item('cmall_name') . ' 이미지 크게보기';
		$layoutconfig = array(
			'path' => 'cmall',
			'layout' => 'layout_popup',
			'skin' => 'itemimage',
			'layout_dir' => $this->cbconfig->item('layout_cmall'),
			'mobile_layout_dir' => $this->cbconfig->item('mobile_layout_cmall'),
			'use_sidebar' => $this->cbconfig->item('sidebar_cmall'),
			'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_cmall'),
			'skin_dir' => $this->cbconfig->item('skin_cmall'),
			'mobile_skin_dir' => $this->cbconfig->item('mobile_skin_cmall'),
			'page_title' => $page_title,
		);
		$view['layout'] = $this->managelayout->front($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}


	public function cart()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_cmall_cart';
		$this->load->event($eventname);

		/**
		 * 로그인이 필요한 페이지입니다
		 */
		required_user_login();

		$mem_id = (int) $this->member->item('mem_id');

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		$this->load->model(array('Cmall_cart_model'));

		if ($this->input->post('chk')) {
			$cit_id = $this->input->post('chk');
			$return = $this->cmalllib->cart_to_order(
				$mem_id,
				$cit_id
			);
			if ($return) {
				redirect('cmall/order');
			}
		}

		$cachename = 'delete_old_cart_cache';
		$cachetime = 3600;
		if ( ! $result = $this->cache->get($cachename)) {
			$days = $this->cbconfig->item('cmall_cart_keep_days')
				? $this->cbconfig->item('cmall_cart_keep_days') : 14;
			$cartdays = cdate('Y-m-d H:i:s', ctimestamp() - $days * 86400);
			$deletewhere = array(
				'cct_datetime <' => $cartdays,
			);
			$this->Cmall_cart_model->delete_where($deletewhere);
			$this->cache->save($cachename, cdate('Y-m-d H:i:s'), $cachetime);
		}


		/**
		 * 페이지에 숫자가 아닌 문자가 입력되거나 1보다 작은 숫자가 입력되면 에러 페이지를 보여줍니다.
		 */
		$param =& $this->querystring;
		$findex = 'cmall_item.cit_id';
		$forder = 'desc';

		/**
		 * 게시판 목록에 필요한 정보를 가져옵니다.
		 */
		$where = array();
		$where['cmall_cart.mem_id'] = $mem_id;
		$result = $this->Cmall_cart_model->get_cart_list($where, $findex, $forder);
		if ($result) {
			foreach ($result as $key => $val) {
				$result[$key]['item_url'] = cmall_item_url(element('cit_key', $val));
				$result[$key]['detail'] = $this->Cmall_cart_model
					->get_cart_detail($mem_id, element('cit_id', $val));
			}
		}
		$view['view']['data'] = $result;
		$view['view']['list_delete_url'] = site_url('cmallact/cart_delete/?' . $param->output());

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 레이아웃을 정의합니다
		 */
		$page_title = $this->cbconfig->item('site_meta_title_cmall_cart');
		$meta_description = $this->cbconfig->item('site_meta_description_cmall_cart');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_cmall_cart');
		$meta_author = $this->cbconfig->item('site_meta_author_cmall_cart');
		$page_name = $this->cbconfig->item('site_page_name_cmall_cart');

		$searchconfig = array(
			'{컨텐츠몰명}',
		);
		$replaceconfig = array(
			$this->cbconfig->item('cmall_name'),
		);

		$page_title = str_replace($searchconfig, $replaceconfig, $page_title);
		$meta_description = str_replace($searchconfig, $replaceconfig, $meta_description);
		$meta_keywords = str_replace($searchconfig, $replaceconfig, $meta_keywords);
		$meta_author = str_replace($searchconfig, $replaceconfig, $meta_author);
		$page_name = str_replace($searchconfig, $replaceconfig, $page_name);

		$layoutconfig = array(
			'path' => 'cmall',
			'layout' => 'layout',
			'skin' => 'cart',
			'layout_dir' => $this->cbconfig->item('layout_cmall'),
			'mobile_layout_dir' => $this->cbconfig->item('mobile_layout_cmall'),
			'use_sidebar' => $this->cbconfig->item('sidebar_cmall'),
			'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_cmall'),
			'skin_dir' => $this->cbconfig->item('skin_cmall'),
			'mobile_skin_dir' => $this->cbconfig->item('mobile_skin_cmall'),
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
	 * 주문하기 입니다
	 */
	public function order()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_cmall_order';
		$this->load->event($eventname);

		/**
		 * 로그인이 필요한 페이지입니다
		 */
		required_user_login();

		$mem_id = (int) $this->member->item('mem_id');

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		$alertmessage = $this->member->is_member()
			? '회원님은 상품을 구매할 수 있는 권한이 없습니다'
			: '비회원은 상품을 구매할 수 있는 권한이 없습니다.\\n\\n회원이시라면 로그인 후 이용해 보십시오';
		$access_buy = $this->cbconfig->item('access_cmall_buy');
		$access_buy_level = $this->cbconfig->item('access_cmall_buy_level');
		$access_buy_group = $this->cbconfig->item('access_cmall_buy_group');
		$this->accesslevel->check(
			$access_buy,
			$access_buy_level,
			$access_buy_group,
			$alertmessage,
			''
		);

		$this->load->model(array('Cmall_cart_model'));

		/**
		 * 페이지에 숫자가 아닌 문자가 입력되거나 1보다 작은 숫자가 입력되면 에러 페이지를 보여줍니다.
		 */
		$param =& $this->querystring;
		$findex = 'cmall_item.cit_id';
		$forder = 'desc';

		/**
		 * 게시판 목록에 필요한 정보를 가져옵니다.
		 */
		$where = array();
		$where['cmall_cart.mem_id'] = $mem_id;
		$result = $this->Cmall_cart_model->get_order_list($where, $findex, $forder);
		$good_name = '';
		$good_count = -1;
		$session_cct_id = array();
		if ($result) {
			foreach ($result as $key => $val) {
				$result[$key]['item_url'] = cmall_item_url(element('cit_key', $val));
				$result[$key]['detail'] = $this->Cmall_cart_model
					->get_order_detail($mem_id, element('cit_id', $val));
				if (empty($good_name)) {
					$good_name = element('cit_name', $val);
				}
				$good_count ++;
				$session_cct_id[] = element('cct_id', $val);
			}
		}
		$view['view']['data'] = $result;

		$this->load->model('Unique_id_model');
		$unique_id = $this->Unique_id_model->get_id($this->input->ip_address());
		$view['view']['unique_id'] = $unique_id;
		$view['view']['good_name'] = $good_name;
		if ($good_count > 0) {
			$view['view']['good_name'] .= ' 외 ' . $good_count . '건';
		}
		$this->session->set_userdata(
			'unique_id',
			$unique_id
		);
		$this->session->set_userdata(
			'order_cct_id',
			implode('-', $session_cct_id)
		);

		$view['view']['use_pg'] = $use_pg = false;
		if ($this->cbconfig->item('use_payment_card')
			OR $this->cbconfig->item('use_payment_realtime')
			OR $this->cbconfig->item('use_payment_vbank')
			OR $this->cbconfig->item('use_payment_phone')
			OR $this->cbconfig->item('use_payment_easy')) {
			$view['view']['use_pg'] = $use_pg = true;
		}


		if ($this->cbconfig->item('use_payment_pg') === 'kcp' && $use_pg) {
			$this->load->library('paymentlib');
			$view['view']['pg'] = $this->paymentlib->kcp_init();
			/*	 //삭제예정
			if ($this->cbconfig->get_device_type() !== 'mobile') {
				$view['view']['body_script'] = 'onLoad="CheckPayplusInstall();"';
			}
			*/
		}

		if ($this->cbconfig->item('use_payment_pg') === 'lg' && $use_pg) {
			$this->load->library('paymentlib');
			$view['view']['pg'] = $this->paymentlib->lg_init();
			/*	 //삭제예정
			if ($this->cbconfig->get_device_type() !== 'mobile') {
				$view['view']['body_script'] = 'onload="isActiveXOK();"';
			}
			*/
		}

		if ($this->cbconfig->item('use_payment_pg') === 'inicis' && $use_pg) {
			$this->load->library('paymentlib');
			$view['view']['pg'] = $this->paymentlib->inicis_init();
			/* //삭제예정
			if ($this->cbconfig->get_device_type() !== 'mobile') {
				$view['view']['body_script'] = 'onload="enable_click();"';
			}
			*/
		}

		$view['view']['ptype'] = 'cmall';

		$view['view']['form1name'] = ($this->cbconfig->get_device_type() === 'mobile') ? 'mform_1' : 'form_1';
		$view['view']['form2name'] = ($this->cbconfig->get_device_type() === 'mobile') ? 'mform_2' : 'form_2';
		$view['view']['form3name'] = ($this->cbconfig->get_device_type() === 'mobile') ? 'mform_3' : 'form_3';
		$view['view']['form4name'] = ($this->cbconfig->get_device_type() === 'mobile') ? 'mform_4' : 'form_4';

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 레이아웃을 정의합니다
		 */
		$page_title = $this->cbconfig->item('site_meta_title_cmall_order');
		$meta_description = $this->cbconfig->item('site_meta_description_cmall_order');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_cmall_order');
		$meta_author = $this->cbconfig->item('site_meta_author_cmall_order');
		$page_name = $this->cbconfig->item('site_page_name_cmall_order');

		$searchconfig = array(
			'{컨텐츠몰명}',
		);
		$replaceconfig = array(
			$this->cbconfig->item('cmall_name'),
		);

		$page_title = str_replace($searchconfig, $replaceconfig, $page_title);
		$meta_description = str_replace($searchconfig, $replaceconfig, $meta_description);
		$meta_keywords = str_replace($searchconfig, $replaceconfig, $meta_keywords);
		$meta_author = str_replace($searchconfig, $replaceconfig, $meta_author);
		$page_name = str_replace($searchconfig, $replaceconfig, $page_name);

		$layoutconfig = array(
			'path' => 'cmall',
			'layout' => 'layout',
			'skin' => 'order',
			'layout_dir' => $this->cbconfig->item('layout_cmall'),
			'mobile_layout_dir' => $this->cbconfig->item('mobile_layout_cmall'),
			'use_sidebar' => $this->cbconfig->item('sidebar_cmall'),
			'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_cmall'),
			'skin_dir' => $this->cbconfig->item('skin_cmall'),
			'mobile_skin_dir' => $this->cbconfig->item('mobile_skin_cmall'),
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


	public function orderresult($cor_id = 0)
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_cmall_orderresult';
		$this->load->event($eventname);

		/**
		 * 로그인이 필요한 페이지입니다
		 */
		required_user_login();

		$this->load->library(array('paymentlib'));
		$mem_id = (int) $this->member->item('mem_id');

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		if (empty($cor_id) OR $cor_id < 1) {
			alert('잘못된 접근입니다');
		}

		$this->load->model(array('Cmall_item_model', 'Cmall_order_model', 'Cmall_order_detail_model'));

		$order = $this->Cmall_order_model->get_one($cor_id);
		if ( ! element('cor_id', $order)) {
			alert('잘못된 접근입니다');
		}
		if ($this->member->is_admin() === false
			&& (int) element('mem_id', $order) !== $mem_id) {
			alert('잘못된 접근입니다');
		}
		$orderdetail = $this->Cmall_order_detail_model->get_by_item($cor_id);
		if ($orderdetail) {
			foreach ($orderdetail as $key => $value) {
				$orderdetail[$key]['item'] = $item
					= $this->Cmall_item_model->get_one(element('cit_id', $value));
				$orderdetail[$key]['itemdetail'] = $itemdetail
					= $this->Cmall_order_detail_model
					->get_detail_by_item($cor_id, element('cit_id', $value));

				$orderdetail[$key]['item']['possible_download'] = 1;
				if (element('cod_download_days', element(0, $itemdetail)) && element('cor_approve_datetime', $order)) {
					$endtimestamp = strtotime(element('cor_approve_datetime', $order))
						+ 86400 * element('cod_download_days', element(0, $itemdetail));
					$orderdetail[$key]['item']['download_end_date'] = $enddate
						= cdate('Y-m-d', $endtimestamp);

					$orderdetail[$key]['item']['possible_download'] = ($enddate >= date('Y-m-d')) ? 1 : 0;
				}
			}
		}
		if (element('cor_status', $order) === '1') {
			$this->session->set_userdata(
				'cmall_item_download_' . element('cor_id', $order),
				'1'
			);
		}

		//핸드폰의 영수증 정보
		if( element('cor_pay_type', $order) === 'phone' ){
			switch( element('cor_pg', $order) ){
				case 'lg' :
					$init			= $this->paymentlib->lg_init();
					$LGD_MID		= element('LGD_MID', $init);
					$LGD_TID		= element('cor_tno', $order);
					$LGD_MERTKEY	= element('pg_lg_key', $init);
					$LGD_HASHDATA	= md5($LGD_MID.$LGD_TID.$LGD_MERTKEY);

					if ( element('is_test', $order) ) {
						$order['card_receipt_js'] = 'http://pgweb.uplus.co.kr:7085/WEB_SERVER/js/receipt_link.js';
					} else {
						$order['card_receipt_js'] = 'http://pgweb.uplus.co.kr/WEB_SERVER/js/receipt_link.js';
					}
					$order['card_receipt_script'] = 'showReceiptByTID(\''.$LGD_MID.'\', \''.$LGD_TID.'\', \''.$LGD_HASHDATA.'\');';
					break;
				case 'inicis' :
					$order['card_receipt_script'] = 'window.open(\'https://iniweb.inicis.com/DefaultWebApp/mall/cr/cm/mCmReceipt_head.jsp?noTid='.element('cor_tno', $order).'&noMethod=1\',\'receipt\',\'width=430,height=700\');';
					break;
				case 'kcp' :
					if ( element('is_test', $order) ) {
						$receipturl = 'https://testadmin8.kcp.co.kr/assist/bill.BillActionNew.do?cmd=';
					} else {
						$receipturl = 'https://admin8.kcp.co.kr/assist/bill.BillActionNew.do?cmd=';
					}
					$order['card_receipt_script'] = 'window.open(\''.$receipturl.'mcash_bill&tno='.element('cor_tno', $order).'&order_no='.element('cor_id', $order).'&trade_mony='.element('cor_cash', $order).'\', \'winreceipt\', \'width=470,height=815,scrollbars=yes,resizable=yes\');';
					break;
			}
		}

		//카드의 영수증 정보
		if( element('cor_pay_type', $order) === 'card' ){
			switch( element('cor_pg', $order) ){
				case 'lg' :
					$init = $this->paymentlib->lg_init();
					$LGD_MID		= element('LGD_MID', $init);
					$LGD_TID		= element('cor_tno', $order);
					$LGD_MERTKEY	= element('pg_lg_key', $init);
					$LGD_HASHDATA	= md5($LGD_MID.$LGD_TID.$LGD_MERTKEY);

					if ( element('is_test', $order) ) {
						$order['card_receipt_js'] = 'http://pgweb.uplus.co.kr:7085/WEB_SERVER/js/receipt_link.js';
					} else {
						$order['card_receipt_js'] = 'http://pgweb.uplus.co.kr/WEB_SERVER/js/receipt_link.js';
					}
					$order['card_receipt_script'] = 'showReceiptByTID(\''.$LGD_MID.'\', \''.$LGD_TID.'\', \''.$LGD_HASHDATA.'\');';
					break;
				case 'inicis' :
					$order['card_receipt_script'] = 'window.open(\'https://iniweb.inicis.com/DefaultWebApp/mall/cr/cm/mCmReceipt_head.jsp?noTid='.element('cor_tno', $order).'&noMethod=1\',\'receipt\',\'width=430,height=700\');';
					break;
				case 'kcp' :
					if ( element('is_test', $order) ) {
						$receipturl = 'https://testadmin8.kcp.co.kr/assist/bill.BillActionNew.do?cmd=';
					} else {
						$receipturl = 'https://admin8.kcp.co.kr/assist/bill.BillActionNew.do?cmd=';
					}
					$order['card_receipt_script'] = 'window.open(\''.$receipturl.'card_bill&tno='.element('cor_tno', $order).'&order_no='.element('cor_id', $order).'&trade_mony='.element('cor_cash', $order).'\', \'winreceipt\', \'width=470,height=815,scrollbars=yes,resizable=yes\');';
					break;
			}
		}

		$view['view']['data'] = $order;
		$view['view']['orderdetail'] = $orderdetail;


		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 레이아웃을 정의합니다
		 */
		$page_title = $this->cbconfig->item('site_meta_title_cmall_orderresult');
		$meta_description = $this->cbconfig->item('site_meta_description_cmall_orderresult');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_cmall_orderresult');
		$meta_author = $this->cbconfig->item('site_meta_author_cmall_orderresult');
		$page_name = $this->cbconfig->item('site_page_name_cmall_orderresult');

		$searchconfig = array(
			'{컨텐츠몰명}',
		);
		$replaceconfig = array(
			$this->cbconfig->item('cmall_name'),
		);

		$page_title = str_replace($searchconfig, $replaceconfig, $page_title);
		$meta_description = str_replace($searchconfig, $replaceconfig, $meta_description);
		$meta_keywords = str_replace($searchconfig, $replaceconfig, $meta_keywords);
		$meta_author = str_replace($searchconfig, $replaceconfig, $meta_author);
		$page_name = str_replace($searchconfig, $replaceconfig, $page_name);

		$layoutconfig = array(
			'path' => 'cmall',
			'layout' => 'layout',
			'skin' => 'order_result',
			'layout_dir' => $this->cbconfig->item('layout_cmall'),
			'mobile_layout_dir' => $this->cbconfig->item('mobile_layout_cmall'),
			'use_sidebar' => $this->cbconfig->item('sidebar_cmall'),
			'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_cmall'),
			'skin_dir' => $this->cbconfig->item('skin_cmall'),
			'mobile_skin_dir' => $this->cbconfig->item('mobile_skin_cmall'),
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


	public function inicisweb(){
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_payment_inicis_pc_pay';
		$this->load->event($eventname);

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		$this->load->library(array('paymentlib'));
		$init = $this->paymentlib->inicis_init();

		if( 'inicis' !== $this->cbconfig->item('use_payment_pg') ){
			die(json_encode(array('error'=>'올바른 방법으로 이용해 주십시오.')));
		}

		$request_mid = $this->input->post('mid', null, '');
		$session_order_num = $this->session->userdata('unique_id');

		if( ($request_mid != element('pg_inicis_mid', $init)) || ! $session_order_num ){
			alert("잘못된 요청입니다.");
		}

		$orderNumber = $this->input->post('orderNumber', true, 0);

		if( !$orderNumber ){
			alert("주문번호가 없습니다.");
		}

		$this->load->model('Payment_order_data_model');
		$row = $this->Payment_order_data_model->get_one($orderNumber);
		$params = array();
		$data = cmall_tmp_replace_data($row['pod_data']);

		if( !$data ){
			alert("임시 주문 정보가 저장되지 않았습니다. \\n 다시 실행해 주세요.");
		}

		foreach($data as $key=>$value) {
			if(is_array($value)) {
				foreach($value as $k=>$v) {
					$_POST[$key][$k] = $params[$key][$k] = $v;
				}
			} else {
				$_POST[$key] = $params[$key] = $value;
			}
		}

		// 이벤트가 존재하면 실행합니다
		Events::trigger('after', $eventname);

		$this->orderupdate();
	}

	/**
	 * 주문 업데이트 함수입니다
	 */
	public function orderupdate($agent_type='')
	{
		if( 'mobile' == $agent_type && $this->cbconfig->item('use_payment_pg') === 'inicis' && ($unique_id = $this->session->userdata('unique_id')) && $exist_order = get_cmall_order_data($unique_id) ){	//상품주문
			exists_inicis_cmall_order($unique_id, array(), $exist_order['cor_datetime']);
			exit;
		}

		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_cmall_orderupdate';
		$this->load->event($eventname);

		/**
		 * 로그인이 필요한 페이지입니다
		 */
		required_user_login();

		$mem_id = (int) $this->member->item('mem_id');

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		if ('bank' != $this->input->post('pay_type') && $this->cbconfig->item('use_payment_pg') === 'lg'
			&& ! $this->input->post('LGD_PAYKEY')) {
			alert('결제등록 요청 후 주문해 주십시오');
		}

		if ( ! $this->session->userdata('unique_id') OR ! $this->input->post('unique_id') OR $this->session->userdata('unique_id') !== $this->input->post('unique_id')) {
			alert('잘못된 접근입니다');
		}
		if ( ! $this->session->userdata('order_cct_id')) {
			alert('잘못된 접근입니다');
		}

		$this->load->model('Cmall_cart_model');
		$where = array();
		$where['cmall_cart.mem_id'] = $mem_id;
		$findex = 'cmall_item.cit_id';
		$forder = 'desc';
		$session_cct_id = array();

		$good_mny = $this->input->post('good_mny', null, 0);	//request 값으로 받은 값
		$item_cct_price = 0;		//주문한 상품의 총 금액의 초기화

		$orderlist = $this->Cmall_cart_model->get_order_list($where, $findex, $forder);
		if ($orderlist) {
			foreach ($orderlist as $key => $val) {
				$details = $this->Cmall_cart_model->get_order_detail($mem_id, element('cit_id', $val));

				if( !empty($details) ){
					foreach((array) $details as $detail ){
						if( empty($detail) ) continue;

						$item_cct_price += ((int) element('cit_price', $val) + (int) element('cde_price', $detail)) * element('cct_count', $detail);
					}
				}

				$session_cct_id[] = element('cct_id', $val);
			}
		}

		if ( $item_cct_price != $good_mny ){
		}

		if ($this->session->userdata('order_cct_id') !== implode('-', $session_cct_id)) {
			alert('결제 내역이 상이합니다, 관리자에게 문의하여주세요');
		}

		if ( ! is_numeric($this->input->post('order_deposit'))) {
			alert(html_escape($this->cbconfig->item('deposit_name')) . ' 의 값은 숫자만 와야 합니다');
		}
		if ( ! is_numeric($this->input->post('total_price_sum'))) {
			alert('총 결제금액의 값은 숫자만 와야 합니다');
		}
		$order_deposit = (int) $this->input->post('order_deposit');
		$total_price_sum = (int) $this->input->post('total_price_sum');
		if ($order_deposit) {
			if ($order_deposit < 0) {
				alert(html_escape($this->cbconfig->item('deposit_name')) . ' 의 값은 0 보다 작을 수 없습니다 ', site_url('cmall/order'));
			}
			if ($order_deposit > $total_price_sum) {
				alert(html_escape($this->cbconfig->item('deposit_name')) . ' 의 값은 총 결제금액보다 클 수 없습니다', site_url('cmall/order'));
			}
			if ($order_deposit > (int) $this->member->item('total_deposit')) {
				alert(html_escape($this->cbconfig->item('deposit_name')) . ' 값이 회원님이 보유하고 계신 값보다 큰 값이 입력되어서 진행할 수 없습니다', site_url('cmall/order'));
			}
		}


		$this->load->library('paymentlib');

		$insertdata = array();
		$result = '';
		$od_status = 'order'; //주문상태

		if ($this->input->post('pay_type') === 'bank') {		//무통장입금
			$insertdata['cor_datetime'] = date('Y-m-d H:i:s');
			$insertdata['mem_realname'] = $this->input->post('mem_realname', null, '');
			$insertdata['cor_total_money'] = $total_price_sum;
			$insertdata['cor_cash_request'] = $this->input->post('good_mny', null, 0);
			$insertdata['cor_deposit'] = $order_deposit;
			$insertdata['cor_cash'] = 0;

			/*	 //request 요청값으로 체크하면 안됨
			if ($this->input->post('good_mny')) {
			}
			*/

			if ( ((int) $item_cct_price - (int) $order_deposit ) != 0 ) {
				$insertdata['cor_status'] = 0;
				$insertdata['cor_approve_datetime'] = null;
			} else {
				$insertdata['cor_status'] = 1;
				$insertdata['cor_approve_datetime'] = date('Y-m-d H:i:s');
				$od_status = 'deposit'; //주문상태
			}

		} elseif ($this->input->post('pay_type') === 'realtime') {
			if ($this->cbconfig->item('use_payment_pg') === 'kcp') {
				$result = $this->paymentlib->kcp_pp_ax_hub();
			} elseif ($this->cbconfig->item('use_payment_pg') === 'lg') {
				$result = $this->paymentlib->xpay_result();
			} elseif ($this->cbconfig->item('use_payment_pg') === 'inicis') {
				$result = $this->paymentlib->inipay_result($agent_type);
			}

			$insertdata['cor_tno'] = element('tno', $result);
			$insertdata['cor_app_no'] = element('app_no', $result) ? element('app_no', $result) : '';
			$insertdata['cor_datetime'] = date('Y-m-d H:i:s');
			$insertdata['cor_approve_datetime'] = preg_replace(
				"/([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})/",
				"\\1-\\2-\\3 \\4:\\5:\\6",
				element('app_time', $result)
			);
			$insertdata['cor_total_money'] = $total_price_sum;
			$insertdata['cor_cash_request'] = element('amount', $result);
			$insertdata['cor_deposit'] = $order_deposit;
			$insertdata['cor_cash'] = $cor_cash = element('amount', $result);
			$insertdata['cor_status'] = 1;
			$insertdata['mem_realname'] = $this->input->post('mem_realname', null, '');
			$insertdata['cor_pg'] = $this->cbconfig->item('use_payment_pg');

			if ( ((int) $item_cct_price - (int) $order_deposit - $cor_cash) == 0 ) {
				$od_status = 'deposit'; //주문상태
			}

		 } elseif ($this->input->post('pay_type') === 'vbank') {
			if ($this->cbconfig->item('use_payment_pg') === 'kcp') {
				$result = $this->paymentlib->kcp_pp_ax_hub();

				$result['bankname'] = iconv("cp949", "utf-8", $result['bankname']);
				$result['depositor'] = iconv("cp949", "utf-8", $result['depositor']);

			} elseif ($this->cbconfig->item('use_payment_pg') === 'lg') {
				$result = $this->paymentlib->xpay_result();
			} elseif ($this->cbconfig->item('use_payment_pg') === 'inicis') {
				$result = $this->paymentlib->inipay_result($agent_type);
			}

			$insertdata['cor_tno'] = element('tno', $result);
			$insertdata['cor_app_no'] = element('app_no', $result);
			$insertdata['cor_datetime'] = date('Y-m-d H:i:s');
			$insertdata['cor_total_money'] = $total_price_sum;
			$insertdata['cor_cash_request'] = element('amount', $result);
			$insertdata['cor_deposit'] = $order_deposit;
			$insertdata['cor_cash'] = 0;
			$insertdata['cor_status'] = 0;
			$insertdata['mem_realname'] = element('depositor', $result);
			$insertdata['cor_vbank_expire'] = element('cor_vbank_expire', $result) ? date("Y-m-d", strtotime(element('cor_vbank_expire', $result))) : '0000-00-00 00:00:00';
			$insertdata['cor_bank_info'] = element('bankname', $result) . ' ' . element('account', $result);
			$insertdata['cor_pg'] = $this->cbconfig->item('use_payment_pg');
		} elseif ($this->input->post('pay_type') === 'phone') {
			if ($this->cbconfig->item('use_payment_pg') === 'kcp') {
				$result = $this->paymentlib->kcp_pp_ax_hub();
			} elseif ($this->cbconfig->item('use_payment_pg') === 'lg') {
				$result = $this->paymentlib->xpay_result();
			} elseif ($this->cbconfig->item('use_payment_pg') === 'inicis') {
				$result = $this->paymentlib->inipay_result($agent_type);
			}

			$insertdata['cor_tno'] = element('tno', $result);
			$insertdata['cor_app_no'] = element('commid', $result) . ' ' . element('mobile_no', $result);
			$insertdata['cor_datetime'] = date('Y-m-d H:i:s');
			$insertdata['cor_approve_datetime'] = preg_replace(
				"/([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})/",
				"\\1-\\2-\\3 \\4:\\5:\\6",
				element('app_time', $result)
			);
			$insertdata['cor_total_money'] = $total_price_sum;
			$insertdata['cor_cash_request'] = element('amount', $result);
			$insertdata['cor_deposit'] = $order_deposit;
			$insertdata['cor_cash'] = $cor_cash = element('amount', $result);
			$insertdata['cor_status'] = 1;
			$insertdata['mem_realname'] = $this->input->post('mem_realname', null, '');
			$insertdata['cor_bank_info'] = element('mobile_no', $result);
			$insertdata['cor_pg'] = $this->cbconfig->item('use_payment_pg');

			if ( ((int) $item_cct_price - (int) $order_deposit - $cor_cash) == 0 ) {
				$od_status = 'deposit'; //주문상태
			}

		} elseif ($this->input->post('pay_type') === 'card') {
			if ($this->cbconfig->item('use_payment_pg') === 'kcp') {
				$result = $this->paymentlib->kcp_pp_ax_hub();
			} elseif ($this->cbconfig->item('use_payment_pg') === 'lg') {
				$result = $this->paymentlib->xpay_result();
			} elseif ($this->cbconfig->item('use_payment_pg') === 'inicis') {
				$result = $this->paymentlib->inipay_result($agent_type);
			}

			$insertdata['cor_tno'] = element('tno', $result);
			$insertdata['cor_app_no'] = element('app_no', $result);
			$insertdata['cor_datetime'] = date('Y-m-d H:i:s');
			$insertdata['cor_approve_datetime'] = preg_replace(
				"/([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})/",
				"\\1-\\2-\\3 \\4:\\5:\\6",
				element('app_time', $result)
			);
			$insertdata['cor_total_money'] = $total_price_sum;
			$insertdata['cor_cash_request'] = element('amount', $result);
			$insertdata['cor_deposit'] = $order_deposit;
			$insertdata['cor_cash'] = $cor_cash = element('amount', $result);
			$insertdata['cor_bank_info'] = element('card_name', $result);
			$insertdata['cor_status'] = 1;
			$insertdata['mem_realname'] = $this->input->post('mem_realname', null, '');
			$insertdata['cor_pg'] = $this->cbconfig->item('use_payment_pg');

			if ( ((int) $item_cct_price - (int) $order_deposit - $cor_cash) == 0 ) {
				$od_status = 'deposit'; //주문상태
			}

		} else {
			alert('결제 수단이 잘못 입력되었습니다');
		}

		// 이벤트가 존재하면 실행합니다
		Events::trigger('step1', $eventname);

		//실제로 결제된 금액
		$real_total_price = $total_price_sum - $order_deposit;

		// 주문금액과 결제금액이 일치하는지 체크
		if (element('tno', $result) && (int) element('amount', $result) !== $real_total_price) {
			if ($this->cbconfig->item('use_payment_pg') === 'kcp') {
				$this->paymentlib->kcp_pp_ax_hub_cancel($result);
			} elseif ($this->cbconfig->item('use_payment_pg') === 'lg') {
				$this->paymentlib->xpay_cancel($result);
			} elseif ($this->cbconfig->item('use_payment_pg') === 'inicis') {
				$this->paymentlib->inipay_cancel($result, $agent_type);
			}
			alert('결제가 완료되지 않았습니다. 다시 시도해주십시오', site_url('cmall/order'));
		}

		// 이벤트가 존재하면 실행합니다
		Events::trigger('step2', $eventname);

		// 정보 입력
		$cor_id = $this->session->userdata('unique_id');
		$insertdata['cor_id'] = $cor_id;
		$insertdata['mem_id'] = $mem_id;
		$insertdata['mem_nickname'] = $this->member->item('mem_nickname');
		$insertdata['mem_email'] = $this->input->post('mem_email', null, '');
		$insertdata['mem_phone'] = $this->input->post('mem_phone', null, '');
		$insertdata['cor_pay_type'] = $this->input->post('pay_type', null, '');
		$insertdata['cor_content'] = $this->input->post('cor_content', null, '');
		$insertdata['cor_ip'] = $this->input->ip_address();
		$insertdata['cor_useragent'] = $this->agent->agent_string();
		$insertdata['is_test'] = $this->cbconfig->item('use_pg_test');
		$insertdata['status'] = $od_status;

		$this->load->model(array('Cmall_item_model', 'Cmall_order_model', 'Cmall_order_detail_model'));
		$res = $this->Cmall_order_model->insert($insertdata);
		if ($res) {
			$cwhere = array(
				'mem_id' => $mem_id,
				'cct_order' => 1,
			);
			$cartorder = $this->Cmall_cart_model->get('', '', $cwhere);
			if ($cartorder) {
				foreach ($cartorder as $key => $val) {
					$item = $this->Cmall_item_model
						->get_one(element('cit_id', $val), 'cit_download_days');
					$insertdetail = array(
						'cor_id' => $cor_id,
						'mem_id' => $mem_id,
						'cit_id' => element('cit_id', $val),
						'cde_id' => element('cde_id', $val),
						'cod_download_days' => element('cit_download_days', $item),
						'cod_count' => element('cct_count', $val),
						'cod_status' => $od_status,
					);
					$this->Cmall_order_detail_model->insert($insertdetail);
					$deletewhere = array(
						'mem_id' => $mem_id,
						'cit_id' => element('cit_id', $val),
						'cde_id' => element('cde_id', $val),
					);
					$this->Cmall_cart_model->delete_where($deletewhere);
				}
			}
			if ($order_deposit) {
				$this->load->library('depositlib');
				$this->depositlib->do_deposit_to_contents(
					$mem_id,
					$order_deposit,
					$pay_type = '',
					$content = '상품구매 주문번호 : ' . $cor_id,
					$admin_memo = ''
				);
			}
		}

		if (empty($res)) {
			if ($this->input->post('pay_type') !== 'bank') {
				if ($this->cbconfig->item('use_payment_pg') === 'kcp') {
					$this->paymentlib->kcp_pp_ax_hub_cancel($result);
				} elseif ($this->cbconfig->item('use_payment_pg') === 'lg') {
					$this->paymentlib->xpay_cancel($result);
				} elseif ($this->cbconfig->item('use_payment_pg') === 'inicis') {
					$this->paymentlib->inipay_cancel($result, $agent_type);
				}
			}
			alert('결제가 완료되지 않았습니다. 다시 시도해주십시오', site_url('cmall/order'));
		}


		if ($this->input->post('pay_type') === 'bank') {
			$this->cmalllib->orderalarm('bank_to_contents', $cor_id);
		} else {
			$this->cmalllib->orderalarm('cash_to_contents', $cor_id);
		}

		// 이벤트가 존재하면 실행합니다
		Events::trigger('after', $eventname);

		$this->session->set_userdata('unique_id', '');
		$this->session->set_userdata('order_cct_id', '');

		redirect('cmall/orderresult/' . $cor_id);
	}


	public function orderlist()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_cmall_orderlist';
		$this->load->event($eventname);

		/**
		 * 로그인이 필요한 페이지입니다
		 */
		required_user_login();

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		$this->load->model(array('Cmall_order_model'));
		/**
		 * 페이지에 숫자가 아닌 문자가 입력되거나 1보다 작은 숫자가 입력되면 에러 페이지를 보여줍니다.
		 */
		$param =& $this->querystring;
		$page = (((int) $this->input->get('page')) > 0) ? ((int) $this->input->get('page')) : 1;
		$findex = $this->Cmall_order_model->primary_key;
		$forder = 'desc';

		$per_page = $this->cbconfig->item('list_count') ? (int) $this->cbconfig->item('list_count') : 20;
		$offset = ($page - 1) * $per_page;

		/**
		 * 게시판 목록에 필요한 정보를 가져옵니다.
		 */
		$where = array();
		$where['mem_id'] = $this->member->item('mem_id');

		$result = $this->Cmall_order_model
			->get_list($per_page, $offset, $where, '', $findex, $forder);
		$list_num = $result['total_rows'] - ($page - 1) * $per_page;
		$view['view']['data'] = $result;

		/**
		 * 페이지네이션을 생성합니다
		 */
		$config['base_url'] = site_url('cmall/orderlist') . '?' . $param->replace('page');
		$config['total_rows'] = $result['total_rows'];
		$config['per_page'] = $per_page;
		$this->pagination->initialize($config);
		$view['view']['paging'] = $this->pagination->create_links();
		$view['view']['page'] = $page;


		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 레이아웃을 정의합니다
		 */
		$page_title = $this->cbconfig->item('site_meta_title_cmall_orderlist');
		$meta_description = $this->cbconfig->item('site_meta_description_cmall_orderlist');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_cmall_orderlist');
		$meta_author = $this->cbconfig->item('site_meta_author_cmall_orderlist');
		$page_name = $this->cbconfig->item('site_page_name_cmall_orderlist');

		$searchconfig = array(
			'{컨텐츠몰명}',
		);
		$replaceconfig = array(
			$this->cbconfig->item('cmall_name'),
		);

		$page_title = str_replace($searchconfig, $replaceconfig, $page_title);
		$meta_description = str_replace($searchconfig, $replaceconfig, $meta_description);
		$meta_keywords = str_replace($searchconfig, $replaceconfig, $meta_keywords);
		$meta_author = str_replace($searchconfig, $replaceconfig, $meta_author);
		$page_name = str_replace($searchconfig, $replaceconfig, $page_name);

		$layoutconfig = array(
			'path' => 'cmall',
			'layout' => 'layout',
			'skin' => 'order_list',
			'layout_dir' => $this->cbconfig->item('layout_cmall'),
			'mobile_layout_dir' => $this->cbconfig->item('mobile_layout_cmall'),
			'use_sidebar' => $this->cbconfig->item('sidebar_cmall'),
			'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_cmall'),
			'skin_dir' => $this->cbconfig->item('skin_cmall'),
			'mobile_skin_dir' => $this->cbconfig->item('mobile_skin_cmall'),
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


	public function wishlist()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_cmall_wishlist';
		$this->load->event($eventname);

		/**
		 * 로그인이 필요한 페이지입니다
		 */
		required_user_login();

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		$this->load->model(array('Cmall_wishlist_model'));
		/**
		 * 페이지에 숫자가 아닌 문자가 입력되거나 1보다 작은 숫자가 입력되면 에러 페이지를 보여줍니다.
		 */
		$param =& $this->querystring;
		$page = (((int) $this->input->get('page')) > 0) ? ((int) $this->input->get('page')) : 1;
		$findex = $this->Cmall_wishlist_model->primary_key;
		$forder = 'desc';

		$per_page = $this->cbconfig->item('list_count') ? (int) $this->cbconfig->item('list_count') : 20;
		$offset = ($page - 1) * $per_page;

		/**
		 * 게시판 목록에 필요한 정보를 가져옵니다.
		 */
		$where = array();
		$where['cmall_wishlist.mem_id'] = $this->member->item('mem_id');
		$where['cit_status'] = 1;
		$result = $this->Cmall_wishlist_model
			->get_list($per_page, $offset, $where, '', $findex, $forder);
		$list_num = $result['total_rows'] - ($page - 1) * $per_page;
		if (element('list', $result)) {
			foreach (element('list', $result) as $key => $val) {
				$result['list'][$key]['item_url'] = cmall_item_url(element('cit_key', $val));
				$result['list'][$key]['delete_url'] = site_url('cmallact/wishlist_delete/' . element('cwi_id', $val) . '?' . $param->output());
				$result['list'][$key]['num'] = $list_num--;
			}
		}
		$view['view']['data'] = $result;

		/**
		 * 페이지네이션을 생성합니다
		 */
		$config['base_url'] = site_url('cmall/wishlist') . '?' . $param->replace('page');
		$config['total_rows'] = $result['total_rows'];
		$config['per_page'] = $per_page;
		$this->pagination->initialize($config);
		$view['view']['paging'] = $this->pagination->create_links();
		$view['view']['page'] = $page;


		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 레이아웃을 정의합니다
		 */
		$page_title = $this->cbconfig->item('site_meta_title_cmall_wishlist');
		$meta_description = $this->cbconfig->item('site_meta_description_cmall_wishlist');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_cmall_wishlist');
		$meta_author = $this->cbconfig->item('site_meta_author_cmall_wishlist');
		$page_name = $this->cbconfig->item('site_page_name_cmall_wishlist');

		$searchconfig = array(
			'{컨텐츠몰명}',
		);
		$replaceconfig = array(
			$this->cbconfig->item('cmall_name'),
		);

		$page_title = str_replace($searchconfig, $replaceconfig, $page_title);
		$meta_description = str_replace($searchconfig, $replaceconfig, $meta_description);
		$meta_keywords = str_replace($searchconfig, $replaceconfig, $meta_keywords);
		$meta_author = str_replace($searchconfig, $replaceconfig, $meta_author);
		$page_name = str_replace($searchconfig, $replaceconfig, $page_name);

		$layoutconfig = array(
			'path' => 'cmall',
			'layout' => 'layout',
			'skin' => 'wishlist',
			'layout_dir' => $this->cbconfig->item('layout_cmall'),
			'mobile_layout_dir' => $this->cbconfig->item('mobile_layout_cmall'),
			'use_sidebar' => $this->cbconfig->item('sidebar_cmall'),
			'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_cmall'),
			'skin_dir' => $this->cbconfig->item('skin_cmall'),
			'mobile_skin_dir' => $this->cbconfig->item('mobile_skin_cmall'),
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
	 * 상품후기 목록을 ajax 로 가져옵니다
	 */
	public function reviewlist($cit_id = 0)
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_cmall_reviewlist';
		$this->load->event($eventname);

		$cit_id = (int) $cit_id;
		if (empty($cit_id) OR $cit_id < 1) {
			show_404();
		}

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		$this->load->model(array('Cmall_item_model', 'Cmall_review_model'));

		$item = $this->Cmall_item_model->get_one($cit_id);
		if ( ! element('cit_id', $item)) {
			show_404();
		}

		$mem_id = (int) $this->member->item('mem_id');

		/**
		 * 페이지에 숫자가 아닌 문자가 입력되거나 1보다 작은 숫자가 입력되면 에러 페이지를 보여줍니다.
		 */
		$param =& $this->querystring;
		$page = (((int) $this->input->get('page')) > 0) ? ((int) $this->input->get('page')) : 1;
		$findex = $this->input->get('findex', null, 'cre_id');
		$forder = $this->input->get('forder', null, 'desc');
		$sfield = '';
		$skeyword = '';

		$per_page = 5;
		$offset = ($page - 1) * $per_page;

		$is_admin = $this->member->is_admin();

		/**
		 * 게시판 목록에 필요한 정보를 가져옵니다.
		 */
		$where = array();
		$where['cre_status'] = 1;
		$where['cit_id'] = $cit_id;

		$thumb_width = ($this->cbconfig->get_device_view_type() === 'mobile')
			? $this->cbconfig->item('cmall_product_review_mobile_thumb_width')
			: $this->cbconfig->item('cmall_product_review_thumb_width');
		$autolink = ($this->cbconfig->get_device_view_type() === 'mobile')
			? $this->cbconfig->item('use_cmall_product_review_mobile_auto_url')
			: $this->cbconfig->item('use_cmall_product_review_auto_url');
		$popup = ($this->cbconfig->get_device_view_type() === 'mobile')
			? $this->cbconfig->item('cmall_product_review_mobile_content_target_blank')
			: $this->cbconfig->item('cmall_product_review_content_target_blank');

		$result = $this->Cmall_review_model
			->get_list($per_page, $offset, $where, '', $findex, $forder);
		$list_num = $result['total_rows'] - ($page - 1) * $per_page;
		if (element('list', $result)) {
			foreach (element('list', $result) as $key => $val) {
				$result['list'][$key]['display_name'] = display_username(
					element('mem_userid', $val),
					element('mem_nickname', $val),
					element('mem_icon', $val)
				);
				$result['list'][$key]['display_datetime'] = display_datetime(element('cre_datetime', $val), 'full');
				$result['list'][$key]['content'] = display_html_content(
					element('cre_content', $val),
					element('cre_content_html_type', $val),
					$thumb_width,
					$autolink,
					$popup
				);
				$result['list'][$key]['can_update'] = false;
				$result['list'][$key]['can_delete'] = false;
				if ($is_admin !== false
					OR (element('mem_id', $val) && $mem_id === (int) element('mem_id', $val))) {
					$result['list'][$key]['can_update'] = true;
					$result['list'][$key]['can_delete'] = true;
				}
				$result['list'][$key]['num'] = $list_num--;
			}
		}
		$view['view']['data'] = $result;
		$view['view']['cit_id'] = $cit_id;

		/**
		 * 페이지네이션을 생성합니다
		 */
		$config['base_url'] = site_url('cmall/reviewlist/' . $cit_id) . '?' . $param->replace('page');
		$config['total_rows'] = $result['total_rows'];
		$config['per_page'] = $per_page;

		if ( ! $this->input->get('page')) {
			$_GET['page'] = (string) $page;
		}

		$config['_attributes'] = 'onClick="cmall_review_page(\'' . $cit_id . '\', $(this).attr(\'data-ci-pagination-page\'));return false;"';
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
		$skindir = ($this->cbconfig->get_device_view_type() === 'mobile')
			? $this->cbconfig->item('mobile_skin_cmall')
			: $this->cbconfig->item('skin_cmall');
		if (empty($skindir)) {
			$skindir = ($this->cbconfig->get_device_view_type() === 'mobile')
				? $this->cbconfig->item('mobile_skin_default')
				: $this->cbconfig->item('skin_default');
		}
		if (empty($skindir)) {
			$skindir = 'basic';
		}
		$skin = 'cmall/' . $skindir . '/review_list';

		$view['view']['view_skin_url'] = site_url(VIEW_DIR . 'cmall/' . $skindir);

		$this->data = $view;
		$this->view = $skin;
	}


	public function review_write($cit_id = 0, $cre_id = 0)
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_cmall_review_write';
		$this->load->event($eventname);

		/**
		 * 로그인이 필요한 페이지입니다
		 */
		required_user_login('alert');


		$view = array();
		$view['view'] = array();

		$mem_id = (int) $this->member->item('mem_id');

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		$this->load->model(array('Cmall_item_model', 'Cmall_review_model'));

		/**
		 * 프라이머리키에 숫자형이 입력되지 않으면 에러처리합니다
		 */
		if ($cre_id) {
			$cre_id = (int) $cre_id;
			if (empty($cre_id) OR $cre_id < 1) {
				show_404();
			}
		}
		$cit_id = (int) $cit_id;
		if (empty($cit_id) OR $cit_id < 1) {
			show_404();
		}

		$primary_key = $this->Cmall_review_model->primary_key;

		$item = $this->Cmall_item_model->get_one($cit_id);
		if ( ! element('cit_id', $item) OR ! element('cit_status', $item)) {
			alert_close('존재하지 않는 상품입니다');
		}

		/**
		 * 수정 페이지일 경우 기존 데이터를 가져옵니다
		 */
		$getdata = array();
		if ($cre_id) {
			$getdata = $this->Cmall_review_model->get_one($cre_id);
			if ( ! element('cre_id', $getdata)) {
				alert_close('잘못된 접근입니다');
			}
			$is_admin = $this->member->is_admin();
			if ($is_admin === false
				&& (int) element('mem_id', $getdata) !== $mem_id) {
				alert_close('본인의 글 외에는 접근하실 수 없습니다');
			}
		}

		/**
		 * 주문완료 후 사용후기 작성 가능한 경우
		 **/
		if ( ! $this->cbconfig->item('use_cmall_product_review_anytime')) {
			$ordered = $this->cmalllib->is_ordered_item($mem_id, $cit_id);
			if (empty($ordered)) {
				alert_close('주문을 완료하신 후에 상품후기 작성이 가능합니다');
			}

		}

		/**
		 * Validation 라이브러리를 가져옵니다
		 */
		$this->load->library('form_validation');

		/**
		 * 전송된 데이터의 유효성을 체크합니다
		 */
		$config = array(
			array(
				'field' => 'cre_title',
				'label' => '제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'cre_content',
				'label' => '내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'cre_score',
				'label' => '평점',
				'rules' => 'trim|required|numeric|is_natural_no_zero|greater_than_equal_to[1]|less_than_equal_to[5]',
			),
		);
		$this->form_validation->set_rules($config);


		/**
		 * 유효성 검사를 하지 않는 경우, 또는 유효성 검사에 실패한 경우입니다.
		 * 즉 글쓰기나 수정 페이지를 보고 있는 경우입니다
		 */
		if ($this->form_validation->run() === false) {

			// 이벤트가 존재하면 실행합니다
			$view['view']['event']['formrunfalse'] = Events::trigger('formrunfalse', $eventname);

			/**
			 * primary key 정보를 저장합니다
			 */
			$view['view']['primary_key'] = $primary_key;
			$view['view']['data'] = $getdata;
			$view['view']['item'] = $item;

			/**
			 * 레이아웃을 정의합니다
			 */
			$page_title = $this->cbconfig->item('site_meta_title_cmall_review_write');
			$meta_description = $this->cbconfig->item('site_meta_description_cmall_review_write');
			$meta_keywords = $this->cbconfig->item('site_meta_keywords_cmall_review_write');
			$meta_author = $this->cbconfig->item('site_meta_author_cmall_review_write');
			$page_name = $this->cbconfig->item('site_page_name_cmall_review_write');

			$searchconfig = array(
				'{컨텐츠몰명}',
				'{상품명}',
				'{판매가격}',
				'{기본설명}',
			);
			$replaceconfig = array(
				$this->cbconfig->item('cmall_name'),
				element('cit_name', $item),
				element('cit_price', $item),
				element('cit_summary', $item),
			);

			$page_title = str_replace($searchconfig, $replaceconfig, $page_title);
			$meta_description = str_replace($searchconfig, $replaceconfig, $meta_description);
			$meta_keywords = str_replace($searchconfig, $replaceconfig, $meta_keywords);
			$meta_author = str_replace($searchconfig, $replaceconfig, $meta_author);
			$page_name = str_replace($searchconfig, $replaceconfig, $page_name);

			$layoutconfig = array(
				'path' => 'cmall',
				'layout' => 'layout_popup',
				'skin' => 'review_write',
				'layout_dir' => $this->cbconfig->item('layout_cmall'),
				'mobile_layout_dir' => $this->cbconfig->item('mobile_layout_cmall'),
				'use_sidebar' => $this->cbconfig->item('sidebar_cmall'),
				'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_cmall'),
				'skin_dir' => $this->cbconfig->item('skin_cmall'),
				'mobile_skin_dir' => $this->cbconfig->item('mobile_skin_cmall'),
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

		} else {
			/**
			 * 유효성 검사를 통과한 경우입니다.
			 * 즉 데이터의 insert 나 update 의 process 처리가 필요한 상황입니다
			 */

			// 이벤트가 존재하면 실행합니다
			Events::trigger('formruntrue', $eventname);

			$content_type = $this->cbconfig->item('use_cmall_product_review_dhtml') ? 1 : 0;
			$updatedata = array(
				'cit_id' => $cit_id,
				'cre_title' => $this->input->post('cre_title', null, ''),
				'cre_content' => $this->input->post('cre_content', null, ''),
				'cre_content_html_type' => $content_type,
				'cre_score' => $this->input->post('cre_score', null, 0),
			);

			/**
			 * 게시물을 수정하는 경우입니다
			 */
			$param =& $this->querystring;
			$page = (((int) $this->input->get('page')) > 0) ? ((int) $this->input->get('page')) : 1;

			if ($cre_id) {

				// 이벤트가 존재하면 실행합니다
				Events::trigger('before_update', $eventname);

				$this->Cmall_review_model->update($cre_id, $updatedata);
				$cntresult = $this->cmalllib->update_review_count($cit_id);
				$jresult = json_decode($cntresult, true);
				$cnt = element('cit_review_count', $jresult);
				echo '<script type="text/javascript">window.opener.view_cmall_review("viewitemreview", ' . $cit_id . ', ' . $page . ');window.opener.cmall_review_count_update(' . $cnt . ');</script>';
				alert_close('정상적으로 수정되었습니다.');
			} else {

				// 이벤트가 존재하면 실행합니다
				Events::trigger('before_insert', $eventname);

				/**
				 * 게시물을 새로 입력하는 경우입니다
				 */
				$updatedata['cre_datetime'] = cdate('Y-m-d H:i:s');
				$updatedata['mem_id'] = $mem_id;
				$updatedata['cre_ip'] = $this->input->ip_address();

				if ( ! $this->cbconfig->item('use_cmall_product_review_approve')) {
					$updatedata['cre_status'] = 1;
				}

				$_cre_id = $this->Cmall_review_model->insert($updatedata);

				$this->cmalllib->review_alarm($_cre_id);

				$cntresult = $this->cmalllib->update_review_count($cit_id);
				$jresult = json_decode($cntresult, true);
				$cnt = element('cit_review_count', $jresult);
				if ($this->cbconfig->item('use_cmall_product_review_approve')) {
					echo '<script type="text/javascript">window.opener.view_cmall_review("viewitemreview", ' . $cit_id . ', ' . $page . ');window.opener.cmall_review_count_update(' . $cnt . ');</script>';
					alert_close('정상적으로 입력되었습니다. 관리자의 승인 후 출력됩니다');
				} else {
					echo '<script type="text/javascript">window.opener.view_cmall_review("viewitemreview", ' . $cit_id . ', ' . $page . ');window.opener.cmall_review_count_update(' . $cnt . ');</script>';
					alert_close('정상적으로 입력되었습니다.');
				}
			}
		}
	}


	/**
	 * Q&A 목록을 ajax 로 가져옵니다
	 */
	public function qnalist($cit_id = 0)
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_cmall_qnalist';
		$this->load->event($eventname);

		$cit_id = (int) $cit_id;
		if (empty($cit_id) OR $cit_id < 1) {
			show_404();
		}

		$view = array();
		$view['view'] = array();

		$mem_id = (int) $this->member->item('mem_id');

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		$this->load->model(array('Cmall_item_model', 'Cmall_qna_model'));

		$item = $this->Cmall_item_model->get_one($cit_id);
		if ( ! element('cit_id', $item)) {
			show_404();
		}

		/**
		 * 페이지에 숫자가 아닌 문자가 입력되거나 1보다 작은 숫자가 입력되면 에러 페이지를 보여줍니다.
		 */
		$param =& $this->querystring;
		$page = (((int) $this->input->get('page')) > 0) ? ((int) $this->input->get('page')) : 1;
		$findex = $this->input->get('findex') ? $this->input->get('findex') : 'cre_id';
		$forder = $this->input->get('forder', null, 'desc');
		$sfield = '';
		$skeyword = '';

		$per_page = 5;
		$offset = ($page - 1) * $per_page;

		$is_admin = $this->member->is_admin();

		/**
		 * 게시판 목록에 필요한 정보를 가져옵니다.
		 */
		$where = array();
		$where['cit_id'] = $cit_id;

		$thumb_width = ($this->cbconfig->get_device_view_type() === 'mobile')
			? $this->cbconfig->item('cmall_product_qna_mobile_thumb_width')
			: $this->cbconfig->item('cmall_product_qna_thumb_width');
		$autolink = ($this->cbconfig->get_device_view_type() === 'mobile')
			? $this->cbconfig->item('use_cmall_product_qna_mobile_auto_url')
			: $this->cbconfig->item('use_cmall_product_qna_auto_url');
		$popup = ($this->cbconfig->get_device_view_type() === 'mobile')
			? $this->cbconfig->item('cmall_product_qna_mobile_content_target_blank')
			: $this->cbconfig->item('cmall_product_qna_content_target_blank');

		$result = $this->Cmall_qna_model
			->get_list($per_page, $offset, $where, '', $findex, $forder);
		$list_num = $result['total_rows'] - ($page - 1) * $per_page;
		if (element('list', $result)) {
			foreach (element('list', $result) as $key => $val) {
				$result['list'][$key]['display_name'] = display_username(
					element('mem_userid', $val),
					element('mem_nickname', $val),
					element('mem_icon', $val)
				);
				$result['list'][$key]['display_datetime'] = display_datetime(element('cqa_datetime', $val), 'full');
				$result['list'][$key]['content'] = display_html_content(
					element('cqa_content', $val),
					element('cqa_content_html_type', $val),
					$thumb_width,
					$autolink,
					$popup
				);
				$result['list'][$key]['reply_content'] = display_html_content(
					element('cqa_reply_content', $val),
					element('cqa_reply_html_type', $val),
					$thumb_width,
					$autolink,
					$popup
				);
				if (element('cqa_secret', $val)) {
					if ($mem_id && ($is_admin !== false OR (int) element('mem_id', $val) === $mem_id)) {
						$result['list'][$key]['content'] = '<div class="label label-warning">비밀글입니다</div> ' . $result['list'][$key]['content'];
						$result['list'][$key]['reply_content'] = '<div class="label label-warning">비밀글입니다</div>' . $result['list'][$key]['reply_content'];
					} else {
						$result['list'][$key]['content'] = '<div class="label label-warning">비밀글입니다</div>';
						$result['list'][$key]['reply_content'] = '<div class="label label-warning">비밀글입니다</div>';
					}
				}
				if ( ! element('cqa_reply_content', $val)) {
					$result['list'][$key]['reply_content'] = '아직 답변이 완료되지 않았습니다';
				}

				$result['list'][$key]['can_update'] = false;
				$result['list'][$key]['can_delete'] = false;
				if ($is_admin !== false OR (element('mem_id', $val) && $mem_id === (int) element('mem_id', $val))) {
					$result['list'][$key]['can_update'] = true;
					$result['list'][$key]['can_delete'] = true;
				}
				$result['list'][$key]['num'] = $list_num--;
			}
		}
		$view['view']['data'] = $result;
		$view['view']['cit_id'] = $cit_id;

		/**
		 * 페이지네이션을 생성합니다
		 */
		$config['base_url'] = site_url('cmall/qnalist/' . $cit_id) . '?' . $param->replace('page');
		$config['total_rows'] = $result['total_rows'];
		$config['per_page'] = $per_page;

		if ( ! $this->input->get('page')) {
			$_GET['page'] = (string) $page;
		}

		$config['_attributes'] = 'onClick="cmall_qna_page(\'' . $cit_id . '\', $(this).attr(\'data-ci-pagination-page\'));return false;"';
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
		$skindir = ($this->cbconfig->get_device_view_type() === 'mobile')
			? $this->cbconfig->item('mobile_skin_cmall')
			: $this->cbconfig->item('skin_cmall');
		if (empty($skindir)) {
			$skindir = ($this->cbconfig->get_device_view_type() === 'mobile')
				? $this->cbconfig->item('mobile_skin_default')
				: $this->cbconfig->item('skin_default');
		}
		if (empty($skindir)) {
			$skindir = 'basic';
		}
		$skin = 'cmall/' . $skindir . '/qna_list';

		$this->data = $view;
		$this->view = $skin;
	}


	public function qna_write($cit_id = 0, $cqa_id = 0)
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_cmall_qna_write';
		$this->load->event($eventname);

		/**
		 * 로그인이 필요한 페이지입니다
		 */
		required_user_login('alert');

		$mem_id = (int) $this->member->item('mem_id');

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		$this->load->model(array('Cmall_item_model', 'Cmall_qna_model'));

		/**
		 * 프라이머리키에 숫자형이 입력되지 않으면 에러처리합니다
		 */
		if ($cqa_id) {
			$cqa_id = (int) $cqa_id;
			if (empty($cqa_id) OR $cqa_id < 1) {
				show_404();
			}
		}
		$cit_id = (int) $cit_id;
		if (empty($cit_id) OR $cit_id < 1) {
			show_404();
		}
		$primary_key = $this->Cmall_qna_model->primary_key;

		$item = $this->Cmall_item_model->get_one($cit_id);
		if ( ! element('cit_id', $item) OR ! element('cit_status', $item)) {
			alert_close('존재하지 않는 상품입니다');
		}

		/**
		 * 수정 페이지일 경우 기존 데이터를 가져옵니다
		 */
		$getdata = array();
		if ($cqa_id) {
			$getdata = $this->Cmall_qna_model->get_one($cqa_id);
			if ( ! element('cqa_id', $getdata)) {
				alert_close('잘못된 접근입니다');
			}
			$is_admin = $this->member->is_admin();
			if ($is_admin === false && (int) element('mem_id', $getdata) !== $mem_id) {
				alert_close('본인의 글 외에는 접근하실 수 없습니다');
			}
		}

		/**
		 * Validation 라이브러리를 가져옵니다
		 */
		$this->load->library('form_validation');

		/**
		 * 전송된 데이터의 유효성을 체크합니다
		 */
		$config = array(
			array(
				'field' => 'cqa_title',
				'label' => '제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'cqa_content',
				'label' => '내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'cqa_secret',
				'label' => '비밀글여부',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'cqa_receive_email',
				'label' => '답변시 메일받기',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'cqa_receive_sms',
				'label' => '답변시 문자받기',
				'rules' => 'trim|numeric',
			),
		);
		$this->form_validation->set_rules($config);


		/**
		 * 유효성 검사를 하지 않는 경우, 또는 유효성 검사에 실패한 경우입니다.
		 * 즉 글쓰기나 수정 페이지를 보고 있는 경우입니다
		 */
		if ($this->form_validation->run() === false) {

			// 이벤트가 존재하면 실행합니다
			$view['view']['event']['formrunfalse'] = Events::trigger('formrunfalse', $eventname);

			/**
			 * primary key 정보를 저장합니다
			 */
			$view['view']['primary_key'] = $primary_key;
			$view['view']['data'] = $getdata;
			$view['view']['item'] = $item;

			// 이벤트가 존재하면 실행합니다
			$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

			/**
			 * 레이아웃을 정의합니다
			 */
			$page_title = $this->cbconfig->item('site_meta_title_cmall_qna_write');
			$meta_description = $this->cbconfig->item('site_meta_description_cmall_qna_write');
			$meta_keywords = $this->cbconfig->item('site_meta_keywords_cmall_qna_write');
			$meta_author = $this->cbconfig->item('site_meta_author_cmall_qna_write');
			$page_name = $this->cbconfig->item('site_page_name_cmall_qna_write');

			$searchconfig = array(
				'{컨텐츠몰명}',
				'{상품명}',
				'{판매가격}',
				'{기본설명}',
			);
			$replaceconfig = array(
				$this->cbconfig->item('cmall_name'),
				element('cit_name', $item),
				element('cit_price', $item),
				element('cit_summary', $item),
			);

			$page_title = str_replace($searchconfig, $replaceconfig, $page_title);
			$meta_description = str_replace($searchconfig, $replaceconfig, $meta_description);
			$meta_keywords = str_replace($searchconfig, $replaceconfig, $meta_keywords);
			$meta_author = str_replace($searchconfig, $replaceconfig, $meta_author);
			$page_name = str_replace($searchconfig, $replaceconfig, $page_name);

			$layoutconfig = array(
				'path' => 'cmall',
				'layout' => 'layout_popup',
				'skin' => 'qna_write',
				'layout_dir' => $this->cbconfig->item('layout_cmall'),
				'mobile_layout_dir' => $this->cbconfig->item('mobile_layout_cmall'),
				'use_sidebar' => $this->cbconfig->item('sidebar_cmall'),
				'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_cmall'),
				'skin_dir' => $this->cbconfig->item('skin_cmall'),
				'mobile_skin_dir' => $this->cbconfig->item('mobile_skin_cmall'),
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

		} else {
			/**
			 * 유효성 검사를 통과한 경우입니다.
			 * 즉 데이터의 insert 나 update 의 process 처리가 필요한 상황입니다
			 */

			// 이벤트가 존재하면 실행합니다
			$view['view']['event']['formruntrue'] = Events::trigger('formruntrue', $eventname);

			$content_type = $this->cbconfig->item('use_cmall_product_qna_dhtml') ? 1 : 0;
			$cqa_secret = $this->input->post('cqa_secret') ? 1 : 0;
			$cqa_receive_email = $this->input->post('cqa_receive_email') ? 1 : 0;
			$cqa_receive_sms = $this->input->post('cqa_receive_sms') ? 1 : 0;

			$updatedata = array(
				'cit_id' => $cit_id,
				'cqa_title' => $this->input->post('cqa_title', null, ''),
				'cqa_content' => $this->input->post('cqa_content', null, ''),
				'cqa_content_html_type' => $content_type,
				'cqa_secret' => $cqa_secret,
				'cqa_receive_email' => $cqa_receive_email,
				'cqa_receive_sms' => $cqa_receive_sms,
			);

			/**
			 * 게시물을 수정하는 경우입니다
			 */
			$param =& $this->querystring;
			$page = (((int) $this->input->get('page')) > 0) ? ((int) $this->input->get('page')) : 1;

			if ($cqa_id) {
				$this->Cmall_qna_model->update($cqa_id, $updatedata);
				$cntresult = $this->cmalllib->update_qna_count($cit_id);
				$jresult = json_decode($cntresult, true);
				$cnt = element('cit_qna_count', $jresult);
				echo '<script type="text/javascript">window.opener.view_cmall_qna("viewitemqna", ' . $cit_id . ', ' . $page . ');window.opener.cmall_qna_count_update(' . $cnt . ');</script>';
				alert_close('정상적으로 수정되었습니다.');
			} else {
				/**
				 * 게시물을 새로 입력하는 경우입니다
				 */
				$updatedata['cqa_datetime'] = cdate('Y-m-d H:i:s');
				$updatedata['mem_id'] = $mem_id;
				$updatedata['cqa_ip'] = $this->input->ip_address();

				$_cqa_id = $this->Cmall_qna_model->insert($updatedata);

				$this->cmalllib->qna_alarm($_cqa_id);

				$cntresult = $this->cmalllib->update_qna_count($cit_id);
				$jresult = json_decode($cntresult, true);
				$cnt = element('cit_qna_count', $jresult);
				echo '<script type="text/javascript">window.opener.view_cmall_qna("viewitemqna", ' . $cit_id . ', ' . $page . ');window.opener.cmall_qna_count_update(' . $cnt . ');</script>';
				alert_close('정상적으로 입력되었습니다.');
			}
		}
	}
}