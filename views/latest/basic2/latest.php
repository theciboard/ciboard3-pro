<?php
$i = 0;
if (element('latest', $view)) {
	foreach (element('latest', $view) as $key => $value) {
?>
		<li><a href="<?php echo element('url', $value); ?>" title="<?php echo html_escape(element('title', $value)); ?>"><?php echo html_escape(element('title', $value)); ?></a>
			<?php if (element('post_comment_count', $value)) { ?> <span class="latest_comment_count"> +<?php echo element('post_comment_count', $value); ?></span><?php } ?>
		</li>
<?php
	$i++;
	}
}

while ($i < element('latest_limit', $view)) {
?>
	<li>게시물이 없습니다</li>
<?php
	$i++;
}
