<div class="box">
	<div class="box-header">
		<ul class="nav nav-tabs">
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir); ?>" onclick="return check_form_changed();">기본정보</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/cleantodormant'); ?>" onclick="return check_form_changed();">휴면계정일괄정리</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/emailtowaiting'); ?>" onclick="return check_form_changed();">안내메일일괄발송</a></li>
			<li role="presentation" class="active"><a href="<?php echo admin_url($this->pagedir . '/emailcontent'); ?>" onclick="return check_form_changed();">안내메일내용</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/emailsendlist'); ?>" onclick="return check_form_changed();">안내메일발송내역</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/waitinglist'); ?>" onclick="return check_form_changed();">휴면처리해야할회원</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/dormantlist'); ?>" onclick="return check_form_changed();">휴면중인회원</a></li>
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
				<p>휴면회원으로 전환되기 일정한 기간 전에 회원에게 보내는 안내메일 내용입니다</p>
				<hr />
				<p>치환가능변수 : <strong>{홈페이지명}</strong>, <strong>{회사명}</strong>, <strong>{홈페이지주소}</strong>, <strong>{회원아이디}</strong>, <strong>{회원닉네임}</strong>, <strong>{회원실명}</strong>, <strong>{회원이메일}</strong>, <strong>{메일수신여부}</strong>, <strong>{쪽지수신여부}</strong>, <strong>{문자수신여부}</strong>, <strong>{최종로그인시간}</strong>, <strong>{정리예정날짜}</strong>, <strong>{정리기준}</strong>, <strong>{정리방법}</strong></p>
				<p><strong>{메일수신여부}</strong>, <strong>{쪽지수신여부}</strong>, <strong>{문자수신여부}</strong> 는 <strong>동의</strong>, <strong>거부</strong> - 이 2개 중 하나로 치환됩니다</p>
				<p><strong>{최종로그인시간}</strong> - 예) 0000년 0월 00일 00시 00분</p>
				<p><strong>{정리예정날짜}</strong> - 예) 0000년 0월 00일</p>
				<p><strong>{정리기준}</strong> - 예) 1년</p>
				<p><strong>{정리방법}</strong> - 예) '삭제' or '별도의 저장소에 보관'</p>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">
					메일 내용<br />
					<button type="button" class="btn btn-xs btn-default reset_email_to_user">내용초기화하기</button>
				</label>
				<div class="col-sm-10">
					<div class="form-group col-sm-12">
						<input type="text" class="form-control" name="send_email_dormant_notify_user_title" id="send_email_dormant_notify_user_title" value="<?php echo set_value('send_email_dormant_notify_user_title', element('send_email_dormant_notify_user_title', element('data', $view))); ?>" />
					</div>
					<div class="form-group col-sm-12">
						<?php echo display_dhtml_editor('send_email_dormant_notify_user_content', set_value('send_email_dormant_notify_user_content', element('send_email_dormant_notify_user_content', element('data', $view))), $classname = 'form-control dhtmleditor', $is_dhtml_editor = true, $editor_type = "smarteditor"); ?>
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
			send_email_dormant_notify_user_title : {'required' : true },
			send_email_dormant_notify_user_content : {'required_smarteditor' : true }
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

$(document).on('click', '.reset_email_to_user', function() {
	$('#send_email_dormant_notify_user_title').val('[{홈페이지명}] 휴면 계정 전환 예정 안내');
	var sHTML = '<table width="100%" cellpadding="0" cellspacing="0" style="border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;"><tbody><tr><td style="font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;"><span style="font-size:14px;font-weight:bold;color:rgb(0,0,0)">안녕하세요 {회원닉네임}님,</span><br />항상 믿고 이용해주시는 회원님께 깊은 감사를 드립니다.</td></tr><tr style="border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;"><td style="padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;"><p>{정리기준} 이상 서비스를 이용하지 않은 계정 ‘정보통신망 이용 촉진 및 정보보호 등에 관한 법률 및 시행령 제16조에 따라 휴면 계정으로 전환되며, 해당 계정 정보는 별도 분리 보관될 예정입니다. </p><p>(법령 시행일 : 2015년 8월 18일)</P><p>&nbsp;</p><p><strong>1. 적용 대상 :</strong> {정리기준}간 로그인 기록이 없는 고객의 개인정보</p><p><strong>2. 적용 시점 :</strong> {정리예정날짜}</p><p><strong>3. 처리 방법 :</strong> {정리방법}</p><p>&nbsp;</p><p>{홈페이지명}에서는 앞으로도 회원님의 개인정보를 소중하게 관리하여 보다 더 안전하게 서비스를 이용하실 수 있도록 최선의 노력을 다하겠습니다. 많은 관심과 참여 부탁 드립니다. 감사합니다.</p></td></tr><tr><td style="padding:10px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;text-align:center;">{홈페이지명}</td></tr></tbody></table>';
	oEditors.getById["send_email_dormant_notify_user_content"].exec("SET_CONTENTS", [sHTML]);
});
//]]>
</script>
