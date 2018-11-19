<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Boardcounter class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 관리자>통계관리>게시판별접속자 controller 입니다.
 */
class Boardcounter extends CB_Controller
{

	/**
	 * 관리자 페이지 상의 현재 디렉토리입니다
	 * 페이지 이동시 필요한 정보입니다
	 */
	public $pagedir = 'stat/boardcounter';

	/**
	 * 모델을 로딩합니다
	 */
	protected $models = array('Stat_count_board', 'Board');

	/**
	 * 이 컨트롤러의 메인 모델 이름입니다
	 */
	protected $modelname = 'Stat_count_board_model';

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
	 * 목록을 가져오는 메소드입니다
	 */
	public function index($export = '')
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_stat_boardcounter_index';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		$param =& $this->querystring;
		$datetype = $this->input->get('datetype', null, 'd');
		if ($datetype !== 'm' && $datetype !== 'y') {
			$datetype = 'd';
		}
		$start_date = $this->input->get('start_date') ? $this->input->get('start_date') : cdate('Y-m-01');
		$end_date = $this->input->get('end_date') ? $this->input->get('end_date') : cdate('Y-m-d');
		if ($datetype === 'y' OR $datetype === 'm') {
			$start_year = substr($start_date, 0, 4);
			$end_year = substr($end_date, 0, 4);
		}
		if ($datetype === 'm') {
			$start_month = substr($start_date, 5, 2);
			$end_month = substr($end_date, 5, 2);
			$start_year_month = $start_year * 12 + $start_month;
			$end_year_month = $end_year * 12 + $end_month;
		}

		$orderby = (strtolower($this->input->get('orderby')) === 'desc') ? 'desc' : 'asc';

		$brd_id = $this->input->get('brd_id', null, '');

		$result = $this->{$this->modelname}->get_board_count($datetype, $start_date, $end_date, $brd_id, $orderby);

		$sum_count = 0;
		$arr = array();
		$_day = array();
		$_brd = array();
		$max = 0;
		if ($result && is_array($result)) {
			foreach ($result as $key => $value) {
				$s = element('day', $value) . '_' . element('brd_id', $value);
				if ( ! isset($arr[$s])) {
					$arr[$s] = 0;
				}
				$arr[$s] += element('scb_count', $value);
				if ( ! isset($_day[$s])) {
					$_day[$s] = element('day', $value);
				}
				if ( ! isset($_brd[$s])) {
					$brdresult = $this->board->item_id('brd_name', element('brd_id', $value));
					$_brd[$s] = $brdresult ? $brdresult : '-';
				}
				if (element('scb_count', $value) > $max) {
					$max = element('scb_count', $value);
				}
				$sum_count += element('scb_count', $value);
			}
		}

		$result = array();
		$result2 = array();
		$i = 0;
		$save_count = -1;
		$tot_count = 0;

		if (count($arr)) {
			foreach ($arr as $key => $value) {
				$count = (int) $arr[$key];
				$result[$key]['count'] = $count;
				$i++;
				if ($save_count !== $count) {
					$no = $i;
					$save_count = $count;
				}
				$result[$key]['day'] = $_day[$key];
				$result[$key]['boardname'] = $_brd[$key];
				$rate = ($count / $sum_count * 100);
				$result[$key]['rate'] = $rate;
				$s_rate = number_format($rate, 1);
				$result[$key]['s_rate'] = $s_rate;

				$bar = (int)($count / $max * 100);
				$result[$key]['bar'] = $bar;
			}
			$view['view']['max_value'] = $max;
			$view['view']['sum_count'] = $sum_count;
		}

		$view['view']['boardlist'] = $boardlist = $this->Board_model->get_board_list();

		$result2 = array();

		if ($datetype === 'y') {
			for ($i = $start_year; $i <= $end_year; $i++) {
				if ($boardlist) {
					foreach ($boardlist as $bkey => $bval) {
						if( ! isset($result[$i . '_' . element('brd_id', $bval)])) $result[$i . '_' . element('brd_id', $bval)] = '';
					}
				}
				if( ! isset($result2[$i])) $result2[$i] = '1';
			}
		} elseif ($datetype === 'm') {
			for ($i = $start_year_month; $i <= $end_year_month; $i++) {
				$year = floor($i / 12);
				if ($year * 12 == $i) $year--;
				$month = sprintf("%02d", ($i - ($year * 12)));
				$date = $year . '-' . $month;
				if ($boardlist) {
					foreach ($boardlist as $bkey => $bval) {
						if( ! isset($result[$date . '_' . element('brd_id', $bval)])) $result[$date . '_' . element('brd_id', $bval)] = '';
					}
				}
				if( ! isset($result2[$date])) $result2[$date] = '1';
			}
		} elseif ($datetype === 'd') {
			$date = $start_date;
			while ($date <= $end_date) {
				if ($boardlist) {
					foreach ($boardlist as $bkey => $bval) {
						if( ! isset($result[$date . '_' . element('brd_id', $bval)])) $result[$date . '_' . element('brd_id', $bval)] = '';
					}
				}
				if( ! isset($result2[$date])) $result2[$date] = '1';
				$date = cdate('Y-m-d', strtotime($date) + 86400);
			}
		}

		if ($orderby === 'desc') {
			krsort($result);
		} else {
			ksort($result);
		}

		$view['view']['list'] = $result;
		$view['view']['list_date'] = $result2;

		$view['view']['start_date'] = $start_date;
		$view['view']['end_date'] = $end_date;

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		if ($export === 'excel') {

			header('Content-type: application/vnd.ms-excel');
			header('Content-Disposition: attachment; filename=게시판별접속자_' . cdate('Y_m_d') . '.xls');
			echo $this->load->view('admin/' . ADMIN_SKIN . '/' . $this->pagedir . '/index_excel', $view, true);

		} else {
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

	/**
	 * 오래된 링크클릭로그삭제 페이지입니다
	 */
	public function cleanlog()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_stat_boardcounter_cleanlog';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		/**
		 * Validation 라이브러리를 가져옵니다
		 */
		$this->load->library('form_validation');

		/**
		 * 전송된 데이터의 유효성을 체크합니다
		 */
		$config = array(
			array(
				'field' => 'day',
				'label' => '기간',
				'rules' => 'trim|required|numeric|is_natural',
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

		} else {
			/**
			 * 유효성 검사를 통과한 경우입니다.
			 * 즉 데이터의 insert 나 update 의 process 처리가 필요한 상황입니다
			 */

			// 이벤트가 존재하면 실행합니다
			$view['view']['event']['formruntrue'] = Events::trigger('formruntrue', $eventname);

			if ($this->input->post('criterion') && $this->input->post('day')) {
				$deletewhere = array(
					'scb_date <=' => $this->input->post('criterion'),
				);
				$this->Stat_count_board_model->delete_where($deletewhere);
				$view['view']['alert_message'] = '총 ' . number_format($this->input->post('log_count')) . ' 건의 '
					. $this->input->post('day') . '일 이상된 게시판별접속자로그가 모두 삭제되었습니다';
			} else {
				$criterion = cdate('Y-m-d', ctimestamp() - $this->input->post('day') * 24 * 60 * 60);
				$countwhere = array(
					'scb_date <=' => $criterion,
				);
				$log_count = $this->Stat_count_board_model->count_by($countwhere);
				$view['view']['criterion'] = $criterion;
				$view['view']['day'] = $this->input->post('day');
				$view['view']['log_count'] = $log_count;
				if ($log_count > 0) {
					$view['view']['msg'] = '총 ' . number_format($log_count) . ' 건의 ' . $this->input->post('day')
						. '일 이상된 게시판별접속자로그가 발견되었습니다. 이를 모두 삭제하시겠습니까?';
				} else {
					$view['view']['alert_message'] = $this->input->post('day') . '일 이상된 게시판별접속자로그가 발견되지 않았습니다';
				}
			}
		}

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 어드민 레이아웃을 정의합니다
		 */
		$layoutconfig = array('layout' => 'layout', 'skin' => 'cleanlog');
		$view['layout'] = $this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}
}
