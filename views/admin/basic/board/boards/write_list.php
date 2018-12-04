<div class="box">
	<div class="box-header">
		<h4 class="pb10 pull-left"><?php echo html_escape($this->board->item_id('brd_name', element('brd_id', element('data', $view)))); ?> <a href="<?php echo goto_url(board_url(html_escape($this->board->item_id('brd_key', element('brd_id', element('data', $view)))))); ?>" class="btn-xs" target="_blank"><span class="glyphicon glyphicon-new-window"></span></a></h4>
		<?php if (element('boardlist', $view)) { ?>
		<div class="pull-right">
			<select name="brd_id" class="form-control" onChange="location.href='<?php echo admin_url($this->pagedir . '/write_list'); ?>/' + this.value;">
				<?php foreach (element('boardlist', $view) as $key => $value) { ?>
					<option value="<?php echo element('brd_id', $value); ?>" <?php echo set_select('brd_id', element('brd_id', $value), (element('brd_id', element('data', $view)) === element('brd_id', $value) ? true : false)); ?>><?php echo html_escape(element('brd_name', $value)); ?></option>
				<?php } ?>
			</select>
		</div>
		<?php } ?>
		<div class="clearfix"></div>
		<ul class="nav nav-tabs">
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/write/' . element('brd_id', element('data', $view))); ?>" onclick="return check_form_changed();">기본정보</a></li>
			<li role="presentation" class="active"><a href="<?php echo admin_url($this->pagedir . '/write_list/' . element('brd_id', element('data', $view))); ?>" onclick="return check_form_changed();">목록페이지</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/write_post/' . element('brd_id', element('data', $view))); ?>" onclick="return check_form_changed();">게시물열람</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/write_write/' . element('brd_id', element('data', $view))); ?>" onclick="return check_form_changed();">게시물작성</a></li>
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
			<div class="box-table-header">
				<h4><a data-toggle="collapse" href="#boardtab1" aria-expanded="true" aria-controls="boardtab1">목록페이지</a></h4>
				<a data-toggle="collapse" href="#boardtab1" aria-expanded="true" aria-controls="boardtab1"><i class="fa fa-chevron-up pull-right"></i></a>
			</div>
			<div class="collapse in" id="boardtab1">
				<div class="form-group">
					<label class="col-sm-2 control-label">정렬방법</label>
					<div class="col-sm-8 form-inline">
						<select name="order_by_field" class="form-control" >
							<option value="post_num, post_reply" <?php echo set_select('order_by_field', 'post_num, post_reply', (element('order_by_field', element('data', $view)) === 'post_num, post_reply' ? true : false)); ?>>기본정렬</option>
							<option value="post_datetime desc" <?php echo set_select('order_by_field', 'post_datetime desc', (element('order_by_field', element('data', $view)) === 'post_datetime desc' ? true : false)); ?>>날짜순(최근날짜우선)</option>
							<option value="post_datetime asc" <?php echo set_select('order_by_field', 'post_datetime asc', (element('order_by_field', element('data', $view)) === 'post_datetime asc' ? true : false)); ?>>날짜순(오래된날짜우선)</option>
							<option value="post_hit desc" <?php echo set_select('order_by_field', 'post_hit desc', (element('order_by_field', element('data', $view)) === 'post_hit desc' ? true : false)); ?>>조회수(높은것우선)</option>
							<option value="post_hit asc" <?php echo set_select('order_by_field', 'post_hit asc', (element('order_by_field', element('data', $view)) === 'post_hit asc' ? true : false)); ?>>조회수(낮은것우선)</option>
							<option value="post_comment_count desc" <?php echo set_select('order_by_field', 'post_comment_count desc', (element('order_by_field', element('data', $view)) === 'post_comment_count desc' ? true : false)); ?>>댓글수(많은것우선)</option>
							<option value="post_comment_count asc" <?php echo set_select('order_by_field', 'post_comment_count asc', (element('order_by_field', element('data', $view)) === 'post_comment_count asc' ? true : false)); ?>>댓글수(적은것우선)</option>
							<option value="post_comment_updated_datetime desc" <?php echo set_select('order_by_field', 'post_comment_updated_datetime desc', (element('order_by_field', element('data', $view)) === 'post_comment_updated_datetime desc' ? true : false)); ?>>댓글시간순(최근에입력된댓글이있는글이가장먼저나옴)</option>
							<option value="post_comment_updated_datetime asc" <?php echo set_select('order_by_field', 'post_comment_updated_datetime asc', (element('order_by_field', element('data', $view)) === 'post_comment_updated_datetime asc' ? true : false)); ?>>댓글시간순(최근에입력된댓글이있는글이가장나중에나옴)</option>
							<option value="post_like desc" <?php echo set_select('order_by_field', 'post_like desc', (element('order_by_field', element('data', $view)) === 'post_like desc' ? true : false)); ?>>추천수(많은것우선)</option>
							<option value="post_like asc" <?php echo set_select('order_by_field', 'post_like asc', (element('order_by_field', element('data', $view)) === 'post_like asc' ? true : false)); ?>>추천수(적은것우선)</option>
						</select>
					</div>
					<div class="col-sm-2">
						<label for="grp_order_by_field" class="checkbox-inline">
							<input type="checkbox" name="grp[order_by_field]" id="grp_order_by_field" value="1" /> 그룹적용
						</label>
						<label for="all_order_by_field" class="checkbox-inline">
							<input type="checkbox" name="all[order_by_field]" id="all_order_by_field" value="1" /> 전체적용
						</label>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">목록수</label>
					<div class="col-sm-8">
						PC - <input type="number" class="form-control" name="list_count" value="<?php echo set_value('list_count', (int) element('list_count', element('data', $view))); ?>" />,
						모바일 - <input type="number" class="form-control" name="mobile_list_count" value="<?php echo set_value('mobile_list_count', (int) element('mobile_list_count', element('data', $view))); ?>" />,
						<span class="help-inline">한페이지에 보이는 게시물 수입니다</span>
					</div>
					<div class="col-sm-2">
						<label for="grp_list_count" class="checkbox-inline">
							<input type="checkbox" name="grp[list_count]" id="grp_list_count" value="1" /> 그룹적용
						</label>
						<label for="all_list_count" class="checkbox-inline">
							<input type="checkbox" name="all[list_count]" id="all_list_count" value="1" /> 전체적용
						</label>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">페이지수</label>
					<div class="col-sm-8">
						PC - <input type="number" class="form-control" name="page_count" value="<?php echo set_value('page_count', (int) element('page_count', element('data', $view))); ?>" />,
						모바일 - <input type="number" class="form-control" name="mobile_page_count" value="<?php echo set_value('mobile_page_count', (int) element('mobile_page_count', element('data', $view))); ?>" />,
						<span class="help-inline">목록 하단, 페이지를 이동하는 링크 수를 지정할 수 있습니다</span>
					</div>
					<div class="col-sm-2">
						<label for="grp_page_count" class="checkbox-inline">
							<input type="checkbox" name="grp[page_count]" id="grp_page_count" value="1" /> 그룹적용
						</label>
						<label for="all_page_count" class="checkbox-inline">
							<input type="checkbox" name="all[page_count]" id="all_page_count" value="1" /> 전체적용
						</label>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">글쓰기버튼 항상보이기</label>
					<div class="col-sm-8">
						<label for="always_show_write_button" class="checkbox-inline">
							<input type="checkbox" name="always_show_write_button" id="always_show_write_button" value="1" <?php echo set_checkbox('always_show_write_button', '1', (element('always_show_write_button', element('data', $view)) ? true : false)); ?> /> PC
						</label>
						<label for="mobile_always_show_write_button" class="checkbox-inline">
							<input type="checkbox" name="mobile_always_show_write_button" id="mobile_always_show_write_button" value="1" <?php echo set_checkbox('mobile_always_show_write_button', '1', (element('mobile_always_show_write_button', element('data', $view)) ? true : false)); ?> /> 모바일
						</label>
						<span class="help-inline">권한이 없는 사용자라도 글쓰기 버튼은 항상 보입니다</span>
					</div>
					<div class="col-sm-2">
						<label for="grp_always_show_write_button" class="checkbox-inline">
							<input type="checkbox" name="grp[always_show_write_button]" id="grp_always_show_write_button" value="1" /> 그룹적용
						</label>
						<label for="all_always_show_write_button" class="checkbox-inline">
							<input type="checkbox" name="all[always_show_write_button]" id="all_always_show_write_button" value="1" /> 전체적용
						</label>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">뷰페이지에서 목록보이기</label>
					<div class="col-sm-8">
						<label for="show_list_from_view" class="checkbox-inline">
							<input type="checkbox" name="show_list_from_view" id="show_list_from_view" value="1" <?php echo set_checkbox('show_list_from_view', '1', (element('show_list_from_view', element('data', $view)) ? true : false)); ?> />PC
						</label>
						<label for="mobile_show_list_from_view" class="checkbox-inline">
							<input type="checkbox" name="mobile_show_list_from_view" id="mobile_show_list_from_view" value="1" <?php echo set_checkbox('mobile_show_list_from_view', '1', (element('mobile_show_list_from_view', element('data', $view)) ? true : false)); ?> /> 모바일
						</label>
						<span class="help-inline">뷰페이지 하단에 목록이 항상 보입니다</span>
					</div>
					<div class="col-sm-2">
						<label for="grp_show_list_from_view" class="checkbox-inline">
							<input type="checkbox" name="grp[show_list_from_view]" id="grp_show_list_from_view" value="1" /> 그룹적용
						</label>
						<label for="all_show_list_from_view" class="checkbox-inline">
							<input type="checkbox" name="all[show_list_from_view]" id="all_show_list_from_view" value="1" /> 전체적용
						</label>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">New아이콘보이기</label>
					<div class="col-sm-8">
						PC - <input type="number" class="form-control" name="new_icon_hour" value="<?php echo set_value('new_icon_hour', (int) element('new_icon_hour', element('data', $view))); ?>" />시간,
						모바일 - <input type="number" class="form-control" name="mobile_new_icon_hour" value="<?php echo set_value('mobile_new_icon_hour', (int) element('mobile_new_icon_hour', element('data', $view))); ?>" />시간,
						<span class="help-inline">해당 시간 동안 New 아이콘이 보입니다</span>
					</div>
					<div class="col-sm-2">
						<label for="grp_new_icon_hour" class="checkbox-inline">
							<input type="checkbox" name="grp[new_icon_hour]" id="grp_new_icon_hour" value="1" /> 그룹적용
						</label>
						<label for="all_new_icon_hour" class="checkbox-inline">
							<input type="checkbox" name="all[new_icon_hour]" id="all_new_icon_hour" value="1" /> 전체적용
						</label>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">Hot아이콘보이기</label>
					<div class="col-sm-8">
						조회수가
						PC - <input type="number" class="form-control" name="hot_icon_hit" value="<?php echo set_value('hot_icon_hit', (int) element('hot_icon_hit', element('data', $view))); ?>" />,
						모바일 - <input type="number" class="form-control" name="mobile_hot_icon_hit" value="<?php echo set_value('mobile_hot_icon_hit', (int) element('mobile_hot_icon_hit', element('data', $view))); ?>" />
						이상이면 Hot 아이콘이 보입니다.<br />
						PC - <input type="number" class="form-control" name="hot_icon_day" value="<?php echo set_value('hot_icon_day', (int) element('hot_icon_day', element('data', $view))); ?>" />일,
						모바일 - <input type="number" class="form-control" name="mobile_hot_icon_day" value="<?php echo set_value('mobile_hot_icon_day', (int) element('mobile_hot_icon_day', element('data', $view))); ?>" />일
						이내의 게시물에만 Hot 아이콘이 보입니다.
						<span class="help-block">기간을 0 으로 하시면 기간에 상관없이 조회수 기준만으로 Hot 아이콘이 보입니다.</span>
					</div>
					<div class="col-sm-2">
						<label for="grp_hot_icon_hit" class="checkbox-inline">
							<input type="checkbox" name="grp[hot_icon_hit]" id="grp_hot_icon_hit" value="1" /> 그룹적용
						</label>
						<label for="all_hot_icon_hit" class="checkbox-inline">
							<input type="checkbox" name="all[hot_icon_hit]" id="all_hot_icon_hit" value="1" /> 전체적용
						</label>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">제목길이</label>
					<div class="col-sm-8">
						PC - <input type="number" class="form-control" name="subject_length" value="<?php echo set_value('subject_length', (int) element('subject_length', element('data', $view))); ?>" />글자,
						모바일 - <input type="number" class="form-control" name="mobile_subject_length" value="<?php echo set_value('mobile_subject_length', (int) element('mobile_subject_length', element('data', $view))); ?>" />글자
						이상은 목록에서만 잘려보입니다.
						<span class="help-block">0 으로 설정하시면 제목이 잘려보이지 않습니다.</span>
					</div>
					<div class="col-sm-2">
						<label for="grp_subject_length" class="checkbox-inline">
							<input type="checkbox" name="grp[subject_length]" id="grp_subject_length" value="1" /> 그룹적용
						</label>
						<label for="all_subject_length" class="checkbox-inline">
							<input type="checkbox" name="all[subject_length]" id="all_subject_length" value="1" /> 전체적용
						</label>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">공지사항제외</label>
					<div class="col-sm-8">
						<label for="except_notice" class="checkbox-inline">
							<input type="checkbox" name="except_notice" id="except_notice" value="1" <?php echo set_checkbox('except_notice', '1', (element('except_notice', element('data', $view)) ? true : false)); ?> /> PC
						</label>
						<label for="mobile_except_notice" class="checkbox-inline">
							<input type="checkbox" name="mobile_except_notice" id="mobile_except_notice" value="1" <?php echo set_checkbox('mobile_except_notice', '1', (element('mobile_except_notice', element('data', $view)) ? true : false)); ?> /> 모바일
						</label>
						<span class="help-inline"> 목록 상단에 늘 나타나는 공지사항을 일반 목록에서 나타나지 않도록 합니다</span>
					</div>
					<div class="col-sm-2">
						<label for="grp_except_notice" class="checkbox-inline">
							<input type="checkbox" name="grp[except_notice]" id="grp_except_notice" value="1" /> 그룹적용
						</label>
						<label for="all_except_notice" class="checkbox-inline">
							<input type="checkbox" name="all[except_notice]" id="all_except_notice" value="1" /> 전체적용
						</label>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">전체공지제외</label>
					<div class="col-sm-8">
						<label for="except_all_notice" class="checkbox-inline">
							<input type="checkbox" name="except_all_notice" id="except_all_notice" value="1" <?php echo set_checkbox('except_all_notice', '1', (element('except_all_notice', element('data', $view)) ? true : false)); ?> /> PC
						</label>
						<label for="mobile_except_all_notice" class="checkbox-inline">
							<input type="checkbox" name="mobile_except_all_notice" id="mobile_except_all_notice" value="1" <?php echo set_checkbox('mobile_except_all_notice', '1', (element('mobile_except_all_notice', element('data', $view)) ? true : false)); ?> /> 모바일
						</label>
						<span class="help-inline"> 설정하시면 다른 게시판에서 입력한 전체 공지가 이 게시판에서는 보이지 않습니다.</span>
					</div>
					<div class="col-sm-2">
						<label for="grp_except_all_notice" class="checkbox-inline">
							<input type="checkbox" name="grp[except_all_notice]" id="grp_except_all_notice" value="1" /> 그룹적용
						</label>
						<label for="all_except_all_notice" class="checkbox-inline">
							<input type="checkbox" name="all[except_all_notice]" id="all_except_all_notice" value="1" /> 전체적용
						</label>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">답변 정렬 순서</label>
					<div class="col-sm-8 form-inline">
						<select name="reply_order" class="form-control" >
							<option value="asc" <?php echo set_select('reply_order', 'asc', (element('reply_order', element('data', $view)) !== 'desc' ? true : false)); ?>>나중에 단 답변 나중에 출력</option>
							<option value="desc" <?php echo set_select('reply_order', 'desc', (element('reply_order', element('data', $view)) === 'desc' ? true : false)); ?>>나중에 단 답변 먼저 출력</option>
						</select>
					</div>
					<div class="col-sm-2">
						<label for="grp_reply_order" class="checkbox-inline">
							<input type="checkbox" name="grp[reply_order]" id="grp_reply_order" value="1" /> 그룹적용
						</label>
						<label for="all_reply_order" class="checkbox-inline">
							<input type="checkbox" name="all[reply_order]" id="all_reply_order" value="1" /> 전체적용
						</label>
					</div>
				</div>
			</div>
			<div class="box-table">
				<div class="box-table-header">
					<h4><a data-toggle="collapse" href="#boardtab2" aria-expanded="true" aria-controls="boardtab2">갤러리게시판일경우</a></h4>
					<a data-toggle="collapse" href="#boardtab2" aria-expanded="true" aria-controls="boardtab2"><i class="fa fa-chevron-up pull-right"></i></a>
				</div>
				<div class="collapse in" id="boardtab2">
					<div class="form-group">
						<label class="col-sm-2 control-label">갤러리 목록 사용</label>
						<div class="col-sm-8">
							<label for="use_gallery_list" class="checkbox-inline">
								<input type="checkbox" name="use_gallery_list" id="use_gallery_list" value="1" <?php echo set_checkbox('use_gallery_list', '1', (element('use_gallery_list', element('data', $view)) ? true : false)); ?> /> 사용합니다
							</label>
							<span class="help-inline"> 설정하시면 스킨 디렉토리에 list.php 대신에 gallerylist.php 를 사용합니다.</span>
						</div>
						<div class="col-sm-2">
							<label for="grp_use_gallery_list" class="checkbox-inline">
								<input type="checkbox" name="grp[use_gallery_list]" id="grp_use_gallery_list" value="1" /> 그룹적용
							</label>
							<label for="all_use_gallery_list" class="checkbox-inline">
								<input type="checkbox" name="all[use_gallery_list]" id="all_use_gallery_list" value="1" /> 전체적용
							</label>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">목록에 가로 이미지 개수</label>
						<div class="col-sm-8">
							<input type="number" class="form-control" name="gallery_cols" value="<?php echo set_value('gallery_cols', (int) element('gallery_cols', element('data', $view))); ?>" />
						</div>
						<div class="col-sm-2">
							<label for="grp_gallery_cols" class="checkbox-inline">
								<input type="checkbox" name="grp[gallery_cols]" id="grp_gallery_cols" value="1" /> 그룹적용
							</label>
							<label for="all_gallery_cols" class="checkbox-inline">
								<input type="checkbox" name="all[gallery_cols]" id="all_gallery_cols" value="1" /> 전체적용
							</label>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">목록 이미지 크기</label>
						<div class="col-sm-8">
							가로 <input type="number" class="form-control" name="gallery_image_width" value="<?php echo set_value('gallery_image_width', (int) element('gallery_image_width', element('data', $view))); ?>" />px,
							세로 <input type="number" class="form-control" name="gallery_image_height" value="<?php echo set_value('gallery_image_height', (int) element('gallery_image_height', element('data', $view))); ?>" />px
						</div>
						<div class="col-sm-2">
							<label for="grp_gallery_image_width" class="checkbox-inline">
								<input type="checkbox" name="grp[gallery_image_width]" id="grp_gallery_image_width" value="1" /> 그룹적용
							</label>
							<label for="all_gallery_image_width" class="checkbox-inline">
								<input type="checkbox" name="all[gallery_image_width]" id="all_gallery_image_width" value="1" /> 전체적용
							</label>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">모바일 목록에 가로 이미지 개수</label>
						<div class="col-sm-8">
							<input type="number" class="form-control" name="mobile_gallery_cols" value="<?php echo set_value('mobile_gallery_cols', (int) element('mobile_gallery_cols', element('data', $view))); ?>" />
						</div>
						<div class="col-sm-2">
							<label for="grp_mobile_gallery_cols" class="checkbox-inline">
								<input type="checkbox" name="grp[mobile_gallery_cols]" id="grp_mobile_gallery_cols" value="1" /> 그룹적용
							</label>
							<label for="all_mobile_gallery_cols" class="checkbox-inline">
								<input type="checkbox" name="all[mobile_gallery_cols]" id="all_mobile_gallery_cols" value="1" /> 전체적용
							</label>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">모바일 목록 이미지 크기</label>
						<div class="col-sm-8">
							가로 <input type="number" class="form-control" name="mobile_gallery_image_width" value="<?php echo set_value('mobile_gallery_image_width', (int) element('mobile_gallery_image_width', element('data', $view))); ?>" />px,
							세로 <input type="number" class="form-control" name="mobile_gallery_image_height" value="<?php echo set_value('mobile_gallery_image_height', (int) element('mobile_gallery_image_height', element('data', $view))); ?>" />px
						</div>
						<div class="col-sm-2">
							<label for="grp_mobile_gallery_image_width" class="checkbox-inline">
								<input type="checkbox" name="grp[mobile_gallery_image_width]" id="grp_mobile_gallery_image_width" value="1" /> 그룹적용
							</label>
							<label for="all_mobile_gallery_image_width" class="checkbox-inline">
								<input type="checkbox" name="all[mobile_gallery_image_width]" id="all_mobile_gallery_image_width" value="1" /> 전체적용
							</label>
						</div>
					</div>
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
			list_count: {required :true, number:true, min:1 },
			mobile_list_count: {required :true, number:true, min:1 },
			page_count: {required :true, number:true, min:1 },
			mobile_page_count: {required :true, number:true, min:1 },
			new_icon_hour: {required :true, number:true },
			mobile_new_icon_hour: {required :true, number:true },
			hot_icon_hit: {required :true, number:true },
			mobile_hot_icon_hit: {required :true, number:true },
			hot_icon_day: {required :true, number:true },
			mobile_hot_icon_day: {required :true, number:true },
			subject_length: {required :true, number:true },
			mobile_subject_length: {required :true, number:true },
			gallery_cols: {required:true, number:true},
			gallery_image_width: {required:true, number:true},
			gallery_image_height: {required:true, number:true},
			mobile_gallery_cols: {required:true, number:true},
			mobile_gallery_image_width: {required:true, number:true},
			mobile_gallery_image_height: {required:true, number:true}
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
