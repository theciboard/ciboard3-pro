<div class="box">
	<div class="box-header">
		<ul class="nav nav-tabs">
			<li role="presentation" class="active"><a href="<?php echo admin_url($this->pagedir . '/register'); ?>" onclick="return check_form_changed();">회원가입</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/changepw'); ?>" onclick="return check_form_changed();">패스워드변경</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/memberleave'); ?>" onclick="return check_form_changed();">회원탈퇴</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/changeemail'); ?>" onclick="return check_form_changed();">이메일변경시인증메일</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/findaccount'); ?>" onclick="return check_form_changed();">회원정보찾기</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/post'); ?>" onclick="return check_form_changed();">게시글작성</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/comment'); ?>" onclick="return check_form_changed();">댓글작성</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/blame'); ?>" onclick="return check_form_changed();">게시글신고발생</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/comment_blame'); ?>" onclick="return check_form_changed();">댓글신고발생</a></li>
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
			<div class="alert alert-success">
				<p><strong>메일발송</strong> : 메일이 보내지지 않는다면 메일발송 환경설정 부분을 확인하여주세요</p>
				<p><strong>쪽지발송</strong> : 쪽지 기능을 사용하는 사이트에서만 쪽지가 발송됩니다.</p>
				<p><strong>문자발송</strong> : 문자 기능을 사용하는 사이트에서만 문자가 발송됩니다.</p>
			</div>
		<?php
		if ( ! element('sms_library_exists', $view)) {
		?>
			<div class="alert alert-dismissible alert-warning">
				SMS Library 가 설치되어있지 않습니다.<br />
				문자발송 서비스를 이용하기 원하시면 우선 SMS 플러그인을 설치하여주세요<br />
				<a href="http://www.ciboard.co.kr/plugins/p/1572" target="_blank">설치하러 가기</a>
			</div>
		<?php
		}
		?>
			<div class="form-group">
				<label class="col-sm-2 control-label">회원가입시</label>
				<div class="col-sm-10">
					<div class="checkbox">
						<label for="send_email_register_admin">
							<input type="checkbox" name="send_email_register_admin" id="send_email_register_admin" value="1" <?php echo set_checkbox('send_email_register_admin', '1', (element('send_email_register_admin', element('data', $view)) ? true : false)); ?> /> <strong>최고관리자</strong>에게 <strong>메일</strong>을 발송합니다 (이메일 수신동의 여부와 상관없이 <strong>무조건 발송</strong>합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="send_email_register_user">
							<input type="checkbox" name="send_email_register_user" id="send_email_register_user" value="1" <?php echo set_checkbox('send_email_register_user', '1', (element('send_email_register_user', element('data', $view)) ? true : false)); ?> /> <strong>회원</strong>에게 <strong>메일</strong>을 발송합니다 (이메일수신<strong>동의한 회원</strong>에게만 발송합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="send_email_register_alluser">
							<input type="checkbox" name="send_email_register_alluser" id="send_email_register_alluser" value="1" <?php echo set_checkbox('send_email_register_alluser', '1', (element('send_email_register_alluser', element('data', $view)) ? true : false)); ?> /> <strong>회원</strong>에게 <strong>메일</strong>을 발송합니다 (이메일 수신동의 여부와 상관없이 <strong>무조건 발송</strong>합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="send_note_register_admin">
							<input type="checkbox" name="send_note_register_admin" id="send_note_register_admin" value="1" <?php echo set_checkbox('send_note_register_admin', '1', (element('send_note_register_admin', element('data', $view)) ? true : false)); ?> /> <strong>최고관리자</strong>에게 <strong>쪽지</strong>를 발송합니다 (쪽지사용에 <strong>동의한 관리자</strong>에게만 발송합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="send_note_register_user">
							<input type="checkbox" name="send_note_register_user" id="send_note_register_user" value="1" <?php echo set_checkbox('send_note_register_user', '1', (element('send_note_register_user', element('data', $view)) ? true : false)); ?> /> <strong>회원</strong>에게 <strong>쪽지</strong>를 발송합니다 (쪽지사용에 <strong>동의한 회원</strong>에게만 발송합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="send_sms_register_admin">
							<input type="checkbox" name="send_sms_register_admin" id="send_sms_register_admin" value="1" <?php echo set_checkbox('send_sms_register_admin', '1', (element('send_sms_register_admin', element('data', $view)) ? true : false)); ?> <?php echo ( ! element('sms_library_exists', $view)) ? 'disabled="disabled"' : ''; ?> /> <strong>최고관리자</strong>에게 <strong>문자</strong>를 발송합니다 (문자수신동의 여부와 상관없이 <strong>무조건 발송</strong>합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="send_sms_register_user">
							<input type="checkbox" name="send_sms_register_user" id="send_sms_register_user" value="1" <?php echo set_checkbox('send_sms_register_user', '1', (element('send_sms_register_user', element('data', $view)) ? true : false)); ?> <?php echo ( ! element('sms_library_exists', $view)) ? 'disabled="disabled"' : ''; ?> /> <strong>회원</strong>에게 <strong>문자</strong>를 발송합니다 (문자수신에 <strong>동의한 회원</strong>에게만 발송합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="send_sms_register_alluser">
							<input type="checkbox" name="send_sms_register_alluser" id="send_sms_register_alluser" value="1" <?php echo set_checkbox('send_sms_register_alluser', '1', (element('send_sms_register_alluser', element('data', $view)) ? true : false)); ?> <?php echo ( ! element('sms_library_exists', $view)) ? 'disabled="disabled"' : ''; ?> /> <strong>회원</strong>에게 <strong>문자</strong>를 발송합니다 (문자수신 동의에 상관없이 <strong>무조건 발송</strong>합니다)
						</label>
					</div>
				</div>
			</div>
			<div class="alert alert-success">
				<p>치환가능변수 : <strong>{홈페이지명}</strong>, <strong>{회사명}</strong>, <strong>{홈페이지주소}</strong>, <strong>{회원아이디}</strong>, <strong>{회원닉네임}</strong>, <strong>{회원실명}</strong>, <strong>{회원이메일}</strong>, <strong>{메일인증주소}</strong>, <strong>{메일수신여부}</strong>, <strong>{쪽지수신여부}</strong>, <strong>{문자수신여부}</strong>, <strong>{회원아이피}</strong></p>
				<p><strong>{메일수신여부}</strong>, <strong>{쪽지수신여부}</strong>, <strong>{문자수신여부}</strong> 는 <strong>동의</strong>, <strong>거부</strong> - 이 2개 중 하나로 치환됩니다</p>
				<p><strong>{회원아이피}</strong> 는 회원가입시 접속한 실제 IP 로 치환됩니다</p>
				<p><strong>{메일인증주소}</strong> 는 이메일 인증기능을 사용하는 설정 하에, 회원에게 보내는 메일에만 치환 기능이 작동합니다</p>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">
					최고관리자에게 보낼 메일<br />
					<button type="button" class="btn btn-xs btn-default reset_email_to_admin">내용초기화하기</button>
				</label>
				<div class="col-sm-10">
					<div class="form-group col-sm-12">
						<input type="text" class="form-control" name="send_email_register_admin_title" id="send_email_register_admin_title" value="<?php echo set_value('send_email_register_admin_title', element('send_email_register_admin_title', element('data', $view))); ?>" />
					</div>
					<div class="form-group col-sm-12">
						<?php echo display_dhtml_editor('send_email_register_admin_content', set_value('send_email_register_admin_content', element('send_email_register_admin_content', element('data', $view))), $classname = 'form-control dhtmleditor', $is_dhtml_editor = true, $editor_type = "smarteditor"); ?>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">
					회원에게 보낼 메일<br />(이메일인증기능 미사용시 )<br />
					<button type="button" class="btn btn-xs btn-default reset_email_to_user">내용초기화하기</button>
				</label>
				<div class="col-sm-10">
					<div class="form-group col-sm-12">
						<input type="text" class="form-control" name="send_email_register_user_title" id="send_email_register_user_title" value="<?php echo set_value('send_email_register_user_title', element('send_email_register_user_title', element('data', $view))); ?>" />
					</div>
					<div class="form-group col-sm-12">
						<?php echo display_dhtml_editor('send_email_register_user_content', set_value('send_email_register_user_content', element('send_email_register_user_content', element('data', $view))), $classname = 'form-control dhtmleditor', $is_dhtml_editor = true, $editor_type = "smarteditor"); ?>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">
					회원에게 보낼 메일<br />(이메일인증기능 사용시)<br />
					<button type="button" class="btn btn-xs btn-default reset_email_to_user_verify">내용초기화하기</button>
				</label>
				<div class="col-sm-10">
					<div class="form-group col-sm-12">
						<input type="text" class="form-control" name="send_email_register_user_verifytitle" id="send_email_register_user_verifytitle" value="<?php echo set_value('send_email_register_user_verifytitle', element('send_email_register_user_verifytitle', element('data', $view))); ?>" />
					</div>
					<div class="form-group col-sm-12">
						<?php echo display_dhtml_editor('send_email_register_user_verifycontent', set_value('send_email_register_user_verifycontent', element('send_email_register_user_verifycontent', element('data', $view))), $classname = 'form-control dhtmleditor', $is_dhtml_editor = true, $editor_type = "smarteditor"); ?>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">
					최고관리자에게 보낼 쪽지<br />
					<button type="button" class="btn btn-xs btn-default reset_note_to_admin">내용초기화하기</button>
				</label>
				<div class="col-sm-10">
					<div class="form-group col-sm-12">
						<input type="text" class="form-control" name="send_note_register_admin_title" id="send_note_register_admin_title" value="<?php echo set_value('send_note_register_admin_title', element('send_note_register_admin_title', element('data', $view))); ?>" />
					</div>
					<div class="form-group col-sm-12">
						<?php echo display_dhtml_editor('send_note_register_admin_content', set_value('send_note_register_admin_content', element('send_note_register_admin_content', element('data', $view))), $classname = 'form-control dhtmleditor', $is_dhtml_editor = true, $editor_type = "smarteditor"); ?>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">
					회원에게 보낼 쪽지<br />
					<button type="button" class="btn btn-xs btn-default reset_note_to_user">내용초기화하기</button>
				</label>
				<div class="col-sm-10">
					<div class="form-group col-sm-12">
						<input type="text" class="form-control" name="send_note_register_user_title" id="send_note_register_user_title" value="<?php echo set_value('send_note_register_user_title', element('send_note_register_user_title', element('data', $view))); ?>" />
					</div>
					<div class="form-group col-sm-12">
						<?php echo display_dhtml_editor('send_note_register_user_content', set_value('send_note_register_user_content', element('send_note_register_user_content', element('data', $view))), $classname = 'form-control dhtmleditor', $is_dhtml_editor = true, $editor_type = "smarteditor"); ?>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">
					최고관리자에게 보낼 문자<br />
					<button type="button" class="btn btn-xs btn-default reset_sms_to_admin">내용초기화하기</button>
				</label>
				<div class="col-sm-10 form-inline has-success ">
					<textarea class="form-control" style="width:140px;background-color:#d9edf7" rows="5" name="send_sms_register_admin_content" id="send_sms_register_admin_content"><?php echo set_value('send_sms_register_admin_content', element('send_sms_register_admin_content', element('data', $view))); ?></textarea>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">
					회원에게 보낼 문자<br />
					<button type="button" class="btn btn-xs btn-default reset_sms_to_user">내용초기화하기</button>
				</label>
				<div class="col-sm-10 form-inline has-success ">
					<textarea class="form-control" style="width:140px;background-color:#d9edf7" rows="5" name="send_sms_register_user_content" id="send_sms_register_user_content"><?php echo set_value('send_sms_register_user_content', element('send_sms_register_user_content', element('data', $view))); ?></textarea>
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
			send_email_register_admin_content : {'required_smarteditor' : true },
			send_email_register_user_content : {'required_smarteditor' : true },
			send_email_register_user_verifycontent : {'required_smarteditor' : true },
			send_note_register_admin_content : {'required_smarteditor' : true },
			send_note_register_user_content : {'required_smarteditor' : true }
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
$(function() {
	$(document).on('change', '#send_email_register_alluser', function() {
		if ($(this).is(':checked')) {
			$('#send_email_register_user').prop('checked', false).prop('disabled', true);
		} else {
			$('#send_email_register_user').prop('disabled', false);
		}
	});
	<?php if (element('send_email_register_alluser', element('data', $view))) {?>
		$('#send_email_register_user').prop('checked', false).prop('disabled', true);
	<?php } ?>
	$(document).on('change', '#send_email_register_user', function() {
		if ($(this).is(':checked')) {
			$('#send_email_register_alluser').prop('checked', false).prop('disabled', true);
		} else {
			$('#send_email_register_alluser').prop('disabled', false);
		}
	});
	<?php if (element('send_email_register_user', element('data', $view))) {?>
		$('#send_email_register_alluser').prop('checked', false).prop('disabled', true);
	<?php } ?>
	$(document).on('change', '#send_sms_register_alluser', function() {
		if ($(this).is(':checked')) {
			$('#send_sms_register_user').prop('checked', false).prop('disabled', true);
		} else {
			$('#send_sms_register_user').prop('disabled', false);
		}
	});
	<?php if (element('send_sms_register_alluser', element('data', $view))) {?>
		$('#send_sms_register_user').prop('checked', false).prop('disabled', true);
	<?php } ?>
	$(document).on('change', '#send_sms_register_user', function() {
		if ($(this).is(':checked')) {
			$('#send_sms_register_alluser').prop('checked', false).prop('disabled', true);
		} else {
			$('#send_sms_register_alluser').prop('disabled', false);
		}
	});
	<?php if (element('send_sms_register_user', element('data', $view))) {?>
		$('#send_sms_register_alluser').prop('checked', false).prop('disabled', true);
	<?php } ?>
});

$(document).on('click', '.reset_email_to_admin', function() {
	$('#send_email_register_admin_title').val('[회원가입알림] {회원닉네임}님이 회원가입하셨습니다');
	var sHTML = '<table width="100%" border="0" cellpadding="0" cellspacing="0" style="border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;"><tr><td style="font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;"><span style="font-size:14px;font-weight:bold;color:rgb(0,0,0)">안녕하세요 관리자님,</span><br /></td></tr><tr style="border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;"><td style="padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;"><p>안녕하세요,</p><p>{회원닉네임} 님이 회원가입 하셨습니다.</p><p>회원아이디 : {회원아이디}</p><p>닉네임 : {회원닉네임}</p><p>이메일 : {회원이메일}</p><p>가입한 곳 IP : {회원아이피}</p><p>감사합니다.</p></td></tr><tr><td style="padding:10px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;text-align:center;">{홈페이지명}</td></tr></table>';
	oEditors.getById["send_email_register_admin_content"].exec("SET_CONTENTS", [sHTML]);
});
$(document).on('click', '.reset_email_to_user', function() {
	$('#send_email_register_user_title').val('[{홈페이지명}] {회원닉네임}님의 회원가입을 축하드립니다');
	var sHTML = '<table width="100%" border="0" cellpadding="0" cellspacing="0" style="border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;"><tr><td style="font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;"><span style="font-size:14px;font-weight:bold;color:rgb(0,0,0)">안녕하세요 {회원닉네임}님,</span><br />회원가입을 축하드립니다.</td></tr><tr style="border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;"><td style="padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;"><p>안녕하세요 {회원닉네임} 회원님,</p><p>회원가입을 축하드립니다.</p><p>{홈페이지명} 회원으로 가입해주셔서 감사합니다.</p><p>더욱 편리한 서비스를 제공하기 위해 항상 최선을 다하겠습니다.</p><p>&nbsp;</p><p>감사합니다.</p></td></tr><tr><td style="padding:10px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;text-align:center;">{홈페이지명}</td></tr></table>';
	oEditors.getById["send_email_register_user_content"].exec("SET_CONTENTS", [sHTML]);
});
$(document).on('click', '.reset_email_to_user_verify', function() {
	$('#send_email_register_user_verifytitle').val('[{홈페이지명}] {회원닉네임}님의 회원가입을 축하드립니다');
	var sHTML = '<table width="100%" border="0" cellpadding="0" cellspacing="0" style="border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;"><tr><td style="font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;"><span style="font-size:14px;font-weight:bold;color:rgb(0,0,0)">안녕하세요 {회원닉네임}님,</span><br />회원가입을 축하드립니다.</td></tr><tr style="border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;"><td style="padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;"><p>안녕하세요 {회원닉네임} 회원님,</p><p>회원가입을 축하드립니다.</p><p>{홈페이지명} 회원으로 가입해주셔서 감사합니다.</p><p>더욱 편리한 서비스를 제공하기 위해 항상 최선을 다하겠습니다.</p><p>&nbsp;</p><p>아래 링크를 클릭하시면 회원가입이 완료됩니다.</p><p><a href="{메일인증주소}" target="_blank" style="font-weight:bold;">메일인증 받기</a></p><p>&nbsp;</p><p>감사합니다.</p></td></tr><tr><td style="padding:10px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;text-align:center;">{홈페이지명}</td></tr></table>';
	oEditors.getById["send_email_register_user_verifycontent"].exec("SET_CONTENTS", [sHTML]);
});
$(document).on('click', '.reset_note_to_admin', function() {
	$('#send_note_register_admin_title').val('[회원가입알림] {회원닉네임}님이 회원가입하셨습니다');
	var sHTML = '<table width="100%" border="0" cellpadding="0" cellspacing="0" style="border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;"><tr><td style="font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;"><span style="font-size:14px;font-weight:bold;color:rgb(0,0,0)">안녕하세요 관리자님,</span><br /></td></tr><tr style="border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;"><td style="padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;"><p>안녕하세요,</p><p>{회원닉네임} 님이 회원가입 하셨습니다.</p><p>회원아이디 : {회원아이디}</p><p>닉네임 : {회원닉네임}</p><p>이메일 : {회원이메일}</p><p>가입한 곳 IP : {회원아이피}</p><p>감사합니다.</p></td></tr><tr><td style="padding:10px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;text-align:center;">{홈페이지명}</td></tr></table>';
	oEditors.getById["send_note_register_admin_content"].exec("SET_CONTENTS", [sHTML]);
});
$(document).on('click', '.reset_note_to_user', function() {
	$('#send_note_register_user_title').val('회원가입을 축하드립니다');
	var sHTML = '<table width="100%" border="0" cellpadding="0" cellspacing="0" style="border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;"><tr><td style="font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;"><span style="font-size:14px;font-weight:bold;color:rgb(0,0,0)">안녕하세요 {회원닉네임}님,</span><br />회원가입을 축하드립니다.</td></tr><tr style="border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;"><td style="padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;"><p>안녕하세요 {회원닉네임} 회원님,</p><p>회원가입을 축하드립니다.</p><p>{홈페이지명} 회원으로 가입해주셔서 감사합니다.</p><p>더욱 편리한 서비스를 제공하기 위해 항상 최선을 다하겠습니다.</p><p>&nbsp;</p><p>감사합니다.</p></td></tr><tr><td style="padding:10px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;text-align:center;">{홈페이지명}</td></tr></table>';
	oEditors.getById["send_note_register_user_content"].exec("SET_CONTENTS", [sHTML]);
});
$(document).on('click', '.reset_sms_to_admin', function() {
	$('#send_sms_register_admin_content').val('[회원가입알림] {회원닉네임}님이 회원가입하셨습니다');
});
$(document).on('click', '.reset_sms_to_user', function() {
	$('#send_sms_register_user_content').val('[{홈페이지명}] 회원가입을 축하드립니다. 감사합니다');
});
//]]>
</script>
