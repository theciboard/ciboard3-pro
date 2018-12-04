<div class="box">
	<div class="box-table">
		<?php
		echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
		$attributes = array('class' => 'form-horizontal', 'name' => 'fadminwrite', 'id' => 'fadminwrite');
		echo form_open(current_full_url(), $attributes);
		?>
			<input type="hidden" name="<?php echo element('primary_key', $view); ?>"	value="<?php echo element(element('primary_key', $view), element('data', $view)); ?>" />
			<div class="form-group">
				<label class="col-sm-2 control-label">질문제목</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" name="cqa_title" value="<?php echo set_value('cqa_title', element('cqa_title', element('data', $view))); ?>" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">비밀글여부</label>
				<div class="col-sm-10">
					<label for="cqa_secret" class="checkbox-inline">
					<input type="checkbox" name="cqa_secret" id="cqa_secret" value="1" <?php echo set_checkbox('cqa_secret', '1', (element('cqa_secret', element('data', $view)) ? true : false)); ?> /> 비밀글입니다
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">질문내용</label>
				<div class="col-sm-10">
					<?php echo display_dhtml_editor('cqa_content', set_value('cqa_content', element('cqa_content', element('data', $view))), $classname = 'form-control dhtmleditor', $is_dhtml_editor = $this->cbconfig->item('use_cmall_product_qna_dhtml'), $editor_type = $this->cbconfig->item('cmall_product_qna_editor_type')); ?>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">답변내용</label>
				<div class="col-sm-10">
					<?php echo display_dhtml_editor('cqa_reply_content', set_value('cqa_reply_content', element('cqa_reply_content', element('data', $view))), $classname = 'form-control dhtmleditor', $is_dhtml_editor = $this->cbconfig->item('use_cmall_product_qna_dhtml'), $editor_type = $this->cbconfig->item('cmall_product_qna_editor_type')); ?>
				</div>
			</div>
			<div class="btn-group pull-right" role="group" aria-label="...">
				<button type="button" class="btn btn-default btn-sm btn-history-back" >취소하기</button>
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
			cqa_title: 'required',
			cqa_content : {<?php echo ($this->cbconfig->item('use_cmall_product_qna_dhtml')) ? 'required_' . $this->cbconfig->item('cmall_product_qna_editor_type') : 'required'; ?> : true },
			cqa_reply_content : {<?php echo ($this->cbconfig->item('use_cmall_product_qna_dhtml')) ? 'required_' . $this->cbconfig->item('cmall_product_qna_editor_type') : 'required'; ?> : true }
		}
	});
});
//]]>
</script>
