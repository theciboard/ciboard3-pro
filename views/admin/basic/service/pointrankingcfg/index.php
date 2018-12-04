<div class="box">
	<div class="box-table">
		<?php
		echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
		echo show_alert_message(element('alert_message', $view), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
		$attributes = array('class' => 'form-horizontal', 'name' => 'fadminwrite', 'id' => 'fadminwrite');
		echo form_open(current_full_url(), $attributes);
		?>
			<input type="hidden" name="is_submit" value="1" />
			<div class="form-group">
				<label class="col-sm-2 control-label">포인트랭킹 기능 사용</label>
					<div class="col-sm-10">
						<label for="use_pointranking" class="checkbox-inline">
							<input type="checkbox" name="use_pointranking" id="use_pointranking" value="1" <?php echo set_checkbox('use_pointranking', '1', (element('use_pointranking', element('data', $view)) ? true : false)); ?> /> 사용합니다
						</label>
						<div style="padding-top:7px;"><span class="fa fa-trophy"></span>
							전체포인트랭킹 <a href="<?php echo site_url('pointranking'); ?>" target="_blank"><?php echo site_url('pointranking'); ?></a>
						</div>
						<div style="padding-top:7px;"><span class="fa fa-trophy"></span>
							월별포인트랭킹 <a href="<?php echo site_url('pointranking/month'); ?>" target="_blank"><?php echo site_url('pointranking/month'); ?></a>
						</div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">PC 레이아웃/스킨</label>
					<div class="col-sm-10 form-inline">
						레이아웃 -
						<select name="layout_pointranking" id="layout_pointranking" class="form-control" >
							<?php echo element('layout_pointranking_option', element('data', $view)); ?>
						</select>
						사이드바 -
						<select class="form-control" name="sidebar_pointranking" id="sidebar_pointranking">
							<option value="">기본설정따름</option>
							<option value="1" <?php echo set_select('sidebar_pointranking', '1', (element('sidebar_pointranking', element('data', $view)) === '1' ? true : false)); ?> >사용</option>
							<option value="2" <?php echo set_select('sidebar_pointranking', '2', (element('sidebar_pointranking', element('data', $view)) === '2' ? true : false)); ?> >사용하지않음</option>
						</select>
						스킨 -
						<select name="skin_pointranking" id="skin_pointranking" class="form-control" >
							<?php echo element('skin_pointranking_option', element('data', $view)); ?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">모바일 레이아웃/스킨</label>
					<div class="col-sm-10 form-inline">
						레이아웃 -
						<select name="mobile_layout_pointranking" id="mobile_layout_pointranking" class="form-control" >
							<?php echo element('mobile_layout_pointranking_option', element('data', $view)); ?>
						</select>
						사이드바 -
						<select class="form-control" name="mobile_sidebar_pointranking" id="mobile_sidebar_pointranking">
							<option value="">기본설정따름</option>
							<option value="1" <?php echo set_select('mobile_sidebar_pointranking', '1', (element('mobile_sidebar_pointranking', element('data', $view)) === '1' ? true : false)); ?> >사용</option>
							<option value="2" <?php echo set_select('mobile_sidebar_pointranking', '2', (element('mobile_sidebar_pointranking', element('data', $view)) === '2' ? true : false)); ?> >사용하지않음</option>
						</select>
						스킨 -
						<select name="mobile_skin_pointranking" id="mobile_skin_pointranking" class="form-control" >
							<?php echo element('mobile_skin_pointranking_option', element('data', $view)); ?>
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
				<label class="col-sm-2 control-label">전체포인트랭킹 메타태그</label>
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
										<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_title_pointranking" value="<?php echo set_value('site_meta_title_pointranking', element('site_meta_title_pointranking', element('data', $view))); ?>" /></div>
									</div>
									<div class="config_meta">
										<div class="start_config_meta">meta description</div>
										<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_description_pointranking" value="<?php echo set_value('site_meta_description_pointranking', element('site_meta_description_pointranking', element('data', $view))); ?>" /></div>
									</div>
									<div class="config_meta">
										<div class="start_config_meta">meta keywords</div>
										<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_keywords_pointranking" value="<?php echo set_value('site_meta_keywords_pointranking', element('site_meta_keywords_pointranking', element('data', $view))); ?>" /></div>
									</div>
									<div class="config_meta">
										<div class="start_config_meta">meta author</div>
										<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_author_pointranking" value="<?php echo set_value('site_meta_author_pointranking', element('site_meta_author_pointranking', element('data', $view))); ?>" /></div>
									</div>
									<div class="config_meta">
										<div class="start_config_meta">page name</div>
										<div class="content_config_meta"><input type="text" class="form-control" name="site_page_name_pointranking" value="<?php echo set_value('site_page_name_pointranking', element('site_page_name_pointranking', element('data', $view))); ?>" /></div>
									</div>
								</td>
								<td>{홈페이지제목}, {현재주소}, {회원아이디}, {회원닉네임}, {회원레벨}, {회원포인트}</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<label class="col-sm-2 control-label">월별포인트랭킹 메타태그</label>
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
										<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_title_pointranking_month" value="<?php echo set_value('site_meta_title_pointranking_month', element('site_meta_title_pointranking_month', element('data', $view))); ?>" /></div>
									</div>
									<div class="config_meta">
										<div class="start_config_meta">meta description</div>
										<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_description_pointranking_month" value="<?php echo set_value('site_meta_description_pointranking_month', element('site_meta_description_pointranking_month', element('data', $view))); ?>" /></div>
									</div>
									<div class="config_meta">
										<div class="start_config_meta">meta keywords</div>
										<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_keywords_pointranking_month" value="<?php echo set_value('site_meta_keywords_pointranking_month', element('site_meta_keywords_pointranking_month', element('data', $view))); ?>" /></div>
									</div>
									<div class="config_meta">
										<div class="start_config_meta">meta author</div>
										<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_author_pointranking_month" value="<?php echo set_value('site_meta_author_pointranking_month', element('site_meta_author_pointranking_month', element('data', $view))); ?>" /></div>
									</div>
									<div class="config_meta">
										<div class="start_config_meta">page name</div>
										<div class="content_config_meta"><input type="text" class="form-control" name="site_page_name_pointranking_month" value="<?php echo set_value('site_page_name_pointranking_month', element('site_page_name_pointranking_month', element('data', $view))); ?>" /></div>
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
