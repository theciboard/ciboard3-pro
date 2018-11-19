<script type="text/javascript">
//<![CDATA[
function add(c) {
	opener.document.getElementById('<?php echo html_escape($this->input->get('id')); ?>').value += c;
	self.close();
}
//]]>
</script>
<table border="1" style="text-align:center;margin:3px;" align="center">
	<tr>
		<?php
		$i =1;
		if (element('char', $view)) {
			foreach (element('char', $view) as $key => $value) {
		?>
			<td style="padding:5px;">
				<a href="javascript:;" onClick="add('<?php echo $value; ?>')" title="<?php echo $value; ?>">
				<?php echo $value; ?>
				</a>
			</td>
		<?php
				if ($i++%20 === 0) {
					echo '</tr><tr>';
				}
			}
		}
		?>
	</tr>
</table>
