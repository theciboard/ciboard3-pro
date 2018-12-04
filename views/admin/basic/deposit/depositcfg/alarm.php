<div class="box">
	<div class="box-header">
		<ul class="nav nav-tabs">
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir); ?>" onclick="return check_form_changed();">기본설정</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/layout'); ?>" onclick="return check_form_changed();">레이아웃/메타태그</a></li>
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
				<label class="col-sm-2 control-label">카드/이체 등으로 예치금 구매시</label>
				<div class="col-sm-10">
					<div class="checkbox">
						<label for="deposit_email_admin_cash_to_deposit">
							<input type="checkbox" name="deposit_email_admin_cash_to_deposit" id="deposit_email_admin_cash_to_deposit" value="1" <?php echo set_checkbox('deposit_email_admin_cash_to_deposit', '1', (element('deposit_email_admin_cash_to_deposit', element('data', $view)) ? true : false)); ?> /> <strong>최고관리자</strong>에게 <strong>메일</strong>을 발송합니다 (이메일 수신동의 여부와 상관없이 <strong>무조건 발송</strong>합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="deposit_email_user_cash_to_deposit">
							<input type="checkbox" name="deposit_email_user_cash_to_deposit" id="deposit_email_user_cash_to_deposit" value="1" <?php echo set_checkbox('deposit_email_user_cash_to_deposit', '1', (element('deposit_email_user_cash_to_deposit', element('data', $view)) ? true : false)); ?> /> <strong>회원</strong>에게 <strong>메일</strong>을 발송합니다 (이메일수신<strong>동의한 회원</strong>에게만 발송합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="deposit_email_alluser_cash_to_deposit">
							<input type="checkbox" name="deposit_email_alluser_cash_to_deposit" id="deposit_email_alluser_cash_to_deposit" value="1" <?php echo set_checkbox('deposit_email_alluser_cash_to_deposit', '1', (element('deposit_email_alluser_cash_to_deposit', element('data', $view)) ? true : false)); ?> /> <strong>회원</strong>에게 <strong>메일</strong>을 발송합니다 (이메일 수신동의 여부와 상관없이 <strong>무조건 발송</strong>합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="deposit_note_admin_cash_to_deposit">
							<input type="checkbox" name="deposit_note_admin_cash_to_deposit" id="deposit_note_admin_cash_to_deposit" value="1" <?php echo set_checkbox('deposit_note_admin_cash_to_deposit', '1', (element('deposit_note_admin_cash_to_deposit', element('data', $view)) ? true : false)); ?> /> <strong>최고관리자</strong>에게 <strong>쪽지</strong>를 발송합니다 (쪽지사용에 <strong>동의한 관리자</strong>에게만 발송합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="deposit_note_user_cash_to_deposit">
							<input type="checkbox" name="deposit_note_user_cash_to_deposit" id="deposit_note_user_cash_to_deposit" value="1" <?php echo set_checkbox('deposit_note_user_cash_to_deposit', '1', (element('deposit_note_user_cash_to_deposit', element('data', $view)) ? true : false)); ?> /> <strong>회원</strong>에게 <strong>쪽지</strong>를 발송합니다 (쪽지사용에 <strong>동의한 회원</strong>에게만 발송합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="deposit_sms_admin_cash_to_deposit">
							<input type="checkbox" name="deposit_sms_admin_cash_to_deposit" id="deposit_sms_admin_cash_to_deposit" value="1" <?php echo set_checkbox('deposit_sms_admin_cash_to_deposit', '1', (element('deposit_sms_admin_cash_to_deposit', element('data', $view)) ? true : false)); ?> /> <strong>최고관리자</strong>에게 <strong>문자</strong>를 발송합니다 (문자수신동의 여부와 상관없이 <strong>무조건 발송</strong>합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="deposit_sms_user_cash_to_deposit">
							<input type="checkbox" name="deposit_sms_user_cash_to_deposit" id="deposit_sms_user_cash_to_deposit" value="1" <?php echo set_checkbox('deposit_sms_user_cash_to_deposit', '1', (element('deposit_sms_user_cash_to_deposit', element('data', $view)) ? true : false)); ?> /> <strong>회원</strong>에게 <strong>문자</strong>를 발송합니다 (문자수신에 <strong>동의한 회원</strong>에게만 발송합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="deposit_sms_alluser_cash_to_deposit">
							<input type="checkbox" name="deposit_sms_alluser_cash_to_deposit" id="deposit_sms_alluser_cash_to_deposit" value="1" <?php echo set_checkbox('deposit_sms_alluser_cash_to_deposit', '1', (element('deposit_sms_alluser_cash_to_deposit', element('data', $view)) ? true : false)); ?> /> <strong>회원</strong>에게 <strong>문자</strong>를 발송합니다 (문자수신 동의에 상관없이 <strong>무조건 발송</strong>합니다)
						</label>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">무통장입금으로 예치금구매요청시</label>
				<div class="col-sm-10">
					<div class="checkbox">
						<label for="deposit_email_admin_bank_to_deposit">
							<input type="checkbox" name="deposit_email_admin_bank_to_deposit" id="deposit_email_admin_bank_to_deposit" value="1" <?php echo set_checkbox('deposit_email_admin_bank_to_deposit', '1', (element('deposit_email_admin_bank_to_deposit', element('data', $view)) ? true : false)); ?> /> <strong>최고관리자</strong>에게 <strong>메일</strong>을 발송합니다 (이메일 수신동의 여부와 상관없이 <strong>무조건 발송</strong>합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="deposit_email_user_bank_to_deposit">
							<input type="checkbox" name="deposit_email_user_bank_to_deposit" id="deposit_email_user_bank_to_deposit" value="1" <?php echo set_checkbox('deposit_email_user_bank_to_deposit', '1', (element('deposit_email_user_bank_to_deposit', element('data', $view)) ? true : false)); ?> /> <strong>회원</strong>에게 <strong>메일</strong>을 발송합니다 (이메일수신<strong>동의한 회원</strong>에게만 발송합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="deposit_email_alluser_bank_to_deposit">
							<input type="checkbox" name="deposit_email_alluser_bank_to_deposit" id="deposit_email_alluser_bank_to_deposit" value="1" <?php echo set_checkbox('deposit_email_alluser_bank_to_deposit', '1', (element('deposit_email_alluser_bank_to_deposit', element('data', $view)) ? true : false)); ?> /> <strong>회원</strong>에게 <strong>메일</strong>을 발송합니다 (이메일 수신동의 여부와 상관없이 <strong>무조건 발송</strong>합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="deposit_note_admin_bank_to_deposit">
							<input type="checkbox" name="deposit_note_admin_bank_to_deposit" id="deposit_note_admin_bank_to_deposit" value="1" <?php echo set_checkbox('deposit_note_admin_bank_to_deposit', '1', (element('deposit_note_admin_bank_to_deposit', element('data', $view)) ? true : false)); ?> /> <strong>최고관리자</strong>에게 <strong>쪽지</strong>를 발송합니다 (쪽지사용에 <strong>동의한 관리자</strong>에게만 발송합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="deposit_note_user_bank_to_deposit">
							<input type="checkbox" name="deposit_note_user_bank_to_deposit" id="deposit_note_user_bank_to_deposit" value="1" <?php echo set_checkbox('deposit_note_user_bank_to_deposit', '1', (element('deposit_note_user_bank_to_deposit', element('data', $view)) ? true : false)); ?> /> <strong>회원</strong>에게 <strong>쪽지</strong>를 발송합니다 (쪽지사용에 <strong>동의한 회원</strong>에게만 발송합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="deposit_sms_admin_bank_to_deposit">
							<input type="checkbox" name="deposit_sms_admin_bank_to_deposit" id="deposit_sms_admin_bank_to_deposit" value="1" <?php echo set_checkbox('deposit_sms_admin_bank_to_deposit', '1', (element('deposit_sms_admin_bank_to_deposit', element('data', $view)) ? true : false)); ?> /> <strong>최고관리자</strong>에게 <strong>문자</strong>를 발송합니다 (문자수신동의 여부와 상관없이 <strong>무조건 발송</strong>합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="deposit_sms_user_bank_to_deposit">
							<input type="checkbox" name="deposit_sms_user_bank_to_deposit" id="deposit_sms_user_bank_to_deposit" value="1" <?php echo set_checkbox('deposit_sms_user_bank_to_deposit', '1', (element('deposit_sms_user_bank_to_deposit', element('data', $view)) ? true : false)); ?> /> <strong>회원</strong>에게 <strong>문자</strong>를 발송합니다 (문자수신에 <strong>동의한 회원</strong>에게만 발송합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="deposit_sms_alluser_bank_to_deposit">
							<input type="checkbox" name="deposit_sms_alluser_bank_to_deposit" id="deposit_sms_alluser_bank_to_deposit" value="1" <?php echo set_checkbox('deposit_sms_alluser_bank_to_deposit', '1', (element('deposit_sms_alluser_bank_to_deposit', element('data', $view)) ? true : false)); ?> /> <strong>회원</strong>에게 <strong>문자</strong>를 발송합니다 (문자수신 동의에 상관없이 <strong>무조건 발송</strong>합니다)
						</label>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">무통장입금 완료처리시</label>
				<div class="col-sm-10">
					<div class="checkbox">
						<label for="deposit_email_admin_approve_bank_to_deposit">
							<input type="checkbox" name="deposit_email_admin_approve_bank_to_deposit" id="deposit_email_admin_approve_bank_to_deposit" value="1" <?php echo set_checkbox('deposit_email_admin_approve_bank_to_deposit', '1', (element('deposit_email_admin_approve_bank_to_deposit', element('data', $view)) ? true : false)); ?> /> <strong>최고관리자</strong>에게 <strong>메일</strong>을 발송합니다 (이메일 수신동의 여부와 상관없이 <strong>무조건 발송</strong>합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="deposit_email_user_approve_bank_to_deposit">
							<input type="checkbox" name="deposit_email_user_approve_bank_to_deposit" id="deposit_email_user_approve_bank_to_deposit" value="1" <?php echo set_checkbox('deposit_email_user_approve_bank_to_deposit', '1', (element('deposit_email_user_approve_bank_to_deposit', element('data', $view)) ? true : false)); ?> /> <strong>회원</strong>에게 <strong>메일</strong>을 발송합니다 (이메일수신<strong>동의한 회원</strong>에게만 발송합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="deposit_email_alluser_approve_bank_to_deposit">
							<input type="checkbox" name="deposit_email_alluser_approve_bank_to_deposit" id="deposit_email_alluser_approve_bank_to_deposit" value="1" <?php echo set_checkbox('deposit_email_alluser_approve_bank_to_deposit', '1', (element('deposit_email_alluser_approve_bank_to_deposit', element('data', $view)) ? true : false)); ?> /> <strong>회원</strong>에게 <strong>메일</strong>을 발송합니다 (이메일 수신동의 여부와 상관없이 <strong>무조건 발송</strong>합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="deposit_note_admin_approve_bank_to_deposit">
							<input type="checkbox" name="deposit_note_admin_approve_bank_to_deposit" id="deposit_note_admin_approve_bank_to_deposit" value="1" <?php echo set_checkbox('deposit_note_admin_approve_bank_to_deposit', '1', (element('deposit_note_admin_approve_bank_to_deposit', element('data', $view)) ? true : false)); ?> /> <strong>최고관리자</strong>에게 <strong>쪽지</strong>를 발송합니다 (쪽지사용에 <strong>동의한 관리자</strong>에게만 발송합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="deposit_note_user_approve_bank_to_deposit">
							<input type="checkbox" name="deposit_note_user_approve_bank_to_deposit" id="deposit_note_user_approve_bank_to_deposit" value="1" <?php echo set_checkbox('deposit_note_user_approve_bank_to_deposit', '1', (element('deposit_note_user_approve_bank_to_deposit', element('data', $view)) ? true : false)); ?> /> <strong>회원</strong>에게 <strong>쪽지</strong>를 발송합니다 (쪽지사용에 <strong>동의한 회원</strong>에게만 발송합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="deposit_sms_admin_approve_bank_to_deposit">
							<input type="checkbox" name="deposit_sms_admin_approve_bank_to_deposit" id="deposit_sms_admin_approve_bank_to_deposit" value="1" <?php echo set_checkbox('deposit_sms_admin_approve_bank_to_deposit', '1', (element('deposit_sms_admin_approve_bank_to_deposit', element('data', $view)) ? true : false)); ?> /> <strong>최고관리자</strong>에게 <strong>문자</strong>를 발송합니다 (문자수신동의 여부와 상관없이 <strong>무조건 발송</strong>합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="deposit_sms_user_approve_bank_to_deposit">
							<input type="checkbox" name="deposit_sms_user_approve_bank_to_deposit" id="deposit_sms_user_approve_bank_to_deposit" value="1" <?php echo set_checkbox('deposit_sms_user_approve_bank_to_deposit', '1', (element('deposit_sms_user_approve_bank_to_deposit', element('data', $view)) ? true : false)); ?> /> <strong>회원</strong>에게 <strong>문자</strong>를 발송합니다 (문자수신에 <strong>동의한 회원</strong>에게만 발송합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="deposit_sms_alluser_approve_bank_to_deposit">
							<input type="checkbox" name="deposit_sms_alluser_approve_bank_to_deposit" id="deposit_sms_alluser_approve_bank_to_deposit" value="1" <?php echo set_checkbox('deposit_sms_alluser_approve_bank_to_deposit', '1', (element('deposit_sms_alluser_approve_bank_to_deposit', element('data', $view)) ? true : false)); ?> /> <strong>회원</strong>에게 <strong>문자</strong>를 발송합니다 (문자수신 동의에 상관없이 <strong>무조건 발송</strong>합니다)
						</label>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">포인트로 예치금 구매시</label>
				<div class="col-sm-10">
					<div class="checkbox">
						<label for="deposit_email_admin_point_to_deposit">
							<input type="checkbox" name="deposit_email_admin_point_to_deposit" id="deposit_email_admin_point_to_deposit" value="1" <?php echo set_checkbox('deposit_email_admin_point_to_deposit', '1', (element('deposit_email_admin_point_to_deposit', element('data', $view)) ? true : false)); ?> /> <strong>최고관리자</strong>에게 <strong>메일</strong>을 발송합니다 (이메일 수신동의 여부와 상관없이 <strong>무조건 발송</strong>합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="deposit_email_user_point_to_deposit">
							<input type="checkbox" name="deposit_email_user_point_to_deposit" id="deposit_email_user_point_to_deposit" value="1" <?php echo set_checkbox('deposit_email_user_point_to_deposit', '1', (element('deposit_email_user_point_to_deposit', element('data', $view)) ? true : false)); ?> /> <strong>회원</strong>에게 <strong>메일</strong>을 발송합니다 (이메일수신<strong>동의한 회원</strong>에게만 발송합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="deposit_email_alluser_point_to_deposit">
							<input type="checkbox" name="deposit_email_alluser_point_to_deposit" id="deposit_email_alluser_point_to_deposit" value="1" <?php echo set_checkbox('deposit_email_alluser_point_to_deposit', '1', (element('deposit_email_alluser_point_to_deposit', element('data', $view)) ? true : false)); ?> /> <strong>회원</strong>에게 <strong>메일</strong>을 발송합니다 (이메일 수신동의 여부와 상관없이 <strong>무조건 발송</strong>합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="deposit_note_admin_point_to_deposit">
							<input type="checkbox" name="deposit_note_admin_point_to_deposit" id="deposit_note_admin_point_to_deposit" value="1" <?php echo set_checkbox('deposit_note_admin_point_to_deposit', '1', (element('deposit_note_admin_point_to_deposit', element('data', $view)) ? true : false)); ?> /> <strong>최고관리자</strong>에게 <strong>쪽지</strong>를 발송합니다 (쪽지사용에 <strong>동의한 관리자</strong>에게만 발송합니다쪽지사용에 <strong>동의한 관리자</strong>에게만 발송합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="deposit_note_user_point_to_deposit">
							<input type="checkbox" name="deposit_note_user_point_to_deposit" id="deposit_note_user_point_to_deposit" value="1" <?php echo set_checkbox('deposit_note_user_point_to_deposit', '1', (element('deposit_note_user_point_to_deposit', element('data', $view)) ? true : false)); ?> /> <strong>회원</strong>에게 <strong>쪽지</strong>를 발송합니다 (쪽지사용에 <strong>동의한 회원</strong>에게만 발송합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="deposit_sms_admin_point_to_deposit">
							<input type="checkbox" name="deposit_sms_admin_point_to_deposit" id="deposit_sms_admin_point_to_deposit" value="1" <?php echo set_checkbox('deposit_sms_admin_point_to_deposit', '1', (element('deposit_sms_admin_point_to_deposit', element('data', $view)) ? true : false)); ?> /> <strong>최고관리자</strong>에게 <strong>문자</strong>를 발송합니다 (문자수신동의 여부와 상관없이 <strong>무조건 발송</strong>합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="deposit_sms_user_point_to_deposit">
							<input type="checkbox" name="deposit_sms_user_point_to_deposit" id="deposit_sms_user_point_to_deposit" value="1" <?php echo set_checkbox('deposit_sms_user_point_to_deposit', '1', (element('deposit_sms_user_point_to_deposit', element('data', $view)) ? true : false)); ?> /> <strong>회원</strong>에게 <strong>문자</strong>를 발송합니다 (문자수신에 <strong>동의한 회원</strong>에게만 발송합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="deposit_sms_alluser_point_to_deposit">
							<input type="checkbox" name="deposit_sms_alluser_point_to_deposit" id="deposit_sms_alluser_point_to_deposit" value="1" <?php echo set_checkbox('deposit_sms_alluser_point_to_deposit', '1', (element('deposit_sms_alluser_point_to_deposit', element('data', $view)) ? true : false)); ?> /> <strong>회원</strong>에게 <strong>문자</strong>를 발송합니다 (문자수신 동의에 상관없이 <strong>무조건 발송</strong>합니다)
						</label>
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">예치금을 포인트로 전환시</label>
				<div class="col-sm-10">
					<div class="checkbox">
						<label for="deposit_email_admin_deposit_to_point">
							<input type="checkbox" name="deposit_email_admin_deposit_to_point" id="deposit_email_admin_deposit_to_point" value="1" <?php echo set_checkbox('deposit_email_admin_deposit_to_point', '1', (element('deposit_email_admin_deposit_to_point', element('data', $view)) ? true : false)); ?> /> <strong>최고관리자</strong>에게 <strong>메일</strong>을 발송합니다 (이메일 수신동의 여부와 상관없이 <strong>무조건 발송</strong>합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="deposit_email_user_deposit_to_point">
							<input type="checkbox" name="deposit_email_user_deposit_to_point" id="deposit_email_user_deposit_to_point" value="1" <?php echo set_checkbox('deposit_email_user_deposit_to_point', '1', (element('deposit_email_user_deposit_to_point', element('data', $view)) ? true : false)); ?> /> <strong>회원</strong>에게 <strong>메일</strong>을 발송합니다 (이메일수신<strong>동의한 회원</strong>에게만 발송합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="deposit_email_alluser_deposit_to_point">
							<input type="checkbox" name="deposit_email_alluser_deposit_to_point" id="deposit_email_alluser_deposit_to_point" value="1" <?php echo set_checkbox('deposit_email_alluser_deposit_to_point', '1', (element('deposit_email_alluser_deposit_to_point', element('data', $view)) ? true : false)); ?> /> <strong>회원</strong>에게 <strong>메일</strong>을 발송합니다 (이메일 수신동의 여부와 상관없이 <strong>무조건 발송</strong>합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="deposit_note_admin_deposit_to_point">
							<input type="checkbox" name="deposit_note_admin_deposit_to_point" id="deposit_note_admin_deposit_to_point" value="1" <?php echo set_checkbox('deposit_note_admin_deposit_to_point', '1', (element('deposit_note_admin_deposit_to_point', element('data', $view)) ? true : false)); ?> /> <strong>최고관리자</strong>에게 <strong>쪽지</strong>를 발송합니다 (쪽지사용에 <strong>동의한 관리자</strong>에게만 발송합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="deposit_note_user_deposit_to_point">
							<input type="checkbox" name="deposit_note_user_deposit_to_point" id="deposit_note_user_deposit_to_point" value="1" <?php echo set_checkbox('deposit_note_user_deposit_to_point', '1', (element('deposit_note_user_deposit_to_point', element('data', $view)) ? true : false)); ?> /> <strong>회원</strong>에게 <strong>쪽지</strong>를 발송합니다 (쪽지사용에 <strong>동의한 회원</strong>에게만 발송합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="deposit_sms_admin_deposit_to_point">
							<input type="checkbox" name="deposit_sms_admin_deposit_to_point" id="deposit_sms_admin_deposit_to_point" value="1" <?php echo set_checkbox('deposit_sms_admin_deposit_to_point', '1', (element('deposit_sms_admin_deposit_to_point', element('data', $view)) ? true : false)); ?> /> <strong>최고관리자</strong>에게 <strong>문자</strong>를 발송합니다 (문자수신동의 여부와 상관없이 <strong>무조건 발송</strong>합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="deposit_sms_user_deposit_to_point">
							<input type="checkbox" name="deposit_sms_user_deposit_to_point" id="deposit_sms_user_deposit_to_point" value="1" <?php echo set_checkbox('deposit_sms_user_deposit_to_point', '1', (element('deposit_sms_user_deposit_to_point', element('data', $view)) ? true : false)); ?> /> <strong>회원</strong>에게 <strong>문자</strong>를 발송합니다 (문자수신에 <strong>동의한 회원</strong>에게만 발송합니다)
						</label>
					</div>
					<div class="checkbox">
						<label for="deposit_sms_alluser_deposit_to_point">
							<input type="checkbox" name="deposit_sms_alluser_deposit_to_point" id="deposit_sms_alluser_deposit_to_point" value="1" <?php echo set_checkbox('deposit_sms_alluser_deposit_to_point', '1', (element('deposit_sms_alluser_deposit_to_point', element('data', $view)) ? true : false)); ?> /> <strong>회원</strong>에게 <strong>문자</strong>를 발송합니다 (문자수신 동의에 상관없이 <strong>무조건 발송</strong>합니다)
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
	$(document).on('change', '#deposit_email_alluser_cash_to_deposit', function() {
		if ($(this).is(':checked')) {
			$('#deposit_email_user_cash_to_deposit').prop('checked', false).prop('disabled', true);
		} else {
			$('#deposit_email_user_cash_to_deposit').prop('disabled', false);
		}
	});
	<?php if (element('deposit_email_alluser_cash_to_deposit', element('data', $view))) {?>
		$('#deposit_email_user_cash_to_deposit').prop('checked', false).prop('disabled', true);
	<?php } ?>
	$(document).on('change', '#deposit_email_user_cash_to_deposit', function() {
		if ($(this).is(':checked')) {
			$('#deposit_email_alluser_cash_to_deposit').prop('checked', false).prop('disabled', true);
		} else {
			$('#deposit_email_alluser_cash_to_deposit').prop('disabled', false);
		}
	});
	<?php if (element('deposit_email_user_cash_to_deposit', element('data', $view))) {?>
		$('#deposit_email_alluser_cash_to_deposit').prop('checked', false).prop('disabled', true);
	<?php } ?>
	$(document).on('change', '#deposit_sms_alluser_cash_to_deposit', function() {
		if ($(this).is(':checked')) {
			$('#deposit_sms_user_cash_to_deposit').prop('checked', false).prop('disabled', true);
		} else {
			$('#deposit_sms_user_cash_to_deposit').prop('disabled', false);
		}
	});
	<?php if (element('deposit_sms_alluser_cash_to_deposit', element('data', $view))) {?>
		$('#deposit_sms_user_cash_to_deposit').prop('checked', false).prop('disabled', true);
	<?php } ?>
	$(document).on('change', '#deposit_sms_user_cash_to_deposit', function() {
		if ($(this).is(':checked')) {
			$('#deposit_sms_alluser_cash_to_deposit').prop('checked', false).prop('disabled', true);
		} else {
			$('#deposit_sms_alluser_cash_to_deposit').prop('disabled', false);
		}
	});
	<?php if (element('deposit_sms_user_cash_to_deposit', element('data', $view))) {?>
		$('#deposit_sms_alluser_cash_to_deposit').prop('checked', false).prop('disabled', true);
	<?php } ?>


	$(document).on('change', '#deposit_email_alluser_bank_to_deposit', function() {
		if ($(this).is(':checked')) {
			$('#deposit_email_user_bank_to_deposit').prop('checked', false).prop('disabled', true);
		} else {
			$('#deposit_email_user_bank_to_deposit').prop('disabled', false);
		}
	});
	<?php if (element('deposit_email_alluser_bank_to_deposit', element('data', $view))) {?>
		$('#deposit_email_user_bank_to_deposit').prop('checked', false).prop('disabled', true);
	<?php } ?>
	$(document).on('change', '#deposit_email_user_bank_to_deposit', function() {
		if ($(this).is(':checked')) {
			$('#deposit_email_alluser_bank_to_deposit').prop('checked', false).prop('disabled', true);
		} else {
			$('#deposit_email_alluser_bank_to_deposit').prop('disabled', false);
		}
	});
	<?php if (element('deposit_email_user_bank_to_deposit', element('data', $view))) {?>
		$('#deposit_email_alluser_bank_to_deposit').prop('checked', false).prop('disabled', true);
	<?php } ?>
	$(document).on('change', '#deposit_sms_alluser_bank_to_deposit', function() {
		if ($(this).is(':checked')) {
			$('#deposit_sms_user_bank_to_deposit').prop('checked', false).prop('disabled', true);
		} else {
			$('#deposit_sms_user_bank_to_deposit').prop('disabled', false);
		}
	});
	<?php if (element('deposit_sms_alluser_bank_to_deposit', element('data', $view))) {?>
		$('#deposit_sms_user_bank_to_deposit').prop('checked', false).prop('disabled', true);
	<?php } ?>
	$(document).on('change', '#deposit_sms_user_bank_to_deposit', function() {
		if ($(this).is(':checked')) {
			$('#deposit_sms_alluser_bank_to_deposit').prop('checked', false).prop('disabled', true);
		} else {
			$('#deposit_sms_alluser_bank_to_deposit').prop('disabled', false);
		}
	});
	<?php if (element('deposit_sms_user_bank_to_deposit', element('data', $view))) {?>
		$('#deposit_sms_alluser_bank_to_deposit').prop('checked', false).prop('disabled', true);
	<?php } ?>


	$(document).on('change', '#deposit_email_alluser_approve_bank_to_deposit', function() {
		if ($(this).is(':checked')) {
			$('#deposit_email_user_approve_bank_to_deposit').prop('checked', false).prop('disabled', true);
		} else {
			$('#deposit_email_user_approve_bank_to_deposit').prop('disabled', false);
		}
	});
	<?php if (element('deposit_email_alluser_approve_bank_to_deposit', element('data', $view))) {?>
		$('#deposit_email_user_approve_bank_to_deposit').prop('checked', false).prop('disabled', true);
	<?php } ?>
	$(document).on('change', '#deposit_email_user_approve_bank_to_deposit', function() {
		if ($(this).is(':checked')) {
			$('#deposit_email_alluser_approve_bank_to_deposit').prop('checked', false).prop('disabled', true);
		} else {
			$('#deposit_email_alluser_approve_bank_to_deposit').prop('disabled', false);
		}
	});
	<?php if (element('deposit_email_user_approve_bank_to_deposit', element('data', $view))) {?>
		$('#deposit_email_alluser_approve_bank_to_deposit').prop('checked', false).prop('disabled', true);
	<?php } ?>
	$(document).on('change', '#deposit_sms_alluser_approve_bank_to_deposit', function() {
		if ($(this).is(':checked')) {
			$('#deposit_sms_user_approve_bank_to_deposit').prop('checked', false).prop('disabled', true);
		} else {
			$('#deposit_sms_user_approve_bank_to_deposit').prop('disabled', false);
		}
	});
	<?php if (element('deposit_sms_alluser_approve_bank_to_deposit', element('data', $view))) {?>
		$('#deposit_sms_user_approve_bank_to_deposit').prop('checked', false).prop('disabled', true);
	<?php } ?>
	$(document).on('change', '#deposit_sms_user_approve_bank_to_deposit', function() {
		if ($(this).is(':checked')) {
			$('#deposit_sms_alluser_approve_bank_to_deposit').prop('checked', false).prop('disabled', true);
		} else {
			$('#deposit_sms_alluser_approve_bank_to_deposit').prop('disabled', false);
		}
	});
	<?php if (element('deposit_sms_user_approve_bank_to_deposit', element('data', $view))) {?>
		$('#deposit_sms_alluser_approve_bank_to_deposit').prop('checked', false).prop('disabled', true);
	<?php } ?>

	$(document).on('change', '#deposit_email_alluser_point_to_deposit', function() {
		if ($(this).is(':checked')) {
			$('#deposit_email_user_point_to_deposit').prop('checked', false).prop('disabled', true);
		} else {
			$('#deposit_email_user_point_to_deposit').prop('disabled', false);
		}
	});
	<?php if (element('deposit_email_alluser_point_to_deposit', element('data', $view))) {?>
		$('#deposit_email_user_point_to_deposit').prop('checked', false).prop('disabled', true);
	<?php } ?>
	$(document).on('change', '#deposit_email_user_point_to_deposit', function() {
		if ($(this).is(':checked')) {
			$('#deposit_email_alluser_point_to_deposit').prop('checked', false).prop('disabled', true);
		} else {
			$('#deposit_email_alluser_point_to_deposit').prop('disabled', false);
		}
	});
	<?php if (element('deposit_email_user_point_to_deposit', element('data', $view))) {?>
		$('#deposit_email_alluser_point_to_deposit').prop('checked', false).prop('disabled', true);
	<?php } ?>
	$(document).on('change', '#deposit_sms_alluser_point_to_deposit', function() {
		if ($(this).is(':checked')) {
			$('#deposit_sms_user_point_to_deposit').prop('checked', false).prop('disabled', true);
		} else {
			$('#deposit_sms_user_point_to_deposit').prop('disabled', false);
		}
	});
	<?php if (element('deposit_sms_alluser_point_to_deposit', element('data', $view))) {?>
		$('#deposit_sms_user_point_to_deposit').prop('checked', false).prop('disabled', true);
	<?php } ?>
	$(document).on('change', '#deposit_sms_user_point_to_deposit', function() {
		if ($(this).is(':checked')) {
			$('#deposit_sms_alluser_point_to_deposit').prop('checked', false).prop('disabled', true);
		} else {
			$('#deposit_sms_alluser_point_to_deposit').prop('disabled', false);
		}
	});
	<?php if (element('deposit_sms_user_point_to_deposit', element('data', $view))) {?>
		$('#deposit_sms_alluser_point_to_deposit').prop('checked', false).prop('disabled', true);
	<?php } ?>


	$(document).on('change', '#deposit_email_alluser_deposit_to_point', function() {
		if ($(this).is(':checked')) {
			$('#deposit_email_user_deposit_to_point').prop('checked', false).prop('disabled', true);
		} else {
			$('#deposit_email_user_deposit_to_point').prop('disabled', false);
		}
	});
	<?php if (element('deposit_email_alluser_deposit_to_point', element('data', $view))) {?>
		$('#deposit_email_user_deposit_to_point').prop('checked', false).prop('disabled', true);
	<?php } ?>
	$(document).on('change', '#deposit_email_user_deposit_to_point', function() {
		if ($(this).is(':checked')) {
			$('#deposit_email_alluser_deposit_to_point').prop('checked', false).prop('disabled', true);
		} else {
			$('#deposit_email_alluser_deposit_to_point').prop('disabled', false);
		}
	});
	<?php if (element('deposit_email_user_deposit_to_point', element('data', $view))) {?>
		$('#deposit_email_alluser_deposit_to_point').prop('checked', false).prop('disabled', true);
	<?php } ?>
	$(document).on('change', '#deposit_sms_alluser_deposit_to_point', function() {
		if ($(this).is(':checked')) {
			$('#deposit_sms_user_deposit_to_point').prop('checked', false).prop('disabled', true);
		} else {
			$('#deposit_sms_user_deposit_to_point').prop('disabled', false);
		}
	});
	<?php if (element('deposit_sms_alluser_deposit_to_point', element('data', $view))) {?>
		$('#deposit_sms_user_deposit_to_point').prop('checked', false).prop('disabled', true);
	<?php } ?>
	$(document).on('change', '#deposit_sms_user_deposit_to_point', function() {
		if ($(this).is(':checked')) {
			$('#deposit_sms_alluser_deposit_to_point').prop('checked', false).prop('disabled', true);
		} else {
			$('#deposit_sms_alluser_deposit_to_point').prop('disabled', false);
		}
	});
	<?php if (element('deposit_sms_user_deposit_to_point', element('data', $view))) {?>
		$('#deposit_sms_alluser_deposit_to_point').prop('checked', false).prop('disabled', true);
	<?php } ?>
});
//]]>
</script>
