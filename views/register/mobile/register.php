<?php $this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css'); ?>

<div class="provision">
	<?php
	echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
	echo show_alert_message($this->session->flashdata('message'), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
	$attributes = array('class' => 'form-horizontal', 'name' => 'fregisterform', 'id' => 'fregisterform');
	echo form_open(current_full_url(), $attributes);
	?>
		<input type="hidden" name="register" value="1" />
		<div class="table-box">
			<div class="table-heading">회원가입 약관</div>
			<div class="table-body">
				<ol class="registeragree">
					<li>
						<p><strong>회원가입약관</strong></p>
						<textarea class="input" rows="3" readonly="readonly"><?php echo html_escape(element('member_register_policy1', $view)); ?></textarea>
						<div class="checkbox">
							<label for="agree">
								<input type="checkbox" name="agree" id="agree" value="1" /> 회원가입약관의 내용에 동의합니다.
							</label>
						</div>
					</li>
					<li class="mt20">
						<p><strong>개인정보취급방침안내</strong></p>
						<textarea class="input" rows="3" readonly="readonly"><?php echo html_escape(element('member_register_policy2', $view)); ?></textarea>
						<div class="checkbox">
							<label for="agree2">
								<input type="checkbox" name="agree2" id="agree2" value="1" /> 개인정보취급방침안내의 내용에 동의합니다.
							</label>
						</div>
					</li>
<?php if ($this->cbconfig->item('use_selfcert') && ($this->cbconfig->item('use_selfcert_phone') OR $this->cbconfig->item('use_selfcert_ipin'))) { ?>
					<li class="mt20">
						<p><strong>본인인증 선택</strong></p>
						<div class="form-inline">
							<input type="hidden" name="selfcert_type" id="selfcert_type" value="" />
							<?php if ($this->cbconfig->item('use_selfcert_phone')) { ?>
								<button type="button" class="btn btn-warning btn-sm" name="mem_selfcert" id="btn_mem_selfcert_phone">휴대폰인증</button>
							<?php } ?>
							<?php if ($this->cbconfig->item('use_selfcert_ipin')) { ?>
								<button type="button" class="btn btn-primary btn-sm" name="mem_selfcert" id="btn_mem_selfcert_ipin">아이핀인증</button>
							<?php } ?>
						</div>
					</li>
<?php } ?>
					<li><button type="submit" class="btn btn-success">회원가입</button></li>
				</ol>
			</div>
		</div>
	<?php echo form_close(); ?>
</div>

<?php if ($this->cbconfig->item('use_selfcert') && ($this->cbconfig->item('use_selfcert_phone') OR $this->cbconfig->item('use_selfcert_ipin'))) {
	$this->managelayout->add_js(base_url('assets/js/member_selfcert.js'));
} ?>

<script type="text/javascript">
//<![CDATA[
$(function() {
	$('#fregisterform').validate({
		rules: {
			agree: {required :true},
			agree2: {required :true}
<?php if ($this->cbconfig->item('use_selfcert') && ($this->cbconfig->item('use_selfcert_phone') OR $this->cbconfig->item('use_selfcert_ipin')) && $this->cbconfig->item('use_selfcert_required')) { ?>
			, selfcert_type: {required :true}
		}
		, messages: {
			selfcert_type: "본인인증 후 회원가입이 가능합니다"
<?php } ?>
		}
	});
});
//]]>
</script>
