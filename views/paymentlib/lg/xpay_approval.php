<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="ko" xml:lang="ko">
<head>
<title>스마트폰 웹 결제창</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Cache-Control" content="No-Cache">
<meta http-equiv="Pragma" content="No-Cache">
<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=0,maximum-scale=10">
<meta name="HandheldFriendly" content="true">
<meta name="format-detection" content="telephone=no">
<body onload="launchCrossPlatform();">
<script language="javascript" src="http://xpay.uplus.co.kr/xpay/js/xpay_crossplatform.js" type="text/javascript"></script>
<script type="text/javascript">
/*
 * iframe으로 결제창을 호출하시기를 원하시면 iframe으로 설정 (변수명 수정 불가)
 */
var LGD_window_type = '<?php echo element('CST_WINDOW_TYPE', element('payReqMap', $view)); ?>';

/*
 * 수정불가
 */
function launchCrossPlatform() {
	lgdwin = open_paymentwindow(document.getElementById('LGD_PAYINFO'), '<?php echo element('CST_PLATFORM', element('payReqMap', $view)); ?>', LGD_window_type);
}
/*
 * FORM 명만 수정 가능
 */
function getFormObject() {
	return document.getElementById('LGD_PAYINFO');
}
</script>
<form method="post" name="LGD_PAYINFO" id="LGD_PAYINFO" action="">
	<?php
	foreach (element('payReqMap', $view) as $key => $value) {
		echo'"<input type="hidden" name="' . $key . '" id="' . $key . '" value="' . $value . '" />';
	}
	?>
</form>
