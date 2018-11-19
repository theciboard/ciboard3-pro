<div class="box">
	<div class="box-table">

		<?php
		echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
		echo show_alert_message(element('alert_message', $view), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
		$attributes = array('class' => 'form-horizontal', 'name' => 'fadminwrite', 'id' => 'fadminwrite');
		echo form_open(current_full_url(), $attributes);
		?>
			<div class="alert alert-info">
				현재 메일이 정상적으로 발송되고 있는지 테스트할 수 있는 페이지입니다 <br />
				아래 입력란에 받는분 이메일 주소를 입력후에 <br />
				테스트 메일을 발송해주세요. <br />
			</div>
			<div class="table-responsive">
				<table class="table table-hover table-striped">
					<colgroup>
						<col class="col-md-2">
						<col class="col-md-10">
					</colgroup>
					<tbody>
						<tr>
							<td ><div class="pull-right">보내는 메일 주소</div></td>
							<td><?php echo html_escape(element('webmaster_email', element('data', $view))); ?></td>
						</tr>
						<tr>
							<td><div class="pull-right">보내는 메일 이름</div></td>
							<td><?php echo html_escape(element('webmaster_name', element('data', $view))); ?></td>
						</tr>
						<tr>
							<td><div class="pull-right">받는 메일 주소</div></td>
							<td><input type="email" name="recv_email" class="form-control" value="<?php echo set_value('recv_email', element('webmaster_email', element('data', $view))); ?>" /> </td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="btn-group pull-right" role="group" aria-label="...">
				<button type="submit" class="btn btn-success btn-sm">테스트 메일 보내기</button>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>

<script type="text/javascript">
//<![CDATA[
$(function() {
	$('#fadminwrite').validate({
		rules: {
			recv_email: {required:true, email:true}
		}
	});
});
//]]>
</script>
