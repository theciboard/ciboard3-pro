<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Point class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 관리자>회원설정>포인트관리 controller 입니다.
 */
class Points extends CB_Controller
{

	/**
	 * 관리자 페이지 상의 현재 디렉토리입니다
	 * 페이지 이동시 필요한 정보입니다
	 */
	public $pagedir = 'member/points';

	/**
	 * 모델을 로딩합니다
	 */
	protected $models = array('Point');

	/**
	 * 이 컨트롤러의 메인 모델 이름입니다
	 */
	protected $modelname = 'Point_model';

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
		$this->load->library(array('pagination', 'querystring', 'point'));
	}

	/**
	 * 목록을 가져오는 메소드입니다
	 */
	public function index()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_member_points_index';
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
		$this->{$this->modelname}->allow_search_field = array('poi_id', 'point.mem_id', 'poi_datetime', 'poi_content', 'poi_type', 'poi_action'); // 검색이 가능한 필드
		$this->{$this->modelname}->search_field_equal = array('poi_id', 'point.mem_id'); // 검색중 like 가 아닌 = 검색을 하는 필드
		$this->{$this->modelname}->allow_order_field = array('poi_id'); // 정렬이 가능한 필드
		$result = $this->{$this->modelname}
			->get_admin_list($per_page, $offset, '', '', $findex, $forder, $sfield, $skeyword);
		$list_num = $result['total_rows'] - ($page - 1) * $per_page;
		if (element('list', $result)) {
			foreach (element('list', $result) as $key => $val) {
				$result['list'][$key]['display_name'] = display_username(
					element('mem_userid', $val),
					element('mem_nickname', $val),
					element('mem_icon', $val)
				);
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
		$search_option = array('poi_datetime' => '날짜', 'poi_content' => '내용');
		$view['view']['skeyword'] = ($sfield && array_key_exists($sfield, $search_option)) ? $skeyword : '';
		$view['view']['search_option'] = search_option($search_option, $sfield);
		$view['view']['listall_url'] = admin_url($this->pagedir);
		$view['view']['write_url'] = admin_url($this->pagedir . '/write');
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
	 * 게시판 글쓰기 또는 수정 페이지를 가져오는 메소드입니다
	 */
	public function write()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_member_points_write';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		$primary_key = $this->{$this->modelname}->primary_key;

		/**
		 * Validation 라이브러리를 가져옵니다
		 */
		$this->load->library('form_validation');

		/**
		 * 전송된 데이터의 유효성을 체크합니다
		 */
		$config = array();
		if ($this->input->post('poi_type') === 'toall') {
			$config = array(
				array(
					'field' => 'poi_point_all',
					'label' => '포인트',
					'rules' => 'trim|required|numeric',
				),
				array(
					'field' => 'poi_content_all',
					'label' => '내용',
					'rules' => 'trim|required',
				),
			);
		} elseif ($this->input->post('poi_type') === 'toone') {
			$config = array(
				array(
					'field' => 'mem_userid',
					'label' => '회원아이디',
					'rules' => 'trim|required|callback__real_userid',
				),
				array(
					'field' => 'poi_point',
					'label' => '포인트',
					'rules' => 'trim|required|numeric',
				),
				array(
					'field' => 'poi_content',
					'label' => '내용',
					'rules' => 'trim|required',
				),
			);
		}
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

			// 이벤트가 존재하면 실행합니다
			$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

			/**
			 * 어드민 레이아웃을 정의합니다
			 */
			$layoutconfig = array('layout' => 'layout', 'skin' => 'write');
			$view['layout'] = $this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
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

			if ($this->input->post('poi_type') === 'toall') {
				$where = array(
					'mem_denied' => 0,
				);
				$mball = $this->Member_model->get('', 'mem_id', $where);
				if ($mball && is_array($mball)) {
					foreach ($mball as $mb) {
						if (element('mem_id', $mb)) {
							$this->point->insert_point(
								element('mem_id', $mb),
								$this->input->post('poi_point_all'),
								$this->input->post('poi_content_all'),
								'@byadmin',
								element('mem_id', $mb),
								$this->member->item('mem_id') . '-' . uniqid('')
							);
						}
					}
				}
			} elseif ($this->input->post('poi_type') === 'toone') {
				$mb = $this->Member_model->get_by_userid($this->input->post('mem_userid'), 'mem_id');
				if (element('mem_id', $mb)) {
					$this->point->insert_point(
						element('mem_id', $mb),
						$this->input->post('poi_point'),
						$this->input->post('poi_content'),
						'@byadmin',
						element('mem_id', $mb),
						$this->member->item('mem_id') . '-' . uniqid('')
					);
				}
			}

			$this->session->set_flashdata(
				'message',
				'정상적으로 입력되었습니다'
			);

			// 이벤트가 존재하면 실행합니다
			Events::trigger('after', $eventname);

			/**
			 * 게시물의 신규입력 또는 수정작업이 끝난 후 목록 페이지로 이동합니다
			 */
			$param =& $this->querystring;
			$redirecturl = admin_url($this->pagedir . '?' . $param->output());
			redirect($redirecturl);
		}
	}

	/**
	 * 목록 페이지에서 선택삭제를 하는 경우 실행되는 메소드입니다
	 */
	public function listdelete()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_member_points_listdelete';
		$this->load->event($eventname);

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		/**
		 * 체크한 게시물의 삭제를 실행합니다
		 */
		$this->load->library('Point');
		if ($this->input->post('chk') && is_array($this->input->post('chk'))) {
			foreach ($this->input->post('chk') as $val) {
				if ($val) {
					$this->point->delete_point_by_pk($val);
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

	/**
	 * 실제로 존재하는 회원인지를 체크합니다
	 */
	public function _real_userid($str)
	{
		$mb = $this->Member_model->get_by_userid($str, 'mem_id, mem_denied');
		if ( ! element('mem_id', $mb)) {
			$this->form_validation->set_message(
				'_real_userid',
				$str . ' 은 존재하지 않는 회원아이디입니다'
			);
			return false;
		}
		if (element('mem_denied', $mb)) {
			$this->form_validation->set_message(
				'_real_userid',
				$str . ' 은 탈퇴, 차단된 회원아이디입니다'
			);
			return false;
		}
		return true;
	}

	/**
	 * 오래된 포인트로그 정리 페이지입니다
	 */
	public function cleanlog()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_member_point_cleanlog';
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
				$criterion = $this->input->post('criterion');
				$result = $this->Point_model->member_list_by_point_count(10, $criterion);
				$mem_count = 0;
				$poi_count = 0;
				if ($result) {
					foreach ($result as $value) {
						$mem_count++;
						$poi_count += element('cnt', $value);
						$where = array(
							'mem_id' => element('mem_id', $value),
							'poi_datetime <=' => $criterion,
						);
						$this->Point_model->delete_where($where);

						$this->point->insert_point(
							element('mem_id', $value),
							element('sum_point', $value),
							$criterion . ' 까지의 포인트 ' . number_format(element('cnt', $value)) . '건 정리',
							'@clean',
							element('mem_id', $value),
							element('mem_id', $value) . '-' . uniqid('')
						);

					}
				}
				$view['view']['alert_message'] = '총 ' . number_format($mem_count) . ' 명의 포인트 ' . number_format($poi_count) . ' 건이 정리되었습니다';
			} else {
				$criterion = cdate('Y-m-d H:i:s', ctimestamp() - $this->input->post('day') * 24 * 60 * 60);
				$view['view']['criterion'] = $criterion;
				$view['view']['day'] = $this->input->post('day');
				$result = $this->Point_model->member_count_by_point_count(1, $criterion);
				$member_count = count($result);
				$point_sum = 0;
				if ($result) {
					foreach ($result as $k => $v) {
						$point_sum += element('cnt', $v);
					}
				}
				if ($member_count > 0) {
					$view['view']['msg'] = '총 ' . number_format($member_count) . ' 명의 회원, ' . number_format($point_sum) . ' 건의 ' . $this->input->post('day') . '일 이상된 포인트 데이터가 발견되었습니다.<br />';
					if ($member_count > 100) {
						$view['view']['msg'] .= '이중 100명 회원의 포인트를 정리하시겠습니까?';
					} else {
						$view['view']['msg'] .= '이를 정리하시겠습니까?';
					}
				} else {
					$view['view']['alert_message'] = $this->input->post('day') . '일 이상된 포인트 데이터가 발견되지 않았습니다';
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
