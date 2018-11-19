<div class="box">
	<div class="box-header">
		<ul class="nav nav-tabs">
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir); ?>">일반기능</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/points'); ?>">시간/포인트설정</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/cleanlog'); ?>">오래된 로그삭제</a></li>
		</ul>
	</div>
	<div class="box-table">
		<div class="alert alert-warning">
			<?php
			if ($view['is_installed']) {
			?>
			<p>출석체크 관련 테이블 설치가 완료되었습니다. </p>
			<p>이제 출석체크 관련 환경설정을 진행해주세요.</p>
			<?php } else { ?>
			<p>이미 출석체크 관련 테이블이 설치되어 있습니다. </p>
			<p>출석체크 관련 환경설정을 진행해주세요.</p>
			<?php } ?>
		</div>
	</div>
</div>
