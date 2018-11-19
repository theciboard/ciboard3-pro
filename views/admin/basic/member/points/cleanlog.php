<div class="box">
	<div class="box-table">
		<div class="box-table-header">
			<ul class="nav nav-pills">
				<li role="presentation"><a href="<?php echo admin_url($this->pagedir); ?>">포인트 목록</a></li>
				<li role="presentation" class="active"><a href="<?php echo admin_url($this->pagedir . '/cleanlog'); ?>">포인트 로그 정리</a></li>
			</ul>
		</div>
		<?php
		echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
		echo show_alert_message(element('alert_message', $view), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
		?>
		<div class="table-responsive">
			<table class="table table-bordered table-hover table-striped">
				<tbody>
					<tr>
						<td>
							<p>포인트 기능을 사용하시는 경우, 포인트 테이블에 데이터가 급속도로 쌓이게 됩니다.</p>
							<p>포인트 테이블에 데이터가 많이 쌓이게 되면 사이트 속도에 영향을 끼칠 수 있습니다.</p>
							<p>이 페이지는 회원님이 보유하고 계신 포인트의 현재 총 합을 유지한채,</p>
							<p>오래된 포인트 내역을 간단히 정리해 포인트 테이블의 크기를 가볍게 하는데에 목적이 있습니다.</p>
							<p>원하는 기간 이상이 된 포인트 중 한 회원이 10건 이상의 포인트 내역을 가지고 있을 경우, 그 포인트 내역을 1건으로 정리합니다.</p>
							<p>정리를 할 때마다 서버 부하 때문에 100명의 회원 것만 정리합니다. 회원이 많을 경우, 정리하기를 여러번 실행하시면 됩니다.</p>
							<?php
							$attributes = array('class' => 'form-horizontal', 'name' => 'fadminwrite', 'id' => 'fadminwrite');
							echo form_open(current_full_url(), $attributes);
							?>
								<input type="number" class="form-control" name="day" value="<?php echo set_value('day', 30); ?>" /> 일 이상된 포인트 내역을 검색합니다.
								<button type="submit" class="btn btn-warning btn-sm">검색</button>
							<?php echo form_close(); ?>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="box-info">
			<?php
			if (element('msg', $view)) {
				$attributes = array('class' => 'form-horizontal', 'name' => 'fadminwrite2', 'id' => 'fadminwrite2', 'onSubmit' => 'return cleancheck()');
				echo form_open(current_full_url(), $attributes);
			?>
				<input type="hidden" name="day" value="<?php echo element('day', $view); ?>" />
				<input type="hidden" name="criterion" value="<?php echo element('criterion', $view); ?>" />
				<input type="hidden" name="log_count" value="<?php echo element('log_count', $view); ?>" />
				<?php echo element('msg', $view); ?>
				<div class="box-button btn-group">
					<button type="submit" class="btn btn-success btn-sm">정리하기</button>
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
	$('#fadminwrite').validate({
		rules: {
			day: {required:true, number:true, min:0}
		}
	});
});

function cleancheck(){
	if (confirm('정말 정리하시겠습니까? 정리하신 후에는 복구가 불가능합니다')) {
		return true;
	} else {
		return false;
	}
}

//]]>
</script>
