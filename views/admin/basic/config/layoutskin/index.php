<div class="box">
	<div class="box-header">
		<ul class="nav nav-tabs">
			<li role="presentation" class="active"><a href="<?php echo admin_url($this->pagedir); ?>" onclick="return check_form_changed();">레이아웃/스킨설정</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/metatag'); ?>" onclick="return check_form_changed();">메타태그</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/favicon'); ?>" onclick="return check_form_changed();">파비콘 등록</a></li>
		</ul>
	</div>
	<div class="box-table">
		<div class="alert alert-warning">
			<p>- 각 페이지의 레이아웃 및 스킨을 설정하는 페이지입니다.</p>
			<p>- 각 페이지에서 별도로 레이아웃이나 스킨을 설정하지 않으면, 기본설정에서 설정한 레이아웃과 스킨이 기본적으로 모든 페이지에 적용됩니다.</p>
			<p>- 각 페이지의 레이아웃을 적용하기 전에 미리보기를 통해 적용될 페이지의 디자인을 미리 확인하실 수 있습니다.</p>
		</div>
		<?php
		echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
		echo show_alert_message(element('alert_message', $view), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
		$attributes = array('class' => 'form-horizontal', 'name' => 'fadminwrite', 'id' => 'fadminwrite');
		echo form_open(current_full_url(), $attributes);
		?>
			<input type="hidden" name="is_submit" value="1" />
			<div class="table-responsive form-group form-group-sm">
				<table class="table table-bordered table-hover table-striped">
					<tbody>
						<tr>
							<th></th>
							<td>일반 레이아웃</td>
							<td>사이드바</td>
							<td>일반 스킨</td>
							<td>모바일 레이아웃</td>
							<td>사이드바</td>
							<td>모바일 스킨</td>
							<td>미리보기</td>
						</tr>
						<tr class="bg bg-warning">
							<th>기본설정</th>
							<td>
								<div class="form-inline">_layout/
									<select class="form-control" name="layout_default" id="layout_default">
										<?php echo element('layout_default_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td>
								<div class="form-inline">
									<label for="sidebar_default" class="checkbox-inline">
										<input type="checkbox" name="sidebar_default" id="sidebar_default" value="1" <?php echo set_checkbox('sidebar_default', '1', (element('sidebar_default', element('data', $view)) ? true : false)); ?> /> 사용
									</label>
								</div>
							</td>
							<td>
								<div class="form-inline">
									<select class="form-control" name="skin_default" id="skin_default">
										<?php echo element('skin_default_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td>
								<div class="form-inline">_layout/
									<select class="form-control" name="mobile_layout_default" id="mobile_layout_default">
										<?php echo element('mobile_layout_default_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td>
								<div class="form-inline">
									<label for="mobile_sidebar_default" class="checkbox-inline">
										<input type="checkbox" name="mobile_sidebar_default" id="mobile_sidebar_default" value="1" <?php echo set_checkbox('mobile_sidebar_default', '1', (element('mobile_sidebar_default', element('data', $view)) ? true : false)); ?> /> 사용
									</label>
								</div>
							</td>
							<td>
								<div class="form-inline">
									<select class="form-control" name="mobile_skin_default" id="mobile_skin_default">
										<?php echo element('mobile_skin_default_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td></td>
						</tr>
						<tr>
							<th>메인페이지</th>
							<td>
								<div class="form-inline">
									<select class="form-control" name="layout_main" id="layout_main">
										<?php echo element('layout_main_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td>
								<div class="form-inline">
									<select class="form-control" name="sidebar_main" id="sidebar_main">
										<option value="">기본설정따름</option>
										<option value="1" <?php echo set_select('sidebar_main', '1', (element('sidebar_main', element('data', $view)) === '1' ? true : false)); ?> >사용</option>
										<option value="2" <?php echo set_select('sidebar_main', '2', (element('sidebar_main', element('data', $view)) === '2' ? true : false)); ?> >사용하지않음</option>
									</select>
								</div>
							</td>
							<td>
								<div class="form-inline">main/
									<select class="form-control" name="skin_main" id="skin_main">
										<?php echo element('skin_main_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td>
								<div class="form-inline">
									<select class="form-control" name="mobile_layout_main" id="mobile_layout_main">
										<?php echo element('mobile_layout_main_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td>
								<div class="form-inline">
									<select class="form-control" name="mobile_sidebar_main" id="mobile_sidebar_main">
										<option value="">기본설정따름</option>
										<option value="1" <?php echo set_select('mobile_sidebar_main', '1', (element('mobile_sidebar_main', element('data', $view)) === '1' ? true : false)); ?> >사용</option>
										<option value="2" <?php echo set_select('mobile_sidebar_main', '2', (element('mobile_sidebar_main', element('data', $view)) === '2' ? true : false)); ?> >사용하지않음</option>
									</select>
								</div>
							</td>
							<td>
								<div class="form-inline">main/
									<select class="form-control" name="mobile_skin_main" id="mobile_skin_main">
										<?php echo element('mobile_skin_main_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td><button type="button" onClick="open_preview('main')" class="btn btn-xs btn-success">미리보기</button></td>
						</tr>
						<tr>
							<th>게시판</th>
							<td>
								<div class="form-inline">
									<select class="form-control" name="layout_board" id="layout_board">
										<?php echo element('layout_board_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td>
								<div class="form-inline">
									<select class="form-control" name="sidebar_board" id="sidebar_board">
										<option value="">기본설정따름</option>
										<option value="1" <?php echo set_select('sidebar_board', '1', (element('sidebar_board', element('data', $view)) === '1' ? true : false)); ?> >사용</option>
										<option value="2" <?php echo set_select('sidebar_board', '2', (element('sidebar_board', element('data', $view)) === '2' ? true : false)); ?> >사용하지않음</option>
									</select>
								</div>
							</td>
							<td>
								<div class="form-inline">board/
									<select class="form-control" name="skin_board" id="skin_board">
										<?php echo element('skin_board_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td>
								<div class="form-inline">
									<select class="form-control" name="mobile_layout_board" id="mobile_layout_board">
										<?php echo element('mobile_layout_board_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td>
								<div class="form-inline">
									<select class="form-control" name="mobile_sidebar_board" id="mobile_sidebar_board">
										<option value="">기본설정따름</option>
										<option value="1" <?php echo set_select('mobile_sidebar_board', '1', (element('mobile_sidebar_board', element('data', $view)) === '1' ? true : false)); ?> >사용</option>
										<option value="2" <?php echo set_select('mobile_sidebar_board', '2', (element('mobile_sidebar_board', element('data', $view)) === '2' ? true : false)); ?> >사용하지않음</option>
									</select>
								</div>
							</td>
							<td>
								<div class="form-inline">board/
									<select class="form-control" name="mobile_skin_board" id="mobile_skin_board">
										<?php echo element('mobile_skin_board_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td><button type="button" onClick="open_preview('board')" class="btn btn-xs btn-success">미리보기</button></td>
						</tr>
						<tr>
							<th>게시판그룹</th>
							<td>
								<div class="form-inline">
									<select class="form-control" name="layout_group" id="layout_group">
										<?php echo element('layout_group_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td>
								<div class="form-inline">
									<select class="form-control" name="sidebar_group" id="sidebar_group">
										<option value="">기본설정따름</option>
										<option value="1" <?php echo set_select('sidebar_group', '1', (element('sidebar_group', element('data', $view)) === '1' ? true : false)); ?> >사용</option>
										<option value="2" <?php echo set_select('sidebar_group', '2', (element('sidebar_group', element('data', $view)) === '2' ? true : false)); ?> >사용하지않음</option>
									</select>
								</div>
							</td>
							<td>
								<div class="form-inline">group/
									<select class="form-control" name="skin_group" id="skin_group">
										<?php echo element('skin_group_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td>
								<div class="form-inline">
									<select class="form-control" name="mobile_layout_group" id="mobile_layout_group">
										<?php echo element('mobile_layout_group_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td>
								<div class="form-inline">
									<select class="form-control" name="mobile_sidebar_group" id="mobile_sidebar_group">
										<option value="">기본설정따름</option>
										<option value="1" <?php echo set_select('mobile_sidebar_group', '1', (element('mobile_sidebar_group', element('data', $view)) === '1' ? true : false)); ?> >사용</option>
										<option value="2" <?php echo set_select('mobile_sidebar_group', '2', (element('mobile_sidebar_group', element('data', $view)) === '2' ? true : false)); ?> >사용하지않음</option>
									</select>
								</div>
							</td>
							<td>
								<div class="form-inline">group/
									<select class="form-control" name="mobile_skin_group" id="mobile_skin_group">
										<?php echo element('mobile_skin_group_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td><button type="button" onClick="open_preview('group')" class="btn btn-xs btn-success">미리보기</button></td>
						</tr>
						<tr>
							<th>일반문서페이지</th>
							<td>
								<div class="form-inline">
									<select class="form-control" name="layout_document" id="layout_document">
										<?php echo element('layout_document_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td>
								<div class="form-inline">
									<select class="form-control" name="sidebar_document" id="sidebar_document">
										<option value="">기본설정따름</option>
										<option value="1" <?php echo set_select('sidebar_document', '1', (element('sidebar_document', element('data', $view)) === '1' ? true : false)); ?> >사용</option>
										<option value="2" <?php echo set_select('sidebar_document', '2', (element('sidebar_document', element('data', $view)) === '2' ? true : false)); ?> >사용하지않음</option>
									</select>
								</div>
							</td>
							<td>
								<div class="form-inline">document/
									<select class="form-control" name="skin_document" id="skin_document">
										<?php echo element('skin_document_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td>
								<div class="form-inline">
									<select class="form-control" name="mobile_layout_document" id="mobile_layout_document">
										<?php echo element('mobile_layout_document_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td>
								<div class="form-inline">
									<select class="form-control" name="mobile_sidebar_document" id="mobile_sidebar_document">
										<option value="">기본설정따름</option>
										<option value="1" <?php echo set_select('mobile_sidebar_document', '1', (element('mobile_sidebar_document', element('data', $view)) === '1' ? true : false)); ?> >사용</option>
										<option value="2" <?php echo set_select('mobile_sidebar_document', '2', (element('mobile_sidebar_document', element('data', $view)) === '2' ? true : false)); ?> >사용하지않음</option>
									</select>
								</div>
							</td>
							<td>
								<div class="form-inline">document/
									<select class="form-control" name="mobile_skin_document" id="mobile_skin_document">
										<?php echo element('mobile_skin_document_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td><button type="button" onClick="open_preview('document')" class="btn btn-xs btn-success">미리보기</button></td>
						</tr>
						<tr>
							<th>FAQ</th>
							<td>
								<div class="form-inline">
									<select class="form-control" name="layout_faq" id="layout_faq">
										<?php echo element('layout_faq_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td>
								<div class="form-inline">
									<select class="form-control" name="sidebar_faq" id="sidebar_faq">
										<option value="">기본설정따름</option>
										<option value="1" <?php echo set_select('sidebar_faq', '1', (element('sidebar_faq', element('data', $view)) === '1' ? true : false)); ?> >사용</option>
										<option value="2" <?php echo set_select('sidebar_faq', '2', (element('sidebar_faq', element('data', $view)) === '2' ? true : false)); ?> >사용하지않음</option>
									</select>
								</div>
							</td>
							<td>
								<div class="form-inline">faq/
									<select class="form-control" name="skin_faq" id="skin_faq">
										<?php echo element('skin_faq_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td>
								<div class="form-inline">
									<select class="form-control" name="mobile_layout_faq" id="mobile_layout_faq">
										<?php echo element('mobile_layout_faq_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td>
								<div class="form-inline">
									<select class="form-control" name="mobile_sidebar_faq" id="mobile_sidebar_faq">
										<option value="">기본설정따름</option>
										<option value="1" <?php echo set_select('mobile_sidebar_faq', '1', (element('mobile_sidebar_faq', element('data', $view)) === '1' ? true : false)); ?> >사용</option>
										<option value="2" <?php echo set_select('mobile_sidebar_faq', '2', (element('mobile_sidebar_faq', element('data', $view)) === '2' ? true : false)); ?> >사용하지않음</option>
									</select>
								</div>
							</td>
							<td>
								<div class="form-inline">faq/
									<select class="form-control" name="mobile_skin_faq" id="mobile_skin_faq">
										<?php echo element('mobile_skin_faq_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td><button type="button" onClick="open_preview('faq')" class="btn btn-xs btn-success">미리보기</button></td>
						</tr>
						<tr>
							<th>회원가입</th>
							<td>
								<div class="form-inline">
									<select class="form-control" name="layout_register" id="layout_register">
										<?php echo element('layout_register_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td>
								<div class="form-inline">
									<select class="form-control" name="sidebar_register" id="sidebar_register">
										<option value="">기본설정따름</option>
										<option value="1" <?php echo set_select('sidebar_register', '1', (element('sidebar_register', element('data', $view)) === '1' ? true : false)); ?> >사용</option>
										<option value="2" <?php echo set_select('sidebar_register', '2', (element('sidebar_register', element('data', $view)) === '2' ? true : false)); ?> >사용하지않음</option>
									</select>
								</div>
							</td>
							<td>
								<div class="form-inline">register/
									<select class="form-control" name="skin_register" id="skin_register">
										<?php echo element('skin_register_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td>
								<div class="form-inline">
									<select class="form-control" name="mobile_layout_register" id="mobile_layout_register">
										<?php echo element('mobile_layout_register_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td>
								<div class="form-inline">
									<select class="form-control" name="mobile_sidebar_register" id="mobile_sidebar_register">
										<option value="">기본설정따름</option>
										<option value="1" <?php echo set_select('mobile_sidebar_register', '1', (element('mobile_sidebar_register', element('data', $view)) === '1' ? true : false)); ?> >사용</option>
										<option value="2" <?php echo set_select('mobile_sidebar_register', '2', (element('mobile_sidebar_register', element('data', $view)) === '2' ? true : false)); ?> >사용하지않음</option>
									</select>
								</div>
							</td>
							<td>
								<div class="form-inline">register/
									<select class="form-control" name="mobile_skin_register" id="mobile_skin_register">
										<?php echo element('mobile_skin_register_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td><button type="button" onClick="open_preview('register')" class="btn btn-xs btn-success">미리보기</button></td>
						</tr>
						<tr>
							<th>아이디패스워드찾기</th>
							<td>
								<div class="form-inline">
									<select class="form-control" name="layout_findaccount" id="layout_findaccount">
										<?php echo element('layout_findaccount_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td>
								<div class="form-inline">
									<select class="form-control" name="sidebar_findaccount" id="sidebar_findaccount">
										<option value="">기본설정따름</option>
										<option value="1" <?php echo set_select('sidebar_findaccount', '1', (element('sidebar_findaccount', element('data', $view)) === '1' ? true : false)); ?> >사용</option>
										<option value="2" <?php echo set_select('sidebar_findaccount', '2', (element('sidebar_findaccount', element('data', $view)) === '2' ? true : false)); ?> >사용하지않음</option>
									</select>
								</div>
							</td>
							<td>
								<div class="form-inline">findaccount/
									<select class="form-control" name="skin_findaccount" id="skin_findaccount">
										<?php echo element('skin_findaccount_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td>
								<div class="form-inline">
									<select class="form-control" name="mobile_layout_findaccount" id="mobile_layout_findaccount">
										<?php echo element('mobile_layout_findaccount_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td>
								<div class="form-inline">
									<select class="form-control" name="mobile_sidebar_findaccount" id="mobile_sidebar_findaccount">
										<option value="">기본설정따름</option>
										<option value="1" <?php echo set_select('mobile_sidebar_findaccount', '1', (element('mobile_sidebar_findaccount', element('data', $view)) === '1' ? true : false)); ?> >사용</option>
										<option value="2" <?php echo set_select('mobile_sidebar_findaccount', '2', (element('mobile_sidebar_findaccount', element('data', $view)) === '2' ? true : false)); ?> >사용하지않음</option>
									</select>
								</div>
							</td>
							<td>
								<div class="form-inline">findaccount/
									<select class="form-control" name="mobile_skin_findaccount" id="mobile_skin_findaccount">
										<?php echo element('mobile_skin_findaccount_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td><button type="button" onClick="open_preview('findaccount')" class="btn btn-xs btn-success">미리보기</button></td>
						</tr>
						<tr>
							<th>로그인</th>
							<td>
								<div class="form-inline">
									<select class="form-control" name="layout_login" id="layout_login">
										<?php echo element('layout_login_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td>
								<div class="form-inline">
									<select class="form-control" name="sidebar_login" id="sidebar_login">
										<option value="">기본설정따름</option>
										<option value="1" <?php echo set_select('sidebar_login', '1', (element('sidebar_login', element('data', $view)) === '1' ? true : false)); ?> >사용</option>
										<option value="2" <?php echo set_select('sidebar_login', '2', (element('sidebar_login', element('data', $view)) === '2' ? true : false)); ?> >사용하지않음</option>
									</select>
								</div>
							</td>
							<td>
								<div class="form-inline">login/
									<select class="form-control" name="skin_login" id="skin_login">
										<?php echo element('skin_login_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td>
								<div class="form-inline">
									<select class="form-control" name="mobile_layout_login" id="mobile_layout_login">
										<?php echo element('mobile_layout_login_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td>
								<div class="form-inline">
									<select class="form-control" name="mobile_sidebar_login" id="mobile_sidebar_login">
										<option value="">기본설정따름</option>
										<option value="1" <?php echo set_select('mobile_sidebar_login', '1', (element('mobile_sidebar_login', element('data', $view)) === '1' ? true : false)); ?> >사용</option>
										<option value="2" <?php echo set_select('mobile_sidebar_login', '2', (element('mobile_sidebar_login', element('data', $view)) === '2' ? true : false)); ?> >사용하지않음</option>
									</select>
								</div>
							</td>
							<td>
								<div class="form-inline">login/
									<select class="form-control" name="mobile_skin_login" id="mobile_skin_login">
										<?php echo element('mobile_skin_login_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td><button type="button" onClick="open_preview('login')" class="btn btn-xs btn-success">미리보기</button></td>
						</tr>
						<tr>
							<th>마이페이지</th>
							<td>
								<div class="form-inline">
									<select class="form-control" name="layout_mypage" id="layout_mypage">
										<?php echo element('layout_mypage_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td>
								<div class="form-inline">
									<select class="form-control" name="sidebar_mypage" id="sidebar_mypage">
										<option value="">기본설정따름</option>
										<option value="1" <?php echo set_select('sidebar_mypage', '1', (element('sidebar_mypage', element('data', $view)) === '1' ? true : false)); ?> >사용</option>
										<option value="2" <?php echo set_select('sidebar_mypage', '2', (element('sidebar_mypage', element('data', $view)) === '2' ? true : false)); ?> >사용하지않음</option>
									</select>
								</div>
							</td>
							<td>
								<div class="form-inline">mypage/
									<select class="form-control" name="skin_mypage" id="skin_mypage">
										<?php echo element('skin_mypage_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td>
								<div class="form-inline">
									<select class="form-control" name="mobile_layout_mypage" id="mobile_layout_mypage">
										<?php echo element('mobile_layout_mypage_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td>
								<div class="form-inline">
									<select class="form-control" name="mobile_sidebar_mypage" id="mobile_sidebar_mypage">
										<option value="">기본설정따름</option>
										<option value="1" <?php echo set_select('mobile_sidebar_mypage', '1', (element('mobile_sidebar_mypage', element('data', $view)) === '1' ? true : false)); ?> >사용</option>
										<option value="2" <?php echo set_select('mobile_sidebar_mypage', '2', (element('mobile_sidebar_mypage', element('data', $view)) === '2' ? true : false)); ?> >사용하지않음</option>
									</select>
								</div>
							</td>
							<td>
								<div class="form-inline">mypage/
									<select class="form-control" name="mobile_skin_mypage" id="mobile_skin_mypage">
										<?php echo element('mobile_skin_mypage_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td><button type="button" onClick="open_preview('mypage')" class="btn btn-xs btn-success">미리보기</button></td>
						</tr>
						<tr>
							<th>현재접속자</th>
							<td>
								<div class="form-inline">
									<select class="form-control" name="layout_currentvisitor" id="layout_currentvisitor">
										<?php echo element('layout_currentvisitor_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td>
								<div class="form-inline">
									<select class="form-control" name="sidebar_currentvisitor" id="sidebar_currentvisitor">
										<option value="">기본설정따름</option>
										<option value="1" <?php echo set_select('sidebar_currentvisitor', '1', (element('sidebar_currentvisitor', element('data', $view)) === '1' ? true : false)); ?> >사용</option>
										<option value="2" <?php echo set_select('sidebar_currentvisitor', '2', (element('sidebar_currentvisitor', element('data', $view)) === '2' ? true : false)); ?> >사용하지않음</option>
									</select>
								</div>
							</td>
							<td>
								<div class="form-inline">currentvisitor/
									<select class="form-control" name="skin_currentvisitor" id="skin_currentvisitor">
										<?php echo element('skin_currentvisitor_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td>
								<div class="form-inline">
									<select class="form-control" name="mobile_layout_currentvisitor" id="mobile_layout_currentvisitor">
										<?php echo element('mobile_layout_currentvisitor_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td>
								<div class="form-inline">
									<select class="form-control" name="mobile_sidebar_currentvisitor" id="mobile_sidebar_currentvisitor">
										<option value="">기본설정따름</option>
										<option value="1" <?php echo set_select('mobile_sidebar_currentvisitor', '1', (element('mobile_sidebar_currentvisitor', element('data', $view)) === '1' ? true : false)); ?> >사용</option>
										<option value="2" <?php echo set_select('mobile_sidebar_currentvisitor', '2', (element('mobile_sidebar_currentvisitor', element('data', $view)) === '2' ? true : false)); ?> >사용하지않음</option>
									</select>
								</div>
							</td>
							<td>
								<div class="form-inline">currentvisitor/
									<select class="form-control" name="mobile_skin_currentvisitor" id="mobile_skin_currentvisitor">
										<?php echo element('mobile_skin_currentvisitor_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td><button type="button" onClick="open_preview('currentvisitor')" class="btn btn-xs btn-success">미리보기</button></td>
						</tr>
						<tr>
							<th>검색페이지</th>
							<td>
								<div class="form-inline">
									<select class="form-control" name="layout_search" id="layout_search">
										<?php echo element('layout_search_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td>
								<div class="form-inline">
									<select class="form-control" name="sidebar_search" id="sidebar_search">
										<option value="">기본설정따름</option>
										<option value="1" <?php echo set_select('sidebar_search', '1', (element('sidebar_search', element('data', $view)) === '1' ? true : false)); ?> >사용</option>
										<option value="2" <?php echo set_select('sidebar_search', '2', (element('sidebar_search', element('data', $view)) === '2' ? true : false)); ?> >사용하지않음</option>
									</select>
								</div>
							</td>
							<td>
								<div class="form-inline">search/
									<select class="form-control" name="skin_search" id="skin_search">
										<?php echo element('skin_search_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td>
								<div class="form-inline">
									<select class="form-control" name="mobile_layout_search" id="mobile_layout_search">
										<?php echo element('mobile_layout_search_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td>
								<div class="form-inline">
									<select class="form-control" name="mobile_sidebar_search" id="mobile_sidebar_search">
										<option value="">기본설정따름</option>
										<option value="1" <?php echo set_select('mobile_sidebar_search', '1', (element('mobile_sidebar_search', element('data', $view)) === '1' ? true : false)); ?> >사용</option>
										<option value="2" <?php echo set_select('mobile_sidebar_search', '2', (element('mobile_sidebar_search', element('data', $view)) === '2' ? true : false)); ?> >사용하지않음</option>
									</select>
								</div>
							</td>
							<td>
								<div class="form-inline">search/
									<select class="form-control" name="mobile_skin_search" id="mobile_skin_search">
										<?php echo element('mobile_skin_search_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td><button type="button" onClick="open_preview('search')" class="btn btn-xs btn-success">미리보기</button></td>
						</tr>
						<tr>
							<th>태그페이지</th>
							<td>
								<div class="form-inline">
									<select class="form-control" name="layout_tag" id="layout_tag">
										<?php echo element('layout_tag_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td>
								<div class="form-inline">
									<select class="form-control" name="sidebar_tag" id="sidebar_tag">
										<option value="">기본설정따름</option>
										<option value="1" <?php echo set_select('sidebar_tag', '1', (element('sidebar_tag', element('data', $view)) === '1' ? true : false)); ?> >사용</option>
										<option value="2" <?php echo set_select('sidebar_tag', '2', (element('sidebar_tag', element('data', $view)) === '2' ? true : false)); ?> >사용하지않음</option>
									</select>
								</div>
							</td>
							<td>
								<div class="form-inline">tag/
									<select class="form-control" name="skin_tag" id="skin_tag">
										<?php echo element('skin_tag_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td>
								<div class="form-inline">
									<select class="form-control" name="mobile_layout_tag" id="mobile_layout_tag">
										<?php echo element('mobile_layout_tag_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td>
								<div class="form-inline">
									<select class="form-control" name="mobile_sidebar_tag" id="mobile_sidebar_tag">
										<option value="">기본설정따름</option>
										<option value="1" <?php echo set_select('mobile_sidebar_tag', '1', (element('mobile_sidebar_tag', element('data', $view)) === '1' ? true : false)); ?> >사용</option>
										<option value="2" <?php echo set_select('mobile_sidebar_tag', '2', (element('mobile_sidebar_tag', element('data', $view)) === '2' ? true : false)); ?> >사용하지않음</option>
									</select>
								</div>
							</td>
							<td>
								<div class="form-inline">tag/
									<select class="form-control" name="mobile_skin_tag" id="mobile_skin_tag">
										<?php echo element('mobile_skin_tag_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td><button type="button" onClick="open_preview('tag')" class="btn btn-xs btn-success">미리보기</button></td>
						</tr>
						<tr>
							<th>쪽지</th>
							<td>
								<div class="form-inline">
									<select class="form-control" name="layout_note" id="layout_note">
										<?php echo element('layout_note_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td></td>
							<td>
								<div class="form-inline">note/
									<select class="form-control" name="skin_note" id="skin_note">
										<?php echo element('skin_note_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td>
								<div class="form-inline">
									<select class="form-control" name="mobile_layout_note" id="mobile_layout_note">
										<?php echo element('mobile_layout_note_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td></td>
							<td>
								<div class="form-inline">note/
									<select class="form-control" name="mobile_skin_note" id="mobile_skin_note">
										<?php echo element('mobile_skin_note_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td><button type="button" onClick="open_preview('note')" class="btn btn-xs btn-success">미리보기</button></td>
						</tr>
						<tr>
							<th>프로필</th>
							<td>
								<div class="form-inline">
									<select class="form-control" name="layout_profile" id="layout_profile">
										<?php echo element('layout_profile_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td></td>
							<td>
								<div class="form-inline">profile/
									<select class="form-control" name="skin_profile" id="skin_profile">
										<?php echo element('skin_profile_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td>
								<div class="form-inline">
									<select class="form-control" name="mobile_layout_profile" id="mobile_layout_profile">
										<?php echo element('mobile_layout_profile_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td></td>
							<td>
								<div class="form-inline">profile/
									<select class="form-control" name="mobile_skin_profile" id="mobile_skin_profile">
										<?php echo element('mobile_skin_profile_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td><button type="button" onClick="open_preview('profile')" class="btn btn-xs btn-success">미리보기</button></td>
						</tr>
						<tr>
							<th>폼메일</th>
							<td>
								<div class="form-inline">
									<select class="form-control" name="layout_formmail" id="layout_formmail">
										<?php echo element('layout_formmail_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td></td>
							<td>
								<div class="form-inline">formmail/
									<select class="form-control" name="skin_formmail" id="skin_formmail">
										<?php echo element('skin_formmail_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td>
								<div class="form-inline">
									<select class="form-control" name="mobile_layout_formmail" id="mobile_layout_formmail">
										<?php echo element('mobile_layout_formmail_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td></td>
							<td>
								<div class="form-inline">formmail/
									<select class="form-control" name="mobile_skin_formmail" id="mobile_skin_formmail">
										<?php echo element('mobile_skin_formmail_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td><button type="button" onClick="open_preview('formmail')" class="btn btn-xs btn-success">미리보기</button></td>
						</tr>
						<tr>
							<th>알림페이지</th>
							<td>
								<div class="form-inline">
									<select class="form-control" name="layout_notification" id="layout_notification">
										<?php echo element('layout_notification_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td>
								<div class="form-inline">
									<select class="form-control" name="sidebar_notification" id="sidebar_notification">
										<option value="">기본설정따름</option>
										<option value="1" <?php echo set_select('sidebar_notification', '1', (element('sidebar_notification', element('data', $view)) === '1' ? true : false)); ?> >사용</option>
										<option value="2" <?php echo set_select('sidebar_notification', '2', (element('sidebar_notification', element('data', $view)) === '2' ? true : false)); ?> >사용하지않음</option>
									</select>
								</div>
							</td>
							<td>
								<div class="form-inline">notification/
									<select class="form-control" name="skin_notification" id="skin_notification">
										<?php echo element('skin_notification_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td>
								<div class="form-inline">
									<select class="form-control" name="mobile_layout_notification" id="mobile_layout_notification">
										<?php echo element('mobile_layout_notification_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td>
								<div class="form-inline">
									<select class="form-control" name="mobile_sidebar_notification" id="mobile_sidebar_notification">
										<option value="">기본설정따름</option>
										<option value="1" <?php echo set_select('mobile_sidebar_notification', '1', (element('mobile_sidebar_notification', element('data', $view)) === '1' ? true : false)); ?> >사용</option>
										<option value="2" <?php echo set_select('mobile_sidebar_notification', '2', (element('mobile_sidebar_notification', element('data', $view)) === '2' ? true : false)); ?> >사용하지않음</option>
									</select>
								</div>
							</td>
							<td>
								<div class="form-inline">notification/
									<select class="form-control" name="mobile_skin_notification" id="mobile_skin_notification">
										<?php echo element('mobile_skin_notification_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td><button type="button" onClick="open_preview('notification')" class="btn btn-xs btn-success">미리보기</button></td>
						</tr>
						<tr>
							<th>헬프툴</th>
							<td>
								<div class="form-inline">
									<select class="form-control" name="layout_helptool" id="layout_helptool">
										<?php echo element('layout_helptool_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td></td>
							<td>
								<div class="form-inline">helptool/
									<select class="form-control" name="skin_helptool" id="skin_helptool">
										<?php echo element('skin_helptool_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td>
								<div class="form-inline">
									<select class="form-control" name="mobile_layout_helptool" id="mobile_layout_helptool">
										<?php echo element('mobile_layout_helptool_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td></td>
							<td>
								<div class="form-inline">helptool/
									<select class="form-control" name="mobile_skin_helptool" id="mobile_skin_helptool">
										<?php echo element('mobile_skin_helptool_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td><button type="button" onClick="open_preview('helptool')" class="btn btn-xs btn-success">미리보기</button></td>
						</tr>
						<tr>
							<th>팝업</th>
							<td></td>
							<td></td>
							<td>
								<div class="form-inline">popup/
									<select class="form-control" name="skin_popup" id="skin_popup">
										<?php echo element('skin_popup_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td></td>
							<td></td>
							<td>
								<div class="form-inline">popup/
									<select class="form-control" name="mobile_skin_popup" id="mobile_skin_popup">
										<?php echo element('mobile_skin_popup_option', element('data', $view)); ?>
									</select>
								</div>
							</td>
							<td></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="btn-group pull-right" role="group" aria-label="...">
				<button type="submit" class="btn btn-success btn-sm">저장하기</button>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>

<script type="text/javascript">
//<![CDATA[
function open_preview(pos) {
	var prevurl = cb_admin_url +
		'/config/preview/preview/' + pos + '?' +
		'layout=' + $('#layout_' + pos).val() +
		'&sidebar=' + $('#sidebar_' + pos).val() +
		'&skin=' + $('#skin_' + pos).val() +
		'&mobile_layout=' + $('#mobile_layout_' + pos).val() +
		'&mobile_sidebar=' + $('#mobile_sidebar_' + pos).val() +
		'&mobile_skin=' + $('#mobile_skin_' + pos).val();
	window.open(prevurl);
}

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
