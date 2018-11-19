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
						<button type="button" class="btn btn-outline btn-default btn-sm btn-list-delete btn-list-selected disabled" data-list-delete-url = "<?php echo element('list_delete_url', $view); ?>" >선택삭제</button>
						<a href="<?php echo element('write_url', $view); ?>" class="btn btn-outline btn-danger btn-sm">FAQ추가</a>
					</div>
				<?php
				$buttons = ob_get_contents();
				ob_end_flush();
				?>
			</div>
			<div class="row">전체 : <?php echo element('total_rows', element('data', $view), 0); ?>건</div>
			<div class="table-responsive">
				<table class="table table-hover table-striped table-bordered">
					<thead>
						<tr>
							<th><a href="<?php echo element('fgr_id', element('sort', $view)); ?>">번호</a></th>
							<th><a href="<?php echo element('fgr_title', element('sort', $view)); ?>">제목</a></th>
							<th><a href="<?php echo element('fgr_key', element('sort', $view)); ?>">주소</a></th>
							<th><a href="<?php echo element('fgr_layout', element('sort', $view)); ?>">레이아웃</a></th>
							<th><a href="<?php echo element('fgr_mobile_layout', element('sort', $view)); ?>">모바일레이아웃</a></th>
							<th><a href="<?php echo element('fgr_skin', element('sort', $view)); ?>">스킨</a></th>
							<th><a href="<?php echo element('fgr_mobile_skin', element('sort', $view)); ?>">모바일스킨</a></th>
							<th><a href="<?php echo element('fgr_datetime', element('sort', $view)); ?>">작성일</a></th>
							<th>작성자</th>
							<th>FAQ 수</th>
							<th>내용추가</th>
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
							<td><a href="<?php echo admin_url('page/faq'); ?>/?fgr_id=<?php echo element(element('primary_key', $view), $result); ?>" ><?php echo html_escape(element('fgr_title', $result)); ?></a></td>
							<td><a href="<?php echo goto_url(faq_url(html_escape(element('fgr_key', $result)))); ?>" target="_blank"><?php echo faq_url(html_escape(element('fgr_key', $result))); ?></a></td>
							<td><?php echo element('fgr_layout', $result) ? html_escape(element('fgr_layout', $result)) : '일반설정따름'; ?></td>
							<td><?php echo element('fgr_mobile_layout', $result) ? html_escape(element('fgr_mobile_layout', $result)) : '일반설정따름'; ?></td>
							<td><?php echo element('fgr_skin', $result) ? html_escape(element('fgr_skin', $result)) : '일반설정따름'; ?></td>
							<td><?php echo element('fgr_mobile_skin', $result) ? html_escape(element('fgr_mobile_skin', $result)) : '일반설정따름'; ?></td>
							<td><?php echo display_datetime(element('fgr_datetime', $result), 'full'); ?></td>
							<td><?php echo element('display_name', $result); ?> <?php if (element('mem_userid', $result)) { ?> ( <a href="?sfield=faq_group.mem_id&amp;skeyword=<?php echo element('mem_id', $result); ?>"><?php echo html_escape(element('mem_userid', $result)); ?></a> ) <?php } ?></td>
							<td><a href="<?php echo admin_url('page/faq'); ?>/?fgr_id=<?php echo element(element('primary_key', $view), $result); ?>" ><?php echo (int) element('faqcount', $result); ?></a></td>
							<td><a href="<?php echo admin_url('page/faq'); ?>/write/?fgr_id=<?php echo element(element('primary_key', $view), $result); ?>" class="btn btn-outline btn-primary btn-xs">내용추가</a></td>
							<td><a href="<?php echo admin_url($this->pagedir); ?>/write/<?php echo element(element('primary_key', $view), $result); ?>?<?php echo $this->input->server('QUERY_STRING', null, ''); ?>" class="btn btn-outline btn-default btn-xs">수정</a></td>
							<td><input type="checkbox" name="chk[]" class="list-chkbox" value="<?php echo element(element('primary_key', $view), $result); ?>" /></td>
						</tr>
					<?php
						}
					}
					if ( ! element('list', element('data', $view))) {
					?>
						<tr>
							<td colspan="13" class="nopost">자료가 없습니다</td>
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
