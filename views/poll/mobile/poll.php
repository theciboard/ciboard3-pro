<h4>설문조사</h4>
<table class="table">
	<thead>
		<tr>
		<th class="text-center">번호</th>
		<th class="text-center">설문제목</th>
		<th class="text-center">참여자수</th>
		<th class="text-center">게시판</th>
		<th class="text-center">포인트</th>
		<th class="text-center">진행여부</th>
		<th class="text-center">등록일</th>
		</tr>
	</thead>
	<tbody>
	<?php
	if (element('list', element('data', $view))) {
		foreach (element('list', element('data', $view)) as $result) {
	?>
		<tr>
			<td class="text-center"><?php echo element('num', $result); ?></td>
			<td><i class="fa fa-bar-chart"></i> <a href="<?php echo element('post_url', $result); ?>" title="<?php echo html_escape(element('ppo_title', $result)); ?>"><?php echo html_escape(element('ppo_title', $result)); ?></a></td>
			<td class="text-right"><?php echo number_format(element('ppo_count', $result)); ?></td>
			<td class="text-center"><?php echo html_escape(element('brd_name', $result)); ?></td>
			<td class="text-right"><?php echo element('ppo_point', $result) ? number_format(element('ppo_point', $result)) : ''; ?></td>
			<td class="text-center"><?php echo element('period', $result); ?></td>
			<td class="text-center"><?php echo display_datetime(element('ppo_datetime', $result), 'full'); ?></td>
		</tr>
	<?php
		}
	}
	if ( ! element('list', element('data', $view))) {
	?>
		<tr>
			<td colspan="7" class="nopost">설문 내역이 없습니다</td>
		</tr>
	<?php
	}
	?>
	</tbody>
</table>
<nav><?php echo element('paging', $view); ?></nav>
