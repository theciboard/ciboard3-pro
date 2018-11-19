<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css');
?>

<div id="order-result">
	<h3>주문상세내역</h3>

	<ul class="prd-list">

		<?php
		$total_price_sum = 0;
		if (element('orderdetail', $view)) {
			foreach (element('orderdetail', $view) as $result) {
		?>
			<li>
				<div class="col-xs-12 col-md-9 prd-info">
					<div class="prd-img"><a href="<?php echo cmall_item_url(element('cit_key', element('item', $result))); ?>" title="<?php echo html_escape(element('cit_name', element('item', $result))); ?>" ><img src="<?php echo thumb_url('cmallitem', element('cit_file_1', element('item', $result)), 60, 60); ?>" class="thumbnail" style="margin:0;width:60px;height:60px;" alt="<?php echo html_escape(element('cit_name', element('item', $result))); ?>" title="<?php echo html_escape(element('cit_name', element('item', $result))); ?>" /></a></div>
					<a href="<?php echo cmall_item_url(element('cit_key', element('item', $result))); ?>" title="<?php echo html_escape(element('cit_name', element('item', $result))); ?>"><?php echo html_escape(element('cit_name', element('item', $result))); ?></a>
					<ul class="cmall-options">
						<?php
						$total_num = 0;
						$total_price = 0;
						foreach (element('itemdetail', $result) as $detail) {
						?>
							<li>
								<i class="fa fa-angle-right" aria-hidden="true"></i>
								<?php
								if (element('cor_status', element('data', $view)) === '1') {
									if (element('possible_download', element('item', $result))) {
								?>
									<a href="<?php echo site_url('cmallact/download/' . element('cor_id', element('data', $view)) . '/' . element('cde_id', $detail));?>" type="button" name="download" class="btn btn-xs btn-info"><i class="fa fa-download" aria-hidden="true"></i> 다운로드</a>
								<?php } else { ?>
									<button type="button" class="btn btn-xs btn-default disabled ">다운로드 기간 완료</button>
								<?php
									}
								} else {
								?>
									<button type="button" class="btn btn-xs btn-warning">입금확인중</button>
								<?php
								}
								?>
								<?php echo html_escape(element('cde_title', $detail)) . ' ' . element('cod_count', $detail);?>개 (+<?php echo number_format(element('cde_price', $detail)); ?>원)
							
							</li>
						<?php
							$total_num += element('cod_count', $detail);
							$total_price += ((int) element('cit_price', element('item', $result)) + (int) element('cde_price', $detail)) * element('cod_count', $detail);
						}
						$total_price_sum += $total_price;
						?>
					</ul>
				</div>
				<div class="col-xs-12 col-md-3 prd-price">
					<div><span>수량 :</span> <?php echo number_format($total_num); ?> 개</div>
					<div><span>판매가 :</span> <?php echo number_format(element('cit_price', element('item', $result))); ?> 원</div>
					<div class="prd-total"><span>소계 :</span> <strong><?php echo number_format($total_price); ?><input type="hidden" name="total_price[<?php echo element('cit_id', element('item', $result)); ?>]" value="<?php echo $total_price; ?>" /></strong> 원</div>
					<div>
						<span>다운로드 :</span>
						<?php
						if (element('cod_download_days', $detail)) {
							echo '구매후 <strong>' . element('cod_download_days', $detail) . '</strong>일간';
							if( element('download_end_date', element('item', $result)) ){
								echo '<br>(~' . element('download_end_date', element('item', $result)) . ' 까지)';
							}
						} else {
							echo '기간제한없음';
						}
						?>
					</div>
				</div>
			</li>
		<?php
			}
		}
		?>
	</ul>


	<div class="credit row">
		<div class="col-xs-12 col-md-6">
			<div class="ord-info">
				<h5>결제정보</h5>
				<table class="table">
					<tbody>
						<tr>
							<th>주문번호</th>
							<td><?php echo element('cor_id', element('data', $view)); ?></td>
						</tr>
						<tr>
							<th>결제방식</th>
							<td><?php echo $this->cmalllib->paymethodtype[element('cor_pay_type', element('data', $view))];?></td>
						</tr>
						<tr>
							<th>결제금액</th>
							<td><?php echo (element('cor_cash', element('data', $view))) ? number_format(abs(element('cor_cash', element('data', $view)))) : '아직 입금되지 않았습니다'; ?></td>
						</tr>
						<?php if (element('cor_approve_datetime', element('data', $view)) > '0000-00-00 00:00:00') { ?>
							<tr>
								<th>결제일시</th>
								<td><?php echo element('cor_approve_datetime', element('data', $view)); ?></td>
							</tr>
						<?php } ?>
						<?php if (element('cor_pay_type', element('data', $view)) === 'bank') {?>
							<tr>
								<th>입금자명</th>
								<td><?php echo html_escape(element('mem_realname', element('data', $view))); ?></td>
							</tr>
							<tr>
								<th>입금계좌</th>
								<td><?php echo nl2br(html_escape($this->cbconfig->item('payment_bank_info'))); ?></td>
							</tr>
						<?php } ?>
						<?php if (element('cor_pay_type', element('data', $view)) === 'card') {?>
							<tr>
								<th>승인번호</th>
								<td><?php echo html_escape(element('cor_app_no', element('data', $view))); ?></td>
							</tr>
						<?php } ?>
						<?php if (element('cor_pay_type', element('data', $view)) === 'phone') {?>
							<tr>
								<th>휴대폰번호</th>
								<td><?php echo html_escape(element('cor_app_no', element('data', $view))); ?></td>
							</tr>
						<?php } ?>
						<?php if (element('cor_pay_type', element('data', $view)) === 'vbank' OR element('cor_pay_type', element('data', $view)) === 'realtime') {?>
							<tr>
								<th>거래번호</th>
								<td><?php echo html_escape(element('cor_tno', element('data', $view))); ?></td>
							</tr>
						<?php } ?>
						<?php if (element('cor_pay_type', element('data', $view)) === 'card' OR element('cor_pay_type', element('data', $view)) === 'phone') {?>
							<tr>
								<th>영수증</th>
								<td>
								<?php if( $receipt_link_js = element('card_receipt_js', element('data', $view)) ){ ?>
								<script language="JavaScript" src="<?php echo $receipt_link_js; ?>"></script>
								<?php } ?>
								<a href="#" onclick="<?php echo element('card_receipt_script', element('data', $view)); ?>">영수증 출력</a>
							</td>
						</tr>
						<?php } ?>
						<?php if ( element('cor_pay_type', element('data', $view)) === 'vbank' ){	//가상계좌이면 ?>
							<tr>
								<th>입금계좌</th>
								<td><?php echo element('cor_bank_info', element('data', $view)); ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
		<div class="col-xs-12 col-md-6 ">
			<div class="pay-info">
				<h5>결제합계</h5>
				<ul>
					<li>
						<span class="info-tit">총 주문액</span>
						<?php echo number_format(abs(element('cor_cash_request', element('data', $view))));?> 원
					</li>
					<li>
						<span class="info-tit">미결제액</span>
						<?php
						$notyet = abs(element('cor_cash_request', element('data', $view))) - abs(element('cor_cash', element('data', $view)));
						echo number_format($notyet);
						?> 원
					</li>
					<li>
						<span class="info-tit">결제액</span>
						<strong><?php echo number_format(abs(element('cor_cash', element('data', $view))));?></strong> 원
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>