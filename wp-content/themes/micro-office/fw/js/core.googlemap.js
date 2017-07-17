"use strict";
function micro_office_googlemap_init(dom_obj, coords) {
	"use strict";
	if (typeof MICRO_OFFICE_STORAGE['googlemap_init_obj'] == 'undefined') micro_office_googlemap_init_styles();
	MICRO_OFFICE_STORAGE['googlemap_init_obj'].geocoder = '';
	try {
		var id = dom_obj.id;
		MICRO_OFFICE_STORAGE['googlemap_init_obj'][id] = {
			dom: dom_obj,
			markers: coords.markers,
			geocoder_request: false,
			opt: {
				zoom: coords.zoom,
				center: null,
				scrollwheel: false,
				scaleControl: false,
				disableDefaultUI: false,
				panControl: true,
				zoomControl: true, //zoom
				mapTypeControl: false,
				streetViewControl: false,
				overviewMapControl: false,
				styles: MICRO_OFFICE_STORAGE['googlemap_styles'][coords.style ? coords.style : 'default'],
				mapTypeId: google.maps.MapTypeId.ROADMAP
			}
		};
		
		micro_office_googlemap_create(id);

	} catch (e) {
		
		dcl(MICRO_OFFICE_STORAGE['strings']['googlemap_not_avail']);

	};
}

function micro_office_googlemap_create(id) {
	"use strict";

	// Create map
	MICRO_OFFICE_STORAGE['googlemap_init_obj'][id].map = new google.maps.Map(MICRO_OFFICE_STORAGE['googlemap_init_obj'][id].dom, MICRO_OFFICE_STORAGE['googlemap_init_obj'][id].opt);

	// Add markers
	for (var i in MICRO_OFFICE_STORAGE['googlemap_init_obj'][id].markers)
		MICRO_OFFICE_STORAGE['googlemap_init_obj'][id].markers[i].inited = false;
	micro_office_googlemap_add_markers(id);
	
	// Add resize listener
	jQuery(window).resize(function() {
		if (MICRO_OFFICE_STORAGE['googlemap_init_obj'][id].map)
			MICRO_OFFICE_STORAGE['googlemap_init_obj'][id].map.setCenter(MICRO_OFFICE_STORAGE['googlemap_init_obj'][id].opt.center);
	});
}

function micro_office_googlemap_add_markers(id) {
	"use strict";
	for (var i in MICRO_OFFICE_STORAGE['googlemap_init_obj'][id].markers) {
		
		if (MICRO_OFFICE_STORAGE['googlemap_init_obj'][id].markers[i].inited) continue;
		
		if (MICRO_OFFICE_STORAGE['googlemap_init_obj'][id].markers[i].latlng == '') {
			
			if (MICRO_OFFICE_STORAGE['googlemap_init_obj'][id].geocoder_request!==false) continue;
			
			if (MICRO_OFFICE_STORAGE['googlemap_init_obj'].geocoder == '') MICRO_OFFICE_STORAGE['googlemap_init_obj'].geocoder = new google.maps.Geocoder();
			MICRO_OFFICE_STORAGE['googlemap_init_obj'][id].geocoder_request = i;
			MICRO_OFFICE_STORAGE['googlemap_init_obj'].geocoder.geocode({address: MICRO_OFFICE_STORAGE['googlemap_init_obj'][id].markers[i].address}, function(results, status) {
				"use strict";
				if (status == google.maps.GeocoderStatus.OK) {
					var idx = MICRO_OFFICE_STORAGE['googlemap_init_obj'][id].geocoder_request;
					if (results[0].geometry.location.lat && results[0].geometry.location.lng) {
						MICRO_OFFICE_STORAGE['googlemap_init_obj'][id].markers[idx].latlng = '' + results[0].geometry.location.lat() + ',' + results[0].geometry.location.lng();
					} else {
						MICRO_OFFICE_STORAGE['googlemap_init_obj'][id].markers[idx].latlng = results[0].geometry.location.toString().replace(/\(\)/g, '');
					}
					MICRO_OFFICE_STORAGE['googlemap_init_obj'][id].geocoder_request = false;
					setTimeout(function() { 
						micro_office_googlemap_add_markers(id); 
						}, 200);
				} else
					dcl(MICRO_OFFICE_STORAGE['strings']['geocode_error'] + ' ' + status);
			});
		
		} else {
			
			// Prepare marker object
			var latlngStr = MICRO_OFFICE_STORAGE['googlemap_init_obj'][id].markers[i].latlng.split(',');
			var markerInit = {
				map: MICRO_OFFICE_STORAGE['googlemap_init_obj'][id].map,
				position: new google.maps.LatLng(latlngStr[0], latlngStr[1]),
				clickable: MICRO_OFFICE_STORAGE['googlemap_init_obj'][id].markers[i].description!=''
			};
			if (MICRO_OFFICE_STORAGE['googlemap_init_obj'][id].markers[i].point) markerInit.icon = MICRO_OFFICE_STORAGE['googlemap_init_obj'][id].markers[i].point;
			if (MICRO_OFFICE_STORAGE['googlemap_init_obj'][id].markers[i].title) markerInit.title = MICRO_OFFICE_STORAGE['googlemap_init_obj'][id].markers[i].title;
			MICRO_OFFICE_STORAGE['googlemap_init_obj'][id].markers[i].marker = new google.maps.Marker(markerInit);
			
			// Set Map center
			if (MICRO_OFFICE_STORAGE['googlemap_init_obj'][id].opt.center == null) {
				MICRO_OFFICE_STORAGE['googlemap_init_obj'][id].opt.center = markerInit.position;
				MICRO_OFFICE_STORAGE['googlemap_init_obj'][id].map.setCenter(MICRO_OFFICE_STORAGE['googlemap_init_obj'][id].opt.center);				
			}
			
			// Add description window
			if (MICRO_OFFICE_STORAGE['googlemap_init_obj'][id].markers[i].description!='') {
				MICRO_OFFICE_STORAGE['googlemap_init_obj'][id].markers[i].infowindow = new google.maps.InfoWindow({
					content: MICRO_OFFICE_STORAGE['googlemap_init_obj'][id].markers[i].description
				});
				google.maps.event.addListener(MICRO_OFFICE_STORAGE['googlemap_init_obj'][id].markers[i].marker, "click", function(e) {
					var latlng = e.latLng.toString().replace("(", '').replace(")", "").replace(" ", "");
					for (var i in MICRO_OFFICE_STORAGE['googlemap_init_obj'][id].markers) {
						if (latlng == MICRO_OFFICE_STORAGE['googlemap_init_obj'][id].markers[i].latlng) {
							MICRO_OFFICE_STORAGE['googlemap_init_obj'][id].markers[i].infowindow.open(
								MICRO_OFFICE_STORAGE['googlemap_init_obj'][id].map,
								MICRO_OFFICE_STORAGE['googlemap_init_obj'][id].markers[i].marker
							);
							break;
						}
					}
				});
			}
			
			MICRO_OFFICE_STORAGE['googlemap_init_obj'][id].markers[i].inited = true;
		}
	}
}

function micro_office_googlemap_refresh() {
	"use strict";
	for (id in MICRO_OFFICE_STORAGE['googlemap_init_obj']) {
		micro_office_googlemap_create(id);
	}
}

function micro_office_googlemap_init_styles() {
	// Init Google map
	"use strict";
	MICRO_OFFICE_STORAGE['googlemap_init_obj'] = {};
	MICRO_OFFICE_STORAGE['googlemap_styles'] = {
		'default': []
	};
	if (window.micro_office_theme_googlemap_styles!==undefined)
		MICRO_OFFICE_STORAGE['googlemap_styles'] = micro_office_theme_googlemap_styles(MICRO_OFFICE_STORAGE['googlemap_styles']);
}