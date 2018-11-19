<?php
$attributes = array('name' => 'finstall', 'class' => 'form-horizontal', 'id' => 'finstall', 'autocomplete' => 'off');
echo form_open(site_url('install/step5'), $attributes);
?>

<input type="hidden" name="agree" value="<?php echo $this->input->post('agree'); ?>" />
<div class="contents">
	<h2>관리자 정보 입력</h2>
	<?php echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>'); ?>
	<div class="form-group">
		<label class="col-sm-3 control-label"><i class="glyphicon glyphicon-star"></i> Email</label>
		<div class="col-sm-9">
			<input type="email" class="form-control" name="mem_email" placeholder="Email Address" value="<?php echo set_value('mem_email'); ?>" />
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label"><i class="glyphicon glyphicon-star"></i> 패스워드</label>
		<div class="col-sm-9">
			<input type="password" class="form-control" name="mem_password" id="mem_password" placeholder="Password" />
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label"><i class="glyphicon glyphicon-star"></i> 패스워드 재입력</label>
		<div class="col-sm-9">
			<input type="password" class="form-control" name="mem_password_re" placeholder="Password" />
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label"><i class="glyphicon glyphicon-star"></i> User ID</label>
		<div class="col-sm-9">
			<input type="text" class="form-control" name="mem_userid" placeholder="User ID" value="admin" />
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label"><i class="glyphicon glyphicon-star"></i> 닉네임</label>
		<div class="col-sm-9">
			<input type="text" class="form-control" name="mem_nickname" placeholder="닉네임" value="관리자" />
		</div>
	</div>

	<h2>설치 테마</h2>
		<div class="form-group">
		<label class="col-sm-3 control-label"><i class="glyphicon glyphicon-star"></i> 스킨설정</label>
		<div class="col-sm-9">
			<label class="radio-inline" for="skin_bootstrap">
				<input type="radio" name="skin" id="skin_bootstrap" value="bootstrap" checked="checked" /> Bootstrap Theme
			</label>
			<label class="radio-inline" for="skin_basic">
				<input type="radio" name="skin" id="skin_basic" value="basic" /> Basic Theme
			</label>
		</div>
	</div>

	<h2>기본 게시판및 메뉴 자동생성</h2>
	<div class="form-group">
		<label class="col-sm-3 control-label"><i class="glyphicon glyphicon-star"></i> 자동생성</label>
		<div class="col-sm-9">
			<label class="radio-inline" for="autocreate">
				<input type="checkbox" name="autocreate" id="autocreate" value="1" checked="checked" /> 체크하시면 그룹, 게시판, 메뉴 등을 기본으로 자동생성합니다
			</label>
		</div>
	</div>

</div>

<!-- footer start -->
<div class="footer">
	<button type="submit" class="btn btn-default btn-xs pull-right">Next <i class="glyphicon glyphicon-chevron-right"></i></button>
</div>
<!-- footer end -->

<?php echo form_close(); ?>

<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.validate.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.validate.extension.js'); ?>"></script>

<script type="text/javascript">
//<![CDATA[
$(function() {
	$('#finstall').validate({
		rules: {
			mem_email: {required :true, email:true},
			mem_password: {required :true, minlength:4},
			mem_password_re : {required: true, equalTo : '#mem_password' },
			mem_userid: {required :true, minlength:3, maxlength:20},
			mem_nickname: {required :true}
		}
	});
});
//]]>
</script>
