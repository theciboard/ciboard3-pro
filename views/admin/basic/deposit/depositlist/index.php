<div class="box">
		<div class="box-table">
			<?php
			echo show_alert_message($this->session->flashdata('message'), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
			echo show_alert_message($this->session->flashdata('dangermessage'), '<div class="alert alert-auto-close alert-dismissible alert-danger"><button type="button" class="close alertclose" >&times;</button>', '</div>');
			$attributes = array('class' => 'form-inline', 'name' => 'flist', 'id' => 'flist');
			echo form_open(current_full_url(), $attributes);
			?>
				<div class="box-table-header">
					<div class="btn-group btn-group-sm" role="group">
						<a href="<?php echo admin_url('deposit/depositlist'); ?>" class="btn btn-sm <?php echo ( ! $this->input->get('dep_from_type') && ! $this->input->get('dep_to_type')) ? 'btn-success' : 'btn-default';?>">전체내역</a>
						<a href="<?php echo admin_url('deposit/depositlist'); ?>?dep_to_type=deposit" class="btn btn-sm <?php echo ($this->input->get('dep_to_type') === 'deposit') ? 'btn-success' : 'btn-default';?>">충전내역</a>
						<?php if ($this->input->get('dep_to_type') === 'deposit') { ?>
							<a href="<?php echo admin_url('deposit/depositlist'); ?>?dep_to_type=deposit&amp;dep_pay_type=bank" class="btn btn-sm <?php echo ($this->input->get('dep_pay_type') === 'bank') ? 'btn-info' : 'btn-default';?>">무통장</a>
							<a href="<?php echo admin_url('deposit/depositlist'); ?>?dep_to_type=deposit&amp;dep_pay_type=card" class="btn btn-sm <?php echo ($this->input->get('dep_pay_type') === 'card') ? 'btn-info' : 'btn-default';?>">카드</a>
							<a href="<?php echo admin_url('deposit/depositlist'); ?>?dep_to_type=deposit&amp;dep_pay_type=realtime" class="btn btn-sm <?php echo ($this->input->get('dep_pay_type') === 'realtime') ? 'btn-info' : 'btn-default';?>">실시간</a>
							<a href="<?php echo admin_url('deposit/depositlist'); ?>?dep_to_type=deposit&amp;dep_pay_type=vbank" class="btn btn-sm <?php echo ($this->input->get('dep_pay_type') === 'vbank') ? 'btn-info' : 'btn-default';?>">가상계좌</a>
							<a href="<?php echo admin_url('deposit/depositlist'); ?>?dep_to_type=deposit&amp;dep_pay_type=phone" class="btn btn-sm <?php echo ($this->input->get('dep_pay_type') === 'phone') ? 'btn-info' : 'btn-default';?>">핸드폰</a>
							<a href="<?php echo admin_url('deposit/depositlist'); ?>?dep_to_type=deposit&amp;dep_pay_type=service" class="btn btn-sm <?php echo ($this->input->get('dep_pay_type') === 'service') ? 'btn-info' : 'btn-default';?>">서비스</a>
							<a href="<?php echo admin_url('deposit/depositlist'); ?>?dep_to_type=deposit&amp;dep_pay_type=point" class="btn btn-sm <?php echo ($this->input->get('dep_pay_type') === 'point') ? 'btn-info' : 'btn-default';?>">포인트결제</a>
						<?php } ?>
						<a href="<?php echo admin_url('deposit/depositlist'); ?>?dep_from_type=deposit" class="btn btn-sm <?php echo ($this->input->get('dep_from_type') === 'deposit') ? 'btn-success' : 'btn-default';?>">사용내역</a>
					</div>
					<?php
					ob_start();
					?>
						<div class="btn-group pull-right" role="group" aria-label="...">
							<a href="<?php echo element('listall_url', $view); ?>" class="btn btn-outline btn-default btn-sm">전체목록</a>
							<a href="<?php echo element('write_url', $view); ?>" class="btn btn-outline btn-danger btn-sm"><?php echo $this->cbconfig->item('deposit_name'); ?> 변동내역추가</a>
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
								<th><a href="<?php echo element('dep_id', element('sort', $view)); ?>">번호</a></th>
								<th>회원아이디</th>
								<th>회원명</th>
								<th>구분</th>
								<th>일시</th>
								<th>결제</th>
								<th>내용</th>
								<th><?php echo $this->cbconfig->item('deposit_name'); ?> 변동</th>
								<th>현금/카드 변동</th>
								<th>포인트 변동</th>
								<th><?php echo $this->cbconfig->item('deposit_name'); ?> 잔액</th>
								<th>수정</th>
							</tr>
						</thead>
						<tbody>
						<?php
						if (element('list', element('data', $view))) {
							foreach (element('list', element('data', $view)) as $result) {
						?>
							<tr>
								<td><?php echo number_format(element('num', $result)); ?></td>
								<td><a href="?sfield=deposit.mem_id&amp;skeyword=<?php echo element('mem_id', $result); ?>"><?php echo html_escape(element('mem_userid', $result)); ?></a></td>
								<td><?php echo element('display_name', $result); ?></td>
								<td>
									<?php if (element('dep_deposit', $result) >= 0) { ?>
										<button type="button" class="btn btn-xs btn-primary" >충전</button>
									<?php } else { ?>
										<button type="button" class="btn btn-xs btn-danger" >사용</button>
									<?php } ?>
									<?php echo element('dep_type_display', $result); ?>
									<?php if( element('is_test', $result) ){ ?>
										<span class="btn btn-xs btn-warning">테스트결제</span>
									<?php } ?>
								</td>
								<td><?php echo display_datetime(element('dep_datetime', $result), 'full'); ?></td>
								<td><?php echo element('dep_pay_type', $result) ? $this->depositlib->paymethodtype[element('dep_pay_type', $result)] : ''; ?></td>
								<td><?php echo nl2br(html_escape(element('dep_content', $result))); ?></td>
								<td class="text-right"><?php echo number_format(element('dep_deposit', $result)); ?><?php echo $this->cbconfig->item('deposit_unit'); ?></td>
								<td class="text-right"><?php echo number_format(element('dep_cash', $result)) . '원'; ?></td>
								<td class="text-right"><?php echo number_format(element('dep_point', $result)); ?></td>
								<td class="text-right"><?php echo number_format(element('dep_deposit_sum', $result)); ?><?php echo $this->cbconfig->item('deposit_unit'); ?></td>
								<td><a href="<?php echo admin_url($this->pagedir); ?>/write/<?php echo element(element('primary_key', $view), $result); ?>?<?php echo $this->input->server('QUERY_STRING', null, ''); ?>" class="btn btn-outline btn-default btn-xs">수정</a></td>
							</tr>
						<?php
							}
						}
						if ( ! element('list', element('data', $view))) {
						?>
							<tr>
								<td colspan="12" class="nopost">자료가 없습니다</td>
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
