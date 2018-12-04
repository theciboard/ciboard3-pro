<div class="box">
	<div class="box-header">
		<h4 class="pb10 pull-left"><?php echo html_escape($this->board->item_id('brd_name', element('brd_id', element('data', $view)))); ?> <a href="<?php echo goto_url(board_url(html_escape($this->board->item_id('brd_key', element('brd_id', element('data', $view)))))); ?>" class="btn-xs" target="_blank"><span class="glyphicon glyphicon-new-window"></span></a></h4>
		<?php if (element('boardlist', $view)) { ?>
		<div class="pull-right">
			<select name="brd_id" class="form-control" onChange="location.href='<?php echo admin_url($this->pagedir . '/write_extravars'); ?>/' + this.value;">
				<?php foreach (element('boardlist', $view) as $key => $value) { ?>
					<option value="<?php echo element('brd_id', $value); ?>" <?php echo set_select('brd_id', element('brd_id', $value), (element('brd_id', element('data', $view)) === element('brd_id', $value) ? true : false)); ?>><?php echo html_escape(element('brd_name', $value)); ?></option>
				<?php } ?>
			</select>
		</div>
		<?php } ?>
		<div class="clearfix"></div>
		<ul class="nav nav-tabs">
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/write/' . element('brd_id', element('data', $view))); ?>" onclick="return check_form_changed();">기본정보</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/write_list/' . element('brd_id', element('data', $view))); ?>" onclick="return check_form_changed();">목록페이지</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/write_post/' . element('brd_id', element('data', $view))); ?>" onclick="return check_form_changed();">게시물열람</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/write_write/' . element('brd_id', element('data', $view))); ?>" onclick="return check_form_changed();">게시물작성</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/write_category/' . element('brd_id', element('data', $view))); ?>" onclick="return check_form_changed();">카테고리</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/write_comment/' . element('brd_id', element('data', $view))); ?>" onclick="return check_form_changed();">댓글기능</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/write_general/' . element('brd_id', element('data', $view))); ?>" onclick="return check_form_changed();">일반기능</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/write_point/' . element('brd_id', element('data', $view))); ?>" onclick="return check_form_changed();">포인트기능</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/write_alarm/' . element('brd_id', element('data', $view))); ?>" onclick="return check_form_changed();">메일/쪽지/문자</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/write_rss/' . element('brd_id', element('data', $view))); ?>" onclick="return check_form_changed();">RSS/사이트맵 설정</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/write_access/' . element('brd_id', element('data', $view))); ?>" onclick="return check_form_changed();">권한관리</a></li>
			<li role="presentation" class="active"><a href="<?php echo admin_url($this->pagedir . '/write_extravars/' . element('brd_id', element('data', $view))); ?>" onclick="return check_form_changed();">사용자정의</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/write_admin/' . element('brd_id', element('data', $view))); ?>" onclick="return check_form_changed();">게시판관리자</a></li>
		</ul>
	</div>
	<div class="box-table">
		<?php
		echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
		echo show_alert_message(element('alert_message', $view), '<div class="alert alert-auto-close alert-dismissible alert-warning"><button type="button" class="close alertclose" >&times;</button>', '</div>');
		echo show_alert_message(element('warning_message', $view), '<div class="alert alert-auto-close alert-dismissible alert-warning"><button type="button" class="close alertclose" >&times;</button>', '</div>');
		echo show_alert_message($this->session->flashdata('message'), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
		$attributes = array('class' => 'form-horizontal', 'name' => 'fadminwrite', 'id' => 'fadminwrite');
		echo form_open(current_full_url(), $attributes);
		?>
			<input type="hidden" name="s" value="1" />
			<div class="alert alert-dismissible alert-warning">
				게시글 작성시 추가하고 싶은 항목을 설정합니다,<br />
				입력항목 ID 는 알파벳으로 시작하며 알파벳과 숫자만을 이용합니다. 기존 값과 중복되지 않게 입력합니다<br />
				radio, select, checkbox 를 선택시 "선택옵션" 항목이 나타납니다. 선택옵션 항목은 엔터로 구분하여 입력합니다
			</div>
			<div class="list-group">
				<div class="form-group list-group-item">
					<div class="col-sm-2">정렬</div>
					<div class="col-sm-2">ID</div>
					<div class="col-sm-2">이름</div>
					<div class="col-sm-2">형식</div>
					<div class="col-sm-1">사용</div>
					<div class="col-sm-1">필수입력</div>
					<div class="col-sm-2"><button type="button" class="btn btn-outline btn-primary btn-xs btn-add-rows">추가</button></div>
				</div>
				<div id="sortable">
					<?php
					if (element('result', element('data', $view))) {
						foreach (element('result', element('data', $view)) as $key => $result) {
					?>
						<div class="form-group list-group-item">
							<div class="col-sm-2">
								<div class="fa fa-arrows" style="cursor:pointer;"></div><input type="hidden" name="key[<?php echo $key; ?>]" value="<?php echo $key; ?>" />
							</div>
							<div class="col-sm-2">
								<?php echo $key; ?>
								<input type="hidden" name="field_name[]" value="<?php echo $key; ?>" />
							</div>
							<div class="col-sm-2">
								<input type="text" class="form-control" name="display_name[]" value="<?php echo html_escape(element('display_name', $result)); ?>" placeholder="입력항목제목" />
							</div>
							<div class="col-sm-2">
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
							<div class="col-sm-1"><input type="checkbox" name="required[<?php echo $key; ?>]" value="1" <?php echo element('required', $result) ? ' checked="checked" ' : ''; ?> /></div>
							<div class="col-sm-2"><button type="button" class="btn btn-outline btn-default btn-xs btn-delete-row" >삭제</button></div>
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
	$('#sortable').append(' <div class="form-group list-group-item"><div class="col-sm-2"><div class="fa fa-arrows" style="cursor:pointer;"></div><input type="hidden" name="key[]" /></div><div class="col-sm-2"><input type="text" class="form-control field_name" name="field_name[]" placeholder="입력항목ID" /></div><div class="col-sm-2"><input type="text" class="form-control display_name" name="display_name[]" placeholder="입력항목제목" /></div><div class="col-sm-2"><select name="field_type[]" class="form-control field_type"><option value="text">한줄 입력 형식(text)</option><option value="url">URL 형식</option><option value="email">이메일 형식(email)</option><option value="phone">전화번호 형식(phone)</option><option value="textarea">여러 줄 입력칸(textarea)</option><option value="radio">단일 선택(radio)</option><option value="select">단일 선택(select)</option><option value="checkbox">다중 선택(checkbox)</option><option value="date">일자(연월일)</option></select><br /><textarea name="options[]" class="form-control options" style="margin-top:5px;display:none;" placeholder="선택 옵션 (엔터로 구분하여 입력)"></textarea></div><div class="col-sm-1"><input type="checkbox" name="use[]" value="1" checked="checked" /></div><div class="col-sm-1"><input type="checkbox" name="required[]" value="1" /></div><div class="col-sm-2"><button type="button" class="btn btn-outline btn-default btn-xs btn-delete-row" >삭제</button></div></div>');
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
})

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
