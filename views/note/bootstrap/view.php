<?php $this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css'); ?>

<div class="modal-header">
	<h4 class="modal-title">쪽지함</h4>
</div>

<div class="modal-body">
	<div class="btn-group btn-group-justified" role="group" aria-label="...">
		<div class="btn-group" role="group">
			<a href="<?php echo site_url('note/lists/recv'); ?>" class="btn btn-default <?php echo element('type', $view) === 'recv' ? 'active' : ''; ?>">받은 쪽지</a>
		</div>
		<div class="btn-group" role="group">
			<a href="<?php echo site_url('note/lists/send'); ?>" class="btn btn-default <?php echo element('type', $view) === 'send' ? 'active' : ''; ?>">보낸 쪽지</a>
		</div>
		<div class="btn-group" role="group">
			<a href="<?php echo site_url('note/write'); ?>" class="btn btn-default">쪽지 쓰기</a>
		</div>
	</div>
	<div class="note-view mt20">
		<div class="note-view-title">
			<?php echo html_escape(element('nte_title', element('data', $view))); ?> <small><?php echo element('display_name', element('data', $view)); ?>, <?php echo display_datetime(element('nte_datetime', element('data', $view)), 'full'); ?> </small>
		</div>

		<?php if (element('nte_originname', element('data', $view))) { ?>
			<ul class="list-group">
				<li class="list-group-item"><i class="fa fa-download"></i> <a href="<?php echo element('download_link', element('data', $view)); ?>"><?php echo html_escape(element('nte_originname', element('data', $view))); ?></a></li>
			</ul>
		<?php } ?>

		<div class="note-contents">
			<?php echo element('content', element('data', $view)); ?>
		</div>
	</div>
	<div class="pull-right" aria-label="...">
		<button type="button" class="btn btn-success btn-sm" onClick="history.back();">이전페이지</button>

		<?php if (element('userid', element('data', $view))) { ?><a href="<?php echo site_url('note/write/' . html_escape(element('userid', element('data', $view)))); ?>" class="btn btn-danger btn-sm"><?php echo element('type', $view) === 'send' ? "쪽지쓰기":"답장"; ?></a><?php } ?>
		<button class="btn btn-default btn-sm" onClick="window.close();">닫기</button>
	</div>
</div>
