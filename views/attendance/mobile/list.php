<table class="table">
	<thead>
		<tr>
		<th class="text-center">순위</th>
		<th class="text-center">이름</th>
		<th class="text-center">인사말</th>
		<?php if (element('attendance_show_attend_time', $view)) { ?>
			<th class="text-center">출석시간</th>
		<?php } ?>
		<th class="text-center">출석포인트</th>
		<th class="text-center">연속</th>
		</tr>
	</thead>
	<tbody>
	<?php
	if (element('list', element('data', $view))) {
		foreach (element('list', element('data', $view)) as $result) {
	?>
		<tr>
			<td class="text-center"><?php echo number_format(element('att_ranking', $result)); ?></td>
			<td class="text-center"><?php echo element('display_name', $result); ?></td>
			<td><?php echo html_escape(element('att_memo', $result)); ?></td>
			<?php if (element('attendance_show_attend_time', $view)) { ?>
				<td class="text-center"><?php echo element('display_datetime', $result); ?></td>
			<?php } ?>
			<td class="text-center"><?php echo number_format(element('att_point', $result)); ?></td>
			<td class="text-center"><?php echo number_format(element('att_continuity', $result)); ?>일</td>
		</tr>
	<?php
		}
	} else {
	?>
		<tr>
			<td class="text-center" colspan="6">출석한 사람이 없습니다</td>
		</tr>
	<?php
	}
	?>
	</tbody>
</table>
<nav><?php echo element('paging', $view); ?></nav>
