<div class="box">
	<?php
	if (element('bgr_id', element('data', $view))) {
	?>
		<div class="box-header">
			<?php if (element('grouplist', $view)) { ?>
				<div class="pull-right">
					<select name="bgr_id" class="form-control" onChange="location.href='<?php echo admin_url($this->pagedir . '/write_admin'); ?>/' + this.value;">
						<?php foreach (element('grouplist', $view) as $key => $value) { ?>
							<option value="<?php echo element('bgr_id', $value); ?>" <?php echo set_select('bgr_id', element('bgr_id', $value), ((string) element('bgr_id', element('data', $view)) === element('bgr_id', $value) ? true : false)); ?>><?php echo html_escape(element('bgr_name', $value)); ?></option>
						<?php } ?>
					</select>
				</div>
			<?php } ?>
			<ul class="nav nav-tabs">
				<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/write/' . element('bgr_id', element('data', $view))); ?>">기본정보</a></li>
				<li role="presentation" class="active"><a href="<?php echo admin_url($this->pagedir . '/write_admin/' . element('bgr_id', element('data', $view))); ?>">그룹관리자</a></li>
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
		$attributes = array('class' => 'form-inline', 'name' => 'flist', 'id' => 'flist');
		echo form_open(current_full_url(), $attributes);
		?>
			<div class="box-table-header">
				<div class="btn-group pull-right" role="group" aria-label="...">
					<button type="button" class="btn btn-outline btn-default btn-sm btn-list-delete btn-list-selected disabled" data-list-delete-url = "<?php echo element('list_delete_url', $view); ?>" >선택삭제</button>
				</div>
			</div>
			<div class="table-responsive">
				<table class="table table-hover table-striped">
					<thead>
						<tr>
							<th>아이디</th>
							<th>닉네임</th>
							<th>회원이메일</th>
							<th><input type="checkbox" name="chkall" id="chkall" /></th>
						</tr>
					</thead>
					<tbody>
					<?php
					if (element('list', $view)) {
						foreach (element('list', $view) as $result) {
					?>
						<tr>
							<td><?php echo html_escape(element('mem_userid', element('member', $result))); ?></td>
							<td><?php echo element('display_name', $result); ?></td>
							<td><?php echo html_escape(element('mem_email', element('member', $result))); ?></td>
							<td><input type="checkbox" name="chk[]" class="list-chkbox" value="<?php echo element(element('primary_key', $view), $result); ?>" /></td>
						</tr>
					<?php
						}
					}
					if ( ! element('list', $view)) {
					?>
						<tr>
							<td colspan="4" class="nopost">자료가 없습니다</td>
						</tr>
					<?php
					}
					?>
					</tbody>
				</table>
			</div>
		<?php echo form_close(); ?>
	<div>
		<div class="box-table">
			<?php
			$attributes = array('class' => 'form-horizontal', 'name' => 'fadminwrite', 'id' => 'fadminwrite');
			echo form_open(current_full_url(), $attributes);
			?>
				<input type="hidden" name="bgr_id"	value="<?php echo element('bgr_id', element('data', $view)); ?>" />
				<div class="form-group">
					<label class="col-sm-2 control-label">회원아이디</label>
					<div class="col-sm-10 form-inline">
						<input type="text" class="form-control" name="userid" />
						<button type="submit" class="btn btn-success btn-sm">추가하기</button>
					</div>
				</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>

<script type="text/javascript">
//<![CDATA[
$(function() {
	$('#fadminwrite').validate({
		rules: {
			userid: { required:true, alpha_dash:true, minlength:3, maxlength:50 }
		}
	});
});
//]]>
</script>
