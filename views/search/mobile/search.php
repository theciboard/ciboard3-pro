<?php $this->managelayout->add_css(element('view_skin_url', $layout) . '/css/style.css'); ?>

<h3><?php echo ($this->input->get('skeyword')) ? '검색결과 : ' . html_escape($this->input->get('skeyword')) : '검색페이지' ?></h3>
<div class="row">
	<form action="<?php echo current_url(); ?>" onSubmit="return checkSearch(this);" class=" search_box text-center">
		<div class="group">
			<select class="input" name="group_id">
				<option value="">전체그룹</option>
				<?php
				if (element('grouplist', $view)) {
					foreach (element('grouplist', $view) as $key => $value) {
				?>
					<option value="<?php echo element('bgr_id', $value); ?>" <?php echo element('bgr_id', $value) === $this->input->get('group_id') ? 'selected="selected"' : ''; ?>><?php echo element('bgr_name', $value); ?></option>
				<?php
					}
				}
				?>
			</select>
		</div>
		<div class="group">
			<select class="input per100" name="sfield">
				<option value="post_both" <?php echo $this->input->get('sfield') === 'post_both' ? 'selected="selected"' : ''; ?>>제목+내용</option>
				<option value="post_title" <?php echo $this->input->get('sfield') === 'post_title' ? 'selected="selected"' : ''; ?>>제목</option>
				<option value="post_content" <?php echo $this->input->get('sfield') === 'post_content' ? 'selected="selected"' : ''; ?>>내용</option>
				<option value="post_userid" <?php echo $this->input->get('sfield') === 'post_userid' ? 'selected="selected"' : ''; ?>>회원아이디</option>
				<option value="post_nickname" <?php echo $this->input->get('sfield') === 'post_nickname' ? 'selected="selected"' : ''; ?>>회원닉네임</option>
			</select>
		</div>
		<div class="group">
			<input type="text" class="input per100" name="skeyword" placeholder="검색어" value="<?php echo html_escape($this->input->get('skeyword')); ?>" />
		</div>
		<div class="group">
			<button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-search"></i> 검색</button>
		</div>
		<div class="group">
			<select class="input" name="sop">
				<option value="OR" <?php echo strtoupper($this->input->get('sop')) !== 'AND' ? 'selected="selected"' : ''; ?>>OR</option>
				<option value="AND" <?php echo strtoupper($this->input->get('sop')) === 'AND' ? 'selected="selected"' : ''; ?>>AND</option>
			</select>
		</div>
	</form>
</div>
<ul class="nav nav-tabs mt20">
<?php
if (element('board_rows', $view)) {
?>
	<li role="presentation" <?php echo ( ! $this->input->get('board_id')) ? 'class="active"' : ''; ?>><a href="<?php echo element('tab_url', $view); ?>">전체게시판 (<?php echo number_format( array_sum(element('board_rows', $view))); ?>)</a></li>
<?php
	foreach (element('board_rows', $view) as $key => $value) {
?>
		<li role="presentation" <?php echo ($this->input->get('board_id') === $key) ? 'class="active"' : ''; ?>><a href="<?php echo element('tab_url', $view) . '&amp;board_id=' . $key; ?>"><?php echo html_escape(element('brd_name', element($key, element('boardlist', $view)))); ?> (<?php echo $value; ?>)</a></li>
<?php
	}
}
?>
</ul>
<div class="media-box mt20" id="searchresult">
<?php
if (element('list', element('data', $view))) {
	foreach (element('list', element('data', $view)) as $result) {
?>
	<div class="media">
<?php
		if (element('images', $result)) {
?>
		<div class="media-left">
			<a href="<?php echo element('post_url', $result); ?>" title="<?php echo html_escape(element('post_title', $result)); ?>">
				<img class="media-object" src="<?php echo thumb_url('post', element('pfi_filename', element('images', $result)), 100, 80); ?>" alt="<?php echo html_escape(element('post_title', $result)); ?>" title="<?php echo html_escape(element('post_title', $result)); ?>" style="width:100px;height:80px;" />
			</a>
		</div>
<?php
		}
?>
		<div class="media-body">
			<h4 class="media-heading"><a href="<?php echo element('post_url', $result); ?>" title="<?php echo html_escape(element('post_title', $result)); ?>"><?php echo html_escape(element('post_title', $result)); ?></a>
			</h4>
			<div class="media-comment">
				<?php if (element('post_comment_count', $result)) { ?><span class="label label-info label-xs"><?php echo element('post_comment_count', $result); ?> comments</span><?php } ?>
				<a href="<?php echo element('post_url', $result); ?>" target="_blank" title="<?php echo html_escape(element('post_title', $result)); ?>"><span class="label label-default label-xs">새창</span></a>
			</div>
			<p><?php echo element('content', $result); ?></p>
			<p class="media-info">
				<span><?php echo element('display_name', $result); ?></span>
				<span><i class="fa fa-clock-o"></i> <?php echo element('display_datetime', $result); ?></span>
			</p>
		</div>
	</div>
<?php
	}
}
if ( ! element('list', element('data', $view))) {
?>
	<div class="media">
		<div class="media-body nopost">
			검색 결과가 없습니다
		</div>
	</div>
<?php
}
?>
</div>
<nav><?php echo element('paging', $view); ?></nav>

<script type="text/javascript">
//<![CDATA[
function checkSearch(f) {
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
<?php if (element('highlight_keyword', $view)) {
	$this->managelayout->add_js(base_url('assets/js/jquery.highlight.js')); ?>
<script type="text/javascript">
//<![CDATA[
$('#searchresult').highlight([<?php echo element('highlight_keyword', $view);?>]);
//]]>
</script>
<?php } ?>
