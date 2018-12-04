<div class="box">
	<div class="box-header">
		<ul class="nav nav-tabs">
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir); ?>" onclick="return check_form_changed();">기본설정</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/layout'); ?>" onclick="return check_form_changed();">레이아웃/메타태그</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/access'); ?>" onclick="return check_form_changed();">권한관리</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/editor'); ?>" onclick="return check_form_changed();">에디터기능</a></li>
			<li role="presentation" class="active"><a href="<?php echo admin_url($this->pagedir . '/smsconfig'); ?>" onclick="return check_form_changed();">SMS 설정</a></li>
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
				<label class="col-sm-2 control-label">SMS 기능 사용</label>
				<div class="col-sm-10">
					<label for="use_sms" class="checkbox-inline">
						<input type="checkbox" name="use_sms" id="use_sms" value="1" <?php echo set_checkbox('use_sms', '1', (element('use_sms', element('data', $view)) ? true : false)); ?> /> 사용합니다
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">아이코드 아이디</label>
				<div class="col-sm-10 form-inline">
					<input type="text" class="form-control" name="sms_icode_id" value="<?php echo set_value('sms_icode_id', element('sms_icode_id', element('data', $view))); ?>" />
					<div class="help-inline">아이코드 가입하기 : <a href="http://www.icodekorea.com" target="_blank">http://www.icodekorea.com</a></div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">아이코드 패스워드</label>
				<div class="col-sm-10 form-inline">
					<input type="password" class="form-control" name="sms_icode_pw" value="<?php echo set_value('sms_icode_pw', element('sms_icode_pw', element('data', $view))); ?>" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">문자 발송시 회신번호</label>
				<div class="col-sm-10 form-inline">
					<input type="text" class="form-control" name="sms_admin_phone" value="<?php echo set_value('sms_admin_phone', element('sms_admin_phone', element('data', $view))); ?>" />
					<div class="help-inline">000-0000-0000 와 같이 중간에 하이픈(-) 을 넣어주세요</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">요금제</label>
				<div class="col-sm-10 form-inline">
					<?php
					if (element('payment', element('smsinfo', element('data', $view))) === 'C') {
						echo '정액제';
					} elseif (element('payment', element('smsinfo', element('data', $view))) === 'A') {
						echo '충전제';
					} else {
						echo '';
					}
					?>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">충전잔액</label>
				<div class="col-sm-10 form-inline">
					<?php echo number_format((int) element('coin', element('smsinfo', element('data', $view)))); ?> 원
					<input type="button" value="충전하기" class="btn btn-xs btn-primary" onclick="window.open('http://icodekorea.com/company/credit_card_input.php?icode_id=<?php echo element('sms_icode_id', element('data', $view)); ?>&icode_passwd=<?php echo element('sms_icode_pw', element('data', $view)); ?>', 'icodekorea', 'width=650,height=500');">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">건별금액</label>
				<div class="col-sm-10 form-inline">
					<?php echo number_format((int) element('gpay', element('smsinfo', element('data', $view)))); ?> 원
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
			sms_icode_id: {required :'#use_sms:checked'},
			sms_icode_pw: {required :'#use_sms:checked'},
			sms_admin_phone: {required :'#use_sms:checked'}
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
