if (typeof(SOCIAL_LOGIN_JS) === 'undefined') {

	var SOCIAL_LOGIN_JS = true;

	function social_connect_off(social_type) {

		if (social_type !== 'facebook' && social_type !== 'twitter' && social_type !== 'google' && social_type !== 'naver' && social_type !== 'kakao') {
			return false;
		}

		if ( ! confirm('정말로 연동을 해제하시겠습니까?')) {
			return false;
		}
		$.ajax({
			url : cb_url + '/social/social_connect_off/' + social_type,
			type : 'post',
			data : {
				is_submit : '1',
				csrf_test_name : cb_csrf_hash
			},
			dataType : 'json',
			success : function(data) {
				if (data.error) {
					alert(data.error);
					return false;
				} else if (data.success) {
					$('.social-' + social_type + '-on').css('display', 'none');
					$('.social-' + social_type + '-off').css('display', 'inline-block');
				}
			}
		});
	}

	function social_connect_on(social_type) {

		if (social_type !== 'facebook' && social_type !== 'twitter' && social_type !== 'google' && social_type !== 'naver' && social_type !== 'kakao') {
			return false;
		}
		window.open(cb_url + '/social/' + social_type + '_login', social_type + '-on', 'width=600,height=600');
	}


	function social_connect_on_done(social_type) {
		$('.social-' + social_type + '-on').css('display', 'inline-block');
		$('.social-' + social_type + '-off').css('display', 'none');
		alert('연동되었습니다');
	}
}
