<div class="box">
	<div class="box-header">
		<ul class="nav nav-tabs">
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir); ?>" onclick="return check_form_changed();">기본정보</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/registerform'); ?>" onclick="return check_form_changed();">가입폼관리</a></li>
			<li role="presentation" class="active"><a href="<?php echo admin_url($this->pagedir . '/membermodify'); ?>" onclick="return check_form_changed();">정보수정시</a></li>
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
				<label class="col-sm-2 control-label">닉네임 수정가능</label>
				<div class="col-sm-10">
					<input type="number" class="form-control" name="change_nickname_date" id="change_nickname_date" value="<?php echo set_value('change_nickname_date', (int) element('change_nickname_date', element('data', $view))); ?>" />일,
					<span class="help-inline">닉네임 변경 후 해당일 동안 바꿀 수 없습니다, 0 으로 설정하면 항상 변경 가능</span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">정보공개수정</label>
				<div class="col-sm-10">
					<input type="number" class="form-control" name="change_open_profile_date" id="change_open_profile_date" value="<?php echo set_value('change_open_profile_date', (int) element('change_open_profile_date', element('data', $view))); ?>" />일,
					<span class="help-inline">수정한 후 해당일 동안 바꿀 수 없음, 정보 공개를 한 사람만 다른 사람의 프로필을 볼 수 있음</span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">쪽지사용수정</label>
				<div class="col-sm-10">
					<input type="number" class="form-control" name="change_use_note_date" id="change_use_note_date" value="<?php echo set_value('change_use_note_date', (int) element('change_use_note_date', element('data', $view))); ?>" />일,
					<span class="help-inline">수정한 후 해당일 동안 바꿀 수 없음, 쪽지 사용수정에 체크한 사람만 쪽지를 주고 받을 수 있음</span>
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
			change_nickname_date: {required :true, number:true, min:0},
			change_open_profile_date: {required :true, number:true, min:0},
			change_use_note_date: {required :true, number:true, min:0}
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
