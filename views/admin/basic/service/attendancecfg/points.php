<div class="box">
	<div class="box-header">
		<ul class="nav nav-tabs">
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir); ?>" onclick="return check_form_changed();">일반기능</a></li>
			<li role="presentation" class="active"><a href="<?php echo admin_url($this->pagedir . '/points'); ?>" onclick="return check_form_changed();">시간/포인트설정</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/cleanlog'); ?>" onclick="return check_form_changed();">오래된 로그삭제</a></li>
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
				<label class="col-sm-2 control-label">출석 가능 시간</label>
				<div class="col-sm-10 form-inline">
					<input type="text" class="form-control" name="attendance_start_time" id="attendance_start_time" value="<?php echo set_value('attendance_start_time', element('attendance_start_time', element('data', $view))); ?>" />
					~
					<input type="text" class="form-control" name="attendance_end_time" id="attendance_end_time" value="<?php echo set_value('attendance_end_time', element('attendance_end_time', element('data', $view))); ?>" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">출석 포인트</label>
				<div class="col-sm-10">
					<input type="number" class="form-control" name="attendance_point" id="attendance_point" value="<?php echo set_value('attendance_point', (int) element('attendance_point', element('data', $view))); ?>" /> 점
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">1등 포인트</label>
				<div class="col-sm-10">
					출석포인트 + <input type="number" class="form-control" name="attendance_point_1" id="attendance_point_1" value="<?php echo set_value('attendance_point_1', (int) element('attendance_point_1', element('data', $view))); ?>" /> 점
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">2등 포인트</label>
				<div class="col-sm-10">
					출석포인트 + <input type="number" class="form-control" name="attendance_point_2" id="attendance_point_2" value="<?php echo set_value('attendance_point_2', (int) element('attendance_point_2', element('data', $view))); ?>" /> 점
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">3등 포인트</label>
				<div class="col-sm-10">
					출석포인트 + <input type="number" class="form-control" name="attendance_point_3" id="attendance_point_3" value="<?php echo set_value('attendance_point_3', (int) element('attendance_point_3', element('data', $view))); ?>" /> 점
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">4등 포인트</label>
				<div class="col-sm-10">
					출석포인트 + <input type="number" class="form-control" name="attendance_point_4" id="attendance_point_4" value="<?php echo set_value('attendance_point_4', (int) element('attendance_point_4', element('data', $view))); ?>" /> 점
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">5등 포인트</label>
				<div class="col-sm-10">
					출석포인트 + <input type="number" class="form-control" name="attendance_point_5" id="attendance_point_5" value="<?php echo set_value('attendance_point_5', (int) element('attendance_point_5', element('data', $view))); ?>" /> 점
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">6등 포인트</label>
				<div class="col-sm-10">
					출석포인트 + <input type="number" class="form-control" name="attendance_point_6" id="attendance_point_6" value="<?php echo set_value('attendance_point_6', (int) element('attendance_point_6', element('data', $view))); ?>" /> 점
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">7등 포인트</label>
				<div class="col-sm-10">
					출석포인트 + <input type="number" class="form-control" name="attendance_point_7" id="attendance_point_7" value="<?php echo set_value('attendance_point_7', (int) element('attendance_point_7', element('data', $view))); ?>" /> 점
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">8등 포인트</label>
				<div class="col-sm-10">
					출석포인트 + <input type="number" class="form-control" name="attendance_point_8" id="attendance_point_8" value="<?php echo set_value('attendance_point_8', (int) element('attendance_point_8', element('data', $view))); ?>" /> 점
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">9등 포인트</label>
				<div class="col-sm-10">
					출석포인트 + <input type="number" class="form-control" name="attendance_point_9" id="attendance_point_9" value="<?php echo set_value('attendance_point_9', (int) element('attendance_point_9', element('data', $view))); ?>" /> 점
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">10등 포인트</label>
				<div class="col-sm-10">
					출석포인트 + <input type="number" class="form-control" name="attendance_point_10" id="attendance_point_10" value="<?php echo set_value('attendance_point_10', (int) element('attendance_point_10', element('data', $view))); ?>" /> 점
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">개근 포인트</label>
				<div class="col-sm-10">
					출석포인트 + <input type="number" class="form-control" name="attendance_point_regular" id="attendance_point_regular" value="<?php echo set_value('attendance_point_regular', (int) element('attendance_point_regular', element('data', $view))); ?>" /> 점,
					<input type="number" class="form-control" name="attendance_point_regular_days" id="attendance_point_regular_days" value="<?php echo set_value('attendance_point_regular_days', (int) element('attendance_point_regular_days', element('data', $view))); ?>" /> 일 마다 지급
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">기본인사말</label>
				<div class="col-sm-10">
					<textarea class="form-control" rows="5" name="attendance_default_memo"><?php echo set_value('attendance_default_memo', element('attendance_default_memo', element('data', $view))); ?></textarea>
					<span class="help-block">엔터로 구분하여 입력해주세요. <br />
						출석체크시 랜덤으로 기본인사말이 보이게 됩니다
					</span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">단어필터링</label>
				<div class="col-sm-10">
					<textarea class="form-control" rows="5" name="attendance_spam_keyword"><?php echo set_value('attendance_spam_keyword', element('attendance_spam_keyword', element('data', $view))); ?></textarea>
					<span class="help-block">인사말에 필터링하고 싶은 단어를 쉼표로 구분해 입력해주세요.</span>
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
$(document).on('change', "select.select-date-style", function() {
	if ($(this).val() === 'user') {
		$('#' + $(this).attr('data-display-target')).css('display', 'inline');
	} else {
		$('#' + $(this).attr('data-display-target')).css('display', 'none');
	}
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
