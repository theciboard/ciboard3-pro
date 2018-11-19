<div class="box">
	<div class="box-header">
		<ul class="nav nav-tabs">
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir); ?>">기본정보</a></li>
			<li role="presentation" class="active"><a href="<?php echo admin_url($this->pagedir . '/cleantodormant'); ?>">휴면계정일괄정리</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/emailtowaiting'); ?>">안내메일일괄발송</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/emailcontent'); ?>">안내메일내용</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/emailsendlist'); ?>">안내메일발송내역</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/waitinglist'); ?>">휴면처리해야할회원</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/dormantlist'); ?>">휴면중인회원</a></li>
		</ul>
	</div>
	<div class="box-table">
		<div class="table-responsive">
			<table class="table table-bordered table-hover table-striped">
				<tbody>
					<tr>
						<td>
							<div class="textbox">
								<p>휴면계정 대상인 회원의 디비를 모두 정리합니다</p>
								<p>정리 대상 : <strong><?php echo $this->cbconfig->item('member_dormant_days')?>일 이상된 회원</strong></p>
								<p>정리 방법 : <strong><?php echo $this->cbconfig->item('member_dormant_method') === 'delete' ? '삭제' : '별도의 저장소에 보관'; ?></strong></p>
								<p>정리 대상에 있는 회원수 : <strong><?php echo number_format(element('count', $view)); ?></strong>명 (<a href="<?php echo admin_url($this->pagedir . '/waitinglist'); ?>">휴면처리해야할 회원</a> 목록에서명단 확인 가능합니다)</p>
								<p>정리 방법이 '삭제' 인 경우, 회원정보는 디비상에서 완전 삭제되며 복구가 불가능합니다</p>
								<p>정리 방법이 '별도의 저장소에 보관' 인 경우, 회원정보는 <a href="<?php echo admin_url($this->pagedir . '/dormantlist'); ?>">휴면중인회원</a> 목록으로 이동합니다</p>
								<p>시간이 조금 오래 걸릴 수도 있습니다</p>
								<p>정리하시겠습니까</p>
								<div class="clean-info"></div>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="box-info">
			<div class="box-button btn-group">
				<button type="button" class="btn btn-success btn-sm execute_clean">정리하기</button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
//<![CDATA[
$(document).on('click', '.execute_clean', function() {
	if (confirm('정말 정리를 시작하시겠습니까?')) {
		$('.clean-info').html('<div style="width:60px;height:60px;background: url(' + cb_url + '/assets/images/ajax-loader.gif) no-repeat 0 0;"></div>');
		$('.box-info').html('');
		$.ajax({
			url : cb_admin_url + '/member/dormant/cleantodormant/',
			data : {execute : '1', csrf_test_name : cb_csrf_hash},
			async: true,
			method : 'post',
			cache: false,
			dataType: 'json',
			success: function(data) {
				$('.textbox').html(data.message);
			}
		});
	} else {
		return;
	}
});
</script>