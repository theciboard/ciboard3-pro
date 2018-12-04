<div class="box">
	<div class="box-header">
		<ul class="nav nav-tabs">
			<li role="presentation" class="active"><a href="<?php echo admin_url($this->pagedir); ?>" onclick="return check_form_changed();">기본정보</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/registerform'); ?>" onclick="return check_form_changed();">가입폼관리</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/membermodify'); ?>" onclick="return check_form_changed();">정보수정시</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/login'); ?>" onclick="return check_form_changed();">로그인</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/alarm'); ?>" onclick="return check_form_changed();">알림 설정</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/sociallogin'); ?>" onclick="return check_form_changed();">소셜로그인</a></li>
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
				<label class="col-sm-2 control-label">로그인시 사용할 계정</label>
				<div class="col-sm-10">
					<label class="radio-inline" for="use_login_account_userid" >
						<input type="radio" name="use_login_account" id="use_login_account_userid" value="userid" <?php echo set_radio('use_login_account', 'userid', (element('use_login_account', element('data', $view)) === 'userid' ? true : false)); ?> /> user id 를 사용해 로그인
					</label>
					<label class="radio-inline" for="use_login_account_email" >
						<input type="radio" name="use_login_account" id="use_login_account_email" value="email" <?php echo set_radio('use_login_account', 'email', (element('use_login_account', element('data', $view)) === 'email' ? true : false)); ?> /> 이메일을 사용해 로그인
					</label>
					<label class="radio-inline" for="use_login_account_both" >
						<input type="radio" name="use_login_account" id="use_login_account_both" value="both" <?php echo set_radio('use_login_account', 'both', (element('use_login_account', element('data', $view)) === 'both' ? true : false)); ?> /> 둘다 사용 가능
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">회원가입 차단</label>
				<div class="col-sm-10">
					<label for="use_register_block" class="checkbox-inline">
						<input type="checkbox" name="use_register_block" id="use_register_block" value="1" <?php echo set_checkbox('use_register_block', '1', (element('use_register_block', element('data', $view)) ? true : false)); ?> /> 회원가입을 차단합니다
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">회원가입시 메일인증사용</label>
				<div class="col-sm-10">
					<label for="use_register_email_auth" class="checkbox-inline">
						<input type="checkbox" name="use_register_email_auth" id="use_register_email_auth" value="1" <?php echo set_checkbox('use_register_email_auth', '1', (element('use_register_email_auth', element('data', $view)) ? true : false)); ?> /> 입력된 메일 주소로 인증 메일을 보내어 회원가입을 확인합니다
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">비밀번호 보안수준</label>
				<div class="col-sm-10">
					패스워드는 최소 <input type="number" class="form-control" name="password_length" id="password_length" value="<?php echo set_value('password_length', (int) element('password_length', element('data', $view))); ?>" /> 글자 이상이어야 하며,
					대문자 <input type="number" class="form-control" name="password_uppercase_length" id="password_uppercase_length" value="<?php echo set_value('password_uppercase_length', (int) element('password_uppercase_length', element('data', $view))); ?>" /> 개를 포함,
					숫자 <input type="number" class="form-control" name="password_numbers_length" id="password_numbers_length" value="<?php echo set_value('password_numbers_length', (int) element('password_numbers_length', element('data', $view))); ?>" /> 개를 포함,
					특수문자 <input type="number" class="form-control" name="password_specialchars_length" id="password_specialchars_length" value="<?php echo set_value('password_specialchars_length', (int) element('password_specialchars_length', element('data', $view))); ?>" /> 개를 포함하고 있어야 합니다.
					<div class="help-block">비밀번호 길이는 최소 4자 이상이어야하며, 대문자, 숫자, 특수문자를 포함하기를 원하지 않는 경우 0을 입력하면 됩니다. 이 규칙은 회원가입시, 정보수정시 적용되며 이미 가입한 회원이 로그인을 하는 경우에는 적용되지 않습니다. 즉 가입 당시 규칙에는 대문자 규칙이 없어서 대문자 없이 회원가입하였는데, 지금 대문자를 꼭 입력하게끔 규칙을 변경하더라도 기존 회원은 대문자 없는 패스워드로 로그인이 가능합니다.</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">회원프로필사진</label>
				<div class="col-sm-10">
					<label for="use_member_photo" class="checkbox-inline">
						<input type="checkbox" name="use_member_photo" id="use_member_photo" value="1" <?php echo set_checkbox('use_member_photo', '1', (element('use_member_photo', element('data', $view)) ? true : false)); ?> /> 사용합니다,
					</label>
					가로길이 <input type="number" class="form-control" name="member_photo_width" id="member_photo_width" value="<?php echo set_value('member_photo_width', (int) element('member_photo_width', element('data', $view))); ?>" />px,
					세로길이 <input type="number" class="form-control" name="member_photo_height" id="member_photo_height" value="<?php echo set_value('member_photo_height', (int) element('member_photo_height', element('data', $view))); ?>" />px
					(jpg, gif, png 만 가능)
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">회원아이콘</label>
				<div class="col-sm-10">
					<label for="use_member_icon" class="checkbox-inline">
						<input type="checkbox" name="use_member_icon" id="use_member_icon" value="1" <?php echo set_checkbox('use_member_icon', '1', (element('use_member_icon', element('data', $view)) ? true : false)); ?> /> 사용합니다,
					</label>
					가로길이 <input type="number" class="form-control" name="member_icon_width" id="member_icon_width" value="<?php echo set_value('member_icon_width', (int) element('member_icon_width', element('data', $view))); ?>" />px,
					세로길이 <input type="number" class="form-control" name="member_icon_height" id="member_icon_height" value="<?php echo set_value('member_icon_height', (int) element('member_icon_height', element('data', $view))); ?>" />px
					(jpg, gif, png 만 가능)
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">금지 닉네임</label>
				<div class="col-sm-10">
					<textarea class="form-control" rows="5" name="denied_nickname_list"><?php echo set_value('denied_nickname_list', element('denied_nickname_list', element('data', $view))); ?></textarea>
					<span class="help-block">제한하고 싶은 닉네임을 쉼표로 구분하여 입력해주세요</span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">금지 아이디</label>
				<div class="col-sm-10">
					<textarea class="form-control" rows="5" name="denied_userid_list"><?php echo set_value('denied_userid_list', element('denied_userid_list', element('data', $view))); ?></textarea>
					<span class="help-block">제한하고 싶은 아이디를 쉼표로 구분하여 입력해주세요</span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">금지 이메일</label>
				<div class="col-sm-10">
					<textarea class="form-control" rows="5" name="denied_email_list"><?php echo set_value('denied_email_list', element('denied_email_list', element('data', $view))); ?></textarea>
					<span class="help-block">제한하고 싶은 이메일 도메인을 쉼표로 구분하여 입력해주세요</span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">회원가입약관</label>
				<div class="col-sm-10">
					<textarea class="form-control" rows="5" name="member_register_policy1"><?php echo set_value('member_register_policy1', element('member_register_policy1', element('data', $view))); ?></textarea>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">개인정보취급방침</label>
				<div class="col-sm-10">
					<textarea class="form-control" rows="5" name="member_register_policy2"><?php echo set_value('member_register_policy2', element('member_register_policy2', element('data', $view))); ?></textarea>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">회원가입시 레벨</label>
				<div class="col-sm-10">
					<input type="number" class="form-control" name="register_level" id="register_level" value="<?php echo set_value('register_level', element('register_level', element('data', $view))); ?>" />
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
			use_login_account: {required :true},
			password_length: {required :true, number:true, min:4 },
			password_uppercase_length: {required :true, number:true, min:0 },
			password_numbers_length: {required :true, number:true, min:0 },
			password_specialchars_length: {required :true, number:true, min:0 },
			register_level: {required :true, number:true, min:1, max:1000 }
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
