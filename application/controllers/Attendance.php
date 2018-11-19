<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Attendance class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 출석체크 담당하는 controller 입니다.
 */
class Attendance extends CB_Controller
{

	/**
	 * 모델을 로딩합니다
	 */
	protected $models = array('Attendance');

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
	 * 출석체크 페이지입니다
	 */
	public function index()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_attendance_index';
		$this->load->event($eventname);

		if ( ! $this->cbconfig->item('use_attendance')) {
			alert('이 웹사이트는 출석체크 기능을 사용하지 않습니다');
		}

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		$attendance_default_memo = str_replace(
			array("\r\n", "\r", "\n"),
			"\n",
			$this->cbconfig->item('attendance_default_memo')
		);
		$default_memo = explode("\n", $attendance_default_memo);
		shuffle($default_memo);
		$view['view']['default_memo'] = $default_memo;

		$date = $this->input->get('date') ? $this->input->get('date') : cdate('Y-m-d');
		if (strlen($date) !== 10) {
			$date = cdate('Y-m-d');
		}
		$arr = explode('-', $date);
		if (checkdate(element(1, $arr), element(2, $arr), element(0, $arr)) === false) {
			$date = cdate('Y-m-d');
		}
		$view['view']['date'] = $date;
		$view['view']['date_format'] = cdate('Y년 m월 d일', strtotime($date));
		$view['view']['lastday'] = cdate('t', strtotime($date));
		$view['view']['ym'] = substr($date,0,7);
		$view['view']['d'] = substr($date,8,2);
		$view['view']['lastmonth'] = cdate('Y-m-d', strtotime(substr($date,0,8) . '01') - 86400);
		$view['view']['nextmonth'] = ($view['view']['ym'] < cdate('Y-m'))
			? cdate('Y-m-d', strtotime(substr($date,0,8) . cdate('t', strtotime($date))) + 86400) : '';

		$view['view']['canonical'] = site_url('attendance');

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 레이아웃을 정의합니다
		 */
		$page_title = $this->cbconfig->item('site_meta_title_attendance');
		$meta_description = $this->cbconfig->item('site_meta_description_attendance');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_attendance');
		$meta_author = $this->cbconfig->item('site_meta_author_attendance');
		$page_name = $this->cbconfig->item('site_page_name_attendance');

		$layoutconfig = array(
			'path' => 'attendance',
			'layout' => 'layout',
			'skin' => 'attendance',
			'layout_dir' => $this->cbconfig->item('layout_attendance'),
			'mobile_layout_dir' => $this->cbconfig->item('mobile_layout_attendance'),
			'use_sidebar' => $this->cbconfig->item('sidebar_attendance'),
			'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_attendance'),
			'skin_dir' => $this->cbconfig->item('skin_attendance'),
			'mobile_skin_dir' => $this->cbconfig->item('mobile_skin_attendance'),
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


	public function update()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_attendance_update';
		$this->load->event($eventname);

		if ( ! $this->cbconfig->item('use_attendance')) {
			alert('이 웹사이트는 출석체크 기능을 사용하지 않습니다');
		}

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		/**
		 * Validation 라이브러리를 가져옵니다
		 */
		$this->load->library('form_validation');

		/**
		 * 전송된 데이터의 유효성을 체크합니다
		 */
		$memo_length = $this->cbconfig->item('attendance_memo_length');
		$maxlength = $memo_length ? '|max_length[' . $memo_length . ']' : '';
		$config = array(
			array(
				'field' => 'memo',
				'label' => '출석 한마디',
				'rules' => 'trim|required|callback__check_attendance' . $maxlength,
			),
		);
		$this->form_validation->set_rules($config);

		/**
		 * 유효성 검사를 하지 않는 경우, 또는 유효성 검사에 실패한 경우입니다.
		 * 즉 글쓰기나 수정 페이지를 보고 있는 경우입니다
		 */
		if ($this->form_validation->run() === false) {

			// 이벤트가 존재하면 실행합니다
			Events::trigger('formrunfalse', $eventname);

			if (validation_errors()) {
				$result = array('error' => validation_errors(' ', ' '));
				exit(json_encode($result));
			}
			$result = array('error' => '잘못된 접근입니다');
			exit(json_encode($result));

		} else {
			/**
			 * 유효성 검사를 통과한 경우입니다.
			 * 즉 데이터의 insert 나 update 의 process 처리가 필요한 상황입니다
			 */

			// 이벤트가 존재하면 실행합니다
			Events::trigger('formruntrue', $eventname);

			$max_data = $this->Attendance_model->get_today_max_ranking();

			$max_ranking = element('att_ranking', $max_data);
			$mypoint = $this->cbconfig->item('attendance_point');
			$mypoint = (int) $mypoint;

			if (empty($max_ranking)) {
				$my_ranking = 1;
				$mypoint += $this->cbconfig->item('attendance_point_1');
			} else {
				$my_ranking = $max_ranking + 1;
			}

			for ($i = 2; $i <= 10; $i++) {
				if ($this->cbconfig->item('attendance_point_' . $i) > 0 && $my_ranking === $i) {
					$mypoint += $this->cbconfig->item('attendance_point_' . $i);
				}
			}
			$yesterdata = $this->Attendance_model->yesterday_data();

			if ( ! element('att_continuity', $yesterdata)) {
				$att_continuity = 1;
			} else {
				$att_continuity = element('att_continuity', $yesterdata) + 1;
			}

			// 개근 포인트
			if ($this->cbconfig->item('attendance_point_regular_days')
				&& $att_continuity % $this->cbconfig->item('attendance_point_regular_days') === 0) {
				$mypoint += $this->cbconfig->item('attendance_point_regular');
			}

			$insertdata = array(
				'mem_id' => $this->member->item('mem_id'),
				'att_point' => $mypoint,
				'att_memo' => $this->input->post('memo', null, ''),
				'att_continuity' => $att_continuity,
				'att_ranking' => $my_ranking,
				'att_date' => cdate('Y-m-d'),
				'att_datetime' => cdate('Y-m-d H:i:s'),
			);
			$att_id = $this->Attendance_model->insert($insertdata);

			$this->load->library('point');
			$this->point->insert_point(
				$this->member->item('mem_id'),
				$mypoint,
				cdate('Y-m-d') . ' 출석체크',
				'attendance',
				$att_id,
				'출석체크'
			);

			// 이벤트가 존재하면 실행합니다
			Events::trigger('after', $eventname);

			$result = array('success' => $my_ranking . '등으로 출석하셨습니다. 감사합니다');
			exit(json_encode($result));
		}
	}


	public function dailylist($date)
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_attendance_dailylist';
		$this->load->event($eventname);

		if ( ! $this->cbconfig->item('use_attendance')) {
			alert('이 웹사이트는 출석체크 기능을 사용하지 않습니다');
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
		$findex = $this->Attendance_model->primary_key;
		$forder = $this->cbconfig->item('attendance_order') === 'desc' ? 'desc' : 'asc';

		$per_page = $this->cbconfig->item('attendance_page_count') ? (int) $this->cbconfig->item('attendance_page_count') : 100;
		$offset = ($page - 1) * $per_page;

		$attendance_date_style = ($this->cbconfig->get_device_view_type() === 'mobile')
			? $this->cbconfig->item('attendance_mobile_date_style')
			: $this->cbconfig->item('attendance_date_style');

		if ( ! $attendance_date_style) {
			$attendance_date_style = 'full';
		}
		$attendance_date_style_manual = ($this->cbconfig->get_device_view_type() === 'mobile')
			? $this->cbconfig->item('attendance_mobile_date_style_manual')
			: $this->cbconfig->item('attendance_date_style_manual');

		$view['view']['attendance_show_attend_time'] = $attendance_show_attend_time
			= ($this->cbconfig->get_device_view_type() === 'mobile')
			? $this->cbconfig->item('attendance_mobile_show_attend_time')
			: $this->cbconfig->item('attendance_show_attend_time');

		/**
		 * 게시판 목록에 필요한 정보를 가져옵니다.
		 */
		if ( ! $date) {
			$date = cdate('Y-m-d');
		}
		if (strlen($date) !== 10) {
			$date = cdate('Y-m-d');
		}
		$arr = explode('-', $date);
		if (checkdate(element(1, $arr), element(2, $arr), element(0, $arr)) === false) {
			$date = cdate('Y-m-d');
		}
		$view['view']['date'] = $date;
		$view['view']['date_format'] = cdate('Y년 m월 d일', strtotime($date));
		$view['view']['lastday'] = cdate('t', strtotime($date));
		$view['view']['ym'] = substr($date,0,7);
		$view['view']['d'] = substr($date,8,2);
		$view['view']['lastmonth'] = cdate('Y-m-d', strtotime(substr($date,0,8) . '01') - 86400);
		$view['view']['nextmonth'] = ($view['view']['ym'] < cdate('Y-m'))
			? cdate('Y-m-d', strtotime(substr($date,0,8).cdate('t', strtotime($date))) + 86400) : '';

		$where = array(
			'att_date' => $date,
		);
		$result = $this->Attendance_model
			->get_attend_list($per_page, $offset, $where, $findex, $forder);

		$list_num = $result['total_rows'] - ($page - 1) * $per_page;

		if (element('list', $result)) {
			foreach (element('list', $result) as $key => $val) {
				$result['list'][$key]['display_name'] = display_username(
					element('mem_userid', $val),
					element('mem_nickname', $val),
					element('mem_icon', $val)
				);
				$result['list'][$key]['display_datetime'] = display_datetime(
					element('att_datetime', $val),
					$attendance_date_style,
					$attendance_date_style_manual
				);
			}
		}
		$view['view']['data'] = $result;

		/**
		 * 페이지네이션을 생성합니다
		 */
		$config['base_url'] = site_url('attendance/dailylist/' . $date) . '?' . $param->replace('page');
		$config['total_rows'] = $result['total_rows'];
		$config['per_page'] = $per_page;

		if ( ! $this->input->get('page')) {
			$_GET['page'] = (string) $page;
		}

		$config['_attributes'] = 'onClick="attendance_page(\'' . $date . '\', $(this).attr(\'data-ci-pagination-page\'));return false;"';
		$config['num_links'] = 5;
		$this->pagination->initialize($config);
		$view['view']['paging'] = $this->pagination->create_links();
		$view['view']['page'] = $page;

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 레이아웃을 정의합니다
		 */
		$page_title = $this->cbconfig->item('site_meta_title_attendance');
		$meta_description = $this->cbconfig->item('site_meta_description_attendance');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_attendance');
		$meta_author = $this->cbconfig->item('site_meta_author_attendance');
		$page_name = $this->cbconfig->item('site_page_name_attendance');

		$layoutconfig = array(
			'path' => 'attendance',
			'skin' => 'list',
			'skin_dir' => $this->cbconfig->item('skin_attendance'),
			'mobile_skin_dir' => $this->cbconfig->item('mobile_skin_attendance'),
			'page_title' => $page_title,
			'meta_description' => $meta_description,
			'meta_keywords' => $meta_keywords,
			'meta_author' => $meta_author,
			'page_name' => $page_name,
		);
		$view['layout'] = $this->managelayout->front($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->view = element('view_skin_file', element('layout', $view));
	}


	public function _check_attendance($memo)
	{
		if ($this->member->is_member() === false) {
			$this->form_validation->set_message(
				'_check_attendance',
				'로그인 후 이용이 가능합니다'
			);
			return false;
		}

		if (cdate('H:i:s') < $this->cbconfig->item('attendance_start_time')
			OR cdate('H:i:s') > $this->cbconfig->item('attendance_end_time')) {
			$this->form_validation->set_message(
				'_check_attendance',
				'지금은 출석 가능 시간이 아닙니다.<br />출석 가능 시간 : '
				. cdate('H시 i분 s초', strtotime($this->cbconfig->item('attendance_start_time'))) . ' ~ '
				. cdate('H시 i분 s초', strtotime($this->cbconfig->item('attendance_end_time')))
			);
			return false;
		}

		$filter = explode(',', trim($this->cbconfig->item('spam_word')));
		for ($i = 0; $i < count($filter); $i++) {
			$str = strtolower($filter[$i]);
			$pos = @strpos(strtolower($memo), $str);
			if ($pos !== false) {
				$this->form_validation->set_message(
					'_check_attendance',
					'출석 한마디에 금지단어 ' . $str . ' 이(가) 포함되어 있습니다.'
				);
				return false;
			}
		}

		$filter = explode(',', trim($this->cbconfig->item('attendance_spam_keyword')));
		for ($i = 0; $i < count($filter); $i++) {
			$str = strtolower($filter[$i]);
			$pos = @strpos(strtolower($memo), $str);
			if ($pos !== false) {
				$this->form_validation->set_message(
					'_check_attendance',
					'출석 한마디에 금지단어 ' . $str . ' 이(가) 포함되어 있습니다.'
				);
				return false;
			}
		}
		$attended = $this->Attendance_model->today_attended();
		if ($attended) {
			$this->form_validation->set_message(
				'_check_attendance',
				'출석체크는 하루에 한번만 가능합니다.'
			);
			return false;
		}

		return true;
	}
}
