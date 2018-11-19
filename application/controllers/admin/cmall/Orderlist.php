<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Orderlist class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 관리자>컨텐츠몰관리>구매내역 controller 입니다.
 */
class Orderlist extends CB_Controller
{

	/**
	 * 관리자 페이지 상의 현재 디렉토리입니다
	 * 페이지 이동시 필요한 정보입니다
	 */
	public $pagedir = 'cmall/orderlist';

	/**
	 * 모델을 로딩합니다
	 */
	protected $models = array('Cmall_order', 'Cmall_item', 'Cmall_order_detail');

	/**
	 * 이 컨트롤러의 메인 모델 이름입니다
	 */
	protected $modelname = 'Cmall_order_model';

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
		$this->load->library(array('pagination', 'querystring', 'cmalllib'));
	}

	/**
	 * 목록을 가져오는 메소드입니다
	 */
	public function index()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_admin_cmall_orderlist_index';
		$this->load->event($eventname);

		$view = array();
		$view['view'] = array();

		// 이벤트가 존재하면 실행합니다
		$view['view']['event']['before'] = Events::trigger('before', $eventname);

		/**
		 * Validation 라이브러리를 가져옵니다
		 */
		$this->load->library('form_validation');

		$config = array(
			array(
				'field' => 'cor_id',
				'label' => '구매아이디',
				'rules' => 'trim|required|numeric',
			),
			array(
				'field' => 'mem_id',
				'label' => '회원아이디',
				'rules' => 'trim|required|numeric|is_natural',
			),
			array(
				'field' => 'cit_id',
				'label' => '상품아이디',
				'rules' => 'trim|required|numeric|is_natural',
			),
			array(
				'field' => 'cod_download_days',
				'label' => '다운로드기간',
				'rules' => 'trim|numeric|is_natural',
			),
		);
		$this->form_validation->set_rules($config);

		/**
		 * 유효성 검사를 하지 않는 경우, 또는 유효성 검사에 실패한 경우입니다.
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

			$cod_download_days = $this->input->post('cod_download_days') ? $this->input->post('cod_download_days') : 0;
			$updatedata = array(
				'cod_download_days' => $cod_download_days,
			);
			$where = array(
				'cor_id' => $this->input->post('cor_id'),
				'mem_id' => $this->input->post('mem_id'),
				'cit_id' => $this->input->post('cit_id'),
			);
			$this->Cmall_order_detail_model->update('', $updatedata, $where);

			redirect(current_full_url(), 'refresh');
		}

		/**
		 * 페이지에 숫자가 아닌 문자가 입력되거나 1보다 작은 숫자가 입력되면 에러 페이지를 보여줍니다.
		 */
		$param =& $this->querystring;
		$page = (((int) $this->input->get('page')) > 0) ? ((int) $this->input->get('page')) : 1;

		$findex = $this->input->get('findex') ? $this->input->get('findex') : 'cor_approve_datetime';
		$forder = $this->input->get('forder', null, 'desc');
		$sfield = $this->input->get('sfield', null, '');
		$skeyword = $this->input->get('skeyword', null, '');

		$per_page = admin_listnum();
		$offset = ($page - 1) * $per_page;

		/**
		 * 게시판 목록에 필요한 정보를 가져옵니다.
		 */
		$this->{$this->modelname}->allow_search_field = array('cor_id', 'member.mem_nickname', 'cmall_order.mem_realname', 'member.mem_userid', 'cor_content', 'cor_total_money', 'cor_cash', 'cor_memo', 'cor_admin_memo', 'cor_ip', 'member.mem_id'); // 검색이 가능한 필드
		$this->{$this->modelname}->search_field_equal = array('member.mem_id', 'cor_total_money', 'cor_cash'); // 검색중 like 가 아닌 = 검색을 하는 필드
		$this->{$this->modelname}->allow_order_field = array('cor_id', 'cor_approve_datetime'); // 정렬이 가능한 필드

		$where = array(
			'cor_status' => 1,
		);

		if ($this->input->get('cor_pay_type')) {
			$where['cor_pay_type'] = $this->input->get('cor_pay_type');
		}

		$result = $this->{$this->modelname}
			->get_admin_list($per_page, $offset, $where, '', $findex, $forder, $sfield, $skeyword);
		$list_num = $result['total_rows'] - ($page - 1) * $per_page;
		if (element('list', $result)) {
			foreach (element('list', $result) as $key => $val) {
				$result['list'][$key]['display_name'] = display_username(
					element('mem_userid', $val),
					element('mem_nickname', $val),
					element('mem_icon', $val)
				);
				$result['list'][$key]['num'] = $list_num--;

				$orderdetail = $this->Cmall_order_detail_model->get_by_item(element('cor_id', $val));
				if ($orderdetail) {
					foreach ($orderdetail as $okey => $oval) {
						$orderdetail[$okey]['item'] = $item
							= $this->Cmall_item_model->get_one(element('cit_id', $oval));
						$orderdetail[$okey]['itemdetail'] = $itemdetail
							= $this->Cmall_order_detail_model->get_detail_by_item(element('cor_id', $val), element('cit_id', $oval));

						$orderdetail[$okey]['item']['possible_download'] = 1;
						if (element('cod_download_days', element(0, $itemdetail))) {
							$endtimestamp = strtotime(element('cor_approve_datetime', $val))
								+ 86400 * element('cod_download_days', element(0, $itemdetail));
							$orderdetail[$okey]['item']['download_end_date'] = $enddate = cdate('Y-m-d', $endtimestamp);

							$orderdetail[$okey]['item']['possible_download'] = ($enddate >= date('Y-m-d')) ? 1 : 0;
						}
					}
				}
				$result['list'][$key]['orderdetail'] = $orderdetail;
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
		$search_option = array('member.mem_nickname' => '회원명', 'cmall_order.mem_realname' => '회원실명', 'member.mem_userid' => '회원아이디', 'cor_content' => '내용', 'cor_total_money' => '결제금액');
		$view['view']['skeyword'] = ($sfield && array_key_exists($sfield, $search_option)) ? $skeyword : '';
		$view['view']['search_option'] = search_option($search_option, $sfield);
		$view['view']['listall_url'] = admin_url($this->pagedir);

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
