<!-- 장바구니 옵션 시작 { -->

<?php
$attributes = array('class' => 'form-inline', 'name' => 'foption', 'id' => 'foption', 'onsubmit' => 'return fcart_submit(this)');
echo form_open(site_url('cmallact/optionupdate'), $attributes);
?>
	<input type="hidden" name="cit_id" value="<?php echo element('cit_id', element('item', $view)); ?>" />
	<div class="popup-cart">
		<p>상품옵션</p>
		<div class="product-option table-responsive">
			<table class="table table-bordered">
				<tbody>
					<tr class="warning">
						<th>옵션</th>
						<th><input type="checkbox" id="chk_all_item" /></th>
						<th>수량</th>
						<th>판매가</th>
					</tr>
					<?php
					if (element('detail', $view)) {
						foreach (element('detail', $view) as $key => $value) {
							$price = element('cit_price', element('item', $view)) + element('cde_price', $value);
					?>
						<tr>
							<td><?php echo html_escape(element('cde_title', $value)); ?></td>
							<td><input type="checkbox" name="chk_detail[]" value="<?php echo element('cde_id', $value); ?>" <?php echo (element('cct_id', element('cart', $value))) ? 'checked="checked" ' : '';?> /></td>
							<td>
								<div class="btn-group" role="group" aria-label="...">
									<button type="button" class="btn btn-default btn-xs btn-change-qty" data-change-type="minus">-</button>
									<input type="text" name="detail_qty[<?php echo element('cde_id', $value); ?>]" class="btn btn-default btn-xs detail_qty" value="<?php echo element('cct_count', element('cart', $value)) ? element('cct_count', element('cart', $value)) : 1; ?>" />
									<button type="button" class="btn btn-default btn-xs btn-change-qty" data-change-type="plus">+</button>
								</div>
							</td>
							<td>
								<input type="hidden" name="item_price[<?php echo element('cde_id', $value); ?>]" value="<?php echo $price; ?>" />
								<?php echo number_format($price); ?>
							</td>
						</tr>
					<?php
						}
					}
					?>
				</tbody>
			</table>
			<div class="pull-right mb20">
				총 구매금액 <span class="product-title"><span id="total_order_price">0</span>원</span>
			</div>
		</div>
		<div class="form-group textcenter">
			<button type="submit" class="btn btn-success btn-sm">선택사항적용</button> <button type="button" class="btn btn-default btn-sm" id="mod_option_close">취소</button>
		</div>
	</div>
<?php echo form_close(); ?>

<script type="text/javascript">
//<![CDATA[

// 구매금액 계산
item_price_calculate();

function fcart_submit(f)
{
	var $el_chk = $('input[name^=chk_detail]:checked');

	if ($el_chk.size() < 1) {
		alert('상품의 옵션을 하나이상 선택해 주십시오.');
		return false;
	}

	// 수량체크
	var is_qty = true;
	var ct_qty = 0;
	$el_chk.each(function() {
		ct_qty = parseInt($(this).closest('tr').find('input[name^=ct_qty]').val().replace(/[^0-9]/g, ""));
		if (isNaN(ct_qty)) {
			ct_qty = 0;
		}

		if (ct_qty < 1) {
			is_qty = false;
			return false;
		}
	});

	if ( ! is_qty) {
		alert('수량을 1이상 입력해 주십시오.');
		return false;
	}

	return true;
}
//]]>
</script>
<!-- } 장바구니 옵션 끝 -->
