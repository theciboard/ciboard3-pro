<?php $this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css'); ?>

<h4>포인트랭킹</h4>
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
