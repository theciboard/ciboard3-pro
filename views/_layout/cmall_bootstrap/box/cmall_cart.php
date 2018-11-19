<div class="sidebar_latest">
	<div class="headline">
		<h3><a href="<?php echo site_url('cmall/cart'); ?>">장바구니</a></h3>
	</div>
	<ul>
	<?php
	$CI =& get_instance();
	$CI->load->library('cmalllib');
	$CI->load->helper('cmall');
	$cart = $CI->cmalllib->get_my_cart('5');
	if ($cart) {
		foreach ($cart as $value) {
	?>
		<li><a href="<?php echo cmall_item_url(element('cit_key', $value)); ?>" title="<?php echo html_escape(element('cit_name', $value));?>"><?php echo html_escape(element('cit_name', $value));?></a></li>
	<?php
		}
	} else {
	?>
		<li>장바구니에 담긴 상품이 없습니다</li>
	<?php } ?>
	</ul>
</div>
