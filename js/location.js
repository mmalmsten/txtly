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
    default:
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

function findSuggestions(str, url){
    var lat = document.getElementById("lat").value;
    var lng = document.getElementById("lng").value;
    var result = "#" + url + "result";
    console.log(lat);
    console.log(lng);
    console.log(url);
    console.log(str);

    if (str.length == 0) { 
      $(result).innerHTML("");
    }
    $.ajaxSetup({
        beforeSend: function() {
            $('.loadScript').fadeIn(); 
        },
        complete: function() {
            $('.loadScript').fadeOut(); 
        }
    });
    $.ajax({
        url: 'functions/' + url + '.php',
        type: 'post',
        data: {
            "searchFor": str,
            "lat": lat,
            "lng": lng,
        },
        success: function(response) { 
            console.log(result);
            $(result).html(response);
        }
    });
}