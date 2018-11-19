<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Editorfileupload class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 에디터를 통해 파일을 업로드하는 controller 입니다.
 */
class Editorfileupload extends CB_Controller
{

	/**
	 * 모델을 로딩합니다
	 */
	protected $models = array('Editor_image');

	/**
	 * 헬퍼를 로딩합니다
	 */
	protected $helpers = array('array', 'dhtml_editor');

	function __construct()
	{
		parent::__construct();
		$this->load->library('upload');
	}


	/**
	 * 스마트 에디터를 통해 이미지를 업로드하는 컨트롤러입니다.
	 */
	public function smarteditor($ed_nonce='')
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_editorfileupload_smarteditor';
		$this->load->event($eventname);

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		$this->_init();

		$mem_id = (int) $this->member->item('mem_id');

		$upload_path = config_item('uploads_dir') . '/editor/' . cdate('Y') . '/' . cdate('m') . '/';

		if( ! ft_nonce_is_valid( urldecode($ed_nonce) , 'smarteditor' ) ){
			exit(json_encode(array('files'=>array('0'=>array('error'=>'토큰오류')))));
		}

		if (isset($_FILES)
			&& isset($_FILES['files'])
			&& isset($_FILES['files']['name'])
			&& isset($_FILES['files']['name'][0])) {

			$uploadconfig = array(
				'upload_path' => $upload_path,
				'allowed_types' => 'jpg|jpeg|png|gif',
				'max_size' => 10 * 1024,
				'encrypt_name' => true,
			);

			$this->upload->initialize($uploadconfig);
			$upload = isset($_FILES['files']) ? $_FILES['files'] : null;
			if( is_array( $upload['tmp_name'] ) ){
				$_FILES['userfile']['name'] = $upload['name'][0];
				$_FILES['userfile']['type'] = $upload['type'][0];
				$_FILES['userfile']['tmp_name'] = $upload['tmp_name'][0];
				$_FILES['userfile']['error'] = $upload['error'][0];
				$_FILES['userfile']['size'] = $upload['size'][0];
			} else {
				if($upload['type'] == "application/octet-stream"){
					$imageMime = getimagesize($upload['tmp_name']); // get temporary file REAL info
					$upload['type'] = $imageMime['mime']; //set in our array the correct mime
				}
				$_FILES['userfile']['name'] = $upload['name'];
				$_FILES['userfile']['type'] = $upload['type'];
				$_FILES['userfile']['tmp_name'] = $upload['tmp_name'];
				$_FILES['userfile']['error'] = $upload['error'];
				$_FILES['userfile']['size'] = $upload['size'];
			}

			if ($this->upload->do_upload()) {

				// 이벤트가 존재하면 실행합니다
				Events::trigger('doupload', $eventname);

				$filedata = $this->upload->data();
				$fileupdate = array(
					'mem_id' => $mem_id,
					'eim_originname' => element('orig_name', $filedata),
					'eim_filename' => cdate('Y') . '/' . cdate('m') . '/' . element('file_name', $filedata),
					'eim_filesize' => intval(element('file_size', $filedata) * 1024),
					'eim_width' => element('image_width', $filedata) ? element('image_width', $filedata) : 0,
					'eim_height' => element('image_height', $filedata) ? element('image_height', $filedata) : 0,
					'eim_type' => str_replace('.', '', element('file_ext', $filedata)),
					'eim_datetime' => cdate('Y-m-d H:i:s'),
					'eim_ip' => $this->input->ip_address(),
				);
				$image_id = $this->Editor_image_model->insert($fileupdate);

				$image_url = site_url(config_item('uploads_dir') . '/editor/' . cdate('Y') . '/' . cdate('m') . '/' . element('file_name', $filedata));
				$info = new stdClass();
				$info->oriname = element('orig_name', $filedata);
				$info->name = element('file_name', $filedata);
				$info->size = intval(element('file_size', $filedata) * 1024);
				$info->type = 'image/' . str_replace('.', '', element('file_ext', $filedata));
				$info->url = $image_url;
				$info->width = element('image_width', $filedata)
					? element('image_width', $filedata) : 0;
				$info->height = element('image_height', $filedata)
					? element('image_height', $filedata) : 0;

				$return['files'][0] = $info;

				// 이벤트가 존재하면 실행합니다
				Events::trigger('doupload_after', $eventname);

				exit(json_encode($return));

			} else {
				exit($this->upload->display_errors());
			}
		} elseif ($this->input->get('file') && $mem_id) {

			// 이벤트가 존재하면 실행합니다
			Events::trigger('delete_before', $eventname);

			$where = array(
				'mem_id' => $mem_id,
				'eim_filename' => cdate('Y') . '/' . cdate('m') . '/' . $this->input->get('file'),
				'eim_ip' => $this->input->ip_address(),
			);
			$image = $this->Editor_image_model->get_one('', '', $where);
			if (element('eim_filename', $image)) {

				// 이벤트가 존재하면 실행합니다
				Events::trigger('delete_after', $eventname);

				unlink($upload_path . $this->input->get('file'));
				$this->Editor_image_model->delete_where($where);
			}
		}
	}


	/**
	 * CK 에디터를 통해 이미지를 업로드하는 컨트롤러입니다.
	 */
	public function ckeditor()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_editorfileupload_ckeditor';
		$this->load->event($eventname);

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		$this->_init();

		$mem_id = (int) $this->member->item('mem_id');

		$upload_path = config_item('uploads_dir') . '/editor/' . cdate('Y') . '/' . cdate('m') . '/';

		$uploadconfig = array(
			'upload_path' => $upload_path,
			'allowed_types' => 'jpg|jpeg|png|gif',
			'max_size' => 10 * 1024,
			'encrypt_name' => true,
		);

		if (isset($_FILES)
			&& isset($_FILES['upload'])
			&& isset($_FILES['upload']['name'])) {

			$this->upload->initialize($uploadconfig);
			$_FILES['userfile']['name'] = $_FILES['upload']['name'];
			$_FILES['userfile']['type'] = $_FILES['upload']['type'];
			$_FILES['userfile']['tmp_name'] = $_FILES['upload']['tmp_name'];
			$_FILES['userfile']['error'] = $_FILES['upload']['error'];
			$_FILES['userfile']['size'] = $_FILES['upload']['size'];

			if ($this->upload->do_upload()) {

				// 이벤트가 존재하면 실행합니다
				Events::trigger('doupload', $eventname);

				$filedata = $this->upload->data();
				$fileupdate = array(
					'mem_id' => $mem_id,
					'eim_originname' => element('orig_name', $filedata),
					'eim_filename' => cdate('Y') . '/' . cdate('m') . '/' . element('file_name', $filedata),
					'eim_filesize' => intval(element('file_size', $filedata) * 1024),
					'eim_width' => element('image_width', $filedata) ? element('image_width', $filedata) : 0,
					'eim_height' => element('image_height', $filedata) ? element('image_height', $filedata) : 0,
					'eim_type' => str_replace('.', '', element('file_ext', $filedata)),
					'eim_datetime' => cdate('Y-m-d H:i:s'),
					'eim_ip' => $this->input->ip_address(),
				);
				$this->Editor_image_model->insert($fileupdate);
				$image_url = site_url(config_item('uploads_dir') . '/editor/' . cdate('Y') . '/' . cdate('m') . '/' . element('file_name', $filedata));

				// 이벤트가 존재하면 실행합니다
				Events::trigger('doupload_after', $eventname);

				echo "<script>window.parent.CKEDITOR.tools.callFunction("
					. $this->input->get('CKEditorFuncNum', null, '') . ", '"
					. $image_url . "', '업로드완료');</script>";
			} else {
				echo $this->upload->display_errors();
			}
		}
	}


	public function _init()
	{
		$upload_path = config_item('uploads_dir') . '/editor/';
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
	}
}
