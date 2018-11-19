<div class="box-table">
	<div class="table-responsive">
		<table class="table table-bordered table-hover table-striped">
			<thead>
				<tr>
					<th>그룹명</th>
					<th>수신가능</th>
					<th>추가</th>
				</tr>
			</thead>
			<tbody>
			<?php
			if (element('data_no_group', $view)) {
				foreach (element('data_no_group', $view) as $result) {
			?>
				<tr>
					<td><a href="javascript:sms_obj.person(<?php echo element('smg_id', $result); ?>)"><?php echo element('smg_name', $result); ?></a></td>
					<td class="td_num"><?php echo number_format(element('receive_num', $result)); ?></td>
					<td class="td_mngsmall">
						<button type="button" class="btn btn-default btn-xs" onclick="sms_obj.group_add(<?php echo element('smg_id', $result); ?>, '<?php echo html_escape(element('smg_name', $result)); ?>', '<?php echo number_format(element('receive_num', $result)); ?>')">추가</button>
					</td>
				</tr>
			<?php
				}
			}
			if (element('data', $view)) {
				foreach (element('data', $view) as $result) {
			?>
				<tr>
					<td><a href="javascript:sms_obj.person(<?php echo element('smg_id', $result); ?>)"><?php echo element('smg_name', $result); ?></a></td>
					<td class="td_num"><?php echo number_format(element('receive_num', $result)); ?></td>
					<td class="td_mngsmall">
						<button type="button" class="btn btn-default btn-xs" onclick="sms_obj.group_add(<?php echo element('smg_id', $result); ?>, '<?php echo html_escape(element('smg_name', $result)); ?>', '<?php echo number_format(element('receive_num', $result)); ?>')">추가</button>
					</td>
				</tr>
			<?php
				}
			}
			?>
			</tbody>
		</table>
	</div>
</div>
