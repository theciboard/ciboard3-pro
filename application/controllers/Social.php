<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Social class
 *
 * Copyright (c) CIBoard <www.ciboard.co.kr>
 *
 * @author CIBoard (develop@ciboard.co.kr)
 */

/**
 * 소셜로그인 관련 controller 입니다.
 */
class Social extends CB_Controller
{

	/**
	 * 모델을 로딩합니다
	 */
	protected $models = array('Social', 'Social_meta', 'Member_nickname');

	/**
	 * 헬퍼를 로딩합니다
	 */
	protected $helpers = array('form', 'array');

	public $socialtype = array(
		'facebook' => '페이스북',
		'twitter' => '트위터',
		'google' => '구글',
		'naver' => '네이버',
		'kakao' => '카카오',
	);

	function __construct()
	{
		parent::__construct();

		/**
		 * 라이브러리를 로딩합니다
		 */
		$this->load->library(array('querystring', 'email'));

		if ( ! $this->cbconfig->item('use_sociallogin')) {
			alert_close('이 웹사이트는 소셜 로그인 기능을 지원하고 있지 않습니다.');
		}
	}


	/**
	 * 페이스북 연동 함수입니다
	 */
	 public function facebook_login()
	{

		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_social_facebook_login';
		$this->load->event($eventname);

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		if ( ! $this->cbconfig->item('use_sociallogin_facebook')) {
			alert_close('이 웹사이트는 페이스북 로그인 기능을 지원하고 있지 않습니다.');
		}

		require_once FCPATH . 'plugin/social/libraries/Facebook/autoload.php';

		$fb = new Facebook\Facebook([
		'app_id' => $this->cbconfig->item('facebook_app_id'),
		'app_secret' => $this->cbconfig->item('facebook_secret'),
		'default_graph_version' => 'v2.2',
		]);

		$helper = $fb->getRedirectLoginHelper();

		try {
			$accessToken = $helper->getAccessToken();
			if (empty($accessToken)) {
				$permissions = ['email', 'public_profile']; // Optional permissions
				$loginUrl = $helper->getLoginUrl(site_url('social/facebook_login'), $permissions);
				redirect($loginUrl);
			}
		} catch(Facebook\Exceptions\FacebookResponseException $e) {
			// When Graph returns an error
			echo 'Graph returned an error: ' . $e->getMessage();
			exit;
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
			// When validation fails or other local issues
			echo 'Facebook SDK returned an error: ' . $e->getMessage();
			exit;
		}

		try {
			// Returns a `Facebook\FacebookResponse` object
			$response = $fb->get('/me?fields=id,name,first_name,last_name,link,gender,locale,timezone,updated_time,verified,email', $accessToken->getValue());
		} catch(Facebook\Exceptions\FacebookResponseException $e) {
			echo 'Graph returned an error: ' . $e->getMessage();
			exit;
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
			echo 'Facebook SDK returned an error: ' . $e->getMessage();
			exit;
		}

		$userinfo = $response->getGraphUser();

		$facebook_id = $userinfo->getProperty('id');

		if ( ! $userinfo->getProperty('name')) {
			alert_close('이름 정보를 확인할 수 없어 로그인할 수 없습니다');
		}

		$socialdata = array(
			'name' => $userinfo->getProperty('name'),
			'first_name' => $userinfo->getProperty('first_name'),
			'last_name' => $userinfo->getProperty('last_name'),
			'email' => $userinfo->getProperty('email'),
			'link' => $userinfo->getProperty('link'),
			'gender' => $userinfo->getProperty('gender'),
			'locale' => $userinfo->getProperty('locale'),
			'timezone' => $userinfo->getProperty('timezone'),
			'update_datetime' => cdate('Y-m-d H:i:s'),
			'ip_address' => $this->input->ip_address(),
		);
		$this->Social_model->save('facebook', $facebook_id, $socialdata);

		// 이벤트가 존재하면 실행합니다
		Events::trigger('after', $eventname);

		$this->_common_login('facebook', $facebook_id);
	}


	/**
	 * 트위터 연동 함수입니다
	 */
	 public function twitter_login()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_social_twitter_login';
		$this->load->event($eventname);

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		if ( ! $this->cbconfig->item('use_sociallogin_twitter')) {
			alert_close('이 웹사이트는 트위터 로그인 기능을 지원하고 있지 않습니다.');
		}

		require_once FCPATH . 'plugin/social/libraries/twitter/twitteroauth.php';

		if ( ! $this->input->get_post('oauth_token')
			OR $this->session->userdata('twitter_oauth_token') !== $this->input->get_post('oauth_token')) {
			$this->session->unset_userdata('twitter_oauth_token');
			$this->session->unset_userdata('twitter_oauth_token_secret');
		}

		if ( ! $this->session->userdata('twitter_oauth_token')) {
			$connection = new TwitterOAuth(
				$this->cbconfig->item('twitter_consumer_key'),
				$this->cbconfig->item('twitter_consumer_secret')
			);
			$request_token = $connection->getRequestToken(site_url('social/twitter_login'));

			$token = element('oauth_token', $request_token);
			$this->session->set_userdata(
				'twitter_oauth_token',
				$token
			);
			$this->session->set_userdata(
				'twitter_oauth_token_secret',
				element('oauth_token_secret', $request_token)
			);

			if ((string) $connection->http_code === '200') {
				$url = $connection->getAuthorizeURL($token);
				redirect($url);
			} else {
				alert_close('트위터에 접속할 수 없습니다');
			}
		} else {

			$connection = new TwitterOAuth(
				$this->cbconfig->item('twitter_consumer_key'),
				$this->cbconfig->item('twitter_consumer_secret'),
				$this->session->userdata('twitter_oauth_token'),
				$this->session->userdata('twitter_oauth_token_secret')
			);

			$this->session->unset_userdata('twitter_oauth_token');
			$this->session->unset_userdata('twitter_oauth_token_secret');

			$twitter_access_token = $connection->getAccessToken($this->input->get_post('oauth_verifier', null, ''));
			$this->session->userdata('twitter_access_token', $twitter_access_token);

			if ((string) $connection->http_code === '200') {

				$userinfo = $connection->get('account/verify_credentials');

				$twitter_id = $userinfo->id;

				if ( ! $userinfo->name OR ! $userinfo->screen_name) {
					$this->session->unset_userdata('twitter_access_token');
					alert_close('이름 정보를 확인할 수 없어 로그인할 수 없습니다');
				}

				$socialdata = array(
					'name' => $userinfo->name,
					'screen_name' => $userinfo->screen_name,
					'url' => $userinfo->url,
					'lang' => $userinfo->lang,
					'description' => $userinfo->description,
					'location' => $userinfo->location,
					'url' => $userinfo->url,
					'followers_count' => $userinfo->followers_count,
					'friends_count' => $userinfo->friends_count,
					'listed_count' => $userinfo->listed_count,
					'created_at' => $userinfo->created_at,
					'lang' => $userinfo->lang,
					'following' => $userinfo->following,
					'update_datetime' => cdate('Y-m-d H:i:s'),
					'ip_address' => $this->input->ip_address(),
				);
				$this->Social_model->save('twitter', $twitter_id, $socialdata);

				// 이벤트가 존재하면 실행합니다
				Events::trigger('after', $eventname);

				$this->_common_login('twitter', $twitter_id);

			} else {
				alert_close('잘못된 접근입니다');
			}
		}
	}


	/**
	 * 구글 연동 함수입니다
	 */
	 public function google_login()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_social_google_login';
		$this->load->event($eventname);

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		if ( ! $this->cbconfig->item('use_sociallogin_google')) {
			alert_close('이 웹사이트는 구글 로그인 기능을 지원하고 있지 않습니다.');
		}

		require_once FCPATH . 'plugin/social/libraries/google/autoload.php';

		$client_id = $this->cbconfig->item('google_client_id');
		$client_secret = $this->cbconfig->item('google_client_secret');
		$redirect_uri = site_url('social/google_login');

		$client = new Google_Client();
		$client->setClientId($client_id);
		$client->setClientSecret($client_secret);
		$client->setRedirectUri($redirect_uri);
		$client->setScopes('email');
		$client->addScope('profile');

		$plus = new Google_Service_Oauth2($client);


		/************************************************
		If we have a code back from the OAuth 2.0 flow,
		we need to exchange that with the authenticate()
		function. We store the resultant access token
		bundle in the session, and redirect to ourself.
		 ************************************************/
		if ($this->input->get('code')) {
			$client->authenticate($this->input->get('code'));
			$this->session->set_userdata(
				'google_access_token',
				$client->getAccessToken()
			);
			redirect(current_url());
		}

		/************************************************
		If we have an access token, we can make
		requests, else we generate an authentication URL.
		 ************************************************/
		if ($this->session->userdata('google_access_token')) {
			$client->setAccessToken($this->session->userdata('google_access_token'));
		} else {
			$authUrl = $client->createAuthUrl();
			redirect($authUrl);
		}

		/************************************************
		If we're signed in we can go ahead and retrieve
		the ID token, which is part of the bundle of
		data that is exchange in the authenticate step
		- we only need to do a network call if we have
		to retrieve the Google certificate to verify it,
		and that can be cached.
		 ************************************************/
		if ($client->getAccessToken()) {
			$this->session->set_userdata(
				'google_access_token',
				$client->getAccessToken()
			);
			$token_data = $client->verifyIdToken()->getAttributes();
		}

		if (strpos($client_id, 'googleusercontent') === false) {
			alert_close('구글 API 정보가 제대로 입력되지 않았습니다');
		}

		if (isset($token_data)) {

			$google_id = element('sub', element('payload', $token_data));
			$userinfo = $plus->userinfo->get();

			if ( ! $userinfo->name) {
				$this->session->unset_userdata('google_access_token');
				alert_close('이름 정보를 확인할 수 없어 로그인할 수 없습니다');
			}

			$socialdata = array(
				'email' => $userinfo->email,
				'familyName' => $userinfo->familyName,
				'givenName' => $userinfo->givenName,
				'name' => $userinfo->name,
				'gender' => $userinfo->gender,
				'link' => $userinfo->link,
				'locale' => $userinfo->locale,
				'picture' => $userinfo->picture,
				'update_datetime' => cdate('Y-m-d H:i:s'),
				'ip_address' => $this->input->ip_address(),
			);
			$this->Social_model->save('google', $google_id, $socialdata);

			// 이벤트가 존재하면 실행합니다
			Events::trigger('after', $eventname);

			$this->_common_login('google', $google_id);

		} else {
			alert_close('잘못된 접근입니다');
		}
	}


	/**
	 * 네이버 연동 함수입니다
	 */
	 public function naver_login()
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_social_naver_login';
		$this->load->event($eventname);

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		if ( ! $this->cbconfig->item('use_sociallogin_naver')) {
			alert_close('이 웹사이트는 네이버 로그인 기능을 지원하고 있지 않습니다.');
		}

		if ($this->session->userdata('naver_access_token')) {
			$url = 'https://apis.naver.com/nidlogin/nid/getUserProfile.xml';

			$ch = curl_init();
			curl_setopt ($ch, CURLOPT_URL, $url);
			curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt ($ch, CURLOPT_SSLVERSION, 1);
			curl_setopt ($ch, CURLOPT_HEADER, 0);
			curl_setopt ($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->session->userdata('naver_access_token')));
			curl_setopt ($ch, CURLOPT_POST, 0);
			curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt ($ch, CURLOPT_TIMEOUT, 30);
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
			$result = curl_exec($ch);
			curl_close($ch);

			$xml = simplexml_load_string($result);

			$naver_id = (string) $xml->response->enc_id;
			$email = (string) $xml->response->email;
			$nickname = (string) $xml->response->nickname;
			$profile_image = (string) $xml->response->profile_image;
			$age = (string) $xml->response->age;
			$gender = (string) $xml->response->gender;
			$id = (string) $xml->response->id;
			$name = (string) $xml->response->name;
			$birthday = (string) $xml->response->birthday;

			if (empty($nickname)) {
				$this->session->unset_userdata('naver_access_token');
				alert_close('이름 정보를 확인할 수 없어 로그인할 수 없습니다');
			}

			$socialdata = array(
				'email' => $email,
				'nickname' => $nickname,
				'profile_image' => $profile_image,
				'age' => $age,
				'gender' => $gender,
				'id' => $id,
				'name' => $name,
				'birthday' => $birthday,
				'update_datetime' => cdate('Y-m-d H:i:s'),
				'ip_address' => $this->input->ip_address(),
			);
			$this->Social_model->save('naver', $naver_id, $socialdata);

			// 이벤트가 존재하면 실행합니다
			Events::trigger('after', $eventname);

			$this->_common_login('naver', $naver_id);
		}

		if ($this->input->get('code')) {
			$url = 'https://nid.naver.com/oauth2.0/token';
			$url.= sprintf("?client_id=%s&client_secret=%s&grant_type=authorization_code&state=%s&code=%s",
				$this->cbconfig->item('naver_client_id'), $this->cbconfig->item('naver_client_secret'), $this->input->get('state', null, ''), $this->input->get('code'));

			$ch = curl_init();
			curl_setopt ($ch, CURLOPT_URL, $url);
			curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt ($ch, CURLOPT_SSLVERSION,1);
			curl_setopt ($ch, CURLOPT_HEADER, 0);
			curl_setopt ($ch, CURLOPT_POST, 0);
			curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt ($ch, CURLOPT_TIMEOUT, 30);
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
			$result = curl_exec($ch);
			curl_close($ch);

			$json = json_decode($result, true);

			if (element('access_token', $json)) {

				$this->session->set_userdata(
					'naver_access_token',
					element('access_token', $json)
				);
				echo '<script type="text/javascript">location.reload()</script>';
				exit;

			} else {

				alert_close('로그인에 실패하였습니다');

			}
		}

		if ($this->input->get('state')) {
			if ($this->input->get('state') === $this->session->userdata('naver_state')) {
				return RESPONSE_SUCCESS;
			} else {
				return RESPONSE_UNAUTHORIZED;
			}
			exit;
		}

		if ( ! $this->session->userdata('naver_access_token')) {
			$this->load->helper('string');
			$state = random_string('alnum', 50);
			$this->session->set_userdata('naver_state', $state);

			$url = 'https://nid.naver.com/oauth2.0/authorize';
			$url.= sprintf("?client_id=%s&response_type=code&redirect_uri=%s&state=%s",
				$this->cbconfig->item('naver_client_id'), urlencode(site_url('social/naver_login')), $state);

			redirect($url);
		}
	}


	/**
	 * 카카오 연동 함수입니다
	 */
	 public function kakao_login()
	{

		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_social_kakao_login';
		$this->load->event($eventname);

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		if ( ! $this->cbconfig->item('use_sociallogin_kakao')) {
			alert_close('이 웹사이트는 카카오 로그인 기능을 지원하고 있지 않습니다.');
		}

		if ($this->session->userdata('kakao_access_token')) {
			$url = 'https://kapi.kakao.com/v1/user/me';

			$ch = curl_init();
			curl_setopt ($ch, CURLOPT_URL, $url);
			curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt ($ch, CURLOPT_SSLVERSION, 1);
			curl_setopt ($ch, CURLOPT_HEADER, 0);
			curl_setopt ($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->session->userdata('kakao_access_token')));
			curl_setopt ($ch, CURLOPT_POST, 0);
			curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt ($ch, CURLOPT_TIMEOUT, 30);
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
			$result = curl_exec($ch);
			curl_close($ch);

			$json = json_decode($result, true);

			$kakao_id = element('id', $json);
			$nickname = element('nickname', element('properties', $json));
			$profile_image = element('profile_image', element('properties', $json));
			$thumbnail_image = element('thumbnail_image', element('properties', $json));

			if (empty($nickname)) {
				$this->session->unset_userdata('kakao_access_token');
				alert_close('이름 정보를 확인할 수 없어 로그인할 수 없습니다');
			}


			$socialdata = array(
				'nickname' => $nickname,
				'profile_image' => $profile_image,
				'thumbnail_image' => $thumbnail_image,
				'update_datetime' => cdate('Y-m-d H:i:s'),
				'ip_address' => $this->input->ip_address(),
			);
			$this->Social_model->save('kakao', $kakao_id, $socialdata);

			// 이벤트가 존재하면 실행합니다
			Events::trigger('after', $eventname);

			$this->_common_login('kakao', $kakao_id);
		}

		if ($this->input->get('code')) {
			$url = 'https://kauth.kakao.com/oauth/token';
			$url.= sprintf("?client_id=%s&grant_type=authorization_code&redirect_uri=%s&code=%s",
				$this->cbconfig->item('kakao_client_id'), urlencode(site_url('social/kakao_login')), $this->input->get('code'));

			$ch = curl_init();
			curl_setopt ($ch, CURLOPT_URL, $url);
			curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt ($ch, CURLOPT_SSLVERSION,1);
			curl_setopt ($ch, CURLOPT_HEADER, 0);
			curl_setopt ($ch, CURLOPT_POST, 0);
			curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt ($ch, CURLOPT_TIMEOUT, 30);
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
			$result = curl_exec($ch);
			curl_close($ch);

			$json = json_decode($result, true);

			if (element('access_token', $json)) {
				$this->session->set_userdata(
					'kakao_access_token',
					element('access_token', $json)
				);

				echo '<script type="text/javascript">location.reload()</script>';
				exit;
			} else {
				alert_close('로그인에 실패하였습니다');
			}
		}

		if ( ! $this->session->userdata('kakao_access_token')) {

			$url = 'https://kauth.kakao.com/oauth/authorize';
			$url.= sprintf("?client_id=%s&response_type=code&redirect_uri=%s",
				$this->cbconfig->item('kakao_client_id'), urlencode(site_url('social/kakao_login')));

			redirect($url);
		}
	}


	/**
	 * 소셜 연동 해제 함수입니다
	 */
	 public function social_connect_off($stype)
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_social_social_connect_off';
		$this->load->event($eventname);

		// 이벤트가 존재하면 실행합니다
		Events::trigger('before', $eventname);

		if ($this->member->is_member() === false) {
			$result = array('error' => '로그인 후 이용해주세요.');
			exit(json_encode($result));
		}

		if (empty($stype)) {
			$result = array('error' => '잘못된 접근입니다');
			exit(json_encode($result));
		}

		if ($stype !== 'facebook' && $stype !== 'twitter' && $stype !== 'google' && $stype !== 'naver' && $stype !== 'kakao') {
			$result = array('error' => '잘못된 접근입니다');
			exit(json_encode($result));
		}

		$mem_id = (int) $this->member->item('mem_id');

		/**
		 * Validation 라이브러리를 가져옵니다
		 */
		$this->load->library('form_validation');

		/**
		 * 전송된 데이터의 유효성을 체크합니다
		 */
		$config = array(
			array(
				'field' => 'is_submit',
				'label' => '전송',
				'rules' => 'trim|required',
			),
		);
		$this->form_validation->set_rules($config);
		/**
		 * 유효성 검사를 하지 않는 경우, 또는 유효성 검사에 실패한 경우입니다.
		 */
		if ($this->form_validation->run() === false) {

			// 이벤트가 존재하면 실행합니다
			Events::trigger('formrunfalse', $eventname);

			$result = array('error' => '올바른 방법으로 접근하여주세요');
			exit(json_encode($result));

		} else {

			// 이벤트가 존재하면 실행합니다
			Events::trigger('formruntrue', $eventname);

			$metadata = '';
			switch ($stype) {
				case 'facebook':
						$metadata = array('facebook_id' => '');
					break;
				case 'twitter':
						$metadata = array('twitter_id' => '');
					break;
				case 'google':
						$metadata = array('google_id' => '');
					break;
				case 'naver':
						$metadata = array('naver_id' => '');
					break;
				case 'kakao':
						$metadata = array('kakao_id' => '');
					break;
			}
			if ($metadata) {
				$this->Social_meta_model->save($mem_id, $metadata);
			}

			$result = array('success' => '연동이 해제되었습니다.');
			exit(json_encode($result));
		}
	}


	public function _common_login($social_type = '', $social_id = '')
	{
		// 이벤트 라이브러리를 로딩합니다
		$eventname = 'event_social_common_login';
		$this->load->event($eventname);

		if (empty($social_type)) {
			return;
		}
		if (empty($social_id)) {
			return;
		}

		if ( ! element($social_type, $this->socialtype)) {
			return;
		}

		// 이벤트가 존재하면 실행합니다
		Events::trigger('common_login_before', $eventname);

		if ($this->member->is_member()) {
			// 이미 로그인 한 상태에서 소셜로그인을 추가로 진행한 상황

			$mem_id = $this->Social_meta_model
				->get_mem_id_by_social($social_type, $social_id);

			if ($mem_id) {
				if ((int) $mem_id === (int) $this->member->item('mem_id')) {
					// 나의 계정과 같으므로, 고의로 접근한 경우임
					// 별다른 액션을 취할 것 없음
				} else {
					// 다른 회원 아이디에 연결된 계정으로 로그인 시도함

					alert_close('연동하고자 하는 ' . element($social_type, $this->socialtype) . ' 계정은 이미 다른 아이디에 연결되어있습니다. 연동을 원하시면 연결되어 있는 다른 아이디에서 ' . element($social_type, $this->socialtype) . ' 연동을 제거하신 후에 이곳에서 다시 연동하여주세요');
				}
			} else {
				// 연결된 계정 없었음, 처음 연동하는 경우임
				$metadata = array(
					$social_type . '_id' => $social_id,
				);
				$this->Social_meta_model
					->save($this->member->item('mem_id'), $metadata);
			}
			$this->_connected_close($social_type);

		} else {

			$mem_id = $this->Social_meta_model
				->get_mem_id_by_social($social_type, $social_id);

			$is_dormant_member = false;

			$userselect = 'mem_id, mem_password, mem_denied, mem_email_cert, mem_is_admin';
			$userinfo = $this->Member_model->get_by_memid($mem_id, $userselect);
			if ( ! $userinfo) {
				$this->load->model('Member_dormant_model');
				$userinfo = $this->Member_dormant_model->get_by_memid($mem_id, $userselect);
				if ($userinfo) {
					$is_dormant_member = true;
				}
			}
			if ( ! element('mem_id', $userinfo)) {
				$this->Social_meta_model->delete_where(array('mem_id' => $mem_id));
				$mem_id = '';
			}
			if (element('mem_denied', $userinfo)) {
				alert_close('회원님의 아이디는 접근이 금지되어 있습니다.');
			}

			if ($is_dormant_member) {
				$this->member->recover_from_dormant($mem_id);
			}

			if ($mem_id) {
				// 이미 회원정보를 가지고 있는 상황에서 소셜로그인을 진행한 상황

				$this->member->update_login_log($mem_id, '', 1, element($social_type, $this->socialtype) . ' 로그인 성공');
				$this->session->set_userdata('mem_id', $mem_id);

				$url_after_login = $this->cbconfig->item('url_after_login');
				if ($url_after_login) {
					$url_after_login = site_url($url_after_login);
				}
				echo '<meta http-equiv="content-type" content="text/html; charset=' . config_item('charset') . '">';
				echo '<script type="text/javascript"> window.close();';
				if ($url_after_login) {
					echo 'window.opener.document.location.href = "' . $url_after_login . '";';
				} else {
					echo 'window.opener.location.reload();';
				}
				echo '</script>';
				exit;

			} else {
				// 비회원이 소셜로그인을 처음 진행하는 상황

				if ($this->cbconfig->item('use_register_block')) {
					alert_close('현재 이 사이트는 회원가입이 금지되어 있습니다.');
				}

				$socialwhere = array(
					'soc_type' => $social_type,
					'soc_account_id' => $social_id,
				);
				$socialinfo = array();
				$socialdata = $this->Social_model->get('', '', $socialwhere);
				if ($socialdata) {
					foreach ($socialdata as $skey => $sval) {
						$socialinfo[$sval['soc_key']] = $sval['soc_value'];
					}
				}

				$nickname = '';
				$user_id = '';
				if ($social_type === 'facebook') {
					$nickname = element('name', $socialinfo);
					$nickname = preg_replace('/\s+/', '', $nickname);

					if (element('email', $socialinfo)) {
						$user_id = '-social_' . strtolower(substr(element('email', $socialinfo), 0, strpos(element('email', $socialinfo), '@')));
					} else {
						$user_id = '-social_' . $social_id;
					}
				} elseif ($social_type === 'twitter') {
					$nickname = element('name', $socialinfo);
					$nickname = preg_replace('/\s+/', '', $nickname);

					if (element('screen_name', $socialinfo)) {
						$user_id = '-social_' . strtolower(element('screen_name', $socialinfo));
					} else {
						$user_id = '-social_' . $social_id;
					}
				} elseif ($social_type === 'google') {
					$nickname = element('name', $socialinfo);
					$nickname = preg_replace('/\s+/', '', $nickname);

					if (element('email', $socialinfo)) {
						$user_id = '-social_' . strtolower(substr(element('email', $socialinfo), 0, strpos(element('email', $socialinfo), '@')));
					} else {
						$user_id = '-social_' . $social_id;
					}
				} elseif ($social_type === 'naver') {
					$nickname = element('nickname', $socialinfo) ? element('nickname', $socialinfo) : element('name', $socialinfo);
					$nickname = preg_replace('/\s+/', '', $nickname);

					if (element('email', $socialinfo)) {
						$user_id = '-social_' . strtolower(substr(element('email', $socialinfo), 0, strpos(element('email', $socialinfo), '@')));
					} else {
						$user_id = '-social_' . $social_id;
					}
				} elseif ($social_type === 'kakao') {
					$nickname = element('nickname', $socialinfo);
					$nickname = preg_replace('/\s+/', '', $nickname);

					$user_id = '-social_' . $social_id;
				}

				if (empty($nickname)) {
					$nickname = '사용자';
				}

				$nickname_origin = $nickname;
				$k = 1;
				while (true) {
					$good = true;
					if (preg_match("/[\,]?{$nickname}/i", $this->cbconfig->item('denied_nickname_list'))) {
						$good = false;
					}
					if ($good) {
						$countwhere = array(
							'mem_nickname' => $nickname,
						);
						$row = $this->Member_model->count_by($countwhere);

						if ($row > 0) {
							$good = false;
						}
					}
					if ($good) {
						$countwhere = array(
							'mni_nickname' => $nickname,
						);
						$row = $this->Member_nickname_model->count_by($countwhere);
						if ($row > 0) {
							$good = false;
						}
					}
					if ($good) {
						break;
					}
					$nickname = $nickname_origin . $k;
					$k++;
				}

				$user_id_origin = $user_id;
				$k = 1;
				while (true) {
					$good = true;
					if (preg_match("/[\,]?{$user_id}/i", $this->cbconfig->item('denied_userid_list'))) {
						$good = false;
					}
					if ($good) {
						$countwhere = array(
							'mem_userid' => $user_id
						);
						$row = $this->Member_model->count_by($countwhere);

						if ($row > 0) {
							$good = false;
						}
					}
					if ($good) {
						break;
					}
					$user_id = $user_id_origin . $k;
					$k++;
				}

				$mem_level = (int) $this->cbconfig->item('register_level');
				$insertdata = array();
				$insertdata['mem_userid'] = $user_id;
				$insertdata['mem_nickname'] = $nickname;
				$insertdata['mem_level'] = $mem_level;

				$insertdata['mem_register_datetime'] = cdate('Y-m-d H:i:s');
				$insertdata['mem_register_ip'] = $this->input->ip_address();
				$insertdata['mem_lastlogin_datetime'] = cdate('Y-m-d H:i:s');
				$insertdata['mem_lastlogin_ip'] = $this->input->ip_address();

				$mem_id = $this->Member_model->insert($insertdata);

				$nickinsert = array(
					'mem_id' => $mem_id,
					'mni_nickname' => $nickname,
					'mni_start_datetime' => cdate('Y-m-d H:i:s'),
				);
				$this->Member_nickname_model->insert($nickinsert);

				$levelhistoryinsert = array(
					'mem_id' => $mem_id,
					'mlh_from' => 0,
					'mlh_to' => $mem_level,
					'mlh_datetime' => cdate('Y-m-d H:i:s'),
					'mlh_reason' => '회원가입',
					'mlh_ip' => $this->input->ip_address(),
				);
				$this->load->model('Member_level_history_model');
				$this->Member_level_history_model->insert($levelhistoryinsert);

				$this->load->model('Member_group_model');
				$allgroup = $this->Member_group_model->get_all_group();
				if ($allgroup && is_array($allgroup)) {
					$this->load->model('Member_group_member_model');
					foreach ($allgroup as $gkey => $gval) {
						if (element('mgr_is_default', $gval)) {
							$gminsert = array(
								'mgr_id' => element('mgr_id', $gval),
								'mem_id' => $mem_id,
								'mgm_datetime' => cdate('Y-m-d H:i:s'),
							);
							$this->Member_group_member_model->insert($gminsert);
						}
					}
				}
				$this->load->library('point');
				$this->point->insert_point(
					$mem_id,
					$this->cbconfig->item('point_register'),
					'회원가입을 축하합니다',
					'member',
					$mem_id,
					'회원가입'
				);

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
				);
				$mem_userid = $user_id;
				$mem_nickname = $nickname;
				$replaceconfig = array(
					$this->cbconfig->item('site_title'),
					$this->cbconfig->item('company_name'),
					site_url(),
					$mem_userid,
					$mem_nickname,
					$this->input->ip_address(),
				);
				$replaceconfig_escape = array(
					html_escape($this->cbconfig->item('site_title')),
					html_escape($this->cbconfig->item('company_name')),
					site_url(),
					html_escape($mem_userid),
					html_escape($mem_nickname),
					$this->input->ip_address(),
				);


				$emailsendlistadmin = array();
				$notesendlistadmin = array();
				$smssendlistadmin = array();

				$superadminlist = '';
				if ($this->cbconfig->item('send_email_register_admin')
					OR $this->cbconfig->item('send_note_register_admin')
					OR $this->cbconfig->item('send_sms_register_admin')) {
					$mselect = 'mem_id, mem_email, mem_nickname, mem_phone';
					$superadminlist = $this->Member_model->get_superadmin_list($mselect);
				}

				if ($this->cbconfig->item('send_email_register_admin') && $superadminlist) {
					foreach ($superadminlist as $key => $value) {
						$emailsendlistadmin[$value['mem_id']] = $value;
					}
				}
				if ($this->cbconfig->item('send_note_register_admin') && $superadminlist) {
					foreach ($superadminlist as $key => $value) {
						$notesendlistadmin[$value['mem_id']] = $value;
					}
				}
				if ($this->cbconfig->item('send_sms_register_admin') && $superadminlist) {
					foreach ($superadminlist as $key => $value) {
						$smssendlistadmin[$value['mem_id']] = $value;
					}
				}

				if ($emailsendlistadmin) {
					$title = str_replace(
						$searchconfig,
						$replaceconfig,
						$this->cbconfig->item('send_email_register_admin_title')
					);
					$content = str_replace(
						$searchconfig,
						$replaceconfig_escape,
						$this->cbconfig->item('send_email_register_admin_content')
					);
					foreach ($emailsendlistadmin as $akey => $aval) {
						$this->email->clear(true);
						$this->email->from($this->cbconfig->item('webmaster_email'), $this->cbconfig->item('webmaster_name'));
						$this->email->to(element('mem_email', $aval));
						$this->email->subject($title);
						$this->email->message($content);
						$this->email->send();
					}
				}
				if ($notesendlistadmin) {
					$title = str_replace(
						$searchconfig,
						$replaceconfig,
						$this->cbconfig->item('send_note_register_admin_title')
					);
					$content = str_replace(
						$searchconfig,
						$replaceconfig_escape,
						$this->cbconfig->item('send_note_register_admin_content')
					);
					foreach ($notesendlistadmin as $akey => $aval) {
						$note_result = $this->notelib->send_note(
							$sender = 0,
							$receiver = element('mem_id', $aval),
							$title,
							$content,
							1
						);
					}
				}
				if ($smssendlistadmin) {
					if (file_exists(APPPATH . 'libraries/Smslib.php')) {
						$this->load->library(array('smslib'));
						$content = str_replace(
							$searchconfig,
							$replaceconfig,
							$this->cbconfig->item('send_sms_register_admin_content')
						);
						$sender = array(
							'phone' => $this->cbconfig->item('sms_admin_phone'),
						);
						$receiver = array();
						foreach ($smssendlistadmin as $akey => $aval) {
							$receiver[] = array(
								'mem_id' => element('mem_id', $aval),
								'name' => element('mem_nickname', $aval),
								'phone' => element('mem_phone', $aval),
							);
						}
						$smsresult = $this->smslib->send($receiver, $sender, $content, $date = '', '회원가입알림');
					}
				}

				$member_register_data = array(
					'mem_id' => $mem_id,
					'mrg_ip' => $this->input->ip_address(),
					'mrg_datetime' => cdate('Y-m-d H:i:s'),
					'mrg_useragent' => $this->agent->agent_string(),
					'mrg_referer' => $this->session->userdata('site_referer'),
				);
				$this->load->model('Member_register_model');
				$this->Member_register_model->insert($member_register_data);


				$metadata = array(
					$social_type . '_id' => $social_id,
				);
				$this->Social_meta_model->save($mem_id, $metadata);

				$this->member->update_login_log($mem_id, '', 1, element($social_type, $this->socialtype) . ' 로그인 성공');
				$this->session->set_userdata('mem_id', $mem_id);

				$url_after_login = $this->cbconfig->item('url_after_login');
				if ($url_after_login) {
					$url_after_login = site_url($url_after_login);
				}

				// 이벤트가 존재하면 실행합니다
				Events::trigger('common_login_after', $eventname);

				echo '<meta http-equiv="content-type" content="text/html; charset=' . config_item('charset') . '">';
				echo '<script type="text/javascript"> window.close();';
				if ($url_after_login) {
					echo 'window.opener.document.location.href = "' . $url_after_login . '";';
				} else {
					echo 'window.opener.location.reload();';
				}
				echo '</script>';
				exit;
			}
		}
	}


	public function _connected_close($stype)
	{
		echo '<meta http-equiv="content-type" content="text/html; charset=' . config_item('charset') . '">';
		echo '<script type="text/javascript"> window.close();window.opener.social_connect_on_done("' . $stype . '");</script>';
		exit;
	}
}
