if (typeof(PAYMENT_JS) === 'undefined') {

	var PAYMENT_JS = true;

	var p_method = '',
		ori_action_url = document.getElementById("fpayment").getAttribute("action");

	function pay_approval() {
		var f = document.sm_form;
		var pf = document.fpayment;

		// 필드체크
		if ( ! fsubmit_requirement(pf))
			return false;

		if (p_method === '무통장') {
			pf.submit();
			return;
		}

		if (pg_type === 'kcp') {

			f.buyr_name.value = pf.mem_realname.value;
			f.buyr_mail.value = pf.mem_email.value;
			f.buyr_tel1.value = pf.mem_phone.value;
			f.buyr_tel2.value = pf.mem_phone.value;
			f.rcvr_name.value = pf.mem_realname.value;
			f.rcvr_tel1.value = pf.mem_phone.value;
			f.rcvr_tel2.value = pf.mem_phone.value;
			f.rcvr_mail.value = pf.mem_email.value;
			f.good_mny.value = pf.good_mny.value;
			f.good_name.value = good_name;
			f.pay_type.value = p_method;

		} else if (pg_type === 'lg') {

			var pay_method = '';
			switch(p_method) {
				case 'realtime':
					pay_method = 'SC0030';
					break;
				case 'vbank':
					pay_method = 'SC0040';
					break;
				case 'phone':
					pay_method = 'SC0060';
					break;
				case 'card':
					pay_method = 'SC0010';
					break;
			}
			f.LGD_CUSTOM_FIRSTPAY.value = pay_method;
			f.LGD_BUYER.value = pf.mem_realname.value;
			f.LGD_BUYEREMAIL.value = pf.mem_email.value;
			f.LGD_BUYERPHONE.value = pf.mem_phone.value;
			f.LGD_AMOUNT.value = pf.good_mny.value;
			f.LGD_PRODUCTINFO.value = good_name;
			f.LGD_RECEIVER.value = pf.mem_realname.value;
			f.LGD_RECEIVERPHONE.value = pf.mem_phone.value;

		} else if (pg_type === 'inicis') {

			var paymethod = '';
			var width = 330;
			var height = 480;
			var xpos = (screen.width - width) / 2;
			var ypos = (screen.width - height) / 2;
			var position = 'top=' + ypos + ',left=' + xpos;
			var features = position + ', width=320, height=440';
			switch(p_method) {
				case 'realtime':
					paymethod = 'bank';
					break;
				case 'vbank':
					paymethod = 'vbank';
					break;
				case 'phone':
					paymethod = 'mobile';
					break;
				case 'card':
					paymethod = 'wcard';
					break;
			}
			f.P_AMT.value = pf.good_mny.value;
			f.P_GOODS.value = good_name;
			f.P_UNAME.value = pf.mem_realname.value;
			f.P_MOBILE.value = pf.mem_phone.value;
			f.P_EMAIL.value = pf.mem_email.value;
			f.P_RETURN_URL.value = cb_url + '/payment/inicis_pay_return/' + ptype + '/' + payment_unique_id;
			f.action = 'https://mobile.inicis.com/smart/' + paymethod + '/';
		}


		// 주문 정보 임시저장
		var order_data = $(pf).serialize();
		var save_result = '';
		$.ajax({
			type: 'post',
			data: order_data,
			url: cb_url + '/payment/orderdatasave',
			cache: false,
			async: false,
			success: function(data) {
				save_result = data;
			}
		});

		if (save_result) {
			alert(save_result);
			return false;
		}

		f.submit();
	}


	function fsubmit_requirement(f) {

		var mem_realname_val = f.mem_realname.value,
			mem_email_val = f.mem_email.value,
			mem_phone_val = f.mem_phone.value,
			phone_regexp = /^01([0|1|6|7|8|9]?)-?([0-9]{3,4})-?([0-9]{4})$/,
			email_regexp = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/;
		
		if( ! jQuery.trim(mem_realname_val) ){
			alert("실명을 올바르게 입력해 주세요.");
			f.mem_realname.select();
			return false;
		}

		if( !email_regexp.test(mem_email_val) ){
			alert("이메일을 올바르게 입력해 주세요.");
			f.mem_email.select();
			return false;
		}

		if( !phone_regexp.test(mem_phone_val) ){
			alert("휴대폰 번호를 올바르게 입력해 주세요.");
			f.mem_phone.select();
			return false;
		}

		if (ptype === 'deposit') {
			var money_val = document.getElementsByName('money_value');
			var deposit_val = document.getElementsByName('deposit_value[]');
			var price_check = false;
			var price = 0;
			var deposit_real = 0;
			for (i = 0; i < money_val.length; i++) {
				if (money_val[i].checked) {
					price_check = true;
					price = parseInt(money_val[i].value);
					deposit_real = deposit_val[i].value;
					break;
				}
			}
			if ( ! price_check) {
				alert('결제금액을 선택하십시오.');
				return false;
			}
		}

		var pay_type = document.getElementsByName('pay_type');
		var pay_type_check = false;
		for (i = 0; i < pay_type.length; i++) {
			if (pay_type[i].checked) {
				pay_type_check = true;
				p_method = pay_type[i].value;
				break;
			}
		}
		if ( ! pay_type_check) {
			alert('결제방식을 선택하십시오.');
			return false;
		}

		if (ptype === 'cmall') {

			if (parseInt($('#order_deposit').val()) < 0) {
				alert('예치금은 0 보다 작은 값을 입력하실 수 없습니다');
				return false;
			} else if (parseInt($('#order_deposit').val()) > parseInt($('#max_deposit').val())) {
				alert('예치금은 ' + $('#max_deposit').val() + ' 보다 큰 값을 입력하실 수 없습니다');
				return false;
			}

			if ( ! parseInt($('#total_price_sum').val())) {
				alert('결제금액이 없습니다.');
				return false;
			}

			price = parseInt($('#total_price_sum').val()) - parseInt($('#order_deposit').val());
			if ( p_method !== 'bank' && ! price) {
				alert('사용예치금이 총주문금액과 같을 경우 무통장입금으로 주문해주세요');
				return false;
			}
		}

		if (document.getElementById('pay_type_realtime')) {
			if (document.getElementById('pay_type_realtime').checked) {
				if (price < 150) {
					alert('계좌이체는 150원 이상 결제가 가능합니다.');
					return false;
				}
			}
		}

		if (document.getElementById('pay_type_card')) {
			if (document.getElementById('pay_type_card').checked) {
				if (price < 1000) {
					alert('신용카드는 1000원 이상 결제가 가능합니다.');
					return false;
				}
			}
		}

		if (document.getElementById('pay_type_phone')) {
			if (document.getElementById('pay_type_phone').checked) {
				if (price < 350) {
					alert('휴대폰은 350원 이상 결제가 가능합니다.');
					return false;
				}
			}
		}

		if (ptype === 'deposit') {
			f.deposit_real.value = deposit_real;
		}
		f.good_mny.value = price;

		return true;
	}


	/* 결제방법에 따른 처리 후 결제등록요청 실행 */
	function fpayment_check() {

		var f = document.fpayment;

		if ( ! fsubmit_requirement(f))
			return false;

		// pay_method 설정

		if (cb_device_type !== 'mobile') {

			if (use_pg === '1') {
				if (pg_type === 'kcp') {

					switch(p_method) {
						case 'realtime':
							f.pay_method.value = '010000000000';
							break;
						case 'vbank':
							f.pay_method.value = '001000000000';
							break;
						case 'phone':
							f.pay_method.value = '000010000000';
							break;
						case 'card':
							f.pay_method.value = '100000000000';
							break;
						default:
							f.pay_method.value = '무통장';
							break;
					}

				} else if (pg_type === 'lg') {

					switch(p_method) {
						case 'realtime':
							f.LGD_CUSTOM_FIRSTPAY.value = 'SC0030';
							f.LGD_CUSTOM_USABLEPAY.value = 'SC0030';
							break;
						case 'vbank':
							f.LGD_CUSTOM_FIRSTPAY.value = 'SC0040';
							f.LGD_CUSTOM_USABLEPAY.value = 'SC0040';
							break;
						case 'phone':
							f.LGD_CUSTOM_FIRSTPAY.value = 'SC0060';
							f.LGD_CUSTOM_USABLEPAY.value = 'SC0060';
							break;
						case 'card':
							f.LGD_CUSTOM_FIRSTPAY.value = 'SC0010';
							f.LGD_CUSTOM_USABLEPAY.value = 'SC0010';
							break;
						default:
							f.LGD_CUSTOM_FIRSTPAY.value = '무통장';
							break;
					}

				} else if (pg_type === 'inicis') {

					switch(p_method) {
						case 'realtime':
							f.gopaymethod.value = 'onlydbank';
							break;
						case 'vbank':
							f.gopaymethod.value = 'onlyvbank';
							break;
						case 'phone':
							f.gopaymethod.value = 'onlyhpp';
							break;
						case 'card':
							f.gopaymethod.value = 'onlycard';
							break;
						default:
							f.gopaymethod.value = '무통장';
							break;
					}
				}

				// 결제정보설정
				if (pg_type === 'kcp') {

					f.good_name.value = good_name;
					f.buyr_name.value = f.mem_realname.value;
					f.buyr_mail.value = f.mem_email.value;
					f.buyr_tel1.value = f.mem_phone.value;
					f.buyr_tel2.value = f.mem_phone.value;
					f.rcvr_name.value = f.mem_realname.value;
					f.rcvr_tel1.value = f.mem_phone.value;
					f.rcvr_tel2.value = f.mem_phone.value;
					f.rcvr_mail.value = f.mem_email.value;

					if (f.pay_method.value !== '무통장') {
						if (jsf__pay( f )) {
							f.submit();
						} else {
							return false;
						}
					} else {
						f.submit();
					}

				} else if (pg_type === 'lg') {

					f.LGD_PRODUCTINFO.value = good_name;
					f.LGD_BUYER.value = f.mem_realname.value;
					f.LGD_BUYEREMAIL.value = f.mem_email.value;
					f.LGD_BUYERPHONE.value = f.mem_phone.value;
					f.LGD_AMOUNT.value = f.good_mny.value;
					f.LGD_RECEIVER.value = f.mem_realname.value;
					f.LGD_RECEIVERPHONE.value = f.mem_phone.value;

					if (f.LGD_CUSTOM_FIRSTPAY.value !== '무통장') {
						Pay_Request(payment_unique_id, f.LGD_AMOUNT.value, f.LGD_TIMESTAMP.value);
						return false;
					} else {
						f.submit();

					}

				} else if (pg_type === 'inicis') {

					f.buyername.value = f.mem_realname.value;
					f.buyeremail.value = f.mem_email.value;
					f.buyertel.value	= f.mem_phone.value;
					f.recvname.value	= f.mem_realname.value;
					f.recvtel.value	 = f.mem_phone.value;
					
					f.price.value = f.good_mny.value;

					if (f.gopaymethod.value !== '무통장') {

						var order_data = $(f).serialize();
						var save_result = "";
						$.ajax({
							type: "POST",
							data: order_data,
							url: cb_url + '/payment/orderdatasave',
							cache: false,
							async: false,
							success: function(data) {
								save_result = data;
							}
						});

						if (save_result) {
							alert(save_result);
							return false;
						}

						if ( ! make_signature(f))
							return false;

							paybtn(f);
					} else {
						if (typeof ori_action_url !== "undefined" && f.action != ori_action_url ) {
							f.action = ori_action_url;
							f.removeAttribute("target");
							f.removeAttribute("accept-charset");
						}
						f.submit();
					}
				}
			} else {
				f.submit();
			}

		} else {

			$('#display_pay_button').hide();
			$('#show_progress').show();
			setTimeout(function() {
				f.submit();
			}, 300);

		}
	}

	if (cb_device_type === 'mobile') {
		$(document).ready(function(){
			$('#show_req_btn').hide();
			$('#show_pay_btn').show();
		});
		$(document).on('click', '#pay_type_bank', function() {
			$('#show_req_btn').hide();
			$('#show_pay_btn').show();
		});
		$(document).on('click', '#pay_type_card,#pay_type_realtime,#pay_type_vbank,#pay_type_phone', function() {
			$('#show_req_btn').show();
			$('#show_pay_btn').hide();
		});
	}
}
