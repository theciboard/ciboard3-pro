<div class="box">
	<div class="box-table">
		<div class="box-table-header">
			<ul class="nav nav-pills">
				<li role="presentation"><a href="<?php echo admin_url($this->pagedir); ?>">방문자로그 </a></li>
				<li role="presentation" class="active"><a href="<?php echo admin_url($this->pagedir . '/visit'); ?>">기간별 그래프</a></li>
				<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/cleanlog'); ?>">오래된 로그삭제</a></li>
			</ul>
			<form class="form-inline" name="flist" method="get" >
				<div class="box-table-button">
					<span class="mr10">
						기간 : <input type="text" class="form-control input-small datepicker " name="start_date" value="<?php echo element('start_date', $view); ?>" readonly="readonly" /> - <input type="text" class="form-control input-small datepicker" name="end_date" value="<?php echo element('end_date', $view); ?>" readonly="readonly" />
					</span>
					<div class="btn-group" role="group" aria-label="...">
						<button data-page-url="<?php echo admin_url($this->pagedir . '/visit'); ?>" class="btn btn-default btn-sm statsubmit">방문자</button>
						<button data-page-url="<?php echo admin_url($this->pagedir . '/domain'); ?>" class="btn btn-default btn-sm statsubmit">도메인</button>
						<button data-page-url="<?php echo admin_url($this->pagedir . '/browser'); ?>" class="btn btn-default btn-sm statsubmit">브라우저</button>
						<button data-page-url="<?php echo admin_url($this->pagedir . '/os'); ?>" class="btn btn-default btn-sm statsubmit">운영체제</button>
						<button data-page-url="<?php echo admin_url($this->pagedir . '/hour'); ?>" class="btn btn-default btn-sm statsubmit">시간</button>
						<button data-page-url="<?php echo admin_url($this->pagedir . '/week'); ?>" class="btn btn-default btn-sm statsubmit">요일</button>
						<button data-page-url="<?php echo admin_url($this->pagedir . '/day'); ?>" class="btn btn-warning btn-sm statsubmit">일</button>
						<button data-page-url="<?php echo admin_url($this->pagedir . '/month'); ?>" class="btn btn-default btn-sm statsubmit">월</button>
						<button data-page-url="<?php echo admin_url($this->pagedir . '/year'); ?>" class="btn btn-default btn-sm statsubmit">년</button>
					</div>
				</div>
			</form>
			<script type="text/javascript">
			//<![CDATA[
			$(document).on('click', '.statsubmit', function() {
				var f = document.flist;
				f.action= $(this).attr('data-page-url');
				f.submit();
			});
			//]]>
			</script>
		</div>
		<div id="chart_div"></div>
		<div class="table-responsive">
			<div class="pull-right form-group">
				<label for="withoutzero" class="checkbox-inline">
					<input type="checkbox" name="withoutzero" id="withoutzero" value="1" /> 방문자가 0 인 데이터 제외
				</label>
				<label for="orderdesc" class="checkbox-inline">
					<input type="checkbox" name="orderdesc" id="orderdesc" value="1"/> 역순으로보기
				</label>
			</div>
			<table class="table table-hover table-striped table-bordered">
				<thead>
					<tr>
						<th>날짜</th>
						<th>방문자수</th>
						<th>비율</th>
						<th>그래프</th>
					</tr>
				</thead>
				<tbody class="graphlist">
				<?php
				if (element('list', $view)) {
					foreach (element('list', $view) as $key => $result) {
				?>
					<tr class="<?php echo ( ! element('scd_count', $result)) ? 'zerodata' : ''; ?>">
						<td><?php echo $key; ?></td>
						<td><?php echo number_format(element('scd_count', $result, 0)); ?></td>
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
				?>
				</tbody>
				<?php
				if (element('list', $view)) {
				?>
					<tfoot>
						<tr class="warning">
							<td>전체</td>
							<td><?php echo number_format(element('sum_count', $view, 0)); ?></td>
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

	var data = new google.visualization.DataTable();

	data.addColumn('string', '일');
	data.addColumn('number', '방문자 수');
	data.addRows([
		<?php
		if (element('list', $view)) {
			foreach (element('list', $view) as $key => $result) {
		?>
		['<?php echo $key; ?>',<?php echo element('scd_count', $result, 0); ?>],
		<?php
			}
		}
		?>
	]);

	var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));

	chart.draw(data, {
		width: '100%', height: '400',
		legendTextStyle: {fontName: 'gulim', fontSize: '12'},
		tooltipTextStyle: {color: '#006679', fontName: 'dotum', fontSize: '12'},
		hAxis: {textStyle: {color: '#959595', fontName: 'dotum', fontSize: '12'}},
		vAxis: {textStyle: {color: '#959595', fontName: 'dotum', fontSize: '12'}, gridlineColor: '#e1e1e1', baselineColor: '#e1e1e1', textPosition: 'out'},
		lineWidth: 3,
		pointSize: 5
	});
}

$(document).on('change', '#withoutzero', function(){
	if (this.checked) {
		$('.zerodata').hide();
	} else {
		$('.zerodata').show();
	}
})
$(document).on('change', '#orderdesc', function(){
	var $body = $('tbody.graphlist');
	var list = $body.children('tr');
	$body.html(list.get().reverse());
})
$(document).on('click', '#export_to_excel', function(){
	exporturl = '<?php echo admin_url($this->pagedir . '/day/excel' . '?' . $this->input->server('QUERY_STRING', null, '')); ?>';
	if ($('#withoutzero:checked').length)
	{
		exporturl += '&withoutzero=1';
	}
	if ($('#orderdesc:checked').length)
	{
		exporturl += '&orderby=desc';
	}
	document.location.href = exporturl;
})
</script>
