<div class="modal-header">
	<h4 class="modal-title">게시글 변경 로그</h4>
</div>
<div class="modal-body">
	<table class="table table-striped mt20">
		<thead>
			<tr>
				<th>번호</th>
				<th>변경한이</th>
				<th>IP</th>
				<th>변경일시</th>
			</tr>
		</thead>
		<tbody>
		<?php
		if (element('list', element('data', $view))) {
			foreach (element('list', element('data', $view)) as $result) {
		?>
			<tr>
				<td><?php echo element('num', $result); ?></td>
				<td><?php echo element('display_name', $result); ?></td>
				<td><?php echo display_admin_ip(element('phi_ip', $result)); ?></td>
				<td><a href="<?php echo site_url('helptool/post_history_view/' . element('post_id', $result). '/' . element('phi_id', $result)); ?>"><?php echo display_datetime(element('phi_datetime', $result), 'full'); ?></a></td>
			</tr>
		<?php
			}
		}
		if ( ! element('list', element('data', $view))) {
		?>
			<tr>
				<td colspan="4" class="nopost">데이터가 없습니다</td>
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
