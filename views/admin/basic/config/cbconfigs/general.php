<div class="box">
	<div class="box-header">
		<ul class="nav nav-tabs">
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir); ?>" onclick="return check_form_changed();">기본정보</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/access'); ?>" onclick="return check_form_changed();">접근기능</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/point'); ?>" onclick="return check_form_changed();">포인트기능</a></li>
			<li role="presentation" class="active"><a href="<?php echo admin_url($this->pagedir . '/general'); ?>" onclick="return check_form_changed();">일반기능 / 에디터</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/note'); ?>" onclick="return check_form_changed();">쪽지기능</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/notification'); ?>" onclick="return check_form_changed();">알림기능</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/company'); ?>" onclick="return check_form_changed();">회사정보</a></li>
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
				<label class="col-sm-2 control-label">사이드뷰 사용</label>
				<div class="col-sm-10">
					<label for="use_sideview" class="checkbox-inline">
					<input type="checkbox" name="use_sideview" id="use_sideview" value="1" <?php echo set_checkbox('use_sideview', '1', (element('use_sideview', element('data', $view)) ? true : false)); ?> /> PC - 사용합니다
					</label>
					<label for="use_mobile_sideview" class="checkbox-inline">
					<input type="checkbox" name="use_mobile_sideview" id="use_mobile_sideview" value="1" <?php echo set_checkbox('use_mobile_sideview', '1', (element('use_mobile_sideview', element('data', $view)) ? true : false)); ?> /> 모바일 - 사용합니다
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">사이드뷰 이메일 사용</label>
				<div class="col-sm-10">
					<label for="use_sideview_email" class="checkbox-inline">
					<input type="checkbox" name="use_sideview_email" id="use_sideview_email" value="1" <?php echo set_checkbox('use_sideview_email', '1', (element('use_sideview_email', element('data', $view)) ? true : false)); ?> /> PC - 사용합니다
					</label>
					<label for="use_mobile_sideview_email" class="checkbox-inline">
					<input type="checkbox" name="use_mobile_sideview_email" id="use_mobile_sideview_email" value="1" <?php echo set_checkbox('use_mobile_sideview_email', '1', (element('use_mobile_sideview_email', element('data', $view)) ? true : false)); ?> /> 모바일 - 사용합니다
					</label>
				</div>
			</div>
			<hr />
			<div class="form-group">
				<label class="col-sm-2 control-label">게시글 에디터 종류</label>
				<div class="col-sm-10 form-inline">
					<select class="form-control" name="post_editor_type" id="post_editor_type">
						<?php echo element('post_editor_type_option', element('data', $view)); ?>
					</select>
				</div>
			</div>
			<hr />
			<div class="form-group">
				<label class="col-sm-2 control-label">일반문서 DHTML 기능</label>
				<div class="col-sm-10">
					<label for="use_document_dhtml" class="checkbox-inline">
						<input type="checkbox" name="use_document_dhtml" id="use_document_dhtml" value="1" <?php echo set_checkbox('use_document_dhtml', '1', (element('use_document_dhtml', element('data', $view)) ? true : false)); ?> /> 사용합니다
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">일반문서 에디터 종류</label>
				<div class="col-sm-10 form-inline">
					<select class="form-control" name="document_editor_type" id="document_editor_type">
						<?php echo element('document_editor_type_option', element('data', $view)); ?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">일반문서 첨부파일 가로크기</label>
				<div class="col-sm-10">
					PC - <input type="number" class="form-control" name="document_thumb_width" id="document_thumb_width" value="<?php echo set_value('document_thumb_width', (int) element('document_thumb_width', element('data', $view))); ?>" />px,
					모바일 - <input type="number" class="form-control" name="document_mobile_thumb_width" id="document_mobile_thumb_width" value="<?php echo set_value('document_mobile_thumb_width', (int) element('document_mobile_thumb_width', element('data', $view))); ?>" />px
					<span class="help-inline">일반문서 본문에 이미지 가로값 최대크기, 매우 큰 이미지를 업로드하더라도 해당 사이즈로 리사이즈가 됩니다</span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">일반문서 링크 새창</label>
				<div class="col-sm-10">
					<label for="document_content_target_blank" class="checkbox-inline">
						<input type="checkbox" name="document_content_target_blank" id="document_content_target_blank" value="1" <?php echo set_checkbox('document_content_target_blank', '1', (element('document_content_target_blank', element('data', $view)) ? true : false)); ?> /> PC
					</label>
					<label for="document_mobile_content_target_blank" class="checkbox-inline">
						<input type="checkbox" name="document_mobile_content_target_blank" id="document_mobile_content_target_blank" value="1" <?php echo set_checkbox('document_mobile_content_target_blank', '1', (element('document_mobile_content_target_blank', element('data', $view)) ? true : false)); ?> /> 모바일
					</label>
					<span class="help-inline">본문 안의 링크가 무조건 새창으로 열립니다</span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">일반문서 본문 안의 URL 자동 링크</label>
				<div class="col-sm-10">
					<label for="use_document_auto_url" class="checkbox-inline">
						<input type="checkbox" name="use_document_auto_url" id="use_document_auto_url" value="1" <?php echo set_checkbox('use_document_auto_url', '1', (element('use_document_auto_url', element('data', $view)) ? true : false)); ?> /> PC
						</label>
					<label for="use_document_mobile_auto_url" class="checkbox-inline">
						<input type="checkbox" name="use_document_mobile_auto_url" id="use_document_mobile_auto_url" value="1" <?php echo set_checkbox('use_document_mobile_auto_url', '1', (element('use_document_mobile_auto_url', element('data', $view)) ? true : false)); ?> /> 모바일
					</label>
					<span class="help-inline">본문 내용 중 URL은 무조건 자동으로 링크를 생성합니다</span>
				</div>
			</div>
			<hr />
			<div class="form-group">
				<label class="col-sm-2 control-label">FAQ DHTML 기능</label>
				<div class="col-sm-10">
					<label for="use_faq_dhtml" class="checkbox-inline">
						<input type="checkbox" name="use_faq_dhtml" id="use_faq_dhtml" value="1" <?php echo set_checkbox('use_faq_dhtml', '1', (element('use_faq_dhtml', element('data', $view)) ? true : false)); ?> /> 사용합니다
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">FAQ 에디터 종류</label>
				<div class="col-sm-10 form-inline">
					<select class="form-control" name="faq_editor_type" id="faq_editor_type">
						<?php echo element('faq_editor_type_option', element('data', $view)); ?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">FAQ 첨부파일 가로크기</label>
				<div class="col-sm-10">
					PC - <input type="number" class="form-control" name="faq_thumb_width" id="faq_thumb_width" value="<?php echo set_value('faq_thumb_width', (int) element('faq_thumb_width', element('data', $view))); ?>" />px,
					모바일 - <input type="number" class="form-control" name="faq_mobile_thumb_width" id="faq_mobile_thumb_width" value="<?php echo set_value('faq_mobile_thumb_width', (int) element('faq_mobile_thumb_width', element('data', $view))); ?>" />px
					<span class="help-inline">FAQ 본문에 이미지 가로값 최대크기, 매우 큰 이미지를 업로드하더라도 해당 사이즈로 리사이즈가 됩니다</span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">FAQ 링크 새창</label>
				<div class="col-sm-10">
					<label for="faq_content_target_blank" class="checkbox-inline">
						<input type="checkbox" name="faq_content_target_blank" id="faq_content_target_blank" value="1" <?php echo set_checkbox('faq_content_target_blank', '1', (element('faq_content_target_blank', element('data', $view)) ? true : false)); ?> /> PC
					</label>
					<label for="faq_mobile_content_target_blank" class="checkbox-inline">
						<input type="checkbox" name="faq_mobile_content_target_blank" id="faq_mobile_content_target_blank" value="1" <?php echo set_checkbox('faq_mobile_content_target_blank', '1', (element('faq_mobile_content_target_blank', element('data', $view)) ? true : false)); ?> /> 모바일
					</label>
					<span class="help-inline">본문 안의 링크가 무조건 새창으로 열립니다</span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">FAQ 본문 안의 URL 자동 링크</label>
				<div class="col-sm-10">
					<label for="use_faq_auto_url" class="checkbox-inline">
						<input type="checkbox" name="use_faq_auto_url" id="use_faq_auto_url" value="1" <?php echo set_checkbox('use_faq_auto_url', '1', (element('use_faq_auto_url', element('data', $view)) ? true : false)); ?> /> PC
						</label>
					<label for="use_faq_mobile_auto_url" class="checkbox-inline">
						<input type="checkbox" name="use_faq_mobile_auto_url" id="use_faq_mobile_auto_url" value="1" <?php echo set_checkbox('use_faq_mobile_auto_url', '1', (element('use_faq_mobile_auto_url', element('data', $view)) ? true : false)); ?> /> 모바일
					</label>
					<span class="help-inline">본문 내용 중 URL은 무조건 자동으로 링크를 생성합니다</span>
				</div>
			</div>
			<hr />
			<div class="form-group">
				<label class="col-sm-2 control-label">팝업 DHTML 기능</label>
				<div class="col-sm-10">
					<label for="use_popup_dhtml" class="checkbox-inline">
						<input type="checkbox" name="use_popup_dhtml" id="use_popup_dhtml" value="1" <?php echo set_checkbox('use_popup_dhtml', '1', (element('use_popup_dhtml', element('data', $view)) ? true : false)); ?> /> 사용합니다
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">팝업 에디터 종류</label>
				<div class="col-sm-10 form-inline">
					<select class="form-control" name="popup_editor_type" id="popup_editor_type">
						<?php echo element('popup_editor_type_option', element('data', $view)); ?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">팝업 첨부파일 가로크기</label>
				<div class="col-sm-10">
					PC - <input type="number" class="form-control" name="popup_thumb_width" id="popup_thumb_width" value="<?php echo set_value('popup_thumb_width', (int) element('popup_thumb_width', element('data', $view))); ?>" />px,
					모바일 - <input type="number" class="form-control" name="popup_mobile_thumb_width" id="popup_mobile_thumb_width" value="<?php echo set_value('popup_mobile_thumb_width', (int) element('popup_mobile_thumb_width', element('data', $view))); ?>" />px
					<span class="help-inline">팝업 본문에 이미지 가로값 최대크기, 매우 큰 이미지를 업로드하더라도 해당 사이즈로 리사이즈가 됩니다</span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">팝업 링크 새창</label>
				<div class="col-sm-10">
					<label for="popup_content_target_blank" class="checkbox-inline">
						<input type="checkbox" name="popup_content_target_blank" id="popup_content_target_blank" value="1" <?php echo set_checkbox('popup_content_target_blank', '1', (element('popup_content_target_blank', element('data', $view)) ? true : false)); ?> /> PC
					</label>
					<label for="popup_mobile_content_target_blank" class="checkbox-inline">
						<input type="checkbox" name="popup_mobile_content_target_blank" id="popup_mobile_content_target_blank" value="1" <?php echo set_checkbox('popup_mobile_content_target_blank', '1', (element('popup_mobile_content_target_blank', element('data', $view)) ? true : false)); ?> /> 모바일
					</label>
					<span class="help-inline">본문 안의 링크가 무조건 새창으로 열립니다</span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">팝업 본문 안의 URL 자동 링크</label>
				<div class="col-sm-10">
					<label for="use_popup_auto_url" class="checkbox-inline">
						<input type="checkbox" name="use_popup_auto_url" id="use_popup_auto_url" value="1" <?php echo set_checkbox('use_popup_auto_url', '1', (element('use_popup_auto_url', element('data', $view)) ? true : false)); ?> /> PC
						</label>
					<label for="use_popup_mobile_auto_url" class="checkbox-inline">
						<input type="checkbox" name="use_popup_mobile_auto_url" id="use_popup_mobile_auto_url" value="1" <?php echo set_checkbox('use_popup_mobile_auto_url', '1', (element('use_popup_mobile_auto_url', element('data', $view)) ? true : false)); ?> /> 모바일
					</label>
					<span class="help-inline">본문 내용 중 URL은 무조건 자동으로 링크를 생성합니다</span>
				</div>
			</div>
			<hr />
			<div class="form-group">
				<label class="col-sm-2 control-label">폼메일 DHTML 기능</label>
				<div class="col-sm-10">
					<label for="use_formmail_dhtml" class="checkbox-inline">
					<input type="checkbox" name="use_formmail_dhtml" id="use_formmail_dhtml" value="1" <?php echo set_checkbox('use_formmail_dhtml', '1', (element('use_formmail_dhtml', element('data', $view)) ? true : false)); ?> /> 사용합니다
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">폼메일 에디터 종류</label>
				<div class="col-sm-10 form-inline">
					<select class="form-control" name="formmail_editor_type" id="formmail_editor_type">
						<?php echo element('formmail_editor_type_option', element('data', $view)); ?>
					</select>
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
