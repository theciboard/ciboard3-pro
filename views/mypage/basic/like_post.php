<?php $this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css'); ?>

<div class="mypage">
	<ul class="nav nav-tabs">
		<li><a href="<?php echo site_url('mypage'); ?>" title="마이페이지">마이페이지</a></li>
		<li><a href="<?php echo site_url('mypage/post'); ?>" title="나의 작성글">나의 작성글</a></li>
		<?php if ($this->cbconfig->item('use_point')) { ?>
			<li><a href="<?php echo site_url('mypage/point'); ?>" title="포인트">포인트</a></li>
		<?php } ?>
		<li><a href="<?php echo site_url('mypage/followinglist'); ?>" title="팔로우">팔로우</a></li>
		<li class="active"><a href="<?php echo site_url('mypage/like_post'); ?>" title="내가 추천한 글">추천</a></li>
		<li><a href="<?php echo site_url('mypage/scrap'); ?>" title="나의 스크랩">스크랩</a></li>
		<li><a href="<?php echo site_url('mypage/loginlog'); ?>" title="나의 로그인기록">로그인기록</a></li>
		<li><a href="<?php echo site_url('membermodify'); ?>" title="정보수정">정보수정</a></li>
		<li><a href="<?php echo site_url('membermodify/memberleave'); ?>" title="탈퇴하기">탈퇴하기</a></li>
	</ul>

	<h3>추천(원글)</h3>

	<ul class="table-top mb10">
		<li><a href="<?php echo site_url('mypage/like_post'); ?>" class="btn btn-warning btn-sm" title="원글">원글</a></li>
		<li><a href="<?php echo site_url('mypage/like_comment'); ?>" class="btn btn-success btn-sm" title="댓글">댓글</a></li>
	</ul>

	<table class="table">
		<thead>
			<tr>
				<th>번호</th>
				<th>이미지</th>
				<th>제목</th>
				<th>날짜</th>
			</tr>
		</thead>
		<tbody>
		<?php
		if (element('list', element('data', $view))) {
			foreach (element('list', element('data', $view)) as $result) {
		?>
			<tr>
				<td><?php echo html_escape(element('num', $result)); ?></td>
				<td><?php if (element('post_image', $result)) { ?><img class="media-object" src="<?php echo thumb_url('post', element('pfi_filename', element('images', $result)), 50, 40); ?>" alt="<?php echo html_escape(element('post_title', $result)); ?>" title="<?php echo html_escape(element('post_title', $result)); ?>" style="width:50px;height:40px;" /><?php } ?></td>
				<td><a href="<?php echo element('post_url', $result); ?>" target="new" title="<?php echo html_escape(element('post_title', $result)); ?>"><?php echo html_escape(element('post_title', $result)); ?></a>
					<?php if (element('post_comment_count', $result)) { ?><span class="label label-success"><?php echo number_format(element('post_comment_count', $result)); ?> comments</span><?php } ?>
					<?php if (element('post_like', $result)) { ?><span class="label label-info">+ <?php echo number_format(element('post_like', $result)); ?></span><?php } ?>
					<?php if (element('post_dislike', $result)) { ?><span class="label label-danger">- <?php echo number_format(element('post_dislike', $result)); ?></span><?php } ?>
				</td>
				<td><?php echo display_datetime(element('post_datetime', $result), 'full'); ?></td>
			</tr>
		<?php
			}
		}
		if ( ! element('list', element('data', $view))) {
		?>
			<tr>
				<td colspan="4" class="nopost">회원님이 추천하신 글이 없습니다</td>
			</tr>
		<?php
		}
		?>
		</tbody>
	</table>
	<nav><?php echo element('paging', $view); ?></nav>
</div>
