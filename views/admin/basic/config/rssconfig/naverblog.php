<div class="box">
	<div class="box-header">
		<ul class="nav nav-tabs">
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir); ?>" onclick="return check_form_changed();">RSS 피드</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/sitemap'); ?>" onclick="return check_form_changed();">사이트맵</a></li>
			<li role="presentation" class="active"><a href="<?php echo admin_url($this->pagedir . '/naverblog'); ?>" onclick="return check_form_changed();">네이버블로그자동등록</a></li>
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
			<div class="alert alert-success">
				<p>게시글 작성시 해당 내용이 네이버 블로그에 자동등록되는 기능입니다.</p>
				<p>게시글을 처음 작성시에 그 내용이 포스팅되며, 게시글을 수정시에는 해당 내용이 반영되지 않습니다.</p>
				<p>하루에 일정 개수 이상을 등록하면 스팸으로 간주되어 블로그가 폐쇄될 수 있으므로 조심하여 주십시오.</p>
				<p>일반회원 또는 비회원이 등록한 글은 네이버에 포스팅되지 않으며, 게시판 관리자, 그룹 관리자, 최고 관리자가 등록한 글에 한하여 네이버 블로그에 등록이 됩니다.</p>
				<p>모든 게시판의 내용이 모두 네이버에 등록되는 것이 아니라, 포함된 게시판 목록에 체크된 게시판의 글만 포스팅됩니다.</p>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">네이버 글등록 기능 사용</label>
				<div class="col-sm-10">
					<label for="use_naver_blog_post" class="checkbox-inline">
					<input type="checkbox" name="use_naver_blog_post" id="use_naver_blog_post" value="1" <?php echo set_checkbox('use_naver_blog_post', '1', (element('use_naver_blog_post', element('data', $view)) ? true : false)); ?> /> 사용합니다
					</label>
					<span class="help-inline">네이버 글등록 기능을 사용하시면 게시글 작성시 해당 내용이 네이버 블로그에 동시등록됩니다.</span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">포함된 게시판 목록</label>
				<div class="col-sm-10 form-inline" style="padding-top:7px;">
					<?php
					if (element('boardlist', $view)) {
						foreach (element('boardlist', $view) as $rval) {
					?>
							<label for="brd_id_<?php echo element('brd_id', $rval)?>" class="checkbox-inline"><input type="checkbox" name="brd_id[<?php echo element('brd_id', $rval)?>]" value="1" id="brd_id_<?php echo element('brd_id', $rval)?>" <?php echo set_checkbox('brd_id[' . element('brd_id', $rval) . ']', '1', (element('usenaverblog', $rval) ? true : false)); ?> /> <?php echo html_escape(element('brd_name', $rval)); ?></label>
					<?php
						}
					}
					?>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">네이버 블로그 회원아이디</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" name="naver_blog_userid" id="naver_blog_userid" value="<?php echo set_value('naver_blog_userid', element('naver_blog_userid', element('data', $view))); ?>" />
					<span class="help-inline">운영하는 네이버블로그 회원 아이디를 입력합니다.</span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">네이버 블로그 글쓰기 API</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" name="naver_blog_api_key" id="naver_blog_api_key" value="<?php echo set_value('naver_blog_api_key', element('naver_blog_api_key', element('data', $view))); ?>" />
					<span class="help-inline">네이버 블로그에 로그인하셔서 환경설정 > 플러그인. 연동관리 > 글쓰기 API 설정에 가셔서 API 연결 암호를 생성하신 후에 해당 암호를 입력합니다.</span>
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
