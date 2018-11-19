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

	<h3>회원 비밀번호 변경</h3>

	<div class="mt20">
		<?php
		echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
		echo show_alert_message(element('message', $view), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
		echo show_alert_message($this->session->flashdata('message'), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
		echo show_alert_message(element('info', $view), '<div class="alert alert-info">', '</div>');
		$attributes = array('class' => 'form-horizontal', 'name' => 'fchangepassword', 'id' => 'fchangepassword');
		echo form_open(current_url(), $attributes);
		?>
			<ol class="askpassword">
				<li>
					<span>아이디</span>
					<div class="form-text"><strong><?php echo $this->member->item('mem_userid'); ?></strong></div>
				</li>
				<li>
					<span>현재비밀번호</span>
					<div class="group">
						<input type="password" class="input" id="cur_password" name="cur_password" />
					</div>
				</li>
				<li>
					<span>새로운비밀번호</span>
					<div class="group">
						<input type="password" class="input" id="new_password" name="new_password" />
					</div>
				</li>
				<li>
					<span>재입력</span>
					<div class="group">
						<input type="password" class="input" id="new_password_re" name="new_password_re" />
					</div>
				</li>
				<li>
					<span></span>
					<button type="submit" class="btn btn-success">수정하기</button>
				</li>
			</ol>
		<?php echo form_close(); ?>
	</div>
</div>

<script type="text/javascript">
//<![CDATA[
$(function() {
	$('#fchangepassword').validate({
		rules: {
			cur_password : { required:true },
			new_password : { required:true, minlength:<?php echo element('password_length', $view); ?> },
			new_password_re : { required:true, minlength:<?php echo element('password_length', $view); ?>, equalTo: '#new_password' }
		}
	});
});
//]]>
</script>
