<div class="box">
	<div class="box-header">
		<ul class="nav nav-tabs">
			<li role="presentation" class="active"><a href="<?php echo admin_url($this->pagedir); ?>">구매통계</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/memberstat'); ?>">회원별 구매회수</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/paynumstat'); ?>">결제회수별 회원수</a></li>
		</ul>
	</div>
	<div class="box-table">
		<div class="box-table-header">
			<form class="form-inline" name="flist" action="<?php echo current_url(); ?>" method="get" >
				<input type="hidden" name="datetype" value="<?php echo html_escape($this->input->get('datetype')); ?>" />
				<input type="hidden" name="method" value="<?php echo html_escape($this->input->get('method')); ?>" />
				<div class="btn-group" role="group" aria-label="...">
					<button type="button" class="btn <?php echo ( ! $this->input->get('method')) ? 'btn-success' : 'btn-default'; ?> btn-sm" onclick="fcharge_submit('');">전체</button>
					<button type="button" class="btn <?php echo ($this->input->get('method') === 'bank') ? 'btn-success' : 'btn-default'; ?> btn-sm" onclick="fmethod_submit('bank');">무통장</button>
					<button type="button" class="btn <?php echo ($this->input->get('method') === 'card') ? 'btn-success' : 'btn-default'; ?> btn-sm" onclick="fmethod_submit('card');">카드</button>
					<button type="button" class="btn <?php echo ($this->input->get('method') === 'realtime') ? 'btn-success' : 'btn-default'; ?> btn-sm" onclick="fmethod_submit('realtime');">실시간</button>
					<button type="button" class="btn <?php echo ($this->input->get('method') === 'vbank') ? 'btn-success' : 'btn-default'; ?> btn-sm" onclick="fmethod_submit('vbank');">가상계좌</button>
					<button type="button" class="btn <?php echo ($this->input->get('method') === 'phone') ? 'btn-success' : 'btn-default'; ?> btn-sm" onclick="fmethod_submit('phone');">핸드폰</button>
					<button type="button" class="btn <?php echo ($this->input->get('method') === 'service') ? 'btn-success' : 'btn-default'; ?> btn-sm" onclick="fmethod_submit('service');">서비스</button>
				</div>
				<div class="box-table-button">
					<span class="mr10">
						기간 : <input type="text" class="form-control input-small datepicker " name="start_date" value="<?php echo element('start_date', $view); ?>" readonly="readonly" /> - <input type="text" class="form-control input-small datepicker" name="end_date" value="<?php echo element('end_date', $view); ?>" readonly="readonly" />
					</span>
					<div class="btn-group" role="group" aria-label="...">
						<button type="button" class="btn <?php echo ($this->input->get('datetype') !== 'y' && $this->input->get('datetype') !== 'm') ? 'btn-success' : 'btn-default'; ?> btn-sm" onclick="fdate_submit('d');">일별보기</button>
						<button type="button" class="btn <?php echo ($this->input->get('datetype') === 'm') ? 'btn-success' : 'btn-default'; ?> btn-sm" onclick="fdate_submit('m');">월별보기</button>
						<button type="button" class="btn <?php echo ($this->input->get('datetype') === 'y') ? 'btn-success' : 'btn-default'; ?> btn-sm" onclick="fdate_submit('y');">년별보기</button>
					</div>
				</div>
			</form>
			<script type="text/javascript">
			//<![CDATA[
			function fdate_submit(datetype)
			{
				var f = document.flist;
				f.datetype.value = datetype;
				f.submit();
			}
			function fcharge_submit(dep_from_type)
			{
				var f = document.flist;
				f.method.value = '';
				f.submit();
			}
			function fmethod_submit(method)
			{
				var f = document.flist;
				f.method.value = method;
				f.submit();
			}
			//]]>
			</script>
		</div>
		<div class="table-responsive">
			<table class="table table-hover table-striped table-bordered">
				<colgroup>
					<col>
					<col>
					<col>
					<col>
					<col class="col-md-5">
				</colgroup>
				<thead>
					<tr>
						<th>날짜</th>
						<th>결제금액</th>
						<th>결제회수</th>
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
						<td><?php echo element('day', $result); ?></td>
						<td class="text-right"><?php echo number_format(element('money', $result)); ?></td>
						<td class="text-right"><?php echo number_format(element('cnt', $result)); ?></td>
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
							<td>전체</td>
							<td class="text-right"><?php echo number_format(element('sum_money', $view)); ?></td>
							<td class="text-right"><?php echo number_format(element('sum_count', $view)); ?></td>
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
