<div class="box">
	<div class="box-header">
		<ul class="nav nav-tabs">
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir); ?>" onclick="return check_form_changed();">설문조사모음</a></li>
			<li role="presentation" class="active"><a href="<?php echo admin_url($this->pagedir . '/boards'); ?>" onclick="return check_form_changed();">게시판별사용여부</a></li>
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
				<div class="table-responsive">
					<table class="table table-hover table-striped table-bordered">
						<thead>
							<tr>
								<th>게시판명</th>
								<th><label for="poll_all"><input type="checkbox" name="poll_all" id="poll_all" /> PC 전체 사용</label></th>
								<th><label for="poll_mobile_all"><input type="checkbox" name="poll_mobile_all" id="poll_mobile_all" /> MOBILE 전체 사용</label></th>
								<th>설문등록가능</th>
								<th>설문참여가능</th>
							</tr>
						</thead>
						<tbody>
						<?php 
						if (element('boardlist', $view)) { 
							foreach (element('boardlist', $view) as $key => $value) {
						?>
								<tr>
									<td><?php echo html_escape(element('brd_name', $value)); ?></td>
									<td>
										<label for="use_poll_<?php echo element('brd_id', $value); ?>" class="checkbox-inline">
											<input type="checkbox" name="use_poll[<?php echo element('brd_id', $value); ?>]" id="use_poll_<?php echo element('brd_id', $value); ?>" value="1" <?php echo set_checkbox('use_poll['.element('brd_id', $value).']', '1', (element('use_poll', $value) ? true : false)); ?> class="use_poll" /> PC 사용
										</label>
									</td>
									<td>
										<label for="use_mobile_poll_<?php echo element('brd_id', $value); ?>" class="checkbox-inline">
											<input type="checkbox" name="use_mobile_poll[<?php echo element('brd_id', $value); ?>]" id="use_mobile_poll_<?php echo element('brd_id', $value); ?>" value="1" <?php echo set_checkbox('use_mobile_poll['.element('brd_id', $value).']', '1', (element('use_mobile_poll', $value) ? true : false)); ?> class="use_mobile_poll" /> MOBILE 사용
										</label>
									</td>
									<td class="form-inline">
										<?php
										$config = array(
											'column_name' => 'access_poll_write[' . element('brd_id', $value) . ']',
											'column_level_name' => 'access_poll_write_level[' . element('brd_id', $value) . ']',
											'column_group_name' => 'access_poll_write_group[' . element('brd_id', $value) . ']',
											'column_value' => element('access_poll_write', $value),
											'column_level_value' => element('access_poll_write_level', $value),
											'column_group_value' => element('access_poll_write_group', $value),
											'max_level' => element('config_max_level', element('data', $view)),
											'mgroup' => element('mgroup', element('data', $view)),
											);
										echo get_access_selectbox($config, true);
										?>
									</td>
									<td class="form-inline">
										<?php
										$config = array(
											'column_name' => 'access_poll_attend[' . element('brd_id', $value) . ']',
											'column_level_name' => 'access_poll_attend_level[' . element('brd_id', $value) . ']',
											'column_group_name' => 'access_poll_attend_group[' . element('brd_id', $value) . ']',
											'column_value' => element('access_poll_attend', $value),
											'column_level_value' => element('access_poll_attend_level', $value),
											'column_group_value' => element('access_poll_attend_group', $value),
											'max_level' => element('config_max_level', element('data', $view)),
											'mgroup' => element('mgroup', element('data', $view)),
											);
										echo get_access_selectbox($config, true);
										?>
									</td>
								</tr>
						<?php 
							}
						}
						?>
						</tbody>
					</table>
				</div>
				<div class="btn-group pull-right" role="group" aria-label="...">
					<button type="submit" class="btn btn-success btn-sm">저장하기</button>
				</div>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>
<script type="text/javascript">
$(document).on('click', '#poll_all', function() {
	if ($(this).is(':checked')) {
		$('.use_poll').prop('checked', true);
	} else {
		$('.use_poll').prop('checked', false);
	}
});
$(document).on('click', '#poll_mobile_all', function() {
	if ($(this).is(':checked')) {
		$('.use_mobile_poll').prop('checked', true);
	} else {
		$('.use_mobile_poll').prop('checked', false);
	}
});

</script>
<script type="text/javascript">
//<![CDATA[
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
