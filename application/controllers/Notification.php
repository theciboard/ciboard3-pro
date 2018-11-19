<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Notification class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 알림페이지와 관련된 controller 입니다.
 */
class Notification extends CB_Controller
{

	/**
	 * 모델을 로딩합니다
	 */
	protected $models = array('Notification');

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

		if ( ! $this->cbconfig->item('use_notification')) {
			alert('이 웹사이트는 알림기능을 사용하지 않습니다.');
		}
	}


	/**
	 * 알림 페이지 입니다
	 */
	public function index()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_notification_index';
		$this->load->event($eventname);

		/**
		 * 로그인이 필요한 페이지입니다
		 */
		required_user_login();

		$mem_id = (int) $this->member->item('mem_id');

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		// 2개월 이상된 알림은 하루에 한번씩 체크해서 삭제합니다.
		$cachename = 'delete_old_notifications_cache';
		$cachetime = 86400;
		if ( ! $result = $this->cache->get($cachename)) {
			$sdate = cdate('Y-m-d H:i:s', ctimestamp() - 24* 60 * 60 * 60);
			$where = array(
				'not_datetime <=' => $sdate,
			);
			$this->Notification_model->delete_where($where);
			$this->cache->save($cachename, cdate('Y-m-d H:i:s'), $cachetime);
		}

		/**
		 * 페이지에 숫자가 아닌 문자가 입력되거나 1보다 작은 숫자가 입력되면 에러 페이지를 보여줍니다.
		 */
		$param =& $this->querystring;
		$page = (((int) $this->input->get('page')) > 0) ? ((int) $this->input->get('page')) : 1;

		$per_page = $this->cbconfig->item('list_count') ? (int) $this->cbconfig->item('list_count') : 20;
		$offset = ($page - 1) * $per_page;

		/**
		 * 게시판 목록에 필요한 정보를 가져옵니다.
		 */
		$read = $this->input->get('read', null, '');
		$result = $this->Notification_model
			->get_notification_list($per_page, $offset, $mem_id, $read);
		$list_num = $result['total_rows'] - ($page - 1) * $per_page;
		if (element('list', $result)) {
			foreach (element('list', $result) as $key => $val) {
				$result['list'][$key]['num'] = $list_num--;
				$result['list'][$key]['delete_url'] = site_url('notification/delete/' . element('not_id', $val) . '?' . $param->output());
				$result['list'][$key]['read_url'] = site_url('notification/read/' . element('not_id', $val) . '?' . $param->output());
				$result['list'][$key]['onClick'] = '';
				if (element('not_type', $val) === 'note') {
					$result['list'][$key]['read_url'] = 'javascript:;';
					$result['list'][$key]['onClick'] = 'note_list(' . element('not_content_id', $val) . ');';
				}
			}
		}
		$view['view']['data'] = $result;
		$view['view']['list_delete_url'] = site_url('notification/listdelete?' . $param->output());
		$view['view']['list_update_url'] = site_url('notification/listupdate?' . $param->output());

		/**
		 * 페이지네이션을 생성합니다
		 */
		$config['base_url'] = site_url('notification') . '?' . $param->replace('page');
		$config['total_rows'] = $result['total_rows'];
		$config['per_page'] = $per_page;
		$this->pagination->initialize($config);
		$view['view']['paging'] = $this->pagination->create_links();
		$view['view']['page'] = $page;

		$view['view']['canonical'] = site_url('notification');

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 레이아웃을 정의합니다
		 */
		$page_title = $this->cbconfig->item('site_meta_title_notification');
		$meta_description = $this->cbconfig->item('site_meta_description_notification');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_notification');
		$meta_author = $this->cbconfig->item('site_meta_author_notification');
		$page_name = $this->cbconfig->item('site_page_name_notification');

		$layoutconfig = array(
			'path' => 'notification',
			'layout' => 'layout',
			'skin' => 'notification',
			'layout_dir' => $this->cbconfig->item('layout_notification'),
			'mobile_layout_dir' => $this->cbconfig->item('mobile_layout_notification'),
			'use_sidebar' => $this->cbconfig->item('sidebar_notification'),
			'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_notification'),
			'skin_dir' => $this->cbconfig->item('skin_notification'),
			'mobile_skin_dir' => $this->cbconfig->item('mobile_skin_notification'),
			'page_title' => $page_title,
			'meta_description' => $meta_description,
			'meta_keywords' => $meta_keywords,
			'meta_author' => $meta_author,
			'page_name' => $page_name,
		);
		$view['layout'] = $this->managelayout->front($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}


	/**
	 * 알림 읽기 입니다
	 */
	public function read($not_id = 0)
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_notification_read';
		$this->load->event($eventname);

		/**
		 * 로그인이 필요한 페이지입니다
		 */
		required_user_login();

		$mem_id = (int) $this->member->item('mem_id');

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		$not_id = (int) $not_id;
		if (empty($not_id) OR $not_id < 1) {
			show_404();
		}

		$notification = $this->Notification_model->get_one($not_id);

		if ( ! element('not_id', $notification)) {
			show_404();
		}

		if ((int) element('mem_id', $notification) !== $mem_id) {
			show_404();
		}

		$this->Notification_model->mark_read($not_id, $mem_id);

		// 이벤트가 존재하면 실행합니다
		Events::trigger('after', $eventname);

		$redirecturl = element('not_url', $notification);
		redirect($redirecturl);
	}


	/**
	 * 알림 읽기 입니다
	 */
	public function readajax($not_id = 0)
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_notification_readajax';
		$this->load->event($eventname);

		$this->output->set_content_type('application/json');

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		/**
		 * 로그인이 필요한 페이지입니다
		 */
		if ($this->member->is_member() === false) {
			$result = array('error' => '로그인 후 접속 가능한 페이지입니다.');
			exit(json_encode($result));
		}

		$mem_id = (int) $this->member->item('mem_id');

		$not_id = (int) $not_id;
		if (empty($not_id) OR $not_id < 1) {
			$result = array('error' => '잘못된 접근입니다.');
			exit(json_encode($result));
		}

		$notification = $this->Notification_model->get_one($not_id);

		if ( ! element('not_id', $notification)) {
			$result = array('error' => '잘못된 접근입니다.');
			exit(json_encode($result));
		}

		if ((int) element('mem_id', $notification) !== $mem_id) {
			$result = array('error' => '잘못된 접근입니다.');
			exit(json_encode($result));
		}

		$this->Notification_model->mark_read($not_id, $mem_id);

		// 이벤트가 존재하면 실행합니다
		Events::trigger('after', $eventname);

		$result = array('success' => '읽음으로 처리하였습니다');

		exit(json_encode($result));
	}


	/**
	 * 알림 전체 읽기 입니다
	 */
	public function readallajax()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_notification_readallajax';
		$this->load->event($eventname);

		$this->output->set_content_type('application/json');

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		/**
		 * 로그인이 필요한 페이지입니다
		 */
		if ($this->member->is_member() === false) {
			$result = array('error' => '로그인 후 접속 가능한 페이지입니다.');
			exit(json_encode($result));
		}

		$this->Notification_model->mark_allread($this->member->item('mem_id'));

		// 이벤트가 존재하면 실행합니다
		Events::trigger('after', $eventname);

		$result = array('success' => '읽음으로 처리하였습니다');
		exit(json_encode($result));
	}


	/**
	 * 알림 AJAX 리스트 입니다
	 */
	public function ajax_list()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_notification_ajax_list';
		$this->load->event($eventname);

		/**
		 * 로그인이 필요한 페이지입니다
		 */
		required_user_login();

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		/**
		 * 페이지에 숫자가 아닌 문자가 입력되거나 1보다 작은 숫자가 입력되면 에러 페이지를 보여줍니다.
		 */
		$param =& $this->querystring;

		/**
		 * 게시판 목록에 필요한 정보를 가져옵니다.
		 */
		$result = $this->Notification_model
			->get_notification_list(5, 0, $this->member->item('mem_id'), 'N');

		$html = '<div class="notifications-list"><strong id="notification_count">'
			. $result['total_rows']
			. '</strong>개의 읽지 않은 알림이 있습니다.<span><a href="javascript:;" class="pull-right point noti-all-read">모두 읽음으로 표시</a></span></div>';

		if (element('list', $result)) {
			foreach (element('list', $result) as $key => $val) {
				$result['list'][$key]['delete_url'] = site_url('notification/delete/' . element('not_id', $val) . '?' . $param->output());
				$jsevent = '';
				$read_url = site_url('notification/read/' . element('not_id', $val) . '?' . $param->output());
				if (element('not_type', $val) === 'note') {
					$read_url = 'javascript:;';
					$jsevent = 'onClick="note_list(' . element('not_content_id', $val) . ');"';
				}
				$html .= '<div class="notifications-list">
					<a href="' . $read_url . '" ' . $jsevent . ' class="notification_read '
					. element('not_type', $val) . '" data-not-id="' . element('not_id', $val) . '">'
					. html_escape(element('not_message', $val))
					. ' <span class="pull-right">'
					. display_datetime(element('not_datetime', $val), 'sns')
					. '</span></a></div> ';
			}
		}
		$html .= '<div class="notifications-list">
			<a class="external point" href="' . site_url('notification') . '">
			알림 페이지로 이동</a></div>';

		echo $html;
		exit();
	}


	/**
	 * 알림삭제 입니다
	 */
	public function delete($not_id = 0)
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_notification_delete';
		$this->load->event($eventname);

		/**
		 * 로그인이 필요한 페이지입니다
		 */
		required_user_login();

		$mem_id = (int) $this->member->item('mem_id');

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		$not_id = (int) $not_id;
		if (empty($not_id) OR $not_id < 1) {
			show_404();
		}

		$notification = $this->Notification_model->get_one($not_id);

		if ( ! element('not_id', $notification)) {
			show_404();
		}

		if ((int) element('mem_id', $notification) !== $mem_id) {
			show_404();
		}

		$this->Notification_model->delete($not_id);

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
		redirect('notification?' . $param->output());
	}


	/**
	 * 알림 선택삭제입니다
	 */
	public function listdelete()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_notification_listdelete';
		$this->load->event($eventname);

		/**
		 * 로그인이 필요한 페이지입니다
		 */
		required_user_login();

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		/**
		 * 체크한 게시물의 삭제를 실행합니다
		 */
		if ($this->input->post('chk') && is_array($this->input->post('chk'))) {
			foreach ($this->input->post('chk') as $val) {
				if ($val) {
					$where = array(
						'not_id' => $val,
						'mem_id' => $this->member->item('mem_id'),
					);
					$this->Notification_model->delete_where($where);
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
		redirect('notification?' . $param->output());
	}


	/**
	 * 알림 선택 읽음으로 표시 입니다
	 */
	public function listupdate()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_notification_listupdate';
		$this->load->event($eventname);

		/**
		 * 로그인이 필요한 페이지입니다
		 */
		required_user_login();

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		/**
		 * 체크한 게시물의 업데이트를 실행합니다
		 */
		if ($this->input->post('chk') && is_array($this->input->post('chk'))) {
			foreach ($this->input->post('chk') as $val) {
				if ($val) {
					$this->Notification_model->mark_read($val, $this->member->item('mem_id'));
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
		redirect('notification?' . $param->output());
	}
}
