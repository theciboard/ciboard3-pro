<?php $this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css'); ?>

<div class="modal-header">
	<h4 class="modal-title"><?php echo $this->cbconfig->item('deposit_name'); ?>을(를) 포인트로 전환</h4>
</div>
<div class="modal-body">
	<?php
	echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
	echo show_alert_message(element('message', $view), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
	$attributes = array('class' => 'form-horizontal', 'name' => 'fwrite', 'id' => 'fwrite');
	echo form_open(current_full_url(), $attributes);
	?>
		<div class="credit">
			<div class="credit_method">
				<div><?php echo $this->cbconfig->item('deposit_name'); ?> <span class="textblue"><?php echo number_format((int) $this->member->item('total_deposit')); ?><?php echo $this->cbconfig->item('deposit_unit'); ?></span>중, <input type="number" class="form-control px100" id="deposit" name="deposit" value="0" onkeydown="if (event.keyCode ==13) return false;" onkeyup="num_deposit_to_point()" /><?php echo $this->cbconfig->item('deposit_unit'); ?> 전환</div>
			</div>
			<div class="credit_method">
				<div>전환되는 포인트 <span class="textblue" id="to_point">0</span></div>
			</div>
			<div class="credit_button">
				<button class="btn btn-default" type="submit" role="button">확인</button>
				<a class="btn btn-default" href="javascript:;" role="button" onClick="window.close();">취소</a>
			</div>
			<p><i class="fa fa-dot-circle-o"></i> <?php echo $this->cbconfig->item('deposit_name'); ?>를 포인트로 전환하실 수 있습니다</p>
			<p><i class="fa fa-dot-circle-o"></i> <?php echo $this->cbconfig->item('deposit_name'); ?> 1<?php echo $this->cbconfig->item('deposit_unit'); ?>당 <?php echo $this->cbconfig->item('deposit_refund_point'); ?>포인트로 전환됩니다</p>
			<?php if ($this->cbconfig->item('deposit_refund_point_min')) { ?>
				<p><i class="fa fa-dot-circle-o"></i> 최소 <?php echo $this->cbconfig->item('deposit_refund_point_min'); ?><?php echo $this->cbconfig->item('deposit_unit'); ?> 이상 전환이 가능합니다</p>
			<?php } ?>
		</div>
	<?php echo form_close(); ?>
</div>
<script type="text/javascript">
//<![CDATA[
$(function() {
	$('#fwrite').validate({
		rules: {
			deposit: {required :true, number:true, min:1}
		}
	});
});
function num_deposit_to_point() {
	var deposit = parseInt($('#deposit').val());
	if ( ! deposit) deposit = 0;
	point = Math.floor(deposit * <?php echo $this->cbconfig->item('deposit_refund_point'); ?>);
	result = '' + point;
	$('#to_point').text(result);
}
//]]>
</script>
