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
		<li><a href="<?php echo site_url('mypage/scrap'); ?>" title="나의 스크랩">스크랩</a></li>
		<li><a href="<?php echo site_url('mypage/loginlog'); ?>" title="나의 로그인기록">로그인기록</a></li>
		<li class="active"><a href="<?php echo site_url('membermodify'); ?>" title="정보수정">정보수정</a></li>
		<li><a href="<?php echo site_url('membermodify/memberleave'); ?>" title="탈퇴하기">탈퇴하기</a></li>
	</ul>
	<div class="page-header">
		<h4>본인인증하기</h4>
	</div>

	<?php
	echo show_alert_message(element('message', $view), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
	echo show_alert_message($this->session->flashdata('message'), '<div class="alert alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
	?>

	<div class="form-horizontal mt20">
		<label class="control-label">본인인증 선택</label>
		<div class="form-group">
			<div class="col-lg-12 ">
				<input type="hidden" name="selfcert_type" id="selfcert_type" value="" />
				<?php if ($this->cbconfig->item('use_selfcert_phone')) { ?>
					<button type="button" class="btn btn-warning btn-sm" name="mem_selfcert" data-redirecturl="<?php echo html_escape($this->input->get_post('redirecturl')); ?>" id="btn_mem_selfcert_phone">휴대폰인증</button>
				<?php } ?>
				<?php if ($this->cbconfig->item('use_selfcert_ipin')) { ?>
					<button type="button" class="btn btn-primary btn-sm" name="mem_selfcert" data-redirecturl="<?php echo html_escape($this->input->get_post('redirecturl')); ?>" id="btn_mem_selfcert_ipin">아이핀인증</button>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
<?php
	$this->managelayout->add_js(base_url('assets/js/member_selfcert.js'));
