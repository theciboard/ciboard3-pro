<div class="box">
	<div class="box-table">
		<div class="box-table-header">
			<ul class="nav nav-pills">
				<li role="presentation" class="active"><a href="<?php echo admin_url($this->pagedir); ?>">게시판별접속자</a></li>
				<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/cleanlog'); ?>">오래된 로그삭제</a></li>
			</ul>
			<form class="form-inline" name="flist" action="<?php echo current_url(); ?>" method="get" >
				<input type="hidden" name="datetype" value="<?php echo html_escape($this->input->get('datetype')); ?>" />
				<div class="box-table-button">
					<?php if (element('boardlist', $view)) { ?>
						<span class="mr10">
							<select name="brd_id" class="form-control">
								<option value="">전체게시판</option>
								<?php foreach (element('boardlist', $view) as $key => $value) { ?>
									<option value="<?php echo element('brd_id', $value); ?>" <?php echo set_select('brd_id', element('brd_id', $value), ($this->input->get('brd_id') === element('brd_id', $value) ? true : false)); ?>><?php echo html_escape(element('brd_name', $value)); ?></option>
								<?php } ?>
							</select>
						</span>
					<?php } ?>
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
				<colgroup>
					<col class="col-md-2">
					<col class="col-md-2">
					<col class="col-md-2">
					<col class="col-md-2">
					<col class="col-md-4">
				</colgroup>
				<thead>
					<tr>
						<th>날짜</th>
						<th>게시판</th>
						<th>방문자수</th>
						<th>비율</th>
						<th>그래프</th>
					</tr>
				</thead>
				<tbody class="graphlist">
				<?php
				if (element('list', $view)) {
					foreach (element('list', $view) as $key => $result) {
						$exkey = explode('_', $key);
						$brd_name = $this->board->item_id('brd_name', element(1, $exkey));
				?>
					<tr class="<?php echo ( ! element('count', $result)) ? 'zerodata' : ''; ?>">
						<td><?php echo element(0, $exkey); ?></td>
						<td><?php echo html_escape($brd_name); ?></td>
						<td><?php echo element('count', $result, 0); ?></td>
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
							<td><?php echo element('sum_count', $view, 0); ?></td>
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
		 ['기간',
		<?php
		if (element('boardlist', $view)) {
			foreach (element('boardlist', $view) as $key => $value) { ?>
			'<?php echo html_escape(element('brd_name', $value)); ?>',
		<?php
			}
		}
		?>
			'총합'
		],
		<?php
		if (element('list_date', $view)) {
			foreach (element('list_date', $view) as $key => $result) {
		?>
		['<?php echo $key; ?>',
		<?php
		$sum = 0;
		if (element('boardlist', $view)) {
			foreach (element('boardlist', $view) as $skey => $value) {
				$v = element('count', element($key . '_' . element('brd_id', $value), element('list', $view)), 0);
				echo $v;
				echo ', ';
				$sum += $v;
			}
		}
		echo $sum;
		?>
		],
		<?php
			}
		}
		?>
	]);

	var chart = new google.visualization.ComboChart(document.getElementById('chart_div'));

	chart.draw(data, {
		width: '100%', height: '400',
		legendTextStyle: {fontName: 'gulim', fontSize: '12'},
		hAxis: {textStyle: {color: '#959595', fontName: 'dotum', fontSize: '12'}},
		vAxis: {textStyle: {color: '#959595', fontName: 'dotum', fontSize: '12'}, gridlineColor: '#e1e1e1', baselineColor: '#e1e1e1', textPosition: 'out'},
		seriesType: 'bars',
		series: {9: {type: 'line'}}
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
	exporturl = '<?php echo admin_url($this->pagedir . '/index/excel' . '?' . $this->input->server('QUERY_STRING', null, '')); ?>';
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
