<div class="box">
	<div class="box-header">
		<h4 class="pb10 pull-left"><?php echo html_escape($this->board->item_id('brd_name', element('brd_id', element('data', $view)))); ?> <a href="<?php echo goto_url(board_url(html_escape($this->board->item_id('brd_key', element('brd_id', element('data', $view)))))); ?>" class="btn-xs" target="_blank"><span class="glyphicon glyphicon-new-window"></span></a></h4>
		<?php if (element('boardlist', $view)) { ?>
		<div class="pull-right">
			<select name="brd_id" class="form-control" onChange="location.href='<?php echo admin_url($this->pagedir . '/write_comment'); ?>/' + this.value;">
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
			<li role="presentation" class="active"><a href="<?php echo admin_url($this->pagedir . '/write_comment/' . element('brd_id', element('data', $view))); ?>" onclick="return check_form_changed();">댓글기능</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/write_general/' . element('brd_id', element('data', $view))); ?>" onclick="return check_form_changed();">일반기능</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/write_point/' . element('brd_id', element('data', $view))); ?>" onclick="return check_form_changed();">포인트기능</a></li>
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
				<label class="col-sm-2 control-label">댓글 목록수</label>
				<div class="col-sm-8">
					<input type="number" class="form-control" name="comment_count" value="<?php echo set_value('comment_count', (int) element('comment_count', element('data', $view))); ?>" />개 댓글마다 페이지 넘김, 0이면 페이징 기능 사용하지 않음
				</div>
				<div class="col-sm-2">
					<label for="grp_comment_count" class="checkbox-inline">
						<input type="checkbox" name="grp[comment_count]" id="grp_comment_count" value="1" /> 그룹적용
					</label>
					<label for="all_comment_count" class="checkbox-inline">
						<input type="checkbox" name="all[comment_count]" id="all_comment_count" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">모바일 댓글 목록수</label>
				<div class="col-sm-8">
					<input type="number" class="form-control" name="mobile_comment_count" value="<?php echo set_value('mobile_comment_count', (int) element('mobile_comment_count', element('data', $view))); ?>" />개 댓글마다 페이지 넘김, 0이면 페이징 기능 사용하지 않음
				</div>
				<div class="col-sm-2">
					<label for="grp_mobile_comment_count" class="checkbox-inline">
						<input type="checkbox" name="grp[mobile_comment_count]" id="grp_mobile_comment_count" value="1" /> 그룹적용
					</label>
					<label for="all_mobile_comment_count" class="checkbox-inline">
						<input type="checkbox" name="all[mobile_comment_count]" id="all_mobile_comment_count" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">댓글 페이지수</label>
				<div class="col-sm-8">
					<input type="number" class="form-control" name="comment_page_count" value="<?php echo set_value('comment_page_count', (int) element('comment_page_count', element('data', $view))); ?>" />댓글하단, 페이지를 이동하는 링크 수를 지정할 수 있습니다
				</div>
				<div class="col-sm-2">
					<label for="grp_comment_count" class="checkbox-inline">
						<input type="checkbox" name="grp[comment_page_count]" id="grp_comment_count" value="1" /> 그룹적용
					</label>
					<label for="all_comment_count" class="checkbox-inline">
						<input type="checkbox" name="all[comment_page_count]" id="all_comment_count" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">모바일 댓글 페이지수</label>
				<div class="col-sm-8">
					<input type="number" class="form-control" name="mobile_comment_page_count" value="<?php echo set_value('mobile_comment_page_count', (int) element('mobile_comment_page_count', element('data', $view))); ?>" />개 댓글마다 페이지 넘김, 0이면 페이징 기능 사용하지 않음
				</div>
				<div class="col-sm-2">
					<label for="grp_mobile_comment_count" class="checkbox-inline">
						<input type="checkbox" name="grp[mobile_comment_page_count]" id="grp_mobile_comment_count" value="1" /> 그룹적용
					</label>
					<label for="all_mobile_comment_count" class="checkbox-inline">
						<input type="checkbox" name="all[mobile_comment_page_count]" id="all_mobile_comment_count" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">댓글 추천기능</label>
				<div class="col-sm-8">
					<label for="use_comment_like" class="checkbox-inline">
						<input type="checkbox" name="use_comment_like" id="use_comment_like" value="1" <?php echo set_checkbox('use_comment_like', '1', (element('use_comment_like', element('data', $view)) ? true : false)); ?> /> 사용합니다
					</label>
				</div>
				<div class="col-sm-2">
					<label for="grp_use_comment_like" class="checkbox-inline">
						<input type="checkbox" name="grp[use_comment_like]" id="grp_use_comment_like" value="1" /> 그룹적용
					</label>
					<label for="all_use_comment_like" class="checkbox-inline">
						<input type="checkbox" name="all[use_comment_like]" id="all_use_comment_like" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">댓글 비추천 기능</label>
				<div class="col-sm-8">
					<label for="use_comment_dislike" class="checkbox-inline">
						<input type="checkbox" name="use_comment_dislike" id="use_comment_dislike" value="1" <?php echo set_checkbox('use_comment_dislike', '1', (element('use_comment_dislike', element('data', $view)) ? true : false)); ?> /> 사용합니다
					</label>
				</div>
				<div class="col-sm-2">
					<label for="grp_use_comment_dislike" class="checkbox-inline">
						<input type="checkbox" name="grp[use_comment_dislike]" id="grp_use_comment_dislike" value="1" /> 그룹적용
					</label>
					<label for="all_use_comment_dislike" class="checkbox-inline">
						<input type="checkbox" name="all[use_comment_dislike]" id="all_use_comment_dislike" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">프로필사진 출력여부</label>
				<div class="col-sm-8">
					<label for="use_comment_profile" class="checkbox-inline">
						<input type="checkbox" name="use_comment_profile" id="use_comment_profile" value="1" <?php echo set_checkbox('use_comment_profile', '1', (element('use_comment_profile', element('data', $view)) ? true : false)); ?> /> PC
					</label>
					<label for="use_mobile_comment_profile" class="checkbox-inline">
						<input type="checkbox" name="use_mobile_comment_profile" id="use_mobile_comment_profile" value="1" <?php echo set_checkbox('use_mobile_comment_profile', '1', (element('use_mobile_comment_profile', element('data', $view)) ? true : false)); ?> /> 모바일
					</label>
					<span class="help-inline">작성된 댓글 목록의 좌측에 작성자가 올린 프로필이미지를 보여줄지를 결정합니다</span>
				</div>
				<div class="col-sm-2">
					<label for="grp_use_comment_profile" class="checkbox-inline">
						<input type="checkbox" name="grp[use_comment_profile]" id="grp_use_comment_profile" value="1" /> 그룹적용
					</label>
					<label for="all_use_comment_profile" class="checkbox-inline">
						<input type="checkbox" name="all[use_comment_profile]" id="all_use_comment_profile" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">댓글 입력시 이모티콘</label>
				<div class="col-sm-8">
					<label for="use_comment_emoticon" class="checkbox-inline">
						<input type="checkbox" name="use_comment_emoticon" id="use_comment_emoticon" value="1" <?php echo set_checkbox('use_comment_emoticon', '1', (element('use_comment_emoticon', element('data', $view)) ? true : false)); ?> /> PC
					</label>
					<label for="use_mobile_comment_profile" class="checkbox-inline">
						<input type="checkbox" name="use_mobile_comment_profile" id="use_mobile_comment_profile" value="1" <?php echo set_checkbox('use_mobile_comment_profile', '1', (element('use_mobile_comment_profile', element('data', $view)) ? true : false)); ?> /> 모바일
					</label>
				</div>
				<div class="col-sm-2">
					<label for="grp_use_comment_emoticon" class="checkbox-inline">
						<input type="checkbox" name="grp[use_comment_emoticon]" id="grp_use_comment_emoticon" value="1" /> 그룹적용
					</label>
					<label for="all_use_comment_emoticon" class="checkbox-inline">
						<input type="checkbox" name="all[use_comment_emoticon]" id="all_use_comment_emoticon" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">댓글 입력시 특수문자</label>
				<div class="col-sm-8">
					<label for="use_comment_specialchars" class="checkbox-inline">
						<input type="checkbox" name="use_comment_specialchars" id="use_comment_specialchars" value="1" <?php echo set_checkbox('use_comment_specialchars', '1', (element('use_comment_specialchars', element('data', $view)) ? true : false)); ?> /> PC
					</label>
					<label for="use_mobile_comment_specialchars" class="checkbox-inline">
						<input type="checkbox" name="use_mobile_comment_specialchars" id="use_mobile_comment_specialchars" value="1" <?php echo set_checkbox('use_mobile_comment_specialchars', '1', (element('use_mobile_comment_specialchars', element('data', $view)) ? true : false)); ?> /> 모바일
					</label>
				</div>
				<div class="col-sm-2">
					<label for="grp_use_comment_specialchars" class="checkbox-inline">
						<input type="checkbox" name="grp[use_comment_specialchars]" id="grp_use_comment_specialchars" value="1" /> 그룹적용
					</label>
					<label for="all_use_comment_specialchars" class="checkbox-inline">
						<input type="checkbox" name="all[use_comment_specialchars]" id="all_use_comment_specialchars" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">댓글 금지 기간</label>
				<div class="col-sm-8">
					<input type="number" class="form-control" name="comment_possible_day" value="<?php echo set_value('comment_possible_day', (int) element('comment_possible_day', element('data', $view))); ?>" />일 이내의 게시물에만 댓글 허용합니다.
					<span class="help-inline">0 이면 항상 댓글 허용, 관리자는 항상 댓글 입력이 가능합니다</span>
				</div>
				<div class="col-sm-2">
					<label for="grp_comment_possible_day" class="checkbox-inline">
						<input type="checkbox" name="grp[comment_possible_day]" id="grp_comment_possible_day" value="1" /> 그룹적용
					</label>
					<label for="all_comment_possible_day" class="checkbox-inline">
						<input type="checkbox" name="all[comment_possible_day]" id="all_comment_possible_day" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">댓글 입력창 항상 출력</label>
				<div class="col-sm-8">
					<label for="always_show_comment_textarea" class="checkbox-inline">
						<input type="checkbox" name="always_show_comment_textarea" id="always_show_comment_textarea" value="1" <?php echo set_checkbox('always_show_comment_textarea', '1', (element('always_show_comment_textarea', element('data', $view)) ? true : false)); ?> /> PC.
					</label>
					<label for="mobile_always_show_comment_textarea" class="checkbox-inline">
						<input type="checkbox" name="mobile_always_show_comment_textarea" id="mobile_always_show_comment_textarea" value="1" <?php echo set_checkbox('mobile_always_show_comment_textarea', '1', (element('mobile_always_show_comment_textarea', element('data', $view)) ? true : false)); ?> /> 모바일
					</label>
					<span class="help-inline">권한이 없는 사용자라도 댓글 입력창은 항상 보입니다</span>
				</div>
				<div class="col-sm-2">
					<label for="grp_always_show_comment_textarea" class="checkbox-inline">
						<input type="checkbox" name="grp[always_show_comment_textarea]" id="grp_always_show_comment_textarea" value="1" /> 그룹적용
					</label>
					<label for="all_always_show_comment_textarea" class="checkbox-inline">
						<input type="checkbox" name="all[always_show_comment_textarea]" id="all_always_show_comment_textarea" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">댓글 베플 출력</label>
				<div class="col-sm-8">
					PC - <input type="number" class="form-control" name="comment_best" value="<?php echo set_value('comment_best', (int) element('comment_best', element('data', $view))); ?>" />개 출력,
					모바일 - <input type="number" class="form-control" name="mobile_comment_best" value="<?php echo set_value('mobile_comment_best', (int) element('mobile_comment_best', element('data', $view))); ?>" />개 출력<br />
					추천수가 <input type="number" class="form-control" name="comment_best_like_num" value="<?php echo set_value('comment_best_like_num', (int) element('comment_best_like_num', element('data', $view))); ?>" />개 이상인 댓글중 추천이 많은 순으로 출력됩니다
				</div>
				<div class="col-sm-2">
					<label for="grp_comment_best" class="checkbox-inline">
						<input type="checkbox" name="grp[comment_best]" id="grp_comment_best" value="1" /> 그룹적용
					</label>
					<label for="all_comment_best" class="checkbox-inline">
						<input type="checkbox" name="all[comment_best]" id="all_comment_best" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">댓글 기본 내용 (PC)</label>
				<div class="col-sm-8">
					<textarea class="form-control" rows="5" name="comment_default_content"><?php echo set_value('comment_default_content', element('comment_default_content', element('data', $view))); ?></textarea>
				</div>
				<div class="col-sm-2">
					<label for="grp_comment_default_content" class="checkbox-inline">
						<input type="checkbox" name="grp[comment_default_content]" id="grp_comment_default_content" value="1" /> 그룹적용
					</label>
					<label for="all_comment_default_content" class="checkbox-inline">
						<input type="checkbox" name="all[comment_default_content]" id="all_comment_default_content" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">댓글 기본 내용 (모바일)</label>
				<div class="col-sm-8">
					<textarea class="form-control" rows="5" name="mobile_comment_default_content"><?php echo set_value('mobile_comment_default_content', element('mobile_comment_default_content', element('data', $view))); ?></textarea>
				</div>
				<div class="col-sm-2">
					<label for="grp_mobile_comment_default_content" class="checkbox-inline">
						<input type="checkbox" name="grp[mobile_comment_default_content]" id="grp_mobile_comment_default_content" value="1" /> 그룹적용
					</label>
					<label for="all_mobile_comment_default_content" class="checkbox-inline">
						<input type="checkbox" name="all[mobile_comment_default_content]" id="all_mobile_comment_default_content" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">최소 댓글 글수 제한</label>
				<div class="col-sm-8">
					<input type="number" class="form-control" name="comment_min_length" value="<?php echo set_value('comment_min_length', (int) element('comment_min_length', element('data', $view))); ?>" />글자 이상 작성하셔야 합니다.
					<span class="help-inline">0 입력시 제한 없음, 에디터 사용시 적용 안됨</span>
				</div>
				<div class="col-sm-2">
					<label for="grp_comment_min_length" class="checkbox-inline">
						<input type="checkbox" name="grp[comment_min_length]" id="grp_comment_min_length" value="1" /> 그룹적용
					</label>
					<label for="all_comment_min_length" class="checkbox-inline">
						<input type="checkbox" name="all[comment_min_length]" id="all_comment_min_length" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">최대 댓글 글수 제한</label>
				<div class="col-sm-8">
					<input type="number" class="form-control" name="comment_max_length" value="<?php echo set_value('comment_max_length', (int) element('comment_max_length', element('data', $view))); ?>" />글자 이하 작성하셔야 합니다.
					<span class="help-inline">0 입력시 제한 없음, 에디터 사용시 적용 안됨</span>
				</div>
				<div class="col-sm-2">
					<label for="grp_comment_max_length" class="checkbox-inline">
						<input type="checkbox" name="grp[comment_max_length]" id="grp_comment_max_length" value="1" /> 그룹적용
					</label>
					<label for="all_comment_max_length" class="checkbox-inline">
						<input type="checkbox" name="all[comment_max_length]" id="all_comment_max_length" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">댓글 비밀글</label>
				<div class="col-sm-8 form-inline">
					<select name="use_comment_secret" class="form-control" >
						<option value="" <?php echo set_select('use_comment_secret', '', (element('use_comment_secret', element('data', $view)) === '' ? true : false)); ?>>사용하지 않음</option>
						<option value="1" <?php echo set_select('use_comment_secret', '1', (element('use_comment_secret', element('data', $view)) === '1' ? true : false)); ?>>선택사용</option>
						<option value="2" <?php echo set_select('use_comment_secret', '2', (element('use_comment_secret', element('data', $view)) === '2' ? true : false)); ?>>항상비밀글</option>
					</select>
					<span class="help-inline">비밀글은 작성자 본인과 게시판 관리자 이상만 열람 가능합니다</span>
				</div>
				<div class="col-sm-2">
					<label for="grp_use_comment_secret" class="checkbox-inline">
						<input type="checkbox" name="grp[use_comment_secret]" id="grp_use_comment_secret" value="1" /> 그룹적용
					</label>
					<label for="all_use_comment_secret" class="checkbox-inline">
						<input type="checkbox" name="all[use_comment_secret]" id="all_use_comment_secret" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">댓글 비밀글 기본 선택</label>
				<div class="col-sm-8">
					<label for="use_comment_secret_selected" class="checkbox-inline">
						<input type="checkbox" name="use_comment_secret_selected" id="use_comment_secret_selected" value="1" <?php echo set_checkbox('use_comment_secret_selected', '1', (element('use_comment_secret_selected', element('data', $view)) ? true : false)); ?> /> 사용
					</label>
					<span class="help-inline">비밀글 선택사용 가능 게시판에서 글쓰기시 비밀글 옵션 부분이 기본으로 체크되어있습니다</span>
				</div>
				<div class="col-sm-2">
					<label for="grp_use_comment_secret_selected" class="checkbox-inline">
						<input type="checkbox" name="grp[use_comment_secret_selected]" id="grp_use_comment_secret_selected" value="1" /> 그룹적용
					</label>
					<label for="all_use_comment_secret_selected" class="checkbox-inline">
						<input type="checkbox" name="all[use_comment_secret_selected]" id="all_use_comment_secret_selected" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">댓글작성자 IP 보이기</label>
				<div class="col-sm-8 form-inline">
					PC - <select name="show_comment_ip" class="form-control" >
						<option value="" <?php echo set_select('show_comment_ip', '', (element('show_comment_ip', element('data', $view)) === '' ? true : false)); ?>>공개하지 않음</option>
						<option value="1" <?php echo set_select('show_comment_ip', '1', (element('show_comment_ip', element('data', $view)) === '1' ? true : false)); ?>>일부 공개(기본환경설정에 정한방법)</option>
						<option value="2" <?php echo set_select('show_comment_ip', '2', (element('show_comment_ip', element('data', $view)) === '2' ? true : false)); ?>>전체 공개</option>
					</select>,
					모바일 - <select name="show_mobile_comment_ip" class="form-control" >
						<option value="" <?php echo set_select('show_mobile_comment_ip', '', (element('show_mobile_comment_ip', element('data', $view)) === '' ? true : false)); ?>>공개하지 않음</option>
						<option value="1" <?php echo set_select('show_mobile_comment_ip', '1', (element('show_mobile_comment_ip', element('data', $view)) === '1' ? true : false)); ?>>일부 공개(기본환경설정에 정한방법)</option>
						<option value="2" <?php echo set_select('show_mobile_comment_ip', '2', (element('show_mobile_comment_ip', element('data', $view)) === '2' ? true : false)); ?>>전체 공개</option>
					</select>
					<span class="help-block">관리자에게는 IP 가 항상 보입니다</span>
				</div>
				<div class="col-sm-2">
					<label for="grp_show_comment_ip" class="checkbox-inline">
						<input type="checkbox" name="grp[show_comment_ip]" id="grp_show_comment_ip" value="1" /> 그룹적용
					</label>
					<label for="all_show_comment_ip" class="checkbox-inline">
						<input type="checkbox" name="all[show_comment_ip]" id="all_show_comment_ip" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">공지글에 댓글 금지</label>
				<div class="col-sm-8">
					<label for="notice_comment_block" class="checkbox-inline">
						<input type="checkbox" name="notice_comment_block" id="notice_comment_block" value="1" <?php echo set_checkbox('notice_comment_block', '1', (element('notice_comment_block', element('data', $view)) ? true : false)); ?> /> 사용합니다
					</label>
					<span class="help-inline">공지글에는 댓글이 나오지 않습니다</span>
				</div>
				<div class="col-sm-2">
					<label for="grp_notice_comment_block" class="checkbox-inline">
						<input type="checkbox" name="grp[notice_comment_block]" id="grp_notice_comment_block" value="1" /> 그룹적용
					</label>
					<label for="all_notice_comment_block" class="checkbox-inline">
						<input type="checkbox" name="all[notice_comment_block]" id="all_notice_comment_block" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">댓글 정렬 순서</label>
				<div class="col-sm-8 form-inline">
					<select name="comment_order" class="form-control" >
						<option value="asc" <?php echo set_select('comment_order', 'asc', (element('comment_order', element('data', $view)) !== 'desc' ? true : false)); ?>>나중에 단 댓글 나중에 출력</option>
						<option value="desc" <?php echo set_select('comment_order', 'desc', (element('comment_order', element('data', $view)) === 'desc' ? true : false)); ?>>나중에 단 댓글 먼저 출력</option>
					</select>
				</div>
				<div class="col-sm-2">
					<label for="grp_comment_order" class="checkbox-inline">
						<input type="checkbox" name="grp[comment_order]" id="grp_comment_order" value="1" /> 그룹적용
					</label>
					<label for="all_comment_order" class="checkbox-inline">
						<input type="checkbox" name="all[comment_order]" id="all_comment_order" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">댓글 신고기능</label>
				<div class="col-sm-8">
					<label for="use_comment_blame" class="checkbox-inline">
						<input type="checkbox" name="use_comment_blame" id="use_comment_blame" value="1" <?php echo set_checkbox('use_comment_blame', '1', (element('use_comment_blame', element('data', $view)) ? true : false)); ?> /> 사용합니다
					</label>
				</div>
				<div class="col-sm-2">
					<label for="grp_use_comment_blame" class="checkbox-inline">
						<input type="checkbox" name="grp[use_comment_blame]" id="grp_use_comment_blame" value="1" /> 그룹적용
					</label>
					<label for="all_use_comment_blame" class="checkbox-inline">
						<input type="checkbox" name="all[use_comment_blame]" id="all_use_comment_blame" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">댓글신고시 블라인드</label>
				<div class="col-sm-8">
					<input type="number" class="form-control" name="comment_blame_blind_count" value="<?php echo set_value('comment_blame_blind_count', (int) element('comment_blame_blind_count', element('data', $view))); ?>" />회
					<span class="help-inline">해당 회수 이상 신고가 발생하면 댓글을 블라인드 처리합니다. 블라인드된 댓글은 관리자와 본인만 열람이 가능합니다.</span>
				</div>
				<div class="col-sm-2">
					<label for="grp_comment_blame_blind_count" class="checkbox-inline">
						<input type="checkbox" name="grp[comment_blame_blind_count]" id="grp_comment_blame_blind_count" value="1" /> 그룹적용
					</label>
					<label for="all_comment_blame_blind_count" class="checkbox-inline">
						<input type="checkbox" name="all[comment_blame_blind_count]" id="all_comment_blame_blind_count" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">댓글 럭키포인트</label>
				<div class="col-sm-8 form-inline">
					<label for="use_comment_lucky" class="checkbox-inline">
						<input type="checkbox" name="use_comment_lucky" id="use_comment_lucky" value="1" <?php echo set_checkbox('use_comment_lucky', '1', (element('use_comment_lucky', element('data', $view)) ? true : false)); ?> /> 사용합니다
					</label>
					<span class="help-inline">댓글을 입력하신 분들에게 무작위로 보너스 포인트를 드립니다.</span>
				</div>
				<div class="col-sm-2">
					<label for="grp_use_comment_lucky" class="checkbox-inline">
						<input type="checkbox" name="grp[use_comment_lucky]" id="grp_use_comment_lucky" value="1" /> 그룹적용
					</label>
					<label for="all_use_comment_lucky" class="checkbox-inline">
						<input type="checkbox" name="all[use_comment_lucky]" id="all_use_comment_lucky" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">럭키포인트 이름</label>
				<div class="col-sm-8 form-inline">
					<input type="text" class="form-control" name="comment_lucky_name" value="<?php echo set_value('comment_lucky_name', element('comment_lucky_name', element('data', $view))); ?>" />
				</div>
				<div class="col-sm-2">
					<label for="grp_comment_lucky_name" class="checkbox-inline">
						<input type="checkbox" name="grp[comment_lucky_name]" id="grp_comment_lucky_name" value="1" /> 그룹적용
					</label>
					<label for="all_comment_lucky_name" class="checkbox-inline">
						<input type="checkbox" name="all[comment_lucky_name]" id="all_comment_lucky_name" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">럭키포인트 당첨확률</label>
				<div class="col-sm-8">
					<input type="number" class="form-control" name="comment_lucky_percent" value="<?php echo set_value('comment_lucky_percent', element('comment_lucky_percent', element('data', $view))); ?>" />%
				</div>
				<div class="col-sm-2">
					<label for="grp_comment_lucky_percent" class="checkbox-inline">
						<input type="checkbox" name="grp[comment_lucky_percent]" id="grp_comment_lucky_percent" value="1" /> 그룹적용
					</label>
					<label for="all_comment_lucky_percent" class="checkbox-inline">
						<input type="checkbox" name="all[comment_lucky_percent]" id="all_comment_lucky_percent" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">럭키포인트 지급포인트</label>
				<div class="col-sm-8">
					<input type="number" class="form-control" name="comment_lucky_point_min" value="<?php echo set_value('comment_lucky_point_min', element('comment_lucky_point_min', element('data', $view))); ?>" /> ~ <input type="number" class="form-control" name="comment_lucky_point_max" value="<?php echo set_value('comment_lucky_point_max', element('comment_lucky_point_max', element('data', $view))); ?>" />점
				</div>
				<div class="col-sm-2">
					<label for="grp_comment_lucky_point_min" class="checkbox-inline">
						<input type="checkbox" name="grp[comment_lucky_point_min]" id="grp_comment_lucky_point_min" value="1" /> 그룹적용
					</label>
					<label for="all_comment_lucky_point_min" class="checkbox-inline">
						<input type="checkbox" name="all[comment_lucky_point_min]" id="all_comment_lucky_point_min" value="1" /> 전체적용
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
			comment_page: {required :true, number:true },
			comment_possible_day: {required :true, number:true },
			comment_best: {required :true, number:true },
			mobile_comment_best: {required :true, number:true },
			comment_min_length: {required :true, number:true },
			comment_max_length: {required :true, number:true },
			comment_blame_blind_count: {required :true, number:true }
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
