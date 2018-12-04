<div class="box">
	<div class="box-header">
		<h4 class="pb10 pull-left"><?php echo html_escape($this->board->item_id('brd_name', element('brd_id', element('data', $view)))); ?> <a href="<?php echo goto_url(board_url(html_escape($this->board->item_id('brd_key', element('brd_id', element('data', $view)))))); ?>" class="btn-xs" target="_blank"><span class="glyphicon glyphicon-new-window"></span></a></h4>
		<?php if (element('boardlist', $view)) { ?>
		<div class="pull-right">
			<select name="brd_id" class="form-control" onChange="location.href='<?php echo admin_url($this->pagedir . '/write_point'); ?>/' + this.value;">
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
			<li role="presentation" class="active"><a href="<?php echo admin_url($this->pagedir . '/write_point/' . element('brd_id', element('data', $view))); ?>" onclick="return check_form_changed();">포인트기능</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/write_alarm/' . element('brd_id', element('data', $view))); ?>" onclick="return check_form_changed();">메일/쪽지/문자</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/write_rss/' . element('brd_id', element('data', $view))); ?>" onclick="return check_form_changed();">RSS/사이트맵 설정</a></li>
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
				<label class="col-sm-2 control-label">포인트기능사용</label>
				<div class="col-sm-8">
					<label for="use_point" class="checkbox-inline">
						<input type="checkbox" name="use_point" id="use_point" value="1" <?php echo set_checkbox('use_point', '1', (element('use_point', element('data', $view)) ? true : false)); ?> /> 사용합니다
					</label>
				</div>
				<div class="col-sm-2">
					<label for="grp_use_point" class="checkbox-inline">
						<input type="checkbox" name="grp[use_point]" id="grp_use_point" value="1" /> 그룹적용
					</label>
					<label for="all_use_point" class="checkbox-inline">
						<input type="checkbox" name="all[use_point]" id="all_use_point" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">포인트안내</label>
				<div class="col-sm-8">
					<label for="use_point_info" class="checkbox-inline">
						<input type="checkbox" name="use_point_info" id="use_point_info" value="1" <?php echo set_checkbox('use_point_info', '1', (element('use_point_info', element('data', $view)) ? true : false)); ?> /> 사용합니다
					</label>
					<div class="help-inline">게시판 목록에 포인트점수에 대한 설명이 나옵니다</div>
				</div>
				<div class="col-sm-2">
					<label for="grp_use_point_info" class="checkbox-inline">
						<input type="checkbox" name="grp[use_point_info]" id="grp_use_point_info" value="1" /> 그룹적용
					</label>
					<label for="all_use_point_info" class="checkbox-inline">
						<input type="checkbox" name="all[use_point_info]" id="all_use_point_info" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">원글 작성</label>
				<div class="col-sm-8">
					<input type="number" class="form-control" name="point_write" value="<?php echo set_value('point_write', (int) element('point_write', element('data', $view))); ?>" />
				</div>
				<div class="col-sm-2">
					<label for="grp_point_write" class="checkbox-inline">
						<input type="checkbox" name="grp[point_write]" id="grp_point_write" value="1" /> 그룹적용
					</label>
					<label for="all_point_write" class="checkbox-inline">
						<input type="checkbox" name="all[point_write]" id="all_point_write" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">댓글 작성</label>
				<div class="col-sm-8">
					<input type="number" class="form-control" name="point_comment" value="<?php echo set_value('point_comment', (int) element('point_comment', element('data', $view))); ?>" />
				</div>
				<div class="col-sm-2">
					<label for="grp_point_comment" class="checkbox-inline">
						<input type="checkbox" name="grp[point_comment]" id="grp_point_comment" value="1" /> 그룹적용
					</label>
					<label for="all_point_comment" class="checkbox-inline">
						<input type="checkbox" name="all[point_comment]" id="all_point_comment" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">작성자 본인이 원글 삭제</label>
				<div class="col-sm-8">
					원글작성시 지급했던 포인트 회수 +
					<input type="number" class="form-control" name="point_post_delete" value="<?php echo set_value('point_post_delete', (int) element('point_post_delete', element('data', $view))); ?>" /> 포인트를 추가로 차감
					<span class="help-inline">추가로 차감하기 원하는 포인트를 양수로 입력해주세요</span>
				</div>
				<div class="col-sm-2">
					<label for="grp_point_post_delete" class="checkbox-inline">
						<input type="checkbox" name="grp[point_post_delete]" id="grp_point_post_delete" value="1" /> 그룹적용
					</label>
					<label for="all_point_post_delete" class="checkbox-inline">
						<input type="checkbox" name="all[point_post_delete]" id="all_point_post_delete" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">관리자가 원글 삭제</label>
				<div class="col-sm-8">
					원글작성시 지급했던 포인트 회수 +
					<input type="number" class="form-control" name="point_admin_post_delete" value="<?php echo set_value('point_admin_post_delete', (int) element('point_admin_post_delete', element('data', $view))); ?>" /> 포인트를 추가로 차감
					<span class="help-inline">관리자가 일반사용자의 글을 삭제시 해당 사용자에게 추가로 차감하기 원하는 포인트를 양수로 입력해주세요</span>
				</div>
				<div class="col-sm-2">
					<label for="grp_point_admin_post_delete" class="checkbox-inline">
						<input type="checkbox" name="grp[point_admin_post_delete]" id="grp_point_admin_post_delete" value="1" /> 그룹적용
					</label>
					<label for="all_point_admin_post_delete" class="checkbox-inline">
						<input type="checkbox" name="all[point_admin_post_delete]" id="all_point_admin_post_delete" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">작성자 본인이 댓글 삭제</label>
				<div class="col-sm-8">
					댓글작성시 지급했던 포인트 회수 +
					<input type="number" class="form-control" name="point_comment_delete" value="<?php echo set_value('point_comment_delete', (int) element('point_comment_delete', element('data', $view))); ?>" /> 포인트를 추가로 차감
					<span class="help-inline">추가로 차감하기 원하는 포인트를 양수로 입력해주세요</span>
				</div>
				<div class="col-sm-2">
					<label for="grp_point_comment_delete" class="checkbox-inline">
						<input type="checkbox" name="grp[point_comment_delete]" id="grp_point_comment_delete" value="1" /> 그룹적용
					</label>
					<label for="all_point_comment_delete" class="checkbox-inline">
						<input type="checkbox" name="all[point_comment_delete]" id="all_point_comment_delete" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">관리자가 댓글 삭제</label>
				<div class="col-sm-8">
					댓글작성시 지급했던 포인트 회수 +
					<input type="number" class="form-control" name="point_admin_comment_delete" value="<?php echo set_value('point_admin_comment_delete', (int) element('point_admin_comment_delete', element('data', $view))); ?>" /> 포인트를 추가로 차감
					<span class="help-inline">관리자가 일반사용자의 글을 삭제시 해당 사용자에게 추가로 차감하기 원하는 포인트를 양수로 입력해주세요</span>
				</div>
				<div class="col-sm-2">
					<label for="grp_point_admin_comment_delete" class="checkbox-inline">
						<input type="checkbox" name="grp[point_admin_comment_delete]" id="grp_point_admin_comment_delete" value="1" /> 그룹적용
					</label>
					<label for="all_point_admin_comment_delete" class="checkbox-inline">
						<input type="checkbox" name="all[point_admin_comment_delete]" id="all_point_admin_comment_delete" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">파일업로드</label>
				<div class="col-sm-8">
					<input type="number" class="form-control" name="point_fileupload" value="<?php echo set_value('point_fileupload', (int) element('point_fileupload', element('data', $view))); ?>" /> <span class="help-inline">파일 업로드시 1회</span>
				</div>
				<div class="col-sm-2">
					<label for="grp_point_fileupload" class="checkbox-inline">
						<input type="checkbox" name="grp[point_fileupload]" id="grp_point_fileupload" value="1" /> 그룹적용
					</label>
					<label for="all_point_fileupload" class="checkbox-inline">
						<input type="checkbox" name="all[point_fileupload]" id="all_point_fileupload" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">파일 다운로드</label>
				<div class="col-sm-8">
					<input type="number" class="form-control" name="point_filedownload" value="<?php echo set_value('point_filedownload', (int) element('point_filedownload', element('data', $view))); ?>" /> <span class="help-inline">파일 다운로드한 사람에게 1회 적용됩니다</span>
				</div>
				<div class="col-sm-2">
					<label for="grp_point_filedownload" class="checkbox-inline">
						<input type="checkbox" name="grp[point_filedownload]" id="grp_point_filedownload" value="1" /> 그룹적용
					</label>
					<label for="all_point_filedownload" class="checkbox-inline">
						<input type="checkbox" name="all[point_filedownload]" id="all_point_filedownload" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">파일다운로드시업로더에게</label>
				<div class="col-sm-8">
					<input type="number" class="form-control" name="point_filedownload_uploader" value="<?php echo set_value('point_filedownload_uploader', (int) element('point_filedownload_uploader', element('data', $view))); ?>" /> <span class="help-inline">파일을 다운로드 할 때마다 파일을 업로드한 사람에게 매번 포인트가 지급됩니다</span>
				</div>
				<div class="col-sm-2">
					<label for="grp_point_filedownload_uploader" class="checkbox-inline">
						<input type="checkbox" name="grp[point_filedownload_uploader]" id="grp_point_filedownload_uploader" value="1" /> 그룹적용
					</label>
					<label for="all_point_filedownload_uploader" class="checkbox-inline">
						<input type="checkbox" name="all[point_filedownload_uploader]" id="all_point_filedownload_uploader" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">게시글 조회</label>
				<div class="col-sm-8">
					<input type="number" class="form-control" name="point_read" value="<?php echo set_value('point_read', (int) element('point_read', element('data', $view))); ?>" />
				</div>
				<div class="col-sm-2">
					<label for="grp_point_read" class="checkbox-inline">
						<input type="checkbox" name="grp[point_read]" id="grp_point_read" value="1" /> 그룹적용
					</label>
					<label for="all_point_read" class="checkbox-inline">
						<input type="checkbox" name="all[point_read]" id="all_point_read" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">원글 추천함</label>
				<div class="col-sm-8">
					<input type="number" class="form-control" name="point_post_like" value="<?php echo set_value('point_post_like', (int) element('point_post_like', element('data', $view))); ?>" />
				</div>
				<div class="col-sm-2">
					<label for="grp_point_post_like" class="checkbox-inline">
						<input type="checkbox" name="grp[point_post_like]" id="grp_point_post_like" value="1" /> 그룹적용
					</label>
					<label for="all_point_post_like" class="checkbox-inline">
						<input type="checkbox" name="all[point_post_like]" id="all_point_post_like" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">원글 비추천함</label>
				<div class="col-sm-8">
					<input type="number" class="form-control" name="point_post_dislike" value="<?php echo set_value('point_post_dislike', (int) element('point_post_dislike', element('data', $view))); ?>" />
				</div>
				<div class="col-sm-2">
					<label for="grp_point_post_dislike" class="checkbox-inline">
						<input type="checkbox" name="grp[point_post_dislike]" id="grp_point_post_dislike" value="1" /> 그룹적용
					</label>
					<label for="all_point_post_dislike" class="checkbox-inline">
						<input type="checkbox" name="all[point_post_dislike]" id="all_point_post_dislike" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">원글 추천받음</label>
				<div class="col-sm-8">
					<input type="number" class="form-control" name="point_post_liked" value="<?php echo set_value('point_post_liked', (int) element('point_post_liked', element('data', $view))); ?>" />
					<span class="help-inline">원글 작성자에게 지급되는 포인트입니다</span>
				</div>
				<div class="col-sm-2">
					<label for="grp_point_post_liked" class="checkbox-inline">
						<input type="checkbox" name="grp[point_post_liked]" id="grp_point_post_liked" value="1" /> 그룹적용
					</label>
					<label for="all_point_post_liked" class="checkbox-inline">
						<input type="checkbox" name="all[point_post_liked]" id="all_point_post_liked" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">원글 비추천받음</label>
				<div class="col-sm-8">
					<input type="number" class="form-control" name="point_post_disliked" value="<?php echo set_value('point_post_disliked', (int) element('point_post_disliked', element('data', $view))); ?>" />
					<span class="help-inline">원글 작성자에게 지급되는 포인트입니다</span>
				</div>
				<div class="col-sm-2">
					<label for="grp_point_post_disliked" class="checkbox-inline">
						<input type="checkbox" name="grp[point_post_disliked]" id="grp_point_post_disliked" value="1" /> 그룹적용
					</label>
					<label for="all_point_post_disliked" class="checkbox-inline">
						<input type="checkbox" name="all[point_post_disliked]" id="all_point_post_disliked" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">댓글 추천함</label>
				<div class="col-sm-8">
					<input type="number" class="form-control" name="point_comment_like" value="<?php echo set_value('point_comment_like', (int) element('point_comment_like', element('data', $view))); ?>" />
				</div>
				<div class="col-sm-2">
					<label for="grp_point_comment_like" class="checkbox-inline">
						<input type="checkbox" name="grp[point_comment_like]" id="grp_point_comment_like" value="1" /> 그룹적용
					</label>
					<label for="all_point_comment_like" class="checkbox-inline">
						<input type="checkbox" name="all[point_comment_like]" id="all_point_comment_like" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">댓글 비추천함</label>
				<div class="col-sm-8">
					<input type="number" class="form-control" name="point_comment_dislike" value="<?php echo set_value('point_comment_dislike', (int) element('point_comment_dislike', element('data', $view))); ?>" />
				</div>
				<div class="col-sm-2">
					<label for="grp_point_comment_dislike" class="checkbox-inline">
						<input type="checkbox" name="grp[point_comment_dislike]" id="grp_point_comment_dislike" value="1" /> 그룹적용
					</label>
					<label for="all_point_comment_dislike" class="checkbox-inline">
						<input type="checkbox" name="all[point_comment_dislike]" id="all_point_comment_dislike" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">댓글 추천받음</label>
				<div class="col-sm-8">
					<input type="number" class="form-control" name="point_comment_liked" value="<?php echo set_value('point_comment_liked', (int) element('point_comment_liked', element('data', $view))); ?>" />
					<span class="help-inline">댓글 작성자에게 지급되는 포인트입니다</span>
				</div>
				<div class="col-sm-2">
					<label for="grp_point_comment_liked" class="checkbox-inline">
						<input type="checkbox" name="grp[point_comment_liked]" id="grp_point_comment_liked" value="1" /> 그룹적용
					</label>
					<label for="all_point_comment_liked" class="checkbox-inline">
						<input type="checkbox" name="all[point_comment_liked]" id="all_point_comment_liked" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">댓글 비추천받음</label>
				<div class="col-sm-8">
					<input type="number" class="form-control" name="point_comment_disliked" value="<?php echo set_value('point_comment_disliked', (int) element('point_comment_disliked', element('data', $view))); ?>" />
					<span class="help-inline">댓글 작성자에게 지급되는 포인트입니다</span>
				</div>
				<div class="col-sm-2">
					<label for="grp_point_comment_disliked" class="checkbox-inline">
						<input type="checkbox" name="grp[point_comment_disliked]" id="grp_point_comment_disliked" value="1" /> 그룹적용
					</label>
					<label for="all_point_comment_disliked" class="checkbox-inline">
						<input type="checkbox" name="all[point_comment_disliked]" id="all_point_comment_disliked" value="1" /> 전체적용
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
			point_write: {required :true, number:true },
			point_comment: {required :true, number:true },
			point_post_delete: {required :true, number:true },
			point_admin_post_delete: {required :true, number:true },
			point_comment_delete: {required :true, number:true },
			point_admin_comment_delete: {required :true, number:true },
			point_fileupload: {required :true, number:true },
			point_filedownload: {required :true, number:true },
			point_filedownload_uploader: {required :true, number:true },
			point_read: {required :true, number:true },
			point_post_like: {required :true, number:true },
			point_post_dislike: {required :true, number:true },
			point_post_liked: {required :true, number:true },
			point_post_disliked: {required :true, number:true },
			point_comment_like: {required :true, number:true },
			point_comment_dislike: {required :true, number:true },
			point_comment_liked: {required :true, number:true },
			point_comment_disliked: {required :true, number:true }
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
