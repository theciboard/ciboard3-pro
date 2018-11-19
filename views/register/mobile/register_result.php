<?php $this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css'); ?>

<div class="final">
	<div class="table-box">
		<div class="table-heading">회원가입을 축하합니다</div>
		<div class="table-body register_result">
			<div class="msg_content">
			<span class="text-primary"><?php echo html_escape($this->session->flashdata('nickname')); ?></span>님의 회원가입을 진심으로 축하드립니다. <br />
				<?php echo $this->session->flashdata('email_auth_message'); ?>
				<p class="btn_final">
					<a href="<?php echo site_url(); ?>" class="btn btn-danger" title="<?php echo html_escape($this->cbconfig->item('site_title'));?>">홈페이지로 이동</a>
				</p>
			</div>
		</div>
	</div>
</div>
