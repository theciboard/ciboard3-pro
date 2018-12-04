<div class="box">
	<div class="box-header">
		<ul class="nav nav-tabs">
			<li role="presentation" class="active"><a href="<?php echo admin_url($this->pagedir); ?>" onclick="return check_form_changed();">기본설정</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/layout'); ?>" onclick="return check_form_changed();">레이아웃/메타태그</a></li>
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
				<label class="col-sm-2 control-label">예치금 기능 사용</label>
				<div class="col-sm-10">
					<label for="use_deposit" class="checkbox-inline">
						<input type="checkbox" name="use_deposit" id="use_deposit" value="1" <?php echo set_checkbox('use_deposit', '1', (element('use_deposit', element('data', $view)) ? true : false)); ?> /> 사용합니다
					</label>
					<div class="help-inline">페이지 주소 : <a href="<?php echo site_url('deposit'); ?>" target="_blank"><?php echo site_url('deposit'); ?></a></div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">예치금 이름</label>
				<div class="col-sm-10 form-inline">
					<input type="text" class="form-control" name="deposit_name" value="<?php echo set_value('deposit_name', element('deposit_name', element('data', $view))); ?>" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">예치금 단위</label>
				<div class="col-sm-10 form-inline">
					<input type="text" class="form-control" name="deposit_unit" value="<?php echo set_value('deposit_unit', element('deposit_unit', element('data', $view))); ?>" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">현금/카드로 예치금 구매가능</label>
				<div class="col-sm-10 form-inline">
					<label for="use_deposit_cash_to_deposit" class="checkbox-inline">
						<input type="checkbox" name="use_deposit_cash_to_deposit" id="use_deposit_cash_to_deposit" value="1" <?php echo set_checkbox('use_deposit_cash_to_deposit', '1', (element('use_deposit_cash_to_deposit', element('data', $view)) ? true : false)); ?> /> 가능합니다
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">현금 충전시 금액</label>
				<div class="col-sm-10 form-inline">
					<textarea class="form-control" rows="7" name="deposit_cash_to_deposit_unit"><?php echo set_value('deposit_cash_to_deposit_unit', element('deposit_cash_to_deposit_unit', element('data', $view))); ?></textarea>
					<div class="help-block">예치금 충전금액을 결제금액:충전금액 형식으로 설정합니다. 충전금액이 여러 개 일 경우 엔터로 구분해 입력합니다. 예)<br />10000:11000<br />30000:33000</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">적립포인트</label>
				<div class="col-sm-10 form-inline">
					현금/카드로 예치금 구매시 결제금액의 <input type="text" class="form-control" name="deposit_charge_point" value="<?php echo set_value('deposit_charge_point', (int) element('deposit_charge_point', element('data', $view))); ?>" />% 가 포인트로 적립됩니다
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">포인트로 예치금 구매가능</label>
				<div class="col-sm-10 form-inline">
					<label for="use_deposit_point_to_deposit" class="checkbox-inline">
						<input type="checkbox" name="use_deposit_point_to_deposit" id="use_deposit_point_to_deposit" value="1" <?php echo set_checkbox('use_deposit_point_to_deposit', '1', (element('use_deposit_point_to_deposit', element('data', $view)) ? true : false)); ?> /> 가능합니다
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">포인트로 구매시</label>
				<div class="col-sm-10 form-inline">
					<input type="text" class="form-control" name="deposit_point" value="<?php echo set_value('deposit_point', (int) element('deposit_point', element('data', $view))); ?>" /> 포인트로 1 예치금 구매가 가능합니다. 최소 <input type="number" class="form-control" name="deposit_point_min" value="<?php echo set_value('deposit_point_min', (int) element('deposit_point_min', element('data', $view))); ?>" /> 포인트 이상으로 구매가 가능합니다
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">예치금을 포인트로 전환가능</label>
				<div class="col-sm-10 form-inline">
					<label for="use_deposit_deposit_to_point" class="checkbox-inline">
						<input type="checkbox" name="use_deposit_deposit_to_point" id="use_deposit_deposit_to_point" value="1" <?php echo set_checkbox('use_deposit_deposit_to_point', '1', (element('use_deposit_deposit_to_point', element('data', $view)) ? true : false)); ?> /> 가능합니다
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">포인트로 전환시</label>
				<div class="col-sm-10 form-inline">
					1 예치금을 <input type="text" class="form-control" name="deposit_refund_point" value="<?php echo set_value('deposit_refund_point', (int) element('deposit_refund_point', element('data', $view))); ?>" /> 포인트로 전환이 가능합니다. 최소 <input type="number" class="form-control" name="deposit_refund_point_min" value="<?php echo set_value('deposit_refund_point_min', (int) element('deposit_refund_point_min', element('data', $view))); ?>" /> 예치금 이상을 전환 가능합니다
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
			deposit_name: {required :true},
			deposit_unit: {required :true},
			deposit_charge_point: {required :true, number:true},
			deposit_point: {required :true, number:true},
			deposit_point_min: {required :true, number:true},
			deposit_refund_point: {required :true, number:true},
			deposit_refund_point_min: {required :true, number:true}
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
