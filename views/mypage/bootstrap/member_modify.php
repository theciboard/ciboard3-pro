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
		<h4>회원 정보 수정</h4>
	</div>
	<?php
	echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
	echo show_alert_message(element('message', $view), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
	echo show_alert_message($this->session->flashdata('message'), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
	$attributes = array('class' => 'form-horizontal', 'name' => 'fregisterform', 'id' => 'fregisterform');
	echo form_open_multipart(current_url(), $attributes);
	?>
		<div class="form-group">
			<label class="col-lg-3 control-label">회원아이디</label>
			<div class="col-lg-8"><p class="form-control-static"><?php echo $this->member->item('mem_userid'); ?></p></div>
		</div>
		<?php if ($this->cbconfig->item('use_selfcert') && ($this->cbconfig->item('use_selfcert_phone') OR $this->cbconfig->item('use_selfcert_ipin'))) { ?>
			<div class="form-group">
				<label class="col-lg-3 control-label">본인인증</label>
				<div class="col-lg-8">
				<?php if ($this->member->item('selfcert_type')) { ?>
					<div class="alert alert-warning">
						<p><strong>회원님의 본인 인증 정보</strong> - 회원님은 본인인증을 받으셨습니다</p>
						<?php if ($this->member->item('selfcert_username')) { ?>
							<p><strong>회원명</strong> <?php echo $this->member->item('selfcert_username'); ?></p>
						<?php } ?>
						<?php if ($this->member->item('selfcert_phone')) { ?>
							<p><strong>휴대폰</strong> <?php echo $this->member->item('selfcert_phone'); ?></p>
						<?php } ?>
						<?php if ($this->member->item('selfcert_birthday')) { ?>
							<p><strong>생년월일</strong> <?php echo $this->member->item('selfcert_birthday'); ?></p>
						<?php } ?>
						<?php if ($this->member->item('selfcert_sex')) { ?>
							<p><strong>성별</strong> <?php echo $this->member->item('selfcert_sex') == '1' ? '남성' : '여성'; ?></p>
						<?php } ?>
					</div>
				<?php } else { ?>
					회원님은 아직 본인인증을 하지 않으셨습니다. <a href="<?php echo site_url('selfcert?redirecturl=' . urlencode(current_full_url())); ?>" class="btn btn-default btn-sm" title="본인인증하기">본인인증 하기</a>
				<?php } ?>
				</div>
			</div>
		<?php
		}
		if ($this->cbconfig->item('use_sociallogin')) {
			$this->managelayout->add_js(base_url('assets/js/social_login.js'));
		?>
			<div class="form-group">
				<label class="col-lg-3 control-label">소셜 연동</label>
				<div class="col-lg-8">
					<div class="social-login-register">
						<?php if ($this->cbconfig->item('use_sociallogin_facebook')) {?>
							<div class="social-login-button social-facebook social-facebook-on">
								<a href="javascript:;" onClick="social_connect_off('facebook');" title="페이스북 연동해제하기"><img src="<?php echo base_url('assets/images/social_facebook.png'); ?>" width="30" height="30" alt="페이스북 연동해제하기" title="페이스북 연동해제하기" /></a>
							</div>
							<div class="social-login-button social-facebook social-facebook-off">
								<a href="javascript:;" onClick="social_connect_on('facebook');" title="페이스북 연동하기"><img src="<?php echo base_url('assets/images/social_facebook_off.png'); ?>" width="30" height="30" alt="페이스북 연동하기" title="페이스북 연동하기" /></a>
							</div>
							<script type="text/javascript">
							<?php if ($this->member->socialitem('facebook_id')) {?>
							$('.social-facebook-on').css('display', 'inline-block');
							$('.social-facebook-off').css('display', 'none');
							<?php } else {?>
							$('.social-facebook-on').css('display', 'none');
							$('.social-facebook-off').css('display', 'inline-block');
							<?php } ?>
							</script>
						<?php } ?>
						<?php if ($this->cbconfig->item('use_sociallogin_twitter')) {?>
							<div class="social-login-button social-twitter social-twitter-on">
								<a href="javascript:;" onClick="social_connect_off('twitter');" title="트위터 연동해제하기"><img src="<?php echo base_url('assets/images/social_twitter.png'); ?>" width="30" height="30" alt="트위터 연동해제하기" title="트위터 연동해제하기" /></a>
							</div>
							<div class="social-login-button social-twitter social-twitter-off">
								<a href="javascript:;" onClick="social_connect_on('twitter');" title="트위터 연동하기"><img src="<?php echo base_url('assets/images/social_twitter_off.png'); ?>" width="30" height="30" alt="트위터 연동하기" title="트위터 연동하기" /></a>
							</div>
							<script type="text/javascript">
							<?php if ($this->member->socialitem('twitter_id')) {?>
								$('.social-twitter-on').css('display', 'inline-block');
								$('.social-twitter-off').css('display', 'none');
							<?php } else {?>
								$('.social-twitter-on').css('display', 'none');
								$('.social-twitter-off').css('display', 'inline-block');
							<?php } ?>
							</script>
						<?php } ?>
						<?php if ($this->cbconfig->item('use_sociallogin_google')) {?>
							<div class="social-login-button social-google social-google-on">
								<a href="javascript:;" onClick="social_connect_off('google');" title="구글 연동해제하기"><img src="<?php echo base_url('assets/images/social_google.png'); ?>" width="30" height="30" alt="구글 연동해제하기" title="구글 연동해제하기" /></a>
							</div>
							<div class="social-login-button social-google social-google-off">
								<a href="javascript:;" onClick="social_connect_on('google');" title="구글 연동하기"><img src="<?php echo base_url('assets/images/social_google_off.png'); ?>" width="30" height="30" alt="구글 연동하기" title="구글 연동하기" /></a>
							</div>
							<script type="text/javascript">
							<?php if ($this->member->socialitem('google_id')) {?>
							$('.social-google-on').css('display', 'inline-block');
							$('.social-google-off').css('display', 'none');
							<?php } else {?>
							$('.social-google-on').css('display', 'none');
							$('.social-google-off').css('display', 'inline-block');
							<?php } ?>
							</script>
						<?php } ?>
						<?php if ($this->cbconfig->item('use_sociallogin_naver')) {?>
							<div class="social-login-button social-naver social-naver-on">
								<a href="javascript:;" onClick="social_connect_off('naver');" title="네이버 연동해제하기"><img src="<?php echo base_url('assets/images/social_naver.png'); ?>" width="30" height="30" alt="네이버 연동해제하기" title="네이버 연동해제하기" /></a>
							</div>
							<div class="social-login-button social-naver social-naver-off">
								<a href="javascript:;" onClick="social_connect_on('naver');" title="네이버 연동하기"><img src="<?php echo base_url('assets/images/social_naver_off.png'); ?>" width="30" height="30" alt="네이버 연동하기" title="네이버 연동하기" /></a>
							</div>
							<script type="text/javascript">
							<?php if ($this->member->socialitem('naver_id')) {?>
							$('.social-naver-on').css('display', 'inline-block');
							$('.social-naver-off').css('display', 'none');
							<?php } else {?>
							$('.social-naver-on').css('display', 'none');
							$('.social-naver-off').css('display', 'inline-block');
							<?php } ?>
							</script>
						<?php } ?>
						<?php if ($this->cbconfig->item('use_sociallogin_kakao')) {?>
							<div class="social-login-button social-kakao social-kakao-on">
								<a href="javascript:;" onClick="social_connect_off('kakao');" title="카카오 연동해제하기"><img src="<?php echo base_url('assets/images/social_kakao.png'); ?>" width="30" height="30" alt="카카오 연동해제하기" title="카카오 연동해제하기" /></a>
							</div>
							<div class="social-login-button social-kakao social-kakao-off">
								<a href="javascript:;" onClick="social_connect_on('kakao');" title="카카오 연동하기"><img src="<?php echo base_url('assets/images/social_kakao_off.png'); ?>" width="30" height="30" alt="카카오 연동하기" title="카카오 연동하기" /></a>
							</div>
							<script type="text/javascript">
							<?php if ($this->member->socialitem('kakao_id')) {?>
							$('.social-kakao-on').css('display', 'inline-block');
							$('.social-kakao-off').css('display', 'none');
							<?php } else {?>
							$('.social-kakao-on').css('display', 'none');
							$('.social-kakao-off').css('display', 'inline-block');
							<?php } ?>
							</script>
						<?php } ?>
					</div>
				</div>
			</div>
		<?php } ?>
		<div class="form-group">
			<label class="col-lg-3 control-label">패스워드</label>
			<div class="col-lg-8"><a href="<?php echo site_url('membermodify/password_modify'); ?>" class="btn btn-default btn-sm" title="패스워드 변경">패스워드 변경</a></div>
		</div>
		<?php foreach (element('html_content', $view) as $key => $value) { ?>
			<div class="form-group">
				<label class="col-lg-3 control-label" for="<?php echo element('field_name', $value); ?>"><?php echo element('display_name', $value); ?></label>
				<div class="col-lg-8">
					<?php echo element('input', $value); ?>
					<?php if (element('description', $value)) { ?>
						<p class="help-block"><?php echo element('description', $value); ?></p>
					<?php } ?>
				</div>
			</div>
		<?php } ?>
		<?php if ($this->cbconfig->item('use_member_photo') && $this->cbconfig->item('member_photo_width') > 0 && $this->cbconfig->item('member_photo_height') > 0) { ?>
			<div class="form-group">
				<label class="col-lg-3 control-label">프로필사진</label>
				<div class="col-lg-8">
					<?php if ($this->member->item('mem_photo')) { ?>
						<img src="<?php echo member_photo_url($this->member->item('mem_photo')); ?>" alt="프로필사진" title="프로필사진" />
						<label for="mem_photo_del">
							<input type="checkbox" name="mem_photo_del" id="mem_photo_del" value="1" <?php echo set_checkbox('mem_photo_del', '1'); ?> />
							삭제
						</label>
					<?php } ?>
					<input type="file" name="mem_photo" id="mem_photo" />
					<p class="help-block">가로길이 : <?php echo number_format($this->cbconfig->item('member_photo_width')); ?>px, 세로길이 : <?php echo number_format($this->cbconfig->item('member_photo_height')); ?>px 에 최적화되어있습니다, gif, jpg, png 파일 업로드가 가능합니다</p>
				</div>
			</div>
		<?php } ?>
		<?php if ($this->cbconfig->item('use_member_icon') && $this->cbconfig->item('member_icon_width') > 0 && $this->cbconfig->item('member_icon_height') > 0) { ?>
			<div class="form-group">
				<label class="col-lg-3 control-label">회원아이콘</label>
				<div class="col-lg-8">
					<?php if ($this->member->item('mem_icon')) { ?>
						<img src="<?php echo member_icon_url($this->member->item('mem_icon')); ?>" alt="회원아이콘" title="회원아이콘" />
						<label for="mem_icon_del">
							<input type="checkbox" name="mem_icon_del" id="mem_icon_del" value="1" <?php echo set_checkbox('mem_icon_del', '1'); ?> />
							삭제
						</label>
					<?php } ?>
					<input type="file" name="mem_icon" id="mem_icon" />
					<p class="help-block">가로길이 : <?php echo number_format($this->cbconfig->item('member_icon_width')); ?>px, 세로길이 : <?php echo number_format($this->cbconfig->item('member_icon_height')); ?>px 에 최적화되어있습니다, gif, jpg, png 파일 업로드가 가능합니다</p>
				</div>
			</div>
		<?php } ?>
		<div class="form-group">
			<label class="col-lg-3 control-label">정보공개</label>
			<div class="col-lg-8">
				<div class="checkbox">
					<label for="mem_open_profile">
						<input type="checkbox" name="mem_open_profile" id="mem_open_profile" value="1" <?php echo set_checkbox('mem_open_profile', '1', ($this->member->item('mem_open_profile') ? true : false)); ?> <?php echo element('can_update_open_profile', $view) ? '' : 'disabled="disabled"'; ?> />
						다른분들이 나의 정보를 볼 수 있도록 합니다.
					</label>
					<?php if (element('open_profile_description', $view)) { ?>
						<p class="help-block"><?php echo element('open_profile_description', $view); ?></p>
					<?php } ?>
				</div>
			</div>
		</div>
		<?php if ($this->cbconfig->item('use_note')) { ?>
			<div class="form-group">
				<label class="col-lg-3 control-label">쪽지기능사용</label>
				<div class="col-lg-8">
					<div class="checkbox">
						<label for="mem_use_note">
							<input type="checkbox" name="mem_use_note" id="mem_use_note" value="1" <?php echo set_checkbox('mem_use_note', '1', ($this->member->item('mem_use_note') ? true : false)); ?> <?php echo element('can_update_use_note', $view) ? '' : 'disabled="disabled"'; ?> />
							쪽지를 주고 받을 수 있습니다.
						</label>
						<?php if (element('use_note_description', $view)) { ?>
							<p class="help-block"><?php echo element('use_note_description', $view); ?></p>
						<?php } ?>
					</div>
				</div>
			</div>
		<?php } ?>
		<div class="form-group">
			<label class="col-lg-3 control-label">이메일 수신여부</label>
			<div class="col-lg-8">
				<div class="checkbox">
					<label for="mem_receive_email" >
						<input type="checkbox" name="mem_receive_email" id="mem_receive_email" value="1" <?php echo set_checkbox('mem_receive_email', '1', ($this->member->item('mem_receive_email') ? true : false)); ?> /> 수신
					</label>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-3 control-label">SMS 문자 수신</label>
			<div class="col-lg-8	">
				<div class="checkbox">
					<label for="mem_receive_sms">
						<input type="checkbox" name="mem_receive_sms" id="mem_receive_sms" value="1" <?php echo set_checkbox('mem_receive_sms', '1', ($this->member->item('mem_receive_sms') ? true : false)); ?> /> 수신
					</label>
				</div>
			</div>
		</div>
		<div class="form-group">
			<div class="col-lg-9 col-lg-offset-3">
				<button type="submit" class="btn btn-success btn-sm">수정하기</button>
			</div>
		</div>
	<?php echo form_close(); ?>
</div>

<?php
$this->managelayout->add_css(base_url('assets/css/datepicker3.css'));
$this->managelayout->add_js('http://dmaps.daum.net/map_js_init/postcode.v2.js');
$this->managelayout->add_js(base_url('assets/js/bootstrap-datepicker.js'));
$this->managelayout->add_js(base_url('assets/js/bootstrap-datepicker.kr.js'));
?>

<script type="text/javascript">
//<![CDATA[
$('.datepicker').datepicker({
	format: 'yyyy-mm-dd',
	language: 'kr',
	autoclose: true,
	todayHighlight: true
});
//]]>
</script>
