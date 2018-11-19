<div class="box">
	<div class="box-header">
		<ul class="nav nav-tabs">
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir); ?>" onclick="return check_form_changed();">본인인증설정</a></li>
			<li role="presentation" class="active"><a href="<?php echo admin_url($this->pagedir . '/boards'); ?>" onclick="return check_form_changed();">게시판별사용여부</a></li>
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
				<div class="alert alert-success">
					해당 기능을 사용하기 위해서는 반드시 본인인증설정에서 본인확인 서비스 기능을 사용함으로 설정해주셔야 합니다.<br />
					성인인증이란 : 본인인증을 받은 회원 중에서 만 19세 이상이 된 회원을 말합니다. (즉, 성인인증 = 본인인증 + 만 19세이상)<br />
					비회원이 접근 가능한 페이지는 인증 기능이 적용되지 않습니다. <br />
					따라서 인증 기능을 사용하기 위해서는 게시판 사용관리에서 사용권한을 로그인사용자 이상으로 변경하여주세요
				</div>
			</div>
			<div class="form-group">
				<div class="table-responsive">
					<table class="table table-hover table-striped table-bordered">
						<thead>
							<tr>
								<th>게시판명</th>
								<th>목록</th>
								<th>글열람, 댓글열람</th>
								<th>원글, 답변작성</th>
								<th>댓글작성</th>
							</tr>
						</thead>
						<tbody>
						<?php 
						if (element('boardlist', $view)) { 
							foreach (element('boardlist', $view) as $key => $value) {
						?>
								<tr>
									<td><?php echo html_escape(element('brd_name', $value)); ?></td>
									<td class="form-inline">
										<select name="access_list_selfcert[<?php echo element('brd_id', $value); ?>]" class="form-control" >
											<option value="" <?php echo set_select('access_list_selfcert[' . element('brd_id', $value) . ']', '', (element('access_list_selfcert', $value) === '' ? true : false)); ?>>인증기능 사용안함</option>
											<option value="1" <?php echo set_select('access_list_selfcert[' . element('brd_id', $value) . ']', '1', (element('access_list_selfcert', $value) === '1' ? true : false)); ?>>본인인증 필수</option>
											<option value="2" <?php echo set_select('access_list_selfcert[' . element('brd_id', $value) . ']', '2', (element('access_list_selfcert', $value) === '2' ? true : false)); ?>>성인인증 필수</option>
										</select>
									</td>
									<td class="form-inline">
										<select name="access_view_selfcert[<?php echo element('brd_id', $value); ?>]" class="form-control" >
											<option value="" <?php echo set_select('access_view_selfcert[' . element('brd_id', $value) . ']', '', (element('access_view_selfcert', $value) === '' ? true : false)); ?>>인증기능 사용안함</option>
											<option value="1" <?php echo set_select('access_view_selfcert[' . element('brd_id', $value) . ']', '1', (element('access_view_selfcert', $value) === '1' ? true : false)); ?>>본인인증 필수</option>
											<option value="2" <?php echo set_select('access_view_selfcert[' . element('brd_id', $value) . ']', '2', (element('access_view_selfcert', $value) === '2' ? true : false)); ?>>성인인증 필수</option>
										</select>
									</td>
									<td class="form-inline">
										<select name="access_write_selfcert[<?php echo element('brd_id', $value); ?>]" class="form-control" >
											<option value="" <?php echo set_select('access_write_selfcert[' . element('brd_id', $value) . ']', '', (element('access_write_selfcert', $value) === '' ? true : false)); ?>>인증기능 사용안함</option>
											<option value="1" <?php echo set_select('access_write_selfcert[' . element('brd_id', $value) . ']', '1', (element('access_write_selfcert', $value) === '1' ? true : false)); ?>>본인인증 필수</option>
											<option value="2" <?php echo set_select('access_write_selfcert[' . element('brd_id', $value) . ']', '2', (element('access_write_selfcert', $value) === '2' ? true : false)); ?>>성인인증 필수</option>
										</select>
									</td>
									<td class="form-inline">
										<select name="access_comment_selfcert[<?php echo element('brd_id', $value); ?>]" class="form-control" >
											<option value="" <?php echo set_select('access_comment_selfcert[' . element('brd_id', $value) . ']', '', (element('access_comment_selfcert', $value) === '' ? true : false)); ?>>인증기능 사용안함</option>
											<option value="1" <?php echo set_select('access_comment_selfcert[' . element('brd_id', $value) . ']', '1', (element('access_comment_selfcert', $value) === '1' ? true : false)); ?>>본인인증 필수</option>
											<option value="2" <?php echo set_select('access_comment_selfcert[' . element('brd_id', $value) . ']', '2', (element('access_list_selfcert', $value) === '2' ? true : false)); ?>>성인인증 필수</option>
										</select>
									</td>
								</tr>
						<?php 
							}
						}
						?>
						</tbody>
					</table>
				</div>
				<div class="btn-group pull-right" role="group" aria-label="...">
					<button type="submit" class="btn btn-success btn-sm">저장하기</button>
				</div>
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
