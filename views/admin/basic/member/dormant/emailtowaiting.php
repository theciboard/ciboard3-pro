<div class="box">
	<div class="box-header">
		<ul class="nav nav-tabs">
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir); ?>">기본정보</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/cleantodormant'); ?>">휴면계정일괄정리</a></li>
			<li role="presentation" class="active"><a href="<?php echo admin_url($this->pagedir . '/emailtowaiting'); ?>">안내메일일괄발송</a></li>
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
								<p>자동메일발송기능을 사용하시는 경우에는 이 페이지의 메일보내기 기능을 굳이 사용하실 필요가 없습니다.</strong></p>
								<p>많은 회원에게 한번에 메일을 보내는 것은 서버에 부하를 발생할 수 있고, 서버 설정에 따라 메일이 제대로 발송되지 않을 수 있습니다.</p>
								<p>따라서 가능하면 자동메일발송기능을 사용하시길 권장드립니다.</p>
								<p>&nbsp;</p>
								<p>메일 발송 대상 : 휴면회원으로 전환일(최종로그인한 날짜로부터 <?php echo element('period_text', $view)?>이 지난날)이 이미 지났거나, 전환일로부터 <?php echo $this->cbconfig->item('member_dormant_auto_email_days'); ?>일전에 있는 회원들 중 아직 메일발송이 되어있지 않은 회원
								<p>메일 발송대상에 있는 회원수 : <strong><?php echo number_format(element('count_unsent_email_member', $view)); ?></strong>명</p>
								<p>메일을 모두 발송하는데에 시간이 조금 오래 걸릴 수도 있습니다</p>
								<p>메일을 발송하시겠습니까</p>
								<p>대량 메일 발송은 서버 부하를 일으킬 수 있는 원인으로 인하여, 최대 100명의 회원에게만 발송됩니다. 발송대상이 100명보다 많은 경우에는 발송하기를 여러번 실행하시면 됩니다.</p>
								<div class="clean-info"></div>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="box-info">
			<div class="box-button btn-group">
				<button type="button" class="btn btn-success btn-sm execute_clean">메일발송하기</button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
//<![CDATA[
$(document).on('click', '.execute_clean', function() {
	if (confirm('정말 메일발송을 시작하시겠습니까?')) {
		$('.clean-info').html('<div style="width:60px;height:60px;background: url(' + cb_url + '/assets/images/ajax-loader.gif) no-repeat 0 0;"></div>');
		$('.box-info').html('');
		$.ajax({
			url : cb_admin_url + '/member/dormant/emailtowaiting/',
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