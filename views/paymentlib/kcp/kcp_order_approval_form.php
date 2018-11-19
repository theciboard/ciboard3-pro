<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="ko" xml:lang="ko">
<head>
<title>스마트폰 웹 결제창</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Cache-Control" content="No-Cache">
<meta http-equiv="Pragma" content="No-Cache">
<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=0,maximum-scale=10">
<meta name="HandheldFriendly" content="true">
<meta name="format-detection" content="telephone=no">

<style type="text/css">
.LINE { background-color:#afc3ff }
.HEAD { font-family:"굴림","굴림체"; font-size:9pt; color:#065491; background-color:#eff5ff; text-align:left; padding:3px; }
.TEXT { font-family:"굴림","굴림체"; font-size:9pt; color:#000000; background-color:#FFFFFF; text-align:left; padding:3px; }
B { font-family:"굴림","굴림체"; font-size:13pt; color:#065491;}
INPUT { font-family:"굴림","굴림체"; font-size:9pt; }
SELECT{font-size:9pt;}
.COMMENT { font-family:"굴림","굴림체"; font-size:9pt; line-height:160% }
</style>
<script type="text/javascript">
// 자바스크립트에서 사용하는 전역변수 선언
var cb_url = "<?php echo trim(site_url(), '/'); ?>";
</script>
<!-- 거래등록 하는 kcp 서버와 통신을 위한 스크립트-->
<script src="<?php echo site_url('plugin/pg/kcp/approval_key.js'); ?>"></script>

<script language="javascript">
/* kcp web 결제창 호출 (변경불가)*/
function call_pay_form()
{

var v_frm = document.sm_form;

	layer_cont_obj = document.getElementById('content');
	layer_receipt_obj = document.getElementById('layer_receipt');

	layer_cont_obj.style.display = "none";
	layer_receipt_obj.style.display = "block";

	v_frm.target = "frm_receipt";
	v_frm.action = PayUrl;

	if (v_frm.Ret_URL.value === '') {
		/* Ret_URL값은 현 페이지의 URL 입니다. */
		alert('연동시 Ret_URL을 반드시 설정하셔야 됩니다.');
		document.location.href = "<?php echo element('js_return_url', element('data', $view)); ?>";
		return false;
	}

	v_frm.submit();
}


/* kcp 통신을 통해 받은 암호화 정보 체크 후 결제 요청*/
function chk_pay()
{
	/*kcp 결제서버에서 가맹점 주문페이지로 폼값을 보내기위한 설정(변경불가)*/
	self.name = "tar_opener";

	var sm_form = document.sm_form;

	if (sm_form.res_cd.value === '3001')
	{
		alert('사용자가 취소하였습니다.');
		document.location.href = '<?php echo element('js_return_url', element('data', $view)); ?>';
		return false;
	}
	else if (sm_form.res_cd.value === '3000')
	{
		alert('30만원 이상 결제 할수 없습니다.');
		document.location.href = "<?php echo element('js_return_url', element('data', $view)); ?>";
		return false;
	}

	if (sm_form.enc_data.value !== '' && sm_form.enc_info.value !== '' && sm_form.tran_cd.value != '')
	{
		document.getElementById('pay_fail').style.display = 'none';
		document.getElementById('show_progress').style.display = 'block';
		setTimeout( function() {
			document.fpayment.submit();
		}, 300);
	} else {
		kcp_AJAX();
	}
}
</script>
</head>
<body onload="chk_pay();">

<div id="content">
<?php
if (element('enc_data', element('data', $view)) && element('enc_info', element('data', $view)) && element('tran_cd', element('data', $view))) {

	// 제외할 필드
	$exclude = array('req_tx', 'res_cd', 'tran_cd', 'ordr_idxx', 'good_mny', 'good_name', 'buyr_name', 'buyr_tel1', 'buyr_tel2', 'buyr_mail', 'enc_info', 'enc_data', 'use_pay_method', 'rcvr_name', 'rcvr_tel1', 'rcvr_tel2', 'rcvr_mail', 'rcvr_zipx', 'rcvr_add1', 'rcvr_add2', 'param_opt_1', 'param_opt_2', 'param_opt_3');

	$attributes = array('name' => 'fpayment', 'id' => 'fpayment');
	echo form_open(element('order_action_url', element('data', $view)), $attributes);

	foreach (element('pod_data', $view) as $key => $value) {
		if (in_array($key, $exclude)) {
			continue;
		}

		if (is_array($value)) {
			foreach ($value as $k => $v) {
				echo '<input type="hidden" name="' . $key . '[' . $k . ']" value="' . $v . '" />' . PHP_EOL;
			}
		} else {
			echo '<input type="hidden" name="' . $key . '" value="' . $value . '" />' . PHP_EOL;
		}
	}

	foreach ($this->input->post() as $key => $value) {
?>
		<input type="hidden" name="<?php echo $key; ?>" value="<?php echo $value; ?>" />
<?php
	}
	echo form_close();
}

$shop_name = $this->cbconfig->item('company_name') ? $this->cbconfig->item('company_name') : $this->cbconfig->item('cmall_name');
$pay_method = element('pay_method', element('data', $view)) ? element('pay_method', element('data', $view)) : '';
?>

	<form name="sm_form" method="POST" accept-charset="euc-kr">
		<input type="hidden" name="csrf_test_name" value="<?php echo $this->security->get_csrf_hash(); ?>" style="display:none;" />
		<input type="hidden" name="good_name" value="<?php echo element('good_name', element('data', $view)); ?>" />
		<input type="hidden" name="good_mny" value="<?php echo element('good_mny', element('data', $view)); ?>" >
		<input type="hidden" name="buyr_name" value="<?php echo element('buyr_name', element('data', $view)); ?>" />
		<input type="hidden" name="buyr_tel1" value="<?php echo element('buyr_tel1', element('data', $view)); ?>" />
		<input type="hidden" name="buyr_tel2" value="<?php echo element('buyr_tel2', element('data', $view)); ?>" />
		<input type="hidden" name="buyr_mail" value="<?php echo element('buyr_mail', element('data', $view)); ?>" />
		<input type="hidden" name="ipgm_date" value="<?php echo element('ipgm_date', element('data', $view)); ?>" />

		<!-- 필수 사항 -->

		<!-- 요청 구분 -->
		<input type="hidden" name="req_tx" value="pay" />
		<!-- 사이트 코드 -->
		<input type="hidden" name="site_cd" value="<?php echo element('pg_kcp_mid', element('pg', $view)); ?>" />
		<!-- 사이트 이름 -->
		<input type="hidden" name="shop_name" value="<?php echo $shop_name; ?>" />
		<!-- 결제수단-->
		<input type="hidden" name="pay_method" value="<?php echo $pay_method; ?>" />
		<!-- 주문번호 -->
		<input type="hidden" name="ordr_idxx" value="<?php echo element('ordr_idxx', element('data', $view)); ?>" />
		<!-- 최대 할부개월수 -->
		<input type="hidden" name="quotaopt" value="12" />
		<!-- 통화 코드 -->
		<input type="hidden" name="currency" value="410" />
		<!-- 결제등록 키 -->
		<input type="hidden" name="approval_key" id="approval" />
		<!-- 리턴 URL (kcp와 통신후 결제를 요청할 수 있는 암호화 데이터를 전송 받을 가맹점의 주문페이지 URL) -->
		<!-- 반드시 가맹점 주문페이지의 URL을 입력 해주시기 바랍니다. -->
		<input type="hidden" name="Ret_URL" value="<?php echo site_url('payment/kcp_order_approval_form/').element('ptype', $view); ?>" />
		<!-- 인증시 필요한 파라미터(변경불가)-->
		<input type="hidden" name="ActionResult" value="<?php echo element('ActionResult', element('data', $view)); ?>" />
		<!-- 에스크로 사용유무 에스크로 사용 업체(가상계좌만 해당)는 Y로 세팅 해주시기 바랍니다.-->
		<input type="hidden" name="escw_used" value="Y" />
		<!-- 에스크로 결제처리모드 -->
		<input type="hidden" name="pay_mod" value="N" />
		<!-- 수취인이름 -->
		<input type="hidden" name="rcvr_name" value="<?php echo element('rcvr_name', element('data', $view)); ?>" />
		<!-- 수취인 연락처 -->
		<input type="hidden" name="rcvr_tel1" value="<?php echo element('rcvr_tel1', element('data', $view)); ?>" />
		<!-- 수취인 휴대폰 번호 -->
		<input type="hidden" name="rcvr_tel2" value="<?php echo element('rcvr_tel2', element('data', $view)); ?>" />
		<!-- 수취인 E-MAIL -->
		<input type="hidden" name="rcvr_add1" value="<?php echo element('rcvr_add1', element('data', $view)); ?>" />
		<!-- 수취인 우편번호 -->
		<input type="hidden" name="rcvr_add2" value="<?php echo element('rcvr_add2', element('data', $view)); ?>" />
		<!-- 수취인 주소 -->
		<input type="hidden" name="rcvr_mail" value="<?php echo element('rcvr_mail', element('data', $view)); ?>" />
		<!-- 수취인 상세 주소 -->
		<input type="hidden" name="rcvr_zipx" value="<?php echo element('rcvr_zipx', element('data', $view)); ?>" />
		<!-- 장바구니 상품 개수 -->
		<input type="hidden" name="bask_cntx" value="<?php echo element('bask_cntx', element('data', $view)); ?>" />
		<!-- 장바구니 정보(상단 스크립트 참조) -->
		<input type="hidden" name="good_info" value="<?php echo element('good_info', element('data', $view)); ?>" />
		<!-- 배송소요기간 -->
		<input type="hidden" name="deli_term" value="03" />
		<!-- 기타 파라메터 추가 부분 - Start - -->
		<input type="hidden" name="param_opt_1"	value="<?php echo element('param_opt_1', element('data', $view)); ?>" />
		<input type="hidden" name="param_opt_2"	value="<?php echo element('param_opt_2', element('data', $view)); ?>" />
		<input type="hidden" name="param_opt_3"	value="<?php echo element('param_opt_3', element('data', $view)); ?>" />
		<input type="hidden" name="disp_tax_yn" value="N" />
		<!-- 기타 파라메터 추가 부분 - End - -->
		<!-- 화면 크기조정 부분 - Start - -->
		<input type="hidden" name="tablet_size"	value="<?php echo element('tablet_size', element('data', $view)); ?>" />
		<!-- 화면 크기조정 부분 - End - -->
		<!--
			사용 카드 설정
			<input type="hidden" name="used_card" value="CClg:ccDI">
			/* 무이자 옵션
					※ 설정할부 (가맹점 관리자 페이지에 설정 된 무이자 설정을 따른다) - "" 로 설정
					※ 일반할부 (KCP 이벤트 이외에 설정 된 모든 무이자 설정을 무시한다) - "N" 로 설정
					※ 무이자 할부 (가맹점 관리자 페이지에 설정 된 무이자 이벤트 중 원하는 무이자 설정을 세팅한다) - "Y" 로 설정
			<input type="hidden" name="kcp_noint" value=""/> */

			/* 무이자 설정
					※ 주의 1 : 할부는 결제금액이 50,000 원 이상일 경우에만 가능
					※ 주의 2 : 무이자 설정값은 무이자 옵션이 Y일 경우에만 결제 창에 적용
					예) 전 카드 2,3,6개월 무이자(국민,비씨,엘지,삼성,신한,현대,롯데,외환) : ALL-02:03:04
					BC 2,3,6개월, 국민 3,6개월, 삼성 6,9개월 무이자 : CCBC-02:03:06,CCKM-03:06,CCSS-03:06:04
			<input type="hidden" name="kcp_noint_quota" value="CCBC-02:03:06,CCKM-03:06,CCSS-03:06:09"/> */
		-->
		<input type="hidden" name="kcp_noint" value="<?php echo ($this->cbconfig->item('use_pg_no_interest') ? '' : 'N'); ?>" />

		<input type="hidden" name="res_cd" value="<?php echo element('res_cd', element('data', $view)); ?>" /> <!-- 결과 코드 -->
		<input type="hidden" name="tran_cd" value="<?php echo element('tran_cd', element('data', $view)); ?>" /> <!-- 트랜잭션 코드 -->
		<input type="hidden" name="enc_info" value="<?php echo element('enc_info', element('data', $view)); ?>" /> <!-- 암호화 정보 -->
		<input type="hidden" name="enc_data" value="<?php echo element('enc_data', element('data', $view)); ?>" /> <!-- 암호화 데이터 -->
	</form>

	<div id="pay_fail">
		<p>결제가 실패한 경우 아래 돌아가기 버튼을 클릭해주세요.</p>
		<a href="<?php echo element('js_return_url', element('data', $view)); ?>">돌아가기</a>
	</div>
	<div id="show_progress" style="display:none;">
		<span style="display:block; text-align:center;margin-top:120px"><img src="<?php echo site_url(VIEW_DIR . 'paymentlib/images/ajax-loader.gif'); ?>" alt="주문완료중" title="주문완료중" /></span>
		<span style="display:block; text-align:center;margin-top:10px; font-size:14px">주문완료 중입니다. 잠시만 기다려 주십시오.</span>
	</div>
</div>

<!-- 스마트폰에서 KCP 결제창을 레이어 형태로 구현-->
<div id="layer_receipt" style="position:absolute; left:1px; top:1px; width:310;height:400; z-index:1; display:none;">
	<table width="310" border="-" cellspacing="0" cellpadding="0" style="text-align:center">
		<tr>
			<td>
				<iframe name="frm_receipt" frameborder="0" border="0" width="310" height="400" scrolling="auto"></iframe>
			</td>
		</tr>
	</table>
</div>
</body>
</html>
