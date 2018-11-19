<h3><?php echo html_escape(element('doc_title', element('data', $view))); ?></h3>

<?php echo element('content', element('data', $view)); ?>

<?php if ($this->member->is_admin() === 'super') { ?>
	<div class="pull-right">
		<a href="<?php echo admin_url('page/document/write/' . element('doc_id', element('data', $view))); ?>" class="btn btn-danger btn-sm" target="_blank">내용수정</a>
	</div>
<?php } ?>
