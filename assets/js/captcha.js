if (typeof(CAPTCHA_JS) === 'undefined') {

	if (typeof cb_url === 'undefined') {
		alert('올바르지 않은 접근입니다.');
	}

	var CAPTCHA_JS = true;
	var captcha_word = '';

	$(function() {
		$(document).on('click', '#captcha', function() {
			$.ajax({
				url : cb_url + '/captcha/show',
				type : 'get',
				dataType : 'json',
				success : function(data) {
					$('#captcha').attr('src', cb_url + '/uploads/captcha/' + data.filename);
					captcha_word= data.word;
				}
			});
		});
		$('#captcha').trigger('click');

		if (typeof $.validator !== 'undefined') {
			$.validator.addMethod('captchaKey', function(value, element) {
				return this.optional(element) || value.toLowerCase() === captcha_word.toLowerCase();
			});
		}
	});
}
