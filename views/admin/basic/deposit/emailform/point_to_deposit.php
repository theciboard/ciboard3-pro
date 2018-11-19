<div class="box">
	<div class="box-header">
		<ul class="nav nav-tabs">
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/cash_to_deposit'); ?>" onclick="return check_form_changed();">카드/이체 등으로 예치금 구매시</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/bank_to_deposit'); ?>" onclick="return check_form_changed();">무통장입금으로 예치금구매요청시</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/approve_bank_to_deposit'); ?>" onclick="return check_form_changed();">무통장입금 완료처리시</a></li>
			<li role="presentation" class="active"><a href="<?php echo admin_url($this->pagedir . '/point_to_deposit'); ?>" onclick="return check_form_changed();">포인트로 예치금 구매시</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/deposit_to_point'); ?>" onclick="return check_form_changed();">예치금을 포인트로 전환시</a></li>
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
			<div class="form-group">
				<label class="col-sm-2 control-label">포인트로 예치금 구매시</label>
				<div class="col-sm-10">
					<div class="checkbox">
						<label for="deposit_email_admin_point_to_deposit">
							<input type="checkbox" name="deposit_email_admin_point_to_deposit" id="deposit_email_admin_point_to_deposit" value="1" <?php echo set_checkbox('deposit_email_admin_point_to_deposit', '1', (element('deposit_email_admin_point_to_deposit', element('data', $view)) ? true : false)); ?> /> <strong>최고관리자</strong>에게 <strong>메일</strong>을 발송합니다 (이메일 수신동의 여부와 상관없이 <strong>무조건 발송</strong>합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="deposit_email_user_point_to_deposit">
							<input type="checkbox" name="deposit_email_user_point_to_deposit" id="deposit_email_user_point_to_deposit" value="1" <?php echo set_checkbox('deposit_email_user_point_to_deposit', '1', (element('deposit_email_user_point_to_deposit', element('data', $view)) ? true : false)); ?> /> <strong>회원</strong>에게 <strong>메일</strong>을 발송합니다 (이메일수신<strong>동의한 회원</strong>에게만 발송합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="deposit_email_alluser_point_to_deposit">
							<input type="checkbox" name="deposit_email_alluser_point_to_deposit" id="deposit_email_alluser_point_to_deposit" value="1" <?php echo set_checkbox('deposit_email_alluser_point_to_deposit', '1', (element('deposit_email_alluser_point_to_deposit', element('data', $view)) ? true : false)); ?> /> <strong>회원</strong>에게 <strong>메일</strong>을 발송합니다 (이메일 수신동의 여부와 상관없이 <strong>무조건 발송</strong>합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="deposit_note_admin_point_to_deposit">
							<input type="checkbox" name="deposit_note_admin_point_to_deposit" id="deposit_note_admin_point_to_deposit" value="1" <?php echo set_checkbox('deposit_note_admin_point_to_deposit', '1', (element('deposit_note_admin_point_to_deposit', element('data', $view)) ? true : false)); ?> /> <strong>최고관리자</strong>에게 <strong>쪽지</strong>를 발송합니다 (쪽지사용에 <strong>동의한 관리자</strong>에게만 발송합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="deposit_note_user_point_to_deposit">
							<input type="checkbox" name="deposit_note_user_point_to_deposit" id="deposit_note_user_point_to_deposit" value="1" <?php echo set_checkbox('deposit_note_user_point_to_deposit', '1', (element('deposit_note_user_point_to_deposit', element('data', $view)) ? true : false)); ?> /> <strong>회원</strong>에게 <strong>쪽지</strong>를 발송합니다 (쪽지사용에 <strong>동의한 회원</strong>에게만 발송합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="deposit_sms_admin_point_to_deposit">
							<input type="checkbox" name="deposit_sms_admin_point_to_deposit" id="deposit_sms_admin_point_to_deposit" value="1" <?php echo set_checkbox('deposit_sms_admin_point_to_deposit', '1', (element('deposit_sms_admin_point_to_deposit', element('data', $view)) ? true : false)); ?> /> <strong>최고관리자</strong>에게 <strong>문자</strong>를 발송합니다 (문자수신동의 여부와 상관없이 <strong>무조건 발송</strong>합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="deposit_sms_user_point_to_deposit">
							<input type="checkbox" name="deposit_sms_user_point_to_deposit" id="deposit_sms_user_point_to_deposit" value="1" <?php echo set_checkbox('deposit_sms_user_point_to_deposit', '1', (element('deposit_sms_user_point_to_deposit', element('data', $view)) ? true : false)); ?> /> <strong>회원</strong>에게 <strong>문자</strong>를 발송합니다 (문자수신에 <strong>동의한 회원</strong>에게만 발송합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="deposit_sms_alluser_point_to_deposit">
							<input type="checkbox" name="deposit_sms_alluser_point_to_deposit" id="deposit_sms_alluser_point_to_deposit" value="1" <?php echo set_checkbox('deposit_sms_alluser_point_to_deposit', '1', (element('deposit_sms_alluser_point_to_deposit', element('data', $view)) ? true : false)); ?> /> <strong>회원</strong>에게 <strong>문자</strong>를 발송합니다 (문자수신 동의에 상관없이 <strong>무조건 발송</strong>합니다)
						</label>
					</div>
				</div>
			</div>
			<div class="alert alert-success">
				<p>치환가능변수 : <strong>{홈페이지명}</strong>, <strong>{회사명}</strong>, <strong>{홈페이지주소}</strong>, <strong>{회원아이디}</strong>, <strong>{회원닉네임}</strong>, <strong>{회원실명}</strong>, <strong>{회원이메일}</strong>, <strong>{메일수신여부}</strong>, <strong>{쪽지수신여부}</strong>, <strong>{문자수신여부}</strong>, <strong>{회원아이피}</strong>, <strong>{결제금액}</strong>, <strong>{전환예치금액}</strong>, <strong>{예치금명}</strong>, <strong>{예치금단위}</strong>, <strong>{전환포인트}</strong></p>
				<p><strong>{메일수신여부}</strong>, <strong>{쪽지수신여부}</strong>, <strong>{문자수신여부}</strong> 는 <strong>동의</strong>, <strong>거부</strong> - 이 2개 중 하나로 치환됩니다</p>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">
					최고관리자에게 보낼 메일<br />
					<button type="button" class="btn btn-xs btn-default reset_email_to_admin">내용초기화하기</button>
				</label>
				<div class="col-sm-10">
					<div class="form-group col-sm-12">
						<input type="text" class="form-control" name="deposit_email_admin_point_to_deposit_title" id="deposit_email_admin_point_to_deposit_title" value="<?php echo set_value('deposit_email_admin_point_to_deposit_title', element('deposit_email_admin_point_to_deposit_title', element('data', $view))); ?>" />
					</div>
					<div class="form-group col-sm-12">
						<?php echo display_dhtml_editor('deposit_email_admin_point_to_deposit_content', set_value('deposit_email_admin_point_to_deposit_content', element('deposit_email_admin_point_to_deposit_content', element('data', $view))), $classname = 'form-control dhtmleditor', $is_dhtml_editor = true, $editor_type = "smarteditor"); ?>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">
					회원에게 보낼 메일<br />
					<button type="button" class="btn btn-xs btn-default reset_email_to_user">내용초기화하기</button>
				</label>
				<div class="col-sm-10">
					<div class="form-group col-sm-12">
						<input type="text" class="form-control" name="deposit_email_user_point_to_deposit_title" id="deposit_email_user_point_to_deposit_title" value="<?php echo set_value('deposit_email_user_point_to_deposit_title', element('deposit_email_user_point_to_deposit_title', element('data', $view))); ?>" />
					</div>
					<div class="form-group col-sm-12">
						<?php echo display_dhtml_editor('deposit_email_user_point_to_deposit_content', set_value('deposit_email_user_point_to_deposit_content', element('deposit_email_user_point_to_deposit_content', element('data', $view))), $classname = 'form-control dhtmleditor', $is_dhtml_editor = true, $editor_type = "smarteditor"); ?>
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
						<input type="text" class="form-control" name="deposit_note_admin_point_to_deposit_title" id="deposit_note_admin_point_to_deposit_title" value="<?php echo set_value('deposit_note_admin_point_to_deposit_title', element('deposit_note_admin_point_to_deposit_title', element('data', $view))); ?>" />
					</div>
					<div class="form-group col-sm-12">
						<?php echo display_dhtml_editor('deposit_note_admin_point_to_deposit_content', set_value('deposit_note_admin_point_to_deposit_content', element('deposit_note_admin_point_to_deposit_content', element('data', $view))), $classname = 'form-control dhtmleditor', $is_dhtml_editor = true, $editor_type = "smarteditor"); ?>
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
						<input type="text" class="form-control" name="deposit_note_user_point_to_deposit_title" id="deposit_note_user_point_to_deposit_title" value="<?php echo set_value('deposit_note_user_point_to_deposit_title', element('deposit_note_user_point_to_deposit_title', element('data', $view))); ?>" />
					</div>
					<div class="form-group col-sm-12">
						<?php echo display_dhtml_editor('deposit_note_user_point_to_deposit_content', set_value('deposit_note_user_point_to_deposit_content', element('deposit_note_user_point_to_deposit_content', element('data', $view))), $classname = 'form-control dhtmleditor', $is_dhtml_editor = true, $editor_type = "smarteditor"); ?>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">
					최고관리자에게 보낼 문자<br />
					<button type="button" class="btn btn-xs btn-default reset_sms_to_admin">내용초기화하기</button>
				</label>
				<div class="col-sm-10 form-inline has-success ">
					<textarea class="form-control" style="width:140px;background-color:#d9edf7" rows="5" name="deposit_sms_admin_point_to_deposit_content" id="deposit_sms_admin_point_to_deposit_content"><?php echo set_value('deposit_sms_admin_point_to_deposit_content', element('deposit_sms_admin_point_to_deposit_content', element('data', $view))); ?></textarea>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">
					회원에게 보낼 문자<br />
					<button type="button" class="btn btn-xs btn-default reset_sms_to_user">내용초기화하기</button>
				</label>
				<div class="col-sm-10 form-inline has-success ">
					<textarea class="form-control" style="width:140px;background-color:#d9edf7" rows="5" name="deposit_sms_user_point_to_deposit_content" id="deposit_sms_user_point_to_deposit_content"><?php echo set_value('deposit_sms_user_point_to_deposit_content', element('deposit_sms_user_point_to_deposit_content', element('data', $view))); ?></textarea>
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
			deposit_email_admin_point_to_deposit_content : {'required_smarteditor' : true },
			deposit_email_user_point_to_deposit_content : {'required_smarteditor' : true },
			deposit_note_admin_point_to_deposit_content : {'required_smarteditor' : true },
			deposit_note_user_point_to_deposit_content : {'required_smarteditor' : true }
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
	$(document).on('change', '#deposit_email_alluser_point_to_deposit', function() {
		if ($(this).is(':checked')) {
			$('#deposit_email_user_point_to_deposit').prop('checked', false).prop('disabled', true);
		} else {
			$('#deposit_email_user_point_to_deposit').prop('disabled', false);
		}
	});
	<?php if (element('deposit_email_alluser_point_to_deposit', element('data', $view))) {?>
		$('#deposit_email_user_point_to_deposit').prop('checked', false).prop('disabled', true);
	<?php } ?>
	$(document).on('change', '#deposit_email_user_point_to_deposit', function() {
		if ($(this).is(':checked')) {
			$('#deposit_email_alluser_point_to_deposit').prop('checked', false).prop('disabled', true);
		} else {
			$('#deposit_email_alluser_point_to_deposit').prop('disabled', false);
		}
	});
	<?php if (element('deposit_email_user_point_to_deposit', element('data', $view))) {?>
		$('#deposit_email_alluser_point_to_deposit').prop('checked', false).prop('disabled', true);
	<?php } ?>
	$(document).on('change', '#deposit_sms_alluser_point_to_deposit', function() {
		if ($(this).is(':checked')) {
			$('#deposit_sms_user_point_to_deposit').prop('checked', false).prop('disabled', true);
		} else {
			$('#deposit_sms_user_point_to_deposit').prop('disabled', false);
		}
	});
	<?php if (element('deposit_sms_alluser_point_to_deposit', element('data', $view))) {?>
		$('#deposit_sms_user_point_to_deposit').prop('checked', false).prop('disabled', true);
	<?php } ?>
	$(document).on('change', '#deposit_sms_user_point_to_deposit', function() {
		if ($(this).is(':checked')) {
			$('#deposit_sms_alluser_point_to_deposit').prop('checked', false).prop('disabled', true);
		} else {
			$('#deposit_sms_alluser_point_to_deposit').prop('disabled', false);
		}
	});
	<?php if (element('deposit_sms_user_point_to_deposit', element('data', $view))) {?>
		$('#deposit_sms_alluser_point_to_deposit').prop('checked', false).prop('disabled', true);
	<?php } ?>
});

$(document).on('click', '.reset_email_to_admin', function() {
	$('#deposit_email_admin_point_to_deposit_title').val('[구매 알림] {회원닉네임}님이 포인트로 {예치금명} 구매 하셨습니다');
	var sHTML = '<table width="100%" border="0" cellpadding="0" cellspacing="0" style="border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;"><tr><td style="font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;"><span style="font-size:14px;font-weight:bold;color:rgb(0,0,0)">안녕하세요 관리자님,</span><br />포인트로 {예치금명} 구매가 완료되었습니다</td></tr><tr style="border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;"><td style="padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;"><div><p>안녕하세요</p><p>{회원닉네임}님이 포인트로 {예치금명} 구매하셨습니다</p><p>회원님께서 구매하신 내용입니다</p><p>사용포인트 : {전환포인트} 점</p><p>전환되는 {예치금명} : {전환예치금액}{예치금단위}</p><p>감사합니다</p></div><p><a href="{홈페이지주소}" target="_blank" style="font-weight:bold;">홈페이지 가기</a></p><p>&nbsp;</p></td></tr><tr><td style="padding:10px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;text-align:center;">{홈페이지명}</td></tr></table>';
	oEditors.getById["deposit_email_admin_point_to_deposit_content"].exec("SET_CONTENTS", [sHTML]);
});
$(document).on('click', '.reset_email_to_user', function() {
	$('#deposit_email_user_point_to_deposit_title').val('[{홈페이지명}] 포인트 결제가 완료되었습니다');
	var sHTML = '<table width="100%" border="0" cellpadding="0" cellspacing="0" style="border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;"><tr><td style="font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;"><span style="font-size:14px;font-weight:bold;color:rgb(0,0,0)">안녕하세요 {회원닉네임}님,</span><br />포인트로 {예치금명} 구매가 완료되었습니다</td></tr><tr style="border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;"><td style="padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;"><div><p>안녕하세요</p><p>구매해주셔서 감사합니다</p><p>회원님께서 구매하신 내용입니다</p><p>사용포인트 : {전환포인트} 점</p><p>전환되는 {예치금명} : {전환예치금액}{예치금단위}</p><p>감사합니다</p></div><p><a href="{홈페이지주소}" target="_blank" style="font-weight:bold;">홈페이지 가기</a></p><p>&nbsp;</p></td></tr><tr><td style="padding:10px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;text-align:center;">{홈페이지명}</td></tr></table>';
	oEditors.getById["deposit_email_user_point_to_deposit_content"].exec("SET_CONTENTS", [sHTML]);
});
$(document).on('click', '.reset_note_to_admin', function() {
	$('#deposit_note_admin_point_to_deposit_title').val('[구매 알림] {회원닉네임}님이 포인트로 {예치금명} 구매 하셨습니다');
	var sHTML = '<table width="100%" border="0" cellpadding="0" cellspacing="0" style="border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;"><tr><td style="font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;"><span style="font-size:14px;font-weight:bold;color:rgb(0,0,0)">안녕하세요 관리자님,</span><br />포인트로 {예치금명} 구매가 완료되었습니다</td></tr><tr style="border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;"><td style="padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;"><div><p>안녕하세요</p><p>{회원닉네임}님이 포인트로 {예치금명} 구매하셨습니다</p><p>회원님께서 구매하신 내용입니다</p><p>사용포인트 : {전환포인트} 점</p><p>전환되는 {예치금명} : {전환예치금액}{예치금단위}</p><p>감사합니다</p></div><p><a href="{홈페이지주소}" target="_blank" style="font-weight:bold;">홈페이지 가기</a></p><p>&nbsp;</p></td></tr><tr><td style="padding:10px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;text-align:center;">{홈페이지명}</td></tr></table>';
	oEditors.getById["deposit_note_admin_point_to_deposit_content"].exec("SET_CONTENTS", [sHTML]);
});
$(document).on('click', '.reset_note_to_user', function() {
	$('#deposit_note_user_point_to_deposit_title').val('포인트 결제가 완료되었습니다');
	var sHTML = '<table width="100%" border="0" cellpadding="0" cellspacing="0" style="border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;"><tr><td style="font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;"><span style="font-size:14px;font-weight:bold;color:rgb(0,0,0)">안녕하세요 {회원닉네임}님,</span><br />포인트로 {예치금명} 구매가 완료되었습니다</td></tr><tr style="border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;"><td style="padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;"><div><p>안녕하세요</p><p>구매해주셔서 감사합니다</p><p>회원님께서 구매하신 내용입니다</p><p>사용포인트 : {전환포인트} 점</p><p>전환되는 {예치금명} : {전환예치금액}{예치금단위}</p><p>감사합니다</p></div><p><a href="{홈페이지주소}" target="_blank" style="font-weight:bold;">홈페이지 가기</a></p><p>&nbsp;</p></td></tr><tr><td style="padding:10px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;text-align:center;">{홈페이지명}</td></tr></table>';
	oEditors.getById["deposit_note_user_point_to_deposit_content"].exec("SET_CONTENTS", [sHTML]);
});
$(document).on('click', '.reset_sms_to_admin', function() {
	$('#deposit_sms_admin_point_to_deposit_content').val('[포인트->예치금 결제] {회원닉네임} 님 결제 완료');
});
$(document).on('click', '.reset_sms_to_user', function() {
	$('#deposit_sms_user_point_to_deposit_content').val('[{홈페이지명}] 결제완료 - 전환{예치금명}:{전환예치금액}{예치금단위} 감사합니다');
});
//]]>
</script>
