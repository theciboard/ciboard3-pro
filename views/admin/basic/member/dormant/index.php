<div class="box">
	<div class="box-header">
		<ul class="nav nav-tabs">
			<li role="presentation" class="active"><a href="<?php echo admin_url($this->pagedir); ?>" onclick="return check_form_changed();">기본정보</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/cleantodormant'); ?>" onclick="return check_form_changed();">휴면계정일괄정리</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/emailtowaiting'); ?>" onclick="return check_form_changed();">안내메일일괄발송</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/emailcontent'); ?>" onclick="return check_form_changed();">안내메일내용</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/emailsendlist'); ?>" onclick="return check_form_changed();">안내메일발송내역</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/waitinglist'); ?>" onclick="return check_form_changed();">휴면처리해야할회원</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/dormantlist'); ?>" onclick="return check_form_changed();">휴면중인회원</a></li>
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
				<label class="col-sm-2 control-label">휴면계정 전환 조건</label>
				<div class="col-sm-10 form-inline">
					<select name="member_dormant_days" class="form-control">
					<?php foreach ($this->dormant_days_text as $key => $value) { ?>
						<option value="<?php echo $key; ?>" <?php echo set_select('member_dormant_days', $key, (element('member_dormant_days', element('data', $view)) === (string) $key ? true : false)); ?> ><?php echo $value;?>간 한번도 로그인하지 않은 회원</option>
					<?php } ?>
					</select>
					<span class="help-inline">해당 기간 로그인하지 않는 회원은 휴면계정으로 전환됩니다</span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">휴면계정 정리 방법</label>
				<div class="col-sm-10">
					<label class="radio-inline" for="member_dormant_method_delete" >
						<input type="radio" name="member_dormant_method" id="member_dormant_method_delete" value="delete" <?php echo set_radio('member_dormant_method', 'delete', (element('member_dormant_method', element('data', $view)) === 'delete' ? true : false)); ?> /> 회원정보를 삭제함
					</label>
					<label class="radio-inline" for="member_dormant_method_archive" >
						<input type="radio" name="member_dormant_method" id="member_dormant_method_archive" value="archive" <?php echo set_radio('member_dormant_method', 'archive', (element('member_dormant_method', element('data', $view)) !== 'delete' ? true : false)); ?> /> 별도의 저장소에 보관
					</label>
					<span class="help-inline">삭제하는 경우 해당 회원 정보를 절대 복구 불가능합니다. <a href="<?php echo admin_url($this->pagedir . '/dormantlist'); ?>">별도의 저장소</a>에 보관하는 경우 회원이 로그인시 자동으로 복원됩니다</span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">자동정리기능사용</label>
				<div class="col-sm-10">
					<label for="member_dormant_auto_clean" class="checkbox-inline">
						<input type="checkbox" name="member_dormant_auto_clean" id="member_dormant_auto_clean" value="1" <?php echo set_checkbox('member_dormant_auto_clean', '1', (element('member_dormant_auto_clean', element('data', $view)) ? true : false)); ?> /> 사용합니다
					</label>
					<span class="help-inline">휴면회원대상인 회원의 정보를 자동으로 정리합니다. 자동정리 기능을 사용하지 않으시는 경우 '휴면계정일괄정리' 메뉴에서 정리해주시면 됩니다.</span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">자동메일발송기능</label>
				<div class="col-sm-10">
					<label for="member_dormant_auto_email" class="checkbox-inline">
						<input type="checkbox" name="member_dormant_auto_email" id="member_dormant_auto_email" value="1" <?php echo set_checkbox('member_dormant_auto_email', '1', (element('member_dormant_auto_email', element('data', $view)) ? true : false)); ?> /> 사용합니다
					</label>
					<span class="help-inline">휴면회원이 되기 일정 기간 전에 자동 메일을 발송할지 여부를 결정합니다.</span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">자동메일발송기간</label>
				<div class="col-sm-10 form-inline">
					<select name="member_dormant_auto_email_days" class="form-control">
						<option value="7" <?php echo set_select('member_dormant_auto_email_days', '7', (element('member_dormant_auto_email_days', element('data', $view)) === '7' ? true : false)); ?> >정리일 7일전</option>
						<option value="15" <?php echo set_select('member_dormant_auto_email_days', '15', (element('member_dormant_auto_email_days', element('data', $view)) === '15' ? true : false)); ?> >정리일 15일전</option>
						<option value="30" <?php echo set_select('member_dormant_auto_email_days', '30', (element('member_dormant_auto_email_days', element('data', $view)) === '30' ? true : false)); ?> >정리일 30일전</option>
						<option value="45" <?php echo set_select('member_dormant_auto_email_days', '45', (element('member_dormant_auto_email_days', element('data', $view)) === '45' ? true : false)); ?> >정리일 45일전</option>
						<option value="60" <?php echo set_select('member_dormant_auto_email_days', '60', (element('member_dormant_auto_email_days', element('data', $view)) === '60' ? true : false)); ?> >정리일 60일전</option>
						<option value="90" <?php echo set_select('member_dormant_auto_email_days', '90', (element('member_dormant_auto_email_days', element('data', $view)) === '90' ? true : false)); ?> >정리일 90일전</option>
					</select>
					<span class="help-inline">휴면계정 대상으로 전환일로 부터 며칠 전에 자동메일을 발송할 것인지를 결정합니다.</span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">포인트몰수여부</label>
				<div class="col-sm-10">
					<label for="member_dormant_reset_point" class="checkbox-inline">
						<input type="checkbox" name="member_dormant_reset_point" id="member_dormant_reset_point" value="1" <?php echo set_checkbox('member_dormant_reset_point', '1', (element('member_dormant_reset_point', element('data', $view)) ? true : false)); ?> /> 몰수합니다
					</label>
					<span class="help-block">
						휴면회원을 별도의 저장소에 보관하는 경우, 그 동안 쌓아온 포인트를 몰수할지 결정합니다. <br />해당 기능을 사용하지 않으면 휴면회원에서 복원시 기존에 사용하던 포인트를 계속 사용가능합니다.
					</span>
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
			member_dormant_days: {required :true},
			member_dormant_auto_email_days: {required :true}
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
