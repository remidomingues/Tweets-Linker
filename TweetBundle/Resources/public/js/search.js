/**
 *Gets a json list of the results of the search
**/

function requestGet(theUrl) {
        var xmlHttp = null;
        xmlHttp = new XMLHttpRequest();

        xmlHttp.onreadystatechange = function() {
        if (xmlHttp.readyState == 4 && (xmlHttp.status == 200 || xmlHttp.status == 0)) {
        	displaySearchResponse(xmlHttp.responseText);
            	hide('loader');
            	$('#search_icon').show(300);
        } else if (xmlHttp.readyState < 4) {
        	showAsInline("loader");
            	hide("search_icon");
        }   
	};  

	console.log(theUrl);
        xmlHttp.open( "GET", theUrl, true );
        xmlHttp.send( null );
}

/**
 * Gets the position od a givien research term
**/

function getPositionOf(){
        var value = $('.input_location').val();
        requestGet('http://cardemo-raw.no-ip.org:9999/index.php/search?format=json&limit=300&q=' + value);
}

/**
 * Inits the SEARCH_LAYER
**/
function initSearch() {
        if(SEARCH_LAYER == null){
                var markers = new OpenLayers.Layer.Vector("SearchMarkers");
                MAP.addLayer(markers);
                SEARCH_LAYER = markers;
                SEARCH_LAYER.events.on({
                         "featureselected": function(e) {
                                addPopupSearch(e);
                     },
                        "featureunselected": function(e) {
                                e.feature.popup.destroy();
                        }
                 });
        }

        clearSearch();
}

/**
 * Clears the feature of the SEARCH_LAYER
**/
function clearSearch() {
        if(SEARCH_LAYER != null)
                SEARCH_LAYER.removeAllFeatures();
}

/**
 * Displays the features based on the response of the web service
**/
function displaySearchResponse(response){
        var responseJSON = JSON.parse(response);
        var pathname = window.location.pathname;
        clearSearch();

        var style = {
        'pointRadius': 5,
                'fillColor': '#0000f0',
                'fillOpacity': 0.5,
                'graphicHeight': MARKER_SIZE.h,
                'graphicWidth': MARKER_SIZE.w,
                'cursor': "pointer"
        };

        console.log("Search : "+responseJSON.length+" Results found in the world");
	$('#search_infos').text(responseJSON.length + " results found.")
        var titleResponse = "";
        for(var i in responseJSON){
                var element = responseJSON[i];
                addMarker(element.osm_id,new OpenLayers.LonLat(element.lon,element.lat), SEARCH_LAYER, style, responseJSON[i]);
                titleResponse += responseJSON[i].display_name+" ("+responseJSON[i].lat+","+responseJSON[i].lon+") \n";
        }
}

/**
 * Adds a feature om the map
**/
function addMarker(featureId,lonLatHumanPosition,layer, style,data){
        var coordOnMap = getMapPosition(lonLatHumanPosition);
        var newFeature = new OpenLayers.Feature.Vector(new OpenLayers.Geometry.Point(coordOnMap.lon,coordOnMap.lat),featureId,style);
        newFeature.data = data;
        layer.addFeatures(newFeature);

        return newFeature;
}

/**
 * Displays a popup when a feature is clicked
**/
function addPopupSearch(e) {
        var feature = e.feature;
        var data = feature.data;

        var data_choice = ["place_id", "osm_type", "osm_id","lat", "lon", "display_name","class","type"];

        var content= "<p class='content_search_popup'>";
        for(var element in data_choice){
                content += data_choice[element]+" : "+data[data_choice[element]]+"<br />";
        }
        content += "</p>";

        // And create a popup with all that
        var position = new OpenLayers.LonLat(feature.geometry.x,feature.geometry.y);
        var icon = new OpenLayers.Icon(feature.style.externalGraphic, MARKER_SIZE);

    var popup = new OpenLayers.Popup.FramedCloud(
                        feature.id,
                        position,
                        POPUP_SIZE,
                        content,
                        icon,
                        true
        );
        $(".input_location").val(data['display_name']);
        popup.feature = feature;
        feature.popup = popup;
        MAP.addPopup(popup);
        popup.panIntoView();
}
