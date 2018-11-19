<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Cmalllib class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * cmall table 을 관리하는 class 입니다.
 */
class Cmalllib extends CI_Controller
{

	private $CI;

	public $paymethodtype = array(
			'point' => '포인트결제',
			'bank' => '무통장입금',
			'card' => '신용카드',
			'phone' => '핸드폰결제',
			'realtime' => '실시간계좌이체',
			'vbank' => '가상계좌',
			'service' => '서비스',
		);

	function __construct()
	{
		$this->CI = & get_instance();
		$this->CI->load->library(array('email', 'notelib'));
	}


	/**
	 * cmall 기능을 사용하는지 체크합니다.
	 */
	public function use_cmall()
	{
		$use = $this->CI->cbconfig->item('use_cmall');
		return $use;
	}


	public function get_all_category()
	{
		$this->CI->load->model('Cmall_category_model');
		$result = $this->CI->Cmall_category_model->get_all_category();
		return $result;
	}

	public function get_paymethodtype($method){

		$paymethodtype = $this->paymethodtype;

		if( isset( $paymethodtype[$method] ) ){
			return $paymethodtype[$method];
		}

		return $method;
	}

	public function get_nav_category($category_id = '')
	{
		if (empty($category_id)) {
			return;
		}

		$this->CI->load->model('Cmall_category_model');

		$my_category = $category_id;

		$result = array();
		while ($my_category) {
			$result[] = $data = $this->CI->Cmall_category_model->get_category_info($my_category);
			$my_category = element('cca_parent', $data);
		}
		$result = array_reverse($result);

		return $result;
	}


	public function addcart($mem_id = 0, $cit_id = 0, $detail_array = '', $qty_array = '')
	{
		$mem_id = (int) $mem_id;
		if (empty($mem_id) OR $mem_id < 1) {
			return;
		}
		$cit_id = (int) $cit_id;
		if (empty($cit_id) OR $cit_id < 1) {
			return;
		}
		if (empty($detail_array)) {
			return;
		}

		$this->CI->load->model(array('Cmall_cart_model', 'Cmall_item_detail_model'));

		$deletewhere = array(
			'mem_id' => $mem_id,
			'cit_id' => $cit_id,
			'cct_cart' => 1,
		);
		$this->CI->Cmall_cart_model->delete_where($deletewhere);

		if ($detail_array && is_array($detail_array)) {
			foreach ($detail_array as $cde_id) {
				$detail = $this->CI->Cmall_item_detail_model->get_one($cde_id, 'cit_id');
				if ( ! element('cit_id', $detail) OR (int) element('cit_id', $detail) !== $cit_id) {
					return;
				}
				if ( ! element($cde_id, $qty_array)) {
					return;
				}
			}
			foreach ($detail_array as $cde_id) {
				$insertdata = array(
					'mem_id' => $mem_id,
					'cit_id' => $cit_id,
					'cde_id' => $cde_id,
					'cct_count' => element($cde_id, $qty_array),
					'cct_cart' => 1,
					'cct_datetime' => cdate('Y-m-d H:i:s'),
					'cct_ip' => $this->CI->input->ip_address(),
				);
				$cct_id = $this->CI->Cmall_cart_model->insert($insertdata);
			}
		}
		return $cit_id;
	}


	public function addorder($mem_id = 0, $cit_id = 0, $detail_array = '', $qty_array = '')
	{
		$mem_id = (int) $mem_id;
		if (empty($mem_id) OR $mem_id < 1) {
			return;
		}
		$cit_id = (int) $cit_id;
		if (empty($cit_id) OR $cit_id < 1) {
			return;
		}
		if (empty($detail_array)) {
			return;
		}

		$this->CI->load->model(array('Cmall_cart_model', 'Cmall_item_detail_model'));

		$deletewhere = array(
			'mem_id' => $mem_id,
			'cct_order' => 1,
		);
		$this->CI->Cmall_cart_model->delete_where($deletewhere);

		if ($detail_array && is_array($detail_array)) {
			foreach ($detail_array as $cde_id) {
				$detail = $this->CI->Cmall_item_detail_model->get_one($cde_id, 'cit_id');
				if ( ! element('cit_id', $detail) OR (int) element('cit_id', $detail) !== $cit_id) {
					return;
				}
				if ( ! element($cde_id, $qty_array)) {
					return;
				}
			}
			foreach ($detail_array as $cde_id) {
				$insertdata = array(
					'mem_id' => $mem_id,
					'cit_id' => $cit_id,
					'cde_id' => $cde_id,
					'cct_count' => element($cde_id, $qty_array),
					'cct_order' => 1,
					'cct_datetime' => cdate('Y-m-d H:i:s'),
					'cct_ip' => $this->CI->input->ip_address(),
				);
				$cct_id = $this->CI->Cmall_cart_model->insert($insertdata);
			}
		}
		return $cit_id;
	}


	public function cart_to_order($mem_id = 0, $cit_id_array = '')
	{
		$mem_id = (int) $mem_id;
		if (empty($mem_id) OR $mem_id < 1) {
			return;
		}
		if (empty($cit_id_array)) {
			return;
		}

		$this->CI->load->model(array('Cmall_cart_model'));

		$deletewhere = array(
			'mem_id' => $mem_id,
			'cct_order' => 1,
		);
		$this->CI->Cmall_cart_model->delete_where($deletewhere);

		if ($cit_id_array && is_array($cit_id_array)) {
			foreach ($cit_id_array as $cit_id) {
				$where = array(
					'mem_id' => $mem_id,
					'cit_id' => $cit_id,
				);
				$result = $this->CI->Cmall_cart_model->get('', '', $where);
				if ($result) {
					foreach ($result as $value) {
						$insertdata = array(
							'mem_id' => $mem_id,
							'cit_id' => $cit_id,
							'cde_id' => element('cde_id', $value),
							'cct_count' => element('cct_count', $value),
							'cct_order' => 1,
							'cct_datetime' => cdate('Y-m-d H:i:s'),
							'cct_ip' => $this->CI->input->ip_address(),
						);
						$cct_id = $this->CI->Cmall_cart_model->insert($insertdata);
					}
				}
			}
		}

		return true;
	}


	public function get_my_cart($limit = 5)
	{
		$mem_id = (int) $this->CI->member->item('mem_id');
		if (empty($mem_id)) {
			return;
		}
		$this->CI->load->model(array('Cmall_cart_model'));
		$where = array(
			'cmall_cart.mem_id' => $mem_id,
		);
		$result = $this->CI->Cmall_cart_model->get_cart_list($where, 'cct_id', 'desc', $limit);

		return $result;
	}


	public function get_my_wishlist($limit = 5)
	{
		$mem_id = (int) $this->CI->member->item('mem_id');
		if (empty($mem_id)) {
			return;
		}
		$this->CI->load->model(array('Cmall_wishlist_model'));
		$where = array(
			'cmall_wishlist.mem_id' => $mem_id,
			'cit_status' => 1,
		);
		$result = $this->CI->Cmall_wishlist_model
			->get_list($limit, $offset = '', $where, $like = '', $findex = 'cwi_id', $forder = 'desc');

		return element('list', $result);
	}


	public function addwish($mem_id = 0, $cit_id = 0)
	{
		$mem_id = (int) $mem_id;
		if (empty($mem_id) OR $mem_id < 1) {
			return;
		}
		$cit_id = (int) $cit_id;
		if (empty($cit_id) OR $cit_id < 1) {
			return;
		}

		$this->CI->load->model(array('Cmall_item_model', 'Cmall_wishlist_model'));

		$insertdata = array(
			'mem_id' => $mem_id,
			'cit_id' => $cit_id,
			'cwi_datetime' => cdate('Y-m-d H:i:s'),
			'cwi_ip' => $this->CI->input->ip_address(),
		);
		$cwi_id = $this->CI->Cmall_wishlist_model->replace($insertdata);

		$where = array(
			'cit_id' => $cit_id,
		);
		$count = $this->CI->Cmall_wishlist_model->count_by($where);

		$updatedata = array(
			'cit_wish_count' => $count,
		);
		$this->CI->Cmall_item_model->update($cit_id, $updatedata);

		return $cwi_id;
	}


	public function is_ordered_item($mem_id = 0, $cit_id = 0)
	{
		$mem_id = (int) $mem_id;
		if (empty($mem_id) OR $mem_id < 1) {
			return;
		}
		$cit_id = (int) $cit_id;
		if (empty($cit_id) OR $cit_id < 1) {
			return;
		}

		$this->CI->load->model(array('Cmall_order_model'));
		$result = $this->CI->Cmall_order_model->is_ordered_item($mem_id, $cit_id);

		return $result;
	}


	public function update_review_count($cit_id = 0)
	{
		$cit_id = (int) $cit_id;
		if (empty($cit_id) OR $cit_id < 1) {
			return;
		}
		$this->CI->load->model(array('Cmall_item_model', 'Cmall_review_model'));
		$result = $this->CI->Cmall_review_model->get_review_count($cit_id);

		$avg = 0;

		if (element('cnt', $result)) {
			$avg = round(10 * element('cre_score', $result) / element('cnt', $result)) / 10;
		}

		$updatedata = array(
			'cit_review_count' => element('cnt', $result),
			'cit_review_average' => $avg,
		);
		$this->CI->Cmall_item_model->update($cit_id, $updatedata);

		return json_encode($updatedata);
	}


	public function update_qna_count($cit_id = 0)
	{
		$cit_id = (int) $cit_id;
		if (empty($cit_id) OR $cit_id < 1) {
			return;
		}
		$this->CI->load->model(array('Cmall_item_model', 'Cmall_qna_model'));
		$result = $this->CI->Cmall_qna_model->get_qna_count($cit_id);

		$updatedata = array(
			'cit_qna_count' => element('cnt', $result),
		);
		$this->CI->Cmall_item_model->update($cit_id, $updatedata);

		return json_encode($updatedata);
	}


	public function review_alarm($cre_id = 0)
	{
		$cre_id = (int) $cre_id;
		if (empty($cre_id) OR $cre_id < 1) {
			return;
		}

		$this->CI->load->model(array('Cmall_review_model', 'Cmall_item_model', 'Member_model'));

		$review = $this->CI->Cmall_review_model->get_one($cre_id);
		$item = $this->CI->Cmall_item_model->get_one(element('cit_id', $review), 'cit_name, cit_key');
		$member = $this->CI->Member_model->get_one(element('mem_id', $review));

		if ( ! element('cre_id', $review)) {
			return;
		}

		$emailsendlistadmin = array();
		$notesendlistadmin = array();
		$smssendlistadmin = array();
		$emailsendlistuser = array();
		$notesendlistuser = array();
		$smssendlistuser = array();

		$superadminlist = '';
		if ($this->CI->cbconfig->item('cmall_email_admin_write_product_review')
			OR $this->CI->cbconfig->item('cmall_note_admin_write_product_review')
			OR $this->CI->cbconfig->item('cmall_sms_admin_write_product_review')) {

			$mselect = 'mem_id, mem_email, mem_nickname, mem_phone';
			$superadminlist = $this->CI->Member_model->get_superadmin_list($mselect);

		}
		if ($this->CI->cbconfig->item('cmall_email_admin_write_product_review') && $superadminlist) {
			foreach ($superadminlist as $key => $value) {
				$emailsendlistadmin[$value['mem_id']] = $value;
			}
		}
		if (($this->CI->cbconfig->item('cmall_email_user_write_product_review') && element('mem_receive_email', $member))
			OR $this->CI->cbconfig->item('cmall_email_alluser_write_product_review')) {
			$emailsendlistuser['mem_email'] = element('mem_email', $member);
		}
		if ($this->CI->cbconfig->item('cmall_note_admin_write_product_review') && $superadminlist) {
			foreach ($superadminlist as $key => $value) {
				$notesendlistadmin[$value['mem_id']] = $value;
			}
		}
		if ($this->CI->cbconfig->item('cmall_note_user_write_product_review') && element('mem_use_note', $member)) {
			$notesendlistuser['mem_id'] = element('mem_id', $member);
		}
		if ($this->CI->cbconfig->item('cmall_sms_admin_write_product_review') && $superadminlist) {
			foreach ($superadminlist as $key => $value) {
				$smssendlistadmin[$value['mem_id']] = $value;
			}
		}
		if (($this->CI->cbconfig->item('cmall_sms_user_write_product_review') && element('mem_receive_sms', $member))
			OR $this->CI->cbconfig->item('cmall_sms_alluser_write_product_review')) {
			if (element('mem_phone', $member)) {
				$smssendlistuser = $member;
			}
		}

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
			'{상품명}',
			'{상품주소}',
			'{후기제목}',
			'{후기내용}',
		);
		$receive_email = element('mem_receive_email', $member) ? '동의' : '거부';
		$receive_note = element('mem_use_note', $member) ? '동의' : '거부';
		$receive_sms = element('mem_receive_sms', $member) ? '동의' : '거부';
		$thumb_width = $this->CI->cbconfig->item('cmall_product_review_thumb_width');
		$autolink = $this->CI->cbconfig->item('use_cmall_product_review_auto_url');
		$popup = $this->CI->cbconfig->item('cmall_product_review_content_target_blank');
		$review_content = display_html_content(
			element('cre_content', $review),
			element('cre_content_html_type', $review),
			$thumb_width,
			$autolink,
			$popup
		);

		$replaceconfig = array(
			$this->CI->cbconfig->item('site_title'),
			$this->CI->cbconfig->item('company_name'),
			site_url(),
			element('mem_userid', $member),
			element('mem_nickname', $member),
			element('mem_username', $member),
			element('mem_email', $member),
			$receive_email,
			$receive_note,
			$receive_sms,
			$this->CI->input->ip_address(),
			element('cit_name', $item),
			cmall_item_url(element('cit_key', $item)),
			element('cre_title', $review),
			$review_content,
		);
		$replaceconfig_escape = array(
			html_escape($this->CI->cbconfig->item('site_title')),
			html_escape($this->CI->cbconfig->item('company_name')),
			site_url(),
			html_escape(element('mem_userid', $member)),
			html_escape(element('mem_nickname', $member)),
			html_escape(element('mem_username', $member)),
			html_escape(element('mem_email', $member)),
			$receive_email,
			$receive_note,
			$receive_sms,
			$this->CI->input->ip_address(),
			html_escape(element('cit_name', $item)),
			cmall_item_url(element('cit_key', $item)),
			html_escape(element('cre_title', $review)),
			$review_content,
		);

		if ($emailsendlistadmin) {
			$title = str_replace(
				$searchconfig,
				$replaceconfig,
				$this->CI->cbconfig->item('cmall_email_admin_write_product_review_title')
			);
			$content = str_replace(
				$searchconfig,
				$replaceconfig_escape,
				$this->CI->cbconfig->item('cmall_email_admin_write_product_review_content')
			);
			foreach ($emailsendlistadmin as $akey => $aval) {
				$this->CI->email->clear(true);
				$this->CI->email->from($this->CI->cbconfig->item('webmaster_email'), $this->CI->cbconfig->item('webmaster_name'));
				$this->CI->email->to(element('mem_email', $aval));
				$this->CI->email->subject($title);
				$this->CI->email->message($content);
				$this->CI->email->send();
			}
		}
		if ($emailsendlistuser) {
			$title = str_replace(
				$searchconfig,
				$replaceconfig,
				$this->CI->cbconfig->item('cmall_email_user_write_product_review_title')
			);
			$content = str_replace(
				$searchconfig,
				$replaceconfig_escape,
				$this->CI->cbconfig->item('cmall_email_user_write_product_review_content')
			);
			$this->CI->email->clear(true);
			$this->CI->email->from($this->CI->cbconfig->item('webmaster_email'), $this->CI->cbconfig->item('webmaster_name'));
			$this->CI->email->to(element('mem_email', $emailsendlistuser));
			$this->CI->email->subject($title);
			$this->CI->email->message($content);
			$this->CI->email->send();
		}
		if ($notesendlistadmin) {
			$title = str_replace(
				$searchconfig,
				$replaceconfig,
				$this->CI->cbconfig->item('cmall_note_admin_write_product_review_title')
			);
			$content = str_replace(
				$searchconfig,
				$replaceconfig_escape,
				$this->CI->cbconfig->item('cmall_note_admin_write_product_review_content')
			);
			foreach ($notesendlistadmin as $akey => $aval) {
				$note_result = $this->CI->notelib->send_note(
					$sender = 0,
					$receiver = element('mem_id', $aval),
					$title,
					$content,
					1
				);
			}
		}
		if ($notesendlistuser) {
			$title = str_replace(
				$searchconfig,
				$replaceconfig,
				$this->CI->cbconfig->item('cmall_note_user_write_product_review_title')
			);
			$content = str_replace(
				$searchconfig,
				$replaceconfig_escape,
				$this->CI->cbconfig->item('cmall_note_user_write_product_review_content')
			);
			$note_result = $this->CI->notelib->send_note(
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
					$this->CI->cbconfig->item('cmall_sms_admin_write_product_review_content')
				);
				$sender = array(
					'phone' => $this->CI->cbconfig->item('sms_admin_phone'),
				);
				$receiver = array();
				foreach ($smssendlistadmin as $akey => $aval) {
					$receiver[] = array(
						'mem_id' => element('mem_id', $aval),
						'name' => element('mem_nickname', $aval),
						'phone' => element('mem_phone', $aval),
					);
				}
				$smsresult = $this->CI->smslib->send($receiver, $sender, $content, $date = '', '상품리뷰작성알림');
			}
		}
		if ($smssendlistuser) {
			if (file_exists(APPPATH . 'libraries/Smslib.php')) {
				$this->load->library(array('smslib'));
				$content = str_replace(
					$searchconfig,
					$replaceconfig,
					$this->CI->cbconfig->item('cmall_sms_user_write_product_review_content')
				);
				$sender = array(
					'phone' => $this->CI->cbconfig->item('sms_admin_phone'),
				);
				$receiver = array();
				$receiver[] = $smssendlistuser;
				$smsresult = $this->CI->smslib->send($receiver, $sender, $content, $date = '', '상품리뷰작성알림');
			}
		}
	}


	public function qna_alarm($cqa_id = 0)
	{
		$cqa_id = (int) $cqa_id;
		if (empty($cqa_id) OR $cqa_id < 1) {
			return;
		}

		$this->CI->load->model(array('Cmall_qna_model', 'Cmall_item_model', 'Member_model'));

		$qna = $this->CI->Cmall_qna_model->get_one($cqa_id);
		$item = $this->CI->Cmall_item_model->get_one(element('cit_id', $qna), 'cit_name, cit_key');
		$member = $this->CI->Member_model->get_one(element('mem_id', $qna));

		if ( ! element('cqa_id', $qna)) {
			return;
		}

		$emailsendlistadmin = array();
		$notesendlistadmin = array();
		$smssendlistadmin = array();
		$emailsendlistuser = array();
		$notesendlistuser = array();
		$smssendlistuser = array();

		$superadminlist = '';
		if ($this->CI->cbconfig->item('cmall_email_admin_write_product_qna')
			OR $this->CI->cbconfig->item('cmall_note_admin_write_product_qna')
			OR $this->CI->cbconfig->item('cmall_sms_admin_write_product_qna')) {

			$mselect = 'mem_id, mem_email, mem_nickname, mem_phone';
			$superadminlist = $this->CI->Member_model->get_superadmin_list($mselect);

		}
		if ($this->CI->cbconfig->item('cmall_email_admin_write_product_qna') && $superadminlist) {
			foreach ($superadminlist as $key => $value) {
				$emailsendlistadmin[$value['mem_id']] = $value;
			}
		}
		if ($this->CI->cbconfig->item('cmall_email_user_write_product_qna') && $member && element('cqa_receive_email', $qna)) {
			$emailsendlistuser['mem_email'] = element('mem_email', $member);
		}
		if ($this->CI->cbconfig->item('cmall_note_admin_write_product_qna') && $superadminlist) {
			foreach ($superadminlist as $key => $value) {
				$notesendlistadmin[$value['mem_id']] = $value;
			}
		}
		if ($this->CI->cbconfig->item('cmall_note_user_write_product_qna') && element('mem_use_note', $member)) {
			$notesendlistuser['mem_id'] = element('mem_id', $member);
		}
		if ($this->CI->cbconfig->item('cmall_sms_admin_write_product_qna') && $superadminlist) {
			foreach ($superadminlist as $key => $value) {
				$smssendlistadmin[$value['mem_id']] = $value;
			}
		}
		if ($this->CI->cbconfig->item('cmall_sms_user_write_product_qna') && $member && element('cqa_receive_sms', $qna)) {
			if (element('mem_phone', $member)) {
				$smssendlistuser = $member;
			}
		}

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
			'{상품명}',
			'{상품주소}',
			'{문의제목}',
			'{문의내용}',
		);
		$receive_email = element('mem_receive_email', $member) ? '동의' : '거부';
		$receive_note = element('mem_use_note', $member) ? '동의' : '거부';
		$receive_sms = element('mem_receive_sms', $member) ? '동의' : '거부';
		$thumb_width = $this->CI->cbconfig->item('cmall_product_qna_thumb_width');
		$autolink = $this->CI->cbconfig->item('use_cmall_product_qna_auto_url');
		$popup = $this->CI->cbconfig->item('cmall_product_qna_content_target_blank');
		$qna_content = display_html_content(
			element('cqa_content', $qna),
			element('cqa_content_html_type', $qna),
			$thumb_width,
			$autolink,
			$popup
		);

		$replaceconfig = array(
			$this->CI->cbconfig->item('site_title'),
			$this->CI->cbconfig->item('company_name'),
			site_url(),
			element('mem_userid', $member),
			element('mem_nickname', $member),
			element('mem_username', $member),
			element('mem_email', $member),
			$receive_email,
			$receive_note,
			$receive_sms,
			$this->CI->input->ip_address(),
			element('cit_name', $item),
			cmall_item_url(element('cit_key', $item)),
			element('cqa_title', $qna),
			$qna_content,
		);
		$replaceconfig_escape = array(
			html_escape($this->CI->cbconfig->item('site_title')),
			html_escape($this->CI->cbconfig->item('company_name')),
			site_url(),
			html_escape(element('mem_userid', $member)),
			html_escape(element('mem_nickname', $member)),
			html_escape(element('mem_username', $member)),
			html_escape(element('mem_email', $member)),
			$receive_email,
			$receive_note,
			$receive_sms,
			$this->CI->input->ip_address(),
			html_escape(element('cit_name', $item)),
			cmall_item_url(element('cit_key', $item)),
			html_escape(element('cqa_title', $qna)),
			$qna_content,
		);

		if ($emailsendlistadmin) {
			$title = str_replace(
				$searchconfig,
				$replaceconfig,
				$this->CI->cbconfig->item('cmall_email_admin_write_product_qna_title')
			);
			$content = str_replace(
				$searchconfig,
				$replaceconfig_escape,
				$this->CI->cbconfig->item('cmall_email_admin_write_product_qna_content')
			);
			foreach ($emailsendlistadmin as $akey => $aval) {
				$this->CI->email->clear(true);
				$this->CI->email->from($this->CI->cbconfig->item('webmaster_email'), $this->CI->cbconfig->item('webmaster_name'));
				$this->CI->email->to(element('mem_email', $aval));
				$this->CI->email->subject($title);
				$this->CI->email->message($content);
				$this->CI->email->send();
			}
		}
		if ($emailsendlistuser) {
			$title = str_replace(
				$searchconfig,
				$replaceconfig,
				$this->CI->cbconfig->item('cmall_email_user_write_product_qna_title')
			);
			$content = str_replace(
				$searchconfig,
				$replaceconfig_escape,
				$this->CI->cbconfig->item('cmall_email_user_write_product_qna_content')
			);
			$this->CI->email->clear(true);
			$this->CI->email->from($this->CI->cbconfig->item('webmaster_email'), $this->CI->cbconfig->item('webmaster_name'));
			$this->CI->email->to(element('mem_email', $emailsendlistuser));
			$this->CI->email->subject($title);
			$this->CI->email->message($content);
			$this->CI->email->send();
		}
		if ($notesendlistadmin) {
			$title = str_replace(
				$searchconfig,
				$replaceconfig,
				$this->CI->cbconfig->item('cmall_note_admin_write_product_qna_title')
			);
			$content = str_replace(
				$searchconfig,
				$replaceconfig_escape,
				$this->CI->cbconfig->item('cmall_note_admin_write_product_qna_content')
			);
			foreach ($notesendlistadmin as $akey => $aval) {
				$note_result = $this->CI->notelib->send_note(
					$sender = 0,
					$receiver = element('mem_id', $aval),
					$title,
					$content,
					1
				);
			}
		}
		if ($notesendlistuser) {
			$title = str_replace(
				$searchconfig,
				$replaceconfig,
				$this->CI->cbconfig->item('cmall_note_user_write_product_qna_title')
			);
			$content = str_replace(
				$searchconfig,
				$replaceconfig_escape,
				$this->CI->cbconfig->item('cmall_note_user_write_product_qna_content')
			);
			$note_result = $this->CI->notelib->send_note(
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
					$this->CI->cbconfig->item('cmall_sms_admin_write_product_qna_content')
				);
				$sender = array(
					'phone' => $this->CI->cbconfig->item('sms_admin_phone'),
				);
				$receiver = array();
				foreach ($smssendlistadmin as $akey => $aval) {
					$receiver[] = array(
						'mem_id' => element('mem_id', $aval),
						'name' => element('mem_nickname', $aval),
						'phone' => element('mem_phone', $aval),
					);
				}
				$smsresult = $this->CI->smslib->send($receiver, $sender, $content, $date = '', '상품문의작성알림');
			}
		}
		if ($smssendlistuser) {
			if (file_exists(APPPATH . 'libraries/Smslib.php')) {
				$this->load->library(array('smslib'));
				$content = str_replace(
					$searchconfig,
					$replaceconfig,
					$this->CI->cbconfig->item('cmall_sms_user_write_product_qna_content')
				);
				$sender = array(
					'phone' => $this->CI->cbconfig->item('sms_admin_phone'),
				);
				$receiver = array();
				$receiver[] = $smssendlistuser;
				$smsresult = $this->CI->smslib->send($receiver, $sender, $content, $date = '', '상품문의작성알림');
			}
		}
	}


	public function qna_reply_alarm($cqa_id = 0)
	{
		$cqa_id = (int) $cqa_id;
		if (empty($cqa_id) OR $cqa_id < 1) {
			return;
		}

		$this->CI->load->model(array('Cmall_qna_model', 'Cmall_item_model', 'Member_model'));

		$qna = $this->CI->Cmall_qna_model->get_one($cqa_id);
		$item = $this->CI->Cmall_item_model->get_one(element('cit_id', $qna), 'cit_name, cit_key');
		$member = $this->CI->Member_model->get_one(element('mem_id', $qna));

		if ( ! element('cqa_id', $qna)) {
			return;
		}

		$emailsendlistadmin = array();
		$notesendlistadmin = array();
		$smssendlistadmin = array();
		$emailsendlistuser = array();
		$notesendlistuser = array();
		$smssendlistuser = array();

		$superadminlist = '';
		if ($this->CI->cbconfig->item('cmall_email_admin_write_product_qna_reply')
			OR $this->CI->cbconfig->item('cmall_note_admin_write_product_qna_reply')
			OR $this->CI->cbconfig->item('cmall_sms_admin_write_product_qna_reply')) {

			$mselect = 'mem_id, mem_email, mem_nickname, mem_phone';
			$superadminlist = $this->CI->Member_model->get_superadmin_list($mselect);

		}
		if ($this->CI->cbconfig->item('cmall_email_admin_write_product_qna_reply') && $superadminlist) {
			foreach ($superadminlist as $key => $value) {
				$emailsendlistadmin[$value['mem_id']] = $value;
			}
		}
		if ($this->CI->cbconfig->item('cmall_email_user_write_product_qna_reply') && $member && element('cqa_receive_email', $qna)) {
			$emailsendlistuser['mem_email'] = element('mem_email', $member);
		}
		if ($this->CI->cbconfig->item('cmall_note_admin_write_product_qna_reply') && $superadminlist) {
			foreach ($superadminlist as $key => $value) {
				$notesendlistadmin[$value['mem_id']] = $value;
			}
		}
		if ($this->CI->cbconfig->item('cmall_note_user_write_product_qna_reply') && element('mem_use_note', $member)) {
			$notesendlistuser['mem_id'] = element('mem_id', $member);
		}
		if ($this->CI->cbconfig->item('cmall_sms_admin_write_product_qna_reply') && $superadminlist) {
			foreach ($superadminlist as $key => $value) {
				$smssendlistadmin[$value['mem_id']] = $value;
			}
		}
		if ($this->CI->cbconfig->item('cmall_sms_user_write_product_qna_reply') && $member && element('cqa_receive_sms', $qna)) {
			if (element('mem_phone', $member)) {
				$smssendlistuser = $member;
			}
		}

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
			'{상품명}',
			'{상품주소}',
			'{문의제목}',
			'{문의내용}',
			'{답변내용}',
		);
		$receive_email = element('mem_receive_email', $member) ? '동의' : '거부';
		$receive_note = element('mem_use_note', $member) ? '동의' : '거부';
		$receive_sms = element('mem_receive_sms', $member) ? '동의' : '거부';
		$thumb_width = $this->CI->cbconfig->item('cmall_product_qna_thumb_width');
		$autolink = $this->CI->cbconfig->item('use_cmall_product_qna_auto_url');
		$popup = $this->CI->cbconfig->item('cmall_product_qna_content_target_blank');
		$qna_content = display_html_content(
			element('cqa_content', $qna),
			element('cqa_content_html_type', $qna),
			$thumb_width,
			$autolink,
			$popup
		);
		$reply_content = display_html_content(
			element('cqa_reply_content', $qna),
			element('cqa_reply_html_type', $qna),
			$thumb_width,
			$autolink,
			$popup
		);

		$replaceconfig = array(
			$this->CI->cbconfig->item('site_title'),
			$this->CI->cbconfig->item('company_name'),
			site_url(),
			element('mem_userid', $member),
			element('mem_nickname', $member),
			element('mem_username', $member),
			element('mem_email', $member),
			$receive_email,
			$receive_note,
			$receive_sms,
			$this->CI->input->ip_address(),
			element('cit_name', $item),
			cmall_item_url(element('cit_key', $item)),
			element('cqa_title', $qna),
			$qna_content,
			$reply_content,
		);
		$replaceconfig_escape = array(
			html_escape($this->CI->cbconfig->item('site_title')),
			html_escape($this->CI->cbconfig->item('company_name')),
			site_url(),
			html_escape(element('mem_userid', $member)),
			html_escape(element('mem_nickname', $member)),
			html_escape(element('mem_username', $member)),
			html_escape(element('mem_email', $member)),
			$receive_email,
			$receive_note,
			$receive_sms,
			$this->CI->input->ip_address(),
			html_escape(element('cit_name', $item)),
			cmall_item_url(element('cit_key', $item)),
			html_escape(element('cqa_title', $qna)),
			$qna_content,
			$reply_content,
		);

		if ($emailsendlistadmin) {
			$title = str_replace(
				$searchconfig,
				$replaceconfig,
				$this->CI->cbconfig->item('cmall_email_admin_write_product_qna_reply_title')
			);
			$content = str_replace(
				$searchconfig,
				$replaceconfig_escape,
				$this->CI->cbconfig->item('cmall_email_admin_write_product_qna_reply_content')
			);
			foreach ($emailsendlistadmin as $akey => $aval) {
				$this->CI->email->clear(true);
				$this->CI->email->from($this->CI->cbconfig->item('webmaster_email'), $this->CI->cbconfig->item('webmaster_name'));
				$this->CI->email->to(element('mem_email', $aval));
				$this->CI->email->subject($title);
				$this->CI->email->message($content);
				$this->CI->email->send();
			}
		}
		if ($emailsendlistuser) {
			$title = str_replace(
				$searchconfig,
				$replaceconfig,
				$this->CI->cbconfig->item('cmall_email_user_write_product_qna_reply_title')
			);
			$content = str_replace(
				$searchconfig,
				$replaceconfig_escape,
				$this->CI->cbconfig->item('cmall_email_user_write_product_qna_reply_content')
			);
			$this->CI->email->clear(true);
			$this->CI->email->from($this->CI->cbconfig->item('webmaster_email'), $this->CI->cbconfig->item('webmaster_name'));
			$this->CI->email->to(element('mem_email', $emailsendlistuser));
			$this->CI->email->subject($title);
			$this->CI->email->message($content);
			$this->CI->email->send();
		}
		if ($notesendlistadmin) {
			$title = str_replace(
				$searchconfig,
				$replaceconfig,
				$this->CI->cbconfig->item('cmall_note_admin_write_product_qna_reply_title')
			);
			$content = str_replace(
				$searchconfig,
				$replaceconfig_escape,
				$this->CI->cbconfig->item('cmall_note_admin_write_product_qna_reply_content')
			);
			foreach ($notesendlistadmin as $akey => $aval) {
				$note_result = $this->CI->notelib->send_note(
					$sender = 0,
					$receiver = element('mem_id', $aval),
					$title,
					$content,
					1
				);
			}
		}
		if ($notesendlistuser) {
			$title = str_replace(
				$searchconfig,
				$replaceconfig,
				$this->CI->cbconfig->item('cmall_note_user_write_product_qna_reply_title')
			);
			$content = str_replace(
				$searchconfig,
				$replaceconfig_escape,
				$this->CI->cbconfig->item('cmall_note_user_write_product_qna_reply_content')
			);
			$note_result = $this->CI->notelib->send_note(
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
					$this->CI->cbconfig->item('cmall_sms_admin_write_product_qna_reply_content')
				);
				$sender = array(
					'phone' => $this->CI->cbconfig->item('sms_admin_phone'),
				);
				$receiver = array();
				foreach ($smssendlistadmin as $akey => $aval) {
					$receiver[] = array(
						'mem_id' => element('mem_id', $aval),
						'name' => element('mem_nickname', $aval),
						'phone' => element('mem_phone', $aval),
					);
				}
				$smsresult = $this->CI->smslib->send($receiver, $sender, $content, $date = '', '상품문의답변작성알림');
			}
		}
		if ($smssendlistuser) {
			if (file_exists(APPPATH . 'libraries/Smslib.php')) {
				$this->load->library(array('smslib'));
				$content = str_replace(
					$searchconfig,
					$replaceconfig,
					$this->CI->cbconfig->item('cmall_sms_user_write_product_qna_reply_content')
				);
				$sender = array(
					'phone' => $this->CI->cbconfig->item('sms_admin_phone'),
				);
				$receiver = array();
				$receiver[] = $smssendlistuser;
				$smsresult = $this->CI->smslib->send($receiver, $sender, $content, $date = '', '상품문의답변작성알림');
			}
		}
	}


	public function orderalarm($type = '', $cor_id = 0)
	{
		if (empty($type)) {
			return;
		}
		$cor_id = (int) $cor_id;
		if (empty($cor_id) OR $cor_id < 1) {
			return;
		}

		$this->CI->load->model(array('Cmall_item_model', 'Cmall_order_model', 'Cmall_order_detail_model', 'Member_model'));
		$order = $this->CI->Cmall_order_model->get_one($cor_id);
		if ( ! element('cor_id', $order)) {
			return;
		}
		$orderdetail = $this->CI->Cmall_order_detail_model->get_by_item($cor_id);
		if ($orderdetail) {
			foreach ($orderdetail as $key => $value) {
				$orderdetail[$key]['item'] = $this->CI->Cmall_item_model->get_one(element('cit_id', $value));
				$orderdetail[$key]['itemdetail'] = $this->CI->Cmall_order_detail_model->get_detail_by_item($cor_id, element('cit_id', $value));
			}
		}

		$member = $this->CI->Member_model->get_one(element('mem_id', $order));

		$emailsendlistadmin = array();
		$notesendlistadmin = array();
		$smssendlistadmin = array();
		$emailsendlistuser = array();
		$notesendlistuser = array();
		$smssendlistuser = array();

		$superadminlist = '';
		if ($this->CI->cbconfig->item('cmall_email_admin_' . $type)
			OR $this->CI->cbconfig->item('cmall_note_admin_' . $type)
			OR $this->CI->cbconfig->item('cmall_sms_admin_' . $type)) {

			$mselect = 'mem_id, mem_email, mem_nickname, mem_phone';
			$superadminlist = $this->CI->Member_model->get_superadmin_list($mselect);

		}
		if ($this->CI->cbconfig->item('cmall_email_admin_' . $type) && $superadminlist) {
			foreach ($superadminlist as $key => $value) {
				$emailsendlistadmin[$value['mem_id']] = $value;
			}
		}
		if (($this->CI->cbconfig->item('cmall_email_user_' . $type) && element('mem_receive_email', $member)) OR $this->CI->cbconfig->item('cmall_email_alluser_' . $type)) {
			$emailsendlistuser['mem_email'] = element('mem_email', $member);
		}
		if ($this->CI->cbconfig->item('cmall_note_admin_' . $type) && $superadminlist) {
			foreach ($superadminlist as $key => $value) {
				$notesendlistadmin[$value['mem_id']] = $value;
			}
		}
		if ($this->CI->cbconfig->item('cmall_note_user_' . $type) && element('mem_use_note', $member)) {
			$notesendlistuser['mem_id'] = element('mem_id', $member);
		}
		if ($this->CI->cbconfig->item('cmall_sms_admin_' . $type) && $superadminlist) {
			foreach ($superadminlist as $key => $value) {
				$smssendlistadmin[$value['mem_id']] = $value;
			}
		}
		if (($this->CI->cbconfig->item('cmall_sms_user_' . $type) && element('mem_receive_sms', $member))
			OR $this->CI->cbconfig->item('cmall_sms_alluser_' . $type)) {
			if (element('mem_phone', $member)) {
				$smssendlistuser = $member;
			}
		}

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
			'{결제금액}',
			'{은행계좌안내}',
		);
		$receive_email = element('mem_receive_email', $member) ? '동의' : '거부';
		$receive_note = element('mem_use_note', $member) ? '동의' : '거부';
		$receive_sms = element('mem_receive_sms', $member) ? '동의' : '거부';

		$replaceconfig = array(
			$this->CI->cbconfig->item('site_title'),
			$this->CI->cbconfig->item('company_name'),
			site_url(),
			element('mem_userid', $member),
			element('mem_nickname', $member),
			element('mem_username', $member),
			element('mem_email', $member),
			$receive_email,
			$receive_note,
			$receive_sms,
			$this->CI->input->ip_address(),
			number_format(abs(element('cor_cash_request', $order))),
			$this->CI->cbconfig->item('payment_bank_info'),
		);
		$replaceconfig_escape = array(
			html_escape($this->CI->cbconfig->item('site_title')),
			html_escape($this->CI->cbconfig->item('company_name')),
			site_url(),
			html_escape(element('mem_userid', $member)),
			html_escape(element('mem_nickname', $member)),
			html_escape(element('mem_username', $member)),
			html_escape(element('mem_email', $member)),
			$receive_email,
			$receive_note,
			$receive_sms,
			$this->CI->input->ip_address(),
			number_format(abs(element('cor_cash_request', $order))),
			html_escape($this->CI->cbconfig->item('payment_bank_info')),
		);
		$emailform = array();
		$emailform['emailform']['order'] = $order;
		$emailform['emailform']['orderdetail'] = $orderdetail;

		if ($emailsendlistadmin) {
			$title = str_replace(
				$searchconfig,
				$replaceconfig,
				$this->CI->cbconfig->item('cmall_email_admin_' . $type . '_title')
			);
			$content = $this->CI->load->view('emailform/cmall/email_admin_' . $type, $emailform, true);
			foreach ($emailsendlistadmin as $akey => $aval) {
				$this->CI->email->clear(true);
				$this->CI->email->from($this->CI->cbconfig->item('webmaster_email'), $this->CI->cbconfig->item('webmaster_name'));
				$this->CI->email->to(element('mem_email', $aval));
				$this->CI->email->subject($title);
				$this->CI->email->message($content);
				$this->CI->email->send();
			}
		}
		if ($emailsendlistuser) {
			$title = str_replace(
				$searchconfig,
				$replaceconfig,
				$this->CI->cbconfig->item('cmall_email_user_' . $type . '_title')
			);
			$content = $this->CI->load->view('emailform/cmall/email_user_' . $type, $emailform, true);
			$this->CI->email->clear(true);
			$this->CI->email->from($this->CI->cbconfig->item('webmaster_email'), $this->CI->cbconfig->item('webmaster_name'));
			$this->CI->email->to(element('mem_email', $emailsendlistuser));
			$this->CI->email->subject($title);
			$this->CI->email->message($content);
			$this->CI->email->send();
		}
		if ($notesendlistadmin) {
			$title = str_replace(
				$searchconfig,
				$replaceconfig,
				$this->CI->cbconfig->item('cmall_note_admin_' . $type . '_title')
			);
			$content = str_replace(
				$searchconfig,
				$replaceconfig_escape,
				$this->CI->cbconfig->item('cmall_note_admin_' . $type . '_content')
			);
			foreach ($notesendlistadmin as $akey => $aval) {
				$note_result = $this->CI->notelib->send_note(
					$sender = 0,
					$receiver = element('mem_id', $aval),
					$title,
					$content,
					1
				);
			}
		}
		if ($notesendlistuser) {
			$title = str_replace(
				$searchconfig,
				$replaceconfig,
				$this->CI->cbconfig->item('cmall_note_user_' . $type . '_title')
			);
			$content = str_replace(
				$searchconfig,
				$replaceconfig_escape,
				$this->CI->cbconfig->item('cmall_note_user_' . $type . '_content')
			);
			$note_result = $this->CI->notelib->send_note(
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
					$this->CI->cbconfig->item('cmall_sms_admin_' . $type . '_content')
				);
				$sender = array(
					'phone' => $this->CI->cbconfig->item('sms_admin_phone'),
				);
				$receiver = array();
				foreach ($smssendlistadmin as $akey => $aval) {
					$receiver[] = array(
						'mem_id' => element('mem_id', $aval),
						'name' => element('mem_nickname', $aval),
						'phone' => element('mem_phone', $aval),
					);
				}
				$smsresult = $this->CI->smslib->send($receiver, $sender, $content, $date = '', '컨텐츠몰');
			}
		}
		if ($smssendlistuser) {
			if (file_exists(APPPATH . 'libraries/Smslib.php')) {
				$this->load->library(array('smslib'));
				$content = str_replace(
					$searchconfig,
					$replaceconfig,
					$this->CI->cbconfig->item('cmall_sms_user_' . $type . '_content')
				);
				$sender = array(
					'phone' => $this->CI->cbconfig->item('sms_admin_phone'),
				 );
				$receiver = array();
				$receiver[] = $smssendlistuser;
				$smsresult = $this->CI->smslib->send($receiver, $sender, $content, $date = '', '컨텐츠몰');
			}
		}
	}
}
