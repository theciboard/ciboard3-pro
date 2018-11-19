<div class="box">
	<div class="box-table">
		<div class="alert alert-warning">
			<?php
			if ($view['is_installed']) {
			?>
			<p>설문조사 관련 테이블 설치가 완료되었습니다. </p>
			<p>이제 설문조사 관련 환경설정을 진행해주세요.</p>
			<?php } else { ?>
			<p>이미 설문조사 관련 테이블이 설치되어 있습니다. </p>
			<p>설문조사 관련 환경설정을 진행해주세요.</p>
			<?php } ?>
		</div>
	</div>
</div>
