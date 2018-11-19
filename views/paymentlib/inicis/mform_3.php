<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div id="display_pay_button">
	<div class="form-group text-center">
		<span id="show_req_btn"><button type="button" name="submitChecked" onClick="pay_approval();" class="btn btn-primary">결제등록</button></span>
		<span id="show_pay_btn" style="display:none;"><button type="button" onClick="fpayment_check();" class="btn btn-order">주문하기</button></span>
	</div>
</div>
