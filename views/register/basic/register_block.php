<?php $this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css'); ?>

<div class="final">
	<div class="table-box">
		<div class="table-heading">회원가입 안내</div>
		<div class="table-body">
			<div class="msg_content">
				안녕하세요,<br />
				현재 이 사이트는 회원가입 기능이 차단되어 있습니다.<br />
				감사합니다.<br />
				<p class="btn_final">
					<a href="<?php echo site_url(); ?>" class="btn btn-danger" title="<?php echo html_escape($this->cbconfig->item('site_title'));?>">홈페이지로 이동</a>
				</p>
			</div>
		</div>
	</div>
</div>
