if (typeof(COMMENT_JS) === 'undefined') {

	var COMMENT_JS = true;
	var is_submit_comment = false;

	function view_comment(id, post_id, page, opt, message) {
		if (opt) {
			$('html, body').animate({
				scrollTop: $('#' + id).offset().top - 100
			}, 0);
		}

		var comment_url = cb_url + '/comment_list/lists/' + post_id + '?page=' + page;
		var hash = window.location.hash;

		$('#' + id).load(comment_url, function() {
			if (message) {
				$('.alert-comment-list-message-content').html(message);
				$('.alert-comment-list-message').addClass('alert-success').removeClass('alert-warning').show(0).delay(2500).hide(0);
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

	function view_comment_password(cmt_id, post_id) {
		var comment_url = cb_url + '/comment_list/password/' + cmt_id + '/' + post_id;
		document.location.href=comment_url;
	}

	function add_comment(f, post_id) {

		if (is_submit_comment === true) {
			return false;
		}

		is_submit_comment = true;

		if ($('#char_count')) {
			if (char_min > 0 || char_max > 0) {
				var cnt = parseInt(check_byte('cmt_content', 'char_count'));
				if (char_min > 0 && char_min > cnt) {
					alert('내용은 ' + char_min + '글자 이상 쓰셔야 합니다.');
					f.cmt_content.focus();
					is_submit_comment = false;
					return false;
				} else if (char_max > 0 && char_max < cnt) {
					alert('내용은 ' + char_max + '글자 이하로 쓰셔야 합니다.');
					f.cmt_content.focus();
					is_submit_comment = false;
					return false;
				}
			}
		}

		$('#fcomment').validate();
		if ($('#fcomment').valid()) // check if form is valid
		{
			// do some stuff
		}
		else
		{
			is_submit_comment = false;
			return false;
			// just show validation errors, dont post
		}

		var content = '';
		$.ajax({
			url: cb_url + '/postact/filter_spam_keyword',
			type: 'post',
			data: {
				title: '',
				content: f.cmt_content.value,
				csrf_test_name: cb_csrf_hash
			},
			dataType: 'json',
			async: false,
			cache: false,
			success: function(data) {
				content = data.content;
			}
		});
		if (content) {
			alert('내용에 금지단어(\'' + content + '\')가 포함되어있습니다');
			f.cmt_content.focus();
			is_submit_comment = false;
			return false;
		}

		$.ajax({
			url : cb_url + '/comment_write/update',
			type : 'POST',
			cache : false,
			data : $('#fcomment').serialize(),
			dataType : 'json',
			success : function(data) {
				is_submit_comment = false;
				if (data.error) {
					$('.alert-comment-message-content').html(data.error);
					$('.alert-comment-message').addClass('alert-warning').removeClass('alert-success').show(0).delay(2500).hide(0);
					return false;
				} else if (data.success) {
					$('.alert-comment-message-content').html(data.success);
					$('.alert-comment-message').addClass('alert-success').removeClass('alert-warning').show(0).delay(2500).hide(0);
					view_comment('viewcomment', post_id, '', '')
					if ($('#char_count')) {
						if (char_min > 0 || char_max > 0) {
								check_byte('cmt_content', 'char_count');
						}
					}
					init_comment_box();
				}
			},
			error : function(data) {
				is_submit_comment = false;
				alert('오류가 발생하였습니다.');
				return false;
			}
		});
	}

	function delete_comment(cmt_id, post_id, page) {
		if (confirm("정말 삭제 하시겠습니까?\n\n삭제하신 후에는 복구가 불가능합니다.")) {
			$.ajax({
				url : cb_url + '/postact/delete_comment',
				type : 'POST',
				cache : false,
				data : {cmt_id:cmt_id, csrf_test_name: cb_csrf_hash},
				dataType : 'json',
				success : function(data) {
					if (data.error) {
						$('.alert-comment-list-message-content').html(data.error);
						$('.alert-comment-list-message').addClass('alert-warning').removeClass('alert-success').show();
						return false;
					} else if (data.password) {
						$('.alert-comment-list-message-content').html(data.password);
						$('.alert-comment-list-message').addClass('alert-warning').removeClass('alert-success').show();
						view_comment_password(post_id, cmt_id);
						return false;
					} else if (data.success) {
						view_comment('viewcomment', post_id, '', '', data.success);
						init_comment_box();
					}
				},
				error : function(data) {
					alert('오류가 발생하였습니다.');
					return false;
				}
			});
		}
	}

	function comment_page(post_id, page) {
		view_comment('viewcomment', post_id, page, '');
		comment_cur_page = page;
	}

	function init_comment_box() {
		$('#cmt_nickname').val('');
		$('#cmt_password').val('');
		$('#cmt_content').val('');
		$('#captcha').trigger('click');
		$('#captcha_key').val('');
		$('#cmt_btn_submit').prop('disabled', false);
		check_byte('cmt_content', 'char_count');
		comment_box('', 'c');
	}

	var save_before = '';
	var save_html = document.getElementById('comment_write_box').innerHTML;
	function comment_box(cmt_id, work) {
		var el_id;
		// 댓글 아이디가 넘어오면 답변, 수정
		if (cmt_id) {
			if (work === 'c') {
				el_id = 'reply_' + cmt_id;
			} else {
				el_id = 'edit_' + cmt_id;
			}
		} else {
			el_id = 'comment_write_box';
		}

		if (save_before !== el_id) {
			if (save_before) {
				 $('#' + save_before).css('display', 'none');
				$('#' + save_before).html('');
			}

			$('#' + el_id).css('display', '');
			$('#' + el_id).html(save_html);
			// 댓글 수정
			if (work === 'cu') {
				$('#cmt_content').val($('#save_comment_' + cmt_id).val());
				if (typeof char_count !== 'undefined') {
					check_byte('cmt_content', 'char_count');
				}
				if ($('#secret_comment_' + cmt_id).val() === '1') {
					$('#cmt_secret').prop('checked', true);
				} else {
					$('#cmt_secret').prop('checked', false);
				}
			}

			$('#cmt_id').val(cmt_id);
			$('#mode').val(work);

			if (save_before) {
				$('#captcha').trigger('click');
			}
			save_before = el_id;
		}
	}
	comment_box('', 'c');
}
