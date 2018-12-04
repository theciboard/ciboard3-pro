<div class="box">
	<div class="box-header">
		<ul class="nav nav-tabs">
			<li role="presentation" class="active"><a href="<?php echo admin_url($this->pagedir); ?>" onclick="return check_form_changed();">스케쥴러 등록</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/interval'); ?>" onclick="return check_form_changed();">스케쥴 주기명</a></li>
		</ul>
	</div>
	<div class="box-table">
		<div class="alert alert-dismissible alert-success">
			스케쥴을 등록하고 관리합니다.<br />
			통상적인 cron이 스케줄에 맞춰 지정된 시간에 정확하게 수행되는 것과 달리, 이 스케쥴러는 웹요청이 들어올 경우에만 실행됩니다.<br />
			즉 사용자가 사이트를 방문할 경우에, 수행되어야 할 스케쥴러를 확인하고 있으면 실행하게 됩니다.<br />
			스케쥴러로 등록하고 싶은 라이브러리를 작성하여 application/libraries/Scheduler/ 디렉토리에 추가합니다. <br />
			매번 사이트가 로딩될 때마다 실행해야할 스케쥴러가 있는지 확인하는 것은 비효율적이고, 사이트에 부하를 일으킬 수 있으므로,<br />
			5분에 1번씩만 실행해야할 스케쥴러가 있는지 확인하며, 있는 경우에 실행하게 됩니다.<br />
			실행해야할 스케쥴러가 2개 이상일 경우에는 1개만 실행되며, 나머지는 5분 뒤에 다시 스케쥴러가 있는지 확인할 때에 실행됩니다.<br />
		</div>
		<div class="alert alert-dismissible <?php echo (config_item('enable_scheduler') === false) ? 'alert-danger' : 'alert-success'; ?>">
			스케쥴러를 사용하기 위해서는 $config['enable_scheduler'] 의 값이 true 로 설정되어 있어야 합니다. <br />
			현재 $config['enable_scheduler'] 의 값은 <strong><?php echo config_item('enable_scheduler') ? "true" : "false"; ?></strong>로 설정되어 있습니다 <br />
			<?php if (config_item('enable_scheduler') === false) { ?>
					스케쥴러를 사용하기 위해서는 application/config/cb_config.php 파일을 열으셔서, $config['enable_scheduler'] 의 값을 true 로 변경해주세요.
			<?php } ?>
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
					<div class="col-sm-3">라이브러리명</div>
					<div class="col-sm-2">최근실행시간</div>
					<div class="col-sm-2">다음실행예정시간</div>
					<div class="col-sm-2">실행주기</div>
					<div class="col-sm-1">지금실행</div>
					<div class="col-sm-1"><button type="button" class="btn btn-outline btn-primary btn-xs btn-add-rows">추가</button></div>
				</div>
				<div id="sortable">
				<?php
				if (element('result', element('data', $view))) {
					foreach (element('result', element('data', $view)) as $key => $result) {
				?>
					<div class="form-group list-group-item">
						<div class="col-sm-1"><div class="fa fa-arrows" style="cursor:pointer;"></div><input type="hidden" name="key[<?php echo $key; ?>]" value="<?php echo $key; ?>" /></div>
						<div class="col-sm-3">
							<?php echo $key; ?>
							<input type="hidden" name="library_name[]" value="<?php echo $key; ?>" />
							<?php
							if(element('class_exists', $result)) {
								echo '<span class="label label-primary">Library 존재</span>';
							} else {
								echo '<span class="label label-danger">Library 존재않음</span>';
							}
							?>
						</div>
						<div class="col-sm-2"><?php echo element('lastexecutetime', $result); ?></div>
						<div class="col-sm-2"><?php echo element('nextexecutetime', $result); ?></div>
						<div class="col-sm-2">
							<div class="form-group form-group-sm">
								<select name="interval_field_name[<?php echo $key; ?>]" class="form-control interval_field_name">
									<option value="">=선택=</option>
									<?php
									if(element('scheduler_interval', element('data', $view))) {
										foreach(element('scheduler_interval', element('data', $view)) as $skey => $svalue) {
									?>
										<option value="<?php echo element('field_name', $svalue); ?>" <?php if(element('field_name', $svalue) === element('interval_field_name', $result)) echo 'selected="selected"';?>><?php echo html_escape(element('display_name', $svalue)); ?> (<?php echo number_format(element('interval', $svalue)); ?>초) </option>
									<?php }} ?>
								</select>
							</div>
						</div>
						<div class="col-sm-1 ajax_loading_<?php echo $key;?>">
							<?php
							if(element('class_exists', $result)) {
							?>
								<button type="button" class="btn btn-xs btn-warning execute_scheduler" data-libraryname="<?php echo $key; ?>">실행하기</button>
							<?php
							}
							?>
						</div>
						<div class="col-sm-1"><button type="button" class="btn btn-outline btn-default btn-xs btn-delete-row">삭제</button></div>
					</div>
				<?php
					}
				}
				?>
				</div>
			</div>
			<div class="pull-left">* 등록하고 싶은 라이브러리명을 대소문자를 구분하여 정확히 입력해주세요. 등록한 라이브러리는 application/libraries/cron/ 에 존재하여야 합니다</div>
			<div class="btn-group pull-right" role="group" aria-label="...">
				<button type="submit" class="btn btn-success btn-sm">저장하기</button>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/jquery-ui.min.js"></script>
<script type="text/javascript">
//<![CDATA[
$(document).on('click', '.execute_scheduler', function() {
	$('.ajax_loading_' + $(this).data('libraryname')).html('<div style="width:60px;height:60px;background: url(' + cb_url + '/assets/images/ajax-loader.gif) no-repeat 0 0;"></div>');
	$.ajax({
		url : cb_admin_url + '/config/scheduler/execute/' + $(this).data('libraryname'),
		async: true,
		method : 'get',
		cache: false,
		dataType: 'json',
		success: function(data) {
			if (data.success !== 'ok') {
				alert(data.message);
				$('.ajax_loading_' + $(this).data('libraryname')).html(data.message);
				return false;
			} else {
				window.location.href= cb_admin_url + '/config/scheduler';
			}
		}
	});
});
$(document).on('click', '.btn-add-rows', function() {
	$('#sortable').append(
		'<div class="form-group list-group-item"><div class="col-sm-1"><div class="fa fa-arrows" style="cursor:pointer;"></div><input type="hidden" name="key[]" /></div><div class="col-sm-3"><input type="text" class="form-control library_name" name="library_name[]" placeholder="라이브러리명" /></div><div class="col-sm-2"></div><div class="col-sm-2"></div><div class="col-sm-2"><div class="form-group form-group-sm"><select name="interval_field_name[]" class="form-control interval_field_name"><option value="">=선택=</option>' +
	<?php
	if(element('scheduler_interval', element('data', $view))) {
		foreach(element('scheduler_interval', element('data', $view)) as $skey => $svalue) {
	?>
		'<option value="<?php echo element('field_name', $svalue); ?>"><?php echo html_escape(element('display_name', $svalue)); ?> (<?php echo number_format(element('interval', $svalue)); ?>초) </option>' +
	<?php }} ?>
		'</select></div></div><div class="col-sm-1"></div><div class="col-sm-1"><button type="button" class="btn btn-outline btn-default btn-xs btn-delete-row" >삭제</button></div></div>');
});
$(document).on('click', '.btn-delete-row', function() {
	$(this).parents('div.list-group-item').remove();
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
