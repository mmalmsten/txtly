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

function locationReady() {
    $("#loading").hide(3000);
    $("#doneloading").show(3000);
}

window.onload = getLocation;


// Suggest search result or location results
function findSuggestions(str, url) {
    if (str.length == 0) { 
        document.getElementById(url + "result").innerHTML = "";
        return;
    } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                document.getElementById(url + "result").innerHTML = xmlhttp.responseText;
            }
        }
        var lat = document.getElementById("lat").value;
        var lng = document.getElementById("lng").value;
        xmlhttp.open("GET", "functions/" + url + ".php?searchFor=" + str + "&lat=" + lat + "&lng=" + lng, true);
        xmlhttp.send();
    }
}