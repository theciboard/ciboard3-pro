<?php $this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css'); ?>

<div class="page-header">
	<h4>찜한 목록</h4>
</div>
<table class="table table-striped">
	<thead>
		<tr>
			<th>이미지</th>
			<th>상품명</th>
			<th>보관일시</th>
			<th>삭제</th>
		</tr>
	</thead>
	<tbody>
		<?php
		if (element('list', element('data', $view))) {
			foreach (element('list', element('data', $view)) as $result) {
		?>
			<tr>
				<td><a href="<?php echo element('item_url', $result); ?>" title="<?php echo html_escape(element('cit_name', $result)); ?>"><img src="<?php echo thumb_url('cmallitem', element('cit_file_1', $result), 60, 60); ?>" class="thumbnail" style="margin:0;width:60px;height:60px;" alt="<?php echo html_escape(element('cit_name', $result)); ?>" title="<?php echo html_escape(element('cit_name', $result)); ?>" /></a></td>
				<td><a href="<?php echo element('item_url', $result); ?>" title="<?php echo html_escape(element('cit_name', $result)); ?>"><?php echo html_escape(element('cit_name', $result)); ?></a></td>
				<td><?php echo display_datetime(element('cwi_datetime', $result), 'full'); ?></td>
				<td><button class="btn btn-xs btn-danger btn-one-delete" type="button" data-one-delete-url = "<?php echo element('delete_url', $result); ?>"><span class="fa fa-trash"></span></button></td>
			</tr>
		<?php
			}
		}
		if ( ! element('list', element('data', $view))) {
		?>
			<tr>
				<td colspan="4" class="nopost">보관 기록이 없습니다</td>
			</tr>
		<?php
		}
		?>
	</tbody>
</table>
<nav><?php echo element('paging', $view); ?></nav>
