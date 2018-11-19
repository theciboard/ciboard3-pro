<?php $this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css'); ?>

<div class="findarea">
	<div class="table-box">
		<div class="table-heading">계정 찾기</div>
		<div class="table-body">
			<div class=" findaccount">
				<?php
				echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
				echo show_alert_message(element('message', $view), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
				$attributes = array('name' => 'findidpwform', 'id' => 'findidpwform');
				echo form_open(current_full_url(), $attributes);
				?>
					<input type="hidden" name="findtype" value="findidpw" />
					<h3>이메일 주소로 계정 찾기</h3>
					<p class="text">아이디/비밀번호는 가입시 등록한 메일 주소로 알려드립니다. 가입할 때 등록한 메일 주소를 입력하고 "ID/PW 찾기" 버튼을 클릭해주세요.</p>
					<div class="group">
						<input type="email" name="idpw_email" id="idpw_email" class="input" placeholder="Email Address" />
						<button class="btn btn-black btn-sm" type="submit">ID/PW 찾기</button>
					</div>
				<?php
				echo form_close();

				if ($this->cbconfig->item('use_register_email_auth')) {
					$attributes = array('name' => 'verifyemailform', 'id' => 'verifyemailform');
					echo form_open(current_full_url(), $attributes);
					?>
						<input type="hidden" name="findtype" value="verifyemail" />
						<h3 class="mt30">인증메일 재발송</h3>
						<p class="text">회원가입이나, 이메일주소 변경 후 인증 메일을 받지 못한 경우 다시 받을 수 있습니다.</p>
						<div class="group">
							<input type="email" name="verify_email" id="verify_email" class="input" placeholder="Email Address" />
							<button class="btn btn-black btn-sm" type="submit">인증메일 재발송</button>
						</div>
					<?php
					echo form_close();
					$attributes = array('name' => 'changeemailform', 'id' => 'changeemailform');
					echo form_open(current_full_url(), $attributes);
					?>
						<input type="hidden" name="findtype" value="changeemail" />
						<h3 class="mt30">이메일 주소 변경</h3>
						<p class="text">인증메일이 도착하지 않아 어려움을 겪고 계시다면, 다른 이메일 주소로 변경해 인증해보세요.</p>
						<div class="group">
							<span>아이디</span>
							<div class="form-text text-primary group">
								<input type="text" name="change_userid" id="change_userid" class="input" placeholder="User ID" />
							</div>
						</div>
						<div class="group">
							<span>비밀번호</span>
							<div class="form-text text-primary group">
								<input type="password" name="change_password" id="change_password" class="input" placeholder="Password" />
							</div>
						</div>
						<div class="group">
							<span>새로운 이메일 주소</span>
							<div class="form-text text-primary group">
								<input type="email" name="change_email" id="change_email" class="input" placeholder="Email Address" />
							</div>
						</div>
						<div class="group">
							<div class="form-text text-primary group">
								<button type="submit" class="btn btn-black btn-sm">새로운 이메일주소로 인증메일 재발송</button>
							</div>
						</div>
				<?php
					echo form_close();
				}
				?>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
//<![CDATA[
$(function() {
	$('#findidpwform').validate({
		rules: {
			idpw_email : { required:true, email:true }
		}
	});
	$('#verifyemailform').validate({
		rules: {
			verify_email : { required:true, email:true }
		}
	});
	$('#changeemailform').validate({
		rules: {
			change_userid : { required:true, minlength:3 },
			change_password : { required:true, minlength:4 },
			change_email : { required:true, email:true }
		}
	});
});
//]]>
</script>
