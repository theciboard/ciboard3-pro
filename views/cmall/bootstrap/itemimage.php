<?php $this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css'); ?>


<div class="modal-header">
	<h4 class="modal-title"><?php echo html_escape(element('cit_name', element('data', $view))); ?> 이미지 크게 보기</h4>
</div>
<div class="slidewrap" id="item-image">
	<ul class="bxslider">
		<?php
		for ($i =1; $i <=10; $i++) {
			if ( ! element('cit_file_' . $i, element('data', $view))) {
				continue;
			}
		?>
			<li><img src="<?php echo thumb_url('cmallitem', element('cit_file_' . $i, element('data', $view)), 1000); ?>" alt="<?php echo html_escape(element('cit_name', element('data', $view))); ?>" title="<?php echo html_escape(element('cit_name', element('data', $view))); ?>" class="draggable" /></li>
		<?php
		}
		?>
	</ul>
	<div id="bx-pager">
		<?php
		$k= 0;
		for ($i =1; $i <=10; $i++) {
			if ( ! element('cit_file_' . $i, element('data', $view))) {
				continue;
			}
		?>
			<a data-slide-index="<?php echo $k; ?>" href="javascript:;"><img src="<?php echo thumb_url('cmallitem', element('cit_file_' . $i, element('data', $view)), 80, 80); ?>" alt="<?php echo html_escape(element('cit_name', element('data', $view))); ?>" title="<?php echo html_escape(element('cit_name', element('data', $view))); ?>" style="width:80px;height:80px;" /></a>
		<?php
			$k++;
		}
		?>
	</div>
</div>
<script type="text/javascript">
//<![CDATA[
var win_w = 800;
var win_h = 1000;
var win_l = (screen.width - win_w) / 2;
var win_t = (screen.height - win_h) / 2;

if (win_w > screen.width) {
	win_l = 0;
	win_w = screen.width - 20;

	if (win_h > screen.height) {
		win_t = 0;
		win_h = screen.height - 40;
	}
}

if (win_h > screen.height) {
	win_t = 0;
	win_h = screen.height - 40;

	if (win_w > screen.width) {
		win_w = screen.width - 20;
		win_l = 0;
	}
}

window.moveTo(win_l, win_t);
window.resizeTo(win_w, win_h);

$(function() {
	$('.draggable').dblclick(function() {
		window.close();
	});
});
//]]>
</script>

<link rel="stylesheet" href="<?php echo base_url('assets/js/bxslider/jquery.bxslider.css'); ?>">
<script type="text/javascript" src="<?php echo base_url('assets/js/bxslider/jquery.bxslider.min.js'); ?>"></script>
<script type="text/javascript">
//<![CDATA[
$('.bxslider').bxSlider({
	pagerCustom: '#bx-pager'
});
//]]>
</script>
