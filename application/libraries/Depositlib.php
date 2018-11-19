<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Depositlib class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * deposit table 을 관리하는 class 입니다.
 */
class Depositlib extends CI_Controller
{

	private $CI;

	public $deptype = array(
			'cash' => '현금/카드',
			'point' => '포인트',
			'contents' => '컨텐츠',
			'deposit' => '예치금',
			'service' => '서비스'
		);

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
	}


	/**
	 * deposit 기능을 사용하는지 체크합니다.
	 */
	public function use_deposit()
	{
		$use = $this->CI->cbconfig->item('use_deposit');
		return $use;
	}


	/**
	 * point 를 deposit 로 변환합니다
	 */
	public function get_point_to_deposit($point = '')
	{
		$deposit = floor($point / $this->CI->cbconfig->item('deposit_point'));
		return $deposit;
	}


	/**
	 * deposit 를 point 로 변환합니다
	 */
	public function get_deposit_to_point($deposit = '')
	{
		$point = $deposit * $this->CI->cbconfig->item('deposit_refund_point');
		return $point;
	}


	public function do_point_to_deposit($mem_id = 0, $point = '', $pay_type = '', $content = '', $admin_memo = '')
	{
		if ( ! $this->use_deposit()) {
			$return = array(
				'result' => 'fail',
				'reason' => '이 사이트는 ' . $this->CI->cbconfig->item('deposit_name') . ' 기능을 사용하지 않습니다',
			);
			return json_encode($return);
		}

		$mem_id = (int) $mem_id;
		if (empty($mem_id) OR $mem_id < 1) {
			$return = array(
				'result' => 'fail',
				'reason' => '회원아이디가 입력되지 않았습니다',
			);
			return json_encode($return);
		}

		if (empty($point)) {
			$return = array(
				'result' => 'fail',
				'reason' => '포인트 값이 입력되지 않았습니다',
			);
			return json_encode($return);
		}

		$member = $this->CI->Member_model->get_one($mem_id, 'mem_id, mem_nickname, mem_point');
		$deposit = $this->get_point_to_deposit($point);

		if ( ! element('mem_id', $member)) {
			$return = array(
				'result' => 'fail',
				'reason' => '존재하지 않는 회원입니다',
			);
			return json_encode($return);
		}

		if ($point > element('mem_point', $member)) {
			$return = array(
				'result' => 'fail',
				'reason' => '전환하려는 포인트(' . $point . ') 보다 가지고 계신 포인트(' . element('mem_point', $member) . ') 가 작습니다',
			);
			return json_encode($return);
		}
		if ($deposit === 0) {
			$return = array(
				'result' => 'fail',
				'reason' => '전환하려는 ' . $this->CI->cbconfig->item('deposit_name') . '가(이) 0 이므로 진행하지 않습니다',
			);
			return json_encode($return);
		}
		if ($this->CI->cbconfig->item('deposit_point_min') && $point < $this->CI->cbconfig->item('deposit_point_min')) {
			$return = array(
				'result' => 'fail',
				'reason' => '최소 ' . $this->CI->cbconfig->item('deposit_point_min') . ' 포인트 이상만 전환이 가능합니다',
			);
			return json_encode($return);
		}

		$_point = (-1) * $point;

		$this->CI->load->model(array('Deposit_model'));

		$sum = $this->CI->Deposit_model->get_deposit_sum($mem_id);
		$deposit_sum = $sum + $deposit;

		$dep_id = $this->CI->session->userdata('unique_id');

		$data = array(
			'dep_id' => $dep_id,
			'mem_id' => $mem_id,
			'mem_nickname' => element('mem_nickname', $member),
			 'dep_from_type' => 'point',
			 'dep_to_type' => 'deposit',
			'dep_deposit_request' => $deposit,
			'dep_deposit' => $deposit,
			'dep_deposit_sum' => $deposit_sum,
			'dep_point' => $_point,
			'dep_pay_type' => $pay_type,
			'dep_content' => $content,
			'dep_admin_memo' => $admin_memo,
			'dep_datetime' => cdate('Y-m-d H:i:s'),
			'dep_deposit_datetime' => cdate('Y-m-d H:i:s'),
			'dep_ip' => $this->CI->input->ip_address(),
			'dep_useragent' => $this->CI->agent->agent_string(),
			'dep_status' => 1,
		);
		$this->CI->Deposit_model->insert($data);

		$this->CI->load->library('point');
		$this->CI->point->insert_point(
			$mem_id,
			$_point,
			$this->CI->cbconfig->item('deposit_name') . ' ' . $deposit . ' ' . $this->CI->cbconfig->item('deposit_unit') . ' (으)로 전환 ',
			'deposit_point',
			$dep_id,
			'전환'
		);
		$this->update_member_deposit($mem_id);

		$return = array(
			'result' => 'success',
			'reason' => '포인트(' . $point . ')를 ' . $this->CI->cbconfig->item('deposit_name') . '(' . $deposit . ') 으로 전환합니다',
			'deposit' => $deposit,
			'dep_id' => $dep_id,
		);
		return json_encode($return);
	}


	public function do_deposit_to_point($mem_id = 0, $deposit = '', $pay_type = '', $content = '', $admin_memo = '')
	{
		if ( ! $this->use_deposit()) {
			$return = array(
				'result' => 'fail',
				'reason' => '이 사이트는 ' . $this->CI->cbconfig->item('deposit_name') . ' 기능을 사용하지 않습니다',
			);
			return json_encode($return);
		}

		$mem_id = (int) $mem_id;
		if (empty($mem_id) OR $mem_id < 1) {
			$return = array(
				'result' => 'fail',
				'reason' => '회원아이디가 입력되지 않았습니다',
			);
			return json_encode($return);
		}

		if (empty($deposit)) {
			$return = array(
				'result' => 'fail',
				'reason' => $this->CI->cbconfig->item('deposit_name') . ' 값이 입력되지 않았습니다',
			);
			return json_encode($return);
		}

		$member = $this->CI->Member_model->get_one($mem_id, 'mem_id, mem_nickname, mem_point');
		$this->CI->load->model('Member_meta_model');
		$meta = $this->CI->Member_meta_model->get_all_meta(element('mem_id', $member));
		$point = $this->get_deposit_to_point($deposit);

		if ( ! element('mem_id', $member)) {
			$return = array(
				'result' => 'fail',
				'reason' => '존재하지 않는 회원입니다',
			);
			return json_encode($return);
		}

		if ($deposit > element('total_deposit', $meta)) {
			$return = array(
				'result' => 'fail',
				'reason' => '전환하려는 ' . $this->CI->cbconfig->item('deposit_name') . '(' . $deposit . ') 보다 가지고 계신 ' . $this->CI->cbconfig->item('deposit_name') . '(' . element('total_deposit', $meta) . ') 가 작습니다',
			);
			return json_encode($return);
		}

		if ($point === 0) {
			$return = array(
				'result' => 'fail',
				'reason' => '전환하려는 포인트가 0 이므로 진행하지 않습니다',
			);
			return json_encode($return);
		}

		$_deposit = (-1) * $deposit;

		$this->CI->load->model(array('Deposit_model'));

		$sum = $this->CI->Deposit_model->get_deposit_sum($mem_id);
		$deposit_sum = $sum + $_deposit;

		$dep_id = $this->CI->session->userdata('unique_id');

		$data = array(
			'dep_id' => $dep_id,
			'mem_id' => $mem_id,
			'mem_nickname' => element('mem_nickname', $member),
			 'dep_from_type' => 'deposit',
			 'dep_to_type' => 'point',
			'dep_deposit_request' => $_deposit,
			'dep_deposit' => $_deposit,
			'dep_deposit_sum' => $deposit_sum,
			'dep_point' => $point,
			'dep_pay_type' => $pay_type,
			'dep_content' => $content,
			'dep_admin_memo' => $admin_memo,
			'dep_datetime' => cdate('Y-m-d H:i:s'),
			'dep_deposit_datetime' => cdate('Y-m-d H:i:s'),
			'dep_ip' => $this->CI->input->ip_address(),
			'dep_useragent' => $this->CI->agent->agent_string(),
			'dep_status' => 1,
		);
		$this->CI->Deposit_model->insert($data);
		$this->CI->load->library('point');
		$this->CI->point->insert_point(
			$mem_id,
			$point,
			$this->CI->cbconfig->item('deposit_name') . ' ' . $deposit . ' ' . $this->CI->cbconfig->item('deposit_unit') . ' 을(를) 포인트로 전환 ',
			'deposit_point',
			$dep_id,
			'전환'
		);

		$this->update_member_deposit($mem_id);

		$return = array(
			'result' => 'success',
			'reason' => $this->CI->cbconfig->item('deposit_name') . '(' . $deposit . ') 을(를) 포인트(' . $point . ')로 전환합니다',
			'point' => $point,
			'dep_id' => $dep_id,
		);

		return json_encode($return);
	}


	public function do_deposit_to_contents($mem_id = 0, $deposit = '', $pay_type = '', $content = '', $admin_memo = '')
	{
		$mem_id = (int) $mem_id;
		if (empty($mem_id) OR $mem_id < 1) {
			$return = array(
				'result' => 'fail',
				'reason' => '회원아이디가 입력되지 않았습니다',
			);
			return json_encode($return);
		}

		if (empty($deposit)) {
			$return = array(
				'result' => 'fail',
				'reason' => $this->CI->cbconfig->item('deposit_name') . ' 값이 입력되지 않았습니다',
			);
			return json_encode($return);
		}

		$member = $this->CI->Member_model->get_one($mem_id, 'mem_id, mem_nickname, mem_point');
		$this->CI->load->model('Member_meta_model');
		$meta = $this->CI->Member_meta_model->get_all_meta(element('mem_id', $member));

		if ( ! element('mem_id', $member)) {
			$return = array(
				'result' => 'fail',
				'reason' => '존재하지 않는 회원입니다',
			);
			return json_encode($return);
		}

		if ($deposit > element('total_deposit', $meta)) {
			$return = array(
				'result' => 'fail',
				'reason' => '전환하려는 ' . $this->CI->cbconfig->item('deposit_name') . '(' . $deposit . ') 보다 가지고 계신 '
					. $this->CI->cbconfig->item('deposit_name') . '(' . element('total_deposit', $meta) . ') 가 작습니다',
			);
			return json_encode($return);
		}

		$_deposit = (-1) * $deposit;

		$this->CI->load->model(array('Deposit_model'));

		$sum = $this->CI->Deposit_model->get_deposit_sum($mem_id);
		$deposit_sum = $sum + $_deposit;

		$this->CI->load->model('Unique_id_model');
		$dep_id = $this->CI->Unique_id_model->get_id($this->CI->input->ip_address());

		$data = array(
			'dep_id' => $dep_id,
			'mem_id' => $mem_id,
			'mem_nickname' => element('mem_nickname', $member),
			 'dep_from_type' => 'deposit',
			 'dep_to_type' => 'contents',
			'dep_deposit_request' => $_deposit,
			'dep_deposit' => $_deposit,
			'dep_deposit_sum' => $deposit_sum,
			'dep_pay_type' => '',
			'dep_content' => $content,
			'dep_admin_memo' => $admin_memo,
			'dep_datetime' => cdate('Y-m-d H:i:s'),
			'dep_deposit_datetime' => cdate('Y-m-d H:i:s'),
			'dep_ip' => $this->CI->input->ip_address(),
			'dep_useragent' => $this->CI->agent->agent_string(),
			'dep_status' => 1,
		);
		$this->CI->Deposit_model->insert($data);

		$this->update_member_deposit($mem_id);

		$return = array(
			'result' => 'success',
			'reason' => '전환이 완료되었습니다',
			'dep_id' => $dep_id,
		);

		return json_encode($return);
	}


	public function update_member_deposit($mem_id = 0)
	{
		$mem_id = (int) $mem_id;
		if (empty($mem_id) OR $mem_id < 1) {
			return;
		}
		$this->CI->load->model(array('Member_meta_model', 'Deposit_model'));

		$member = $this->CI->Member_model->get_by_memid($mem_id, 'mem_id');

		if ( ! element('mem_id', $member)) {
			return false;
		}

		$sum = $this->CI->Deposit_model->get_deposit_sum($mem_id);
		$this->CI->Member_meta_model->save($mem_id, array('total_deposit' => $sum));

		return $sum;
	}


	public function alarm($type = '', $dep_id = 0)
	{
		if (empty($type)) {
			return;
		}
		$dep_id = (int) $dep_id;
		if (empty($dep_id) OR $dep_id < 1) {
			return;
		}

		$this->CI->load->model(array('Deposit_model', 'Member_model'));
		$this->CI->load->library(array('email', 'notelib'));
		$deposit = $this->CI->Deposit_model->get_one($dep_id);
		$member = $this->CI->Member_model->get_one(element('mem_id', $deposit));

		if ( ! element('dep_id', $deposit)) {
			return;
		}

		$emailsendlistadmin = array();
		$notesendlistadmin = array();
		$smssendlistadmin = array();
		$emailsendlistuser = array();
		$notesendlistuser = array();
		$smssendlistuser = array();

		$superadminlist = '';
		if ($this->CI->cbconfig->item('deposit_email_admin_' . $type)
			OR $this->CI->cbconfig->item('deposit_note_admin_' . $type)
			OR $this->CI->cbconfig->item('deposit_sms_admin_' . $type)) {

			$mselect = 'mem_id, mem_email, mem_nickname, mem_phone';
			$superadminlist = $this->CI->Member_model->get_superadmin_list($mselect);

		}
		if ($this->CI->cbconfig->item('deposit_email_admin_' . $type) && $superadminlist) {
			foreach ($superadminlist as $key => $value) {
				$emailsendlistadmin[$value['mem_id']] = $value;
			}
		}
		if (($this->CI->cbconfig->item('deposit_email_user_' . $type) && element('mem_receive_email', $member))
			OR $this->CI->cbconfig->item('deposit_email_alluser_' . $type)) {
			$emailsendlistuser['mem_email'] = element('mem_email', $member);
		}
		if ($this->CI->cbconfig->item('deposit_note_admin_' . $type) && $superadminlist) {
			foreach ($superadminlist as $key => $value) {
				$notesendlistadmin[$value['mem_id']] = $value;
			}
		}
		if ($this->CI->cbconfig->item('deposit_note_user_' . $type) && element('mem_use_note', $member)) {
			$notesendlistuser['mem_id'] = element('mem_id', $member);
		}
		if ($this->CI->cbconfig->item('deposit_sms_admin_' . $type) && $superadminlist) {
			foreach ($superadminlist as $key => $value) {
				$smssendlistadmin[$value['mem_id']] = $value;
			}
		}
		if (($this->CI->cbconfig->item('deposit_sms_user_' . $type) && element('mem_receive_sms', $member))
			OR $this->CI->cbconfig->item('deposit_sms_alluser_' . $type)) {
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
			'{전환예치금액}',
			'{예치금명}',
			'{예치금단위}',
			'{전환포인트}',
			'{은행계좌안내}',
		);
		$receive_email = element('mem_receive_email', $member) ? '동의' : '거부';
		$receive_note = element('mem_use_note', $member) ? '동의' : '거부';
		$receive_sms = element('mem_receive_sms', $member) ? '동의' : '거부';

		$money1 = ''; // 결제금액
		$money2 = ''; // 전환예치금액
		$changepoint = ''; // 전환포인트

		if ($type === 'cash_to_deposit') {
			$money1 = number_format(abs(element('dep_cash_request', $deposit))); // 결제금액
			$money2 = number_format(element('dep_deposit_request', $deposit)); // 전환예치금액
		}
		if ($type === 'bank_to_deposit') {
			$money1 = number_format(abs(element('dep_cash_request', $deposit))); // 결제금액
			$money2 = number_format(element('dep_deposit_request', $deposit)); // 전환예치금액
		}
		if ($type === 'approve_bank_to_deposit') {
			$money1 = number_format(abs(element('dep_cash_request', $deposit))); // 결제금액
			$money2 = number_format(element('dep_deposit_request', $deposit)); // 전환예치금액
		}
		if ($type === 'point_to_deposit') {
			$money2 = number_format(element('dep_deposit_request', $deposit)); // 전환예치금액
			$changepoint = number_format(abs(element('dep_point', $deposit))); // 전환포인트
		}
		if ($type === 'deposit_to_point') {
			$money2 = number_format(abs(element('dep_deposit_request', $deposit))); // 전환예치금액
			$changepoint = number_format(abs(element('dep_point', $deposit))); // 전환포인트
		}

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
			$money1,
			$money2,
			$this->CI->cbconfig->item('deposit_name'),
			$this->CI->cbconfig->item('deposit_unit'),
			$changepoint,
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
			$money1,
			$money2,
			html_escape($this->CI->cbconfig->item('deposit_name')),
			html_escape($this->CI->cbconfig->item('deposit_unit')),
			$changepoint,
			html_escape($this->CI->cbconfig->item('payment_bank_info')),
		);

		if ($emailsendlistadmin) {
			$title = str_replace(
				$searchconfig,
				$replaceconfig,
				$this->CI->cbconfig->item('deposit_email_admin_' . $type . '_title')
			);
			$content = str_replace(
				$searchconfig,
				$replaceconfig_escape,
				$this->CI->cbconfig->item('deposit_email_admin_' . $type . '_content')
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
				$this->CI->cbconfig->item('deposit_email_user_' . $type . '_title')
			);
			$content = str_replace(
				$searchconfig,
				$replaceconfig_escape,
				$this->CI->cbconfig->item('deposit_email_user_' . $type . '_content')
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
				$this->CI->cbconfig->item('deposit_note_admin_' . $type . '_title')
			);
			$content = str_replace(
				$searchconfig,
				$replaceconfig_escape,
				$this->CI->cbconfig->item('deposit_note_admin_' . $type . '_content')
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
		if ($notesendlistuser && element('mem_id', $notesendlistuser)) {
			$title = str_replace(
				$searchconfig,
				$replaceconfig,
				$this->CI->cbconfig->item('deposit_note_user_' . $type . '_title')
			);
			$content = str_replace(
				$searchconfig,
				$replaceconfig_escape,
				$this->CI->cbconfig->item('deposit_note_user_' . $type . '_content')
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
					$this->CI->cbconfig->item('deposit_sms_admin_' . $type . '_content')
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
				$smsresult = $this->CI->smslib->send($receiver, $sender, $content, $date = '', '예치금알림');
			}
		}
		if ($smssendlistuser) {
			if (file_exists(APPPATH . 'libraries/Smslib.php')) {
				$this->load->library(array('smslib'));
				$content = str_replace(
					$searchconfig,
					$replaceconfig,
					$this->CI->cbconfig->item('deposit_sms_user_' . $type . '_content')
				);
				$sender = array(
					'phone' => $this->cbconfig->item('sms_admin_phone'),
				);
				$receiver = array();
				$receiver[] = $smssendlistuser;
				$smsresult = $this->CI->smslib->send($receiver, $sender, $content, $date = '', '예치금알림');
			}
		}
	}
}
