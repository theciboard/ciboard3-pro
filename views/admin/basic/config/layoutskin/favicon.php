<div class="box">
	<div class="box-header">
		<ul class="nav nav-tabs">
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir); ?>" onclick="return check_form_changed();">레이아웃/스킨설정</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/metatag'); ?>" onclick="return check_form_changed();">메타태그</a></li>
			<li role="presentation" class="active"><a href="<?php echo admin_url($this->pagedir . '/favicon'); ?>" onclick="return check_form_changed();">파비콘 등록</a></li>
		</ul>
	</div>
	<div class="box-table">
		<?php
		echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
		echo show_alert_message(element('alert_message', $view), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
		echo show_alert_message(element('message', $view), '<div class="alert alert-warning">', '</div>');
		$attributes = array('class' => 'form-horizontal', 'name' => 'fadminwrite', 'id' => 'fadminwrite');
		echo form_open_multipart(current_full_url(), $attributes);
		?>
			<input type="hidden" name="is_submit" value="1" />
			<div class="alert alert-info">
				<p>파비콘은 16px * 16px 크기의 이미지 ico 확장자로 등록해 주십시요.</p>
				<p>파비콘은 즐겨찾기(favorites) 와 아이콘(icon)의 합성어로 주소창 좌측에 조그만 아이콘으로 표시됩니다.</p>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">파비콘</label>
				<div class="col-sm-10">
					<?php
					if (element('site_favicon', element('data', $view))) {
					?>
						<img src="<?php echo site_url(config_item('uploads_dir') . '/favicon/' . element('site_favicon', element('data', $view))); ?>" alt="파비콘" title="파비콘" />
						<label for="site_favicon_del">
							<input type="checkbox" name="site_favicon_del" id="site_favicon_del" value="1"/> 삭제
						</label>
					<?php
					}
					?>
					<input type="file" name="site_favicon" id="site_favicon" />
				</div>
			</div>
			<div class="btn-group pull-right" role="group" aria-label="...">
				<button type="submit" class="btn btn-success btn-sm">저장하기</button>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>

<script type="text/javascript">
//<![CDATA[
var form_original_data = $('#fadminwrite').serialize();
function check_form_changed() {
	if ($('#fadminwrite').serialize() !== form_original_data) {
		if (confirm('저장하지 않은 정보가 있습니다. 저장하지 않은 상태로 이동하시겠습니까?')) {
			return true;
		} else {
			return false;
		}
	}
	return true;
}
//]]>
</script>
