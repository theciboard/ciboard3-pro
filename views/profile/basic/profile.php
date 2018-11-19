<div class="modal-header">
	<h4 class="modal-title">Profile</h4>
</div>
<div class="modal-body">
	<ul class="profile-info">
		<li class="pull-left">
		<?php
		if ($this->cbconfig->item('use_member_photo') && element('mem_photo', element('member', $view))) {
		?>
			<img src="<?php echo member_photo_url(element('mem_photo', element('member', $view))); ?>" width="64" height="64" class="media-object" alt="<?php echo html_escape(element('mem_nickname', element('member', $view))); ?> 님의 사진" title="<?php echo html_escape(element('mem_nickname', element('member', $view))); ?> 님의 사진" />
		<?php
		} else {
		?>
			<span class="fa fa-user fa-3x"></span>
		<?php
		}
		?>
		</li>
		<li class="like pull-right">
			<a class="good" href="javascript:;" onClick="add_follow('<?php echo element('mem_userid', element('member', $view)); ?>', 'followed_number');" title="<?php echo html_escape(element('mem_nickname', element('member', $view))); ?> 님을 친구추가하기"><i class="fa fa-thumbs-o-up fa-lg"></i> <span class="followed_number"><?php echo number_format(element('mem_followed', element('member', $view))); ?></span></a>
		</li>
	</ul>
	<table class="table mt20">
		<tbody>
			<tr>
				<th>포인트</th>
				<td><?php echo number_format(element('mem_point', element('member', $view))); ?></td>
			</tr>

			<?php
			if ($data) {
				foreach ($data as $key => $value) {
			?>
				<tr>
					<th><?php echo html_escape(element('display_name', $value)); ?></th>
					<td><?php echo nl2br(html_escape(element('value', $value))); ?> </td>
				</tr>
			<?php
				}
			}
			?>
			<tr>
				<th>회원가입일</th>
				<td><?php echo display_datetime(element('mem_register_datetime', element('member', $view)), 'full'); ?></td>
			</tr>
			<tr>
				<th>최종접속일</th>
				<td><?php echo display_datetime(element('mem_lastlogin_datetime', element('member', $view)), 'full'); ?></td>
			</tr>
		</tbody>
	</table>
</div>
<div class="modal-footer">
	<button type="submit" class="btn btn-black btn-sm" onClick="window.close();">닫기</button>
</div>
