<div class="box">
	<div class="box-table">
		<?php
		echo show_alert_message($this->session->flashdata('message'), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
		$attributes = array('class' => 'form-inline', 'name' => 'flist', 'id' => 'flist');
		echo form_open(current_full_url(), $attributes);
		?>
			<div class="box-table-header">
				<?php
				ob_start();
				?>
					<div class="btn-group pull-right" role="group" aria-label="...">
						<a href="<?php echo element('listall_url', $view); ?>" class="btn btn-outline btn-default btn-sm">전체목록</a>
						<button type="button" class="btn btn-outline btn-default btn-sm btn-list-update btn-list-selected disabled" data-list-update-url = "<?php echo element('list_update_url', $view); ?>" >선택수정</button>
						<button type="button" class="btn btn-outline btn-default btn-sm btn-list-delete btn-list-selected disabled" data-list-delete-url = "<?php echo element('list_delete_url', $view); ?>" >선택삭제</button>
						<a href="<?php echo element('write_url', $view); ?>" class="btn btn-outline btn-danger btn-sm">그룹추가</a>
					</div>
				<?php
				$buttons = ob_get_contents();
				ob_end_flush();
				?>
				<div class="row">전체 : <?php echo element('total_rows', element('data', $view), 0); ?>건</div>
			</div>
			<div class="table-responsive">
				<table class="table table-hover table-striped table-bordered">
					<thead>
						<tr>
							<th><a href="<?php echo element('bgr_id', element('sort', $view)); ?>">번호</a></th>
							<th><a href="<?php echo element('bgr_name', element('sort', $view)); ?>">제목</a> / <a href="<?php echo element('bgr_key', element('sort', $view)); ?>">URL</a></th>
							<th>PC (레이아웃 / 사이드바 / 스킨)</th>
							<th>모바일 (레이아웃 / 사이드바 / 스킨)</th>
							<th><a href="<?php echo element('bgr_order', element('sort', $view)); ?>">정렬순서</a></th>
							<th>게시판개수</th>
							<th>수정</th>
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
							<td>
								<input type="text" name="bgr_name[<?php echo element(element('primary_key', $view), $result); ?>]" class="form-control" value="<?php echo html_escape(element('bgr_name', $result)); ?>" />
								<br />
								<a href="<?php echo goto_url(group_url(html_escape(element('bgr_key', $result)))); ?>" target="_blank"><?php echo group_url(html_escape(element('bgr_key', $result))); ?></a>
							</td>
							<td class=" form-group-sm">
								<select class="form-control" name="group_layout[<?php echo element(element('primary_key', $view), $result); ?>]" >
									<?php echo element('group_layout_option', $result); ?>
								</select>
								<br />
								<select class="form-control" name="group_sidebar[<?php echo element(element('primary_key', $view), $result); ?>]">
									<option value="">기본설정따름</option>
									<option value="1" <?php echo set_select('group_sidebar[' . element(element('primary_key', $view), $result) . ']', '1', (element('group_sidebar', element('meta', $result)) === '1' ? true : false)); ?> >사용</option>
									<option value="2" <?php echo set_select('group_sidebar[' . element(element('primary_key', $view), $result) . ']', '2', (element('group_sidebar', element('meta', $result)) === '2' ? true : false)); ?> >사용하지않음</option>
								</select>
								<br />
								<select class="form-control" name="group_skin[<?php echo element(element('primary_key', $view), $result); ?>]" >
									<?php echo element('group_skin_option', $result); ?>
								</select>
							</td>
							<td class=" form-group-sm">
								<select class="form-control" name="group_mobile_layout[<?php echo element(element('primary_key', $view), $result); ?>]" >
									<?php echo element('group_mobile_layout_option', $result); ?>
								</select>
								<br />
								<select class="form-control" name="group_mobile_sidebar[<?php echo element(element('primary_key', $view), $result); ?>]">
									<option value="">기본설정따름</option>
									<option value="1" <?php echo set_select('group_mobile_sidebar[' . element(element('primary_key', $view), $result) . ']', '1', (element('group_mobile_sidebar', element('meta', $result)) === '1' ? true : false)); ?> >사용</option>
									<option value="2" <?php echo set_select('group_mobile_sidebar[' . element(element('primary_key', $view), $result) . ']', '2', (element('group_mobile_sidebar', element('meta', $result)) === '2' ? true : false)); ?> >사용하지않음</option>
								</select>
								<br />
								<select class="form-control" name="group_mobile_skin[<?php echo element(element('primary_key', $view), $result); ?>]" >
									<?php echo element('group_mobile_skin_option', $result); ?>
								</select>
							</td>
							<td><input type="number" name="bgr_order[<?php echo element(element('primary_key', $view), $result); ?>]" class="form-control" value="<?php echo html_escape(element('bgr_order', $result)); ?>" /></td>
							<td><a href="<?php echo admin_url('board/boards?sfield=bgr_id&amp;skeyword=' . element(element('primary_key', $view), $result)); ?>"><?php echo element('brd_cnt', $result, 0); ?></a></td>
							<td><a href="<?php echo admin_url($this->pagedir); ?>/write/<?php echo element(element('primary_key', $view), $result); ?>?<?php echo $this->input->server('QUERY_STRING', null, ''); ?>" class="btn btn-outline btn-default btn-xs">수정</a></td>
							<td><input type="checkbox" name="chk[]" class="list-chkbox" value="<?php echo element(element('primary_key', $view), $result); ?>" /></td>
						</tr>
					<?php
						}
					}
					if ( ! element('list', element('data', $view))) {
					?>
						<tr>
							<td colspan="8" class="nopost">자료가 없습니다</td>
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
