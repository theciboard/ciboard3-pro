<?php $this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css'); ?>

<div class="mypage">
	<ul class="nav nav-tabs">
		<li><a href="<?php echo site_url('mypage'); ?>" title="마이페이지">마이페이지</a></li>
		<li><a href="<?php echo site_url('mypage/post'); ?>" title="나의 작성글">나의 작성글</a></li>
		<?php if ($this->cbconfig->item('use_point')) { ?>
			<li><a href="<?php echo site_url('mypage/point'); ?>" title="포인트">포인트</a></li>
		<?php } ?>
		<li class="active"><a href="<?php echo site_url('mypage/followinglist'); ?>" title="팔로우">팔로우</a></li>
		<li><a href="<?php echo site_url('mypage/like_post'); ?>" title="내가 추천한 글">추천</a></li>
		<li><a href="<?php echo site_url('mypage/scrap'); ?>" title="나의 스크랩">스크랩</a></li>
		<li><a href="<?php echo site_url('mypage/loginlog'); ?>" title="나의 로그인기록">로그인기록</a></li>
		<li><a href="<?php echo site_url('membermodify'); ?>" title="정보수정">정보수정</a></li>
		<li><a href="<?php echo site_url('membermodify/memberleave'); ?>" title="탈퇴하기">탈퇴하기</a></li>
	</ul>

	<h3>Followed</h3>

	<ul class="table-top mb10">
		<li><a href="<?php echo site_url('mypage/followinglist'); ?>" class="btn btn-warning btn-sm" title="Following">Following (<?php echo number_format(element('following_total_rows', $view)); ?>)</a></li>
		<li><a href="<?php echo site_url('mypage/followedlist'); ?>" class="btn btn-success btn-sm" title="Followed">Followed (<?php echo number_format(element('followed_total_rows', $view)); ?>)</a></li>
	</ul>
	<table class="table">
		<thead>
			<tr>
				<th><i class="fa fa-user"></i></th>
				<th>회원명</th>
				<th>Follow한날짜</th>
			</tr>
		</thead>
		<tbody>
		<?php
		if (element('list', element('data', $view))) {
			foreach (element('list', element('data', $view)) as $result) {
		?>
			<tr>
				<td><i class="fa fa-user"></i></td>
				<td><?php echo element('display_name', $result); ?></td>
				<td><?php echo display_datetime(element('fol_datetime', $result), 'full'); ?></td>
			</tr>
		<?php
			}
		}
		if ( ! element('list', element('data', $view))) {
		?>
			<tr>
				<td colspan="3" class="nopost">아직 나를 Follow 한 사람이 없습니다</td>
			</tr>
		<?php
		}
		?>
		</tbody>
	</table>
	<nav><?php echo element('paging', $view); ?></nav>
</div>
