<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Boardgroup class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 관리자>게시판설정>게시판그룹관리 controller 입니다.
 */
class Boardgroup extends CB_Controller
{

	/**
	 * 관리자 페이지 상의 현재 디렉토리입니다
	 * 페이지 이동시 필요한 정보입니다
	 */
	public $pagedir = 'board/boardgroup';

	/**
	 * 모델을 로딩합니다
	 */
	protected $models = array('Board_group', 'Board_group_meta', 'Board', 'Board_group_admin');

	/**
	 * 이 컨트롤러의 메인 모델 이름입니다
	 */
	protected $modelname = 'Board_group_model';

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
		$eventname = 'event_admin_board_boardgroup_index';
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
			'bgr_id' => $param->sort('bgr_id', 'asc'),
			'bgr_key' => $param->sort('bgr_key', 'asc'),
			'bgr_name' => $param->sort('bgr_name', 'asc'),
			'bgr_order' => $param->sort('bgr_order', 'asc'),
		);
		$findex = $this->input->get('findex', null, 'bgr_order');
		$forder = $this->input->get('forder', null, 'asc');
		$sfield = $this->input->get('sfield', null, '');
		$skeyword = $this->input->get('skeyword', null, '');

		$per_page = admin_listnum();
		$offset = ($page - 1) * $per_page;

		/**
		 * 게시판 목록에 필요한 정보를 가져옵니다.
		 */
		$this->{$this->modelname}->allow_search_field = array('bgr_id', 'bgr_key', 'bgr_name', 'bgr_order'); // 검색이 가능한 필드
		$this->{$this->modelname}->search_field_equal = array('bgr_id'); // 검색중 like 가 아닌 = 검색을 하는 필드
		$this->{$this->modelname}->allow_order_field = array('bgr_id', 'bgr_key', 'bgr_name', 'bgr_order'); // 정렬이 가능한 필드
		$result = $this->{$this->modelname}
			->get_admin_list($per_page, $offset, '', '', $findex, $forder, $sfield, $skeyword);
		$list_num = $result['total_rows'] - ($page - 1) * $per_page;
		if (element('list', $result)) {
			foreach (element('list', $result) as $key => $val) {
				$countwhere = array(
					'bgr_id' => element('bgr_id', $val),
				);
				$result['list'][$key]['brd_cnt'] = $this->Board_model->count_by($countwhere);
				$result['list'][$key]['meta'] = $this->Board_group_meta_model
					->get_all_meta(element('bgr_id', $val));
				$result['list'][$key]['group_skin_option'] = get_skin_name(
					'group',
					element('group_skin', $result['list'][$key]['meta']),
					'기본설정따름'
				);
				$result['list'][$key]['group_mobile_skin_option'] = get_skin_name(
					'group',
					element('group_mobile_skin', $result['list'][$key]['meta']),
					'기본설정따름'
				);
				$result['list'][$key]['group_layout_option'] = get_skin_name(
					'_layout',
					element('group_layout', $result['list'][$key]['meta']),
					'기본설정따름'
				);
				$result['list'][$key]['group_mobile_layout_option'] = get_skin_name(
					'_layout',
					element('group_mobile_layout', $result['list'][$key]['meta']),
					'기본설정따름'
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
		$search_option = array('bgr_key' => 'KEY', 'bgr_name' => '제목', 'bgr_order' => '정렬순서');
		$view['view']['skeyword'] = ($sfield && array_key_exists($sfield, $search_option)) ? $skeyword : '';
		$view['view']['search_option'] = search_option($search_option, $sfield);
		$view['view']['listall_url'] = admin_url($this->pagedir);
		$view['view']['write_url'] = admin_url($this->pagedir . '/write');
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
	 * 게시판 글쓰기 또는 수정 페이지를 가져오는 메소드입니다
	 */
	public function write($pid = 0)
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_board_boardgroup_write';
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
		if ($pid) {
			$getdata = $this->{$this->modelname}->get_one($pid);
			$board_group_meta = $this->Board_group_meta_model->get_all_meta($getdata['bgr_id']);
			if (is_array($board_group_meta)) {
				$getdata = array_merge($getdata, $board_group_meta);
			}
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
				'field' => 'bgr_name',
				'label' => '그룹명',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'bgr_order',
				'label' => '그룹정렬순서',
				'rules' => 'trim|required|numeric|is_natural',
			),
			array(
				'field' => 'group_layout',
				'label' => '레이아웃',
				'rules' => 'trim',
			),
			array(
				'field' => 'group_mobile_layout',
				'label' => '모바일레이아웃',
				'rules' => 'trim',
			),
			array(
				'field' => 'group_sidebar',
				'label' => '사이드바',
				'rules' => 'trim',
			),
			array(
				'field' => 'group_mobile_sidebar',
				'label' => '모바일사이드바',
				'rules' => 'trim',
			),
			array(
				'field' => 'group_skin',
				'label' => '스킨',
				'rules' => 'trim',
			),
			array(
				'field' => 'group_mobile_skin',
				'label' => '모바일스킨',
				'rules' => 'trim',
			),
			array(
				'field' => 'header_content',
				'label' => '상단내용',
				'rules' => 'trim',
			),
			array(
				'field' => 'footer_content',
				'label' => '하단내용',
				'rules' => 'trim',
			),
			array(
				'field' => 'mobile_header_content',
				'label' => '모바일상단내용',
				'rules' => 'trim',
			),
			array(
				'field' => 'mobile_footer_content',
				'label' => '모바일하단내용',
				'rules' => 'trim',
			),
		);
		if ($this->input->post($primary_key)) {
			$config[] = array(
				'field' => 'bgr_key',
				'label' => '페이지주소',
				'rules' => 'trim|required|alpha_dash|min_length[3]|max_length[50]|is_unique[board_group.bgr_key.bgr_id.' . $getdata['bgr_id'] . ']',
			);
		} else {
			$config[] = array(
				'field' => 'bgr_key',
				'label' => '페이지주소',
				'rules' => 'trim|required|alpha_dash|min_length[3]|max_length[50]|is_unique[board_group.bgr_key]',
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

		} else {
			/**
			 * 유효성 검사를 통과한 경우입니다.
			 * 즉 데이터의 insert 나 update 의 process 처리가 필요한 상황입니다
			 */

			// 이벤트가 존재하면 실행합니다
			$view['view']['event']['formruntrue'] = Events::trigger('formruntrue', $eventname);

			$bgr_order = $this->input->post('bgr_order') ? $this->input->post('bgr_order') : 0;
			$updatedata = array(
				'bgr_key' => $this->input->post('bgr_key', null, ''),
				'bgr_name' => $this->input->post('bgr_name', null, ''),
				'bgr_order' => $bgr_order,
			);

			$array = array(
				'group_layout', 'group_mobile_layout', 'group_sidebar', 'group_mobile_sidebar',
				'group_skin', 'group_mobile_skin', 'header_content', 'footer_content',
				'mobile_header_content', 'mobile_footer_content',);
			$metadata = array();
			if ($array) {
				foreach ($array as $value) {
					$metadata[$value] = $this->input->post($value, null, '');
				}
			}

			/**
			 * 게시물을 수정하는 경우입니다
			 */
			if ($this->input->post($primary_key)) {
				$this->{$this->modelname}->update($this->input->post($primary_key), $updatedata);
				$this->Board_group_meta_model->save($pid, $metadata);
				$view['view']['alert_message'] = '기본정보 설정이 저장되었습니다';
			} else {
				/**
				 * 게시물을 새로 입력하는 경우입니다
				 */
				$pid = $this->{$this->modelname}->insert($updatedata);
				$this->Board_group_meta_model->save($pid, $metadata);
				$this->session->set_flashdata(
					'message',
					'기본정보 설정이 저장되었습니다'
				);

				$redirecturl = admin_url($this->pagedir . '/write/' . $pid);
				redirect($redirecturl);
			}
		}

		$getdata = array();
		if ($pid) {
			$getdata = $this->{$this->modelname}->get_one($pid);
			$board_group_meta = $this->Board_group_meta_model->get_all_meta($getdata['bgr_id']);
			if (is_array($board_group_meta)) {
				$getdata = array_merge($getdata, $board_group_meta);
			}
		}
		$view['view']['data'] = $getdata;
		$view['view']['data']['group_layout_option'] = get_skin_name(
			'_layout',
			set_value('group_layout', element('group_layout', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['group_mobile_layout_option'] = get_skin_name(
			'_layout',
			set_value('group_mobile_layout', element('group_mobile_layout', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['group_skin_option'] = get_skin_name(
			'group',
			set_value('group_skin', element('group_skin', $getdata)),
			'기본설정따름'
		);
		$view['view']['data']['group_mobile_skin_option'] = get_skin_name(
			'group',
			set_value('group_mobile_skin', element('group_mobile_skin', $getdata)),
			'기본설정따름'
		);

		/**
		 * primary key 정보를 저장합니다
		 */
		$view['view']['primary_key'] = $primary_key;
		$view['view']['grouplist'] = $this->Board_group_model->get_group_list();

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
	}

	/**
	 * 그룹관리자를 관리하는 페이지입니다
	 */
	public function write_admin($pid = 0)
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_board_boardgroup_write_admin';
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

		/**
		 * Validation 라이브러리를 가져옵니다
		 */
		$this->load->library('form_validation');
		/**
		 * 전송된 데이터의 유효성을 체크합니다
		 */
		$config = array(
			array(
				'field' => 'bgr_id',
				'label' => '그룹아이디',
				'rules' => 'trim|required|numeric|is_natural',
			),
			array(
				'field' => 'userid',
				'label' => '회원아이디',
				'rules' => 'trim|required|alpha_dash|min_length[3]|max_length[50]|callback__userid_check[' . $pid . ']',
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

			$memdata = $this->Member_model
				->get_by_userid($this->input->post('userid'), 'mem_id');
			$mem_id = element('mem_id', $memdata);

			$insertdata = array(
				'bgr_id' => $this->input->post('bgr_id'),
				'mem_id' => $mem_id,
			);

			$this->Board_group_admin_model->insert($insertdata);
		}


		/**
		 * 페이지에 숫자가 아닌 문자가 입력되거나 1보다 작은 숫자가 입력되면 에러 페이지를 보여줍니다.
		 */
		$param =& $this->querystring;
		$findex = $this->input->get('findex') ? $this->input->get('findex') : $this->Board_group_admin_model->primary_key;
		$forder = $this->input->get('forder', null, 'desc');

		$getdata = $this->{$this->modelname}->get_one($pid);
		$where = array(
			'bgr_id' => $pid,
		);
		$result = $this->Board_group_admin_model
			->get('', '', $where, '', '', $findex, $forder);
		if ($result && is_array($result)) {
			foreach ($result as $key => $val) {
				$select = 'mem_id, mem_userid, mem_nickname, mem_email, mem_icon';
				$result[$key]['member'] = $dbmember = $this->Member_model->get_by_memid(element('mem_id', $val), $select);
				$result[$key]['display_name'] = display_username(
					element('mem_userid', $dbmember),
					element('mem_nickname', $dbmember),
					element('mem_icon', $dbmember)
				);
			}
		}
		$view['view']['list'] = $result;
		$view['view']['data'] = $getdata;

		$primary_key = $this->Board_group_admin_model->primary_key;

		$view['view']['list_delete_url'] = admin_url($this->pagedir . '/write_admin_delete/' . $pid . '?' . $param->output());
		$view['view']['primary_key'] = $primary_key;
		$view['view']['grouplist'] = $this->Board_group_model->get_group_list();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 어드민 레이아웃을 정의합니다
		 */
		$layoutconfig = array('layout' => 'layout', 'skin' => 'write_admin');
		$view['layout'] = $this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}

	/**
	 * 그룹 관리자 추가시 실제 존재하는 회원인지 체크합니다.
	 */
	public function _userid_check($str, $pid)
	{
		if (empty($str)) {
			$this->form_validation->set_message(
				'_userid_check',
				'회원아이디가 입력되지 않았습니다'
			);
			return false;
		}

		$select = 'mem_id, mem_denied';
		$getdata = $this->Member_model->get_by_userid($str, $select);

		if ( ! element('mem_id', $getdata)) {
			$this->form_validation->set_message(
				'_userid_check',
				$str . ' 은(는) 존재하지 않는 회원아이디입니다'
			);
			return false;
		} elseif (element('mem_denied', $getdata)) {
			$this->form_validation->set_message(
				'_userid_check',
				$str . ' 은(는) 탈퇴 또는 차단된 회원아이디입니다'
			);
			return false;
		} else {
			$where = array(
				'bgr_id' => $pid,
				'mem_id' => element('mem_id', $getdata),
			);
			$chkdata = $this->Board_group_admin_model->get_one('', '', $where);
			if (element('mem_id', $chkdata)) {
				$this->form_validation->set_message(
					'_userid_check',
					$str . ' 은(는) 이미 입력된 회원아이디입니다'
				);
				return false;
			}

			return true;
		}
	}

	/**
	 * 목록 페이지에서 선택수정을 하는 경우 실행되는 메소드입니다
	 */
	public function listupdate()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_board_boardgroup_listupdate';
		$this->load->event($eventname);

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		/**
		 * 체크한 게시물의 업데이트를 실행합니다
		 */
		if ($this->input->post('chk') && is_array($this->input->post('chk'))) {

			$bgr_name = $this->input->post('bgr_name');
			$bgr_order = $this->input->post('bgr_order');
			$group_layout = $this->input->post('group_layout');
			$group_mobile_layout = $this->input->post('group_mobile_layout');
			$group_sidebar = $this->input->post('group_sidebar');
			$group_mobile_sidebar = $this->input->post('group_mobile_sidebar');
			$group_skin = $this->input->post('group_skin');
			$group_mobile_skin = $this->input->post('group_mobile_skin');

			foreach ($this->input->post('chk') as $val) {
				if ($val) {
					$bgr_order_update = element($val, $bgr_order) ? element($val, $bgr_order) : 0;
					$updatedata = array(
						'bgr_name' => element($val, $bgr_name),
						'bgr_order' => $bgr_order_update,
					);
					$this->{$this->modelname}->update($val, $updatedata);
					$metadata = array(
						'group_layout' => element($val, $group_layout),
						'group_mobile_layout' => element($val, $group_mobile_layout),
						'group_sidebar' => element($val, $group_sidebar),
						'group_mobile_sidebar' => element($val, $group_mobile_sidebar),
						'group_skin' => element($val, $group_skin),
						'group_mobile_skin' => element($val, $group_mobile_skin),
					);
					$this->Board_group_meta_model->save($val, $metadata);
				}
			}
		}

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
		$eventname = 'event_admin_board_boardgroup_listdelete';
		$this->load->event($eventname);

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		$msg = '';
		/**
		 * 체크한 게시물의 삭제를 실행합니다
		 */
		if ($this->input->post('chk') && is_array($this->input->post('chk'))) {
			foreach ($this->input->post('chk') as $val) {
				if ($val) {
					$countwhere = array(
						'bgr_id' => $val,
					);
					$cnt = $this->Board_model->count_by($countwhere);
					if ($cnt > 0) {
						$this->session->set_flashdata(
							'message',
							'게시판이 존재하는 그룹은 삭제할 수 없습니다'
						);
						$msg = 1;
					}
					if ($cnt === 0) {
						$this->{$this->modelname}->delete($val);
						$deletewhere = array(
							'bgr_id' => $val,
						);
						$this->Board_group_admin_model->delete_where($deletewhere);
						$this->Board_group_meta_model->deletemeta($val);
					}
				}
			}
		}

		// 이벤트가 존재하면 실행합니다
		Events::trigger('after', $eventname);

		/**
		 * 삭제가 끝난 후 목록페이지로 이동합니다
		 */
		if (empty($msg)) {
			$this->session->set_flashdata(
				'message',
				'정상적으로 삭제되었습니다'
			);
		}
		$param =& $this->querystring;
		$redirecturl = admin_url($this->pagedir . '?' . $param->output());

		redirect($redirecturl);
	}

	/**
	 * 그룹관리자 삭제를 할 경우 실행되는 메소드입니다
	 */
	public function write_admin_delete($pid = 0)
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_board_boardgroup_write_admin_delete';
		$this->load->event($eventname);

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		$pid = (int) $pid;
		if (empty($pid) OR $pid < 1) {
			show_404();
		}

		/**
		 * 체크한 게시물의 삭제를 실행합니다
		 */
		if ($this->input->post('chk') && is_array($this->input->post('chk'))) {
			foreach ($this->input->post('chk') as $val) {
				if ($val) {
					$this->Board_group_admin_model->delete($val);
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
		$redirecturl = admin_url($this->pagedir . '/write_admin/' . $pid . '?' . $param->output());

		redirect($redirecturl);
	}
}
