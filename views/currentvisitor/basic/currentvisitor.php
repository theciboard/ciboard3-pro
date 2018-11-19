<h4>현재 접속자</h4>
<table class="table">
	<thead>
		<tr>
			<th>번호</th>
			<th>이름</th>
			<th>위치</th>
		</tr>
	</thead>
	<tbody>
	<?php
	if (element('list', $view)) {
		foreach (element('list', $view) as $result) {
	?>
		<tr>
			<td><?php echo element('num', $result); ?></td>
			<td><?php echo element('name_or_ip', $result); ?></td>
			<td><?php echo element('cur_page', $result); ?></td>
		</tr>
	<?php
		}
	}
	?>
	</tbody>
</table>
<nav><?php echo element('paging', $view); ?></nav>
