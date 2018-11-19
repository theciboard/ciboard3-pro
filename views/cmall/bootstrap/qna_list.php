<div class="alert alert-auto-close alert-dismissible alert-cmall-qna-list-message" style="display:none;"><button type="button" class="close alertclose">×</button><span class="alert-cmall-qna-list-message-content"></span></div>

<?php
$i = 0;
if (element('list', element('data', $view))) {
	foreach (element('list', element('data', $view)) as $result) {
?>
	<div class="product-feedback">
		<div class="qna-wr">
			<p class="item_qna_title col-lg-8" onclick="return qna_open(this);"><i class="fa fa-comments-o"></i> <?php echo html_escape(element('cqa_title', $result)); ?></p>
			<ul class="col-lg-4 qna-info">
				<li><span class="sd-only">작성자</span> <?php echo element('display_name', $result); ?></li>
				<li><i class="fa fa-clock-o" aria-hidden="true"></i> <?php echo element('display_datetime', $result); ?></li>
				<li><?php echo (element('cqa_reply_mem_id', $result)) ? '<span class="qna-done">답변완료</span>' : '<span class="qna-yet">답변대기</span>';?></li>
			</ul>
		</div>
		<div class="feedback-box qna-content ">
			<div class="mb10"><div class="bold">문의내용</div> <?php echo element('content', $result); ?></div>
			<div class="qa-ans"><div class="bold">답변내용</div> <?php echo (element('cqa_reply_mem_id', $result)) ? element('reply_content', $result) : '답변 대기중입니다.';?></div>
			<?php if (element('can_update', $result)) { ?>
				<a href="javascript:;" class="btn btn-xs btn-default" onClick="window.open('<?php echo site_url('cmall/qna_write/' . element('cit_id', $view) . '/' . element('cqa_id', $result) . '?page=' . $this->input->get('page')); ?>', 'qna_popup', 'width=750,height=770,scrollbars=1'); return false;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> 수정</a>
			<?php } ?>
			<?php if (element('can_delete', $result)) { ?>
				<a href="javascript:;" class="btn btn-xs btn-default" onClick="delete_cmall_qna('<?php echo element('cqa_id', $result); ?>', '<?php echo element('cit_id', $result); ?>', '<?php echo element('page', $view); ?>');"><i class="fa fa-trash-o" aria-hidden="true"></i> 삭제</a>
			<?php } ?>
		</div>
	</div>
<?php
	}
}
?>
<nav><?php echo element('paging', $view); ?></nav>
