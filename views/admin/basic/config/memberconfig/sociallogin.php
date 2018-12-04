<div class="box">
	<div class="box-header">
		<ul class="nav nav-tabs">
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir); ?>" onclick="return check_form_changed();">기본정보</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/registerform'); ?>" onclick="return check_form_changed();">가입폼관리</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/membermodify'); ?>" onclick="return check_form_changed();">정보수정시</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/login'); ?>" onclick="return check_form_changed();">로그인</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/alarm'); ?>" onclick="return check_form_changed();">알림 설정</a></li>
			<li role="presentation" class="active"><a href="<?php echo admin_url($this->pagedir . '/sociallogin'); ?>" onclick="return check_form_changed();">소셜로그인</a></li>
		</ul>
	</div>
	<div class="box-table">
		<?php if ( ! is_php('5.4')) { ?>
			<div class="alert alert-warning" role="alert">
				소셜 로그인 기능은 PHP 버전이 5.4.0 이상인 경우에만 지원됩니다.<br />
				이 서버의 현재 PHP 버전은 <?php echo phpversion();?> 입니다.<br />
				PHP 버전을 업그레이드한 후에 이용하여 주십시오.
			</div>
		<?php
		} else {
			echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
			echo show_alert_message(element('alert_message', $view), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
			$attributes = array('class' => 'form-horizontal', 'name' => 'fadminwrite', 'id' => 'fadminwrite');
			echo form_open(current_full_url(), $attributes);
		?>
			<input type="hidden" name="is_submit" value="1" />
			<div class="form-group">
				<label class="col-sm-2 control-label">소셜로그인</label>
				<div class="col-sm-10">
					<label for="use_sociallogin" class="checkbox-inline">
						<input type="checkbox" name="use_sociallogin" id="use_sociallogin" value="1" <?php echo set_checkbox('use_sociallogin', '1', (element('use_sociallogin', element('data', $view)) ? true : false)); ?> /> 사용합니다
					</label>
					<div class="help-block">소셜로그인 기능을 사용하시는 경우, 소셜 계정 정보로 본 사이트에 로그인이 가능합니다</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">페이스북</label>
				<div class="col-sm-10">
					<label for="use_sociallogin_facebook" class="checkbox-inline">
						<input type="checkbox" name="use_sociallogin_facebook" id="use_sociallogin_facebook" class="chk" value="1" <?php echo set_checkbox('use_sociallogin_facebook', '1', (element('use_sociallogin_facebook', element('data', $view)) ? true : false)); ?> /> 페이스북 로그인을 사용합니다
					</label>
					<a href="http://www.ciboard.co.kr/tiptech/p/134" class="btn btn-xs btn-warning" target="_blank">설정방법 자세히 보기</a>
				</div>
			</div>
			<div class="form-group">
				<span class="col-sm-2 text-right" style="padding-top:7px;">페이스북 APP ID</span>
				<div class="col-sm-10">
					<input type="text" class="form-control px400" name="facebook_app_id" id="facebook_app_id" value="<?php echo set_value('facebook_app_id', element('facebook_app_id', element('data', $view))); ?>" />
				</div>
			</div>
			<div class="form-group">
				<span class="col-sm-2 text-right" style="padding-top:7px;">페이스북 Secret</span>
				<div class="col-sm-10">
					<input type="text" class="form-control px400" name="facebook_secret" id="facebook_secret" value="<?php echo set_value('facebook_secret', element('facebook_secret', element('data', $view))); ?>" />
				</div>
			</div>
			<hr />
			<div class="form-group">
				<label class="col-sm-2 control-label">트위터</label>
				<div class="col-sm-10">
					<label for="use_sociallogin_twitter" class="checkbox-inline">
						<input type="checkbox" name="use_sociallogin_twitter" id="use_sociallogin_twitter" class="chk" value="1" <?php echo set_checkbox('use_sociallogin_twitter', '1', (element('use_sociallogin_twitter', element('data', $view)) ? true : false)); ?> /> 트위터 로그인을 사용합니다
					</label>
					<a href="http://www.ciboard.co.kr/tiptech/p/135" class="btn btn-xs btn-warning" target="_blank">설정방법 자세히 보기</a>
				</div>
			</div>
			<div class="form-group">
				<span class="col-sm-2 text-right" style="padding-top:7px;">트위터 Consumer Key</span>
				<div class="col-sm-10">
					<input type="text" class="form-control px400" name="twitter_consumer_key" id="twitter_consumer_key" value="<?php echo set_value('twitter_consumer_key', element('twitter_consumer_key', element('data', $view))); ?>" />
				</div>
			</div>
			<div class="form-group">
				<span class="col-sm-2 text-right" style="padding-top:7px;">트위터 Consumer Secret</span>
				<div class="col-sm-10">
					<input type="text" class="form-control px400" name="twitter_consumer_secret" id="twitter_consumer_secret" value="<?php echo set_value('twitter_consumer_secret', element('twitter_consumer_secret', element('data', $view))); ?>" />
				</div>
			</div>
			<hr />
			<div class="form-group">
				<label class="col-sm-2 control-label">구글</label>
				<div class="col-sm-10">
					<label for="use_sociallogin_google" class="checkbox-inline">
						<input type="checkbox" name="use_sociallogin_google" id="use_sociallogin_google" class="chk" value="1" <?php echo set_checkbox('use_sociallogin_google', '1', (element('use_sociallogin_google', element('data', $view)) ? true : false)); ?> /> 구글 로그인을 사용합니다
					</label>
					<a href="http://www.ciboard.co.kr/tiptech/p/136" class="btn btn-xs btn-warning" target="_blank">설정방법 자세히 보기</a>
				</div>
			</div>
			<div class="form-group">
				<span class="col-sm-2 text-right" style="padding-top:7px;">구글 Client ID</span>
				<div class="col-sm-10">
					<input type="text" class="form-control px400" name="google_client_id" id="google_client_id" value="<?php echo set_value('google_client_id', element('google_client_id', element('data', $view))); ?>" />
				</div>
			</div>
			<div class="form-group">
				<span class="col-sm-2 text-right" style="padding-top:7px;">구글 Client Secret</span>
				<div class="col-sm-10">
					<input type="text" class="form-control px400" name="google_client_secret" id="google_client_secret" value="<?php echo set_value('google_client_secret', element('google_client_secret', element('data', $view))); ?>" />
				</div>
			</div>
			<hr />
			<div class="form-group">
				<label class="col-sm-2 control-label">네이버</label>
				<div class="col-sm-10">
					<label for="use_sociallogin_naver" class="checkbox-inline">
						<input type="checkbox" name="use_sociallogin_naver" id="use_sociallogin_naver" class="chk" value="1" <?php echo set_checkbox('use_sociallogin_naver', '1', (element('use_sociallogin_naver', element('data', $view)) ? true : false)); ?> /> 네이버 로그인을 사용합니다
					</label>
					<a href="http://www.ciboard.co.kr/tiptech/p/137" class="btn btn-xs btn-warning" target="_blank">설정방법 자세히 보기</a>
				</div>
			</div>
			<div class="form-group">
				<span class="col-sm-2 text-right" style="padding-top:7px;">네이버 Client ID</span>
				<div class="col-sm-10">
					<input type="text" class="form-control px400" name="naver_client_id" id="naver_client_id" value="<?php echo set_value('naver_client_id', element('naver_client_id', element('data', $view))); ?>" />
				</div>
			</div>
			<div class="form-group">
				<span class="col-sm-2 text-right" style="padding-top:7px;">네이버 Client Secret</span>
				<div class="col-sm-10">
					<input type="text" class="form-control px400" name="naver_client_secret" id="naver_client_secret" value="<?php echo set_value('naver_client_secret', element('naver_client_secret', element('data', $view))); ?>" />
				</div>
			</div>
			<hr />
			<div class="form-group">
				<label class="col-sm-2 control-label">카카오</label>
				<div class="col-sm-10">
					<label for="use_sociallogin_kakao" class="checkbox-inline">
						<input type="checkbox" name="use_sociallogin_kakao" id="use_sociallogin_kakao" class="chk" value="1" <?php echo set_checkbox('use_sociallogin_kakao', '1', (element('use_sociallogin_kakao', element('data', $view)) ? true : false)); ?> /> 카카오 로그인을 사용합니다
					</label>
					<a href="http://www.ciboard.co.kr/tiptech/p/138" class="btn btn-xs btn-warning" target="_blank">설정방법 자세히 보기</a>
				</div>
			</div>
			<div class="form-group">
				<span class="col-sm-2 text-right" style="padding-top:7px;">카카오 Client ID</span>
				<div class="col-sm-10">
					<input type="text" class="form-control px400" name="kakao_client_id" id="kakao_client_id" value="<?php echo set_value('kakao_client_id', element('kakao_client_id', element('data', $view))); ?>" />
				</div>
			</div>
			<div class="btn-group pull-right" role="group" aria-label="...">
				<button type="submit" class="btn btn-success btn-sm">저장하기</button>
			</div>
		<?php
			echo form_close();
		}
		?>
	</div>
</div>

<script type="text/javascript">
//<![CDATA[
$(function() {
	$(document).on('change', '#use_sociallogin', function() {
		if ($(this).is(':checked')) {
			$('.chk').prop('disabled', false);
		} else {
			$('.chk').prop('checked', false).prop('disabled', true);
		}
	});
	<?php if ( ! element('use_sociallogin', element('data', $view))) {?>
		$('.chk').prop('checked', false).prop('disabled', true);
	<?php } ?>
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
