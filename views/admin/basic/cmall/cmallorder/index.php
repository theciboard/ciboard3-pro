<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style type="text/css">
.cmall-options {background: #F2F3F5;border: 1px solid #DEE3E0;margin: 5px 0;border-bottom: 0;}
.cmall-options li {color: #5A5A5A;border-bottom: 1px solid #DEE3E0;padding: 5px;}
</style>

<div class="box">
	<div class="box-table">
	<?php
	echo show_alert_message($this->session->flashdata('message'), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
	echo show_alert_message($this->session->flashdata('dangermessage'), '<div class="alert alert-auto-close alert-dismissible alert-danger"><button type="button" class="close alertclose" >&times;</button>', '</div>');
	?>
		<div class="box-table-header">
			<div class="btn-group btn-group-sm" role="group">
				<a href="<?php echo admin_url('cmall/cmallorder'); ?>" class="btn btn-sm <?php echo ( ! $this->input->get('cor_pay_type')) ? 'btn-success' : 'btn-default';?>">전체내역</a>
				<a href="<?php echo admin_url('cmall/cmallorder'); ?>?cor_pay_type=bank" class="btn btn-sm <?php echo ($this->input->get('cor_pay_type') === 'bank') ? 'btn-info' : 'btn-default';?>">무통장</a>
				<a href="<?php echo admin_url('cmall/cmallorder'); ?>?cor_pay_type=card" class="btn btn-sm <?php echo ($this->input->get('cor_pay_type') === 'card') ? 'btn-info' : 'btn-default';?>">카드</a>
				<a href="<?php echo admin_url('cmall/cmallorder'); ?>?cor_pay_type=realtime" class="btn btn-sm <?php echo ($this->input->get('cor_pay_type') === 'realtime') ? 'btn-info' : 'btn-default';?>">실시간</a>
				<a href="<?php echo admin_url('cmall/cmallorder'); ?>?cor_pay_type=vbank" class="btn btn-sm <?php echo ($this->input->get('cor_pay_type') === 'vbank') ? 'btn-info' : 'btn-default';?>">가상계좌</a>
				<a href="<?php echo admin_url('cmall/cmallorder'); ?>?cor_pay_type=phone" class="btn btn-sm <?php echo ($this->input->get('cor_pay_type') === 'phone') ? 'btn-info' : 'btn-default';?>">핸드폰</a>
			</div>
			<?php
			ob_start();
			?>
				<div class="btn-group pull-right" role="group" aria-label="...">
					<a href="<?php echo element('listall_url', $view); ?>" class="btn btn-outline btn-default btn-sm">전체목록</a>
				</div>
			<?php
			$buttons = ob_get_contents();
			ob_end_flush();
			?>
		</div>
		<div class="row">전체 : <?php echo element('total_rows', element('data', $view), 0); ?>건</div>
		<div class="table-responsive">
			<table class="table table-hover table-striped table-bordered">
				<thead>
					<tr>
						<th>주문번호</th>
						<th>회원아이디</th>
						<th>회원명/실명</th>
						<th>주문상태</th>
						<th>주문상품수</th>
						<th>결제수단</th>
						<th>주문합계</th>
						<th>입금합계</th>
						<th>주문취소</th>
						<th>보기</th>
					</tr>
				</thead>
				<tbody>
				<?php
				if (element('list', element('data', $view))) {
					foreach (element('list', element('data', $view)) as $result) {

						$order_detail = element('orderdetail', $result);
				?>
					<tr>
						<td><a href="<?php echo site_url('cmall/orderresult/' . element('cor_id', $result)); ?>" target="_blank"><?php echo element('cor_id', $result); ?></a>
						<?php if( element('is_test', $result) ){ ?>
							<span class="btn btn-xs btn-warning">테스트 결제</span>
						<?php } ?>
						</td>
						<td><a href="?sfield=deposit.mem_id&amp;skeyword=<?php echo element('mem_id', $result); ?>"><?php echo html_escape(element('mem_userid', $result)); ?></a></td>
						<td><?php echo element('display_name', $result); ?> / <?php echo html_escape(element('mem_realname', $result)); ?></td>
						<td><?php echo element('order_status', $result);	 //주문상태 ?></td>
						<td><?php echo count($order_detail);	 //주문상품수 ?></td>
						<?php /* echo display_datetime(element('cor_datetime', $result), 'full') */ ?>
						<td><?php echo element('pay_method', $result); ?></td>
						<td class="text-right"><?php echo number_format(element('cor_total_money', $result)) . '원'; ?></td>
						<td class="text-right"><?php echo number_format(element('cor_cash', $result)) . '원'; ?></td>
						<td class="text-right"><?php echo number_format(element('cor_refund_price', $result)) . '원'; ?></td>
						<td><a href="<?php echo element('form_url', $view) .'/'. element('cor_id', $result); ?>">보기</a></td>
					</tr>
					<?php
						}
					}
					if ( ! element('list', element('data', $view))) {
					?>
						<tr>
							<td colspan="10" class="nopost">자료가 없습니다</td>
						</tr>
					<?php
					}
					?>
				</tbody>
			</table>
		</div>
		<div class="box-info">
			<?php echo element('paging', $view); ?>
			<div class="pull-left ml20"><?php echo admin_listnum_selectbox();?></div>
			<?php echo $buttons; ?>
		</div>
	</div>
	<form name="fsearch" id="fsearch" action="<?php echo current_full_url(); ?>" method="get">
		<div class="box-search">
			<div class="row">
				<div class="col-md-6 col-md-offset-3">
					<select class="form-control" name="sfield" >
						<?php echo element('search_option', $view); ?>
					</select>
					<div class="input-group">
						<input type="text" class="form-control" name="skeyword" value="<?php echo html_escape(element('skeyword', $view)); ?>" placeholder="Search for..." />
						<span class="input-group-btn">
							<button class="btn btn-default btn-sm" name="search_submit" type="submit">검색!</button>
						</span>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>

<script type="text/javascript">
//<![CDATA[
$(document).on('click', '.btn-download-days-modify', function() {
	$('.cor-id-cit-id-' + $(this).attr('data-cor-id-cit-id')).toggle();
});
//]]>
</script>
