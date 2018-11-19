if (typeof(CMALLITEM_JS) === 'undefined') {

	var CMALLITEM_JS = true;

	$(document).on('click', '#chk_all_item', function(){
		var chkbox = document.getElementsByName('chk_detail[]');
		for (i = 0; i < chkbox.length; i++) {
			chkbox[i].checked = this.checked;
		}

		item_price_calculate();
	});

	$(document).on('click', 'input[name^=chk_detail]', function() {
		item_price_calculate();
	});

	// 수량변경
	$(document).on('click', 'button.btn-change-qty', function() {

		if( $(this).triggerHandler( 'item_change_qty' ) !== false ){
			var change_type = $(this).attr('data-change-type');
			var $qty = $(this).closest('tr').find('input[name^=detail_qty]');
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
		}
	});

	// 수량입력
	$(document).on('keyup', 'input[name^=detail_qty]', function() {
		var qty = parseInt($(this).val().replace(/[^0-9]/g, ""));
		if (isNaN(qty)) {
			alert('수량은 숫자만 입력해 주십시오.');
			$(this).val(1);
			return false;
		}

		if (qty < 1) {
			alert('수량은 1이상 입력해 주십시오.');
			$(this).val(1);
			return false;
		}

		item_price_calculate();
	});

	function item_price_calculate(){
		var tot_price = 0,
			price = 0,
			qty = 0,
			$sel = jQuery('input[name^=chk_detail]:checked'),
			$total_order_price = jQuery('#total_order_price');

		if( $total_order_price.triggerHandler( 'item_total_order_price' ) !== false ){

			if ($sel.size() > 0) {
				$sel.each(function() {
					price = parseInt($(this).closest('tr').find('input[name^=item_price]').val());
					qty = parseInt($(this).closest('tr').find('input[name^=detail_qty]').val());

					tot_price += (price * qty);
				});
			}

			$total_order_price.text(number_format(String(tot_price)));
		}
	}


	// 바로구매, 장바구니, 찜하기
	function fitem_submit(f) {
		if (f.stype.value === 'wish') {
			return true;
		}

		var $el_chk = jQuery('input[name^=chk_detail]:checked');

		if ($el_chk.size() < 1) {
			alert('상품의 옵션을 하나이상 선택해 주십시오.');
			return false;
		}

		if( jQuery(f).triggerHandler( 'item_form_submit' ) !== false ){
			// 수량체크
			var is_qty = true;
			var detail_qty = 0;
			$el_chk.each(function() {
				detail_qty = parseInt($(this).closest('tr').find('input[name^=detail_qty]').val().replace(/[^0-9]/g, ""));
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
		}

		return true;
	}

	function view_cmall_review(id, cit_id, page, opt, message) {
		if (opt) {
			$('html, body').animate({
				scrollTop: $('#' + id).offset().top - 100
			}, 0);
		}

		var cmall_review_url = cb_url + '/cmall/reviewlist/' + cit_id + '?page=' + page;
		var hash = window.location.hash;

		$('#' + id).load(cmall_review_url, function() {
			if (message) {
				$('.alert-cmall-review-list-message-content').html(message);
				$('.alert-cmall-review-list-message').addClass('alert-success').removeClass('alert-warning').show();
			}
			if (hash) {
				var st = $(hash).offset().top;
				$('html, body').animate({ scrollTop: st }, 200); //200ms duration
			}
			if (typeof(SyntaxHighlighter) !== 'undefined') {
				SyntaxHighlighter.highlight();
			}
		});
	}

	function delete_cmall_review(cre_id, cit_id, page) {
		if (confirm("정말 삭제 하시겠습니까?\n\n삭제하신 후에는 복구가 불가능합니다.")) {
			$.ajax({
				url : cb_url + '/cmallact/delete_review',
				type : 'POST',
				cache : false,
				data : {cre_id:cre_id, csrf_test_name: cb_csrf_hash},
				dataType : 'json',
				success : function(data) {
					if (data.error) {
						$('.alert-cmall-review-list-message-content').html(data.error);
						$('.alert-cmall-review-list-message').addClass('alert-warning').removeClass('alert-success').show();
						return false;
					} else if (data.success) {
						view_cmall_review('viewitemreview', cit_id, '', '', data.success);
						cmall_review_count_update(data.review_count);
					}
				},
				error : function(data) {
					alert('오류가 발생하였습니다.');
					return false;
				}
			});
		}
	}

	function cmall_review_page(cit_id, page) {
		view_cmall_review('viewitemreview', cit_id, page, '');
		review_cur_page = page;
	}

	function review_open(el) {
		var $con = $(el).closest('.product-feedback').find('.review-content');

		if ($con.is(':visible')) {
			$con.slideUp();
		} else {
			$('.review-content:visible').css('display', 'none');
			$con.slideDown();
		}

		return false;
	}

	function qna_open(el) {
		var $con = $(el).closest('.product-feedback').find('.qna-content');

		if ($con.is(':visible')) {
			$con.slideUp();
		} else {
			$('.qna-content:visible').css('display', 'none');
			$con.slideDown();
		}

		return false;
	}

	function view_cmall_qna(id, cit_id, page, opt, message) {
		if (opt) {
			$('html, body').animate({
				scrollTop: $('#' + id).offset().top - 100
			}, 0);
		}

		var cmall_qna_url = cb_url + '/cmall/qnalist/' + cit_id + '?page=' + page;
		var hash = window.location.hash;

		$('#' + id).load(cmall_qna_url, function() {
			if (message) {
				$('.alert-cmall-qna-list-message-content').html(message);
				$('.alert-cmall-qna-list-message').addClass('alert-success').removeClass('alert-warning').show();
			}
			if (hash) {
				var st = $(hash).offset().top;
				$('html, body').animate({ scrollTop: st }, 200); //200ms duration
			}
			if (typeof(SyntaxHighlighter) !== 'undefined') {
				SyntaxHighlighter.highlight();
			}
		});
	}

	function delete_cmall_qna(cqa_id, cit_id, page) {
		if (confirm("정말 삭제 하시겠습니까?\n\n삭제하신 후에는 복구가 불가능합니다.")) {
			$.ajax({
				url : cb_url + '/cmallact/delete_qna',
				type : 'POST',
				cache : false,
				data : {cqa_id:cqa_id, csrf_test_name: cb_csrf_hash},
				dataType : 'json',
				success : function(data) {
					if (data.error) {
						$('.alert-cmall-qna-list-message-content').html(data.error);
						$('.alert-cmall-qna-list-message').addClass('alert-warning').removeClass('alert-success').show();
						return false;
					} else if (data.success) {
						view_cmall_qna('viewitemqna', cit_id, '', '', data.success);
						cmall_qna_count_update(data.qna_count);
					}
				},
				error : function(data) {
					alert('오류가 발생하였습니다.');
					return false;
				}
			});
		}
	}

	function cmall_qna_page(cit_id, page) {
		view_cmall_qna('viewitemqna', cit_id, page, '');
		qna_cur_page = page;
	}

	function cmall_review_count_update(cnt) {
		$('.item_review_count').text(cnt);
	}

	function cmall_qna_count_update(cnt) {
		$('.item_qna_count').text(cnt);
	}
}
