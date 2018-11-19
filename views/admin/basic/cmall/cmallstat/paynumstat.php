<div class="box">
	<div class="box-header">
		<ul class="nav nav-tabs">
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir); ?>">구매통계</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/memberstat'); ?>">회원별 구매회수</a></li>
			<li role="presentation" class="active"><a href="<?php echo admin_url($this->pagedir . '/paynumstat'); ?>">결제회수별 회원수</a></li>
		</ul>
	</div>
	<div class="box-table">
		<div class="box-table-header">
			<form class="form-inline" name="flist" action="<?php echo current_url(); ?>" method="get" >
				<div class="box-table-button">
					<span class="mr10">기간 : <input type="text" class="form-control input-small datepicker " name="start_date" value="<?php echo element('start_date', $view); ?>" readonly="readonly" /> - <input type="text" class="form-control input-small datepicker" name="end_date" value="<?php echo element('end_date', $view); ?>" readonly="readonly" /></span>
					<div class="btn-group" role="group" aria-label="..."><button type="submit" class="btn btn-default btn-sm">확인</button></div>
				</div>
			</form>
		</div>
		<div class="table-responsive">
			<table class="table table-hover table-striped table-bordered">
				<colgroup>
					<col>
					<col>
					<col>
					<col>
					<col class="col-md-6">
				</colgroup>
				<thead>
					<tr>
						<th>순위</th>
						<th>결제회수</th>
						<th>회원수</th>
						<th>비율</th>
						<th>그래프</th>
					</tr>
				</thead>
				<tbody>
					<?php
					if (element('list', $view)) {
						foreach (element('list', $view) as $result) {
					?>
						<tr>
							<td><?php echo element('no', $result); ?></td>
							<td class="text-right"><?php echo element('cnt', $result); ?></td>
							<td class="text-right"><?php echo number_format(element('member', $result)); ?></td>
							<td class="text-right"><?php echo element('s_rate', $result); ?>%</td>
							<td>
								<div class="progress">
								<div class="progress-bar progress-bar-warning progress-bar-striped" role="progressbar" aria-valuenow="<?php echo element('s_rate', $result); ?>" aria-valuemin="0" aria-valuemax="<?php echo element('max_value', $view); ?>" style="width: <?php echo element('bar', $result); ?>%">
									<span class="sr-only"><?php echo element('s_rate', $result); ?>% Complete</span>
								</div>
								</div>
							</td>
						</tr>
					<?php
						}
					}
					if ( ! element('list', $view)) {
					?>
						<tr>
							<td colspan="5" class="nopost">자료가 없습니다</td>
						</tr>
					<?php
					}
					?>
				</tbody>
					<?php
					if (element('list', $view)) {
					?>
						<tfoot>
							<tr class="warning">
								<td></td>
								<td class="text-right"><?php echo number_format(element('sum_cnt', $view)); ?></td>
								<td class="text-right"><?php echo number_format(element('sum_member', $view)); ?></td>
								<td></td>
								<td></td>
							</tr>
						</tfoot>
					<?php
					}
					?>
			</table>
		</div>
	</div>
</div>
