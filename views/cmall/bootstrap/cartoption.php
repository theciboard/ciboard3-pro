<!-- 장바구니 옵션 시작 { -->
<?php
$attributes = array('class' => 'form-inline', 'name' => 'foption', 'id' => 'foption', 'onsubmit' => 'return fcart_submit(this)');
echo form_open(site_url('cmallact/optionupdate'), $attributes);
?>
	<input type="hidden" name="cit_id" value="<?php echo element('cit_id', element('item', $view)); ?>" />
	<div class="popup popup-cart">
		<h3>상품옵션</h3>
		<div class="pop-con">
<!--			 <input type="checkbox" id="chk_all_item" /></th> -->
			<div class="product-option">
				<ul>
				
					<?php
					if (element('detail', $view)) {
						foreach (element('detail', $view) as $key => $value) {
							$price = element('cit_price', element('item', $view)) + element('cde_price', $value);
					?>
						<li>
							 <div class="opt-name">
								<span class="span-chk"><input type="checkbox" name="chk_detail[]" value="<?php echo element('cde_id', $value); ?>" <?php echo (element('cct_id', element('cart', $value))) ? 'checked="checked" ' : '';?></span>
								<?php echo html_escape(element('cde_title', $value)); ?>
							</div>
							<div>
								<span class="span-qty">
									<div class="btn-group" role="group" aria-label="...">
										<button type="button" class="btn btn-default btn-xs btn-change-qty" data-change-type="minus">-</button>
										<input type="text" name="detail_qty[<?php echo element('cde_id', $value); ?>]" class="btn btn-default btn-xs detail_qty" value="<?php echo element('cct_count', element('cart', $value)) ? element('cct_count', element('cart', $value)) : 1; ?>" />
										<button type="button" class="btn btn-default btn-xs btn-change-qty" data-change-type="plus">+</button>
									</div>
								</span>
								<span class="detail_price">
									<input type="hidden" name="item_price[<?php echo element('cde_id', $value); ?>]" value="<?php echo $price; ?>" />
									<strong><?php echo number_format($price); ?></strong> 원
								</span>
							</div>
						</li>
					<?php
						}
					}
					?>
				</ul>
			</div>
			<div class="cart_total_price">
				총 구매금액 <span class="product-title"><span id="total_order_price">0</span>원</span>
			</div>
		</div>
		<div class="pop-btn ">
			<button type="submit" class="btn btn-info btn-sm">선택사항적용</button> <button type="button" class="btn-popclose" id="mod_option_close"><i class="fa fa-times" aria-hidden="true"></i><span class="sd-only">취소</span></button>
		</div>
	</div>
<?php echo form_close(); ?>

<script type="text/javascript">
//<![CDATA[
jQuery(function($){

	$('#total_order_price').on("item_total_order_price", function(e){

		var tot_price = 0,
			price = 0,
			qty = 0,
			$sel = jQuery('input[name^=chk_detail]:checked'),
			$total_order_price = $(this);

		if ($sel.size() > 0) {
			$sel.each(function() {

				price = parseInt($(this).closest('li').find('input[name^=item_price]').val());
				qty = parseInt($(this).closest('li').find('input[name^=detail_qty]').val());
				
				tot_price += (price * qty);
			});
		}

		$total_order_price.text(number_format(String(tot_price)));

		return false;
	});

	$("button.btn-change-qty").on("item_change_qty", function(e){
		var change_type = $(this).attr('data-change-type');
		var $qty = $(this).closest('li').find('input[name^=detail_qty]');
		var qty = parseInt($qty.val().replace(/[^0-9]/g, ""));
		if (isNaN(qty)) {
			qty = 1;
		}

		if (change_type === 'plus') {
			qty++;
			$qty.val(qty);
		} else if (change_type === 'minus') {
			qty--;
			if (qty < 1) {
				alert('수량은 1이상 입력해 주십시오.');
				$qty.val(1);
				return false;
			}

			$qty.val(qty);
		}

		item_price_calculate();

		return false;
	});

});

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
