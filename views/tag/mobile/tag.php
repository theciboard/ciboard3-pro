<?php $this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css'); ?>

<h3>태그 : <?php echo html_escape($this->input->get('tag')); ?></h3>
<div class="media-box mt20">
<?php
if (element('list', element('data', $view))) {
	foreach (element('list', element('data', $view)) as $result) {
?>
	<div class="media">
		<?php
		if (element('images', $result)) {
		?>
			<div class="media-left">
				<a href="<?php echo element('post_url', $result); ?>" title="<?php echo html_escape(element('post_title', $result)); ?>">
				<img class="media-object" src="<?php echo thumb_url('post', element('pfi_filename', element('images', $result)), 100, 80); ?>" alt="<?php echo html_escape(element('post_title', $result)); ?>" title="<?php echo html_escape(element('post_title', $result)); ?>" style="width:100px;height:80px;" />
				</a>
			</div>
		<?php
		}
		?>
		<div class="media-body">
			<h4 class="media-heading"><a href="<?php echo element('post_url', $result); ?>" title="<?php echo html_escape(element('post_title', $result)); ?>"><?php echo html_escape(element('post_title', $result)); ?></a></h4>
			<div class="media-comment">
				<?php if (element('post_comment_count', $result)) { ?><span class="label label-info label-xs"><?php echo element('post_comment_count', $result); ?> comments</span><?php } ?>
				<a href="<?php echo element('post_url', $result); ?>" target="_blank" title="<?php echo html_escape(element('post_title', $result)); ?>"><span class="label label-default label-xs">새창</span></a>
			</div>
			<p><?php echo element('content', $result); ?></p>
			<p class="media-info">
				<span><?php echo element('display_name', $result); ?></span>
				<span><i class="fa fa-clock-o"></i> <?php echo element('display_datetime', $result); ?></span>
			</p>
		</div>
	</div>
<?php
	}
}
if ( ! element('list', element('data', $view))) {
?>
	<div class="media">
		<div class="media-body nopost">검색 결과가 없습니다</div>
	</div>
<?php
}
?>
</div>
<nav><?php echo element('paging', $view); ?></nav>
