<?php $this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css'); ?>

<h3>주문상세내역</h3>
<table class="table table-hover mt20">
	<thead>
		<tr class="success">
			<th>이미지</th>
			<th>상품명</th>
			<th class="text-right">다운로드</th>
			<th class="text-center">총수량</th>
			<th>판매가</th>
			<th>소계</th>
			<th>다운로드기간</th>
		</tr>
	</thead>
	<tbody>
	<?php
	$total_price_sum = 0;
	if (element('orderdetail', $view)) {
		foreach (element('orderdetail', $view) as $result) {
	?>
		<tr>
			<td><a href="<?php echo cmall_item_url(element('cit_key', element('item', $result))); ?>" title="<?php echo html_escape(element('cit_name', element('item', $result))); ?>"><img src="<?php echo thumb_url('cmallitem', element('cit_file_1', element('item', $result)), 60, 60); ?>" class="thumbnail" style="margin:0;width:60px;height:60px;" alt="<?php echo html_escape(element('cit_name', element('item', $result))); ?>" title="<?php echo html_escape(element('cit_name', element('item', $result))); ?>" /></a></td>
			<td colspan="2"><a href="<?php echo cmall_item_url(element('cit_key', element('item', $result))); ?>" title="<?php echo html_escape(element('cit_name', element('item', $result))); ?>"><?php echo html_escape(element('cit_name', element('item', $result))); ?></a>
				<ul class="cmall-options">
					<?php
					$total_num = 0;
					$total_price = 0;
					foreach (element('itemdetail', $result) as $detail) {
					?>
						<li><?php echo html_escape(element('cde_title', $detail)) . ' ' . element('cod_count', $detail);?>개 (+<?php echo number_format(element('cde_price', $detail)); ?>원)
							<?php
							if (element('cor_status', element('data', $view)) === '1') {
								if (element('possible_download', element('item', $result))) {
							?>
								<a href="<?php echo site_url('cmallact/download/' . element('cor_id', element('data', $view)) . '/' . element('cde_id', $detail));?>" type="button" name="download" class="btn btn-xs btn-black pull-right">다운로드</a>
							<?php } else { ?>
								<button type="button" class="btn btn-xs btn-danger pull-right">다운로드 기간 완료</button>
							<?php
								}
							} else {
							?>
								<button type="button" class="btn btn-xs btn-danger pull-right">입금확인중</button>
							<?php
							}
							?>
						</li>
					<?php
						$total_num += element('cod_count', $detail);
						$total_price += ((int) element('cit_price', element('item', $result)) + (int) element('cde_price', $detail)) * element('cod_count', $detail);
					}
					$total_price_sum += $total_price;
					?>
				</ul>
			</td>
			<td class="text-center"><?php echo number_format($total_num); ?></td>
			<td><?php echo number_format(element('cit_price', element('item', $result))); ?></td>
			<td><?php echo number_format($total_price); ?><input type="hidden" name="total_price[<?php echo element('cit_id', element('item', $result)); ?>]" value="<?php echo $total_price; ?>" /></td>
			<td>
				<?php
				if (element('cod_download_days', $detail)) {
					echo '구매후 ' . element('cod_download_days', $detail) . '일간 ( ~ ' . element('download_end_date', element('item', $result)) . ' 까지)';
				} else {
					echo '기간제한없음';
				}
				?>
			</td>
		</tr>
	<?php
		}
	}
	?>
	</tbody>
</table>
<div class="credit">
	<table class="table table-striped table-bordered table-hover">
		<tbody>
			<tr>
				<td class="text-center col-md-3">주문번호</td>
				<td><?php echo element('cor_id', element('data', $view)); ?></td>
			</tr>
			<tr>
				<td class="text-center">결제방식</td>
				<td><?php echo $this->cmalllib->paymethodtype[element('cor_pay_type', element('data', $view))];?></td>
			</tr>
			<tr>
				<td class="text-center">결제금액</td>
				<td><?php echo (element('cor_cash', element('data', $view))) ? number_format(abs(element('cor_cash', element('data', $view)))) : '아직 입금되지 않았습니다'; ?></td>
			</tr>
			<?php if (element('cor_approve_datetime', element('data', $view)) > '0000-00-00 00:00:00') { ?>
				<tr>
					<td class="text-center">결제일시</td>
					<td><?php echo element('cor_approve_datetime', element('data', $view)); ?></td>
				</tr>
			<?php } ?>
			<?php if (element('cor_pay_type', element('data', $view)) === 'bank') {?>
				<tr>
					<td class="text-center">입금자명</td>
					<td><?php echo html_escape(element('mem_realname', element('data', $view))); ?></td>
				</tr>
				<tr>
					<td class="text-center">입금계좌</td>
					<td><?php echo nl2br(html_escape($this->cbconfig->item('payment_bank_info'))); ?></td>
				</tr>
			<?php } ?>
			<?php if (element('cor_pay_type', element('data', $view)) === 'card') {?>
				<tr>
					<td class="text-center">승인번호</td>
					<td><?php echo html_escape(element('cor_app_no', element('data', $view))); ?></td>
				</tr>
			<?php } ?>
			<?php if (element('cor_pay_type', element('data', $view)) === 'phone') {?>
				<tr>
					<td class="text-center">휴대폰번호</td>
					<td><?php echo html_escape(element('cor_app_no', element('data', $view))); ?></td>
				</tr>
			<?php } ?>
			<?php if (element('cor_pay_type', element('data', $view)) === 'vbank' OR element('cor_pay_type', element('data', $view)) === 'realtime') {?>
				<tr>
					<td class="text-center">거래번호</td>
					<td><?php echo html_escape(element('cor_tno', element('data', $view))); ?></td>
				</tr>
			<?php } ?>
			<?php if (element('cor_pay_type', element('data', $view)) === 'card' OR element('cor_pay_type', element('data', $view)) === 'phone') {?>
			<tr>
				<td class="text-center">영수증</td>
				<td>
					<?php
					if (element('cor_pay_type', element('data', $view)) === 'card') {
						if ($this->cbconfig->item('use_pg_test')) {
							$receipturl = 'https://testadmin8.kcp.co.kr/assist/bill.BillActionNew.do?cmd=';
						} else {
							$receipturl = 'https://admin8.kcp.co.kr/assist/bill.BillActionNew.do?cmd=';
						}
					?>
						<a href="javascript:;" onclick="window.open('<?php echo $receipturl; ?>card_bill&tno=<?php echo element('cor_tno', element('data', $view)); ?>&amp;order_no=<?php echo element('cor_id', element('data', $view)); ?>&trade_mony=<?php echo element('cor_cash', element('data', $view)); ?>', 'winreceipt', 'width=500,height=690,scrollbars=yes,resizable=yes');" title="영수증 출력">영수증 출력</a>
					<?php } ?>
					<?php
					if (element('cor_pay_type', element('data', $view)) === 'phone') {
						if ($this->cbconfig->item('use_pg_test')) {
							$receipturl = 'https://testadmin8.kcp.co.kr/assist/bill.BillActionNew.do?cmd=';
						} else {
							$receipturl = 'https://admin8.kcp.co.kr/assist/bill.BillActionNew.do?cmd=';
						}
					?>
						<a href="javascript:;" onclick="window.open('<?php echo $receipturl; ?>mcash_bill&tno=<?php echo element('cor_tno', element('data', $view)); ?>&amp;order_no=<?php echo element('cor_id', element('data', $view)); ?>&trade_mony=<?php echo element('cor_cash', element('data', $view)); ?>', 'winreceipt', 'width=500,height=690,scrollbars=yes,resizable=yes');" title="영수증 출력">영수증 출력</a>
					<?php } ?>
				</td>
			</tr>
		<?php } ?>
		</tbody>
	</table>

	<h4>결제합계</h4>
	<table class="table table-striped table-bordered table-hover">
		<tbody>
			<tr>
				<td class="table-left">총 주문액</td>
				<td><?php echo number_format(abs(element('cor_cash_request', element('data', $view))));?> 원</td>
			</tr>
			<tr>
				<td class="table-left">미결제액</td>
				<td>
					<?php
						$notyet = abs(element('cor_cash_request', element('data', $view))) - abs(element('cor_cash', element('data', $view)));
						echo number_format($notyet);
					?> 원
				</td>
			</tr>
			<tr class="info">
				<td class="table-left">결제액</td>
				<td><?php echo number_format(abs(element('cor_cash', element('data', $view))));?> 원</td>
			</tr>
		</tbody>
	</table>
</div>
