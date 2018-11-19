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
								<div class="textbox">
									현재 설치된 DB Version은 <strong><?php echo element('current_version', $view); ?></strong> 입니다 <br />
									<?php
									if (element('latest_version', $view) > element('current_version', $view)) {
									?>
										DB Version <strong><?php echo element('latest_version', $view); ?></strong> 으로 업그레이드가 필요합니다
									<?php
									} else {
									?>
										현재 최신 버전이 설치되어있습니다. <br />
									<?php
									}
									?>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<?php
			if (element('latest_version', $view) > element('current_version', $view)) {
			?>
				<div class="box-info">
					<div class="box-button btn-group">
						<button type="submit" class="btn btn-success btn-sm">최신버전으로 업그레이드</button>
					</div>
				</div>
			<?php
			}
			?>
		<?php echo form_close(); ?>
	</div>
</div>
