<div class="box">
	<div class="box-header">
		<ul class="nav nav-tabs">
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/register'); ?>" onclick="return check_form_changed();">회원가입</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/changepw'); ?>" onclick="return check_form_changed();">패스워드변경</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/memberleave'); ?>" onclick="return check_form_changed();">회원탈퇴</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/changeemail'); ?>" onclick="return check_form_changed();">이메일변경시인증메일</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/findaccount'); ?>" onclick="return check_form_changed();">회원정보찾기</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/post'); ?>" onclick="return check_form_changed();">게시글작성</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/comment'); ?>" onclick="return check_form_changed();">댓글작성</a></li>
			<li role="presentation" class="active"><a href="<?php echo admin_url($this->pagedir . '/blame'); ?>" onclick="return check_form_changed();">게시글신고발생</a></li>
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
				<hr />
				<p><strong>치환가능변수</strong></p>
				<p>일반 : <strong>{홈페이지명}</strong>, <strong>{회사명}</strong>, <strong>{홈페이지주소}</strong></p>
				<p>게시글관련 : <strong>{게시글제목}</strong>, <strong>{게시글내용}</strong>, <strong>{게시글작성자닉네임}</strong>, <strong>{게시글작성자아이디}</strong>, <strong>{게시글작성시간}</strong>, <strong>{게시글주소}</strong></p>
				<p>게시판관련 : <strong>{게시판명}</strong>, <strong>{게시판주소}</strong></p>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">
					관리자에게 보낼 메일<br />
					<button type="button" class="btn btn-xs btn-default reset_email_to_admin">내용초기화하기</button>
				</label>
				<div class="col-sm-10">
					<div class="form-group col-sm-12">
						<input type="text" class="form-control" name="send_email_blame_admin_title" id="send_email_blame_admin_title" value="<?php echo set_value('send_email_blame_admin_title', element('send_email_blame_admin_title', element('data', $view))); ?>" />
					</div>
					<div class="form-group col-sm-12">
						<?php echo display_dhtml_editor('send_email_blame_admin_content', set_value('send_email_blame_admin_content', element('send_email_blame_admin_content', element('data', $view))), $classname = 'form-control dhtmleditor', $is_dhtml_editor = true, $editor_type = "smarteditor"); ?>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">
					게시글작성자에게 보낼 메일<br />
					<button type="button" class="btn btn-xs btn-default reset_email_to_blame_writer">내용초기화하기</button>
				</label>
				<div class="col-sm-10">
					<div class="form-group col-sm-12">
						<input type="text" class="form-control" name="send_email_blame_post_writer_title" id="send_email_blame_post_writer_title" value="<?php echo set_value('send_email_blame_post_writer_title', element('send_email_blame_post_writer_title', element('data', $view))); ?>" />
					</div>
					<div class="form-group col-sm-12">
						<?php echo display_dhtml_editor('send_email_blame_post_writer_content', set_value('send_email_blame_post_writer_content', element('send_email_blame_post_writer_content', element('data', $view))), $classname = 'form-control dhtmleditor', $is_dhtml_editor = true, $editor_type = "smarteditor"); ?>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">
					관리자에게 보낼 쪽지<br />
					<button type="button" class="btn btn-xs btn-default reset_note_to_admin">내용초기화하기</button>
				</label>
				<div class="col-sm-10">
					<div class="form-group col-sm-12">
						<input type="text" class="form-control" name="send_note_blame_admin_title" id="send_note_blame_admin_title" value="<?php echo set_value('send_note_blame_admin_title', element('send_note_blame_admin_title', element('data', $view))); ?>" />
					</div>
					<div class="form-group col-sm-12">
						<?php echo display_dhtml_editor('send_note_blame_admin_content', set_value('send_note_blame_admin_content', element('send_note_blame_admin_content', element('data', $view))), $classname = 'form-control dhtmleditor', $is_dhtml_editor = true, $editor_type = "smarteditor"); ?>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">
					게시글작성자에게 보낼 쪽지<br />
					<button type="button" class="btn btn-xs btn-default reset_note_to_blame_writer">내용초기화하기</button>
				</label>
				<div class="col-sm-10">
					<div class="form-group col-sm-12">
						<input type="text" class="form-control" name="send_note_blame_post_writer_title" id="send_note_blame_post_writer_title" value="<?php echo set_value('send_note_blame_post_writer_title', element('send_note_blame_post_writer_title', element('data', $view))); ?>" />
					</div>
					<div class="form-group col-sm-12">
						<?php echo display_dhtml_editor('send_note_blame_post_writer_content', set_value('send_note_blame_post_writer_content', element('send_note_blame_post_writer_content', element('data', $view))), $classname = 'form-control dhtmleditor', $is_dhtml_editor = true, $editor_type = "smarteditor"); ?>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">
					관리자에게 보낼 문자<br />
					<button type="button" class="btn btn-xs btn-default reset_sms_to_admin">내용초기화하기</button>
				</label>
				<div class="col-sm-10 form-inline has-success ">
					<textarea class="form-control" style="width:140px;background-color:#d9edf7" rows="5" name="send_sms_blame_admin_content" id="send_sms_blame_admin_content"><?php echo set_value('send_sms_blame_admin_content', element('send_sms_blame_admin_content', element('data', $view))); ?></textarea>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">
					게시글작성자에게 보낼 문자<br />
					<button type="button" class="btn btn-xs btn-default reset_sms_to_blame_writer">내용초기화하기</button>
				</label>
				<div class="col-sm-10 form-inline has-success ">
					<textarea class="form-control" style="width:140px;background-color:#d9edf7" rows="5" name="send_sms_blame_post_writer_content" id="send_sms_blame_post_writer_content"><?php echo set_value('send_sms_blame_post_writer_content', element('send_sms_blame_post_writer_content', element('data', $view))); ?></textarea>
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
			send_email_blame_admin_content : {'required_smarteditor' : true },
			send_email_blame_post_writer_content : {'required_smarteditor' : true },
			send_note_blame_admin_content : {'required_smarteditor' : true },
			send_note_blame_post_writer_content : {'required_smarteditor' : true }
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

$(document).on('click', '.reset_email_to_admin', function() {
	$('#send_email_blame_admin_title').val('[{게시판명}] {게시글제목} - 신고가접수되었습니다');
	var sHTML = '<table width="100%" border="0" cellpadding="0" cellspacing="0" style="border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;"><tr><td style="font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;"><span style="font-size:14px;font-weight:bold;color:rgb(0,0,0)">{게시글제목}</span><br />게시글에 신고가 접수되었습니다</td></tr><tr style="border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;"><td style="padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;"><div>{게시글내용}</div><p><a href="{게시글주소}" target="_blank" style="font-weight:bold;">사이트에서 게시글 확인하기</a></p><p>&nbsp;</p></td></tr><tr><td style="padding:10px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;text-align:center;">{홈페이지명}</td></tr></table>';
	oEditors.getById["send_email_blame_admin_content"].exec("SET_CONTENTS", [sHTML]);
});
$(document).on('click', '.reset_email_to_blame_writer', function() {
	$('#send_email_blame_post_writer_title').val('[{게시판명}] {게시글제목} - 신고가접수되었습니다');
	var sHTML = '<table width="100%" border="0" cellpadding="0" cellspacing="0" style="border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;"><tr><td style="font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;"><span style="font-size:14px;font-weight:bold;color:rgb(0,0,0)">{게시글제목}</span><br />게시글에 신고가 접수되었습니다</td></tr><tr style="border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;"><td style="padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;"><div>{게시글내용}</div><p><a href="{게시글주소}" target="_blank" style="font-weight:bold;">사이트에서 게시글 확인하기</a></p><p>&nbsp;</p></td></tr><tr><td style="padding:10px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;text-align:center;">{홈페이지명}</td></tr></table>';
	oEditors.getById["send_email_blame_post_writer_content"].exec("SET_CONTENTS", [sHTML]);
});
$(document).on('click', '.reset_note_to_admin', function() {
	$('#send_note_blame_admin_title').val('[{게시판명}] {게시글제목} - 신고가접수되었습니다');
	var sHTML = '<table width="100%" border="0" cellpadding="0" cellspacing="0" style="border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;"><tr><td style="font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;"><span style="font-size:14px;font-weight:bold;color:rgb(0,0,0)">{게시글제목}</span><br />게시글에 신고가 접수되었습니다</td></tr><tr style="border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;"><td style="padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;"><div>{게시글내용}</div><p><a href="{게시글주소}" target="_blank" style="font-weight:bold;">사이트에서 게시글 확인하기</a></p><p>&nbsp;</p></td></tr><tr><td style="padding:10px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;text-align:center;">{홈페이지명}</td></tr></table>';
	oEditors.getById["send_note_blame_admin_content"].exec("SET_CONTENTS", [sHTML]);
});
$(document).on('click', '.reset_note_to_blame_writer', function() {
	$('#send_note_blame_post_writer_title').val('[{게시판명}] {게시글제목} - 신고가접수되었습니다');
	var sHTML = '<table width="100%" border="0" cellpadding="0" cellspacing="0" style="border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;"><tr><td style="font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;"><span style="font-size:14px;font-weight:bold;color:rgb(0,0,0)">{게시글제목}</span><br />게시글에 신고가 접수되었습니다</td></tr><tr style="border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;"><td style="padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;"><div>{게시글내용}</div><p><a href="{게시글주소}" target="_blank" style="font-weight:bold;">사이트에서 게시글 확인하기</a></p><p>&nbsp;</p></td></tr><tr><td style="padding:10px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;text-align:center;">{홈페이지명}</td></tr></table>';
	oEditors.getById["send_note_blame_post_writer_content"].exec("SET_CONTENTS", [sHTML]);
});
$(document).on('click', '.reset_sms_to_admin', function() {
	$('#send_sms_blame_admin_content').val('[게시글신고알림] {게시판명} - {게시글제목}');
});
$(document).on('click', '.reset_sms_to_blame_writer', function() {
	$('#send_sms_blame_post_writer_content').val('[게시글신고알림] {게시판명} - {게시글제목}');
});
//]]>
</script>
