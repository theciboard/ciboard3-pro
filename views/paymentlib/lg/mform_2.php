<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<input type="hidden" name="LGD_PAYKEY" id="LGD_PAYKEY" /> <!-- LG유플러스 PAYKEY(인증후 자동셋팅)-->
<input type="hidden" name="LGD_PRODUCTINFO" value="<?php echo element('good_name', $view); ?>" /> <!-- 상품정보 -->
<input type="hidden" name="res_cd" value="" />