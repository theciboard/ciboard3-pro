if (typeof(DEPOSIT_JS) === 'undefined') {

	var DEPOSIT_JS = true;

	function open_point_to_deposit() {
		window.open(cb_url + '/deposit/point_to_deposit', 'win_charge', 'left=100,top=100,width=600,height=600,scrollbars=1');
		return false;
	}
	function open_deposit_to_point() {
		window.open(cb_url + '/deposit/deposit_to_point', 'win_charge', 'left=100,top=100,width=600,height=600,scrollbars=1');
		return false;
	}
}
