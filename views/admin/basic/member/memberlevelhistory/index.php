<div class="box">
	<div class="box-table">
		<?php
		echo show_alert_message($this->session->flashdata('message'), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
		$attributes = array('class' => 'form-inline', 'name' => 'flist', 'id' => 'flist');
		echo form_open(current_full_url(), $attributes);
		?>
			<div class="box-table-header">
				이전레벨
				<select name="mlh_from" class="form-control" onChange="location.href='<?php echo current_url(); ?>?mlh_to=<?php echo $this->input->get('mlh_to')?>&mlh_from=' + this.value;">
					<option value="">전체레벨</option>
					<?php for ($i = 0; $i <= $this->cbconfig->item('max_level'); $i++) { ?>
						<option value="<?php echo $i; ?>" <?php echo set_select('mlh_from', $i, ($this->input->get('mlh_from') === (string) $i ? true : false)); ?>>레벨 <?php echo $i; ?></option>
					<?php } ?>
				</select>
				&nbsp;
				이후레벨
				<select name="mlh_to" class="form-control" onChange="location.href='<?php echo current_url(); ?>?mlh_from=<?php echo $this->input->get('mlh_from')?>&mlh_to=' + this.value;">
					<option value="">전체레벨</option>
					<?php for ($i = 0; $i <= $this->cbconfig->item('max_level'); $i++) { ?>
						<option value="<?php echo $i; ?>" <?php echo set_select('mlh_to', $i, ($this->input->get('mlh_to') === (string) $i ? true : false)); ?>>레벨 <?php echo $i; ?></option>
					<?php } ?>
				</select>
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
							<th>번호</th>
							<th>회원아이디</th>
							<th>회원명</th>
							<th>이전레벨</th>
							<th>이후레벨</th>
							<th>변경일</th>
							<th>변경이유</th>
							<th>변경아이피</th>
						</tr>
					</thead>
					<tbody>
					<?php
					if (element('list', element('data', $view))) {
						foreach (element('list', element('data', $view)) as $result) {
					?>
						<tr>
							<td><?php echo number_format(element('num', $result)); ?></td>
							<td><a href="?sfield=member_level_history.mem_id&amp;skeyword=<?php echo element('mem_id', $result); ?>"><?php echo html_escape(element('mem_userid', $result)); ?></a></td>
							<td><?php echo element('display_name', $result); ?></td>
							<td class="text-right"><?php echo number_format(element('mlh_from', $result)); ?></td>
							<td class="text-right"><?php echo number_format(element('mlh_to', $result)); ?></td>
							<td><?php echo display_datetime(element('mlh_datetime', $result), 'full'); ?></td>
							<td><?php echo html_escape(element('mlh_reason', $result)); ?></td>
							<td><a href="?sfield=mlh_ip&amp;skeyword=<?php echo display_admin_ip(element('mlh_ip', $result)); ?>"><?php echo display_admin_ip(element('mlh_ip', $result)); ?></a></td>
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
