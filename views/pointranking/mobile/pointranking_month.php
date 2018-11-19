<?php $this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css'); ?>

<h4>월별 포인트랭킹</h4>
<div class="pull-right mb20">
	<select name="ym" onchange="location.href='<?php echo site_url('pointranking/month'); ?>/' + this.value" class="input">
	<?php
	$opty = cdate('Y');
	$optm = cdate('m');
	for ($i =1; $i <=12; $i++) {
		$optm = sprintf("%02d", $optm);
		$optym = $opty . '/' . $optm;
	?>
		<option value="<?php echo $optym; ?>" <?php echo ($this->uri->segment(3) === (string) $opty && $this->uri->segment(4) === (string) $optm) ? 'selected="selected"' : ''; ?>><?php echo $optym; ?></option>
	<?php
		$optm--;
		if ((int) $optm === 0) {
			$optm=12;
			$opty--;
		}
	}
	?>
	</select>
</div>
<div class="help-block">100위까지 포인트 순위를 보여줍니다.</div>
<table class="table">
	<thead>
		<tr>
		<th>순위</th>
		<th>닉네임</th>
		<th>포인트</th>
		</tr>
	</thead>
	<tbody>
	<?php
	for ($i = 0; $i < 100; $i++) {
		$result = element($i, element('data', $view));
		if ( ! element('ranking', $result)) {
			break;
		}
	?>
		<tr>
			<td><?php echo element('ranking', $result); ?></td>
			<td><?php echo element('display_name', $result); ?></td>
			<td class="text-right"><?php echo element('poi_point', $result) ? number_format(element('poi_point', $result)) : '&nbsp;'; ?></td>
		</tr>
	<?php
	}
	?>
	</tbody>
</table>
