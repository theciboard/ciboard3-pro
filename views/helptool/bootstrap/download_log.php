<div class="modal-header">
	<h4 class="modal-title">다운로드 로그</h4>
</div>
<div class="modal-body">
	<table class="table table-striped mt20">
		<thead>
			<tr>
				<th>번호</th>
				<th>파일명</th>
				<th>회원명</th>
				<th>IP</th>
				<th>다운로드일시</th>
			</tr>
		</thead>
		<tbody>
		<?php
		if (element('list', element('data', $view))) {
			foreach (element('list', element('data', $view)) as $result) {
		?>
			<tr>
				<td><?php echo element('num', $result); ?></td>
				<td><?php echo html_escape(element('pfi_originname', $result)); ?></td>
				<td><?php echo element('display_name', $result); ?></td>
				<td><?php echo display_admin_ip(element('pfd_ip', $result)); ?></td>
				<td><?php echo display_datetime(element('pfd_datetime', $result), 'full'); ?></a></td>
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
