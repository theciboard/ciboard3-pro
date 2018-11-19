<style type="text/css">
.caption {height:46px; overflow:hidden;}
</style>

<div class="main_box1 pull-left">
	<div class="table-box">
	<!-- Default panel contents -->
		<div class="table-heading">
			<?php echo html_escape(element('board_name', element('board', $view))); ?>
			<div class="view-all pull-right">
				<a href="<?php echo board_url(element('brd_key', element('board', $view))); ?>" title="<?php echo html_escape(element('board_name', element('board', $view))); ?>">더보기 <i class="fa fa-angle-right"></i></a>
			</div>
		</div>
		<div class="table-image pd15">

		<?php
		if (element('latest', $view)) {
			foreach (element('latest', $view) as $key => $value) {
		?>

			<li>
				<a href="<?php echo element('url', $value); ?>" class="thumbnail" title="<?php echo html_escape(element('title', $value)); ?>">
					<img src="<?php echo element('thumb_url', $value); ?>" alt="<?php echo html_escape(element('title', $value)); ?>" title="<?php echo html_escape(element('title', $value)); ?>" class="img-responsive" style="width:<?php echo element('image_width', element('config', $view)); ?>px;height:<?php echo element('image_height', element('config', $view)); ?>;" />
				</a>
				<div class="caption"><p><?php echo html_escape(element('title', $value)); ?>
					<?php if (element('post_comment_count', $value)) { ?> <span class="latest_comment_count"> +<?php echo element('post_comment_count', $value); ?></span><?php } ?>
				</p></div>
			</li>
		<?php
			}
		}
		?>
		</div>
	</div>
</div>
