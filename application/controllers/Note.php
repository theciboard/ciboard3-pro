<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Note class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 쪽지 목록, 쪽지 읽기, 쪽지 발송과 관련된 controller 입니다.
 */
class Note extends CB_Controller
{

	/**
	 * 모델을 로딩합니다
	 */
	protected $models = array('Note');

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
		$this->load->library(array('pagination', 'querystring', 'notelib'));

		if ($this->member->item('mem_id') && $this->member->item('meta_note_received_datetime')) {
			$this->load->model('Member_meta_model');
			$metadata = array(
				'meta_note_received_datetime' => '',
				'meta_note_received_from_id' => '',
				'meta_note_received_from_nickname' => '',
			);
			$this->Member_meta_model->save($this->member->item('mem_id'), $metadata);
		}
	}


	/**
	 * 쪽지 목록 페이지입니다
	 */
	public function lists($type = 'recv')
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_note_lists';
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

		if ( ! $this->cbconfig->item('use_note')) {
			alert_close('쪽지 기능을 사용하지 않는 사이트입니다');
			return false;
		} elseif ( ! $this->member->item('mem_use_note') && $this->member->is_admin() !== 'super') {
			alert_close('회원님은 쪽지 기능을 사용하지 않는 중이십니다');
			return false;
		}

		if ($type !== 'send') {
			$type = 'recv';
		}
		$nte_type = ($type === 'send') ? 2 : 1;
		$mem_column = ($type === 'send') ? 'recv_mem_id' : 'send_mem_id';
		$my_column = ($type === 'send') ? 'send_mem_id' : 'recv_mem_id';

		/**
		 * 페이지에 숫자가 아닌 문자가 입력되거나 1보다 작은 숫자가 입력되면 에러 페이지를 보여줍니다.
		 */
		$param =& $this->querystring;
		$page = (((int) $this->input->get('page')) > 0) ? ((int) $this->input->get('page')) : 1;
		$findex = $this->Note_model->primary_key;
		$forder = 'desc';

		if ($this->cbconfig->get_device_view_type() === 'mobile') {
			$per_page = $this->cbconfig->item('note_mobile_list_page')
				? (int) $this->cbconfig->item('note_mobile_list_page') : 10;
		} else {
			$per_page = $this->cbconfig->item('note_list_page')
				? (int) $this->cbconfig->item('note_list_page') : 10;
		}
		$offset = ($page - 1) * $per_page;

		/**
		 * 게시판 목록에 필요한 정보를 가져옵니다.
		 */
		$where = array(
			$my_column => $mem_id,
			'nte_type' => $nte_type,
		);
		if ($type === 'send') {
			$result = $this->Note_model
				->get_send_list($per_page, $offset, $where, '', $findex, $forder);
		} else {
			$result = $this->Note_model
				->get_recv_list($per_page, $offset, $where, '', $findex, $forder);
		}
		$list_num = $result['total_rows'] - ($page - 1) * $per_page;

		if (element('list', $result)) {
			foreach (element('list', $result) as $key => $val) {
				if ($val[$mem_column]) {
					$result['list'][$key]['display_name'] = display_username(
						element('mem_userid', $val),
						element('mem_nickname', $val),
						element('mem_icon', $val)
					);
				} else {
					$result['list'][$key]['display_name'] = '알림';
				}
				$result['list'][$key]['delete_url'] = site_url('note/delete/' . element('nte_type', $val) . '/' . element('nte_id', $val));
				$result['list'][$key]['num'] = $list_num--;
			}
		}
		$view['view']['data'] = $result;
		$view['view']['type'] = $type;

		/**
		 * 페이지네이션을 생성합니다
		 */
		$config['base_url'] = site_url('note/lists/' . $type) . '?' . $param->replace('page');
		$config['total_rows'] = $result['total_rows'];
		$config['per_page'] = $per_page;
		$this->pagination->initialize($config);
		$view['view']['paging'] = $this->pagination->create_links();
		$view['view']['page'] = $page;

		$view['view']['canonical'] = site_url('note/lists/' . $type);

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 레이아웃을 정의합니다
		 */
		$page_title = $this->cbconfig->item('site_meta_title_note_list');
		$meta_description = $this->cbconfig->item('site_meta_description_note_list');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_note_list');
		$meta_author = $this->cbconfig->item('site_meta_author_note_list');
		$page_name = $this->cbconfig->item('site_page_name_note_list');

		$layoutconfig = array(
			'path' => 'note',
			'layout' => 'layout_popup',
			'skin' => 'lists',
			'layout_dir' => $this->cbconfig->item('layout_note'),
			'mobile_layout_dir' => $this->cbconfig->item('mobile_layout_note'),
			'skin_dir' => $this->cbconfig->item('skin_note'),
			'mobile_skin_dir' => $this->cbconfig->item('mobile_skin_note'),
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
	 * 쪽지 상세보기 페이지입니다
	 */
	public function view($type = 'recv', $note_id = 0)
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_note_view';
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

		if ( ! $this->cbconfig->item('use_note')) {
			alert_close('쪽지 기능을 사용하지 않는 사이트입니다');
			return false;
		} elseif ( ! $this->member->item('mem_use_note') && $this->member->is_admin() !== 'super') {
			alert_close('회원님은 쪽지 기능을 사용하지 않는 중이십니다');
			return false;
		}

		if ($type !== 'send') {
			$type = 'recv';
		}
		$nte_type = ($type === 'send') ? 2 : 1;
		$columnname = ($type === 'send') ? 'send_mem_id' : 'recv_mem_id';
		$mem_column = ($type === 'send') ? 'recv_mem_id' : 'send_mem_id';

		$note_id = (int) $note_id;
		if (empty($note_id) OR $note_id < 1) {
			show_404();
		}

		$where = array(
			'nte_id' => $note_id,
			$columnname => $mem_id,
			'nte_type' => $nte_type,
		);
		$result = $this->Note_model->get_note($where);
		if ( ! element('nte_id', $result)) {
			show_404();
		}
		if ($result[$mem_column]) {
			$result['userid'] = element('mem_userid', $result);
			$result['display_name'] = display_username(
				element('mem_userid', $result),
				element('mem_nickname', $result),
				element('mem_icon', $result)
			);
		} else {
			$result['display_name'] = '알림';
		}
		if (element('nte_type', $result) === '1'
			&& (empty($result['nte_read_datetime']) OR $result['nte_read_datetime'] <= '0000-00-00 00:00:00')) {
			$updatedata = array(
				'nte_read_datetime' => cdate('Y-m-d H:i:s'),
			);
			$this->Note_model->update(element('nte_id', $result), $updatedata);
			if ($result[$mem_column] > 0) {
				$updatedata = array(
					'nte_read_datetime' => cdate('Y-m-d H:i:s'),
				);
				$where = array(
					'related_note_id' => element('nte_id', $result),
				);
				$this->Note_model->update('', $updatedata, $where);
			}

			$where = array(
				'recv_mem_id' => $mem_id,
				'nte_type' => 1,
			);
			$this->db->where($where);
			$this->db->group_start();
			$this->db->where(array('nte_read_datetime <=' => '0000-00-00 00:00:00'));
			$this->db->or_where(array('nte_read_datetime' => null));
			$this->db->group_end();
			$cnt = $this->db->count_all_results('note');
			$updatedata = array(
				'meta_unread_note_num' => $cnt,
			);
			$this->load->model('Member_meta_model');
			$this->Member_meta_model->save($mem_id, $updatedata);
		}
		$result['content'] = display_html_content(
			element('nte_content', $result),
			element('nte_content_html_type', $result),
			$thumb_width = 500,
			$autolink = true,
			$popup = true
		);

		if ($result['nte_filename']) {
			$result['download_link'] = site_url('note/download/' . $type . '/' . $note_id);
		}

		$view['view']['data'] = $result;
		$view['view']['type'] = $type;
		$view['view']['canonical'] = site_url('note/view/' . $type . '/' . $note_id);

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 레이아웃을 정의합니다
		 */
		$page_title = $this->cbconfig->item('site_meta_title_note_view');
		$meta_description = $this->cbconfig->item('site_meta_description_note_view');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_note_view');
		$meta_author = $this->cbconfig->item('site_meta_author_note_view');
		$page_name = $this->cbconfig->item('site_page_name_note_view');

		$searchconfig = array(
			'{쪽지제목}',
		);
		$replaceconfig = array(
			element('nte_title', $result),
		);

		$page_title = str_replace($searchconfig, $replaceconfig, $page_title);
		$meta_description = str_replace($searchconfig, $replaceconfig, $meta_description);
		$meta_keywords = str_replace($searchconfig, $replaceconfig, $meta_keywords);
		$meta_author = str_replace($searchconfig, $replaceconfig, $meta_author);
		$page_name = str_replace($searchconfig, $replaceconfig, $page_name);

		$layoutconfig = array(
			'path' => 'note',
			'layout' => 'layout_popup',
			'skin' => 'view',
			'layout_dir' => $this->cbconfig->item('layout_note'),
			'mobile_layout_dir' => $this->cbconfig->item('mobile_layout_note'),
			'skin_dir' => $this->cbconfig->item('skin_note'),
			'mobile_skin_dir' => $this->cbconfig->item('mobile_skin_note'),
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
	 * 쪽지 글쓰기 페이지입니다
	 */
	public function write($userid = '')
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_note_write';
		$this->load->event($eventname);

		/**
		 * 로그인이 필요한 페이지입니다
		 */
		required_user_login();

		if ( ! $this->cbconfig->item('use_note')) {
			alert_close('쪽지 기능을 사용하지 않는 사이트입니다');
			return false;
		} elseif ( ! $this->member->item('mem_use_note') && $this->member->is_admin() !== 'super') {
			alert_close('회원님은 쪽지 기능을 사용하지 않는 중이십니다');
			return false;
		}

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		$view['view']['userid'] = $userid;
		$view['view']['use_dhtml'] = false;
		if ($this->cbconfig->get_device_view_type() !== 'mobile'
			&& $this->cbconfig->item('use_note_dhtml')) {
			$view['view']['use_dhtml'] = true;
		}
		if ($this->cbconfig->get_device_view_type() === 'mobile'
			&& $this->cbconfig->item('use_note_mobile_dhtml')) {
			$view['view']['use_dhtml'] = true;
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
				'field' => 'userid',
				'label' => '회원아이디',
				'rules' => 'trim|required|callback__check_userid',
			),
			array(
				'field' => 'title',
				'label' => '제목',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'content',
				'label' => '내용',
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

		} else {

			// 이벤트가 존재하면 실행합니다
			$view['view']['event']['formruntrue'] = Events::trigger('formruntrue', $eventname);

			$file_error = '';
			$uploadfiledata = array();

			if ($this->cbconfig->item('use_note_file')) {
				$this->load->library('upload');

				if (isset($_FILES)
					&& isset($_FILES['note_file'])
					&& isset($_FILES['note_file']['name'])
					&& $_FILES['note_file']['name']) {
					$upload_path = config_item('uploads_dir') . '/note/';
					if (is_dir($upload_path) === false) {
						mkdir($upload_path, 0707);
						$file = $upload_path . 'index.php';
						$f = @fopen($file, 'w');
						@fwrite($f, '');
						@fclose($f);
						@chmod($file, 0644);
					}
					$upload_path .= cdate('Y') . '/';
					if (is_dir($upload_path) === false) {
						mkdir($upload_path, 0707);
						$file = $upload_path . 'index.php';
						$f = @fopen($file, 'w');
						@fwrite($f, '');
						@fclose($f);
						@chmod($file, 0644);
					}
					$upload_path .= cdate('m') . '/';
					if (is_dir($upload_path) === false) {
						mkdir($upload_path, 0707);
						$file = $upload_path . 'index.php';
						$f = @fopen($file, 'w');
						@fwrite($f, '');
						@fclose($f);
						@chmod($file, 0644);
					}

					$uploadconfig = array();
					$uploadconfig['upload_path'] = $upload_path;
					$uploadconfig['allowed_types'] = '*';
					$uploadconfig['encrypt_name'] = true;

					$this->upload->initialize($uploadconfig);
					$_FILES['userfile']['name'] = $_FILES['note_file']['name'];
					$_FILES['userfile']['type'] = $_FILES['note_file']['type'];
					$_FILES['userfile']['tmp_name'] = $_FILES['note_file']['tmp_name'];
					$_FILES['userfile']['error'] = $_FILES['note_file']['error'];
					$_FILES['userfile']['size'] = $_FILES['note_file']['size'];
					if ($this->upload->do_upload()) {
						$filedata = $this->upload->data();

						$uploadfiledata['nte_filename'] = cdate('Y') . '/' . cdate('m') . '/' . element('file_name', $filedata);
						$uploadfiledata['nte_originname'] = element('orig_name', $filedata);
					} else {
						$file_error = $this->upload->display_errors();
					}
				}
			}
			if ($file_error) {
				$view['view']['message'] = $file_error;
			} else {
				$recv_list = explode(',', $this->input->post('userid'));
				$mem_list = array();
				$error_list = array();
				$view['view']['message'] = '';
				$content_type = $view['view']['use_dhtml'] ? 1 : 0;

				if ($recv_list && is_array($recv_list)) {
					foreach ($recv_list as $key => $value) {
						$value = trim($value);
						if ($value) {
							$mem = $this->Member_model->get_by_userid($value, 'mem_id');

							if (element('mem_id', $mem)) {

								$send_result = $this->notelib->send_note(
									$this->member->item('mem_id'),
									element('mem_id', $mem),
									$this->input->post('title'),
									$this->input->post('content'),
									$content_type,
									element('nte_originname', $uploadfiledata, ''),
									element('nte_filename', $uploadfiledata, '')
								);

								$jsonresult = json_decode($send_result, true);

								if (isset($jsonresult['error']) && $jsonresult['error']) {
									$view['view']['message'] .= $jsonresult['error'] . '<br />';
								}

							} else {
								$view['view']['message'] .= $value . '는 존재하지 않는 회원입니다<br />';
							}
						}
					}
				}
			}

			if (empty($view['view']['message'])) {

				// 이벤트가 존재하면 실행합니다
				$view['view']['event']['after'] = Events::trigger('after', $eventname);

				$this->session->set_flashdata(
					'message',
					'쪽지가 발송되었습니다'
				);
				redirect('note/lists/send');
			}
		}

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 레이아웃을 정의합니다
		 */
		$page_title = $this->cbconfig->item('site_meta_title_note_write');
		$meta_description = $this->cbconfig->item('site_meta_description_note_write');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_note_write');
		$meta_author = $this->cbconfig->item('site_meta_author_note_write');
		$page_name = $this->cbconfig->item('site_page_name_note_write');

		$layoutconfig = array(
			'path' => 'note',
			'layout' => 'layout_popup',
			'skin' => 'write',
			'layout_dir' => $this->cbconfig->item('layout_note'),
			'mobile_layout_dir' => $this->cbconfig->item('mobile_layout_note'),
			'skin_dir' => $this->cbconfig->item('skin_note'),
			'mobile_skin_dir' => $this->cbconfig->item('mobile_skin_note'),
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
	 * 쪽지 다운로드 기능입니다
	 */
	public function download($type = 'recv', $note_id = 0)
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_note_download';
		$this->load->event($eventname);

		/**
		 * 로그인이 필요한 페이지입니다
		 */
		required_user_login();

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		if ( ! $this->cbconfig->item('use_note')) {
			alert_close('쪽지 기능을 사용하지 않는 사이트입니다');
			return false;
		} elseif ( ! $this->member->item('mem_use_note') && $this->member->is_admin() !== 'super') {
			alert_close('회원님은 쪽지 기능을 사용하지 않는 중이십니다');
			return false;
		}

		if ($type !== 'send') {
			$type = 'recv';
		}
		$nte_type = ($type === 'send') ? 2 : 1;
		$columnname = ($type === 'send') ? 'send_mem_id' : 'recv_mem_id';
		$mem_column = ($type === 'send') ? 'recv_mem_id' : 'send_mem_id';

		$note_id = (int) $note_id;
		if (empty($note_id) OR $note_id < 1) {
			show_404();
		}

		$where = array(
			'nte_id' => $note_id,
			$columnname => $this->member->item('mem_id'),
			'nte_type' => $nte_type,
		);
		$result = $this->Note_model->get_note($where);
		if ( ! element('nte_id', $result) OR ! element('nte_filename', $result) OR ! element('nte_originname', $result)) {
			show_404();
		}

		$this->load->helper('download');
		// Read the file's contents
		$data = file_get_contents(config_item('uploads_dir') . '/note/' . element('nte_filename', $result));
		$name = element('nte_originname', $result);

		// 이벤트가 존재하면 실행합니다
		Events::trigger('after', $eventname);
		force_download($name, $data);
	}


	/**
	 * 쪽지 발송시 실제 존재하는 회원인지, 수신가능한 회원인지 체크하는 함수입니다
	 */
	public function _check_userid($str)
	{
		$recv_list = explode(',', $str);
		$mem_list = array();
		$error_list = array();

		if ($recv_list && is_array($recv_list)) {
			foreach ($recv_list as $key => $value) {
				$value = trim($value);
				if ($value) {
					$select = 'mem_id, mem_denied, mem_use_note, mem_userid, mem_nickname';
					$mem = $this->Member_model->get_by_userid($value, $select);
					if (element('mem_id', $mem)
						&& element('mem_denied', $mem) === '0'
						&& element('mem_use_note', $mem) === '1') {
						$mem_list['mem_id'] = element('mem_id', $mem);
						$mem_list['mem_userid'] = element('mem_userid', $mem);
						$mem_list['mem_nickname'] = element('mem_nickname', $mem);
					} else {
						$error_list[] = $value;
					}
				}
			}
		}

		$error_msg = implode(',', $error_list);

		if ($error_msg && $this->member->is_admin() !== 'super') {
			$this->form_validation->set_message(
				'_check_userid',
				$error_msg . ' 는 쪽지를 받을 수 없는 회원아이디입니다. <br />쪽지를 발송하지 않았습니다'
			);
			return false;
		}
		if ($this->member->is_admin() !== 'super') {
			if (count(element('mem_id', $mem_list))) {
				$point = $this->cbconfig->item('point_note') * count(element('mem_id', $mem_list));
				if ($point) {
					if ($this->member->item('mem_point') - $point < 0) {
						$this->form_validation->set_message(
							'_check_userid',
							'보유하신 포인트(' . number_format($this->member->item('mem_point')) . '점)가 모자라서 쪽지를 보낼 수 없습니다'
						);
						return false;
					}
				}
			}
		}
		return true;
	}


	/**
	 * 쪽지를 삭제하는 함수입니다
	 */
	public function delete($note_type = 'recv', $note_id = 0)
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_note_delete';
		$this->load->event($eventname);

		/**
		 * 로그인이 필요한 페이지입니다
		 */
		required_user_login();

		$mem_id = (int) $this->member->item('mem_id');

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		if ( ! $this->cbconfig->item('use_note')) {
			alert_close('쪽지 기능을 사용하지 않는 사이트입니다');
			return false;
		} elseif ( ! $this->member->item('mem_use_note') && $this->member->is_admin() !== 'super') {
			alert_close('회원님은 쪽지 기능을 사용하지 않는 중이십니다');
			return false;
		}

		$note_id = (int) $note_id;
		if (empty($note_id) OR $note_id < 1) {
			show_404();
		}

		$note = $this->Note_model->get_one($note_id);
		if ( ! element('nte_id', $note)) {
			show_404();
		}
		if (element('nte_type', $note) === '1') {
			if ($note_type !== element('nte_type', $note)) {
				show_404();
			}
			if ((int) element('recv_mem_id', $note) !== $mem_id) {
				show_404();
			}
			$note_list = 'recv';
		} elseif (element('nte_type', $note) === '2') {
			if ($note_type !== element('nte_type', $note)) {
				show_404();
			}
			if ((int) element('send_mem_id', $note) !== $mem_id) {
				show_404();
			}
			$note_list = 'send';
		}

		$this->Note_model->delete($note_id);

		$where = array(
			'recv_mem_id' => $mem_id,
			'nte_type' => 1,
		);
		$this->db->where($where);
		$this->db->group_start();
		$this->db->where(array('nte_read_datetime <=' => '0000-00-00 00:00:00'));
		$this->db->or_where(array('nte_read_datetime' => null));
		$this->db->group_end();
		$cnt = $this->db->count_all_results('note');
		$updatedata = array(
			'meta_unread_note_num' => $cnt,
		);
		$this->load->model('Member_meta_model');
		$this->Member_meta_model->save($mem_id, $updatedata);

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

		redirect('note/lists/' . $note_list . '?' . $param->output());
	}
}
