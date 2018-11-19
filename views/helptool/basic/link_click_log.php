<div class="modal-header">
	<h4 class="modal-title">링크클릭 로그</h4>
</div>
<div class="modal-body">
	<table class="table table-striped mt20">
		<thead>
			<tr>
				<th>번호</th>
				<th>링크주소</th>
				<th>회원명</th>
				<th>IP</th>
				<th>클릭일시</th>
			</tr>
		</thead>
		<tbody>
		<?php
		if (element('list', element('data', $view))) {
			foreach (element('list', element('data', $view)) as $result) {
		?>
			<tr>
				<td><?php echo element('num', $result); ?></td>
				<td><?php echo cut_str(html_escape(element('pln_url', $result)),30); ?></td>
				<td><?php echo element('display_name', $result); ?></td>
				<td><?php echo display_admin_ip(element('plc_ip', $result)); ?></td>
				<td><?php echo display_datetime(element('plc_datetime', $result), 'full'); ?></a></td>
			</tr>
		<?php
			}
		}
		if ( ! element('list', element('data', $view))) {
		?>
			<tr>
				<td colspan="5" class="nopost">데이터가 없습니다</td>
			</tr>
		<?php
		}
		?>
		</tbody>
	</table>
	<div class="pull-left">
		<nav><?php echo element('paging', $view); ?></nav>
	</div>
	<div class="pull-right" style="margin:20px;"><button class="btn btn-default" onClick="window.close();">닫기</button></div>
</div>
