<?php $this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css'); ?>

<div class="modal-header">
	<h4 class="modal-title">상품문의 작성</h4>
</div>
<div class="modal-body">
	<div class="form-horizontal">
		<?php
		echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
		echo show_alert_message(element('message', $view), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
		$attributes = array('class' => 'form-horizontal', 'name' => 'fwrite', 'id' => 'fwrite');
		echo form_open(current_full_url(), $attributes);
		?>
			<div class="form-group">
				<label for="cqa_title" class="col-sm-2 control-label">제목</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" name="cqa_title" id="cqa_title" value="<?php echo set_value('cqa_title', element('cqa_title', element('data', $view))); ?>" placeholder="제목을 입력해주세요" />
				</div>
			</div>
			<div class="form-group">
				<label for="cqa_title" class="col-sm-2 control-label">옵션</label>
				<div class="col-sm-10">
					<label for="cqa_secret" class="checkbox-inline">
						<input type="checkbox" name="cqa_secret" id="cqa_secret" value="1" <?php echo set_checkbox('cqa_secret', '1', (element('cqa_secret', element('data', $view)) ? true : false)); ?> /> 비밀글
					</label>
					<?php if ($this->cbconfig->item('cmall_email_user_write_product_qna_reply')) { ?>
						<label for="cqa_receive_email" class="checkbox-inline">
							<input type="checkbox" name="cqa_receive_email" id="cqa_receive_email" value="1" <?php echo set_checkbox('cqa_receive_email', '1', (element('cqa_receive_email', element('data', $view)) ? true : false)); ?> /> 답변등록시 메일로 답변받기
						</label>
					<?php } ?>
					<?php if ($this->cbconfig->item('cmall_sms_user_write_product_qna_reply')) { ?>
						<label for="cqa_receive_sms" class="checkbox-inline">
							<input type="checkbox" name="cqa_receive_sms" id="cqa_receive_sms" value="1" <?php echo set_checkbox('cqa_receive_sms', '1', (element('cqa_receive_sms', element('data', $view)) ? true : false)); ?> /> 답변등록시 문자(SMS)로 답변받기
						</label>
					<?php } ?>
				</div>
			</div>
			<div class="form-group">
				<label for="cqa_content" class="col-sm-2 control-label">내용</label>
				<div class="col-sm-10">
					<?php echo display_dhtml_editor('cqa_content', set_value('cqa_content', element('cqa_content', element('data', $view))), $classname = 'form-control dhtmleditor', $is_dhtml_editor = $this->cbconfig->item('use_cmall_product_qna_dhtml'), $editor_type = $this->cbconfig->item('cmall_product_qna_editor_type')); ?>
				</div>
			</div>
			<div class="form-group col-sm-6">
				<div class="pull-right">
					<a href="javascript:;" class="btn btn-default" onClick="window.close();">취소</a>
					<button type="submit" class="btn btn-primary ">작성완료</button>
				</div>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>
<script type="text/javascript">
//<![CDATA[
$(function() {
	$('#fwrite').validate({
		rules: {
			cqa_title : { required:true},
			cqa_content : {<?php echo ($this->cbconfig->item('use_cmall_product_qna_dhtml')) ? 'required_' . $this->cbconfig->item('cmall_product_qna_editor_type') : 'required'; ?> : true }
		}
	});
});
//]]>
</script>
