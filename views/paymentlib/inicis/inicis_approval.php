<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<form name="fpayment" method="post" action="<?php echo element('order_action_url', $view); ?>" autocomplete="off">

<?php
$exclude = array('res_cd', 'P_HASH', 'P_TYPE', 'P_AUTH_DT', 'P_AUTH_NO', 'P_HPP_CORP', 'P_APPL_NUM', 'P_VACT_NUM', 'P_VACT_NAME', 'P_VACT_BANK', 'P_CARD_ISSUER', 'P_UNAME', 'csrf_test_name');

foreach (element('data', $view) as $key => $value) {
	if (in_array($key, $exclude)) {
		continue;
	}
	if (is_array($value)) {
		foreach ($value as $k => $v) {
			echo '<input type="hidden" name="' . $key . '[' . $k . ']" value="' . $v . '" />' . PHP_EOL;
		}
	} else {
		echo '<input type="hidden" name="' . $key . '" value="' . $value . '" />' . PHP_EOL;
	}
}
?>
	<input type="hidden" name="csrf_test_name" value="<?php echo $this->security->get_csrf_hash(); ?>" style="display:none;" />
	<input type="hidden" name="res_cd" value="<?php echo element('P_STATUS', element('PAY', $view)); ?>" />
	<input type="hidden" name="P_HASH" value="<?php echo element('hash', $view); ?>" />
	<input type="hidden" name="P_TYPE" value="<?php echo element('P_TYPE', element('PAY', $view)); ?>" />
	<input type="hidden" name="P_AUTH_DT" value="<?php echo element('P_AUTH_DT', element('PAY', $view)); ?>" />
	<input type="hidden" name="P_AUTH_NO" value="<?php echo element('P_AUTH_NO', element('PAY', $view)); ?>" />
	<input type="hidden" name="P_HPP_CORP" value="<?php echo element('P_HPP_CORP', element('PAY', $view)); ?>" />
	<input type="hidden" name="P_APPL_NUM" value="<?php echo element('P_APPL_NUM', element('PAY', $view)); ?>" />
	<input type="hidden" name="P_VACT_NUM" value="<?php echo element('P_VACT_NUM', element('PAY', $view)); ?>" />
	<input type="hidden" name="P_VACT_NAME" value="<?php echo iconv('euc-kr', 'utf-8', element('P_VACT_NAME', element('PAY', $view))); ?>" />
	<input type="hidden" name="P_VACT_DATE" value="<?php echo element('P_VACT_DATE', element('PAY', $view)); ?>" />
	<input type="hidden" name="P_VACT_BANK" value="<?php echo element(element('P_VACT_BANK_CODE', element('PAY', $view)), element('BANK_CODE', element('pg', $view))); ?>" />
	<input type="hidden" name="P_CARD_ISSUER" value="<?php echo element(element('P_CARD_ISSUER_CODE', element('PAY', $view)), element('CARD_CODE', element('pg', $view))); ?>" />
	<input type="hidden" name="P_UNAME" value="<?php echo iconv('euc-kr', 'utf-8', element('P_UNAME', element('PAY', $view))); ?>" />

</form>

<div id="display_pay_process">
	<img src="<?php echo site_url(VIEW_DIR . 'paymentlib/images/ajax-loader.gif'); ?>" alt="주문완료중" title="주문완료중" />
	<span>주문완료 중입니다. 잠시만 기다려 주십시오.</span>
</div>

<script type="text/javascript">
function setPAYResult() {
	setTimeout( function() {
		document.fpayment.submit();
	}, 100);
}
</script>
