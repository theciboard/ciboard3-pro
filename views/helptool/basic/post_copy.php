<div class="modal-header">
	<h4 class="modal-title">게시물 <?php echo element('typetext', $view); ?></h4>
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
			if (element('list', $view)) {
				foreach (element('list', $view) as $result) {
					$my_one = (element('brd_id', $result) === element('brd_id', element('post', $view))) ? true : false;
			?>
				<tr>
					<td>
					<label for="chk_<?php echo element('brd_id', $result); ?>" class="checkbox-inline">
						<input type="radio" id="chk_<?php echo element('brd_id', $result); ?>" name="chk_brd_id" value="<?php echo element('brd_id', $result); ?>" <?php if ($my_one) { ?>disabled="disabled"<?php } ?> />
	<?php echo html_escape(element('bgr_name', element('group', $result))); ?> &gt; <?php echo html_escape(element('brd_name', $result)); ?> ( <?php echo html_escape(element('brd_key', $result)); ?> )
						</label>
	<?php if ($my_one) { ?><div class="pull-right label label-warning">현재</div><?php } ?>
					</td>
				</tr>
			<?php
				}
			}
			?>
			</tbody>
		</table>
		<div class="pull-right" style="margin:20px;">
			<button class="btn btn-primary" type="submit"><?php echo element('typetext', $view); ?>하기</button>
			<button class="btn btn-default" onClick="window.close();">닫기</button>
		</div>
	<?php echo form_close(); ?>
</div>
