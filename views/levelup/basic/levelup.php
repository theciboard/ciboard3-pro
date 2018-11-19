<h4>레벨업</h4>
<div class="help-block">일정 조건을 만족하시면 상위레벨로 레벨업이 가능합니다</div>
<div class="alert alert-info">
	<strong>회원님의 가입일</strong> <div style="display:inline-block;padding-left:20px;">가입한지 <?php echo number_format(element('register', $view)); ?> 일째</div><br />
	<strong>회원님의 현재 레벨</strong> <div style="display:inline-block;padding-left:20px;">Lv <?php echo $this->member->item('mem_level'); ?></div><br />
	<strong>회원님의 작성 글수</strong> <div style="display:inline-block;padding-left:20px;"><?php echo number_format(element('postnum', $view)); ?> 개</div><br />
	<strong>회원님의 작성 댓글</strong> <div style="display:inline-block;padding-left:20px;"><?php echo number_format(element('commentnum', $view)); ?> 개</div><br />
	<strong>현재 보유 포인트</strong> <div style="display:inline-block;padding-left:30px;"><?php echo number_format($this->member->item('mem_point')); ?></div><br />
</div>

<?php
echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
echo show_alert_message(element('alert_message', $view), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
echo show_alert_message($this->session->flashdata('message'), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
$attributes = array('name' => 'levelup', 'id' => 'levelup');
echo form_open(current_full_url(), $attributes);
?>
	<input type="hidden" name="is_submit" value="1" />
	<table class="table">
		<thead>
			<tr>
				<th class="text-center">레벨</th>
				<th class="text-center">가입일</th>
				<th class="text-center">보유포인트</th>
				<th class="text-center">글작성</th>
				<th class="text-center">댓글작성</th>
				<th class="text-center">차감포인트</th>
				<th class="text-center">레벨업</th>
			</tr>
		</thead>
		<tbody>
		<?php
		$max_level = $this->cbconfig->item('max_level');
		$levelupconfig = json_decode($this->cbconfig->item('levelupconfig'), true);
		if ($max_level) {
			for ($i = 2; $i <= $max_level; $i++) {
				$use = false;
				if (element($i, element('register', $levelupconfig)) OR element($i, element('point_required', $levelupconfig)) OR element($i, element('post_num', $levelupconfig)) OR element($i, element('comment_num', $levelupconfig)) OR element($i, element('point_use', $levelupconfig))) {
					$use = true;
				}
				if (in_array($i, element('use', $levelupconfig)) && $use) {
		?>
			<tr>
				<td class="text-center">Lv <?php echo $i; ?></td>
				<td class="text-center"><?php echo number_format((int) element($i, element('register', $levelupconfig))); ?>일</td>
				<td class="text-center"><?php echo number_format((int) element($i, element('point_required', $levelupconfig))); ?>점</td>
				<td class="text-center"><?php echo number_format((int) element($i, element('post_num', $levelupconfig))); ?>개</td>
				<td class="text-center"><?php echo number_format((int) element($i, element('comment_num', $levelupconfig))); ?>개</td>
				<td class="text-center"><?php echo number_format((int) element($i, element('point_use', $levelupconfig))); ?>점</td>
				<td class="text-center">
					<?php if ((int) element('next_level', $view) === $i) {?>
						<button type="submit" class="btn btn-default">레벨업신청</button>
					<?php } else { ?>
						-
					<?php } ?>
				</td>
			</tr>
		<?php
		} else {
		?>
			<tr>
				<td class="text-center">Lv <?php echo $i; ?></td>
				<td class="text-center">-</td>
				<td class="text-center">-</td>
				<td class="text-center">-</td>
				<td class="text-center">-</td>
				<td class="text-center">-</td>
				<td class="text-center">-</td>
			</tr>
		<?php
				}
			}
		}
		?>
		</tbody>
	</table>
<?php echo form_close(); ?>
