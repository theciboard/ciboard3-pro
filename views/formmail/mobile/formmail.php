<?php $this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css'); ?>

<div class="modal-header">
	<h4 class="modal-title"><?php echo element('mem_nickname', element('member', $view)); ?> 님께 이메일 전송</h4>
</div>
<div class="modal-body">
	<div class="mt20 formmail">
		<?php
		echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
		echo show_alert_message(element('message', $view), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
		$attributes = array('class' => 'form-horizontal', 'name' => 'fmail', 'id' => 'fmail');
		echo form_open(current_full_url(), $attributes);
		?>
			<ol>
				<li>
					<span>제목</span>
					<input type="text" class="input" name="title" id="title" value="<?php echo set_value('title'); ?>" placeholder="제목을 입력해주세요" />
				</li>
				<li class="formmailcontent">
					<?php echo display_dhtml_editor('content', set_value('content'), $classname = 'dhtmleditor', $is_dhtml_editor = element('use_dhtml', $view), $editor_type = $this->cbconfig->item('formmail_editor_type')); ?>
				</li>
			</ol>
			<div class="pull-right">
				<button type="submit" class="btn btn-success">보내기</button>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>
<script type="text/javascript">
//<![CDATA[
$(function() {
	$('#fmail').validate({
		rules: {
			title : { required:true},
			content : {<?php echo (element('use_dhtml', $view)) ? 'required_' . $this->cbconfig->item('formmail_editor_type') : 'required'; ?> : true }
		}
	});
});
//]]>
</script>
