<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// 전자결제를 사용할 때만 실행
if ($this->cbconfig->item('use_payment_card')
	OR $this->cbconfig->item('use_payment_realtime')
	OR $this->cbconfig->item('use_payment_vbank')
	OR $this->cbconfig->item('use_payment_phone')
	OR $this->cbconfig->item('use_payment_easy')) {

} else {
	return;
}
?>

<script language="javascript" type="text/javascript" src="<?php echo element('ini_js_url', element('pg', $view)); ?>" charset="UTF-8"></script>

<script language="javascript">
function make_signature(frm)
{
	// 데이터 암호화 처리
	var result = true,
		price_value = parseInt(frm.price.value);

	if( isNaN(price_value) ){
		price_value = parseInt(frm.good_mny.value);
	}

	jQuery.ajax({
		url: cb_url + '/payment/inicis_makesignature',
		type: "POST",
		data: {
			price : price_value,
			csrf_test_name: cb_csrf_hash
		},
		dataType: "json",
		async: false,
		cache: false,
		success: function(data) {
			if(data.error == "") {
				frm.timestamp.value = data.timestamp;
				frm.signature.value = data.sign;
				frm.mKey.value = data.mKey;
			} else {
				alert(data.error);
				result = false;
			}
		}
	});

	return result;
}

function paybtn(f) {
	INIStdPay.pay(f.id);
}
</script>