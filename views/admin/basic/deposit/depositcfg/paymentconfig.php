<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style type="text/css">
/* 외부서비스 사이트코드 */
.pg_setting .sitecode {display:inline-block;font:bold 15px 'Verdana';vertical-align:middle}

.form-group .pg_info .form-inline{background-color:#FFF;padding-bottom:10px}
.pg_setting .pg_kcp{background-color:#F6FCFF}
.pg_setting .pg_lg{background-color:#FFF4FA}
.pg_setting .pg_inicis{background-color:#F6F1FF}
.pg_setting .kcp_btn{display:inline-block;margin:5px 0 0;padding:5px 10px;background:#226C8B;color:#fff;font-weight:normal;text-decoration:none}
.kcp_btn:hover, .kcp_btn:active{color:#fff}
.pg_setting .lg_btn{display:inline-block;margin:5px 0 0;padding:5px 10px;background:#ED008C;color:#fff;font-weight:normal;text-decoration:none}
.pg_setting .lg_btn:hover, .lg_btn:active{color:#fff}
.pg_setting .kg_btn{display:inline-block;margin:5px 0 0;padding:5px 10px;background:#4A2C7C;color:#fff;font-weight:normal;text-decoration:none}
.pg_setting .kg_btn:hover, .kg_btn:active{color:#fff}
.pg_setting .pg_input{font:bold 15px Consolas}
</style>
<div class="box pg_setting">
	<div class="box-header">
		<ul class="nav nav-tabs">
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir); ?>" onclick="return check_form_changed();">기본설정</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/layout'); ?>" onclick="return check_form_changed();">레이아웃/메타태그</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/smsconfig'); ?>" onclick="return check_form_changed();">SMS 설정</a></li>
			<li role="presentation" class="active"><a href="<?php echo admin_url($this->pagedir . '/paymentconfig'); ?>" onclick="return check_form_changed();">결제기능</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/alarm'); ?>" onclick="return check_form_changed();">알림설정</a></li>
		</ul>
	</div>
	<div class="box-table">
		<?php
		echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
		echo show_alert_message(element('alert_message', $view), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
		$attributes = array('class' => 'form-horizontal', 'name' => 'fadminwrite', 'id' => 'fadminwrite');
		echo form_open(current_full_url(), $attributes);
		?>
			<input type="hidden" name="is_submit" value="1" />
			<div class="form-group">
				<label class="col-sm-2 control-label">현금/카드 결제시 결제 가능 방법</label>
				<div class="col-sm-10 form-inline">
					<label for="use_payment_bank" class="checkbox-inline">
						<input type="checkbox" name="use_payment_bank" id="use_payment_bank" value="1" <?php echo set_checkbox('use_payment_bank', '1', (element('use_payment_bank', element('data', $view)) ? true : false)); ?> /> 무통장입금
					</label>
					<label for="use_payment_card" class="checkbox-inline">
						<input type="checkbox" name="use_payment_card" id="use_payment_card" value="1" <?php echo set_checkbox('use_payment_card', '1', (element('use_payment_card', element('data', $view)) ? true : false)); ?> /> 카드결제
					</label>
					<label for="use_payment_realtime" class="checkbox-inline">
						<input type="checkbox" name="use_payment_realtime" id="use_payment_realtime" value="1" <?php echo set_checkbox('use_payment_realtime', '1', (element('use_payment_realtime', element('data', $view)) ? true : false)); ?> /> 실시간계좌이체
					</label>
					<label for="use_payment_vbank" class="checkbox-inline">
						<input type="checkbox" name="use_payment_vbank" id="use_payment_vbank" value="1" <?php echo set_checkbox('use_payment_vbank', '1', (element('use_payment_vbank', element('data', $view)) ? true : false)); ?> /> 가상계좌결제
					</label>
					<label for="use_payment_phone" class="checkbox-inline">
						<input type="checkbox" name="use_payment_phone" id="use_payment_phone" value="1" <?php echo set_checkbox('use_payment_phone', '1', (element('use_payment_phone', element('data', $view)) ? true : false)); ?> /> 핸드폰결제
					</label>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-2 control-label">결제대행사</label>
				<div class="col-sm-10 form-inline">
					<select name="use_payment_pg" class="form-control" id="use_payment_pg">
						<option value="kcp" <?php echo set_select('use_payment_pg', 'kcp', (element('use_payment_pg', element('data', $view)) === 'kcp' ? true : false)); ?> >KCP</option>
						<option value="lg" <?php echo set_select('use_payment_pg', 'lg', (element('use_payment_pg', element('data', $view)) === 'lg' ? true : false)); ?> >LG유플러스</option>
						<option value="inicis" <?php echo set_select('use_payment_pg', 'inicis', (element('use_payment_pg', element('data', $view)) === 'inicis' ? true : false)); ?> >KG이니시스</option>
					</select>
					<ul class="de_pg_tab">
						<li class="<?php if((element('use_payment_pg', element('data', $view)) === 'kcp')) echo 'tab-current'; ?>"><a href="#kcp_info_anchor" data-value="kcp" title="NHN KCP 선택하기" >NHN KCP</a></li>
						<li class="<?php if((element('use_payment_pg', element('data', $view)) === 'lg')) echo 'tab-current'; ?>"><a href="#lg_info_anchor" data-value="lg" title="LG유플러스 선택하기">LG유플러스</a></li>
						<li class="<?php if((element('use_payment_pg', element('data', $view)) === 'inicis')) echo 'tab-current'; ?>"><a href="#inicis_info_anchor" data-value="inicis" title="KG이니시스 선택하기">KG이니시스</a></li>
					</ul>
					<div class="help-block">쇼핑몰에서 사용할 결제대행사를 선택합니다.</div>
				</div>
			</div>

			<div class="form-group" id="kcp_info_anchor">
				<div class="pg_info pg_kcp clearfix">
					<div class="col-sm-2 control-label">
						<label for="pg_kcp_mid">KCP SITE CODE</label>
					</div>
					<div class="col-sm-10 form-inline">
						<input type="text" class="form-control pg_input" name="pg_kcp_mid" id="pg_kcp_mid" value="<?php echo set_value('pg_kcp_mid', element('pg_kcp_mid', element('data', $view))); ?>" />
						<div class="help-block">NHN KCP 에서 받은 영대문자, 숫자 혼용 총 5자리를 입력하세요.</div>
					</div>
				</div>
				<div class="pg_info pg_kcp clearfix">
					<label class="col-sm-2 control-label" for="pg_kcp_key">NHN KCP SITE KEY</label>
					<div class="col-sm-10 form-inline">
						<input type="text" class="form-control" name="pg_kcp_key" id="pg_kcp_key" value="<?php echo set_value('pg_kcp_key', element('pg_kcp_key', element('data', $view))); ?>" size="30" />
						<div class="help-block">25자리 영대소문자와 숫자 - 그리고 _ 로 이루어 집니다. SITE KEY 발급 NHN KCP 전화: 1544-8660<br>예) 1Q9YRV83gz6TukH8PjH0xFf__</div>
					</div>
				</div>
				<div class="pg_info pg_kcp clearfix pg_kcp_info_view pg_info_hide">
					<label class="col-sm-2 control-label" for="pg_kcp_vbank_url">NHN KCP 가상계좌 입금통보 URL</label>
					<div class="col-sm-10 form-inline">
						<div class="help-block">NHN KCP 가상계좌 사용시 다음 주소를 <strong><a href="http://admin.kcp.co.kr" target="_blank">NHN KCP 관리자</a> &gt; 상점정보관리 &gt; 정보변경 &gt; 공통URL 정보 &gt; 공통URL 변경후</strong>에 넣으셔야 상점에 자동으로 입금 통보됩니다.</div>
						<div class="kcp_url_address">
						<?php echo site_url('payment/kcp_return_result'); ?>
						</div>
					</div>
				</div>
			</div>

			<div class="form-group" id="lg_info_anchor">
				<div class="pg_info pg_lg clearfix">
					<div class="col-sm-2 control-label">
						<label for="pg_lg_mid">LG유플러스 상점아이디</label>
					</div>
					<div class="col-sm-10 form-inline">
						<input type="text" class="form-control pg_input" name="pg_lg_mid" id="pg_lg_mid" value="<?php echo set_value('pg_lg_mid', element('pg_lg_mid', element('data', $view))); ?>" />
						<div class="help-block">LG유플러스에서 받은 상점 ID를 입력하세요.</div>
					</div>
				</div>
				<div class="pg_info pg_lg clearfix">
					<label class="col-sm-2 control-label" for="pg_lg_key">LG유플러스 MERT KEY</label>
					<div class="col-sm-10 form-inline">
						<input type="text" class="form-control" name="pg_lg_key" id="pg_lg_key" value="<?php echo set_value('pg_lg_key', element('pg_lg_key', element('data', $view))); ?>" size="40" />
						<div class="help-block">LG유플러스 상점MertKey는 상점관리자 -> 계약정보 -> 상점정보관리에서 확인하실 수 있습니다.<br>예) 95160cce09854ef44d2edb2bfb05f9f3</div>
					</div>
				</div>
			</div>

			<div class="form-group" id="inicis_info_anchor">
				<div class="clearfix pg_info pg_inicis">
					<div class="col-sm-2 control-label">
						<label for="pg_inicis_mid">KG이니시스 상점아이디</label>
					</div>
					<div class="col-sm-10 form-inline">
						<input type="text" class="form-control pg_input" name="pg_inicis_mid" id="pg_inicis_mid" value="<?php echo set_value('pg_inicis_mid', element('pg_inicis_mid', element('data', $view))); ?>" />
						<div class="help-block">
						KG이니시스로 부터 발급 받으신 상점아이디(MID) 10자리를 입력 합니다.
						</div>
					</div>
				</div>
				<div class="clearfix pg_info pg_inicis">
					<label class="col-sm-2 control-label" for="pg_inicis_key">KG이니시스 키패스워드</label>
					<div class="col-sm-10 form-inline">
						<input type="text" class="form-control" name="pg_inicis_key" id="pg_inicis_key" value="<?php echo set_value('pg_inicis_key', element('pg_inicis_key', element('data', $view))); ?>" />
						<div class="help-block">KG이니시스에서 발급받은 4자리 상점 키패스워드를 입력합니다.<br>KG이니시스 상점관리자 패스워드와 관련이 없습니다.<br>키패스워드 값을 확인하시려면 상점측에 발급된 키파일 안의 readme.txt 파일을 참조해 주십시오</div>
					</div>
				</div>
				<div class="clearfix pg_info pg_inicis">
					<label class="col-sm-2 control-label" for="pg_inicis_websign">KG이니시스 웹결제 사인키</label>
					<div class="col-sm-10 form-inline">
						<input type="text" class="form-control" name="pg_inicis_websign" id="pg_inicis_websign" value="<?php echo set_value('pg_inicis_websign', element('pg_inicis_websign', element('data', $view))); ?>" size="40" />
						<div class="help-block">KG이니시스에서 발급받은 웹결제 사인키를 입력합니다.<br>관리자 페이지의 상점정보 > 계약정보 > 부가정보의 웹결제 signkey생성 조회 버튼 클릭, 팝업창에서 생성 버튼 클릭 후 해당 값을 입력합니다.</div>
					</div>
				</div>
				<div class="clearfix pg_info pg_inicis pg_inicis_info_view pg_info_hide">
					<label class="col-sm-2 control-label" for="pg_inicis_vbankurl">KG이니시스 가상계좌 입금통보 URL</label>
					<div class="col-sm-10 form-inline">
						<div class="help-block">KG이니시스 가상계좌 사용시 다음 주소를 <strong><a href="https://iniweb.inicis.com/" target="_blank">KG이니시스 관리자</a> &gt; 거래조회 &gt; 가상계좌 &gt; 입금통보방식선택 &gt; URL 수신 설정</strong>에 넣으셔야 상점에 자동으로 입금 통보됩니다.</div>
						<div class="inicis_url_address">
							<?php echo site_url('payment/inicis_return_result'); ?>
						</div>
					</div>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-2 control-label">무이자할부 기능 사용</label>
				<div class="col-sm-10">
					<label for="use_pg_no_interest" class="checkbox-inline">
						<input type="checkbox" name="use_pg_no_interest" id="use_pg_no_interest" value="1" <?php echo set_checkbox('use_pg_no_interest', '1', (element('use_pg_no_interest', element('data', $view)) ? true : false)); ?> /> 사용합니다
					</label>
					<div class="help-block">이 기능을 사용하시면, PG사 가맹점 관리자 페이지에서 설정하신 무이자할부 설정이 적용됩니다.<br />
					사용안하시면 PG사 무이자 이벤트 카드를 제외한 모든 카드의 무이자 설정이 적용되지 않습니다.</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">실결제여부</label>
				<div class="col-sm-10">
					<label class="radio-inline" for="use_pg_test_2" >
						<input type="radio" name="use_pg_test" id="use_pg_test_2" value="0" <?php echo set_checkbox('use_pg_test', '0', ( ! element('use_pg_test', element('data', $view)) ? true : false)); ?> /> 실결제
					</label>
					<label class="radio-inline" for="use_pg_test_1" >
						<input type="radio" name="use_pg_test" id="use_pg_test_1" value="1" <?php echo set_checkbox('use_pg_test', '1', (element('use_pg_test', element('data', $view)) ? true : false)); ?> /> 테스트 결제
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">계좌안내(무통장입금시)</label>
				<div class="col-sm-10">
					<textarea class="form-control" rows="3" name="payment_bank_info"><?php echo set_value('payment_bank_info', element('payment_bank_info', element('data', $view))); ?></textarea>
					<div class="help-block">예) 00은행 123-456-7890 예금주 : 홍길동</div>
				</div>
			</div>
			<div class="btn-group pull-right" role="group" aria-label="...">
				<button type="submit" class="btn btn-success btn-sm">저장하기</button>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>

<script type="text/javascript">
//<![CDATA[
jQuery(function($) {
	$('#fadminwrite').validate({
		rules: {
			use_payment_pg: {required :true}
		}
	});

	$("#use_payment_pg").hide();
	
	/*
	$('.pg_info').hide();
	$('.pg_<?php echo element('use_payment_pg', element('data', $view)); ?>').show();
	$('#use_payment_pg').on('change', function() {
		var pg = $(this).val();
		$('.pg_info').hide();
		$('.pg_' + pg).show();
	});
	*/

	$(".de_pg_tab").on("click", "a", function(e){

		var pg = $(this).attr("data-value"),
			class_name = "tab-current";
		
		$("#use_payment_pg").val(pg)
			.trigger("payment_change");

		$(this).parent("li").addClass(class_name).siblings().removeClass(class_name);

	});

	$("#use_payment_vbank").on("click", function(e){
		$("#use_payment_pg").trigger("payment_change");
	});

	$("#use_payment_pg").on("payment_change", function(e){

		$(".pg_info_hide").hide();

		var $pg = $(this).val(),
			$info_anchor = $("#"+$pg+"_info_anchor");

		if( $("#use_payment_vbank").prop( "checked" ) && $info_anchor.length ){
			$info_anchor.find(".pg_info_hide").show();
		}

	});

	$("#use_payment_pg").trigger("payment_change");
});

var form_original_data = $('#fadminwrite').serialize();
function check_form_changed() {
	if ($('#fadminwrite').serialize() !== form_original_data) {
		if (confirm('저장하지 않은 정보가 있습니다. 저장하지 않은 상태로 이동하시겠습니까?')) {
			return true;
		} else {
			return false;
		}
	}
	return true;
}
//]]>
</script>
