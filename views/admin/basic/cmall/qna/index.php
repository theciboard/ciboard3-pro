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
								<th><a href="<?php echo element('cre_id', element('sort', $view)); ?>">번호</a></th>
								<th><a href="<?php echo element('cit_name', element('sort', $view)); ?>">상품명</a></th>
								<th><a href="<?php echo element('cit_key', element('sort', $view)); ?>">상품코드</a></th>
								<th><a href="<?php echo element('cqa_title', element('sort', $view)); ?>">문의제목</a></th>
								<th>작성자</th>
								<th>일시</th>
								<th>답변자</th>
								<th>답변일시</th>
								<th>답변시 이메일 / SMS 수신</th>
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
								<td><a href="<?php echo goto_url(cmall_item_url(element('cit_key', $result))); ?>" target="_blank"><?php echo html_escape(element('cit_name', $result)); ?></a></td>
								<td><a href="<?php echo goto_url(cmall_item_url(element('cit_key', $result))); ?>" target="_blank"><?php echo html_escape(element('cit_key', $result)); ?></a></td>
								<td><?php echo html_escape(element('cqa_title', $result)); ?></td>
								<td><?php echo element('display_name', $result); ?> <?php if (element('mem_userid', $result)) { ?> ( <a href="?sfield=cmall_qna.mem_id&amp;skeyword=<?php echo element('mem_id', $result); ?>"><?php echo html_escape(element('mem_userid', $result)); ?></a> ) <?php } ?></td>
								<td><?php echo display_datetime(element('cqa_datetime', $result), 'full'); ?></td>
								<td><?php echo element('reply_display_name', $result) ? element('reply_display_name', $result) : '<span class="text-danger">답변하지 않음</span>'; ?></td>
								<td><?php echo element('reply_display_name', $result) ? display_datetime(element('cqa_reply_datetime', $result), 'full') : ' - '; ?></td>
								<td>
									<?php echo element('cqa_receive_email', $result) ? '<i class="fa fa-check-square-o"></i>' : '<i class="fa fa-square-o"></i>';; ?>
									<?php echo element('cqa_receive_sms', $result) ? '<i class="fa fa-check-square-o"></i>' : '<i class="fa fa-square-o"></i>';; ?>
								</td>
								<td><a href="<?php echo admin_url($this->pagedir); ?>/write/<?php echo element(element('primary_key', $view), $result); ?>?<?php echo $this->input->server('QUERY_STRING', null, ''); ?>" class="btn btn-outline btn-default btn-xs">수정</a></td>
								<td><input type="checkbox" name="chk[]" class="list-chkbox" value="<?php echo element(element('primary_key', $view), $result); ?>" /></td>
							</tr>
						<?php
							}
						}
						if ( ! element('list', element('data', $view))) {
						?>
							<tr>
								<td colspan="11" class="nopost">자료가 없습니다</td>
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
