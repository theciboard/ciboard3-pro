<div class="modal-header">
	<h4 class="modal-title"><?php echo html_escape(element('socialname', $view)); ?> 연동 정보</h4>
</div>
<div class="modal-body">
	<div class="box-table">
		<div class="collapse in">
			<div class="table-responsive">
				<table class="table table-bordered table-hover table-striped">
					<colgroup>
						<col class="col-md-2">
						<col class="col-md-10">
					</colgroup>
					<tbody>
					<?php
					if (element('data', $view)) {
						foreach (element('data', $view) as $value) {
					?>
						<tr>
							<th><?php echo $value['text']; ?> ( <?php echo $value['key']; ?> )</th>
							<td><div class="textbox"><?php echo $value['value'];?></div></td>
						</tr>
					<?php
						}
					}
					?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="pull-right" style="margin:20px;"><button class="btn btn-default" onClick="window.close();">닫기</button></div>
</div>
