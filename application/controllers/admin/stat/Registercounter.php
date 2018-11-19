<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Registercounter class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 관리자>통계관리>회원가입통계 controller 입니다.
 */
class Registercounter extends CB_Controller
{

	/**
	 * 관리자 페이지 상의 현재 디렉토리입니다
	 * 페이지 이동시 필요한 정보입니다
	 */
	public $pagedir = 'stat/registercounter';

	/**
	 * 모델을 로딩합니다
	 */
	protected $models = array();

	/**
	 * 이 컨트롤러의 메인 모델 이름입니다
	 */
	protected $modelname = 'Member_model';

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
		$eventname = 'event_admin_stat_registercounter_index';
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

		$result = $this->{$this->modelname}->get_register_count($datetype, $start_date, $end_date, $orderby);
		$sum_count = 0;
		$arr = array();
		$max = 0;

		if ($result && is_array($result)) {
			foreach ($result as $key => $value) {
				$s = element('day', $value);
				if ( ! isset($arr[$s])) {
					$arr[$s] = 0;
				}
				$arr[$s] += element('cnt', $value);

				if ($arr[$s] > $max) {
					$max = $arr[$s];
				}
				$sum_count += element('cnt', $value);
			}
		}

		$result = array();
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
				$result[$key]['no'] = $no;

				$result[$key]['key'] = $key;
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

		if ($datetype === 'y') {
			for ($i = $start_year; $i <= $end_year; $i++) {
				if( ! isset($result[$i])) $result[$i] = '';
			}
		} elseif ($datetype === 'm') {
			for ($i = $start_year_month; $i <= $end_year_month; $i++) {
				$year = floor($i / 12);
				if ($year * 12 == $i) $year--;
				$month = sprintf("%02d", ($i - ($year * 12)));
				$date = $year . '-' . $month;
				if( ! isset($result[$date])) $result[$date] = '';
			}
		} elseif ($datetype === 'd') {
			$date = $start_date;
			while ($date <= $end_date) {
				if( ! isset($result[$date])) $result[$date] = '';
				$date = cdate('Y-m-d', strtotime($date) + 86400);
			}
		}

		if ($orderby === 'desc') {
			krsort($result);
		} else {
			ksort($result);
		}

		$view['view']['list'] = $result;

		$view['view']['start_date'] = $start_date;
		$view['view']['end_date'] = $end_date;
		$view['view']['datetype'] = $datetype;

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		if ($export === 'excel') {

			header('Content-type: application/vnd.ms-excel');
			header('Content-Disposition: attachment; filename=회원가입통계_' . cdate('Y_m_d') . '.xls');
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
}
