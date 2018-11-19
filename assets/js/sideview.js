if (typeof(SIDEVIEW_JS) === 'undefined') // 한번만 실행
{
	if (typeof is_member === 'undefined') {
		alert('is_member 변수가 선언되지 않았습니다.');
	}

	var SIDEVIEW_JS = true;

	// 아래의 소스코드는 daum.net 카페의 자바스크립트를 참고하였습니다.
	// 회원이름 클릭시 회원정보등을 보여주는 레이어
	function insertHead(name, text, evt) {
		var idx = this.heads.length;
		var row = new SideViewRow(-idx, name, text, evt);
		this.heads[idx] = row;
		return row;
	}

	function insertTail(name, evt) {
		var idx = this.tails.length;
		var row = new SideViewRow(idx, name, evt);
		this.tails[idx] = row;
		return row;
	}

	function SideViewRow(idx, name, onclickEvent) {
		this.idx = idx;
		this.name = name;
		this.onclickEvent = onclickEvent;
		this.renderRow = renderRow;

		this.isVisible = true;
		this.isDim = false;
	}

	function renderRow() {
		if ( ! this.isVisible)
			return '';

		var str = '<tr><td id="sideViewRow_' + this.name + '">' + this.onclickEvent + '</td></tr>';
		return str;
	}

	function getSideView(curObj, userid) {
		clickAreaCheck = true;
		$.ajax({
			url : cb_url + '/profile/sideview/' + userid,
			type : 'get',
			dataType : 'json',
			success : function(data) {
				if (data.error) {
					alert(data.error);
					return false;
				} else if (data.success) {
					showSideView(curObj, userid, data.name, data.homepage, data.note, data.email, data.profile, data.following, data.memid);
				}
			}
		});
	}

	function showSideView(curObj, userid, name, homepage, note, email, profile, following, memid) {
		var sideView = new SideView('nameContextMenu', curObj, userid, name, homepage, note, email, profile, following, memid);
		sideView.showLayer();
	}

	function SideView(targetObj, curObj, userid, name, homepage, note, email, profile, following, memid) {
		this.targetObj = targetObj;
		this.curObj = curObj;
		this.userid = userid;
		name = name.replace(/…/g,"");
		this.name = name;
		this.homepage = homepage;
		this.note = note;
		this.email = email;
		this.profile = profile;
		this.following = following;
		this.showLayer = showLayer;
		this.makeNameContextMenus = makeNameContextMenus;
		this.heads = new Array();
		this.insertHead = insertHead;
		this.tails = new Array();
		this.insertTail = insertTail;
		this.getRow = getRow;
		this.hideRow = hideRow;
		this.dimRow = dimRow;

		// 회원이라면 // (비회원의 경우 검색 없음)
		if (is_member) {
			if (userid) {
			// 쪽지보내기
			if (note == '1')
				this.insertTail('note', '<a href="javascript:;" onclick="note_write(\'' + userid + '\'); return false;">쪽지보내기</a>');
			else if (note == '3')
				this.insertTail('note', '<a href="javascript:;" onclick="alert(\'회원님이 쪽지수신설정을 해제하셨습니다.. 먼저 회원님의 쪽지수신여부를 체크하신 후에 이용해주세요\'); return false;">쪽지보내기</a>');
			else if (note == '2')
				this.insertTail('note', '<a href="javascript:;" onclick="alert(\'상대방이 쪽지수신을 원하지 않으십니다\'); return false;">쪽지보내기</a>');
			// 친구등록
			if (following == '1')
				this.insertTail('follow', '<a href="javascript:;" onclick="delete_follow(\'' + userid + '\'); return false;">친구해제</a>');
			else
				this.insertTail('follow', '<a href="javascript:;" onclick="add_follow(\'' + userid + '\'); return false;">친구등록</a>');
			// 메일보내기
			if (email == '1')
				this.insertTail('email', '<a href="javascript:;" onclick="email_write(\'' + userid + '\'); return false;">메일보내기</a>');
			else if (email == '3')
				this.insertTail('email', '<a href="javascript:;" onclick="alert(\'회원님이 이메일수신설정을 해제하셨습니다.. 먼저 회원님의 이메일수신여부를 체크하신 후에 이용해주세요\'); return false;">메일보내기</a>');
			else if (email == '2')
				this.insertTail('email', '<a href="javascript:;" onclick="alert(\'상대방이 이메일수신을 원하지 않으십니다\'); return false;">메일보내기</a>');
			// 홈페이지
			if (homepage == '1')
				this.insertTail('homepage', '<a href="' + homepage + '" target="_blank">홈페이지</a>');
			// 자기소개
			if (profile == '1')
				this.insertTail('profile', '<a href="javascript:;" onclick="open_profile(\'' + userid + '\'); return false;">프로필</a>');
			else if (profile == '3')
				this.insertTail('profile', '<a href="javascript:;" onclick="alert(\'회원님이 프로필을 공개하지 않으셨습니다. 먼저 회원님의 프로필을 공개하신 후 이용해주세요\'); return false;">프로필</a>');
			else if (profile == '2')
				this.insertTail('profile', '<a href="javascript:;" onclick="alert(\'상대방이 프로필을 공개하지 않으셨습니다\'); return false;">프로필</a>');
			}
		}

		// 게시판테이블 아이디가 넘어왔을 경우
		if (cb_board) {
			if (userid) // 회원일 경우 아이디로 검색
				this.insertTail('userid', '<a href="' + cb_board_url + '/?sfield=post_userid&skeyword=' + userid + '">아이디로 검색</a>');
			else // 비회원일 경우 이름으로 검색
				this.insertTail('name', '<a href="' + cb_board_url + '/?sfield=post_nickname&skeyword=' + name + '">이름으로 검색</a>');
		}

		// 최고관리자일 경우
		if (is_admin == 'super') {
			// 회원정보변경
			if (userid)
				this.insertTail('modify', '<a href="' + cb_admin_url + '/member/members/write/' + memid + '" target="_blank">회원정보변경</a>');
			// 포인트내역
			if (userid)
				this.insertTail('point', '<a href="' + cb_admin_url + '/member/points?sfield=point.mem_id&skeyword=' + memid + '" target="_blank">포인트내역</a>');
			if (userid)
				this.insertTail('new', '<a href="' + cb_admin_url + '/board/post?sfield=mem_id&skeyword=' + memid + '" target="_blank">전체게시물</a>');
			if (userid)
				this.insertTail('new', '<a href="' + cb_admin_url + '/board/comment?sfield=post.mem_id&skeyword=' + memid + '" target="_blank">전체댓글</a>');
		}
	}

	function showLayer() {
		var oSideViewLayer = document.getElementById(this.targetObj);
		var oBody = document.body;

		if (oSideViewLayer === null) {
			oSideViewLayer = document.createElement('DIV');
			oSideViewLayer.id = this.targetObj;
			oSideViewLayer.style.position = 'absolute';
			oBody.appendChild(oSideViewLayer);
		}
		oSideViewLayer.innerHTML = this.makeNameContextMenus();

		if (getAbsoluteTop(this.curObj) + this.curObj.offsetHeight + oSideViewLayer.scrollHeight + 5 > oBody.scrollHeight)
			oSideViewLayer.style.top = (getAbsoluteTop(this.curObj) - oSideViewLayer.scrollHeight) + 'px';
		else
			oSideViewLayer.style.top = (getAbsoluteTop(this.curObj) + this.curObj.offsetHeight) + 'px';

		oSideViewLayer.style.left = (getAbsoluteLeft(this.curObj) - this.curObj.offsetWidth + 14) + 'px';

		divDisplay(this.targetObj, 'block');

		selectBoxHidden(this.targetObj);
	}

	function getAbsoluteTop(oNode) {
		var oCurrentNode=oNode;
		var iTop = 0;
		while(oCurrentNode.tagName != 'BODY') {
			iTop+=oCurrentNode.offsetTop - oCurrentNode.scrollTop;
			oCurrentNode=oCurrentNode.offsetParent;
		}
		return iTop;
	}

	function getAbsoluteLeft(oNode) {
		var oCurrentNode=oNode;
		var iLeft = 0;
		iLeft+=oCurrentNode.offsetWidth;
		while(oCurrentNode.tagName != 'BODY') {
			iLeft+=oCurrentNode.offsetLeft;
			oCurrentNode=oCurrentNode.offsetParent;
		}
		return iLeft;
	}

	function makeNameContextMenus() {
		var str = '<table class="mbLayer">';

		var i = 0;
		for (i=this.heads.length - 1; i >= 0; i--)
			str += this.heads[i].renderRow();

		var j = 0;
		for (j = 0; j < this.tails.length; j++)
			str += this.tails[j].renderRow();

		str += '</table>';
		return str;
	}

	function getRow(name) {
		var i = 0;
		var row = null;
		for (i = 0; i<this.heads.length; ++i) {
			row = this.heads[i];
			if (row.name == name) return row;
		}

		for (i = 0; i<this.tails.length; ++i) {
			row = this.tails[i];
			if (row.name == name) return row;
		}
		return row;
	}

	function hideRow(name) {
		var row = this.getRow(name);
		if (row !== null)
			row.isVisible = false;
	}

	function dimRow(name) {
		var row = this.getRow(name);
		if (row !== null)
			row.isDim = true;
	}
	// Internet Explorer에서 셀렉트박스와 레이어가 겹칠시 레이어가 셀렉트 박스 뒤로 숨는 현상을 해결하는 함수
	// 레이어가 셀렉트 박스를 침범하면 셀렉트 박스를 hidden 시킴
	// <div id=LayerID style="display:none; position:absolute;" onpropertychange="selectBoxHidden('LayerID')">
	function selectBoxHidden(layer_id) {
		//var ly = eval(layer_id);
		var ly = document.getElementById(layer_id);

		// 레이어 좌표
		var ly_left = ly.offsetLeft;
		var ly_top	= ly.offsetTop;
		var ly_right = ly.offsetLeft + ly.offsetWidth;
		var ly_bottom = ly.offsetTop + ly.offsetHeight;

		// 셀렉트박스의 좌표
		var el;

		for (i = 0; i < document.forms.length; i++) {
			for (k = 0; k < document.forms[i].length; k++) {
				el = document.forms[i].elements[k];
				if (el.type == 'select-one') {
					var el_left = el_top = 0;
					var obj = el;
					if (obj.offsetParent) {
						while (obj.offsetParent) {
							el_left += obj.offsetLeft;
							el_top += obj.offsetTop;
							obj = obj.offsetParent;
						}
					}
					el_left += el.clientLeft;
					el_top	+= el.clientTop;
					el_right = el_left + el.clientWidth;
					el_bottom = el_top + el.clientHeight;

					// 좌표를 따져 레이어가 셀렉트 박스를 침범했으면 셀렉트 박스를 hidden 시킴
					if ( (el_left >= ly_left && el_top >= ly_top && el_left <= ly_right && el_top <= ly_bottom) ||
						 (el_right >= ly_left && el_right <= ly_right && el_top >= ly_top && el_top <= ly_bottom) ||
						 (el_left >= ly_left && el_bottom >= ly_top && el_right <= ly_right && el_bottom <= ly_bottom) ||
						 (el_left >= ly_left && el_left <= ly_right && el_bottom >= ly_top && el_bottom <= ly_bottom) ||
						 (el_top <= ly_bottom && el_left <= ly_left && el_right >= ly_right)
						)
						el.style.visibility = 'hidden';
				}
			}
		}
	}

	// 감추어진 셀렉트 박스를 모두 보이게 함
	function selectBoxVisible() {
		for (i = 0; i<document.forms.length; i++) {
			for (k = 0; k<document.forms[i].length; k++) {
				el = document.forms[i].elements[k];
				if (el.type == 'select-one' && el.style.visibility == 'hidden')
					el.style.visibility = 'visible';
			}
		}
	}

	function divDisplay(id, act) {
		selectBoxVisible();

		document.getElementById(id).style.display = act;
	}

	function hideSideView() {
		if (document.getElementById('nameContextMenu'))
			divDisplay ('nameContextMenu', 'none');
	}

	var clickAreaCheck = false;
	document.onclick = function() {
		if ( ! clickAreaCheck) {
			hideSideView();
		} else {
			clickAreaCheck = false;
		}
	}
}
