<!-- 팝업레이어 시작 -->
<style type="text/css">
.popup_layer {position:absolute;border:1px solid #e9e9e9;background:#fff}
.popup_layer_footer {padding:10px 0;background:#000;color:#fff;text-align:right}
.popup_layer_footer button {margin-right:10px;padding:5px 10px;border:0;background:#4F4F4F;color:#FFFFFF}
</style>
<?php
if (element('popup', $view)) {
	foreach (element('popup', $view) as $key => $result) {
?>
	<div id="popup_layer_<?php echo element('pop_id', $result); ?>" class="popup_layer" style="top:<?php echo element('pop_top', $result); ?>px;left:<?php echo element('pop_left', $result); ?>px">
		<div class="popup_layer_con" style="width:<?php echo element('pop_width', $result); ?>px;height:<?php echo element('pop_height', $result); ?>px">
			<?php echo element('content', $result); ?>
		</div>
		<div class="popup_layer_footer">
			<?php if (element('pop_disable_hours', $result)) { ?>
				<button class="popup_layer_reject" data-wrapper-id="popup_layer_<?php echo element('pop_id', $result); ?>" data-disable-hours="<?php echo element('pop_disable_hours', $result); ?>"><strong><?php echo element('pop_disable_hours', $result); ?></strong>시간 동안 열지 않기</button>
			<?php } ?>
			<button class="popup_layer_close" data-wrapper-id="popup_layer_<?php echo element('pop_id', $result); ?>">닫기</button>
		</div>
	</div>
		<?php
		if (element('pop_is_center', $result) === '1') {
		?>
		<script type="text/javascript">
		//<![CDATA[
		popup_center_left_<?php echo element('pop_id', $result); ?> = $(window).scrollLeft() + ($(window).width() - <?php echo element('pop_width', $result); ?>) / 2
		$('#popup_layer_<?php echo element('pop_id', $result); ?>').css('left', popup_center_left_<?php echo element('pop_id', $result); ?>);
		//]]>
		</script>
<?php
		}
	}
}
?>
<script type="text/javascript">
//<![CDATA[
$(function() {
	$(document).on('click', '.popup_layer_reject', function() {
		var cookie_name = $(this).attr('data-wrapper-id');
		var cookie_expire = $(this).attr('data-disable-hours');
		$('#' + $(this).attr('data-wrapper-id')).hide();
		set_cookie(cookie_name, 1, cookie_expire, cb_cookie_domain);
	});
	$(document).on('click', '.popup_layer_close', function() {
		$('#' + $(this).attr('data-wrapper-id')).hide();
	});
});
//]]>
</script>
<!-- 팝업레이어 끝 -->
