<div class="box">
	<div class="box-header">
		<ul class="nav nav-tabs">
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir); ?>" onclick="return check_form_changed();">기본정보</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/access'); ?>" onclick="return check_form_changed();">접근기능</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/point'); ?>" onclick="return check_form_changed();">포인트기능</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/general'); ?>" onclick="return check_form_changed();">일반기능 / 에디터</a></li>
			<li role="presentation" class="active"><a href="<?php echo admin_url($this->pagedir . '/note'); ?>" onclick="return check_form_changed();">쪽지기능</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/notification'); ?>" onclick="return check_form_changed();">알림기능</a></li>
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
				<label class="col-sm-2 control-label">쪽지기능</label>
				<div class="col-sm-10">
					<label for="use_note" class="checkbox-inline">
						<input type="checkbox" name="use_note" id="use_note" value="1" <?php echo set_checkbox('use_note', '1', (element('use_note', element('data', $view)) ? true : false)); ?> /> 사용합니다
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">한페이지 쪽지수</label>
				<div class="col-sm-10">
					PC <input type="number" class="form-control" name="note_list_page" id="note_list_page" value="<?php echo set_value('note_list_page', (int) element('note_list_page', element('data', $view))); ?>" />,
					모바일 <input type="number" class="form-control" name="note_mobile_list_page" id="note_mobile_list_page" value="<?php echo set_value('note_mobile_list_page', (int) element('note_mobile_list_page', element('data', $view))); ?>" />
					한 페이지에 보이는 쪽지수를 입력합니다
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">쪽지 DHTML 기능</label>
				<div class="col-sm-10">
					<label for="use_note_dhtml" class="checkbox-inline">
						<input type="checkbox" name="use_note_dhtml" id="use_note_dhtml" value="1" <?php echo set_checkbox('use_note_dhtml', '1', (element('use_note_dhtml', element('data', $view)) ? true : false)); ?> /> PC - 사용합니다
					</label>
					<label for="use_note_mobile_dhtml" class="checkbox-inline">
						<input type="checkbox" name="use_note_mobile_dhtml" id="use_note_mobile_dhtml" value="1" <?php echo set_checkbox('use_note_mobile_dhtml', '1', (element('use_note_mobile_dhtml', element('data', $view)) ? true : false)); ?> /> 모바일 - 사용합니다
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">쪽지 에디터 종류</label>
				<div class="col-sm-10 form-inline">
					<select class="form-control" name="note_editor_type" id="note_editor_type">
						<?php echo element('note_editor_type_option', element('data', $view)); ?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">쪽지발송시 차감포인트</label>
				<div class="col-sm-10">
					<input type="number" class="form-control" name="point_note" id="point_note" value="<?php echo set_value('point_note', (int) element('point_note', element('data', $view))); ?>" /> 양수로 입력해주세요, 해당 포인트만큼 차감됩니다
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">쪽지첨부파일기능</label>
				<div class="col-sm-10">
					<label for="use_note_file" class="checkbox-inline">
						<input type="checkbox" name="use_note_file" id="use_note_file" value="1" <?php echo set_checkbox('use_note_file', '1', (element('use_note_file', element('data', $view)) ? true : false)); ?> /> 사용합니다
					</label>
				<div class="help-inline">쪽지를 통해 첨부파일을 주고받을 수 있습니다</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">첨부파일 업로드시 차감포인트</label>
				<div class="col-sm-10">
					<input type="number" class="form-control" name="point_note_file" id="point_note_file" value="<?php echo set_value('point_note_file', (int) element('point_note_file', element('data', $view))); ?>" /> 양수로 입력해주세요, 쪽지 발송시 차감포인트와는 별도로 추가로 해당 포인트만큼 차감됩니다
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
			point_note: {required :true, number:true},
			point_note_file: {required :true, number:true}
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
