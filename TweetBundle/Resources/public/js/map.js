var MAP = window.map;
var POSITION_DUBLIN = new OpenLayers.LonLat(-6.262791111584865,53.34958495160993);
var ZOOM_INIT = 10;
var ZONE_LAYER = null;
var MARKER_SIZE = new OpenLayers.Size(21, 25);
var POPUP_SIZE = new OpenLayers.Size(200, 200);
var SEARCH_LAYER = null;
var DRAW_CONTROL = null;

/**
 * Initialisation of the OpenLayers map
 * @param div_name Div in which we want to display the map
 */
function map_init(div_name) {
	
	OpenLayers.ImgPath = "img/";
	 
	/*---------------- Initialise the map -------------------*/
	// Create a map Object
	var map_created = new OpenLayers.Map(div_name);
	MAP = map_created;
	window.map = map_created;
	
	var mapnik = new OpenLayers.Layer.OSM("MapQuest Open",
            "http://otile1.mqcdn.com/tiles/1.0.0/osm/${z}/${x}/${y}.png",
            {numZoomLevels:19, isBaseLayer:true, transitionEffect:'resize'});
	
	// Add the layer "Map" to the map
	MAP.addLayer(mapnik);
	
	// We store the map element as static variable in the javascript
	console.log("Map : Initialised");
	console.log(map_created);
	
	// Define the initial Zoom and position
	map_created.setCenter(getMapPosition(POSITION_DUBLIN),ZOOM_INIT);
}


/**
 * Function to init the controls
 */
function initControls(){
	//Init controls to dram circle on map
        var drawControl = new OpenLayers.Control.DrawFeature(ZONE_LAYER,
             OpenLayers.Handler.RegularPolygon, {
                 handlerOptions: {
                     sides: 40,
                     irregular: false
                 },
		featureAdded:stopDrawing
             });

	DRAW_CONTROL = drawControl;
	MAP.addControl(drawControl);

        // Add select control on features
        var selectControlClick = new OpenLayers.Control.SelectFeature(
                SEARCH_LAYER,
        {
                        clickout:true,
            highlightOnly:false,
            renderIntent: "default"
        }
    );

        MAP.addControl(selectControlClick);
        selectControlClick.activate();
}

function init() {
 	//Add layer for drazing zones 
	var zoneLayer = new OpenLayers.Layer.Vector("ZoneLayer", null);
	ZONE_LAYER = zoneLayer;
	MAP.addLayer(ZONE_LAYER);

	//Add enter control when looking for a place in the search field
	$(".input_location").keyup(function (e) {
    		if (e.keyCode == 13) {
			requestGet('http://cardemo-raw.no-ip.org:9999/index.php/search?format=json&limit=300&q='+$(".input_location").val());
		}
	});

	//Init Datepickers and Timepickers
	$("div[id^='datepicker_']").datepicker();	
	$("input[id^='timepicker_']").timepicker();

	//Initialize controls and search layer
        initSearch();
	initControls();
        $("#searchForm").submit(function(){

      //get the url for the form
      var url= $("#searchForm").attr('action');
  	 
      //start send the post request
       $.post(url,
	 $('#searchForm').serialize()
       ,function(data){
           //the response is in the data variable
            if(data.responseCode==200 ){    
		var selectedTweetsHTML = "";
		$(".tweet_div").each(function() {
			if ($(this).find(">:first-child").attr('linked') == "true" || $(this).find(">:first-child").attr('linked') == "false") {
				selectedTweetsHTML += $(this)[0].outerHTML + "<br/>";
			}
		});

		var content = data.responseHTML + selectedTweetsHTML;
                $('#tweets_container').html(content);
            }   
           else if(data.responseCode==400){//bad request
                $('#tweets_container').html("fail....");
           }   
           else{
              //if we got to this point we know that the controller
              //did not return a json_encoded array. We can assume that           
              //an unexpected PHP error occured

              //if you want to print the error:
              $('#tweets_container').html(data);
           }   
       });
       return false;
        }); 

}

/**
 * Function to clear the markers of the map
 */
function clearMap() {
	console.log("Map : Cleared");
}

/**
 * This function returns a position in the good format to be displayed on the map
 * @param coords coordinates in the lon lat system
 * @returns An OpenLayers well formed position for display
 */
function getMapPosition(coords) {
	var fromProjection = new OpenLayers.Projection("EPSG:4326");   // Transform from WGS 1984
	var toProjection   = new OpenLayers.Projection("EPSG:900913"); // to Spherical Mercator Projection
	var position       = coords.transform( fromProjection, toProjection);
    
	return position;
}

/**
 * This function returns a position in the good format for a human being
 * @param coords coordinates in the map
 * @returns An OpenLayers well formed position for you
 */
function getHumanPosition(coords) {
	var fromProjection = new OpenLayers.Projection("EPSG:900913"); // to Spherical Mercator Projection
	var toProjection = new OpenLayers.Projection("EPSG:4326");   // Transform from WGS 1984
	var position = coords.transform( fromProjection, toProjection);
    
	return position;
}

/**
 * Function to hide a div
 * @param id Id of the div to hide
 */
function hide(element_id){
        if ($('#'+element_id)!=null) {
                $('#'+element_id).css('display', 'none');
        }
}

/**
 * Function to show an element as an inline
 * @param id Id of the div to show
 */
function showAsInline(element_id){
        if ($('#'+element_id)!=null) {
                $('#'+element_id).css('display', 'inline');
        }
}

/**
 * Function to calculate a distance in meters between two coordinates
 * @param latitude1 Latitude of point 1
 * @param longitude1 Longitude of point 1
 * @param latitude2 Latitude of point 2
 * @param longitude2 Longitude of point 2
 * @returns {Number} Distance in meters
 */
function coordsToMeters(latitude1,longitude1,latitude2,longitude2){
        var R = 6371; // km
        var dLat = (latitude2-latitude1)* Math.PI / 180;
        var dLon = (longitude2-longitude1)* Math.PI / 180;
        var lat1 = latitude1* Math.PI / 180;
        var lat2 = latitude2* Math.PI / 180;

        var a = Math.sin(dLat/2) * Math.sin(dLat/2) +
                Math.sin(dLon/2) * Math.sin(dLon/2) * Math.cos(lat1) * Math.cos(lat2);
        var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
        return R * c *1000;
}


//Initializing the map 
map_init('map_container');
init();
