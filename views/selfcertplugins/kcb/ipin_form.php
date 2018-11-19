<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<title>IPIN 본인확인</title>
</head>
<body>

<form name="kcbInForm" method="post" action="<?php echo element('kcbForm_action', element('kcbconfig', $view)); ?>">
  <input type="hidden" name="IDPCODE" value="<?php echo element('idpCode', element('kcbconfig', $view)); ?>" />
  <input type="hidden" name="IDPURL" value="<?php echo element('idpUrl', element('kcbconfig', $view)); ?>" />
  <input type="hidden" name="CPCODE" value="<?php echo element('selfcert_kcb_mid', element('kcbconfig', $view)); ?>" />
  <input type="hidden" name="CPREQUESTNUM" value="<?php echo element('curtime', $view); ?>" />
  <input type="hidden" name="RETURNURL" value="<?php echo element('returnUrl', element('kcbconfig', $view)); ?>" />
  <input type="hidden" name="WEBPUBKEY" value="<?php echo element('pubkey', $view); ?>" />
  <input type="hidden" name="WEBSIGNATURE" value="<?php echo element('sig', $view); ?>" />
</form>

<script type="text/javascript">
document.kcbInForm.submit();
</script>


</body>
</html>
