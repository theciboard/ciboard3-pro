<script type="text/javascript" src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<div class="box">
	<div class="box-table">
		<?php
		echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
		echo show_alert_message(element('message', $view), '<div class="alert alert-warning">', '</div>');
		$attributes = array('class' => 'form-horizontal', 'name' => 'fadminwrite', 'id' => 'fadminwrite');
		echo form_open_multipart(current_full_url(), $attributes);
		?>
			<input type="hidden" name="<?php echo element('primary_key', $view); ?>"	value="<?php echo element(element('primary_key', $view), element('data', $view)); ?>" />
			<div class="form-group">
				<label class="col-sm-2 control-label">회원아이디</label>
				<div class="col-sm-10 form-inline">
					<input type="text" class="form-control" name="mem_userid" value="<?php echo set_value('mem_userid', element('mem_userid', element('data', $view))); ?>" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">회원이메일</label>
				<div class="col-sm-8">
					<input type="email" class="form-control" name="mem_email" value="<?php echo set_value('mem_email', element('mem_email', element('data', $view))); ?>" />
				</div>
				<div class="col-sm-2">
					<label for="mem_email_cert" class="checkbox-inline">
						<input type="checkbox" name="mem_email_cert" id="mem_email_cert" value="1" <?php echo set_checkbox('mem_email_cert', '1', (element('mem_email_cert', element('data', $view)) ? true : false)); ?> /> 인증
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">패스워드</label>
				<div class="col-sm-10">
					<input type="password" class="form-control" name="mem_password" value="" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">회원실명</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" name="mem_username" value="<?php echo set_value('mem_username', element('mem_username', element('data', $view))); ?>" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">닉네임</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" name="mem_nickname" value="<?php echo set_value('mem_nickname', element('mem_nickname', element('data', $view))); ?>" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">회원그룹</label>
				<div class="col-sm-10">
					<?php
					if (element('mgroup', element('data', $view))) {
						foreach (element('mgroup', element('data', $view)) as $gkey => $gval) {
							$chkvalue = is_array(element('member_group_member', element('data', $view))) && in_array(element('mgr_id', $gval), element('member_group_member', element('data', $view))) ? element('mgr_id', $gval) : '';
					?>
						<label for="member_group_<?php echo element('mgr_id', $gval); ?>" class="checkbox-inline">
							<input type="checkbox" name="member_group[]" id="member_group_<?php echo element('mgr_id', $gval); ?>" value="<?php echo element('mgr_id', $gval); ?>" <?php echo set_checkbox('member_group[]', element('mgr_id', $gval), ($chkvalue ? true : false)); ?> /> <?php echo element('mgr_title', $gval); ?>
						</label>
					<?php
						}
					}
					?>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">레벨</label>
				<div class="col-sm-10 form-inline">
					<select name="mem_level" class="form-control">
					<?php for ($i = 1; $i <= element('config_max_level', element('data', $view)); $i++) { ?>
						<option value="<?php echo $i; ?>" <?php echo set_select('mem_level', $i, ((int) element('mem_level', element('data', $view)) === $i ? true : false)); ?>><?php echo $i; ?></option>
					<?php } ?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">홈페이지</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" name="mem_homepage" value="<?php echo set_value('mem_homepage', element('mem_homepage', element('data', $view))); ?>" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">생일</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" name="mem_birthday" value="<?php echo set_value('mem_birthday', element('mem_birthday', element('data', $view))); ?>" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">전화번호</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" name="mem_phone" value="<?php echo set_value('mem_phone', element('mem_phone', element('data', $view))); ?>" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">성별</label>
				<div class="col-sm-10">
					<div class="input-group">
						<input type="radio" name="mem_sex" value="1" <?php echo set_radio('mem_sex', '1', (element('mem_sex', element('data', $view)) === '1' ? true : false)); ?> /> 남성
						<input type="radio" name="mem_sex" value="2" <?php echo set_radio('mem_sex', '2', (element('mem_sex', element('data', $view)) === '2' ? true : false)); ?> /> 여성
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">주소</label>
				<div class="col-sm-10">
					<label for="mem_zipcode">우편번호</label>
					<label>
						<input type="text" name="mem_zipcode" value="<?php echo set_value('mem_zipcode', element('mem_zipcode', element('data', $view))); ?>" id="mem_zipcode" class="form-control" size="7" maxlength="7" />
					</label>
					<label>
						<button type="button" class="btn btn-black btn-sm" style="margin-top:0px;" onclick="win_zip('fadminwrite', 'mem_zipcode', 'mem_address1', 'mem_address2', 'mem_address3', 'mem_address4');">주소 검색</button>
					</label>
					<div class="addr-line">
						<label for="mem_address1">기본주소</label>
						<input type="text" name="mem_address1" value="<?php echo set_value('mem_address1', element('mem_address1', element('data', $view))); ?>" id="mem_address1" class="form-control" size="50" placeholder="기본주소" />
					</div>
					<div class="addr-line">
						<label for="mem_address2">상세주소</label>
						<input type="text" name="mem_address2" value="<?php echo set_value('mem_address2', element('mem_address2', element('data', $view))); ?>" id="mem_address2" class="form-control" size="50" placeholder="상세주소" />
					</div>
					<label for="mem_address3">참고항목</label>
					<input type="text" name="mem_address3" value="<?php echo set_value('mem_address3', element('mem_address3', element('data', $view))); ?>" id="mem_address3" class="form-control" size="50" readonly="readonly" placeholder="참고항목" />
					<input type="hidden" name="mem_address4" value="<?php echo set_value('mem_address4', element('mem_address4', element('data', $view))); ?>" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">프로필사진</label>
				<div class="col-sm-10">
					<?php
					if (element('mem_photo', element('data', $view))) {
					?>
						<img src="<?php echo member_photo_url(element('mem_photo', element('data', $view))); ?>" alt="회원 사진" title="회원 사진" />
						<label for="mem_photo_del">
							<input type="checkbox" name="mem_photo_del" id="mem_photo_del" value="1" <?php echo set_checkbox('mem_photo_del', '1'); ?> /> 삭제
						</label>
					<?php
					}
					?>
					<input type="file" name="mem_photo" id="mem_photo" />
					<p class="help-block">가로길이 : <?php echo $this->cbconfig->item('member_photo_width'); ?>px, 세로길이 : <?php echo $this->cbconfig->item('member_photo_height'); ?>px 에 최적화되어있습니다, gif, jpg, png 파일 업로드가 가능합니다</p>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">회원아이콘</label>
				<div class="col-sm-10">
					<?php
					if (element('mem_icon', element('data', $view))) {
					?>
						<img src="<?php echo member_icon_url(element('mem_icon', element('data', $view))); ?>" alt="회원 아이콘" title="회원 아이콘" />
						<label for="mem_icon_del">
							<input type="checkbox" name="mem_icon_del" id="mem_icon_del" value="1" <?php echo set_checkbox('mem_icon_del', '1'); ?> /> 삭제
						</label>
					<?php
					}
					?>
					<input type="file" name="mem_icon" id="mem_icon" />
					<p class="help-block">가로길이 : <?php echo $this->cbconfig->item('member_icon_width'); ?>px, 세로길이 : <?php echo $this->cbconfig->item('member_icon_height'); ?>px 에 최적화되어있습니다, gif, jpg, png 파일 업로드가 가능합니다</p>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">메일받기</label>
				<div class="col-sm-10">
					<label for="mem_receive_email" class="checkbox-inline">
						<input type="checkbox" name="mem_receive_email" id="mem_receive_email" value="1" <?php echo set_checkbox('mem_receive_email', '1', (element('mem_receive_email', element('data', $view)) ? true : false)); ?> /> 사용합니다
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">쪽지사용</label>
				<div class="col-sm-10">
					<label for="mem_use_note" class="checkbox-inline">
						<input type="checkbox" name="mem_use_note" id="mem_use_note" value="1" <?php echo set_checkbox('mem_use_note', '1', (element('mem_use_note', element('data', $view)) ? true : false)); ?> /> 사용합니다
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">SMS 문자받기</label>
				<div class="col-sm-10">
					<label for="mem_receive_sms" class="checkbox-inline">
						<input type="checkbox" name="mem_receive_sms" id="mem_receive_sms" value="1" <?php echo set_checkbox('mem_receive_sms', '1', (element('mem_receive_sms', element('data', $view)) ? true : false)); ?> /> 사용합니다
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">프로필공개</label>
				<div class="col-sm-10">
					<label for="mem_open_profile" class="checkbox-inline">
						<input type="checkbox" name="mem_open_profile" id="mem_open_profile" value="1" <?php echo set_checkbox('mem_open_profile', '1', (element('mem_open_profile', element('data', $view)) ? true : false)); ?> /> 공개합니다
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">승인상태</label>
				<div class="col-sm-10 form-inline">
					<select name="mem_denied" class="form-control">
						<option value="0" <?php echo set_select('mem_denied', '0', ( ! element('mem_denied', element('data', $view)) ? true : false)); ?>>승인</option>
						<option value="1" <?php echo set_select('mem_denied', '1', (element('mem_denied', element('data', $view)) ? true : false)); ?>>차단</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">최고관리자</label>
				<div class="col-sm-10 form-inline">
					<select name="mem_is_admin" class="form-control">
						<option value="0" <?php echo set_select('mem_is_admin', '0', (element('mem_is_admin', element('data', $view)) !== '1' ? true : false)); ?>>아닙니다</option>
						<option value="1" <?php echo set_select('mem_is_admin', '1', (element('mem_is_admin', element('data', $view)) === '1' ? true : false)); ?>>최고관리자입니다</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">프로필</label>
				<div class="col-sm-10">
					<textarea class="form-control" rows="5" name="mem_profile_content"><?php echo set_value('mem_profile_content', element('mem_profile_content', element('data', $view))); ?></textarea>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">관리자용 메모</label>
				<div class="col-sm-10">
					<textarea class="form-control" rows="5" name="mem_adminmemo"><?php echo set_value('mem_adminmemo', element('mem_adminmemo', element('data', $view))); ?></textarea>
				</div>
			</div>
			<?php
			if (element('html_content', $view)) {
				foreach (element('html_content', $view) as $key => $value) {
			?>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="<?php echo element('field_name', $value); ?>"><?php echo element('display_name', $value); ?></label>
					<div class="col-sm-10"><?php echo element('input', $value); ?></div>
				</div>
			<?php
				}
			}
			?>
			<div class="btn-group pull-right" role="group" aria-label="...">
				<button type="button" class="btn btn-default btn-sm btn-history-back" >취소하기</button>
				<button type="submit" class="btn btn-success btn-sm">저장하기</button>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>

<script type="text/javascript">
//<![CDATA[
$(function() {
	$('#fadminwrite').validate({
		rules: {
			mem_userid: { required: true, minlength:3, maxlength:20 },
			mem_username: {minlength:2, maxlength:20 },
			mem_nickname: {required :true, minlength:2, maxlength:20 },
			mem_email: {required :true, email:true },
			mem_password: {minlength :4 }
		}
	});
});
//]]>
</script>
