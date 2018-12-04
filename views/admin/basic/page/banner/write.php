<div class="box">
	<div class="box-table">
		<?php
		echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
		$attributes = array('class' => 'form-horizontal', 'name' => 'fadminwrite', 'id' => 'fadminwrite');
		echo form_open_multipart(current_full_url(), $attributes);
		?>
			<input type="hidden" name="<?php echo element('primary_key', $view); ?>"	value="<?php echo element(element('primary_key', $view), element('data', $view)); ?>" />
			<div class="form-group">
				<label class="col-sm-2 control-label">이미지 업로드</label>
				<div class="col-sm-10">
					<?php
					if (element('ban_image', element('data', $view))) {
					?>
						<img src="<?php echo banner_image_url(element('ban_image', element('data', $view)), '', 150); ?>" alt="배너 이미지" title="배너 이미지" />
						<label for="ban_image_del">
							<input type="checkbox" name="ban_image_del" id="ban_image_del" value="1" <?php echo set_checkbox('ban_image_del', '1'); ?> /> 삭제
						</label>
					<?php
					}
					?>
					<input type="file" name="ban_image" id="ban_image" />
					<p class="help-block">gif, jpg, png 파일 업로드가 가능합니다</p>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">이미지 설명</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" name="ban_title" value="<?php echo set_value('ban_title', element('ban_title', element('data', $view))); ?>" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">시작일</label>
				<div class="col-sm-10 form-inline">
					<input type="text" class="form-control datepicker" name="ban_start_date" value="<?php echo set_value('ban_start_date', element('ban_start_date', element('data', $view))); ?>" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">종료일</label>
				<div class="col-sm-10 form-inline">
					<input type="text" class="form-control datepicker" name="ban_end_date" value="<?php echo set_value('ban_end_date', element('ban_end_date', element('data', $view))); ?>" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">배너 위치</label>
				<div class="col-sm-10 form-inline">
					<select class="form-control" name="bng_name">
						<option value="">=배너 위치 선택=</option>
						<?php
						if (element('group', $view)) {
							foreach (element('group', $view) as $gval) {
						?>
							<option value="<?php echo html_escape(element('bng_name', $gval)); ?>" <?php echo set_select('bng_name', element('bng_name', $gval), (element('bng_name', element('data', $view)) === element('bng_name', $gval) ? true : false)); ?>><?php echo html_escape(element('bng_name', $gval)); ?></option>
						<?php
							}
						}
						?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">배너 URL</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" name="ban_url" value="<?php echo set_value('ban_url', element('ban_url', element('data', $view))); ?>" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">배너 사이즈</label>
				<div class="col-sm-10 form-inline">
					가로 :
					<input type="number" class="form-control" name="ban_width" value="<?php echo set_value('ban_width', (int) element('ban_width', element('data', $view))); ?>" />px
					,
					세로 :
					<input type="number" class="form-control" name="ban_height" value="<?php echo set_value('ban_height', (int) element('ban_height', element('data', $view))); ?>" />px
					<div class="help-inline">가로값과 세로값을 입력하시면 입력하신 사이즈로 배너가 출력이 되며, 입력하지 않으면 업로드한 원본 크기대로 출력됩니다</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">배너 링크 새창여부</label>
				<div class="col-sm-10 form-inline">
					<select name="ban_target" class="form-control">
						<option value="" <?php echo set_select('ban_target', '', ( ! element('ban_target', element('data', $view)) ? true : false)); ?> >현재창</option>
						<option value="_blank" <?php echo set_select('ban_target', '_blank', (element('ban_target', element('data', $view)) === '_blank' ? true : false)); ?> >새창</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">배너 정렬순서</label>
				<div class="col-sm-10">
					<input type="number" class="form-control" name="ban_order" value="<?php echo set_value('ban_order', (int) element('ban_order', element('data', $view))); ?>" />
					<div class="help-inline">정렬 순서가 큰 값이 먼저 출력됩니다</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">배너표시기기</label>
				<div class="col-sm-10 form-inline">
					<select class="form-control" name="ban_device">
						<option value="all" <?php echo set_select('ban_device', 'all', (element('ban_device', element('data', $view)) === 'all' ? true : false)); ?>>모든기기</option>
						<option value="pc" <?php echo set_select('ban_device', 'pc', (element('ban_device', element('data', $view)) === 'pc' ? true : false)); ?>>PC만</option>
						<option value="mobile" <?php echo set_select('ban_device', 'mobile', (element('ban_device', element('data', $view)) === 'mobile' ? true : false)); ?>>모바일만</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">배너활성화</label>
				<div class="col-sm-10">
					<label class="radio-inline" for="ban_activated_1">
						<input type="radio" name="ban_activated" id="ban_activated_1" value="1" <?php echo set_radio('ban_activated', '1', (element('ban_activated', element('data', $view)) === '1' ? true : false)); ?> /> 활성
					</label>
					<label class="radio-inline" for="ban_activated_0">
						<input type="radio" name="ban_activated" id="ban_activated_0" value="0" <?php echo set_radio('ban_activated', '0', (element('ban_activated', element('data', $view)) !== '1' ? true : false)); ?> /> 비활성
					</label>
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
			ban_title: 'required',
			ban_start_date: { alpha_dash:true, minlength:10, maxlength:10 },
			ban_end_date: { alpha_dash:true, minlength:10, maxlength:10 },
			bng_name: 'required',
			ban_width: { number:true },
			ban_height: { number:true },
			ban_order: { number:true },
			ban_activated: 'required'
		}
	});
});
//]]>
</script>
