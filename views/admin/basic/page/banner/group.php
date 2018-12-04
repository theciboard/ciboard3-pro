<div class="box">
	<div class="box-table">
		<?php
		echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
		echo show_alert_message(element('alert_message', $view), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
		echo show_alert_message($this->session->flashdata('message'), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
		$attributes = array('class' => 'form-inline', 'name' => 'flist', 'id' => 'flist');
		echo form_open(current_full_url(), $attributes);
		?>
			<div class="box-table-header">
				<ul class="nav nav-pills">
					<li role="presentation"><a href="<?php echo admin_url($this->pagedir); ?>">배너관리</a></li>
					<li role="presentation" class="active"><a href="<?php echo admin_url($this->pagedir . '/group'); ?>">배너 위치 관리</a></li>
				</ul>
				<div class="btn-group pull-right" role="group" aria-label="...">
					<button type="button" class="btn btn-outline btn-default btn-sm btn-list-delete btn-list-selected disabled" data-list-delete-url = "<?php echo element('list_delete_url', $view); ?>" >선택삭제</button>
				</div>
			</div>
			<div class="table-responsive">
				<table class="table table-hover table-striped">
					<thead>
						<tr>
							<th>배너 위치명</th>
							<th>이 위치에 속한 배너수</th>
							<th><input type="checkbox" name="chkall" id="chkall" /></th>
						</tr>
					</thead>
					<tbody>
					<?php
					if (element('list', element('data', $view))) {
						foreach (element('list', element('data', $view)) as $result) {
					?>
						<tr>
							<td><?php echo html_escape(element('bng_name', $result)); ?></td>
							<td><?php echo number_format((int) element('banner_count', $result)); ?></td>
							<td><?php if ( ! element('banner_count', $result)) { ?><input type="checkbox" name="chk[]" class="list-chkbox" value="<?php echo element(element('primary_key', $view), $result); ?>" /><?php } ?></td>
						</tr>
					<?php
						}
					}
					if ( ! element('list', element('data', $view))) {
					?>
						<tr>
							<td colspan="3" class="nopost">자료가 없습니다</td>
						</tr>
					<?php
					}
					?>
					</tbody>
				</table>
			</div>
		<?php echo form_close(); ?>
	</div>
	<div>
		<div class="box-table">
			<?php
			$attributes = array('class' => 'form-horizontal', 'name' => 'fadminwrite', 'id' => 'fadminwrite');
			echo form_open(current_full_url(), $attributes);
			?>
				<div class="form-group">
					<label class="col-sm-2 control-label">배너 위치명</label>
					<div class="col-sm-8 form-inline">
						<input type="text" class="form-control" name="bng_name" />
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
			bng_name: { required:true, alpha_dash:true }
		}
	});
});
//]]>
</script>
