<div class="sidebar_latest">
	<div class="headline">
		<h3><a href="<?php echo site_url('cmall/wishlist'); ?>" title="찜한 목록">찜한 목록</a></h3>
	</div>
	<ul>
	<?php
	$CI =& get_instance();
	$CI->load->library('cmalllib');
	$cart = $CI->cmalllib->get_my_wishlist('5');
	if ($cart) {
		foreach ($cart as $value) {
	?>
		<li><a href="<?php echo cmall_item_url(element('cit_key', $value)); ?>" title="<?php echo html_escape(element('cit_name', $value));?>"><?php echo html_escape(element('cit_name', $value));?></a></li>
	<?php
		}
	} else {
	?>
		<li>찜한 목록이 없습니다</li>
	<?php } ?>
	</ul>
</div>
