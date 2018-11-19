<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Cmallact class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 컨텐츠몰 아이템 페이지에서 각종 이벤트를 발생할 때 필요한 controller 입니다.
 */
class Cmallact extends CB_Controller
{

	/**
	 * 모델을 로딩합니다
	 */
	protected $models = array();

	/**
	 * 헬퍼를 로딩합니다
	 */
	protected $helpers = array('form', 'array', 'cmall');

	function __construct()
	{
		parent::__construct();

		/**
		 * 라이브러리를 로딩합니다
		 */
		$this->load->library(array('querystring', 'cbconfig', 'cmalllib'));

		if ( ! $this->cbconfig->item('use_cmall')) {
			alert('이 웹사이트는 ' . html_escape($this->cbconfig->item('cmall_name')) . ' 기능을 사용하지 않습니다');
			return;
		}
	}


	public function optionupdate()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_cmallact_optionupdate';
		$this->load->event($eventname);

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		$cit_id = (int) $this->input->post('cit_id');
		if (empty($cit_id) OR $cit_id < 1) {
			show_404();
		}

		if ($this->member->is_member() === false) {
			show_404();
		}

		$return = $this->cmalllib->addcart(
			$this->member->item('mem_id'),
			$cit_id,
			$this->input->post('chk_detail', null, ''),
			$this->input->post('detail_qty', null, '')
		);

		if ($return) {
			redirect('cmall/cart');
		}
	}


	/**
	 * 장바구니삭제 입니다
	 */
	public function cart_delete()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_cmallact_cart_delete';
		$this->load->event($eventname);

		/**
		 * 로그인이 필요한 페이지입니다
		 */
		required_user_login();

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		$this->load->model(array('Cmall_cart_model'));

		/**
		 * 체크한 게시물의 삭제를 실행합니다
		 */
		if ($this->input->post('chk') && is_array($this->input->post('chk'))) {
			foreach ($this->input->post('chk') as $val) {
				if ($val) {
					$where = array(
						'mem_id' => $this->member->item('mem_id'),
						'cit_id' => $val,
						'cct_cart' => 1,
					);
					$this->Cmall_cart_model->delete_where($where);
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
		redirect('cmall/cart?' . $param->output());
	}


	/**
	 * 상품 첨부파일 다운로드 하기
	 */
	public function download($cor_id = 0, $cde_id = 0)
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_cmallact_download';
		$this->load->event($eventname);

		$cor_id = preg_replace('/[^0-9]/', '', $cor_id);
		if (empty($cor_id) OR $cor_id < 1) {
			show_404();
		}
		$cde_id = preg_replace('/[^0-9]/', '', $cde_id);
		if (empty($cde_id) OR $cde_id < 1) {
			show_404();
		}

		/**
		 * 로그인이 필요한 페이지입니다
		 */
		required_user_login();

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		$this->load->model(array('Cmall_item_model', 'Cmall_item_detail_model', 'Cmall_order_model'));

		$itemdetail = $this->Cmall_item_detail_model->get_one($cde_id);
		$item = $this->Cmall_item_model->get_one(element('cit_id', $itemdetail));

		if ( ! element('cde_id', $itemdetail)) {
			show_404();
		}
		if ( ! $this->session->userdata('cmall_item_download_' . $cor_id)) {
			alert('주문상세내역 페이지에서만 접근 가능합니다');
		}

		$order = $this->Cmall_order_model
			->is_ordered_item_detail($this->member->item('mem_id'), $cor_id, $cde_id);
		if ( ! element('cor_id', $order) OR preg_replace('/[^0-9]/', '', element('cor_id', $order)) != $cor_id) {
			alert('회원님은 다운받으실 수 있는 권한이 없습니다');
		}
		if (element('cod_download_days', $order)) {
			$endtimestamp = strtotime(element('cor_approve_datetime', $order))
				+ 86400 * element('cod_download_days', $order);
			if (cdate('Y-m-d', $endtimestamp) < cdate('Y-m-d')) {
				alert('다운로드 가능한 기간이 지났습니다');
			}
		}

		if ( ! $this->session->userdata('cmall_download_item_' . element('cde_id', $itemdetail))) {
			$this->session->set_userdata(
				'cmall_download_item_' . element('cde_id', $itemdetail),
				'1'
			);
			$insertdata = array(
				'cde_id' => element('cde_id', $itemdetail),
				'cit_id' => element('cit_id', $itemdetail),
				'mem_id' => $this->member->item('mem_id'),
				'cdo_datetime' => cdate('Y-m-d H:i:s'),
				'cdo_ip' => $this->input->ip_address(),
				'cdo_useragent' => $this->agent->agent_string(),
			);
			$this->load->model(array('Cmall_download_log_model'));
			$this->Cmall_download_log_model->insert($insertdata);
		}

		// 이벤트가 존재하면 실행합니다
		Events::trigger('after', $eventname);

		$this->load->helper('download');

		// Read the file's contents
		$data = file_get_contents(config_item('uploads_dir') . '/cmallitemdetail/' . element('cde_filename', $itemdetail));
		$name = element('cde_originname', $itemdetail);

		force_download($name, $data);
	}


	/**
	 * 찜한목록삭제 입니다
	 */
	public function wishlist_delete($cwi_id = 0)
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_cmallact_wishlist_delete';
		$this->load->event($eventname);

		/**
		 * 로그인이 필요한 페이지입니다
		 */
		required_user_login();

		$mem_id = (int) $this->member->item('mem_id');

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		$cwi_id = (int) $cwi_id;
		if (empty($cwi_id) OR $cwi_id < 1) {
			show_404();
		}

		$this->load->model(array('Cmall_item_model', 'Cmall_wishlist_model'));
		$wishlist = $this->Cmall_wishlist_model->get_one($cwi_id);

		if ( ! element('cwi_id', $wishlist)) {
			show_404();
		}

		if ((int) element('mem_id', $wishlist) !== $mem_id) {
			show_404();
		}

		$this->Cmall_wishlist_model->delete($cwi_id);

		$where = array(
			'cit_id' => element('cit_id', $wishlist),
		);
		$count = $this->Cmall_wishlist_model->count_by($where);

		$updatedata = array(
			'cit_wish_count' => $count,
		);
		$this->Cmall_item_model->update(element('cit_id', $wishlist), $updatedata);

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
		redirect('cmall/wishlist?' . $param->output());
	}


	/**
	 * 링크 클릭 하기
	 */
	public function link($type = '', $cit_id = 0)
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_cmallact_link';
		$this->load->event($eventname);

		if ($type !== 'admin' && $type !== 'user') {
			show_404();
		}
		$cit_id = (int) $cit_id;
		if (empty($cit_id) OR $cit_id < 1) {
			show_404();
		}
		$mem_id = (int) $this->member->item('mem_id');

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		$this->load->model(array('Cmall_item_meta_model'));

		$meta = $this->Cmall_item_meta_model->get_all_meta($cit_id);

		$column = '';
		if ($type === 'user') {
			$column = 'demo_user_link';
		}
		if ($type === 'admin') {
			$column = 'demo_admin_link';
		}

		if ( ! element($column, $meta)) {
			show_404();
		}
		if ( ! $this->session->userdata('cmall_item_id_' . $cit_id)) {
			alert('해당 상품에서만 접근 가능합니다');
		}

		if ( ! $this->session->userdata('cmall_link_item_' . $type . '_' . $cit_id)) {

			$this->session->set_userdata(
				'cmall_link_item_' . $type . '_' . $cit_id,
				'1'
			);
			$insertdata = array(
				'cit_id' => $cit_id,
				'cdc_type' => $type,
				'mem_id' => $mem_id,
				'cdc_datetime' => cdate('Y-m-d H:i:s'),
				'cdc_ip' => $this->input->ip_address(),
				'cdc_useragent' => $this->agent->agent_string(),
			);
			$this->load->model(array('Cmall_demo_click_log_model'));
			$this->Cmall_demo_click_log_model->insert($insertdata);
		}

		// 이벤트가 존재하면 실행합니다
		Events::trigger('after', $eventname);

		redirect(prep_url(strip_tags(element($column, $meta))));
	}


	/**
	 * 리뷰 삭제하기
	 */
	public function delete_review()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_cmallact_delete_review';
		$this->load->event($eventname);

		$this->output->set_content_type('application/json');

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		$cre_id = (int) $this->input->post('cre_id');

		if (empty($cre_id) OR $cre_id < 1) {
			$result = array('error' => '잘못된 접근입니다');
			exit(json_encode($result));
		}

		$mem_id = (int) $this->member->item('mem_id');

		$this->load->model( array('Cmall_review_model'));

		$review = $this->Cmall_review_model->get_one($cre_id);
		if ( ! element('cre_id', $review)) {
			$result = array('error' => '잘못된 접근입니다');
			exit(json_encode($result));
		}

		$is_admin = $this->member->is_admin();
		if ($is_admin === false && (int) element('mem_id', $review) !== $mem_id) {
			$result = array('error' => '본인의 글 외에는 접근하실 수 없습니다');
			exit(json_encode($result));
		}

		$this->Cmall_review_model->delete($cre_id);
		$cntresult = $this->cmalllib->update_review_count(element('cit_id', $review));
		$jresult = json_decode($cntresult, true);
		$cnt = element('cit_review_count', $jresult);

		// 이벤트가 존재하면 실행합니다
		Events::trigger('after', $eventname);

		$result = array(
			'success' => '상품후기가 삭제되었습니다',
			'review_count' => $cnt,
		);
		exit(json_encode($result));
	}


	/**
	 * Q&A 삭제하기
	 */
	public function delete_qna()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_cmallact_delete_qna';
		$this->load->event($eventname);

		$this->output->set_content_type('application/json');

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		$cqa_id = (int) $this->input->post('cqa_id');

		if (empty($cqa_id) OR $cqa_id < 1) {
			$result = array('error' => '잘못된 접근입니다');
			exit(json_encode($result));
		}

		$mem_id = (int) $this->member->item('mem_id');

		$this->load->model( array('Cmall_qna_model'));

		$qna = $this->Cmall_qna_model->get_one($cqa_id);
		if ( ! element('cqa_id', $qna)) {
			$result = array('error' => '잘못된 접근입니다');
			exit(json_encode($result));
		}

		$is_admin = $this->member->is_admin();
		if ($is_admin === false && (int) element('mem_id', $qna) !== $mem_id) {
			$result = array('error' => '본인의 글 외에는 접근하실 수 없습니다');
			exit(json_encode($result));
		}

		$this->Cmall_qna_model->delete($cqa_id);
		$cntresult = $this->cmalllib->update_qna_count(element('cit_id', $qna));
		$jresult = json_decode($cntresult, true);
		$cnt = element('cit_qna_count', $jresult);

		// 이벤트가 존재하면 실행합니다
		Events::trigger('after', $eventname);

		$result = array(
			'success' => '상품문의가 삭제되었습니다',
			'qna_count' => $cnt,
		);
		exit(json_encode($result));
	}
}
