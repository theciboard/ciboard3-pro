<div class="box">
	<div class="box-header">
		<ul class="nav nav-tabs">
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir); ?>" onclick="return check_form_changed();">기본설정</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/layout'); ?>" onclick="return check_form_changed();">레이아웃/메타태그</a></li>
			<li role="presentation" class="active"><a href="<?php echo admin_url($this->pagedir . '/access'); ?>" onclick="return check_form_changed();">권한관리</a></li>
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
				<label class="col-sm-2 control-label">목록보기 권한</label>
				<div class="col-sm-10 form-inline">
					<?php
					$config = array(
						'column_name' => 'access_cmall_list',
						'column_level_name' => 'access_cmall_list_level',
						'column_group_name' => 'access_cmall_list_group',
						'column_value' => element('access_cmall_list', element('data', $view)),
						'column_level_value' => element('access_cmall_list_level', element('data', $view)),
						'column_group_value' => element('access_cmall_list_group', element('data', $view)),
						'max_level' => element('config_max_level', element('data', $view)),
						'mgroup' => element('mgroup', element('data', $view)),
						);
					echo get_access_selectbox($config);
					?>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">열람 권한</label>
				<div class="col-sm-10 form-inline">
					<?php
					$config = array(
						'column_name' => 'access_cmall_read',
						'column_level_name' => 'access_cmall_read_level',
						'column_group_name' => 'access_cmall_read_group',
						'column_value' => element('access_cmall_read', element('data', $view)),
						'column_level_value' => element('access_cmall_read_level', element('data', $view)),
						'column_group_value' => element('access_cmall_read_group', element('data', $view)),
						'max_level' => element('config_max_level', element('data', $view)),
						'mgroup' => element('mgroup', element('data', $view)),
						);
					echo get_access_selectbox($config);
					?>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">상품구매 권한</label>
				<div class="col-sm-10 form-inline">
					<?php
					$config = array(
						'column_name' => 'access_cmall_buy',
						'column_level_name' => 'access_cmall_buy_level',
						'column_group_name' => 'access_cmall_buy_group',
						'column_value' => element('access_cmall_buy', element('data', $view)),
						'column_level_value' => element('access_cmall_buy_level', element('data', $view)),
						'column_group_value' => element('access_cmall_buy_group', element('data', $view)),
						'max_level' => element('config_max_level', element('data', $view)),
						'mgroup' => element('mgroup', element('data', $view)),
						);
					echo get_access_selectbox($config, true);
					?>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">사용후기 작성</label>
				<div class="col-sm-10">
					<label class="radio-inline" for="use_cmall_product_review_anytime_1" >
						<input type="radio" name="use_cmall_product_review_anytime" id="use_cmall_product_review_anytime_1" value="1" <?php echo set_checkbox('use_cmall_product_review_anytime', '1', (element('use_cmall_product_review_anytime', element('data', $view)) === '1' ? true : false)); ?> /> 주문상태와무관하게작성가능
					</label>
					<label class="radio-inline" for="use_cmall_product_review_anytime_2" >
						<input type="radio" name="use_cmall_product_review_anytime" id="use_cmall_product_review_anytime_2" value="0" <?php echo set_checkbox('use_cmall_product_review_anytime', '0', (element('use_cmall_product_review_anytime', element('data', $view)) !== '1' ? true : false)); ?> /> 주문완료후작성가능
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">사용후기 승인후 출력</label>
				<div class="col-sm-10">
					<label for="use_cmall_product_review_approve" class="checkbox-inline">
						<input type="checkbox" name="use_cmall_product_review_approve" id="use_cmall_product_review_approve" value="1" <?php echo set_checkbox('use_cmall_product_review_approve', '1', (element('use_cmall_product_review_approve', element('data', $view)) ? true : false)); ?> /> 승인 후 출력합니다
					</label>
					<span class="help-inline">이 기능을 사용하지 않으시면 후기를 작성하는 즉시 출력됩니다</span>
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
