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
 * 관리자>통계관리>현재접속자 controller 입니다.
 */
class Currentvisitor extends CB_Controller
{

	/**
	 * 관리자 페이지 상의 현재 디렉토리입니다
	 * 페이지 이동시 필요한 정보입니다
	 */
	public $pagedir = 'stat/currentvisitor';

	/**
	 * 모델을 로딩합니다
	 */
	protected $models = array('Currentvisitor');

	/**
	 * 이 컨트롤러의 메인 모델 이름입니다
	 */
	protected $modelname = 'Currentvisitor_model';

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
	 * 목록을 가져오는 메소드입니다
	 */
	public function index()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_stat_currentvisitor_index';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		/**
		 * 페이지에 숫자가 아닌 문자가 입력되거나 1보다 작은 숫자가 입력되면 에러 페이지를 보여줍니다.
		 */
		$param =& $this->querystring;
		$page = (((int) $this->input->get('page')) > 0) ? ((int) $this->input->get('page')) : 1;

		$view['view']['sort'] = array(
			'cur_ip' => $param->sort('cur_ip', 'asc'),
			'cur_datetime' => $param->sort('cur_datetime', 'asc'),
			'cur_page' => $param->sort('cur_page', 'asc'),
			'cur_url' => $param->sort('cur_url', 'asc'),
			'cur_referer' => $param->sort('cur_referer', 'asc'),
		);
		$findex = $this->input->get('findex', null, 'cur_datetime');
		$forder = $this->input->get('forder', null, 'desc');
		$sfield = $this->input->get('sfield', null, '');
		$skeyword = $this->input->get('skeyword', null, '');

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

		/**
		 * 게시판 목록에 필요한 정보를 가져옵니다.
		 */
		$this->{$this->modelname}->allow_search_field = array('cur_ip', 'cur_mem_name', 'cur_datetime', 'cur_page', 'cur_url', 'cur_referer', 'cur_useragent', 'currentvisitor.mem_id'); // 검색이 가능한 필드
		$this->{$this->modelname}->search_field_equal = array('currentvisitor.mem_id'); // 검색중 like 가 아닌 = 검색을 하는 필드
		$this->{$this->modelname}->allow_order_field = array('cur_ip', 'cur_datetime', 'cur_page', 'cur_url', 'cur_referer'); // 정렬이 가능한 필드

		$where = array(
			'cur_datetime >' => $curdatetime,
		);
		$result = $this->{$this->modelname}
			->get_admin_list($per_page, $offset, $where, '', $findex, $forder, $sfield, $skeyword);
		$list_num = $result['total_rows'] - ($page - 1) * $per_page;

		if (element('list', $result)) {
			foreach (element('list', $result) as $key => $val) {
				$result['list'][$key]['display_name'] = display_username(
					element('mem_userid', $val),
					element('mem_nickname', $val),
					element('mem_icon', $val)
				);
				if (element('cur_useragent', $val)) {
					$userAgent = get_useragent_info(element('cur_useragent', $val));
					$result['list'][$key]['browsername'] = $userAgent['browsername'];
					$result['list'][$key]['browserversion'] = $userAgent['browserversion'];
					$result['list'][$key]['os'] = $userAgent['os'];
					$result['list'][$key]['engine'] = $userAgent['engine'];
				}
				$result['list'][$key]['num'] = $list_num--;
			}
		}

		$view['view']['data'] = $result;

		/**
		 * primary key 정보를 저장합니다
		 */
		$view['view']['primary_key'] = $this->{$this->modelname}->primary_key;

		/**
		 * 페이지네이션을 생성합니다
		 */
		$config['base_url'] = admin_url($this->pagedir) . '?' . $param->replace('page');
		$config['total_rows'] = $result['total_rows'];
		$config['per_page'] = $per_page;
		$this->pagination->initialize($config);
		$view['view']['paging'] = $this->pagination->create_links();
		$view['view']['page'] = $page;

		/**
		 * 쓰기 주소, 삭제 주소등 필요한 주소를 구합니다
		 */
		$search_option = array('cur_ip' => '아이피', 'cur_datetime' => '날짜', 'cur_page' => '페이지이름', 'cur_url' => '현재주소', 'cur_referer' => '이전주소', 'cur_useragent' => '운영체제/브라우저');
		$view['view']['skeyword'] = ($sfield && array_key_exists($sfield, $search_option)) ? $skeyword : '';
		$view['view']['search_option'] = search_option($search_option, $sfield);
		$view['view']['listall_url'] = admin_url($this->pagedir);

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 어드민 레이아웃을 정의합니다
		 */
		$layoutconfig = array('layout' => 'layout', 'skin' => 'index');
		$view['layout'] = $this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}
}
