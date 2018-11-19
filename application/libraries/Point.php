<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Point class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 포인트 추가 및 삭제를 관리하는 class 입니다.
 */
class Point extends CI_Controller
{

	private $CI;

	function __construct()
	{
		$this->CI = & get_instance();
		$this->CI->load->helper( array('array'));
	}


	/**
	 * 포인트를 추가합니다
	 */
	public function insert_point($mem_id = 0, $point = 0, $content = '', $poi_type = '', $poi_related_id = '', $poi_action = '')
	{
		// 포인트 사용을 하지 않는다면 return
		if ( ! $this->CI->cbconfig->item('use_point')) {
			return false;
		}

		// 포인트가 없다면 업데이트 할 필요 없음
		$point = (int) $point;
		if (empty($point)) {
			return false;
		}

		// 회원아이디가 없다면 업데이트 할 필요 없음
		$mem_id = (int) $mem_id;
		if (empty($mem_id) OR $mem_id < 1) {
			return false;
		}

		if (empty($content)) {
			return false;
		}

		if (empty($poi_type) && empty($poi_related_id) && empty($poi_action)) {
			return false;
		}

		$member = $this->CI->Member_model->get_by_memid($mem_id, 'mem_id');

		if ( ! element('mem_id', $member)) {
			return false;
		}

		$this->CI->load->model('Point_model');

		// 이미 등록된 내역이라면 건너뜀
		if ($poi_type OR $poi_related_id OR $poi_action) {
			$where = array(
				'mem_id' => $mem_id,
				'poi_type' => $poi_type,
				'poi_related_id' => $poi_related_id,
				'poi_action' => $poi_action,
			);
			$cnt = $this->CI->Point_model->count_by($where);

			if ($cnt > 0) {
				return false;
			}
		}

		$insertdata = array(
			'mem_id' => $mem_id,
			'poi_datetime' => cdate('Y-m-d H:i:s'),
			'poi_content' => $content,
			'poi_point' => $point,
			'poi_type' => $poi_type,
			'poi_related_id' => $poi_related_id,
			'poi_action' => $poi_action,
		);
		$this->CI->Point_model->insert($insertdata);

		$sum = $this->CI->Point_model->get_point_sum($mem_id);

		$updatedata = array(
			'mem_point' => $sum,
		);
		$this->CI->Member_model->update($mem_id, $updatedata);

		return $sum;
	}


	/**
	 * 포인트를 삭제합니다
	 */
	public function delete_point($mem_id = 0, $poi_type = '', $poi_related_id = '', $poi_action = '')
	{
		$mem_id = (int) $mem_id;
		if (empty($mem_id) OR $mem_id < 1) {
			return false;
		}

		if ($poi_type OR $poi_related_id OR $poi_action) {
			$this->CI->load->model('Point_model');

			$where = array(
				'mem_id' => $mem_id,
				'poi_type' => $poi_type,
				'poi_related_id' => $poi_related_id,
				'poi_action' => $poi_action,
			);
			$this->CI->Point_model->delete_where($where);

			// 포인트 내역의 합을 구하고
			$sum = $this->CI->Point_model->get_point_sum($mem_id);
			$updatedata = array(
				'mem_point' => $sum,
			);
			$this->CI->Member_model->update($mem_id, $updatedata);

			return $sum;
		}

		return false;
	}


	/**
	 * 포인트 PK 를 이용한 포인트 삭제입니다.
	 */
	public function delete_point_by_pk($poi_id = 0)
	{
		$poi_id = (int) $poi_id;
		if (empty($poi_id) OR $poi_id < 1) {
			return false;
		}

		$this->CI->load->model('Point_model');

		$result = $this->CI->Point_model->get_one($poi_id, 'mem_id');
		$this->CI->Point_model->delete($poi_id);

		if (element('mem_id', $result)) {
			$mem_id = element('mem_id', $result);
			// 포인트 내역의 합을 구하고
			$sum = $this->CI->Point_model->get_point_sum($mem_id);
			$updatedata = array(
				'mem_point' => $sum,
			);
			$this->CI->Member_model->update($mem_id, $updatedata);

			return $sum;
		}

		return true;
	}
}
