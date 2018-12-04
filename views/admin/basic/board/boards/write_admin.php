<div class="box">
	<div class="box-header">
		<h4 class="pb10 pull-left"><?php echo html_escape($this->board->item_id('brd_name', element('brd_id', element('data', $view)))); ?> <a href="<?php echo goto_url(board_url(html_escape($this->board->item_id('brd_key', element('brd_id', element('data', $view)))))); ?>" class="btn-xs" target="_blank"><span class="glyphicon glyphicon-new-window"></span></a></h4>
		<?php if (element('boardlist', $view)) { ?>
		<div class="pull-right">
			<select name="brd_id" class="form-control" onChange="location.href='<?php echo admin_url($this->pagedir . '/write_admin'); ?>/' + this.value;">
				<?php foreach (element('boardlist', $view) as $key => $value) { ?>
					<option value="<?php echo element('brd_id', $value); ?>" <?php echo set_select('brd_id', element('brd_id', $value), (element('brd_id', element('data', $view)) === element('brd_id', $value) ? true : false)); ?>><?php echo html_escape(element('brd_name', $value)); ?></option>
				<?php } ?>
			</select>
		</div>
		<?php } ?>
		<div class="clearfix"></div>
		<ul class="nav nav-tabs">
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/write/' . element('brd_id', element('data', $view))); ?>">기본정보</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/write_list/' . element('brd_id', element('data', $view))); ?>">목록페이지</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/write_post/' . element('brd_id', element('data', $view))); ?>">게시물열람</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/write_write/' . element('brd_id', element('data', $view))); ?>">게시물작성</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/write_category/' . element('brd_id', element('data', $view))); ?>">카테고리</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/write_comment/' . element('brd_id', element('data', $view))); ?>">댓글기능</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/write_general/' . element('brd_id', element('data', $view))); ?>">일반기능</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/write_point/' . element('brd_id', element('data', $view))); ?>">포인트기능</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/write_alarm/' . element('brd_id', element('data', $view))); ?>">메일/쪽지/문자</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/write_rss/' . element('brd_id', element('data', $view))); ?>">RSS/사이트맵 설정</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/write_access/' . element('brd_id', element('data', $view))); ?>">권한관리</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/write_extravars/' . element('brd_id', element('data', $view))); ?>">사용자정의</a></li>
			<li role="presentation" class="active"><a href="<?php echo admin_url($this->pagedir . '/write_admin/' . element('brd_id', element('data', $view))); ?>">게시판관리자</a></li>
		</ul>
	</div>
	<div class="box-table">
		<?php
		echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
		echo show_alert_message(element('alert_message', $view), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
		echo show_alert_message($this->session->flashdata('message'), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
		$attributes = array('class' => 'form-inline', 'name' => 'flist', 'id' => 'flist');
		echo form_open(current_full_url(), $attributes);
		?>
			<div class="box-table-header">
				<div class="btn-group pull-right" role="group" aria-label="...">
					<button type="button" class="btn btn-outline btn-default btn-sm btn-list-delete btn-list-selected disabled" data-list-delete-url = "<?php echo element('list_delete_url', $view); ?>" >선택삭제</button>
				</div>
			</div>
			<div class="table-responsive">
				<table class="table table-hover table-striped">
					<thead>
						<tr>
							<th>아이디</th>
							<th>닉네임</th>
							<th>회원이메일</th>
							<th><input type="checkbox" name="chkall" id="chkall" /></th>
						</tr>
					</thead>
					<tbody>
					<?php
					if (element('list', $view)) {
						foreach (element('list', $view) as $result) {
					?>
						<tr>
							<td><?php echo html_escape(element('mem_userid', element('member', $result))); ?></td>
							<td><?php echo element('display_name', $result); ?></td>
							<td><?php echo html_escape(element('mem_email', element('member', $result))); ?></td>
							<td><input type="checkbox" name="chk[]" class="list-chkbox" value="<?php echo element(element('primary_key', $view), $result); ?>" /></td>
						</tr>
					<?php
						}
					}
					if ( ! element('list', $view)) {
					?>
						<tr>
							<td colspan="5" class="nopost">자료가 없습니다</td>
						</tr>
					<?php
					}
					?>
					</tbody>
				</table>
			</div>
		<?php echo form_close(); ?>
		<div>
			<div class="box-table">
				<?php
				$attributes = array('class' => 'form-horizontal', 'name' => 'fadminwrite', 'id' => 'fadminwrite');
				echo form_open(current_full_url(), $attributes);
				?>
					<input type="hidden" name="brd_id"	value="<?php echo element('brd_id', element('data', $view)); ?>" />
					<div class="form-group">
						<label class="col-sm-2 control-label">회원아이디</label>
						<div class="col-sm-8 form-inline">
							<input type="text" class="form-control" name="userid" />
							<button type="submit" class="btn btn-success btn-sm">추가하기</button>
						</div>
					</div>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
//<![CDATA[
$(function() {
	$('#fadminwrite').validate({
		rules: {
			userid: { required:true, alpha_dash:true, minlength:3, maxlength:50 }
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
