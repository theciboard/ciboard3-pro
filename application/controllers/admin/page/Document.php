<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Document class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 관리자>페이지설정>일반페이지 controller 입니다.
 */
class Document extends CB_Controller
{

	/**
	 * 관리자 페이지 상의 현재 디렉토리입니다
	 * 페이지 이동시 필요한 정보입니다
	 */
	public $pagedir = 'page/document';

	/**
	 * 모델을 로딩합니다
	 */
	protected $models = array('Document');

	/**
	 * 이 컨트롤러의 메인 모델 이름입니다
	 */
	protected $modelname = 'Document_model';

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
		$eventname = 'event_admin_page_document_index';
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
			'doc_id' => $param->sort('doc_id', 'asc'),
			'doc_key' => $param->sort('doc_key', 'asc'),
			'doc_title' => $param->sort('doc_title', 'asc'),
			'doc_layout' => $param->sort('doc_layout', 'asc'),
			'doc_mobile_layout' => $param->sort('doc_mobile_layout', 'asc'),
			'doc_skin' => $param->sort('doc_skin', 'asc'),
			'doc_mobile_skin' => $param->sort('doc_mobile_skin', 'asc'),
			'doc_updated_datetime' => $param->sort('doc_updated_datetime', 'asc'),
			'doc_hit' => $param->sort('doc_hit', 'asc'),
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
		$this->{$this->modelname}->allow_search_field = array('doc_id', 'doc_key', 'doc_title', 'doc_layout', 'doc_mobile_layout', 'doc_skin', 'doc_mobile_skin', 'doc_datetime', 'doc_updated_datetime', 'document.mem_id', 'doc_content', 'doc_mobile_content'); // 검색이 가능한 필드
		$this->{$this->modelname}->search_field_equal = array('doc_id', 'document.mem_id'); // 검색중 like 가 아닌 = 검색을 하는 필드
		$this->{$this->modelname}->allow_order_field = array('doc_id', 'doc_key', 'doc_title', 'doc_layout', 'doc_mobile_layout', 'doc_skin', 'doc_mobile_skin', 'doc_updated_datetime', 'doc_hit'); // 정렬이 가능한 필드
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
		$search_option = array('doc_key' => 'KEY', 'doc_title' => '제목', 'doc_content' => '내용', 'doc_mobile_content' => '모바일용내용', 'doc_datetime' => '입력일', 'doc_updated_datetime' => '최종수정일');
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
		$eventname = 'event_admin_page_document_write';
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
				'field' => 'doc_title',
				'label' => '제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'doc_content',
				'label' => '내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'doc_mobile_content',
				'label' => '모바일내용',
				'rules' => 'trim',
			),
			array(
				'field' => 'doc_layout',
				'label' => '레이아웃',
				'rules' => 'trim',
			),
			array(
				'field' => 'doc_sidebar',
				'label' => '사이드바',
				'rules' => 'trim',
			),
			array(
				'field' => 'doc_skin',
				'label' => '스킨',
				'rules' => 'trim',
			),
			array(
				'field' => 'doc_mobile_layout',
				'label' => '모바일레이아웃',
				'rules' => 'trim',
			),
			array(
				'field' => 'doc_mobile_sidebar',
				'label' => '모바일사이드바',
				'rules' => 'trim',
			),
			array(
				'field' => 'doc_mobile_skin',
				'label' => '모바일스킨',
				'rules' => 'trim',
			),
		);
		if ($this->input->post($primary_key)) {
			$config[] = array(
				'field' => 'doc_key',
				'label' => '페이지주소',
				'rules' => 'trim|required|alpha_dash|min_length[3]|max_length[50]|is_unique[document.doc_key.doc_id.' . $getdata['doc_id'] . ']',
			);
		} else {
			$config[] = array(
				'field' => 'doc_key',
				'label' => '페이지주소',
				'rules' => 'trim|required|alpha_dash|min_length[3]|max_length[50]|is_unique[document.doc_key]',
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

			$view['view']['data'] = $getdata;
			$view['view']['data']['doc_layout_option'] = get_skin_name(
				'_layout',
				set_value('doc_layout', element('doc_layout', $getdata)),
				'기본설정따름'
			);
			$view['view']['data']['doc_mobile_layout_option'] = get_skin_name(
				'_layout',
				set_value('doc_mobile_layout', element('doc_mobile_layout', $getdata)),
				'기본설정따름'
			);
			$view['view']['data']['doc_skin_option'] = get_skin_name(
				'document',
				set_value('doc_skin', element('doc_skin', $getdata)),
				'기본설정따름'
			);
			$view['view']['data']['doc_mobile_skin_option'] = get_skin_name(
				'document',
				set_value('doc_mobile_skin', element('doc_mobile_skin', $getdata)),
				'기본설정따름'
			);

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

			$content_type = $this->cbconfig->item('use_document_dhtml') ? 1 : 0;
			$doc_sidebar = $this->input->post('doc_sidebar') ? $this->input->post('doc_sidebar') : 0;
			$doc_mobile_sidebar = $this->input->post('doc_mobile_sidebar') ? $this->input->post('doc_mobile_sidebar') : 0;

			$updatedata = array(
				'doc_key' => $this->input->post('doc_key', null, ''),
				'doc_title' => $this->input->post('doc_title', null, ''),
				'doc_content' => $this->input->post('doc_content', null, ''),
				'doc_mobile_content' => $this->input->post('doc_mobile_content', null, ''),
				'doc_content_html_type' => $content_type,
				'doc_layout' => $this->input->post('doc_layout', null, ''),
				'doc_mobile_layout' => $this->input->post('doc_mobile_layout', null, ''),
				'doc_sidebar' => $doc_sidebar,
				'doc_mobile_sidebar' => $doc_mobile_sidebar,
				'doc_skin' => $this->input->post('doc_skin', null, ''),
				'doc_mobile_skin' => $this->input->post('doc_mobile_skin', null, ''),
				'doc_updated_mem_id' => $this->member->item('mem_id'),
				'doc_updated_datetime' => cdate('Y-m-d H:i:s'),
			);

			if ( $this->input->post($primary_key) != $pid ){
			}

			/**
			 * 게시물을 수정하는 경우입니다
			 */
			if ($pid) {
				$this->{$this->modelname}->update($pid, $updatedata);
				$this->session->set_flashdata(
					'message',
					'정상적으로 수정되었습니다'
				);
			} else {
				/**
				 * 게시물을 새로 입력하는 경우입니다
				 */
				$updatedata['doc_datetime'] = cdate('Y-m-d H:i:s');
				$updatedata['mem_id'] = $this->member->item('mem_id');
				$this->{$this->modelname}->insert($updatedata);
				$this->session->set_flashdata(
					'message',
					'정상적으로 입력되었습니다'
				);
			}

			$list = $this->{$this->modelname}->get('', 'doc_id, doc_key');
			$cachedata = '';
			if ($list && is_array($list)) {
				foreach ($list as $key => $val) {
					$cachedata[$val['doc_key']] = element('doc_id', $val);
				}
			}
			$this->cache->save('document-key-id-info', $cachedata, 86400*365);

			// 이벤트가 존재하면 실행합니다
			$view['view']['event']['after'] = Events::trigger('after', $eventname);

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
		$eventname = 'event_admin_page_document_listdelete';
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
		$list = $this->{$this->modelname}->get('', 'doc_id, doc_key');
		$cachedata = '';
		if ($list && is_array($list)) {
			foreach ($list as $key => $val) {
				$cachedata[$val['doc_key']] = element('doc_id', $val);
			}
		}
		$this->cache->save('document-key-id-info', $cachedata, 86400*365);

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
