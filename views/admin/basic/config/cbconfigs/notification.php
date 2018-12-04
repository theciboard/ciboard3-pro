<div class="box">
	<div class="box-header">
		<ul class="nav nav-tabs">
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir); ?>" onclick="return check_form_changed();">기본정보</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/access'); ?>" onclick="return check_form_changed();">접근기능</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/point'); ?>" onclick="return check_form_changed();">포인트기능</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/general'); ?>" onclick="return check_form_changed();">일반기능 / 에디터</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/note'); ?>" onclick="return check_form_changed();">쪽지기능</a></li>
			<li role="presentation" class="active"><a href="<?php echo admin_url($this->pagedir . '/notification'); ?>" onclick="return check_form_changed();">알림기능</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/company'); ?>" onclick="return check_form_changed();">회사정보</a></li>
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
				<label class="col-sm-2 control-label">알림기능</label>
				<div class="col-sm-10">
					<label for="use_notification" class="checkbox-inline">
					<input type="checkbox" name="use_notification" id="use_notification" value="1" <?php echo set_checkbox('use_notification', '1', (element('use_notification', element('data', $view)) ? true : false)); ?> /> 사용합니다
					</label>
					<label class=" form-inline" style="padding-top:7px;padding-left:10px;">
						<span class="fa fa-bell-o"></span><a href="<?php echo site_url('notification'); ?>" target="_blank"><?php echo site_url('notification'); ?></a>
					</label>
					<div class="help-block">알림기능을 사용하시게 되면, 내 글에 댓글이 달리거나, 쪽지가 수신된 경우에 우측상단에 알림이 뜨게 됩니다</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">내 글에 답변글이 달렸을 때 알림</label>
				<div class="col-sm-10">
					<label for="notification_reply" class="checkbox-inline">
						<input type="checkbox" name="notification_reply" id="notification_reply" class="chk" value="1" <?php echo set_checkbox('notification_reply', '1', (element('notification_reply', element('data', $view)) ? true : false)); ?> /> 사용합니다
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">내 글에 댓글이 달렸을 때 알림</label>
				<div class="col-sm-10">
					<label for="notification_comment" class="checkbox-inline">
						<input type="checkbox" name="notification_comment" id="notification_comment" class="chk" value="1" <?php echo set_checkbox('notification_comment', '1', (element('notification_comment', element('data', $view)) ? true : false)); ?> /> 사용합니다
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">내 댓글에 댓글이 달렸을 때 알림</label>
				<div class="col-sm-10">
					<label for="notification_comment_comment" class="checkbox-inline">
						<input type="checkbox" name="notification_comment_comment" id="notification_comment_comment" class="chk" value="1" <?php echo set_checkbox('notification_comment_comment', '1', (element('notification_comment_comment', element('data', $view)) ? true : false)); ?> /> 사용합니다
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">쪽지가 도착하였을 때 알림</label>
				<div class="col-sm-10">
					<label for="notification_note" class="checkbox-inline">
						<input type="checkbox" name="notification_note" id="notification_note" class="chk" value="1" <?php echo set_checkbox('notification_note', '1', (element('notification_note', element('data', $view)) ? true : false)); ?> /> 사용합니다
					</label>
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
	$(document).on('change', '#use_notification', function() {
		if ($(this).is(':checked')) {
			$('.chk').prop('checked', true).prop('disabled', false);
		} else {
			$('.chk').prop('checked', false).prop('disabled', true);
		}
	});
	<?php if ( ! element('use_notification', element('data', $view))) {?>
		$('.chk').prop('checked', false).prop('disabled', true);
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
