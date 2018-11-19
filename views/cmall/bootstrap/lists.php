<?php $this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css'); ?>
<div id="lists">
	<?php if (element('category_nav', $view)) { ?>
		<ol class="breadcrumb">
			<li><a href="<?php echo site_url('cmall/lists');?>">상품목록</a></li>
			<?php foreach (element('category_nav', $view) as $result) { ?>
				<li><a href="<?php echo site_url('cmall/lists/' . element('cca_id', $result));?>" title="<?php echo html_escape(element('cca_value', $result)); ?>"><?php echo html_escape(element('cca_value', $result)); ?></a></li>
			<?php } ?>
		</ol>
		<?php if (element('category_all', $view) && element(element('category_id', $view), element('category_all', $view))) { ?>
			<div class="panel panel-default">
				<div class="panel-body">
					<?php foreach (element(element('category_id', $view), element('category_all', $view)) as $result) { ?>
						<div class="pull-left ml20"><i class="fa fa-caret-right"></i> <a href="<?php echo site_url('cmall/lists/' . element('cca_id', $result));?>" title="<?php echo html_escape(element('cca_value', $result));?>"><?php echo html_escape(element('cca_value', $result));?></a></div>
					<?php } ?>
				</div>
			</div>
		<?php } ?>
	<?php } else { ?>
		<h3>전체상품</h3>
	<?php } ?>

	<div class="cmall-list">
		<ul class="row">
			<?php
			$k = 0;
			if (element('list', element('data', $view))) {
				foreach (element('list', element('data', $view)) as $item) {
			?>
				<li class="col-xs-6 col-sm-6 col-md-4 col-lg-4 cmall-list-col">
					<div class="thumbnail" >
						<a href="<?php echo cmall_item_url(element('cit_key', $item)); ?>" title="<?php echo html_escape(element('cit_name', $item)); ?>">
							<img src="<?php echo thumb_url('cmallitem', element('cit_file_1', $item), 420, 300); ?>" alt="<?php echo html_escape(element('cit_name', $item)); ?>" title="<?php echo html_escape(element('cit_name', $item)); ?>" />
						</a>
						<p class="cmall-tit"><a href="<?php echo cmall_item_url(element('cit_key', $item)); ?>" title="<?php echo html_escape(element('cit_name', $item)); ?>"><?php echo html_escape(element('cit_name', $item)); ?></a></p>
						<p class="cmall-txt"><?php echo element('cit_summary', $item); ?></p>
						<ul class="cmall-detail">
							<li><i class="fa fa-heart"></i> <span class="detail-tit">찜</span> <?php echo number_format(element('cit_wish_count', $item)); ?></li>
							<li><i class="fa fa-shopping-cart"></i> <span class="detail-tit">구매</span> <?php echo number_format(element('cit_sell_count', $item)); ?></li>
							<li class="cmall-price pull-right"><span><?php echo number_format(element('cit_price', $item)); ?></span>원</li>
						</ul>
					</div>
				</li>
			<?php


				$k++;
				}
			}
			?>
		</ul>
	</div>

	<div class="searchbox">
		<form class="navbar-form navbar-right pull-right" action="<?php echo current_url(); ?>" onSubmit="return itemSearch(this);">
			<input type="hidden" name="findex" value="<?php echo html_escape($this->input->get('findex')); ?>" />
			<div class="form-group">
				<select class="form-control pull-left px100" name="sfield">
					<option value="cit_both" <?php echo ($this->input->get('sfield') === 'cit_both') ? ' selected="selected" ' : ''; ?>>상품명+내용</option>
					<option value="cit_title" <?php echo ($this->input->get('sfield') === 'cit_title') ? ' selected="selected" ' : ''; ?>>상품명</option>
					<option value="cit_content" <?php echo ($this->input->get('sfield') === 'cit_content') ? ' selected="selected" ' : ''; ?>>내용</option>
				</select>
				<input type="text" class="form-control px150" placeholder="Search" name="skeyword" value="<?php echo $this->input->get('skeyword'); ?>" />
				<button class="btn btn-primary btn-sm" type="submit"><i class="fa fa-search"></i></button>
			</div>
		</form>
	</div>
</div>
<script type="text/javascript">
//<![CDATA[
function itemSearch(f) {
	var skeyword = f.skeyword.value.replace(/(^\s*)|(\s*$)/g,'');
	if (skeyword.length < 2) {
		alert('2글자 이상으로 검색해 주세요');
		f.skeyword.focus();
		return false;
	}
	return true;
}
//]]>
</script>

<a href="<?php echo current_url(); ?>" class="btn btn-default btn-sm">목록</a>
<nav><?php echo element('paging', $view); ?></nav>
