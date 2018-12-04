<div class="box">
	<div class="box-table">
		<?php
		echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
		$attributes = array('class' => 'form-horizontal', 'name' => 'fadminwrite', 'id' => 'fadminwrite');
		echo form_open(current_full_url(), $attributes);
		?>
			<input type="hidden" name="<?php echo element('primary_key', $view); ?>"	value="<?php echo element(element('primary_key', $view), element('data', $view)); ?>" />
			<div class="form-group">
				<label class="col-sm-2 control-label">회원정보</label>
				<div class="col-sm-10 form-inline">
					<?php echo html_escape(element('mem_nickname', element('data', $view))); ?>
					( <?php echo html_escape(element('mem_userid', element('member', element('data', $view)))); ?> )
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label"><?php echo $this->cbconfig->item('deposit_name'); ?> 충전</label>
				<div class="col-sm-10">
					<?php echo number_format(element('dep_deposit_request', element('data', $view))); ?> <?php echo html_escape($this->cbconfig->item('deposit_unit')); ?>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">결제해야할 금액</label>
				<div class="col-sm-10">
					<?php echo number_format(element('dep_cash_request', element('data', $view))); ?> 원
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">결제상태</label>
				<div class="col-sm-10">
					<label class="radio-inline" for="dep_cash_status_1" >
						<input type="radio" name="dep_cash_status" class="dep_cash_status" id="dep_cash_status_1" value="not" <?php echo set_checkbox('dep_cash_status', 'not', ((int) element('dep_cash', element('data', $view)) === 0 ? true : false)); ?> /> 미납
					</label>
					<label class="radio-inline" for="dep_cash_status_2" >
						<input type="radio" name="dep_cash_status" class="dep_cash_status" id="dep_cash_status_2" value="some" <?php echo set_checkbox('dep_cash_status', 'some', ((element('dep_cash_request', element('data', $view)) > element('dep_cash', element('data', $view)) && element('dep_cash', element('data', $view))) ? true : false)); ?> /> 일부납
					</label>
					<label class="radio-inline" for="dep_cash_status_3" >
						<input type="radio" name="dep_cash_status" class="dep_cash_status" id="dep_cash_status_3" value="all" <?php echo set_checkbox('dep_cash_status', 'all', ((int) element('dep_cash_request', element('data', $view)) === (int) element('dep_cash', element('data', $view)) ? true : false)); ?> /> 완납
					</label>
					<div class="help-block">완납으로 변경시 예치금이 자동으로 충전완료됩니다</div>
				</div>
			</div>
			<div class="form-group some_cash">
				<label class="col-sm-2 control-label">실제결제한 금액</label>
				<div class="col-sm-10 form-inline">
					<input type="number" class="form-control" name="dep_cash" id="dep_cash" value="<?php echo set_value('dep_cash', (int) element('dep_cash', element('data', $view))); ?>" /> 원
				</div>
			</div>
			<div class="form-group approve_datetime">
				<label class="col-sm-2 control-label">결제일시</label>
				<div class="col-sm-10 form-inline">
					<input type="text" class="form-control" name="dep_deposit_datetime" id="dep_deposit_datetime" value="<?php echo set_value('dep_deposit_datetime', (element('dep_deposit_datetime', element('data', $view)) > '0000-00-00 00:00:00' ? element('dep_deposit_datetime', element('data', $view)):cdate('Y-m-d H:i:s'))); ?>" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">관리자 메모</label>
				<div class="col-sm-10">
					<textarea class="form-control" rows="5" name="dep_admin_memo"><?php echo set_value('dep_admin_memo', element('dep_admin_memo', element('data', $view))); ?></textarea>
				</div>
			</div>
			<div class="btn-group pull-right" role="group" aria-label="...">
				<button type="submit" class="btn btn-outline btn-success btn-sm">저장하기</button>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function() {
	if ($('.dep_cash_status:checked').val() === 'not') {
		$('.some_cash').hide();
		$('.approve_datetime').hide();
	}
	if ($('.dep_cash_status:checked').val() === 'some') {
		$('.some_cash').show();
		$('.approve_datetime').hide();
	}
	if ($('.dep_cash_status:checked').val() === 'all') {
		$('.some_cash').hide();
		$('.approve_datetime').show();
	}
});
$(document).on('click', '.dep_cash_status', function() {
	if ($(this).val() === 'not') {
		$('.some_cash').hide();
		$('.approve_datetime').hide();
	}
	if ($(this).val() === 'some') {
		$('.some_cash').show();
		$('.approve_datetime').hide();
	}
	if ($(this).val() === 'all') {
		$('.some_cash').hide();
		$('.approve_datetime').show();
	}
});
//]]>
</script>
