<div class="sidebar_latest">
	<div class="headline">
		<h3>상품카테고리</h3>
	</div>
	<ul class="list-group">
		<?php
		$CI =& get_instance();
		$CI->load->library('cmalllib');
		$category = $CI->cmalllib->get_all_category();
		if (element(0, $category)) {
			foreach (element(0, $category) as $value) {
		?>
			<li class="list-group-item"><span class="fa fa-chevron-right" style="font-size:0.7em;"></span> <a href="<?php echo site_url('cmall/lists/' . element('cca_id', $value))?>" style="font-weight:bold;" title="<?php echo html_escape(element('cca_value', $value)); ?>"><?php echo html_escape(element('cca_value', $value)); ?></a></li>
				<?php
				if (element(element('cca_id', $value), $category)) {
					foreach (element(element('cca_id', $value), $category) as $svalue) {
				?>
					<li class="list-group-item pl30"><a href="<?php echo site_url('cmall/lists/' . element('cca_id', $svalue))?>" title="<?php echo html_escape(element('cca_value', $svalue)); ?>"><?php echo html_escape(element('cca_value', $svalue)); ?></a></li>
		<?php
					}
				}
			}
		}
		?>
	</ul>
</div>
