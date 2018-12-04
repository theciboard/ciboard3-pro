<div class="box">
	<div class="box-table">
		<?php
		echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
		$attributes = array('class' => 'form-horizontal', 'name' => 'fadminwrite', 'id' => 'fadminwrite');
		echo form_open(current_full_url(), $attributes);
		?>
			<input type="hidden" name="<?php echo element('primary_key', $view); ?>"	value="<?php echo element(element('primary_key', $view), element('data', $view)); ?>" />
			<div class="form-group">
				<label class="col-sm-2 control-label">제목</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" name="fgr_title" value="<?php echo set_value('fgr_title', element('fgr_title', element('data', $view))); ?>" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">주소</label>
				<div class="col-sm-10 form-inline">
					<?php echo faq_url(); ?>/ <input type="text" class="form-control" name="fgr_key" value="<?php echo set_value('fgr_key', element('fgr_key', element('data', $view))); ?>" /> 페이지주소를 입력해주세요
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">PC 레이아웃/스킨</label>
				<div class="col-sm-10 form-inline">
					레이아웃 -
					<select name="fgr_layout" id="fgr_layout" class="form-control" >
						<?php echo element('fgr_layout_option', element('data', $view)); ?>
					</select>
					사이드바 -
					<select class="form-control" name="fgr_sidebar" id="fgr_sidebar">
						<option value="">기본설정따름</option>
						<option value="1" <?php echo set_select('fgr_sidebar', '1', (element('fgr_sidebar', element('data', $view)) === '1' ? true : false)); ?> >사용</option>
						<option value="2" <?php echo set_select('fgr_sidebar', '2', (element('fgr_sidebar', element('data', $view)) === '2' ? true : false)); ?> >사용하지않음</option>
					</select>
					스킨 -
					<select name="fgr_skin" id="fgr_skin" class="form-control" >
						<?php echo element('fgr_skin_option', element('data', $view)); ?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">모바일 레이아웃/스킨</label>
				<div class="col-sm-10 form-inline">
					레이아웃 -
					<select name="fgr_mobile_layout" id="fgr_mobile_layout" class="form-control" >
						<?php echo element('fgr_mobile_layout_option', element('data', $view)); ?>
					</select>
					사이드바 -
					<select class="form-control" name="fgr_mobile_idebar" id="fgr_mobile_idebar">
						<option value="">기본설정따름</option>
						<option value="1" <?php echo set_select('fgr_mobile_idebar', '1', (element('fgr_mobile_idebar', element('data', $view)) === '1' ? true : false)); ?> >사용</option>
						<option value="2" <?php echo set_select('fgr_mobile_idebar', '2', (element('fgr_mobile_idebar', element('data', $view)) === '2' ? true : false)); ?> >사용하지않음</option>
					</select>
					스킨 -
					<select name="fgr_mobile_skin" id="fgr_mobile_skin" class="form-control" >
						<?php echo element('fgr_mobile_skin_option', element('data', $view)); ?>
					</select>
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
			fgr_title: 'required',
			fgr_key: { required:true, alpha_dash:true, minlength:3, maxlength:50 }
		}
	});
});
//]]>
</script>
