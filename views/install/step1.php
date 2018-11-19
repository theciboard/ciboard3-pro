<?php
	$attributes = array('name' => 'finstall', 'id' => 'finstall', 'onSubmit' => 'return fsubmit(this);');
	echo form_open(site_url('install/step2'), $attributes);
?>

<div class="contents">
	<h2>약관동의</h2>
	<textarea class="form-control" style="height:500px;">
		<?php echo implode('', file('./CIBOARD_LICENSE')); ?>
	</textarea>
	<label for="agree"><input type="checkbox" id="agree" name="agree" value="1" /> 위 약관에 동의합니다</label>
</div>

<!-- footer start -->
<div class="footer">
	<button type="submit" class="btn btn-default btn-xs pull-right">Next <i class="glyphicon glyphicon-chevron-right"></i></button>
</div>
<!-- footer end -->

<?php echo form_close(); ?>

<script type="text/javascript">
//<![CDATA[
function fsubmit(f)
{
	if ( ! f.agree.checked) {
		alert('라이센스 내용에 동의하셔야 설치가 가능합니다.');
		return false;
	}
	return true;
}
//]]>
</script>
