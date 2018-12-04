<div class="box">
	<div class="box-table">
		<?php
		echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
		$attributes = array('class' => 'form-horizontal', 'name' => 'fadminwrite', 'id' => 'fadminwrite');
		echo form_open(current_full_url(), $attributes);
		?>
			<input type="hidden" name="<?php echo element('primary_key', $view); ?>"	value="<?php echo element(element('primary_key', $view), element('data', $view)); ?>" />
			<input type="hidden" name="fgr_id"	value="<?php echo element('fgr_id', element('faqgroup', element('data', $view))); ?>" />
			<div class="form-group">
				<label class="col-sm-2 control-label">그룹</label>
				<div class="col-sm-10">
					<?php echo html_escape(element('fgr_title', element('faqgroup', element('data', $view)))); ?>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">정렬순서</label>
				<div class="col-sm-10">
					<input type="number" class="form-control" name="faq_order" value="<?php echo set_value('faq_order', element('faq_order', element('data', $view))); ?>" />
					<div class="help-inline">정렬순서가 낮은 FAQ가 먼저 출력됩니다</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">질문</label>
				<div class="col-sm-10">
					<?php echo display_dhtml_editor('faq_title', set_value('faq_title', element('faq_title', element('data', $view))), $classname = 'form-control dhtmleditor', $is_dhtml_editor = $this->cbconfig->item('use_faq_dhtml'), $editor_type = $this->cbconfig->item('faq_editor_type')); ?>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">답변</label>
				<div class="col-sm-10">
					<?php echo display_dhtml_editor('faq_content', set_value('faq_content', element('faq_content', element('data', $view))), $classname = 'form-control dhtmleditor', $is_dhtml_editor = $this->cbconfig->item('use_faq_dhtml'), $editor_type = $this->cbconfig->item('faq_editor_type')); ?>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">답변(모바일용)</label>
				<div class="col-sm-10">
					<?php echo display_dhtml_editor('faq_mobile_content', set_value('faq_mobile_content', element('faq_mobile_content', element('data', $view))), $classname = 'form-control dhtmleditor', $is_dhtml_editor = $this->cbconfig->item('use_faq_dhtml'), $editor_type = $this->cbconfig->item('faq_editor_type')); ?>
					모바일 내용이 일반웹페이지 내용과 다를 경우에 입력합니다. 같은 경우는 입력하지 않으셔도 됩니다
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
			faq_order: { required:true, number:true, min:0 },
			faq_title : {<?php echo ($this->cbconfig->item('use_faq_dhtml')) ? 'required_' . $this->cbconfig->item('faq_editor_type') : 'required'; ?> : true },
			faq_content : {<?php echo ($this->cbconfig->item('use_faq_dhtml')) ? 'required_' . $this->cbconfig->item('faq_editor_type') : 'required'; ?> : true },
			faq_mobile_content : {<?php echo ($this->cbconfig->item('use_faq_dhtml')) ? 'valid_' . $this->cbconfig->item('faq_editor_type') : ''; ?> : true }
		}
	});
});
//]]>
</script>
