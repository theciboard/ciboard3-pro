<?php $this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css'); ?>

<div class="board">
	<div class="page-header">
		<h4>비밀번호 입력</h4>
	</div>
	<div class="form-horizontal mt20">
		<?php
		echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
		echo show_alert_message(element('message', $view), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
		echo show_alert_message($this->session->flashdata('message'), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
		$attributes = array('class' => 'form-horizontal', 'name' => 'fpassword', 'id' => 'fpassword');
		echo form_open(current_url(), $attributes);
		?>
			<div class="alert alert-dismissible alert-info infoalert"><?php echo element('info', $view); ?></div>
			<div class="form-group">
				<label for="cur_password" class="col-sm-3 control-label">비밀번호</label>
				<div class="col-sm-3">
					<input type="password" class="form-control" id="modify_password" name="modify_password" />
				</div>
				<div class="col-sm-2">
					<button type="submit" class="btn btn-success btn-sm">확인</button>
				</div>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>
<script type="text/javascript">
//<![CDATA[
$(function() {
	$('#fpassword').validate({
		rules: {
			modify_password : { required:true }
		}
	});
});
//]]>
</script>
