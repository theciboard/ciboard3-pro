<div class="box">
	<div class="box-table">
		<div class="table-responsive">
			<table class="table table-bordered table-hover table-striped">
				<tbody>
					<tr>
						<td>
							<div class="textbox">
								<div>Browscap 기능을 사용하기 위해서는 캐시를 생성해야 합니다. </div>
								<div>이 페이지에서는 Browscap 캐시를 생성합니다. </div>
								<div>캐시파일은 plugin/browscap/ 디렉토리에 browscap_cache.php 라는 파일명으로 저장됩니다.</div>
								<div>혹시 실행 후에도 해당파일이 생성되지 않는다면 plugin/browscap/ 디렉토리의 권한을 확인해주십시오.</div>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="box-info">
			<div class="box-button btn-group">
				<button type="button" class="btn btn-success btn-sm" id="execute_update">캐시파일 생성하기</button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
$(document).on('click', '#execute_update', function() {
	$('.textbox').html('<div style="width:60px;height:60px;background: url(' + cb_url + '/assets/images/ajax-loader.gif) no-repeat 0 0;"></div><div>Browscap 캐시를 생성 중입니다. 브라우저를 닫지 마시고 잠시만 기다려주세요. 업데이트는 약 1분에서 5분 정도 소요됩니다</div>');
	$.ajax({
		url : cb_admin_url + '/config/browscapupdate/update',
		async: true,
		method : 'get',
		cache: false,
		dataType: 'json',
		success: function(data) {
			if (data.success !== 'ok') {
				alert(data.message);
				$('.textbox').html(data.message);
				return false;
			}
			$('.textbox').html('<div>Browscap 캐시를 생성했습니다. 이제 이 페이지를 닫으셔도 됩니다.</div>');
		}
	});
});
</script>
