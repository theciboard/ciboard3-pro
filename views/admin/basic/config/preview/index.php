<style type="text/css">
body{margin:0;padding:0;overflow:hidden;}
.headerbar{width:100%;height:45px;border-bottom:1px solid #000;background-color:#555555;text-align:center;margin:0 auto; padding:5px;}
.topbar {text-align:left;}
.pagename {width:350px; display:inline-block; font-size:16px; color:#FFF; margin:5px;}
.options {display:inline-block; margin:0 auto;}
.option_title {color:#FFF;display:inline-block;}
.framewrapper {width:100%; text-align:center;background:#EFEFEF;}
#contentFrame {margin:0 auto;}
</style>
<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.iframe.auto.js'); ?>"></script>
<div class="headerbar">
	<div class="topbar">
		<div class="pagename" >미리보기 페이지명 : <?php echo html_escape(element('pagename', $view));?></div>
		<div class="options form-group form-inline">
			<div class="option_title">레이아웃 -</div>
			<select class="form-control" name="layout" id="layout">
				<?php echo element('layout_option', $view); ?>
			</select>
		</div>
		<?php if (element('use_sidebar_option', $view)) { ?>
			<div class="options form-group form-inline">
				<div class="option_title">사이드바 -</div>
				<select class="form-control" name="sidebar" id="sidebar">
					<option value="">기본설정따름</option>
					<option value="1" <?php echo set_select('sidebar', '1', $this->input->get('sidebar') === '1' ? true : false); ?> >사용</option>
					<option value="2" <?php echo set_select('sidebar', '2', $this->input->get('sidebar') === '2' ? true : false); ?> >사용하지않음</option>
				</select>
			</div>
		<?php } ?>
		<div class="options form-group form-inline">
			<div class="option_title">스킨 -</div>
			<select class="form-control" name="skin" id="skin">
				<?php echo element('skin_option', $view); ?>
			</select>
		</div>
		<div class="options form-group form-inline">
			<div class="option_title">PC/모바일 -</div>
			<select class="form-control" name="is_mobile" id="is_mobile">
				<option value="" <?php echo set_select('is_mobile', '', ! $this->input->get('is_mobile') ? true : false); ?> >PC</option>
				<option value="1" <?php echo set_select('is_mobile', '1', $this->input->get('is_mobile') ? true : false); ?> >모바일</option>
			</select>
		</div>
	</div>
</div>
<div class="framewrapper"><iframe id="contentFrame" frameborder="0" width="100%"></iframe></div>

<script type="text/javascript">
$(function() {
	$(document).on('change', 'select', function() {
		window.location.href = cb_admin_url + '/config/preview/preview/<?php echo element('pagetype', $view); ?>?layout='
			+ $('#layout').val() + '&sidebar=' + $('#sidebar').val() + '&skin=' + $('#skin').val() + '&is_mobile=' + $('#is_mobile').val();
	});
	$('#contentFrame').attr('src', '<?php echo element('previewurl', $view);?>');
	<?php if ($this->input->get('is_mobile')) { ?>
		$('#contentFrame').attr('width', '400px');
	<?php } else { ?>
		$('#contentFrame').attr('width', '100%');
	<?php } ?>
});
</script>
