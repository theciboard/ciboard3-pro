<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Profile class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 회원님의 프로필 보기 페이지에 필요한 controller 입니다.
 */
class Profile extends CB_Controller
{

	/**
	 * 모델을 로딩합니다
	 */
	protected $models = array('Follow', 'Member_meta', 'Member_extra_vars');

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
	 * 프로필보기 페이지입니다
	 */
	 public function index($userid = '')
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_profile_index';
		$this->load->event($eventname);

		/**
		 * 로그인이 필요한 페이지입니다
		 */
		required_user_login();

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		if (empty($userid)) {
			show_404();
		}

		if ( ! $this->member->item('mem_open_profile') && $this->member->is_admin() !== 'super' && $this->member->item('mem_userid') !== $userid) {
			alert_close('회원님이 프로필 공개를 하지 않으셨으므로, 상대방의 프로필을 볼 수 없습니다. 먼저 회원님의 프로필을 공개해주세요');
			return false;
		}

		$view['view']['member'] = $member = $this->Member_model->get_by_userid($userid);
		$member_meta = $this->Member_meta_model->get_all_meta(element('mem_id', $member));
		if (is_array($member_meta)) {
			$view['view']['member'] = $member = array_merge($view['view']['member'], $member_meta);
		}
		if ( ! element('mem_id', $member)) {
			alert_close('존재하지 않는 회원아이디입니다');
			return false;
		}
		if (element('mem_denied', $member)) {
			alert_close('탈퇴 또는 차단된 회원아이디입니다');
			return false;
		}
		$member_extra_vars = $this->Member_extra_vars_model->get_all_meta(element('mem_id', $member));
		if (is_array($member_extra_vars)) {
			$view['view']['member'] = $member = array_merge($view['view']['member'], $member_extra_vars);
		}

		if ( ! element('mem_open_profile', $member)
			&& $this->member->is_admin() !== 'super'
			&& $this->member->item('mem_userid') !== $userid) {
			alert_close(html_escape(element('mem_nickname', $member)) . ' 님이 프로필을 공개하지 않으셨습니다');
			return false;
		}

		$registerform = $this->cbconfig->item('registerform');
		$form = json_decode($registerform, true);
		$display = array();
		if ($form && is_array($form)) {
			foreach ($form as $key => $value) {
				if (element('use', $value) && element('open', $value)) {
					if (element('field_name', $value) === 'mem_address') {
						$value['value']
							= element('mem_zipcode', $member) . ' ' .
							element('mem_address1', $member) . ' ' .
							element('mem_address2', $member) . ' ' .
							element('mem_address3', $member);
					} else {
						$value['value'] = element(element('field_name', $value), $member);
						if (element('field_type', $value) === 'checkbox') {
							$tmp_value = json_decode($value['value']);
							$tmp = '';
							if ($tmp_value) {
								foreach ($tmp_value as $val) {
									if ($tmp) {
										$tmp .= ', ';
									}
									$tmp .= $val;
								}
							}
							$value['value'] = $tmp;
						}
					}
					$display[] = $value;
				}
			}
		}
		$view['data'] = $display;

		$view['view']['canonical'] = site_url('profile/' . $userid);

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 레이아웃을 정의합니다
		 */
		$page_title = $this->cbconfig->item('site_meta_title_profile');
		$meta_description = $this->cbconfig->item('site_meta_description_profile');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_profile');
		$meta_author = $this->cbconfig->item('site_meta_author_profile');
		$page_name = $this->cbconfig->item('site_page_name_profile');

		$searchconfig = array(
			'{프로필회원명}',
			'{프로필회원아이디}',
		);
		$replaceconfig = array(
			element('mem_nickname', $member),
			element('mem_userid', $member),
		);

		$page_title = str_replace($searchconfig, $replaceconfig, $page_title);
		$meta_description = str_replace($searchconfig, $replaceconfig, $meta_description);
		$meta_keywords = str_replace($searchconfig, $replaceconfig, $meta_keywords);
		$meta_author = str_replace($searchconfig, $replaceconfig, $meta_author);
		$page_name = str_replace($searchconfig, $replaceconfig, $page_name);

		$layoutconfig = array(
			'path' => 'profile',
			'layout' => 'layout_popup',
			'skin' => 'profile',
			'layout_dir' => $this->cbconfig->item('layout_profile'),
			'mobile_layout_dir' => $this->cbconfig->item('mobile_layout_profile'),
			'skin_dir' => $this->cbconfig->item('skin_profile'),
			'mobile_skin_dir' => $this->cbconfig->item('mobile_skin_profile'),
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
	 * 회원닉네임 클릭시 나타나는 사이드뷰 보기 관련 함수입니다
	 */
	public function sideview($userid = '')
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_profile_sideview';
		$this->load->event($eventname);

		if (empty($userid)) {
			return false;
		}

		$result = array();
		$this->output->set_content_type('application/json');

		// 이벤트가 존재하면 실행합니다
		$result['event']['before'] = Events::trigger('before', $eventname);

		if ($this->member->is_member() === false) {
			$result = array('error' => '로그인후 이용해주세요');
			exit(json_encode($result));
		}
		if (empty($userid)) {
			$result = array('error' => '잘못된 접근입니다');
			exit(json_encode($result));
		}

		$mem_id = (int) $this->member->item('mem_id');

		$select = 'mem_id, mem_nickname, mem_homepage, mem_receive_email, mem_use_note, mem_open_profile, mem_denied';
		$member = $this->Member_model->get_by_userid($userid, $select);
		if ( ! element('mem_id', $member)) {
			$result = array('error' => '존재하지 않는 회원 아이디입니다');
			exit(json_encode($result));
		}
		if (element('mem_denied', $member)) {
			$result = array('error' => '탈퇴 또는 차단된 회원 아이디입니다');
			exit(json_encode($result));
		}
		$countwhere = array(
			'mem_id' => $mem_id,
			'target_mem_id' => element('mem_id', $member),
		);
		$followcount = $this->Follow_model->count_by($countwhere);
		$result = array();
		$result['success'] = html_escape(element('mem_nickname', $member)) . ' 님의 사이드뷰';
		$result['name'] = html_escape(element('mem_nickname', $member));

		if ( ! $this->cbconfig->item('use_note')) {
			$result['note'] = '10'; // 쪽지 기능을 사용하지 않는 사이트
		} elseif ( ! $this->member->item('mem_use_note') && $this->member->is_admin() !== 'super') {
			$result['note'] = '3'; // 내가 쪽지 기능을 사용하지 않고 있음
		} elseif ($member['mem_use_note']) {
			$result['note'] = '1'; // 쪽지 기능 사용
		} else {
			$result['note'] = '2'; // 상대방이 쪽지 기능을 사용하지 않고 있음
		}

		if ($this->cbconfig->get_device_view_type() !== 'mobile'
			&& ! $this->cbconfig->item('use_sideview_email')) {
			$result['email'] = '10'; // 이메일 기능을 사용하지 않는 사이트
		} elseif ($this->cbconfig->get_device_view_type() === 'mobile'
			&& ! $this->cbconfig->item('use_mobile_sideview_email')) {
			$result['email'] = '10'; // 이메일 기능을 사용하지 않는 사이트
		} elseif ( ! $this->member->item('mem_receive_email')
			&& $this->member->is_admin() !== 'super') {
			$result['email'] = '3'; // 내가 이메일수신 기능을 사용하지 않고 있음
		} elseif ($member['mem_receive_email']
			OR $this->member->is_admin() === 'super') {
			$result['email'] = '1'; // 이메일수신 기능 사용
		} else {
			$result['email'] = '2'; // 상대방이 이메일수신 기능을 사용하지 않고 있음
		}

		if ( ! $this->member->item('mem_open_profile')
			&& $this->member->is_admin() !== 'super') {
			$result['profile'] = '3'; // 내가 프로필공개 기능을 사용하지 않고 있음
		} elseif ($member['mem_open_profile']
			OR $this->member->is_admin() === 'super') {
			$result['profile'] = '1'; // 프로필공개 기능 사용
		} else {
			$result['profile'] = '2'; // 상대방이 프로필공개 기능을 사용하지 않고 있음
		}

		$result['following'] = $followcount;
		$result['homepage'] = html_escape(element('mem_homepage', $member));
		$result['memid'] = $this->member->is_admin() === 'super' ? element('mem_id', $member) : '';

		// 이벤트가 존재하면 실행합니다
		$result['event']['after'] = Events::trigger('after', $eventname);

		exit(json_encode($result));
	}


	/**
	 * 친구추가 관련 함수입니다
	 */
	public function add_follow($userid = '')
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_profile_add_follow';
		$this->load->event($eventname);

		if (empty($userid)) {
			return false;
		}

		$result = array();
		$this->output->set_content_type('application/json');

		// 이벤트가 존재하면 실행합니다
		$resutl['event']['after'] = Events::trigger('before', $eventname);

		if ($this->member->is_member() === false) {
			$result = array('error' => '로그인후 이용해주세요');
			exit(json_encode($result));
		}
		if (empty($userid)) {
			$result = array('error' => '잘못된 접근입니다');
			exit(json_encode($result));
		}

		$mem_id = (int) $this->member->item('mem_id');

		$select = 'mem_id, mem_nickname, mem_homepage, mem_receive_email, mem_use_note, mem_open_profile, mem_denied';
		$target = $this->Member_model->get_by_userid($userid, $select);
		if ( ! element('mem_id', $target)) {
			$result = array('error' => '잘못된 접근입니다');
			exit(json_encode($result));
		}
		if (element('mem_denied', $target)) {
			$result = array('error' => '탈퇴 또는 차단된 회원입니다');
			exit(json_encode($result));
		}
		if ((int) element('mem_id', $target) === $mem_id) {
			$result = array('error' => '자기자신을 친구로 등록할 수 없습니다');
			exit(json_encode($result));
		}
		$countwhere = array(
			'mem_id' => $mem_id,
			'target_mem_id' => element('mem_id', $target),
		);
		$followcount = $this->Follow_model->count_by($countwhere);
		if ($followcount > 0) {
			$result = array('error' => '이미 친구로 등록된 회원입니다');
			exit(json_encode($result));
		}
		$insertdata = array(
			'mem_id' => $mem_id,
			'target_mem_id' => element('mem_id', $target),
			'fol_datetime' => cdate('Y-m-d H:i:s'),
		);
		$this->Follow_model->insert($insertdata);

		$countwhere = array(
			'target_mem_id' => element('mem_id', $target),
		);
		$target_count = $this->Follow_model->count_by($countwhere);

		$countwhere = array(
			'mem_id' => $mem_id,
		);
		$my_count = $this->Follow_model->count_by($countwhere);

		$updatedata = array(
			'mem_followed' => $target_count,
		);
		$this->Member_model->update(element('mem_id', $target), $updatedata);

		$updatedata = array(
			'mem_following' => $my_count,
		);
		$this->Member_model->update($this->member->item('mem_id'), $updatedata);

		$result['success'] = html_escape(element('mem_nickname', $target)) . ' 님을 Following 하셨습니다';
		$result['my_count'] = $my_count;
		$result['target_count'] = $target_count;

		// 이벤트가 존재하면 실행합니다
		$resutl['event']['after'] = Events::trigger('after', $eventname);

		exit(json_encode($result));
	}


	/**
	 * 친구해제 관련 함수입니다
	 */
	public function delete_follow($userid = '')
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_profile_delete_follow';
		$this->load->event($eventname);

		if (empty($userid)) {
			return false;
		}

		$result = array();
		$this->output->set_content_type('application/json');

		// 이벤트가 존재하면 실행합니다
		$resutl['event']['before'] = Events::trigger('before', $eventname);

		if ($this->member->is_member() === false) {
			$result = array('error' => '로그인후 이용해주세요');
			exit(json_encode($result));
		}
		if (empty($userid)) {
			$result = array('error' => '잘못된 접근입니다');
			exit(json_encode($result));
		}

		$mem_id = (int) $this->member->item('mem_id');

		$select = 'mem_id, mem_nickname, mem_homepage, mem_receive_email,
			mem_use_note, mem_open_profile, mem_denied';
		$target = $this->Member_model->get_by_userid($userid, $select);
		if ( ! element('mem_id', $target)) {
			$result = array('error' => '잘못된 접근입니다');
			exit(json_encode($result));
		}
		$countwhere = array(
			'mem_id' => $mem_id,
			'target_mem_id' => element('mem_id', $target),
		);
		$followcount = $this->Follow_model->count_by($countwhere);
		if ($followcount === 0) {
			$result = array('error' => '아직 친구로 등록되지 않은 회원입니다');
			exit(json_encode($result));
		}
		$deletewhere = array(
			'mem_id' => $mem_id,
			'target_mem_id' => element('mem_id', $target),
		);
		$this->Follow_model->delete_where($deletewhere);

		$countwhere = array(
			'target_mem_id' => element('mem_id', $target),
		);
		$target_count = $this->Follow_model->count_by($countwhere);

		$countwhere = array(
			'mem_id' => $mem_id,
		);
		$my_count = $this->Follow_model->count_by($countwhere);

		$updatedata = array(
			'mem_followed' => $target_count,
		);
		$this->Member_model->update(element('mem_id', $target), $updatedata);

		$updatedata = array(
			'mem_following' => $my_count,
		);
		$this->Member_model->update($mem_id, $updatedata);

		$result['success'] = html_escape(element('mem_nickname', $target)) . ' 님이 Follow 해제되었습니다';
		$result['my_count'] = $my_count;
		$result['target_count'] = $target_count;

		// 이벤트가 존재하면 실행합니다
		$resutl['event']['after'] = Events::trigger('after', $eventname);

		exit(json_encode($result));
	}
}
