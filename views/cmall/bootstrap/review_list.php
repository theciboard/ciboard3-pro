<div class="alert alert-auto-close alert-dismissible alert-cmall-review-list-message" style="display:none;"><button type="button" class="close alertclose">×</button><span class="alert-cmall-review-list-message-content"></span></div>

<?php
$i = 0;
if (element('list', element('data', $view))) {
	foreach (element('list', element('data', $view)) as $result) {
?>
	<div class="product-feedback">
		<div class="review-wr">
			<p class="item_review_title col-lg-8" onclick="return review_open(this);"><i class="fa fa-comments-o"></i> <?php echo html_escape(element('cre_title', $result)); ?></p>
			<ul class="col-lg-4 review-info">
				<li><span class="sd-only">작성자</span> <?php echo element('display_name', $result); ?></li>
				<li><i class="fa fa-clock-o" aria-hidden="true"></i> <?php echo element('display_datetime', $result); ?></li>
				<?php if (element('cre_score', $result) >=1 && element('cre_score', $result) <=5) { ?>
					<li><span class="sd-only">평점</span> <img src="<?php echo element('view_skin_url', $view); ?>/images/star<?php echo element('cre_score', $result); ?>.png" alt="평점" title="평점" /></li>
				<?php } ?>
			</ul>
		</div>
		<div class="feedback-box review-content">
			<?php echo element('content', $result); ?>
			<?php if (element('can_update', $result)) { ?>
				<a href="javascript:;" class="btn btn-xs btn-default" onClick="window.open('<?php echo site_url('cmall/review_write/' . element('cit_id', $view) . '/' . element('cre_id', $result) . '?page=' . $this->input->get('page')); ?>', 'review_popup', 'width=750,height=770,scrollbars=1'); return false;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> 수정</a>
			<?php } ?>
			<?php if (element('can_delete', $result)) { ?>
				<a href="javascript:;" class="btn btn-xs btn-default" onClick="delete_cmall_review('<?php echo element('cre_id', $result); ?>', '<?php echo element('cit_id', $result); ?>', '<?php echo element('page', $view); ?>');"><i class="fa fa-trash-o" aria-hidden="true"></i> 삭제</a>
			<?php } ?>
		</div>
	</div>
<?php
	}
}
?>
<nav><?php echo element('paging', $view); ?></nav>
