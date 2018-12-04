<style type="text/css">
.write_scemo {display:none;z-index:10;position:absolute;left:150px;border:1px solid #e9e9e9;background:#f7f7f7}
.write_scemo .scemo_list {z-index:11;margin:0;padding:0;width:190px;height:150px;background:#fff;overflow-y:scroll}
.write_scemo .scemo_add {margin:0;padding:0;height:25px;border:0;background:transparent}
#write_sc .scemo_add {width:25px}
#write_emo .scemo_add {width:50px}
#write_emo .emo_long {width:80px}
</style>

<div class="box">
	<div class="box-table">
		<?php
		echo validation_errors('<div class="alert alert-warning" role="alert">', '</div>');
		$attributes = array('class' => 'form-horizontal', 'name' => 'fadminwrite', 'id' => 'fadminwrite');
		echo form_open(current_full_url(), $attributes);
		?>
			<input type="hidden" name="<?php echo element('primary_key', $view); ?>"	value="<?php echo element(element('primary_key', $view), element('data', $view)); ?>" />
			<div class="form-group">
				<label class="col-sm-2 control-label">제목</label>
				<div class="col-sm-10">
					<input type="text" class="form-control" name="sfa_title" value="<?php echo set_value('sfa_title', element('sfa_title', element('data', $view))); ?>" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">문자내용</label>
				<div class="col-sm-10 form-inline">
					<div style="width:190px;">
						<textarea class="form-control" rows="5" name="sfa_content" id="sfa_content" style="width:190px;" onkeyup="byte_check('sfa_content', 'sms_bytes');" ><?php echo set_value('sfa_content', element('sfa_content', element('data', $view))); ?></textarea>
						<div class="form-inline">
							<div id="sms_byte"><span id="sms_bytes">0</span> / 80 byte</div>
							<div class="pull-right">
								<button type="button" id="write_sc_btn" class="btn btn-default btn-xs write_scemo_btn">특수기호</button>
								<div id="write_sc" class="write_scemo">
									<span class="scemo_ico"></span>
									<div class="scemo_list">
										<button type="button" class="scemo_add" onclick="javascript:add('■')">■</button>
										<button type="button" class="scemo_add" onclick="javascript:add('□')">□</button>
										<button type="button" class="scemo_add" onclick="javascript:add('▣')">▣</button>
										<button type="button" class="scemo_add" onclick="javascript:add('◈')">◈</button>
										<button type="button" class="scemo_add" onclick="javascript:add('◆')">◆</button>
										<button type="button" class="scemo_add" onclick="javascript:add('◇')">◇</button>
										<button type="button" class="scemo_add" onclick="javascript:add('♥')">♥</button>
										<button type="button" class="scemo_add" onclick="javascript:add('♡')">♡</button>
										<button type="button" class="scemo_add" onclick="javascript:add('●')">●</button>
										<button type="button" class="scemo_add" onclick="javascript:add('○')">○</button>
										<button type="button" class="scemo_add" onclick="javascript:add('▲')">▲</button>
										<button type="button" class="scemo_add" onclick="javascript:add('▼')">▼</button>
										<button type="button" class="scemo_add" onclick="javascript:add('▶')">▶</button>
										<button type="button" class="scemo_add" onclick="javascript:add('▷')">▷</button>
										<button type="button" class="scemo_add" onclick="javascript:add('◀')">◀</button>
										<button type="button" class="scemo_add" onclick="javascript:add('◁')">◁</button>
										<button type="button" class="scemo_add" onclick="javascript:add('☎')">☎</button>
										<button type="button" class="scemo_add" onclick="javascript:add('☏')">☏</button>
										<button type="button" class="scemo_add" onclick="javascript:add('♠')">♠</button>
										<button type="button" class="scemo_add" onclick="javascript:add('♤')">♤</button>
										<button type="button" class="scemo_add" onclick="javascript:add('♣')">♣</button>
										<button type="button" class="scemo_add" onclick="javascript:add('♧')">♧</button>
										<button type="button" class="scemo_add" onclick="javascript:add('★')">★</button>
										<button type="button" class="scemo_add" onclick="javascript:add('☆')">☆</button>
										<button type="button" class="scemo_add" onclick="javascript:add('☞')">☞</button>
										<button type="button" class="scemo_add" onclick="javascript:add('☜')">☜</button>
										<button type="button" class="scemo_add" onclick="javascript:add('▒')">▒</button>
										<button type="button" class="scemo_add" onclick="javascript:add('⊙')">⊙</button>
										<button type="button" class="scemo_add" onclick="javascript:add('㈜')">㈜</button>
										<button type="button" class="scemo_add" onclick="javascript:add('№')">№</button>
										<button type="button" class="scemo_add" onclick="javascript:add('㉿')">㉿</button>
										<button type="button" class="scemo_add" onclick="javascript:add('♨')">♨</button>
										<button type="button" class="scemo_add" onclick="javascript:add('™')">™</button>
										<button type="button" class="scemo_add" onclick="javascript:add('℡')">℡</button>
										<button type="button" class="scemo_add" onclick="javascript:add('∑')">∑</button>
										<button type="button" class="scemo_add" onclick="javascript:add('∏')">∏</button>
										<button type="button" class="scemo_add" onclick="javascript:add('♬')">♬</button>
										<button type="button" class="scemo_add" onclick="javascript:add('♪')">♪</button>
										<button type="button" class="scemo_add" onclick="javascript:add('♩')">♩</button>
										<button type="button" class="scemo_add" onclick="javascript:add('♭')">♭</button>
									</div>
									<div class="pull-right"><button type="button" class="btn btn-xs scemo_cls_btn">닫기</button></div>
								</div>
								<button type="button" id="write_emo_btn" class="btn btn-default btn-xs write_scemo_btn">이모티콘</button>
								<div id="write_emo" class="write_scemo">
									<span class="scemo_ico"></span>
									<div class="scemo_list">
										<button type="button" class="scemo_add" onclick="javascript:add('*^^*')">*^^*</button>
										<button type="button" class="scemo_add" onclick="javascript:add('♡.♡')">♡.♡</button>
										<button type="button" class="scemo_add" onclick="javascript:add('@_@')">@_@</button>
										<button type="button" class="scemo_add" onclick="javascript:add('☞_☜')">☞_☜</button>
										<button type="button" class="scemo_add" onclick="javascript:add('ㅠ ㅠ')">ㅠ ㅠ</button>
										<button type="button" class="scemo_add" onclick="javascript:add('Θ.Θ')">Θ.Θ</button>
										<button type="button" class="scemo_add" onclick="javascript:add('^_~♥')">^_~♥</button>
										<button type="button" class="scemo_add" onclick="javascript:add('~o~')">~o~</button>
										<button type="button" class="scemo_add" onclick="javascript:add('★.★')">★.★</button>
										<button type="button" class="scemo_add" onclick="javascript:add('(!.!)')">(!.!)</button>
										<button type="button" class="scemo_add" onclick="javascript:add('⊙.⊙')">⊙.⊙</button>
										<button type="button" class="scemo_add" onclick="javascript:add('q.p')">q.p</button>
										<button type="button" class="scemo_add emo_long" onclick="javascript:add('┏( \'\')┛')">┏( \'\')┛</button>
										<button type="button" class="scemo_add emo_long" onclick="javascript:add('@)-)--')">@)-)--')</button>
										<button type="button" class="scemo_add emo_long" onclick="javascript:add('↖(^-^)↗')">↖(^-^)↗</button>
										<button type="button" class="scemo_add emo_long" onclick="javascript:add('(*^-^*)')">(*^-^*)</button>
									</div>
									<div class="pull-right"><button type="button" class="btn btn-xs scemo_cls_btn">닫기</button></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div style="height:190px;">&nbsp;</div>
			<div class="btn-group pull-right" role="group" aria-label="...">
				<button type="button" class="btn btn-default btn-sm btn-history-back" >취소하기</button>
				<button type="submit" class="btn btn-success btn-sm">저장하기</button>
			</div>
		<?php echo form_close(); ?>
	</div>
</div>

<script type="text/javascript">
//<![CDATA[
$(function() {
	$(document).on('focus keydown', '#sfa_content', function() {
		$('.write_scemo').hide();
	});
	$(document).on('click', '.write_scemo_btn', function() {
		$('.write_scemo').hide();
		$(this).next('.write_scemo').show();
	});
	$(document).on('click', '.scemo_cls_btn', function() {
		$('.write_scemo').hide();
	});
});

function add(str) {
	var conts = document.getElementById('sfa_content');
	var bytes = document.getElementById('sms_bytes');
	conts.focus();
	conts.value += str;
	byte_check('sfa_content', 'sms_bytes');
	return;
}

function byte_check(sfa_content, sms_bytes) {
	var conts = document.getElementById(sfa_content);
	var bytes = document.getElementById(sms_bytes);
	var i = 0;
	var cnt = 0;
	var exceed = 0;
	var ch = '';

	for (i = 0; i <conts.value.length; i++) {
		ch = conts.value.charAt(i);
		if (escape(ch).length > 4) {
			cnt += 2;
		} else {
			cnt += 1;
		}
	}

	bytes.innerHTML = cnt;

	if (cnt > 80) {
		exceed = cnt - 80;
		alert('메시지 내용은 80바이트를 넘을수 없습니다.\n\n작성하신 메세지 내용은 ' + exceed + 'byte가 초과되었습니다.\n\n초과된 부분은 자동으로 삭제됩니다.');
		var tcnt = 0;
		var xcnt = 0;
		var tmp = conts.value;
		for (i = 0; i <tmp.length; i++) {
			ch = tmp.charAt(i);
			if (escape(ch).length > 4) {
				tcnt += 2;
			} else {
				tcnt += 1;
			}

			if (tcnt > 80) {
				tmp = tmp.substring(0, i);
				break;
			} else {
				xcnt = tcnt;
			}
		}
		conts.value = tmp;
		bytes.innerHTML = xcnt;
		return;
	}
}

byte_check('sfa_content', 'sms_bytes');
document.getElementById('sfa_content').focus();
//]]>
</script>
<script type="text/javascript">
//<![CDATA[
$(function() {
	$('#fadminwrite').validate({
		rules: {
			sfa_title: 'required',
			sfa_content: 'required'
		}
	});
});
//]]>
</script>
