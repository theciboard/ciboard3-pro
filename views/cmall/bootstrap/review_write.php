<?php $this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css'); ?>

<div class="modal-header">
	<h4 class="modal-title">상품후기 작성</h4>
</div>
<div class="modal-body">
	<div class="form-horizontal ">
		<?php
		echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
		echo show_alert_message(element('message', $view), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
		$attributes = array('class' => 'form-horizontal', 'name' => 'fwrite', 'id' => 'fwrite');
		echo form_open(current_full_url(), $attributes);
		?>
			<div class="form-group">
				<label for="cre_title" class="col-sm-2 control-label">제목</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" name="cre_title" id="cre_title" value="<?php echo set_value('cre_title', element('cre_title', element('data', $view))); ?>" placeholder="제목을 입력해주세요" />
				</div>
			</div>
			<div class="form-group mt20">
				<label for="cre_content" class="col-sm-2 control-label">내용</label>
				<div class="col-sm-10">
					<?php echo display_dhtml_editor('cre_content', set_value('cre_content', element('cre_content', element('data', $view))), $classname = 'form-control dhtmleditor', $is_dhtml_editor = $this->cbconfig->item('use_cmall_product_review_dhtml'), $editor_type = $this->cbconfig->item('cmall_product_review_editor_type')); ?>
				</div>
			</div>
			<div class="form-group mt20">
				<label for="cre_title" class="col-sm-2 control-label">평점</label>
				<div class="col-sm-10 review-score">
					<label for="cre_score_5" class="col-xs-6 col-sm-4"><input type="radio" name="cre_score" id="cre_score_5" value="5" <?php echo set_radio('cre_score', '5', ((element('cre_score', element('data', $view)) === '5' OR ! element('cre_score', element('data', $view))) ? true : false)); ?> /> <img src="<?php echo element('view_skin_url', $layout); ?>/images/star5.png" alt="매우만족" title="매우만족" style="vertical-align:top;" /></label>
					<label for="cre_score_4" class="col-xs-6 col-sm-4"><input type="radio" name="cre_score" id="cre_score_4" value="4" <?php echo set_radio('cre_score', '4', (element('cre_score', element('data', $view)) === '4' ? true : false)); ?> /> <img src="<?php echo element('view_skin_url', $layout); ?>/images/star4.png" alt="만족" title="만족" style="vertical-align:top;" /></label>
					<label for="cre_score_3" class="col-xs-6 col-sm-4"><input type="radio" name="cre_score" id="cre_score_3" value="3" <?php echo set_radio('cre_score', '3', (element('cre_score', element('data', $view)) === '3' ? true : false)); ?> /> <img src="<?php echo element('view_skin_url', $layout); ?>/images/star3.png" alt="보통" title="보통" style="vertical-align:top;" /></label>
					<label for="cre_score_2" class="col-xs-6 col-sm-4"><input type="radio" name="cre_score" id="cre_score_2" value="2" <?php echo set_radio('cre_score', '2', (element('cre_score', element('data', $view)) === '2' ? true : false)); ?> /> <img src="<?php echo element('view_skin_url', $layout); ?>/images/star2.png" alt="불만" title="불만" style="vertical-align:top;" /></label>
					<label for="cre_score_1" class="col-xs-6 col-sm-4"><input type="radio" name="cre_score" id="cre_score_1" value="1" <?php echo set_radio('cre_score', '1', (element('cre_score', element('data', $view)) === '1' ? true : false)); ?> /> <img src="<?php echo element('view_skin_url', $layout); ?>/images/star1.png" alt="매우불만" title="매우불만" style="vertical-align:top;" /></label>
				</div>
			</div>
			<div class="form-group col-sm-6">
				<div class="pull-right">
					<a href="javascript:;" class="btn btn-default" onClick="window.close();">취소</a>
					<button type="submit" class="btn btn-primary">작성완료</button>
				</div>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>
<script type="text/javascript">
//<![CDATA[
$(function() {
	$('#fwrite').validate({
		rules: {
			cre_title : { required:true},
			cre_content : {<?php echo ($this->cbconfig->item('use_cmall_product_review_dhtml')) ? 'required_' . $this->cbconfig->item('cmall_product_review_editor_type') : 'required'; ?> : true }
		}
	});
});
//]]>
</script>
