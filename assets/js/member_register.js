
	$.validator.addMethod('is_userid_available', function(value, element) {
		var response = '';
		$.ajax({
			type: 'post',
			url: cb_url + '/register/ajax_userid_check',
			data : {
				csrf_test_name: cb_csrf_hash,
				userid : value
			},
			async: false,
			dataType : 'json',
			success : function(data) {
				if (data.result === 'available'){
					response = true;
				} else {
					response = false;
				}
			}
		});
		return response;
	}, '이 아이디는 사용하실 수 없습니다');


	$.validator.addMethod('is_email_available', function(value, element) {
		var response = '';
		$.ajax({
			type: 'post',
			url: cb_url + '/register/ajax_email_check',
			data : {
				csrf_test_name: cb_csrf_hash,
				email : value
			},
			async: false,
			dataType : 'json',
			success : function(data) {
				if (data.result === 'available'){
					response = true;
				} else {
					response = false;
				}
			}
		});
		return response;
	}, '이 이메일은 사용하실 수 없습니다');


	$.validator.addMethod('is_password_available', function(value, element) {
		var response = '';
		$.ajax({
			type: 'post',
			url: cb_url + '/register/ajax_password_check',
			data : {
				csrf_test_name: cb_csrf_hash,
				password : value
			},
			async: false,
			dataType : 'json',
			success : function(data) {
				if (data.result === 'available'){
					response = true;
				} else {
					response = false;
				}
			}
		});
		return response;
	}, '이 패스워드는 사용하실 수 없습니다');


	$.validator.addMethod('is_nickname_available', function(value, element) {
		var response = '';
		$.ajax({
			type: 'post',
			url: cb_url + '/register/ajax_nickname_check',
			data : {
				csrf_test_name: cb_csrf_hash,
				nickname : value
			},
			async: false,
			dataType : 'json',
			success : function(data) {
				if (data.result === 'available'){
					response = true;
				} else {
					response = false;
				}
			}
		});
		return response;
	}, '이 닉네임은 사용하실 수 없습니다');

