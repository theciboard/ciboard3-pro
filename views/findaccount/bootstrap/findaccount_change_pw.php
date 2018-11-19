<?php $this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css'); ?>

<div class="search col-md-8 col-md-offset-2">
	<div class="panel panel-default">
		<div class="panel-heading">패스워드 변경하기</div>
		<div class="panel-body">
			<?php
			echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
			echo show_alert_message(element('error_message', $view), '<div class="alert alert-dismissible alert-warning"><button type="button" class="close alertclose" >&times;</button>', '</div>');
			echo show_alert_message(element('success_message', $view), '<div class="alert alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
			if ( ! element('error_message', $view) && ! element('success_message', $view)) {
				echo show_alert_message(element('info', $view), '<div class="alert alert-info">', '</div>');
				$attributes = array('class' => 'form-horizontal', 'name' => 'fresetpw', 'id' => 'fresetpw');
				echo form_open(current_full_url(), $attributes);
			?>
				<legend>패스워드 변경</legend>
				<p>회원님의 패스워드를 변경합니다.</p>
				<div class="form-group">
					<label class="col-lg-3 control-label">아이디</label>
					<div class="col-md-4"><?php echo element('mem_userid', $view); ?></div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label">새로운 패스워드</label>
					<div class="col-md-4">
						<input type="password" name="new_password" id="new_password" class="form-control" placeholder="Password" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-3 control-label">새로운 패스워드(재입력)</label>
					<div class="col-md-4">
						<input type="password" name="new_password_re" id="new_password_re" class="form-control" placeholder="Password" />
					</div>
				</div>
				<div class="form-group">
					<div class="col-md-4 col-lg-offset-3">
						<button type="submit" class="btn btn-black btn-sm">패스워드 변경하기</button>
					</div>
				</div>
			<?php
				echo form_close();
			}
			?>
		</div>
	</div>
</div>

<script type="text/javascript">
//<![CDATA[
$(function() {
	$('#fresetpw').validate({
		rules: {
			new_password : { required:true, minlength:<?php echo element('password_length', $view); ?> },
			new_password_re : { required:true, minlength:<?php echo element('password_length', $view); ?>, equalTo : '#new_password' }
		}
	});
});
//]]>
</script>
