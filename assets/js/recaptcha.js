if (typeof(RECAPTCHA_JS) === 'undefined') {

	 if (typeof cb_url === 'undefined') {
		alert('올바르지 않은 접근입니다.');
	 }

	var RECAPTCHA_JS = true;

	jQuery(function($) {
		$(document).on('click', '#captcha', function() {
			$.ajax({
				url : cb_url + '/captcha/recaptcha',
				type : 'get',
				cache: false,
				async: false,
				success : function(data) {
					$('#recaptcha').html(data);
				}
			});
		});
		$('#captcha').trigger('click');
		
		/*
		if (typeof $.validator !== 'undefined') {
			$.validator.addMethod('recaptchaKey', function(value, element) {
				if ($('#g-recaptcha-response').val() === '') {
					alert('자동등록방지코드에 체크해주세요');
					return false;
				} else {
					return true;
				}
			});
		}
		*/

		if (typeof $.validator !== 'undefined') {
			$.validator.addMethod('recaptchaKey', function(value, element) {
				if ($('#g-recaptcha-response').val() === '') {
					
					if( $(".g-recaptcha").attr("data-size") === "invisible" ){
						grecaptcha.execute();
					} else {
						alert('자동등록방지코드에 체크해주세요');
					}
					
					return false;
				} else {
					return true;
				}
			});
		}
	});

	function recaptcha_validate(token) {
		
		var $form = jQuery("#g-recaptcha-response").closest("form");
			form_id = $form.attr("id");

		if( $form.length ){

			switch(form_id) {
				case "fcomment":
					jQuery("#cmt_btn_submit").trigger("click");
					break;
				default:
					$form.submit();
			}

		}

	}
}