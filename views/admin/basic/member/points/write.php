<div class="box">
	<div class="box-table">
		<?php echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>'); ?>
		<div class="box-table-header">
			<h4><a data-toggle="collapse" href="#collapse1" aria-expanded="true" aria-controls="collapse1">특정 회원 포인트 추가</a></h4>
			<a data-toggle="collapse" href="#collapse1" aria-expanded="true" aria-controls="collapse1"><i class="fa fa-chevron-up pull-right"></i></a>
		</div>
		<div class="collapse in" id="collapse1">
			<?php
			$attributes = array('class' => 'form-horizontal', 'name' => 'fadminwrite', 'id' => 'fadminwrite');
			echo form_open(current_full_url(), $attributes);
			?>
				<input type="hidden" name="poi_type"	value="toone" />
				<div class="form-group">
					<label class="col-sm-2 control-label">회원아이디</label>
					<div class="col-sm-10 form-inline">
						<input type="text" class="form-control" name="mem_userid" value="<?php echo set_value('mem_userid'); ?>" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">포인트</label>
					<div class="col-sm-10">
						<input type="number" class="form-control" name="poi_point" value="<?php echo set_value('poi_point'); ?>" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">내용</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" name="poi_content" value="<?php echo set_value('poi_content'); ?>" />
					</div>
				</div>
				<div class="btn-group pull-right" role="group" aria-label="...">
					<button type="submit" class="btn btn-outline btn-success btn-sm">특정회원 포인트 추가하기</button>
				</div>
			<?php echo form_close(); ?>
		</div>
	</div>
	<div class="box-table">
		<div class="box-table-header">
			<h4><a data-toggle="collapse" href="#collapse2" aria-expanded="true" aria-controls="collapse2">모든 회원 동시 포인트 추가</a></h4>
			<a data-toggle="collapse" href="#collapse2" aria-expanded="true" aria-controls="collapse2"><i class="fa fa-chevron-up pull-right"></i></a>
		</div>
		<div class="collapse in" id="collapse2">
			<?php
			$attributes = array('class' => 'form-horizontal', 'name' => 'fadminwrite2', 'id' => 'fadminwrite2');
			echo form_open(current_full_url(), $attributes);
			?>
				<input type="hidden" name="poi_type"	value="toall" />
				<div class="form-group">
					<label class="col-sm-2 control-label">포인트</label>
					<div class="col-sm-10 form-inline">
						<input type="number" class="form-control" name="poi_point_all" value="<?php echo set_value('poi_point_all'); ?>" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label">내용</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" name="poi_content_all" value="<?php echo set_value('poi_content_all'); ?>" />
					</div>
				</div>
				<div class="btn-group pull-right" role="group" aria-label="...">
					<button type="submit" class="btn btn-outline btn-success btn-sm">모든 회원 포인트 동시 추가</button>
				</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>

<script type="text/javascript">
//<![CDATA[
$(function() {
	$('#fadminwrite').validate({
		rules: {
			mem_userid: { required:true, minlength:3, maxlength:20 },
			poi_point: { required:true, number:true },
			poi_content: 'required'
		}
	});
	$('#fadminwrite2').validate({
		rules: {
			poi_point_all: { required:true, number:true },
			poi_content_all: 'required'
		}
	});
});
//]]>
</script>
