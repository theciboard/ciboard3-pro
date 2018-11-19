<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Videoplayer class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 비디오 플레이어 관련 함수 모음입니다.
 * 그누보드 기반의 아미나보드 참조
 * http://www.amina.co.kr
 */
class Videoplayer extends CI_Controller
{

	private $CI;
	private $today_popup_data;


	function __construct()
	{
		$this->CI = & get_instance();
		$this->CI->load->helper('string');
	}


	/**
	 * JWPlayer 를 실행합니다
	 */
	function get_jwplayer($file = '', $max_width = '')
	{
		if (empty($file)) {
			return;
		}
		$ext = get_extension($file);
		if (empty($ext)) {
			return;
		}
		if (empty($max_width)) {
			$max_width = 600;
		}

		$video = array('mp4', 'm4v', 'f4v', 'mov', 'flv', 'webm');
		$audio = array('acc', 'm4a', 'f4a', 'mp3', 'ogg', 'oga');

		if ($ext === 'rss') {
			$is_type = 'plist';
			$cnt = $this->get_jwplayer_list($file);
			if ($cnt > 0) {
				;
			} else {
				return;
			}
		} elseif (in_array($ext, $audio)) {
			$is_type = 'audio';
		} elseif (in_array($ext, $video)) {
			$is_type = 'video';
		} else {
			return;
		}

		$jwplayer = '';
		if ( ! defined('JS_JWPLAYER6')) {
			define('JS_JWPLAYER6', true);
			$jwplayer .= '<script type="text/javascript" src="' . base_url('plugin/jwplayer/jwplayer.js') . '"></script>' . PHP_EOL;
			$jwplayer .= '<script type="text/javascript">jwplayer.key="' . $this->CI->cbconfig->item('jwplayer6_key') . '";</script>' . PHP_EOL;
		}

		$jw_id = random_string('alnum');
		$jwplayer .= '<div id="' . $jw_id . '">Loading the player...</div>' . PHP_EOL;
		if ($is_type === 'audio') {
			$jwplayer .= '<script type="text/javascript">
							jwplayer("' . $jw_id . '").setup({
								file: "' . $file . '",
								width: "100%",
								height: "40",
								repeat: "file"
							});
						</script>' . PHP_EOL;
			$jwplayer = '<div style="margin-bottom:15px;">' . $jwplayer . '</div>' . PHP_EOL;
		} elseif ($is_type === 'plist') {
			$plist_set = 'aspectratio: "16:9"';
			$padding = '56.25';
			if ($cnt > 1) {
				$plist_set = $this->CI->cbconfig->get_device_view_type() === 'mobile' ? 'aspectratio: "16:9", listbar: { position: "right", size:150 }' : 'aspectratio: "16:9", listbar: { position: "right", size:200 }';
				$padding = '37.5';
			}
			$jwplayer .= '<script type="text/javascript">
							jwplayer("' . $jw_id . '").setup({
								playlist: "' . $file . '",
								width: "100%",
								' . $plist_set . '
							});
						</script>' . PHP_EOL;
			$jwplayer = '<div class="autowrap" style="max-width:' . $max_width . 'px;"><div class="autosize" style="padding-bottom: ' . $padding . '%;">' . $jwplayer . '</div></div>' . PHP_EOL;
		} else {
			$jwplayer .= '<script type="text/javascript">
							jwplayer("' . $jw_id . '").setup({
								file: "' . $file . '",
								aspectratio: "4:3",
								width: "100%"
							});
						</script>' . PHP_EOL;
			$jwplayer = '<div class="autowrap" style="max-width:' . $max_width . 'px;"><div class="autosize" style="padding-bottom: 75%;">' . $jwplayer . '</div></div>' . PHP_EOL;
		}

		return $jwplayer;
	}


	/**
	 * JWPlayer LIST 를 얻습니다
	 */
	function get_jwplayer_list($url)
	{
		if (empty($url)) {
			return;
		}

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		@curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		$xml = trim(curl_exec($ch));
		curl_close($ch);

		if (empty($xml)) {
			return;
		}

		preg_match_all("/<item>(.*)<\/item>/iUs", $xml, $matchs);

		return count($matchs[1]);
	}


	/**
	 * Video Player 를 보여줍니다
	 */
	function get_video($vid = '')
	{
		if (empty($vid)) {
			return;
		}

		$video = array();
		$vid = str_replace("&nbsp;", " ", $vid);
		$video = $this->get_video_info($vid);

		switch (element('type', $video)) {
			case 'vimeo':
				$video['width'] = 717;
				$video['height'] = 403;
				break;
			case 'nate':
				$video['width'] = 640;
				$video['height'] = 384;
				break;
			case 'tagstory':
				$video['width'] = 720;
				$video['height'] = 480;
				break;
			case 'tvcast':
				$video['width'] = 720;
				$video['height'] = 410;
				break;
			case 'naver':
				$video['width'] = 720;
				$video['height'] = 438;
				break;
			case 'slidershare':
				$video['width'] = 425;
				$video['height'] = 355;
				break;
			default:
				$video['width'] = 640;
				$video['height'] = 360;
				break;
		}

		$ratio = round((element('height', $video) / element('width', $video)), 4) * 100;

		$video_show = '';

		if (element('type', $video) === 'file') { //JWPLAYER
			$show = $this->get_jwplayer(element('video', $video));

			if ($show) {
				return $show;
			}

		} else {

			$show = '';

			if (element('type', $video) === 'youtube') { //유튜브
				$vlist = element('vlist', $video) ? '&list=' . element('vlist', $video) : '';
				$autoplay = (element('auto', $video)) ? '&autoplay=1' : '';
				$show = '<iframe width="' . element('width', $video) . '" height="' . element('height', $video) . '" src="//www.youtube.com/embed/' . element('vid', $video) . '?autohide=1&vq=hd720' . $vlist . $autoplay . '" frameborder="0" allowfullscreen></iframe>';
			} elseif (element('type', $video) === 'vimeo') { //비메오
				$autoplay = (element('auto', $video)) ? '&amp;autoplay=1' : '';
				$show = '<iframe src="http://player.vimeo.com/video/' . element('vid', $video) . '?title=0&amp;byline=0&amp;portrait=0&amp;color=ffffff' . $autoplay . '&amp;wmode=opaque" width="' . element('width', $video) . '" height="' . element('height', $video) . '" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
			} elseif (element('type', $video) === 'ted') { //테드
				$show = '<iframe src="http://embed.ted.com' . element('rid', $video) . '?&wmode=opaque" width="' . element('width', $video) . '" height="' . element('height', $video) . '" frameborder="0" scrolling="no" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
			} elseif (element('type', $video) === 'daum') { //다음TV
				$autoplay = (element('auto', $video)) ? '&autoPlay=1' : '';
				$show = '<iframe width="' . element('width', $video) . '" height="' . element('height', $video) . '" src="http://videofarm.daum.net/controller/video/viewer/Video.html?vid=' . element('rid', $video) . '&play_loc=undefined' . $autoplay . '&wmode=opaque" frameborder="0" scrolling="no"></iframe>';
			} elseif (element('type', $video) === 'dailymotion') { //Dailymotion
				$show = '<iframe frameborder="0" width="' . element('width', $video) . '" height="' . element('height', $video) . '" src="http://www.dailymotion.com/embed/video/' . element('vid', $video) . '?&wmode=opaque"></iframe>';
			} elseif (element('type', $video) === 'pandora') { //판도라TV
				if (element('auto', $video)) {
					$auto = "&amp;autoPlay=true";
				}
				$show = '<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=10,0,0,0" width="' . element('width', $video) . '" height="' . element('height', $video) . '" id="movie" align="middle">';
				$show .= '<param name="quality" value="high" /><param name="movie" value="http://flvr.pandora.tv/flv2pan/flvmovie.dll/userid=' . element('ch_userid', $video) . '&amp;prgid=' . element('prgid', $video) . '&amp;skin=1' . $auto . '&amp;share=on&countryChk=ko" /><param name="allowScriptAccess" value="always" /><param name="allowFullScreen" value="true" /><param name="wmode" value="transparent" />';
				$show .= '<embed src="http://flvr.pandora.tv/flv2pan/flvmovie.dll/userid=' . element('ch_userid', $video) . '&amp;prgid=' . element('prgid', $video) . '&amp;skin=1' . $auto . '&amp;share=on&countryChk=ko" type="application/x-shockwave-flash" wmode="transparent" allowScriptAccess="always" allowFullScreen="true" pluginspage="http://www.macromedia.com/go/getflashplayer" width="' . element('width', $video) . '" height="' . element('height', $video) . '" /></embed></object>';
			} elseif (element('type', $video) === 'nate') { //네이트TV
				$autoplay = (element('auto', $video)) ? '&autoPlay=1' : '';
				$show = '<object id="skplayer" name="skplayer" width="' . element('width', $video) . '" height="' . element('height', $video) . '" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9.0.115.00">';
				$show .= '<param name="movie" value="http://v.nate.com/v.sk/movie/' . element('vs_keys', $video) . '/' . element('mov_id', $video) . $autoplay . '" /><param name="allowFullscreen" value="true" /><param name="allowScriptAccess" value="always" /><param name="wmode" value="transparent" />';
				$show .= '<embed src="http://v.nate.com/v.sk/movie/' . element('vs_keys', $video) . '/' . element('mov_id', $video) . '" wmode="transparent" allowScriptAccess="always" allowFullscreen="true" name="skplayer" width="' . element('width', $video) . '" height="' . element('height', $video) . '" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" /></object>';
			} elseif (element('type', $video) === 'tagstory') { //Tagstory
				$show = '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,16,0" width="' . element('width', $video) . '" height="' . element('height', $video) . '" id="ScrapPlayer" >';
				$show .= '<param name="movie" value="http://www.tagstory.com/player/basic/' . element('vid', $video) . '" /><param name="wmode" value="transparent" /><param name="quality" value="high" /><param name="allowScriptAccess" value="always" /><param name="allowNetworking" value="all" /><param name="allowFullScreen" value="true" />';
				$show .= '<embed src="http://www.tagstory.com/player/basic/' . element('vid', $video) . '" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,16,0" id="ScrapPlayer" name="ScrapPlayer" width="' . element('width', $video) . '" height="' . element('height', $video) . '" wmode="transparent" quality="high" allowScriptAccess="always" allowNetworking="all" allowFullScreen="true" /></object>';
			} elseif (element('type', $video) === 'slidershare') { // SliderShare
				$show = '<iframe src="' . element('play_url', $video) . '" width="' . element('width', $video) . '" height="' . element('height', $video) . '" frameborder="0" marginwidth="0" marginheight="0" scrolling="no" allowfullscreen></iframe>';
			} elseif (element('type', $video) === 'facebook') { // Facebook - 라니안님 코드 반영
				$show = '<iframe src="https://www.facebook.com/video/embed?video_id=' . urlencode(element('vid', $video)) . '" width="' . element('width', $video) . '" height="' . element('height', $video) . '" frameborder="0"></iframe>';
			} elseif (element('type', $video) === 'naver') { // Naver - 라니안님 코드 반영
				$autoplay = (element('auto', $video)) ? '&isp=1' : '';
				$show = '<iframe width="' . element('width', $video) . '" height="' . element('height', $video) . '" src="http://serviceapi.nmv.naver.com/flash/convertIframeTag.nhn?vid=' . element('vid', $video) . '&outKey=' . element('outKey', $video) . $autoplay . '" frameborder="no" scrolling="no"></iframe>';
			} elseif (element('type', $video) === 'tvcast') { // Naver Tvcast - 라니안님 코드 반영
				$autoplay = (element('auto', $video)) ? '&isp=1' : '';
				$show = '<iframe width="' . element('width', $video) . '" height="' . element('height', $video) . '" src="http://serviceapi.rmcnmv.naver.com/flash/outKeyPlayer.nhn?vid=' . element('vid', $video) . '&outKey=' . element('outKey', $video) . '&controlBarMovable=true&jsCallable=true&skinName=tvcast_black' . $autoplay . '" frameborder="no" scrolling="no" marginwidth="0" marginheight="0"></iframe>';
			}

			if ($show) {
				$video_show .= '<div class="autowrap"><div class="autosize" style="padding-bottom: ' . $ratio . '%;">' . PHP_EOL;
				$video_show .= $show . PHP_EOL;
				$video_show .= '</div></div>' . PHP_EOL;
			}
		}
		return $video_show;
	}


	/**
	 * 동영상 종류를 파악합니다
	 */
	function get_video_info($video_url)
	{
		$video = array();
		$query = array();
		$video_url = trim(strip_tags($video_url));
		$url = trim($video_url);

		if ($url) {
			if ( ! preg_match('/(http|https)\:\/\//i', $url)) {
				$url = 'http:' . $url;
			}
		} else {
			return;
		}

		$video['video'] = str_replace(array('&nbsp;', ' '), array('', ''), $url);
		$video['video_url'] = str_replace(array('&nbsp;', '&amp;', ' '), array('', '&', ''), $url);

		$info = parse_url(element('video_url', $video));
		if (element('query', $info)) parse_str(element('query', $info), $query);

		if (element('host', $info) === 'youtu.be') { //유튜브
			$video['type'] = 'youtube';
			$video['vid'] = trim(str_replace('/', '', element('path', $info)));
			$video['vid'] = substr(element('vid', $video), 0, 11);
			$video['vlist'] = element('list', $query);
			$video['auto'] = element('autoplay', $query);
		} elseif (element('host', $info) === 'www.youtube.com') { //유튜브
			$video['type'] = 'youtube';
			if (preg_match('/\/embed\//i', element('video_url', $video))) {
				list($youtube_url, $youtube_opt) = explode('/embed/', element('video_url', $video));
				$vids = explode('?', $youtube_opt);
				$video['vid'] = element(0, $vids);
			} else {
				$video['vid'] = element('v', $query);
				$video['vlist'] = element('list', $query);
			}
		} elseif (element('host', $info) === 'vimeo.com') { //비메오
			$video['type'] = 'vimeo';
			$vquery = explode('/', element('video_url', $video));
			$num = count($vquery) - 1;
			list($video['vid']) = explode('#', element($num, $vquery));
		} elseif (element('host', $info) === 'www.ted.com') { //테드
			$video['type'] = 'ted';
			$vquery = explode('/', element('video_url', $video));
			$num = count($vquery) - 1;
			list($video['vid']) = explode('.', element($num, $vquery));
			$video['rid'] = trim(element('path', $info));
		} elseif (element('host', $info) === 'tvpot.daum.net') { //다음tv
			$video['type'] = 'daum';
			if (element('vid', $query)) {
				$video['vid'] = element('vid', $query);
				$video['rid'] = element('list', $video);
			} else {
				if (element('clipid', $query)) {
					$video['vid'] = element('clipid', $query);
				} else {
					$video['vid'] = trim(str_replace('/v/', '', element('path', $info)));
				}
				$play = $this->get_video_id(element('video_url', $video), element('vid', $video), element('type', $video));
				$video['rid'] = $play['rid'];
			}
		} elseif (element('host', $info) === 'channel.pandora.tv') { //판도라tv
			$video['type'] = 'pandora';
			$video['ch_userid'] = element('ch_userid', $query);
			$video['prgid'] = element('prgid', $query);
			$video['vid'] = element('ch_userid', $video) . '_' . element('prgid', $video);
		} elseif (element('host', $info) === 'pann.nate.com') { //네이트tv
			$video['type'] = 'nate';
			$video['vid'] = trim(str_replace('/video/', '', element('path', $info)));
			$play = $this->get_video_id(element('video_url', $video), element('vid', $video), element('type', $video));
			$video['mov_id'] = element('mov_id', $play);
			$video['vs_keys'] = element('vs_keys', $play);
		} elseif (element('host', $info) === 'www.tagstory.com') { //Tagstory
			$video['type'] = 'tagstory';
			$vquery = explode('/', element('video_url', $video));
			$num = count($vquery) - 1;
			$video['vid'] = element($num, $vquery);
		} elseif (element('host', $info) === 'www.dailymotion.com') { //Dailymotion
			$video['type'] = 'dailymotion';
			$vquery = explode('/', element('video_url', $video));
			$num = count($vquery) - 1;
			list($video['vid']) = explode('_', element($num, $vquery));
		} elseif (element('host', $info) === 'www.facebook.com') { //Facebook
			$video['type'] = 'facebook';
			if (element('video_id', $query)) {
				$video['vid'] = element('video_id', $query);
			} else {
				$video['vid'] = element('v', $query);
			}
			if ( ! is_numeric(element('vid', $video))) {
				$video = null;
			}
		} elseif (element('host', $info) === 'serviceapi.nmv.naver.com') { // 네이버
			$video['type'] = 'naver';
			$video['vid'] = element('vid', $query);
			$video['outKey'] = element('outKey', $query);
		} elseif (element('host', $info) === 'serviceapi.rmcnmv.naver.com') { // 네이버
			$video['type'] = 'tvcast';
			$video['vid'] = element('vid', $query);
			$video['outKey'] = element('outKey', $query);
		} elseif (element('host', $info) === 'tvcast.naver.com') {
			$video['type'] = 'tvcast';
			$video['clipNo'] = trim(str_replace('/v/', '', element('path', $info)));
			$play = $this->get_video_id(element('video_url', $video), element('clipNo', $video), element('type', $video));
			$video['vid'] = element('vid', $play);
			$video['outKey'] = element('outKey', $play);
		} elseif (element('host', $info) === 'www.slideshare.net') { // slidershare
			$video['type'] = 'slidershare';
			$play = $this->get_video_id(element('video_url', $video), 1, element('type', $video));
			$video['play_url'] = element('play_url', $play);
			$video['vid'] = element('vid', $play);
		}

		return $video;
	}


	/**
	 * 동영상 실제 아이디 가져오기
	 */
	function get_video_id($url, $vid, $type)
	{
		$play = array();
		$info = array();
		$query = array();

		if (empty($url) OR empty($vid) OR empty($type) OR ($type === 'file')) {
			return;
		}

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		@curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		$output = curl_exec($ch);
		curl_close($ch);

		if ($type === 'daum') {
			preg_match('/\<meta property=\"og\:video\"([^\<\>])*\>/i', $output, $video);
			if ($video) {
				$video = change_key_case(element(0, $video));
				$video['content'] = preg_replace("/&amp;/", "&", element('content', $video));
				$info = parse_url($video['content']);
				parse_str(element('query', $info), $query);
				$play['rid'] = element('vid', $query);
			}
		} elseif ($type === 'nate') {
			preg_match('/mov_id = \"([^\"]*)\"/i', $output, $video);
			$play['mov_id'] = element(0, $video);

			preg_match('/vs_keys = \"([^\"]*)\"/i', $output, $video);
			$play['vs_keys'] = element(0, $video);

			if ($play) {
				$meta = "<meta {$play[mov_id]} {$play[vs_keys]} >";
				$video = change_key_case($meta);
				$play['mov_id'] = element('mov_id', $video);
				$play['vs_keys'] = element('vs_keys', $video);
			}
		} elseif ($type === 'tvcast') {
			preg_match('/nhn.rmcnmv.RMCVideoPlayer\("(?P<vid>[A-Z0-9]+)", "(?P<inKey>[a-z0-9]+)"/i', $output, $video);

			$play['vid'] = element('vid', $video);
			$play['inkey'] = element('inKey', $video);

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'http://serviceapi.rmcnmv.naver.com/flash/getExternSwfUrl.nhn?vid=' . element('vid', $video) . '&inKey=' . element('inKey', $video));
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_TIMEOUT, 10);
			$output = curl_exec($ch);
			curl_close($ch);
			preg_match('/&outKey=(?P<outKey>[a-zA-Z0-9]+)&/i', $output, $video);

			$play['outKey'] = element('outKey', $video);
		} elseif ($type === 'slidershare') {
			preg_match('/\<meta class=\"twitter_player\"([^\<\>])*\>/i', $output, $video);
			if ($video) {
				$video = change_key_case(element(0, $video));
				$play['play_url'] = preg_replace("/&amp;/", "&", element('value', $video));
				$info = parse_url(element('play_url', $play));
				$play['vid'] = trim(str_replace('/slideshow/embed_code/', '', element('path', $info)));
			}
		}
		return $play;
	}
}
