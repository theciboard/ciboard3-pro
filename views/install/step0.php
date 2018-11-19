<div class="contents">
	<h2>설치 준비</h2>
	<div class="cont">
		<p style="font-size:14px;height:200px;">
			설치를 진행하기 위해서는 현재 접속하고 계신 IP를 application/config/cb_config.php 파일에 &dollar;config&lsqb;&apos;install_ip&apos;&rsqb; 변수에 등록해주십시오. <br />
			현재 접속하고 계신 IP는 <strong><?php echo $this->input->ip_address(); ?></strong> 로 확인되고 있습니다.
		</p>
	</div>
</div>
<?php
echo form_open(site_url('install'));
?>
	<!-- footer start -->
	<div class="footer">
		<button type="submit" class="btn btn-default btn-xs pull-right">Check Again <i class="glyphicon glyphicon-chevron-right"></i></button>
	</div>
	<!-- footer end -->
<?php
echo form_close();
?>
