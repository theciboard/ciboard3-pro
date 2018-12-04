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
				<label class="col-sm-2 control-label">레벨업 기능 사용</label>
				<div class="col-sm-10">
					<label for="use_levelup" class="checkbox-inline">
						<input type="checkbox" name="use_levelup" id="use_levelup" value="1" <?php echo set_checkbox('use_levelup', '1', (element('use_levelup', element('data', $view)) ? true : false)); ?> /> 사용합니다
					</label>
					<label class=" form-inline" style="padding-top:7px;padding-left:10px;"><span class="fa fa-level-up"></span>
						<a href="<?php echo site_url('levelup'); ?>" target="_blank"><?php echo site_url('levelup'); ?></a>
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">PC 레이아웃/스킨</label>
				<div class="col-sm-10 form-inline">
					레이아웃 -
					<select name="layout_levelup" id="layout_levelup" class="form-control" >
						<?php echo element('layout_levelup_option', element('data', $view)); ?>
					</select>
					사이드바 -
					<select class="form-control" name="sidebar_levelup" id="sidebar_levelup">
						<option value="">기본설정따름</option>
						<option value="1" <?php echo set_select('sidebar_levelup', '1', (element('sidebar_levelup', element('data', $view)) === '1' ? true : false)); ?> >사용</option>
						<option value="2" <?php echo set_select('sidebar_levelup', '2', (element('sidebar_levelup', element('data', $view)) === '2' ? true : false)); ?> >사용하지않음</option>
					</select>
					스킨 -
					<select name="skin_levelup" id="skin_levelup" class="form-control" >
						<?php echo element('skin_levelup_option', element('data', $view)); ?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">모바일 레이아웃/스킨</label>
				<div class="col-sm-10 form-inline">
					레이아웃 -
					<select name="mobile_layout_levelup" id="mobile_layout_levelup" class="form-control" >
						<?php echo element('mobile_layout_levelup_option', element('data', $view)); ?>
					</select>
					사이드바 -
					<select class="form-control" name="mobile_sidebar_levelup" id="mobile_sidebar_levelup">
						<option value="">기본설정따름</option>
						<option value="1" <?php echo set_select('mobile_sidebar_levelup', '1', (element('mobile_sidebar_levelup', element('data', $view)) === '1' ? true : false)); ?> >사용</option>
						<option value="2" <?php echo set_select('mobile_sidebar_levelup', '2', (element('mobile_sidebar_levelup', element('data', $view)) === '2' ? true : false)); ?> >사용하지않음</option>
					</select>
					스킨 -
					<select name="mobile_skin_levelup" id="mobile_skin_levelup" class="form-control" >
						<?php echo element('mobile_skin_levelup_option', element('data', $view)); ?>
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
			<div class="form-group">
				<label class="col-sm-2 control-label">레벨업페이지 메타태그</label>
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
											<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_title_levelup" value="<?php echo set_value('site_meta_title_levelup', element('site_meta_title_levelup', element('data', $view))); ?>" /></div>
										</div>
										<div class="config_meta">
											<div class="start_config_meta">meta description</div>
											<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_description_levelup" value="<?php echo set_value('site_meta_description_levelup', element('site_meta_description_levelup', element('data', $view))); ?>" /></div>
										</div>
										<div class="config_meta">
											<div class="start_config_meta">meta keywords</div>
											<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_keywords_levelup" value="<?php echo set_value('site_meta_keywords_levelup', element('site_meta_keywords_levelup', element('data', $view))); ?>" /></div>
										</div>
										<div class="config_meta">
											<div class="start_config_meta">meta author</div>
											<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_author_levelup" value="<?php echo set_value('site_meta_author_levelup', element('site_meta_author_levelup', element('data', $view))); ?>" /></div>
										</div>
										<div class="config_meta">
											<div class="start_config_meta">page name</div>
											<div class="content_config_meta"><input type="text" class="form-control" name="site_page_name_levelup" value="<?php echo set_value('site_page_name_levelup', element('site_page_name_levelup', element('data', $view))); ?>" /></div>
										</div>
									</td>
									<td>{홈페이지제목}, {현재주소}, {회원아이디}, {회원닉네임}, {회원레벨}, {회원포인트}</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<div class="form-group">
				<div class="table-responsive">
					<table class="table table-hover table-striped table-bordered">
						<thead>
							<tr>
								<th>레벨</th>
								<th><label for="chkall"><input type="checkbox" name="chkall" id="chkall" /> 사용</label></th>
								<th>가입일</th>
								<th>보유포인트</th>
								<th>차감포인트</th>
								<th>글작성개수</th>
								<th>댓글작성개수</th>
							</tr>
						</thead>
						<tbody>
						<?php
						$max_level = element('max_level', element('data', $view));
						$levelupconfig = json_decode(element('levelupconfig', element('data', $view)), true);
						if ($max_level) {
							for ($i = 2; $i <= $max_level; $i++) {
						?>
							<tr>
								<td><?php echo $i-1; ?> -&gt; <?php echo $i; ?> </td>
								<td><label for="chk_<?php echo $i; ?>"><input type="checkbox" name="chk[]" id="chk_<?php echo $i; ?>" class="list-chkbox" value="<?php echo $i; ?>" <?php echo ($levelupconfig && is_array(element('use', $levelupconfig)) && in_array($i, element('use', $levelupconfig))) ? 'checked="checked"' : '';?> /> 사용</label></td>
								<td><input type="number" name="register[<?php echo $i; ?>]" class="form-control" value="<?php echo set_value('register[' . $i . ']', (int) element($i, element('register', $levelupconfig))); ?>" />일</td>
								<td><input type="number" name="point_required[<?php echo $i; ?>]" class="form-control" value="<?php echo set_value('point_required[' . $i . ']', (int) element($i, element('point_required', $levelupconfig))); ?>" />점</td>
								<td><input type="number" name="point_use[<?php echo $i; ?>]" class="form-control" value="<?php echo set_value('point_use[' . $i . ']', (int) element($i, element('point_use', $levelupconfig))); ?>" />점</td>
								<td><input type="number" name="post_num[<?php echo $i; ?>]" class="form-control" value="<?php echo set_value('post_num[' . $i . ']', (int) element($i, element('post_num', $levelupconfig))); ?>" />개</td>
								<td><input type="number" name="comment_num[<?php echo $i; ?>]" class="form-control" value="<?php echo set_value('comment_num[' . $i . ']', (int) element($i, element('comment_num', $levelupconfig))); ?>" />개</td>
							</tr>
						<?php
							}
						}
						?>
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
