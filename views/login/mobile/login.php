<?php $this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css'); ?>

<div class="access">
	<div class="table-box">
		<div class="table-heading">로그인</div>
		<div class="table-body">
			<?php
			echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
			echo show_alert_message(element('message', $view), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
			echo show_alert_message($this->session->flashdata('message'), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
			$attributes = array('class' => 'form-horizontal', 'name' => 'flogin', 'id' => 'flogin');
			echo form_open(current_full_url(), $attributes);
			?>
				<input type="hidden" name="url" value="<?php echo html_escape($this->input->get_post('url')); ?>" />
				<ol class="loginform">
					<li>
						<span><?php echo element('userid_label_text', $view);?></span>
						<input type="text" name="mem_userid" class="input" value="<?php echo set_value('mem_userid'); ?>" accesskey="L" />
					</li>
					<li>
						<span>비밀번호</span>
						<input type="password" class="input" name="mem_password" />
					</li>
					<li>
						<span></span>
						<button type="submit" class="btn btn-primary btn-sm">로그인</button>
						<label for="autologin">
							<input type="checkbox" name="autologin" id="autologin" value="1" /> 자동로그인
						</label>
					</li>
				</ol>
				<div class="alert alert-dismissible alert-info autologinalert" style="display:none;">
					자동로그인 기능을 사용하시면, 브라우저를 닫더라도 로그인이 계속 유지될 수 있습니다. 자동로그이 기능을 사용할 경우 다음 접속부터는 로그인할 필요가 없습니다. 단, 공공장소에서 이용 시 개인정보가 유출될 수 있으니 꼭 로그아웃을 해주세요.
				</div>
			<?php echo form_close(); ?>
			<?php
			if ($this->cbconfig->item('use_sociallogin')) {
				$this->managelayout->add_js(base_url('assets/js/social_login.js'));
			?>
				<ol class="loginform">
					<li>
						<span>소셜로그인</span>
						<div>
							<?php if ($this->cbconfig->item('use_sociallogin_facebook')) {?>
								<a href="javascript:;" onClick="social_connect_on('facebook');" title="페이스북 로그인"><img src="<?php echo base_url('assets/images/social_facebook.png'); ?>" width="22" height="22" alt="페이스북 로그인" title="페이스북 로그인" /></a>
							<?php } ?>
							<?php if ($this->cbconfig->item('use_sociallogin_twitter')) {?>
								<a href="javascript:;" onClick="social_connect_on('twitter');" title="트위터 로그인"><img src="<?php echo base_url('assets/images/social_twitter.png'); ?>" width="22" height="22" alt="트위터 로그인" title="트위터 로그인" /></a>
							<?php } ?>
							<?php if ($this->cbconfig->item('use_sociallogin_google')) {?>
								<a href="javascript:;" onClick="social_connect_on('google');" title="구글 로그인"><img src="<?php echo base_url('assets/images/social_google.png'); ?>" width="22" height="22" alt="구글 로그인" title="구글 로그인" /></a>
							<?php } ?>
							<?php if ($this->cbconfig->item('use_sociallogin_naver')) {?>
								<a href="javascript:;" onClick="social_connect_on('naver');" title="네이버 로그인"><img src="<?php echo base_url('assets/images/social_naver.png'); ?>" width="22" height="22" alt="네이버 로그인" title="네이버 로그인" /></a>
							<?php } ?>
							<?php if ($this->cbconfig->item('use_sociallogin_kakao')) {?>
								<a href="javascript:;" onClick="social_connect_on('kakao');" title="카카오 로그인"><img src="<?php echo base_url('assets/images/social_kakao.png'); ?>" width="22" height="22" alt="카카오 로그인" title="카카오 로그인" /></a>
							<?php } ?>
						</div>
					</li>
				</ol>
			<?php } ?>
		</div>
		<div class="table-footer">
			<a href="<?php echo site_url('register'); ?>" class="btn btn-success btn-sm" title="회원가입">회원가입</a>
			<a href="<?php echo site_url('findaccount'); ?>" class="btn btn-default btn-sm" title="아이디 패스워드 찾기">아이디 패스워드 찾기</a>
		</div>
	</div>
</div>

<script type="text/javascript">
//<![CDATA[
$(function() {
	$('#flogin').validate({
		rules: {
			mem_userid : { required:true, minlength:3 },
			mem_password : { required:true, minlength:4 }
		}
	});
});
$(document).on('change', "input:checkbox[name='autologin']", function() {
	if (this.checked) {
		$('.autologinalert').show(300);
	} else {
		$('.autologinalert').hide(300);
	}
});
//]]>
</script>
