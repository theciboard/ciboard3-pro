<?php $this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css'); ?>

<div class="final col-md-8 col-md-offset-2">
	<div class="panel panel-default">
		<div class="panel-heading">회원가입 안내</div>
		<div class="panel-body">
			안녕하세요,<br />
			현재 이 사이트는 회원가입 기능이 차단되어 있습니다.<br />
			감사합니다.<br />
			<p class="btn_final"><a href="<?php echo site_url(); ?>" class="btn btn-danger btn-sm" title="<?php echo html_escape($this->cbconfig->item('site_title'));?>">홈페이지로 이동</a></p>
		</div>
	</div>
</div>
