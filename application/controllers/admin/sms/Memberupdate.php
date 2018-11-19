<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Memberupdate class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 관리자>SMS 설정>회원정보업데이트 controller 입니다.
 */
class Memberupdate extends CB_Controller
{

	/**
	 * 관리자 페이지 상의 현재 디렉토리입니다
	 * 페이지 이동시 필요한 정보입니다
	 */
	public $pagedir = 'sms/memberupdate';

	/**
	 * 모델을 로딩합니다
	 */
	protected $models = array('Sms_member');

	/**
	 * 이 컨트롤러의 메인 모델 이름입니다
	 */
	protected $modelname = 'Member_model';

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
		$this->load->library(array('querystring', 'smslib'));
	}


	/**
	 * SMS 설정>회원정보업데이트 페이지입니다
	 */
	public function index()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_sms_memberupdate_index';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		/**
		 * Validation 라이브러리를 가져옵니다
		 */
		$this->load->library('form_validation');

		/**
		 * 전송된 데이터의 유효성을 체크합니다
		 */
		$config = array(
			array(
				'field' => 'execute',
				'label' => '실행',
				'rules' => 'trim',
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

			$count = 0;
			$phone_count = 0;
			$phone_empty = 0;
			$denied = 0;
			$recevice_count = 0;

			// 회원 데이터 마이그레이션

			$select = 'mem_id, mem_nickname, mem_phone, mem_receive_sms, mem_denied';
			$memberdata = $this->Member_model->get('', $select, '', '', '', 'mem_id', 'ASC');

			foreach ($memberdata as $val) {
				if (element('mem_denied', $val)) {
					$denied++;
				} elseif ( ! element('mem_phone', $val)) {
					$phone_empty++;
				} else {
					$phone_count++;
				}

				$phone = element('mem_phone', $val);

				$receive = $phone ? (int) element('mem_receive_sms', $val) : 0;
				if ($receive) {
					$recevice_count++;
				}

				$update = array(
					'mem_id' => element('mem_id', $val),
					'sme_name' => element('mem_nickname', $val),
					'sme_phone' => $phone,
					'sme_receive' => $receive,
					'sme_datetime' => cdate('Y-m-d H:i:s'),
				);
				$where = array(
					'mem_id' => element('mem_id', $val),
				);
				$smsmember = $this->Sms_member_model->get_one('', '', $where);

				if (element('mem_id', $smsmember)) { // 기존에 등록되어 있을 경우 업데이트
					// 회원이 삭제되었다면 휴대폰번호 DB 에서도 삭제한다.
					if (element('mem_denied', $val)) {
						$deletewhere = array(
							'mem_id' => element('mem_id', $val),
						);
						$this->Sms_member_model->delete_where($deletewhere);
					} else {
						$upwhere = array(
							'mem_id' => element('mem_id', $val),
						);
						$this->Sms_member_model->update('', $update, $upwhere);
					}
				} elseif ( ! element('mem_denied', $val)) { // 기존에 등록되어 있지 않을 경우 추가 (삭제된 회원이 아닐 경우)
						$this->Sms_member_model->insert($update);
				}
				$count++;
			}


			$view['view']['alert_message'] = '';

			$msg = '';

			$msg .= '<p>회원정보를 휴대폰번호 DB로 업데이트 하였습니다.</p>';
			$msg .= '<dt>총 회원 수</dt><dd>' . number_format($count) . '명</dd>';
			$msg .= '<dt>삭제된 회원</dt><dd>' . number_format($denied) . '명</dd>';
			$msg .= '<dt><span style="gray">휴대폰번호 없음</span></dt><dd>' . number_format($phone_empty) . ' 명</dd>';
			$msg .= '<dt><span style="color:blue;">휴대폰번호 정상</span></dt><dd>' . number_format($phone_count) . ' 명</span>&nbsp;';
			$msg .= '(<span style="color:blue;">수신</span>' . number_format($recevice_count) . ' 명';
			$msg .= ' / ';
			$msg .= '<span style="color:red;">거부</span>' . number_format($phone_count-$recevice_count) . ' 명)</dd>';
			$msg .= '</dl>';
			$msg .= '<p>프로그램의 실행을 끝마치셔도 좋습니다.</p>';

			$view['view']['alert_message'] = $msg;
		}

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
}
