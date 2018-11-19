<?php $this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css'); ?>

<div class="board">
	<div class="table-box">
		<div class="table-heading">패스워드 입력</div>
		<div class="table-body change_pw">

			<?php
			echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
			echo show_alert_message(element('message', $view), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
			echo show_alert_message($this->session->flashdata('message'), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
			$attributes = array('class' => 'form-horizontal', 'name' => 'fpassword', 'id' => 'fpassword');
			echo form_open(current_url(), $attributes);
			?>
				<div class="alert alert-dismissible alert-info infoalert"><?php echo element('info', $view); ?></div>
				<ol class="confirm_password">
					<li>
						<span>패스워드</span>
						<input type="password" class="input" id="modify_password" name="modify_password" />
					</li>
					<li>
						<span></span>
						<button type="submit" class="btn btn-black">확인</button>
					</li>
				</ol>
			<?php echo form_close(); ?>
		</div>
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
