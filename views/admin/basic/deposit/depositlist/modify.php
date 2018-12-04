<div class="box">
	<div class="box-table">
		<?php
		echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
		$attributes = array('class' => 'form-horizontal', 'name' => 'fadminwrite', 'id' => 'fadminwrite');
		echo form_open(current_full_url(), $attributes);
		?>
			<input type="hidden" name="<?php echo element('primary_key', $view); ?>"	value="<?php echo element(element('primary_key', $view), element('data', $view)); ?>" />
			<?php if( element('is_test', element('data', $view)) ){ ?>
				<div class="form-group">
					<label class="col-sm-2 control-label">테스트결제</label>
					<div class="col-sm-10">
						<span style="color:red;font-weight:bold">테스트로 결제 되었습니다.</span>
					</div>
				</div>
			<?php } ?>
			<div class="form-group">
			<label class="col-sm-2 control-label">회원명</label>
				<div class="col-sm-10">
					<?php
					echo html_escape(element('mem_nickname', element('member', element('data', $view))));
					echo ' ( ' . html_escape(element('mem_userid', element('member', element('data', $view)))) . ' ) ';
					?>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">구분</label>
				<div class="col-sm-10">
					<?php
					echo element(element('dep_from_type', element('data', $view)), element('deptype', $view));
					echo ' =&gt; ';
					echo element(element('dep_to_type', element('data', $view)), element('deptype', $view));
					?>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">결제방법</label>
				<div class="col-sm-10">
					<?php
					echo element(element('dep_pay_type', element('data', $view)), element('paymethodtype', $view));
					?>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label"><?php echo $this->cbconfig->item('deposit_name'); ?> 변동</label>
				<div class="col-sm-10">
					<?php
					echo number_format(element('dep_deposit', element('data', $view)));
					echo $this->cbconfig->item('deposit_unit');
					?>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">현금 변동</label>
				<div class="col-sm-10">
					<?php echo number_format(element('dep_cash', element('data', $view))); ?> 원
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">포인트 변동</label>
				<div class="col-sm-10">
					<?php echo number_format(element('dep_point', element('data', $view))); ?> 점
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">내용</label>
				<div class="col-sm-10">
					<textarea class="form-control" rows="5" name="dep_content"><?php echo set_value('dep_content', element('dep_content', element('data', $view))); ?></textarea>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">관리자 메모</label>
				<div class="col-sm-10">
					<textarea class="form-control" rows="5" name="dep_admin_memo"><?php echo set_value('dep_admin_memo', element('dep_admin_memo', element('data', $view))); ?></textarea>
				</div>
			</div>
			<div class="btn-group pull-right" role="group" aria-label="...">
				<button type="submit" class="btn btn-outline btn-success btn-sm">저장하기</button>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>
<script type="text/javascript">
//<![CDATA[
$(function() {
	$('#fadminwrite').validate({
		rules: {
			dep_content: 'required'
		}
	});
});
//]]>
</script>
