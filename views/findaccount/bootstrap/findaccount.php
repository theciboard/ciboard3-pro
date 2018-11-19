<?php $this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css'); ?>

<div class="search col-md-8 col-md-offset-2">
	<div class="panel panel-default">
		<div class="panel-heading">계정 찾기</div>
		<div class="panel-body">
			<?php
			echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
			echo show_alert_message(element('message', $view), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
			$attributes = array('class' => 'form-horizontal', 'name' => 'findidpwform', 'id' => 'findidpwform');
			echo form_open(current_full_url(), $attributes);
			?>
				<input type="hidden" name="findtype" value="findidpw" />
				<legend>이메일 주소로 계정 찾기</legend>
				<p>아이디/비밀번호는 가입시 등록한 메일 주소로 알려드립니다. 가입할 때 등록한 메일 주소를 입력하고 "ID/PW 찾기" 버튼을 클릭해주세요.</p>
				<div class="input-group col-md-8">
					<input type="email" name="idpw_email" id="idpw_email" class="form-control" placeholder="Email Address" />
					<span class="input-group-btn">
						<button class="btn btn-black btn-sm" type="submit">ID/PW 찾기</button>
					</span>
				</div>
			<?php
			echo form_close();

			if ($this->cbconfig->item('use_register_email_auth')) {
				$attributes = array('class' => 'form-horizontal', 'name' => 'verifyemailform', 'id' => 'verifyemailform');
				echo form_open(current_full_url(), $attributes);
				?>
					<input type="hidden" name="findtype" value="verifyemail" />
					<legend>인증메일 재발송</legend>
					<p>회원가입이나, 이메일주소 변경 후 인증 메일을 받지 못한 경우 다시 받을 수 있습니다.</p>
					<div class="input-group col-md-8">
						<input type="email" name="verify_email" id="verify_email" class="form-control" placeholder="Email Address" />
						<span class="input-group-btn">
							<button class="btn btn-black btn-sm" type="submit">인증메일 재발송</button>
						</span>
					</div>
				<?php
				echo form_close();
				$attributes = array('class' => 'form-horizontal', 'name' => 'changeemailform', 'id' => 'changeemailform');
				echo form_open(current_full_url(), $attributes);
				?>
					<input type="hidden" name="findtype" value="changeemail" />
					<legend>이메일 주소 변경</legend>
					<p>인증메일이 도착하지 않아 어려움을 겪고 계시다면, 다른 이메일 주소로 변경해 인증해보세요.</p>
					<div class="form-group">
						<label class="col-lg-3 control-label">아이디</label>
						<div class="col-md-4">
							<input type="text" name="change_userid" id="change_userid" class="form-control" placeholder="User ID" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 control-label">비밀번호</label>
						<div class="col-md-4">
							<input type="password" name="change_password" id="change_password" class="form-control" placeholder="Password" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 control-label">새로운이메일주소</label>
						<div class="col-md-6">
							<input type="email" name="change_email" id="change_email" class="form-control" placeholder="Email Address" />
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-4 col-lg-offset-3">
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
