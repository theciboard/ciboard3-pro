<div class="box">
	<div class="box-table">
		<?php
		echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
		echo show_alert_message(element('alert_message', $view), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
		echo show_alert_message($this->session->flashdata('message'), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
		?>
		<ul class="list-group">
			<?php
			$data = element('data', $view);
			function cmall_ca_list($p, $data, $len)
			{
				$return = '';
				$nextlen = $len + 1;
				if ($p && is_array($p)) {
					foreach ($p as $result) {
						$margin = 20 * $len;
						$attributes = array('class' => 'form-inline', 'name' => 'fcategory');
						$return .= '<li class="list-group-item">
											<div class="form-horizontal">
												<div class="form-group" style="margin-bottom:0;">';
						if ($len) {
							$return .= '<div style="width:10px;float:left;margin-left:' . $margin . 'px;margin-right:10px;"><span class="fa fa-arrow-right"></span></div>';
						}
						$return .= '<div class="pl10">
							<div class="cat-cca-id-' . element('cca_id', $result) . '">
								' . html_escape(element('cca_value', $result)) . ' (' . html_escape(element('cca_order', $result)) . ')
								<button class="btn btn-primary btn-xs" onClick="cat_modify(\'' . element('cca_id', $result) . '\')"><span class="glyphicon glyphicon-edit"></span></button>';
						if ( ! element(element('cca_id', $result), $data)) {
							$return .= '					<button class="btn btn-danger btn-xs btn-one-delete" data-one-delete-url = "' . admin_url('cmall/cmallcategory/delete/' . element('cca_id', $result)) . '"><span class="glyphicon glyphicon-trash"></span></button>';
						}
						$return .= '	</div><div class="form-inline mod-cca-id-' . element('cca_id', $result) . '" style="display:none;">';
						$return .= form_open(current_full_url(), $attributes);
						$return .= '<input type="hidden" name="cca_id"	value="' . element('cca_id', $result) . '" />
															<input type="hidden" name="type" value="modify" />
															<div class="form-group" style="margin-left:0;">
																카테고리명 <input type="text" class="form-control" name="cca_value" value="' . html_escape(element('cca_value', $result)) . '" />
																정렬순서 <input type="number" class="form-control" name="cca_order" value="' . html_escape(element('cca_order', $result)) . '"/>
																<button class="btn btn-primary btn-xs" type="submit" >저장</button>
																<a href="javascript:;" class="btn btn-default btn-xs" onClick="cat_cancel(\'' . element('cca_id', $result) . '\')">취소</a>
															</div>';
						$return .= form_close();
						$return .= '</div>
													</div>
													</div>
												</div>
											</li>';
						$parent = element('cca_id', $result);
						$return .= cmall_ca_list(element($parent, $data), $data, $nextlen);
					}
				}
				return $return;
			}
			echo cmall_ca_list(element(0, $data), $data, 0);
			?>
		</ul>
	<div>
		<div class="box-table">
			<?php
			$attributes = array('class' => 'form-horizontal', 'name' => 'fadminwrite', 'id' => 'fadminwrite');
			echo form_open(current_full_url(), $attributes);
			?>
				<input type="hidden" name="is_submit" value="1" />
				<input type="hidden" name="type" value="add" />
				<div class="form-group">
					<label class="col-sm-2 control-label">카테고리 추가</label>
					<div class="col-sm-8 form-inline">
						<select name="cca_parent" class="form-control">
							<option value="0">최상위카테고리</option>
							<?php
							$data = element('data', $view);
							function cmall_ca_select($p, $data)
							{
								$return = '';
								if ($p && is_array($p)) {
									foreach ($p as $result) {
										$return .= '<option value="' . html_escape(element('cca_id', $result)) . '">' . html_escape(element('cca_value', $result)) . '의 하위카테고리</option>';
										$parent = element('cca_id', $result);
										$return .= cmall_ca_select(element($parent, $data), $data);
									}
								}
								return $return;
							}
							echo cmall_ca_select(element(0, $data), $data);
							?>
						</select>
						<input type="text" name="cca_value" class="form-control" value="" placeholder="카테고리명 입력" />
						<input type="number" name="cca_order" class="form-control" value="0" placeholder="정렬순서" />
						<button type="submit" class="btn btn-success btn-sm">추가하기</button>
					</div>
				</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>

<script type="text/javascript">
//<![CDATA[
$(function() {
	$('#fadminwrite', 'input[name=fcategory]').validate({
		rules: {
			cca_value: {required :true},
			cca_order: {required :true, numeric:true},
		}
	});
});

function cat_modify(cca_id) {
	$('.cat-cca-id-' + cca_id).hide();
	$('.mod-cca-id-' + cca_id).show();
}
function cat_cancel(cca_id) {
	$('.cat-cca-id-' + cca_id).show();
	$('.mod-cca-id-' + cca_id).hide();
}
//]]>
</script>
