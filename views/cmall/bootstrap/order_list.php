<?php $this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css'); ?>

<div id="orderlist">
	<h3>주문 내역 관리</h3>

	<div class="credit table-responsive">
		<span class="list-total">전체 <?php echo number_format(element('total_rows', element('data', $view), 0)); ?> 건</span>
		<table class="table table-striped table-hover">
			<thead>
				<tr>
					<th>주문번호</th>
					<th class="text-center">주문금액</th>
					<th class="text-center">입금액</th>
					<th class="text-center">미입금액</th>
				</tr>
			</thead>
			<tbody>
			<?php
			if (element('list', element('data', $view))) {
				foreach (element('list', element('data', $view)) as $result) {
			?>
				<tr>
					<td>
						<?php
						if (element('cor_status', $result) === '1') {
							echo '<span class="btn btn-xs btn-info">입금완료</span>';
						} elseif (element('cor_status', $result) === '2') {
							echo '<span class="btn btn-xs btn-danger">주문취소</span>';
						} elseif ( ! element('cor_status', $result)) {
							echo '<span class="btn btn-xs btn-warning">입금확인중</span>';
						}
						?>
						<a href="<?php echo site_url('cmall/orderresult/' . element('cor_id', $result)); ?>" class="bold"><?php echo html_escape(element('cor_id', $result)); ?></a>
						<span class="date"> [<?php echo display_datetime(element('cor_datetime', $result), 'full'); ?>]</span>
					
					</td>
					<td class="text-right"><?php echo number_format((int) element('cor_total_money', $result)); ?>원</td>
					<td class="text-right text-primary"><?php echo number_format((int) element('cor_cash', $result) + (int) element('cor_deposit', $result)); ?>원</td>
					<td class="text-right text-danger"><strong><?php echo number_format((int) element('cor_cash_request', $result) - (int) element('cor_cash', $result)); ?></strong>원</td>
					
				</tr>
			<?php
				}
			}
			if ( ! element('list', element('data', $view))) {
			?>
				<tr>
					<td colspan="6" class="nopost">회원님이 주문 내역이 없습니다</td>
				</tr>
			<?php
			}
			?>
			</tbody>
		</table>
		<nav><?php echo element('paging', $view); ?></nav>
	</div>
</div>
