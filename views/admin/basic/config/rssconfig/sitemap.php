<div class="box">
	<div class="box-header">
		<ul class="nav nav-tabs">
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir); ?>" onclick="return check_form_changed();">RSS 피드</a></li>
			<li role="presentation" class="active"><a href="<?php echo admin_url($this->pagedir . '/sitemap'); ?>" onclick="return check_form_changed();">사이트맵</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/naverblog'); ?>" onclick="return check_form_changed();">네이버블로그자동등록</a></li>
		 </ul>
	</div>
	<div class="box-table">
		<?php
		echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
		echo show_alert_message(element('alert_message', $view), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
		$attributes = array('class' => 'form-horizontal', 'name' => 'fadminwrite', 'id' => 'fadminwrite');
		echo form_open(current_full_url(), $attributes);
		?>
			<input type="hidden" name="is_submit" value="1" />
			<div class="form-group">
				<label class="col-sm-2 control-label">사이트맵 사용</label>
				<div class="col-sm-10">
					<label for="use_sitemap" class="checkbox-inline">
					<input type="checkbox" name="use_sitemap" id="use_sitemap" value="1" <?php echo set_checkbox('use_sitemap', '1', (element('use_sitemap', element('data', $view)) ? true : false)); ?> /> 사용합니다
					</label>
					<label class=" form-inline" style="padding-top:7px;padding-left:10px;">
						<span class="fa fa-rss"></span>
						<a href="<?php echo site_url('sitemap.xml'); ?>" target="_blank"><?php echo site_url('sitemap.xml'); ?></a>
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">포함된 게시판 목록</label>
				<div class="col-sm-10 form-inline" style="padding-top:7px;">
					<?php
					if (element('boardlist', $view)) {
						foreach (element('boardlist', $view) as $rval) {
					?>
							<label for="brd_id_<?php echo element('brd_id', $rval)?>" class="checkbox-inline"><input type="checkbox" name="brd_id[<?php echo element('brd_id', $rval)?>]" value="1" id="brd_id_<?php echo element('brd_id', $rval)?>" <?php echo set_checkbox('brd_id[' . element('brd_id', $rval) . ']', '1', (element('usesitemap', $rval) ? true : false)); ?> /> <?php echo html_escape(element('brd_name', $rval)); ?></label>
					<?php
						}
					}
					?>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">사이트맵 출력 게시물수</label>
				<div class="col-sm-10">
					<input type="number" class="form-control" name="sitemap_count" id="sitemap_count" value="<?php echo set_value('sitemap_count', element('sitemap_count', element('data', $view))); ?>" />
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
$(function() {
	$('#fadminwrite').validate({
		rules: {
			total_rss_feed_count: {number:true, min:25, max:1000}
		}
	});
});
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
