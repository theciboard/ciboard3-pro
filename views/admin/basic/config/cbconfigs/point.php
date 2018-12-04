<div class="box">
	<div class="box-header">
		<ul class="nav nav-tabs">
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir); ?>" onclick="return check_form_changed();">기본정보</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/access'); ?>" onclick="return check_form_changed();">접근기능</a></li>
			<li role="presentation" class="active"><a href="<?php echo admin_url($this->pagedir . '/point'); ?>" onclick="return check_form_changed();">포인트기능</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/general'); ?>" onclick="return check_form_changed();">일반기능 / 에디터</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/note'); ?>" onclick="return check_form_changed();">쪽지기능</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/notification'); ?>" onclick="return check_form_changed();">알림기능</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/company'); ?>" onclick="return check_form_changed ();">회사정보</a></li>
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
				<label class="col-sm-2 control-label">포인트 기능</label>
				<div class="col-sm-10">
					<label for="use_point" class="checkbox-inline">
						<input type="checkbox" name="use_point" id="use_point" value="1" <?php echo set_checkbox('use_point', '1', (element('use_point', element('data', $view)) ? true : false)); ?> /> 사용합니다
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">회원가입시 포인트</label>
				<div class="col-sm-10">
					<input type="number" class="form-control" name="point_register" id="point_register" value="<?php echo set_value('point_register', (int) element('point_register', element('data', $view))); ?>" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">로그인시 포인트</label>
				<div class="col-sm-10">
					<input type="number" class="form-control" name="point_login" id="point_login" value="<?php echo set_value('point_login', (int) element('point_login', element('data', $view))); ?>" /> 하루에 한번 적용
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">회원가입시 추천인에게 포인트</label>
				<div class="col-sm-10">
					<input type="number" class="form-control" name="point_recommended" id="point_recommended" value="<?php echo set_value('point_recommended', (int) element('point_recommended', element('data', $view))); ?>" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">추천인 존재시 가입자에게 포인트</label>
				<div class="col-sm-10">
					<input type="number" class="form-control" name="point_recommender" id="point_recommender" value="<?php echo set_value('point_recommender', (int) element('point_recommender', element('data', $view))); ?>" /> 회원가입 포인트와 별도로 적용됩니다
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">다운로드 금지</label>
				<div class="col-sm-10">
					<label for="block_download_zeropoint" class="checkbox-inline">
						<input type="checkbox" name="block_download_zeropoint" id="block_download_zeropoint" value="1" <?php echo set_checkbox('block_download_zeropoint', '1', (element('block_download_zeropoint', element('data', $view)) ? true : false)); ?> /> 다운로드시 포인트를 차감하는 게시판일 경우, 포인트가 부족할 시 다운로드를 금지합니다
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">글열람 금지</label>
				<div class="col-sm-10">
					<label for="block_read_zeropoint" class="checkbox-inline">
						<input type="checkbox" name="block_read_zeropoint" id="block_read_zeropoint" value="1" <?php echo set_checkbox('block_read_zeropoint', '1', (element('block_read_zeropoint', element('data', $view)) ? true : false)); ?> /> 글 열람시 포인트를 차감하는 게시판일 경우 포인트가 부족할 시 글 열람을 금지합니다
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">글작성 금지</label>
				<div class="col-sm-10">
					<label for="block_write_zeropoint" class="checkbox-inline">
						<input type="checkbox" name="block_write_zeropoint" id="block_write_zeropoint" value="1" <?php echo set_checkbox('block_write_zeropoint', '1', (element('block_write_zeropoint', element('data', $view)) ? true : false)); ?> /> 글 작성시 포인트를 차감하는 게시판일 경우 포인트가 부족할 시 글 작성을 금지합니다
					</label>
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
			point_register: {required :true, number:true},
			point_login: {required :true, number:true},
			point_recommended: {required :true, number:true},
			point_recommender: {required :true, number:true}
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
