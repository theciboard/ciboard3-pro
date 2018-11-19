<style type="text/css">
.caption {height:46px; overflow:hidden;}
</style>

<div class="col-md-6">
	<div class="panel panel-default">
	<!-- Default panel contents -->
		<div class="panel-heading">
			<?php echo html_escape(element('board_name', element('board', $view))); ?>
			<div class="view-all pull-right">
				<a href="<?php echo board_url(element('brd_key', element('board', $view))); ?>" title="<?php echo html_escape(element('board_name', element('board', $view))); ?>">더보기 <i class="fa fa-angle-right"></i></a>
			</div>
		</div>
		<div class="panel-body row">
		<?php
			$i = 0;
			if (element('latest', $view)) {
				foreach (element('latest', $view) as $key => $value) {
		?>
			<div class="col-sm-3 col-xs-6 ">
				<div class="thumbnail">
					<a href="<?php echo element('url', $value); ?>" title="<?php echo html_escape(element('title', $value)); ?>">
						<img src="<?php echo element('thumb_url', $value); ?>" alt="<?php echo html_escape(element('title', $value)); ?>" title="<?php echo html_escape(element('title', $value)); ?>" class="img-responsive" style="width:<?php echo element('image_width', element('config', $view)); ?>px;height:<?php echo element('image_height', element('config', $view)); ?>;" />
					</a>
					<div class="caption">
						<p>
							<?php echo html_escape(element('title', $value)); ?>
							<?php if (element('post_comment_count', $value)) { ?> <span class="latest_comment_count"> +<?php echo element('post_comment_count', $value); ?></span><?php } ?>
						</p>
					</div>
				</div>
			</div>
		<?php
				}
			}
		?>
		</div>
	</div>
</div>
