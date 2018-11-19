<div class="box">
	<div class="box-header">
		<ul class="nav nav-tabs">
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/register'); ?>" onclick="return check_form_changed();">회원가입</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/changepw'); ?>" onclick="return check_form_changed();">패스워드변경</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/memberleave'); ?>" onclick="return check_form_changed();">회원탈퇴</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/changeemail'); ?>" onclick="return check_form_changed();">이메일변경시인증메일</a></li>
			<li role="presentation" class="active"><a href="<?php echo admin_url($this->pagedir . '/findaccount'); ?>" onclick="return check_form_changed();">회원정보찾기</a></li>
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
				<p>회원정보찾기 페이지에서 발송되는 이메일내용을 관리합니다</p>
				<hr />
				<p>치환가능변수 : <strong>{홈페이지명}</strong>, <strong>{회사명}</strong>, <strong>{홈페이지주소}</strong>, <strong>{회원아이디}</strong>, <strong>{회원닉네임}</strong>, <strong>{회원실명}</strong>, <strong>{회원이메일}</strong>, <strong>{메일수신여부}</strong>, <strong>{쪽지수신여부}</strong>, <strong>{문자수신여부}</strong>, <strong>{회원아이피}</strong>, <strong>{메일인증주소}</strong>, <strong>{패스워드변경주소}</strong></p>
				<p><strong>{메일수신여부}</strong>, <strong>{쪽지수신여부}</strong>, <strong>{문자수신여부}</strong> 는 <strong>동의</strong>, <strong>거부</strong> - 이 2개 중 하나로 치환됩니다</p>
				<p><strong>{회원아이피}</strong> 는 회원정보찾기시 접속한 실제 IP 로 치환됩니다</p>
				<p><strong>{패스워드변경버튼}</strong> 을 클릭하면 실제 패스워드 변경페이지로 이동합니다</p>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">
					ID/PW 찾기<br />
					<button type="button" class="btn btn-xs btn-default reset_findaccount">내용초기화하기</button>
				</label>
				<div class="col-sm-10">
					<div class="form-group col-sm-12">
						<input type="text" class="form-control" name="send_email_findaccount_user_title" id="send_email_findaccount_user_title" value="<?php echo set_value('send_email_findaccount_user_title', element('send_email_findaccount_user_title', element('data', $view))); ?>" />
					</div>
					<div class="form-group col-sm-12">
						<?php echo display_dhtml_editor('send_email_findaccount_user_content', set_value('send_email_findaccount_user_content', element('send_email_findaccount_user_content', element('data', $view))), $classname = 'form-control dhtmleditor', $is_dhtml_editor = true, $editor_type = "smarteditor"); ?>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">
					인증메일재발송<br />
					<button type="button" class="btn btn-xs btn-default reset_resendverify">내용초기화하기</button>
				</label>
				<div class="col-sm-10">
					<div class="form-group col-sm-12">
						<input type="text" class="form-control" name="send_email_resendverify_user_title" id="send_email_resendverify_user_title" value="<?php echo set_value('send_email_resendverify_user_title', element('send_email_resendverify_user_title', element('data', $view))); ?>" />
					</div>
					<div class="form-group col-sm-12">
						<?php echo display_dhtml_editor('send_email_resendverify_user_content', set_value('send_email_resendverify_user_content', element('send_email_resendverify_user_content', element('data', $view))), $classname = 'form-control dhtmleditor', $is_dhtml_editor = true, $editor_type = "smarteditor"); ?>
					</div>
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
			send_email_findaccount_user_content : {'required_smarteditor' : true },
			send_email_resendverify_user_content : {'required_smarteditor' : true }
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
$(document).on('click', '.reset_findaccount', function() {
	$('#send_email_findaccount_user_title').val('{회원닉네임}님의 아이디와 패스워드를 보내드립니다');
	var sHTML = '<table width="100%" border="0" cellpadding="0" cellspacing="0" style="border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;"><tr><td style="font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;"><span style="font-size:14px;font-weight:bold;color:rgb(0,0,0)">안녕하세요 {회원닉네임}님,</span><br />회원님의 아이디와 패스워드를 보내드립니다.</td></tr><tr style="border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;"><td style="padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;"><p>안녕하세요,</p><p>&nbsp;</p><p>회원님의 아이디는 <strong>{회원아이디}</strong> 입니다.</p><p>아래 링크를 클릭하시면 회원님의 패스워드 변경이 가능합니다.</p><p><a href="{패스워드변경주소}" target="_blank" style="font-weight:bold;">패스워드 변경하기</a></p><p>&nbsp;</p><p>감사합니다.</p></td></tr><tr><td style="padding:10px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;text-align:center;">{홈페이지명}</td></tr></table>';
	oEditors.getById["send_email_findaccount_user_content"].exec("SET_CONTENTS", [sHTML]);
});
$(document).on('click', '.reset_resendverify', function() {
	$('#send_email_resendverify_user_title').val('{회원닉네임}님의 인증메일이 재발송되었습니다');
	var sHTML = '<table width="100%" border="0" cellpadding="0" cellspacing="0" style="border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;"><tr><td style="font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;"><span style="font-size:14px;font-weight:bold;color:rgb(0,0,0)">안녕하세요 {회원닉네임}님,</span><br />회원님의 인증메일을 다시 보내드립니다..</td></tr><tr style="border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;"><td style="padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;"><p>안녕하세요,</p><p>&nbsp;</p><p>아래 링크를 클릭하시면 이메일 인증이 완료됩니다.</p><p><a href="{메일인증주소}" target="_blank" style="font-weight:bold;">메일인증 받기</a></p><p>&nbsp;</p><p>감사합니다.</p></td></tr><tr><td style="padding:10px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;text-align:center;">{홈페이지명}</td></tr></table>';
	oEditors.getById["send_email_resendverify_user_content"].exec("SET_CONTENTS", [sHTML]);
});
//]]>
</script>
