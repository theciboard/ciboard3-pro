<div class="alert alert-auto-close alert-dismissible alert-cmall-qna-list-message" style="display:none;"><button type="button" class="close alertclose">×</button><span class="alert-cmall-qna-list-message-content"></span></div>

<?php
$i = 0;
if (element('list', element('data', $view))) {
	foreach (element('list', element('data', $view)) as $result) {
?>
	<div class="product-feedback">
		<p class="item_qna_title" onclick="return qna_open(this);"><i class="fa fa-comments-o"></i> <?php echo html_escape(element('cqa_title', $result)); ?></p>
		<ul>
			<li>작성자: <?php echo element('display_name', $result); ?></li>
			<li>작성일 <?php echo element('display_datetime', $result); ?></li>
			<li>상태: <?php echo (element('cqa_reply_mem_id', $result)) ? '답변완료' : '답변대기중';?></li>
		</ul>
		<div class="feedback-box qna-content ">
			<div class="mb10"><div class="bold">[문의내용]</div> <?php echo element('content', $result); ?></div>
			<div><div class="bold">[답변내용]</div> <?php echo (element('cqa_reply_mem_id', $result)) ? element('reply_content', $result) : '답변 대기중입니다.';?></div>
			<?php if (element('can_update', $result)) { ?>
				<a href="javascript:;" class="btn btn-xs btn-success" onClick="window.open('<?php echo site_url('cmall/qna_write/' . element('cit_id', $view) . '/' . element('cqa_id', $result) . '?page=' . $this->input->get('page')); ?>', 'qna_popup', 'width=750,height=770,scrollbars=1'); return false;">수정</a>
			<?php } ?>
			<?php if (element('can_delete', $result)) { ?>
				<a href="javascript:;" class="btn btn-xs btn-danger" onClick="delete_cmall_qna('<?php echo element('cqa_id', $result); ?>', '<?php echo element('cit_id', $result); ?>', '<?php echo element('page', $view); ?>');">삭제</a>
			<?php } ?>
		</div>
	</div>
<?php
	}
} else {
?>
	<div class="product-feedback">아직 등록된 상품문의가 없습니다</div>
<?php } ?>

<nav><?php echo element('paging', $view); ?></nav>
