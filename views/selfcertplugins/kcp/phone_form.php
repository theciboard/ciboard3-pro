<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<title>휴대폰 본인확인</title>
</head>
<body>

<form name="form_auth" method="post" target="auth_popup" action="<?php echo element('cert_url', element('kcpconfig', $view)); ?>">
<!-- 유저네임 -->
<input type="hidden" name="user_name"	value="" />
<!-- 주문번호 -->
<input type="hidden" name="ordr_idxx"	value="<?php echo element('ordr_idxx', $view); ?>">
<!-- 요청종류 -->
<input type="hidden" name="req_tx"	   value="cert"/>
<!-- 인증종류 -->
<input type="hidden" name="cert_type"	value="01"/>
<!-- 웹사이트아이디 -->
<input type="hidden" name="web_siteid"   value=""/>
<!-- 노출 통신사 default 처리시 아래의 주석을 해제하고 사용하십시요
	 SKT : SKT , KT : KTF , LGU+ : LGT
<input type="hidden" name="fix_commid"	  value="KTF"/>
-->
<!-- 사이트코드 -->
<input type="hidden" name="site_cd"	  value="<?php echo element('site_cd', element('kcpconfig', $view)); ?>" />
<!-- Ret_URL : 인증결과 리턴 페이지 ( 가맹점 URL 로 설정해 주셔야 합니다. ) -->
<input type="hidden" name="Ret_URL"	  value="<?php echo site_url('selfcert/kcp_phone_return'); ?>" />
<!-- cert_otp_use 필수 ( 메뉴얼 참고)
	 Y : 실명 확인 + OTP 점유 확인 , N : 실명 확인 only
-->
<input type="hidden" name="cert_otp_use" value="Y"/>
<!-- cert_enc_use 필수 (고정값 : 메뉴얼 참고) -->
<input type="hidden" name="cert_enc_use" value="Y"/>

<input type="hidden" name="res_cd"	   value=""/>
<input type="hidden" name="res_msg"	  value=""/>

<input type="hidden" name="up_hash" value="<?php echo element('up_hash', $view); ?>"/>

<!-- up_hash 검증 을 위한 필드 -->
<input type="hidden" name="veri_up_hash" value=""/>

<!-- 가맹점 사용 필드 (인증완료시 리턴)-->
<input type="hidden" name="param_opt_1"  value="opt1"/>
<input type="hidden" name="param_opt_2"  value="opt2"/>
<input type="hidden" name="param_opt_3"  value="opt3"/>
</form>

<script type="text/javascript">
document.form_auth.submit();
</script>


</body>
</html>
