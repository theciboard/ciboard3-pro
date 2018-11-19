<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Register class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 회원 가입과 관련된 controller 입니다.
 */
class Register extends CB_Controller
{

	/**
	 * 모델을 로딩합니다
	 */
	protected $models = array('Member_nickname', 'Member_meta', 'Member_auth_email', 'Member_userid');

	/**
	 * 헬퍼를 로딩합니다
	 */
	protected $helpers = array('form', 'array', 'string');

	function __construct()
	{
		parent::__construct();

		/**
		 * 라이브러리를 로딩합니다
		 */
		$this->load->library(array('querystring', 'form_validation', 'email', 'notelib', 'point'));

		if ( ! function_exists('password_hash')) {
			$this->load->helper('password');
		}
	}


	/**
	 * 회원 약관 동의시 작동하는 함수입니다
	 */
	public function index()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_register_index';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		if ($this->member->is_member()
			&& ! ($this->member->is_admin() === 'super' && $this->uri->segment(1) === config_item('uri_segment_admin'))) {
			redirect();
		}

		if ($this->cbconfig->item('use_register_block')) {

			// 이벤트가 존재하면 실행합니다
			$view['view']['event']['before_block_layout'] = Events::trigger('before_block_layout', $eventname);

			/**
			 * 레이아웃을 정의합니다
			 */
			$page_title = $this->cbconfig->item('site_meta_title_register');
			$meta_description = $this->cbconfig->item('site_meta_description_register');
			$meta_keywords = $this->cbconfig->item('site_meta_keywords_register');
			$meta_author = $this->cbconfig->item('site_meta_author_register');
			$page_name = $this->cbconfig->item('site_page_name_register');

			$layoutconfig = array(
				'path' => 'register',
				'layout' => 'layout',
				'skin' => 'register_block',
				'layout_dir' => $this->cbconfig->item('layout_register'),
				'mobile_layout_dir' => $this->cbconfig->item('mobile_layout_register'),
				'use_sidebar' => $this->cbconfig->item('sidebar_register'),
				'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_register'),
				'skin_dir' => $this->cbconfig->item('skin_register'),
				'mobile_skin_dir' => $this->cbconfig->item('mobile_skin_register'),
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

			return false;
		}

		/**
		 * 전송된 데이터의 유효성을 체크합니다
		 */
		$config = array(
			array(
				'field' => 'agree',
				'label' => '회원가입약관',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'agree2',
				'label' => '개인정보취급방침',
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

			$this->session->set_userdata('registeragree', '');
			$this->session->set_userdata('selfcertinfo', '');

			$view['view']['member_register_policy1'] = $this->cbconfig->item('member_register_policy1');
			$view['view']['member_register_policy2'] = $this->cbconfig->item('member_register_policy2');
			$view['view']['canonical'] = site_url('register');

			// 이벤트가 존재하면 실행합니다
			$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

			/**
			 * 레이아웃을 정의합니다
			 */
			$page_title = $this->cbconfig->item('site_meta_title_register');
			$meta_description = $this->cbconfig->item('site_meta_description_register');
			$meta_keywords = $this->cbconfig->item('site_meta_keywords_register');
			$meta_author = $this->cbconfig->item('site_meta_author_register');
			$page_name = $this->cbconfig->item('site_page_name_register');

			$layoutconfig = array(
				'path' => 'register',
				'layout' => 'layout',
				'skin' => 'register',
				'layout_dir' => $this->cbconfig->item('layout_register'),
				'mobile_layout_dir' => $this->cbconfig->item('mobile_layout_register'),
				'use_sidebar' => $this->cbconfig->item('sidebar_register'),
				'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_register'),
				'skin_dir' => $this->cbconfig->item('skin_register'),
				'mobile_skin_dir' => $this->cbconfig->item('mobile_skin_register'),
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

		} else {
			/**
			 * 유효성 검사를 통과한 경우입니다.
			 * 즉 데이터의 insert 나 update 의 process 처리가 필요한 상황입니다
			 */

			// 이벤트가 존재하면 실행합니다
			$view['view']['event']['formruntrue'] = Events::trigger('formruntrue', $eventname);

			$this->session->set_userdata('registeragree', '1');
			redirect('register/form');
		}
	}


	/**
	 * 회원가입 폼 페이지입니다
	 */
	public function form()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_register_form';
		$this->load->event($eventname);

		if ($this->member->is_member() && ! ($this->member->is_admin() === 'super' && $this->uri->segment(1) === config_item('uri_segment_admin'))) {
			redirect();
		}

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		if ($this->cbconfig->item('use_register_block')) {

			// 이벤트가 존재하면 실행합니다
			$view['view']['event']['before_block_layout'] = Events::trigger('before_block_layout', $eventname);

			/**
			 * 레이아웃을 정의합니다
			 */
			$page_title = $this->cbconfig->item('site_meta_title_register_form');
			$meta_description = $this->cbconfig->item('site_meta_description_register_form');
			$meta_keywords = $this->cbconfig->item('site_meta_keywords_register_form');
			$meta_author = $this->cbconfig->item('site_meta_author_register_form');
			$page_name = $this->cbconfig->item('site_page_name_register_form');

			$layoutconfig = array(
				'path' => 'register',
				'layout' => 'layout',
				'skin' => 'register_block',
				'layout_dir' => $this->cbconfig->item('layout_register'),
				'mobile_layout_dir' => $this->cbconfig->item('mobile_layout_register'),
				'use_sidebar' => $this->cbconfig->item('sidebar_register'),
				'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_register'),
				'skin_dir' => $this->cbconfig->item('skin_register'),
				'mobile_skin_dir' => $this->cbconfig->item('mobile_skin_register'),
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
			return false;
		}


		if ($this->cbconfig->item('use_selfcert') && $this->cbconfig->item('use_selfcert_required') && ! $this->session->userdata('selfcertinfo')) {
			if ( ! $this->session->userdata('selfcertinfo')) {
				$this->session->set_flashdata(
					'message',
					'본인 확인 후에 회원가입이 가능합니다.'
				);
				redirect('register');
			}
		}

		$selfcert_phone = $selfcert_username = $selfcert_birthday = $selfcert_sex = '';
		$selfcert_meta = '';

		if ($this->cbconfig->item('use_selfcert') && $this->session->userdata('selfcertinfo')) {
			$selfcertinfo = $this->session->userdata('selfcertinfo');
			if (element('selfcert_type', $selfcertinfo) == 'phone') {
				if ($this->cbconfig->item('use_selfcert_phone') == 'kcb' OR $this->cbconfig->item('use_selfcert_phone') == 'kcp') {
					$selfcert_phone = element('selfcert_phone', $selfcertinfo);
					$selfcert_username = element('selfcert_username', $selfcertinfo);
					$selfcert_birthday = element('selfcert_birthday', $selfcertinfo);
					$selfcert_sex = element('selfcert_sex', $selfcertinfo);
					$selfcert_key = element('selfcert_key', $selfcertinfo);
					$selfcert_local_code = element('selfcert_local_code', $selfcertinfo);
					$selfcert_meta = array(
						'selfcert_type' => element('selfcert_type', $selfcertinfo),
						'selfcert_company' => $this->cbconfig->item('use_selfcert_phone'),
						'selfcert_comm_id' => element('selfcert_comm_id', $selfcertinfo),
						'selfcert_phone' => $selfcert_phone,
						'selfcert_username' => $selfcert_username,
						'selfcert_birthday' => $selfcert_birthday,
						'selfcert_sex' => $selfcert_sex,
						'selfcert_key' => $selfcert_key,
						'selfcert_local_code' => $selfcert_local_code,
					);
				}
			}
		}

		$password_length = $this->cbconfig->item('password_length');
		$email_description = '';
		if ($this->cbconfig->item('use_register_email_auth')) {
			$email_description = '회원가입 후 인증메일이 발송됩니다. 인증메일을 확인하신 후에 사이트 이용이 가능합니다';
		}

		$configbasic = array();

		$nickname_description = '';
		if ($this->cbconfig->item('change_nickname_date')) {
			$nickname_description = '<br />닉네임을 입력하시면 앞으로 '
				. $this->cbconfig->item('change_nickname_date') . '일 이내에는 변경할 수 없습니다';
		}

		$configbasic['mem_userid'] = array(
			'field' => 'mem_userid',
			'label' => '아이디',
			'rules' => 'trim|required|alphanumunder|min_length[3]|max_length[20]|is_unique[member_userid.mem_userid]|callback__mem_userid_check',
			'description' => '영문자, 숫자, _ 만 입력 가능. 최소 3자이상 입력하세요',
		);

		$password_description = '비밀번호는 ' . $password_length . '자리 이상이어야 ';
		if ($this->cbconfig->item('password_uppercase_length')
			OR $this->cbconfig->item('password_numbers_length')
			OR $this->cbconfig->item('password_specialchars_length')) {

			$password_description .= '하며 ';
			if ($this->cbconfig->item('password_uppercase_length')) {
				$password_description .= ', ' . $this->cbconfig->item('password_uppercase_length') . '개의 대문자';
			}
			if ($this->cbconfig->item('password_numbers_length')) {
				$password_description .= ', ' . $this->cbconfig->item('password_numbers_length') . '개의 숫자';
			}
			if ($this->cbconfig->item('password_specialchars_length')) {
				$password_description .= ', ' . $this->cbconfig->item('password_specialchars_length') . '개의 특수문자';
			}
			$password_description .= '를 포함해야 ';
		}
		$password_description .= '합니다';

		$configbasic['mem_password'] = array(
			'field' => 'mem_password',
			'label' => '패스워드',
			'rules' => 'trim|required|min_length[' . $password_length . ']|callback__mem_password_check',
			'description' => $password_description,
		);
		$configbasic['mem_password_re'] = array(
			'field' => 'mem_password_re',
			'label' => '패스워드 확인',
			'rules' => 'trim|required|min_length[' . $password_length . ']|matches[mem_password]',
		);
		if ( ! $selfcert_username) {
			$configbasic['mem_username'] = array(
				'field' => 'mem_username',
				'label' => '이름',
				'rules' => 'trim|min_length[2]|max_length[20]',
			);
		}
		$configbasic['mem_nickname'] = array(
			'field' => 'mem_nickname',
			'label' => '닉네임',
			'rules' => 'trim|required|min_length[2]|max_length[20]|callback__mem_nickname_check',
			'description' => '공백없이 한글, 영문, 숫자만 입력 가능 2글자 이상' . $nickname_description,
		);
		$configbasic['mem_email'] = array(
			'field' => 'mem_email',
			'label' => '이메일',
			'rules' => 'trim|required|valid_email|max_length[50]|is_unique[member.mem_email]|callback__mem_email_check',
			'description' => $email_description,
		);
		$configbasic['mem_homepage'] = array(
			'field' => 'mem_homepage',
			'label' => '홈페이지',
			'rules' => 'prep_url|valid_url',
		);
		if ( ! $selfcert_phone) {
			$configbasic['mem_phone'] = array(
				'field' => 'mem_phone',
				'label' => '전화번호',
				'rules' => 'trim|valid_phone',
			);
		}
		if ( ! $selfcert_birthday) {
			$configbasic['mem_birthday'] = array(
				'field' => 'mem_birthday',
				'label' => '생년월일',
				'rules' => 'trim|exact_length[10]',
			);
		}
		if ( ! $selfcert_sex) {
			$configbasic['mem_sex'] = array(
				'field' => 'mem_sex',
				'label' => '성별',
				'rules' => 'trim|exact_length[1]',
			);
		}
		$configbasic['mem_zipcode'] = array(
			'field' => 'mem_zipcode',
			'label' => '우편번호',
			'rules' => 'trim|min_length[5]|max_length[7]',
		);
		$configbasic['mem_address1'] = array(
			'field' => 'mem_address1',
			'label' => '기본주소',
			'rules' => 'trim',
		);
		$configbasic['mem_address2'] = array(
			'field' => 'mem_address2',
			'label' => '상세주소',
			'rules' => 'trim',
		);
		$configbasic['mem_address3'] = array(
			'field' => 'mem_address3',
			'label' => '참고항목',
			'rules' => 'trim',
		);
		$configbasic['mem_address4'] = array(
			'field' => 'mem_address4',
			'label' => '지번',
			'rules' => 'trim',
		);
		$configbasic['mem_profile_content'] = array(
			'field' => 'mem_profile_content',
			'label' => '자기소개',
			'rules' => 'trim',
		);
		$configbasic['mem_open_profile'] = array(
			'field' => 'mem_open_profile',
			'label' => '정보공개',
			'rules' => 'trim|exact_length[1]',
		);
		if ($this->cbconfig->item('use_note')) {
			$configbasic['mem_use_note'] = array(
				'field' => 'mem_use_note',
				'label' => '쪽지사용',
				'rules' => 'trim|exact_length[1]',
			);
		}
		$configbasic['mem_receive_email'] = array(
			'field' => 'mem_receive_email',
			'label' => '이메일수신여부',
			'rules' => 'trim|exact_length[1]',
		);
		$configbasic['mem_receive_sms'] = array(
			'field' => 'mem_receive_sms',
			'label' => 'SMS 문자수신여부',
			'rules' => 'trim|exact_length[1]',
		);
		$configbasic['mem_recommend'] = array(
			'field' => 'mem_recommend',
			'label' => '추천인아이디',
			'rules' => 'trim|alphanumunder|min_length[3]|max_length[20]|callback__mem_recommend_check',
		);

		if ($this->member->is_admin() === false && ! $this->session->userdata('registeragree')) {
			$this->session->set_flashdata(
				'message',
				'회원가입약관동의와 개인정보취급방침동의후 회원가입이 가능합니다'
			);
			redirect('register');
		}

		$registerform = $this->cbconfig->item('registerform');
		$form = json_decode($registerform, true);

		$config = array();
		if ($form && is_array($form)) {
			foreach ($form as $key => $value) {
				if ( ! element('use', $value)) {
					continue;
				}
				if (element('func', $value) === 'basic') {
					if ($key == 'mem_username' && $selfcert_username) {
						continue;
					}
					if ($key == 'mem_phone' && $selfcert_phone) {
						continue;
					}
					if ($key == 'mem_birthday' && $selfcert_birthday) {
						continue;
					}
					if ($key == 'mem_sex' && $selfcert_sex) {
						continue;
					}

					if ($key === 'mem_address') {
						if (element('required', $value) === '1') {
							$configbasic['mem_zipcode']['rules'] = $configbasic['mem_zipcode']['rules'] . '|required';
						}
						$config[] = $configbasic['mem_zipcode'];
						if (element('required', $value) === '1') {
							$configbasic['mem_address1']['rules'] = $configbasic['mem_address1']['rules'] . '|required';
						}
						$config[] = $configbasic['mem_address1'];
						if (element('required', $value) === '1') {
							$configbasic['mem_address2']['rules'] = $configbasic['mem_address2']['rules'] . '|required';
						}
						$config[] = $configbasic['mem_address2'];
					} else {
						if (element('required', $value) === '1') {
							$configbasic[$value['field_name']]['rules'] = $configbasic[$value['field_name']]['rules'] . '|required';
						}
						if (element('field_type', $value) === 'phone') {
							$configbasic[$value['field_name']]['rules'] = $configbasic[$value['field_name']]['rules'] . '|valid_phone';
						}
						$config[] = $configbasic[$value['field_name']];
						if ($key === 'mem_password') {
							$config[] = $configbasic['mem_password_re'];
						}
					}
				} else {
					$required = element('required', $value) ? '|required' : '';
					if (element('field_type', $value) === 'checkbox') {
						$config[] = array(
							'field' => element('field_name', $value) . '[]',
							'label' => element('display_name', $value),
							'rules' => 'trim' . $required,
						);
					} else {
						$config[] = array(
							'field' => element('field_name', $value),
							'label' => element('display_name', $value),
							'rules' => 'trim' . $required,
						);
					}
				}
			}
		}

		if ($this->cbconfig->item('use_recaptcha')) {
			$config[] = array(
				'field' => 'g-recaptcha-response',
				'label' => '자동등록방지문자',
				'rules' => 'trim|required|callback__check_recaptcha',
			);
		} else {
			$config[] = array(
				'field' => 'captcha_key',
				'label' => '자동등록방지문자',
				'rules' => 'trim|required|callback__check_captcha',
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
			if ($this->cbconfig->item('use_member_photo') && $this->cbconfig->item('member_photo_width') > 0 && $this->cbconfig->item('member_photo_height') > 0) {
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
						$updatephoto = cdate('Y') . '/' . cdate('m') . '/' . $img['file_name'];
					} else {
						$file_error = $this->upload->display_errors();

					}
				}
			}

			if ($this->cbconfig->item('use_member_icon') && $this->cbconfig->item('member_icon_width') > 0 && $this->cbconfig->item('member_icon_height') > 0) {
				if (isset($_FILES) && isset($_FILES['mem_icon']) && isset($_FILES['mem_icon']['name']) && $_FILES['mem_icon']['name']) {
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
						$updateicon = cdate('Y') . '/' . cdate('m') . '/' . $img['file_name'];
					} else {
						$file_error2 = $this->upload->display_errors();
					}
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

			$html_content = array();

			$k = 0;
			if ($form && is_array($form)) {
				foreach ($form as $key => $value) {
					if ( ! element('use', $value)) {
						continue;
					}
					if (element('field_name', $value) === 'mem_username' && $selfcert_username) {
						continue;
					}
					if (element('field_name', $value) === 'mem_phone' && $selfcert_phone) {
						continue;
					}
					if (element('field_name', $value) === 'mem_birthday' && $selfcert_birthday) {
						continue;
					}
					if (element('field_name', $value) === 'mem_sex' && $selfcert_sex) {
						continue;
					}

					$required = element('required', $value) ? 'required' : '';

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
							$html_content[$k]['input'] .= '<input type="text" id="' . element('field_name', $value) . '" name="' . element('field_name', $value) . '" class="form-control input datepicker" value="' . set_value(element('field_name', $value)) . '" readonly="readonly" ' . $required . ' />';
						} elseif (element('field_type', $value) === 'phone') {
							$html_content[$k]['input'] .= '<input type="text" id="' . element('field_name', $value) . '" name="' . element('field_name', $value) . '" class="form-control input validphone" value="' . set_value(element('field_name', $value)) . '" ' . $required . ' />';
						} else {
							$html_content[$k]['input'] .= '<input type="' . element('field_type', $value) . '" id="' . element('field_name', $value) . '" name="' . element('field_name', $value) . '" class="form-control input" value="' . set_value(element('field_name', $value)) . '" ' . $required . '/>';
						}
					} elseif (element('field_type', $value) === 'textarea') {
						$html_content[$k]['input'] .= '<textarea id="' . element('field_name', $value) . '" name="' . element('field_name', $value) . '" class="form-control input" ' . $required . '>' . set_value(element('field_name', $value)) . '</textarea>';
					} elseif (element('field_type', $value) === 'radio') {
						$html_content[$k]['input'] .= '<div class="checkbox">';
						if (element('field_name', $value) === 'mem_sex') {
							$options = array(
								'1' => '남성',
								'2' => '여성',
							);
						} else {
							$options = explode("\n", element('options', $value));
						}
						$i =1;
						if ($options) {
							foreach ($options as $okey => $oval) {
								$radiovalue = (element('field_name', $value) === 'mem_sex') ? $okey : $oval;
								$html_content[$k]['input'] .= '<label for="' . element('field_name', $value) . '_' . $i . '"><input type="radio" name="' . element('field_name', $value) . '" id="' . element('field_name', $value) . '_' . $i . '" value="' . $radiovalue . '" ' . set_radio(element('field_name', $value), $radiovalue) . ' /> ' . $oval . ' </label> ';
								$i++;
							}
						}
						$html_content[$k]['input'] .= '</div>';
					} elseif (element('field_type', $value) === 'checkbox') {
						$html_content[$k]['input'] .= '<div class="checkbox">';
						$options = explode("\n", element('options', $value));
						$i =1;
						if ($options) {
							foreach ($options as $okey => $oval) {
								$html_content[$k]['input'] .= '<label for="' . element('field_name', $value) . '_' . $i . '"><input type="checkbox" name="' . element('field_name', $value) . '[]" id="' . element('field_name', $value) . '_' . $i . '" value="' . $oval . '" ' . set_checkbox(element('field_name', $value), $oval) . ' /> ' . $oval . ' </label> ';
								$i++;
							}
						}
						$html_content[$k]['input'] .= '</div>';
					} elseif (element('field_type', $value) === 'select') {
						$html_content[$k]['input'] .= '<div class="input-group">';
						$html_content[$k]['input'] .= '<select name="' . element('field_name', $value) . '" class="form-control input" ' . $required . '>';
						$html_content[$k]['input'] .= '<option value="" >선택하세요</option> ';
						$options = explode("\n", element('options', $value));
						if ($options) {
							foreach ($options as $okey => $oval) {
								$html_content[$k]['input'] .= '<option value="' . $oval . '" ' . set_select(element('field_name', $value), $oval) . ' >' . $oval . '</option> ';
							}
						}
						$html_content[$k]['input'] .= '</select>';
						$html_content[$k]['input'] .= '</div>';
					} elseif (element('field_name', $value) === 'mem_address') {
						$html_content[$k]['input'] .= '
							<label for="mem_zipcode">우편번호</label>
							<label>
								<input type="text" name="mem_zipcode" value="' . set_value('mem_zipcode') . '" id="mem_zipcode" class="form-control input" size="7" maxlength="7" ' . $required . '/>
							</label>
							<label>
								<button type="button" class="btn btn-black btn-sm" style="margin-top:0px;" onclick="win_zip(\'fregisterform\', \'mem_zipcode\', \'mem_address1\', \'mem_address2\', \'mem_address3\', \'mem_address4\');">주소 검색</button>
							</label>
							<div class="addr-line mt10">
								<label for="mem_address1">기본주소</label>
								<input type="text" name="mem_address1" value="' . set_value('mem_address1') . '" id="mem_address1" class="form-control input" placeholder="기본주소" ' . $required . ' />
							</div>
							<div class="addr-line mt10">
								<label for="mem_address2">상세주소</label>
								<input type="text" name="mem_address2" value="' . set_value('mem_address2') . '" id="mem_address2" class="form-control input" placeholder="상세주소" ' . $required . ' />
							</div>
							<div class="addr-line mt10">
								<label for="mem_address3">참고항목</label>
								<input type="text" name="mem_address3" value="' . set_value('mem_address3') . '" id="mem_address3" class="form-control input" readonly="readonly" placeholder="참고항목" />
							</div>
							<input type="hidden" name="mem_address4" value="' . set_value('mem_address4') . '" />
						';
					} elseif (element('field_name', $value) === 'mem_password') {
						$html_content[$k]['input'] .= '<input type="' . element('field_type', $value) . '" id="' . element('field_name', $value) . '" name="' . element('field_name', $value) . '" class="form-control input" minlength="' . $password_length . '" />';
					}

					$html_content[$k]['description'] = '';
					if (isset($configbasic[$value['field_name']]['description']) && $configbasic[$value['field_name']]['description']) {
						$html_content[$k]['description'] = $configbasic[$value['field_name']]['description'];
					}
					if (element('field_name', $value) === 'mem_password') {
						$k++;
						$html_content[$k]['field_name'] = 'mem_password_re';
						$html_content[$k]['display_name'] = '비밀번호 확인';
						$html_content[$k]['input'] = '<input type="password" id="mem_password_re" name="mem_password_re" class="form-control input" minlength="' . $password_length . '" />';
					}
					$k++;
				}
			}

			$view['view']['html_content'] = $html_content;
			$view['view']['open_profile_description'] = '';
			if ($this->cbconfig->item('change_open_profile_date')) {
				$view['view']['open_profile_description'] = '정보공개 설정은 ' . $this->cbconfig->item('change_open_profile_date') . '일 이내에는 변경할 수 없습니다';
			}

			$view['view']['use_note_description'] = '';
			if ($this->cbconfig->item('change_use_note_date')) {
				$view['view']['use_note_description'] = '쪽지 기능 사용 설정은 ' . $this->cbconfig->item('change_use_note_date') . '일 이내에는 변경할 수 없습니다';
			}

			$view['view']['canonical'] = site_url('register/form');

			// 이벤트가 존재하면 실행합니다
			$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

			/**
			 * 레이아웃을 정의합니다
			 */
			$page_title = $this->cbconfig->item('site_meta_title_register_form');
			$meta_description = $this->cbconfig->item('site_meta_description_register_form');
			$meta_keywords = $this->cbconfig->item('site_meta_keywords_register_form');
			$meta_author = $this->cbconfig->item('site_meta_author_register_form');
			$page_name = $this->cbconfig->item('site_page_name_register_form');

			$layoutconfig = array(
				'path' => 'register',
				'layout' => 'layout',
				'skin' => 'register_form',
				'layout_dir' => $this->cbconfig->item('layout_register'),
				'mobile_layout_dir' => $this->cbconfig->item('mobile_layout_register'),
				'use_sidebar' => $this->cbconfig->item('sidebar_register'),
				'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_register'),
				'skin_dir' => $this->cbconfig->item('skin_register'),
				'mobile_skin_dir' => $this->cbconfig->item('mobile_skin_register'),
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

		} else {

			/**
			 * 유효성 검사를 통과한 경우입니다.
			 * 즉 데이터의 insert 나 update 의 process 처리가 필요한 상황입니다
			 */

			// 이벤트가 존재하면 실행합니다
			$view['view']['event']['formruntrue'] = Events::trigger('formruntrue', $eventname);

			$mem_level = (int) $this->cbconfig->item('register_level');
			$insertdata = array();
			$metadata = array();

			$insertdata['mem_userid'] = $this->input->post('mem_userid');
			$insertdata['mem_email'] = $this->input->post('mem_email');
			$insertdata['mem_password'] = password_hash($this->input->post('mem_password'), PASSWORD_BCRYPT);
			$insertdata['mem_nickname'] = $this->input->post('mem_nickname');
			$metadata['meta_nickname_datetime'] = cdate('Y-m-d H:i:s');
			$insertdata['mem_level'] = $mem_level;

			if ($selfcert_username) {
				$insertdata['mem_username'] = $selfcert_username;
			} else if (isset($form['mem_username']['use']) && $form['mem_username']['use']) {
				$insertdata['mem_username'] = $this->input->post('mem_username', null, '');
			}
			if (isset($form['mem_homepage']['use']) && $form['mem_homepage']['use']) {
				$insertdata['mem_homepage'] = $this->input->post('mem_homepage', null, '');
			}
			if ($selfcert_phone) {
				$insertdata['mem_phone'] = $selfcert_phone;
			} else if (isset($form['mem_phone']['use']) && $form['mem_phone']['use']) {
				$insertdata['mem_phone'] = $this->input->post('mem_phone', null, '');
			}
			if ($selfcert_birthday) {
				$insertdata['mem_birthday'] = $selfcert_birthday;
			} else if (isset($form['mem_birthday']['use']) && $form['mem_birthday']['use']) {
				$insertdata['mem_birthday'] = $this->input->post('mem_birthday', null, '');
			}
			if ($selfcert_sex) {
				$insertdata['mem_sex'] = $selfcert_sex;
			} else if (isset($form['mem_sex']['use']) && $form['mem_sex']['use']) {
				$insertdata['mem_sex'] = $this->input->post('mem_sex', null, '');
			}
			if (isset($form['mem_address']['use']) && $form['mem_address']['use']) {
				$insertdata['mem_zipcode'] = $this->input->post('mem_zipcode', null, '');
				$insertdata['mem_address1'] = $this->input->post('mem_address1', null, '');
				$insertdata['mem_address2'] = $this->input->post('mem_address2', null, '');
				$insertdata['mem_address3'] = $this->input->post('mem_address3', null, '');
				$insertdata['mem_address4'] = $this->input->post('mem_address4', null, '');
			}
			$insertdata['mem_receive_email'] = $this->input->post('mem_receive_email') ? 1 : 0;
			if ($this->cbconfig->item('use_note')) {
				$insertdata['mem_use_note'] = $this->input->post('mem_use_note') ? 1 : 0;
				$metadata['meta_use_note_datetime'] = cdate('Y-m-d H:i:s');
			}
			$insertdata['mem_receive_sms'] = $this->input->post('mem_receive_sms') ? 1 : 0;
			$insertdata['mem_open_profile'] = $this->input->post('mem_open_profile') ? 1 : 0;
			$metadata['meta_open_profile_datetime'] = cdate('Y-m-d H:i:s');
			$insertdata['mem_register_datetime'] = cdate('Y-m-d H:i:s');
			$insertdata['mem_register_ip'] = $this->input->ip_address();
			$metadata['meta_change_pw_datetime'] = cdate('Y-m-d H:i:s');
			if (isset($form['mem_profile_content']['use']) && $form['mem_profile_content']['use']) {
				$insertdata['mem_profile_content'] = $this->input->post('mem_profile_content', null, '');
			}

			if ($this->cbconfig->item('use_register_email_auth')) {
				$insertdata['mem_email_cert'] = 0;
				$metadata['meta_email_cert_datetime'] = '';
			} else {
				$insertdata['mem_email_cert'] = 1;
				$metadata['meta_email_cert_datetime'] = cdate('Y-m-d H:i:s');
			 }

			if ($updatephoto) {
				$insertdata['mem_photo'] = $updatephoto;
			}
			if ($updateicon) {
				$insertdata['mem_icon'] = $updateicon;
			}

			$mem_id = $this->Member_model->insert($insertdata);

			$useridinsertdata = array(
				'mem_id' => $mem_id,
				'mem_userid' => $this->input->post('mem_userid'),
			);
			$this->Member_userid_model->insert($useridinsertdata);

			if ($selfcert_meta) {
				foreach ($selfcert_meta as $certkey => $certvalue) {
					$metadata[$certkey] = $certvalue;
				}

				$selfcertupdatedata = array(
					'mem_id' => $mem_id
				);
				$selfcertwhere = array(
					'msh_cert_key' => $selfcert_key,
				);

				$this->load->model('Member_selfcert_history_model');
				$this->Member_selfcert_history_model->update('', $selfcertupdatedata, $selfcertwhere);
			}

			$this->Member_meta_model->save($mem_id, $metadata);

			$nickinsert = array(
				'mem_id' => $mem_id,
				'mni_nickname' => $this->input->post('mem_nickname'),
				'mni_start_datetime' => cdate('Y-m-d H:i:s'),
			);
			$this->Member_nickname_model->insert($nickinsert);

			$extradata = array();
			if ($form && is_array($form)) {
				$this->load->model('Member_extra_vars_model');
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

			$levelhistoryinsert = array(
				'mem_id' => $mem_id,
				'mlh_from' => 0,
				'mlh_to' => $mem_level,
				'mlh_datetime' => cdate('Y-m-d H:i:s'),
				'mlh_reason' => '회원가입',
				'mlh_ip' => $this->input->ip_address(),
			);
			$this->load->model('Member_level_history_model');
			$this->Member_level_history_model->insert($levelhistoryinsert);

			$this->load->model('Member_group_model');
			$allgroup = $this->Member_group_model->get_all_group();
			if ($allgroup && is_array($allgroup)) {
				$this->load->model('Member_group_member_model');
				foreach ($allgroup as $gkey => $gval) {
					if (element('mgr_is_default', $gval)) {
						$gminsert = array(
							'mgr_id' => element('mgr_id', $gval),
							'mem_id' => $mem_id,
							'mgm_datetime' => cdate('Y-m-d H:i:s'),
						);
						$this->Member_group_member_model->insert($gminsert);
					}
				}
			}

			$this->point->insert_point(
				$mem_id,
				$this->cbconfig->item('point_register'),
				'회원가입을 축하합니다',
				'member',
				$mem_id,
				'회원가입'
			);

			$searchconfig = array(
				'{홈페이지명}',
				'{회사명}',
				'{홈페이지주소}',
				'{회원아이디}',
				'{회원닉네임}',
				'{회원실명}',
				'{회원이메일}',
				'{메일수신여부}',
				'{쪽지수신여부}',
				'{문자수신여부}',
				'{회원아이피}',
			);
			$mem_userid = $this->input->post('mem_userid', null, '');
			$mem_nickname = $this->input->post('mem_nickname', null, '');
			$mem_username = $selfcert_username ? $selfcert_username : $this->input->post('mem_username', null, '');
			$mem_email = $this->input->post('mem_email', null, '');
			$receive_email = $this->input->post('mem_receive_email') ? '동의' : '거부';
			$receive_note = $this->input->post('mem_use_note') ? '동의' : '거부';
			$receive_sms = $this->input->post('mem_receive_sms') ? '동의' : '거부';
			$replaceconfig = array(
				$this->cbconfig->item('site_title'),
				$this->cbconfig->item('company_name'),
				site_url(),
				$mem_userid,
				$mem_nickname,
				$mem_username,
				$mem_email,
				$receive_email,
				$receive_note,
				$receive_sms,
				$this->input->ip_address(),
			);
			$replaceconfig_escape = array(
				html_escape($this->cbconfig->item('site_title')),
				html_escape($this->cbconfig->item('company_name')),
				site_url(),
				html_escape($mem_userid),
				html_escape($mem_nickname),
				html_escape($mem_username),
				html_escape($mem_email),
				$receive_email,
				$receive_note,
				$receive_sms,
				$this->input->ip_address(),
			);

			if ( ! $this->cbconfig->item('use_register_email_auth')) {
				if (($this->cbconfig->item('send_email_register_user') && $this->input->post('mem_receive_email'))
					OR $this->cbconfig->item('send_email_register_alluser')) {
					$title = str_replace(
						$searchconfig,
						$replaceconfig,
						$this->cbconfig->item('send_email_register_user_title')
					);
					$content = str_replace(
						$searchconfig,
						$replaceconfig_escape,
						$this->cbconfig->item('send_email_register_user_content')
					);
					$this->email->from($this->cbconfig->item('webmaster_email'), $this->cbconfig->item('webmaster_name'));
					$this->email->to($this->input->post('mem_email'));
					$this->email->subject($title);
					$this->email->message($content);
					$this->email->send();
				}
			} else {
				$vericode = array('$', '/', '.');
				$verificationcode = str_replace(
					$vericode,
					'',
					password_hash($mem_id . '-' . $this->input->post('mem_email') . '-' . random_string('alnum', 10), PASSWORD_BCRYPT)
				);

				$beforeauthdata = array(
					'mem_id' => $mem_id,
					'mae_type' => 1,
				);
				$this->Member_auth_email_model->delete_where($beforeauthdata);
				$authdata = array(
					'mem_id' => $mem_id,
					'mae_key' => $verificationcode,
					'mae_type' => 1,
					'mae_generate_datetime' => cdate('Y-m-d H:i:s'),
				);
				$this->Member_auth_email_model->insert($authdata);

				$verify_url = site_url('verify/confirmemail?user=' . $this->input->post('mem_userid') . '&code=' . $verificationcode);

				$title = str_replace(
					$searchconfig,
					$replaceconfig,
					$this->cbconfig->item('send_email_register_user_verifytitle')
				);
				$content = str_replace(
					$searchconfig,
					$replaceconfig_escape,
					$this->cbconfig->item('send_email_register_user_verifycontent')
				);

				$title = str_replace('{메일인증주소}', $verify_url, $title);
				$content = str_replace('{메일인증주소}', $verify_url, $content);

				$this->email->from($this->cbconfig->item('webmaster_email'), $this->cbconfig->item('webmaster_name'));
				$this->email->to($this->input->post('mem_email'));
				$this->email->subject($title);
				$this->email->message($content);
				$this->email->send();

				$email_auth_message = $this->input->post('mem_email') . '로 인증메일이 발송되었습니다. <br />발송된 인증메일을 확인하신 후에 사이트 이용이 가능합니다';
				$this->session->set_flashdata(
					'email_auth_message',
					$email_auth_message
				);
			}

			$emailsendlistadmin = array();
			$notesendlistadmin = array();
			$smssendlistadmin = array();
			$notesendlistuser = array();
			$smssendlistuser = array();

			$superadminlist = '';
			if ($this->cbconfig->item('send_email_register_admin')
				OR $this->cbconfig->item('send_note_register_admin')
				OR $this->cbconfig->item('send_sms_register_admin')) {
				$mselect = 'mem_id, mem_email, mem_nickname, mem_phone';
				$superadminlist = $this->Member_model->get_superadmin_list($mselect);
			}

			if ($this->cbconfig->item('send_email_register_admin') && $superadminlist) {
				foreach ($superadminlist as $key => $value) {
					$emailsendlistadmin[$value['mem_id']] = $value;
				}
			}
			if ($this->cbconfig->item('send_note_register_admin') && $superadminlist) {
				foreach ($superadminlist as $key => $value) {
					$notesendlistadmin[$value['mem_id']] = $value;
				}
			}
			if (($this->cbconfig->item('send_note_register_user') && $this->input->post('mem_use_note'))) {
				$notesendlistuser['mem_id'] = $mem_id;
			}
			if ($this->cbconfig->item('send_sms_register_admin') && $superadminlist) {
				foreach ($superadminlist as $key => $value) {
					$smssendlistadmin[$value['mem_id']] = $value;
				}
			}
			if (($this->cbconfig->item('send_sms_register_user') && $this->input->post('mem_receive_sms'))
				OR $this->cbconfig->item('send_sms_register_alluser')) {
				if ($selfcert_phone OR $this->input->post('mem_phone')) {
					$smssendlistuser['mem_id'] = $mem_id;
					$smssendlistuser['mem_nickname'] = $this->input->post('mem_nickname');
					$smssendlistuser['mem_phone'] = $selfcert_phone ? $selfcert_phone : $this->input->post('mem_phone');
				}
			}

			if ($emailsendlistadmin) {
				$title = str_replace(
					$searchconfig,
					$replaceconfig,
					$this->cbconfig->item('send_email_register_admin_title')
				);
				$content = str_replace(
					$searchconfig,
					$replaceconfig_escape,
					$this->cbconfig->item('send_email_register_admin_content')
				);
				foreach ($emailsendlistadmin as $akey => $aval) {
					$this->email->clear(true);
					$this->email->from($this->cbconfig->item('webmaster_email'), $this->cbconfig->item('webmaster_name'));
					$this->email->to(element('mem_email', $aval));
					$this->email->subject($title);
					$this->email->message($content);
					$this->email->send();
				}
			}
			if ($notesendlistadmin) {
				$title = str_replace(
					$searchconfig,
					$replaceconfig,
					$this->cbconfig->item('send_note_register_admin_title')
				);
				$content = str_replace(
					$searchconfig,
					$replaceconfig_escape,
					$this->cbconfig->item('send_note_register_admin_content')
				);
				foreach ($notesendlistadmin as $akey => $aval) {
					$note_result = $this->notelib->send_note(
						$sender = 0,
						$receiver = element('mem_id', $aval),
						$title,
						$content,
						1
					);
				}
			}
			if ($notesendlistuser && element('mem_id', $notesendlistuser)) {
				$title = str_replace(
					$searchconfig,
					$replaceconfig,
					$this->cbconfig->item('send_note_register_user_title')
				);
				$content = str_replace(
					$searchconfig,
					$replaceconfig_escape,
					$this->cbconfig->item('send_note_register_user_content')
				);
				$note_result = $this->notelib->send_note(
					$sender = 0,
					$receiver = element('mem_id', $notesendlistuser),
					$title,
					$content,
					1
				);
			}
			if ($smssendlistadmin) {
				if (file_exists(APPPATH . 'libraries/Smslib.php')) {
					$this->load->library(array('smslib'));
					$content = str_replace(
						$searchconfig,
						$replaceconfig,
						$this->cbconfig->item('send_sms_register_admin_content')
					);
					$sender = array(
						'phone' => $this->cbconfig->item('sms_admin_phone'),
					);
					$receiver = array();
					foreach ($smssendlistadmin as $akey => $aval) {
						$receiver[] = array(
							'mem_id' => element('mem_id', $aval),
							'name' => element('mem_nickname', $aval),
							'phone' => element('mem_phone', $aval),
						);
					}
					$smsresult = $this->smslib->send($receiver, $sender, $content, $date = '', '회원가입알림');
				}
			}
			if ($smssendlistuser) {
				if (file_exists(APPPATH . 'libraries/Smslib.php')) {
					$this->load->library(array('smslib'));
					$content = str_replace(
						$searchconfig,
						$replaceconfig,
						$this->cbconfig->item('send_sms_register_user_content')
					);
					$sender = array(
						'phone' => $this->cbconfig->item('sms_admin_phone'),
					);
					$receiver = array();
					$receiver[] = $smssendlistuser;
					$smsresult = $this->smslib->send($receiver, $sender, $content, $date = '', '회원가입알림');
				}
			}

			$member_register_data = array(
				'mem_id' => $mem_id,
				'mrg_ip' => $this->input->ip_address(),
				'mrg_datetime' => cdate('Y-m-d H:i:s'),
				'mrg_useragent' => $this->agent->agent_string(),
				'mrg_referer' => $this->session->userdata('site_referer'),
			);
			$recommended = '';
			if ($this->input->post('mem_recommend')) {
				$recommended = $this->Member_model->get_by_userid($this->input->post('mem_recommend'), 'mem_id');
				if (element('mem_id', $recommended)) {
					$member_register_data['mrg_recommend_mem_id'] = element('mem_id', $recommended);
				} else {
					$recommended['mem_id'] = 0;
				}
			}
			$this->load->model('Member_register_model');
			$this->Member_register_model->insert($member_register_data);

			if ($this->input->post('mem_recommend')) {
				if ($this->cbconfig->item('point_recommended')) {
					// 추천인이 존재할 경우 추천된 사람
					$this->point->insert_point(
						element('mem_id', $recommended),
						$this->cbconfig->item('point_recommended'),
						$this->input->post('mem_nickname') . ' 님이 회원가입시 추천함',
						'member_recommended',
						$mem_id,
						'회원가입추천'
					);
				}
				if ($this->cbconfig->item('point_recommender')) {
					// 추천인이 존재할 경우 가입자에게
					$this->point->insert_point(
						$mem_id,
						$this->cbconfig->item('point_recommender'),
						'회원가입 추천인존재',
						'member_recommender',
						$mem_id,
						'회원가입추천인존재'
					);
				}
			}

			$this->session->set_flashdata(
				'nickname',
				$this->input->post('mem_nickname')
			);

			if ( ! $this->cbconfig->item('use_register_email_auth')) {
				$this->session->set_userdata(
					'mem_id',
					$mem_id
				);
			}
			$this->session->unset_userdata('selfcertinfo');

			redirect('register/result');
		}
	}


	/**
	 * 회원가입 결과 페이지입니다
	 */
	public function result()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_register_result';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		$this->session->keep_flashdata('nickname');
		$this->session->keep_flashdata('email_auth_message');

		if ( ! $this->session->flashdata('nickname')) {
			redirect();
		}

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before_layout'] = Events::trigger('before_layout', $eventname);

		/**
		 * 레이아웃을 정의합니다
		 */
		$page_title = $this->cbconfig->item('site_meta_title_register_result');
		$meta_description = $this->cbconfig->item('site_meta_description_register_result');
		$meta_keywords = $this->cbconfig->item('site_meta_keywords_register_result');
		$meta_author = $this->cbconfig->item('site_meta_author_register_result');
		$page_name = $this->cbconfig->item('site_page_name_register_result');

		$layoutconfig = array(
			'path' => 'register',
			'layout' => 'layout',
			'skin' => 'register_result',
			'layout_dir' => $this->cbconfig->item('layout_register'),
			'mobile_layout_dir' => $this->cbconfig->item('mobile_layout_register'),
			'use_sidebar' => $this->cbconfig->item('sidebar_register'),
			'use_mobile_sidebar' => $this->cbconfig->item('mobile_sidebar_register'),
			'skin_dir' => $this->cbconfig->item('skin_register'),
			'mobile_skin_dir' => $this->cbconfig->item('mobile_skin_register'),
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


	public function ajax_userid_check()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_register_ajax_userid_check';
		$this->load->event($eventname);

		$result = array();
		$this->output->set_content_type('application/json');

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		$userid = trim($this->input->post('userid'));
		if (empty($userid)) {
			$result = array(
				'result' => 'no',
				'reason' => '아이디값이 넘어오지 않았습니다',
			);
			exit(json_encode($result));
		}

		if ( ! preg_match("/^([a-z0-9_])+$/i", $userid)) {
			$result = array(
				'result' => 'no',
				'reason' => '아이디는 숫자, 알파벳, _ 만 입력가능합니다',
			);
			exit(json_encode($result));
		}

		$where = array(
			'mem_userid' => $userid,
		);
		$count = $this->Member_userid_model->count_by($where);
		if ($count > 0) {
			$result = array(
				'result' => 'no',
				'reason' => '이미 사용중인 아이디입니다',
			);
			exit(json_encode($result));
		}

		if ($this->_mem_userid_check($userid) === false) {
			$result = array(
				'result' => 'no',
				'reason' => $userid . '은(는) 예약어로 사용하실 수 없는 회원아이디입니다',
			);
			exit(json_encode($result));
		}

		// 이벤트가 존재하면 실행합니다
		Events::trigger('after', $eventname);

		$result = array(
			'result' => 'available',
			'reason' => '사용 가능한 아이디입니다',
		);
		exit(json_encode($result));
	}


	public function ajax_email_check()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_register_ajax_email_check';
		$this->load->event($eventname);

		$result = array();
		$this->output->set_content_type('application/json');

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		$email = trim($this->input->post('email'));
		if (empty($email)) {
			$result = array(
				'result' => 'no',
				'reason' => '이메일값이 넘어오지 않았습니다',
			);
			exit(json_encode($result));
		}

		if ($this->member->item('mem_email')
			&& $this->member->item('mem_email') === $email) {
			$result = array(
				'result' => 'available',
				'reason' => '사용 가능한 이메일입니다',
			);
			exit(json_encode($result));
		}

		$where = array(
			'mem_email' => $email,
		);
		$count = $this->Member_model->count_by($where);
		if ($count > 0) {
			$result = array(
				'result' => 'no',
				'reason' => '이미 사용중인 이메일입니다',
			);
			exit(json_encode($result));
		}

		if ($this->_mem_email_check($email) === false) {
			$result = array(
				'result' => 'no',
				'reason' => $email . '은(는) 예약어로 사용하실 수 없는 이메일입니다',
			);
			exit(json_encode($result));
		}

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		$result = array(
			'result' => 'available',
			'reason' => '사용 가능한 이메일입니다',
		);
		exit(json_encode($result));
	}


	public function ajax_password_check()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_register_ajax_password_check';
		$this->load->event($eventname);

		$result = array();
		$this->output->set_content_type('application/json');

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		$password = trim($this->input->post('password'));
		if (empty($password)) {
			$result = array(
				'result' => 'no',
				'reason' => '패스워드값이 넘어오지 않았습니다',
			);
			exit(json_encode($result));
		}

		if ($this->_mem_password_check($password) === false) {
			$result = array(
				'result' => 'no',
				'reason' => '패스워드는 최소 1개 이상의 숫자를 포함해야 합니다',
			);
			exit(json_encode($result));
		}

		$result = array(
			'result' => 'available',
			'reason' => '사용 가능한 패스워드입니다',
		);
		exit(json_encode($result));
	}


	public function ajax_nickname_check()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_register_ajax_nickname_check';
		$this->load->event($eventname);

		$result = array();
		$this->output->set_content_type('application/json');

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		$nickname = trim($this->input->post('nickname'));
		if (empty($nickname)) {
			$result = array(
				'result' => 'no',
				'reason' => '닉네임값이 넘어오지 않았습니다',
			);
			exit(json_encode($result));
		}

		if ($this->member->item('mem_nickname')
			&& $this->member->item('mem_nickname') === $nickname) {
			$result = array(
				'result' => 'available',
				'reason' => '사용 가능한 닉네임입니다',
			);
			exit(json_encode($result));
		}

		$where = array(
			'mem_nickname' => $nickname,
		);
		$count = $this->Member_model->count_by($where);
		if ($count > 0) {
			$result = array(
				'result' => 'no',
				'reason' => '이미 사용중인 닉네임입니다',
			);
			exit(json_encode($result));
		}

		if ($this->_mem_nickname_check($nickname) === false) {
			$result = array(
				'result' => 'no',
				'reason' => '이미 사용중인 닉네임입니다',
			);
			exit(json_encode($result));
		}

		$result = array(
			'result' => 'available',
			'reason' => '사용 가능한 닉네임입니다',
		);
		exit(json_encode($result));
	}


	/**
	 * 회원가입시 회원아이디를 체크하는 함수입니다
	 */
	public function _mem_userid_check($str)
	{
		if (preg_match("/[\,]?{$str}/i", $this->cbconfig->item('denied_userid_list'))) {
			$this->form_validation->set_message(
				'_mem_userid_check',
				$str . ' 은(는) 예약어로 사용하실 수 없는 회원아이디입니다'
			);
			return false;
		}

		return true;
	}


	/**
	 * 회원가입시 닉네임을 체크하는 함수입니다
	 */
	public function _mem_nickname_check($str)
	{
		$this->load->helper('chkstring');
		if (chkstring($str, _HANGUL_ + _ALPHABETIC_ + _NUMERIC_) === false) {
			$this->form_validation->set_message(
				'_mem_nickname_check',
				'닉네임은 공백없이 한글, 영문, 숫자만 입력 가능합니다'
			);
			return false;
		}

		if (preg_match("/[\,]?{$str}/i", $this->cbconfig->item('denied_nickname_list'))) {
			$this->form_validation->set_message(
				'_mem_nickname_check',
				$str . ' 은(는) 예약어로 사용하실 수 없는 닉네임입니다'
			);
			return false;
		}
		$countwhere = array(
			'mem_nickname' => $str,
		);
		$row = $this->Member_model->count_by($countwhere);

		if ($row > 0) {
			$this->form_validation->set_message(
				'_mem_nickname_check',
				$str . ' 는 이미 다른 회원이 사용하고 있는 닉네임입니다'
			);
			return false;
		}

		$countwhere = array(
			'mni_nickname' => $str,
		);
		$row = $this->Member_nickname_model->count_by($countwhere);

		if ($row > 0) {
			$this->form_validation->set_message(
				'_mem_nickname_check',
				$str . ' 는 이미 다른 회원이 사용하고 있는 닉네임입니다'
			);
			return false;
		}

		return true;
	}


	/**
	 * 회원가입시 이메일을 체크하는 함수입니다
	 */
	public function _mem_email_check($str)
	{
		list($emailid, $emaildomain) = explode('@', $str);
		$denied_list = explode(',', $this->cbconfig->item('denied_email_list'));
		$emaildomain = trim($emaildomain);
		$denied_list = array_map('trim', $denied_list);
		if (in_array($emaildomain, $denied_list)) {
			$this->form_validation->set_message(
				'_mem_email_check',
				$emaildomain . ' 은(는) 사용하실 수 없는 이메일입니다'
			);
			return false;
		}

		return true;
	}


	/**
	 * 회원가입시 추천인을 체크하는 함수입니다
	 */
	public function _mem_recommend_check($str)
	{
		if( ! $str) {
			return true;
		}

		$countwhere = array(
			'mem_userid' => $str,
			'mem_denied' => 0,
		);
		$row = $this->Member_model->count_by($countwhere);

		if ($row === 0) {
			$this->form_validation->set_message(
				'_mem_recommend_check',
				$str . ' 는 존재하지 않는 추천인 회원아이디입니다'
			);
			return false;
		}

		return true;
	}


	/**
	 * 회원가입시 captcha 체크하는 함수입니다
	 */
	public function _check_captcha($str)
	{
		$captcha = $this->session->userdata('captcha');
		if ( ! is_array($captcha) OR ! element('word', $captcha) OR strtolower(element('word', $captcha)) !== strtolower($str)) {
			$this->session->unset_userdata('captcha');
			$this->form_validation->set_message(
				'_check_captcha',
				'자동등록방지코드가 잘못되었습니다'
			);
			return false;
		}
		return true;
	}


	/**
	 * 회원가입시 recaptcha 체크하는 함수입니다
	 */
	public function _check_recaptcha($str)
	{
		$url = 'https://www.google.com/recaptcha/api/siteverify';
		$data = array(
			'secret' => $this->cbconfig->item('recaptcha_secret'),
			'response' => $str,
		);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, sizeof($data));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);
		curl_close($ch);

		$obj = json_decode($result);

		if ((string) $obj->success !== '1') {
			$this->form_validation->set_message(
				'_check_recaptcha',
				'자동등록방지코드가 잘못되었습니다'
			);
			return false;
		}

		return true;
	}


	/**
	 * 회원가입시 패스워드가 올바른 규약에 의해 입력되었는지를 체크하는 함수입니다
	 */
	public function _mem_password_check($str)
	{
		$uppercase = $this->cbconfig->item('password_uppercase_length');
		$number = $this->cbconfig->item('password_numbers_length');
		$specialchar = $this->cbconfig->item('password_specialchars_length');

		$this->load->helper('chkstring');
		$str_uc = count_uppercase($str);
		$str_num = count_numbers($str);
		$str_spc = count_specialchars($str);

		if ($str_uc < $uppercase OR $str_num < $number OR $str_spc < $specialchar) {

			$description = '비밀번호는 ';
			if ($str_uc < $uppercase) {
				$description .= ' ' . $uppercase . '개 이상의 대문자';
			}
			if ($str_num < $number) {
				$description .= ' ' . $number . '개 이상의 숫자';
			}
			if ($str_spc < $specialchar) {
				$description .= ' ' . $specialchar . '개 이상의 특수문자';
			}
			$description .= '를 포함해야 합니다';

			$this->form_validation->set_message(
				'_mem_password_check',
				$description
			);
			return false;

		}

		return true;
	}
}
