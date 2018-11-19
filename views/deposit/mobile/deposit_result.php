<?php $this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css'); ?>
<?php $this->managelayout->add_js(base_url('assets/js/deposit.js')); ?>

<h3><?php echo $this->cbconfig->item('deposit_name'); ?> 결제상세내역</h3>
<div class="credit">
	<table class="table table-striped table-bordered table-hover">
		<tbody>
			<tr>
				<td class="text-center col-md-3">주문번호</td>
				<td><?php echo element('dep_id', element('data', $view)); ?></td>
			</tr>
			<tr>
				<td class="text-center"><?php echo html_escape($this->cbconfig->item('deposit_name')); ?> 충전</td>
				<td><?php echo number_format(element('dep_deposit_request', element('data', $view))); ?> <?php echo html_escape($this->cbconfig->item('deposit_unit')); ?></td>
			</tr>
			<tr>
				<td class="text-center">결제방식</td>
				<td><?php echo $this->depositlib->paymethodtype[element('dep_pay_type', element('data', $view))];?></td>
			</tr>
			<tr>
				<td class="text-center">결제금액</td>
				<td><?php echo (element('dep_cash', element('data', $view))) ? number_format(abs(element('dep_cash', element('data', $view)))) : '아직 입금되지 않았습니다'; ?></td>
			</tr>
			<?php if (element('dep_deposit_datetime', element('data', $view)) > '0000-00-00 00:00:00') { ?>
				<tr>
					<td class="text-center">결제일시</td>
					<td><?php echo element('dep_deposit_datetime', element('data', $view)); ?></td>
				</tr>
			<?php } ?>
			<?php if (element('dep_pay_type', element('data', $view)) === 'bank') {?>
				<tr>
					<td class="text-center">입금자명</td>
					<td><?php echo html_escape(element('mem_realname', element('data', $view))); ?></td>
				</tr>
				<tr>
					<td class="text-center">입금계좌</td>
					<td><?php echo nl2br(html_escape($this->cbconfig->item('payment_bank_info'))); ?></td>
				</tr>
			<?php } ?>
			<?php if (element('dep_pay_type', element('data', $view)) === 'card') {?>
				<tr>
					<td class="text-center">승인번호</td>
					<td><?php echo html_escape(element('dep_app_no', element('data', $view))); ?></td>
				</tr>
			<?php } ?>
			<?php if (element('dep_pay_type', element('data', $view)) === 'phone') {?>
				<tr>
					<td class="text-center">휴대폰번호</td>
					<td><?php echo html_escape(element('dep_app_no', element('data', $view))); ?></td>
				</tr>
			<?php } ?>
			<?php if (element('dep_pay_type', element('data', $view)) === 'vbank' OR element('dep_pay_type', element('data', $view)) === 'realtime') {?>
				<tr>
					<td class="text-center">거래번호</td>
					<td><?php echo html_escape(element('dep_tno', element('data', $view))); ?></td>
				</tr>
			<?php } ?>
			<?php if (element('dep_pay_type', element('data', $view)) === 'card' OR element('dep_pay_type', element('data', $view)) === 'phone') {?>
				<tr>
					<td class="text-center">영수증</td>
					<td>
					<?php
					if (element('dep_pay_type', element('data', $view)) === 'card') {
						if ($this->cbconfig->item('use_pg_test')) {
							$receipturl = 'https://testadmin8.kcp.co.kr/assist/bill.BillActionNew.do?cmd=';
						} else {
							$receipturl = 'https://admin8.kcp.co.kr/assist/bill.BillActionNew.do?cmd=';
						}
					?>
						<a href="javascript:;" onclick="window.open('<?php echo $receipturl; ?>card_bill&tno=<?php echo element('dep_tno', element('data', $view)); ?>&amp;order_no=<?php echo element('dep_id', element('data', $view)); ?>&trade_mony=<?php echo element('dep_cash', element('data', $view)); ?>', 'winreceipt', 'width=500,height=690,scrollbars=yes,resizable=yes');">영수증 출력</a>
					<?php } ?>
					<?php
					if (element('dep_pay_type', element('data', $view)) === 'phone') {
						if ($this->cbconfig->item('use_pg_test')) {
							$receipturl = 'https://testadmin8.kcp.co.kr/assist/bill.BillActionNew.do?cmd=';
						} else {
							$receipturl = 'https://admin8.kcp.co.kr/assist/bill.BillActionNew.do?cmd=';
						}
					?>
						<a href="javascript:;" onclick="window.open('<?php echo $receipturl; ?>mcash_bill&tno=<?php echo element('dep_tno', element('data', $view)); ?>&amp;order_no=<?php echo element('dep_id', element('data', $view)); ?>&trade_mony=<?php echo element('dep_cash', element('data', $view)); ?>', 'winreceipt', 'width=500,height=690,scrollbars=yes,resizable=yes');">영수증 출력</a>
					<?php } ?>
				</td>
			</tr>
		<?php } ?>
		</tbody>
	</table>

	<h5>결제합계</h5>

	<table class="table table-striped table-bordered table-hover">
		<tbody>
			<tr>
				<td class="text-center col-md-3">총 주문액</td>
				<td><?php echo number_format(abs(element('dep_cash_request', element('data', $view))));?> 원</td>
			</tr>
			<tr>
				<td class="text-center">미결제액</td>
				<td>
					<?php
						$notyet = abs(element('dep_cash_request', element('data', $view))) - abs(element('dep_cash', element('data', $view)));
						echo number_format($notyet);
					?> 원
				</td>
			</tr>
			<tr>
				<td class="text-center">결제액</td>
				<td><?php echo number_format(abs(element('dep_cash', element('data', $view))));?> 원</td>
			</tr>
		</tbody>
	</table>
</div>
