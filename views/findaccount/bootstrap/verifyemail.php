<?php $this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css'); ?>

<div class="final col-md-6 col-md-offset-3">
	<div class="panel panel-default">
		<div class="panel-heading">이메일 인증</div>
		<div class="panel-body">
			<?php echo element('message', $view); ?>
			<p class="btn_final">
				<a href="<?php echo site_url(); ?>" class="btn btn-danger btn-sm" title="<?php echo html_escape($this->cbconfig->item('site_title'));?>">홈페이지로 이동</a>
			</p>
		</div>
	</div>
</div>
