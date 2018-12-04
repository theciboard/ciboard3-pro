<div class="box">
	<?php
	if (element('bgr_id', element('data', $view))) {
	?>
		<div class="box-header">
			<?php if (element('grouplist', $view)) { ?>
				<div class="pull-right">
					<select name="bgr_id" class="form-control" onChange="location.href='<?php echo admin_url($this->pagedir . '/write'); ?>/' + this.value;">
						<?php foreach (element('grouplist', $view) as $key => $value) { ?>
							<option value="<?php echo element('bgr_id', $value); ?>" <?php echo set_select('bgr_id', element('bgr_id', $value), ((string) element('bgr_id', element('data', $view)) === element('bgr_id', $value) ? true : false)); ?>><?php echo html_escape(element('bgr_name', $value)); ?></option>
						<?php } ?>
					</select>
				</div>
			<?php } ?>
			<ul class="nav nav-tabs">
				<li role="presentation" class="active"><a href="<?php echo admin_url($this->pagedir . '/write/' . element('bgr_id', element('data', $view))); ?>" onclick="return check_form_changed();">기본정보</a></li>
				<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/write_admin/' . element('bgr_id', element('data', $view))); ?>" onclick="return check_form_changed();">그룹관리자</a></li>
			</ul>
		</div>
	<?php
	} else {
	?>
		<div class="box-header">
			<ul class="nav nav-tabs">
				<li role="presentation" class="active"><a href="javascript:;">기본정보</a></li>
				<li role="presentation"><a href="javascript:;" onClick="alert('기본정보를 저장하신 후에 다른 정보 수정이 가능합니다');">그룹관리자</a></li>
			</ul>
		</div>
	<?php
	}
	?>
	<div class="box-table">
		<?php
		echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
		echo show_alert_message(element('alert_message', $view), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
		echo show_alert_message($this->session->flashdata('message'), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
		$attributes = array('class' => 'form-horizontal', 'name' => 'fadminwrite', 'id' => 'fadminwrite');
		echo form_open(current_full_url(), $attributes);
		?>
			<input type="hidden" name="<?php echo element('primary_key', $view); ?>"	value="<?php echo element(element('primary_key', $view), element('data', $view)); ?>" />
			<div class="form-group">
				<label class="col-sm-2 control-label">게시물 주소</label>
				<div class="col-sm-10">
					<div class="form-inline">
						<?php echo site_url('group'); ?>/ <input type="text" class="form-control" name="bgr_key" value="<?php echo set_value('bgr_key', element('bgr_key', element('data', $view))); ?>" /> 페이지주소를 입력해주세요
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">그룹명</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" name="bgr_name" value="<?php echo set_value('bgr_name', element('bgr_name', element('data', $view))); ?>" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">그룹 정렬 순서</label>
				<div class="col-sm-10">
					<input type="number" class="form-control" name="bgr_order" value="<?php echo set_value('bgr_order', (int) element('bgr_order', element('data', $view))); ?>" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">PC 레이아웃/스킨</label>
				<div class="col-sm-10 form-inline">
					레이아웃 -
					<select name="group_layout" id="group_layout" class="form-control" >
						<?php echo element('group_layout_option', element('data', $view)); ?>
					</select>
					사이드바 -
					<select class="form-control" name="group_sidebar" id="group_sidebar">
						<option value="">기본설정따름</option>
						<option value="1" <?php echo set_select('group_sidebar', '1', (element('group_sidebar', element('data', $view)) === '1' ? true : false)); ?> >사용</option>
						<option value="2" <?php echo set_select('group_sidebar', '2', (element('group_sidebar', element('data', $view)) === '2' ? true : false)); ?> >사용하지않음</option>
					</select>
					스킨 -
					<select name="group_skin" id="group_skin" class="form-control" >
						<?php echo element('group_skin_option', element('data', $view)); ?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">모바일 레이아웃/스킨</label>
				<div class="col-sm-10 form-inline">
					레이아웃 -
					<select name="group_mobile_layout" id="group_mobile_layout" class="form-control" >
						<?php echo element('group_mobile_layout_option', element('data', $view)); ?>
					</select>
					사이드바 -
					<select class="form-control" name="group_mobile_idebar" id="group_mobile_idebar">
						<option value="">기본설정따름</option>
						<option value="1" <?php echo set_select('group_mobile_idebar', '1', (element('group_mobile_idebar', element('data', $view)) === '1' ? true : false)); ?> >사용</option>
						<option value="2" <?php echo set_select('group_mobile_idebar', '2', (element('group_mobile_idebar', element('data', $view)) === '2' ? true : false)); ?> >사용하지않음</option>
					</select>
					스킨 -
					<select name="group_mobile_skin" id="group_mobile_skin" class="form-control" >
						<?php echo element('group_mobile_skin_option', element('data', $view)); ?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">상단 내용</label>
				<div class="col-sm-10">
					<textarea class="form-control" rows="5" name="header_content"><?php echo set_value('header_content', element('header_content', element('data', $view))); ?></textarea>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">하단 내용</label>
				<div class="col-sm-10">
					<textarea class="form-control" rows="5" name="footer_content"><?php echo set_value('footer_content', element('footer_content', element('data', $view))); ?></textarea>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">모바일 상단 내용</label>
				<div class="col-sm-10">
					<textarea class="form-control" rows="5" name="mobile_header_content"><?php echo set_value('mobile_header_content', element('mobile_header_content', element('data', $view))); ?></textarea>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">모바일 하단 내용</label>
				<div class="col-sm-10">
					<textarea class="form-control" rows="5" name="mobile_footer_content"><?php echo set_value('mobile_footer_content', element('mobile_footer_content', element('data', $view))); ?></textarea>
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
			bgr_key: { required:true, alpha_dash:true, minlength:3, maxlength:50 },
			bgr_name: 'required',
			bgr_order: { required:true, number:true, min:0 }
		}
	});
});
var form_original_data = $('#fadminwrite').serialize();
function check_form_changed() {
	if ($('#fadminwrite').serialize() !== form_original_data) {
		if (confirm('저장하지 않은 정보가 있습니다. 저장하지 않은 상태로 이동하시겠습니까?')) {
			return true;
		} else {
			return false;
		}
	}
	return true;
}
//]]>
</script>
