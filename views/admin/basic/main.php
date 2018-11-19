<div class="col-sm-6">
	<div class="panel panel-default">
		<!-- Default panel contents -->
		<div class="panel-heading">최근등록회원
			<div class="view-all">
				<a href="<?php echo admin_url('member/members'); ?>">More <i class="fa fa-angle-right"></i></a>
			</div>
		</div>
		<!-- Table -->
		<table class="table table-hover table-striped">
			<colgroup>
				<col class="col-md-6">
				<col class="col-md-3">
				<col class="col-md-3">
			</colgroup>
			<tbody>
			<?php
			if (element('list', element('latest_member', $view))) {
				foreach (element('list', element('latest_member', $view)) as $key => $value) {
			?>
				<tr>
					<td><?php echo html_escape(element('mem_userid', $value)); ?></td>
					<td><?php echo element('display_name', $value); ?></td>
					<td class="text-right"><?php echo display_datetime(element('mem_register_datetime', $value)); ?></td>
				</tr>
			<?php
				}
			}
			?>
			</tbody>
		</table>
	</div>
</div>
<div class="col-sm-6">
	<div class="panel panel-default">
		<!-- Default panel contents -->
		<div class="panel-heading">최근포인트
			<div class="view-all">
				<a href="<?php echo admin_url('member/points'); ?>">More <i class="fa fa-angle-right"></i></a>
			</div>
		</div>
		<!-- Table -->
		<table class="table table-hover table-striped">
			<colgroup>
				<col class="col-md-4">
				<col class="col-md-2">
				<col class="col-md-3">
				<col class="col-md-3">
			</colgroup>
			<tbody>
			<?php
			if (element('list', element('latest_point', $view))) {
				foreach (element('list', element('latest_point', $view)) as $key => $value) {
			?>
				<tr>
					<td><?php echo html_escape(element('poi_content', $value)); ?></td>
					<td><?php echo number_format((int) element('poi_point', $value)); ?> P</td>
					<td><?php echo element('display_name', $value); ?></td>
					<td class="text-right"><?php echo display_datetime(element('poi_datetime', $value)); ?></td>
				</tr>
			<?php
				}
			}
			?>
			</tbody>
		</table>
	</div>
</div>

<div class="clearfix"></div>

<div class="col-sm-6">
	<div class="panel panel-default">
		<!-- Default panel contents -->
		<div class="panel-heading">최근게시물
			<div class="view-all">
				<a href="<?php echo admin_url('board/post'); ?>">More <i class="fa fa-angle-right"></i></a>
			</div>
		</div>
		<!-- Table -->
		<table class="table table-hover table-striped">
			<colgroup>
				<col class="col-md-6">
				<col class="col-md-3">
				<col class="col-md-3">
			</colgroup>
			<tbody>
			<?php
			if (element('list', element('latest_post', $view))) {
				foreach (element('list', element('latest_post', $view)) as $key => $value) {
			?>
				<tr>
					<td><a href="<?php echo element('post_url', $value); ?>"><?php echo html_escape(element('post_title', $value)); ?></a></td>
					<td><?php echo element('display_name', $value); ?></td>
					<td class="text-right"><?php echo display_datetime(element('post_datetime', $value)); ?></td>
				</tr>
			<?php
				}
			}
			?>
			</tbody>
		</table>
	</div>
</div>
<div class="col-sm-6">
	<div class="panel panel-default">
		<!-- Default panel contents -->
		<div class="panel-heading">최근댓글
			<div class="view-all">
				<a href="<?php echo admin_url('board/comment'); ?>">More <i class="fa fa-angle-right"></i></a>
			</div>
		</div>
		<!-- Table -->
		<table class="table table-hover table-striped">
			<colgroup>
				<col class="col-md-6">
				<col class="col-md-3">
				<col class="col-md-3">
			</colgroup>
			<tbody>
			<?php
			if (element('list', element('latest_comment', $view))) {
				foreach (element('list', element('latest_comment', $view)) as $key => $value) {
			?>
				<tr>
					<td><a href="<?php echo element('post_url', $value); ?>"><?php echo cut_str(html_escape(strip_tags(element('cmt_content', $value))),50); ?></a></td>
					<td><?php echo element('display_name', $value); ?></td>
					<td class="text-right"><?php echo display_datetime(element('cmt_datetime', $value)); ?></td>
				</tr>
			<?php
				}
			}
			?>
			</tbody>
		</table>
	</div>
</div>
