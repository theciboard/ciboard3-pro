<div class="box">
	<div class="box-header">
		<ul class="nav nav-tabs">
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir); ?>" onclick="return check_form_changed();">스케쥴러 등록</a></li>
			<li role="presentation" class="active"><a href="<?php echo admin_url($this->pagedir . '/interval'); ?>" onclick="return check_form_changed();">스케쥴 주기명</a></li>
		</ul>
	</div>
	<div class="box-table">
		<div class="alert alert-dismissible alert-success">
			스케쥴 주기명을 관리합니다.<br />
			입력항목 ID 는 알파벳으로 시작하며 알파벳과 숫자만을 이용합니다. 기존 값과 중복되지 않게 입력합니다<br />
		</div>
		<?php
		echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
		echo show_alert_message(element('alert_message', $view), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
		echo show_alert_message(element('warning_message', $view), '<div class="alert alert-auto-close alert-dismissible alert-warning"><button type="button" class="close alertclose" >&times;</button>', '</div>');
		$attributes = array('class' => 'form-horizontal', 'name' => 'fadminwrite', 'id' => 'fadminwrite');
		echo form_open(current_full_url(), $attributes);
		?>
			<input type="hidden" name="s" value="1" />
			<div class="list-group">
				<div class="form-group list-group-item">
					<div class="col-sm-1">정렬</div>
					<div class="col-sm-2">ID</div>
					<div class="col-sm-3">주기명</div>
					<div class="col-sm-3">실행간격</div>
					<div class="col-sm-2">등록된스케쥴러</div>
					<div class="col-sm-1"><button type="button" class="btn btn-outline btn-primary btn-xs btn-add-rows">추가</button></div>
				</div>
				<div id="sortable">
				<?php
				if (element('result', element('data', $view))) {
					foreach (element('result', element('data', $view)) as $key => $result) {
				?>
					<div class="form-group list-group-item">
						<div class="col-sm-1"><div class="fa fa-arrows" style="cursor:pointer;"></div><input type="hidden" name="key[<?php echo $key; ?>]" value="<?php echo $key; ?>" /></div>
						<div class="col-sm-2"><?php echo $key; ?><input type="hidden" name="field_name[]" value="<?php echo $key; ?>" /></div>
						<div class="col-sm-3"><input type="text" class="form-control" name="display_name[]" value="<?php echo html_escape(element('display_name', $result)); ?>" placeholder="주기명" /></div>
						<div class="col-sm-3"><input type="number" class="form-control" name="interval[]" value="<?php echo html_escape(element('interval', $result)); ?>" />초 (<?php echo seconds2human(element('interval', $result))?>)</div>
						<div class="col-sm-2"><?php echo number_format(element('registered_scheduler', $result)); ?> 개</div>
						<div class="col-sm-1"><button type="button" class="btn btn-outline btn-default btn-xs <?php echo element('registered_scheduler', $result) ? 'btn-alert-delete' : 'btn-delete-row'; ?>" >삭제</button></div>
					</div>
				<?php
					}
				}
				?>
				</div>
			</div>
			<div class="btn-group pull-right" role="group" aria-label="...">
				<button type="submit" class="btn btn-success btn-sm">저장하기</button>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>

<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/jquery-ui.min.js"></script>
<script type="text/javascript">
//<![CDATA[
$(document).on('click', '.btn-add-rows', function() {
	$('#sortable').append(' <div class="form-group list-group-item"><div class="col-sm-1"><div class="fa fa-arrows" style="cursor:pointer;"></div><input type="hidden" name="key[]" /></div><div class="col-sm-2"><input type="text" class="form-control field_name" name="field_name[]" placeholder="입력항목 ID" /></div><div class="col-sm-3"><input type="text" class="form-control display_name" name="display_name[]" placeholder="주기명" /></div><div class="col-sm-3"><input type="number" class="form-control interval" name="interval[]" placeholder="실행간격" />초 <span class="help-inline">(초단위로 숫자만 입력하세요)</span></div><div class="col-sm-2"></div><div class="col-sm-1"><button type="button" class="btn btn-outline btn-default btn-xs btn-delete-row" >삭제</button></div></div>');
});
$(document).on('click', '.btn-delete-row', function() {
	$(this).parents('div.list-group-item').remove();
});
$(document).on('click', '.btn-alert-delete', function() {
	alert('등록된 스케쥴러를 삭제한 후에 주기명을 삭제하실 수 있습니다');
});
$(document).on('change', '.field_type', function() {
	if ($(this).val() === 'radio' || $(this).val() === 'select' || $(this).val() === 'checkbox') {
		$(this).siblings('.options').show();
	} else {
		$(this).siblings('.options').hide();
	}
});

$(function () {
	$('#sortable').sortable({
		handle:'.fa-arrows'
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
