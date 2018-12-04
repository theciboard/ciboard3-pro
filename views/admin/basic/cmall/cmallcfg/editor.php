<div class="box">
	<div class="box-header">
		<ul class="nav nav-tabs">
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir); ?>" onclick="return check_form_changed();">기본설정</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/layout'); ?>" onclick="return check_form_changed();">레이아웃/메타태그</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/access'); ?>" onclick="return check_form_changed();">권한관리</a></li>
			<li role="presentation" class="active"><a href="<?php echo admin_url($this->pagedir . '/editor'); ?>" onclick="return check_form_changed();">에디터기능</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/smsconfig'); ?>" onclick="return check_form_changed();">SMS 설정</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/paymentconfig'); ?>" onclick="return check_form_changed();">결제기능</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/alarm'); ?>" onclick="return check_form_changed();">알림설정</a></li>
		</ul>
	</div>
	<div class="box-table">
		<?php
		echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
		echo show_alert_message(element('alert_message', $view), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
		$attributes = array('class' => 'form-horizontal', 'name' => 'fadminwrite', 'id' => 'fadminwrite');
		echo form_open(current_full_url(), $attributes);
		?>
			<input type="hidden" name="is_submit" value="1" />
			<div class="form-group">
				<label class="col-sm-2 control-label">상품관리페이지 에디터사용</label>
				<div class="col-sm-10 form-inline">
					<label for="use_cmall_product_dhtml" class="checkbox-inline">
					<input type="checkbox" name="use_cmall_product_dhtml" id="use_cmall_product_dhtml" value="1" <?php echo set_checkbox('use_cmall_product_dhtml', '1', (element('use_cmall_product_dhtml', element('data', $view)) ? true : false)); ?> /> 사용합니다
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">상품관리페이지 에디터종류</label>
				<div class="col-sm-10 form-inline">
						<select class="form-control" name="cmall_product_editor_type" id="cmall_product_editor_type">
							<?php echo element('cmall_product_editor_type_option', element('data', $view)); ?>
						</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">상품관리페이지 첨부파일 가로크기</label>
				<div class="col-sm-10">
					PC - <input type="number" class="form-control" name="cmall_product_thumb_width" id="cmall_product_thumb_width" value="<?php echo set_value('cmall_product_thumb_width', (int) element('cmall_product_thumb_width', element('data', $view))); ?>" />px,
					모바일 - <input type="number" class="form-control" name="cmall_product_mobile_thumb_width" id="cmall_product_mobile_thumb_width" value="<?php echo set_value('cmall_product_mobile_thumb_width', (int) element('cmall_product_mobile_thumb_width', element('data', $view))); ?>" />px
					<span class="help-inline">상품관리 페이지 본문에 이미지 가로값 최대크기, 매우 큰 이미지를 업로드하더라도 해당 사이즈로 리사이즈가 됩니다</span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">상품관리페이지 링크 새창</label>
				<div class="col-sm-10">
					<label for="cmall_product_content_target_blank" class="checkbox-inline">
						<input type="checkbox" name="cmall_product_content_target_blank" id="cmall_product_content_target_blank" value="1" <?php echo set_checkbox('cmall_product_content_target_blank', '1', (element('cmall_product_content_target_blank', element('data', $view)) ? true : false)); ?> /> PC
					</label>
					<label for="cmall_product_mobile_content_target_blank" class="checkbox-inline">
						<input type="checkbox" name="cmall_product_mobile_content_target_blank" id="cmall_product_mobile_content_target_blank" value="1" <?php echo set_checkbox('cmall_product_mobile_content_target_blank', '1', (element('cmall_product_mobile_content_target_blank', element('data', $view)) ? true : false)); ?> /> 모바일
					</label>
					<span class="help-inline">본문 안의 링크가 무조건 새창으로 열립니다</span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">상품관리페이지 본문 안의 URL 자동 링크</label>
				<div class="col-sm-10">
					<label for="use_cmall_product_auto_url" class="checkbox-inline">
						<input type="checkbox" name="use_cmall_product_auto_url" id="use_cmall_product_auto_url" value="1" <?php echo set_checkbox('use_cmall_product_auto_url', '1', (element('use_cmall_product_auto_url', element('data', $view)) ? true : false)); ?> /> PC
					</label>
					<label for="use_cmall_product_mobile_auto_url" class="checkbox-inline">
						<input type="checkbox" name="use_cmall_product_mobile_auto_url" id="use_cmall_product_mobile_auto_url" value="1" <?php echo set_checkbox('use_cmall_product_mobile_auto_url', '1', (element('use_cmall_product_mobile_auto_url', element('data', $view)) ? true : false)); ?> /> 모바일
					</label>
					<span class="help-inline">본문 내용 중 URL은 무조건 자동으로 링크를 생성합니다</span>
				</div>
			</div>
			<hr />
			<div class="form-group">
				<label class="col-sm-2 control-label">사용후기페이지 에디터사용</label>
				<div class="col-sm-10 form-inline">
					<label for="use_cmall_product_review_dhtml" class="checkbox-inline">
					<input type="checkbox" name="use_cmall_product_review_dhtml" id="use_cmall_product_review_dhtml" value="1" <?php echo set_checkbox('use_cmall_product_review_dhtml', '1', (element('use_cmall_product_review_dhtml', element('data', $view)) ? true : false)); ?> /> 사용합니다
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">사용후기페이지 에디터종류</label>
				<div class="col-sm-10 form-inline">
					<select class="form-control" name="cmall_product_review_editor_type" id="cmall_product_review_editor_type">
						<?php echo element('cmall_product_review_editor_type_option', element('data', $view)); ?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">사용후기페이지 첨부파일 가로크기</label>
				<div class="col-sm-10">
					PC - <input type="number" class="form-control" name="cmall_product_review_thumb_width" id="cmall_product_review_thumb_width" value="<?php echo set_value('cmall_product_review_thumb_width', (int) element('cmall_product_review_thumb_width', element('data', $view))); ?>" />px,
					모바일 - <input type="number" class="form-control" name="cmall_product_review_mobile_thumb_width" id="cmall_product_review_mobile_thumb_width" value="<?php echo set_value('cmall_product_review_mobile_thumb_width', (int) element('cmall_product_review_mobile_thumb_width', element('data', $view))); ?>" />px
					<span class="help-inline">사용후기페이지 본문에 이미지 가로값 최대크기, 매우 큰 이미지를 업로드하더라도 해당 사이즈로 리사이즈가 됩니다</span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">사용후기페이지 링크 새창</label>
				<div class="col-sm-10">
					<label for="cmall_product_review_content_target_blank" class="checkbox-inline">
						<input type="checkbox" name="cmall_product_review_content_target_blank" id="cmall_product_review_content_target_blank" value="1" <?php echo set_checkbox('cmall_product_review_content_target_blank', '1', (element('cmall_product_review_content_target_blank', element('data', $view)) ? true : false)); ?> /> PC
					</label>
					<label for="cmall_product_review_mobile_content_target_blank" class="checkbox-inline">
						<input type="checkbox" name="cmall_product_review_mobile_content_target_blank" id="cmall_product_review_mobile_content_target_blank" value="1" <?php echo set_checkbox('cmall_product_review_mobile_content_target_blank', '1', (element('cmall_product_review_mobile_content_target_blank', element('data', $view)) ? true : false)); ?> /> 모바일
					</label>
					<span class="help-inline">본문 안의 링크가 무조건 새창으로 열립니다</span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">사용후기페이지 본문 안의 URL 자동 링크</label>
				<div class="col-sm-10">
					<label for="use_cmall_product_review_auto_url" class="checkbox-inline">
						<input type="checkbox" name="use_cmall_product_review_auto_url" id="use_cmall_product_review_auto_url" value="1" <?php echo set_checkbox('use_cmall_product_review_auto_url', '1', (element('use_cmall_product_review_auto_url', element('data', $view)) ? true : false)); ?> /> PC
					</label>
					<label for="use_cmall_product_review_mobile_auto_url" class="checkbox-inline">
						<input type="checkbox" name="use_cmall_product_review_mobile_auto_url" id="use_cmall_product_review_mobile_auto_url" value="1" <?php echo set_checkbox('use_cmall_product_review_mobile_auto_url', '1', (element('use_cmall_product_review_mobile_auto_url', element('data', $view)) ? true : false)); ?> /> 모바일
					</label>
					<span class="help-inline">본문 내용 중 URL은 무조건 자동으로 링크를 생성합니다</span>
				</div>
			</div>
			<hr />
			<div class="form-group">
				<label class="col-sm-2 control-label">상품문의페이지 에디터사용</label>
				<div class="col-sm-10 form-inline">
					<label for="use_cmall_product_qna_dhtml" class="checkbox-inline">
					<input type="checkbox" name="use_cmall_product_qna_dhtml" id="use_cmall_product_qna_dhtml" value="1" <?php echo set_checkbox('use_cmall_product_qna_dhtml', '1', (element('use_cmall_product_qna_dhtml', element('data', $view)) ? true : false)); ?> /> 사용합니다
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">상품문의페이지 에디터종류</label>
				<div class="col-sm-10 form-inline">
					<select class="form-control" name="cmall_product_qna_editor_type" id="cmall_product_qna_editor_type">
						<?php echo element('cmall_product_qna_editor_type_option', element('data', $view)); ?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">상품문의페이지 첨부파일 가로크기</label>
				<div class="col-sm-10">
					PC - <input type="number" class="form-control" name="cmall_product_qna_thumb_width" id="cmall_product_qna_thumb_width" value="<?php echo set_value('cmall_product_qna_thumb_width', (int) element('cmall_product_qna_thumb_width', element('data', $view))); ?>" />px,
					모바일 - <input type="number" class="form-control" name="cmall_product_qna_mobile_thumb_width" id="cmall_product_qna_mobile_thumb_width" value="<?php echo set_value('cmall_product_qna_mobile_thumb_width', (int) element('cmall_product_qna_mobile_thumb_width', element('data', $view))); ?>" />px
					<span class="help-inline">상품문의페이지 본문에 이미지 가로값 최대크기, 매우 큰 이미지를 업로드하더라도 해당 사이즈로 리사이즈가 됩니다</span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">상품문의페이지 링크 새창</label>
				<div class="col-sm-10">
					<label for="cmall_product_qna_content_target_blank" class="checkbox-inline">
						<input type="checkbox" name="cmall_product_qna_content_target_blank" id="cmall_product_qna_content_target_blank" value="1" <?php echo set_checkbox('cmall_product_qna_content_target_blank', '1', (element('cmall_product_qna_content_target_blank', element('data', $view)) ? true : false)); ?> /> PC
					</label>
					<label for="cmall_product_qna_mobile_content_target_blank" class="checkbox-inline">
						<input type="checkbox" name="cmall_product_qna_mobile_content_target_blank" id="cmall_product_qna_mobile_content_target_blank" value="1" <?php echo set_checkbox('cmall_product_qna_mobile_content_target_blank', '1', (element('cmall_product_qna_mobile_content_target_blank', element('data', $view)) ? true : false)); ?> /> 모바일
					</label>
					<span class="help-inline">본문 안의 링크가 무조건 새창으로 열립니다</span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">상품문의페이지 본문 안의 URL 자동 링크</label>
				<div class="col-sm-10">
					<label for="use_cmall_product_qna_auto_url" class="checkbox-inline">
						<input type="checkbox" name="use_cmall_product_qna_auto_url" id="use_cmall_product_qna_auto_url" value="1" <?php echo set_checkbox('use_cmall_product_qna_auto_url', '1', (element('use_cmall_product_qna_auto_url', element('data', $view)) ? true : false)); ?> /> PC
					</label>
					<label for="use_cmall_product_qna_mobile_auto_url" class="checkbox-inline">
						<input type="checkbox" name="use_cmall_product_qna_mobile_auto_url" id="use_cmall_product_qna_mobile_auto_url" value="1" <?php echo set_checkbox('use_cmall_product_qna_mobile_auto_url', '1', (element('use_cmall_product_qna_mobile_auto_url', element('data', $view)) ? true : false)); ?> /> 모바일
					</label>
					<span class="help-inline">본문 내용 중 URL은 무조건 자동으로 링크를 생성합니다</span>
				</div>
			</div>
			<div class="btn-group pull-right" role="group" aria-label="...">
				<button type="submit" class="btn btn-success btn-sm">저장하기</button>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>

<script type="text/javascript">
//<![CDATA[
var form_original_data = $('#fadminwrite').serialize();
function check_form_changed() {
	if ($('#fadminwrite').serialize() !== form_original_data) {
		if (confirm('저장하지 않은 정보가 있습니다. 저장하지 않은 상태로 이동하시겠습니까?')) {
			return true;
		} else {
			return false;
		}
	}
	return true;
}
//]]>
</script>
