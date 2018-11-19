<div class="box">
	<div class="box-table">
		<?php
		echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
		$attributes = array('class' => 'form-horizontal', 'name' => 'fadminwrite', 'id' => 'fadminwrite');
		echo form_open(current_full_url(), $attributes);
		?>
			<input type="hidden" name="execute" value="1" />
			<div class="table-responsive">
				<?php
				if (element('alert_message', $view)) {
					echo show_alert_message(element('alert_message', $view), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
				} else {
				?>
					<table class="table table-bordered table-hover table-striped">
						<tbody>
							<tr><td><div class="textbox">최신 회원 정보로 업데이트합니다.<br />
							실행한 후에 업데잍 완료 메세지가 나올 때까지 기다려주세요</div></td></tr>
						</tbody>
					</table>
				<?php
				}
				?>
			</div>
			<div class="box-info">
				<div class="box-button btn-group">
					<button type="submit" class="btn btn-success btn-sm">업데이트 실행하기</button>
				</div>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>
