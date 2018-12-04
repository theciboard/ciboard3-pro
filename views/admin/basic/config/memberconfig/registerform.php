<div class="box">
	<div class="box-header">
		<ul class="nav nav-tabs">
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir); ?>" onclick="return check_form_changed();">기본정보</a></li>
			<li role="presentation" class="active"><a href="<?php echo admin_url($this->pagedir . '/registerform'); ?>" onclick="return check_form_changed();">가입폼관리</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/membermodify'); ?>" onclick="return check_form_changed();">정보수정시</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/login'); ?>" onclick="return check_form_changed();">로그인</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/alarm'); ?>" onclick="return check_form_changed();">알림 설정</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/sociallogin'); ?>" onclick="return check_form_changed();">소셜로그인</a></li>
		</ul>
	</div>
	<div class="box-table">
		<div class="alert alert-dismissible alert-success">
			회원가입시 나타나는 항목입니다, 정렬순서대로 항목이 나타납니다,<br />
			"사용" 에 체크하시면 회원가입 및 개인정보 수정 페이지에 나타납니다. <br />
			"공개" 에 체크하시면 해당 항목이 개인 프로필 페이지에 나타납니다.<br />
			입력항목 ID 는 알파벳으로 시작하며 알파벳과 숫자만을 이용합니다. 기존 값과 중복되지 않게 입력합니다<br />
			radio, select, checkbox 를 선택시 "선택옵션" 항목이 나타납니다. 선택옵션 항목은 엔터로 구분하여 입력합니다
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
					<div class="col-sm-2">이름</div>
					<div class="col-sm-3">형식</div>
					<div class="col-sm-1">사용</div>
					<div class="col-sm-1">공개</div>
					<div class="col-sm-1">필수입력</div>
					<div class="col-sm-1"><button type="button" class="btn btn-outline btn-primary btn-xs btn-add-rows">추가</button></div>
				</div>
				<div id="sortable">
				<?php
				if (element('result', element('data', $view))) {
					foreach (element('result', element('data', $view)) as $key => $result) {
						if ( array_key_exists ($key, element('default_form', element('data', $view)))) {
				?>
					<div class="form-group list-group-item">
						<div class="col-sm-1"><div class="fa fa-arrows" style="cursor:pointer;"></div><input type="hidden" name="key[<?php echo $key; ?>]" value="<?php echo $key; ?>" /><input type="hidden" name="basic[<?php echo $key; ?>]" value="<?php echo $key; ?>" /></div>
						<div class="col-sm-2"><?php echo $key; ?>
							<input type="hidden" name="field_name[]" value="<?php echo $key; ?>" />
							<input type="hidden" name="display_name[]" value="<?php echo html_escape(element('display_name', $result)); ?>" />
						</div>
						<div class="col-sm-2"><?php echo html_escape(element('display_name', $result)); ?></div>
						<div class="col-sm-3">-</div>
						<div class="col-sm-1"><input type="checkbox" name="use[<?php echo $key; ?>]" value="1" <?php echo element('use', $result) ? ' checked="checked" ' : ''; ?> <?php echo element('disable_use', element($key, element('default_form', element('data', $view)))) ? ' disabled="disabled" ' : ''; ?> /></div>
						<div class="col-sm-1"><input type="checkbox" name="open[<?php echo $key; ?>]" value="1" <?php echo element('open', $result) ? ' checked="checked" ' : ''; ?> <?php echo element('disable_open', element($key, element('default_form', element('data', $view)))) ? ' disabled="disabled" ' : ''; ?> /></div>
						<div class="col-sm-1"><input type="checkbox" name="required[<?php echo $key; ?>]" value="1" <?php echo element('required', $result) ? ' checked="checked" ' : ''; ?> <?php echo element('disable_required', element($key, element('default_form', element('data', $view)))) ? ' disabled="disabled" ' : ''; ?> /></div>
						<div class="col-sm-1"></div>
					</div>
				<?php
				} else {
				?>
					<div class="form-group list-group-item">
						<div class="col-sm-1"><div class="fa fa-arrows" style="cursor:pointer;"></div><input type="hidden" name="key[<?php echo $key; ?>]" value="<?php echo $key; ?>" /></div>
						<div class="col-sm-2"><?php echo $key; ?>
						<input type="hidden" name="field_name[]" value="<?php echo $key; ?>" /></div>
						<div class="col-sm-2"><input type="text" class="form-control" name="display_name[]" value="<?php echo html_escape(element('display_name', $result)); ?>" placeholder="입력항목제목" /></div>
						<div class="col-sm-3">
							<select name="field_type[<?php echo $key; ?>]" class="form-control field_type">
								<option value="text" <?php echo element('field_type', $result) === 'text' ? ' selected="selected" ' : ''; ?>>한줄 입력 형식(text)</option>
								<option value="url" <?php echo element('field_type', $result) === 'url' ? ' selected="selected" ' : ''; ?>>URL 형식</option>
								<option value="email" <?php echo element('field_type', $result) === 'email' ? ' selected="selected" ' : ''; ?>>이메일 형식(email)</option>
								<option value="phone" <?php echo element('field_type', $result) === 'phone' ? ' selected="selected" ' : ''; ?>>전화번호 형식(phone)</option>
								<option value="textarea" <?php echo element('field_type', $result) === 'textarea' ? ' selected="selected" ' : ''; ?>>여러 줄 입력칸(textarea)</option>
								<option value="radio" <?php echo element('field_type', $result) === 'radio' ? ' selected="selected" ' : ''; ?>>단일 선택(radio)</option>
								<option value="select" <?php echo element('field_type', $result) === 'select' ? ' selected="selected" ' : ''; ?>>단일 선택(select)</option>
								<option value="checkbox" <?php echo element('field_type', $result) === 'checkbox' ? ' selected="selected" ' : ''; ?>>다중 선택(checkbox)</option>
								<option value="date" <?php echo element('field_type', $result) === 'date' ? ' selected="selected" ' : ''; ?>>일자(연월일)</option>
							</select>
							<br /><textarea name="options[<?php echo $key; ?>]" class="form-control options" style="margin-top:5px;display:<?php echo (element('field_type', $result) === 'radio' OR element('field_type', $result) === 'select' OR element('field_type', $result) === 'checkbox') ? 'block' : 'none'; ?>;" placeholder="선택 옵션 (엔터로 구분하여 입력)"><?php echo html_escape(element('options', $result)); ?></textarea>
						</div>
						<div class="col-sm-1"><input type="checkbox" name="use[<?php echo $key; ?>]" value="1" <?php echo element('use', $result) ? ' checked="checked" ' : ''; ?> /></div>
						<div class="col-sm-1"><input type="checkbox" name="open[<?php echo $key; ?>]" value="1" <?php echo element('open', $result) ? ' checked="checked" ' : ''; ?> /></div>
						<div class="col-sm-1"><input type="checkbox" name="required[<?php echo $key; ?>]" value="1" <?php echo element('required', $result) ? ' checked="checked" ' : ''; ?> /></div>
						<div class="col-sm-1"><button type="button" class="btn btn-outline btn-default btn-xs btn-delete-row" >삭제</button></div>
					</div>
				<?php
						}
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
	$('#sortable').append(' <div class="form-group list-group-item"><div class="col-sm-1"><div class="fa fa-arrows" style="cursor:pointer;"></div><input type="hidden" name="key[]" /></div><div class="col-sm-2"><input type="text" class="form-control field_name" name="field_name[]" placeholder="입력항목ID" /></div><div class="col-sm-2"><input type="text" class="form-control display_name" name="display_name[]" placeholder="입력항목제목" /></div><div class="col-sm-3"><select name="field_type[]" class="form-control field_type"><option value="text">한줄 입력 형식(text)</option><option value="url">URL 형식</option><option value="email">이메일 형식(email)</option><option value="phone">전화번호 형식(phone)</option><option value="textarea">여러 줄 입력칸(textarea)</option><option value="radio">단일 선택(radio)</option><option value="select">단일 선택(select)</option><option value="checkbox">다중 선택(checkbox)</option><option value="date">일자(연월일)</option></select><br /><textarea name="options[]" class="form-control options" style="margin-top:5px;display:none;" placeholder="선택 옵션 (엔터로 구분하여 입력)"></textarea></div><div class="col-sm-1"><input type="checkbox" name="use[]" value="1" checked="checked" /></div><div class="col-sm-1"><input type="checkbox" name="open[]" value="1" /></div><div class="col-sm-1"><input type="checkbox" name="required[]" value="1" /></div><div class="col-sm-1"><button type="button" class="btn btn-outline btn-default btn-xs btn-delete-row" >삭제</button></div></div>');
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
