<div class="box">
	<div class="box-header">
		<ul class="nav nav-tabs">
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir); ?>" onclick="return check_form_changed();">기본정보</a></li>
			<li role="presentation" class="active"><a href="<?php echo admin_url($this->pagedir . '/access'); ?>" onclick="return check_form_changed();">접근기능</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/point'); ?>" onclick="return check_form_changed();">포인트기능</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/general'); ?>" onclick="return check_form_changed();">일반기능 / 에디터</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/note'); ?>" onclick="return check_form_changed();">쪽지기능</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/notification'); ?>" onclick="return check_form_changed();">알림기능</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/company'); ?>" onclick="return check_form_changed();">회사정보</a></li>
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
			<div class="alert alert-dismissible alert-danger">
				사이트 접근 기능을 사용시, 현재 사용하고 계신 IP 가 차단되지 않도록 주의합니다. <br />
				현재 접속하신 아이피는 <?php echo $this->input->ip_address(); ?> 입니다. <br />
				만약 회원님의 아이피가 차단되어 사이트에 접근이 불가할 경우는 FTP 로 application/config/cb_config.php 파일을 열으셔서, $config['use_lock_ip'] 의 값을 false 로 변경해주시면 접근차단기능이 해제 됩니다. <br />
				현재 $config['use_lock_ip'] 의 값은 <strong><?php echo config_item('use_lock_ip') ? "true" : "false"; ?></strong>로 설정되어 있습니다 <br />
				<?php if (config_item('use_lock_ip') === false) { ?>
						현재 $config['use_lock_ip'] 이 false 로 설정되어있어서, 접근기능이 작동하지 않습니다. 접근기능을 사용하기 위해서는 $config['use_lock_ip'] 의 값을 true 로 변경해주세요
				<?php } ?>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">관리자 페이지 접근 가능 IP</label>
				<div class="col-sm-10">
					<textarea class="form-control" rows="5" name="admin_ip_whitelist"><?php echo set_value('admin_ip_whitelist', element('admin_ip_whitelist', element('data', $view))); ?></textarea>
					<span class="help-block">해당 아이피에서만 관리자 페이지에 접근이 가능합니다. <br />
						IP주소 입력형식 <br />
						1. 와일드카드(*) 사용가능(예: 192.168.0.*) <br />
						2. 하이픈(-)을 사용하여 대역으로 입력가능 <br />
						(단, 대역폭으로 입력할 경우 와일드카드 사용불가. 예: 192.168.0.1-192.168.0.254) <br />
						3.여러개의 항목은 줄을 바꾸어 입력하세요
					</span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">사이트 접근 불가 IP</label>
				<div class="col-sm-10">
					<textarea class="form-control" rows="5" name="site_ip_blacklist"><?php echo set_value('site_ip_blacklist', element('site_ip_blacklist', element('data', $view))); ?></textarea>
					<span class="help-block"> 해당 아이피에서는 사이트에 접근이 불가합니다. <br />
						IP주소 입력형식 <br />
						1. 와일드카드(*) 사용가능(예: 192.168.0.*) <br />
						2. 하이픈(-)을 사용하여 대역으로 입력가능 <br />
						(단, 대역폭으로 입력할 경우 와일드카드 사용불가. 예: 192.168.0.1-192.168.0.254) <br />
						3.여러개의 항목은 줄을 바꾸어 입력하세요
					</span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">사이트 접근 가능 IP</label>
				<div class="col-sm-10">
					<textarea class="form-control" rows="5" name="site_ip_whitelist"><?php echo set_value('site_ip_whitelist', element('site_ip_whitelist', element('data', $view))); ?></textarea>
					<span class="help-block">해당 아이피에서만 사이트에 접근이 가능합니다. <br />
						IP주소 입력형식 <br />
						1. 와일드카드(*) 사용가능(예: 192.168.0.*) <br />
						2. 하이픈(-)을 사용하여 대역으로 입력가능 <br />
						(단, 대역폭으로 입력할 경우 와일드카드 사용불가. 예: 192.168.0.1-192.168.0.254) <br />
						3.여러개의 항목은 줄을 바꾸어 입력하세요
					</span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">사이트 차단시 안내문 제목</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" name="site_blacklist_title" id="site_blacklist_title" value="<?php echo set_value('site_blacklist_title', element('site_blacklist_title', element('data', $view))); ?>" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">사이트 차단시 안내문 내용</label>
				<div class="col-sm-10">
					<?php echo display_dhtml_editor('site_blacklist_content', set_value('site_blacklist_content', element('site_blacklist_content', element('data', $view))), $classname = 'form-control dhtmleditor', $is_dhtml_editor = true, $editor_type = "smarteditor"); ?>
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
			site_blacklist_content : {'required_smarteditor' : true }
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
