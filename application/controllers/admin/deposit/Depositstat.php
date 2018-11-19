<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Depositstat class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 관리자>예치금>예치금통계 controller 입니다.
 */
class Depositstat extends CB_Controller
{

	/**
	 * 관리자 페이지 상의 현재 디렉토리입니다
	 * 페이지 이동시 필요한 정보입니다
	 */
	public $pagedir = 'deposit/depositstat';

	/**
	 * 모델을 로딩합니다
	 */
	protected $models = array('Deposit');

	/**
	 * 이 컨트롤러의 메인 모델 이름입니다
	 */
	protected $modelname = 'Deposit_model';

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
		$this->load->library(array('pagination', 'querystring', 'depositlib'));
	}

	/**
	 * 충전통계
	 */
	public function index()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_deposit_depositstat_index';
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

		$where = array(
			'dep_to_type' => 'deposit',
			'dep_status' => 1,
		);
		if ($this->input->get('dep_from_type') === 'cash' OR $this->input->get('dep_from_type') === 'point') {
			$where['dep_from_type'] = $this->input->get('dep_from_type');
		}

		if ($this->input->get('method') === 'bank'
			OR $this->input->get('method') === 'card'
			OR $this->input->get('method') === 'realtime'
			OR $this->input->get('method') === 'phone'
			OR $this->input->get('method') === 'vbank'
			OR $this->input->get('method') === 'service') {
			$where['dep_pay_type'] = $this->input->get('method');
		}

		$result = $this->{$this->modelname}->get_graph_count($datetype, $start_date, $end_date, $where);

		$sum_deposit = 0;
		$sum_cash = 0;
		$sum_point = 0;
		$arr = array();
		$arr2 = array();
		$arr3 = array();
		$_day = array();
		$max = 0;
		if ($result && is_array($result)) {
			foreach ($result as $key => $value) {
				$s = element('day', $value);
				if ( ! isset($arr[$s])) {
					$arr[$s] = 0;
				}
				if ( ! isset($arr2[$s])) {
					$arr2[$s] = 0;
				}
				if ( ! isset($arr3[$s])) {
					$arr3[$s] = 0;
				}
				$arr[$s] += abs(element('dep_deposit', $value));
				$arr2[$s] += abs(element('dep_cash', $value));
				$arr3[$s] += abs(element('dep_point', $value));
				if ( ! isset($_day[$s])) {
					$_day[$s] = element('day', $value);
				}
				if (element('dep_deposit', $value) > $max) {
					$max = element('dep_deposit', $value);
				}
				$sum_deposit += abs(element('dep_deposit', $value));
				$sum_cash += abs(element('dep_cash', $value));
				$sum_point += abs(element('dep_point', $value));
			}
		}

		$view['view']['list'] = array();
		$k = 0;

		if (count($arr)) {
			foreach ($arr as $key => $value) {
				$deposit = $arr[$key];
				$view['view']['list'][$k]['deposit'] = $deposit;
				$cash = $arr2[$key];
				$view['view']['list'][$k]['cash'] = $cash;
				$point = $arr3[$key];
				$view['view']['list'][$k]['point'] = $point;
				$view['view']['list'][$k]['day'] = $_day[$key];
				$rate = ($deposit / $sum_deposit * 100);
				$view['view']['list'][$k]['rate'] = $rate;
				$s_rate = number_format($rate, 1);
				$view['view']['list'][$k]['s_rate'] = $s_rate;

				$bar = (int)($deposit / $max * 100);
				$view['view']['list'][$k]['bar'] = $bar;
				$k++;
			}

			$view['view']['max_value'] = $max;
			$view['view']['sum_deposit'] = $sum_deposit;
			$view['view']['sum_cash'] = $sum_cash;
			$view['view']['sum_point'] = $sum_point;
		}

		$view['view']['start_date'] = $start_date;
		$view['view']['end_date'] = $end_date;

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

	/**
	 * 사용통계
	 */
	public function usestat()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_deposit_depositstat_usestat';
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

		$where = array(
			'dep_from_type' => 'deposit',
			'dep_status' => 1,
		);
		if ($this->input->get('dep_to_type') === 'contents' OR $this->input->get('dep_to_type') === 'point') {
			$where['dep_to_type'] = $this->input->get('dep_to_type');
		}
		$result = $this->{$this->modelname}->get_graph_count($datetype, $start_date, $end_date, $where);

		$sum_deposit = 0;
		$sum_cash = 0;
		$sum_point = 0;
		$arr = array();
		$arr2 = array();
		$arr3 = array();
		$_day = array();
		$max = 0;
		if ($result && is_array($result)) {
			foreach ($result as $key => $value) {
				$s = element('day', $value);
				if ( ! isset($arr[$s])) {
					$arr[$s] = 0;
				}
				if ( ! isset($arr2[$s])) {
					$arr2[$s] = 0;
				}
				if ( ! isset($arr3[$s])) {
					$arr3[$s] = 0;
				}
				$arr[$s] += abs(element('dep_deposit', $value));
				$arr2[$s] += abs(element('dep_cash', $value));
				$arr3[$s] += abs(element('dep_point', $value));
				if ( ! isset($_day[$s])) {
					$_day[$s] = element('day', $value);
				}
				if (abs(element('dep_deposit', $value)) > $max) {
					$max = abs(element('dep_deposit', $value));
				}
				$sum_deposit += abs(element('dep_deposit', $value));
				$sum_cash += abs(element('dep_cash', $value));
				$sum_point += abs(element('dep_point', $value));
			}
		}

		$view['view']['list'] = array();
		$k = 0;

		if (count($arr)) {
			foreach ($arr as $key => $value) {
				$deposit = $arr[$key];
				$view['view']['list'][$k]['deposit'] = $deposit;
				$cash = $arr2[$key];
				$view['view']['list'][$k]['cash'] = $cash;
				$point = $arr3[$key];
				$view['view']['list'][$k]['point'] = $point;
				$view['view']['list'][$k]['day'] = $_day[$key];
				$rate = ($deposit / $sum_deposit * 100);
				$view['view']['list'][$k]['rate'] = $rate;
				$s_rate = number_format($rate, 1);
				$view['view']['list'][$k]['s_rate'] = $s_rate;

				$bar = (int)($deposit / $max * 100);
				$view['view']['list'][$k]['bar'] = $bar;
				$k++;
			}
			$view['view']['max_value'] = $max;
			$view['view']['sum_deposit'] = $sum_deposit;
			$view['view']['sum_cash'] = $sum_cash;
			$view['view']['sum_point'] = $sum_point;
		}

		$view['view']['start_date'] = $start_date;
		$view['view']['end_date'] = $end_date;

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 어드민 레이아웃을 정의합니다
		 */
		$layoutconfig = array('layout' => 'layout', 'skin' => 'usestat');
		$view['layout'] = $this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}

	/**
	 * 회원별 구매회수
	 */
	public function memberstat()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_deposit_depositstat_memberstat';
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

		$where = array(
			'dep_deposit >' => 0,
			'dep_status' => 1,
		);
		$orderby = ($this->input->get('orderby') === 'deposit') ? 'dep_deposit DESC' : 'cnt DESC';
		$result = $this->{$this->modelname}->get_graph_paycount($datetype, $start_date, $end_date, $where, $orderby);

		$arr = array();
		$arr2 = array();
		$max = 0;
		$sum_deposit = 0;
		$sum_count = 0;

		if ($result && is_array($result)) {
			foreach ($result as $key => $value) {
				$s = element('mem_id', $value);
				$arr[$s] = element('cnt', $value);
				$arr2[$s] = element('dep_deposit', $value);
				$compare = ($this->input->get('orderby') === 'deposit') ? $arr2[$s] : $arr[$s];
				if ($compare > $max) {
					$max = $compare;
				}
				$sum_count+= element('cnt', $value);
				$sum_deposit+= element('dep_deposit', $value);
			}
		}

		$view['view']['list'] = array();
		$k = 0;
		$i = 0;
		$save_count = -1;

		$sum_cnt = 0;
		if (count($arr)) {
			foreach ($arr as $key => $value) {
				$select = 'mem_id, mem_userid, mem_nickname, mem_icon';
				$view['view']['list'][$k]['member'] = $dbmember = $this->Member_model->get_by_memid($key, $select);
				$view['view']['list'][$k]['display_name'] = display_username(
					element('mem_userid', $dbmember),
					element('mem_nickname', $dbmember),
					element('mem_icon', $dbmember)
				);
				$view['view']['list'][$k]['cnt'] = $value;
				$view['view']['list'][$k]['deposit'] = $arr2[$key];
				$compare = ($this->input->get('orderby') === 'deposit') ? (int) $arr2[$key] : (int) $value;
				$compare2 = ($this->input->get('orderby') === 'deposit') ? $sum_deposit : $sum_count;
				$rate = ($compare / $compare2 * 100);
				$view['view']['list'][$k]['rate'] = $rate;
				$s_rate = number_format($rate, 1);
				$view['view']['list'][$k]['s_rate'] = $s_rate;

				$i++;
				if ($save_count !== $compare) {
					$no = $i;
					$save_count = $compare;
				}
				$view['view']['list'][$k]['no'] = $no;

				$bar = (int)($compare / $max * 100);
				$view['view']['list'][$k]['bar'] = $bar;
				$sum_cnt += $value;
				$k++;
			}
			$view['view']['sum_cnt'] = $sum_cnt;
			$view['view']['sum_deposit'] = $sum_deposit;
		}

		$view['view']['start_date'] = $start_date;
		$view['view']['end_date'] = $end_date;

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 어드민 레이아웃을 정의합니다
		 */
		$layoutconfig = array('layout' => 'layout', 'skin' => 'memberstat');
		$view['layout'] = $this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}

	/**
	 * 결제회수별 회원수
	 */
	public function paynumstat()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_deposit_depositstat_paynumstat';
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

		$where = array(
			'dep_deposit >' => 0,
			'dep_status' => 1,
		);
		$result = $this->{$this->modelname}->get_graph_paycount($datetype, $start_date, $end_date, $where);

		$arr = array();
		$arr2 = array();
		$max = 0;
		$sum_member = 0;

		if ($result && is_array($result)) {
			foreach ($result as $key => $value) {
				$s = element('cnt', $value);
				if ( ! isset($arr[$s])) {
					$arr[$s] = 0;
				}

				$arr[$s]++;
				if ($arr[$s] > $max) {
					$max = $arr[$s];
				}
				$sum_member++;
			}
		}

		$view['view']['list'] = array();
		$k = 0;
		$i = 0;
		$save_count = -1;

		$sum_cnt = 0;
		if (count($arr)) {
			foreach ($arr as $key => $value) {
				$key = (int) $key;
				$view['view']['list'][$k]['cnt'] = $key;
				$view['view']['list'][$k]['member'] = $value;
				$rate = ($value / $sum_member * 100);
				$view['view']['list'][$k]['rate'] = $rate;
				$s_rate = number_format($rate, 1);
				$view['view']['list'][$k]['s_rate'] = $s_rate;

				$i++;
				if ($save_count !== $key) {
					$no = $i;
					$save_count = $key;
				}
				$view['view']['list'][$k]['no'] = $no;

				$bar = (int)($value / $max * 100);
				$view['view']['list'][$k]['bar'] = $bar;
				$sum_cnt += $key;
				$k++;
			}

			$view['view']['sum_cnt'] = $sum_cnt;
			$view['view']['sum_member'] = $sum_member;
		}

		$view['view']['start_date'] = $start_date;
		$view['view']['end_date'] = $end_date;

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 어드민 레이아웃을 정의합니다
		 */
		$layoutconfig = array('layout' => 'layout', 'skin' => 'paynumstat');
		$view['layout'] = $this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}
}
