<div class="tag-clouds mb30">
	<div class="headline">
		<h3>Tag Clouds</h3>
	</div>
	<ul>
	<?php
	$startdate = cdate('Y-m-d' , ctimestamp()-24*60*60*60);
	$tags = $this->board->get_popular_tags($startdate, $limit = 15);
	if ($tags) {
		foreach ($tags as $value) {
	?>
		<li><a href="<?php echo site_url('tags?tag=' . html_escape(element('pta_tag', $value))); ?>" title="<?php echo html_escape(element('pta_tag', $value)); ?>"><?php echo html_escape(element('pta_tag', $value)); ?></a></li>
	<?php
		}
	}
	?>
	</ul>
</div>