<div class="box">
	<div class="box-header">
		<ul class="nav nav-tabs">
			<li role="presentation" class="active"><a href="<?php echo admin_url($this->pagedir); ?>" onclick="return check_form_changed();">기본설정</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/layout'); ?>" onclick="return check_form_changed();">레이아웃/메타태그</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/access'); ?>" onclick="return check_form_changed();">권한관리</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/editor'); ?>" onclick="return check_form_changed();">에디터기능</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/smsconfig'); ?>" onclick="return check_form_changed();">SMS 설정</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/paymentconfig'); ?>" onclick="return check_form_changed();">결제기능</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/alarm'); ?>" onclick="return check_form_changed();">알림설정</a></li>
		</ul>
	</div>
	<div class="box-table">

		<?php
		echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
		echo show_alert_message(element('alert_message', $view), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
		$attributes = array('class' => 'form-horizontal', 'name' => 'fadminwrite', 'id' => 'fadminwrite');
		echo form_open(current_full_url(), $attributes);
		?>
			<input type="hidden" name="is_submit" value="1" />
			<div class="form-group">
				<label class="col-sm-2 control-label">컨텐츠몰 기능 사용</label>
				<div class="col-sm-10">
					<label for="use_cmall" class="checkbox-inline">
					<input type="checkbox" name="use_cmall" id="use_cmall" value="1" <?php echo set_checkbox('use_cmall', '1', (element('use_cmall', element('data', $view)) ? true : false)); ?> /> 사용합니다
					</label>
					<div class="help-inline">페이지 주소 : <a href="<?php echo site_url('cmall'); ?>" target="_blank"><?php echo site_url('cmall'); ?></a></div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">컨텐츠몰명</label>
				<div class="col-sm-10 form-inline">
						<input type="text" class="form-control" name="cmall_name" value="<?php echo set_value('cmall_name', element('cmall_name', element('data', $view))); ?>" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">예치금으로 상품구매 가능</label>
				<div class="col-sm-10">
					<div class="form-group">
					<label for="use_cmall_deposit_to_contents" class="checkbox-inline">
					<input type="checkbox" name="use_cmall_deposit_to_contents" id="use_cmall_deposit_to_contents" value="1" <?php echo set_checkbox('use_cmall_deposit_to_contents', '1', (element('use_cmall_deposit_to_contents', element('data', $view)) ? true : false)); ?> /> 가능합니다
					</label>
				</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">장바구니 보관기간</label>
				<div class="col-sm-10">
					<input type="number" class="form-control" name="cmall_cart_keep_days" value="<?php echo set_value('cmall_cart_keep_days', (int) element('cmall_cart_keep_days', element('data', $view))); ?>" /> 일
				</div>
			</div>
			<div class="btn-group pull-right" role="group" aria-label="...">
				<button type="submit" class="btn btn-success btn-sm">저장하기</button>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>

<script type="text/javascript">
//<![CDATA[
$(function() {
	$('#fadminwrite').validate({
		rules: {
			cmall_name: {required :true},
			cmall_cart_keep_days: {required:true, number:true},
			cmall_payment_point: {required:true, number:true, min:0, max:100}
		}
	});
});

var form_original_data = $('#fadminwrite').serialize();
function check_form_changed() {
	if ($('#fadminwrite').serialize() !== form_original_data) {
		if (confirm('저장하지 않은 정보가 있습니다. 저장하지 않은 상태로 이동하시겠습니까?')) {
			return true;
		} else {
			return false;
		}
	}
	return true;
}
//]]>
</script>
