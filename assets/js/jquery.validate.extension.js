
(function ($) {
	$.extend($.validator.messages, {
		required		: '필수 항목입니다.',
		remote			: '항목을 수정하세요.',
		email			: '유효하지 않은 E-Mail 주소입니다.',
		url				: 'URL 형식이 올바르지 않습니다. http://~',
		date			: '옳바른 날짜를 입력하세요.',
		dateISO			: '옳바른 날짜(ISO)를 입력하세요.',
		number			: '숫자가 아닙니다.',
		digits			: '숫자만 입력하세요.',
		creditcard		: '신용카드 번호가 올바르지 않습니다.',
		equalTo			: '같은 값을 다시 입력하세요.',
		accept			: '옳바른 확장자가 아닙니다.',
		maxlength		: $.validator.format('{0}자를 넘을 수 없습니다.'),
		minlength		: $.validator.format('최소 {0}자 이상 입력하세요.'),
		rangelength		: $.validator.format('문자 길이를 {0} 에서 {1} 사이로 입력하세요.'),
		range			: $.validator.format('{0} 에서 {1} 값을 입력하세요.'),
		max				: $.validator.format('{0} 이하의 값을 입력하세요.'),
		min			: $.validator.format('{0} 이상의 값을 입력하세요.')
	});

	if(typeof($.fn.modal) != 'undefined') {
		$.validator.setDefaults({ // Bootstrap Required.
			ignore: [], // hidden
			showErrors: function(errorMap, errorList) {
				$.each(this.successList, function(index, value) {
					var field = $(value);
					if (field.is(':hidden'))
						field = field.parent();

					field.tooltip('destroy');
				});

				$.each(errorList, function(index, value) { 
					var field = $(value.element);
					if (field.is(':hidden'))
						field = field.parent();

					field.tooltip('destroy').tooltip({
						placement: 'bottom',
						title: value.message,
						trigger: 'manual'
					}).tooltip('show');
				});
			}
		});
	} else {
		$.validator.setDefaults({ // Bootstrap Required.
			ignore: [] // hidden
		});
	}
}(jQuery));




 $(function() {
	function validHangul(fld) {
		var pattern = /([^가-힣\x20])/i;
		if (!pattern.test(fld)) {
			return true;
		}
		return false;
	}

	function validSmarteditor(column, value, stype) {
		
		var editor_data = oEditors.getById[column].getIR();
		oEditors.getById[column].exec('UPDATE_CONTENTS_FIELD', []);
		if(jQuery.inArray(document.getElementById(column).value.toLowerCase().replace(/^\s*|\s*$/g, ''), ['&nbsp;','<p>&nbsp;</p>','<p><br></p>','<div><br></div>','<p></p>','<br>','']) != -1) {
			document.getElementById(column).value='';
		}
		if(stype == 'required') {
			if (!editor_data || jQuery.inArray(editor_data.toLowerCase(), ['&nbsp;','<p>&nbsp;</p>','<p><br></p>','<p></p>','<br>']) != -1) { 
				oEditors.getById[column].exec('FOCUS'); 
				return false; 
			}
		}
		return true;
	}

	function validCkeditor(column, value, stype) {

		var editor_data = eval("CKEDITOR.instances." + column + ".getData();");

		if(stype == 'required') {
			if (! editor_data) { 
				eval("CKEDITOR.instances." + column + ".focus();");
				return false;
			}
		}
		return true;
	}
	
	$.validator.addMethod('hangul', function(value, element) {
		return this.optional(element) || validHangul(value);
	}, '한글이 아닙니다. (자음, 모음만 있는 한글은 처리하지 않습니다.)');

	$.validator.addMethod('alphanumunder', function(value, element) {
		return this.optional(element) || /(^[a-zA-Z0-9\_]+$)/.test(value);
	}, '영문, 숫자, _ 가 아닙니다.');

	$.validator.addMethod('alpha_dash', function(value, element) {
		return this.optional(element) || /(^[a-zA-Z0-9\_\-]+$)/.test(value);
	}, '영문, 숫자, _ , - 가 아닙니다.');

	$.validator.addMethod('valid_smarteditor', function(value, element) {
		return validSmarteditor($(element).attr('name'), value, 'valid');
	}, '내용을 입력해주세요');

	$.validator.addMethod('required_smarteditor', function(value, element) {
		return validSmarteditor($(element).attr('name'), value, 'required');
	}, '내용을 입력해주세요');

	$.validator.addMethod('valid_ckeditor', function(value, element) {
		return validCkeditor($(element).attr('name'), value, 'valid');
	}, '내용을 입력해주세요');

	$.validator.addMethod('required_ckeditor', function(value, element) {
		return validCkeditor($(element).attr('name'), value, 'required');
	}, '내용을 입력해주세요');
 
 });