<div class="box">
	<div class="box-header">
		<ul class="nav nav-tabs">
			<li role="presentation" class="active"><a href="<?php echo admin_url($this->pagedir); ?>" onclick="return check_form_changed();">RSS 피드</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/sitemap'); ?>" onclick="return check_form_changed();">사이트맵</a></li>
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
				<label class="col-sm-2 control-label">통합 RSS 피드 사용</label>
				<div class="col-sm-10">
					<label for="use_total_rss_feed" class="checkbox-inline">
					<input type="checkbox" name="use_total_rss_feed" id="use_total_rss_feed" value="1" <?php echo set_checkbox('use_total_rss_feed', '1', (element('use_total_rss_feed', element('data', $view)) ? true : false)); ?> /> 사용합니다
					</label>
					<label class=" form-inline" style="padding-top:7px;padding-left:10px;">
						<span class="fa fa-rss"></span>
						<a href="<?php echo rss_url(); ?>" target="_blank"><?php echo rss_url(); ?></a>
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
							<label for="brd_id_<?php echo element('brd_id', $rval)?>" class="checkbox-inline"><input type="checkbox" name="brd_id[<?php echo element('brd_id', $rval)?>]" value="1" id="brd_id_<?php echo element('brd_id', $rval)?>" <?php echo set_checkbox('brd_id[' . element('brd_id', $rval) . ']', '1', (element('userss', $rval) ? true : false)); ?> /> <?php echo html_escape(element('brd_name', $rval)); ?></label>
					<?php
						}
					}
					?>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">내용공개</label>
				<div class="col-sm-10 form-inline">
					<select name="total_rss_feed_content" class="form-control" >
						<option value="" <?php echo set_select('total_rss_feed_content', '', ( ! element('total_rss_feed_content', element('data', $view)) ? true : false)); ?>>공개하지 않음</option>
						<option value="1" <?php echo set_select('total_rss_feed_content', '1', (element('total_rss_feed_content', element('data', $view)) === '1' ? true : false)); ?>>HTML 태그 제외 공개</option>
						<option value="2" <?php echo set_select('total_rss_feed_content', '2', (element('total_rss_feed_content', element('data', $view)) === '2' ? true : false)); ?>>전부공개</option>
					</select>
					<span class="help-inline">RSS 페이지에 본문 내용을 얼마나 공개할 것인지 설정합니다</span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">통합 RSS 제목</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" name="total_rss_feed_title" id="total_rss_feed_title" value="<?php echo set_value('total_rss_feed_title', element('total_rss_feed_title', element('data', $view))); ?>" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">통합 RSS 설명</label>
				<div class="col-sm-10">
					<textarea class="form-control" rows="5" name="total_rss_feed_description"><?php echo set_value('total_rss_feed_description', element('total_rss_feed_description', element('data', $view))); ?></textarea>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">RSS 표시 저작권</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" name="total_rss_feed_copyright" id="total_rss_feed_copyright" value="<?php echo set_value('total_rss_feed_copyright', element('total_rss_feed_copyright', element('data', $view))); ?>" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">RSS 출력 게시물수</label>
				<div class="col-sm-10">
					<input type="number" class="form-control" name="total_rss_feed_count" id="total_rss_feed_count" value="<?php echo set_value('total_rss_feed_count', element('total_rss_feed_count', element('data', $view))); ?>" />
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
			total_rss_feed_title: {required :'#use_total_rss_feed:checked'},
			total_rss_feed_description: {required :'#use_total_rss_feed:checked'},
			total_rss_feed_copyright: {required :'#use_total_rss_feed:checked'},
			total_rss_feed_count: {required :'#use_total_rss_feed:checked', number:true, min:0, max:1000}
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
