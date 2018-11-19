<?php $this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css'); ?>

<div class="register">
	<div class="table-box">
		<div class="table-heading">회원 가입</div>
		<div class="table-body">
			<?php
			echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
			echo show_alert_message($this->session->flashdata('message'), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
			$attributes = array('class' => 'form-horizontal', 'name' => 'fregisterform', 'id' => 'fregisterform');
			echo form_open_multipart(current_full_url(), $attributes);
			?>
			<ol class="registerform">
				<?php
				foreach (element('html_content', $view) as $key => $value) {
				?>
					<li>
						<span><?php echo element('display_name', $value); ?></span>
						<div class="form-text text-primary group">
							<?php echo element('input', $value); ?>
							<?php if (element('description', $value)) { ?>
								<p class="help-block"><?php echo element('description', $value); ?></p>
							<?php } ?>
						</div>
					</li>
				<?php
				}
				if ($this->cbconfig->item('use_member_photo') && $this->cbconfig->item('member_photo_width') > 0 && $this->cbconfig->item('member_photo_height') > 0) {
				?>
					<li>
						<span>프로필사진</span>
						<div class="form-text text-primary group">
							<input type="file" name="mem_photo" id="mem_photo" />
							<p class="help-block">가로길이 : <?php echo number_format($this->cbconfig->item('member_photo_width')); ?>px, 세로길이 : <?php echo number_format($this->cbconfig->item('member_photo_height')); ?>px 에 최적화되어있습니다, gif, jpg, png 파일 업로드가 가능합니다</p>
						</div>
					</li>
				<?php
				}
				if ($this->cbconfig->item('use_member_icon') && $this->cbconfig->item('member_icon_width') > 0 && $this->cbconfig->item('member_icon_height') > 0) {
				?>
					<li>
						<span>회원아이콘</span>
						<div class="form-text text-primary group">
							<input type="file" name="mem_icon" id="mem_icon" />
							<p class="help-block">가로길이 : <?php echo number_format($this->cbconfig->item('member_icon_width')); ?>px, 세로길이 : <?php echo number_format($this->cbconfig->item('member_icon_height')); ?>px 에 최적화되어있습니다, gif, jpg, png 파일 업로드가 가능합니다</p>
						</div>
					</li>
				<?php
				}
				?>
					<li>
						<span>정보공개</span>
						<div class="form-text text-primary group">
							<label for="mem_open_profile">
								<input type="checkbox" name="mem_open_profile" id="mem_open_profile" value="1" <?php echo set_checkbox('mem_open_profile', '1', true); ?> />
								다른분들이 나의 정보를 볼 수 있도록 합니다.
							</label>
							<?php
							if (element('open_profile_description', $view)) {
							?>
								<p class="help-block"><?php echo element('open_profile_description', $view); ?></p>
							<?php
							}
							?>
						</div>
					</li>
					<?php
					if ($this->cbconfig->item('use_note')) {
					?>
						<li>
							<span>쪽지기능사용</span>
							<div class="form-text text-primary group">
								<div class="checkbox">
									<label for="mem_use_note">
										<input type="checkbox" name="mem_use_note" id="mem_use_note" value="1" <?php echo set_checkbox('mem_use_note', '1', true); ?> />
										쪽지를 주고 받을 수 있습니다.
									</label>
									<?php
									if (element('use_note_description', $view)) {
									?>
										<p class="help-block"><?php echo element('use_note_description', $view); ?></p>
									<?php
									}
									?>
								</div>
							</div>
						</li>
					<?php
					}
					?>
					<li>
						<span>이메일수신여부</span>
						<div class="form-text text-primary group">
							<div class="checkbox">
								<label for="mem_receive_email" >
									<input type="checkbox" name="mem_receive_email" id="mem_receive_email" value="1" <?php echo set_checkbox('mem_receive_email', '1', true); ?> /> 수신
								</label>
							</div>
						</div>
					</li>
					<li>
						<span>SMS 문자수신</span>
						<div class="form-text text-primary group">
							<div class="checkbox">
							<label for="mem_receive_sms">
								<input type="checkbox" name="mem_receive_sms" id="mem_receive_sms" value="1" <?php echo set_checkbox('mem_receive_sms', '1', true); ?> /> 수신
							</label>
							</div>
						</div>
					</li>
					<li>
						<?php if ($this->cbconfig->item('use_recaptcha')) { ?>
							<span></span>
							<div class="form-text text-primary group captcha" id="recaptcha"><button type="button" id="captcha"></button></div>
							<input type="hidden" name="recaptcha" />
						<?php } else { ?>
							<span><img src="<?php echo base_url('assets/images/preload.png'); ?>" width="160" height="40" id="captcha" alt="captcha" title="captcha" /></span>
							<div class="form-text text-primary group">
								<input type="text" name="captcha_key" id="captcha_key" class=" input px150" value="" />
								<p class="help-block">좌측에 보이는 문자를 입력해주세요</p>
							</div>
						<?php } ?>
					</li>
					<li>
						<span></span>
						<div class="group">
							<button type="submit" class="btn btn-success">회원가입</button>
							<a href="<?php echo site_url(); ?>" class="btn btn-default">취소</a>
						</div>
					</li>
				</ol>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>

<?php
$this->managelayout->add_css(base_url('assets/css/datepicker3.css'));
$this->managelayout->add_js('http://dmaps.daum.net/map_js_init/postcode.v2.js');
$this->managelayout->add_js(base_url('assets/js/bootstrap-datepicker.js'));
$this->managelayout->add_js(base_url('assets/js/bootstrap-datepicker.kr.js'));
$this->managelayout->add_js(base_url('assets/js/member_register.js'));
if ($this->cbconfig->item('use_recaptcha')) {
	$this->managelayout->add_js(base_url('assets/js/recaptcha.js'));
} else {
	$this->managelayout->add_js(base_url('assets/js/captcha.js'));
}
?>

<script type="text/javascript">
//<![CDATA[
$('.datepicker').datepicker({
	format: 'yyyy-mm-dd',
	language: 'kr',
	autoclose: true,
	todayHighlight: true
});
$(function() {
	$('#fregisterform').validate({
		onkeyup: false,
		onclick: false,
		rules: {
			mem_userid: {required :true, minlength:3, maxlength:20, is_userid_available:true},
			mem_email: {required :true, email:true, is_email_available:true},
			mem_password: {required :true, is_password_available:true},
			mem_password_re : {required: true, equalTo : '#mem_password' },
			mem_nickname: {required :true, is_nickname_available:true}
			<?php if ($this->cbconfig->item('use_recaptcha')) { ?>
				, recaptcha : {recaptchaKey:true}
			<?php } else { ?>
				, captcha_key : {required: true, captchaKey:true}
			<?php } ?>
		},
		messages: {
			recaptcha: '',
			captcha_key: '자동등록방지용 코드가 올바르지 않습니다.'
		}
	});
});
//]]>
</script>
