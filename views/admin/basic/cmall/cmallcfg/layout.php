<div class="box">
	<div class="box-header">
		<ul class="nav nav-tabs">
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir); ?>" onclick="return check_form_changed();">기본설정</a></li>
			<li role="presentation" class="active"><a href="<?php echo admin_url($this->pagedir . '/layout'); ?>" onclick="return check_form_changed();">레이아웃/메타태그</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/access'); ?>" onclick="return check_form_changed();">권한관리</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/editor'); ?>" onclick="return check_form_changed();">에디터기능</a></li>
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
					<select name="layout_cmall" id="layout_cmall" class="form-control" >
						<?php echo element('layout_cmall_option', element('data', $view)); ?>
					</select>
				사이드바 -
					<select class="form-control" name="sidebar_cmall" id="sidebar_cmall">
						<option value="">기본설정따름</option>
						<option value="1" <?php echo set_select('sidebar_cmall', '1', (element('sidebar_cmall', element('data', $view)) === '1' ? true : false)); ?> >사용</option>
						<option value="2" <?php echo set_select('sidebar_cmall', '2', (element('sidebar_cmall', element('data', $view)) === '2' ? true : false)); ?> >사용하지않음</option>
					</select>
				스킨 -
					<select name="skin_cmall" id="skin_cmall" class="form-control" >
						<?php echo element('skin_cmall_option', element('data', $view)); ?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">모바일 레이아웃/스킨</label>
				<div class="col-sm-10 form-inline">
					레이아웃 -
					<select name="mobile_layout_cmall" id="mobile_layout_cmall" class="form-control" >
						<?php echo element('mobile_layout_cmall_option', element('data', $view)); ?>
					</select>
				사이드바 -
					<select class="form-control" name="mobile_sidebar_cmall" id="mobile_sidebar_cmall">
						<option value="">기본설정따름</option>
						<option value="1" <?php echo set_select('mobile_sidebar_cmall', '1', (element('mobile_sidebar_cmall', element('data', $view)) === '1' ? true : false)); ?> >사용</option>
						<option value="2" <?php echo set_select('mobile_sidebar_cmall', '2', (element('mobile_sidebar_cmall', element('data', $view)) === '2' ? true : false)); ?> >사용하지않음</option>
					</select>
				스킨 -
					<select name="mobile_skin_cmall" id="mobile_skin_cmall" class="form-control" >
						<?php echo element('mobile_skin_cmall_option', element('data', $view)); ?>
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
			<label class="col-sm-2 control-label">컨텐츠몰 메인 메타태그</label>
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
										<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_title_cmall" value="<?php echo set_value('site_meta_title_cmall', element('site_meta_title_cmall', element('data', $view))); ?>" /></div>
									</div>
									<div class="config_meta">
										<div class="start_config_meta">meta description</div>
										<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_description_cmall" value="<?php echo set_value('site_meta_description_cmall', element('site_meta_description_cmall', element('data', $view))); ?>" /></div>
									</div>
									<div class="config_meta">
										<div class="start_config_meta">meta keywords</div>
										<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_keywords_cmall" value="<?php echo set_value('site_meta_keywords_cmall', element('site_meta_keywords_cmall', element('data', $view))); ?>" /></div>
									</div>
									<div class="config_meta">
										<div class="start_config_meta">meta author</div>
										<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_author_cmall" value="<?php echo set_value('site_meta_author_cmall', element('site_meta_author_cmall', element('data', $view))); ?>" /></div>
									</div>
									<div class="config_meta">
										<div class="start_config_meta">page name</div>
										<div class="content_config_meta"><input type="text" class="form-control" name="site_page_name_cmall" value="<?php echo set_value('site_page_name_cmall', element('site_page_name_cmall', element('data', $view))); ?>" /></div>
									</div>
								</td>
								<td>{홈페이지제목}, {현재주소}, {회원아이디}, {회원닉네임}, {회원레벨}, {회원포인트}</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<label class="col-sm-2 control-label">상품목록 메타태그</label>
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
										<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_title_cmall_list" value="<?php echo set_value('site_meta_title_cmall_list', element('site_meta_title_cmall_list', element('data', $view))); ?>" /></div>
									</div>
									<div class="config_meta">
										<div class="start_config_meta">meta description</div>
										<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_description_cmall_list" value="<?php echo set_value('site_meta_description_cmall_list', element('site_meta_description_cmall_list', element('data', $view))); ?>" /></div>
									</div>
									<div class="config_meta">
										<div class="start_config_meta">meta keywords</div>
										<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_keywords_cmall_list" value="<?php echo set_value('site_meta_keywords_cmall_list', element('site_meta_keywords_cmall_list', element('data', $view))); ?>" /></div>
									</div>
									<div class="config_meta">
										<div class="start_config_meta">meta author</div>
										<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_author_cmall_list" value="<?php echo set_value('site_meta_author_cmall_list', element('site_meta_author_cmall_list', element('data', $view))); ?>" /></div>
									</div>
									<div class="config_meta">
										<div class="start_config_meta">page name</div>
										<div class="content_config_meta"><input type="text" class="form-control" name="site_page_name_cmall_list" value="<?php echo set_value('site_page_name_cmall_list', element('site_page_name_cmall_list', element('data', $view))); ?>" /></div>
									</div>
								</td>
								<td>{홈페이지제목}, {현재주소}, {회원아이디}, {회원닉네임}, {회원레벨}, {회원포인트}</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<label class="col-sm-2 control-label">상품상세페이지 메타태그</label>
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
										<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_title_cmall_item" value="<?php echo set_value('site_meta_title_cmall_item', element('site_meta_title_cmall_item', element('data', $view))); ?>" /></div>
									</div>
									<div class="config_meta">
										<div class="start_config_meta">meta description</div>
										<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_description_cmall_item" value="<?php echo set_value('site_meta_description_cmall_item', element('site_meta_description_cmall_item', element('data', $view))); ?>" /></div>
									</div>
									<div class="config_meta">
										<div class="start_config_meta">meta keywords</div>
										<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_keywords_cmall_item" value="<?php echo set_value('site_meta_keywords_cmall_item', element('site_meta_keywords_cmall_item', element('data', $view))); ?>" /></div>
									</div>
									<div class="config_meta">
										<div class="start_config_meta">meta author</div>
										<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_author_cmall_item" value="<?php echo set_value('site_meta_author_cmall_item', element('site_meta_author_cmall_item', element('data', $view))); ?>" /></div>
									</div>
									<div class="config_meta">
										<div class="start_config_meta">page name</div>
										<div class="content_config_meta"><input type="text" class="form-control" name="site_page_name_cmall_item" value="<?php echo set_value('site_page_name_cmall_item', element('site_page_name_cmall_item', element('data', $view))); ?>" /></div>
									</div>
								</td>
								<td>{홈페이지제목}, {현재주소}, {회원아이디}, {회원닉네임}, {회원레벨}, {회원포인트}, {상품명}, {판매가격}, {기본설명}</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<label class="col-sm-2 control-label">장바구니 메타태그</label>
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
										<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_title_cmall_cart" value="<?php echo set_value('site_meta_title_cmall_cart', element('site_meta_title_cmall_cart', element('data', $view))); ?>" /></div>
									</div>
									<div class="config_meta">
										<div class="start_config_meta">meta description</div>
										<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_description_cmall_cart" value="<?php echo set_value('site_meta_description_cmall_cart', element('site_meta_description_cmall_cart', element('data', $view))); ?>" /></div>
									</div>
									<div class="config_meta">
										<div class="start_config_meta">meta keywords</div>
										<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_keywords_cmall_cart" value="<?php echo set_value('site_meta_keywords_cmall_cart', element('site_meta_keywords_cmall_cart', element('data', $view))); ?>" /></div>
									</div>
									<div class="config_meta">
										<div class="start_config_meta">meta author</div>
										<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_author_cmall_cart" value="<?php echo set_value('site_meta_author_cmall_cart', element('site_meta_author_cmall_cart', element('data', $view))); ?>" /></div>
									</div>
									<div class="config_meta">
										<div class="start_config_meta">page name</div>
										<div class="content_config_meta"><input type="text" class="form-control" name="site_page_name_cmall_cart" value="<?php echo set_value('site_page_name_cmall_cart', element('site_page_name_cmall_cart', element('data', $view))); ?>" /></div>
									</div>
								</td>
								<td>{홈페이지제목}, {현재주소}, {회원아이디}, {회원닉네임}, {회원레벨}, {회원포인트}</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<label class="col-sm-2 control-label">주문하기 메타태그</label>
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
										<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_title_cmall_order" value="<?php echo set_value('site_meta_title_cmall_order', element('site_meta_title_cmall_order', element('data', $view))); ?>" /></div>
									</div>
									<div class="config_meta">
										<div class="start_config_meta">meta description</div>
										<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_description_cmall_order" value="<?php echo set_value('site_meta_description_cmall_order', element('site_meta_description_cmall_order', element('data', $view))); ?>" /></div>
									</div>
									<div class="config_meta">
										<div class="start_config_meta">meta keywords</div>
										<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_keywords_cmall_order" value="<?php echo set_value('site_meta_keywords_cmall_order', element('site_meta_keywords_cmall_order', element('data', $view))); ?>" /></div>
									</div>
									<div class="config_meta">
										<div class="start_config_meta">meta author</div>
										<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_author_cmall_order" value="<?php echo set_value('site_meta_author_cmall_order', element('site_meta_author_cmall_order', element('data', $view))); ?>" /></div>
									</div>
									<div class="config_meta">
										<div class="start_config_meta">page name</div>
										<div class="content_config_meta"><input type="text" class="form-control" name="site_page_name_cmall_order" value="<?php echo set_value('site_page_name_cmall_order', element('site_page_name_cmall_order', element('data', $view))); ?>" /></div>
									</div>
								</td>
								<td>{홈페이지제목}, {현재주소}, {회원아이디}, {회원닉네임}, {회원레벨}, {회원포인트}</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<label class="col-sm-2 control-label">주문결과 메타태그</label>
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
										<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_title_cmall_orderresult" value="<?php echo set_value('site_meta_title_cmall_orderresult', element('site_meta_title_cmall_orderresult', element('data', $view))); ?>" /></div>
									</div>
									<div class="config_meta">
										<div class="start_config_meta">meta description</div>
										<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_description_cmall_orderresult" value="<?php echo set_value('site_meta_description_cmall_orderresult', element('site_meta_description_cmall_orderresult', element('data', $view))); ?>" /></div>
									</div>
									<div class="config_meta">
										<div class="start_config_meta">meta keywords</div>
										<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_keywords_cmall_orderresult" value="<?php echo set_value('site_meta_keywords_cmall_orderresult', element('site_meta_keywords_cmall_orderresult', element('data', $view))); ?>" /></div>
									</div>
									<div class="config_meta">
										<div class="start_config_meta">meta author</div>
										<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_author_cmall_orderresult" value="<?php echo set_value('site_meta_author_cmall_orderresult', element('site_meta_author_cmall_orderresult', element('data', $view))); ?>" /></div>
									</div>
									<div class="config_meta">
										<div class="start_config_meta">page name</div>
										<div class="content_config_meta"><input type="text" class="form-control" name="site_page_name_cmall_orderresult" value="<?php echo set_value('site_page_name_cmall_orderresult', element('site_page_name_cmall_orderresult', element('data', $view))); ?>" /></div>
									</div>
								</td>
								<td>{홈페이지제목}, {현재주소}, {회원아이디}, {회원닉네임}, {회원레벨}, {회원포인트}</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<label class="col-sm-2 control-label">주문내역 메타태그</label>
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
										<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_title_cmall_orderlist" value="<?php echo set_value('site_meta_title_cmall_orderlist', element('site_meta_title_cmall_orderlist', element('data', $view))); ?>" /></div>
									</div>
									<div class="config_meta">
										<div class="start_config_meta">meta description</div>
										<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_description_cmall_orderlist" value="<?php echo set_value('site_meta_description_cmall_orderlist', element('site_meta_description_cmall_orderlist', element('data', $view))); ?>" /></div>
									</div>
									<div class="config_meta">
										<div class="start_config_meta">meta keywords</div>
										<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_keywords_cmall_orderlist" value="<?php echo set_value('site_meta_keywords_cmall_orderlist', element('site_meta_keywords_cmall_orderlist', element('data', $view))); ?>" /></div>
									</div>
									<div class="config_meta">
										<div class="start_config_meta">meta author</div>
										<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_author_cmall_orderlist" value="<?php echo set_value('site_meta_author_cmall_orderlist', element('site_meta_author_cmall_orderlist', element('data', $view))); ?>" /></div>
									</div>
									<div class="config_meta">
										<div class="start_config_meta">page name</div>
										<div class="content_config_meta"><input type="text" class="form-control" name="site_page_name_cmall_orderlist" value="<?php echo set_value('site_page_name_cmall_orderlist', element('site_page_name_cmall_orderlist', element('data', $view))); ?>" /></div>
									</div>
								</td>
								<td>{홈페이지제목}, {현재주소}, {회원아이디}, {회원닉네임}, {회원레벨}, {회원포인트}</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<label class="col-sm-2 control-label">찜한목록 메타태그</label>
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
										<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_title_cmall_wishlist" value="<?php echo set_value('site_meta_title_cmall_wishlist', element('site_meta_title_cmall_wishlist', element('data', $view))); ?>" /></div>
									</div>
									<div class="config_meta">
										<div class="start_config_meta">meta description</div>
										<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_description_cmall_wishlist" value="<?php echo set_value('site_meta_description_cmall_wishlist', element('site_meta_description_cmall_wishlist', element('data', $view))); ?>" /></div>
									</div>
									<div class="config_meta">
										<div class="start_config_meta">meta keywords</div>
										<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_keywords_cmall_wishlist" value="<?php echo set_value('site_meta_keywords_cmall_wishlist', element('site_meta_keywords_cmall_wishlist', element('data', $view))); ?>" /></div>
									</div>
									<div class="config_meta">
										<div class="start_config_meta">meta author</div>
										<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_author_cmall_wishlist" value="<?php echo set_value('site_meta_author_cmall_wishlist', element('site_meta_author_cmall_wishlist', element('data', $view))); ?>" /></div>
									</div>
									<div class="config_meta">
										<div class="start_config_meta">page name</div>
										<div class="content_config_meta"><input type="text" class="form-control" name="site_page_name_cmall_wishlist" value="<?php echo set_value('site_page_name_cmall_wishlist', element('site_page_name_cmall_wishlist', element('data', $view))); ?>" /></div>
									</div>
								</td>
								<td>{홈페이지제목}, {현재주소}, {회원아이디}, {회원닉네임}, {회원레벨}, {회원포인트}</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<label class="col-sm-2 control-label">후기작성 메타태그</label>
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
										<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_title_cmall_review_write" value="<?php echo set_value('site_meta_title_cmall_review_write', element('site_meta_title_cmall_review_write', element('data', $view))); ?>" /></div>
									</div>
									<div class="config_meta">
										<div class="start_config_meta">meta description</div>
										<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_description_cmall_review_write" value="<?php echo set_value('site_meta_description_cmall_review_write', element('site_meta_description_cmall_review_write', element('data', $view))); ?>" /></div>
									</div>
									<div class="config_meta">
										<div class="start_config_meta">meta keywords</div>
										<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_keywords_cmall_review_write" value="<?php echo set_value('site_meta_keywords_cmall_review_write', element('site_meta_keywords_cmall_review_write', element('data', $view))); ?>" /></div>
									</div>
									<div class="config_meta">
										<div class="start_config_meta">meta author</div>
										<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_author_cmall_review_write" value="<?php echo set_value('site_meta_author_cmall_review_write', element('site_meta_author_cmall_review_write', element('data', $view))); ?>" /></div>
									</div>
									<div class="config_meta">
										<div class="start_config_meta">page name</div>
										<div class="content_config_meta"><input type="text" class="form-control" name="site_page_name_cmall_review_write" value="<?php echo set_value('site_page_name_cmall_review_write', element('site_page_name_cmall_review_write', element('data', $view))); ?>" /></div>
									</div>
								</td>
								<td>{홈페이지제목}, {현재주소}, {회원아이디}, {회원닉네임}, {회원레벨}, {회원포인트}, {상품명}, {판매가격}, {기본설명}</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<label class="col-sm-2 control-label">상품문의작성 메타태그</label>
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
										<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_title_cmall_qna_write" value="<?php echo set_value('site_meta_title_cmall_qna_write', element('site_meta_title_cmall_qna_write', element('data', $view))); ?>" /></div>
									</div>
									<div class="config_meta">
										<div class="start_config_meta">meta description</div>
										<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_description_cmall_qna_write" value="<?php echo set_value('site_meta_description_cmall_qna_write', element('site_meta_description_cmall_qna_write', element('data', $view))); ?>" /></div>
									</div>
									<div class="config_meta">
										<div class="start_config_meta">meta keywords</div>
										<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_keywords_cmall_qna_write" value="<?php echo set_value('site_meta_keywords_cmall_qna_write', element('site_meta_keywords_cmall_qna_write', element('data', $view))); ?>" /></div>
									</div>
									<div class="config_meta">
										<div class="start_config_meta">meta author</div>
										<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_author_cmall_qna_write" value="<?php echo set_value('site_meta_author_cmall_qna_write', element('site_meta_author_cmall_qna_write', element('data', $view))); ?>" /></div>
									</div>
									<div class="config_meta">
										<div class="start_config_meta">page name</div>
										<div class="content_config_meta"><input type="text" class="form-control" name="site_page_name_cmall_qna_write" value="<?php echo set_value('site_page_name_cmall_qna_write', element('site_page_name_cmall_qna_write', element('data', $view))); ?>" /></div>
									</div>
								</td>
								<td>{홈페이지제목}, {현재주소}, {회원아이디}, {회원닉네임}, {회원레벨}, {회원포인트}, {상품명}, {판매가격}, {기본설명}</td>
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
