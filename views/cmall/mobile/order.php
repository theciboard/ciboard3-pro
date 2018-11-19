<?php $this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css'); ?>
<?php $this->managelayout->add_js(base_url('assets/js/cmallitem.js')); ?>

<h3>주문하기</h3>
<table class="table table-hover mt20">
	<thead>
		<tr class="success">
			<th>이미지</th>
			<th>상품명</th>
			<th>총수량</th>
			<th>판매가</th>
			<th>소계</th>
			<th>다운로드가능기간</th>
		</tr>
	</thead>
	<tbody>
	<?php
	$total_price_sum = 0;
	if (element('data', $view)) {
		foreach (element('data', $view) as $result) {
	?>
		<tr>
			<td><a href="<?php echo element('item_url', $result); ?>" title="<?php echo html_escape(element('cit_name', $result)); ?>"><img src="<?php echo thumb_url('cmallitem', element('cit_file_1', $result), 60, 60); ?>" class="thumbnail" style="margin:0;width:60px;height:60px;" alt="<?php echo html_escape(element('cit_name', $result)); ?>" title="<?php echo html_escape(element('cit_name', $result)); ?>" /></a></td>
			<td>
				<a href="<?php echo element('item_url', $result); ?>" title="<?php echo html_escape(element('cit_name', $result)); ?>"><?php echo html_escape(element('cit_name', $result)); ?></a>
				<ul class="cmall-options">
				<?php
				$total_num = 0;
				$total_price = 0;
				foreach (element('detail', $result) as $detail) {
				?>
					<li><?php echo html_escape(element('cde_title', $detail)) . ' ' . element('cct_count', $detail);?>개 (+<?php echo number_format(element('cde_price', $detail)); ?>원)</li>
				<?php
					$total_num += element('cct_count', $detail);
					$total_price += ((int) element('cit_price', $result) + (int) element('cde_price', $detail)) * element('cct_count', $detail);
				}
				$total_price_sum += $total_price;
				?>
				</ul>
			</td>
			<td><?php echo number_format($total_num); ?></td>
			<td><?php echo number_format(element('cit_price', $result)); ?></td>
			<td><?php echo number_format($total_price); ?><input type="hidden" name="total_price[<?php echo element('cit_id', $result); ?>]" value="<?php echo $total_price; ?>" /></td>
			<td><?php echo (element('cit_download_days', $result)) ? '구매후 ' . element('cit_download_days', $result) . '일간 ' : '기간제한없음'; ?></td>
		</tr>
	<?php
		}
	}
	if ( ! element('data', $view)) {
	?>
		<tr>
			<td colspan="6" class="nopost">주문내역이 비어있습니다</td>
		</tr>
	<?php
	}
	?>
	</tbody>
</table>
<div class="well well-sm mt20">
	결제해야할 금액 <div class="total_price"><span class="checked_price"><?php echo number_format($total_price_sum); ?></span> 원</div>
</div>

<?php
$sform['view'] = $view;
if ($this->cbconfig->item('use_payment_pg') && element('use_pg', $view)) {
	$this->load->view('paymentlib/' . $this->cbconfig->item('use_payment_pg') . '/' . element('form1name', $view), $sform);
}
$attributes = array('class' => 'form-horizontal', 'name' => 'fpayment', 'id' => 'fpayment', 'autocomplete' => 'off');
echo form_open(site_url('cmall/orderupdate'), $attributes);
if ($this->cbconfig->item('use_payment_pg') && element('use_pg', $view)) {
	$this->load->view('paymentlib/' . $this->cbconfig->item('use_payment_pg') . '/' . element('form2name', $view), $sform);
}
?>
	<input type="hidden" name="unique_id" value="<?php echo element('unique_id', $view); ?>" />
	<input type="hidden" name="total_price_sum" id="total_price_sum" value="<?php echo $total_price_sum; ?>" />
	<input type="hidden" name="good_mny" value="0" />

	<div class="market-order-person">
		<p class="market-title mt20">구매하시는 분</p>
		<div class="form-group">
			<label class="control-label">실명</label>
			<div>
				<input type="text" name="mem_realname" class="input" value="<?php echo $this->member->item('mem_nickname'); ?>" />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label">이메일</label>
			<div>
				<input type="email" name="mem_email" class="input" value="<?php echo $this->member->item('mem_email'); ?>" />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label">휴대폰</label>
			<div>
				<input type="text" name="mem_phone" class="input" value="<?php echo $this->member->item('mem_phone'); ?>" />
			</div>
		</div>
		<div class="form-group">
			<label class="control-label">하고싶은 말</label>
			<div>
				<textarea name="cor_content" class="input per90" cols="5"></textarea>
			</div>
		</div>
		<p class="market-title mt20">결제정보</p>
		<div class="form-group">
			<label class="col-lg-2 control-label">총 주문금액</label>
			<div class="col-lg-9">
				<strong><?php echo number_format($total_price_sum); ?>원</strong>
				<?php
				if ($this->cbconfig->item('use_deposit')) {
				?>
					<br /><br />
					보유<?php echo html_escape($this->cbconfig->item('deposit_name')); ?> : (<?php echo number_format((int) $this->member->item('total_deposit'));?> <?php echo html_escape($this->cbconfig->item('deposit_unit')); ?>)중
					최대
					<?php
					$max_deposit = min((int) $this->member->item('total_deposit'), $total_price_sum);
					echo number_format($max_deposit);
					echo html_escape($this->cbconfig->item('deposit_unit'));
					?>
					까지 사용 가능<br /><br />
					<input type="hidden" name="max_deposit" id="max_deposit" value="<?php echo $max_deposit; ?>" />
					사용<?php echo html_escape($this->cbconfig->item('deposit_name')); ?> : <input type="text" name="order_deposit" id="order_deposit" class="input" value="0" /> 원
				<?php } else { ?>
					<input type="hidden" name="order_deposit" id="order_deposit" class="input" value="0" />
				<?php }?>
			</div>
		</div>

		<div class="feedback-box mb20">
			<?php if ($this->cbconfig->item('use_payment_bank')) { ?>
				<label class="radio-inline" for="pay_type_bank" >
					<input type="radio" name="pay_type" value="bank" id="pay_type_bank" /> 무통장입금
				</label>
			<?php } ?>
			<?php if ($this->cbconfig->item('use_payment_card')) { ?>
				<label class="radio-inline" for="pay_type_card" >
					<input type="radio" name="pay_type" value="card" id="pay_type_card" /> 신용카드
				</label>
			<?php } ?>
			<?php if ($this->cbconfig->item('use_payment_realtime')) { ?>
				<label class="radio-inline" for="pay_type_realtime" >
					<input type="radio" name="pay_type" value="realtime" id="pay_type_realtime" /> 계좌이체
				</label>
			<?php } ?>
			<?php if ($this->cbconfig->item('use_payment_vbank')) { ?>
				<label class="radio-inline" for="pay_type_vbank" >
					<input type="radio" name="pay_type" value="vbank" id="pay_type_vbank" /> 가상계좌
				</label>
			<?php } ?>
			<?php if ($this->cbconfig->item('use_payment_phone')) { ?>
				<label class="radio-inline" for="pay_type_phone" >
					<input type="radio" name="pay_type" value="phone" id="pay_type_phone" /> 휴대폰결제
				</label>
			<?php } ?>
		</div>
	</div>
	<div class="alert alert-success bank-info">
		<div><strong>계좌안내</strong></div>
		<div><?php echo nl2br($this->cbconfig->item('payment_bank_info')); ?> </div>
	</div>
<?php
if ($this->cbconfig->item('use_payment_pg')) {
	$this->load->view('paymentlib/' . $this->cbconfig->item('use_payment_pg') . '/' . element('form3name', $view), $sform);
}
echo form_close();
?>

<script type="text/javascript">
//<![CDATA[
$(document).on('change', 'input[name= pay_type]', function() {
	if ($("input[name='pay_type']:checked").val() === 'bank') {
		$('.bank-info').show();
	} else {
		$('.bank-info').hide();
	}
});
//]]>
</script>

<script type="text/javascript">
var use_pg = '<?php echo element('use_pg', $view) ? '1' : ''; ?>';
var pg_type = '<?php echo $this->cbconfig->item('use_payment_pg'); ?>';
var payment_unique_id = '<?php echo element('unique_id', $view); ?>';
var good_name = '<?php echo html_escape(element('good_name', $view)); ?>';
var ptype = 'cmall';
</script>
<script type="text/javascript" src="<?php echo base_url('assets/js/payment.js'); ?>"></script>
<?php
if ($this->cbconfig->item('use_payment_pg') && element('use_pg', $view)) {
	$this->load->view('paymentlib/' . $this->cbconfig->item('use_payment_pg') . '/' . element('form4name', $view), $sform);
}
