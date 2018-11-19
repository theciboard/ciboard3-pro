<div class="alert alert-auto-close alert-dismissible alert-comment-list-message" style="display:none;"><button type="button" class="close alertclose">×</button><span class="alert-comment-list-message-content"></span></div>

<?php
if (element('best_list', $view)) {
	foreach (element('best_list', $view) as $result) {
?>
	<div class="media" id="comment_<?php echo element('cmt_id', $result); ?>">
		<?php if (element('use_comment_profile', element('board', $view))) { ?>
			<div class="media-left">
				<img class="media-object member-photo" src="<?php echo element('member_photo_url', $result); ?>" width="64" height="64" alt="<?php echo html_escape(element('cmt_nickname', $result)); ?>" title="<?php echo html_escape(element('cmt_nickname', $result)); ?>" />
			</div>
		<?php } ?>
		<div class="media-body">
			<h4 class="media-heading">
				<?php if (element('is_admin', $view)) { ?><input type="checkbox" name="chk_comment_id[]" value="<?php echo element('cmt_id', $result); ?>" /><?php } ?>
				<span class="label label-warning">베플</span>
				<?php echo element('display_name', $result); ?>
				<span class="time"><i class="fa fa-clock-o"></i> <?php echo element('display_datetime', $result); ?></span>
				<?php if (element('display_ip', $result)) { ?>
					<span class="ip"><i class="fa fa-map-marker"></i> <?php echo element('display_ip', $result); ?></span>
				<?php } ?>
				<?php if (element('is_mobile', $result)) { ?><i class="fa fa-wifi"></i><?php } ?>
				<?php
				if ( ! element('post_del', element('post', $view))) {
				?>
					<span class="reply">
						<?php if (element('use_comment_like', element('board', $view))) { ?>
							<a class="good" href="javascript:;" id="btn-comment-like-<?php echo element('cmt_id', $result); ?>" onClick="comment_like('<?php echo element('cmt_id', $result); ?>', '1', 'comment-like-<?php echo element('cmt_id', $result); ?>');" title="추천하기"><i class="fa fa-thumbs-o-up fa-xs"></i> 추천 <span class="comment-like-<?php echo element('cmt_id', $result); ?>"><?php echo number_format(element('cmt_like', $result)); ?></span></a>
						<?php } ?>
						<?php if (element('use_comment_dislike', element('board', $view))) { ?>
							<a class="bad" href="javascript:;" id="btn-comment-dislike-<?php echo element('cmt_id', $result); ?>" onClick="comment_like('<?php echo element('cmt_id', $result); ?>', '2', 'comment-dislike-<?php echo element('cmt_id', $result); ?>');" title="비추하기"><i class="fa fa-thumbs-o-down fa-xs"></i> 비추 <span class="comment-dislike-<?php echo element('cmt_id', $result); ?>"><?php echo number_format(element('cmt_dislike', $result)); ?></span></a>
						<?php } ?>
						<?php if (element('use_comment_blame', element('board', $view)) && ( ! element('comment_blame_blind_count', element('board', $view)) OR element('cmt_blame', $result) < element('comment_blame_blind_count', element('board', $view)))) { ?>
							<a href="javascript:;" id="btn-blame" onClick="comment_blame('<?php echo element('cmt_id', $result); ?>', 'comment-blame-<?php echo element('cmt_id', $result); ?>');" title="신고하기"><i class="fa fa-bell fa-xs"></i>신고 <span class="comment-blame-<?php echo element('cmt_id', $result); ?>"><?php echo element('cmt_blame', $result) ? '+' . number_format(element('cmt_blame', $result)) : ''; ?></span></a>
						<?php } ?>

						<?php
						if (element('is_admin', $view) && element('use_comment_secret', element('board', $view))) {
							if (element('cmt_secret', $result)) {
						?>
							<a href="javascript:;" onClick="post_action('comment_secret', '<?php echo element('cmt_id', $result); ?>', '0');" title="비밀글 해제하기"><i class="fa fa-lock"></i></a>
						<?php } else { ?>
							<a href="javascript:;" onClick="post_action('comment_secret', '<?php echo element('cmt_id', $result); ?>', '1');" title="비밀글로 설정하기"><i class="fa fa-unlock"></i></a>
						<?php
								}
							}
						?>
					</span>
				<?php } ?>
			</h4>
			<div class="bg-success" style="padding:10px 5px;"><?php echo element('content', $result); ?></div>
		</div>
	</div>
<?php
	}
}
if (element('list', element('data', $view))) {
	foreach (element('list', element('data', $view)) as $result) {
?>
	<div class="media" id="comment_<?php echo element('cmt_id', $result); ?>" style="padding-left:<?php echo element('cmt_depth', $result); ?>px;">
		<?php if (element('use_comment_profile', element('board', $view))) { ?>
			<div class="media-left">
				<img class="media-object member-photo" src="<?php echo element('member_photo_url', $result); ?>" width="64" height="64" alt="<?php echo html_escape(element('cmt_nickname', $result)); ?>" title="<?php echo html_escape(element('cmt_nickname', $result)); ?>" />
			</div>
		<?php } ?>
		<div class="media-body">
			<h4 class="media-heading">
				<?php if (element('is_admin', $view)) { ?><input type="checkbox" name="chk_comment_id[]" value="<?php echo element('cmt_id', $result); ?>" /><?php } ?>
				<?php echo element('display_name', $result); ?>
				<span class="time"><i class="fa fa-clock-o"></i> <?php echo element('display_datetime', $result); ?></span>
				<?php if (element('display_ip', $result)) { ?>
					<span class="ip"><i class="fa fa-map-marker"></i> <?php echo element('display_ip', $result); ?></span>
				<?php } ?>
				<?php if (element('is_mobile', $result)) { ?><i class="fa fa-wifi"></i><?php } ?>
				<?php
				if ( ! element('post_del', element('post', $view)) && ! element('cmt_del', $result)) {
				?>
					<span class="reply">
						<?php if (element('use_comment_like', element('board', $view))) { ?>
							<a class="good" href="javascript:;" id="btn-comment-like-<?php echo element('cmt_id', $result); ?>" onClick="comment_like('<?php echo element('cmt_id', $result); ?>', '1', 'comment-like-<?php echo element('cmt_id', $result); ?>');" title="추천하기"><i class="fa fa-thumbs-o-up fa-xs"></i> 추천 <span class="comment-like-<?php echo element('cmt_id', $result); ?>"><?php echo number_format(element('cmt_like', $result)); ?></span></a>
						<?php } ?>
						<?php if (element('use_comment_dislike', element('board', $view))) { ?>
							<a class="bad" href="javascript:;" id="btn-comment-dislike-<?php echo element('cmt_id', $result); ?>" onClick="comment_like('<?php echo element('cmt_id', $result); ?>', '2', 'comment-dislike-<?php echo element('cmt_id', $result); ?>');" title="비추하기"><i class="fa fa-thumbs-o-down fa-xs"></i> 비추 <span class="comment-dislike-<?php echo element('cmt_id', $result); ?>"><?php echo number_format(element('cmt_dislike', $result)); ?></span></a>
						<?php } ?>
						<?php if (element('use_comment_blame', element('board', $view)) && ( ! element('comment_blame_blind_count', element('board', $view)) OR element('cmt_blame', $result) < element('comment_blame_blind_count', element('board', $view)))) { ?>
							<a href="javascript:;" id="btn-blame" onClick="comment_blame('<?php echo element('cmt_id', $result); ?>', 'comment-blame-<?php echo element('cmt_id', $result); ?>');" title="신고하기"><i class="fa fa-bell fa-xs"></i><span class="comment-blame-<?php echo element('cmt_id', $result); ?>"><?php echo element('cmt_blame', $result) ? '+' . number_format(element('cmt_blame', $result)) : ''; ?></span></a>
						<?php } ?>
						<?php if (element('can_reply', $result)) { ?>
							<a href="javascript:;" onClick="comment_box('<?php echo element('cmt_id', $result); ?>', 'c'); return false;">답변</a>
						<?php } ?>
						<?php if (element('can_update', $result)) { ?>
							<a href="javascript:;" onClick="comment_box('<?php echo element('cmt_id', $result); ?>', 'cu'); return false;">수정</a>
						<?php } ?>
						<?php if (element('can_delete', $result)) { ?>
							<a href="javascript:;" onClick="delete_comment('<?php echo element('cmt_id', $result); ?>', '<?php echo element('post_id', $result); ?>', '<?php echo element('page', $view); ?>');">삭제</a>
						<?php } ?>
						<?php
						if (element('is_admin', $view) && element('use_comment_secret', element('board', $view))) {
							if (element('cmt_secret', $result)) {
						?>
								<a href="javascript:;" onClick="post_action('comment_secret', '<?php echo element('cmt_id', $result); ?>', '0');"><i class="fa fa-lock"></i></a>
						<?php } else { ?>
								<a href="javascript:;" onClick="post_action('comment_secret', '<?php echo element('cmt_id', $result); ?>', '1');"><i class="fa fa-unlock"></i></a>
						<?php
							}
						}
						?>
					</span>
				<?php
				}
				?>
			</h4>
			<?php echo element('content', $result); ?>
			<?php if (element('lucky', $result)) { ?><div class="lucky"><i class="fa fa-star"></i> <?php echo element('lucky', $result); ?></div><?php } ?>
			<span id="edit_<?php echo element('cmt_id', $result); ?>"></span><!-- 수정 -->
			<span id="reply_<?php echo element('cmt_id', $result); ?>"></span><!-- 답변 -->
			<input type="hidden" value="<?php echo element('cmt_secret', $result); ?>" id="secret_comment_<?php echo element('cmt_id', $result); ?>" />
			<textarea id="save_comment_<?php echo element('cmt_id', $result); ?>" style="display:none"><?php echo html_escape(element('cmt_content', $result)); ?></textarea>
		</div>
	</div>
<?php
	}
}
?>
<nav><?php echo element('paging', $view); ?></nav>
