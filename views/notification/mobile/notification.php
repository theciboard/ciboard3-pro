<?php $this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css'); ?>

<div class="notification">
	<h3>알림</h3>
	<?php
	echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
	echo show_alert_message(element('alert_message', $view), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
	$attributes = array('class' => 'form-inline', 'name' => 'flist', 'id' => 'flist');
	echo form_open(current_full_url(), $attributes);
	?>
		<ul class="table-top mb10 pull-left">
			<li>
				<button type="button" class="btn btn-black btn-sm btn-list-delete btn-list-selected disabled" data-list-delete-url = "<?php echo element('list_delete_url', $view); ?>" >선택삭제</button>
			</li>
			<li>
				<button type="button" class="btn btn-black btn-sm btn-list-update btn-list-selected disabled" data-list-update-url = "<?php echo element('list_update_url', $view); ?>" >읽음표시</button>
			</li>
		</ul>
		<ul class="table-top mb10 pull-right">
			<li><a href="<?php echo site_url('notification'); ?>" class="btn <?php echo ($this->input->get('read') === 'Y' OR $this->input->get('read') === 'N') ? 'btn-success' : 'btn-warning'; ?> btn-sm">전체보기</a></li>
			<li><a href="<?php echo site_url('notification?read=Y'); ?>" class="btn <?php echo ($this->input->get('read') === 'Y') ? 'btn-warning' : 'btn-success'; ?> btn-sm">읽은알림</a></li>
			<li><a href="<?php echo site_url('notification?read=N'); ?>" class="btn <?php echo ($this->input->get('read') === 'N') ? 'btn-warning' : 'btn-success'; ?> btn-sm">안읽은알림</a></li>
		</ul>
		<table class="table clearfix">
			<thead>
				<tr>
					<th><input type="checkbox" name="chkall" id="chkall" /></th>
					<th>번호</th>
					<th>알림시간</th>
					<th>읽은시간</th>
					<th>알림내용</th>
					<th>삭제</th>
				</tr>
			</thead>
			<tbody>
			<?php
			if (element('list', element('data', $view))) {
				foreach (element('list', element('data', $view)) as $result) {
			?>
				<tr>
					<td class="text-center"><input type="checkbox" name="chk[]" class="list-chkbox" value="<?php echo element('not_id', $result); ?>" /></td>
					<td class="text-center"><?php echo number_format(element('num', $result)); ?></td>
					<td class="text-center"><?php echo display_datetime(element('not_datetime', $result), 'sns'); ?></td>
					<td class="text-center">
						<a href="<?php echo element('read_url', $result); ?>" <?php echo element('onClick', $result) ? 'onClick="' . element('onClick', $result) . '";' : ''; ?> class="noti_read <?php echo element('not_type', $result); ?>" data-not-id="<?php echo element('not_id', $result); ?>"><?php echo (element('not_read_datetime', $result) > '0000-00-00 00:00:00') ? '<span class="read">' . display_datetime(element('not_read_datetime', $result), 'sns') . '</span>' : '<span class="unread">읽지 않음</span>'; ?></a></td>
					<td><a href="<?php echo element('read_url', $result); ?>" <?php echo element('onClick', $result) ? 'onClick="' . element('onClick', $result) . '";' : ''; ?> class="noti_read <?php echo element('not_type', $result); ?>" data-not-id="<?php echo element('not_id', $result); ?>"><?php echo html_escape(element('not_message', $result)); ?></a></td>
					<td class="text-center">
						<button class="btn btn-danger btn-one-delete" type="button" data-one-delete-url = "<?php echo element('delete_url', $result); ?>"><i class="fa fa-trash"></i></button>
					</td>
				</tr>
			<?php
				}
			}
			if ( ! element('list', element('data', $view))) {
			?>
				<tr>
					<td colspan="6" class="nopost">알림 내역이 없습니다</td>
				</tr>
			<?php
			}
			?>
			</tbody>
		</table>
	<?php echo form_close(); ?>
	<nav><?php echo element('paging', $view); ?></nav>
</div>
<script type="text/javascript">
//<![CDATA[
$(document).on('click', '.noti_read.note', function() {
	$.ajax({
		url : cb_url + '/notification/readajax/' + $(this).attr('data-not-id'),
		type : 'get',
		dataType : 'json',
		success : function(data) {
			if (data.error) {
				alert(data.error);
				return false;
			} else if (data.success) {
				//alert(data.success);
			}

		}
	});
});
//]]>
</script>
