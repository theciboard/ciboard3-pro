<?php $this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css'); ?>

<div class="modal-header">
	<h4 class="modal-title">쪽지함</h4>
</div>

<div class="modal-body">
	<ul class="note_menu">
		<li><a href="<?php echo site_url('note/lists/recv'); ?>" class="btn btn-default">받은 쪽지</a></li>
		<li><a href="<?php echo site_url('note/lists/send'); ?>" class="btn btn-default ">보낸 쪽지</a></li>
		<li><a href="<?php echo site_url('note/write'); ?>" class="btn btn-default active">쪽지 쓰기</a></li>
	</ul>

	<?php
	echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
	echo show_alert_message(element('message', $view), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
	$attributes = array('class' => 'mt20', 'name' => 'fnote', 'id' => 'fnote');
	echo form_open_multipart(current_full_url(), $attributes);
	?>
		<ol>
			<li><span>받은 회원</span>
				<input type="text" class="input px300" name="userid" id="userid" value="<?php echo set_value('userid', element('userid', $view)); ?>" placeholder="회원아이디를 입력, 여러명에게 보낼 때는 쉼표로 구분" />
			</li>
			<li><span>제목</span>
				<input type="text" class="input px300" name="title" id="title" value="<?php echo set_value('title'); ?>" placeholder="쪽지 제목을 입력해주세요" />
			</li>
			<li>
				<?php echo display_dhtml_editor('content', set_value('content'), $classname = 'dhtmleditor', $is_dhtml_editor = element('use_dhtml', $view), $editor_type = $this->cbconfig->item('note_editor_type')); ?>
			</li>
			<?php if ($this->cbconfig->item('use_note_file')) { ?>
				<li><span>첨부파일</span>
					<input type="file" class="form-control" name="note_file" />
				</li>
			<?php } ?>
		</ol>
		<div class="pull-right">
			<button type="submit" class="btn btn-success">보내기</button>
		</div>
	<?php echo form_close(); ?>
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
