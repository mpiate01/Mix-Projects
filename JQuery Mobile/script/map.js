$(document).on("pagecontainershow", function (e, ui) {
	var page = ui.toPage[0].id;
	if( page == 'n7' ) {
		if (navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(initialize_n7);
		} else {
		documentgetElementById("nogeolocation").innerHTML = "Geolocation is not supported by this browser.";
		}
	}
	if( page == 'nw' ) {
		if (navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(initialize_nw);
		} else {
		documentgetElementById("nogeolocation").innerHTML = "Geolocation is not supported by this browser.";
		}
	}
	if( page == 'nw3' ) {
		if (navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(initialize_nw3);
		} else {
		documentgetElementById("nogeolocation").innerHTML = "Geolocation is not supported by this browser.";
		}
	}		
}); 

function initialize_nw3(position) {

	var lat = position.coords.latitude;
	var lon = position.coords.longitude;
	var currentPosition = new google.maps.LatLng(lat, lon);
	var nw3accommodationPosition = new google.maps.LatLng(51.555522, -0.180755);

	var mapOptions = {
	zoom: 18,
	center: nw3accommodationPosition,
	mapTypeControl: true,
	mapTypeControlOptions: {
	style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
		}
	}

	var accommodationMap = new google.maps.Map(document.getElementById('map-canvas-nw3'), mapOptions);

	var currentPositionImage ='https://titan.dcs.bbk.ac.uk/~mpiate01/mad/madtma/img/currentlocation.png';
	var userPosition  = new google.maps.Marker({
	position: currentPosition,
	map: accommodationMap,
	icon: currentPositionImage,
	title: 'You are here'
	});

	var nw3accommodationMarkerImage = 'https://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=A|FF0000|000000';
	var nw3Position = new google.maps.Marker({
	position: nw3accommodationPosition,
	map: accommodationMap,
	icon: nw3accommodationMarkerImage,
	title: 'Camden - NW3'
	});

	var nw3accommodationInfo ='<div id="mappopup">'+
	'<h4>Camden - NW3</h4>'+
	'<h4>Sharing - 700 pounds</h4>'+
	'<p>Available now</p>' +
	'</div>'; 

	var nw3accommodationInfoWindow = new google.maps.InfoWindow({
	content: nw3accommodationInfo
	});

	google.maps.event.addListener(nw3Position, 'click', function() {
	nw3accommodationInfoWindow.open(accommodationMap, nw3Position);
	});
}

function initialize_nw(position) {

	var lat = position.coords.latitude;
	var lon = position.coords.longitude;
	var currentPosition = new google.maps.LatLng(lat, lon);
	var nwaccommodationPosition = new google.maps.LatLng(51.523951, -0.137436);

	var mapOptions = {
	zoom: 18,
	center: nwaccommodationPosition,
	mapTypeControl: true,
	mapTypeControlOptions: {
	style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
		}
	}

	var accommodationMap = new google.maps.Map(document.getElementById('map-canvas-nw'), mapOptions);

	var currentPositionImage ='https://titan.dcs.bbk.ac.uk/~mpiate01/mad/madtma/img/currentlocation.png';
	var userPosition  = new google.maps.Marker({
	position: currentPosition,
	map: accommodationMap,
	icon: currentPositionImage,
	title: 'You are here'
	});

	var nwaccommodationMarkerImage = 'https://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=A|FF0000|000000';
	var nwPosition = new google.maps.Marker({
	position: nwaccommodationPosition,
	map: accommodationMap,
	icon: nwaccommodationMarkerImage,
	title: 'Camden - NW'
	});

	var nwaccommodationInfo ='<div id="mappopup">'+
	'<h4>Camden - NW</h4>'+
	'<h4>Studio - 1200 pounds</h4>'+
	'<p>Available from 20th Apr 2018</p>' +
	'</div>'; 

	var nwaccommodationInfoWindow = new google.maps.InfoWindow({
	content: nwaccommodationInfo
	});

	google.maps.event.addListener(nwPosition, 'click', function() {
	nwaccommodationInfoWindow.open(accommodationMap, nwPosition);
	});
}

function initialize_n7(position) {

	var lat = position.coords.latitude;
	var lon = position.coords.longitude;
	var currentPosition = new google.maps.LatLng(lat, lon);
	var n7accommodationPosition = new google.maps.LatLng(51.548016, -0.117975);

	var mapOptions = {
	zoom: 18,
	center: n7accommodationPosition,
	mapTypeControl: true,
	mapTypeControlOptions: {
	style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
		}
	}

	var accommodationMap = new google.maps.Map(document.getElementById('map-canvas-n7'), mapOptions);

	var currentPositionImage ='https://titan.dcs.bbk.ac.uk/~mpiate01/mad/madtma/img/currentlocation.png';
	var userPosition  = new google.maps.Marker({
	position: currentPosition,
	map: accommodationMap,
	icon: currentPositionImage,
	title: 'You are here'
	});

	var n7accommodationMarkerImage = 'https://chart.apis.google.com/chart?chst=d_map_pin_letter&chld=A|FF0000|000000';
	var n7Position = new google.maps.Marker({
	position: n7accommodationPosition,
	map: accommodationMap,
	icon: n7accommodationMarkerImage,
	title: 'Camden - N7'
	});

	var n7accommodationInfo ='<div id="mappopup">'+
	'<h4>Camden - N7</h4>'+
	'<h4>House - 1500 pounds</h4>'+
	'<p>Available from 20th May 2018</p>' +
	'</div>'; 

	var n7accommodationInfoWindow = new google.maps.InfoWindow({
	content: n7accommodationInfo
	});

	google.maps.event.addListener(n7Position, 'click', function() {
	n7accommodationInfoWindow.open(accommodationMap, n7Position);
	});
}
