<div class="box">
	<div class="box-header">
		<ul class="nav nav-tabs">
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir); ?>" onclick="return check_form_changed();">기본설정</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/layout'); ?>" onclick="return check_form_changed();">레이아웃/메타태그</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/smsconfig'); ?>" onclick="return check_form_changed();">SMS 설정</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/paymentconfig'); ?>" onclick="return check_form_changed();">결제기능</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/alarm'); ?>" onclick="return check_form_changed();">알림설정</a></li>
		</ul>
	</div>
	<div class="box-table">
		<div class="alert alert-warning">
			<?php
			if ($view['is_installed']) {
			?>
			<p>결제 관련 테이블 설치가 완료되었습니다. </p>
			<p>이제 결제 관련 환경설정을 진행해주세요.</p>
			<?php } else { ?>
			<p>이미 결제 관련 테이블이 설치되어 있습니다. </p>
			<p>결제 관련 환경설정을 진행해주세요.</p>
			<?php } ?>
		</div>
	</div>
</div>
