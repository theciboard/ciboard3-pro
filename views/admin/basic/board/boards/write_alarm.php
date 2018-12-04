<div class="box">
	<div class="box-header">
		<h4 class="pb10 pull-left"><?php echo html_escape($this->board->item_id('brd_name', element('brd_id', element('data', $view)))); ?> <a href="<?php echo goto_url(board_url(html_escape($this->board->item_id('brd_key', element('brd_id', element('data', $view)))))); ?>" class="btn-xs" target="_blank"><span class="glyphicon glyphicon-new-window"></span></a></h4>
		<?php if (element('boardlist', $view)) { ?>
		<div class="pull-right">
			<select name="brd_id" class="form-control" onChange="location.href='<?php echo admin_url($this->pagedir . '/write_alarm'); ?>/' + this.value;">
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
			<li role="presentation" class="active"><a href="<?php echo admin_url($this->pagedir . '/write_alarm/' . element('brd_id', element('data', $view))); ?>" onclick="return check_form_changed();">메일/쪽지/문자</a></li>
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
		?>
		<div class="alert alert-dismissible alert-success">메일, 쪽지, 문자 알림 설정입니다. 해당 설정은 해당 회원님이 수신 동의한 경우에만 정상 발송됩니다</div>
		<?php
		if ( ! element('sms_library_exists', $view)) {
		?>
			<div class="alert alert-dismissible alert-warning">
				SMS Library 가 설치되어있지 않습니다.<br />
				문자발송 서비스를 이용하기 원하시면 우선 SMS 플러그인을 설치하여주세요<br />
				<a href="http://www.ciboard.co.kr/plugins/p/1572" target="_blank">설치하러 가기</a>
			</div>
		<?php
		}
		?>

		<?php
		$attributes = array('class' => 'form-horizontal', 'name' => 'fadminwrite', 'id' => 'fadminwrite');
		echo form_open(current_full_url(), $attributes);
		?>
			<input type="hidden" name="is_submit" value="1" />
			<input type="hidden" name="<?php echo element('primary_key', $view); ?>"	value="<?php echo element(element('primary_key', $view), element('data', $view)); ?>" />
			<div class="form-group">
				<label class="col-sm-2 control-label">메일사용(원글작성시)</label>
				<div class="col-sm-8">
					<div class="checkbox">
						<label for="send_email_post_super_admin" >
							<input type="checkbox" name="send_email_post_super_admin" id="send_email_post_super_admin" value="1" <?php echo set_checkbox('send_email_post_super_admin', '1', (element('send_email_post_super_admin', element('data', $view)) ? true : false)); ?> /> 최고관리자에게 메일발송
						</label>
					</div>
					<div class="checkbox">
						<label for="send_email_post_group_admin">
							<input type="checkbox" name="send_email_post_group_admin" id="send_email_post_group_admin" value="1" <?php echo set_checkbox('send_email_post_group_admin', '1', (element('send_email_post_group_admin', element('data', $view)) ? true : false)); ?> /> 그룹관리자에게 메일발송
						</label>
					</div>
					<div class="checkbox">
						<label for="send_email_post_board_admin">
							<input type="checkbox" name="send_email_post_board_admin" id="send_email_post_board_admin" value="1" <?php echo set_checkbox('send_email_post_board_admin', '1', (element('send_email_post_board_admin', element('data', $view)) ? true : false)); ?> /> 게시판관리자에게 메일발송
						</label>
					</div>
					<div class="checkbox">
						<label for="send_email_post_writer">
							<input type="checkbox" name="send_email_post_writer" id="send_email_post_writer" value="1" <?php echo set_checkbox('send_email_post_writer', '1', (element('send_email_post_writer', element('data', $view)) ? true : false)); ?> /> 원글작성자에게 메일발송
						</label>
					</div>
				</div>
				<div class="col-sm-2">
					<label for="grp_send_email_post" class="checkbox-inline">
						<input type="checkbox" name="grp[send_email_post]" id="grp_send_email_post" value="1" /> 그룹적용
					</label>
					<label for="all_send_email_post" class="checkbox-inline">
						<input type="checkbox" name="all[send_email_post]" id="all_send_email_post" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">메일사용(댓글작성시)</label>
				<div class="col-sm-8">
					<div class="checkbox">
						<label for="send_email_comment_super_admin" >
							<input type="checkbox" name="send_email_comment_super_admin" id="send_email_comment_super_admin" value="1" <?php echo set_checkbox('send_email_comment_super_admin', '1', (element('send_email_comment_super_admin', element('data', $view)) ? true : false)); ?> /> 최고관리자에게 메일발송
						</label>
					</div>
					<div class="checkbox">
						<label for="send_email_comment_group_admin">
							<input type="checkbox" name="send_email_comment_group_admin" id="send_email_comment_group_admin" value="1" <?php echo set_checkbox('send_email_comment_group_admin', '1', (element('send_email_comment_group_admin', element('data', $view)) ? true : false)); ?> /> 그룹관리자에게 메일발송
						</label>
					</div>
					<div class="checkbox">
						<label for="send_email_comment_board_admin">
							<input type="checkbox" name="send_email_comment_board_admin" id="send_email_comment_board_admin" value="1" <?php echo set_checkbox('send_email_comment_board_admin', '1', (element('send_email_comment_board_admin', element('data', $view)) ? true : false)); ?> /> 게시판관리자에게 메일발송
						</label>
					</div>
					<div class="checkbox">
						<label for="send_email_comment_post_writer">
							<input type="checkbox" name="send_email_comment_post_writer" id="send_email_comment_post_writer" value="1" <?php echo set_checkbox('send_email_comment_post_writer', '1', (element('send_email_comment_post_writer', element('data', $view)) ? true : false)); ?> /> 원글작성자에게 메일발송
						</label>
					</div>
					<div class="checkbox">
						<label for="send_email_comment_comment_writer">
							<input type="checkbox" name="send_email_comment_comment_writer" id="send_email_comment_comment_writer" value="1" <?php echo set_checkbox('send_email_comment_comment_writer', '1', (element('send_email_comment_comment_writer', element('data', $view)) ? true : false)); ?> /> 해당댓글작성자에게 메일발송
						</label>
					</div>
				</div>
				<div class="col-sm-2">
					<label for="grp_send_email_comment" class="checkbox-inline">
						<input type="checkbox" name="grp[send_email_comment]" id="grp_send_email_comment" value="1" /> 그룹적용
					</label>
					<label for="all_send_email_comment" class="checkbox-inline">
						<input type="checkbox" name="all[send_email_comment]" id="all_send_email_comment" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">메일사용(원글신고발생시)</label>
				<div class="col-sm-8">
					<div class="checkbox">
						<label for="send_email_blame_super_admin" >
							<input type="checkbox" name="send_email_blame_super_admin" id="send_email_blame_super_admin" value="1" <?php echo set_checkbox('send_email_blame_super_admin', '1', (element('send_email_blame_super_admin', element('data', $view)) ? true : false)); ?> /> 최고관리자에게 메일발송
						</label>
					</div>
					<div class="checkbox">
						<label for="send_email_blame_group_admin" >
							<input type="checkbox" name="send_email_blame_group_admin" id="send_email_blame_group_admin" value="1" <?php echo set_checkbox('send_email_blame_group_admin', '1', (element('send_email_blame_group_admin', element('data', $view)) ? true : false)); ?> /> 그룹관리자에게 메일발송
						</label>
					</div>
					<div class="checkbox">
						<label for="send_email_blame_board_admin" >
							<input type="checkbox" name="send_email_blame_board_admin" id="send_email_blame_board_admin" value="1" <?php echo set_checkbox('send_email_blame_board_admin', '1', (element('send_email_blame_board_admin', element('data', $view)) ? true : false)); ?> /> 게시판관리자에게 메일발송
						</label>
					</div>
					<div class="checkbox">
						<label for="send_email_blame_post_writer" >
							<input type="checkbox" name="send_email_blame_post_writer" id="send_email_blame_post_writer" value="1" <?php echo set_checkbox('send_email_blame_post_writer', '1', (element('send_email_blame_post_writer', element('data', $view)) ? true : false)); ?> /> 원글작성자에게 메일발송
						</label>
					</div>
				</div>
				<div class="col-sm-2">
					<label for="grp_send_email_blame" class="checkbox-inline">
						<input type="checkbox" name="grp[send_email_blame]" id="grp_send_email_blame" value="1" /> 그룹적용
					</label>
					<label for="all_send_email_blame" class="checkbox-inline">
						<input type="checkbox" name="all[send_email_blame]" id="all_send_email_blame" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">메일사용(댓글신고발생시)</label>
				<div class="col-sm-8">
					<div class="checkbox">
						<label for="send_email_comment_blame_super_admin" >
							<input type="checkbox" name="send_email_comment_blame_super_admin" id="send_email_comment_blame_super_admin" value="1" <?php echo set_checkbox('send_email_comment_blame_super_admin', '1', (element('send_email_comment_blame_super_admin', element('data', $view)) ? true : false)); ?> /> 최고관리자에게 메일발송
						</label>
					</div>
					<div class="checkbox">
						<label for="send_email_comment_blame_group_admin" >
							<input type="checkbox" name="send_email_comment_blame_group_admin" id="send_email_comment_blame_group_admin" value="1" <?php echo set_checkbox('send_email_comment_blame_group_admin', '1', (element('send_email_comment_blame_group_admin', element('data', $view)) ? true : false)); ?> /> 그룹관리자에게 메일발송
						</label>
					</div>
					<div class="checkbox">
						<label for="send_email_comment_blame_board_admin" >
							<input type="checkbox" name="send_email_comment_blame_board_admin" id="send_email_comment_blame_board_admin" value="1" <?php echo set_checkbox('send_email_comment_blame_board_admin', '1', (element('send_email_comment_blame_board_admin', element('data', $view)) ? true : false)); ?> /> 게시판관리자에게 메일발송
						</label>
					</div>
					<div class="checkbox">
						<label for="send_email_comment_blame_post_writer" >
							<input type="checkbox" name="send_email_comment_blame_post_writer" id="send_email_comment_blame_post_writer" value="1" <?php echo set_checkbox('send_email_comment_blame_post_writer', '1', (element('send_email_comment_blame_post_writer', element('data', $view)) ? true : false)); ?> /> 원글작성자에게 메일발송
						</label>
					</div>
					<div class="checkbox">
						<label for="send_email_comment_blame_comment_writer" >
							<input type="checkbox" name="send_email_comment_blame_comment_writer" id="send_email_comment_blame_comment_writer" value="1" <?php echo set_checkbox('send_email_comment_blame_comment_writer', '1', (element('send_email_comment_blame_comment_writer', element('data', $view)) ? true : false)); ?> /> 해당댓글작성자에게 메일발송
						</label>
					</div>
				</div>
				<div class="col-sm-2">
					<label for="grp_send_email_comment_blame" class="checkbox-inline">
						<input type="checkbox" name="grp[send_email_comment_blame]" id="grp_send_email_comment_blame" value="1" /> 그룹적용
					</label>
					<label for="all_send_email_comment_blame" class="checkbox-inline">
						<input type="checkbox" name="all[send_email_comment_blame]" id="all_send_email_comment_blame" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">쪽지사용(원글작성시)</label>
				<div class="col-sm-8">
					<div class="checkbox">
						<label for="send_note_post_super_admin" >
							<input type="checkbox" name="send_note_post_super_admin" id="send_note_post_super_admin" value="1" <?php echo set_checkbox('send_note_post_super_admin', '1', (element('send_note_post_super_admin', element('data', $view)) ? true : false)); ?> /> 최고관리자에게 쪽지발송
						</label>
					</div>
					<div class="checkbox">
						<label for="send_note_post_group_admin">
							<input type="checkbox" name="send_note_post_group_admin" id="send_note_post_group_admin" value="1" <?php echo set_checkbox('send_note_post_group_admin', '1', (element('send_note_post_group_admin', element('data', $view)) ? true : false)); ?> /> 그룹관리자에게 쪽지발송
						</label>
					</div>
					<div class="checkbox">
						<label for="send_note_post_board_admin">
							<input type="checkbox" name="send_note_post_board_admin" id="send_note_post_board_admin" value="1" <?php echo set_checkbox('send_note_post_board_admin', '1', (element('send_note_post_board_admin', element('data', $view)) ? true : false)); ?> /> 게시판관리자에게 쪽지발송
						</label>
					</div>
					<div class="checkbox">
						<label for="send_note_post_writer">
							<input type="checkbox" name="send_note_post_writer" id="send_note_post_writer" value="1" <?php echo set_checkbox('send_note_post_writer', '1', (element('send_note_post_writer', element('data', $view)) ? true : false)); ?> /> 원글작성자에게 쪽지발송
						</label>
					</div>
				</div>
				<div class="col-sm-2">
					<label for="grp_send_note_post" class="checkbox-inline">
						<input type="checkbox" name="grp[send_note_post]" id="grp_send_note_post" value="1" /> 그룹적용
					</label>
					<label for="all_send_note_post" class="checkbox-inline">
						<input type="checkbox" name="all[send_note_post]" id="all_send_note_post" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">쪽지사용(댓글작성시)</label>
				<div class="col-sm-8">
					<div class="checkbox">
						<label for="send_note_comment_super_admin" >
							<input type="checkbox" name="send_note_comment_super_admin" id="send_note_comment_super_admin" value="1" <?php echo set_checkbox('send_note_comment_super_admin', '1', (element('send_note_comment_super_admin', element('data', $view)) ? true : false)); ?> /> 최고관리자에게 쪽지발송
						</label>
					</div>
					<div class="checkbox">
						<label for="send_note_comment_group_admin">
							<input type="checkbox" name="send_note_comment_group_admin" id="send_note_comment_group_admin" value="1" <?php echo set_checkbox('send_note_comment_group_admin', '1', (element('send_note_comment_group_admin', element('data', $view)) ? true : false)); ?> /> 그룹관리자에게 쪽지발송
						</label>
					</div>
					<div class="checkbox">
						<label for="send_note_comment_board_admin">
							<input type="checkbox" name="send_note_comment_board_admin" id="send_note_comment_board_admin" value="1" <?php echo set_checkbox('send_note_comment_board_admin', '1', (element('send_note_comment_board_admin', element('data', $view)) ? true : false)); ?> /> 게시판관리자에게 쪽지발송
						</label>
					</div>
					<div class="checkbox">
						<label for="send_note_comment_post_writer">
							<input type="checkbox" name="send_note_comment_post_writer" id="send_note_comment_post_writer" value="1" <?php echo set_checkbox('send_note_comment_post_writer', '1', (element('send_note_comment_post_writer', element('data', $view)) ? true : false)); ?> /> 원글작성자에게 쪽지발송
						</label>
					</div>
					<div class="checkbox">
						<label for="send_note_comment_comment_writer">
							<input type="checkbox" name="send_note_comment_comment_writer" id="send_note_comment_comment_writer" value="1" <?php echo set_checkbox('send_note_comment_comment_writer', '1', (element('send_note_comment_comment_writer', element('data', $view)) ? true : false)); ?> /> 해당댓글작성자에게 쪽지발송
						</label>
					</div>
				</div>
				<div class="col-sm-2">
					<label for="grp_send_note_comment" class="checkbox-inline">
						<input type="checkbox" name="grp[send_note_comment]" id="grp_send_note_comment" value="1" /> 그룹적용
					</label>
					<label for="all_send_note_comment" class="checkbox-inline">
						<input type="checkbox" name="all[send_note_comment]" id="all_send_note_comment" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">쪽지사용(원글신고발생시)</label>
				<div class="col-sm-8">
					<div class="checkbox">
						<label for="send_note_blame_super_admin" >
							<input type="checkbox" name="send_note_blame_super_admin" id="send_note_blame_super_admin" value="1" <?php echo set_checkbox('send_note_blame_super_admin', '1', (element('send_note_blame_super_admin', element('data', $view)) ? true : false)); ?> /> 최고관리자에게 쪽지발송
						</label>
					</div>
					<div class="checkbox">
						<label for="send_note_blame_group_admin" >
							<input type="checkbox" name="send_note_blame_group_admin" id="send_note_blame_group_admin" value="1" <?php echo set_checkbox('send_note_blame_group_admin', '1', (element('send_note_blame_group_admin', element('data', $view)) ? true : false)); ?> /> 그룹관리자에게 쪽지발송
						</label>
					</div>
					<div class="checkbox">
						<label for="send_note_blame_board_admin" >
							<input type="checkbox" name="send_note_blame_board_admin" id="send_note_blame_board_admin" value="1" <?php echo set_checkbox('send_note_blame_board_admin', '1', (element('send_note_blame_board_admin', element('data', $view)) ? true : false)); ?> /> 게시판관리자에게 쪽지발송
						</label>
					</div>
					<div class="checkbox">
						<label for="send_note_blame_post_writer" >
							<input type="checkbox" name="send_note_blame_post_writer" id="send_note_blame_post_writer" value="1" <?php echo set_checkbox('send_note_blame_post_writer', '1', (element('send_note_blame_post_writer', element('data', $view)) ? true : false)); ?> /> 원글작성자에게 쪽지발송
						</label>
					</div>
				</div>
				<div class="col-sm-2">
					<label for="grp_send_note_blame" class="checkbox-inline">
						<input type="checkbox" name="grp[send_note_blame]" id="grp_send_note_blame" value="1" /> 그룹적용
					</label>
					<label for="all_send_note_blame" class="checkbox-inline">
						<input type="checkbox" name="all[send_note_blame]" id="all_send_note_blame" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">쪽지사용(댓글신고발생시)</label>
				<div class="col-sm-8">
					<div class="checkbox">
						<label for="send_note_comment_blame_super_admin" >
							<input type="checkbox" name="send_note_comment_blame_super_admin" id="send_note_comment_blame_super_admin" value="1" <?php echo set_checkbox('send_note_comment_blame_super_admin', '1', (element('send_note_comment_blame_super_admin', element('data', $view)) ? true : false)); ?> /> 최고관리자에게 쪽지발송
						</label>
					</div>
					<div class="checkbox">
						<label for="send_note_comment_blame_group_admin" >
							<input type="checkbox" name="send_note_comment_blame_group_admin" id="send_note_comment_blame_group_admin" value="1" <?php echo set_checkbox('send_note_comment_blame_group_admin', '1', (element('send_note_comment_blame_group_admin', element('data', $view)) ? true : false)); ?> /> 그룹관리자에게 쪽지발송
						</label>
					</div>
					<div class="checkbox">
						<label for="send_note_comment_blame_board_admin" >
							<input type="checkbox" name="send_note_comment_blame_board_admin" id="send_note_comment_blame_board_admin" value="1" <?php echo set_checkbox('send_note_comment_blame_board_admin', '1', (element('send_note_comment_blame_board_admin', element('data', $view)) ? true : false)); ?> /> 게시판관리자에게 쪽지발송
						</label>
					</div>
					<div class="checkbox">
						<label for="send_note_comment_blame_post_writer" >
							<input type="checkbox" name="send_note_comment_blame_post_writer" id="send_note_comment_blame_post_writer" value="1" <?php echo set_checkbox('send_note_comment_blame_post_writer', '1', (element('send_note_comment_blame_post_writer', element('data', $view)) ? true : false)); ?> /> 원글작성자에게 쪽지발송
						</label>
					</div>
					<div class="checkbox">
						<label for="send_note_comment_blame_comment_writer" >
							<input type="checkbox" name="send_note_comment_blame_comment_writer" id="send_note_comment_blame_comment_writer" value="1" <?php echo set_checkbox('send_note_comment_blame_comment_writer', '1', (element('send_note_comment_blame_comment_writer', element('data', $view)) ? true : false)); ?> /> 해당댓글작성자에게 쪽지발송
						</label>
					</div>
				</div>
				<div class="col-sm-2">
					<label for="grp_send_note_comment_blame" class="checkbox-inline">
						<input type="checkbox" name="grp[send_note_comment_blame]" id="grp_send_note_comment_blame" value="1" /> 그룹적용
					</label>
					<label for="all_send_note_comment_blame" class="checkbox-inline">
						<input type="checkbox" name="all[send_note_comment_blame]" id="all_send_note_comment_blame" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">문자(SMS)사용(원글작성시)</label>
				<div class="col-sm-8">
					<div class="checkbox">
						<label for="send_sms_post_super_admin" >
							<input type="checkbox" name="send_sms_post_super_admin" id="send_sms_post_super_admin" value="1" <?php echo set_checkbox('send_sms_post_super_admin', '1', (element('send_sms_post_super_admin', element('data', $view)) ? true : false)); ?> <?php echo ( ! element('sms_library_exists', $view)) ? 'disabled="disabled"' : ''; ?> /> 최고관리자에게 문자(SMS)발송
						</label>
					</div>
					<div class="checkbox">
						<label for="send_sms_post_group_admin">
							<input type="checkbox" name="send_sms_post_group_admin" id="send_sms_post_group_admin" value="1" <?php echo set_checkbox('send_sms_post_group_admin', '1', (element('send_sms_post_group_admin', element('data', $view)) ? true : false)); ?> <?php echo ( ! element('sms_library_exists', $view)) ? 'disabled="disabled"' : ''; ?> /> 그룹관리자에게 문자(SMS)발송
						</label>
					</div>
					<div class="checkbox">
						<label for="send_sms_post_board_admin">
							<input type="checkbox" name="send_sms_post_board_admin" id="send_sms_post_board_admin" value="1" <?php echo set_checkbox('send_sms_post_board_admin', '1', (element('send_sms_post_board_admin', element('data', $view)) ? true : false)); ?> <?php echo ( ! element('sms_library_exists', $view)) ? 'disabled="disabled"' : ''; ?> /> 게시판관리자에게 문자(SMS)발송
						</label>
					</div>
					<div class="checkbox">
						<label for="send_sms_post_writer">
							<input type="checkbox" name="send_sms_post_writer" id="send_sms_post_writer" value="1" <?php echo set_checkbox('send_sms_post_writer', '1', (element('send_sms_post_writer', element('data', $view)) ? true : false)); ?> <?php echo ( ! element('sms_library_exists', $view)) ? 'disabled="disabled"' : ''; ?> /> 원글작성자에게 문자(SMS)발송
						</label>
					</div>
				</div>
				<div class="col-sm-2">
					<label for="grp_send_sms_post" class="checkbox-inline">
						<input type="checkbox" name="grp[send_sms_post]" id="grp_send_sms_post" value="1" /> 그룹적용
					</label>
					<label for="all_send_sms_post" class="checkbox-inline">
						<input type="checkbox" name="all[send_sms_post]" id="all_send_sms_post" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">문자(SMS)사용(댓글작성시)</label>
				<div class="col-sm-8">
					<div class="checkbox">
						<label for="send_sms_comment_super_admin" >
							<input type="checkbox" name="send_sms_comment_super_admin" id="send_sms_comment_super_admin" value="1" <?php echo set_checkbox('send_sms_comment_super_admin', '1', (element('send_sms_comment_super_admin', element('data', $view)) ? true : false)); ?> <?php echo ( ! element('sms_library_exists', $view)) ? 'disabled="disabled"' : ''; ?> /> 최고관리자에게 문자(SMS)발송
						</label>
					</div>
					<div class="checkbox">
						<label for="send_sms_comment_group_admin">
							<input type="checkbox" name="send_sms_comment_group_admin" id="send_sms_comment_group_admin" value="1" <?php echo set_checkbox('send_sms_comment_group_admin', '1', (element('send_sms_comment_group_admin', element('data', $view)) ? true : false)); ?> <?php echo ( ! element('sms_library_exists', $view)) ? 'disabled="disabled"' : ''; ?> /> 그룹관리자에게 문자(SMS)발송
						</label>
					</div>
					<div class="checkbox">
						<label for="send_sms_comment_board_admin">
							<input type="checkbox" name="send_sms_comment_board_admin" id="send_sms_comment_board_admin" value="1" <?php echo set_checkbox('send_sms_comment_board_admin', '1', (element('send_sms_comment_board_admin', element('data', $view)) ? true : false)); ?> <?php echo ( ! element('sms_library_exists', $view)) ? 'disabled="disabled"' : ''; ?> /> 게시판관리자에게 문자(SMS)발송
						</label>
					</div>
					<div class="checkbox">
						<label for="send_sms_comment_post_writer">
							<input type="checkbox" name="send_sms_comment_post_writer" id="send_sms_comment_post_writer" value="1" <?php echo set_checkbox('send_sms_comment_post_writer', '1', (element('send_sms_comment_post_writer', element('data', $view)) ? true : false)); ?> <?php echo ( ! element('sms_library_exists', $view)) ? 'disabled="disabled"' : ''; ?> /> 원글작성자에게 문자(SMS)발송
						</label>
					</div>
					<div class="checkbox">
						<label for="send_sms_comment_comment_writer">
							<input type="checkbox" name="send_sms_comment_comment_writer" id="send_sms_comment_comment_writer" value="1" <?php echo set_checkbox('send_sms_comment_comment_writer', '1', (element('send_sms_comment_comment_writer', element('data', $view)) ? true : false)); ?> <?php echo ( ! element('sms_library_exists', $view)) ? 'disabled="disabled"' : ''; ?> /> 해당댓글작성자에게 문자(SMS)발송
						</label>
					</div>
				</div>
				<div class="col-sm-2">
					<label for="grp_send_sms_comment" class="checkbox-inline">
						<input type="checkbox" name="grp[send_sms_comment]" id="grp_send_sms_comment" value="1" /> 그룹적용
					</label>
					<label for="all_send_sms_comment" class="checkbox-inline">
						<input type="checkbox" name="all[send_sms_comment]" id="all_send_sms_comment" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">문자(SMS)사용(원글신고발생시)</label>
				<div class="col-sm-8">
					<div class="checkbox">
						<label for="send_sms_blame_super_admin" >
							<input type="checkbox" name="send_sms_blame_super_admin" id="send_sms_blame_super_admin" value="1" <?php echo set_checkbox('send_sms_blame_super_admin', '1', (element('send_sms_blame_super_admin', element('data', $view)) ? true : false)); ?> <?php echo ( ! element('sms_library_exists', $view)) ? 'disabled="disabled"' : ''; ?> /> 최고관리자에게 문자(SMS)발송
						</label>
					</div>
					<div class="checkbox">
						<label for="send_sms_blame_group_admin" >
							<input type="checkbox" name="send_sms_blame_group_admin" id="send_sms_blame_group_admin" value="1" <?php echo set_checkbox('send_sms_blame_group_admin', '1', (element('send_sms_blame_group_admin', element('data', $view)) ? true : false)); ?> <?php echo ( ! element('sms_library_exists', $view)) ? 'disabled="disabled"' : ''; ?> /> 그룹관리자에게 문자(SMS)발송
						</label>
					</div>
					<div class="checkbox">
						<label for="send_sms_blame_board_admin" >
							<input type="checkbox" name="send_sms_blame_board_admin" id="send_sms_blame_board_admin" value="1" <?php echo set_checkbox('send_sms_blame_board_admin', '1', (element('send_sms_blame_board_admin', element('data', $view)) ? true : false)); ?> <?php echo ( ! element('sms_library_exists', $view)) ? 'disabled="disabled"' : ''; ?> /> 게시판관리자에게 문자(SMS)발송
						</label>
					</div>
					<div class="checkbox">
						<label for="send_sms_blame_post_writer" >
							<input type="checkbox" name="send_sms_blame_post_writer" id="send_sms_blame_post_writer" value="1" <?php echo set_checkbox('send_sms_blame_post_writer', '1', (element('send_sms_blame_post_writer', element('data', $view)) ? true : false)); ?> <?php echo ( ! element('sms_library_exists', $view)) ? 'disabled="disabled"' : ''; ?> /> 원글작성자에게 문자(SMS)발송
						</label>
					</div>
				</div>
				<div class="col-sm-2">
					<label for="grp_send_sms_blame" class="checkbox-inline">
						<input type="checkbox" name="grp[send_sms_blame]" id="grp_send_sms_blame" value="1" /> 그룹적용
					</label>
					<label for="all_send_sms_blame" class="checkbox-inline">
						<input type="checkbox" name="all[send_sms_blame]" id="all_send_sms_blame" value="1" /> 전체적용
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">문자(SMS)사용(댓글신고발생시)</label>
				<div class="col-sm-8">
					<div class="checkbox">
						<label for="send_sms_comment_blame_super_admin" >
							<input type="checkbox" name="send_sms_comment_blame_super_admin" id="send_sms_comment_blame_super_admin" value="1" <?php echo set_checkbox('send_sms_comment_blame_super_admin', '1', (element('send_sms_comment_blame_super_admin', element('data', $view)) ? true : false)); ?> <?php echo ( ! element('sms_library_exists', $view)) ? 'disabled="disabled"' : ''; ?> /> 최고관리자에게 문자(SMS)발송
						</label>
					</div>
					<div class="checkbox">
						<label for="send_sms_comment_blame_group_admin" >
							<input type="checkbox" name="send_sms_comment_blame_group_admin" id="send_sms_comment_blame_group_admin" value="1" <?php echo set_checkbox('send_sms_comment_blame_group_admin', '1', (element('send_sms_comment_blame_group_admin', element('data', $view)) ? true : false)); ?> <?php echo ( ! element('sms_library_exists', $view)) ? 'disabled="disabled"' : ''; ?> /> 그룹관리자에게 문자(SMS)발송
						</label>
					</div>
					<div class="checkbox">
						<label for="send_sms_comment_blame_board_admin" >
							<input type="checkbox" name="send_sms_comment_blame_board_admin" id="send_sms_comment_blame_board_admin" value="1" <?php echo set_checkbox('send_sms_comment_blame_board_admin', '1', (element('send_sms_comment_blame_board_admin', element('data', $view)) ? true : false)); ?> <?php echo ( ! element('sms_library_exists', $view)) ? 'disabled="disabled"' : ''; ?> /> 게시판관리자에게 문자(SMS)발송
						</label>
					</div>
					<div class="checkbox">
						<label for="send_sms_comment_blame_post_writer" >
							<input type="checkbox" name="send_sms_comment_blame_post_writer" id="send_sms_comment_blame_post_writer" value="1" <?php echo set_checkbox('send_sms_comment_blame_post_writer', '1', (element('send_sms_comment_blame_post_writer', element('data', $view)) ? true : false)); ?> <?php echo ( ! element('sms_library_exists', $view)) ? 'disabled="disabled"' : ''; ?> /> 원글작성자에게 문자(SMS)발송
						</label>
					</div>
					<div class="checkbox">
						<label for="send_sms_comment_blame_comment_writer" >
							<input type="checkbox" name="send_sms_comment_blame_comment_writer" id="send_sms_comment_blame_comment_writer" value="1" <?php echo set_checkbox('send_sms_comment_blame_comment_writer', '1', (element('send_sms_comment_blame_comment_writer', element('data', $view)) ? true : false)); ?> <?php echo ( ! element('sms_library_exists', $view)) ? 'disabled="disabled"' : ''; ?> /> 해당댓글작성자에게 문자(SMS)발송
						</label>
					</div>
				</div>
				<div class="col-sm-2">
					<label for="grp_send_sms_comment_blame" class="checkbox-inline">
						<input type="checkbox" name="grp[send_sms_comment_blame]" id="grp_send_sms_comment_blame" value="1" /> 그룹적용
					</label>
					<label for="all_send_sms_comment_blame" class="checkbox-inline">
						<input type="checkbox" name="all[send_sms_comment_blame]" id="all_send_sms_comment_blame" value="1" /> 전체적용
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
