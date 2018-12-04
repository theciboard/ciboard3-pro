<div class="box">
	<div class="box-header">
		<h4 class="pb10 pull-left"><?php echo html_escape($this->board->item_id('brd_name', element('brd_id', element('data', $view)))); ?> <a href="<?php echo goto_url(board_url(html_escape($this->board->item_id('brd_key', element('brd_id', element('data', $view)))))); ?>" class="btn-xs" target="_blank"><span class="glyphicon glyphicon-new-window"></span></a></h4>
		<?php if (element('boardlist', $view)) { ?>
		<div class="pull-right">
			<select name="brd_id" class="form-control" onChange="location.href='<?php echo admin_url($this->pagedir . '/write_rss'); ?>/' + this.value;">
				<?php foreach (element('boardlist', $view) as $key => $value) { ?>
					<option value="<?php echo element('brd_id', $value); ?>" <?php echo set_select('brd_id', element('brd_id', $value), (element('brd_id', element('data', $view)) === element('brd_id', $value) ? true : false)); ?>><?php echo html_escape(element('brd_name', $value)); ?></option>
				<?php } ?>
			</select>
		</div>
		<?php } ?>
		<div class="clearfix"></div>
		<ul class="nav nav-tabs">
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/write/' . element('brd_id', element('data', $view))); ?>" onclick="return check_form_changed();">기본정보</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/write_list/' . element('brd_id', element('data', $view))); ?>" onclick="return check_form_changed();">목록페이지</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/write_post/' . element('brd_id', element('data', $view))); ?>" onclick="return check_form_changed();">게시물열람</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/write_write/' . element('brd_id', element('data', $view))); ?>" onclick="return check_form_changed();">게시물작성</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/write_category/' . element('brd_id', element('data', $view))); ?>" onclick="return check_form_changed();">카테고리</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/write_comment/' . element('brd_id', element('data', $view))); ?>" onclick="return check_form_changed();">댓글기능</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/write_general/' . element('brd_id', element('data', $view))); ?>" onclick="return check_form_changed();">일반기능</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/write_point/' . element('brd_id', element('data', $view))); ?>" onclick="return check_form_changed();">포인트기능</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/write_alarm/' . element('brd_id', element('data', $view))); ?>" onclick="return check_form_changed();">메일/쪽지/문자</a></li>
			<li role="presentation" class="active"><a href="<?php echo admin_url($this->pagedir . '/write_rss/' . element('brd_id', element('data', $view))); ?>" onclick="return check_form_changed();">RSS/사이트맵 설정</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/write_access/' . element('brd_id', element('data', $view))); ?>" onclick="return check_form_changed();">권한관리</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/write_extravars/' . element('brd_id', element('data', $view))); ?>" onclick="return check_form_changed();">사용자정의</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/write_admin/' . element('brd_id', element('data', $view))); ?>" onclick="return check_form_changed();">게시판관리자</a></li>
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
			<input type="hidden" name="<?php echo element('primary_key', $view); ?>"	value="<?php echo element(element('primary_key', $view), element('data', $view)); ?>" />
			<div class="form-group">
				<label class="col-sm-2 control-label">RSS 피드 사용</label>
				<div class="col-sm-8">
					<label for="use_rss_feed" class="checkbox-inline">
						<input type="checkbox" name="use_rss_feed" id="use_rss_feed" value="1" <?php echo set_checkbox('use_rss_feed', '1', (element('use_rss_feed', element('data', $view)) ? true : false)); ?> /> 사용합니다
					</label>
				</div>
				<div class="col-sm-2">
					<label for="grp_use_rss_feed" class="checkbox-inline">
						<input type="checkbox" name="grp[use_rss_feed]" id="grp_use_rss_feed" value="1" /> 그룹적용
					</label>
					<label for="all_use_rss_feed" class="checkbox-inline">
						<input type="checkbox" name="all[use_rss_feed]" id="all_use_rss_feed" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">통합 피드에 포함</label>
				<div class="col-sm-8">
					<label for="use_rss_total_feed" class="checkbox-inline">
						<input type="checkbox" name="use_rss_total_feed" id="use_rss_total_feed" value="1" <?php echo set_checkbox('use_rss_total_feed', '1', (element('use_rss_total_feed', element('data', $view)) ? true : false)); ?> /> 포함합니다
					</label>
				</div>
				<div class="col-sm-2">
					<label for="grp_use_rss_total_feed" class="checkbox-inline">
						<input type="checkbox" name="grp[use_rss_total_feed]" id="grp_use_rss_total_feed" value="1" /> 그룹적용
					</label>
					<label for="all_use_rss_total_feed" class="checkbox-inline">
						<input type="checkbox" name="all[use_rss_total_feed]" id="all_use_rss_total_feed" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">내용공개</label>
				<div class="col-sm-8 form-inline">
					<select name="rss_feed_content" class="form-control" >
						<option value="" <?php echo set_select('rss_feed_content', '', (element('rss_feed_content', element('data', $view)) === '' ? true : false)); ?>>공개하지 않음</option>
						<option value="1" <?php echo set_select('rss_feed_content', '1', (element('rss_feed_content', element('data', $view)) === '1' ? true : false)); ?>>HTML 태그 제외 공개</option>
						<option value="2" <?php echo set_select('rss_feed_content', '2', (element('rss_feed_content', element('data', $view)) === '2' ? true : false)); ?>>전부공개</option>
					</select>
					RSS 페이지에 본문 내용을 얼마나 공개할 것인지 설정합니다
				</div>
				<div class="col-sm-2">
					<label for="grp_rss_feed_content" class="checkbox-inline">
						<input type="checkbox" name="grp[rss_feed_content]" id="grp_rss_feed_content" value="1" /> 그룹적용
					</label>
					<label for="all_rss_feed_content" class="checkbox-inline">
						<input type="checkbox" name="all[rss_feed_content]" id="all_rss_feed_content" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">RSS 피드 설명</label>
				<div class="col-sm-8">
					<textarea class="form-control" rows="5" name="rss_feed_description"><?php echo set_value('rss_feed_description', element('rss_feed_description', element('data', $view))); ?></textarea>
				</div>
				<div class="col-sm-2">
					<label for="grp_rss_feed_description" class="checkbox-inline">
						<input type="checkbox" name="grp[rss_feed_description]" id="grp_rss_feed_description" value="1" /> 그룹적용
					</label>
					<label for="all_rss_feed_description" class="checkbox-inline">
						<input type="checkbox" name="all[rss_feed_description]" id="all_rss_feed_description" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">RSS 피드 저작권</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="rss_feed_copyright" value="<?php echo set_value('rss_feed_copyright', element('rss_feed_copyright', element('data', $view))); ?>" />
				</div>
				<div class="col-sm-2">
					<label for="grp_rss_feed_copyright" class="checkbox-inline">
						<input type="checkbox" name="grp[rss_feed_copyright]" id="grp_rss_feed_copyright" value="1" /> 그룹적용
					</label>
					<label for="all_rss_feed_copyright" class="checkbox-inline">
						<input type="checkbox" name="all[rss_feed_copyright]" id="all_rss_feed_copyright" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">RSS 출력 게시물수</label>
				<div class="col-sm-8">
					<input type="number" class="form-control" name="rss_feed_post_count" value="<?php echo set_value('rss_feed_post_count', (int) element('rss_feed_post_count', element('data', $view))); ?>" />
				</div>
				<div class="col-sm-2">
					<label for="grp_rss_feed_post_count" class="checkbox-inline">
						<input type="checkbox" name="grp[rss_feed_post_count]" id="grp_rss_feed_post_count" value="1" /> 그룹적용
					</label>
					<label for="all_rss_feed_post_count" class="checkbox-inline">
						<input type="checkbox" name="all[rss_feed_post_count]" id="all_rss_feed_post_count" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<hr />
			<div class="form-group">
				<label class="col-sm-2 control-label">사이트맵 사용</label>
				<div class="col-sm-8">
					<label for="use_sitemap" class="checkbox-inline">
						<input type="checkbox" name="use_sitemap" id="use_sitemap" value="1" <?php echo set_checkbox('use_sitemap', '1', (element('use_sitemap', element('data', $view)) ? true : false)); ?> /> 사용합니다
					</label>
				</div>
				<div class="col-sm-2">
					<label for="grp_use_sitemap" class="checkbox-inline">
						<input type="checkbox" name="grp[use_sitemap]" id="grp_use_sitemap" value="1" /> 그룹적용
					</label>
					<label for="all_use_sitemap" class="checkbox-inline">
						<input type="checkbox" name="all[use_sitemap]" id="all_use_sitemap" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<hr />
			<div class="form-group">
				<label class="col-sm-2 control-label">네이버 블로그 자동등록사용</label>
				<div class="col-sm-8">
					<label for="use_naver_blog_post" class="checkbox-inline">
						<input type="checkbox" name="use_naver_blog_post" id="use_naver_blog_post" value="1" <?php echo set_checkbox('use_naver_blog_post', '1', (element('use_naver_blog_post', element('data', $view)) ? true : false)); ?> /> 사용합니다
					</label>
					<div class="help-inline">
						해당 게시판의 글내용을 네이버블로그로 자동등록할지를 결정합니다.
					</div>
				</div>
				<div class="col-sm-2">
					<label for="grp_use_naver_blog_post" class="checkbox-inline">
						<input type="checkbox" name="grp[use_naver_blog_post]" id="grp_use_naver_blog_post" value="1" /> 그룹적용
					</label>
					<label for="all_use_naver_blog_post" class="checkbox-inline">
						<input type="checkbox" name="all[use_naver_blog_post]" id="all_use_naver_blog_post" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="btn-group pull-right" role="group" aria-label="...">
				<a href="<?php echo admin_url($this->pagedir); ?>" class="btn btn-default btn-sm">목록으로</a>
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
			rss_feed_description: {required :'#use_rss_feed:checked'},
			rss_feed_copyright: {required :'#use_rss_feed:checked'},
			rss_feed_post_count: {required :'#use_rss_feed:checked', number:true, min:0, max:1000}
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
