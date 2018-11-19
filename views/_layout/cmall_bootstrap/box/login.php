<?php if ($this->member->is_member() === false) { ?>

	<!-- login start -->
	<?php
	$attributes = array('class' => 'form-horizontal', 'name' => 'fsidelogin', 'id' => 'fsidelogin');
	echo form_open(site_url('login'), $attributes);
	?>
		<input type="hidden" name="url" value="<?php echo urlencode(current_full_url()); ?>" />
		<input type="hidden" name="returnurl" value="<?php echo urlencode(current_full_url()); ?>" />
		<div class="loginbox mb10">
			<div class="headline">
				<h3>로그인</h3>
			</div>
			<?php echo $this->session->flashdata('loginvalidationmessage'); ?>
			<input type="text" class="form-control mb10" name="mem_userid" placeholder="Enter User ID" value="<?php echo $this->session->flashdata('loginuserid'); ?>" />
			<input type="password" class="form-control mb10" name="mem_password" placeholder="Enter Password" />
			<button class="btn btn-primary btn-sm pull-left" type="submit">로그인</button>
			<ul class="text pull-right">
				<li><a href="<?php echo site_url('register'); ?>" title="회원가입">회원가입</a></li>
				<li>|</li>
				<li><a href="<?php echo site_url('findaccount'); ?>" title="회원정보찾기">회원정보찾기</a></li>
			</ul>
			<?php if ($this->cbconfig->item('use_sociallogin')) { ?>
				<script type="text/javascript" src="<?php echo base_url('assets/js/social_login.js'); ?>"></script>
				<div class="col-lg-12 text-center well well-sm clearfix">
				<?php if ($this->cbconfig->item('use_sociallogin_facebook')) {?>
					<a href="javascript:;" onClick="social_connect_on('facebook');" title="페이스북 로그인"><img src="<?php echo base_url('assets/images/social_facebook.png')?>" width="26" height="26" alt="페이스북 로그인" title="페이스북 로그인" /></a>
				<?php } ?>
				<?php if ($this->cbconfig->item('use_sociallogin_twitter')) {?>
					<a href="javascript:;" onClick="social_connect_on('twitter');" title="트위터 로그인"><img src="<?php echo base_url('assets/images/social_twitter.png')?>" width="26" height="26" alt="트위터 로그인" title="트위터 로그인" /></a>
				<?php } ?>
				<?php if ($this->cbconfig->item('use_sociallogin_google')) {?>
					<a href="javascript:;" onClick="social_connect_on('google');" title="구글 로그인"><img src="<?php echo base_url('assets/images/social_google.png')?>" width="26" height="26" alt="구글 로그인" title="구글 로그인" /></a>
				<?php } ?>
				<?php if ($this->cbconfig->item('use_sociallogin_naver')) {?>
					<a href="javascript:;" onClick="social_connect_on('naver');" title="네이버 로그인"><img src="<?php echo base_url('assets/images/social_naver.png')?>" width="26" height="26" alt="네이버 로그인" title="네이버 로그인" /></a>
				<?php } ?>
				<?php if ($this->cbconfig->item('use_sociallogin_kakao')) {?>
					<a href="javascript:;" onClick="social_connect_on('kakao');" title="카카오 로그인"><img src="<?php echo base_url('assets/images/social_kakao.png')?>" width="26" height="26" alt="카카오 로그인" title="카카오 로그인" /></a>
				<?php } ?>
			</div>
			<?php } ?>
		</div>
	<?php echo form_close(); ?>
	<script type="text/javascript">
	//<![CDATA[
	$(function() {
		$('#fsidelogin').validate({
			rules: {
				mem_userid: {required:true, minlength:3},
				mem_password: {required:true, minlength:4}
			}
		});
	});
	//]]>
	</script>
	<!-- login end -->

<?php } else { ?>

	<!-- welcome start -->
	<div class="welcome mb10">
		<div class="headline">
			<h3><?php echo html_escape($this->member->item('mem_nickname')); ?>님 어서오세요.</h3>
		</div>
		<div class="mb10">
			<?php if ($this->cbconfig->item('use_note') && $this->member->item('mem_use_note')) { ?>
				<p><strong>쪽지</strong> : <a href="javascript:;" onClick="note_list();" title="나의 쪽지"><?php echo number_format((int) $this->member->item('meta_unread_note_num')); ?> 개</a></p>
			<?php } ?>
			<?php if ($this->cbconfig->item('use_point')) { ?>
				<p><strong>포인트</strong> :<a href="<?php echo site_url('mypage/point'); ?>" title="나의 포인트"><?php echo number_format((int) $this->member->item('mem_point')); ?> 점</a></p>
			<?php } ?>
			<?php if ($this->cbconfig->item('use_deposit')) { ?>
				<p><strong><?php echo $this->cbconfig->item('deposit_name'); ?></strong> :<a href="<?php echo site_url('deposit'); ?>" title="<?php echo $this->cbconfig->item('deposit_name'); ?> 충전"><?php echo number_format((int) $this->member->item('total_deposit')); ?> <?php echo $this->cbconfig->item('deposit_unit'); ?></a></p>
			<?php } ?>
		</div>
		<ul class="mt20">
			<li><a href="javascript:;" onClick="open_profile('<?php echo $this->member->item('mem_userid'); ?>');" class="btn btn-default btn-xs" title="나의 프로필">프로필</a></li>
			<li><a href="<?php echo site_url('mypage'); ?>" class="btn btn-default btn-xs" title="마이페이지">마이페이지</a></li>
			<li><a href="<?php echo site_url('mypage/scrap'); ?>" class="btn btn-default btn-xs" title="나의 스크랩">스크랩</a></li>
			<li><a href="<?php echo site_url('membermodify'); ?>" class="btn btn-default btn-xs" title="정보수정">정보수정</a></li>
			<li><a href="<?php echo site_url('login/logout?url=' . urlencode(current_full_url())); ?>" class="btn btn-default btn-xs" title="로그아웃">로그아웃</a></li>
		</ul>
	</div>
	<!-- welcome end -->

<?php } ?>
