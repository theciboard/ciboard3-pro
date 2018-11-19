<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Members class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 관리자>회원설정>회원관리 controller 입니다.
 */
class Members extends CB_Controller
{

	/**
	 * 관리자 페이지 상의 현재 디렉토리입니다
	 * 페이지 이동시 필요한 정보입니다
	 */
	public $pagedir = 'member/members';

	/**
	 * 모델을 로딩합니다
	 */
	protected $models = array('Member_meta', 'Member_group', 'Member_group_member', 'Member_nickname', 'Member_extra_vars', 'Member_userid', 'Social_meta');

	/**
	 * 이 컨트롤러의 메인 모델 이름입니다
	 */
	protected $modelname = 'Member_model';

	/**
	 * 헬퍼를 로딩합니다
	 */
	protected $helpers = array('form', 'array', 'chkstring');

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
		$eventname = 'event_admin_member_members_index';
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
			'mem_id' => $param->sort('mem_id', 'asc'),
			'mem_userid' => $param->sort('mem_userid', 'asc'),
			'mem_username' => $param->sort('mem_username', 'asc'),
			'mem_nickname' => $param->sort('mem_nickname', 'asc'),
			'mem_email' => $param->sort('mem_email', 'asc'),
			'mem_point' => $param->sort('mem_point', 'asc'),
			'mem_register_datetime' => $param->sort('mem_register_datetime', 'asc'),
			'mem_lastlogin_datetime' => $param->sort('mem_lastlogin_datetime', 'asc'),
			'mem_level' => $param->sort('mem_level', 'asc'),
		);
		$findex = $this->input->get('findex', null, 'member.mem_id');
		$forder = $this->input->get('forder', null, 'desc');
		$sfield = $this->input->get('sfield', null, '');
		$skeyword = $this->input->get('skeyword', null, '');

		$per_page = admin_listnum();
		$offset = ($page - 1) * $per_page;

		/**
		 * 게시판 목록에 필요한 정보를 가져옵니다.
		 */
		$this->{$this->modelname}->allow_search_field = array('mem_id', 'mem_userid', 'mem_email', 'mem_username', 'mem_nickname', 'mem_level', 'mem_homepage', 'mem_register_datetime', 'mem_register_ip', 'mem_lastlogin_datetime', 'mem_lastlogin_ip', 'mem_is_admin'); // 검색이 가능한 필드
		$this->{$this->modelname}->search_field_equal = array('mem_id', 'mem_level', 'mem_is_admin'); // 검색중 like 가 아닌 = 검색을 하는 필드
		$this->{$this->modelname}->allow_order_field = array('member.mem_id', 'mem_userid', 'mem_username', 'mem_nickname', 'mem_email', 'mem_point', 'mem_register_datetime', 'mem_lastlogin_datetime', 'mem_level'); // 정렬이 가능한 필드

		$where = array();
		if ($this->input->get('mem_is_admin')) {
			$where['mem_is_admin'] = 1;
		}
		if ($this->input->get('mem_denied')) {
			$where['mem_denied'] = 1;
		}
		if ($mgr_id = (int) $this->input->get('mgr_id')) {
			if ($mgr_id > 0) {
				$where['mgr_id'] = $mgr_id;
			}
		}
		$result = $this->{$this->modelname}
			->get_admin_list($per_page, $offset, $where, '', $findex, $forder, $sfield, $skeyword);
		$list_num = $result['total_rows'] - ($page - 1) * $per_page;

		if (element('list', $result)) {
			foreach (element('list', $result) as $key => $val) {

				$where = array(
					'mem_id' => element('mem_id', $val),
				);
				$result['list'][$key]['member_group_member'] = $this->Member_group_member_model->get('', '', $where, '', 0, 'mgm_id', 'ASC');
				$mgroup = array();
				if ($result['list'][$key]['member_group_member']) {
					foreach ($result['list'][$key]['member_group_member'] as $mk => $mv) {
						if (element('mgr_id', $mv)) {
							$mgroup[] = $this->Member_group_model->item(element('mgr_id', $mv));
						}
					}
				}
				$result['list'][$key]['member_group'] = '';
				if ($mgroup) {
					foreach ($mgroup as $mk => $mv) {
						if ($result['list'][$key]['member_group']) {
							$result['list'][$key]['member_group'] .= ', ';
						}
						$result['list'][$key]['member_group'] .= element('mgr_title', $mv);
					}
				}
				$result['list'][$key]['display_name'] = display_username(
					element('mem_userid', $val),
					element('mem_nickname', $val),
					element('mem_icon', $val)
				);
				$result['list'][$key]['meta'] = $this->Member_meta_model->get_all_meta(element('mem_id', $val));
				$result['list'][$key]['social'] = $this->Social_meta_model->get_all_meta(element('mem_id', $val));

				$result['list'][$key]['num'] = $list_num--;
			}
		}

		$view['view']['data'] = $result;
		$view['view']['all_group'] = $this->Member_group_model->get_all_group();

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
		$search_option = array('mem_userid' => '회원아이디', 'mem_email' => '이메일', 'mem_username' => '회원명', 'mem_nickname' => '닉네임', 'mem_level' => '회원레벨', 'mem_homepage' => '홈페이지', 'mem_register_datetime' => '회원가입날짜', 'mem_register_ip' => '회원가입IP', 'mem_lastlogin_datetime' => '최종로그인날짜', 'mem_lastlogin_ip' => '최종로그인IP', 'mem_adminmemo' => '관리자메모');
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
		$eventname = 'event_admin_member_members_write';
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
			$getdata['extras'] = $this->Member_extra_vars_model->get_all_meta($pid);
			$getdata['meta'] = $this->Member_meta_model->get_all_meta($pid);
			$where = array(
				'mem_id' => $pid,
			);
			$group_member = $this->Member_group_member_model->get('', '', $where);
			if ($group_member) {
				foreach ($group_member as $mkey => $mval) {
					$getdata['member_group_member'][] = element('mgr_id', $mval);
				}
			}
		}
		$getdata['config_max_level'] = $this->cbconfig->item('max_level');
		$getdata['mgroup'] = $this->Member_group_model->get_all_group();
		$registerform = $this->cbconfig->item('registerform');
		$form = json_decode($registerform, true);

		/**
		 * Validation 라이브러리를 가져옵니다
		 */
		$this->load->library('form_validation');

		 if ( ! function_exists('password_hash')) {
			$this->load->helper('password');
		}

		/**
		 * 전송된 데이터의 유효성을 체크합니다
		 */
		$config = array(
			array(
				'field' => 'mem_username',
				'label' => '이름',
				'rules' => 'trim|min_length[2]|max_length[20]',
			),
			array(
				'field' => 'mem_level',
				'label' => '레벨',
				'rules' => 'trim|required|numeric|less_than_equal_to[' . element('config_max_level', $getdata) . ']|is_natural_no_zero',
			),
			array(
				'field' => 'mem_homepage',
				'label' => '홈페이지',
				'rules' => 'valid_url',
			),
			array(
				'field' => 'mem_birthday',
				'label' => '생일',
				'rules' => 'trim|exact_length[10]',
			),
			array(
				'field' => 'mem_sex',
				'label' => '성별',
				'rules' => 'trim|exact_length[1]',
			),
			array(
				'field' => 'mem_phone',
				'label' => '전화번호',
				'rules' => 'trim|valid_phone',
			),
			array(
				'field' => 'mem_zipcode',
				'label' => '우편번호',
				'rules' => 'trim|min_length[5]|max_length[7]',
			),
			array(
				'field' => 'mem_address1',
				'label' => '기본주소',
				'rules' => 'trim',
			),
			array(
				'field' => 'mem_address2',
				'label' => '상세주소',
				'rules' => 'trim',
			),
			array(
				'field' => 'mem_address3',
				'label' => '참고항목',
				'rules' => 'trim',
			),
			array(
				'field' => 'mem_address4',
				'label' => '지번',
				'rules' => 'trim',
			),
			array(
				'field' => 'mem_profile_content',
				'label' => '자기소개',
				'rules' => 'trim',
			),
			array(
				'field' => 'mem_open_profile',
				'label' => '정보공개',
				'rules' => 'trim|exact_length[1]',
			),
			array(
				'field' => 'mem_use_note',
				'label' => '쪽지사용',
				'rules' => 'trim|exact_length[1]',
			),
			array(
				'field' => 'mem_receive_email',
				'label' => '이메일수신여부',
				'rules' => 'trim|exact_length[1]',
			),
			array(
				'field' => 'mem_receive_sms',
				'label' => 'SMS 문자수신여부',
				'rules' => 'trim|exact_length[1]',
			),
		);
		if ($this->input->post($primary_key)) {
			$config[] = array(
				'field' => 'mem_userid',
				'label' => '회원아이디',
				'rules' => 'trim|required|alphanumunder|min_length[3]|max_length[20]|is_unique[member_userid.mem_userid.mem_id.' . element('mem_id', $getdata) . ']|callback__mem_userid_check',
			);
			$config[] = array(
				'field' => 'mem_password',
				'label' => '패스워드',
				'rules' => 'trim|min_length[4]',
			);
			$config[] = array(
				'field' => 'mem_email',
				'label' => '회원이메일',
				'rules' => 'trim|required|valid_email|is_unique[member.mem_email.mem_id.' . element('mem_id', $getdata) . ']',
			);
			$config[] = array(
				'field' => 'mem_nickname',
				'label' => '회원닉네임',
				'rules' => 'trim|required|min_length[2]|max_length[20]|callback__mem_nickname_check|is_unique[member.mem_nickname.mem_id.' . element('mem_id', $getdata) . ']',
			);
		} else {
			$config[] = array(
				'field' => 'mem_userid',
				'label' => '회원아이디',
				'rules' => 'trim|required|alphanumunder|min_length[3]|max_length[20]|is_unique[member_userid.mem_userid]',
			);
			$config[] = array(
				'field' => 'mem_password',
				'label' => '패스워드',
				'rules' => 'trim|required|min_length[4]',
			);
			$config[] = array(
				'field' => 'mem_email',
				'label' => '회원이메일',
				'rules' => 'trim|required|valid_email|is_unique[member.mem_email]',
			);
			$config[] = array(
				'field' => 'mem_nickname',
				'label' => '회원닉네임',
				'rules' => 'trim|required|min_length[2]|max_length[20]|callback__mem_nickname_check|is_unique[member.mem_nickname]',
			);
		}
		$this->form_validation->set_rules($config);
		$form_validation = $this->form_validation->run();
		$file_error = '';
		$updatephoto = '';
		$file_error2 = '';
		$updateicon = '';

		if ($form_validation) {
			$this->load->library('upload');
			if (isset($_FILES) && isset($_FILES['mem_photo']) && isset($_FILES['mem_photo']['name']) && $_FILES['mem_photo']['name']) {
				$upload_path = config_item('uploads_dir') . '/member_photo/';
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
				$uploadconfig['allowed_types'] = 'jpg|jpeg|png|gif';
				$uploadconfig['max_size'] = '2000';
				$uploadconfig['max_width'] = '1000';
				$uploadconfig['max_height'] = '1000';
				$uploadconfig['encrypt_name'] = true;

				$this->upload->initialize($uploadconfig);

				if ($this->upload->do_upload('mem_photo')) {
					$img = $this->upload->data();
					$updatephoto = cdate('Y') . '/' . cdate('m') . '/' . element('file_name', $img);
				} else {
					$file_error = $this->upload->display_errors();

				}
			}

			if (isset($_FILES)
				&& isset($_FILES['mem_icon'])
				&& isset($_FILES['mem_icon']['name'])
				&& $_FILES['mem_icon']['name']) {
				$upload_path = config_item('uploads_dir') . '/member_icon/';
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
				$uploadconfig['allowed_types'] = 'jpg|jpeg|png|gif';
				$uploadconfig['max_size'] = '2000';
				$uploadconfig['max_width'] = '1000';
				$uploadconfig['max_height'] = '1000';
				$uploadconfig['encrypt_name'] = true;

				$this->upload->initialize($uploadconfig);

				if ($this->upload->do_upload('mem_icon')) {
					$img = $this->upload->data();
					$updateicon = cdate('Y') . '/' . cdate('m') . '/' . element('file_name', $img);
				} else {
					$file_error2 = $this->upload->display_errors();

				}
			}
		}

		/**
		 * 유효성 검사를 하지 않는 경우, 또는 유효성 검사에 실패한 경우입니다.
		 * 즉 글쓰기나 수정 페이지를 보고 있는 경우입니다
		 */
		if ($form_validation === false OR $file_error !== '' OR $file_error2 !== '') {

			// 이벤트가 존재하면 실행합니다
			$view['view']['event']['formrunfalse'] = Events::trigger('formrunfalse', $eventname);

			$view['view']['message'] = $file_error . $file_error2;

			$view['view']['data'] = $getdata;

			if (empty($pid)) {
				$view['view']['data']['mem_receive_email'] = 1;
				$view['view']['data']['mem_use_note'] = 1;
				$view['view']['data']['mem_receive_sms'] = 1;
				$view['view']['data']['mem_open_profile'] = 1;
			}

			/**
			 * primary key 정보를 저장합니다
			 */
			$view['view']['primary_key'] = $primary_key;

			$html_content = '';
			$k = 0;
			if ($form && is_array($form)) {
				foreach ($form as $key => $value) {
					if ( ! element('use', $value)) {
						continue;
					}
					if (element('func', $value) === 'basic') {
						continue;
					}
					$required = element('required', $value) ? 'required' : '';

					$item = element(element('field_name', $value), element('extras', $getdata));
					$html_content[$k]['field_name'] = element('field_name', $value);
					$html_content[$k]['display_name'] = element('display_name', $value);
					$html_content[$k]['input'] = '';

					//field_type : text, url, email, phone, textarea, radio, select, checkbox, date
					if (element('field_type', $value) === 'text'
						OR element('field_type', $value) === 'url'
						OR element('field_type', $value) === 'email'
						OR element('field_type', $value) === 'phone'
						OR element('field_type', $value) === 'date') {
						if (element('field_type', $value) === 'date') {
							$html_content[$k]['input'] .= '<input type="text" id="' . element('field_name', $value) . '" name="' . element('field_name', $value) . '" class="form-control datepicker" value="' . set_value(element('field_name', $value), $item) . '" readonly="readonly" ' . $required . ' />';
						} elseif (element('field_type', $value) === 'phone') {
							$html_content[$k]['input'] .= '<input type="text" id="' . element('field_name', $value) . '" name="' . element('field_name', $value) . '" class="form-control validphone" value="' . set_value(element('field_name', $value), $item) . '" ' . $required . ' />';
						} else {
							$html_content[$k]['input'] .= '<input type="' . element('field_type', $value) . '" id="' . element('field_name', $value) . '" name="' . element('field_name', $value) . '" class="form-control" value="' . set_value(element('field_name', $value), $item) . '" ' . $required . ' />';
						}
					} elseif (element('field_type', $value) === 'textarea') {
						$html_content[$k]['input'] .= '<textarea id="' . element('field_name', $value) . '" name="' . element('field_name', $value) . '" class="form-control" ' . $required . ' >' . set_value(element('field_name', $value), $item) . '</textarea>';
					} elseif (element('field_type', $value) === 'radio') {
						$html_content[$k]['input'] .= '<div class="checkbox">';
						$options = explode("\n", element('options', $value));
						$i =1;
						if ($options) {
							foreach ($options as $okey => $oval) {
								$oval = trim($oval);
								$radiovalue = (element('field_name', $value) === 'mem_sex') ? $okey : $oval;
								$html_content[$k]['input'] .= '<label for="' . element('field_name', $value) . '_' . $i . '"><input type="radio" name="' . element('field_name', $value) . '" id="' . element('field_name', $value) . '_' . $i . '" value="' . $radiovalue . '" ' . set_radio(element('field_name', $value), $radiovalue, ($item === $radiovalue ? true : false)) . ' /> ' . $oval . ' </label> ';
							$i++;
							}
						}
						$html_content[$k]['input'] .= '</div>';
					} elseif (element('field_type', $value) === 'checkbox') {
						$html_content[$k]['input'] .= '<div class="checkbox">';
						$options = explode("\n", element('options', $value));
						$item = json_decode($item, true);
						$i =1;
						if ($options) {
							foreach ($options as $okey => $oval) {
								$oval = trim($oval);
								$chkvalue = is_array($item) && in_array($oval, $item) ? $oval : '';
								$html_content[$k]['input'] .= '<label for="' . element('field_name', $value) . '_' . $i . '"><input type="checkbox" name="' . element('field_name', $value) . '[]" id="' . element('field_name', $value) . '_' . $i . '" value="' . $oval . '" ' . set_checkbox(element('field_name', $value), $oval, ($chkvalue ? true : false)) . ' /> ' . $oval . ' </label> ';
							$i++;
							}
						}
						$html_content[$k]['input'] .= '</div>';
					} elseif (element('field_type', $value) === 'select') {
						$html_content[$k]['input'] .= '<div class="input-group">';
						$html_content[$k]['input'] .= '<select name="' . element('field_name', $value) . '" class="form-control" ' . $required . '>';
						$html_content[$k]['input'] .= '<option value="" >선택하세요</option> ';
						$options = explode("\n", element('options', $value));
						if ($options) {
							foreach ($options as $okey => $oval) {
								$oval = trim($oval);
								$html_content[$k]['input'] .= '<option value="' . $oval . '" ' . set_select(element('field_name', $value), $oval, ($item === $oval ? true : false)) . ' >' . $oval . '</option> ';
							}
						}
						$html_content[$k]['input'] .= '</select>';
						$html_content[$k]['input'] .= '</div>';
					}
					$k++;
				}
			}

			$view['view']['html_content'] = $html_content;

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

			$mem_sex = $this->input->post('mem_sex') ? $this->input->post('mem_sex') : 0;
			$mem_receive_email = $this->input->post('mem_receive_email') ? 1 : 0;
			$mem_use_note = $this->input->post('mem_use_note') ? 1 : 0;
			$mem_receive_sms = $this->input->post('mem_receive_sms') ? 1 : 0;
			$mem_open_profile = $this->input->post('mem_open_profile') ? 1 : 0;
			$mem_email_cert = $this->input->post('mem_email_cert') ? 1 : 0;
			$mem_is_admin = $this->input->post('mem_is_admin') ? 1 : 0;

			$updatedata = array(
				'mem_userid' => $this->input->post('mem_userid', null, ''),
				'mem_email' => $this->input->post('mem_email', null, ''),
				'mem_username' => $this->input->post('mem_username', null, ''),
				'mem_level' => $this->input->post('mem_level', null, ''),
				'mem_homepage' => $this->input->post('mem_homepage', null, ''),
				'mem_birthday' => $this->input->post('mem_birthday', null, ''),
				'mem_phone' => $this->input->post('mem_phone', null, ''),
				'mem_sex' => $mem_sex,
				'mem_zipcode' => $this->input->post('mem_zipcode', null, ''),
				'mem_address1' => $this->input->post('mem_address1', null, ''),
				'mem_address2' => $this->input->post('mem_address2', null, ''),
				'mem_address3' => $this->input->post('mem_address3', null, ''),
				'mem_address4' => $this->input->post('mem_address4', null, ''),
				'mem_receive_email' => $mem_receive_email,
				'mem_use_note' => $mem_use_note,
				'mem_receive_sms' => $mem_receive_sms,
				'mem_open_profile' => $mem_open_profile,
				'mem_denied' => $this->input->post('mem_denied', null, ''),
				'mem_email_cert' => $mem_email_cert,
				'mem_is_admin' => $mem_is_admin,
				'mem_profile_content' => $this->input->post('mem_profile_content', null, ''),
				'mem_adminmemo' => $this->input->post('mem_adminmemo', null, ''),
			);

			$metadata = array();

			if (empty($getdata['mem_denied']) && $this->input->post('mem_denied')) {
				$metadata['meta_denied_datetime'] = cdate('Y-m-d H:i:s');
				$metadata['meta_denied_by_mem_id'] = $this->member->item('mem_id');
			}
			if ( ! empty($getdata['mem_denied']) && ! $this->input->post('mem_denied')) {
				$metadata['meta_denied_datetime'] = '';
				$metadata['meta_denied_by_mem_id'] = '';
			}
			if (empty($getdata['mem_email_cert']) && $this->input->post('mem_email_cert')) {
				$metadata['meta_email_cert_datetime'] = cdate('Y-m-d H:i:s');
			}
			if ( ! empty($getdata['mem_email_cert']) && ! $this->input->post('mem_email_cert')) {
					$metadata['meta_email_cert_datetime'] = '';
			}
			if (element('mem_nickname', $getdata) !== $this->input->post('mem_nickname')) {
				$updatedata['mem_nickname'] = $this->input->post('mem_nickname', null, '');
				$metadata['meta_nickname_datetime'] = cdate('Y-m-d H:i:s');
			}
			if ($this->input->post('mem_password')) {
				$updatedata['mem_password'] = password_hash($this->input->post('mem_password'), PASSWORD_BCRYPT);
			}

			if ($this->input->post('mem_photo_del')) {
				$updatedata['mem_photo'] = '';
			} elseif ($updatephoto) {
				$updatedata['mem_photo'] = $updatephoto;
			}
			if (element('mem_photo', $getdata) && ($this->input->post('mem_photo_del') OR $updatephoto)) {
				// 기존 파일 삭제
				@unlink(config_item('uploads_dir') . '/member_photo/' . element('mem_photo', $getdata));
			}
			if ($this->input->post('mem_icon_del')) {
				$updatedata['mem_icon'] = '';
			} elseif ($updateicon) {
				$updatedata['mem_icon'] = $updateicon;
			}
			if (element('mem_icon', $getdata) && ($this->input->post('mem_icon_del') OR $updateicon)) {
				// 기존 파일 삭제
				@unlink(config_item('uploads_dir') . '/member_icon/' . element('mem_icon', $getdata));
			}

			/**
			 * 게시물을 수정하는 경우입니다
			 */
			if ($this->input->post($primary_key)) {
				$mem_id = $this->input->post($primary_key);
				$this->{$this->modelname}->update($mem_id, $updatedata);
				$this->Member_meta_model->save($mem_id, $metadata);
				if (element('mem_userid', $getdata) !== $this->input->post('mem_userid')) {
					$useriddata = array('mem_userid' => $this->input->post('mem_userid'));
					$useridwhere = array('mem_id' => element('mem_id', $getdata));
					$this->Member_userid_model->update('', $useriddata, $useridwhere);
				}
				if (element('mem_nickname', $getdata) !== $this->input->post('mem_nickname')) {
					$upnick = array(
						'mni_end_datetime' => cdate('Y-m-d H:i:s'),
					);
					$upwhere = array(
						'mem_id' => $mem_id,
					);
					$this->Member_nickname_model->update('', $upnick, $upwhere);

					$nickinsert = array(
						'mem_id' => $mem_id,
						'mni_nickname' => $this->input->post('mem_nickname'),
						'mni_start_datetime' => cdate('Y-m-d H:i:s'),
					);
					$this->Member_nickname_model->insert($nickinsert);
				}

				if (element('mem_level', $getdata) !== $this->input->post('mem_level')) {
					$levelhistoryinsert = array(
						'mem_id' => $mem_id,
						'mlh_from' => element('mem_level', $getdata),
						'mlh_to' => $this->input->post('mem_level'),
						'mlh_datetime' => cdate('Y-m-d H:i:s'),
						'mlh_reason' => '관리자에 의한 레벨변경',
						'mlh_ip' => $this->input->ip_address(),
					);
					$this->load->model('Member_level_history_model');
					$this->Member_level_history_model->insert($levelhistoryinsert);
				}

				$deletewhere = array(
					'mem_id' => $mem_id,
				);
				$this->Member_group_member_model->delete_where($deletewhere);
				if ($this->input->post('member_group')) {
					foreach ($this->input->post('member_group') as $gkey => $gval) {
						$mginsert = array(
							'mgr_id' => $gval,
							'mem_id' => $mem_id,
							'mgm_datetime' => cdate('Y-m-d H:i:s'),
						);
						$this->Member_group_member_model->insert($mginsert);
					}
				}
				$extradata = array();
				if ($form && is_array($form)) {
					foreach ($form as $key => $value) {
						if ( ! element('use', $value)) {
							continue;
						}
						if (element('func', $value) === 'basic') {
							continue;
						}
						$extradata[element('field_name', $value)] = $this->input->post(element('field_name', $value), null, '');
					}
					$this->Member_extra_vars_model->save($mem_id, $extradata);
				}

				$this->session->set_flashdata(
					'message',
					'정상적으로 수정되었습니다'
				);
			} else {
				/**
				 * 게시물을 새로 입력하는 경우입니다
				 */
				$updatedata['mem_register_datetime'] = cdate('Y-m-d H:i:s');
				$updatedata['mem_register_ip'] = $this->input->ip_address();

				$mem_id = $this->{$this->modelname}->insert($updatedata);

				$useridinsertdata = array(
					'mem_id' => $mem_id,
					'mem_userid' => $this->input->post('mem_userid'),
				);
				$this->Member_userid_model->insert($useridinsertdata);

				$this->Member_meta_model->save($mem_id, $metadata);
				$nickinsert = array(
					'mem_id' => $mem_id,
					'mni_nickname' => $this->input->post('mem_nickname'),
					'mni_start_datetime' => cdate('Y-m-d H:i:s'),
				);
				$this->Member_nickname_model->insert($nickinsert);
				$levelhistoryinsert = array(
					'mem_id' => $mem_id,
					'mlh_from' => 0,
					'mlh_to' => $this->input->post('mem_level'),
					'mlh_datetime' => cdate('Y-m-d H:i:s'),
					'mlh_reason' => '관리자에 의한 회원가입',
					'mlh_ip' => $this->input->ip_address(),
				);
				$this->load->model('Member_level_history_model');
				$this->Member_level_history_model->insert($levelhistoryinsert);

				if ($this->input->post('member_group')) {
					foreach ($this->input->post('member_group') as $gkey => $gval) {
						$mginsert = array(
							'mgr_id' => $gval,
							'mem_id' => $mem_id,
							'mgm_datetime' => cdate('Y-m-d H:i:s'),
						);
						$this->Member_group_member_model->insert($mginsert);
					}
				}

				$extradata = array();
				if ($form && is_array($form)) {
					foreach ($form as $key => $value) {
						if ( ! element('use', $value)) {
							continue;
						}
						if (element('func', $value) === 'basic') {
							continue;
						}
						$extradata[element('field_name', $value)] = $this->input->post(element('field_name', $value), null, '');
					}
					$this->Member_extra_vars_model->save($mem_id, $extradata);
				}

				$this->session->set_flashdata(
					'message',
					'정상적으로 입력되었습니다'
				);
			}

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
	 * 소셜 정보 자세히 보는 팝업페이지입니다
	 */
	public function socialinfo($socialtype = '', $mem_id = 0)
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_member_members_socialinfo';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		$this->load->model(array('Social_model', 'Social_meta_model'));

		$social = array('facebook' => '페이스북', 'twitter' => '트위터', 'google' => '구글', 'naver' => '네이버', 'kakao' => '카카오');

		$mem_id = (int) $mem_id;
		if (empty($mem_id) OR $mem_id < 1) {
			alert('잘못된 접근입니다');
		}
		if (empty($socialtype)) {
			alert('잘못된 접근입니다');
		}
		if ( ! element($socialtype, $social)) {
			alert('잘못된 접근입니다');
		}

		$social_id = $this->Social_meta_model->get_social_id_by_mem_id($socialtype, $mem_id);

		$swhere = array(
			'soc_type' => $socialtype,
			'soc_account_id' => $social_id,
		);
		$socialinfo = $this->Social_model->get('', '', $swhere, '', '', 'soc_id', 'ASC');
		$data = array();
		if ($socialinfo) {
			foreach ($socialinfo as $key => $value) {
				if ($value['soc_value']) {
					$data[$value['soc_key']] = $value['soc_value'];
				}
			}
		}

		$info = '';
		if ($socialtype === 'facebook') {
			$info = array(
				array(
					'key' => 'name',
					'text' => '성명',
					'value' => element('name', $data),
				),
				array(
					'key' => 'last_name',
					'text' => '성',
					'value' => element('last_name', $data),
				),
				array(
					'key' => 'first_name',
					'text' => '이름',
					'value' => element('first_name', $data),
				),
				array(
					'key' => 'email',
					'text' => '이메일',
					'value' => element('email', $data),
				),
				array(
					'key' => 'link',
					'text' => '주소',
					'value' => '<a href="' . goto_url(element('link', $data)) . '" target="_blank">' . element('link', $data) . '</a>',
				),
				array(
					'key' => 'gender',
					'text' => '성별',
					'value' => (element('gender', $data) === 'male' ? '남성' : '여성'),
				),
				array(
					'key' => 'locale',
					'text' => '언어',
					'value' => element('locale', $data),
				),
				array(
					'key' => 'timezone',
					'text' => '타임존',
					'value' => element('timezone', $data),
				),
				array(
					'key' => 'update_datetime',
					'text' => '최종정보갱신일',
					'value' => element('update_datetime', $data),
				),
				array(
					'key' => 'ip_address',
					'text' => '최종접속IP',
					'value' => element('ip_address', $data),
				),
			);
		}
		if ($socialtype === 'twitter') {
			$info = array(
				array(
					'key' => 'name',
					'text' => '이름',
					'value' => element('name', $data),
				),
				array(
					'key' => 'screen_name',
					'text' => '주소',
					'value' => element('screen_name', $data),
				),
				array(
					'key' => 'url',
					'text' => 'URL',
					'value' => element('url', $data),
				),
				array(
					'key' => 'lang',
					'text' => '언어',
					'value' => element('lang', $data),
				),
				array(
					'key' => 'description',
					'text' => '설명',
					'value' => element('description', $data),
				),
				array(
					'key' => 'location',
					'text' => '지역',
					'value' => element('location', $data),
				),
				array(
					'key' => 'created_at',
					'text' => '트위터 생성일',
					'value' => element('created_at', $data),
				),
				array(
					'key' => 'update_datetime',
					'text' => '최종정보갱신일',
					'value' => element('update_datetime', $data),
				),
				array(
					'key' => 'ip_address',
					'text' => '최종접속IP',
					'value' => element('ip_address', $data),
				),
			);
		}
		if ($socialtype === 'google') {
			$info = array(
				array(
					'key' => 'name',
					'text' => '성명',
					'value' => element('name', $data),
				),
				array(
					'key' => 'familyName',
					'text' => '성',
					'value' => element('familyName', $data),
				),
				array(
					'key' => 'givenName',
					'text' => '이름',
					'value' => element('givenName', $data),
				),
				array(
					'key' => 'email',
					'text' => '이메일',
					'value' => element('email', $data),
				),
				array(
					'key' => 'gender',
					'text' => '성별',
					'value' => (element('gender', $data) === 'male' ? '남성' : '여성'),
				),
				array(
					'key' => 'locale',
					'text' => '언어',
					'value' => element('locale', $data),
				),
				array(
					'key' => 'link',
					'text' => '주소',
					'value' => '<a href="' . goto_url(element('link', $data)) . '" target="_blank">' . element('link', $data) . '</a>',
				),
				array(
					'key' => 'picture',
					'text' => '사진',
					'value' => '<img src="' . element('picture', $data) . '" width="200" />',
				),
				array(
					'key' => 'update_datetime',
					'text' => '최종정보갱신일',
					'value' => element('update_datetime', $data),
				),
				array(
					'key' => 'ip_address',
					'text' => '최종접속IP',
					'value' => element('ip_address', $data),
				),
			);
		}
		if ($socialtype === 'naver') {
			$info = array(
				array(
					'key' => 'name',
					'text' => '이름',
					'value' => element('name', $data),
				),
				array(
					'key' => 'nickname',
					'text' => '닉네임',
					'value' => element('nickname', $data),
				),
				array(
					'key' => 'email',
					'text' => '이메일',
					'value' => element('email', $data),
				),
				array(
					'key' => 'age',
					'text' => '연령',
					'value' => element('age', $data),
				),
				array(
					'key' => 'gender',
					'text' => '성별',
					'value' => (element('gender', $data) === 'F' ? '여성' : '남성'),
				),
				array(
					'key' => 'birthday',
					'text' => '생일',
					'value' => element('birthday', $data),
				),
				array(
					'key' => 'update_datetime',
					'text' => '최종정보갱신일',
					'value' => element('update_datetime', $data),
				),
				array(
					'key' => 'ip_address',
					'text' => '최종접속IP',
					'value' => element('ip_address', $data),
				),
			);
		}
		if ($socialtype === 'kakao') {
			$info = array(
				array(
					'key' => 'nickname',
					'text' => '닉네임',
					'value' => element('nickname', $data),
				),
				array(
					'key' => 'profile_image',
					'text' => '프로필이미지',
					'value' => '<img src="' . element('profile_image', $data) . '" width="200" />'
				),
				array(
					'key' => 'thumbnail_image',
					'text' => '썸네일이미지',
					'value' => '<img src="' . element('thumbnail_image', $data) . '" width="200" />'
				),
				array(
					'key' => 'update_datetime',
					'text' => '최종정보갱신일',
					'value' => element('update_datetime', $data),
				),
				array(
					'key' => 'ip_address',
					'text' => '최종접속IP',
					'value' => element('ip_address', $data),
				),
			);
		}

		$view['view']['data'] = $info;
		$view['view']['socialtype'] = $socialtype;
		$view['view']['socialname'] = $social[$socialtype];


		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 어드민 레이아웃을 정의합니다
		 */
		$layoutconfig = array('layout' => 'layout_popup', 'skin' => 'socialinfo');
		$view['layout'] = $this->managelayout->admin($layoutconfig, $this->cbconfig->get_device_view_type());
		$this->data = $view;
		$this->layout = element('layout_skin_file', element('layout', $view));
		$this->view = element('view_skin_file', element('layout', $view));
	}

	/**
	 * 엑셀로 데이터를 추출합니다.
	 */
	public function excel()
	{

		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_member_members_excel';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		/**
		 * 페이지에 숫자가 아닌 문자가 입력되거나 1보다 작은 숫자가 입력되면 에러 페이지를 보여줍니다.
		 */
		$param =& $this->querystring;
		$findex = $this->input->get('findex', null, 'member.mem_id');
		$forder = $this->input->get('forder', null, 'desc');
		$sfield = $this->input->get('sfield', null, '');
		$skeyword = $this->input->get('skeyword', null, '');

		/**
		 * 게시판 목록에 필요한 정보를 가져옵니다.
		 */
		$this->{$this->modelname}->allow_search_field = array('mem_id', 'mem_userid', 'mem_email', 'mem_username', 'mem_nickname', 'mem_level', 'mem_homepage', 'mem_register_datetime', 'mem_register_ip', 'mem_lastlogin_datetime', 'mem_lastlogin_ip', 'mem_is_admin'); // 검색이 가능한 필드
		$this->{$this->modelname}->search_field_equal = array('mem_id', 'mem_level', 'mem_is_admin'); // 검색중 like 가 아닌 = 검색을 하는 필드
		$this->{$this->modelname}->allow_order_field = array('member.mem_id', 'mem_userid', 'mem_username', 'mem_nickname', 'mem_email', 'mem_point', 'mem_register_datetime', 'mem_lastlogin_datetime', 'mem_level'); // 정렬이 가능한 필드

		$where = array();
		if ($this->input->get('mem_is_admin')) {
			$where['mem_is_admin'] = 1;
		}
		if ($this->input->get('mem_denied')) {
			$where['mem_denied'] = 1;
		}
		if ($mgr_id = (int) $this->input->get('mgr_id')) {
			if ($mgr_id > 0) {
				$where['mgr_id'] = $mgr_id;
			}
		}
		$result = $this->{$this->modelname}
			->get_admin_list('', '', $where, '', $findex, $forder, $sfield, $skeyword);

		if (element('list', $result)) {
			foreach (element('list', $result) as $key => $val) {

				$where = array(
					'mem_id' => element('mem_id', $val),
				);
				$result['list'][$key]['member_group_member'] = $this->Member_group_member_model->get('', '', $where, '', 0, 'mgm_id', 'ASC');
				$mgroup = '';
				if ($result['list'][$key]['member_group_member']) {
					foreach ($result['list'][$key]['member_group_member'] as $mk => $mv) {
						if (element('mgr_id', $mv)) {
							$mgroup[] = $this->Member_group_model->item(element('mgr_id', $mv));
						}
					}
				}
				$result['list'][$key]['member_group'] = '';
				if ($mgroup) {
					foreach ($mgroup as $mk => $mv) {
						if ($result['list'][$key]['member_group']) {
							$result['list'][$key]['member_group'] .= ', ';
						}
						$result['list'][$key]['member_group'] .= element('mgr_title', $mv);
					}
				}
				$result['list'][$key]['display_name'] = display_username(
					element('mem_userid', $val),
					element('mem_nickname', $val),
					element('mem_icon', $val)
				);
				$result['list'][$key]['meta'] = $this->Member_meta_model->get_all_meta(element('mem_id', $val));
				$result['list'][$key]['social'] = $this->Social_meta_model->get_all_meta(element('mem_id', $val));
			}
		}

		$view['view']['data'] = $result;
		$view['view']['all_group'] = $this->Member_group_model->get_all_group();

		/**
		 * primary key 정보를 저장합니다
		 */
		$view['view']['primary_key'] = $this->{$this->modelname}->primary_key;


		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		header('Content-type: application/vnd.ms-excel');
		header('Content-Disposition: attachment; filename=회원정보_' . cdate('Y_m_d') . '.xls');
		echo $this->load->view('admin/' . ADMIN_SKIN . '/' . $this->pagedir . '/excel', $view, true);
	}

	/**
	 * 목록 페이지에서 선택삭제를 하는 경우 실행되는 메소드입니다
	 */
	public function listdelete()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_member_members_listdelete';
		$this->load->event($eventname);

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		/**
		 * 체크한 게시물의 삭제를 실행합니다
		 */
		if ($this->input->post('chk') && is_array($this->input->post('chk'))) {
			foreach ($this->input->post('chk') as $val) {
				if ($val) {
					$this->member->delete_member($val);
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
	 * 회원아이디 체크함수입니다
	 */
	public function _mem_userid_check($str)
	{
		if (preg_match("/[\,]?{$str}/i", $this->cbconfig->item('prohibit_id'))) {
			$this->form_validation->set_message(
				'_mem_userid_check',
				$str . ' 은(는) 예약어로 사용하실 수 없는 회원아이디입니다'
			);
			return false;
		}
		return true;
	}

	/**
	 * 회원닉네임 체크함수입니다
	 */
	public function _mem_nickname_check($str)
	{
		if (chkstring($str, _HANGUL_ + _ALPHABETIC_ + _NUMERIC_) === false) {
			$this->form_validation->set_message(
				'_mem_nickname_check',
				'닉네임은 공백없이 한글, 영문, 숫자만 입력 가능합니다'
			);
			return false;
		}
		return true;
	}
}
