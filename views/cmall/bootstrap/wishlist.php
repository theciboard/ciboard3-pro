<?php $this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css'); ?>
<div id="wish-list">
	<h3>찜한 목록</h3>
	<div class="row">
		<ul class="table table-striped">

			<?php
			if (element('list', element('data', $view))) {
				foreach (element('list', element('data', $view)) as $result) {
			?>
				<li class="col-xs-6 col-md-3">
					<a href="<?php echo element('item_url', $result); ?>" title="<?php echo html_escape(element('cit_name', $result)); ?>"><img src="<?php echo thumb_url('cmallitem', element('cit_file_1', $result), 260, 260); ?>" alt="<?php echo html_escape(element('cit_name', $result)); ?>" title="<?php echo html_escape(element('cit_name', $result)); ?>" /></a>
					<a href="<?php echo element('item_url', $result); ?>" title="<?php echo html_escape(element('cit_name', $result)); ?>" class="prd-tit"><?php echo html_escape(element('cit_name', $result)); ?></a>
					<span class="prd-date"><i class="fa fa-clock-o" aria-hidden="true"></i> <?php echo display_datetime(element('cwi_datetime', $result), 'full'); ?></span>
					<button class="btn btn-xs btn-danger btn-one-delete" type="button" data-one-delete-url = "<?php echo element('delete_url', $result); ?>"><i class="fa fa-trash"></i> 삭제</button>
				</li>
			<?php
				}
			}
			if ( ! element('list', element('data', $view))) {
			?>
				<li class="nopost">보관 기록이 없습니다</li>
			<?php
			}
			?>
		</ul>
		<nav><?php echo element('paging', $view); ?></nav>
	</div>
</div>
