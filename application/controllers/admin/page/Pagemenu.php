<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Pagemenu class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 관리자>페이지설정>메뉴관리 controller 입니다.
 */
class Pagemenu extends CB_Controller
{

	/**
	 * 관리자 페이지 상의 현재 디렉토리입니다
	 * 페이지 이동시 필요한 정보입니다
	 */
	public $pagedir = 'page/pagemenu';

	/**
	 * 모델을 로딩합니다
	 */
	protected $models = array('Menu');

	/**
	 * 이 컨트롤러의 메인 모델 이름입니다
	 */
	protected $modelname = 'Menu_model';

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
	public function index()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_page_pagemenu_index';
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
				'field' => 'men_name',
				'label' => '메뉴명',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'men_parent',
				'label' => '메뉴위치',
				'rules' => 'trim|numeric|is_natural',
			),
			array(
				'field' => 'men_target',
				'label' => '새창여부',
				'rules' => 'trim',
			),
			array(
				'field' => 'men_custom',
				'label' => '커스텀',
				'rules' => 'trim',
			),
			array(
				'field' => 'men_order',
				'label' => '순서',
				'rules' => 'trim|numeric|is_natural',
			),
			array(
				'field' => 'men_desktop',
				'label' => 'PC사용',
				'rules' => 'trim',
			),
			array(
				'field' => 'men_mobile',
				'label' => '모바일사용',
				'rules' => 'trim',
			),
			array(
				'field' => 'men_link',
				'label' => '링크주소',
				'rules' => 'trim|required|prep_url|valid_url',
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

			$men_parent = $this->input->post('men_parent') ? $this->input->post('men_parent') : 0;
			$men_desktop = $this->input->post('men_desktop') ? $this->input->post('men_desktop') : 0;
			$men_mobile = $this->input->post('men_mobile') ? $this->input->post('men_mobile') : 0;
			$men_order = $this->input->post('men_order') ? $this->input->post('men_order') : 0;
			$updatedata = array(
				'men_parent' => $men_parent,
				'men_name' => $this->input->post('men_name', null, ''),
				'men_link' => $this->input->post('men_link', null, ''),
				'men_target' => $this->input->post('men_target', null, ''),
				'men_desktop' => $men_desktop,
				'men_mobile' => $men_mobile,
				'men_custom' => $this->input->post('men_custom', null, ''),
				'men_order' => $men_order,
			);

			$this->{$this->modelname}->insert($updatedata);
			$view['view']['alert_message'] = '메뉴가 추가되었습니다';
			$this->_delete_cache();

			redirect(admin_url('page/pagemenu'), 'refresh');
		}

		/**
		 * 페이지에 숫자가 아닌 문자가 입력되거나 1보다 작은 숫자가 입력되면 에러 페이지를 보여줍니다.
		 */
		$param =& $this->querystring;

		/**
		 * 게시판 목록에 필요한 정보를 가져옵니다.
		 */
		$this->{$this->modelname}->allow_order_field = array('men_order'); // 정렬이 가능한 필드
		$where = array('men_parent' => 0);
		$result = $this->{$this->modelname}
			->get_admin_list($per_page = 1000, '', $where, '', $findex = 'men_order', $forder = 'ASC', '', '');
		if (element('list', $result)) {
			foreach (element('list', $result) as $key => $val) {
				$subwhere = array('men_parent' => element('men_id', $val));
				$subresult = $this->{$this->modelname}
					->get_admin_list($per_page = 1000, '', $subwhere, '', $findex = 'men_order', $forder = 'ASC', '', '');
				$result['list'][$key]['subresult'] = $subresult;
			}
		}

		$view['view']['data'] = $result;

		/**
		 * primary key 정보를 저장합니다
		 */
		$view['view']['primary_key'] = $this->{$this->modelname}->primary_key;

		/**
		 * 쓰기 주소, 삭제 주소등 필요한 주소를 구합니다
		 */
		$view['view']['list_update_url'] = admin_url($this->pagedir . '/listupdate/?' . $param->output());
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
	 * 목록 페이지에서 선택수정을 하는 경우 실행되는 메소드입니다
	 */
	public function listupdate()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_page_pagemenu_listupdate';
		$this->load->event($eventname);

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		/**
		 * 체크한 게시물의 업데이트를 실행합니다
		 */
		if ($this->input->post('chk') && is_array($this->input->post('chk'))) {

			$men_name = $this->input->post('men_name');
			$men_target = $this->input->post('men_target');
			$men_custom = $this->input->post('men_custom');
			$men_order = $this->input->post('men_order');
			$men_desktop = $this->input->post('men_desktop');
			$men_mobile = $this->input->post('men_mobile');
			$men_link = $this->input->post('men_link');

			foreach ($this->input->post('chk') as $val) {
				if ($val) {
					$men_order_update = element($val, $men_order) ? element($val, $men_order) : 0;
					$men_desktop_update = element($val, $men_desktop) ? element($val, $men_desktop) : 0;
					$men_mobile_update = element($val, $men_mobile) ? element($val, $men_mobile) : 0;
					$updatedata = array(
						'men_name' => element($val, $men_name),
						'men_target' => element($val, $men_target),
						'men_custom' => element($val, $men_custom),
						'men_order' => $men_order_update,
						'men_desktop' => $men_desktop_update,
						'men_mobile' => $men_mobile_update,
						'men_link' => element($val, $men_link),
					);
					$this->{$this->modelname}->update($val, $updatedata);
				}
			}
		}

		$this->_delete_cache();

		// 이벤트가 존재하면 실행합니다
		Events::trigger('after', $eventname);

		/**
		 * 업데이트가 끝난 후 목록페이지로 이동합니다
		 */
		$this->session->set_flashdata(
			'message',
			'정상적으로 수정되었습니다'
		);
		$param =& $this->querystring;
		$redirecturl = admin_url($this->pagedir . '?' . $param->output());

		redirect($redirecturl);
	}

	/**
	 * 목록 페이지에서 선택삭제를 하는 경우 실행되는 메소드입니다
	 */
	public function listdelete()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_page_pagemenu_listdelete';
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

		$this->_delete_cache();


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
	 * 메뉴관련 캐시를 삭제합니다
	 */
	public function _delete_cache()
	{
		$this->cache->delete('pagemenu-mobile');
		$this->cache->delete('pagemenu-desktop');
	}
}
