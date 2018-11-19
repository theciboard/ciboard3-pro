<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<title>LG유플러스 전자결제 본인확인서비스</title>
<script language="javascript" src="//xpay.uplus.co.kr/xpay/js/xpay_authonly.js" type="text/javascript" charset="EUC-KR"></script>
<script type="text/javascript">
function do_Authonly() {
	ret = xpay_authonly_check(document.getElementById("LGD_PAYINFO"), document.getElementById("CST_PLATFORM").value);
	if (ret == "00"){	 //ActiveX 로딩 성공
		if(dpop.getData("LGD_RESPCODE") == "0000"){
			document.getElementById("LGD_AUTHONLYKEY").value = dpop.getData("LGD_AUTHONLYKEY");
			document.getElementById("LGD_PAYTYPE").value = dpop.getData("LGD_PAYTYPE");
			//alert("인증요청을 합니다.");
			document.getElementById("LGD_PAYINFO").submit();
		} else {
			alert(dpop.getData("LGD_RESPMSG"));
		}
	} else {
		alert("LG유플러스 본인확인서비스를 위한 ActiveX 설치 실패\nInternet Explorer 외의 브라우저에서는 사용할 수 없습니다.");
		//window.close();
	}
}
</script>
</head>
<body>
<form method="post" id="LGD_PAYINFO" action="<?php echo site_url('selfcert/lg_phone_return'); ?>">
<input type="hidden" name="CST_MID" id="CST_MID" value="<?php echo element('CST_MID', $view); ?>" />
<input type="hidden" name="LGD_MID" id="LGD_MID" value="<?php echo element('LGD_MID', $view); ?>"/>
<input type="hidden" name="CST_PLATFORM" id="CST_PLATFORM" value="<?php echo element('CST_PLATFORM', $view); ?>"/>
<input type="hidden" name="LGD_BUYERSSN" value="<?php echo element('LGD_BUYERSSN', $view); ?>"/>
<input type="hidden" name="LGD_BUYER" value="<?php echo element('LGD_BUYER', $view); ?>"/>
<input type="hidden" name="LGD_MOBILE_SUBAUTH_SITECD" value="<?php echo element('LGD_MOBILE_SUBAUTH_SITECD', $view); ?>"/>
<input type="hidden" name="LGD_TIMESTAMP" value="<?php echo element('LGD_TIMESTAMP', $view); ?>"/>
<input type="hidden" name="LGD_HASHDATA" value="<?php echo element('LGD_HASHDATA', $view); ?>"/>
<input type="hidden" name="LGD_NAMECHECKYN" value="N">
<input type="hidden" name="LGD_HOLDCHECKYN" value="Y">
<input type="hidden" name="LGD_CUSTOM_SKIN" value="red">
<input type="hidden" name="LGD_CUSTOM_FIRSTPAY" value="ASC007">
<input type="hidden" name="LGD_CUSTOM_USABLEPAY" value="ASC007">
<input type="hidden" name="LGD_PAYTYPE" id="LGD_PAYTYPE"/>
<input type="hidden" name="LGD_AUTHONLYKEY" id="LGD_AUTHONLYKEY"/>
</form>

<div id="uplus_win" class="alert alert-warning">
	<strong>휴대폰 본인확인</strong>
	<span class="text-danger">LG유플러스에 휴대폰 본인확인 요청 중입니다.</span>
	<div>
		<p>본인확인이 진행되지 않는다면<br /> <a href="http://pgweb.uplus.co.kr:8080/pg/wmp/Home2009/skill/payment_error_center01.jsp" target="_blank">여기</a>로 이동하여보세요.</p>
	</div>
	<div class="win_btn">
		<button type="button" class="btn btn-xs btn-danger" onclick="window.close();">창닫기</button>
	</div>
</div>
<script type="text/javascript">
setTimeout("do_Authonly();",300);
</script>
</body>
</html>