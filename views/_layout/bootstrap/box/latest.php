<!-- sidebar_latest start -->
<div class="sidebar_latest">
	<div class="headline">
		<h3>최근 게시물</h3>
	</div>
	<ul>
		<?php
		$config = array(
			'skin' => 'basic2',
			'brd_id' => '',
			'limit' => 5,
			'length' => 20,
			'is_gallery' => '',
			'image_width' => '',
			'image_height' => '',
			'cache_minute' => 1,
		);
		echo $this->board->latest($config);
		?>
	</ul>
</div>
<!-- sidebar_latest end -->
<!-- sidebar_latest start -->
<div class="sidebar_latest">
	<div class="headline">
		<h3>최근 댓글</h3>
	</div>
	 <ul>
		<?php
		$config = array(
			'skin' => 'basic2',
			'brd_id' => '',
			'limit' => 5,
			'length' => 20,
			'cache_minute' => 1,
		);
		echo $this->board->latest_comment($config);
		?>
	</ul>
</div>
<!-- sidebar_latest end -->