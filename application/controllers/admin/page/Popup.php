<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Popup class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 관리자>페이지설정>팝업관리 controller 입니다.
 */
class Popup extends CB_Controller
{

	/**
	 * 관리자 페이지 상의 현재 디렉토리입니다
	 * 페이지 이동시 필요한 정보입니다
	 */
	public $pagedir = 'page/popup';

	/**
	 * 모델을 로딩합니다
	 */
	protected $models = array('Popup');

	/**
	 * 이 컨트롤러의 메인 모델 이름입니다
	 */
	protected $modelname = 'Popup_model';

	/**
	 * 헬퍼를 로딩합니다
	 */
	protected $helpers = array('form', 'array', 'dhtml_editor');

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
		$eventname = 'event_admin_page_popup_index';
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
			'pop_title' => $param->sort('pop_title', 'asc'),
			'pop_device' => $param->sort('pop_device', 'asc'),
			'pop_start_date' => $param->sort('pop_start_date', 'asc'),
			'pop_end_date' => $param->sort('pop_end_date', 'asc'),
			'pop_activated' => $param->sort('pop_activated', 'asc'),
		);
		$findex = $this->input->get('findex') ? $this->input->get('findex') : $this->{$this->modelname}->primary_key;
		$forder = $this->input->get('forder', null, 'desc');
		$sfield = $this->input->get('sfield', null, '');
		$skeyword = $this->input->get('skeyword', null, '');

		$per_page = admin_listnum();
		$offset = ($page - 1) * $per_page;

		/**
		 * 게시판 목록에 필요한 정보를 가져옵니다.
		 */
		$this->{$this->modelname}->allow_search_field = array('pop_id', 'pop_title', 'pop_content'); // 검색이 가능한 필드
		$this->{$this->modelname}->search_field_equal = array('pop_id'); // 검색중 like 가 아닌 = 검색을 하는 필드
		$this->{$this->modelname}->allow_order_field = array('pop_title', 'pop_device', 'pop_start_date', 'pop_end_date', 'pop_activated'); // 정렬이 가능한 필드

		$where = array();
		if ($this->input->get('pop_activated') === 'Y') {
			$where['pop_activated'] = '1';
		}
		if ($this->input->get('pop_activated') === 'N') {
			$where['pop_activated'] = '0';
		}

		$result = $this->{$this->modelname}
			->get_admin_list($per_page, $offset, $where, '', $findex, $forder, $sfield, $skeyword);
		$list_num = $result['total_rows'] - ($page - 1) * $per_page;
		if (element('list', $result)) {
			foreach (element('list', $result) as $key => $val) {
				if (empty($val['pop_start_date']) OR $val['pop_start_date'] === '0000-00-00') {
					$result['list'][$key]['pop_start_date'] = '미지정';
				}
				if (empty($val['pop_end_date']) OR $val['pop_end_date'] === '0000-00-00') {
					$result['list'][$key]['pop_end_date'] = '미지정';
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
		$search_option = array('pop_title' => '제목', 'pop_content' => '내용');
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
	public function write($pid = 0)
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_page_popup_write';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		/**
		 * 프라이머리키에 숫자형이 입력되지 않으면 에러처리합니다
		 */
		if ($pid) {
			$pid = (int) $pid;
			if (empty($pid) OR $pid < 1) {
				show_404();
			}
		}
		$primary_key = $this->{$this->modelname}->primary_key;

		/**
		 * 수정 페이지일 경우 기존 데이터를 가져옵니다
		 */
		$getdata = array();
		if ($pid) {
			$getdata = $this->{$this->modelname}->get_one($pid);
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
				'field' => 'pop_title',
				'label' => '팝업명',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'pop_start_date',
				'label' => '팝업시작일',
				'rules' => 'trim|alpha_dash|exact_length[10]',
			),
			array(
				'field' => 'pop_end_date',
				'label' => '팝업종료일',
				'rules' => 'trim|alpha_dash|exact_length[10]',
			),
			array(
				'field' => 'pop_is_center',
				'label' => '팝업정렬',
				'rules' => 'trim|required|numeric',
			),
			array(
				'field' => 'pop_left',
				'label' => '좌측위치',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'pop_top',
				'label' => '상단위치',
				'rules' => 'trim|required|numeric',
			),
			array(
				'field' => 'pop_width',
				'label' => '팝업가로길이',
				'rules' => 'trim|required|numeric',
			),
			array(
				'field' => 'pop_height',
				'label' => '팝업세로길이',
				'rules' => 'trim|required|numeric',
			),
			array(
				'field' => 'pop_device',
				'label' => '팝업표시기기',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'pop_page',
				'label' => '팝업이뜨는페이지',
				'rules' => 'trim|required|numeric',
			),
			array(
				'field' => 'pop_disable_hours',
				'label' => '시간',
				'rules' => 'trim|required|numeric',
			),
			array(
				'field' => 'pop_activated',
				'label' => '팝업활성화',
				'rules' => 'trim|required|numeric',
			),
			array(
				'field' => 'pop_content',
				'label' => '팝업내용',
				'rules' => 'trim|required',
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

			if ($pid) {
				if (empty($getdata['pop_start_date']) OR $getdata['pop_start_date'] === '0000-00-00') {
					$getdata['pop_start_date'] = '';
				}
				if (empty($getdata['pop_end_date']) OR $getdata['pop_end_date'] === '0000-00-00') {
					$getdata['pop_end_date'] = '';
				}
				$view['view']['data'] = $getdata;
			}

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

			$pop_start_date = $this->input->post('pop_start_date') ? $this->input->post('pop_start_date') : null;
			$pop_end_date = $this->input->post('pop_end_date') ? $this->input->post('pop_end_date') : null;
			$pop_is_center = $this->input->post('pop_is_center') ? $this->input->post('pop_is_center') : 0;
			$pop_left = $this->input->post('pop_left') ? $this->input->post('pop_left') : 0;
			$pop_top = $this->input->post('pop_top') ? $this->input->post('pop_top') : 0;
			$pop_width = $this->input->post('pop_width') ? $this->input->post('pop_width') : 0;
			$pop_height = $this->input->post('pop_height') ? $this->input->post('pop_height') : 0;
			$content_type = $this->cbconfig->item('use_popup_dhtml') ? 1 : 0;
			$pop_page = $this->input->post('pop_page') ? $this->input->post('pop_page') : 0;
			$pop_disable_hours = $this->input->post('pop_disable_hours') ? $this->input->post('pop_disable_hours') : 0;
			$pop_activated = $this->input->post('pop_activated') ? $this->input->post('pop_activated') : 0;

			$updatedata = array(
				'pop_title' => $this->input->post('pop_title', null, ''),
				'pop_start_date' => $pop_start_date,
				'pop_end_date' => $pop_end_date,
				'pop_is_center' => $pop_is_center,
				'pop_left' => $pop_left,
				'pop_top' => $pop_top,
				'pop_width' => $pop_width,
				'pop_height' => $pop_height,
				'pop_device' => $this->input->post('pop_device', null, ''),
				'pop_page' => $pop_page,
				'pop_disable_hours' => $pop_disable_hours,
				'pop_activated' => $pop_activated,
				'pop_content' => $this->input->post('pop_content', null, ''),
				'pop_content_html_type' => $content_type,
			);

			/**
			 * 게시물을 수정하는 경우입니다
			 */
			if ($this->input->post($primary_key)) {
				$this->{$this->modelname}->update($this->input->post($primary_key), $updatedata);
				$this->session->set_flashdata(
					'message',
					'정상적으로 수정되었습니다'
				);
			} else {
				/**
				 * 게시물을 새로 입력하는 경우입니다
				 */
				$updatedata['pop_datetime'] = cdate('Y-m-d H:i:s');
				$updatedata['pop_ip'] = $this->input->ip_address();
				$updatedata['mem_id'] = $this->member->item('mem_id');
				$this->{$this->modelname}->insert($updatedata);
				$this->session->set_flashdata(
					'message',
					'정상적으로 입력되었습니다'
				);
			}
			//오늘 생성된 팝업 캐시를 삭제합니다.
			$this->cache->delete('popup/popup-info-' . cdate('Y-m-d'));

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
		$eventname = 'event_admin_page_popup_listdelete';
		$this->load->event($eventname);

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		/**
		 * 체크한 게시물의 삭제를 실행합니다
		 */
		if ($this->input->post('chk') && is_array($this->input->post('chk'))) {
			foreach ($this->input->post('chk') as $val) {
				if ($val) {
					$this->{$this->modelname}->delete($val);
				}
			}
		}

		//오늘 생성된 팝업 캐시를 삭제합니다.
		$this->cache->delete('popup/popup-info-' . cdate('Y-m-d'));

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
