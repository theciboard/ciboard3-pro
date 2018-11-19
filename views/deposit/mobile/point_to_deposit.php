<?php $this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css'); ?>

<div class="modal-header">
	<h4 class="modal-title">포인트로 <?php echo $this->cbconfig->item('deposit_name'); ?> 충전</h4>
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
				<div>포인트 <span class="textblue"><?php echo number_format($this->member->item('mem_point')); ?>점</span>중, <input type="number" class="input px100" id="point" name="point" value="0" onkeydown="if (event.keyCode ==13) return false;" onkeyup="num_point_to_deposit()" />점을 전환</div>
			</div>
			<div class="credit_method">
				<div>전환되는 <?php echo $this->cbconfig->item('deposit_name'); ?> <span class="textblue" id="to_deposit">0</span></div>
			</div>
			<div class="credit_button">
				<button class="btn btn-default" type="submit" role="button">확인</button>
				<a class="btn btn-default" href="javascript:;" role="button" onClick="window.close();">취소</a>
			</div>
				<p><i class="fa fa-dot-circle-o"></i> 포인트로 <?php echo $this->cbconfig->item('deposit_name'); ?> 충전이 가능합니다</p>
				<?php if ($this->cbconfig->item('deposit_point_min')) {?>
					<p><i class="fa fa-dot-circle-o"></i> <?php echo $this->cbconfig->item('deposit_point_min'); ?> 포인트 이상부터 전환 가능합니다.</p>
				<?php } ?>
				<p><i class="fa fa-dot-circle-o"></i> 포인트 <?php echo $this->cbconfig->item('deposit_point'); ?> 점당 <?php echo $this->cbconfig->item('deposit_name'); ?> 1<?php echo $this->cbconfig->item('deposit_unit'); ?> (으)로 전환됩니다.</p>
		</div>
	<?php echo form_close(); ?>
</div>
<script type="text/javascript">
//<![CDATA[
$(function() {
	$('#fwrite').validate({
		rules: {
			point: {required :true, number:true, min:<?php echo $this->cbconfig->item('deposit_point_min') ? $this->cbconfig->item('deposit_point_min'):1; ?>}
		}
	});
});
function num_point_to_deposit() {
	var point = parseInt($('#point').val());
	if ( ! point) point = 0;
	deposit = Math.floor(point / <?php echo $this->cbconfig->item('deposit_point'); ?>);
	result = '' + deposit;
	$('#to_deposit').text(result);
}
//]]>
</script>
