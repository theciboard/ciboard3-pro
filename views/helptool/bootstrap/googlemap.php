<?php
//설정값
$geo = $this->input->get('geo');
$marker = $this->input->get('marker');
list($lat, $lng, $zoom) = explode(',', $geo);
$lat = $lat ? $lat : '37.566535';
$lng = $lng ? $lng : '126.977969';
$zoom = $zoom ? $zoom : 14;
?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<title>구글지도보기</title>
<style type="text/css">
body { margin:0; padding:0; font:normal 12px dotum; -webkit-text-size-adjust:100%; }
.infowindow { min-width:180px; max-width:280px; line-height:22px; }
</style>
<script src="http://maps.google.com/maps/api/js?v=3.3&sensor=false&language=ko"></script>
<script type="text/javascript">
// 구글맵
var map;
var marker;
var infowindow;

function addLoadEvent(func) {
	var oldonload = window.onload;
	if (typeof window.onload !== 'function') {
		window.onload = func;
	} else {
		window.onload = function() {
			if (oldonload) {
				oldonload();
			}
			func();
		}
	}
}

function initialize() {
	var myLatlng = new google.maps.LatLng("<?php echo $lat; ?>", "<?php echo $lng; ?>");
	var myOptions = {
		zoom: <?php echo $zoom; ?>,
		scaleControl: true,

		navigationControl: true,
		navigationControlOptions: {
				style: google.maps.NavigationControlStyle.SMALL,
				position: google.maps.ControlPosition.TOP_RIGHT
		},

		center: myLatlng,
		mapTypeId: google.maps.MapTypeId.ROADMAP
	}

	map = new google.maps.Map(document.getElementById('map_canvas'), myOptions);

<?php if ($marker) { ?>
		infowindow = new google.maps.InfoWindow();
<?php } ?>

	google.maps.event.addListener(map, 'zoom_changed', function() {
		zoomLevel = map.getZoom();
		if (zoomLevel > 19) {
		map.setZoom(19);
		}
		if (zoomLevel < 1) {
		map.setZoom(1);
		}
	});

<?php if ($marker) { ?>
	marker = new google.maps.Marker({
		position: myLatlng,
		map: map
	});

	infowindow.setContent("<div class='infowindow'><?php echo $marker; ?></div>");
	infowindow.open(map, marker);
<?php } ?>
}
</script>
</head>
<body>
	<div id="map_canvas" class="google_map" style="width:100%; height:480px;"></div>
	<script type="text/javascript"> addLoadEvent(initialize); </script>
</body>
</html>
