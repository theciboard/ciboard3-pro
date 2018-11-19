<style type="text/css">
body { padding:0; margin:0; }
#img { position:relative;top:0;left:0;cursor:move; }
</style>
<div>
	<img src="<?php echo element('imgurl', $view); ?>" id="img" class="draggable" alt="이미지 크게 보기" title="이미지 크게 보기" />
</div>

<script type="text/javascript">
//<![CDATA[
$(document).ready(function() {
	$('#img').one('load', function() {

		var win_w = parseInt($('#img').width()) + 30;
		var win_h = parseInt($('#img').height()) + 70;
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

	}).each(function() {
		if (this.complete) {
			$(this).load();
		}
	});;
});
$(function() {
	var is_draggable = false;
	var x = y = 0;
	var pos_x = pos_y = 0;

	$('.draggable').mousemove(function(e) {
		if (is_draggable) {
			x = parseInt($(this).css("left")) - (pos_x - e.pageX);
			y = parseInt($(this).css("top")) - (pos_y - e.pageY);

			pos_x = e.pageX;
			pos_y = e.pageY;

			$(this).css({ "left" : x, "top" : y });
		}

		return false;
	});

	$('.draggable').mousedown(function(e) {
		pos_x = e.pageX;
		pos_y = e.pageY;
		is_draggable = true;
		return false;
	});

	$('.draggable').mouseup(function() {
		is_draggable = false;
		return false;
	});

	$('.draggable').dblclick(function() {
		window.close();
	});
});
//]]>
</script>
