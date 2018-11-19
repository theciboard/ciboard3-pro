// 인증체크
function selfcert_confirm()
{
	var type;
	var val = $("#selfcert_type").val();

	switch(val) {
		case "ipin":
			type = "아이핀";
			break;
		case "phone":
			type = "휴대폰";
			break;
		default:
			return true;
	}

	if(confirm("이미 " + type + "으로 본인확인을 완료하셨습니다.\n\n이전 인증을 취소하고 다시 인증하시겠습니까?"))
		return true;
	else
		return false;
}

// 아이핀인증
$(document).on('click', '#btn_mem_selfcert_ipin', function() {
	if( ! selfcert_confirm())
		return false;
	
	var openurl = cb_url + '/selfcert/ipin';

	if ($(this).data('redirecturl')) {
		openurl += '?redirecturl=' + $(this).data('redirecturl');
	}
	
	var popupWindow = window.open( openurl, 'selfcert-ipin', 'left=200, top=100, status=0, width=450, height=550' );
	popupWindow.focus();
	return;
});

// 휴대폰인증
$(document).on('click', '#btn_mem_selfcert_phone', function() {
	if( ! selfcert_confirm())
		return false;

	var openurl = cb_url + '/selfcert/phone';

	if ($(this).data('redirecturl')) {
		openurl += '?redirecturl=' + $(this).data('redirecturl');
	}
	
	var popupWindow = window.open( openurl, 'auth_popup', 'left=200, top=100, status=0, width=450, height=550' );
	popupWindow.focus();
	return;
});
