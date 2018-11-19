<?php $this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css'); ?>

<h4>추천상품</h4>
<div class="cmall-list">
	<ul class="row">
	<?php
	if (element('type1', $view)) {
		foreach (element('type1', $view) as $item) {
	?>
		<li class="col-xs-6 col-sm-6 col-md-4 col-lg-4 cmall-list-col">
			<div class="thumbnail">
				<a href="<?php echo cmall_item_url(element('cit_key', $item)); ?>" title="<?php echo html_escape(element('cit_name', $item)); ?>">
					<img alt="<?php echo html_escape(element('cit_name', $item)); ?>" title="<?php echo html_escape(element('cit_name', $item)); ?>" src="<?php echo thumb_url('cmallitem', element('cit_file_1', $item), 420, 300); ?>">
				</a>
				<p class="cmall-tit"><a href="<?php echo cmall_item_url(element('cit_key', $item)); ?>" title="<?php echo html_escape(element('cit_name', $item)); ?>"><?php echo html_escape(element('cit_name', $item)); ?></a></p>
				<p class="cmall-txt"><?php echo element('cit_summary', $item); ?></p>
				<ul class="cmall-detail">
					<li><i class="fa fa-heart"></i> <span class="detail-tit">찜</span> <?php echo number_format(element('cit_wish_count', $item)); ?></li>
					<li><i class="fa fa-shopping-cart"></i> <span class="detail-tit">구매</span> <?php echo number_format(element('cit_sell_count', $item)); ?></li>
					<li class="cmall-price pull-right"><span><?php echo number_format(element('cit_price', $item)); ?></span>원</li>
				</ul>
			</div>
		</li>
	<?php
		}
	}
	?>
	</ul>
</div>

<h4>인기상품</h4>
<div class="cmall-list">
	<ul class="row">
	<?php
	if (element('type2', $view)) {
		foreach (element('type2', $view) as $item) {
	?>
		<li class="col-xs-6 col-sm-6 col-md-4 col-lg-4 cmall-list-col">
			<div class="thumbnail">
				<a href="<?php echo cmall_item_url(element('cit_key', $item)); ?>" title="<?php echo html_escape(element('cit_name', $item)); ?>">
					<img alt="<?php echo html_escape(element('cit_name', $item)); ?>" title="<?php echo html_escape(element('cit_name', $item)); ?>" src="<?php echo thumb_url('cmallitem', element('cit_file_1', $item), 420, 300); ?>">
				</a>
				<p class="cmall-tit"><a href="<?php echo cmall_item_url(element('cit_key', $item)); ?>" title="<?php echo html_escape(element('cit_name', $item)); ?>"><?php echo html_escape(element('cit_name', $item)); ?></a></p>
				<p class="cmall-txt"><?php echo element('cit_summary', $item); ?></p>
				<ul class="cmall-detail">
					<li><i class="fa fa-heart"></i> <span class="detail-tit">찜</span> <?php echo number_format(element('cit_wish_count', $item)); ?></li>
					<li><i class="fa fa-shopping-cart"></i> <span class="detail-tit">구매</span> <?php echo number_format(element('cit_sell_count', $item)); ?></li>
					<li class="cmall-price pull-right"><span><?php echo number_format(element('cit_price', $item)); ?></span>원</li>
				</ul>
			</div>
		</li>
	<?php
		}
	}
	?>
	</ul>
</div>

<h4>최신상품</h4>
<div class="cmall-list">
	<ul class="row">
	<?php
	if (element('type3', $view)) {
		foreach (element('type3', $view) as $item) {
	?>
		<li class="col-xs-6 col-sm-6 col-md-4 col-lg-4 cmall-list-col">
			<div class="thumbnail">
				<a href="<?php echo cmall_item_url(element('cit_key', $item)); ?>" title="<?php echo html_escape(element('cit_name', $item)); ?>">
					<img alt="<?php echo html_escape(element('cit_name', $item)); ?>" title="<?php echo html_escape(element('cit_name', $item)); ?>" src="<?php echo thumb_url('cmallitem', element('cit_file_1', $item), 420, 300); ?>">
				</a>
				<p class="cmall-tit"><a href="<?php echo cmall_item_url(element('cit_key', $item)); ?>" title="<?php echo html_escape(element('cit_name', $item)); ?>"><?php echo html_escape(element('cit_name', $item)); ?></a></p>
				<p class="cmall-txt"><?php echo element('cit_summary', $item); ?></p>
				<ul class="cmall-detail">
					<li><i class="fa fa-heart"></i> <span class="detail-tit">찜</span> <?php echo number_format(element('cit_wish_count', $item)); ?></li>
					<li><i class="fa fa-shopping-cart"></i> <span class="detail-tit">구매</span> <?php echo number_format(element('cit_sell_count', $item)); ?></li>
					<li class="cmall-price pull-right"><span><?php echo number_format(element('cit_price', $item)); ?></span>원</li>
				</ul>
			</div>
		</li>
	<?php
		}
	}
	?>
	</ul>
</div>

<h4>할인상품</h4>
<div class="cmall-list">
	<ul class="row">
	<?php
	if (element('type4', $view)) {
		foreach (element('type4', $view) as $item) {
	?>
		<li class="col-xs-6 col-sm-6 col-md-4 col-lg-4 cmall-list-col">
			<div class="thumbnail">
				<a href="<?php echo cmall_item_url(element('cit_key', $item)); ?>" title="<?php echo html_escape(element('cit_name', $item)); ?>">
					<img alt="<?php echo html_escape(element('cit_name', $item)); ?>" title="<?php echo html_escape(element('cit_name', $item)); ?>" src="<?php echo thumb_url('cmallitem', element('cit_file_1', $item), 420, 300); ?>">
				</a>
				<p class="cmall-tit"><a href="<?php echo cmall_item_url(element('cit_key', $item)); ?>" title="<?php echo html_escape(element('cit_name', $item)); ?>"><?php echo html_escape(element('cit_name', $item)); ?></a></p>
				<p class="cmall-txt"><?php echo element('cit_summary', $item); ?></p>
				<ul class="cmall-detail">
					<li><i class="fa fa-heart"></i> <span class="detail-tit">찜</span> <?php echo number_format(element('cit_wish_count', $item)); ?></li>
					<li><i class="fa fa-shopping-cart"></i> <span class="detail-tit">구매</span> <?php echo number_format(element('cit_sell_count', $item)); ?></li>
					<li class="cmall-price pull-right"><span><?php echo number_format(element('cit_price', $item)); ?></span>원</li>
				</ul>
			</div>
		</li>
	<?php
		}
	}
	?>
	</ul>
</div>
