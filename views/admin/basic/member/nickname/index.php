<div class="box">
	<div class="box-table">
		<?php
		echo show_alert_message($this->session->flashdata('message'), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
		$attributes = array('class' => 'form-inline', 'name' => 'flist', 'id' => 'flist');
		echo form_open(current_full_url(), $attributes);
		?>
			<div class="box-table-header">
				<div class="btn-group btn-group-sm" role="group">
					<a href="?" class="btn btn-sm <?php echo ($this->input->get('use') !== 'Y' && $this->input->get('use') !== 'N') ? 'btn-success' : 'btn-default'; ?>">전체닉네임</a>
					<a href="?use=Y" class="btn btn-sm <?php echo ($this->input->get('use') === 'Y') ? 'btn-success' : 'btn-default'; ?>">현재사용중인닉네임</a>
					<a href="?use=N" class="btn btn-sm <?php echo ($this->input->get('use') === 'N') ? 'btn-success' : 'btn-default'; ?>">사용종료된닉네임</a>
				</div>
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
							<th><a href="<?php echo element('mni_id', element('sort', $view)); ?>">번호</a></th>
							<th>아이디</th>
							<th>현재닉네임</th>
							<th><a href="<?php echo element('mni_nickname', element('sort', $view)); ?>">닉네임</a></th>
							<th><a href="<?php echo element('mni_start_datetime', element('sort', $view)); ?>">사용시작일</a></th>
							<th><a href="<?php echo element('mni_end_datetime', element('sort', $view)); ?>">사용종료일</a></th>
						</tr>
					</thead>
					<tbody>
					<?php
					if (element('list', element('data', $view))) {
						foreach (element('list', element('data', $view)) as $result) {
					?>
						<tr>
							<td><?php echo number_format(element('num', $result)); ?></td>
							<td><a href="?sfield=member_nickname.mem_id&amp;skeyword=<?php echo element('mem_id', $result); ?>"><?php echo html_escape(element('mem_userid', $result)); ?></a></td>
							<td><?php echo element('display_name', $result); ?></td>
							<td><?php echo html_escape(element('mni_nickname', $result)); ?></td>
							<td><?php echo display_datetime(element('mni_start_datetime', $result), 'full'); ?></td>
							<td><?php echo element('mni_end_datetime', $result) > '0000-00-00 00:00:00' ? display_datetime(element('mni_end_datetime', $result), 'full') : '현재 사용중'; ?></td>
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
