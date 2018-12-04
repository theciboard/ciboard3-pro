<style type="text/css">
.start_config_meta {display:inline-block;width:130px;text-align:right;float:left;margin-right:10px; }
.content_config_meta {display:inline-block;width: -moz-calc(100% - 150px);width: -webkit-calc(100% - 150px);width: calc(100% - 150px); }
.use_controllers {font-weight:normal;padding-top:10px;}
</style>
<div class="box">
	<div class="box-header">
		<ul class="nav nav-tabs">
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir); ?>" onclick="return check_form_changed();">레이아웃/스킨설정</a></li>
			<li role="presentation" class="active"><a href="<?php echo admin_url($this->pagedir . '/metatag'); ?>" onclick="return check_form_changed();">메타태그</a></li>
			<li role="presentation"><a href="<?php echo admin_url($this->pagedir . '/favicon'); ?>" onclick="return check_form_changed();">파비콘 등록</a></li>
		</ul>
	</div>
	<div class="box-table">
		<div class="alert alert-warning">
			<p>- 각 페이지의 Title 과 Meta Tag 를 관리하는 페이지입니다.</p>
			<p>- 각 페이지에서 별도로 Title 과 Meta Tag 를 설정하지 않으면, 기본설정에서 설정한 Title 과 Meta Tag 가 기본적으로 모든 페이지에 적용됩니다.</p>
			<p>- 치환가능변수를 활용하여 내용을 작성할 수 있습니다. 치환가능변수는 상황에 맞게 적절히 변경됩니다</p>
			<p>- 치환가능변수 중 {회원아이디}, {회원닉네임} 등은 로그인한 회원의 경우에만 알맞게 치환됩니다. 따라서 마이페이지 등 로그인 후에 볼 수 있는 페이지에 활용하는 것이 좋습니다</p>
		</div>
		<div class="alert alert-success">
			<p>공통적으로 사용할 수 있는 치환가능변수 : <strong>{홈페이지제목}</strong>, <strong>{현재주소}</strong>, <strong>{회원아이디}</strong>, <strong>{회원닉네임}</strong>, <strong>{회원레벨}</strong>, <strong>{회원포인트}</strong></p>
			<hr />
			<p><strong>Title : </strong> &lt;title&gt;여기에 입력하신 내용이 들어갑니다&lt;/title&gt;</p>
			<p><strong>meta description : </strong> &lt;meta name=&quot;description&quot; content=&quot;여기에 입력하신 내용이 들어갑니다&quot;&gt;</p>
			<p><strong>meta keywords : </strong> &lt;meta name=&quot;keywords&quot; content=&quot;여기에 입력하신 내용이 들어갑니다&quot;&gt;</p>
			<p><strong>meta author : </strong> &lt;meta name=&quot;author&quot; content=&quot;여기에 입력하신 내용이 들어갑니다&quot;&gt;</p>
			<p><strong>page name : </strong> 현재접속자 페이지에 보입니다</p>
		</div>
		<?php
		echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
		echo show_alert_message(element('alert_message', $view), '<div class="alert alert-auto-close alert-dismissible alert-info"><button type="button" class="close alertclose" >&times;</button>', '</div>');
		$attributes = array('class' => 'form-horizontal', 'name' => 'fadminwrite', 'id' => 'fadminwrite');
		echo form_open(current_full_url(), $attributes);
		?>
			<input type="hidden" name="is_submit" value="1" />
			<div class="table-responsive form-group form-group-sm">
				<table class="table table-bordered table-hover table-striped">
					<tbody>
						<tr>
							<th class="px200">위치</th>
							<td>내용</td>
							<td class="px200">치환가능변수</td>
						</tr>
						<tr class="bg bg-warning">
							<th>기본설정</th>
							<td>
								<div class="config_meta">
									<div class="start_config_meta">Title</div>
									<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_title_default" value="<?php echo set_value('site_meta_title_default', element('site_meta_title_default', element('data', $view))); ?>" /></div>
								</div>
								<div class="config_meta">
									<div class="start_config_meta">meta description</div>
									<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_description_default" value="<?php echo set_value('site_meta_description_default', element('site_meta_description_default', element('data', $view))); ?>" /></div>
								</div>
								<div class="config_meta">
									<div class="start_config_meta">meta keywords</div>
									<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_keywords_default" value="<?php echo set_value('site_meta_keywords_default', element('site_meta_keywords_default', element('data', $view))); ?>" /></div>
								</div>
								<div class="config_meta">
									<div class="start_config_meta">meta author</div>
									<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_author_default" value="<?php echo set_value('site_meta_author_default', element('site_meta_author_default', element('data', $view))); ?>" /></div>
								</div>
								<div class="config_meta">
									<div class="start_config_meta">page name</div>
									<div class="content_config_meta"><input type="text" class="form-control" name="site_page_name_default" value="<?php echo set_value('site_page_name_default', element('site_page_name_default', element('data', $view))); ?>" /></div>
								</div>
							</td>
							<td></td>
						</tr>
						<?php foreach (element('pagelist', $view) as $data) { ?>
						<tr>
							<th>
								<?php echo element('name', $data); ?>
								<?php if (element('controllers', $data) && is_array(element('controllers', $data))) { ?>
									<div class="use_controllers">
										<?php
										$k= 0;
										foreach (element('controllers', $data) as $cval) {
											if ($k> 0) {
												echo ',<br />';
											}
											echo $cval;
											$k++;
										}
										?>
									</div>
								<?php }?>
							</th>
							<td>
								<div class="config_meta">
									<div class="start_config_meta">Title</div>
									<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_title_<?php echo element('key', $data); ?>" value="<?php echo set_value('site_meta_title_' . element('key', $data), element('site_meta_title_' . element('key', $data), element('data', $view))); ?>" /></div>
								</div>
								<div class="config_meta">
									<div class="start_config_meta">meta description</div>
									<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_description_<?php echo element('key', $data); ?>" value="<?php echo set_value('site_meta_description_' . element('key', $data), element('site_meta_description_' . element('key', $data), element('data', $view))); ?>" /></div>
								</div>
								<div class="config_meta">
									<div class="start_config_meta">meta keywords</div>
									<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_keywords_<?php echo element('key', $data); ?>" value="<?php echo set_value('site_meta_keywords_' . element('key', $data), element('site_meta_keywords_' . element('key', $data), element('data', $view))); ?>" /></div>
								</div>
								<div class="config_meta">
									<div class="start_config_meta">meta author</div>
									<div class="content_config_meta"><input type="text" class="form-control" name="site_meta_author_<?php echo element('key', $data); ?>" value="<?php echo set_value('site_meta_author_' . element('key', $data), element('site_meta_author_' . element('key', $data), element('data', $view))); ?>" /></div>
								</div>
								<div class="config_meta">
									<div class="start_config_meta">page name</div>
									<div class="content_config_meta"><input type="text" class="form-control" name="site_page_name_<?php echo element('key', $data); ?>" value="<?php echo set_value('site_page_name_' . element('key', $data), element('site_page_name_' . element('key', $data), element('data', $view))); ?>" /></div>
								</div>
							</td>
							<td><?php echo element('description', $data); ?></td>
						</tr>
					<?php } ?>
					</tbody>
				</table>
			</div>
			<div class="btn-group pull-right" role="group" aria-label="...">
				<button type="submit" class="btn btn-success btn-sm">저장하기</button>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>

<script type="text/javascript">
//<![CDATA[
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
