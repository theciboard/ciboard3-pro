<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//pc에서 요청했는지 mobile 에서 요청했는지 체크
$is_pay_pc = (!empty(element('LGD_WINDOW_VER', element('data', $view))) && !empty(element('is_pay_pc', element('data', $view)))) ? true : false;
?>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
</head>
<body <?php echo element('body_script', $view); ?>>
<?php

$exclude = array('res_cd', 'LGD_PAYKEY');

$attributes = array('name' => 'fpayment', 'id' => 'fpayment');
echo form_open(element('order_action_url', $view), $attributes);

	foreach (element('data', $view) as $key => $value) {
		if (in_array($key, $exclude)) {
			continue;
		}

		if (is_array($value)) {
			foreach ($value as $k => $v) {
				echo '<input type="hidden" name="' . $key . '[' . $k . ']" value="' . $v . '" />' . PHP_EOL;
			}
		} else {
			echo '<input type="hidden" name="' . $key . '" id="' . $key . '" value="' . $value . '" />' . PHP_EOL;
		}
	}
	echo '<input type="hidden" name="LGD_PAYKEY" id="LGD_PAYKEY" value="' . element('LGD_PAYKEY', $view) . '" />' . PHP_EOL;
	if( !$is_pay_pc ){
		echo '<input type="hidden" name="res_cd" value="' . element('LGD_RESPCODE', $view) . '" />' . PHP_EOL;
	}

echo form_close();
?>

<?php if( element('fail_msg', $view) ){
	echo element('fail_msg', $view);
} else { ?>
<div>
	<div id="show_progress">
		<span style="display:block; text-align:center;margin-top:120px"><img src="<?php echo site_url(VIEW_DIR . 'paymentlib/images/ajax-loader.gif'); ?>" alt="주문완료중" title="주문완료중" /></span>
		<span style="display:block; text-align:center;margin-top:10px; font-size:14px">주문완료 중입니다. 잠시만 기다려 주십시오.</span>
	</div>
</div>
<?php } //end if ?>

<?php if( $is_pay_pc ){	 //pc 에서 결제 요청했다면 ?>
<script type="text/javascript">
	function setLGDResult() {
		parent.payment_return();
		try {
		} catch (e) {
			alert(e.message);
		}
	}
</script>
<?php } else { ?>
<script type="text/javascript">
function setLGDResult() {
	setTimeout( function() {
		document.fpayment.submit();
	}, 300);
}
</script>
<?php }	 //end if ?>
</form>
</body>
</html>