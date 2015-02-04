// Find User Location
function getLocation() {
	var lat = document.getElementById("lat");
	var lng = document.getElementById("lng");
    if (navigator.geolocation) {
        navigator.geolocation.watchPosition(showPosition, showError);
    } else { 
        lat.value = "Geolocation is not supported by this browser.";
        lng.value = "Geolocation is not supported by this browser.";
    }
}
    
function showPosition(position) {
    lat.value = position.coords.latitude;	
    lng.value = position.coords.longitude;	
    locationReady();
}

function showError(error) {
  switch(error.code) {
    case error.PERMISSION_DENIED:
      document.getElementById("locationinfo").value="User denied the request for Geolocation."
      break;
    case error.POSITION_UNAVAILABLE:
      document.getElementById("locationinfo").value="Location information is unavailable."
      break;
    case error.TIMEOUT:
      document.getElementById("locationinfo").value="The request to get user location timed out."
      break;
    case error.UNKNOWN_ERROR:
      document.getElementById("locationinfo").value="An unknown error occurred."
      break;
    }
    lat.value = "59.329444";   
    lng.value = "18.068611";  
    locationReady();
}

/*    var myCenter=new google.maps.LatLng(position.coords.latitude + ", " + position.coords.longitude);

    var mapProp = {
    center:myCenter,
    zoom:12,
    scrollwheel: false,
    navigationControl: false,
    mapTypeControl: false,
    scaleControl: false,
    draggable: false,
    mapTypeId:google.maps.MapTypeId.ROADMAP
    };

    var map=new google.maps.Map(document.getElementById("mymap"),mapProp);

    var marker=new google.maps.Marker({
      position:myCenter,
    });

    marker.setMap(map); */

function locationReady() {
    $("#loading").hide(3000);
    $("#doneloading").show(3000);
}

window.onload = getLocation;