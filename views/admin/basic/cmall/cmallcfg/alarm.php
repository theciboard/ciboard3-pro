<div class="box">
	<div class="box-header">
		<ul class="nav nav-tabs">
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir); ?>" onclick="return check_form_changed();">기본설정</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/layout'); ?>" onclick="return check_form_changed();">레이아웃/메타태그</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/access'); ?>" onclick="return check_form_changed();">권한관리</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/editor'); ?>" onclick="return check_form_changed();">에디터기능</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/smsconfig'); ?>" onclick="return check_form_changed();">SMS 설정</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/paymentconfig'); ?>" onclick="return check_form_changed();">결제기능</a></li>
			<li role="presentation" class="active"><a href="<?php echo admin_url($this->pagedir . '/alarm'); ?>" onclick="return check_form_changed();">알림설정</a></li>
		</ul>
	</div>
	<div class="box-table">
		<?php
		echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
		echo show_alert_message(element('alert_message', $view), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
		$attributes = array('class' => 'form-horizontal', 'name' => 'fadminwrite', 'id' => 'fadminwrite');
		echo form_open(current_full_url(), $attributes);
		?>
			<input type="hidden" name="is_submit" value="1" />
			<div class="form-group">
				<label class="col-sm-2 control-label">카드/이체 등으로 컨텐츠 구매시</label>
				<div class="col-sm-10">
					<div class="checkbox">
						<label for="cmall_email_admin_cash_to_contents">
							<input type="checkbox" name="cmall_email_admin_cash_to_contents" id="cmall_email_admin_cash_to_contents" value="1" <?php echo set_checkbox('cmall_email_admin_cash_to_contents', '1', (element('cmall_email_admin_cash_to_contents', element('data', $view)) ? true : false)); ?> /> <strong>최고관리자</strong>에게 <strong>메일</strong>을 발송합니다 (이메일 수신동의 여부와 상관없이 <strong>무조건 발송</strong>합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="cmall_email_user_cash_to_contents">
							<input type="checkbox" name="cmall_email_user_cash_to_contents" id="cmall_email_user_cash_to_contents" value="1" <?php echo set_checkbox('cmall_email_user_cash_to_contents', '1', (element('cmall_email_user_cash_to_contents', element('data', $view)) ? true : false)); ?> /> <strong>회원</strong>에게 <strong>메일</strong>을 발송합니다 (이메일수신<strong>동의한 회원</strong>에게만 발송합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="cmall_email_alluser_cash_to_contents">
							<input type="checkbox" name="cmall_email_alluser_cash_to_contents" id="cmall_email_alluser_cash_to_contents" value="1" <?php echo set_checkbox('cmall_email_alluser_cash_to_contents', '1', (element('cmall_email_alluser_cash_to_contents', element('data', $view)) ? true : false)); ?> /> <strong>회원</strong>에게 <strong>메일</strong>을 발송합니다 (이메일 수신동의 여부와 상관없이 <strong>무조건 발송</strong>합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="cmall_note_admin_cash_to_contents">
							<input type="checkbox" name="cmall_note_admin_cash_to_contents" id="cmall_note_admin_cash_to_contents" value="1" <?php echo set_checkbox('cmall_note_admin_cash_to_contents', '1', (element('cmall_note_admin_cash_to_contents', element('data', $view)) ? true : false)); ?> /> <strong>최고관리자</strong>에게 <strong>쪽지</strong>를 발송합니다 (쪽지사용에 <strong>동의한 관리자</strong>에게만 발송합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="cmall_note_user_cash_to_contents">
							<input type="checkbox" name="cmall_note_user_cash_to_contents" id="cmall_note_user_cash_to_contents" value="1" <?php echo set_checkbox('cmall_note_user_cash_to_contents', '1', (element('cmall_note_user_cash_to_contents', element('data', $view)) ? true : false)); ?> /> <strong>회원</strong>에게 <strong>쪽지</strong>를 발송합니다 (쪽지사용에 <strong>동의한 회원</strong>에게만 발송합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="cmall_sms_admin_cash_to_contents">
							<input type="checkbox" name="cmall_sms_admin_cash_to_contents" id="cmall_sms_admin_cash_to_contents" value="1" <?php echo set_checkbox('cmall_sms_admin_cash_to_contents', '1', (element('cmall_sms_admin_cash_to_contents', element('data', $view)) ? true : false)); ?> /> <strong>최고관리자</strong>에게 <strong>문자</strong>를 발송합니다 (문자수신동의 여부와 상관없이 <strong>무조건 발송</strong>합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="cmall_sms_user_cash_to_contents">
							<input type="checkbox" name="cmall_sms_user_cash_to_contents" id="cmall_sms_user_cash_to_contents" value="1" <?php echo set_checkbox('cmall_sms_user_cash_to_contents', '1', (element('cmall_sms_user_cash_to_contents', element('data', $view)) ? true : false)); ?> /> <strong>회원</strong>에게 <strong>문자</strong>를 발송합니다 (문자수신에 <strong>동의한 회원</strong>에게만 발송합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="cmall_sms_alluser_cash_to_contents">
							<input type="checkbox" name="cmall_sms_alluser_cash_to_contents" id="cmall_sms_alluser_cash_to_contents" value="1" <?php echo set_checkbox('cmall_sms_alluser_cash_to_contents', '1', (element('cmall_sms_alluser_cash_to_contents', element('data', $view)) ? true : false)); ?> /> <strong>회원</strong>에게 <strong>문자</strong>를 발송합니다 (문자수신 동의에 상관없이 <strong>무조건 발송</strong>합니다)
						</label>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">무통장입금으로 컨텐츠구매요청시</label>
				<div class="col-sm-10">
					<div class="checkbox">
						<label for="cmall_email_admin_bank_to_contents">
							<input type="checkbox" name="cmall_email_admin_bank_to_contents" id="cmall_email_admin_bank_to_contents" value="1" <?php echo set_checkbox('cmall_email_admin_bank_to_contents', '1', (element('cmall_email_admin_bank_to_contents', element('data', $view)) ? true : false)); ?> /> <strong>최고관리자</strong>에게 <strong>메일</strong>을 발송합니다 (이메일 수신동의 여부와 상관없이 <strong>무조건 발송</strong>합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="cmall_email_user_bank_to_contents">
							<input type="checkbox" name="cmall_email_user_bank_to_contents" id="cmall_email_user_bank_to_contents" value="1" <?php echo set_checkbox('cmall_email_user_bank_to_contents', '1', (element('cmall_email_user_bank_to_contents', element('data', $view)) ? true : false)); ?> /> <strong>회원</strong>에게 <strong>메일</strong>을 발송합니다 (이메일수신<strong>동의한 회원</strong>에게만 발송합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="cmall_email_alluser_bank_to_contents">
							<input type="checkbox" name="cmall_email_alluser_bank_to_contents" id="cmall_email_alluser_bank_to_contents" value="1" <?php echo set_checkbox('cmall_email_alluser_bank_to_contents', '1', (element('cmall_email_alluser_bank_to_contents', element('data', $view)) ? true : false)); ?> /> <strong>회원</strong>에게 <strong>메일</strong>을 발송합니다 (이메일 수신동의 여부와 상관없이 <strong>무조건 발송</strong>합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="cmall_note_admin_bank_to_contents">
							<input type="checkbox" name="cmall_note_admin_bank_to_contents" id="cmall_note_admin_bank_to_contents" value="1" <?php echo set_checkbox('cmall_note_admin_bank_to_contents', '1', (element('cmall_note_admin_bank_to_contents', element('data', $view)) ? true : false)); ?> /> <strong>최고관리자</strong>에게 <strong>쪽지</strong>를 발송합니다 (쪽지사용에 <strong>동의한 관리자</strong>에게만 발송합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="cmall_note_user_bank_to_contents">
							<input type="checkbox" name="cmall_note_user_bank_to_contents" id="cmall_note_user_bank_to_contents" value="1" <?php echo set_checkbox('cmall_note_user_bank_to_contents', '1', (element('cmall_note_user_bank_to_contents', element('data', $view)) ? true : false)); ?> /> <strong>회원</strong>에게 <strong>쪽지</strong>를 발송합니다 (쪽지사용에 <strong>동의한 회원</strong>에게만 발송합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="cmall_sms_admin_bank_to_contents">
							<input type="checkbox" name="cmall_sms_admin_bank_to_contents" id="cmall_sms_admin_bank_to_contents" value="1" <?php echo set_checkbox('cmall_sms_admin_bank_to_contents', '1', (element('cmall_sms_admin_bank_to_contents', element('data', $view)) ? true : false)); ?> /> <strong>최고관리자</strong>에게 <strong>문자</strong>를 발송합니다 (문자수신동의 여부와 상관없이 <strong>무조건 발송</strong>합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="cmall_sms_user_bank_to_contents">
							<input type="checkbox" name="cmall_sms_user_bank_to_contents" id="cmall_sms_user_bank_to_contents" value="1" <?php echo set_checkbox('cmall_sms_user_bank_to_contents', '1', (element('cmall_sms_user_bank_to_contents', element('data', $view)) ? true : false)); ?> /> <strong>회원</strong>에게 <strong>문자</strong>를 발송합니다 (문자수신에 <strong>동의한 회원</strong>에게만 발송합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="cmall_sms_alluser_bank_to_contents">
							<input type="checkbox" name="cmall_sms_alluser_bank_to_contents" id="cmall_sms_alluser_bank_to_contents" value="1" <?php echo set_checkbox('cmall_sms_alluser_bank_to_contents', '1', (element('cmall_sms_alluser_bank_to_contents', element('data', $view)) ? true : false)); ?> /> <strong>회원</strong>에게 <strong>문자</strong>를 발송합니다 (문자수신 동의에 상관없이 <strong>무조건 발송</strong>합니다)
						</label>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">무통장입금 완료처리시</label>
				<div class="col-sm-10">
					<div class="checkbox">
						<label for="cmall_email_admin_approve_bank_to_contents">
							<input type="checkbox" name="cmall_email_admin_approve_bank_to_contents" id="cmall_email_admin_approve_bank_to_contents" value="1" <?php echo set_checkbox('cmall_email_admin_approve_bank_to_contents', '1', (element('cmall_email_admin_approve_bank_to_contents', element('data', $view)) ? true : false)); ?> /> <strong>최고관리자</strong>에게 <strong>메일</strong>을 발송합니다 (이메일 수신동의 여부와 상관없이 <strong>무조건 발송</strong>합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="cmall_email_user_approve_bank_to_contents">
							<input type="checkbox" name="cmall_email_user_approve_bank_to_contents" id="cmall_email_user_approve_bank_to_contents" value="1" <?php echo set_checkbox('cmall_email_user_approve_bank_to_contents', '1', (element('cmall_email_user_approve_bank_to_contents', element('data', $view)) ? true : false)); ?> /> <strong>회원</strong>에게 <strong>메일</strong>을 발송합니다 (이메일수신<strong>동의한 회원</strong>에게만 발송합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="cmall_email_alluser_approve_bank_to_contents">
							<input type="checkbox" name="cmall_email_alluser_approve_bank_to_contents" id="cmall_email_alluser_approve_bank_to_contents" value="1" <?php echo set_checkbox('cmall_email_alluser_approve_bank_to_contents', '1', (element('cmall_email_alluser_approve_bank_to_contents', element('data', $view)) ? true : false)); ?> /> <strong>회원</strong>에게 <strong>메일</strong>을 발송합니다 (이메일 수신동의 여부와 상관없이 <strong>무조건 발송</strong>합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="cmall_note_admin_approve_bank_to_contents">
							<input type="checkbox" name="cmall_note_admin_approve_bank_to_contents" id="cmall_note_admin_approve_bank_to_contents" value="1" <?php echo set_checkbox('cmall_note_admin_approve_bank_to_contents', '1', (element('cmall_note_admin_approve_bank_to_contents', element('data', $view)) ? true : false)); ?> /> <strong>최고관리자</strong>에게 <strong>쪽지</strong>를 발송합니다 (쪽지사용에 <strong>동의한 관리자</strong>에게만 발송합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="cmall_note_user_approve_bank_to_contents">
							<input type="checkbox" name="cmall_note_user_approve_bank_to_contents" id="cmall_note_user_approve_bank_to_contents" value="1" <?php echo set_checkbox('cmall_note_user_approve_bank_to_contents', '1', (element('cmall_note_user_approve_bank_to_contents', element('data', $view)) ? true : false)); ?> /> <strong>회원</strong>에게 <strong>쪽지</strong>를 발송합니다 (쪽지사용에 <strong>동의한 회원</strong>에게만 발송합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="cmall_sms_admin_approve_bank_to_contents">
							<input type="checkbox" name="cmall_sms_admin_approve_bank_to_contents" id="cmall_sms_admin_approve_bank_to_contents" value="1" <?php echo set_checkbox('cmall_sms_admin_approve_bank_to_contents', '1', (element('cmall_sms_admin_approve_bank_to_contents', element('data', $view)) ? true : false)); ?> /> <strong>최고관리자</strong>에게 <strong>문자</strong>를 발송합니다 (문자수신동의 여부와 상관없이 <strong>무조건 발송</strong>합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="cmall_sms_user_approve_bank_to_contents">
							<input type="checkbox" name="cmall_sms_user_approve_bank_to_contents" id="cmall_sms_user_approve_bank_to_contents" value="1" <?php echo set_checkbox('cmall_sms_user_approve_bank_to_contents', '1', (element('cmall_sms_user_approve_bank_to_contents', element('data', $view)) ? true : false)); ?> /> <strong>회원</strong>에게 <strong>문자</strong>를 발송합니다 (문자수신에 <strong>동의한 회원</strong>에게만 발송합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="cmall_sms_alluser_approve_bank_to_contents">
							<input type="checkbox" name="cmall_sms_alluser_approve_bank_to_contents" id="cmall_sms_alluser_approve_bank_to_contents" value="1" <?php echo set_checkbox('cmall_sms_alluser_approve_bank_to_contents', '1', (element('cmall_sms_alluser_approve_bank_to_contents', element('data', $view)) ? true : false)); ?> /> <strong>회원</strong>에게 <strong>문자</strong>를 발송합니다 (문자수신 동의에 상관없이 <strong>무조건 발송</strong>합니다)
						</label>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">상품후기 작성시</label>
				<div class="col-sm-10">
					<div class="checkbox">
						<label for="cmall_email_admin_write_product_review">
							<input type="checkbox" name="cmall_email_admin_write_product_review" id="cmall_email_admin_write_product_review" value="1" <?php echo set_checkbox('cmall_email_admin_write_product_review', '1', (element('cmall_email_admin_write_product_review', element('data', $view)) ? true : false)); ?> /> <strong>최고관리자</strong>에게 <strong>메일</strong>을 발송합니다 (이메일 수신동의 여부와 상관없이 <strong>무조건 발송</strong>합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="cmall_email_user_write_product_review">
							<input type="checkbox" name="cmall_email_user_write_product_review" id="cmall_email_user_write_product_review" value="1" <?php echo set_checkbox('cmall_email_user_write_product_review', '1', (element('cmall_email_user_write_product_review', element('data', $view)) ? true : false)); ?> /> <strong>회원</strong>에게 <strong>메일</strong>을 발송합니다 (이메일수신<strong>동의한 회원</strong>에게만 발송합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="cmall_email_alluser_write_product_review">
							<input type="checkbox" name="cmall_email_alluser_write_product_review" id="cmall_email_alluser_write_product_review" value="1" <?php echo set_checkbox('cmall_email_alluser_write_product_review', '1', (element('cmall_email_alluser_write_product_review', element('data', $view)) ? true : false)); ?> /> <strong>회원</strong>에게 <strong>메일</strong>을 발송합니다 (이메일 수신동의 여부와 상관없이 <strong>무조건 발송</strong>합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="cmall_note_admin_write_product_review">
							<input type="checkbox" name="cmall_note_admin_write_product_review" id="cmall_note_admin_write_product_review" value="1" <?php echo set_checkbox('cmall_note_admin_write_product_review', '1', (element('cmall_note_admin_write_product_review', element('data', $view)) ? true : false)); ?> /> <strong>최고관리자</strong>에게 <strong>쪽지</strong>를 발송합니다 (쪽지사용에 <strong>동의한 관리자</strong>에게만 발송합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="cmall_note_user_write_product_review">
							<input type="checkbox" name="cmall_note_user_write_product_review" id="cmall_note_user_write_product_review" value="1" <?php echo set_checkbox('cmall_note_user_write_product_review', '1', (element('cmall_note_user_write_product_review', element('data', $view)) ? true : false)); ?> /> <strong>회원</strong>에게 <strong>쪽지</strong>를 발송합니다 (쪽지사용에 <strong>동의한 회원</strong>에게만 발송합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="cmall_sms_admin_write_product_review">
							<input type="checkbox" name="cmall_sms_admin_write_product_review" id="cmall_sms_admin_write_product_review" value="1" <?php echo set_checkbox('cmall_sms_admin_write_product_review', '1', (element('cmall_sms_admin_write_product_review', element('data', $view)) ? true : false)); ?> /> <strong>최고관리자</strong>에게 <strong>문자</strong>를 발송합니다 (문자수신동의 여부와 상관없이 <strong>무조건 발송</strong>합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="cmall_sms_user_write_product_review">
							<input type="checkbox" name="cmall_sms_user_write_product_review" id="cmall_sms_user_write_product_review" value="1" <?php echo set_checkbox('cmall_sms_user_write_product_review', '1', (element('cmall_sms_user_write_product_review', element('data', $view)) ? true : false)); ?> /> <strong>회원</strong>에게 <strong>문자</strong>를 발송합니다 (문자수신에 <strong>동의한 회원</strong>에게만 발송합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="cmall_sms_alluser_write_product_review">
							<input type="checkbox" name="cmall_sms_alluser_write_product_review" id="cmall_sms_alluser_write_product_review" value="1" <?php echo set_checkbox('cmall_sms_alluser_write_product_review', '1', (element('cmall_sms_alluser_write_product_review', element('data', $view)) ? true : false)); ?> /> <strong>회원</strong>에게 <strong>문자</strong>를 발송합니다 (문자수신 동의에 상관없이 <strong>무조건 발송</strong>합니다)
						</label>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">상품문의 작성시</label>
				<div class="col-sm-10">
					<div class="checkbox">
						<label for="cmall_email_admin_write_product_qna">
							<input type="checkbox" name="cmall_email_admin_write_product_qna" id="cmall_email_admin_write_product_qna" value="1" <?php echo set_checkbox('cmall_email_admin_write_product_qna', '1', (element('cmall_email_admin_write_product_qna', element('data', $view)) ? true : false)); ?> /> <strong>최고관리자</strong>에게 <strong>메일</strong>을 발송합니다 (이메일 수신동의 여부와 상관없이 <strong>무조건 발송</strong>합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="cmall_email_user_write_product_qna">
							<input type="checkbox" name="cmall_email_user_write_product_qna" id="cmall_email_user_write_product_qna" value="1" <?php echo set_checkbox('cmall_email_user_write_product_qna', '1', (element('cmall_email_user_write_product_qna', element('data', $view)) ? true : false)); ?> /> <strong>회원</strong>에게 <strong>메일</strong>을 발송합니다 (상품문의작성시 <strong>동의한 회원</strong>에게만 발송합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="cmall_note_admin_write_product_qna">
							<input type="checkbox" name="cmall_note_admin_write_product_qna" id="cmall_note_admin_write_product_qna" value="1" <?php echo set_checkbox('cmall_note_admin_write_product_qna', '1', (element('cmall_note_admin_write_product_qna', element('data', $view)) ? true : false)); ?> /> <strong>최고관리자</strong>에게 <strong>쪽지</strong>를 발송합니다 (쪽지사용에 <strong>동의한 관리자</strong>에게만 발송합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="cmall_note_user_write_product_qna">
							<input type="checkbox" name="cmall_note_user_write_product_qna" id="cmall_note_user_write_product_qna" value="1" <?php echo set_checkbox('cmall_note_user_write_product_qna', '1', (element('cmall_note_user_write_product_qna', element('data', $view)) ? true : false)); ?> /> <strong>회원</strong>에게 <strong>쪽지</strong>를 발송합니다 (쪽지사용에 <strong>동의한 회원</strong>에게만 발송합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="cmall_sms_admin_write_product_qna">
							<input type="checkbox" name="cmall_sms_admin_write_product_qna" id="cmall_sms_admin_write_product_qna" value="1" <?php echo set_checkbox('cmall_sms_admin_write_product_qna', '1', (element('cmall_sms_admin_write_product_qna', element('data', $view)) ? true : false)); ?> /> <strong>최고관리자</strong>에게 <strong>문자</strong>를 발송합니다 (문자수신동의 여부와 상관없이 <strong>무조건 발송</strong>합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="cmall_sms_user_write_product_qna">
							<input type="checkbox" name="cmall_sms_user_write_product_qna" id="cmall_sms_user_write_product_qna" value="1" <?php echo set_checkbox('cmall_sms_user_write_product_qna', '1', (element('cmall_sms_user_write_product_qna', element('data', $view)) ? true : false)); ?> /> <strong>회원</strong>에게 <strong>문자</strong>를 발송합니다 (상품문의작성시 <strong>동의한 회원</strong>에게만 발송합니다)
						</label>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">상품문의 답변시</label>
				<div class="col-sm-10">
					<div class="checkbox">
						<label for="cmall_email_admin_write_product_qna_reply">
							<input type="checkbox" name="cmall_email_admin_write_product_qna_reply" id="cmall_email_admin_write_product_qna_reply" value="1" <?php echo set_checkbox('cmall_email_admin_write_product_qna_reply', '1', (element('cmall_email_admin_write_product_qna_reply', element('data', $view)) ? true : false)); ?> /> <strong>최고관리자</strong>에게 <strong>메일</strong>을 발송합니다 (이메일 수신동의 여부와 상관없이 <strong>무조건 발송</strong>합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="cmall_email_user_write_product_qna_reply">
							<input type="checkbox" name="cmall_email_user_write_product_qna_reply" id="cmall_email_user_write_product_qna_reply" value="1" <?php echo set_checkbox('cmall_email_user_write_product_qna_reply', '1', (element('cmall_email_user_write_product_qna_reply', element('data', $view)) ? true : false)); ?> /> <strong>회원</strong>에게 <strong>메일</strong>을 발송합니다 (상품문의작성시 <strong>동의한 회원</strong>에게만 발송합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="cmall_note_admin_write_product_qna_reply">
							<input type="checkbox" name="cmall_note_admin_write_product_qna_reply" id="cmall_note_admin_write_product_qna_reply" value="1" <?php echo set_checkbox('cmall_note_admin_write_product_qna_reply', '1', (element('cmall_note_admin_write_product_qna_reply', element('data', $view)) ? true : false)); ?> /> <strong>최고관리자</strong>에게 <strong>쪽지</strong>를 발송합니다 (쪽지사용에 <strong>동의한 관리자</strong>에게만 발송합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="cmall_note_user_write_product_qna_reply">
							<input type="checkbox" name="cmall_note_user_write_product_qna_reply" id="cmall_note_user_write_product_qna_reply" value="1" <?php echo set_checkbox('cmall_note_user_write_product_qna_reply', '1', (element('cmall_note_user_write_product_qna_reply', element('data', $view)) ? true : false)); ?> /> <strong>회원</strong>에게 <strong>쪽지</strong>를 발송합니다 (쪽지사용에 <strong>동의한 회원</strong>에게만 발송합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="cmall_sms_admin_write_product_qna_reply">
							<input type="checkbox" name="cmall_sms_admin_write_product_qna_reply" id="cmall_sms_admin_write_product_qna_reply" value="1" <?php echo set_checkbox('cmall_sms_admin_write_product_qna_reply', '1', (element('cmall_sms_admin_write_product_qna_reply', element('data', $view)) ? true : false)); ?> /> <strong>최고관리자</strong>에게 <strong>문자</strong>를 발송합니다 (문자수신동의 여부와 상관없이 <strong>무조건 발송</strong>합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="cmall_sms_user_write_product_qna_reply">
							<input type="checkbox" name="cmall_sms_user_write_product_qna_reply" id="cmall_sms_user_write_product_qna_reply" value="1" <?php echo set_checkbox('cmall_sms_user_write_product_qna_reply', '1', (element('cmall_sms_user_write_product_qna_reply', element('data', $view)) ? true : false)); ?> /> <strong>회원</strong>에게 <strong>문자</strong>를 발송합니다 (상품문의작성시 <strong>동의한 회원</strong>에게만 발송합니다)
						</label>
					</div>
				</div>
			</div>
			<div class="btn-group pull-right" role="group" aria-label="...">
				<button type="submit" class="btn btn-success btn-sm">저장하기</button>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>

<script type="text/javascript">
//<![CDATA[
var form_original_data = $('#fadminwrite').serialize();
function check_form_changed() {
	if ($('#fadminwrite').serialize() !== form_original_data) {
		if (confirm('저장하지 않은 정보가 있습니다. 저장하지 않은 상태로 이동하시겠습니까?')) {
			return true;
		} else {
			return false;
		}
	}
	return true;
}

$(function() {
	$(document).on('change', '#cmall_email_alluser_cash_to_contents', function() {
		if ($(this).is(':checked')) {
			$('#cmall_email_user_cash_to_contents').prop('checked', false).prop('disabled', true);
		} else {
			$('#cmall_email_user_cash_to_contents').prop('disabled', false);
		}
	});
<?php if (element('cmall_email_alluser_cash_to_contents', element('data', $view))) {?>
	$('#cmall_email_user_cash_to_contents').prop('checked', false).prop('disabled', true);
<?php } ?>
	$(document).on('change', '#cmall_email_user_cash_to_contents', function() {
		if ($(this).is(':checked')) {
			$('#cmall_email_alluser_cash_to_contents').prop('checked', false).prop('disabled', true);
		} else {
			$('#cmall_email_alluser_cash_to_contents').prop('disabled', false);
		}
	});
<?php if (element('cmall_email_user_cash_to_contents', element('data', $view))) {?>
	$('#cmall_email_alluser_cash_to_contents').prop('checked', false).prop('disabled', true);
<?php } ?>
	$(document).on('change', '#cmall_sms_alluser_cash_to_contents', function() {
		if ($(this).is(':checked')) {
			$('#cmall_sms_user_cash_to_contents').prop('checked', false).prop('disabled', true);
		} else {
			$('#cmall_sms_user_cash_to_contents').prop('disabled', false);
		}
	});
<?php if (element('cmall_sms_alluser_cash_to_contents', element('data', $view))) {?>
	$('#cmall_sms_user_cash_to_contents').prop('checked', false).prop('disabled', true);
<?php } ?>
	$(document).on('change', '#cmall_sms_user_cash_to_contents', function() {
		if ($(this).is(':checked')) {
			$('#cmall_sms_alluser_cash_to_contents').prop('checked', false).prop('disabled', true);
		} else {
			$('#cmall_sms_alluser_cash_to_contents').prop('disabled', false);
		}
	});
<?php if (element('cmall_sms_user_cash_to_contents', element('data', $view))) {?>
	$('#cmall_sms_alluser_cash_to_contents').prop('checked', false).prop('disabled', true);
<?php } ?>


	$(document).on('change', '#cmall_email_alluser_bank_to_contents', function() {
		if ($(this).is(':checked')) {
			$('#cmall_email_user_bank_to_contents').prop('checked', false).prop('disabled', true);
		} else {
			$('#cmall_email_user_bank_to_contents').prop('disabled', false);
		}
	});
<?php if (element('cmall_email_alluser_bank_to_contents', element('data', $view))) {?>
	$('#cmall_email_user_bank_to_contents').prop('checked', false).prop('disabled', true);
<?php } ?>
	$(document).on('change', '#cmall_email_user_bank_to_contents', function() {
		if ($(this).is(':checked')) {
			$('#cmall_email_alluser_bank_to_contents').prop('checked', false).prop('disabled', true);
		} else {
			$('#cmall_email_alluser_bank_to_contents').prop('disabled', false);
		}
	});
<?php if (element('cmall_email_user_bank_to_contents', element('data', $view))) {?>
	$('#cmall_email_alluser_bank_to_contents').prop('checked', false).prop('disabled', true);
<?php } ?>
	$(document).on('change', '#cmall_sms_alluser_bank_to_contents', function() {
		if ($(this).is(':checked')) {
			$('#cmall_sms_user_bank_to_contents').prop('checked', false).prop('disabled', true);
		} else {
			$('#cmall_sms_user_bank_to_contents').prop('disabled', false);
		}
	});
<?php if (element('cmall_sms_alluser_bank_to_contents', element('data', $view))) {?>
	$('#cmall_sms_user_bank_to_contents').prop('checked', false).prop('disabled', true);
<?php } ?>
	$(document).on('change', '#cmall_sms_user_bank_to_contents', function() {
		if ($(this).is(':checked')) {
			$('#cmall_sms_alluser_bank_to_contents').prop('checked', false).prop('disabled', true);
		} else {
			$('#cmall_sms_alluser_bank_to_contents').prop('disabled', false);
		}
	});
<?php if (element('cmall_sms_user_bank_to_contents', element('data', $view))) {?>
	$('#cmall_sms_alluser_bank_to_contents').prop('checked', false).prop('disabled', true);
<?php } ?>


	$(document).on('change', '#cmall_email_alluser_approve_bank_to_contents', function() {
		if ($(this).is(':checked')) {
			$('#cmall_email_user_approve_bank_to_contents').prop('checked', false).prop('disabled', true);
		} else {
			$('#cmall_email_user_approve_bank_to_contents').prop('disabled', false);
		}
	});
<?php if (element('cmall_email_alluser_approve_bank_to_contents', element('data', $view))) {?>
	$('#cmall_email_user_approve_bank_to_contents').prop('checked', false).prop('disabled', true);
<?php } ?>
	$(document).on('change', '#cmall_email_user_approve_bank_to_contents', function() {
		if ($(this).is(':checked')) {
			$('#cmall_email_alluser_approve_bank_to_contents').prop('checked', false).prop('disabled', true);
		} else {
			$('#cmall_email_alluser_approve_bank_to_contents').prop('disabled', false);
		}
	});
<?php if (element('cmall_email_user_approve_bank_to_contents', element('data', $view))) {?>
	$('#cmall_email_alluser_approve_bank_to_contents').prop('checked', false).prop('disabled', true);
<?php } ?>
	$(document).on('change', '#cmall_sms_alluser_approve_bank_to_contents', function() {
		if ($(this).is(':checked')) {
			$('#cmall_sms_user_approve_bank_to_contents').prop('checked', false).prop('disabled', true);
		} else {
			$('#cmall_sms_user_approve_bank_to_contents').prop('disabled', false);
		}
	});
<?php if (element('cmall_sms_alluser_approve_bank_to_contents', element('data', $view))) {?>
	$('#cmall_sms_user_approve_bank_to_contents').prop('checked', false).prop('disabled', true);
<?php } ?>
	$(document).on('change', '#cmall_sms_user_approve_bank_to_contents', function() {
		if ($(this).is(':checked')) {
			$('#cmall_sms_alluser_approve_bank_to_contents').prop('checked', false).prop('disabled', true);
		} else {
			$('#cmall_sms_alluser_approve_bank_to_contents').prop('disabled', false);
		}
	});
<?php if (element('cmall_sms_user_approve_bank_to_contents', element('data', $view))) {?>
	$('#cmall_sms_alluser_approve_bank_to_contents').prop('checked', false).prop('disabled', true);
<?php } ?>


	$(document).on('change', '#cmall_email_alluser_write_product_review', function() {
		if ($(this).is(':checked')) {
			$('#cmall_email_user_write_product_review').prop('checked', false).prop('disabled', true);
		} else {
			$('#cmall_email_user_write_product_review').prop('disabled', false);
		}
	});
<?php if (element('cmall_email_alluser_write_product_review', element('data', $view))) {?>
	$('#cmall_email_user_write_product_review').prop('checked', false).prop('disabled', true);
<?php } ?>
	$(document).on('change', '#cmall_email_user_write_product_review', function() {
		if ($(this).is(':checked')) {
			$('#cmall_email_alluser_write_product_review').prop('checked', false).prop('disabled', true);
		} else {
			$('#cmall_email_alluser_write_product_review').prop('disabled', false);
		}
	});
<?php if (element('cmall_email_user_write_product_review', element('data', $view))) {?>
	$('#cmall_email_alluser_write_product_review').prop('checked', false).prop('disabled', true);
<?php } ?>
	$(document).on('change', '#cmall_sms_alluser_write_product_review', function() {
		if ($(this).is(':checked')) {
			$('#cmall_sms_user_write_product_review').prop('checked', false).prop('disabled', true);
		} else {
			$('#cmall_sms_user_write_product_review').prop('disabled', false);
		}
	});
<?php if (element('cmall_sms_alluser_write_product_review', element('data', $view))) {?>
	$('#cmall_sms_user_write_product_review').prop('checked', false).prop('disabled', true);
<?php } ?>
	$(document).on('change', '#cmall_sms_user_write_product_review', function() {
		if ($(this).is(':checked')) {
			$('#cmall_sms_alluser_write_product_review').prop('checked', false).prop('disabled', true);
		} else {
			$('#cmall_sms_alluser_write_product_review').prop('disabled', false);
		}
	});
<?php if (element('cmall_sms_user_write_product_review', element('data', $view))) {?>
	$('#cmall_sms_alluser_write_product_review').prop('checked', false).prop('disabled', true);
<?php } ?>

});

//]]>
</script>
