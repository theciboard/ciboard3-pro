<div class="box">
	<div class="box-header">
		<ul class="nav nav-tabs">
			<li role="presentation" class="active"><a href="<?php echo admin_url($this->pagedir); ?>" onclick="return check_form_changed();">일반기능</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/points'); ?>" onclick="return check_form_changed();">시간/포인트설정</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/cleanlog'); ?>" onclick="return check_form_changed();">오래된 로그삭제</a></li>
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
				<label class="col-sm-2 control-label">출석체크 기능 사용</label>
				<div class="col-sm-10">
					<label for="use_attendance" class="checkbox-inline">
						<input type="checkbox" name="use_attendance" id="use_attendance" value="1" <?php echo set_checkbox('use_attendance', '1', (element('use_attendance', element('data', $view)) ? true : false)); ?> /> 사용합니다
					</label>
					<label class=" form-inline" style="padding-top:7px;padding-left:10px;"><span class="fa fa-trophy"></span>
						<a href="<?php echo site_url('attendance'); ?>" target="_blank"><?php echo site_url('attendance'); ?></a>
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">정렬방법</label>
				<div class="col-sm-10 form-inline">
					<select class="form-control" name="attendance_order" id="attendance_order">
						<option value="asc" <?php echo set_select('attendance_order', 'asc', (element('attendance_order', element('data', $view)) === 'asc' ? true : false)); ?> >먼저 출석한 사람을 먼저 출력</option>
						<option value="desc" <?php echo set_select('attendance_order', 'desc', (element('attendance_order', element('data', $view)) === 'desc' ? true : false)); ?> >나중에 출석한 사람을 먼저 출력</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">한페이지당 출력개수</label>
				<div class="col-sm-10">
					<input type="number" class="form-control" name="attendance_page_count" id="attendance_page_count" value="<?php echo set_value('attendance_page_count', (int) element('attendance_page_count', element('data', $view))); ?>" />
					<span class="help-inline">한 페이지에 보이는 출석자 수입니다</span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">출석시간표시</label>
				<div class="col-sm-10">
					<label for="attendance_show_attend_time" class="checkbox-inline">
						<input type="checkbox" name="attendance_show_attend_time" id="attendance_show_attend_time" value="1" <?php echo set_checkbox('attendance_show_attend_time', '1', (element('attendance_show_attend_time', element('data', $view)) ? true : false)); ?> /> PC
					</label>
					<label for="attendance_mobile_show_attend_time" class="checkbox-inline">
						<input type="checkbox" name="attendance_mobile_show_attend_time" id="attendance_mobile_show_attend_time" value="1" <?php echo set_checkbox('attendance_mobile_show_attend_time', '1', (element('attendance_mobile_show_attend_time', element('data', $view)) ? true : false)); ?> /> 모바일
					</label>
					<span class="help-inline"> 출석 시간을 표시할 것인지를 결정합니다</span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">출석시간 표시 방법 (PC)</label>
				<div class="col-sm-10 form-inline">
					<select name="attendance_date_style" class="form-control select-date-style" data-display-target="attendance_date_style_manual_wrapper">
						<option value="" <?php echo set_select('attendance_date_style', '', ( ! element('attendance_date_style', element('data', $view)) ? true : false)); ?>>기본</option>
						<option value="sns" <?php echo set_select('attendance_date_style', 'sns', (element('attendance_date_style', element('data', $view)) === 'sns' ? true : false)); ?>>SNS 스타일</option>
						<option value="user" <?php echo set_select('attendance_date_style', 'user', (element('attendance_date_style', element('data', $view)) === 'user' ? true : false)); ?>>사용자정의</option>
					</select>
					<span id="attendance_date_style_manual_wrapper" style="display:<?php echo (element('attendance_date_style', element('data', $view)) === 'user') ? 'inline' : 'none'; ?>">
					&lt;&#x0003F;php echo date("<input type="text" class="form-control" name="attendance_date_style_manual" value="<?php echo set_value('attendance_date_style_manual', element('attendance_date_style_manual', element('data', $view))); ?>" />", $time); &#x0003F;&gt;
					</span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">출석시간 표시 방법 (모바일)</label>
				<div class="col-sm-10 form-inline">
					<select name="attendance_mobile_date_style" class="form-control select-date-style" data-display-target="attendance_mobile_date_style_manual_wrapper">
						<option value="" <?php echo set_select('attendance_mobile_date_style', '', ( ! element('attendance_mobile_date_style', element('data', $view)) ? true : false)); ?>>기본</option>
						<option value="sns" <?php echo set_select('attendance_mobile_date_style', 'sns', (element('attendance_mobile_date_style', element('data', $view)) === 'sns' ? true : false)); ?>>SNS 스타일</option>
						<option value="user" <?php echo set_select('attendance_mobile_date_style', 'user', (element('attendance_mobile_date_style', element('data', $view)) === 'user' ? true : false)); ?>>사용자정의</option>
					</select>
					<span id="attendance_mobile_date_style_manual_wrapper" style="display:<?php echo (element('attendance_mobile_date_style', element('data', $view)) === 'user') ? 'inline' : 'none'; ?>">
					&lt;&#x0003F;php echo date("<input type="text" class="form-control" name="attendance_mobile_date_style_manual" value="<?php echo set_value('attendance_mobile_date_style_manual', element('attendance_mobile_date_style_manual', element('data', $view))); ?>" />", $time); &#x0003F;&gt;
					</span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">인사말 길이</label>
				<div class="col-sm-10">
					<input type="number" class="form-control" name="attendance_memo_length" id="attendance_memo_length" value="<?php echo set_value('attendance_memo_length', (int) element('attendance_memo_length', element('data', $view))); ?>" /> 글자 이내
				</div>
			</div>
			<hr />
			<div class="form-group">
				<label class="col-sm-2 control-label">PC 레이아웃/스킨</label>
				<div class="col-sm-10 form-inline">
					레이아웃 -
					<select name="layout_attendance" id="layout_attendance" class="form-control" >
						<?php echo element('layout_attendance_option', element('data', $view)); ?>
					</select>
					사이드바 -
					<select class="form-control" name="sidebar_attendance" id="sidebar_attendance">
						<option value="">기본설정따름</option>
						<option value="1" <?php echo set_select('sidebar_attendance', '1', (element('sidebar_attendance', element('data', $view)) === '1' ? true : false)); ?> >사용</option>
						<option value="2" <?php echo set_select('sidebar_attendance', '2', (element('sidebar_attendance', element('data', $view)) === '2' ? true : false)); ?> >사용하지않음</option>
					</select>
					스킨 -
					<select name="skin_attendance" id="skin_attendance" class="form-control" >
						<?php echo element('skin_attendance_option', element('data', $view)); ?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">모바일 레이아웃/스킨</label>
				<div class="col-sm-10 form-inline">
					레이아웃 -
					<select name="mobile_layout_attendance" id="mobile_layout_attendance" class="form-control" >
						<?php echo element('mobile_layout_attendance_option', element('data', $view)); ?>
					</select>
					사이드바 -
					<select class="form-control" name="mobile_sidebar_attendance" id="mobile_sidebar_attendance">
						<option value="">기본설정따름</option>
						<option value="1" <?php echo set_select('mobile_sidebar_attendance', '1', (element('mobile_sidebar_attendance', element('data', $view)) === '1' ? true : false)); ?> >사용</option>
						<option value="2" <?php echo set_select('mobile_sidebar_attendance', '2', (element('mobile_sidebar_attendance', element('data', $view)) === '2' ? true : false)); ?> >사용하지않음</option>
					</select>
					스킨 -
					<select name="mobile_skin_attendance" id="mobile_skin_attendance" class="form-control" >
						<?php echo element('mobile_skin_attendance_option', element('data', $view)); ?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">메타태그 설정</label>
				<div class="col-sm-10">
					<div class="alert alert-success">
						<p>공통적으로 사용할 수 있는 치환가능변수 : <strong>{홈페이지제목}</strong>, <strong>{현재주소}</strong>, <strong>{회원아이디}</strong>, <strong>{회원닉네임}</strong>, <strong>{회원레벨}</strong>, <strong>{회원포인트}</strong></p>
						<hr />
						<p><strong>Title : </strong> &lt;title&gt;여기에 입력하신 내용이 들어갑니다&lt;/title&gt;</p>
						<p><strong>meta description : </strong> &lt;meta name=&quot;description&quot; content=&quot;여기에 입력하신 내용이 들어갑니다&quot;&gt;</p>
						<p><strong>meta keywords : </strong> &lt;meta name=&quot;keywords&quot; content=&quot;여기에 입력하신 내용이 들어갑니다&quot;&gt;</p>
						<p><strong>meta author : </strong> &lt;meta name=&quot;author&quot; content=&quot;여기에 입력하신 내용이 들어갑니다&quot;&gt;</p>
						<p><strong>page name : </strong> 현재접속자 페이지에 보입니다</p>
					</div>
				</div>
			</div>
			<label class="col-sm-2 control-label">출석체크 메타태그</label>
			<div class="col-sm-10">
				<div class="table-responsive form-group form-group-sm">
					<table class="table table-bordered table-hover table-striped">
						<tbody>
							<tr>
								<td>내용</td>
								<td class="px200">치환가능변수</td>
							</tr>
							<tr class="bg bg-warning">
								<td>
									<div class="config_meta">
										<div class="start_config_meta">Title</div>
										<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_title_attendance" value="<?php echo set_value('site_meta_title_attendance', element('site_meta_title_attendance', element('data', $view))); ?>" /></div>
									</div>
									<div class="config_meta">
										<div class="start_config_meta">meta description</div>
										<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_description_attendance" value="<?php echo set_value('site_meta_description_attendance', element('site_meta_description_attendance', element('data', $view))); ?>" /></div>
									</div>
									<div class="config_meta">
										<div class="start_config_meta">meta keywords</div>
										<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_keywords_attendance" value="<?php echo set_value('site_meta_keywords_attendance', element('site_meta_keywords_attendance', element('data', $view))); ?>" /></div>
									</div>
									<div class="config_meta">
										<div class="start_config_meta">meta author</div>
										<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_author_attendance" value="<?php echo set_value('site_meta_author_attendance', element('site_meta_author_attendance', element('data', $view))); ?>" /></div>
									</div>
									<div class="config_meta">
										<div class="start_config_meta">page name</div>
										<div class="content_config_meta"><input type="text" class="form-control" name="site_page_name_attendance" value="<?php echo set_value('site_page_name_attendance', element('site_page_name_attendance', element('data', $view))); ?>" /></div>
									</div>
								</td>
								<td>{홈페이지제목}, {현재주소}, {회원아이디}, {회원닉네임}, {회원레벨}, {회원포인트}</td>
							</tr>
						</tbody>
					</table>
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
$(document).on('change', "select.select-date-style", function() {
	if ($(this).val() === 'user') {
		$('#' + $(this).attr('data-display-target')).css('display', 'inline');
	} else {
		$('#' + $(this).attr('data-display-target')).css('display', 'none');
	}
});

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
