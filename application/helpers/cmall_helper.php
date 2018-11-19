<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Cmall helper
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */


/**
 * 게시물 열람 페이지 주소를 return 합니다
 */
if ( ! function_exists('cmall_item_url')) {
	function cmall_item_url($url = '')
	{
		$url = trim($url, '/');
		$itemurl = site_url(config_item('uri_segment_cmall_item') . '/' . $url);
		return $itemurl;
	}
}

/**
 * 임시 저장된 주문 데이터를 serialize인지 base64_encode 인지 체크하여 배열로 리턴합니다.
 */
if ( ! function_exists('cmall_tmp_replace_data')) {
	function cmall_tmp_replace_data($data)
	{
		$result = is_serialized($data) ? unserialize($data) : unserialize(base64_decode($data));
		return $result;
	}
}

//주문데이터 가져오기
if ( ! function_exists('get_cmall_order_data')) {
	function get_cmall_order_data($order_no, $is_cache=true)
	{
		static $cache = array();

		if( $is_cache && isset($cache[$order_no]) ){
			return $cache[$order_no];
		}

		$CI = & get_instance();

		$CI->load->model('Cmall_order_model');

		$where = array(
			'cor_id' => $order_no
		);
		$cache[$order_no] = $order = $CI->Cmall_order_model->get_one('', '', $where);

		return $order;
	}
}

if ( ! function_exists('get_cmall_key_localize')) {
	function get_cmall_key_localize(){

		$keys = array(
			'order'		=> '주문',	//주문
			'deposit'	=> '입금', //입금
			'cancel'	=> '취소', //취소
		);

		return $keys;

	}
}

if ( ! function_exists('cmall_print_stype_names')) {
	function cmall_print_stype_names($key, $print=false){
		$key = strtolower($key);
		$tmps = get_cmall_key_localize();

		if( $print ){	//출력한다면
			if( array_key_exists($key, $tmps) ){
				echo $tmps[$key];
			} else {
				echo $key;
			}
		} else {
			if( array_key_exists($key, $tmps) ){
				return $tmps[$key];
			} else {
				return $key;
			}
		}
	}
}

if ( ! function_exists('cmall_get_stype_names')) {
	function cmall_get_stype_names($str, $index=null, $array_return=false){
		$tmps = get_cmall_key_localize();

		$key = array_search($str, $tmps);

		if ( $key !== false ){
			return $key;
		}

		return $str;
	}
}

if ( ! function_exists('check_datetime') ){
	// 일자 시간을 검사한다.
	function check_datetime($datetime)
	{
		if ($datetime == "0000-00-00 00:00:00")
			return true;

		$year		= substr($datetime, 0, 4);
		$month		= substr($datetime, 5, 2);
		$day		= substr($datetime, 8, 2);
		$hour		= substr($datetime, 11, 2);
		$minute		= substr($datetime, 14, 2);
		$second		= substr($datetime, 17, 2);

		$timestamp = mktime($hour, $minute, $second, $month, $day, $year);

		$tmp_datetime = date("Y-m-d H:i:s", $timestamp);
		if ($datetime == $tmp_datetime)
			return true;
		else
			return false;
	}
}

if ( ! function_exists('get_cmall_order_amounts')) {

	function get_cmall_order_amounts($order_no){

		$CI = & get_instance();

		$od = get_cmall_order_data( $order_no );

		if( ! element('cor_id', $od) ){
			return false;
		}

		$info = array();

		// 주문금액정보

		$CI->load->model(array('Cmall_order_model', 'Cmall_item_model', 'Cmall_order_detail_model'));

		$orderdetail = $CI->Cmall_order_detail_model->get_by_item(element('cor_id', $od));

		$od_cancel_price = 0;
		$od_total_price = 0;
		$od_cash_price = 0;

		if ($orderdetail) {
			foreach ($orderdetail as $okey => $oval) {

				$orderdetail[$okey]['item'] = $item
							= $CI->Cmall_item_model->get_one(element('cit_id', $oval));

				$orderdetail[$okey]['itemdetail'] = $itemdetail
							= $CI->Cmall_order_detail_model->get_detail_by_item(element('cor_id', $od), element('cit_id', $oval));

				$orderdetail[$okey]['item']['possible_download'] = 1;

				if (element('cod_download_days', element(0, $itemdetail))) {
					$endtimestamp = strtotime(element('cor_approve_datetime', $val))
						+ 86400 * element('cod_download_days', element(0, $itemdetail));
					$orderdetail[$okey]['item']['download_end_date'] = $enddate = cdate('Y-m-d', $endtimestamp);

					$orderdetail[$okey]['item']['possible_download'] = ($enddate >= date('Y-m-d')) ? 1 : 0;
				}

				foreach( $itemdetail as $detail ){

					$cod_status = element('cod_status', $detail);

					if( in_array( $cod_status, array('cancel') ) ){	//주문취소
						$od_cancel_price += ((int) element('cit_price', $item) + (int) element('cde_price', $detail)) * element('cod_count', $detail);
					} else if ( $cod_status === 'deposit' ) {
						$od_cash_price += ((int) element('cit_price', $item) + (int) element('cde_price', $detail)) * element('cod_count', $detail);
					}

					$od_total_price += ((int) element('cit_price', $item) + (int) element('cde_price', $detail)) * element('cod_count', $detail);
				}

			}	//end foreach
		}	//end if $orderdetail

		$info['od_total_price']	= $od_total_price;	//총 요청 금액
		$info['od_cash_price']	= $od_cash_price;	//입금된 금액
		$info['od_cancel_price']	= $od_cancel_price;	//취소된 금액

		return $info;

	}	//end function

}

if ( ! function_exists('exists_inicis_cmall_order')) {

	function exists_inicis_cmall_order($order_no, $pp=array(), $od_time=''){

		$CI = & get_instance();

		$CI->session->set_userdata('unique_id', '');
		$CI->session->set_userdata('order_cct_id', '');

		$CI->load->model('Payment_inicis_log_model');
		$CI->Payment_inicis_log_model->delete($oid);		//임시 저장 삭제

		redirect('cmall/orderresult/' . $order_no);

	}

}