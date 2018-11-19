<div class="box">
	<div class="box-table">
		<div class="box-table-header">
			<ul class="nav nav-pills">
				<li role="presentation" class="active"><a href="<?php echo admin_url($this->pagedir); ?>">상품내용변경로그</a></li>
				<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/cleanlog'); ?>">오래된 로그삭제</a></li>
			</ul>
		</div>
		<div class="box-table-header">
			<div class="box-table-title">
				<h4>게시물 정보</h4>
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
							<th>글쓴이</th>
							<td><div class="textbox"><?php echo element('item_display_name', element('data', $view)); ?></div></td>
						</tr>
						<tr>
							<th>작성일</th>
							<td><div class="textbox"><?php echo display_datetime(element('cit_datetime', element('item', element('data', $view))), 'full'); ?></div></td>
						</tr>
						<tr>
							<th>IP 주소</th>
							<td><?php echo display_admin_ip(element('cit_ip', element('itemmeta', element('data', $view)))); ?></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="box-table">
		<div class="box-table-header">
			<div class="box-table-title">
				<h4>게시물 변경정보</h4>
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
							<th>변경한 사람</th>
							<td><div class="textbox"><?php echo element('display_name', element('data', $view)); ?></div></td>
						</tr>
						<tr>
							<th>변경일</th>
							<td><div class="textbox"><?php echo display_datetime(element('chi_datetime', element('data', $view)), 'full'); ?></div></td>
						</tr>
						<tr>
							<th>변경 IP 주소</th>
							<td><?php echo display_admin_ip(element('chi_ip', element('data', $view))); ?></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="box-table">
		<div class="box-table-header">
			<div class="box-table-title">
				<h4>변경내용</h4>
			</div>
			<div class="box-table-button">
				<a data-toggle="collapse" href="#collapse3" aria-expanded="false" aria-controls="collapse3"><i class="fa fa-chevron-up"></i></a>
			</div>
		</div>
		<div class="collapse in" id="collapse3">
			<div class="table-responsive">
				<table class="table table-bordered table-hover table-striped">
					<colgroup>
						<col class="col-md-2">
						<col class="col-md-10">
					</colgroup>
					<tbody>
						<tr>
							<th>변경전 상품명</th>
							<td><div class="textbox"><?php echo html_escape(element('chi_title', element('prev', element('data', $view)))); ?></div></td>
						</tr>
						<tr>
							<th>변경전 내용</th>
							<td><div class="textbox"><?php echo element('content', element('prev', element('data', $view))); ?></div></td>
						</tr>
						<tr>
							<th>변경후 상품명</th>
							<td><div class="textbox"><?php echo html_escape(element('chi_title', element('data', $view))); ?></div></td>
						</tr>
						<tr>
							<th>변경후 내용</th>
							<td><div class="textbox"><?php echo element('content', element('data', $view)); ?></div></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="btn-group pull-right" role="group" aria-label="...">
				<button type="button" class="btn btn-outline btn-default btn-sm btn-history-back">목록으로</button>
				<button type="button" class="btn btn-outline btn-warning btn-sm btn-one-delete" data-one-delete-url = "<?php echo element('delete_url', $view); ?>">삭제하기</button>
			</div>
		</div>
	</div>
</div>
