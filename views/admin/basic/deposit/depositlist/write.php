<div class="box">
	<div class="box-table">
		<?php
		echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
		$attributes = array('class' => 'form-horizontal', 'name' => 'fadminwrite', 'id' => 'fadminwrite');
		echo form_open(current_full_url(), $attributes);
		?>
			<input type="hidden" name="<?php echo element('primary_key', $view); ?>"	value="<?php echo element(element('primary_key', $view), element('data', $view)); ?>" />
			<div class="form-group">
				<label class="col-sm-2 control-label">회원아이디</label>
				<div class="col-sm-10 form-inline">
					<input type="text" class="form-control" name="mem_userid" value="<?php echo set_value('mem_userid', element('mem_userid', element('data', $view))); ?>" <?php echo element(element('primary_key', $view), element('data', $view)) ? ' readonly="readonly" ' : '';?> />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label"><?php echo $this->cbconfig->item('deposit_name'); ?> 변동</label>
				<div class="col-sm-10">
					<input type="number" class="form-control" name="dep_deposit" value="<?php echo set_value('dep_deposit', element('dep_deposit', element('data', $view))); ?>" />
					<div class="help-inline">예치금을 충전하는 경우는 양수로, 예치금을 사용하는 경우는 음수로 입력해주세요</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">내용</label>
				<div class="col-sm-10">
					<textarea class="form-control" rows="5" name="dep_content"><?php echo set_value('dep_content', element('dep_content', element('data', $view))); ?></textarea>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">관리자 메모</label>
				<div class="col-sm-10">
					<textarea class="form-control" rows="5" name="dep_admin_memo"><?php echo set_value('dep_admin_memo', element('dep_admin_memo', element('data', $view))); ?></textarea>
				</div>
			</div>
			<div class="btn-group pull-right" role="group" aria-label="...">
				<button type="submit" class="btn btn-outline btn-success btn-sm">저장하기</button>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>
<script type="text/javascript">
//<![CDATA[
$(function() {
	$('#fadminwrite').validate({
		rules: {
			mem_userid: { required:true, minlength:3, maxlength:20 },
			dep_deposit: { required:true, number:true},
			dep_content: 'required'
		}
	});
});
//]]>
</script>
