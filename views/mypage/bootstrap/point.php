<?php $this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css'); ?>

<div class="mypage">
	<ul class="nav nav-tabs">
		<li><a href="<?php echo site_url('mypage'); ?>" title="마이페이지">마이페이지</a></li>
		<li><a href="<?php echo site_url('mypage/post'); ?>" title="나의 작성글">나의 작성글</a></li>
		<?php if ($this->cbconfig->item('use_point')) { ?>
			<li class="active"><a href="<?php echo site_url('mypage/point'); ?>" title="포인트">포인트</a></li>
		<?php } ?>
		<li><a href="<?php echo site_url('mypage/followinglist'); ?>" title="팔로우">팔로우</a></li>
		<li><a href="<?php echo site_url('mypage/like_post'); ?>" title="내가 추천한 글">추천</a></li>
		<li><a href="<?php echo site_url('mypage/scrap'); ?>" title="나의 스크랩">스크랩</a></li>
		<li><a href="<?php echo site_url('mypage/loginlog'); ?>" title="나의 로그인기록">로그인기록</a></li>
		<li><a href="<?php echo site_url('membermodify'); ?>" title="정보수정">정보수정</a></li>
		<li><a href="<?php echo site_url('membermodify/memberleave'); ?>" title="탈퇴하기">탈퇴하기</a></li>
	</ul>

	<div class="page-header">
		<h4>포인트 내역 <small>보유 포인트 : <?php echo number_format($this->member->item('mem_point')); ?>점</small></h4>
	</div>
	<table class="table table-striped">
		<thead>
			<tr>
				<th>번호</th>
				<th>일시</th>
				<th>내용</th>
				<th>지급 포인트</th>
				<th>사용 포인트</th>
			</tr>
		</thead>
		<tbody>
		<?php
		if (element('list', element('data', $view))) {
			foreach (element('list', element('data', $view)) as $result) {
		?>
			<tr>
				<td><?php echo element('num', $result); ?></td>
				<td><?php echo display_datetime(element('poi_datetime', $result), 'full'); ?></td>
				<td><?php echo html_escape(element('poi_content', $result)); ?></td>
				<td><?php if (element('poi_point', $result) > 0) { ?><span class="label label-success">+<?php echo number_format(element('poi_point', $result)); ?></span><?php } ?></td>
				<td><?php if (element('poi_point', $result) < 0) { ?><span class="label label-danger"><?php echo number_format(element('poi_point', $result)); ?></span><?php } ?></td>
			</tr>
		<?php
			}
		}
		if ( ! element('list', element('data', $view))) {
		?>
			<tr>
				<td colspan="5" class="nopost">회원님의 포인트 내역이 없습니다</td>
			</tr>
		<?php
		}
		?>
		<tr class="success">
			<td>소계</td>
			<td></td>
			<td></td>
			<td>+<?php echo number_format(element('plus', element('data', $view))); ?></td>
			<td><?php echo number_format(element('minus', element('data', $view))); ?></td>
		</tr>
	</tbody>
	</table>
	<nav><?php echo element('paging', $view); ?></nav>
</div>
