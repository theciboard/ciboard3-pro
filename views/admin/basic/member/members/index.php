<div class="box">
	<div class="box-table">
		<?php
		echo show_alert_message($this->session->flashdata('message'), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
		$attributes = array('class' => 'form-inline', 'name' => 'flist', 'id' => 'flist');
		echo form_open(current_full_url(), $attributes);
		?>
			<div class="box-table-header">
				<div class="btn-group btn-group-sm" role="group">
					<a href="?" class="btn btn-sm <?php echo ( ! $this->input->get('mem_is_admin') && ! $this->input->get('mem_denied')) ? 'btn-success' : 'btn-default'; ?>">전체회원</a>
					<a href="?mem_is_admin=1" class="btn btn-sm <?php echo ($this->input->get('mem_is_admin')) ? 'btn-success' : 'btn-default'; ?>">최고관리자</a>
					<a href="?mem_denied=1" class="btn btn-sm <?php echo ($this->input->get('mem_denied')) ? 'btn-success' : 'btn-default'; ?>">차단회원</a>
				</div>
				<div class="btn-group btn-group-sm" role="group">
					<a href="?" class="btn btn-sm <?php echo ( ! $this->input->get('mgr_id')) ? 'btn-success' : 'btn-default'; ?>">전체그룹</a>
					<?php
					foreach (element('all_group', $view) as $gkey => $gval) {
					?>
						<a href="?mgr_id=<?php echo element('mgr_id', $gval); ?>" class="btn btn-sm <?php echo (element('mgr_id', $gval) === $this->input->get('mgr_id')) ? 'btn-success' : 'btn-default'; ?>"><?php echo element('mgr_title', $gval); ?></a>
					<?php
					}
					?>
				</div>
				<?php
				ob_start();
				?>
					<div class="btn-group pull-right" role="group" aria-label="...">
						<button type="button" class="btn btn-outline btn-success btn-sm" id="export_to_excel"><i class="fa fa-file-excel-o"></i> 엑셀 다운로드</button>
						<a href="<?php echo element('listall_url', $view); ?>" class="btn btn-outline btn-default btn-sm">전체목록</a>
						<button type="button" class="btn btn-outline btn-default btn-sm btn-list-delete btn-list-selected disabled" data-list-delete-url = "<?php echo element('list_delete_url', $view); ?>" >선택삭제</button>
						<a href="<?php echo element('write_url', $view); ?>" class="btn btn-outline btn-danger btn-sm">회원추가</a>
					</div>
				<?php
				$buttons = ob_get_contents();
				ob_end_flush();
				?>
			</div>
			<div class="row">전체 : <?php echo element('total_rows', element('data', $view), 0); ?>건</div>
			<div class="table-responsive">
				<table class="table table-hover table-striped table-bordered">
					<thead>
						<tr>
							<th><a href="<?php echo element('mem_id', element('sort', $view)); ?>">번호</a></th>
							<th><a href="<?php echo element('mem_userid', element('sort', $view)); ?>">아이디</a></th>
							<th><a href="<?php echo element('mem_username', element('sort', $view)); ?>">실명</a></th>
							<th><a href="<?php echo element('mem_nickname', element('sort', $view)); ?>">닉네임</a></th>
							<th><a href="<?php echo element('mem_email', element('sort', $view)); ?>">이메일</a></th>
							<?php if ($this->cbconfig->item('use_selfcert')) { ?>
								<th>본인인증</th>
							<?php } ?>
							<?php if ($this->cbconfig->item('use_sociallogin')) { ?>
								<th>소셜연동</th>
							<?php } ?>
							<th><a href="<?php echo element('mem_point', element('sort', $view)); ?>">포인트</a></th>
							<th><?php echo $this->cbconfig->item('deposit_name') ? html_escape($this->cbconfig->item('deposit_name')) : '예치금'; ?></th>
							<th><a href="<?php echo element('mem_register_datetime', element('sort', $view)); ?>">가입일</a></th>
							<th><a href="<?php echo element('mem_lastlogin_datetime', element('sort', $view)); ?>">최근로그인</a></th>
							<th>회원그룹</th>
							<th><a href="<?php echo element('mem_level', element('sort', $view)); ?>">회원레벨</a></th>
							<th>메일인증/공개/메일/쪽지/문자</th>
							<th>승인</th>
							<th>수정</th>
							<th><input type="checkbox" name="chkall" id="chkall" /></th>
						</tr>
					</thead>
					<tbody>
					<?php
					if (element('list', element('data', $view))) {
						foreach (element('list', element('data', $view)) as $result) {
					?>
						<tr>
							<td><?php echo number_format(element('num', $result)); ?></td>
							<td><?php echo html_escape(element('mem_userid', $result)); ?></td>
							<td>
								<span><?php echo html_escape(element('mem_username', $result)); ?></span>
								<?php echo element('mem_is_admin', $result) ? '<span class="label label-primary">최고관리자</span>' : ''; ?>
								<?php echo element('mem_denied', $result) ? '<span class="label label-danger">차단</span>' : ''; ?>
							</td>
							<td><?php echo element('display_name', $result); ?></td>
							<td><?php echo html_escape(element('mem_email', $result)); ?></td>
							<?php if ($this->cbconfig->item('use_selfcert')) { ?>
								<td>
									<?php
									echo (element('selfcert_type', element('meta', $result)) === 'phone') ? '<span class="label label-success">휴대폰</span>' : '';
									echo (element('selfcert_type', element('meta', $result)) === 'ipin') ? '<span class="label label-success">IPIN</span>' : '';
									echo is_adult(element('selfcert_birthday', element('meta', $result))) ? '<span class="label label-danger">성인</span>' : '';
									?>
								</td>
							<?php } ?>
							<?php if ($this->cbconfig->item('use_sociallogin')) { ?>
								<td>
									<?php if ($this->cbconfig->item('use_sociallogin_facebook') && element('facebook_id', element('social', $result))) { ?>
										<a href="javascript:;" onClick="social_open('facebook', '<?php echo element('mem_id', $result); ?>');"><img src="<?php echo base_url('assets/images/social_facebook.png'); ?>" width="15" height="15" alt="페이스북 로그인" title="페이스북 로그인" /></a>
									<?php } ?>
									<?php if ($this->cbconfig->item('use_sociallogin_twitter') && element('twitter_id', element('social', $result))) { ?>
										<a href="javascript:;" onClick="social_open('twitter', '<?php echo element('mem_id', $result); ?>');"><img src="<?php echo base_url('assets/images/social_twitter.png'); ?>" width="15" height="15" alt="트위터 로그인" title="트위터 로그인" /></a>
									<?php } ?>
									<?php if ($this->cbconfig->item('use_sociallogin_google') && element('google_id', element('social', $result))) { ?>
										<a href="javascript:;" onClick="social_open('google', '<?php echo element('mem_id', $result); ?>');"><img src="<?php echo base_url('assets/images/social_google.png'); ?>" width="15" height="15" alt="구글 로그인" title="구글 로그인" /></a>
									<?php } ?>
									<?php if ($this->cbconfig->item('use_sociallogin_naver') && element('naver_id', element('social', $result))) { ?>
										<a href="javascript:;" onClick="social_open('naver', '<?php echo element('mem_id', $result); ?>');"><img src="<?php echo base_url('assets/images/social_naver.png'); ?>" width="15" height="15" alt="네이버 로그인" title="네이버 로그인" /></a>
									<?php } ?>
									<?php if ($this->cbconfig->item('use_sociallogin_kakao') && element('kakao_id', element('social', $result))) { ?>
										<a href="javascript:;" onClick="social_open('kakao', '<?php echo element('mem_id', $result); ?>');"><img src="<?php echo base_url('assets/images/social_kakao.png'); ?>" width="15" height="15" alt="카카오 로그인" title="카카오 로그인" /></a>
									<?php } ?>
								</td>
							<?php } ?>
							<td class="text-right"><?php echo number_format(element('mem_point', $result)); ?></td>
							<td class="text-right"><?php echo number_format((int) element('total_deposit', element('meta', $result))); ?></td>
							<td><?php echo display_datetime(element('mem_register_datetime', $result), 'full'); ?></td>
							<td><?php echo display_datetime(element('mem_lastlogin_datetime', $result), 'full'); ?></td>
							<td><?php echo element('member_group', $result); ?></td>
							<td class="text-right"><?php echo element('mem_level', $result); ?></td>
							<td>
								<?php echo element('mem_email_cert', $result) ? '<i class="fa fa-check-square-o"></i>' : '<i class="fa fa-square-o"></i>';; ?>
								<?php echo element('mem_open_profile', $result) ? '<i class="fa fa-check-square-o"></i>' : '<i class="fa fa-square-o"></i>';; ?>
								<?php echo element('mem_receive_email', $result) ? '<i class="fa fa-check-square-o"></i>' : '<i class="fa fa-square-o"></i>';; ?>
								<?php echo element('mem_use_note', $result) ? '<i class="fa fa-check-square-o"></i>' : '<i class="fa fa-square-o"></i>';; ?>
								<?php echo element('mem_receive_sms', $result) ? '<i class="fa fa-check-square-o"></i>' : '<i class="fa fa-square-o"></i>';; ?>
							</td>
							<td><?php echo element('mem_denied', $result) ? '<span class="label label-danger">차단</span>' : '승인'; ?></td>
							<td><a href="<?php echo admin_url($this->pagedir); ?>/write/<?php echo element(element('primary_key', $view), $result); ?>?<?php echo $this->input->server('QUERY_STRING', null, ''); ?>" class="btn btn-outline btn-default btn-xs">수정</a></td>
							<td><input type="checkbox" name="chk[]" class="list-chkbox" value="<?php echo element(element('primary_key', $view), $result); ?>" /></td>
						</tr>
					<?php
						}
					}
					if ( ! element('list', element('data', $view))) {
					?>
						<tr>
							<td colspan="17" class="nopost">자료가 없습니다</td>
						</tr>
					<?php
					}
					?>
					</tbody>
				</table>
			</div>
			<div class="box-info">
				<?php echo element('paging', $view); ?>
				<div class="pull-left ml20"><?php echo admin_listnum_selectbox();?></div>
				<?php echo $buttons; ?>
			</div>
		<?php echo form_close(); ?>
	</div>
	<form name="fsearch" id="fsearch" action="<?php echo current_full_url(); ?>" method="get">
		<div class="box-search">
			<div class="row">
				<div class="col-md-6 col-md-offset-3">
					<select class="form-control" name="sfield" >
						<?php echo element('search_option', $view); ?>
					</select>
					<div class="input-group">
						<input type="text" class="form-control" name="skeyword" value="<?php echo html_escape(element('skeyword', $view)); ?>" placeholder="Search for..." />
						<span class="input-group-btn">
							<button class="btn btn-default btn-sm" name="search_submit" type="submit">검색!</button>
						</span>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>

<script type="text/javascript">
//<![CDATA[
function social_open(stype, mem_id) {
	var pop_url = cb_admin_url + '/member/members/socialinfo/' + stype + '/' + mem_id;
	window.open(pop_url, 'win_socialinfo', 'left=100,top=100,width=730,height=500,scrollbars=1');
	return false;
}

$(document).on('click', '#export_to_excel', function(){
	exporturl = '<?php echo admin_url($this->pagedir . '/excel' . '?' . $this->input->server('QUERY_STRING', null, '')); ?>';
	document.location.href = exporturl;
})

//]]>
</script>
