<div class="box">
	<div class="box-header">
		<ul class="nav nav-tabs">
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir); ?>" onclick="return check_form_changed();">기본설정</a></li>
			<li role="presentation" class="active"><a href="<?php echo admin_url($this->pagedir . '/layout'); ?>" onclick="return check_form_changed();">레이아웃/메타태그</a></li>
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
				<label class="col-sm-2 control-label">PC 레이아웃/스킨</label>
				<div class="col-sm-10 form-inline">
					레이아웃 -
					<select name="layout_deposit" id="layout_deposit" class="form-control" >
						<?php echo element('layout_deposit_option', element('data', $view)); ?>
					</select>
					사이드바 -
					<select class="form-control" name="sidebar_deposit" id="sidebar_deposit">
						<option value="">기본설정따름</option>
						<option value="1" <?php echo set_select('sidebar_deposit', '1', (element('sidebar_deposit', element('data', $view)) === '1' ? true : false)); ?> >사용</option>
						<option value="2" <?php echo set_select('sidebar_deposit', '2', (element('sidebar_deposit', element('data', $view)) === '2' ? true : false)); ?> >사용하지않음</option>
					</select>
					스킨 -
					<select name="skin_deposit" id="skin_deposit" class="form-control" >
						<?php echo element('skin_deposit_option', element('data', $view)); ?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">모바일 레이아웃/스킨</label>
				<div class="col-sm-10 form-inline">
					레이아웃 -
					<select name="mobile_layout_deposit" id="mobile_layout_deposit" class="form-control" >
						<?php echo element('mobile_layout_deposit_option', element('data', $view)); ?>
					</select>
					사이드바 -
					<select class="form-control" name="mobile_sidebar_deposit" id="mobile_sidebar_deposit">
						<option value="">기본설정따름</option>
						<option value="1" <?php echo set_select('mobile_sidebar_deposit', '1', (element('mobile_sidebar_deposit', element('data', $view)) === '1' ? true : false)); ?> >사용</option>
						<option value="2" <?php echo set_select('mobile_sidebar_deposit', '2', (element('mobile_sidebar_deposit', element('data', $view)) === '2' ? true : false)); ?> >사용하지않음</option>
					</select>
					스킨 -
					<select name="mobile_skin_deposit" id="mobile_skin_deposit" class="form-control" >
						<?php echo element('mobile_skin_deposit_option', element('data', $view)); ?>
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
			<label class="col-sm-2 control-label">예치금 페이지 메타태그</label>
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
										<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_title_deposit" value="<?php echo set_value('site_meta_title_deposit', element('site_meta_title_deposit', element('data', $view))); ?>" /></div>
									</div>
									<div class="config_meta">
										<div class="start_config_meta">meta description</div>
										<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_description_deposit" value="<?php echo set_value('site_meta_description_deposit', element('site_meta_description_deposit', element('data', $view))); ?>" /></div>
									</div>
									<div class="config_meta">
										<div class="start_config_meta">meta keywords</div>
										<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_keywords_deposit" value="<?php echo set_value('site_meta_keywords_deposit', element('site_meta_keywords_deposit', element('data', $view))); ?>" /></div>
									</div>
									<div class="config_meta">
										<div class="start_config_meta">meta author</div>
										<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_author_deposit" value="<?php echo set_value('site_meta_author_deposit', element('site_meta_author_deposit', element('data', $view))); ?>" /></div>
									</div>
									<div class="config_meta">
										<div class="start_config_meta">page name</div>
										<div class="content_config_meta"><input type="text" class="form-control" name="site_page_name_deposit" value="<?php echo set_value('site_page_name_deposit', element('site_page_name_deposit', element('data', $view))); ?>" /></div>
									</div>
								</td>
								<td>{홈페이지제목}, {현재주소}, {회원아이디}, {회원닉네임}, {회원레벨}, {회원포인트}</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<label class="col-sm-2 control-label">예치금 페이지 &gt; 나의사용내역 메타태그</label>
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
										<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_title_deposit_mylist" value="<?php echo set_value('site_meta_title_deposit_mylist', element('site_meta_title_deposit_mylist', element('data', $view))); ?>" /></div>
									</div>
									<div class="config_meta">
										<div class="start_config_meta">meta description</div>
										<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_description_deposit_mylist" value="<?php echo set_value('site_meta_description_deposit_mylist', element('site_meta_description_deposit_mylist', element('data', $view))); ?>" /></div>
									</div>
									<div class="config_meta">
										<div class="start_config_meta">meta keywords</div>
										<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_keywords_deposit_mylist" value="<?php echo set_value('site_meta_keywords_deposit_mylist', element('site_meta_keywords_deposit_mylist', element('data', $view))); ?>" /></div>
									</div>
									<div class="config_meta">
										<div class="start_config_meta">meta author</div>
										<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_author_deposit_mylist" value="<?php echo set_value('site_meta_author_deposit_mylist', element('site_meta_author_deposit_mylist', element('data', $view))); ?>" /></div>
									</div>
									<div class="config_meta">
										<div class="start_config_meta">page name</div>
										<div class="content_config_meta"><input type="text" class="form-control" name="site_page_name_deposit_mylist" value="<?php echo set_value('site_page_name_deposit_mylist', element('site_page_name_deposit_mylist', element('data', $view))); ?>" /></div>
									</div>
								</td>
								<td>{홈페이지제목}, {현재주소}, {회원아이디}, {회원닉네임}, {회원레벨}, {회원포인트}</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<label class="col-sm-2 control-label">예치금 페이지 &gt; 결제후결과페이지 메타태그</label>
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
										<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_title_deposit_result" value="<?php echo set_value('site_meta_title_deposit_result', element('site_meta_title_deposit_result', element('data', $view))); ?>" /></div>
									</div>
									<div class="config_meta">
										<div class="start_config_meta">meta description</div>
										<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_description_deposit_result" value="<?php echo set_value('site_meta_description_deposit_result', element('site_meta_description_deposit_result', element('data', $view))); ?>" /></div>
									</div>
									<div class="config_meta">
										<div class="start_config_meta">meta keywords</div>
										<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_keywords_deposit_result" value="<?php echo set_value('site_meta_keywords_deposit_result', element('site_meta_keywords_deposit_result', element('data', $view))); ?>" /></div>
									</div>
									<div class="config_meta">
										<div class="start_config_meta">meta author</div>
										<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_author_deposit_result" value="<?php echo set_value('site_meta_author_deposit_result', element('site_meta_author_deposit_result', element('data', $view))); ?>" /></div>
									</div>
									<div class="config_meta">
										<div class="start_config_meta">page name</div>
										<div class="content_config_meta"><input type="text" class="form-control" name="site_page_name_deposit_result" value="<?php echo set_value('site_page_name_deposit_result', element('site_page_name_deposit_result', element('data', $view))); ?>" /></div>
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
