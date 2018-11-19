<?php $this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css'); ?>

<div class="modal-header">
	<h4 class="modal-title">쪽지함</h4>
</div>

<div class="modal-body">
	<div class="btn-group btn-group-justified" role="group" aria-label="...">
		<div class="btn-group" role="group">
			<a href="<?php echo site_url('note/lists/recv'); ?>" class="btn btn-default">받은 쪽지</a>
		</div>
		<div class="btn-group" role="group">
			<a href="<?php echo site_url('note/lists/send'); ?>" class="btn btn-default">보낸 쪽지</a>
		</div>
		<div class="btn-group" role="group">
			<a href="<?php echo site_url('note/write'); ?>" class="btn btn-default active">쪽지 쓰기</a>
		</div>
	</div>
	<div class="form-horizontal mt20">
		<?php
		echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
		echo show_alert_message(element('message', $view), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
		$attributes = array('class' => 'form-horizontal', 'name' => 'fnote', 'id' => 'fnote');
		echo form_open_multipart(current_full_url(), $attributes);
		?>
			<div class="form-group">
				<label for="userid" class="col-sm-2 control-label">받은 회원</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" name="userid" id="userid" value="<?php echo set_value('userid', element('userid', $view)); ?>" placeholder="회원아이디를 입력해주세요" />
				</div>
			</div>
			<div class="form-group">
				<label for="title" class="col-sm-2 control-label">제목</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" name="title" id="title" value="<?php echo set_value('title'); ?>" placeholder="쪽지 제목을 입력해주세요" />
				</div>
			</div>
			<div class="form-group">
				<label for="content" class="col-sm-2 control-label">쪽지 내용</label>
				<div class="col-sm-10">
					<?php echo display_dhtml_editor('content', set_value('content'), $classname = 'form-control dhtmleditor', $is_dhtml_editor = element('use_dhtml', $view), $editor_type = $this->cbconfig->item('note_editor_type')); ?>
				</div>
			</div>

			<?php if ($this->cbconfig->item('use_note_file')) { ?>
				<div class="form-group">
					<label for="title" class="col-sm-2 control-label">첨부파일</label>
					<div class="col-sm-10">
						<input type="file" class="form-control" name="note_file" />
					</div>
				</div>
			<?php } ?>

			<div class="form-group">
				<div class="col-sm-10 pull-right">
					<button type="submit" class="btn btn-success btn-sm">보내기</button>
				</div>
			</div>
			<div class="success text-center">여러명에게 보낼 때에는 회원아이디를 쉼표(,) 로 구분하여 입력해주세요</div>
		<?php echo form_close(); ?>
	</div>
</div>

<script type="text/javascript">
//<![CDATA[
$(function() {
	$('#fnote').validate({
		rules: {
			userid: {required :true, minlength:3 },
			title: {required :true},
			content : {<?php echo (element('use_dhtml', $view)) ? 'required_' . $this->cbconfig->item('note_editor_type') : 'required'; ?> : true }
		}
	});
});
//]]>
</script>
