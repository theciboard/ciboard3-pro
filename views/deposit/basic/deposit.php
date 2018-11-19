<?php $this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css'); ?>
<?php
if ($this->cbconfig->item('use_deposit_cash_to_deposit')) {
	$sform['view'] = $view;
	if ($this->cbconfig->item('use_payment_pg') && element('use_pg', $view)) {
		$this->load->view('paymentlib/' . $this->cbconfig->item('use_payment_pg') . '/' . element('form1name', $view), $sform);
	}
?>
<h3><?php echo $this->cbconfig->item('deposit_name'); ?> 충전</h3>

<?php
$attributes = array('class' => 'form-horizontal', 'name' => 'fpayment', 'id' => 'fpayment', 'autocomplete' => 'off');
echo form_open(site_url('deposit/update'), $attributes);
if ($this->cbconfig->item('use_payment_pg') && element('use_pg', $view)) {
	$this->load->view('paymentlib/' . $this->cbconfig->item('use_payment_pg') . '/' . element('form2name', $view), $sform);
}
?>
	<input type="hidden" name="deposit_real" value="0" />
	<input type="hidden" name="unique_id" value="<?php echo element('unique_id', $view); ?>" />
	<input type="hidden" name="good_mny" value="0" />

	<div class="credit">
		<p class="credit_tit">현재 <?php echo $this->cbconfig->item('deposit_name'); ?> 소유 : <span class="textblue"><?php echo number_format((int) $this->member->item('total_deposit')); ?> <?php echo $this->cbconfig->item('deposit_unit'); ?></span></p>
			<table class="table table-striped table-bordered table-hover">
				<thead>
					<tr>
						<th class="text-center">선택</th>
						<th class="text-center">결제금액</th>
						<th class="text-center">충전금액</th>
					</tr>
				</thead>
				<tbody>
				<?php
				if (element('cashtodep', $view)) {
					foreach (element('cashtodep', $view) as $key => $val) {
						if ( ! element('0', $val) OR ! element('1', $val)) {
							continue;
						}
				?>
					<tr>
						<td class="text-center">
							<label class=" btn-block" for="charge_<?php echo element('0', $val); ?>"><input type="radio" name="money_value" value="<?php echo element('0', $val); ?>" id="charge_<?php echo element('0', $val); ?>" /></label>
							<input type="hidden" name="deposit_value[]" value="<?php echo element('1', $val); ?>" />
						</td>
						<td class="text-right"><label class=" btn-block" for="charge_<?php echo element('0', $val); ?>"><?php echo number_format(element('0', $val));?> 원</label></td>
						<td class="text-right"><label class=" btn-block" for="charge_<?php echo element('0', $val); ?>"><?php echo number_format(element('1', $val));?> <?php echo $this->cbconfig->item('deposit_unit'); ?></label></td>
					</tr>
				<?php
					}
				}
				?>
				</tbody>
			</table>

			<h4>고객정보</h4>
			<table class="table table-striped table-bordered table-hover">
				<tbody>
					<tr>
						<td class="text-center">실명</td>
						<td class="form-inline"><input type="text" name="mem_realname" class="input" value="<?php echo $this->member->item('mem_nickname'); ?>" /></td>
					</tr>
					<tr>
						<td class="text-center">이메일</td>
						<td><input type="email" name="mem_email" class="input" value="<?php echo $this->member->item('mem_email'); ?>" /></td>
					</tr>
					<tr>
						<td class="text-center">휴대폰</td>
						<td class="form-inline"><input type="text" name="mem_phone" class="input" value="<?php echo $this->member->item('mem_phone'); ?>" /></td>
					</tr>
				</tbody>
			</table>
			<div class="credit_form">
				<label>결제방법</label>
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
			<div class="alert alert-success bank-info">
				<div><strong>계좌안내</strong></div>
				<div><?php echo nl2br($this->cbconfig->item('payment_bank_info')); ?> </div>
			</div>
			<?php
			if ($this->cbconfig->item('use_payment_pg')) {
				$this->load->view('paymentlib/' . $this->cbconfig->item('use_payment_pg') . '/' . element('form3name', $view), $sform);
				}
			?>
			<?php if ($this->cbconfig->item('deposit_charge_point')) { ?>
				<p><i class="fa fa-dot-circle-o"></i> 결제시, 결제 금액의 <?php echo $this->cbconfig->item('deposit_charge_point'); ?>% 가 포인트로 적립됩니다.</p>
			<?php } ?>
	</div>
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
<?php
	echo form_close();
}

if ($this->cbconfig->item('use_deposit_point_to_deposit')) {
?>
<div class="page-header">
	<h4>포인트로 <?php echo $this->cbconfig->item('deposit_name'); ?> 충전</h4>
</div>
<div class="credit">
	<p class="credit_tit">현재 포인트 : <span class="textblue"><?php echo number_format($this->member->item('mem_point')); ?> 포인트</span></p>
	<div class="credit_form">
		<a class="btn btn-default" href="javascript:;" role="button" onClick="open_point_to_deposit();" title="포인트를 <?php echo $this->cbconfig->item('deposit_name'); ?>(으)로 전환하기"><i class="fa fa-refresh"></i> 전환하기</a>
	</div>
	<p><i class="fa fa-dot-circle-o"></i> 포인트로 <?php echo $this->cbconfig->item('deposit_name'); ?> 충전이 가능합니다</p>
	<?php if ($this->cbconfig->item('deposit_point_min')) {?>
		<p><i class="fa fa-dot-circle-o"></i> <?php echo $this->cbconfig->item('deposit_point_min'); ?> 포인트 이상부터 충전 가능합니다.</p>
	<?php } ?>
	<p><i class="fa fa-dot-circle-o"></i> 포인트 <?php echo $this->cbconfig->item('deposit_point'); ?> 점당 <?php echo $this->cbconfig->item('deposit_name'); ?> 1<?php echo $this->cbconfig->item('deposit_unit'); ?> (으)로 전환됩니다.</p>
</div>
<?php
}

if ($this->cbconfig->item('use_deposit_deposit_to_point')) {
?>
<div class="page-header">
	<h4><?php echo $this->cbconfig->item('deposit_name'); ?>을(를) 포인트로 전환</h4>
</div>
<div class="credit">
	<p class="credit_tit">현재 <?php echo $this->cbconfig->item('deposit_name'); ?> 소유 : <span class="textblue"><?php echo number_format((int) $this->member->item('total_deposit')); ?> <?php echo $this->cbconfig->item('deposit_unit'); ?></span></p>
	<div class="credit_form">
		<a class="btn btn-default" href="javascript:;" role="button" onClick="open_deposit_to_point();" title="<?php echo $this->cbconfig->item('deposit_name'); ?>(을)를 포인트로 전환하기"><i class="fa fa-refresh"></i> 전환하기</a>
	</div>
	<p><i class="fa fa-dot-circle-o"></i> <?php echo $this->cbconfig->item('deposit_name'); ?>(을)를 포인트로 전환하실 수 있습니다</p>
	<p><i class="fa fa-dot-circle-o"></i> <?php echo $this->cbconfig->item('deposit_name'); ?> 1<?php echo $this->cbconfig->item('deposit_unit'); ?>당 <?php echo $this->cbconfig->item('deposit_refund_point'); ?>포인트로 전환됩니다</p>
	<?php if ($this->cbconfig->item('deposit_refund_point_min')) { ?>
		<p><i class="fa fa-dot-circle-o"></i> 최소 <?php echo $this->cbconfig->item('deposit_refund_point_min'); ?><?php echo $this->cbconfig->item('deposit_unit'); ?> 이상 전환이 가능합니다</p>
	<?php } ?>
</div>
<?php
}
?>

<div class="page-header">
	<h4><?php echo $this->cbconfig->item('deposit_name'); ?> 최근 변동내역</h4>
</div>
<div class="credit">
	<div class="credit_info">
		<span class="pull-left">현재 나의 <?php echo $this->cbconfig->item('deposit_name'); ?> : <?php echo number_format((int) $this->member->item('total_deposit')); ?> <?php echo $this->cbconfig->item('deposit_unit'); ?></span>
		<span class="pull-right"><a href="<?php echo site_url('deposit/mylist'); ?>" title="나의 <?php echo $this->cbconfig->item('deposit_name'); ?> 최근 변동내역 보기">더보기</a></span>
	</div>
	<table class="table table-striped">
		<thead>
			<tr>
				<th>날짜</th>
				<th class="text-center">충전</th>
				<th class="text-center">사용</th>
				<th class="text-center">잔액</th>
				<th class="col-md-6 col-md-offset-1">내용</th>
			</tr>
		</thead>
		<tbody>
		<?php
		if (element('list', $view)) {
			foreach (element('list', $view) as $result) {
		?>
			<tr>
				<td><?php echo display_datetime(element('dep_deposit_datetime', $result)); ?></td>
				<td class="text-right text-primary"><?php if (element('dep_deposit', $result) >= 0) { echo number_format(element('dep_deposit', $result)) . ' ' . html_escape($this->cbconfig->item('deposit_unit')); } ?></td>
				<td class="text-right text-danger"><?php if (element('dep_deposit', $result) < 0) { echo number_format(abs(element('dep_deposit', $result))) . ' ' . html_escape($this->cbconfig->item('deposit_unit')); } ?></td>
				<td class="text-right"><?php echo number_format(element('dep_deposit_sum', $result)) . ' ' . html_escape($this->cbconfig->item('deposit_unit')); ?></td>
				<td><?php echo nl2br(html_escape(element('dep_content', $result))); ?></td>
			</tr>
		<?php
			}
		}
		if ( ! element('list', $view)) {
		?>
			<tr>
				<td colspan="5" class="nopost">자료가 없습니다</td>
			</tr>
		<?php
		}
		?>
		</tbody>
	</table>
</div>

<script type="text/javascript">
var use_pg = '<?php echo element('use_pg', $view) ? '1' : ''; ?>';
var pg_type = '<?php echo $this->cbconfig->item('use_payment_pg'); ?>';
var payment_unique_id = '<?php echo element('unique_id', $view); ?>';
var good_name = '<?php echo html_escape(element('good_name', $view)); ?>';
var ptype = 'deposit';
</script>
<script type="text/javascript" src="<?php echo base_url('assets/js/deposit.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/payment.js'); ?>"></script>
<?php
if ($this->cbconfig->item('use_payment_pg') && element('use_pg', $view)) {
	$this->load->view('paymentlib/' . $this->cbconfig->item('use_payment_pg') . '/' . element('form4name', $view), $sform);
}
