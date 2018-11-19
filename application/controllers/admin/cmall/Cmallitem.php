<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Cmallitem class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 관리자>컨텐츠몰관리>상품관리 controller 입니다.
 */
class Cmallitem extends CB_Controller
{

	/**
	 * 관리자 페이지 상의 현재 디렉토리입니다
	 * 페이지 이동시 필요한 정보입니다
	 */
	public $pagedir = 'cmall/cmallitem';

	/**
	 * 모델을 로딩합니다
	 */
	protected $models = array('Cmall_item', 'Cmall_item_meta', 'Cmall_item_detail', 'Cmall_category', 'Cmall_category_rel');

	/**
	 * 이 컨트롤러의 메인 모델 이름입니다
	 */
	protected $modelname = 'Cmall_item_model';

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
		$eventname = 'event_admin_cmall_cmallitem_index';
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
			'cit_id' => $param->sort('cit_id', 'asc'),
			'cit_key' => $param->sort('cit_key', 'asc'),
			'cit_order' => $param->sort('cit_order', 'asc'),
			'cit_name' => $param->sort('cit_name', 'asc'),
			'cit_datetime' => $param->sort('cit_datetime', 'asc'),
			'cit_updated_datetime' => $param->sort('cit_updated_datetime', 'asc'),
			'cit_hit' => $param->sort('cit_hit', 'asc'),
			'cit_sell_count' => $param->sort('cit_sell_count', 'asc'),
			'cit_price' => $param->sort('cit_price', 'asc'),
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
		$this->{$this->modelname}->allow_search_field = array('cit_id', 'cit_key', 'cit_name', 'cit_datetime', 'cit_updated_datetime', 'cit_content', 'cit_mobile_content', 'cit_price'); // 검색이 가능한 필드
		$this->{$this->modelname}->search_field_equal = array('cit_id', 'cit_price'); // 검색중 like 가 아닌 = 검색을 하는 필드
		$this->{$this->modelname}->allow_order_field = array('cit_id', 'cit_key', 'cit_order', 'cit_name', 'cit_datetime', 'cit_updated_datetime', 'cit_hit', 'cit_sell_count', 'cit_price'); // 정렬이 가능한 필드
		$result = $this->{$this->modelname}
			->get_admin_list($per_page, $offset, '', '', $findex, $forder, $sfield, $skeyword);

		$list_num = $result['total_rows'] - ($page - 1) * $per_page;
		if (element('list', $result)) {
			foreach (element('list', $result) as $key => $val) {
				$result['list'][$key]['meta'] = $this->Cmall_item_meta_model->get_all_meta(element('cit_id', $val));
				$result['list'][$key]['category'] = $this->Cmall_category_model->get_category(element('cit_id', $val));
				$result['list'][$key]['item_layout_option'] = get_skin_name(
					'_layout',
					element('item_layout', $result['list'][$key]['meta']),
					'기본설정따름'
				);
				$result['list'][$key]['item_mobile_layout_option'] = get_skin_name(
					'_layout',
					element('item_mobile_layout', $result['list'][$key]['meta']),
					'기본설정따름'
				);
				$result['list'][$key]['item_skin_option'] = get_skin_name(
					'cmall',
					element('item_skin', $result['list'][$key]['meta']),
					'기본설정따름'
				);
				$result['list'][$key]['item_mobile_skin_option'] = get_skin_name(
					'cmall',
					element('item_mobile_skin', $result['list'][$key]['meta']),
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
		$search_option = array('cit_key' => '상품코드', 'cit_name' => '상품명', 'cit_content' => '상품내용', 'cit_mobile_content' => '모바일상품내용', 'cit_datetime' => '입력일', 'cit_updated_datetime' => '최종수정일', 'cit_price' => '판매가격');
		$view['view']['skeyword'] = ($sfield && array_key_exists($sfield, $search_option)) ? $skeyword : '';
		$view['view']['search_option'] = search_option($search_option, $sfield);
		$view['view']['listall_url'] = admin_url($this->pagedir);
		$view['view']['write_url'] = admin_url($this->pagedir . '/write');
		$view['view']['list_delete_url'] = admin_url($this->pagedir . '/listdelete/?' . $param->output());
		$view['view']['list_update_url'] = admin_url($this->pagedir . '/listupdate/?' . $param->output());

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
		$eventname = 'event_admin_cmall_cmallitem_write';
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
			$cmall_item_meta = $this->Cmall_item_meta_model->get_all_meta(element('cit_id', $getdata));
			if (is_array($cmall_item_meta)) {
				$getdata = array_merge($getdata, $cmall_item_meta);
			}
			$cat = $this->Cmall_category_model->get_category(element('cit_id', $getdata));
			if ($cat) {
				foreach ($cat as $ck => $cv) {
					$getdata['category'][] = $cv['cca_id'];
				}
			}
		} else {
			// 기본값 설정
			$getdata['cit_key'] = time();
			$getdata['cit_status'] = '1';
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
				'label' => '전송',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'cit_name',
				'label' => '상품명',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'cit_order',
				'label' => '상품정렬순서',
				'rules' => 'trim|required|numeric',
			),
			array(
				'field' => 'cit_type1',
				'label' => '추천',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'cit_type2',
				'label' => '인기',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'cit_type3',
				'label' => '신상품',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'cit_type4',
				'label' => '할인',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'cit_status',
				'label' => '출력여부',
				'rules' => 'trim|numeric',
			),
			array(
				'field' => 'cit_summary',
				'label' => '기본설명',
				'rules' => 'trim',
			),
			array(
				'field' => 'cit_content',
				'label' => '상품내용',
				'rules' => 'trim|required',
			),
			array(
				'field' => 'cit_mobile_content',
				'label' => '모바일상품내용',
				'rules' => 'trim',
			),
			array(
				'field' => 'cit_price',
				'label' => '상품가격',
				'rules' => 'trim|required|numeric|is_natural',
			),
			array(
				'field' => 'cit_download_days',
				'label' => '다운로드기간제한',
				'rules' => 'trim|required|numeric|is_natural',
			),
			array(
				'field' => 'info_title_1',
				'label' => '기본정보1제목',
				'rules' => 'trim',
			),
			array(
				'field' => 'info_content_1',
				'label' => '기본정보1내용',
				'rules' => 'trim',
			),
			array(
				'field' => 'info_title_2',
				'label' => '기본정보2제목',
				'rules' => 'trim',
			),
			array(
				'field' => 'info_content_2',
				'label' => '기본정보2내용',
				'rules' => 'trim',
			),
			array(
				'field' => 'info_title_3',
				'label' => '기본정보3제목',
				'rules' => 'trim',
			),
			array(
				'field' => 'info_content_3',
				'label' => '기본정보3내용',
				'rules' => 'trim',
			),
			array(
				'field' => 'info_title_4',
				'label' => '기본정보4제목',
				'rules' => 'trim',
			),
			array(
				'field' => 'info_content_4',
				'label' => '기본정보4내용',
				'rules' => 'trim',
			),
			array(
				'field' => 'info_title_5',
				'label' => '기본정보5제목',
				'rules' => 'trim',
			),
			array(
				'field' => 'info_content_5',
				'label' => '기본정보5내용',
				'rules' => 'trim',
			),
			array(
				'field' => 'info_title_6',
				'label' => '기본정보6제목',
				'rules' => 'trim',
			),
			array(
				'field' => 'info_content_6',
				'label' => '기본정보6내용',
				'rules' => 'trim',
			),
			array(
				'field' => 'info_title_7',
				'label' => '기본정보7제목',
				'rules' => 'trim',
			),
			array(
				'field' => 'info_content_7',
				'label' => '기본정보7내용',
				'rules' => 'trim',
			),
			array(
				'field' => 'info_title_8',
				'label' => '기본정보8제목',
				'rules' => 'trim',
			),
			array(
				'field' => 'info_content_8',
				'label' => '기본정보8내용',
				'rules' => 'trim',
			),
			array(
				'field' => 'info_title_9',
				'label' => '기본정보9제목',
				'rules' => 'trim',
			),
			array(
				'field' => 'info_content_9',
				'label' => '기본정보9내용',
				'rules' => 'trim',
			),
			array(
				'field' => 'info_title_10',
				'label' => '기본정보10제목',
				'rules' => 'trim',
			),
			array(
				'field' => 'info_content_10',
				'label' => '기본정보10내용',
				'rules' => 'trim',
			),
			array(
				'field' => 'item_layout',
				'label' => '레이아웃',
				'rules' => 'trim',
			),
			array(
				'field' => 'item_mobile_layout',
				'label' => '모바일레이아웃',
				'rules' => 'trim',
			),
			array(
				'field' => 'item_sidebar',
				'label' => '사이드바',
				'rules' => 'trim',
			),
			array(
				'field' => 'item_mobile_sidebar',
				'label' => '모바일사이드바',
				'rules' => 'trim',
			),
			array(
				'field' => 'item_skin',
				'label' => '스킨',
				'rules' => 'trim',
			),
			array(
				'field' => 'item_mobile_skin',
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
				'label' => '모바일하다내용',
				'rules' => 'trim',
			),
			array(
				'field' => 'demo_user_link',
				'label' => '사용자데모',
				'rules' => 'trim',
			),
			array(
				'field' => 'demo_admin_link',
				'label' => '관리자데모',
				'rules' => 'trim',
			),
		);
		if ($this->input->post($primary_key)) {
			$config[] = array(
				'field' => 'cit_key',
				'label' => '페이지주소',
				'rules' => 'trim|required|alpha_dash|min_length[3]|max_length[50]|is_unique[cmall_item.cit_key.cit_id.' . $getdata['cit_id'] . ']',
			);
		} else {
			$config[] = array(
				'field' => 'cit_key',
				'label' => '페이지주소',
				'rules' => 'trim|required|alpha_dash|min_length[3]|max_length[50]|is_unique[cmall_item.cit_key]',
			);
		}
		$this->form_validation->set_rules($config);

		$form_validation = $this->form_validation->run();
		$file_error = '';

		if ($form_validation) {
			$this->load->library('upload');
			for ($k = 1; $k <= 10; $k++) {
				if (isset($_FILES) && isset($_FILES['cit_file_' . $k]) && isset($_FILES['cit_file_' . $k]['name']) && $_FILES['cit_file_' . $k]['name']) {
					$upload_path = config_item('uploads_dir') . '/cmallitem/';
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
					$uploadconfig['max_size'] = '5000';
					$uploadconfig['encrypt_name'] = true;

					$this->upload->initialize($uploadconfig);

					if ($this->upload->do_upload('cit_file_' . $k)) {
						$img = $this->upload->data();
						$cit_file[$k] = cdate('Y') . '/' . cdate('m') . '/' . element('file_name', $img);
					} else {
						$file_error = $this->upload->display_errors();
						break;

					}
				}
			}
		}

		$uploadfiledata = array();
		$uploadfiledata2 = array();

		if ($form_validation && $file_error === '') {
			$this->load->library('upload');
			if (isset($_FILES) && isset($_FILES['cde_file']) && isset($_FILES['cde_file']['name']) && is_array($_FILES['cde_file']['name'])) {
				$filecount = count($_FILES['cde_file']['name']);
				$upload_path = config_item('uploads_dir') . '/cmallitemdetail/';
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

				foreach ($_FILES['cde_file']['name'] as $i => $value) {
					if ($value) {
						$uploadconfig = array();
						$uploadconfig['upload_path'] = $upload_path;
						$uploadconfig['allowed_types'] = '*';
						$uploadconfig['encrypt_name'] = true;

						$this->upload->initialize($uploadconfig);
						$_FILES['userfile']['name'] = $_FILES['cde_file']['name'][$i];
						$_FILES['userfile']['type'] = $_FILES['cde_file']['type'][$i];
						$_FILES['userfile']['tmp_name'] = $_FILES['cde_file']['tmp_name'][$i];
						$_FILES['userfile']['error'] = $_FILES['cde_file']['error'][$i];
						$_FILES['userfile']['size'] = $_FILES['cde_file']['size'][$i];
						if ($this->upload->do_upload()) {
							$filedata = $this->upload->data();

							$uploadfiledata[$i]['cde_filename'] = cdate('Y') . '/' . cdate('m') . '/' . element('file_name', $filedata);
							$uploadfiledata[$i]['cde_originname'] = element('orig_name', $filedata);
							$uploadfiledata[$i]['cde_filesize'] = intval(element('file_size', $filedata) * 1024);
							$uploadfiledata[$i]['cde_type'] = str_replace('.', '', element('file_ext', $filedata));
							$uploadfiledata[$i]['is_image'] = element('is_image', $filedata) ? element('is_image', $filedata) : 0;
							$cde_title = $this->input->post('cde_title');
							$uploadfiledata[$i]['cde_title'] = element($i, $cde_title);
							$cde_price = $this->input->post('cde_price');
							$uploadfiledata[$i]['cde_price'] = element($i, $cde_price) ? element($i, $cde_price) : 0;
							$cde_status = $this->input->post('cde_status');
							$uploadfiledata[$i]['cde_status'] = element($i, $cde_status) ? element($i, $cde_status) : 0;

						} else {
							$file_error = $this->upload->display_errors();
							break;
						}
					}
				}
			}
			if (isset($_FILES) && isset($_FILES['cde_file_update']) && isset($_FILES['cde_file_update']['name']) && is_array($_FILES['cde_file_update']['name']) && $file_error === '') {
				$filecount = count($_FILES['cde_file_update']['name']);
				$upload_path = config_item('uploads_dir') . '/cmallitemdetail/';
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

				foreach ($_FILES['cde_file_update']['name'] as $i => $value) {
					if ($value) {
						$uploadconfig = array();
						$uploadconfig['upload_path'] = $upload_path;
						$uploadconfig['allowed_types'] = '*';
						$uploadconfig['encrypt_name'] = true;
						$this->upload->initialize($uploadconfig);
						$_FILES['userfile']['name'] = $_FILES['cde_file_update']['name'][$i];
						$_FILES['userfile']['type'] = $_FILES['cde_file_update']['type'][$i];
						$_FILES['userfile']['tmp_name'] = $_FILES['cde_file_update']['tmp_name'][$i];
						$_FILES['userfile']['error'] = $_FILES['cde_file_update']['error'][$i];
						$_FILES['userfile']['size'] = $_FILES['cde_file_update']['size'][$i];
						if ($this->upload->do_upload()) {
							$filedata = $this->upload->data();

							$uploadfiledata2[$i]['cde_id'] = $i;
							$uploadfiledata2[$i]['cde_filename'] = cdate('Y') . '/' . cdate('m') . '/' . element('file_name', $filedata);
							$uploadfiledata2[$i]['cde_originname'] = element('orig_name', $filedata);
							$uploadfiledata2[$i]['cde_filesize'] = intval(element('file_size', $filedata) * 1024);
							$uploadfiledata2[$i]['cde_type'] = str_replace('.', '', element('file_ext', $filedata));
							$uploadfiledata2[$i]['is_image'] = element('is_image', $filedata) ? element('is_image', $filedata) : 0;
						} else {
							$file_error = $this->upload->display_errors();
							break;
						}
					}
				}
			}
		}


		/**
		 * 유효성 검사를 하지 않는 경우, 또는 유효성 검사에 실패한 경우입니다.
		 * 즉 글쓰기나 수정 페이지를 보고 있는 경우입니다
		 */
		if ($form_validation === false OR $file_error !== '') {

			// 이벤트가 존재하면 실행합니다
			$view['view']['event']['formrunfalse'] = Events::trigger('formrunfalse', $eventname);

			if ($file_error) {
				$view['view']['message'] = $file_error;
			}

			$getdata = array();
			if ($pid) {
				$getdata = $this->{$this->modelname}->get_one($pid);
				$cmall_item_meta = $this->Cmall_item_meta_model->get_all_meta(element('cit_id', $getdata));
				if (is_array($cmall_item_meta)) {
					$getdata = array_merge($getdata, $cmall_item_meta);
				}
				$cat = $this->Cmall_category_model->get_category(element('cit_id', $getdata));
				if ($cat) {
					foreach ($cat as $ck => $cv) {
						$getdata['category'][] = $cv['cca_id'];
					}
				}
				$where = array(
					'cit_id' => element('cit_id', $getdata),
				);
				$getdata['item_detail'] = $this->Cmall_item_detail_model->get('', '', $where, '', '', 'cde_id', 'ASC');

			}
			$view['view']['data'] = $getdata;
			$view['view']['data']['item_layout_option'] = get_skin_name(
				'_layout',
				set_value('item_layout', element('item_layout', $getdata)),
				'기본설정따름'
			);
			$view['view']['data']['item_mobile_layout_option'] = get_skin_name(
				'_layout',
				set_value('item_mobile_layout', element('item_mobile_layout', $getdata)),
				'기본설정따름'
			);
			$view['view']['data']['item_skin_option'] = get_skin_name(
				'cmall',
				set_value('item_skin', element('item_skin', $getdata)),
				'기본설정따름'
			);
			$view['view']['data']['item_mobile_skin_option'] = get_skin_name(
				'cmall',
				set_value('item_mobile_skin', element('item_mobile_skin', $getdata)),
				'기본설정따름'
			);
			$view['view']['data']['all_category'] = $this->Cmall_category_model->get_all_category();

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

			$cit_order = $this->input->post('cit_order') ? $this->input->post('cit_order') : 0;
			$cit_type1 = $this->input->post('cit_type1') ? $this->input->post('cit_type1') : 0;
			$cit_type2 = $this->input->post('cit_type2') ? $this->input->post('cit_type2') : 0;
			$cit_type3 = $this->input->post('cit_type3') ? $this->input->post('cit_type3') : 0;
			$cit_type4 = $this->input->post('cit_type4') ? $this->input->post('cit_type4') : 0;
			$cit_status = $this->input->post('cit_status') ? 1 : 0;
			$content_type = $this->cbconfig->item('use_cmall_product_dhtml') ? 1 : 0;
			$cit_price = $this->input->post('cit_price') ? $this->input->post('cit_price') : 0;
			$cit_download_days = $this->input->post('cit_download_days') ? $this->input->post('cit_download_days') : 0;

			$updatedata = array(
				'cit_key' => $this->input->post('cit_key', null, ''),
				'cit_name' => $this->input->post('cit_name', null, ''),
				'cit_order' => $cit_order,
				'cit_type1' => $cit_type1,
				'cit_type2' => $cit_type2,
				'cit_type3' => $cit_type3,
				'cit_type4' => $cit_type4,
				'cit_status' => $cit_status,
				'cit_summary' => $this->input->post('cit_summary', null, ''),
				'cit_content' => $this->input->post('cit_content', null, ''),
				'cit_mobile_content' => $this->input->post('cit_mobile_content', null, ''),
				'cit_content_html_type' => $content_type,
				'cit_price' => $cit_price,
				'cit_updated_datetime' => cdate('Y-m-d H:i:s'),
				'cit_download_days' => $cit_download_days,
			);

			for ($k = 1; $k <= 10; $k++) {
				if ($this->input->post('cit_file_' . $k . '_del')) {
					$updatedata['cit_file_' . $k] = '';
				} elseif (isset($cit_file[$k]) && $cit_file[$k]) {
					$updatedata['cit_file_' . $k] = $cit_file[$k];
				}
			}

			$array = array(
				'info_title_1', 'info_content_1', 'info_title_2', 'info_content_2', 'info_title_3',
				'info_content_3', 'info_title_4', 'info_content_4', 'info_title_5', 'info_content_5',
				'info_title_6', 'info_content_6', 'info_title_7', 'info_content_7', 'info_title_8',
				'info_content_8', 'info_title_9', 'info_content_9', 'info_title_10', 'info_content_10',
				'item_layout', 'item_mobile_layout', 'item_sidebar', 'item_mobile_sidebar', 'item_skin',
				'item_mobile_skin', 'header_content', 'footer_content', 'mobile_header_content',
				'mobile_footer_content', 'demo_user_link', 'demo_admin_link', 'seller_mem_userid'
			);

			$metadata = array();
			foreach ($array as $value) {
				$metadata[$value] = $this->input->post($value, null, '');
			}
			$metadata['updated_mem_id'] = $this->member->item('mem_id');
			$metadata['updated_ip_address'] = $this->input->ip_address();
			$metadata['seller_mem_id'] = '';
			if ($this->input->post('seller_mem_userid')) {
				$mem = $this->Member_model->get_by_userid($this->input->post('seller_mem_userid'), 'mem_id');
				$metadata['seller_mem_id'] = element('mem_id', $mem);
			}


			/**
			 * 게시물을 수정하는 경우입니다
			 */
			$cmall_category = $this->input->post('cmall_category', null, '');

			if ($this->input->post($primary_key)) {
				$this->{$this->modelname}->update($this->input->post($primary_key), $updatedata);
				$this->Cmall_item_meta_model->save($pid, $metadata);
				$this->Cmall_category_rel_model->save_category($this->input->post($primary_key), $cmall_category);

				$this->session->set_flashdata(
					'message',
					'정상적으로 수정되었습니다'
				);
			} else {
				/**
				 * 게시물을 새로 입력하는 경우입니다
				 */
				$updatedata['cit_datetime'] = cdate('Y-m-d H:i:s');
				$updatedata['mem_id'] = $this->member->item('mem_id');
				$pid = $this->{$this->modelname}->insert($updatedata);

				$metadata['ip_address'] = $this->input->ip_address();

				$this->Cmall_item_meta_model->save($pid, $metadata);
				$this->Cmall_category_rel_model->save_category($pid, $cmall_category);

				$this->session->set_flashdata(
					'message',
					'정상적으로 입력되었습니다'
				);
			}

			$this->load->model('Cmall_item_history_model');
			$historydata = array(
				'cit_id' => $pid,
				'mem_id' => $this->member->item('mem_id'),
				'chi_title' => $this->input->post('cit_name', null, ''),
				'chi_content' => $this->input->post('cit_content', null, ''),
				'chi_content_html_type' => $content_type,
				'chi_ip' => $this->input->ip_address(),
				'chi_datetime' => cdate('Y-m-d H:i:s'),

			);
			$this->Cmall_item_history_model->insert($historydata);

			$file_updated = false;
			$file_changed = false;
			if ($uploadfiledata && is_array($uploadfiledata) && count($uploadfiledata) > 0) {
				foreach ($uploadfiledata as $pkey => $pval) {
					if ($pval) {
						$cde_price = element('cde_price', $pval) ? element('cde_price', $pval) : 0;
						$cde_is_image = element('is_image', $pval) ? 1 : 0;
						$cde_status = element('cde_status', $pval) ? element('cde_status', $pval) : 0;
						$fileupdate = array(
							'cit_id' => $pid,
							'mem_id' => $this->member->item('mem_id'),
							'cde_title' => element('cde_title', $pval),
							'cde_price' => $cde_price,
							'cde_originname' => element('cde_originname', $pval),
							'cde_filename' => element('cde_filename', $pval),
							'cde_filesize' => element('cde_filesize', $pval),
							'cde_type' => element('cde_type', $pval),
							'cde_is_image' => $cde_is_image,
							'cde_datetime' => cdate('Y-m-d H:i:s'),
							'cde_ip' => $this->input->ip_address(),
							'cde_status' => $cde_status,
						);
						$file_id = $this->Cmall_item_detail_model->insert($fileupdate);
					}
				}
			}
			if ($uploadfiledata2 && is_array($uploadfiledata2) && count($uploadfiledata2) > 0) {
				foreach ($uploadfiledata2 as $pkey => $pval) {
					if ($pval) {
						$cde_is_image = element('is_image', $pval) ? 1 : 0;
						$fileupdate = array(
							'mem_id' => $this->member->item('mem_id'),
							'cde_originname' => element('cde_originname', $pval),
							'cde_filename' => element('cde_filename', $pval),
							'cde_filesize' => element('cde_filesize', $pval),
							'cde_type' => element('cde_type', $pval),
							'cde_is_image' => $cde_is_image,
							'cde_datetime' => cdate('Y-m-d H:i:s'),
							'cde_ip' => $this->input->ip_address(),
						);
						$this->Cmall_item_detail_model->update($pkey, $fileupdate);
					}
				}
			}

			if ($this->input->post('cde_title_update')) {
				foreach ($this->input->post('cde_title_update') as $pkey => $pval) {
					$cde_price = element($pkey, $this->input->post('cde_price_update')) ? element($pkey, $this->input->post('cde_price_update')) : 0;
					$cde_status = element($pkey, $this->input->post('cde_status_update')) ? 1 : 0;
					$update = array(
						'cde_title' => element($pkey, $this->input->post('cde_title_update')),
						'cde_price' => $cde_price,
						'cde_status' => $cde_status,
					);
					$this->Cmall_item_detail_model->update($pkey, $update);
				}
			}

			// 이벤트가 존재하면 실행합니다
			Events::trigger('after', $eventname);

			if ($this->input->post($primary_key)) {
				redirect(current_url(), 'refresh');
			} else {
				$param =& $this->querystring;
				$redirecturl = admin_url($this->pagedir . '?' . $param->output());
				redirect($redirecturl);
			}
		}
	}

	/**
	 * 목록 페이지에서 선택수정을 하는 경우 실행되는 메소드입니다
	 */
	public function listupdate()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_cmall_cmallitem_listupdate';
		$this->load->event($eventname);

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		/**
		 * 체크한 게시물의 업데이트를 실행합니다
		 */
		if ($this->input->post('chk') && is_array($this->input->post('chk'))) {

			$cit_name = $this->input->post('cit_name');
			$cit_price = $this->input->post('cit_price');
			$cit_order = $this->input->post('cit_order');
			$cit_status = $this->input->post('cit_status');

			$item_layout = $this->input->post('item_layout');
			$item_mobile_layout = $this->input->post('item_mobile_layout');
			$item_sidebar = $this->input->post('item_sidebar');
			$item_mobile_sidebar = $this->input->post('item_mobile_sidebar');
			$item_skin = $this->input->post('item_skin');
			$item_mobile_skin = $this->input->post('item_mobile_skin');

			foreach ($this->input->post('chk') as $val) {
				if ($val) {
					$cit_price_update = element($val, $cit_price) ? element($val, $cit_price) : 0;
					$cit_order_update = element($val, $cit_order) ? element($val, $cit_order) : 0;
					$cit_status_update = element($val, $cit_status) ? 1 : 0;
					$updatedata = array(
						'cit_name' => element($val, $cit_name),
						'cit_price' => $cit_price_update,
						'cit_order' => $cit_order_update,
						'cit_status' => $cit_status_update,
					);
					$metadata = array(
						'item_layout' => element($val, $item_layout),
						'item_mobile_layout' => element($val, $item_mobile_layout),
						'item_sidebar' => element($val, $item_sidebar),
						'item_mobile_sidebar' => element($val, $item_mobile_sidebar),
						'item_skin' => element($val, $item_skin),
						'item_mobile_skin' => element($val, $item_mobile_skin),
					);
					$this->{$this->modelname}->update($val, $updatedata);
					$this->Cmall_item_meta_model->save($val, $metadata);
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
		$eventname = 'event_admin_cmall_cmallitem_listdelete';
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
					$this->Cmall_item_meta_model->deletemeta($val);
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
}
