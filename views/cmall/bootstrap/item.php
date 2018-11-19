<?php $this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css'); ?>
<?php $this->managelayout->add_js(base_url('assets/js/cmallitem.js')); ?>

<div class="market" id="item">
	<?php if ($this->member->is_admin()) { ?>
		<a href="<?php echo admin_url('cmall/cmallitem/write/' . element('cit_id', element('data', $view))); ?>" target="_blank" class="btn-sm btn btn-danger pull-right btn-edit">상품내용수정</a>
	<?php } ?>
	<?php if (element('header_content', element('data', $view))) { ?>
		<div class="product-detail"><?php echo element('header_content', element('data', $view)); ?></div>
	<?php } ?>
		<div class="product-box mb20">
			<div class="product-left col-xs-12 col-lg-6">
				<div class="prd-slide">
					<div class="item_slider">
						<?php
						for ($i =1; $i <=10; $i++) {
							if ( ! element('cit_file_' . $i, element('data', $view))) {
								continue;
							}
						?>
							<div><img src="<?php echo thumb_url('cmallitem', element('cit_file_' . $i, element('data', $view)), 450, 450); ?>" alt="<?php echo html_escape(element('cit_name', element('data', $view))); ?>" title="<?php echo html_escape(element('cit_name', element('data', $view))); ?>" onClick="window.open('<?php echo site_url('cmall/itemimage/' . html_escape(element('cit_key', element('data', $view)))); ?>', 'win_image', 'left=100,top=100,width=730,height=700,scrollbars=1');" /></div>
						<?php } ?>
					</div>
					<span class="prev" id="slider-prev"></span>
					<span class="next" id="slider-next"></span>
				</div>
				<?php if (element('demo_user_link', element('meta', element('data', $view))) OR element('demo_admin_link', element('meta', element('data', $view)))) { ?>
				<div class="prduct-demo">
					<?php if (element('demo_user_link', element('meta', element('data', $view)))) { ?>
						<a href="<?php echo site_url('cmallact/link/user/' . element('cit_id', element('data', $view))); ?>" target="_blank"><span class="btn-default btn-sm btn">샘플사이트</span></a>
					<?php } ?>
					<?php if (element('demo_admin_link', element('meta', element('data', $view)))) { ?>
						<a href="<?php echo site_url('cmallact/link/admin/' . element('cit_id', element('data', $view))); ?>" target="_blank"><span class="btn-default btn-sm btn">관리자사이트</span></a>
					<?php } ?>
				</div>
				<?php } ?>
			</div>
			<div class="product-right col-xs-12 col-lg-6">
				<div class="product-title"><?php echo html_escape(element('cit_name', element('data', $view))); ?></div>
				<div class="product-no">
					<table class="table">
						<tbody>
							<tr>
								<td>상품코드</td>
								<td><?php echo html_escape(element('cit_key', element('data', $view))); ?></td>
							</tr>
							<?php
							for ($k=1; $k<=10; $k++) {
								if (element('info_title_' . $k, element('meta', element('data', $view)))) {
							?>
								<tr>
									<td><?php echo html_escape(element('info_title_' . $k, element('meta', element('data', $view)))); ?></td>
									<td><?php echo html_escape(element('info_content_' . $k, element('meta', element('data', $view)))); ?></td>
								</tr>
							<?php
									}
								}
							?>
							<tr>
								<td>다운로드 가능기간</td>
								<td><?php echo (element('cit_download_days', element('data', $view))) ? '구매후 ' . element('cit_download_days', element('data', $view)) . '일 동안 언제든지 다운로드 가능' : '구매후 기간제한없이 언제나 가능'; ?></td>
							</tr>
						</tbody>
					</table>
				</div>

				<?php
				if (element('detail', element('data', $view))) {
					$attributes = array('class' => 'form-horizontal', 'name' => 'fitem', 'id' => 'fitem', 'onSubmit' => 'return fitem_submit(this)');
					echo form_open(current_full_url(), $attributes);
				?>
					<input type="hidden" name="stype" id="stype" value="" />
					<input type="hidden" name="cit_id" value="<?php echo element('cit_id', element('data', $view)); ?>" />
					<div class="product-option">

						<ul>
						<?php
						foreach (element('detail', element('data', $view)) as $detail) {
							$price = element('cit_price', element('data', $view)) + element('cde_price', $detail);
						?>
							<li>
								<div class="opt-name">
									<span class="span-chk"><input type="checkbox" name="chk_detail[]" value="<?php echo element('cde_id', $detail); ?>" /></span>
									<?php echo html_escape(element('cde_title', $detail)); ?>
								</div>
								<div>
									<span class="span-qty">
										<div class="btn-group" role="group" aria-label="...">
											<button type="button" class="btn btn-default btn-xs btn-change-qty" data-change-type="minus">-</button>
											<input type="text" name="detail_qty[<?php echo element('cde_id', $detail); ?>]" class="btn btn-default btn-xs detail_qty" value="1" />
											<button type="button" class="btn btn-default btn-xs btn-change-qty" data-change-type="plus">+</button>
										</div>
									</span>
									<span class="detail_price">
										<input type="hidden" name="item_price[<?php echo element('cde_id', $detail); ?>]" value="<?php echo $price; ?>" />
										<span><?php echo number_format($price); ?></span>원
									</span>
								</div>
							</li>
						<?php } ?>
						</ul>
	
						<div class="cart_total_price">총금액 : <span id="total_order_price">0</span>원</div>
					</div>
					<div class="item-btn">
						<button type="submit" onClick="$('#stype').val('order');" class="btn btn-order">바로구매</button>
						<button type="submit" onClick="$('#stype').val('cart');" class="btn btn-cart btn-border">장바구니</button>
						<button type="submit" onClick="$('#stype').val('wish');" class="btn btn-wish btn-border">찜하기</button>
					</div>
				<?php
					echo form_close();
				}
				?>


			</div>
		</div>

	<div class="product-info mb20">
		<ul class="product-info-top" id="itemtabmenu1">
			<li class="current"><a href="#itemtabmenu1">상품정보</a></li>
			<li><a href="#itemtabmenu2">사용후기 <span class="item_review_count"><?php echo number_format(element('cit_review_count', element('data', $view)));?></span></a></li>
			<li><a href="#itemtabmenu3">상품문의 <span class="item_qna_count"><?php echo number_format(element('cit_qna_count', element('data', $view)));?></span></a></li>
		</ul>
		<div class="product-detail"><?php echo element('content', element('data', $view)); ?></div>
	</div>
	<div class="product-info mb40">
		<ul class="product-info-top" id="itemtabmenu2">
			<li><a href="#itemtabmenu1">상품정보</a></li>
			<li class="current"><a href="#itemtabmenu2">사용후기 <span class="item_review_count"><?php echo number_format(element('cit_review_count', element('data', $view)));?></span></a></li>
			<li><a href="#itemtabmenu3">상품문의 <span class="item_qna_count"><?php echo number_format(element('cit_qna_count', element('data', $view)));?></span></a></li>
		</ul>
		<div id="viewitemreview"></div>
		<div class="btn-wr">
			<a href="javascript:;" class="btn btn-primary" onClick="window.open('<?php echo site_url('cmall/review_write/' . element('cit_id', element('data', $view))); ?>', 'review_popup', 'width=750,height=770,scrollbars=1'); return false;">사용후기 쓰기</a>
		</div>
	</div>
	<div class="product-info mb40">
		<ul class="product-info-top" id="itemtabmenu3">
			<li><a href="#itemtabmenu1">상품정보</a></li>
			<li><a href="#itemtabmenu2">사용후기 <span class="item_review_count"><?php echo number_format(element('cit_review_count', element('data', $view)));?></span></a></li>
			<li class="current"><a href="#itemtabmenu3">상품문의 <span class="item_qna_count"><?php echo number_format(element('cit_qna_count', element('data', $view)));?></span></a></li>
		</ul>
		<div id="viewitemqna"></div>
		<div class="btn-wr">
			<a href="javascript:;" class="btn btn-primary " onClick="window.open('<?php echo site_url('cmall/qna_write/' . element('cit_id', element('data', $view))); ?>', 'qna_popup', 'width=750,height=770,scrollbars=1'); return false;">상품문의 쓰기</a>
		</div>
	</div>
	<?php if (element('footer_content', element('data', $view))) { ?>
		<div class="product-detail"><?php echo element('footer_content', element('data', $view)); ?></div>
	<?php } ?>
</div>


<script type="text/javascript" src="<?php echo base_url('assets/js/bxslider/jquery.bxslider.min.js'); ?>"></script>
<script type="text/javascript">
//<![CDATA[
jQuery(function($){

	$('.item_slider').bxSlider({
		pager : false,
		nextSelector: '#slider-next',
		prevSelector: '#slider-prev',
		nextText: '<img src="<?php echo element('view_skin_url', $layout); ?>/images/btn_next.png" alt="다음" title="다음" />',
		prevText: '<img src="<?php echo element('view_skin_url', $layout); ?>/images/btn_prev.png" alt="이전" title="이전" />'
	});

	$(document).ready(function($) {
		view_cmall_review('viewitemreview', '<?php echo element('cit_id', element('data', $view)); ?>', '', '');
		view_cmall_qna('viewitemqna', '<?php echo element('cit_id', element('data', $view)); ?>', '', '');
	});


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

	$("#fitem").on("item_form_submit", function(e){

		// 수량체크
		var is_qty = true;
		var detail_qty = 0;
		var $el_chk = jQuery('input[name^=chk_detail]:checked');

		$el_chk.each(function() {
			detail_qty = parseInt($(this).closest('li').find('input[name^=detail_qty]').val().replace(/[^0-9]/g, ""));
			if (isNaN(detail_qty)) {
				detail_qty = 0;
			}

			if (detail_qty < 1) {
				is_qty = false;
				return false;
			}
		});

		if ( ! is_qty) {
			alert('수량을 1이상 입력해 주십시오.');
			return false;
		}

		return false;
	});
});
//]]>
</script>
