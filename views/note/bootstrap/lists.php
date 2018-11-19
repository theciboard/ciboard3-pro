<?php $this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css'); ?>

<div class="modal-header">
	<h4 class="modal-title">쪽지함</h4>
</div>
<div class="modal-body">

<?php echo show_alert_message($this->session->flashdata('message'), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>'); ?>

	<div class="btn-group btn-group-justified" role="group" aria-label="...">
		<div class="btn-group" role="group">
			<a href="<?php echo site_url('note/lists/recv'); ?>" class="btn btn-default <?php echo element('type', $view) === 'recv' ? 'active' : ''; ?>">받은 쪽지</a>
		</div>
		<div class="btn-group" role="group">
			<a href="<?php echo site_url('note/lists/send'); ?>" class="btn btn-default <?php echo element('type', $view) === 'send' ? 'active' : ''; ?>">보낸 쪽지</a>
		</div>
		<div class="btn-group" role="group">
			<a href="<?php echo site_url('note/write'); ?>" class="btn btn-default">쪽지 쓰기</a>
		</div>
	</div>

	<table class="table table-striped mt20">
		<thead>
			<tr>
				<th><?php echo element('type', $view) === 'recv' ? "보낸사람":"받은사람"; ?></th>
				<th>제목</th>
				<th>보낸시간</th>
				<th>읽은시간</th>
				<th>관리</th>
			</tr>
		</thead>
		<tbody>
		<?php
		if (element('list', element('data', $view))) {
			foreach (element('list', element('data', $view)) as $result) {
		?>
			<tr>
				<td><?php echo element('display_name', $result); ?></td>
				<td><a href="<?php echo site_url('note/view/' . element('type', $view) . '/' . element('nte_id', $result)); ?>"><?php echo html_escape(element('nte_title', $result)); ?></a></td>
				<td><a href="<?php echo site_url('note/view/' . element('type', $view) . '/' . element('nte_id', $result)); ?>"><?php echo display_datetime(element('nte_datetime', $result), 'full'); ?></a></td>
				<td><a href="<?php echo site_url('note/view/' . element('type', $view) . '/' . element('nte_id', $result)); ?>"><?php echo element('nte_read_datetime', $result) > '0000-00-00 00:00:00' ? display_datetime(element('nte_read_datetime', $result), 'full') : '<span class="text-danger">아직 읽지 않음</span>'; ?></a></td>
				<td><button class="btn-link btn-one-delete" data-one-delete-url = "<?php echo element('delete_url', $result); ?>">삭제</button></td>
			</tr>
		<?php
			}
		}
		if ( ! element('list', element('data', $view))) {
		?>
			<tr>
				<td colspan="5" class="nopost">쪽지가 없습니다</td>
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
