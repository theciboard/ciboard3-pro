<div class="box">
	<div class="box-table">
		<div class="box-table-header">
			<div class="box-table-title">
				<h4>삭제 정보</h4>
			</div>
			<div class="box-table-button">
				<a data-toggle="collapse" href="#collapse1" aria-expanded="false" aria-controls="collapse1"><i class="fa fa-chevron-up"></i></a>
			</div>
		</div>
		<div class="collapse in" id="collapse1">
			<div class="table-responsive">
				<table class="table table-bordered table-hover table-striped">
					<colgroup>
						<col class="col-md-2">
						<col class="col-md-10">
					</colgroup>
					<tbody>
						<tr>
							<th>삭제자</th>
							<td><div class="textbox"><?php echo element('display_name', element('data', $view)); ?></div></td>
						</tr>
						<tr>
							<th>삭제 날짜</th>
							<td><div class="textbox"><?php echo display_datetime(element('trash_datetime', element('meta', element('data', $view))), 'full'); ?></div></td>
						</tr>
							<th>IP 주소</th>
							<td><?php echo display_admin_ip(element('trash_ip', element('meta', element('data', $view)))); ?></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="box-table">
		<div class="box-table-header">
			<div class="box-table-title">
				<h4>원문정보</h4>
			</div>
			<div class="box-table-button">
				<a data-toggle="collapse" href="#collapse2" aria-expanded="false" aria-controls="collapse2"><i class="fa fa-chevron-up"></i></a>
			</div>
		</div>
		<div class="collapse in" id="collapse2">
			<div class="table-responsive">
				<table class="table table-bordered table-hover table-striped">
					<colgroup>
						<col class="col-md-2">
						<col class="col-md-10">
					</colgroup>
					<tbody>
						<tr>
							<th>게시판</th>
							<td>
								<div class="textbox">
									<a href="<?php echo goto_url(element('boardurl', element('data', $view))); ?>" target="_blank"><?php echo html_escape(element('brd_name', element('board', element('data', $view)))); ?></a>
								</div>
							</td>
						</tr>
						<tr>
							<th>원글제목</th>
							<td>
								<div class="textbox">
									<a href="<?php echo element('posturl', element('data', $view)); ?>" target="_blank"><?php echo html_escape(element('post_title', element('post', element('data', $view)))); ?></a>
								</div>
							</td>
						</tr>
						<tr>
							<th>글쓴이</th>
							<td>
								<div class="textbox">
									<?php echo element('cmt_display_name', element('data', $view)); ?>
								</div>
							</td>
						</tr>
						<tr>
							<th>등록일</th>
							<td>
								<div class="textbox">
									<?php echo display_datetime(element('cmt_datetime', element('data', $view)), 'full'); ?>
								</div>
							</td>
						</tr>
						<tr>
							<th>댓글내용</th>
							<td>
								<div class="textbox">
									<?php echo element('content', element('data', $view)); ?>
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="btn-group pull-right" role="group" aria-label="...">
				<button type="button" class="btn btn-outline btn-default btn-sm btn-history-back">목록으로</button>
				<button type="button" class="btn btn-outline btn-warning btn-sm btn-one-delete" data-one-delete-url = "<?php echo element('delete_url', $view); ?>">삭제하기</button>
				<button type="button" class="btn btn-outline btn-success btn-sm btn-one-recover" data-one-recover-url = "<?php echo element('recover_url', $view); ?>">복원하기</button>
			</div>
		</div>
	</div>
</div>
