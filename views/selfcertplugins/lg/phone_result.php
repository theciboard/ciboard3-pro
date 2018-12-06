<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<title>휴대폰 본인확인</title>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
</head>
<body>

<script type="text/javascript">
$(function() {
	var $opener = window.opener;
	$opener.$("input[name=selfcert_type]").val("<?php echo element('selfcert_type', $view); ?>");
	alert("본인의 휴대폰번호로 확인 되었습니다.");
<?php if (element('redirecturl', $view)) { ?>
	$opener.location.href='<?php echo element('redirecturl', $view); ?>';
<?php } ?>
	window.close();
});
</script>

</body>
</html>
