<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Like class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 관리자>게시판설정>추천/비추 controller 입니다.
 */
class Like extends CB_Controller
{

	/**
	 * 관리자 페이지 상의 현재 디렉토리입니다
	 * 페이지 이동시 필요한 정보입니다
	 */
	public $pagedir = 'board/like';

	/**
	 * 모델을 로딩합니다
	 */
	protected $models = array('Like', 'Board', 'Post', 'Comment');

	/**
	 * 이 컨트롤러의 메인 모델 이름입니다
	 */
	protected $modelname = 'Like_model';

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
		$eventname = 'event_admin_board_like_index';
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
		$findex = $this->input->get('findex') ? $this->input->get('findex') : $this->{$this->modelname}->primary_key;
		$forder = $this->input->get('forder', null, 'desc');
		$sfield = $this->input->get('sfield', null, '');
		$skeyword = $this->input->get('skeyword', null, '');

		$per_page = admin_listnum();
		$offset = ($page - 1) * $per_page;

		/**
		 * 게시판 목록에 필요한 정보를 가져옵니다.
		 */
		$this->{$this->modelname}->allow_search_field = array('lik_id', 'target_id', 'target_type', 'post_id', 'like.mem_id', 'target_mem_id', 'lik_type', 'lik_datetime', 'lik_ip'); // 검색이 가능한 필드
		$this->{$this->modelname}->search_field_equal = array('lik_id', 'target_id', 'target_type', 'post_id', 'like.mem_id', 'target_mem_id', 'lik_type'); // 검색중 like 가 아닌 = 검색을 하는 필드
		$this->{$this->modelname}->allow_order_field = array('lik_id'); // 정렬이 가능한 필드

		$where = array();
		if ($brdid = (int) $this->input->get('brd_id')) {
			$where['like.brd_id'] = $brdid;
		}
		if ($this->input->get('target_type')) {
			$where['like.target_type'] = $this->input->get('target_type');
		}
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
				$result['list'][$key]['target_member'] = $target_member
					= $this->Member_model->get_by_memid(element('target_mem_id', $val), 'mem_id, mem_userid, mem_nickname, mem_icon');
				$result['list'][$key]['target_display_name'] = display_username(
					element('mem_userid', $target_member),
					element('mem_nickname', $target_member),
					element('mem_icon', $target_member)
				);
				$result['list'][$key]['num'] = $list_num--;

				if (element('target_type', $val) === '1') {
					$result['list'][$key]['target_name'] = '원글';
					$result['list'][$key]['post'] = $post = $this->Post_model->get_one(element('target_id', $val), 'post_id, brd_id, post_title');
					if ($post) {
						$result['list'][$key]['board'] = $board = $this->board->item_all(element('brd_id', $post));
						$result['list'][$key]['origin_content'] = element('post_title', $post);
						$result['list'][$key]['posturl'] = post_url(element('brd_key', $board), element('post_id', $post));
					}
				} elseif (element('target_type', $val) === '2') {
					$result['list'][$key]['target_name'] = '댓글';
					$result['list'][$key]['comment'] = $comment = $this->Comment_model->get_one(element('target_id', $val), 'cmt_id, post_id, cmt_content');
					if ($comment) {
						$result['list'][$key]['post'] = $post = $this->Post_model->get_one(element('post_id', $comment));
						$result['list'][$key]['origin_content'] = cut_str(element('cmt_content', $comment),40);
						if ($post) {
							$result['list'][$key]['board'] = $board = $this->board->item_all(element('brd_id', $post));
							$result['list'][$key]['posturl'] = post_url(element('brd_key', $board), element('post_id', $post)) . '#comment_id=' . element('cmt_id', $comment);
						}
					}
				}
				if (element('lik_type', $val) === '1') {
					$result['list'][$key]['like_or_dislike'] = '<span class="label label-success">추천</span>';
				} elseif (element('lik_type', $val) === '2') {
					$result['list'][$key]['like_or_dislike'] = '<span class="label label-warning">비추</span>';
				}
			}
		}

		$view['view']['data'] = $result;

		$view['view']['boardlist'] = $this->Board_model->get_board_list();

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
		$search_option = array('lik_datetime' => '날짜', 'lik_ip' => 'IP');
		$view['view']['skeyword'] = ($sfield && array_key_exists($sfield, $search_option)) ? $skeyword : '';
		$view['view']['search_option'] = search_option($search_option, $sfield);
		$view['view']['listall_url'] = admin_url($this->pagedir);
		$view['view']['list_delete_url'] = admin_url($this->pagedir . '/listdelete/?' . $param->output());

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
	 * 추천수를 그래프 형식으로 보는 페이지입니다
	 */
	public function graph($export = '')
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_board_like_graph';
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

		$result = $this->{$this->modelname}->get_like_count($datetype, $start_date, $end_date, $brd_id, $orderby);
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

		$view['view']['boardlist'] = $this->Board_model->get_board_list();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		if ($export === 'excel') {

			header('Content-type: application/vnd.ms-excel');
			header('Content-Disposition: attachment; filename=추천_' . cdate('Y_m_d') . '.xls');
			echo $this->load->view('admin/' . ADMIN_SKIN . '/' . $this->pagedir . '/graph_excel', $view, true);

		} else {
			/**
			 * 어드민 레이아웃을 정의합니다
			 */
			$layoutconfig = array('layout' => 'layout', 'skin' => 'graph');
			$view['layout'] = $this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
			$this->data = $view;
			$this->layout = element('layout_skin_file', element('layout', $view));
			$this->view = element('view_skin_file', element('layout', $view));
		}
	}

	/**
	 * 비추천 수를 그래프 형식으로 보는 페이지입니다
	 */
	public function graph_dislike($export = '')
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_board_like_graph_dislike';
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

		$result = $this->{$this->modelname}->get_dislike_count($datetype, $start_date, $end_date, $brd_id, $orderby);
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

		$view['view']['boardlist'] = $this->Board_model->get_board_list();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		if ($export === 'excel') {

			header('Content-type: application/vnd.ms-excel');
			header('Content-Disposition: attachment; filename=비추천_' . cdate('Y_m_d') . '.xls');
			echo $this->load->view('admin/' . ADMIN_SKIN . '/' . $this->pagedir . '/graph_dislike_excel', $view, true);

		} else {
			/**
			 * 어드민 레이아웃을 정의합니다
			 */
			$layoutconfig = array('layout' => 'layout', 'skin' => 'graph_dislike');
			$view['layout'] = $this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
			$this->data = $view;
			$this->layout = element('layout_skin_file', element('layout', $view));
			$this->view = element('view_skin_file', element('layout', $view));
		}
	}

	/**
	 * 목록 페이지에서 선택삭제를 하는 경우 실행되는 메소드입니다
	 */
	public function listdelete()
	{

		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_board_like_listdelete';
		$this->load->event($eventname);

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		/**
		 * 체크한 게시물의 삭제를 실행합니다
		 */
		if ($this->input->post('chk') && is_array($this->input->post('chk'))) {
			foreach ($this->input->post('chk') as $val) {
				if ($val) {
					$getdata = $this->{$this->modelname}->get_one($val);

					$this->{$this->modelname}->delete($val);

					$where = array(
						'target_id' => element('target_id', $getdata),
						'target_type' => element('target_type', $getdata),
						'lik_type' => element('lik_type', $getdata),
					);
					$like_cnt = $this->{$this->modelname}->count_by($where);

					if (element('target_type', $getdata) === '1') { // 원글 추천의 경우
						if (element('lik_type', $getdata) === '1') {
							$updatedata = array(
								'post_like' => $like_cnt,
							);
							$this->Post_model->update(element('target_id', $getdata), $updatedata);
						} elseif (element('lik_type', $getdata) === '2') {
							$updatedata = array(
								'post_dislike' => $like_cnt,
							);
							$this->Post_model->update(element('target_id', $getdata), $updatedata);
						}
					} elseif (element('target_type', $getdata) === '2') { // 댓글 추천의 경우
						if (element('lik_type', $getdata) === '1') {
							$updatedata = array(
								'cmt_like' => $like_cnt,
							);
							$this->Comment_model->update(element('target_id', $getdata), $updatedata);
						} elseif (element('lik_type', $getdata) === '2') {
							$updatedata = array(
								'cmt_dislike' => $like_cnt,
							);
							$this->Comment_model->update(element('target_id', $getdata), $updatedata);
						}
					}
				}
			}
		}

		// 이벤트가 존재하면 실행합니다
		Events::trigger('after', $eventname);

		/**
		 * 삭제가 끝난 후 목록페이지로 이동합니다
		 */
		$this->session->set_flashdata(
			'message',
			'정상적으로 삭제되었습니다'
		);
		$param =& $this->querystring;
		$redirecturl = admin_url($this->pagedir . '?' . $param->output());

		redirect($redirecturl);
	}
}
