<?php $this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css'); ?>

<div class="mypage">
	<ul class="nav nav-tabs">
		<li><a href="<?php echo site_url('mypage'); ?>" title="마이페이지">마이페이지</a></li>
		<li><a href="<?php echo site_url('mypage/post'); ?>" title="나의 작성글">나의 작성글</a></li>
		<?php if ($this->cbconfig->item('use_point')) { ?>
			<li><a href="<?php echo site_url('mypage/point'); ?>" title="포인트">포인트</a></li>
		<?php } ?>
		<li><a href="<?php echo site_url('mypage/followinglist'); ?>" title="팔로우">팔로우</a></li>
		<li><a href="<?php echo site_url('mypage/like_post'); ?>" title="내가 추천한 글">추천</a></li>
		<li class="active"><a href="<?php echo site_url('mypage/scrap'); ?>" title="나의 스크랩">스크랩</a></li>
		<li><a href="<?php echo site_url('mypage/loginlog'); ?>" title="나의 로그인기록">로그인기록</a></li>
		<li><a href="<?php echo site_url('membermodify'); ?>" title="정보수정">정보수정</a></li>
		<li><a href="<?php echo site_url('membermodify/memberleave'); ?>" title="탈퇴하기">탈퇴하기</a></li>
	</ul>

	<div class="page-header">
		<h4>스크랩 <small>총: <?php echo number_format(element('total_rows', element('data', $view), 0)); ?>건</small></h4>
	</div>

	<?php
	echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
	echo show_alert_message(element('alert_message', $view), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
	?>

	<table class="table table-striped">
		<thead>
			<tr>
				<th>번호</th>
				<th>게시판</th>
				<th>제목</th>
				<th>보관일시</th>
				<th>제목수정</th>
				<th>삭제</th>
			</tr>
		</thead>
		<tbody>
		<?php
		if (element('list', element('data', $view))) {
			foreach (element('list', element('data', $view)) as $result) {
		?>
			<tr>
				<td><?php echo element('num', $result); ?></td>
				<td><a href="<?php echo element('board_url', $result); ?>" title="<?php echo html_escape(element('board_name', element('board', $result))); ?>"><?php echo html_escape(element('board_name', element('board', $result))); ?></a></td>
				<td>
					<div class="title-a-<?php echo element('scr_id', $result); ?>">
					<a href="<?php echo element('post_url', $result); ?>" title="<?php echo element('scr_title', $result) ? html_escape(element('scr_title', $result)) : html_escape(element('post_title', $result)); ?>"><?php echo element('scr_title', $result) ? html_escape(element('scr_title', $result)) : html_escape(element('post_title', $result)); ?></a>
						<?php if (element('post_comment_count', $result)) { ?><span class="label label-success"><?php echo element('post_comment_count', $result); ?> comments</span><?php } ?>
						<?php if (element('post_like', $result)) { ?><span class="label label-info">+ <?php echo element('post_like', $result); ?></span><?php } ?>
						<?php if (element('post_dislike', $result)) { ?><span class="label label-danger">- <?php echo element('post_dislike', $result); ?></span><?php } ?>
					</div>
					<div class="title-b-<?php echo element('scr_id', $result); ?>" style="display:none;">
						<?php
						$attributes = array('class' => 'form-inline', 'name' => 'fscrap');
						echo form_open(current_full_url(), $attributes);
						?>
							<input type="hidden" name="scr_id" value="<?php echo element('scr_id', $result); ?>" />
							<input type="text" name="scr_title" class="form-control" value="<?php echo html_escape(element('scr_title', $result)); ?>" />
							<button class="btn btn-xs btn-primary" type="submit" >저장</button>
						<?php echo form_close(); ?>
					</div>
				</td>
				<td><?php echo display_datetime(element('scr_datetime', $result), 'full'); ?></td>
				<td><button class="btn btn-xs btn-success btn-scrap-modify" data-scrap-id="<?php echo element('scr_id', $result); ?>" type="button"><span class="fa fa-pencil"></span></button></td>
				<td><button class="btn btn-xs btn-danger btn-one-delete" type="button" data-one-delete-url = "<?php echo element('delete_url', $result); ?>"><span class="fa fa-trash"></span></button></td>
			</tr>
		<?php
			}
		}
		if ( ! element('list', element('data', $view))) {
		?>
			<tr>
				<td colspan="6" class="nopost">회원님이 스크랩하신 글이 없습니다</td>
			</tr>
		<?php
		}
		?>
		</tbody>
	</table>
	<nav><?php echo element('paging', $view); ?></nav>
</div>

<script type="text/javascript">
//<![CDATA[
$(document).on('click', '.btn-scrap-modify', function() {
	$('.title-a-' + $(this).attr('data-scrap-id')).toggle();
	$('.title-b-' + $(this).attr('data-scrap-id')).toggle();
});
//]]>
</script>
