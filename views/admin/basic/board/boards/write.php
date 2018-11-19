<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="box">
<?php
if (element('brd_id', element('data', $view))) {
?>
	<div class="box-header">
		<h4 class="pb10 pull-left"><?php echo html_escape($this->board->item_id('brd_name', element('brd_id', element('data', $view)))); ?> <a href="<?php echo goto_url(board_url(html_escape($this->board->item_id('brd_key', element('brd_id', element('data', $view)))))); ?>" class="btn-xs" target="_blank"><span class="glyphicon glyphicon-new-window"></span></a></h4>
		<?php if (element('boardlist', $view)) { ?>
		<div class="pull-right">
			<select name="brd_id" class="form-control" onChange="location.href='<?php echo admin_url($this->pagedir . '/write'); ?>/' + this.value;">
				<?php foreach (element('boardlist', $view) as $key => $value) { ?>
					<option value="<?php echo element('brd_id', $value); ?>" <?php echo set_select('brd_id', element('brd_id', $value), (element('brd_id', element('data', $view)) === element('brd_id', $value) ? true : false)); ?>><?php echo html_escape(element('brd_name', $value)); ?></option>
				<?php } ?>
			</select>
		</div>
		<?php } ?>
		<div class="clearfix"></div>
		<ul class="nav nav-tabs">
			<li role="presentation" class="active"><a href="<?php echo admin_url($this->pagedir . '/write/' . element('brd_id', element('data', $view))); ?>" onclick="return check_form_changed();">기본정보</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/write_list/' . element('brd_id', element('data', $view))); ?>" onclick="return check_form_changed();">목록페이지</a></li>
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
<?php
} else {
?>
	<div class="box-header">
		<ul class="nav nav-tabs">
			<li role="presentation" class="active"><a href="javascript:;" >기본정보</a></li>
			<li role="presentation"><a href="javascript:;" onClick="alert('기본정보를 저장하신 후에 다른 정보 수정이 가능합니다');">목록페이지</a></li>
			<li role="presentation"><a href="javascript:;" onClick="alert('기본정보를 저장하신 후에 다른 정보 수정이 가능합니다');">게시물열람</a></li>
			<li role="presentation"><a href="javascript:;" onClick="alert('기본정보를 저장하신 후에 다른 정보 수정이 가능합니다');">게시물작성</a></li>
			<li role="presentation"><a href="javascript:;" onClick="alert('기본정보를 저장하신 후에 다른 정보 수정이 가능합니다');">카테고리</a></li>
			<li role="presentation"><a href="javascript:;" onClick="alert('기본정보를 저장하신 후에 다른 정보 수정이 가능합니다');">댓글기능</a></li>
			<li role="presentation"><a href="javascript:;" onClick="alert('기본정보를 저장하신 후에 다른 정보 수정이 가능합니다');">일반기능</a></li>
			<li role="presentation"><a href="javascript:;" onClick="alert('기본정보를 저장하신 후에 다른 정보 수정이 가능합니다');">포인트기능</a></li>
			<li role="presentation"><a href="javascript:;" onClick="alert('기본정보를 저장하신 후에 다른 정보 수정이 가능합니다');">메일/쪽지/문자</a></li>
			<li role="presentation"><a href="javascript:;" onClick="alert('기본정보를 저장하신 후에 다른 정보 수정이 가능합니다');">RSS/사이트맵 설정</a></li>
			<li role="presentation"><a href="javascript:;" onClick="alert('기본정보를 저장하신 후에 다른 정보 수정이 가능합니다');">권한관리</a></li>
			<li role="presentation"><a href="javascript:;" onClick="alert('기본정보를 저장하신 후에 다른 정보 수정이 가능합니다');">사용자정의</a></li>
			<li role="presentation"><a href="javascript:;" onClick="alert('기본정보를 저장하신 후에 다른 정보 수정이 가능합니다');">게시판관리자</a></li>
		</ul>
	</div>
<?php
}
?>
	<div class="box-table">
		<?php
		echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
		echo show_alert_message(element('alert_message', $view), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
		echo show_alert_message($this->session->flashdata('message'), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
		$attributes = array('class' => 'form-horizontal', 'name' => 'fadminwrite', 'id' => 'fadminwrite');
		echo form_open(current_full_url(), $attributes);
		?>
			<input type="hidden" name="is_submit" value="1" />
			<input type="hidden" name="<?php echo element('primary_key', $view); ?>"	value="<?php echo element(element('primary_key', $view), element('data', $view)); ?>" />
			<div>
				<div class="form-group">
					<label class="col-sm-2 control-label">게시판주소(KEY)</label>
					<div class="col-sm-8 form-inline">
						<?php echo board_url(); ?> <input type="text" class="form-control" name="brd_key" value="<?php echo set_value('brd_key', element('brd_key', element('data', $view))); ?>" /> <span class="help-inline">페이지주소를 입력해주세요</span>
						<?php
						if (element('brd_key', element('data', $view))) {
						?>
							<a href="<?php echo goto_url(board_url(html_escape(element('brd_key', element('data', $view))))); ?>" target="_blank"><span class="glyphicon glyphicon-new-window"></span></a>
						<?php
						}
						?>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">게시판 이름</label>
					<div class="col-sm-8">
						PC - <input type="text" class="form-control per30" name="brd_name" value="<?php echo set_value('brd_name', element('brd_name', element('data', $view))); ?>" />,
						모바일 - <input type="text" class="form-control per30" name="brd_mobile_name" value="<?php echo set_value('brd_mobile_name', element('brd_mobile_name', element('data', $view))); ?>" />
						<span class="help-block">모바일용 제목을 입력하지 않으시면, 모바일에서 PC 용 제목이 보이게 됩니다</span>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">그룹명</label>
					<div class="col-sm-8 form-inline">
						<select name="bgr_id" id="bgr_id" class="form-control" >
							<?php echo element('group_option', element('data', $view)); ?>
						</select>
						<div class="help-inline"><a href="<?php echo admin_url('board/boardgroup'); ?>">그룹생성하러 가기</a></div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">레이아웃</label>
					<div class="col-sm-8 form-inline">
						<select name="board_layout" id="board_layout" class="form-control" >
							<?php echo element('board_layout_option', element('data', $view)); ?>
						</select>
					</div>
					<div class="col-sm-2">
						<label for="grp_board_layout" class="checkbox-inline">
							<input type="checkbox" name="grp[board_layout]" id="grp_board_layout" value="1" /> 그룹적용
						</label>
						<label for="all_board_layout" class="checkbox-inline">
							<input type="checkbox" name="all[board_layout]" id="all_board_layout" value="1" /> 전체적용
						</label>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">레이아웃에 사이드바 사용</label>
					<div class="col-sm-8 form-inline">
						<select class="form-control" name="board_sidebar" id="board_sidebar">
							<option value="">기본설정따름</option>
							<option value="1" <?php echo set_select('board_sidebar', '1', (element('board_sidebar', element('data', $view)) === '1' ? true : false)); ?> >사용</option>
							<option value="2" <?php echo set_select('board_sidebar', '2', (element('board_sidebar', element('data', $view)) === '2' ? true : false)); ?> >사용하지않음</option>
						</select>
					</div>
					<div class="col-sm-2">
						<label for="grp_board_sidebar" class="checkbox-inline">
							<input type="checkbox" name="grp[board_sidebar]" id="grp_board_sidebar" value="1" /> 그룹적용
						</label>
						<label for="all_board_sidebar" class="checkbox-inline">
							<input type="checkbox" name="all[board_sidebar]" id="all_board_sidebar" value="1" /> 전체적용
						</label>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">모바일 레이아웃</label>
					<div class="col-sm-8 form-inline">
						<select name="board_mobile_layout" id="board_mobile_layout" class="form-control" >
							<?php echo element('board_mobile_layout_option', element('data', $view)); ?>
						</select>
					</div>
					<div class="col-sm-2">
						<label for="grp_board_mobile_layout" class="checkbox-inline">
							<input type="checkbox" name="grp[board_mobile_layout]" id="grp_board_mobile_layout" value="1" /> 그룹적용
						</label>
						<label for="all_board_mobile_layout" class="checkbox-inline">
							<input type="checkbox" name="all[board_mobile_layout]" id="all_board_mobile_layout" value="1" /> 전체적용
						</label>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">모바일레이아웃에 사이드바 사용</label>
					<div class="col-sm-8 form-inline">
						<select class="form-control" name="board_mobile_sidebar" id="board_mobile_sidebar">
							<option value="">기본설정따름</option>
							<option value="1" <?php echo set_select('board_mobile_sidebar', '1', (element('board_mobile_sidebar', element('data', $view)) === '1' ? true : false)); ?> >사용</option>
							<option value="2" <?php echo set_select('board_mobile_sidebar', '2', (element('board_mobile_sidebar', element('data', $view)) === '2' ? true : false)); ?> >사용하지않음</option>
						</select>
					</div>
					<div class="col-sm-2">
						<label for="grp_board_mobile_sidebar" class="checkbox-inline">
							<input type="checkbox" name="grp[board_mobile_sidebar]" id="grp_board_mobile_sidebar" value="1" /> 그룹적용
						</label>
						<label for="all_board_mobile_sidebar" class="checkbox-inline">
							<input type="checkbox" name="all[board_mobile_sidebar]" id="all_board_mobile_sidebar" value="1" /> 전체적용
						</label>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">스킨</label>
					<div class="col-sm-8 form-inline">
						<select name="board_skin" id="board_skin" class="form-control" >
							<?php echo element('board_skin_option', element('data', $view)); ?>
						</select>
					</div>
					<div class="col-sm-2">
						<label for="grp_board_skin" class="checkbox-inline">
							<input type="checkbox" name="grp[board_skin]" id="grp_board_skin" value="1" /> 그룹적용
						</label>
						<label for="all_board_skin" class="checkbox-inline">
							<input type="checkbox" name="all[board_skin]" id="all_board_skin" value="1" /> 전체적용
						</label>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">모바일스킨</label>
					<div class="col-sm-8 form-inline">
						<select name="board_mobile_skin" id="board_mobile_skin" class="form-control" >
							<?php echo element('board_mobile_skin_option', element('data', $view)); ?>
						</select>
					</div>
					<div class="col-sm-2">
						<label for="grp_board_mobile_skin" class="checkbox-inline">
							<input type="checkbox" name="grp[board_mobile_skin]" id="grp_board_mobile_skin" value="1" /> 그룹적용
						</label>
						<label for="all_board_mobile_skin" class="checkbox-inline">
							<input type="checkbox" name="all[board_mobile_skin]" id="all_board_mobile_skin" value="1" /> 전체적용
						</label>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">상단 내용</label>
					<div class="col-sm-8">
						<textarea class="form-control" rows="5" name="header_content"><?php echo set_value('header_content', element('header_content', element('data', $view))); ?></textarea>
					</div>
					<div class="col-sm-2">
						<label for="grp_header_content" class="checkbox-inline">
							<input type="checkbox" name="grp[header_content]" id="grp_header_content" value="1" /> 그룹적용
						</label>
						<label for="all_header_content" class="checkbox-inline">
							<input type="checkbox" name="all[header_content]" id="all_header_content" value="1" /> 전체적용
						</label>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">하단 내용</label>
					<div class="col-sm-8">
						<textarea class="form-control" rows="5" name="footer_content"><?php echo set_value('footer_content', element('footer_content', element('data', $view))); ?></textarea>
					</div>
					<div class="col-sm-2">
						<label for="grp_footer_content" class="checkbox-inline">
							<input type="checkbox" name="grp[footer_content]" id="grp_footer_content" value="1" /> 그룹적용
						</label>
						<label for="all_footer_content" class="checkbox-inline">
							<input type="checkbox" name="all[footer_content]" id="all_footer_content" value="1" /> 전체적용
						</label>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">모바일 상단 내용</label>
					<div class="col-sm-8">
						<textarea class="form-control" rows="5" name="mobile_header_content"><?php echo set_value('mobile_header_content', element('mobile_header_content', element('data', $view))); ?></textarea>
					</div>
					<div class="col-sm-2">
						<label for="grp_mobile_header_content" class="checkbox-inline">
							<input type="checkbox" name="grp[mobile_header_content]" id="grp_mobile_header_content" value="1" /> 그룹적용
						</label>
						<label for="all_mobile_header_content" class="checkbox-inline">
							<input type="checkbox" name="all[mobile_header_content]" id="all_mobile_header_content" value="1" /> 전체적용
						</label>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">모바일 하단 내용</label>
					<div class="col-sm-8">
						<textarea class="form-control" rows="5" name="mobile_footer_content"><?php echo set_value('mobile_footer_content', element('mobile_footer_content', element('data', $view))); ?></textarea>
					</div>
					<div class="col-sm-2">
						<label for="grp_mobile_footer_content" class="checkbox-inline">
							<input type="checkbox" name="grp[mobile_footer_content]" id="grp_mobile_footer_content" value="1" /> 그룹적용
						</label>
						<label for="all_mobile_footer_content" class="checkbox-inline">
							<input type="checkbox" name="all[mobile_footer_content]" id="all_mobile_footer_content" value="1" /> 전체적용
						</label>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">정렬순서</label>
					<div class="col-sm-8">
						<input type="number" class="form-control" name="brd_order" value="<?php echo set_value('brd_order', (int) element('brd_order', element('data', $view))); ?>" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">검색여부</label>
					<div class="col-sm-8">
						<label for="brd_search" class="checkbox-inline">
							<input type="checkbox" name="brd_search" id="brd_search" value="1" <?php echo set_checkbox('brd_search', '1', (element('brd_search', element('data', $view)) ? true : false)); ?> /> 사용합니다
						</label>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">캡챠 사용</label>
					<div class="col-sm-8">
						<label for="board_use_captcha" class="checkbox-inline">
							<input type="checkbox" name="board_use_captcha" id="board_use_captcha" value="1" <?php echo set_checkbox('board_use_captcha', '1', (element('board_use_captcha', element('data', $view)) ? true : false)); ?> /> 사용합니다
						</label>
						<span class="help-block">체크하면 글 작성시 캡챠를 무조건 사용합니다.( 회원 + 비회원 모두 )</span>
						<span class="help-block">미 체크하면 비회원에게만 캡챠를 사용합니다.</span>
					</div>
					<div class="col-sm-2">
						<label for="grp_board_use_captcha" class="checkbox-inline">
							<input type="checkbox" name="grp[board_use_captcha]" id="grp_board_use_captcha" value="1" /> 그룹적용
						</label>
						<label for="all_board_use_captcha" class="checkbox-inline">
							<input type="checkbox" name="all[board_use_captcha]" id="all_board_use_captcha" value="1" /> 전체적용
						</label>
					</div>
				</div>
				<div class="btn-group pull-right" role="group" aria-label="...">
					<a href="<?php echo admin_url($this->pagedir); ?>" class="btn btn-default btn-sm">목록으로</a>
					<button type="submit" class="btn btn-success btn-sm">저장하기</button>
				</div>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>

<script type="text/javascript">
//<![CDATA[
$(function() {
	$('#fadminwrite').validate({
		rules: {
			brd_key: {required :true, alpha_dash:true, minlength:3, maxlength:50 },
			brd_name: {required :true },
			bgr_id: {required :true },
			brd_order: {required :true, number:true, min:0, max:10000}
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
