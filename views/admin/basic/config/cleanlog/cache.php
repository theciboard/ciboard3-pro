<div class="box">
	<div class="box-header">
		<ul class="nav nav-tabs">
			<li role="presentation" class="active"><a href="<?php echo admin_url($this->pagedir . '/cache'); ?>">캐시삭제</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/thumbnail'); ?>">썸네일삭제</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/visitlog'); ?>">방문자로그삭제</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/loginlog'); ?>">로그인로그삭제</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/filedownloadlog'); ?>">파일다운로드로그삭제</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/posthistory'); ?>">게시물변경로그삭제</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/linkclicklog'); ?>">링크클릭로그삭제</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/note'); ?>">오래된쪽지삭제</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/autologin'); ?>">자동로그인로그삭제</a></li>
		</ul>
	</div>
	<div class="box-table">
		<?php
		echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
		echo show_alert_message(element('alert_message', $view), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
		$attributes = array('class' => 'form-horizontal', 'name' => 'fadminwrite', 'id' => 'fadminwrite', 'onSubmit' => 'return deletecheck()');
		echo form_open(current_full_url(), $attributes);
		?>
			<input type="hidden" name="execute" value="1" />
			<div class="table-responsive">
				<table class="table table-bordered table-hover table-striped">
					<tbody>
						<tr>
							<td><div class="textbox">캐시데이터를 모두 삭제하고 초기화합니다 <br />시간이 조금 오래 걸릴 수도 있습니다 <br />삭제하시겠습니까</div></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="box-info">
				<div class="box-button btn-group">
					<button type="submit" class="btn btn-success btn-sm">삭제하기</button>
				</div>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>
