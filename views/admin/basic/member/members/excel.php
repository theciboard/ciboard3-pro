<meta http-equiv="Content-Type" content="text/html; charset=<?php echo config_item('charset');?>" />
<style type="text/css">
th {font-weight:bold;padding:5px; min-width:120px; width:120px; _width:120px; text-align:center; line-height:18px; font-size:12px; color:#959595; font-family:dotum,돋움; border-right:1px solid #e4e4e4;}
td {text-align:center; line-height:40px; font-size:12px; color:#474747; font-family:gulim,굴림; border-right:1px solid #e4e4e4;}
</style>
<table width="100%" border="1" bordercolor="#E4E4E4" cellspacing="0" cellpadding="0">
		<tr>
			<th>아이디</th>
			<th>실명</th>
			<th>닉네임</th>
			<th>이메일</th>
			<?php if ($this->cbconfig->item('use_selfcert')) { ?>
				<th>본인인증</th>
			<?php } ?>
			<?php if ($this->cbconfig->item('use_sociallogin')) { ?>
				<th>소셜연동</th>
			<?php } ?>
			<th>포인트</th>
			<th><?php echo $this->cbconfig->item('deposit_name') ? html_escape($this->cbconfig->item('deposit_name')) : '예치금'; ?></th>
			<th>가입일</th>
			<th>최근로그인</th>
			<th>회원그룹</th>
			<th>회원레벨</th>
			<th>메일인증/공개/메일/쪽지/문자</th>
			<th>승인</th>
		</tr>
	<?php
	if (element('list', element('data', $view))) {
		foreach (element('list', element('data', $view)) as $result) {
	?>
			<tr>
				<td height="30"><?php echo html_escape(element('mem_userid', $result)); ?></td>
				<td>
					<span><?php echo html_escape(element('mem_username', $result)); ?></span>
					<?php echo element('mem_is_admin', $result) ? '(최고관리자)' : ''; ?>
					<?php echo element('mem_denied', $result) ? '(차단회원)' : ''; ?>
				</td>
				<td><?php echo html_escape(element('mem_nickname', $result)); ?></td>
				<td><?php echo html_escape(element('mem_email', $result)); ?></td>
				<?php if ($this->cbconfig->item('use_selfcert')) { ?>
					<td>
						<?php
						echo (element('selfcert_type', element('meta', $result)) === 'phone') ? '휴대폰 ' : '';
						echo (element('selfcert_type', element('meta', $result)) === 'ipin') ? 'IPIN ' : '';
						echo is_adult(element('selfcert_birthday', element('meta', $result))) ? '성인' : '';
						?>
					</td>
				<?php } ?>
				<?php if ($this->cbconfig->item('use_sociallogin')) { ?>
					<td>
						<?php if ($this->cbconfig->item('use_sociallogin_facebook') && element('facebook_id', element('social', $result))) { ?>페이스북 <?php } ?>
						<?php if ($this->cbconfig->item('use_sociallogin_twitter') && element('twitter_id', element('social', $result))) { ?>트위터 <?php } ?>
						<?php if ($this->cbconfig->item('use_sociallogin_google') && element('google_id', element('social', $result))) { ?>구글 <?php } ?>
						<?php if ($this->cbconfig->item('use_sociallogin_naver') && element('naver_id', element('social', $result))) { ?>네이버 <?php } ?>
						<?php if ($this->cbconfig->item('use_sociallogin_kakao') && element('kakao_id', element('social', $result))) { ?>카카오 <?php } ?>
					</td>
				<?php } ?>
				<td><?php echo number_format(element('mem_point', $result)); ?></td>
				<td><?php echo number_format((int) element('total_deposit', element('meta', $result))); ?></td>
				<td><?php echo element('mem_register_datetime', $result); ?></td>
				<td><?php echo element('mem_lastlogin_datetime', $result); ?></td>
				<td><?php echo element('member_group', $result); ?></td>
				<td><?php echo element('mem_level', $result); ?></td>
				<td>
					<?php echo element('mem_email_cert', $result) ? 'O' : 'X';; ?>
					<?php echo element('mem_open_profile', $result) ? 'O' : 'X';; ?>
					<?php echo element('mem_receive_email', $result) ? 'O' : 'X';; ?>
					<?php echo element('mem_use_note', $result) ? 'O' : 'X';; ?>
					<?php echo element('mem_receive_sms', $result) ? 'O' : 'X';; ?>
				</td>
				<td><?php echo element('mem_denied', $result) ? '차단' : '승인'; ?></td>
			</tr>
		<?php
			}
		}
		?>
</table>
