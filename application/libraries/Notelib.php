<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Notelib class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 쪽지 발송을 관리하는 class 입니다.
 */
class Notelib extends CI_Controller
{

	private $CI;

	function __construct()
	{
		$this->CI = & get_instance();
		$this->CI->load->model( array('Note_model', 'Member_meta_model'));
		$this->CI->load->helper( array('array'));
	}


	/**
	 * 쪽지를 발송하는 함수입니다
	 */
	public function send_note($sender = '', $receiver = '', $title = '', $content = '', $content_type = '', $originfilename = '', $uploadfilename = '')
	{
		// 포인트 사용을 하지 않는다면 return
		if ( ! $this->CI->cbconfig->item('use_note')) {
			$result = json_encode( array('error' => '쪽지를 사용하지 않는 사이트입니다'));
			return $result;
		}
		if ($sender && ! is_numeric($sender)) {
			$result = json_encode( array('error' => '보내는 이가 잘못되었습니다'));
			return $result;
		}
		if (empty($sender)) {
			$sender = 0; // 사이트 관리자가 보낸 경우임
		}
		if (empty($receiver)) {
			$result = json_encode( array('error' => '받는이가 지정되지 않았습니다'));
			return $result;
		}
		if (empty($title)) {
			$result = json_encode( array('error' => '쪽지 제목이 존재하지 않습니다'));
			return $result;
		}
		if (empty($content)) {
			$result = json_encode( array('error' => '쪽지 내용이 존재하지 않습니다'));
			return $result;
		}
		if ( ! $this->CI->cbconfig->item('use_note_file')) {
			if ($originfilename OR $uploadfilename) {
				$result = json_encode( array('error' => '쪽지에 첨부파일 기능을 지원하지 않습니다'));
				return $result;
			}
		}
		$point_note = $this->CI->cbconfig->item('use_point')
			? $this->CI->cbconfig->item('point_note') : 0;

		$point_note_file = ($uploadfilename && $this->CI->cbconfig->item('use_point'))
			? $this->CI->cbconfig->item('point_note_file') : 0;

		$send_member = '';
		if ($sender) {
			$select = 'mem_id, mem_point, mem_use_note, mem_is_admin, mem_nickname';
			$send_member = $this->CI->Member_model->get_by_memid($sender, $select);
			if ( ! element('mem_id', $send_member)) {
				$result = json_encode( array('error' => '보내는이가 존재하지 않습니다'));
				return $result;
			}
			if ( ! element('mem_use_note', $send_member) && ! element('mem_is_admin', $send_member)) {
				$result = json_encode( array('error' => '보내는이가 쪽지기능을 사용하지 않습니다'));
				return $result;
			}
			if ((element('mem_point', $send_member) < $point_note) && $this->CI->member->is_admin() !== 'super') {
				$result = json_encode( array('error' => '보유하신 포인트가 모자라 쪽지를 보낼 수 없습니다, 쪽지발송에는 ' . number_format($point_note) . '점이 필요합니다'));
				return $result;
			}
		}
		$recv_member = '';
		if ($receiver) {
			$select = 'mem_id, mem_point, mem_use_note, mem_nickname, mem_denied';
			$recv_member = $this->CI->Member_model->get_by_memid($receiver, $select);
			if ( ! element('mem_id', $recv_member)) {
				$result = json_encode( array('error' => '받는이가 존재하지 않습니다'));
				return $result;
			}
			if (element('mem_denied', $recv_member)) {
				$result = json_encode( array('error' => '받는이가 존재하지 않습니다'));
				return $result;
			}
			if ( ! element('mem_use_note', $recv_member)) {
				$result = json_encode( array('error' => '받는이가 쪽지기능을 사용하지 않습니다'));
				return $result;
			}
		}

		if (empty($content_type)) {
			$content_type = 0;
		}
		$nte_content_html_type = $content_type;
		$insertdata = array(
			'send_mem_id' => $sender,
			'recv_mem_id' => $receiver,
			'nte_type' => 1,
			'nte_title' => $title,
			'nte_content' => $content,
			'nte_content_html_type' => $nte_content_html_type,
			'nte_datetime' => cdate('Y-m-d H:i:s'),
			'nte_originname' => $originfilename,
			'nte_filename' => $uploadfilename,
		);
		$note_id = $this->CI->Note_model->insert($insertdata);

		$unread_note_num = $this->CI->Member_meta_model->item($receiver, 'meta_unread_note_num') + 1;
		$metadata = array(
			'meta_note_received_datetime' => cdate('Y-m-d H:i:s'),
			'meta_note_received_from_id' => $sender,
			'meta_note_received_from_nickname' => element('mem_nickname', $send_member),
			'meta_unread_note_num' => $unread_note_num,
		);
		$this->CI->Member_meta_model->save($receiver, $metadata);

		if ($sender) {
			$insertdata = array(
				'send_mem_id' => $sender,
				'recv_mem_id' => $receiver,
				'nte_type' => 2,
				'related_note_id' => $note_id,
				'nte_title' => $title,
				'nte_content' => $content,
				'nte_content_html_type' => $nte_content_html_type,
				'nte_datetime' => cdate('Y-m-d H:i:s'),
				'nte_originname' => $originfilename,
				'nte_filename' => $uploadfilename,
			);
			$note_id2 = $this->CI->Note_model->insert($insertdata);

			if ($this->CI->cbconfig->item('use_point') && $point_note && $point_note > 0) {
				$point = 0 - $point_note;
				$this->CI->load->library('point');
				$this->CI->point->insert_point(
					$sender,
					$point,
					element('mem_nickname', $recv_member) . '님께 쪽지 발송',
					'note',
					$note_id,
					$note_id . ' 쪽지 발송'
				);
			}

			if ($this->CI->cbconfig->item('use_point') && $point_note_file && $point_note_file > 0) {
				$point = 0 - $point_note_file;
				$this->CI->load->library('point');
				$this->CI->point->insert_point(
					$sender,
					$point,
					element('mem_nickname', $recv_member) . '님께 발송한 쪽지에 파일첨부',
					'note',
					$note_id,
					$note_id . ' 쪽지 발송 파일첨부'
				);
			}
		}

		if ($this->CI->cbconfig->item('use_notification') && $this->CI->cbconfig->item('notification_note')) {
			$this->CI->load->library('notificationlib');
			$sender_name = $sender ? element('mem_nickname', $send_member) : '알림';
			$not_message = $sender_name . '님께서 [' . $title . '] 쪽지를 남기셨습니다';
			$not_url = site_url('note/view/recv/' . $note_id);
			$return = $this->CI->notificationlib->set_noti(
				$receiver,
				$sender,
				'note',
				$note_id,
				$not_message,
				$not_url
			);
		}

		$result = json_encode( array('success' => element('mem_nickname', $recv_member) . '님께 쪽지를 발송하였습니다'));

		return $result;
	}
}
