<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Qna class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 관리자>컨텐츠몰관리>상품문의 controller 입니다.
 */
class Qna extends CB_Controller
{

	/**
	 * 관리자 페이지 상의 현재 디렉토리입니다
	 * 페이지 이동시 필요한 정보입니다
	 */
	public $pagedir = 'cmall/qna';

	/**
	 * 모델을 로딩합니다
	 */
	protected $models = array('Cmall_item', 'Cmall_qna');

	/**
	 * 이 컨트롤러의 메인 모델 이름입니다
	 */
	protected $modelname = 'Cmall_qna_model';

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
		$this->load->library(array('pagination', 'querystring', 'cmalllib'));
	}

	/**
	 * 목록을 가져오는 메소드입니다
	 */
	public function index()
	{

		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_cmall_qna_index';
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
			'cqa_id' => $param->sort('cqa_id', 'asc'),
			'cit_id' => $param->sort('cit_id', 'asc'),
			'cit_name' => $param->sort('cit_name', 'asc'),
			'cit_key' => $param->sort('cit_key', 'asc'),
			'cqa_title' => $param->sort('cqa_title', 'asc'),
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
		$this->{$this->modelname}->allow_search_field = array('cqa_id', 'cit_id', 'cqa_title', 'cqa_content', 'cqa_reply_content', 'cmall_qna.mem_id', 'cqa_datetime', 'cqa_reply_datetime', 'cqa_ip', 'cqa_reply_mem_id', 'cit_name', 'cit_key'); // 검색이 가능한 필드
		$this->{$this->modelname}->search_field_equal = array('cqa_id', 'cit_id', 'cmall_qna.mem_id', 'cqa_reply_mem_id'); // 검색중 like 가 아닌 = 검색을 하는 필드
		$this->{$this->modelname}->allow_order_field = array('cqa_id', 'cit_id', 'cqa_title', 'cit_name', 'cit_key'); // 정렬이 가능한 필드
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
				if (element('cqa_reply_mem_id', $val)) {
					$select = 'mem_id, mem_userid, mem_nickname, mem_icon';
					$result['list'][$key]['reply_member'] = $reply_member = $this->Member_model->get_by_memid(element('cqa_reply_mem_id', $val), $select);
					$result['list'][$key]['reply_display_name'] = display_username(
						element('mem_userid', $reply_member),
						element('mem_nickname', $reply_member),
						element('mem_icon', $reply_member)
					);
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
		$search_option = array('cqa_title' => '질문제목', 'cqa_content' => '질문내용', 'cqa_reply_content' => '답변내용', 'cit_name' => '상품명', 'cit_key' => '상품코드', 'cqa_datetime' => '입력일');
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
		$eventname = 'event_admin_cmall_qna_write';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		/**
		 * 프라이머리키에 숫자형이 입력되지 않으면 에러처리합니다
		 */
		$pid = (int) $pid;
		if (empty($pid) OR $pid < 1) {
			show_404();
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
				'field' => 'cqa_title',
				'label' => '제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'cqa_secret',
				'label' => '비밀글여부',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'cqa_content',
				'label' => '질문내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'cqa_reply_content',
				'label' => '답변내용',
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

			$view['view']['data'] = $getdata;

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

			$content_type = $this->cbconfig->item('use_cmall_product_qna_dhtml') ? 1 : 0;
			$cqa_secret = $this->input->post('cqa_secret') ? 1 : 0;

			$updatedata = array(
				'cqa_title' => $this->input->post('cqa_title', null, ''),
				'cqa_content' => $this->input->post('cqa_content', null, ''),
				'cqa_content_html_type' => $content_type,
				'cqa_reply_content' => $this->input->post('cqa_reply_content', null, ''),
				'cqa_reply_html_type' => $content_type,
				'cqa_secret' => $cqa_secret,
			);

			$is_reply_alarm = false;
			if ($this->input->post('cqa_reply_content') && ! element('cqa_reply_mem_id', $getdata)) {
				$updatedata['cqa_reply_datetime'] = cdate('Y-m-d H:i:s');
				$updatedata['cqa_reply_mem_id'] = $this->member->item('mem_id');
				$updatedata['cqa_reply_ip'] = $this->input->ip_address();
				$is_reply_alarm = true;
			}

			$this->{$this->modelname}->update($this->input->post($primary_key), $updatedata);
			$this->_update_qna_count(element('cit_id', $getdata));

			if ($is_reply_alarm) {
				$this->load->library('cmalllib');
				$this->cmalllib->qna_reply_alarm($this->input->post($primary_key));
			}

			$this->session->set_flashdata(
				'message',
				'정상적으로 수정되었습니다'
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
		$eventname = 'event_admin_cmall_qna_listdelete';
		$this->load->event($eventname);

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		/**
		 * 체크한 게시물의 삭제를 실행합니다
		 */
		if ($this->input->post('chk') && is_array($this->input->post('chk'))) {
			foreach ($this->input->post('chk') as $val) {
				if ($val) {
					$data = $this->{$this->modelname}->get($val);
					$this->{$this->modelname}->delete($val);
					$this->_update_qna_count(element('cit_id', $data));
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

	public function _update_qna_count($cit_id)
	{
		if (empty($cit_id)) {
			return;
		}

		$result = $this->{$this->modelname}->get_qna_count($cit_id);

		$update = array(
			'cit_qna_count' => element('cnt', $result),
		);
		$this->Cmall_item_model->update($cit_id, $update);

		return true;
	}
}
