<div class="box">
	<div class="box-table">
		<div class="box-table-header">
			<ul class="nav nav-pills">
				<li role="presentation"><a href="<?php echo admin_url($this->pagedir); ?>">장바구니현황</a></li>
				<li role="presentation" class="active"><a href="<?php echo admin_url($this->pagedir . '/rank'); ?>">순위</a></li>
			</ul>
			<form class="form-inline" name="flist" action="<?php echo current_url(); ?>" method="get" >
				<div class="box-table-button">
					<span class="mr10">
						기간 : <input type="text" class="form-control input-small datepicker " name="start_date" value="<?php echo element('start_date', $view); ?>" readonly="readonly" /> - <input type="text" class="form-control input-small datepicker" name="end_date" value="<?php echo element('end_date', $view); ?>" readonly="readonly" />
					</span>
					<div class="btn-group" role="group" aria-label="...">
						<button type="submit" class="btn btn-default btn-sm">검색</button>
					</div>
				</div>
			</form>
		</div>
		<div id="chart_div"></div>
		<div class="table-responsive">
			<table class="table table-hover table-striped table-bordered">
				<colgroup>
					<col class="col-md-1">
					<col class="col-md-2">
					<col class="col-md-1">
					<col class="col-md-2">
					<col class="col-md-6">
				</colgroup>
				<thead>
					<tr>
						<th>순위</th>
						<th>상품명</th>
						<th>보관자수</th>
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
						<td><?php echo html_escape(element('cit_name', $result)); ?></td>
						<td><?php echo number_format(element('count', $result, 0)); ?></td>
						<td><?php echo element('s_rate', $result, 0); ?>%</td>
						<td>
							<div class="progress">
								<div class="progress-bar progress-bar-warning progress-bar-striped" role="progressbar" aria-valuenow="<?php echo element('s_rate', $result, 0); ?>" aria-valuemin="0" aria-valuemax="<?php echo element('max_value', $view, 0); ?>" style="width: <?php echo element('bar', $result, 0); ?>%">
									<span class="sr-only"><?php echo element('s_rate', $result, 0); ?>%</span>
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
							<td></td>
							<td><?php echo element('sum_count', $view); ?></td>
							<td></td>
							<td></td>
						</tr>
					</tfoot>
				<?php
				}
				?>
			</table>
		</div>
		<div class="box-info">
			<div class="btn-group pull-right" role="group" aria-label="...">
				<button type="button" class="btn btn-outline btn-success btn-sm" id="export_to_excel"><i class="fa fa-file-excel-o"></i> 엑셀 다운로드</button>
			</div>			
		</div>
	</div>
</div>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">

// Load the Visualization API and the corechart package.
google.charts.load('current', {'packages':['corechart']});

// Set a callback to run when the Google Visualization API is loaded.
google.charts.setOnLoadCallback(drawChart);

// Callback that creates and populates a data table,
// instantiates the pie chart, passes in the data and
// draws it.
function drawChart() {

	var data = new google.visualization.arrayToDataTable([
		['상품명', '비율'],
		<?php
		$sum = 0;
		if (element('list', $view)) {
			$i=0;
			foreach (element('list', $view) as $result) {
		?>
		['<?php echo html_escape(element('cit_name', $result)); ?>',<?php echo element('count', $result, 0); ?>],
		<?php
				$i++;
				$sum += element('count', $result, 0);
				if ($i > 8) break;
			}
		}
		if (element('sum_count', $view) && $sum && $sum < element('sum_count', $view)) {
		?>
			['기타',<?php echo element('sum_count', $view, 0) - $sum; ?>],
		<?php
		}
		?>
	]);

	var chart = new google.visualization.PieChart(document.getElementById('chart_div'));

	chart.draw(data, {
		width: '100%', height: '400',
	});
}

$(document).on('click', '#export_to_excel', function(){
	exporturl = '<?php echo admin_url($this->pagedir . '/rank/excel' . '?' . $this->input->server('QUERY_STRING', null, '')); ?>';
	document.location.href = exporturl;
})
</script>
