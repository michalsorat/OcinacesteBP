// In the following example, markers appear when the user clicks on the map.
// Each marker is labeled with a single alphabetical character.
var labels = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
var labelIndex = 0;

function initialize() {
    var trnava = { lat: 48.3767994, lng: 17.5835082 };
    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 14,
        center: trnava
    });

    // This event listener calls addMarker() when the map is clicked.
    google.maps.event.addListener(map, 'click', function(event) {
        addMarker(event.latLng, map);
    });

    // Add a marker at the center of the map.
    addMarker(trnava, map);
}

// Adds a marker to the map.
function addMarker(location, map) {
    // Add the marker at the clicked location, and add the next-available label
    // from the array of alphabetical characters.
    var marker = new google.maps.Marker({
        position: location,
        label: labels[labelIndex++ % labels.length],
        map: map
    });
}

google.maps.event.addDomListener(window, 'load', initialize);

$("document").ready(function() {
    $(".main-table tr").on("click", function() {
        console.log($(this).attr("data-number"));
        $('.modal').modal('show')
    });

});

