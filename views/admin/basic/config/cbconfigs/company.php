<div class="box">
	<div class="box-header">
		<ul class="nav nav-tabs">
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir); ?>" onclick="return check_form_changed();">기본정보</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/access'); ?>" onclick="return check_form_changed();">접근기능</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/point'); ?>" onclick="return check_form_changed();">포인트기능</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/general'); ?>" onclick="return check_form_changed();">일반기능 / 에디터</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/note'); ?>" onclick="return check_form_changed();">쪽지기능</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/notification'); ?>" onclick="return check_form_changed();">알림기능</a></li>
			<li role="presentation" class="active"><a href="<?php echo admin_url($this->pagedir . '/company'); ?>" onclick="return check_form_changed();">회사정보</a></li>
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
				<label class="col-sm-2 control-label">회사명</label>
				<div class="col-sm-10 form-inline">
					<input type="text" class="form-control" name="company_name" value="<?php echo set_value('company_name', element('company_name', element('data', $view))); ?>" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">사업자등록번호</label>
				<div class="col-sm-10 form-inline">
					<input type="text" class="form-control" name="company_reg_no" value="<?php echo set_value('company_reg_no', element('company_reg_no', element('data', $view))); ?>" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">대표자명</label>
				<div class="col-sm-10 form-inline">
					<input type="text" class="form-control" name="company_owner" value="<?php echo set_value('company_owner', element('company_owner', element('data', $view))); ?>" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">대표전화번호</label>
				<div class="col-sm-10 form-inline">
					<input type="text" class="form-control" name="company_phone" value="<?php echo set_value('company_phone', element('company_phone', element('data', $view))); ?>" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">팩스번호</label>
				<div class="col-sm-10 form-inline">
					<input type="text" class="form-control" name="company_fax" value="<?php echo set_value('company_fax', element('company_fax', element('data', $view))); ?>" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">통신판매업신고번호</label>
				<div class="col-sm-10 form-inline">
					<input type="text" class="form-control" name="company_retail_sale_no" value="<?php echo set_value('company_retail_sale_no', element('company_retail_sale_no', element('data', $view))); ?>" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">부가통신 사업자번호</label>
				<div class="col-sm-10 form-inline">
					<input type="text" class="form-control" name="company_added_sale_no" value="<?php echo set_value('company_added_sale_no', element('company_added_sale_no', element('data', $view))); ?>" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">사업장 우편번호</label>
				<div class="col-sm-10 form-inline">
					<input type="text" class="form-control" name="company_zipcode" value="<?php echo set_value('company_zipcode', element('company_zipcode', element('data', $view))); ?>" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">사업장 주소</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" name="company_address" value="<?php echo set_value('company_address', element('company_address', element('data', $view))); ?>" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">정보관리책임자명</label>
				<div class="col-sm-10 form-inline">
					<input type="text" class="form-control" name="company_admin_name" value="<?php echo set_value('company_admin_name', element('company_admin_name', element('data', $view))); ?>" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">정보관리책임자 email</label>
				<div class="col-sm-10 form-inline">
					<input type="text" class="form-control" name="company_admin_email" value="<?php echo set_value('company_admin_email', element('company_admin_email', element('data', $view))); ?>" />
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
			company_admin_email: {email : true }
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
