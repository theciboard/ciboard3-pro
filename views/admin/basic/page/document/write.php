<div class="box">
	<div class="box-table">
		<?php
		echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
		$attributes = array('class' => 'form-horizontal', 'name' => 'fadminwrite', 'id' => 'fadminwrite');
		echo form_open(current_full_url(), $attributes);
		?>
			<input type="hidden" name="<?php echo element('primary_key', $view); ?>"	value="<?php echo element(element('primary_key', $view), element('data', $view)); ?>" />
			<div class="form-group">
				<label class="col-sm-2 control-label">페이지주소</label>
				<div class="col-sm-10 form-inline">
					<?php echo document_url(); ?> <input type="text" class="form-control" name="doc_key" value="<?php echo set_value('doc_key', element('doc_key', element('data', $view))); ?>" /> 페이지주소를 입력해주세요
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">페이지제목</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" name="doc_title" value="<?php echo set_value('doc_title', element('doc_title', element('data', $view))); ?>" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">PC 레이아웃/스킨</label>
				<div class="col-sm-10 form-inline">
					레이아웃 -
					<select name="doc_layout" id="doc_layout" class="form-control" >
						<?php echo element('doc_layout_option', element('data', $view)); ?>
					</select>
					사이드바 -
					<select class="form-control" name="doc_sidebar" id="doc_sidebar">
						<option value="">기본설정따름</option>
						<option value="1" <?php echo set_select('doc_sidebar', '1', (element('doc_sidebar', element('data', $view)) === '1' ? true : false)); ?> >사용</option>
						<option value="2" <?php echo set_select('doc_sidebar', '2', (element('doc_sidebar', element('data', $view)) === '2' ? true : false)); ?> >사용하지않음</option>
					</select>
					스킨 -
					<select name="doc_skin" id="doc_skin" class="form-control" >
						<?php echo element('doc_skin_option', element('data', $view)); ?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">모바일 레이아웃/스킨</label>
				<div class="col-sm-10 form-inline">
					레이아웃 -
					<select name="doc_mobile_layout" id="doc_mobile_layout" class="form-control" >
						<?php echo element('doc_mobile_layout_option', element('data', $view)); ?>
					</select>
					사이드바 -
					<select class="form-control" name="doc_mobile_sidebar" id="doc_mobile_sidebar">
						<option value="">기본설정따름</option>
						<option value="1" <?php echo set_select('doc_mobile_sidebar', '1', (element('doc_mobile_sidebar', element('data', $view)) === '1' ? true : false)); ?> >사용</option>
						<option value="2" <?php echo set_select('doc_mobile_sidebar', '2', (element('doc_mobile_sidebar', element('data', $view)) === '2' ? true : false)); ?> >사용하지않음</option>
					</select>
					스킨 -
					<select name="doc_mobile_skin" id="doc_mobile_skin" class="form-control" >
						<?php echo element('doc_mobile_skin_option', element('data', $view)); ?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">내용</label>
				<div class="col-sm-10">
					<?php echo display_dhtml_editor('doc_content', set_value('doc_content', element('doc_content', element('data', $view))), $classname = 'form-control dhtmleditor', $is_dhtml_editor = $this->cbconfig->item('use_document_dhtml'), $editor_type = $this->cbconfig->item('document_editor_type')); ?>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">모바일내용</label>
				<div class="col-sm-10">
					<?php echo display_dhtml_editor('doc_mobile_content', set_value('doc_mobile_content', element('doc_mobile_content', element('data', $view))), $classname = 'form-control dhtmleditor', $is_dhtml_editor = $this->cbconfig->item('use_document_dhtml'), $editor_type = $this->cbconfig->item('document_editor_type')); ?>
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
			doc_key: {required:true, minlength:3, maxlength:50, alpha_dash : true},
			doc_title: 'required',
			doc_content : {<?php echo ($this->cbconfig->item('use_document_dhtml')) ? 'required_' . $this->cbconfig->item('document_editor_type') : 'required'; ?> : true },
			doc_mobile_content : {<?php echo ($this->cbconfig->item('use_document_dhtml')) ? 'valid_' . $this->cbconfig->item('document_editor_type') : ''; ?> : true }
		}
	});
});
//]]>
</script>
