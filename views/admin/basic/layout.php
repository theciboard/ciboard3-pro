<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CIBoard Admin</title>
<link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" />
<link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/datepicker3.css'); ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo element('layout_skin_url', $layout); ?>/css/style.css" />
<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/earlyaccess/nanumgothic.css" />
<?php if (element('favicon', $layout)) { ?><link rel="shortcut icon" type="image/x-icon" href="<?php echo element('favicon', $layout); ?>" /><?php } ?>

<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/bootstrap-datepicker.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/bootstrap-datepicker.kr.js'); ?>"></script>

<script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.validate.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.validate.extension.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/common.js'); ?>"></script>
<!--[if lt IE 9]>
<script type="text/javascript" src="<?php echo base_url('assets/js/html5shiv.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/respond.min.js'); ?>"></script>
<![endif]-->
<script type="text/javascript">
// 자바스크립트에서 사용하는 전역변수 선언
var cb_url = "<?php echo trim(site_url(), '/'); ?>";
var cb_admin_url = "<?php echo admin_url(); ?>";
var cb_charset = "<?php echo config_item('charset'); ?>";
var cb_time_ymd = "<?php echo cdate('Y-m-d'); ?>";
var cb_time_ymdhis = "<?php echo cdate('Y-m-d H:i:s'); ?>";
var admin_skin_path = "<?php echo element('layout_skin_path', $layout); ?>";
var admin_skin_url = "<?php echo element('layout_skin_url', $layout); ?>";
var is_member = "<?php echo $this->member->is_member() ? '1' : ''; ?>";
var is_admin = "<?php echo $this->member->is_admin(); ?>";
var cb_admin_url = <?php echo $this->member->is_admin() === 'super' ? 'cb_url + "/' . config_item('uri_segment_admin') . '"' : '""'; ?>;
var cb_board = "";
var cb_csrf_hash = "<?php echo $this->security->get_csrf_hash(); ?>";
var cookie_prefix = "<?php echo config_item('cookie_prefix'); ?>";
</script>
<script type="text/javascript" src="<?php echo base_url('assets/js/sideview.js'); ?>"></script>
</head>
<body>
<!-- start wrapper -->
<div class="wrapper">
	<!-- start nav -->
	<nav class="nav-default">
		<h1 class="nav-header"><a href="<?php echo admin_url(); ?>"><?php echo $this->cbconfig->item('admin_logo'); ?></a></h1>
		<ul class="nav">
			<?php
			foreach (element('admin_page_menu', $layout) as $__akey => $__aval) {
			?>
				<li class="nav-first-level nav_menuname_<?php echo $__akey; ?> <?php echo (element('menu_dir1', $layout) === $__akey) ? 'active' : ''; ?>">
					<a data-toggle="menu_collapse" href="#collapse<?php echo $__akey; ?>" aria-expanded="false" aria-controls="menu_collapse<?php echo $__akey; ?>" onClick="changemenu('<?php echo $__akey; ?>');" class="<?php echo (element('menu_dir1', $layout) === $__akey) ? '' : 'collapsed'; ?>">
						<i class="fa <?php echo element(1, element('__config', $__aval)); ?>"></i>
						<span class="nav-label"><?php echo element(0, element('__config', $__aval)); ?></span>
						<i class="fa <?php echo (element('menu_dir1', $layout) === $__akey) ? 'fa-angle-down' : 'fa-angle-left'; ?> menu-arrow-icon <?php echo $__akey; ?>"></i>
					</a>
					<ul class="nav nav-second-level menu_collapse collapse <?php echo (element('menu_dir1', $layout) === $__akey) ? 'in' : ''; ?>" id="menu_collapse<?php echo $__akey; ?>" <?php echo (element('menu_dir1', $layout) === $__akey) ? '' : 'style="height:0;"'; ?>>
						<?php
						foreach (element('menu', $__aval) as $menukey => $menuvalue) {
							if (element(2, $menuvalue) === 'hide') {
								continue;
							}
						?>
							<li <?php echo (element('menu_dir1', $layout) === $__akey && element('menu_dir2', $layout) === $menukey) ? 'class="active"' : ''; ?>><a href="<?php echo admin_url($__akey . '/' . $menukey); ?>" <?php echo element(1, $menuvalue); ?> ><?php echo element(0, $menuvalue); ?></a></li>
						<?php
						}
						?>
					</ul>
				</li>
			<?php
			}
			?>
		</ul>
	</nav>
	<script type="text/javascript">
	//<![CDATA[
	$('#menu_collapse<?php echo element('menu_dir1', $layout); ?>').collapse('show');
	function changemenu( menukey) {
		if ($('#menu_collapse' + menukey).parent().hasClass('active')) {
			close_admin_menu();
		} else {
			open_admin_menu(menukey);
		}
	}
	function close_admin_menu() {
		$('.menu_collapse').collapse('hide');
		$('.nav-first-level').removeClass('active');
		$('.menu-arrow-icon').removeClass('fa-angle-down').addClass('fa-angle-left');
	}
	function open_admin_menu(menukey) {
		close_admin_menu();
		$('.nav-first-level.nav_menuname_' + menukey).addClass('active');
		$('.menu-arrow-icon.' + menukey).removeClass('fa-angle-left').addClass('fa-angle-down');
		$('#menu_collapse' + menukey).collapse('toggle');
	}
	//]]>
	</script>
	<!-- end nav -->

	<!-- start content_wrapper -->
	<div class="content_wrapper">
		<!-- start header -->
		<div class="row header">
			<div class="navbar-minimalize"><a href="#" class="btn btn-primary btn-mini"><i class="fa fa-bars"></i></a></div>
			<script type="text/javascript">
			//<![CDATA[
			$(document).on('click', '.navbar-minimalize>a', function() {
				if ($('.nav-default').is(':visible') === true) {
					$('.nav-default').hide();
					$('.content_wrapper').css('margin-left', '0px');
				} else {
					$('.nav-default').show();
					$('.content_wrapper').css('margin-left', '220px');
				}
			});
			//]]>
			</script>
			<ul class="nav-top">
				<li>
					<a href="<?php echo site_url(); ?>" target="_blank">홈페이지로 이동</a>
				</li>
				<li><a href="<?php echo site_url('login/logout'); ?>"><i class="fa fa-sign-out"></i> Log out</a></li>
			</ul>
		</div>
		<!-- end header -->
		<div class="contents">
			<?php echo element('menu_title', $layout) ? '<h3>' . element('menu_title', $layout) . '</h3>' : ''; ?>

			<!-- 여기까지 레이아웃 상단입니다 -->

			<?php echo $yield; ?>

			<!-- 여기부터 레이아웃 하단입니다 -->

		</div>
	</div>
	<!-- end content_wrapper -->
</div>
<!-- end wrapper -->
<footer class="footer">
	Powered by <a href="<?php echo config_item('ciboard_website'); ?>" target="_blank">CIBoard</a>,
	Your Version <?php echo CB_VERSION; ?>,
    Latest Version <?php echo element('latest_version_name', element('version_latest', $layout)); ?> <a href="<?php echo element('latest_download_url', element('version_latest', $layout)); ?>" target="_blank"><i class="fa fa-share-square-o"></i></a>
	<span class="btn_top"><a href="#">Top <i class="fa fa-arrow-circle-o-up fa-lg"></i></a></span>
</footer>
<script type="text/javascript">
//<![CDATA[
$(document).ready(function() {
	$(function() {
		$('#fsearch').validate({
			rules: {
				skeyword: { required:true, minlength:2}
			}
		});
	});
});
//]]>
</script>
</body>
</html>
