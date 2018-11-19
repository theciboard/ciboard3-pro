<div class="modal-header">
	<h4 class="modal-title">게시물 카테고리 변경</h4>
</div>
<div class="modal-body">
	<?php
	echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
	$attributes = array('class' => 'form-horizontal', 'name' => 'fwrite', 'id' => 'fwrite');
	echo form_open(current_full_url(), $attributes);
	?>
		<input type="hidden" name="is_submit" value="1" />
		<input type="hidden" name="post_id_list" value="<?php echo element('post_id_list', $view); ?>" />
		<table class="table table-striped mt20">
			<tbody>
			<?php
			$data = element('data', $view);
			function ca_list($p, $data)
			{
				$return = '';
				if ($p && is_array($p)) {
					foreach ($p as $result) {
						$exp = explode('.', element('bca_key', $result));
						$len = (element(1, $exp)) ? strlen(element(1, $exp)) : 0;
						$margin = $len * 20;
						$attributes = array('class' => 'form-inline', 'name' => 'fcategory');
						$return .= '<tr>
									<td>
									<label for="chk_' . element('bca_id', $result) . '" class="checkbox-inline">
									<input type="radio" id="chk_' . element('bca_id', $result) . '" name="chk_post_category" value="' . element('bca_key', $result) . '" /> ' . html_escape(element('bca_value', $result)) . '
									</label>
									</td>
								</tr>';
						$parent = element('bca_key', $result);
						$return .= ca_list(element($parent, $data), $data);
					}
				}
				return $return;
			}
			echo ca_list(element(0, $data), $data);
			?>
			</tbody>
		</table>
		<div class="pull-right" style="margin:20px;">
			<button class="btn btn-primary" type="submit">변경하기</button>
			<button class="btn btn-default" onClick="window.close();">닫기</button>
		</div>
	<?php echo form_close(); ?>
</div>
