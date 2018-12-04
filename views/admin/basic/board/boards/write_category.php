<div class="box">
	<div class="box-header">
		<h4 class="pb10 pull-left"><?php echo html_escape($this->board->item_id('brd_name', element('brd_id', element('data', $view)))); ?> <a href="<?php echo goto_url(board_url(html_escape($this->board->item_id('brd_key', element('brd_id', element('data', $view)))))); ?>" class="btn-xs" target="_blank"><span class="glyphicon glyphicon-new-window"></span></a></h4>
		<?php if (element('boardlist', $view)) { ?>
		<div class="pull-right">
			<select name="brd_id" class="form-control" onChange="location.href='<?php echo admin_url($this->pagedir . '/write_category'); ?>/' + this.value;">
				<?php foreach (element('boardlist', $view) as $key => $value) { ?>
					<option value="<?php echo element('brd_id', $value); ?>" <?php echo set_select('brd_id', element('brd_id', $value), ((string) element('brd_id', element('data', $view)) === element('brd_id', $value) ? true : false)); ?>><?php echo html_escape(element('brd_name', $value)); ?></option>
				<?php } ?>
			</select>
		</div>
		<?php } ?>
		<div class="clearfix"></div>
		<ul class="nav nav-tabs">
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/write/' . element('brd_id', element('data', $view))); ?>">기본정보</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/write_list/' . element('brd_id', element('data', $view))); ?>">목록페이지</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/write_post/' . element('brd_id', element('data', $view))); ?>">게시물열람</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/write_write/' . element('brd_id', element('data', $view))); ?>">게시물작성</a></li>
			<li role="presentation" class="active"><a href="<?php echo admin_url($this->pagedir . '/write_category/' . element('brd_id', element('data', $view))); ?>">카테고리</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/write_comment/' . element('brd_id', element('data', $view))); ?>">댓글기능</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/write_general/' . element('brd_id', element('data', $view))); ?>">일반기능</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/write_point/' . element('brd_id', element('data', $view))); ?>">포인트기능</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/write_alarm/' . element('brd_id', element('data', $view))); ?>">메일/쪽지/문자</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/write_rss/' . element('brd_id', element('data', $view))); ?>">RSS/사이트맵 설정</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/write_access/' . element('brd_id', element('data', $view))); ?>">권한관리</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/write_extravars/' . element('brd_id', element('data', $view))); ?>">사용자정의</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/write_admin/' . element('brd_id', element('data', $view))); ?>">게시판관리자</a></li>
		</ul>
	</div>
	<div class="box-table">
		<?php
		echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
		echo show_alert_message(element('alert_message', $view), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
		echo show_alert_message($this->session->flashdata('message'), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
		?>
		<?php
		if ($this->board->item_id('use_category', element('brd_id', element('data', $view)))) {
		?>
			<div class="alert alert-dismissible alert-warning">
				현재 카테고리 기능이 활성화되어 있습니다.<br />
				카테고리 기능을 잠시 해제하기 위해서는 "일반 기능" 탭에서 "카테고리 기능" 란에 체크를 해제해주세요
			</div>
		<?php
		} else {
		?>
			<div class="alert alert-dismissible alert-danger">
				현재 카테고리 기능이 활성화되어있지 않습니다.<br />
				카테고리 기능을 사용하기 위해서는 "일반 기능" 탭에서 "카테고리 기능" 란에 체크해주세요
			</div>
		<?php
		}
		?>
		<ul class="list-group">
			<?php
			$data = element('data', $view);
			function ca_list($p, $data)
			{
				$return = '';
				if ($p && is_array($p)) {
					foreach ($p as $result) {
						$exp = explode('.', element('bca_key', $result));
						$len = (element(1, $exp)) ? strlen(element(1, $exp)) : 0;
						$margin = $len * 20;
						$attributes = array('class' => 'form-inline', 'name' => 'fcategory');
						$return .= '<li class="list-group-item">
											<div class="form-horizontal">
												<div class="form-group" style="margin-bottom:0;">';
						if ($len) {
							$return .= '<div style="width:10px;float:left;margin-left:' . $margin . 'px;margin-right:10px;"><span class="fa fa-arrow-right"></span></div>';
						}
						$return .= '<div class="pl10">
							<div class="cat-bca-id-' . element('bca_id', $result) . '">
								' . html_escape(element('bca_value', $result)) . ' (' . html_escape(element('bca_order', $result)) . ')
								<button class="btn btn-primary btn-xs" onClick="cat_modify(\'' . element('bca_id', $result) . '\')"><span class="glyphicon glyphicon-edit"></span></button>';
						if ( ! element(element('bca_key', $result), $data)) {
							$return .= '<button class="btn btn-danger btn-xs btn-one-delete" data-one-delete-url = "' . admin_url('board/boards/write_category_delete/' . element('brd_id', $data) . '/' . element('bca_id', $result)) . '"><span class="glyphicon glyphicon-trash"></span></button>';
						}
						$return .= '				</div>
														<div class="form-inline mod-bca-id-' . element('bca_id', $result) . '" style="display:none;">';
						$return .= form_open(current_full_url(), $attributes);
						$return .= '
															<input type="hidden" name="brd_id"	value="' . element('brd_id', $data) . '" />
															<input type="hidden" name="bca_id"	value="' . element('bca_id', $result) . '" />
															<input type="hidden" name="type" value="modify" />
															<div class="form-group" style="margin-left:0;">
																카테고리명 <input type="text" class="form-control" name="bca_value" value="' . html_escape(element('bca_value', $result)) . '" />
																정렬순서 <input type="number" class="form-control" name="bca_order" value="' . html_escape(element('bca_order', $result)) . '"/>
																<button class="btn btn-primary btn-xs" type="submit" >저장</button>
																<a href="javascript:;" class="btn btn-default btn-xs" onClick="cat_cancel(\'' . element('bca_id', $result) . '\')">취소</a>
															</div>';
						$return .= form_close();
						$return .= '				</div>
													</div>
													</div>
												</div>
											</li>';
						$parent = element('bca_key', $result);
						$return .= ca_list(element($parent, $data), $data);
					}
				}
				return $return;
			}
			echo ca_list(element(0, $data), $data);
			?>
		</ul>
	</div>
	<div>
		<div class="box-table">
			<?php
			$attributes = array('class' => 'form-horizontal', 'name' => 'fadminwrite', 'id' => 'fadminwrite');
			echo form_open(current_full_url(), $attributes);
			?>
				<input type="hidden" name="is_submit" value="1" />
				<input type="hidden" name="brd_id"	value="<?php echo element('brd_id', element('data', $view)); ?>" />
				<input type="hidden" name="type" value="add" />
				<div class="form-group">
					<label class="col-sm-2 control-label">카테고리 추가</label>
					<div class="col-sm-8 form-inline">
						<select name="bca_parent" class="form-control">
							<option value="0">최상위카테고리</option>
							<?php
							$data = element('data', $view);
							function ca_select($p, $data)
							{
								$return = '';
								if ($p && is_array($p)) {
									foreach ($p as $result) {
										$return .= '<option value="' . html_escape(element('bca_key', $result)) . '">' . html_escape(element('bca_value', $result)) . '의 하위카테고리</option>';
										$parent = element('bca_key', $result);
										$return .= ca_select(element($parent, $data), $data);
									}
								}
								return $return;
							}
							echo ca_select(element(0, $data), $data);
							?>
						</select>
						<input type="text" name="bca_value" class="form-control" value="" placeholder="카테고리명 입력" />
						<input type="number" name="bca_order" class="form-control" value="" placeholder="정렬순서" />
						<button type="submit" class="btn btn-success btn-sm">추가하기</button>
					</div>
				</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>

<script type="text/javascript">
//<![CDATA[
function cat_modify(bca_id) {
	$('.cat-bca-id-' + bca_id).hide();
	$('.mod-bca-id-' + bca_id).show();
}
function cat_cancel(bca_id) {
	$('.cat-bca-id-' + bca_id).show();
	$('.mod-bca-id-' + bca_id).hide();
}
//]]>
</script>
