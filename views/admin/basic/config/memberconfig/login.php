<div class="box">
	<div class="box-header">
		<ul class="nav nav-tabs">
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir); ?>" onclick="return check_form_changed();">기본정보</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/registerform'); ?>" onclick="return check_form_changed();">가입폼관리</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/membermodify'); ?>" onclick="return check_form_changed();">정보수정시</a></li>
			<li role="presentation" class="active"><a href="<?php echo admin_url($this->pagedir . '/login'); ?>" onclick="return check_form_changed();">로그인</a></li>
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
				<label class="col-sm-2 control-label">비밀번호갱신주기</label>
				<div class="col-sm-10">
					<input type="number" class="form-control" name="change_password_date" id="change_password_date" value="<?php echo set_value('change_password_date', (int) element('change_password_date', element('data', $view))); ?>" /> 일,
					<span class="help-inline">일정기간이 지나면 비밀번호 변경을 하도록 유도하는 기능입니다. (사용하지 않음 : 0 입력)</span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">로그인시도횟수제한</label>
				<div class="col-sm-10">
					<input type="number" class="form-control" name="max_login_try_count" id="max_login_try_count" value="<?php echo set_value('max_login_try_count', (int) element('max_login_try_count', element('data', $view))); ?>" /> 회,
					<span class="help-inline">정해진 시간 안에 허용되는 로그인 시도 횟수를 입력하십시오. 짧은 시간 동안 하나의 아이피(IP)에서 시도할 수 있는 로그인 횟수에 제한을 둡니다.</span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">로그인시도제한시간</label>
				<div class="col-sm-10">
					<input type="number" class="form-control" name="max_login_try_limit_second" id="max_login_try_limit_second" value="<?php echo set_value('max_login_try_limit_second', (int) element('max_login_try_limit_second', element('data', $view))); ?>" />초, <span class="help-inline"> 위에서 설정한 횟수 이상 로그인실실패하였을 경우, 해당 초 동안 로그인 시도를 할 수 없습니다</span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">로그인후 이동할 주소</label>
				<div class="col-sm-10 form-inline">
					<?php echo rtrim(site_url(), '/'); ?><input type="text" class="form-control" name="url_after_login" id="url_after_login" value="<?php echo set_value('url_after_login', element('url_after_login', element('data', $view))); ?>" /> <span class="help-inline">로그인 후 이동할 URL을 정할 수 있습니다. 입력 URL이 없는 경우 이전 페이지가 유지됩니다.</span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">로그아웃후 이동할 주소</label>
				<div class="col-sm-10 form-inline">
					<?php echo rtrim(site_url(), '/'); ?><input type="text" class="form-control" name="url_after_logout" id="url_after_logout" value="<?php echo set_value('url_after_logout', element('url_after_logout', element('data', $view))); ?>" /> <span class="help-inline">로그아웃 후 이동할 URL을 정할 수 있습니다. 입력 URL이 없는 경우 이전 페이지가 유지됩니다.</span>
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
			change_password_date: {required:true, number:true, min:0},
			max_login_try_count: {required:true, number:true, min:0},
			max_login_try_limit_second: {required:true, number:true, min:0}
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
