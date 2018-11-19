<table width="100%" border="0" cellpadding="0" cellspacing="0" style="border-left: 1px solid rgb(226,226,225);border-right: 1px solid rgb(226,226,225);background-color: rgb(255,255,255);border-top:10px solid #348fe2; border-bottom:5px solid #348fe2;border-collapse: collapse;">
	<tr>
		<td width="101" style="padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;"><?php echo html_escape($this->cbconfig->item('site_title')); ?></td>
		<td width="497" style="font-size:12px;padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;"><span style="font-size:14px;font-weight:bold;color:rgb(0,0,0)"><?php echo html_escape(element('title', element('emailcontent', $emailform))); ?></span></td>
	</tr>
	<tr style="border-top:1px solid #e2e2e2; border-bottom:1px solid #e2e2e2;">
		<td colspan="2" style="padding:20px 30px;font-family: Arial,sans-serif;color: rgb(0,0,0);font-size: 14px;line-height: 20px;">
			<div>
				<table style="margin:0 auto 20px;width:94%;border:0;border-collapse:collapse">
					<caption style="padding:0 0 5px;font-weight:bold"> 주문 내역</caption>
					<colgroup>
						<col style="width:120px;">
						<col>
					</colgroup>
					<tbody>
					<?php
					$total_price = 0;
					if (element('orderdetail', $emailform)) {
						foreach (element('orderdetail', $emailform) as $key => $value) {

						$price = (int) element('cit_price', element('item', $value));
					?>
						<tr>
							<th style="padding:4px;border-top:1px solid #E8E8E8;border-bottom:1px solid #E8E8E8;background:#F4F4F4;">상품명</th>
							<td style="padding:4px;border-top:1px solid #E8E8E8;border-bottom:1px solid #E8E8E8;"><a href="<?php echo cmall_item_url(element('cit_key', element('item', $value))); ?>" style="text-decoration:none" target="_blank"><span style="display:inline-block;vertical-align:middle"><img src="<?php echo thumb_url('cmallitem', element('cit_file_1', element('item', $value)), 70, 70); ?>" width="70" height="70" alt="<?php echo html_escape(element('cit_name', element('item', $value))); ?>" title="<?php echo html_escape(element('cit_name', element('item', $value))); ?>" /></span> <?php echo html_escape(element('cit_name', element('item', $value))); ?></a></td>
						</tr>
						<tr>
							<th style="padding:4px;border-top:1px solid #E8E8E8;border-bottom:1px solid #E8E8E8;background:#F4F4F4;">판매가격</th>
							<td style="padding:4px;border-top:1px solid #E8E8E8;border-bottom:1px solid #E8E8E8;"><?php echo number_format(element('cit_price', element('item', $value))); ?>원</td>
						</tr>
						<?php if (element('itemdetail', $value)) {?>
							<tr>
								<th style="padding:4px;border-top:1px solid #E8E8E8;border-bottom:1px solid #E8E8E8;background:#F4F4F4;">선택옵션 </th>
								<td style="padding:4px;border-top:1px solid #E8E8E8;border-bottom:1px solid #E8E8E8;">
									<ul style="margin:0;padding:0">
										<?php foreach (element('itemdetail', $value) as $dval) {?>
										<li style="padding:5px 0;list-style:none"><?php echo html_escape(element('cde_title', $dval)); ?> (+<?php echo number_format(element('cde_price', $dval)); ?>원) <?php echo number_format(element('cod_count', $dval)); ?>개</li>
										<?php
											$price += element('cde_price', $dval);
											$price += (int) element('cde_price', $dval) * element('cod_count', $dval);
										}
										?>
									</ul>
								</td>
							</tr>
						<?php } ?>
						<tr>
							<th scope="row" style="padding:4px;border-top:1px solid #E8E8E8;border-bottom:1px solid #E8E8E8;background:#F4F4F4;">소계</th>
							<td style="padding:4px;border-top:1px solid #E8E8E8;border-bottom:1px solid #E8E8E8;"><?php echo number_format($price); ?>원</td>
						</tr>
						<?php
								$total_price += $price;
							}
						}
						?>
						<tr>
							<th style="padding:4px;border-top:1px solid #E8E8E8;border-bottom:1px solid #E8E8E8;background:#F4F4F4;">주문합계</th>
							<td style="padding:4px;border-top:1px solid #E8E8E8;border-bottom:1px solid #E8E8E8;"><?php echo number_format($total_price); ?>원</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div>
				<table style="margin:0 auto 20px;width:94%;border:0;border-collapse:collapse">
					<caption style="padding:0 0 5px;font-weight:bold"> 주문자 정보</caption>
					<colgroup>
						<col style="width:120px;">
						<col>
					</colgroup>
					<tbody>
						<tr>
							<th style="padding:4px;border-top:1px solid #E8E8E8;border-bottom:1px solid #E8E8E8;background:#F4F4F4;">주문자명</th>
							<td style="padding:4px;border-top:1px solid #E8E8E8;border-bottom:1px solid #E8E8E8;"><?php echo html_escape(element('mem_realname', element('order', $emailform))); ?></td>
						</tr>
						<tr>
							<th style="padding:4px;border-top:1px solid #E8E8E8;border-bottom:1px solid #E8E8E8;background:#F4F4F4;">연락처</th>
							<td style="padding:4px;border-top:1px solid #E8E8E8;border-bottom:1px solid #E8E8E8;"><?php echo html_escape(element('mem_phone', element('order', $emailform))); ?></td>
						</tr>
						<tr>
							<th style="padding:4px;border-top:1px solid #E8E8E8;border-bottom:1px solid #E8E8E8;background:#F4F4F4;">하고싶은 말</th>
							<td style="padding:4px;border-top:1px solid #E8E8E8;border-bottom:1px solid #E8E8E8;"><?php echo nl2br(html_escape(element('cor_content', element('order', $emailform)))); ?></td>
						</tr>
					</tbody>
				</table>
			</div>
			<p><a href="<?php echo site_url('cmall/orderlist'); ?>" target="_blank" style="font-weight:bold;">주문 상세내역 확인하기</a></p>
			<p>&nbsp;</p>
		</td>
	</tr>
</table>
