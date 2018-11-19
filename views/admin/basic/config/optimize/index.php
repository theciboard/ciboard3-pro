<div class="box">
	<div class="box-table">
		<?php
		echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
		echo show_alert_message(element('alert_message', $view), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
		$attributes = array('class' => 'form-horizontal', 'name' => 'fadminwrite', 'id' => 'fadminwrite');
		echo form_open(current_full_url(), $attributes);
		?>
			<input type="hidden" name="execute" value="1" />
			<div class="table-responsive">
				<table class="table table-bordered table-hover table-striped">
					<tbody>
						<tr>
							<td>
								<div class="textbox">DB Table 을 Repair 하고 Optimize 합니다. 이는 데이터베이스 테이블이 문제가 생겼다고 판단되었을 경우 실행해주시면 됩니다.</div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="box-info">
				<div class="box-button btn-group">
					<button type="submit" class="btn btn-success btn-sm">복구 실행하기</button>
				</div>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>
