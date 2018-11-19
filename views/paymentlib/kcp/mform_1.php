<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// 전자결제를 사용할 때만 실행
if ($this->cbconfig->item('use_payment_card')
	OR $this->cbconfig->item('use_payment_realtime')
	OR $this->cbconfig->item('use_payment_vbank')
	OR $this->cbconfig->item('use_payment_phone')
	OR $this->cbconfig->item('use_payment_easy')) {

} else {
	return;
}
?>
<!-- 거래등록 하는 kcp 서버와 통신을 위한 스크립트-->
<script src="<?php echo site_url('plugin/pg/kcp/approval_key.js'); ?>"></script>

<form name="sm_form" method="POST" action="<?php echo site_url('payment/kcp_order_approval_form/' . element('ptype', $view)); ?>">
	<input type="hidden" name="csrf_test_name" value="<?php echo $this->security->get_csrf_hash(); ?>" style="display:none;" />
	<input type="hidden" name="ptype" value="<?php echo element('ptype', $view); ?>" />

	<input type="hidden" name="good_name" value="" />
	<input type="hidden" name="good_mny" value="" >
	<input type="hidden" name="buyr_name" value="" />
	<input type="hidden" name="buyr_tel1" value="" />
	<input type="hidden" name="buyr_tel2" value="" />
	<input type="hidden" name="buyr_mail" value="" />
	<input type="hidden" name="ipgm_date" value="" />
	<input type="hidden" name="pay_type" value="" />

	<!-- 주문번호 -->
	<input type="hidden" name="ordr_idxx" value="<?php echo element('unique_id', $view); ?>" />
	<!-- 결제등록 키 -->
	<input type="hidden" name="approval_key" id="approval" />
	<!-- 수취인이름 -->
	<input type="hidden" name="rcvr_name" value="" />
	<!-- 수취인 연락처 -->
	<input type="hidden" name="rcvr_tel1" value="" />
	<!-- 수취인 휴대폰 번호 -->
	<input type="hidden" name="rcvr_tel2" value="" />
	<!-- 수취인 E-MAIL -->
	<input type="hidden" name="rcvr_add1" value="" />
	<!-- 수취인 우편번호 -->
	<input type="hidden" name="rcvr_add2" value="" />
	<!-- 수취인 주소 -->
	<input type="hidden" name="rcvr_mail" value="" />
	<!-- 수취인 상세 주소 -->
	<input type="hidden" name="rcvr_zipx" value="" />
	<!-- 장바구니 상품 개수 -->
	<input type="hidden" name="bask_cntx" value="" />
	<!-- 장바구니 정보(상단 스크립트 참조) -->
	<input type="hidden" name="good_info" value="<?php echo element('good_name', $view); ?>" />
	<!-- 배송소요기간 -->
	<input type="hidden" name="deli_term" value="03" />
	<!-- 기타 파라메터 추가 부분 - Start - -->
	<input type="hidden" name="param_opt_1" value="" />
	<input type="hidden" name="param_opt_2" value="" />
	<input type="hidden" name="param_opt_3" value="" />
	<input type="hidden" name="disp_tax_yn" value="N" />
	<!-- 기타 파라메터 추가 부분 - End - -->
	<!-- 화면 크기조정 부분 - Start - -->
	<input type="hidden" name="tablet_size" value="1.0" /> <?php // 화면 사이즈 조정 - 기기화면에 맞게 수정(갤럭시탭,아이패드 - 1.85, 스마트폰 - 1.0) ; ?>
	<!-- 화면 크기조정 부분 - End - -->
	<!--
	 사용 카드 설정
	<input type="hidden" name='used_card' value="CClg:ccDI" />
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
</form>
