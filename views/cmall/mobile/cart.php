<?php $this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css'); ?>
<?php $this->managelayout->add_js(base_url('assets/js/cmallitem.js')); ?>

<h3>장바구니</h3>

<?php
$attributes = array('class' => 'form-inline', 'name' => 'flist', 'id' => 'flist');
echo form_open(site_url('cmall/cart'), $attributes);
?>

	<table class="table mt20">
		<thead>
			<tr class="success">
				<th>이미지</th>
				<th>상품명</th>
				<th>총수량</th>
				<th>판매가</th>
				<th>소계</th>
				<th><input type="checkbox" name="chkall" id="chkall" checked="checked" /></th>
			</tr>
		</thead>
		<tbody>
		<?php
		$total_price_sum = 0;
		if (element('data', $view)) {
			foreach (element('data', $view) as $result) {
		?>
			<tr>
				<td><a href="<?php echo element('item_url', $result); ?>" title="<?php echo html_escape(element('cit_name', $result)); ?>" ><img src="<?php echo thumb_url('cmallitem', element('cit_file_1', $result), 60, 60); ?>" class="thumbnail" style="margin:0;width:60px;height:60px;" alt="<?php echo html_escape(element('cit_name', $result)); ?>" title="<?php echo html_escape(element('cit_name', $result)); ?>" /></a></td>
				<td><a href="<?php echo element('item_url', $result); ?>" title="<?php echo html_escape(element('cit_name', $result)); ?>" ><?php echo html_escape(element('cit_name', $result)); ?></a>
					<ul class="cmall-options">
						<?php
						$total_num = 0;
						$total_price = 0;
						foreach (element('detail', $result) as $detail) { ?>
							<li><?php echo html_escape(element('cde_title', $detail)) . ' ' . element('cct_count', $detail);?>개 (+<?php echo number_format(element('cde_price', $detail)); ?>원)</li>
						<?php
						$total_num += element('cct_count', $detail);
						$total_price += ((int) element('cit_price', $result) + (int) element('cde_price', $detail)) * element('cct_count', $detail);
						}
						$total_price_sum += $total_price;
						?>
					</ul>
					<div class="cmall-option-change">
						<button class="change_option" type="button" data-cit-id="<?php echo element('cit_id', $result); ?>">선택사항수정</button>
					</div>
				</td>
				<td><?php echo number_format($total_num); ?></td>
				<td><?php echo number_format(element('cit_price', $result)); ?></td>
				<td><?php echo number_format($total_price); ?><input type="hidden" name="total_price[<?php echo element('cit_id', $result); ?>]" value="<?php echo $total_price; ?>" /></td>
				<td><input type="checkbox" name="chk[]" class="list-chkbox" value="<?php echo element('cit_id', $result); ?>" checked="checked" /></td>
			</tr>
		<?php
			}
		}
		if ( ! element('data', $view)) {
		?>
			<tr>
				<td colspan="6" class="nopost">장바구니가 비어있습니다</td>
			</tr>
		<?php
		}
		?>
		</tbody>
	</table>
	<table class="table table-hover mb20">
		<tbody>
			<tr class="active">
				<td>결제해야할 금액</td>
				<td class="text-right"><span class="checked_price"><?php echo number_format($total_price_sum); ?></span> 원</td>
			</tr>
		</tbody>
	</table>

	<button type="button" class="btn btn-outline btn-default btn-sm btn-list-delete btn-list-selected" data-list-delete-url = "<?php echo element('list_delete_url', $view); ?>" >선택삭제</button>
	<button type="submit" class="btn btn-outline btn-danger btn-sm btn-list-selected pull-right" >주문하기</button>

<?php echo form_close(); ?>

<script type="text/javascript">
//<![CDATA[
$(document).on('change', '.list-chkbox', function() {
	var sum = 0;
	$('.list-chkbox:checked').each(function () {
		sum += parseInt($("input[name='total_price[" + $(this).val() + "]']").val());
	});
	$('.checked_price').text(number_format(sum.toString()));
});

$(function() {
	var close_btn_idx;

	// 선택사항수정
	$(document).on('click', '.change_option', function() {
		var cit_id = $(this).attr('data-cit-id');
		var $this = $(this);
		close_btn_idx = $('.change_option').index($(this));

		$.post(
			cb_url + '/cmall/cartoption',
			{ cit_id: cit_id, csrf_test_name: cb_csrf_hash },
			function(data) {
				$('#cart_option_modify').remove();
				$this.after("<div id=\"cart_option_modify\"></div>");
				$('#cart_option_modify').html(data);
			}
		);
	});

	// 모두선택
	$(document).on('click', 'input[name=ct_all]', function() {
		if ($(this).is(':checked')) {
			$('input[name^=ct_chk]').attr('checked', true);
		} else {
			$('input[name^=ct_chk]').attr('checked', false);
		}
	});

	// 옵션수정 닫기
	$(document).on('click', '#mod_option_close', function() {
		$('#cart_option_modify').remove();
		$('.change_option').eq(close_btn_idx).focus();
	});

});
//]]>
</script>
