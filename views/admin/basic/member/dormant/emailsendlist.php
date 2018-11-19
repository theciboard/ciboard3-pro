<div class="box">
	<div class="box-header">
		<ul class="nav nav-tabs">
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir); ?>">기본정보</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/cleantodormant'); ?>">휴면계정일괄정리</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/emailtowaiting'); ?>">안내메일일괄발송</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/emailcontent'); ?>">안내메일내용</a></li>
			<li role="presentation" class="active"><a href="<?php echo admin_url($this->pagedir . '/emailsendlist'); ?>">안내메일발송내역</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/waitinglist'); ?>">휴면처리해야할회원</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/dormantlist'); ?>">휴면중인회원</a></li>
		</ul>
	</div>
	<div class="box-table">
		<div class="alert alert-info">
			오랫동안 로그인을 하지 않아 곧 휴면처리될 회원에게 안내메일을 발송한 내역을 이 곳에서 확인이 가능합니다 .<br />
		</div>
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
							<th><a href="<?php echo element('mem_id', element('sort', $view)); ?>">번호</a></th>
							<th><a href="<?php echo element('mem_userid', element('sort', $view)); ?>">아이디</a></th>
							<th><a href="<?php echo element('mem_username', element('sort', $view)); ?>">실명</a></th>
							<th><a href="<?php echo element('mem_nickname', element('sort', $view)); ?>">닉네임</a></th>
							<th><a href="<?php echo element('mem_email', element('sort', $view)); ?>">이메일</a></th>
							<th><a href="<?php echo element('mem_register_datetime', element('sort', $view)); ?>">가입일</a></th>
							<th><a href="<?php echo element('mem_lastlogin_datetime', element('sort', $view)); ?>">최근로그인</a></th>
							<th><a href="<?php echo element('mdn_dormant_datetime', element('sort', $view)); ?>">정리예정일</a></th>
							<th><a href="<?php echo element('mdn_dormant_notify_datetime', element('sort', $view)); ?>">안내메일발송일</a></th>
						</tr>
					</thead>
					<tbody>
					<?php
					if (element('list', element('data', $view))) {
						foreach (element('list', element('data', $view)) as $result) {
					?>
						<tr>
							<td><?php echo number_format(element('num', $result)); ?></td>
							<td><?php echo html_escape(element('mem_userid', $result)); ?></td>
							<td><?php echo html_escape(element('mem_username', $result)); ?></td>
							<td><?php echo html_escape(element('mem_nickname', $result)); ?></td>
							<td><?php echo html_escape(element('mem_email', $result)); ?></td>
							<td><?php echo display_datetime(element('mem_register_datetime', $result), 'full'); ?></td>
							<td>
								<?php echo display_datetime(element('mem_lastlogin_datetime', $result), 'full'); ?>
								(<?php echo element('daygap', $result); ?>일전)
							</td>
							<td><?php echo display_datetime(element('mdn_dormant_datetime', $result), 'full'); ?></td>
							<td><?php echo display_datetime(element('mdn_dormant_notify_datetime', $result), 'full'); ?></td>
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
