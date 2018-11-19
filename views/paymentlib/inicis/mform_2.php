<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<input type="hidden" name="res_cd" value="" /> <!-- 결과 코드 -->
<input type="hidden" name="P_HASH" value="" />
<input type="hidden" name="P_TYPE" value="" />
<input type="hidden" name="P_UNAME" value="" />
<input type="hidden" name="P_GOODS" value="<?php echo element('good_name', $view); ?>" />
<input type="hidden" name="P_AUTH_DT" value="" />
<input type="hidden" name="P_AUTH_NO" value="" />
<input type="hidden" name="P_HPP_CORP" value="" />
<input type="hidden" name="P_APPL_NUM" value="" />
<input type="hidden" name="P_VACT_NUM" value="" />
<input type="hidden" name="P_VACT_NAME" value="" />
<input type="hidden" name="P_VACT_BANK" value="" />
<input type="hidden" name="P_CARD_ISSUER" value="" />
<input type="hidden" name="mem_id" value="<?php echo $this->member->item('mem_id'); ?>" />
<input type="hidden" name="cct_ids" value="<?php echo implode(',', (array) $this->session->userdata('order_cct_id')); ?>" />
<input type="hidden" name="ptype" value="<?php echo element('ptype', $view); ?>" />