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

<form name="sm_form" method="POST" action="" accept-charset="euc-kr">
	<input type="hidden" name="csrf_test_name" value="<?php echo $this->security->get_csrf_hash(); ?>" style="display:none;" />
	<input type="hidden" name="ptype" value="<?php echo element('ptype', $view); ?>" />

	<input type="hidden" name="P_OID" value="<?php echo element('unique_id', $view); ?>" />
	<input type="hidden" name="P_GOODS" value="" />
	<input type="hidden" name="P_AMT" value="" />
	<input type="hidden" name="P_UNAME" value="" />
	<input type="hidden" name="P_MOBILE" value="" />
	<input type="hidden" name="P_EMAIL" value="" />
	<input type="hidden" name="P_MID" value="<?php echo element('pg_inicis_mid', element('pg', $view)); ?>" />
	<input type="hidden" name="P_NEXT_URL" value="<?php echo site_url('payment/inicis_approval/' . element('ptype', $view)); ?>" />
	<input type="hidden" name="P_NOTI_URL" value="<?php echo site_url('payment/inicis_noti'); ?>" />
	<input type="hidden" name="P_RETURN_URL" value="" />
	<input type="hidden" name="P_HPP_METHOD" value="2" />
	<input type="hidden" name="P_RESERVED" value="bank_receipt=N&twotrs_isp=Y&block_isp=Y" />
	<input type="hidden" name="P_NOTI" value="<?php echo element('unique_id', $view); ?>" />
</form>
