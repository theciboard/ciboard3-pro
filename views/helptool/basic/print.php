<style type="text/css">
body { padding:10px; margin:0; }
div#post_print { border:1px solid #D8D8D8; padding:20px; }
div#post_print div { margin:0 0 3px 0; }
div#post_print .print_subject { font-size:15px; height:30px; font-weight:bold; margin:0 0 10px 0; border-bottom:1px solid #D8D8D8; }
div#post_print .print_name { font-size:12px; height:20px; }
div#post_print .print_date { font-size:12px; height:20px; }
div#post_print .print_content { font-size:12px; border-top:1px solid #ddd; padding:20px 10px; line-height:20px; }
div#post_print .print_label { float:left; width:50px; font-weight:bold; }
</style>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function () {
	window.print();
});
//]]>
</script>

<div id="post_print">
	<div class="print_subject"><?php echo html_escape(element('post_title', element('post', $view))); ?></div>
	<div class="print_name"><span class="print_label">글쓴이</span> : <?php echo html_escape(element('post_nickname', element('post', $view))); ?> </div>
	<div class="print_date"><span class="print_label">작성일</span> : <?php echo element('post_datetime', element('post', $view)); ?></div>
	<div class="print_url"><span class="print_label">글주소</span> : <?php echo element('post_url', $view); ?></div>
	<div class="print_content">
		<div class="contents-view">
			<div class="contents-view-img">
			<?php
			if (element('file_image', $view)) {
				foreach (element('file_image', $view) as $key => $value) {
			?>
				<img src="<?php echo element('thumb_image_url', $value); ?>" alt="<?php echo html_escape(element('pfi_originname', $value)); ?>" title="<?php echo html_escape(element('pfi_originname', $value)); ?>" class="view_full_image" data-origin-image-url="<?php echo element('origin_image_url', $value); ?>" />
			<?php
				}
			}
			?>
			</div>
			<!-- 본문 내용 시작 -->
			<div id="post-content"><?php echo element('content', element('post', $view)); ?></div>
			<!-- 본문 내용 끝 -->
		</div>
	</div>
</div>
