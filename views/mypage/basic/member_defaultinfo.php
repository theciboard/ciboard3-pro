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

	<h3>회원정보 수정</h3>

	<?php
	echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
	echo show_alert_message(element('message', $view), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
	echo show_alert_message($this->session->flashdata('message'), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
	?>

	<div class="alert alert-dismissible alert-info">
		회원님은 소셜 계정을 통하여 로그인하셨습니다. <br />
		회원님의 아이디, 패스워드, 이메일, 닉네임 정보를 입력하시면, 앞으로는 소셜계정으로도 로그인을 계속 하실 수 있으며, 또한 입력하신 회원 아이디와 패스워드로도 이 홈페이지에 로그인이 가능해집니다. <br />
		또한 이 정보를 입력하신 후에 상세 개인정보 수정페이지에서 메일수신여부, 쪽지수신여부 등을 계속하여 설정하실 수 있습니다.
	</div>

	<?php
	$attributes = array('class' => 'form-horizontal', 'name' => 'fdefaultinfoform', 'id' => 'fdefaultinfoform');
	echo form_open_multipart(current_url(), $attributes);
	?>
		<ol class="member_modify">
			<li>
				<span>회원아이디</span>
				<div class="form-text text-primary group"><input type="text" id="mem_userid" name="mem_userid" class="form-control input" minlength="3" />
					<p class="help-block">영문자, 숫자, _ 만 입력 가능. 최소 3자이상 입력하세요</p>
				</div>
			</li>
			<li>
				<span>패스워드</span>
				<div class="form-text text-primary group"><input type="password" id="mem_password" name="mem_password" class="form-control input" />
					<p class="help-block">패스워드는 <?php echo element('password_length', $view); ?> 자리 이상이어야 하며 영문과 숫자를 반드시 포함해야 합니다</p>
				</div>
			</li>
			<li>
				<span>패스워드 확인</span>
				<div class="form-text text-primary group">
					<input type="password" id="mem_password_re" name="mem_password_re" class="form-control input" />
				</div>
			</li>
			<li>
				<span>이메일</span>
				<div class="form-text text-primary group"><input type="email" id="mem_email" name="mem_email" class="form-control input" />
				<?php if ($this->cbconfig->item('use_register_email_auth')) { ?>
					<p class="help-block">이메일 인증을 받으신 후에 아이디/패스워드로 로그인이 가능합니다</p>
				<?php } ?>
				</div>
			</li>
			<li>
				<span>닉네임</span>
				<div class="form-text text-primary group"><input type="text" id="mem_nickname" name="mem_nickname" class="form-control input" value="<?php echo html_escape($this->member->item('mem_nickname'));?>" />
					<p class="help-block">공백없이 한글, 영문, 숫자만 입력 가능 2글자 이상
						<?php if ($this->cbconfig->item('change_nickname_date')) { ?>
							<br />지금 적용되는 닉네임은 앞으로 <?php echo $this->cbconfig->item('change_nickname_date'); ?>일 이내에는 변경할 수 없습니다
						<?php } ?>
					</p>
				</div>
			</li>
			<li>
				<span></span>
				<button type="submit" class="btn btn-success">수정하기</button>
			</li>
		</ol>
	<?php echo form_close(); ?>
</div>

<?php
	$this->managelayout->add_js(base_url('assets/js/member_register.js'));
?>
<script type="text/javascript">
//<![CDATA[
$(function() {
	$('#fdefaultinfoform').validate({
		rules: {
			mem_userid: {required :true, minlength:3, maxlength:20, is_userid_available:true},
			mem_password: {required :true, minlength:<?php echo element('password_length', $view); ?>, is_password_available:true},
			mem_password_re : {required: true, minlength:<?php echo element('password_length', $view); ?>, equalTo : '#mem_password' },
			mem_email: {required :true, email:true, is_email_available:true},
			mem_nickname: {required :true, is_nickname_available:true}
		}
	});
});
//]]>
</script>
