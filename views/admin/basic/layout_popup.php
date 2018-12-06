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
var admin_skin_path = "<?php echo element('layout_skin_dir', $layout); ?>";
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
<!-- 여기까지 레이아웃 상단입니다 -->

<?php echo $yield; ?>

<!-- 여기부터 레이아웃 하단입니다 -->
</body>
</html>
