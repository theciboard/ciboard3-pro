<div class="box">
	<div class="box-header">
		<ul class="nav nav-tabs">
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir); ?>">기본정보</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/cleantodormant'); ?>">휴면계정일괄정리</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/emailtowaiting'); ?>">안내메일일괄발송</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/emailcontent'); ?>">안내메일내용</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/emailsendlist'); ?>">안내메일발송내역</a></li>
			<li role="presentation" class="active"><a href="<?php echo admin_url($this->pagedir . '/waitinglist'); ?>">휴면처리해야할회원</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/dormantlist'); ?>">휴면중인회원</a></li>
		</ul>
	</div>
	<div class="box-table">
		<div class="alert alert-info">
			휴면처리해야할 회원이란,<br />
			최종로그인 후 일정시간동안 한 번도 로그인하지 않는 회원을 말합니다.<br />
			'자동정리기능' 을 사용하시는 경우에는 휴먼처리해야할 회원에 목록이 쌓이지 않으며,<br />
			'자동정리기능' 을 사용하지 않는 경우에 이 곳에서 휴먼처리해야할 회원 목록을 확인하실 수 있습니다.<br />
			이 곳에 보여지는 회원은 기본정보에서 설정한 것에 따라, <strong><?php echo element('period_text', $view); ?></strong> 동안 한 번도 로그인하지 않은 회원입니다.
		</div>
		<?php if ($this->cbconfig->item('member_dormant_auto_clean')) { ?>
			<div class="alert alert-danger">
				회원님은 현재 '자동정리기능' 을 사용하고 계시므로, 휴먼처리해야할 회원 목록이 나타나지 않습니다.
			</div>
		<?php } else {?>
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
							<button type="button" class="btn btn-outline btn-default btn-sm btn-list-update btn-list-selected disabled" data-list-update-url = "<?php echo element('list_update_url', $view); ?>" >별도저장소로이동</button>
							<button type="button" class="btn btn-outline btn-default btn-sm btn-list-delete btn-list-selected disabled" data-list-delete-url = "<?php echo element('list_delete_url', $view); ?>" >선택한회원영구삭제</button>
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
								<th>안내메일발송일</th>
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
								<td><?php echo html_escape(element('mem_userid', $result)); ?></td>
								<td>
									<span><?php echo html_escape(element('mem_username', $result)); ?></span>
									<?php echo element('mem_is_admin', $result) ? '<span class="label label-primary">최고관리자</span>' : ''; ?>
								</td>
								<td><?php echo element('display_name', $result); ?></td>
								<td><?php echo html_escape(element('mem_email', $result)); ?></td>
								<td><?php echo display_datetime(element('mem_register_datetime', $result), 'full'); ?></td>
								<td>
									<?php echo display_datetime(element('mem_lastlogin_datetime', $result), 'full'); ?>
									(<?php echo element('daygap', $result); ?>일전)
								</td>
								<td><?php echo element('mdn_dormant_notify_datetime', $result); ?></td>
								<td><input type="checkbox" name="chk[]" class="list-chkbox" value="<?php echo element(element('primary_key', $view), $result); ?>" /></td>
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
		<?php } ?>
	</div>
	<?php if ( ! $this->cbconfig->item('member_dormant_auto_clean')) { ?>
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
	<?php } ?>
</div>
