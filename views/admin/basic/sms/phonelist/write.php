<div class="box">
	<div class="box-table">
		<?php
		echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
		$attributes = array('class' => 'form-horizontal', 'name' => 'fadminwrite', 'id' => 'fadminwrite');
		echo form_open(current_full_url(), $attributes);
		?>
			<input type="hidden" name="<?php echo element('primary_key', $view); ?>"	value="<?php echo element(element('primary_key', $view), element('data', $view)); ?>" />
			<div class="form-group">
				<label class="col-sm-2 control-label">그룹</label>
				<div class="col-sm-10 form-inline">
					<select name="smg_id" id="smg_id" class="form-control" >
						<?php echo element('group_option', element('data', $view)); ?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">이름</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" name="sme_name" value="<?php echo set_value('sme_name', element('sme_name', element('data', $view))); ?>" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">핸드폰</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" name="sme_phone" value="<?php echo set_value('sme_phone', element('sme_phone', element('data', $view))); ?>" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">수신여부</label>
				<div class="col-sm-10">
					<label class="radio-inline" for="sme_receive_1" >
						<input type="radio" name="sme_receive" id="sme_receive_1" value="1" <?php echo set_checkbox('sme_receive', '1', (element('sme_receive', element('data', $view)) ? true : false)); ?> /> 수신
					</label>
					<label class="radio-inline" for="sme_receive_0" >
						<input type="radio" name="sme_receive" id="sme_receive_0" value="" <?php echo set_checkbox('sme_receive', '', ( ! element('sme_receive', element('data', $view)) ? true : false)); ?> /> 비수신
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">관리자 메모</label>
				<div class="col-sm-10">
					<textarea class="form-control" rows="5" name="sme_memo"><?php echo set_value('sme_memo', element('sme_memo', element('data', $view))); ?></textarea>
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
			doc_layout: 'required',
			doc_skin: 'required'
		}
	});
});
//]]>
</script>
