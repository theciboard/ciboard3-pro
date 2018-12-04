<div class="box">
	<div class="box-header">
		<ul class="nav nav-tabs">
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir); ?>" onclick="return check_form_changed();">기본정보</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/registerform'); ?>" onclick="return check_form_changed();">가입폼관리</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/membermodify'); ?>" onclick="return check_form_changed();">정보수정시</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/login'); ?>" onclick="return check_form_changed();">로그인</a></li>
			<li role="presentation" class="active"><a href="<?php echo admin_url($this->pagedir . '/alarm'); ?>" onclick="return check_form_changed();">알림 설정</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/sociallogin'); ?>" onclick="return check_form_changed();">소셜로그인</a></li>
		</ul>
	</div>
	<div class="box-table">
		<?php
		echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
		echo show_alert_message(element('alert_message', $view), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
		$attributes = array('class' => 'form-horizontal', 'name' => 'fadminwrite', 'id' => 'fadminwrite');
		echo form_open(current_full_url(), $attributes);
		
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
			<input type="hidden" name="is_submit" value="1" />
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
			<div class="form-group">
				<label class="col-sm-2 control-label">패스워드변경시</label>
				<div class="col-sm-10">
					<div class="checkbox">
						<label for="send_email_changepw_admin">
							<input type="checkbox" name="send_email_changepw_admin" id="send_email_changepw_admin" value="1" <?php echo set_checkbox('send_email_changepw_admin', '1', (element('send_email_changepw_admin', element('data', $view)) ? true : false)); ?> /> <strong>최고관리자</strong>에게 <strong>메일</strong>을 발송합니다 (이메일 수신동의 여부와 상관없이 <strong>무조건 발송</strong>합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="send_email_changepw_user">
							<input type="checkbox" name="send_email_changepw_user" id="send_email_changepw_user" value="1" <?php echo set_checkbox('send_email_changepw_user', '1', (element('send_email_changepw_user', element('data', $view)) ? true : false)); ?> /> <strong>회원</strong>에게 <strong>메일</strong>을 발송합니다 (이메일수신<strong>동의한 회원</strong>에게만 발송합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="send_email_changepw_alluser">
							<input type="checkbox" name="send_email_changepw_alluser" id="send_email_changepw_alluser" value="1" <?php echo set_checkbox('send_email_changepw_alluser', '1', (element('send_email_changepw_alluser', element('data', $view)) ? true : false)); ?> /> <strong>회원</strong>에게 <strong>메일</strong>을 발송합니다 (이메일 수신동의 여부와 상관없이 <strong>무조건 발송</strong>합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="send_note_changepw_admin">
							<input type="checkbox" name="send_note_changepw_admin" id="send_note_changepw_admin" value="1" <?php echo set_checkbox('send_note_changepw_admin', '1', (element('send_note_changepw_admin', element('data', $view)) ? true : false)); ?> /> <strong>최고관리자</strong>에게 <strong>쪽지</strong>를 발송합니다 (쪽지사용에 <strong>동의한 관리자</strong>에게만 발송합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="send_note_changepw_user">
							<input type="checkbox" name="send_note_changepw_user" id="send_note_changepw_user" value="1" <?php echo set_checkbox('send_note_changepw_user', '1', (element('send_note_changepw_user', element('data', $view)) ? true : false)); ?> /> <strong>회원</strong>에게 <strong>쪽지</strong>를 발송합니다 (쪽지사용에 <strong>동의한 회원</strong>에게만 발송합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="send_sms_changepw_admin">
							<input type="checkbox" name="send_sms_changepw_admin" id="send_sms_changepw_admin" value="1" <?php echo set_checkbox('send_sms_changepw_admin', '1', (element('send_sms_changepw_admin', element('data', $view)) ? true : false)); ?> <?php echo ( ! element('sms_library_exists', $view)) ? 'disabled="disabled"' : ''; ?> /> <strong>최고관리자</strong>에게 <strong>문자</strong>를 발송합니다 (문자수신동의 여부와 상관없이 <strong>무조건 발송</strong>합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="send_sms_changepw_user">
							<input type="checkbox" name="send_sms_changepw_user" id="send_sms_changepw_user" value="1" <?php echo set_checkbox('send_sms_changepw_user', '1', (element('send_sms_changepw_user', element('data', $view)) ? true : false)); ?> <?php echo ( ! element('sms_library_exists', $view)) ? 'disabled="disabled"' : ''; ?> /> <strong>회원</strong>에게 <strong>문자</strong>를 발송합니다 (문자수신에 <strong>동의한 회원</strong>에게만 발송합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="send_sms_changepw_alluser">
							<input type="checkbox" name="send_sms_changepw_alluser" id="send_sms_changepw_alluser" value="1" <?php echo set_checkbox('send_sms_changepw_alluser', '1', (element('send_sms_changepw_alluser', element('data', $view)) ? true : false)); ?> <?php echo ( ! element('sms_library_exists', $view)) ? 'disabled="disabled"' : ''; ?> /> <strong>회원</strong>에게 <strong>문자</strong>를 발송합니다 (문자수신 동의에 상관없이 <strong>무조건 발송</strong>합니다)
						</label>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">회원탈퇴시</label>
				<div class="col-sm-10">
					<div class="checkbox">
						<label for="send_email_memberleave_admin">
							<input type="checkbox" name="send_email_memberleave_admin" id="send_email_memberleave_admin" value="1" <?php echo set_checkbox('send_email_memberleave_admin', '1', (element('send_email_memberleave_admin', element('data', $view)) ? true : false)); ?> /> <strong>최고관리자</strong>에게 <strong>메일</strong>을 발송합니다 (이메일 수신동의 여부와 상관없이 <strong>무조건 발송</strong>합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="send_email_memberleave_user">
							<input type="checkbox" name="send_email_memberleave_user" id="send_email_memberleave_user" value="1" <?php echo set_checkbox('send_email_memberleave_user', '1', (element('send_email_memberleave_user', element('data', $view)) ? true : false)); ?> /> <strong>회원</strong>에게 <strong>메일</strong>을 발송합니다 (이메일수신<strong>동의한 회원</strong>에게만 발송합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="send_email_memberleave_alluser">
							<input type="checkbox" name="send_email_memberleave_alluser" id="send_email_memberleave_alluser" value="1" <?php echo set_checkbox('send_email_memberleave_alluser', '1', (element('send_email_memberleave_alluser', element('data', $view)) ? true : false)); ?> /> <strong>회원</strong>에게 <strong>메일</strong>을 발송합니다 (이메일 수신동의 여부와 상관없이 <strong>무조건 발송</strong>합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="send_note_memberleave_admin">
							<input type="checkbox" name="send_note_memberleave_admin" id="send_note_memberleave_admin" value="1" <?php echo set_checkbox('send_note_memberleave_admin', '1', (element('send_note_memberleave_admin', element('data', $view)) ? true : false)); ?> /> <strong>최고관리자</strong>에게 <strong>쪽지</strong>를 발송합니다 (쪽지사용에 <strong>동의한 관리자</strong>에게만 발송합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="send_sms_memberleave_admin">
							<input type="checkbox" name="send_sms_memberleave_admin" id="send_sms_memberleave_admin" value="1" <?php echo set_checkbox('send_sms_memberleave_admin', '1', (element('send_sms_memberleave_admin', element('data', $view)) ? true : false)); ?> <?php echo ( ! element('sms_library_exists', $view)) ? 'disabled="disabled"' : ''; ?> /> <strong>최고관리자</strong>에게 <strong>문자</strong>를 발송합니다 (문자수신동의 여부와 상관없이 <strong>무조건 발송</strong>합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="send_sms_memberleave_user">
							<input type="checkbox" name="send_sms_memberleave_user" id="send_sms_memberleave_user" value="1" <?php echo set_checkbox('send_sms_memberleave_user', '1', (element('send_sms_memberleave_user', element('data', $view)) ? true : false)); ?> <?php echo ( ! element('sms_library_exists', $view)) ? 'disabled="disabled"' : ''; ?> /> <strong>회원</strong>에게 <strong>문자</strong>를 발송합니다 (문자수신에 <strong>동의한 회원</strong>에게만 발송합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="send_sms_memberleave_alluser">
							<input type="checkbox" name="send_sms_memberleave_alluser" id="send_sms_memberleave_alluser" value="1" <?php echo set_checkbox('send_sms_memberleave_alluser', '1', (element('send_sms_memberleave_alluser', element('data', $view)) ? true : false)); ?> <?php echo ( ! element('sms_library_exists', $view)) ? 'disabled="disabled"' : ''; ?> /> <strong>회원</strong>에게 <strong>문자</strong>를 발송합니다 (문자수신 동의에 상관없이 <strong>무조건 발송</strong>합니다)
						</label>
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

$(function() {
	$(document).on('change', '#send_email_changepw_alluser', function() {
		if ($(this).is(':checked')) {
			$('#send_email_changepw_user').prop('checked', false).prop('disabled', true);
		} else {
			$('#send_email_changepw_user').prop('disabled', false);
		}
	});
	<?php if (element('send_email_changepw_alluser', element('data', $view))) {?>
		$('#send_email_changepw_user').prop('checked', false).prop('disabled', true);
	<?php } ?>
	$(document).on('change', '#send_email_changepw_user', function() {
		if ($(this).is(':checked')) {
			$('#send_email_changepw_alluser').prop('checked', false).prop('disabled', true);
		} else {
			$('#send_email_changepw_alluser').prop('disabled', false);
		}
	});
	<?php if (element('send_email_changepw_user', element('data', $view))) {?>
		$('#send_email_changepw_alluser').prop('checked', false).prop('disabled', true);
	<?php } ?>
	$(document).on('change', '#send_sms_changepw_alluser', function() {
		if ($(this).is(':checked')) {
			$('#send_sms_changepw_user').prop('checked', false).prop('disabled', true);
		} else {
			$('#send_sms_changepw_user').prop('disabled', false);
		}
	});
	<?php if (element('send_sms_changepw_alluser', element('data', $view))) {?>
		$('#send_sms_changepw_user').prop('checked', false).prop('disabled', true);
	<?php } ?>
	$(document).on('change', '#send_sms_changepw_user', function() {
		if ($(this).is(':checked')) {
			$('#send_sms_changepw_alluser').prop('checked', false).prop('disabled', true);
		} else {
			$('#send_sms_changepw_alluser').prop('disabled', false);
		}
	});
	<?php if (element('send_sms_changepw_user', element('data', $view))) {?>
		$('#send_sms_changepw_alluser').prop('checked', false).prop('disabled', true);
	<?php } ?>
});

$(function() {
	$(document).on('change', '#send_email_memberleave_alluser', function() {
		if ($(this).is(':checked')) {
			$('#send_email_memberleave_user').prop('checked', false).prop('disabled', true);
		} else {
			$('#send_email_memberleave_user').prop('disabled', false);
		}
	});
	<?php if (element('send_email_memberleave_alluser', element('data', $view))) {?>
		$('#send_email_memberleave_user').prop('checked', false).prop('disabled', true);
	<?php } ?>
	$(document).on('change', '#send_email_memberleave_user', function() {
		if ($(this).is(':checked')) {
			$('#send_email_memberleave_alluser').prop('checked', false).prop('disabled', true);
		} else {
			$('#send_email_memberleave_alluser').prop('disabled', false);
		}
	});
	<?php if (element('send_email_memberleave_user', element('data', $view))) {?>
		$('#send_email_memberleave_alluser').prop('checked', false).prop('disabled', true);
	<?php } ?>
	$(document).on('change', '#send_sms_memberleave_alluser', function() {
		if ($(this).is(':checked')) {
			$('#send_sms_memberleave_user').prop('checked', false).prop('disabled', true);
		} else {
			$('#send_sms_memberleave_user').prop('disabled', false);
		}
	});
	<?php if (element('send_sms_memberleave_alluser', element('data', $view))) {?>
		$('#send_sms_memberleave_user').prop('checked', false).prop('disabled', true);
	<?php } ?>
	$(document).on('change', '#send_sms_memberleave_user', function() {
		if ($(this).is(':checked')) {
			$('#send_sms_memberleave_alluser').prop('checked', false).prop('disabled', true);
		} else {
			$('#send_sms_memberleave_alluser').prop('disabled', false);
		}
	});
	<?php if (element('send_sms_memberleave_user', element('data', $view))) {?>
		$('#send_sms_memberleave_alluser').prop('checked', false).prop('disabled', true);
	<?php } ?>
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
