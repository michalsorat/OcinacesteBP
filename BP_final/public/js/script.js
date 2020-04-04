
/**
 * Global marker object that holds all markers.
 * @type {Object.<string, google.maps.LatLng>}
 */
var markers = {};
var markerString;
const test = "Poloha";

function initialize() {
    var trnava = { lat: 48.3767994, lng: 17.5835082 };

    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 14,
        center: trnava
    });

    // This event listener calls addMarker() when the map is clicked.
    google.maps.event.addListener(map, 'click', function(event) {

        var lat = event.latLng.lat();
        var lng = event.latLng.lng();

        markerString = getMarkerString(lat, lng);
        window.alert(markerString);
        addMarker(event.latLng, map);

        document.getElementById('poloha').value = markerString;
    });
}

google.maps.event.addDomListener(window, 'load', initialize);


/**
 * Concatenates given lat and lng with an comma and returns it.
 * @param {!number} lat Latitude.
 * @param {!number} lng Longitude.
 * @return {string} Concatenated marker id.
 */
var getMarkerString= function(lat, lng) {
    return lat + ',' + lng;
};

/**
 * Creates an instance of google.maps.LatLng by given lat and lng values and returns it.
 * This function can be useful for getting new coordinates quickly.
 * @param {!number} lat Latitude.
 * @param {!number} lng Longitude.
 * @return {google.maps.LatLng} An instance of google.maps.LatLng object
 */
var getLatLng = function(lat, lng) {
    return new google.maps.LatLng(lat, lng);
};


// Adds a marker to the map.
function addMarker(location, map) {
    // Add the marker at the clicked location, and add the next-available label
    // from the array of alphabetical characters.
    var marker = new google.maps.Marker({
        position: location,
        animation: google.maps.Animation.DROP,
        map: map,
    });

}




$("document").ready(function() {
    /*$(".main-table tr").on("click", function() {
        $('#update-modal').modal('show')
    });*/

});
