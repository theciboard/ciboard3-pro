<?php
$attributes = array('name' => 'finstall', 'id' => 'finstall');
echo form_open(site_url('install/step5'), $attributes);
?>

<input type="hidden" name="agree" value="<?php echo $this->input->post('agree'); ?>" />
<div class="contents">
	<h2>디비설정체크</h2>
	<div class="cont">
		<p><i class="glyphicon glyphicon-record"></i><span class="title"><?php echo $title2;?></span> : <?php echo $content2;?></p>
	</div>
	<div class="cont">
		<p><i class="glyphicon glyphicon-record"></i><span class="title"><?php echo $title3;?></span> : <?php echo $content3;?></p>
	</div>
	<div class="cont">
		<p><i class="glyphicon glyphicon-record"></i><span class="title"><?php echo $title4;?></span> : <?php echo $content4;?></p>
	</div>
	<div class="cont">
		<p><i class="glyphicon glyphicon-record"></i><span class="title"><?php echo $title5;?></span> : <?php echo $content5;?></p>
	</div>

	<?php
	if ($message) {
		echo '<div class="alert alert-danger">' . $message . '</div>';
	}
	?>
</div>

<?php if ( ! $message) {?>
	<!-- footer start -->
	<div class="footer">
		<button type="submit" class="btn btn-default btn-xs pull-right">Next <i class="glyphicon glyphicon-chevron-right"></i></button>
	</div>
	<!-- footer end -->
<?php } ?>
<?php echo form_close(); ?>
<?php
if ($message) {
	$attributes = array('name' => 'fcheck', 'id' => 'fcheck');
	echo form_open(site_url('install/step4'), $attributes);
?>
	<input type="hidden" name="agree" value="<?php echo $this->input->post('agree'); ?>" />
	<!-- footer start -->
	<div class="footer">
		<button type="submit" class="btn btn-default btn-xs pull-right">Check Again <i class="glyphicon glyphicon-chevron-right"></i></button>
	</div>
	<!-- footer end -->
<?php
	echo form_close();
}
