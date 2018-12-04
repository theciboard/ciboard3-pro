<div class="box">
	<div class="box-header">
		<h4 class="pb10 pull-left"><?php echo html_escape($this->board->item_id('brd_name', element('brd_id', element('data', $view)))); ?> <a href="<?php echo goto_url(board_url(html_escape($this->board->item_id('brd_key', element('brd_id', element('data', $view)))))); ?>" class="btn-xs" target="_blank"><span class="glyphicon glyphicon-new-window"></span></a></h4>
		<?php if (element('boardlist', $view)) { ?>
		<div class="pull-right">
			<select name="brd_id" class="form-control" onChange="location.href='<?php echo admin_url($this->pagedir . '/write_write'); ?>/' + this.value;">
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
			<li role="presentation" class="active"><a href="<?php echo admin_url($this->pagedir . '/write_write/' . element('brd_id', element('data', $view))); ?>" onclick="return check_form_changed();">게시물작성</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/write_category/' . element('brd_id', element('data', $view))); ?>" onclick="return check_form_changed();">카테고리</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/write_comment/' . element('brd_id', element('data', $view))); ?>" onclick="return check_form_changed();">댓글기능</a></li>
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
				<label class="col-sm-2 control-label">글쓰기시 기본 제목 (PC)</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="post_default_title" value="<?php echo set_value('post_default_title', element('post_default_title', element('data', $view))); ?>" />
				</div>
				<div class="col-sm-2">
					<label for="grp_post_default_title" class="checkbox-inline">
						<input type="checkbox" name="grp[post_default_title]" id="grp_post_default_title" value="1" /> 그룹적용
					</label>
					<label for="all_post_default_title" class="checkbox-inline">
						<input type="checkbox" name="all[post_default_title]" id="all_post_default_title" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">글쓰기시 기본 제목 (모바일)</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="mobile_post_default_title" value="<?php echo set_value('mobile_post_default_title', element('mobile_post_default_title', element('data', $view))); ?>" />
				</div>
				<div class="col-sm-2">
					<label for="grp_mobile_post_default_title" class="checkbox-inline">
						<input type="checkbox" name="grp[mobile_post_default_title]" id="grp_mobile_post_default_title" value="1" /> 그룹적용
					</label>
					<label for="all_mobile_post_default_title" class="checkbox-inline">
						<input type="checkbox" name="all[mobile_post_default_title]" id="all_mobile_post_default_title" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">글쓰기시 기본 내용 (PC)</label>
				<div class="col-sm-8">
					<textarea class="form-control" rows="5" name="post_default_content"><?php echo set_value('post_default_content', element('post_default_content', element('data', $view))); ?></textarea>
				</div>
				<div class="col-sm-2">
					<label for="grp_post_default_content" class="checkbox-inline">
						<input type="checkbox" name="grp[post_default_content]" id="grp_post_default_content" value="1" /> 그룹적용
					</label>
					<label for="all_post_default_content" class="checkbox-inline">
						<input type="checkbox" name="all[post_default_content]" id="all_post_default_content" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">글쓰기시 기본 내용 (모바일)</label>
				<div class="col-sm-8">
					<textarea class="form-control" rows="5" name="mobile_post_default_content"><?php echo set_value('mobile_post_default_content', element('mobile_post_default_content', element('data', $view))); ?></textarea>
				</div>
				<div class="col-sm-2">
					<label for="grp_mobile_post_default_content" class="checkbox-inline">
						<input type="checkbox" name="grp[mobile_post_default_content]" id="grp_mobile_post_default_content" value="1" /> 그룹적용
					</label>
					<label for="all_mobile_post_default_content" class="checkbox-inline">
						<input type="checkbox" name="all[mobile_post_default_content]" id="all_mobile_post_default_content" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">본문 에디터 사용</label>
				<div class="col-sm-8">
					<label for="use_post_dhtml" class="checkbox-inline">
						<input type="checkbox" name="use_post_dhtml" id="use_post_dhtml" value="1" <?php echo set_checkbox('use_post_dhtml', '1', (element('use_post_dhtml', element('data', $view)) ? true : false)); ?> /> PC
					</label>
					<label for="use_mobile_post_dhtml" class="checkbox-inline">
						<input type="checkbox" name="use_mobile_post_dhtml" id="use_mobile_post_dhtml" value="1" <?php echo set_checkbox('use_mobile_post_dhtml', '1', (element('use_mobile_post_dhtml', element('data', $view)) ? true : false)); ?> /> 모바일
					</label>
				</div>
				<div class="col-sm-2">
					<label for="grp_use_post_dhtml" class="checkbox-inline">
						<input type="checkbox" name="grp[use_post_dhtml]" id="grp_use_post_dhtml" value="1" /> 그룹적용
					</label>
					<label for="all_use_post_dhtml" class="checkbox-inline">
						<input type="checkbox" name="all[use_post_dhtml]" id="all_use_post_dhtml" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">외부 이미지 가져오기</label>
				<div class="col-sm-8">
					<label for="save_external_image" class="checkbox-inline">
						<input type="checkbox" name="save_external_image" id="save_external_image" value="1" <?php echo set_checkbox('save_external_image', '1', (element('save_external_image', element('data', $view)) ? true : false)); ?> /> 게시물 입력시 본문에 외부사이트 이미지가 있는 경우 서버로 이미지를 수집합니다.
					</label>
				</div>
				<div class="col-sm-2">
					<label for="grp_save_external_image" class="checkbox-inline">
						<input type="checkbox" name="grp[save_external_image]" id="grp_save_external_image" value="1" /> 그룹적용
					</label>
					<label for="all_save_external_image" class="checkbox-inline">
						<input type="checkbox" name="all[save_external_image]" id="all_save_external_image" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">최소 글수 제한</label>
				<div class="col-sm-8">
					<input type="number" class="form-control" name="post_min_length" value="<?php echo set_value('post_min_length', (int) element('post_min_length', element('data', $view))); ?>" />글자 이상 작성하셔야 합니다. 0 입력시 제한 없음, 에디터 사용시 적용 안됨
				</div>
				<div class="col-sm-2">
					<label for="grp_post_min_length" class="checkbox-inline">
						<input type="checkbox" name="grp[post_min_length]" id="grp_post_min_length" value="1" /> 그룹적용
					</label>
					<label for="all_post_min_length" class="checkbox-inline">
						<input type="checkbox" name="all[post_min_length]" id="all_post_min_length" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">최대 글수 제한</label>
				<div class="col-sm-8">
					<input type="number" class="form-control" name="post_max_length" value="<?php echo set_value('post_max_length', (int) element('post_max_length', element('data', $view))); ?>" />글자 이하 작성하셔야 합니다. 0 입력시 제한 없음, 에디터 사용시 적용 안됨
				</div>
				<div class="col-sm-2">
					<label for="grp_post_max_length" class="checkbox-inline">
						<input type="checkbox" name="grp[post_max_length]" id="grp_post_max_length" value="1" /> 그룹적용
					</label>
					<label for="all_post_max_length" class="checkbox-inline">
						<input type="checkbox" name="all[post_max_length]" id="all_post_max_length" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">비밀글 사용</label>
				<div class="col-sm-8 form-inline">
					<select name="use_post_secret" class="form-control" >
						<option value="" <?php echo set_select('use_post_secret', '', (element('use_post_secret', element('data', $view)) === '' ? true : false)); ?>>사용하지 않음</option>
						<option value="1" <?php echo set_select('use_post_secret', '1', (element('use_post_secret', element('data', $view)) === '1' ? true : false)); ?>>선택사용</option>
						<option value="2" <?php echo set_select('use_post_secret', '2', (element('use_post_secret', element('data', $view)) === '2' ? true : false)); ?>>항상비밀글</option>
					</select>
					비밀글은 작성자 본인과 게시판 관리자 이상만 열람 가능합니다
				</div>
				<div class="col-sm-2">
					<label for="grp_use_post_secret" class="checkbox-inline">
						<input type="checkbox" name="grp[use_post_secret]" id="grp_use_post_secret" value="1" /> 그룹적용
					</label>
					<label for="all_use_post_secret" class="checkbox-inline">
						<input type="checkbox" name="all[use_post_secret]" id="all_use_post_secret" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">비밀글 기본 선택</label>
				<div class="col-sm-8">
					<label for="use_post_secret_selected" class="checkbox-inline">
						<input type="checkbox" name="use_post_secret_selected" id="use_post_secret_selected" value="1" <?php echo set_checkbox('use_post_secret_selected', '1', (element('use_post_secret_selected', element('data', $view)) ? true : false)); ?> /> 비밀글 선택사용 가능 게시판에서 글쓰기시 비밀글 옵션 부분이 기본으로 체크되어있습니다
					</label>
				</div>
				<div class="col-sm-2">
					<label for="grp_use_post_secret_selected" class="checkbox-inline">
						<input type="checkbox" name="grp[use_post_secret_selected]" id="grp_use_post_secret_selected" value="1" /> 그룹적용
					</label>
					<label for="all_use_post_secret_selected" class="checkbox-inline">
						<input type="checkbox" name="all[use_post_secret_selected]" id="all_use_post_secret_selected" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">답변 메일 받기 기능</label>
				<div class="col-sm-8">
					<label for="use_post_receive_email" class="checkbox-inline">
						<input type="checkbox" name="use_post_receive_email" id="use_post_receive_email" value="1" <?php echo set_checkbox('use_post_receive_email', '1', (element('use_post_receive_email', element('data', $view)) ? true : false)); ?> /> 사용합니다. 회원님이 글을 작성시 답변메일받기에 체크한 경우, 댓글이 달리면 답변메일을 받아볼 수 있습니다.
					</label>
				</div>
				<div class="col-sm-2">
					<label for="grp_use_post_receive_email" class="checkbox-inline">
						<input type="checkbox" name="grp[use_post_receive_email]" id="grp_use_post_receive_email" value="1" /> 그룹적용
					</label>
					<label for="all_use_post_receive_email" class="checkbox-inline">
						<input type="checkbox" name="all[use_post_receive_email]" id="all_use_post_receive_email" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">링크 필드 개수</label>
				<div class="col-sm-8">
					PC - <input type="number" class="form-control" name="link_num" value="<?php echo set_value('link_num', (int) element('link_num', element('data', $view))); ?>" />개,
					모바일 - <input type="number" class="form-control" name="mobile_link_num" value="<?php echo set_value('mobile_link_num', (int) element('mobile_link_num', element('data', $view))); ?>" />개
					<span class="help-inline">글 입력시 링크입력 필드가 나타납니다</span>
				</div>
				<div class="col-sm-2">
					<label for="grp_link_num" class="checkbox-inline">
						<input type="checkbox" name="grp[link_num]" id="grp_link_num" value="1" /> 그룹적용
					</label>
					<label for="all_link_num" class="checkbox-inline">
						<input type="checkbox" name="all[link_num]" id="all_link_num" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">글입력시 이모티콘 사용</label>
				<div class="col-sm-8">
					<label for="use_post_emoticon" class="checkbox-inline">
						<input type="checkbox" name="use_post_emoticon" id="use_post_emoticon" value="1" <?php echo set_checkbox('use_post_emoticon', '1', (element('use_post_emoticon', element('data', $view)) ? true : false)); ?> /> PC
					</label>
					<label for="use_mobile_post_emoticon" class="checkbox-inline">
						<input type="checkbox" name="use_mobile_post_emoticon" id="use_mobile_post_emoticon" value="1" <?php echo set_checkbox('use_mobile_post_emoticon', '1', (element('use_mobile_post_emoticon', element('data', $view)) ? true : false)); ?> /> 모바일
					</label>
					<span class="help-inline">에디터를 사용하지 않는 게시판의 경우에 해당</span>
				</div>
				<div class="col-sm-2">
					<label for="grp_use_post_emoticon" class="checkbox-inline">
						<input type="checkbox" name="grp[use_post_emoticon]" id="grp_use_post_emoticon" value="1" /> 그룹적용
					</label>
					<label for="all_use_post_emoticon" class="checkbox-inline">
						<input type="checkbox" name="all[use_post_emoticon]" id="all_use_post_emoticon" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">글입력시 특수문자 사용</label>
				<div class="col-sm-8">
					<label for="use_post_specialchars" class="checkbox-inline">
						<input type="checkbox" name="use_post_specialchars" id="use_post_specialchars" value="1" <?php echo set_checkbox('use_post_specialchars', '1', (element('use_post_specialchars', element('data', $view)) ? true : false)); ?> /> PC
					</label>
					<label for="use_mobile_post_specialchars" class="checkbox-inline">
						<input type="checkbox" name="use_mobile_post_specialchars" id="use_mobile_post_specialchars" value="1" <?php echo set_checkbox('use_mobile_post_specialchars', '1', (element('use_mobile_post_specialchars', element('data', $view)) ? true : false)); ?> /> 모바일
					</label>
					<span class="help-inline">에디터를 사용하지 않는 게시판의 경우에 해당</span>
				</div>
				<div class="col-sm-2">
					<label for="grp_use_post_specialchars" class="checkbox-inline">
						<input type="checkbox" name="grp[use_post_specialchars]" id="grp_use_post_specialchars" value="1" /> 그룹적용
					</label>
					<label for="all_use_post_specialchars" class="checkbox-inline">
						<input type="checkbox" name="all[use_post_specialchars]" id="all_use_post_specialchars" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">제목 스타일 사용</label>
				<div class="col-sm-8">
					<label for="use_subject_style" class="checkbox-inline">
						<input type="checkbox" name="use_subject_style" id="use_subject_style" value="1" <?php echo set_checkbox('use_subject_style', '1', (element('use_subject_style', element('data', $view)) ? true : false)); ?> /> PC
					</label>
					<label for="use_mobile_subject_style" class="checkbox-inline">
						<input type="checkbox" name="use_mobile_subject_style" id="use_mobile_subject_style" value="1" <?php echo set_checkbox('use_mobile_subject_style', '1', (element('use_mobile_subject_style', element('data', $view)) ? true : false)); ?> /> 모바일
					</label>
					<span class="help-inline">볼드, 글시체, 색깔 적용 가능 - 게시판 목록에서만 적용됨</span>
				</div>
				<div class="col-sm-2">
					<label for="grp_use_subject_style" class="checkbox-inline">
						<input type="checkbox" name="grp[use_subject_style]" id="grp_use_subject_style" value="1" /> 그룹적용
					</label>
					<label for="all_use_subject_style" class="checkbox-inline">
						<input type="checkbox" name="all[use_subject_style]" id="all_use_subject_style" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">구글 지도 사용</label>
				<div class="col-sm-8">
					<label for="use_google_map" class="checkbox-inline">
						<input type="checkbox" name="use_google_map" id="use_google_map" value="1" <?php echo set_checkbox('use_google_map', '1', (element('use_google_map', element('data', $view)) ? true : false)); ?> /> 사용합니다
					</label>
				</div>
				<div class="col-sm-2">
					<label for="grp_use_google_map" class="checkbox-inline">
						<input type="checkbox" name="grp[use_google_map]" id="grp_use_google_map" value="1" /> 그룹적용
					</label>
					<label for="all_use_google_map" class="checkbox-inline">
						<input type="checkbox" name="all[use_google_map]" id="all_use_google_map" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">태그 기능</label>
				<div class="col-sm-8">
					<label for="use_post_tag" class="checkbox-inline">
						<input type="checkbox" name="use_post_tag" id="use_post_tag" value="1" <?php echo set_checkbox('use_post_tag', '1', (element('use_post_tag', element('data', $view)) ? true : false)); ?> /> 사용합니다
					</label>
				</div>
				<div class="col-sm-2">
					<label for="grp_use_post_tag" class="checkbox-inline">
						<input type="checkbox" name="grp[use_post_tag]" id="grp_use_post_tag" value="1" /> 그룹적용
					</label>
					<label for="all_use_post_tag" class="checkbox-inline">
						<input type="checkbox" name="all[use_post_tag]" id="all_use_post_tag" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">첨부파일 기능</label>
				<div class="col-sm-8">
					<label for="use_upload_file" class="checkbox-inline">
						<input type="checkbox" name="use_upload_file" id="use_upload_file" value="1" <?php echo set_checkbox('use_upload_file', '1', (element('use_upload_file', element('data', $view)) ? true : false)); ?> /> 사용합니다
					</label>
				</div>
				<div class="col-sm-2">
					<label for="grp_use_upload_file" class="checkbox-inline">
						<input type="checkbox" name="grp[use_upload_file]" id="grp_use_upload_file" value="1" /> 그룹적용
					</label>
					<label for="all_use_upload_file" class="checkbox-inline">
						<input type="checkbox" name="all[use_upload_file]" id="all_use_upload_file" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">첨부파일 개수 제한</label>
				<div class="col-sm-8">
					PC - <input type="number" class="form-control" name="upload_file_num" value="<?php echo set_value('upload_file_num', (int) element('upload_file_num', element('data', $view))); ?>" />개,
					모바일 - <input type="number" class="form-control" name="mobile_upload_file_num" value="<?php echo set_value('mobile_upload_file_num', (int) element('mobile_upload_file_num', element('data', $view))); ?>" />개
					이하로 첨부가능
				</div>
				<div class="col-sm-2">
					<label for="grp_upload_file_num" class="checkbox-inline">
						<input type="checkbox" name="grp[upload_file_num]" id="grp_upload_file_num" value="1" /> 그룹적용
					</label>
					<label for="all_upload_file_num" class="checkbox-inline">
						<input type="checkbox" name="all[upload_file_num]" id="all_upload_file_num" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">첨부파일 용량제한</label>
				<div class="col-sm-8">
					<input type="number" class="form-control" name="upload_file_max_size" value="<?php echo set_value('upload_file_max_size', (int) element('upload_file_max_size', element('data', $view))); ?>" /> MB /
					최대 <?php echo html_escape(element('upload_max_filesize', $view)); ?> 까지 업로드 가능
				</div>
				<div class="col-sm-2">
					<label for="grp_upload_file_max_size" class="checkbox-inline">
						<input type="checkbox" name="grp[upload_file_max_size]" id="grp_upload_file_max_size" value="1" /> 그룹적용
					</label>
					<label for="all_upload_file_max_size" class="checkbox-inline">
						<input type="checkbox" name="all[upload_file_max_size]" id="all_upload_file_max_size" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">첨부파일 확장자</label>
				<div class="col-sm-8">
					<input type="text" class="form-control" name="upload_file_extension" value="<?php echo set_value('upload_file_extension', element('upload_file_extension', element('data', $view))); ?>" /> jpg|jpeg|gif|png 등으로 | 로 구분하여 입력, 입력하지 않으면 확장자 제한없이 업로드 가능
				</div>
				<div class="col-sm-2">
					<label for="grp_upload_file_extension" class="checkbox-inline">
						<input type="checkbox" name="grp[upload_file_extension]" id="grp_upload_file_extension" value="1" /> 그룹적용
					</label>
					<label for="all_upload_file_extension" class="checkbox-inline">
						<input type="checkbox" name="all[upload_file_extension]" id="all_upload_file_extension" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">다운로드 제한 (코멘트 필수)</label>
				<div class="col-sm-8">
					<label for="comment_to_download" class="checkbox-inline">
						<input type="checkbox" name="comment_to_download" id="comment_to_download" value="1" <?php echo set_checkbox('comment_to_download', '1', (element('comment_to_download', element('data', $view)) ? true : false)); ?> /> 사용합니다, 첨부파일을 다운로드 받기 위해서는 코멘트를 반드시 입력하셔야 합니다, 회원들에게만 적용
					</label>
				</div>
				<div class="col-sm-2">
					<label for="grp_comment_to_download" class="checkbox-inline">
						<input type="checkbox" name="grp[comment_to_download]" id="grp_comment_to_download" value="1" /> 그룹적용
					</label>
					<label for="all_comment_to_download" class="checkbox-inline">
						<input type="checkbox" name="all[comment_to_download]" id="all_comment_to_download" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">다운로드 제한 (추천 필수)</label>
				<div class="col-sm-8">
					<label for="like_to_download" class="checkbox-inline">
						<input type="checkbox" name="like_to_download" id="like_to_download" value="1" <?php echo set_checkbox('like_to_download', '1', (element('like_to_download', element('data', $view)) ? true : false)); ?> /> 사용합니다, 첨부파일을 다운로드 받기 위해서는 추천을	반드시 하셔야 합니다, 회원들에게만 적용
					</label>
				</div>
				<div class="col-sm-2">
					<label for="grp_like_to_download" class="checkbox-inline">
						<input type="checkbox" name="grp[like_to_download]" id="grp_like_to_download" value="1" /> 그룹적용
					</label>
					<label for="all_like_to_download" class="checkbox-inline">
						<input type="checkbox" name="all[like_to_download]" id="all_like_to_download" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">글쓰기 기간제한</label>
				<div class="col-sm-8">
					가입한지 <input type="number" class="form-control" name="write_possible_days" value="<?php echo set_value('write_possible_days', (int) element('write_possible_days', element('data', $view))); ?>" />일 이상 지난 회원만 글쓰기가 가능합니다.
					<div class="help-block">0 으로 설정하시면 기간체크하지 않습니다.</div>
				</div>
				<div class="col-sm-2">
					<label for="grp_write_possible_days" class="checkbox-inline">
						<input type="checkbox" name="grp[write_possible_days]" id="grp_write_possible_days" value="1" /> 그룹적용
					</label>
					<label for="all_write_possible_days" class="checkbox-inline">
						<input type="checkbox" name="all[write_possible_days]" id="all_write_possible_days" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">글 한개만 작성가능</label>
				<div class="col-sm-8">
					<label for="use_only_one_post" class="checkbox-inline">
						<input type="checkbox" name="use_only_one_post" id="use_only_one_post" value="1" <?php echo set_checkbox('use_only_one_post', '1', (element('use_only_one_post', element('data', $view)) ? true : false)); ?> /> 사용합니다
					</label>
					<div class="help-inline">사용하시면 이 게시판은 한 사람당 글 한개씩만 작성이 가능합니다.</div>
				</div>
				<div class="col-sm-2">
					<label for="grp_use_only_one_post" class="checkbox-inline">
						<input type="checkbox" name="grp[use_only_one_post]" id="grp_use_only_one_post" value="1" /> 그룹적용
					</label>
					<label for="all_use_only_one_post" class="checkbox-inline">
						<input type="checkbox" name="all[use_only_one_post]" id="all_use_only_one_post" value="1" /> 전체적용
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
			post_min_length: {required :true, number:true },
			post_max_length: {required :true, number:true },
			link_num: {required :true, number:true },
			mobile_link_num: {required :true, number:true },
			upload_file_num: {required :true, number:true },
			mobile_upload_file_num: {required :true, number:true },
			upload_file_max_size: {required :true, number:true },
			write_possible_days: {required :true, number:true }
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
