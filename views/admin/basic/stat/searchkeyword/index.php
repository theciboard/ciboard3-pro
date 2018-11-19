<div class="box">
	<div class="box-table">

		<?php
		$attributes = array('class' => 'form-inline', 'name' => 'flist', 'id' => 'flist');
		echo form_open(current_full_url(), $attributes);
		?>
			<div class="box-table-header">
				<ul class="nav nav-pills">
					<li role="presentation" class="active"><a href="<?php echo admin_url($this->pagedir); ?>">인기검색어</a></li>
					<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/rank'); ?>">순위</a></li>
					<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/cleanlog'); ?>">오래된 로그삭제</a></li>
				</ul>
				<?php
				ob_start();
				?>
					<div class="btn-group pull-right" role="group" aria-label="...">
						<a href="<?php echo element('listall_url', $view); ?>" class="btn btn-outline btn-default btn-sm">전체목록</a>
						<button type="button" class="btn btn-outline btn-default btn-sm btn-list-delete btn-list-selected disabled" data-list-delete-url = "<?php echo element('list_delete_url', $view); ?>" >선택삭제</button>
					</div>
				<?php
				$buttons = ob_get_contents();
				ob_end_flush();
				?>
			</div>
			<div class="row">전체 : <?php echo element('total_rows', element('data', $view), 0); ?>건</div>
			<div class="table-responsive">
				<table class="table table-hover table-striped table-bordered">
					<colgroup>
						<col class="col-md-1">
						<col class="col-md-3">
						<col class="col-md-3">
						<col class="col-md-2">
						<col class="col-md-2">
						<col class="col-md-1">
					</colgroup>
					<thead>
						<tr>
							<th><a href="<?php echo element('sek_id', element('sort', $view)); ?>">번호</a></th>
							<th><a href="<?php echo element('sek_keyword', element('sort', $view)); ?>">검색어</a></th>
							<th><a href="<?php echo element('sek_datetime', element('sort', $view)); ?>">검색일시</a></th>
							<th><a href="<?php echo element('sek_ip', element('sort', $view)); ?>">IP</a></th>
							<th>회원명</th>
							<th><input type="checkbox" name="chkall" id="chkall" /></th>
						</tr>
					</thead>
					<tbody>
					<?php
					if (element('list', element('data', $view))) {
						foreach (element('list', element('data', $view)) as $result) {
					?>
						<tr>
							<td><?php echo number_format(element('num', $result)); ?></td>
							<td><a href="<?php echo goto_url(site_url('search/?sfield=post_both&skeyword=' . urlencode(element('sek_keyword', $result)))); ?>" target="_blank"><?php echo html_escape(element('sek_keyword', $result)); ?></a></td>
							<td><?php echo display_datetime(element('sek_datetime', $result), 'full'); ?></td>
							<td><a href="?sfield=sek_ip&amp;skeyword=<?php echo display_admin_ip(element('sek_ip', $result)); ?>"><?php echo display_admin_ip(element('sek_ip', $result)); ?></a></td>
							<td><?php echo element('display_name', $result); ?> <?php if (element('mem_userid', $result)) { ?> ( <a href="?sfield=search_keyword.mem_id&amp;skeyword=<?php echo element('mem_id', $result); ?>"><?php echo html_escape(element('mem_userid', $result)); ?></a> ) <?php } ?></td>
							<td><input type="checkbox" name="chk[]" class="list-chkbox" value="<?php echo element(element('primary_key', $view), $result); ?>" /></td>
						</tr>
					<?php
						}
					}
					if ( ! element('list', element('data', $view))) {
					?>
						<tr>
							<td colspan="6" class="nopost">자료가 없습니다</td>
						</tr>
					<?php
					}
					?>
					</tbody>
				</table>
			</div>
			<div class="box-info">
				<?php echo element('paging', $view); ?>
				<div class="pull-left ml20"><?php echo admin_listnum_selectbox();?></div>
				<?php echo $buttons; ?>
			</div>

		<?php echo form_close(); ?>

	</div>

	<form name="fsearch" id="fsearch" action="<?php echo current_full_url(); ?>" method="get">
		<div class="box-search">
			<div class="row">
				<div class="col-md-6 col-md-offset-3">
					<select class="form-control" name="sfield" >
						<?php echo element('search_option', $view); ?>
					</select>
					<div class="input-group">
						<input type="text" class="form-control" name="skeyword" value="<?php echo html_escape(element('skeyword', $view)); ?>" placeholder="Search for..." />
						<span class="input-group-btn">
							<button class="btn btn-default btn-sm" name="search_submit" type="submit">검색!</button>
						</span>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
