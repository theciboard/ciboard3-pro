<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// kcp 전자결제를 사용할 때만 실행
if ($this->cbconfig->item('use_payment_card')
	OR $this->cbconfig->item('use_payment_realtime')
	OR $this->cbconfig->item('use_payment_vbank')
	OR $this->cbconfig->item('use_payment_phone')
	OR $this->cbconfig->item('use_payment_easy')) {

} else {
	return;
}
?>

<script language="javascript" src="<?php echo $this->input->server('SERVER_PORT') !== '443' ? 'http' : 'https'; ?>://xpay.uplus.co.kr/xpay/js/xpay_crossplatform.js" type="text/javascript"></script>

<script type="text/javascript">
//<![CDATA[

/*
* 수정불가.
*/
var LGD_window_type = "<?php echo element('LGD_WINDOW_TYPE', element('pg', $view)); ?>";

/*
* 수정불가
*/

function Pay_Request(od_id, amount, timestamp) {

	var frm = getFormObject();

	jQuery.ajax({
		url: "<?php echo site_url('payment/lg_pc_xpay_request/').element('ptype', $view); ?>",
		type: 'POST',
		cache: false,
		dataType: 'html',
		data: { LGD_OID : od_id, LGD_AMOUNT : amount, LGD_TIMESTAMP : timestamp, is_pc : '1', csrf_test_name: cb_csrf_hash },
		success: function(data) {
			
			if( !data ){
				alert('해시키를 받지 못했습니다.');
				return false;
			}
			
			frm.LGD_HASHDATA.value = data.LGD_HASHDATA;

			lgdwin = openXpay(frm, "<?php echo element('CST_PLATFORM', element('pg', $view)); ?>", LGD_window_type, null, "", "");
		}
	});
}

/*
* FORM 명만 수정 가능
*/
function getFormObject() {
	return document.getElementById("fpayment");
}

/*
 * 인증결과 처리
 */
function payment_return() {
	var fDoc;

	fDoc = lgdwin.contentWindow || lgdwin.contentDocument;

	var lg_pay_form = getFormObject();

	if (fDoc.document.getElementById('LGD_RESPCODE').value == "0000") {
		document.getElementById("LGD_PAYKEY").value = fDoc.document.getElementById('LGD_PAYKEY').value;
		lg_pay_form.target = "_self";
		
		<?php if ( 'deposit' === element('ptype', $view) ) { //예치금 action_url ?>
			lg_pay_form.action = "<?php echo site_url('deposit/update'); ?>";
		<?php } else { //컨텐츠몰 action_url ?>
			lg_pay_form.action = "<?php echo site_url('cmall/orderupdate'); ?>";
		<?php }	 //end if ?>

		lg_pay_form.submit();
	} else {
		lg_pay_form.target = "_self";

		<?php if ( 'deposit' === element('ptype', $view) ) { //예치금 action_url ?>
			lg_pay_form.action = "<?php echo site_url('deposit/update'); ?>";
		<?php } else { //컨텐츠몰 action_url ?>
			lg_pay_form.action = "<?php echo site_url('cmall/orderupdate'); ?>";
		<?php }	 //end if ?>

		alert("LGD_RESPCODE (결과코드) : " + fDoc.document.getElementById('LGD_RESPCODE').value + "\n" + "LGD_RESPMSG (결과메시지): " + fDoc.document.getElementById('LGD_RESPMSG').value);
		closeIframe();
	}
}

/*
 * 상점결제 인증요청후 PAYKEY를 받아서 최종결제 요청.
 */

/*
function doPay_ActiveX() {
	ret = xpay_check(document.getElementById('fpayment'), '<?php echo element('CST_PLATFORM', element('pg', $view)); ?>');

	if (ret === '00') { //ActiveX 로딩 성공
		var LGD_RESPCODE = dpop.getData('LGD_RESPCODE'); //결과코드
		var LGD_RESPMSG = dpop.getData('LGD_RESPMSG'); //결과메세지

		if ('0000' === LGD_RESPCODE) { //인증성공
			var LGD_PAYKEY = dpop.getData('LGD_PAYKEY'); //LG유플러스 인증KEY
			//var msg = "인증결과 : " + LGD_RESPMSG + "\n";
			//msg += "LGD_PAYKEY : " + LGD_PAYKEY +"\n\n";
			document.getElementById('LGD_PAYKEY').value = LGD_PAYKEY;
			//alert(msg);
			$('#display_pay_button').hide();
			$('#display_pay_process').show();
			document.getElementById('fpayment').submit();
		} else { //인증실패
			alert('인증이 실패하였습니다. ' + LGD_RESPMSG);
			return false;
		}
	} else {
		alert('LG유플러스 전자결제를 위한 ActiveX Control이 설치되지 않았습니다.');
		xpay_showInstall(); //설치안내 팝업페이지 표시 코드 추가
	}
}

function isActiveXOK() {
	if (lgdacom_atx_flag === true) {
		$('#display_pay_button').show();
	} else {
		$('#display_pay_button').hide();
	}
}

function Pay_Request(od_id, amount, timestamp) {
	$.ajax({
		url: '<?php echo site_url('payment/lg_markethashdata'); ?>',
		type: 'POST',
		cache: false,
		dataType: 'html',
		data: { LGD_OID : od_id, LGD_AMOUNT : amount, LGD_TIMESTAMP : timestamp, csrf_test_name: cb_csrf_hash },
		success: function(data) {
			$('#LGD_HASHDATA').val(data);
			doPay_ActiveX();
		}
	});
}
*/

//]]>
</script>