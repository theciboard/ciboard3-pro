<div class="box">
	<div class="box-table">
		<?php
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
							<th><a href="<?php echo element('cur_id', element('sort', $view)); ?>">번호</a></th>
							<th><a href="<?php echo element('cur_ip', element('sort', $view)); ?>">아이피</a></th>
							<th>회원명</th>
							<th><a href="<?php echo element('cur_datetime', element('sort', $view)); ?>">접속시간</a></th>
							<th><a href="<?php echo element('cur_page', element('sort', $view)); ?>">접속페이지</a></th>
							<th><a href="<?php echo element('cur_url', element('sort', $view)); ?>">현재주소</a></th>
							<th><a href="<?php echo element('cur_referer', element('sort', $view)); ?>">이전주소</a></th>
							<th>OS</th>
							<th>Browser</th>
						</tr>
					</thead>
					<tbody>
					<?php
					if (element('list', element('data', $view))) {
						foreach (element('list', element('data', $view)) as $result) {
					?>
						<tr>
							<td><?php echo number_format(element('num', $result)); ?></td>
							<td><a href="?sfield=cur_ip&amp;skeyword=<?php echo display_admin_ip(element('cur_ip', $result)); ?>"><?php echo display_admin_ip(element('cur_ip', $result)); ?></a></td>
							<td><?php echo element('display_name', $result); ?></td>
							<td><?php echo display_datetime(element('cur_datetime', $result), 'full'); ?></td>
							<td><?php echo html_escape(element('cur_page', $result)); ?></td>
							<td><a href="<?php echo goto_url(element('cur_url', $result)); ?>" target="_blank"><?php echo html_escape(element('cur_url', $result)); ?></a></td>
							<td><a href="<?php echo goto_url(element('cur_referer', $result)); ?>" target="_blank"><?php echo html_escape(element('cur_referer', $result)); ?></a></td>
							<td><?php echo element('os', $result); ?></td>
							<td><?php echo element('browsername', $result); ?> <?php echo element('browserversion', $result); ?> <?php echo element('engine', $result); ?></td>
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
