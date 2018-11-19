<div class="box">
	<div class="box-table">
		<?php
		echo show_alert_message($this->session->flashdata('message'), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
		$attributes = array('class' => 'form-inline', 'name' => 'flist', 'id' => 'flist');
		echo form_open(current_full_url(), $attributes);
		?>
			<div class="box-table-header">
				<ul class="nav nav-pills">
					<li role="presentation" class="active"><a href="<?php echo admin_url($this->pagedir); ?>">상품다운로드</a></li>
					<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/graph'); ?>">기간별 그래프</a></li>
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
					<thead>
						<tr>
							<th><a href="<?php echo element('cdo_id', element('sort', $view)); ?>">번호</a></th>
							<th>파일</th>
							<th>파일크기</th>
							<th><a href="<?php echo element('cit_name', element('sort', $view)); ?>">상품명</a></th>
							<th><a href="<?php echo element('cit_key', element('sort', $view)); ?>">상품코드</a></th>
							<th>상품이미지</th>
							<th><a href="<?php echo element('cde_title', element('sort', $view)); ?>">세부사항</a></th>
							<th><a href="<?php echo element('cdd_datetime', element('sort', $view)); ?>">다운로드일시</a></th>
							<th>다운로드 IP</th>
							<th>다운로드 회원</th>
							<th>OS</th>
							<th>Browser</th>
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
							<td><a href="<?php echo element('download_link', $result); ?>"><?php echo html_escape(element('cde_originname', $result)); ?></a></td>
							<td><?php echo byte_format(element('cde_filesize', $result), 2); ?></td>
							<td><a href="<?php echo goto_url(element('itemurl', $result)); ?>" target="_blank"><?php echo html_escape(element('cit_name', $result)); ?></a></td>
							<td><a href="<?php echo goto_url(element('itemurl', $result)); ?>" target="_blank"><?php echo html_escape(element('cit_key', $result)); ?></a></td>
							<td>
							<?php if (element('cit_file_1', $result)) {?>
								<a href="<?php echo goto_url(cmall_item_url(html_escape(element('cit_key', $result)))); ?>" target="_blank">
									<img src="<?php echo thumb_url('cmallitem', element('cit_file_1', $result), 80); ?>" alt="<?php echo html_escape(element('cde_title', $result)); ?>" title="<?php echo html_escape(element('cde_title', $result)); ?>" class="thumbnail mg0" />
								</a>
							<?php } ?>
							</td>
							<td><?php echo html_escape(element('cde_title', $result)); ?></td>
							<td><?php echo display_datetime(element('cdd_datetime', $result), 'full'); ?></td>
							<td><a href="?sfield=cdo_ip&amp;skeyword=<?php echo display_admin_ip(element('cdo_ip', $result)); ?>"><?php echo display_admin_ip(element('cdo_ip', $result)); ?></a></td>
							<td><?php echo element('display_name', $result); ?> <?php if (element('mem_userid', element('member', $result))) { ?> ( <a href="?sfield=cmall_download_log.mem_id&amp;skeyword=<?php echo element('mem_id', $result); ?>"><?php echo html_escape(element('mem_userid', element('member', $result))); ?></a> ) <?php } ?></td>
							<td><?php echo element('os', $result); ?></td>
							<td><?php echo element('browsername', $result); ?> <?php echo element('browserversion', $result); ?> <?php echo element('engine', $result); ?></td>
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
