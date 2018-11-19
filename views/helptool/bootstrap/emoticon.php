<script type="text/javascript">
//<![CDATA[
function add(img) {
	opener.document.getElementById('<?php echo html_escape($this->input->get('id')); ?>').value += "\n[" + img + "]\n";
	self.close();
}
//]]>
</script>

<?php
$emoticon = element('emoticon', $view);

if ($emoticon && is_array($emoticon)) {
	foreach ($emoticon as $key => $value) {
		$size = @getimagesize(config_item('uploads_dir') . '/emoticon/' . $value);
		if ( ! isset($size[0])) {
			continue;
		}
		$img = site_url(config_item('uploads_dir') . '/emoticon/' . $value);
?>
		<span style="margin:5px;"> <a href="javascript:add('<?php echo $img; ?>');"><img src="<?php echo $img; ?>" alt="emoticon" title="emoticon" /></a> </span>
<?php
	}
}
