//http://dev.epiloum.net/916

function sendSns(sns, url, txt) {
	var o;
	var _url = encodeURIComponent(url);
	var _txt = encodeURIComponent(txt);
	var _br = encodeURIComponent('\r\n');

	switch(sns) {
		case 'facebook':
			o = {
				method:'popup',
				url:'http://www.facebook.com/sharer/sharer.php?u=' + _url
			};
			break;

		case 'twitter':
			o = {
				method:'popup',
				url:'http://twitter.com/intent/tweet?text=' + _txt + '&url=' + _url
			};
			break;

		case 'me2day':
			o = {
				method:'popup',
				url:'http://me2day.net/posts/new?new_post[body]=' + _txt + _br + _url + '&new_post[tags]=epiloum'
			};
			break;

		case 'kakaostory':
			o = {
				method:'popup',
				url:'https://story.kakao.com/share?url=' + _url
			};
			break;

		case 'band':
			o = {
				method:'popup',
				url:'http://www.band.us/plugin/share?body=' + _txt + _br + _url
			};
			break;

		default:
			alert('지원하지 않는 SNS입니다.');
			return false;
	}

	switch(o.method) {
		case 'popup':
			window.open(o.url,'snspopup','width=500, height=400, menubar=no, status=no, toolbar=no');
			break;

		case 'web2app':
			if (navigator.userAgent.match(/android/i))
			{
				// Android
				setTimeout(function(){ location.href = 'intent://' + o.param + '#Intent;' + o.g_proto + ';end'}, 100);
			}
			else if (navigator.userAgent.match(/(iphone)|(ipod)|(ipad)/i))
			{
				// Apple
				setTimeout(function(){ location.href = o.a_store; }, 200);
				setTimeout(function(){ location.href = o.a_proto + o.param }, 100);
			}
			else
			{
				alert('이 기능은 모바일에서만 사용할 수 있습니다.');
			}
			break;
	}
}

function kakaolink_send(text, url, img, img_w, img_h) {

	if ( ! img) img = '';
	if ( ! img_w) img_w = 300;
	if ( ! img_h) img_h = 200;

	// 카카오톡 링크 버튼을 생성합니다. 처음 한번만 호출하면 됩니다.
	if (img) {
		Kakao.Link.sendTalkLink({
		 label: String(text),
		 image: {
			src: img,
			width: img_w,
			height: img_h
		 },
		 webButton: {
			text: String('자세히 보기'), //카톡 링크시 타이틀
			url : url // 앱 설정의 웹 플랫폼에 등록한 도메인의 URL이어야 합니다.
		 }
		});
	} else {
		Kakao.Link.sendTalkLink({
		 label: String(text),
		 webButton: {
			text: String('자세히 보기'), //카톡 링크시 타이틀
			url : url // 앱 설정의 웹 플랫폼에 등록한 도메인의 URL이어야 합니다.
		 }
		});
	}
}
