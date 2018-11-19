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
							<th><a href="<?php echo element('ssc_id', element('sort', $view)); ?>">번호</a></th>
							<th>그룹</th>
							<th>이름</th>
							<th>전화번호</th>
							<th>전송일시</th>
							<th>예약</th>
							<th>전송</th>
							<th>전송내용</th>
							<th>비고</th>
						</tr>
					</thead>
					<tbody>
					<?php
					if (element('list', element('data', $view))) {
						foreach (element('list', element('data', $view)) as $result) {
					?>
						<tr>
							<td><?php echo number_format(element('num', $result)); ?></td>
							<td><?php echo element('mem_id', $result) && element('smg_id', $result) ? element('smg_name', element(element('smg_id', $result), element('group', $view))) : ''; ?></td>
							<td><?php echo html_escape(element('sme_name', $result)); ?></td>
							<td><?php echo html_escape(element('ssh_phone', $result)); ?></td>
							<td><?php echo display_datetime(element('ssh_datetime', $result), 'full'); ?></td>
							<td><?php echo element('ssc_booking', $result) > '0000-00-00 00:00:00' ? '예약' : ''; ?></td>
							<td><?php echo element('ssh_success', $result) === '1'? '성공' : '실패'; ?></td>
							<td><?php echo nl2br(html_escape(element('ssc_content', $result))); ?></td>
							<td>
								로그 : <?php echo nl2br(html_escape(element('ssh_log', $result))); ?><br />
								메모 : <?php echo nl2br(html_escape(element('ssh_memo', $result))); ?>
							</td>
						</tr>
					<?php
						}
					}
					if ( ! element('list', element('data', $view))) {
					?>
						<tr>
							<td colspan="9" class="nopost">자료가 없습니다</td>
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
