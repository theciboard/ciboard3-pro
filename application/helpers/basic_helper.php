<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Baisc helper
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * print_r2
 */
if ( ! function_exists('print_r2')) {
	function print_r2($var)
	{
		ob_start();
		print_r($var);
		$str = ob_get_contents();
		ob_end_clean();
		$str = str_replace(" ", "&nbsp;", $str);
		echo nl2br("<span style='font-family:Tahoma, 굴림; font-size:9pt;'>$str</span>");
	}
}


/**
 * Alert 띄우기
 */
if ( ! function_exists('alert')) {
	function alert($msg = '', $url = '')
	{
		if (empty($msg)) {
			$msg = '잘못된 접근입니다';
		}
		echo '<meta http-equiv="content-type" content="text/html; charset=' . config_item('charset') . '">';
		echo '<script type="text/javascript">alert("' . $msg . '");';
		if (empty($url)) {
			echo 'history.go(-1);';
		}
		if ($url) {
			echo 'document.location.href="' . $url . '"';
		}
		echo '</script>';
		exit;
	}
}


/**
 * Alert 후 창 닫음
 */
if ( ! function_exists('alert_close')) {
	function alert_close($msg = '')
	{
		if (empty($msg)) {
			$msg = '잘못된 접근입니다';
		}
		echo '<meta http-equiv="content-type" content="text/html; charset=' . config_item('charset') . '">';
		echo '<script type="text/javascript"> alert("' . $msg . '"); window.close(); </script>';
		exit;
	}
}


/**
 * Alert 후 부모창 새로고침 후 창 닫음
 */
if ( ! function_exists('alert_refresh_close')) {
	function alert_refresh_close($msg = '')
	{
		if (empty($msg)) {
			$msg = '잘못된 접근입니다';
		}
		echo '<meta http-equiv="content-type" content="text/html; charset=' . config_item('charset') . '">';
		echo '<script type="text/javascript"> alert("' . $msg . '"); window.opener.location.reload();window.close(); </script>';
		exit;
	}
}


/**
 * DATE 함수의 약간 변형
 */
if ( ! function_exists('cdate')) {
	function cdate($date, $timestamp = '')
	{
		defined('TIMESTAMP') or define('TIMESTAMP', time());
		return $timestamp ? date($date, $timestamp) : date($date, TIMESTAMP);
	}
}


/**
 * TIMESTAMP 불러오기
 */
if ( ! function_exists('ctimestamp')) {
	function ctimestamp()
	{
		defined('TIMESTAMP') or define('TIMESTAMP', time());
		return TIMESTAMP;
	}
}


/**
 * 초를 사람이 쉽게 읽을 수 있는 시간으로 변환하기
 */
if ( ! function_exists('seconds2human')) {
	function seconds2human($second = 0)
	{
		$second = (int) $second;
		$s = $second%60;
		$m = floor(($second % 3600)/60);
		$h = floor(($second % 86400)/3600);
		$d = floor($second / 86400);

		$return = '';
		if ($d) {
			$return .= $d . " 일 ";
		}
		if ($h) {
			$return .= $h . " 시간 ";
		}
		if ($m) {
			$return .= $m . " 분 ";
		}
		if ($s) {
			$return .= $s . " 초";
		}
		$return = trim($return);

		return $return;
	}
}


if ( ! function_exists('array_to_keys')) {
	function array_to_keys($array = '')
	{
		$result = array();
		if ( ! is_array($array)) {
			return false;
		}
		foreach ($array as $key) {
			$result[$key] = false;
		}
		return $result;
	}
}


/**
 * 검색 select option
 */
if ( ! function_exists('search_option')) {
	function search_option($options = '', $selected = '')
	{
		if (empty($options) OR ! is_array($options)) {
			return false;
		}

		$result = '';
		foreach ($options as $key => $val) {
			$result .= '<option value="' . $key . '" ';
			if ($selected === $key) {
				$result .= ' selected="selected" ';
			}
			$result .= ' >' . $val . '</option>';
		}
		return $result;
	}
}


/**
 * 글자자르기
 */
if ( ! function_exists('cut_str')) {
	function cut_str($str = '', $len = '', $suffix = '…')
	{
		$arr_str = preg_split("//u", $str, -1, PREG_SPLIT_NO_EMPTY);
		$str_len = count($arr_str);

		if ($str_len >= $len) {
			$slice_str = array_slice($arr_str, 0, $len);
			$str = join('', $slice_str);
			return $str . ($str_len > $len ? $suffix : '');
		} else {
			$str = join('', $arr_str);
			return $str;
		}
	}
}


/**
 * ALERT MESSAGE 가 있을 경우 html 으로 감싸서 보여주기
 */
if ( ! function_exists('show_alert_message')) {
	function show_alert_message($message = '', $html1 = '', $html2 = '')
	{
		if (empty($message)) {
			return false;
		}

		$result = $html1 . $message . $html2;
		return $result;
	}
}


/**
 * 스킨 디렉토리 검색
 */
if ( ! function_exists('get_skin_name')) {
	function get_skin_name($skin_path = '', $selected_skin = '', $default_text = '', $dir = VIEW_DIR)
	{
		$result = '';

		if ($dir) {
			$dir = rtrim($dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
		}
		if ($default_text) {
			$result .= '<option value="">' . $default_text . '</option>';
		}

		$skin_dir = array();
		$dirname = $dir . $skin_path . '/';

		if (is_dir($dir . $skin_path) === false) {
			return;
		}

		$handle = opendir($dirname);
		while ($file = readdir($handle)) {
			if ($file === '.' OR $file === '..') {
				continue;
			}

			if (is_dir($dirname . $file)) {
				$skin_dir[] = $file;
			}
		}
		closedir($handle);
		sort($skin_dir);

		foreach ($skin_dir as $row) {
			$option = $row;
			if (strlen($option) > 10) {
				$option = substr($row, 0, 18) . '…';
			}

			$slt = ($selected_skin === $row) ? 'selected="selected"' : '';
			$result .= '<option value="' . $row . '" ' . $slt . '>' . $option . '</option>';
		}

		return $result;
	}
}


/**
 * 권한관리 페이지에 보이는 셀렉트박스
 */
if ( ! function_exists('get_access_selectbox')) {
	function get_access_selectbox($config = '', $memberonly = '')
	{
		if (empty($config)) {
			return false;
		}

		$show_level_array = array('3', '4', '5');
		$show_group_array = array('2', '4', '5');
		$replace = array('[', ']');

		$result = '';
		$result .= '<select name="' . element('column_name', $config) . '" class="form-control" >';

		if (empty($memberonly)) {
			$result .= '<option value=""';
			$result .= (element('column_value', $config) === '') ? 'selected="selected"' : '';
			$result .= '>모든 사용자</option>';
		}

		$result .= '<option value="1"';
		$result .= element('column_value', $config) === '1' ? 'selected="selected"' : '';
		$result .= '>로그인 사용자</option>';

		$result .= '<option value="100"';
		$result .= element('column_value', $config) === '100' ? 'selected="selected"' : '';
		$result .= '>관리자</option>';

		$result .= '<option value="2"';
		$result .= element('column_value', $config) === '2' ? 'selected="selected"' : '';
		$result .= '>특정그룹사용자</option>';

		$result .= '<option value="3"';
		$result .= element('column_value', $config) === '3' ? 'selected="selected"' : '';
		$result .= '>특정레벨이상인자</option>';

		$result .= '<option value="4"';
		$result .= element('column_value', $config) === '4' ? 'selected="selected"' : '';
		$result .= '>특정그룹 OR 특정레벨</option>';

		$result .= '<option value="5"';
		$result .= element('column_value', $config) === '5' ? 'selected="selected"' : '';
		$result .= '>특정그룹 AND 특정레벨</option>';

		$result .= '</select>';

		$result .= '<span id="' . str_replace($replace, '_', element('column_level_name', $config)) . '" style="';
		$result .= in_array(element('column_value', $config), $show_level_array)
			? 'display:inline;' : 'display:none;';

		$result .= '">';
		$result .= '<select name="'
			. element('column_level_name', $config)
			. '" class="form-control">';

		for ($level = 1; $level <= element('max_level', $config); $level++) {
			$result .= '<option value="' . $level . '" ';
			$result .= (int) element('column_level_value', $config) === (int) $level ? 'selected="selected"' : '';
			$result .= ' >' . $level . '</option>';
		}
		$result .= '</select> 레벨 이상인자 </span>';

		$result .= '<div id="' . str_replace($replace, '_', element('column_group_name', $config)) . '" style="';
		$result .= in_array(element('column_value', $config), $show_group_array)
			? 'display:block;' : 'display:none;';

		$result .= '">';

		$mgroup = element('mgroup', $config);
		$group_value = json_decode(element('column_group_value', $config), true);
		if (element('list', $mgroup)) {
			foreach (element('list', $mgroup) as $key => $value) {
				$result .= '	<label class="checkbox-inline">
					<input type="checkbox" name="'
					. element('column_group_name', $config)
					. '[]" value="' . element('mgr_id', $value) . '" ';
				$result .= is_array($group_value) && in_array(element('mgr_id', $value), $group_value)
					? 'checked="checked"' : '';

				$result .= ' /> ' . element('mgr_title', $value) . '</label>';
			}
		}

		$result .= '</div>';
		$result .= '<script type="text/javascript">';
		$result .= '$(function() {
			$(document).on("change", "select[name=\'' . element('column_name', $config) . '\']", function() {';
				$result .= 'if ($(this).val() == "2" || $(this).val() == "4" || $(this).val() == "5") {';
					$result .= '$("#' . str_replace($replace, '_', element('column_group_name', $config)) . '").css("display", "block");';
				$result .= '} else {';
					$result .= '$("#' . str_replace($replace, '_', element('column_group_name', $config)) . '").css("display", "none");';
				$result .= '}';
				$result .= 'if ($(this).val() == "3" || $(this).val() == "4" || $(this).val() == "5") {';
					$result .= '$("#' . str_replace($replace, '_', element('column_level_name', $config)) . '").css("display", "inline");';
				$result .= '} else {';
					$result .= '$("#' . str_replace($replace, '_', element('column_level_name', $config)) . '").css("display", "none");';
				$result .= '}';

			$result .= '})
		});';
		$result .= '</script>';

		return $result;
	}
}


/**
 * 로그인한 회원만 접근이 가능합니다
 */
if ( ! function_exists('required_user_login')) {
	function required_user_login($type = '')
	{
		$CI =& get_instance();
		if ($CI->member->is_member() === false) {
			if ($type === 'alert') {
				alert_close('로그인 후 이용이 가능합니다');
			} else {
				$CI->session->set_flashdata(
					'message',
					'로그인 후 이용이 가능합니다'
				);
				redirect('login?url=' . urlencode(current_full_url()));
			}
		}
		return true;
	}
}


/**
 * ip 를 정한 형식에 따라 보여주기
 */
if ( ! function_exists('display_ipaddress')) {
	function display_ipaddress($ip = '', $type = '0001')
	{
		$len = strlen($type);
		if ($len !== 4) {
			return false;
		}
		if (empty($ip)) {
			return false;
		}

		$regex = '';
		$regex .= ($type[0] === '1') ? '\\1' : '&#9825;';
		$regex .= '.';
		$regex .= ($type[1] === '1') ? '\\2' : '&#9825;';
		$regex .= '.';
		$regex .= ($type[2] === '1') ? '\\3' : '&#9825;';
		$regex .= '.';
		$regex .= ($type[3] === '1') ? '\\4' : '&#9825;';

		return preg_replace("/([0-9]+).([0-9]+).([0-9]+).([0-9]+)/", $regex, $ip);
	}
}


/**
 * 관리자에게 보여주는 IP 팝업버튼
 */
if ( ! function_exists('display_admin_ip')) {
	function display_admin_ip($ip = '')
	{
		if (empty($ip)) {
			return false;
		}
		$CI = & get_instance();
		if ($CI->member->is_admin() !== 'super') {
			return;
		}

		return $ip;
	}
}


/**
 * 회원닉네임을 사이드뷰와 함께 출력
 */
if ( ! function_exists('display_username')) {
	function display_username($userid = '', $name = '', $icon = '', $use_sideview = '')
	{
		$CI = & get_instance();
		$name = $name ? html_escape($name) : '비회원';
		$title = $userid ? '[' . $userid . ']' : '[비회원]';

		$_use_sideview = ($CI->cbconfig->get_device_view_type() === 'mobile')
			? $CI->cbconfig->item('use_mobile_sideview')
			: $CI->cbconfig->item('use_sideview');

		$result = '';
		if ($use_sideview) {
			if ($use_sideview === 'Y' && $userid) {
				$result .= '<a href="javascript:;"
					onClick="getSideView(this, \'' . $userid . '\');"
					title="' . $title . $name . '" style="text-decoration:none;">';
			}
		} elseif ($_use_sideview && $userid) {
			$result .= '<a href="javascript:;"
				onClick="getSideView(this, \'' . $userid . '\');"
				title="' . $title . $name . '" style="text-decoration:none;">';
		}
		if ($CI->cbconfig->item('use_member_icon') && $icon) {
			$width = $CI->cbconfig->item('member_icon_width');
			$height = $CI->cbconfig->item('member_icon_height');
			$result .= '<img src="'
				. member_icon_url($icon) . '" alt="icon" class="member-icon"
				width="' . $width . '" height="' . $height . '" /> ';
		}

		$result .= $name;

		if ($use_sideview) {
			if ($use_sideview === 'Y' && $userid) {
				$result .= '</a>';
			}
		} elseif ($_use_sideview && $userid) {
			$result .= '</a>';
		}

		return $result;
	}
}


/**
 * 성인인지 여부
 */
if ( ! function_exists('is_adult')) {
	function is_adult($birthday = '')
	{
		$birthday = str_replace('-', '', $birthday);
		if (strlen($birthday) !== 8) return false;
		if ( ! is_numeric($birthday)) return false;

		$adult_day = date("Ymd", strtotime("-19 years", ctimestamp()));
		$is_adult = ($birthday < $adult_day) ? true : false;

		return $is_adult;
	}
}


/**
 * 회원 사진 가져오기
 */
if ( ! function_exists('member_photo_url')) {
	function member_photo_url($img = '', $width = '', $height = '')
	{
		$CI = & get_instance();
		if (empty($img)) {
			return false;
		}
		is_numeric($width) OR $width = $CI->cbconfig->item('member_photo_width');
		is_numeric($height) OR $height = $CI->cbconfig->item('member_photo_height');

		return thumb_url('member_photo', $img, $width, $height);
	}
}


/**
 * 회원 아이콘 가져오기
 */
if ( ! function_exists('member_icon_url')) {
	function member_icon_url($img = '', $width = '', $height = '')
	{
		$CI = & get_instance();
		if (empty($img)) {
			return false;
		}
		is_numeric($width) OR $width = $CI->cbconfig->item('member_icon_width');
		is_numeric($height) OR $height = $CI->cbconfig->item('member_icon_height');

		return thumb_url('member_icon', $img, $width, $height);
	}
}


/**
 * 배너 이미지 가져오기
 */
if ( ! function_exists('banner_image_url')) {
	function banner_image_url($img = '', $width = '', $height = '')
	{
		if (empty($img)) {
			return false;
		}
		is_numeric($width) OR $width = '';
		is_numeric($height) OR $height = '';

		return thumb_url('banner', $img, $width, $height);
	}
}


/**
 * 배너 출력하기
 */
if ( ! function_exists('banner')) {
	function banner($position = '', $type = 'rand', $limit = 1, $start_tag = '', $end_tag = '')
	{

		/**
		 * 배너 함수 사용법
		 * banner('위치명', '배너보여주는방식', '보여줄 배너 개수', '각 배너 시작전 html 태그', '각 배너 끝난후에 html 태그')
		 *
		 * type 의 종류
		 * rand : 같은 위치에 여러 배너를 올렸을 경우, limit 에서 정한 개수를 랜덤으로 보여줍니다
		 * order : 같은 위치에 여러 배너를 올렸을 경우, limit 에서 정한 개수를
		 * order 값(관리자페이지에서 정한값)이 큰 순으로 보여줍니다
		 *
		 * limit : 보여줄 배너 개수입니다
		 *
		 * start_tag, end_tag : 각 배너의 시작과 끝에 html 태그를 삽입합니다
		 * 즉 2개의 배너를 start_tag 와 end_tag 와 함께 사용하면 아래와 같은 태그를 리턴합니다
		 * {start_tag}<a href="첫번째배너링크"><img src="첫번재배너이미지"></a>{end_tag}
		 * {start_tag}<a href="두번째배너링크"><img src="두번재배너이미지"></a>{end_tag}
		 *
		 */

		$CI = & get_instance();

		if (empty($position)) {
			return;
		}
		if ($type !== 'order') {
			$type = 'rand';
		}

		$html = '';

		$CI->load->model('Banner_model');
		$result = $CI->Banner_model->get_banner($position, $type, $limit);

		if ($result) {
			foreach ($result as $key => $val) {
				if ($CI->cbconfig->get_device_view_type() === 'mobile'
					&& element('ban_device', $val) === 'pc') {
					continue;
				}
				if ($CI->cbconfig->get_device_view_type() !== 'mobile'
					&& element('ban_device', $val) === 'mobile') {
					continue;
				}
				if (element('ban_image', $val)) {

					$html .= $start_tag;

					if (element('ban_url', $val)) {
						$html .= '<a href="' . site_url('gotourl/banner/' . element('ban_id', $val)) . '" ';
						if (element('ban_target', $val)) {
							$html .= ' target="_blank" ';
						}
						$html .= ' title="' . html_escape(element('ban_title', $val)) . '" ';
						$html .= ' >';
					}

					$html .= '<img src="'
						. thumb_url(
							'banner',
							element('ban_image', $val),
							element('ban_width', $val),
							element('ban_height', $val)
						)
						. '" class="cb_banner" id="cb_banner_' . element('ban_id', $val) . '" '
						. ' alt="' . html_escape(element('ban_title', $val))
						. '" title="' . html_escape(element('ban_title', $val)) . '" />';
					if (element('ban_url', $val)) {
						$html .= '</a>';
					}
					$html .= $end_tag;
				}
			}
		}

		return $html;
	}
}


/**
 * 본문 가져오기
 */
if ( ! function_exists('display_html_content')) {
	function display_html_content($content = '', $html = '', $thumb_width=700, $autolink = false, $popup = false, $writer_is_admin = false)
	{
		$phpversion = phpversion();
		if (empty($html)) {
			$content = nl2br(html_escape($content));
			if ($autolink) {
				$content = url_auto_link($content, $popup);
			}
			$content = preg_replace(
				"/\[<a\s*href\=\"(http|https|ftp)\:\/\/([^[:space:]]+)\.(gif|png|jpg|jpeg|bmp).*<\/a>(\s\]|\]|)/i",
				"<img src=\"$1://$2.$3\" alt=\"\" style=\"max-width:100%;border:0;\">",
				$content
			);
			if (version_compare($phpversion, '7.2.0') >= 0) {

			} else {
				$content = preg_replace_callback(
					"/{지도\:([^}]*)}/is",
					create_function('$match', '
						 global $thumb_width;
						 return get_google_map($match[1], $thumb_width);
					'),
					$content
				); // Google Map
			}


			return $content;
		}

		$source = array();
		$target = array();

		$source[] = '//';
		$target[] = '';

		$source[] = "/<\?xml:namespace prefix = o ns = \"urn:schemas-microsoft-com:office:office\" \/>/";
		$target[] = '';

		// 테이블 태그의 갯수를 세어 테이블이 깨지지 않도록 한다.
		$table_begin_count = substr_count(strtolower($content), '<table');
		$table_end_count = substr_count(strtolower($content), '</table');
		for ($i = $table_end_count; $i < $table_begin_count; $i++) {
			$content .= '</table>';
		}

		$content = preg_replace($source, $target, $content);

		if ($autolink) {
			$content = url_auto_link($content, $popup);
		}

		//if ($writer_is_admin === false) {
			$content = html_purifier($content);
		//}

		$content = get_view_thumbnail($content, $thumb_width);

		if (version_compare($phpversion, '7.2.0') >= 0) {

		} else {
			$content = preg_replace_callback(
				"/{&#51648;&#46020;\:([^}]*)}/is",
				create_function('$match', '
					 global $thumb_width;
					 return get_google_map($match[1], $thumb_width);
				'),
				$content
			); // Google Map
		}

		return $content;
	}
}


/*
 * http://htmlpurifier.org/
 * Standards-Compliant HTML Filtering
 * Safe : HTML Purifier defeats XSS with an audited whitelist
 * Clean : HTML Purifier ensures standards-compliant output
 * Open : HTML Purifier is open-source and highly customizable
 */
if ( ! function_exists('html_purifier')) {
	function html_purifier($html)
	{
		$CI = & get_instance();

		$white_iframe = $CI->cbconfig->item('white_iframe');;
		$white_iframe = preg_replace("/[\r|\n|\r\n]+/", ",", $white_iframe);
		$white_iframe = preg_replace("/\s+/", "", $white_iframe);
		if ($white_iframe) {
			$white_iframe = explode(',', trim($white_iframe, ','));
			$white_iframe = array_unique($white_iframe);
		}
		$domains = array();
		if ($white_iframe) {
			foreach ($white_iframe as $domain) {
				$domain = trim($domain);
				if ($domain) {
					array_push($domains, $domain);
				}
			}
		}
		// 내 도메인도 추가
		array_push($domains, $CI->input->server('HTTP_HOST') . '/');
		$safeiframe = implode('|', $domains);

		if ( ! defined('INC_HTMLPurifier')) {
			include_once(FCPATH . 'plugin/htmlpurifier/HTMLPurifier.standalone.php');
			define('INC_HTMLPurifier', true);
		}
		$config = HTMLPurifier_Config::createDefault();
		// cache 디렉토리에 CSS, HTML, URI 디렉토리 등을 만든다.

		$cache_path = config_item('cache_path') ? config_item('cache_path') : APPPATH . 'cache/';

		$config->set('Cache.SerializerPath', $cache_path);
		$config->set('HTML.SafeEmbed', false);
		$config->set('HTML.SafeObject', false);
		$config->set('HTML.SafeIframe', true);
		$config->set('URI.SafeIframeRegexp','%^(https?:)?//(' . $safeiframe . ')%');
		$config->set('Attr.AllowedFrameTargets', array('_blank'));
		$config->set('Core.Encoding', 'utf-8');
		$config->set('Core.EscapeNonASCIICharacters', true);
		$config->set('HTML.MaxImgLength', null);
		$config->set('CSS.MaxImgLength', null);
		$purifier = new HTMLPurifier($config);

		return $purifier->purify($html);
	}
}


/**
 * URL 자동 링크 생성
 */
if ( ! function_exists('url_auto_link')) {
	function url_auto_link($str = '', $popup = false)
	{
		if (empty($str)) {
			return false;
		}
		$target = $popup ? 'target="_blank"' : '';
		$str = str_replace(
			array("&lt;", "&gt;", "&amp;", "&quot;", "&nbsp;", "&#039;"),
			array("\t_lt_\t", "\t_gt_\t", "&", "\"", "\t_nbsp_\t", "'"),
			$str
		);
		$str = preg_replace(
			"/([^(href=\"?'?)|(src=\"?'?)]|\(|^)((http|https|ftp|telnet|news|mms):\/\/[a-zA-Z0-9\.-]+\.[가-힣\xA1-\xFEa-zA-Z0-9\.:&#=_\?\/~\+%@;\-\|\,\(\)]+)/i",
			"\\1<a href=\"\\2\" {$target}>\\2</A>",
			$str
		);
		$str = preg_replace(
			"/(^|[\"'\s(])(www\.[^\"'\s()]+)/i",
			"\\1<a href=\"http://\\2\" {$target}>\\2</A>",
			$str
		);
		$str = preg_replace(
			"/[0-9a-z_-]+@[a-z0-9._-]{4,}/i",
			"<a href=\"mailto:\\0\">\\0</a>",
			$str
		);
		$str = str_replace(
			array("\t_nbsp_\t", "\t_lt_\t", "\t_gt_\t", "'"),
			array("&nbsp;", "&lt;", "&gt;", "&#039;"),
			$str
		);
		return $str;
	}
}


/**
 * syntax highlight
 */
if ( ! function_exists('content_syntaxhighlighter')) {
	function content_syntaxhighlighter($m)
	{
		$str = $m[3];

		if (empty($str)) {
			return;
		}

		$str = str_replace(
			array("<br>", "<br/>", "<br />", "<div>", "</div>", "<p>", "</p>", "&nbsp;"),
			"",
			$str
		);
		$target = array("/</", "/>/", "/\"/", "/\'/");
		$source = array("&lt;", "&gt;", "&#034;", "&#039;");

		$str = preg_replace($target, $source, $str);

		if (empty($str)) {
			return;
		}

		$brush = strtolower(trim($m[2]));
		$brush_arr = array('css', 'js', 'jscript', 'javascript', 'php', 'xml', 'xhtml', 'xslt', 'html');
		$brush = ($brush && in_array($brush, $brush_arr)) ? $brush : 'html';

		return '<pre class="brush: ' . $brush . ';">' . $str . '</pre>' . PHP_EOL;
	}
}


/**
 * syntax highlight
 */
if ( ! function_exists('content_syntaxhighlighter_html')) {
	function content_syntaxhighlighter_html($m)
	{
		$str = $m[3];

		if (empty($str)) {
			return;
		}

		$str = str_replace(
			array("\n\r", "\r"),
			array("\n"),
			$str
		);
		$str = str_replace("\n", "", $str);
		$str = str_replace(
			array("<br>", "<br/>", "<br />", "<div>", "</div>", "<p>", "</p>", "&nbsp;"),
			array("\n", "\n", "\n", "\n", "", "\n", "", "\t"),
			$str
		);
		$target = array("/<span[^>]+>/i", "/<\/span>/i", "/</", "/>/", "/\"/", "/\'/");
		$source = array("", "", "&lt;", "&gt;", "&#034;", "&#039;");

		$str = preg_replace($target, $source, $str);

		if (empty($str)) {
			return;
		}

		$brush = strtolower(trim($m[2]));
		$brush_arr = array('css', 'js', 'jscript', 'javascript', 'php', 'xml', 'xhtml', 'xslt', 'html');
		$brush = ($brush && in_array($brush, $brush_arr)) ? $brush : 'html';

		return '<pre class="brush: ' . $brush . ';">' . $str . '</pre>' . PHP_EOL;
	}
}


if ( ! function_exists('change_key_case')) {
	function change_key_case($str)
	{
		$str = stripcslashes($str);
		preg_match_all('@(?P<attribute>[^\s\'\"]+)\s*=\s*(\'|\")?(?P<value>[^\s\'\"]+)(\'|\")?@i', $str, $match);
		$value = @array_change_key_case(array_combine($match['attribute'], $match['value']));

		return $value;
	}
}


/**
 * Google Map
 */
if ( ! function_exists('get_google_map')) {
	function get_google_map($geo_data = '', $maxwidth = '')
	{
		if (empty($geo_data)) {
			return;
		}

		$maxwidth = (int) $maxwidth;
		if (empty($maxwidth)) {
			$maxwidth = 700;
		}

		$geo_data = stripslashes($geo_data);
		$geo_data = str_replace('&quot;', '', $geo_data);

		if (empty($geo_data)) {
			return;
		}

		$map = array();
		$map = change_key_case($geo_data);

		if (isset($map['loc'])) {
			list($lat, $lng) = explode(',', element('loc', $map));
			$zoom = element('z', $map);
		} else {
			list($lat, $lng, $zoom) = explode(',', element('geo', $map));
		}

		if (empty($lat) OR empty($lng)) {
			return;
		}

		//Map
		$map['geo'] = $lat . ',' . $lng . ',' . $zoom;

		//Marker
		preg_match("/m=\"([^\"]*)\"/is", $geo_data, $marker);
		$map['m'] = element(1, $marker);

		$google_map = '<div style="width:100%; margin:0 auto 15px; max-width:'
			. $maxwidth . 'px;">' . PHP_EOL;
		$google_map .= '<iframe width="100%" height="480" src="'
			. site_url('helptool/googlemap?geo=' . urlencode($map['geo'])
			. '&marker=' . urlencode($map['m']))
			. '" frameborder="0" scrolling="no"></iframe>' . PHP_EOL;
		$google_map .= '</div>' . PHP_EOL;

		return $google_map;
	}
}


/**
 * 게시글보기 썸네일 생성
 */
if ( ! function_exists('get_view_thumbnail')) {
	function get_view_thumbnail($contents = '', $thumb_width= 0)
	{
		if (empty($contents)) {
			return false;
		}

		$CI = & get_instance();

		if (empty($thumb_width)) {
			$thumb_width = 700;
		}

		// $contents 중 img 태그 추출
		$matches = get_editor_image($contents, true);

		if (empty($matches)) {
			return $contents;
		}

		$end = count(element(1, $matches));
		for ($i = 0; $i < $end; $i++) {

			$img = $matches[1][$i];
			preg_match("/src=[\'\"]?([^>\'\"]+[^>\'\"]+)/i", $img, $m);
			$src = isset($m[1]) ? $m[1] : '';
			preg_match("/style=[\"\']?([^\"\'>]+)/i", $img, $m);
			$style = isset($m[1]) ? $m[1] : '';
			preg_match("/width:\s*(\d+)px/", $style, $m);
			$width = isset($m[1]) ? $m[1] : '';
			preg_match("/height:\s*(\d+)px/", $style, $m);
			$height = isset($m[1]) ? $m[1] : '';
			preg_match("/alt=[\"\']?([^\"\']*)[\"\']?/", $img, $m);
			$alt = isset($m[1]) ? html_escape($m[1]) : '';
			if (empty($width)) {
				preg_match("/width=[\"\']?([^\"\']*)[\"\']?/", $img, $m);
				$width = isset($m[1]) ? html_escape($m[1]) : '';
			}
			if (empty($height)) {
				preg_match("/height=[\"\']?([^\"\']*)[\"\']?/", $img, $m);
				$height = isset($m[1]) ? html_escape($m[1]) : '';
			}

			// 이미지 path 구함
			$p = parse_url($src);
			if (isset($p['host']) && $p['host'] === $CI->input->server('HTTP_HOST')
				&& strpos($p['path'], '/' . config_item('uploads_dir') . '/editor/') !== false) {
				$thumb_tag = '<img src="' . thumb_url('editor', str_replace(site_url(config_item('uploads_dir') . '/editor') . '/', '', $src), $thumb_width) . '" ';
			} else {
				$thumb_tag = '<img src="' . $src . '" ';
			}
			if ($width) {
				$thumb_tag .= ' width="' . $width . '" ';
			}
			$thumb_tag .= 'alt="' . $alt . '" style="max-width:100%;"/>';

			$img_tag = $matches[0][$i];
			$contents = str_replace($img_tag, $thumb_tag, $contents);
			if ($width) {
				$thumb_tag .= ' width="' . $width . '" ';
			}
			$thumb_tag .= 'alt="' . $alt . '" style="max-width:100%;"/>';

			$img_tag = $matches[0][$i];
			$contents = str_replace($img_tag, $thumb_tag, $contents);
		}

		return $contents;
	}
}


/**
 * 에디터 이미지 1개 url 얻기
 */
if ( ! function_exists('get_post_image_url')) {
	function get_post_image_url($contents = '', $thumb_width = '', $thumb_height = '')
	{
		$CI = & get_instance();

		if (empty($contents)) {
			return;
		}

		$matches = get_editor_image($contents);
		if (empty($matches)) {
			return;
		}

		$img = element(0, element(1, $matches));
		if (empty($img)) {
			return;
		}

		preg_match("/src=[\'\"]?([^>\'\"]+[^>\'\"]+)/i", $img, $m);
		$src = isset($m[1]) ? $m[1] : '';

		$p = parse_url($src);
		if (isset($p['host']) && $p['host'] === $CI->input->server('HTTP_HOST')
			&& strpos($p['path'], '/' . config_item('uploads_dir') . '/editor/') !== false) {
			$src = thumb_url(
				'editor',
				str_replace(site_url(config_item('uploads_dir') . '/editor') . '/', '', $src),
				$thumb_width,
				$thumb_height
			);
		}
		return $src;
	}
}


/**
 * 에디터 이미지 얻기
 */
if ( ! function_exists('get_editor_image')) {
	function get_editor_image($contents = '', $view = true)
	{
		if (empty($contents)) {
			return false;
		}

		// $contents 중 img 태그 추출
		if ($view) {
			$pattern = "/<img([^>]*)>/iS";
		} else {
			$pattern = "/<img[^>]*src=[\'\"]?([^>\'\"]+[^>\'\"]+)[\'\"]?[^>]*>/i";
		}
		preg_match_all($pattern, $contents, $matchs);

		return $matchs;
	}
}


/**
 * 날짜 표시하기
 */
if ( ! function_exists('display_datetime')) {
	function display_datetime($datetime = '', $type = '', $custom = '')
	{
		if (empty($datetime)) {
			return false;
		}

		if ($type === 'sns') {

			$diff = ctimestamp() - strtotime($datetime);

			$s = 60; //1분 = 60초
			$h = $s * 60; //1시간 = 60분
			$d = $h * 24; //1일 = 24시간
			$y = $d * 10; //1년 = 1일 * 10일

			if ($diff < $s) {
				$result = $diff . '초전';
			} elseif ($h > $diff && $diff >= $s) {
				$result = round($diff/$s) . '분전';
			} elseif ($d > $diff && $diff >= $h) {
				$result = round($diff/$h) . '시간전';
			} elseif ($y > $diff && $diff >= $d) {
				$result = round($diff/$d) . '일전';
			} else {
				if (substr($datetime,0, 10) === cdate('Y-m-d')) {
					$result = str_replace('-', '.', substr($datetime,11,5));
				} else {
					$result = substr($datetime, 5, 5);
				}
			}
		} elseif ($type === 'user' && $custom) {
			return cdate($custom, strtotime($datetime));
		} elseif ($type === 'full') {
			if (substr($datetime,0, 10) === cdate('Y-m-d')) {
				$result = substr($datetime,11,5);
			} elseif (substr($datetime,0, 4) === cdate('Y')) {
				$result = substr($datetime,5,11);
			} else {
				$result = substr($datetime,0,10);
			}
		} else {
			if (substr($datetime,0, 10) === cdate('Y-m-d')) {
				$result = substr($datetime,11,5);
			} else {
				$result = substr($datetime,5,5);
			}
		}

		return $result;
	}
}


/**
 * 파일 확장자 얻기
 */
if ( ! function_exists('get_extension')) {
	function get_extension($filename)
	{
		$file = explode('.', basename($filename));
		$count = count($file);
		if ($count > 1) {
			return strtolower($file[$count-1]);
		} else {
			return '';
		}
	}
}


/**
 * get_sock 함수 대체
 */
if ( ! function_exists('get_sock')) {
	function get_sock($url)
	{
		// host 와 uri 를 분리
		$host = '';
		$get = '';

		if (preg_match("/http:\/\/([a-zA-Z0-9_\-\.]+)([^<]*)/", $url, $res)) {
			$host = $res[1];
			$get = $res[2];
		}

		// 80번 포트로 소캣접속 시도
		$fp = fsockopen ($host, 80, $errno, $errstr, 30);
		if (empty($fp)) {
			die($errstr . ' (' . $errno . ")\n");
		} else {
			fputs($fp, "GET $get HTTP/1.0\r\n");
			fputs($fp, "Host: $host\r\n");
			fputs($fp, "\r\n");

			$header = '';
			// header 와 content 를 분리한다.
			while (trim($buffer = fgets($fp,1024)) !== '') {
				$header .= $buffer;
			}
			while ( ! feof($fp)) {
				$buffer .= fgets($fp,1024);
			}
		}
		fclose($fp);

		// content 만 return 한다.
		return $buffer;
	}
}


/**
 * 핸드폰 번호 얻기
 */
if ( ! function_exists('get_phone')) {
	function get_phone($phone, $hyphen=1)
	{
		if (is_phone($phone) === false) {
			return '';
		}
		if ($hyphen) {
			$preg = "$1-$2-$3";
		} else {
			$preg = "$1$2$3";
		}

		$phone = str_replace('-', '', trim($phone));
		$phone = preg_replace(
			"/^(01[016789])([0-9]{3,4})([0-9]{4})$/",
			$preg,
			$phone
		);
		return $phone;
	}
}


/**
 * 핸드폰 번호인지 체크
 */
if ( ! function_exists('is_phone')) {
	function is_phone($phone)
	{
		$phone = str_replace('-', '', trim($phone));
		if (preg_match("/^(01[016789])([0-9]{3,4})([0-9]{4})$/", $phone)) {
			return true;
		} else {
			return false;
		}
	}
}


/**
 * json_encode
 */
if ( ! function_exists('json_encode')) {
	function json_encode($data)
	{
		$CI = & get_instance();
		$CI->load->library('Services_json');
		$json = new Services_JSON();
		return($json->encode($data));
	}
}


/**
 * json_decode
 */
if ( ! function_exists('json_decode')) {
	function json_decode($data, $output_mode = false)
	{
		$CI = & get_instance();
		$CI->load->library('Services_json');
		$param = $output_mode ? 16 : null;
		$json = new Services_JSON($param);
		return($json->decode($data));
	}
}


/**
 * 관리자 게시물 목록 한페이지에 보이는 게시물 수 저장
 */
if ( ! function_exists('admin_listnum')) {
	function admin_listnum()
	{
		$CI = & get_instance();
		if ($CI->input->get('listnum')
			&& is_numeric($CI->input->get('listnum'))
			&& $CI->input->get('listnum') > 0
			&& $CI->input->get('listnum') <= 1000) {

			$listnum = (int) $CI->input->get('listnum');
			$cookie_name = 'admin_listnum';
			$cookie_value = $listnum;
			$cookie_expire = 8640000;
			set_cookie($cookie_name, $cookie_value, $cookie_expire);

		} else {
			$cookienum = (int) get_cookie('admin_listnum');
			$listnum = $cookienum > 0 ? $cookienum : 20;
		}
		return $listnum;
	}
}


/**
 * 관리자 게시물 목록 한페이지에 보이는 게시물 수 지정하는 셀렉트 박스
 */
if ( ! function_exists('admin_listnum_selectbox')) {
	function admin_listnum_selectbox()
	{
		$CI = & get_instance();
		if ($CI->input->get('listnum')
			&& is_numeric($CI->input->get('listnum'))
			&& $CI->input->get('listnum') > 0
			&& $CI->input->get('listnum') <= 1000) {
			$listnum = $CI->input->get('listnum');
		} else {
			$listnum = get_cookie('admin_listnum')
				? get_cookie('admin_listnum') : '20';
		}
		$array = array('10', '15', '20', '25', '30', '40', '50', '60', '70', '100');

		$html = '<select name="listnum" class="form-control" onchange="location.href=\'' . current_url() . '?listnum=\' + this.value;">';
		$html .= '<option value="">선택</option>';

		foreach ($array as $val) {
			$html .= '<option value="' . $val . '" ';
			$html .= ((int) $listnum === (int) $val) ? ' selected="selected" ' : '';
			$html .= ' >' . $val . '</option>';
		}
		$html .= '</select>개씩 보기';

		return $html;
	}
}


/**
 * http://kr1.php.net/manual/en/function.curl-setopt-array.php 참고
 */
if ( ! function_exists('curl_setopt_array')) {
	function curl_setopt_array(&$ch, $curl_options)
	{
		foreach ($curl_options as $option => $value) {
			if ( ! curl_setopt($ch, $option, $value)) {
				return false;
			}
		}
		return true;
	}
}

if ( ! function_exists('is_serialized')) {
	function is_serialized($value, &$result = null)
	{
		// Bit of a give away this one
		if (!is_string($value))
		{
			return false;
		}
		// Serialized false, return true. unserialize() returns false on an
		// invalid string or it could return false if the string is serialized
		// false, eliminate that possibility.
		if ($value === 'b:0;')
		{
			$result = false;
			return true;
		}
		$length	= strlen($value);
		$end	= '';
		switch ($value[0])
		{
			case 's':
				if ($value[$length - 2] !== '"')
				{
					return false;
				}
			case 'b':
			case 'i':
			case 'd':
				// This looks odd but it is quicker than isset()ing
				$end .= ';';
			case 'a':
			case 'O':
				$end .= '}';
				if ($value[1] !== ':')
				{
					return false;
				}
				switch ($value[2])
				{
					case 0:
					case 1:
					case 2:
					case 3:
					case 4:
					case 5:
					case 6:
					case 7:
					case 8:
					case 9:
					break;
					default:
						return false;
				}
			case 'N':
				$end .= ';';
				if ($value[$length - 1] !== $end[0])
				{
					return false;
				}
			break;
			default:
				return false;
		}
		if (($result = @unserialize($value)) === false)
		{
			$result = null;
			return false;
		}
		return true;
	}
}

/**
 * Browscap 정보 얻기
 */
if ( ! function_exists('get_useragent_info')) {
	function get_useragent_info($useragent = '')
	{
		global $_browscap;

		if (empty($useragent)) {
			return false;
		}

		$result = array();

		if (config_item('user_agent_parser') === 'browscap'
			&& is_file(FCPATH . 'plugin/browscap/browscap_cache.php')) {

			if ( ! defined('CONSTANT_GET_USERAGENT_INFO')) {
				ini_set('memory_limit', '-1');
				require_once FCPATH . 'plugin/browscap/Browscap.php';
				$_browscap = new Browscap(FCPATH . 'plugin/browscap');
				$_browscap->updateMethod = 'cURL';
				$_browscap->doAutoUpdate = false;
				$_browscap->cacheFilename = 'browscap_cache.php';
			}
			$cap = $_browscap->getBrowser($useragent);
			$result['browsername'] = $cap->Browser;
			$result['browserversion'] = $cap->Version;
			$result['os'] = $cap->Platform;
			$result['engine'] = '';

		} else {

			if ( ! defined('CONSTANT_GET_USERAGENT_INFO')) {
				$CI = & get_instance();
				$CI->load->library(array('phpuseragentstringparser', 'phpuseragent'));
			}
			$userAgent = new phpUserAgent($useragent);
			$result['browsername'] = $userAgent->getBrowserName();
			$result['browserversion'] = $userAgent->getBrowserVersion();
			$result['os'] = $userAgent->getOperatingSystem();
			$result['engine'] = $userAgent->getEngine();

		}
		defined('CONSTANT_GET_USERAGENT_INFO') OR define('CONSTANT_GET_USERAGENT_INFO', 1);

		return $result;
	}
}

// 로그 폴더에 기록을 남깁니다. ( 디버그시 사용 )
if ( ! function_exists('test_write_log')) {
	function test_write_log($msg, $file_add='log', $is_export=false)
	{
		$log_path = APPPATH . 'logs';
		if( is_writeable($log_path) ){
			$file = $log_path."/test_".$file_add."_".date("Ymd").".php";

			if(!($fp = fopen($file, "a+"))) return 0;

			ob_start();
			if( $is_export ){
				echo var_export($msg, true);
			} else {
				print_r($msg);
			}
			$ob_msg = ob_get_contents();
			ob_clean();

			if(fwrite($fp, " ".$ob_msg."\n") === FALSE)
			{
				fclose($fp);
				return 0;
			}
			fclose($fp);
			return 1;
		}
	}
}

/**
 * cache 디렉토리에 해당 디렉토리가 생성되어 있는지 체크
 */
if ( ! function_exists('check_cache_dir')) {
	function check_cache_dir($dir = '')
	{

		$cache_path = config_item('cache_path') ? config_item('cache_path') : APPPATH . 'cache/';
		if ($dir) $cache_path .= '/' . $dir;

		if ( ! is_dir($cache_path) OR ! is_really_writable($cache_path))
		{
			if (mkdir($cache_path , 0755)) {
				return true;
			} else {
				return false;
			}
		}
		return true;
	}
}
