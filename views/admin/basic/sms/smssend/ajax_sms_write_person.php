<div class="box-table">
	<div class="table-responsive">
		<table class="table table-bordered table-hover table-striped">
			<thead>
				<tr>
					<th>
						<input type="checkbox" id="all_checked" onclick="sms_obj.book_all_checked(this.checked)" />
						<label for="all_checked">회원 전체</label>
					</th>
					<th>이름</th>
					<th>휴대폰번호</th>
					<th>회원</th>
					<th>추가</th>
				</tr>
			</thead>
			<tbody>
			<?php
			if (element('list', element('data', $view))) {
				foreach (element('list', element('data', $view)) as $result) {
			?>
				<tr>
					<td>
						<input type="checkbox" name="sme_id" value="<?php echo element('sme_id', $result); ?>" id="sme_id_<?php echo element('sme_id', $result); ?>" />
						<label for="sme_id_<?php echo element('sme_id', $result); ?>"><?php echo html_escape(element('sme_name', $result)); ?></label>
					</td>
					<td><?php echo html_escape(element('sme_name', $result)); ?></td>
					<td><?php echo element('sme_phone', $result); ?></td>
					<td><?php echo element('mem_id', $result) ? '회원' : '비회원'; ?></td>
					<td><button type="button" class="btn btn-default btn-xs" onclick="sms_obj.person_add(<?php echo element('sme_id', $result); ?>, '<?php echo html_escape(element('sme_name', $result)); ?>', '<?php echo element('sme_phone', $result); ?>')">추가</button></td>
				</tr>
			<?php
				}
			}
			?>
			</tbody>
		</table>
	</div>
	<div class="btn_list01 btn_list">
		<button type="button" class="btn btn-default btn-xs" onclick="sms_obj.person_multi_add()">선택추가</button>
		<button type="button" class="btn btn-default btn-xs" onclick="sms_obj.triggerclick('#book_group')" >그룹목록</button>
	</div>
	<ul class="pagination pb10" id="person_pg"></ul>

	<form name="search_form" id="sms_person_form" class="form-inline" method="get" action="<?php echo current_full_url(); ?>">
		<label for="smg_id">그룹</label>
		<select name="smg_id" id="smg_id" class="form-control">
			<option value=""> 전체 </option>
			<option value="n" <?php echo set_select('smg_id', 'n', ($this->input->get('smg_id') === 'n' ? true : false)); ?>> 미분류 </option>
			<?php
			if (element('group', $view)) {
				foreach (element('group', $view) as $gval) {
			?>
				<option value="<?php echo element('smg_id', $gval); ?>" <?php echo set_select('smg_id', element('smg_id', $gval), ($this->input->get('smg_id') === element('smg_id', $gval) ? true : false)); ?>><?php echo element('smg_name', $gval); ?></option>
			<?php
				}
			}
			?>
		</select>
		<label for="sfield">검색대상</label>
		<select name="sfield" id="sfield" class="form-control">
			<option value="sme_name" <?php echo set_select('sfield', 'sme_name', ($this->input->get('sfield') === 'sme_name' ? true : false)); ?>>이름</option>
			<option value="sme_phone" <?php echo set_select('sfield', 'sme_phone', ($this->input->get('sfield') === 'sme_phone' ? true : false)); ?>>번호</option>
		</select>
		<label for="skeyword">검색어</label>
		<input type="text" size="15" name="skeyword" value="<?php echo html_escape($this->input->get('skeyword')); ?>" id="skeyword" class="form-control" />
		<input type="submit" value="검색" class="btn btn-default btn-xs" />
	</form>
</div>