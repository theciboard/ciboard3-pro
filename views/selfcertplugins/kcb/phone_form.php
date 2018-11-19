<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<title>휴대폰 본인확인</title>
</head>
<body>

<script type="text/javascript">
function request(){
	document.form1.action = "<?php echo element('commonSvlUrl', element('kcbconfig', $view)); ?>";
	document.form1.method = "post";

	document.form1.submit();
}
</script>

<form name="form1">
<!-- 인증 요청 정보 -->
<!--// 필수 항목 -->
<input type="hidden" name="tc" value="kcb.oknm.online.safehscert.popup.cmd.P901_CertChoiceCmd"> <!-- 변경불가-->
<input type="hidden" name="rqst_data"				value="<?php echo element('e_rqstData', $view); ?>">			<!-- 요청데이터 -->
<input type="hidden" name="target_id"				value="">			<!-- 타겟ID -->
<!-- 필수 항목 //-->
</form>

<?php
if (element('retcode', $view) == "B000") {
	//인증요청
	echo '<script>request();</script>';
} else {
	//요청 실패 페이지로 리턴
	echo '<script>alert("' . element('retcode', $view) . '"); self.close();</script>';
}
?>

</body>
</html>
