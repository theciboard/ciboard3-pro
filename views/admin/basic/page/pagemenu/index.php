<div class="box">
	<div class="box-table">
		<?php
		echo show_alert_message($this->session->flashdata('message'), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
		$attributes = array('class' => 'form-horizontal', 'name' => 'flist', 'id' => 'flist');
		echo form_open(current_full_url(), $attributes);
		?>
			<div class="box-table-header">
				<?php
				ob_start();
				?>
					<div class="btn-group pull-right" role="group" aria-label="...">
						<button type="button" class="btn btn-outline btn-default btn-sm btn-list-update btn-list-selected disabled" data-list-update-url = "<?php echo element('list_update_url', $view); ?>" >선택수정</button>
						<button type="button" class="btn btn-outline btn-default btn-sm btn-list-delete btn-list-selected disabled" data-list-delete-url = "<?php echo element('list_delete_url', $view); ?>" >선택삭제</button>
					</div>
				<?php
				$buttons = ob_get_contents();
				ob_end_flush();
				?>
				<div class="row">전체 : <?php echo element('total_rows', element('data', $view), 0); ?>건</div>
			</div>
			<div class="table-responsive">
				<table class="table table-hover table-striped">
					<thead>
						<tr>
							<th>메뉴명</th>
							<th>새창여부</th>
							<th>커스텀(a 태그안)</th>
							<th>순서</th>
							<th>PC 사용</th>
							<th>모바일 사용</th>
							<th><input type="checkbox" name="chkall" id="chkall" /></th>
						</tr>
					</thead>
					<tbody>
					<?php
					if (element('list', element('data', $view))) {
						foreach (element('list', element('data', $view)) as $result) {
					?>
						<tr class="success">
							<td>
								<div class="form-group form-group-sm">
									<input type="text" name="men_name[<?php echo element(element('primary_key', $view), $result); ?>]" class="form-control input-sm" value="<?php echo html_escape(element('men_name', $result)); ?>" />
								</div>
							</td>
							<td>
								<div class="form-group form-group-sm">
									<select name="men_target[<?php echo element(element('primary_key', $view), $result); ?>]" class="form-control input-sm">
										<option value="" <?php echo ( ! element('men_target', $result)) ? 'selected="selected"' : ''; ?>>현재창</option>
										<option value="_blank" <?php echo (element('men_target', $result) === '_blank') ? 'selected="selected"' : ''; ?>>새창</option>
									</select>
								</div>
							</td>
							<td>
								<div class="form-group form-group-sm">
									<input type="text" name="men_custom[<?php echo element(element('primary_key', $view), $result); ?>]" class="form-control input-sm" value="<?php echo html_escape(element('men_custom', $result)); ?>" />
								</div>
							</td>
							<td>
								<div class="form-group form-group-sm">
									<input type="number" name="men_order[<?php echo element(element('primary_key', $view), $result); ?>]" class="form-control input-sm" value="<?php echo html_escape(element('men_order', $result)); ?>" />
								</div>
							</td>
							<td>
								<div class="form-group form-group-sm">
									<select name="men_desktop[<?php echo element(element('primary_key', $view), $result); ?>]" class="form-control input-sm">
										<option value="1" <?php echo (element('men_desktop', $result) === '1') ? 'selected="selected"' : ''; ?>>사용함</option>
										<option value="0" <?php echo (element('men_desktop', $result) !== '1') ? 'selected="selected"' : ''; ?>>사용안함</option>
									</select>
								</div>
							</td>
							<td>
								<div class="form-group form-group-sm">
									<select name="men_mobile[<?php echo element(element('primary_key', $view), $result); ?>]" class="form-control input-sm">
										<option value="1" <?php echo (element('men_mobile', $result) === '1') ? 'selected="selected"' : ''; ?>>사용함</option>
										<option value="0" <?php echo (element('men_mobile', $result) !== '1') ? 'selected="selected"' : ''; ?>>사용안함</option>
									</select>
								</div>
							</td>
							<td>
								<div class="form-group form-group-sm">
									<input type="checkbox" name="chk[]" class="list-chkbox" value="<?php echo element(element('primary_key', $view), $result); ?>" />
								</div>
							</td>
						</tr>
						<tr class="success">
							<th><div class="pull-right">링크주소</div></th>
							<td colspan="6">
								<div class="form-group form-group-sm">
									<input type="text" name="men_link[<?php echo element(element('primary_key', $view), $result); ?>]" class="form-control input-sm" value="<?php echo html_escape(element('men_link', $result)); ?>" />
								</div>
							</td>
						</tr>
						<?php
						if (element('list', element('subresult', $result))) {
							foreach (element('list', element('subresult', $result)) as $subresult) {
						?>
						<tr class="warning">
							<td>
								<div class="form-group form-group-sm form-inline pull-right">
									<span class="fa fa-arrow-right"></span>
									<input type="text" name="men_name[<?php echo element(element('primary_key', $view), $subresult); ?>]" class="form-control input-sm" value="<?php echo html_escape(element('men_name', $subresult)); ?>" />
								</div>
							</td>
							<td>
								<div class="form-group form-group-sm">
									<select name="men_target[<?php echo element(element('primary_key', $view), $subresult); ?>]" class="form-control input-sm">
										<option value="" <?php echo ( ! element('men_target', $subresult)) ? 'selected="selected"' : ''; ?>>현재창</option>
										<option value="_blank" <?php echo (element('men_target', $subresult) === '_blank') ? 'selected="selected"' : ''; ?>>새창</option>
									</select>
								</div>
							</td>
							<td>
								<div class="form-group form-group-sm">
									<input type="text" name="men_custom[<?php echo element(element('primary_key', $view), $subresult); ?>]" class="form-control input-sm" value="<?php echo html_escape(element('men_custom', $subresult)); ?>" />
								</div>
							</td>
							<td>
								<div class="form-group form-group-sm">
									<input type="number" name="men_order[<?php echo element(element('primary_key', $view), $subresult); ?>]" class="form-control input-sm" value="<?php echo html_escape(element('men_order', $subresult)); ?>" />
								</div>
							</td>
							<td>
								<div class="form-group form-group-sm">
									<select name="men_desktop[<?php echo element(element('primary_key', $view), $subresult); ?>]" class="form-control input-sm">
										<option value="1" <?php echo (element('men_desktop', $subresult) === '1') ? 'selected="selected"' : ''; ?>>사용함</option>
										<option value="0" <?php echo (element('men_desktop', $subresult) !== '1') ? 'selected="selected"' : ''; ?>>사용안함</option>
									</select>
								</div>
							</td>
							<td>
								<div class="form-group form-group-sm">
									<select name="men_mobile[<?php echo element(element('primary_key', $view), $subresult); ?>]" class="form-control input-sm">
										<option value="1" <?php echo (element('men_mobile', $subresult) === '1') ? 'selected="selected"' : ''; ?>>사용함</option>
										<option value="0" <?php echo (element('men_mobile', $subresult) !== '1') ? 'selected="selected"' : ''; ?>>사용안함</option>
									</select>
								</div>
							</td>
							<td>
								<div class="form-group form-group-sm">
								<input type="checkbox" name="chk[]" class="list-chkbox" value="<?php echo element(element('primary_key', $view), $subresult); ?>" />
								</div>
							</td>
						</tr>
						<tr class="warning">
							<th><div class="pull-right">링크주소</div></th>
							<td colspan="6">
								<div class="form-group form-group-sm">
									<input type="text" name="men_link[<?php echo element(element('primary_key', $view), $subresult); ?>]" class="form-control input-sm" value="<?php echo html_escape(element('men_link', $subresult)); ?>" />
								</div>
							</td>
						</tr>
						<?php
											}
									}
							}
						}
						if ( ! element('list', element('data', $view))) {
						?>
							<tr>
								<td colspan="7" class="nopost">자료가 없습니다</td>
							</tr>
						<?php
						}
						?>
					</tbody>
				</table>
			</div>
		<?php echo form_close(); ?>
	</div>
	<div class="box-table">
		<div class="box-table-header">
			<h5>메뉴 추가</h5>
		</div>
		<?php
		echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
		echo show_alert_message(element('alert_message', $view), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
		$attributes = array('class' => 'form-horizontal', 'name' => 'fadminwrite', 'id' => 'fadminwrite');
		echo form_open(current_full_url(), $attributes);
		?>
			<div class="table-responsive">
				<table class="table table-hover table-striped">
					<thead>
						<tr>
							<th>메뉴명</th>
							<th>메뉴위치</th>
							<th>새창여부</th>
							<th>커스텀(a 태그안)</th>
							<th>순서</th>
							<th>PC 사용</th>
							<th>모바일 사용</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>
								<div class="form-group form-group-sm">
									<input type="text" name="men_name" class="form-control input-sm" value="" />
								</div>
							</td>
							<td>
								<div class="form-group form-group-sm">
									<select name="men_parent" class="form-control input-sm">
										<option value="0">최상위메뉴</option>
										<?php
										if (element('list', element('data', $view))) {
											foreach (element('list', element('data', $view)) as $result) {
										?>
											<option value="<?php echo html_escape(element('men_id', $result)); ?>"><?php echo html_escape(element('men_name', $result)); ?>의 하위메뉴</option>
										<?php
											}
										}
										?>
									</select>
								</div>
							</td>
							<td>
								<div class="form-group form-group-sm">
									<select name="men_target" class="form-control input-sm">
										<option value="">현재창</option>
										<option value="_blank">새창</option>
									</select>
								</div>
							</td>
							<td>
								<div class="form-group form-group-sm">
									<input type="text" name="men_custom" class="form-control input-sm" value="" />
								</div>
							</td>
							<td>
								<div class="form-group form-group-sm">
									<input type="number" name="men_order" class="form-control input-sm" value="0" />
								</div>
							</td>
							<td>
								<div class="form-group form-group-sm">
									<select name="men_desktop" class="form-control input-sm">
										<option value="1">사용함</option>
										<option value="0">사용안함</option>
									</select>
								</div>
							</td>
							<td>
								<div class="form-group form-group-sm">
									<select name="men_mobile" class="form-control input-sm">
										<option value="1">사용함</option>
										<option value="0">사용안함</option>
									</select>
								</div>
							</td>
						</tr>
						<tr>
							<th class="pull-right">링크주소</th>
							<td colspan="6">
								<div class="form-group form-group-sm">
									<input type="text" name="men_link" class="form-control input-sm" value="" />
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="btn-group pull-right" role="group" aria-label="...">
				<button type="submit" class="btn btn-outline btn-success btn-sm">메뉴 추가하기</button>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>

<script type="text/javascript">
//<![CDATA[
$(function() {
	$('#fadminwrite').validate({
		rules: {
			men_nemu: { required:true},
			men_link: { required:true}
		}
	});
});
//]]>
</script>
