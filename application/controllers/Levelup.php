<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Levelup class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 레벨업 담당하는 controller 입니다.
 */
class Levelup extends CB_Controller
{

	/**
	 * 모델을 로딩합니다
	 */
	protected $models = array('Member');

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
	 * 레벨업 페이지입니다
	 */
	public function index()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_levelup_index';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		if ( ! $this->cbconfig->item('use_levelup')) {
			alert('이 웹사이트는 레벨업 기능을 사용하지 않습니다');
		}

		$mem_id = (int) $this->member->item('mem_id');

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		$next_level = 0;
		$postnum = 0;
		$commentnum = 0;
		$register = 0;
		$point_use = 0;

		if ($mem_id) {
			$this->load->model(array('Post_model', 'Comment_model'));
			$where = array(
				'mem_id' => $mem_id,
				'post_del' => 0,
			);
			$postnum = $this->Post_model->count_by($where);
			$where = array(
				'mem_id' => $mem_id,
				'cmt_del' => 0,
			);
			$commentnum = $this->Comment_model->count_by($where);
			$regtime = strtotime(substr($this->member->item('mem_register_datetime'),0,10));
			$totime = strtotime(cdate('Y-m-d'));
			$register = (($totime - $regtime) / 86400) + 1;

			$view['view']['postnum'] = $postnum;
			$view['view']['commentnum'] = $commentnum;
			$view['view']['register'] = $register;

			$next_level = $this->member->item('mem_level') + 1;
			$levelup_available = true;

			$levelupconfig = json_decode($this->cbconfig->item('levelupconfig'), true);
			if ( ! in_array($next_level, element('use', $levelupconfig))) {
				$levelup_available = false;
			}
			if (element($next_level, element('point_required', $levelupconfig))
				&& element($next_level, element('point_required', $levelupconfig)) > $this->member->item('mem_point')) {
				$levelup_available = false;
			}
			if (element($next_level, element('post_num', $levelupconfig))
				&& element($next_level, element('post_num', $levelupconfig)) > $postnum) {
				$levelup_available = false;
			}
			if (element($next_level, element('comment_num', $levelupconfig))
				&& element($next_level, element('comment_num', $levelupconfig)) > $commentnum) {
				$levelup_available = false;
			}

			$view['view']['next_level'] = $next_level;
			$view['view']['levelup_available'] = $levelup_available;
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
				'field' => 'is_submit',
				'label' => '레벨업',
				'rules' => 'trim|required|callback__chk_levelup[' . $postnum . ',' . $commentnum . ',' . $register . ']',
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

			$updatedata = array(
				'mem_level' => $next_level,
			);
			$this->Member_model->update($mem_id, $updatedata);

			$this->load->model('Member_level_history_model');
			$levelhistoryinsert = array(
				'mem_id' => $mem_id,
				'mlh_from' => $this->member->item('mem_level'),
				'mlh_to' => $next_level,
				'mlh_datetime' => cdate('Y-m-d H:i:s'),
				'mlh_reason' => '레벨업',
				'mlh_ip' => $this->input->ip_address(),
			);
			$this->Member_level_history_model->insert($levelhistoryinsert);

			$point_use = (-1) * abs(element($next_level, element('point_use', $levelupconfig)));
			if ($point_use < 0) {
				$this->load->library('point');
				$point_title = '레벨업 Lv ' . $this->member->item('mem_level') . '-> ' . $next_level;
				$point = $this->point->insert_point(
					$mem_id,
					$point_use,
					$point_title,
					'levelup',
					$next_level,
					'레벨업'
				);
			}

			// 이벤트가 존재하면 실행합니다
			$view['view']['event']['after'] = Events::trigger('after', $eventname);

			$this->session->set_flashdata(
				'message',
				'레벨업이 완료되었습니다. 감사합니다'
			);
			redirect('levelup', 'refresh');
		}

		$view['view']['canonical'] = site_url('levelup');

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 레이아웃을 정의합니다
		 */
		$page_title = $this->cbconfig->item('site_meta_title_levelup');
		$meta_description = $this->cbconfig->item('site_meta_description_levelup');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_levelup');
		$meta_author = $this->cbconfig->item('site_meta_author_levelup');
		$page_name = $this->cbconfig->item('site_page_name_levelup');

		$layoutconfig = array(
			'path' => 'levelup',
			'layout' => 'layout',
			'skin' => 'levelup',
			'layout_dir' => $this->cbconfig->item('layout_levelup'),
			'mobile_layout_dir' => $this->cbconfig->item('mobile_layout_levelup'),
			'use_sidebar' => $this->cbconfig->item('sidebar_levelup'),
			'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_levelup'),
			'skin_dir' => $this->cbconfig->item('skin_levelup'),
			'mobile_skin_dir' => $this->cbconfig->item('mobile_skin_levelup'),
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


	public function _chk_levelup($str, $param)
	{
		$param = preg_split('/,/', $param);
		$postnum = $param[0];
		$commentnum = $param[1];
		$register = $param[2];

		if ( ! $this->cbconfig->item('use_levelup')) {
			$this->form_validation->set_message(
				'_chk_levelup',
				'현재 이 사이트는 레벨업 기능을 사용하지 않습니다'
			);
			return false;
		}
		if ($this->member->is_member() === false) {
			$this->form_validation->set_message(
				'_chk_levelup',
				'로그인 후 이용해주세요'
			);
			return false;
		}

		$next_level = $this->member->item('mem_level') + 1;
		$levelupconfig = json_decode($this->cbconfig->item('levelupconfig'), true);

		if ( ! in_array($next_level, element('use', $levelupconfig))) {
			$this->form_validation->set_message(
				'_chk_levelup',
				'다음 레벨로의 레벨업 기능을 사용하지 않습니다'
			);
			return false;
		}

		if (element($next_level, element('register', $levelupconfig))
			&& element($next_level, element('register', $levelupconfig)) > $register) {
			$this->form_validation->set_message(
				'_chk_levelup',
				'회원님의 가입일이 레벨업 가능한 가입일 조건보다 작아 레벨업을 진행할 수 없습니다'
			);
			return false;
		}

		if (element($next_level, element('point_required', $levelupconfig))
			&& element($next_level, element('point_required', $levelupconfig)) > $this->member->item('mem_point')) {
			$this->form_validation->set_message(
				'_chk_levelup',
				'회원님의 현재 보유하신 포인트가 레벨업 가능한 보유포인트보다 작아 레벨업을 진행할 수 없습니다'
			);
			return false;
		}

		if (element($next_level, element('post_num', $levelupconfig))
			&& element($next_level, element('post_num', $levelupconfig)) > $postnum) {
			$this->form_validation->set_message(
				'_chk_levelup',
				'회원님의 현재 작성하신 글 개수가 레벨업 가능한 글 작성 개수보다 작아 레벨업을 진행할 수 없습니다'
			);
			return false;
		}

		if (element($next_level, element('comment_num', $levelupconfig))
			&& element($next_level, element('comment_num', $levelupconfig)) > $commentnum) {
			$this->form_validation->set_message(
				'_chk_levelup',
				'회원님의 현재 작성하신 댓글 개수가 레벨업 가능한 댓글 작성 개수보다 작아 레벨업을 진행할 수 없습니다'
			);
			return false;
		}

		return true;
	}
}
